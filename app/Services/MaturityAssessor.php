<?php

namespace App\Services;

use App\Models\Answer;
use App\Models\Assessment;
use App\Models\AssessmentProcess;
use App\Models\Question;
use App\Support\Itil;

/**
 * Logika penilaian maturity level ITIL V3 sesuai proposal (Gambar 3.3, Rumus 2.1 - 2.3):
 *
 * 1. Penilaian dimulai dari Level 1 & 2 (disimpan sebagai level 2), setiap pertanyaan dijawab Y / P / N
 *    dengan nilai 1 / 0,5 / 0.
 * 2. Nilai level = jumlah nilai jawaban seluruh atribut pada level itu / jumlah pertanyaan x 100%
 *    (contoh Tabel 3.59: 13 / 43 = 30,23%).
 * 3. Nilai >= 85% (rating F) -> lanjut ke level berikutnya.
 *    Selain itu penilaian berhenti dan hasilnya level sebelumnya.
 * 4. Level yang tidak memiliki pertanyaan dilewati.
 * 5. Kematangan domain = jumlah level subdomain / jumlah subdomain.
 */
class MaturityAssessor
{
    public const IN_PROGRESS = 'in_progress';
    public const COMPLETED = 'completed';
    public const EMPTY = 'empty'; // subdomain belum memiliki pertanyaan sama sekali

    /**
     * Level hasil ketika penilaian berhenti di $level dengan rating $rating.
     * Level 1 & 2 yang tidak lulus (P / L / N) langsung hasil Level 0.
     */
    public static function resultLevel(int $level, string $rating): int
    {
        if ($rating === 'F') {
            return $level;
        }

        if ($level === config('itil.start_level')) {
            return 0;
        }

        return $level - 1;
    }

    /**
     * Keterangan keputusan sebuah level berdasarkan rating-nya.
     */
    public static function decisionText(int $level, string $rating, bool $hasNext = true): string
    {
        if ($rating === 'F') {
            return $hasNext ? 'Lulus, lanjut ke level berikutnya' : "Lulus, hasil Level {$level}";
        }

        return 'Berhenti, hasil Level ' . self::resultLevel($level, $rating);
    }

    /**
     * Hitung kondisi penilaian satu subdomain dalam sebuah assessment.
     *
     * @return array{
     *     status: string,
     *     active_level: ?int,
     *     result_level: ?int,
     *     levels: int[],
     *     has_next_level: bool,
     *     history: array<int, array>
     * }
     */
    public function evaluate(Assessment $assessment, string $processCode): array
    {
        $questions = Question::with('attribute')
            ->where('process_code', $processCode)
            ->get(['id', 'attribute_code']);

        $questionsByLevel = $questions->groupBy(fn ($q) => $q->attribute->level);

        $answers = Answer::where('assessment_id', $assessment->id)
            ->whereIn('question_id', $questions->pluck('id'))
            ->get(['question_id', 'score'])
            ->keyBy('question_id');

        $history = [];
        $levels = $questionsByLevel->keys()->sort()->values()->all();

        for ($level = config('itil.start_level'); $level <= config('itil.max_level'); $level++) {
            $levelQuestions = $questionsByLevel->get($level, collect());

            if ($levelQuestions->isEmpty()) {
                $history[$level] = ['status' => 'skipped'];
                continue;
            }

            // Level ini belum dijawab lengkap -> menjadi level aktif
            if ($levelQuestions->contains(fn ($q) => ! $answers->has($q->id))) {
                return $this->result(self::IN_PROGRESS, $history, $levels, activeLevel: $level);
            }

            $history[$level] = $this->score($levelQuestions, $answers);
            $history[$level]['status'] = $history[$level]['rating'] === 'F' ? 'passed' : 'stopped';

            if ($history[$level]['rating'] !== 'F') {
                return $this->result(self::COMPLETED, $history, $levels, resultLevel: self::resultLevel($level, $history[$level]['rating']));
            }
        }

        // Semua level yang memiliki pertanyaan lulus -> hasil = level tertinggi yang lulus
        $passed = collect($history)->filter(fn ($h) => $h['status'] === 'passed')->keys();

        if ($passed->isEmpty()) {
            return $this->result(self::EMPTY, $history, $levels);
        }

        return $this->result(self::COMPLETED, $history, $levels, resultLevel: $passed->max());
    }

    /**
     * Evaluasi lalu simpan hasilnya ke subdomain dan perbarui ringkasan assessment.
     */
    public function sync(AssessmentProcess $item): array
    {
        $assessment = $item->assessment;
        $result = $this->evaluate($assessment, $item->process_code);

        $hasAnswers = Answer::where('assessment_id', $assessment->id)
            ->whereHas('question', fn ($q) => $q->where('process_code', $item->process_code))
            ->exists();

        $item->update([
            'status' => match (true) {
                $result['status'] === self::COMPLETED => 'completed',
                $hasAnswers => 'in_progress',
                default => 'not_started',
            },
            'result_level' => $result['status'] === self::COMPLETED ? $result['result_level'] : null,
        ]);

        $this->refreshAssessment($assessment);

        return $result;
    }

    /**
     * Perbarui status assessment dan nilai kematangan domain (Rumus 2.3).
     */
    public function refreshAssessment(Assessment $assessment): void
    {
        $processes = $assessment->processes()->get(['status', 'result_level']);
        $completed = $processes->where('status', 'completed');

        $assessment->update([
            'maturity_index' => $completed->isNotEmpty() ? Itil::roundMaturity($completed->avg('result_level')) : null,
            'status' => match (true) {
                $processes->isNotEmpty() && $completed->count() === $processes->count() => 'completed',
                $processes->contains(fn ($p) => $p->status !== 'not_started') => 'in_progress',
                default => 'draft',
            },
        ]);
    }

    /**
     * Nilai satu level: total keseluruhan dan rincian per atribut (Tabel 3.50 - 3.59).
     */
    private function score($levelQuestions, $answers): array
    {
        $attributes = $levelQuestions
            ->groupBy('attribute_code')
            ->map(function ($items) use ($answers) {
                $score = $items->sum(fn ($q) => $answers[$q->id]->score);
                $percent = round($score / $items->count() * 100, 2);

                return [
                    'name' => $items->first()->attribute->name,
                    'sort_order' => $items->first()->attribute->sort_order,
                    'total' => $items->count(),
                    'score' => $score,
                    'percent' => $percent,
                    'rating' => Itil::rating($percent),
                ];
            })
            ->sortBy('sort_order')
            ->all();

        $total = $levelQuestions->count();
        $score = $levelQuestions->sum(fn ($q) => $answers[$q->id]->score);
        $percent = round($score / $total * 100, 2);

        return [
            'total' => $total,
            'score' => $score,
            'percent' => $percent,
            'rating' => Itil::rating($percent),
            'attributes' => $attributes,
        ];
    }

    private function result(string $status, array $history, array $levels, ?int $activeLevel = null, ?int $resultLevel = null): array
    {
        return [
            'status' => $status,
            'active_level' => $activeLevel,
            'result_level' => $resultLevel,
            // Level yang memiliki pertanyaan, contoh: [2, 3, 4, 5]
            'levels' => $levels,
            // Masih ada level berpertanyaan setelah level aktif (untuk prediksi "lanjut" atau "selesai")
            'has_next_level' => $activeLevel !== null && collect($levels)->contains(fn ($l) => $l > $activeLevel),
            'history' => $history,
        ];
    }
}

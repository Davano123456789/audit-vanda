<?php

namespace App\Services;

use App\Models\Answer;
use App\Models\Assessment;
use App\Models\ProcessAttribute;
use App\Models\Question;
use App\Support\Itil;

/**
 * Menyusun data laporan hasil assessment:
 * hasil per subdomain, rincian per atribut, dan nilai kematangan domain (Tabel 3.50 - 3.60).
 */
class AssessmentReport
{
    public function __construct(private MaturityAssessor $assessor)
    {
    }

    public function build(Assessment $assessment): array
    {
        $assessment->loadMissing(['asesor', 'processes' => fn ($q) => $q->orderBy('process_code'), 'processes.process']);

        $rows = $assessment->processes->map(function ($item) use ($assessment) {
            $result = $this->assessor->evaluate($assessment, $item->process_code);

            return [
                'code' => $item->process_code,
                'name' => $item->process->name,
                'target' => $item->target_level,
                'level' => $item->isCompleted() ? $item->result_level : null,
                'gap' => $item->gap,
                'completed' => $item->isCompleted(),
                'status_label' => $item->status_label,
                'status_color' => $item->status_color,
                'history' => $result['history'],
                'answers' => $this->answers($assessment, $item->process_code),
            ];
        });

        $completed = $rows->where('completed', true);
        $sum = $completed->sum('level');
        $count = $completed->count();
        $index = $count ? Itil::roundMaturity($sum / $count) : null;
        $maturityLevel = Itil::maturityLevel($index);

        return [
            'assessment' => $assessment,
            'rows' => $rows,
            'attributes' => ProcessAttribute::orderBy('level')->orderBy('sort_order')->get(),
            'is_final' => $count > 0 && $count === $rows->count(),
            'completed_count' => $count,
            'total_count' => $rows->count(),
            'sum' => $sum,
            'index' => $index,
            'maturity_level' => $maturityLevel,
            'maturity_name' => Itil::levelName($maturityLevel),
        ];
    }

    /**
     * Pertanyaan dan jawaban satu subdomain, dikelompokkan per atribut (format lembar kerja Tabel 3.49).
     * Hanya atribut yang sudah dijawab yang ikut.
     */
    private function answers(Assessment $assessment, string $processCode)
    {
        $answers = Answer::where('assessment_id', $assessment->id)
            ->whereHas('question', fn ($q) => $q->where('process_code', $processCode))
            ->get()
            ->keyBy('question_id');

        if ($answers->isEmpty()) {
            return collect();
        }

        return Question::with('attribute')
            ->whereIn('id', $answers->keys())
            ->get()
            ->sortBy(fn ($q) => [$q->attribute->level, $q->attribute->sort_order, $q->number])
            ->groupBy('attribute_code')
            ->map(fn ($questions) => [
                'attribute' => $questions->first()->attribute,
                'items' => $questions->map(fn ($question) => [
                    'question' => $question,
                    'answer' => $answers->get($question->id),
                ]),
            ]);
    }
}

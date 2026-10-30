<?php

namespace App\Http\Controllers;

use App\Http\Requests\SimpanJawabanRequest;
use App\Models\Answer;
use App\Models\Assessment;
use App\Models\AssessmentProcess;
use App\Models\ProcessAttribute;
use App\Models\Question;
use App\Services\MaturityAssessor;
use App\Support\Itil;
use Illuminate\Http\Request;

/**
 * Halaman penilaian untuk asesor.
 */
class PenilaianController extends Controller
{
    public function __construct(private MaturityAssessor $assessor)
    {
    }

    /**
     * Daftar assessment yang ditugaskan kepada asesor yang sedang login.
     */
    public function index(Request $request)
    {
        $assessments = $request->user()->assessments()
            ->withCount([
                'processes',
                'processes as completed_processes_count' => fn ($q) => $q->where('status', 'completed'),
            ])
            ->latest()
            ->get();

        return view('penilaian.index', compact('assessments'));
    }

    /**
     * Daftar subdomain dalam satu assessment beserta progres penilaiannya.
     */
    public function show(Request $request, Assessment $assessment)
    {
        $this->authorizeAssessment($request, $assessment);

        $assessment->load(['processes' => fn ($q) => $q->orderBy('process_code'), 'processes.process' => fn ($q) => $q->withCount('questions')]);

        return view('penilaian.show', compact('assessment'));
    }

    /**
     * Lembar kerja satu subdomain: pertanyaan level aktif, atau hasil jika sudah selesai.
     */
    public function workspace(Request $request, Assessment $assessment, string $proses)
    {
        $this->authorizeAssessment($request, $assessment);
        $item = $this->findProcess($assessment, $proses);
        $result = $this->assessor->sync($item);

        if ($result['status'] !== MaturityAssessor::IN_PROGRESS) {
            return view('penilaian.hasil', [
                'assessment' => $assessment,
                'item' => $item,
                'result' => $result,
                'answersByLevel' => $this->answersByLevel($assessment, $proses),
            ]);
        }

        $questions = $this->levelQuestions($proses, $result['active_level']);

        return view('penilaian.workspace', [
            'assessment' => $assessment,
            'item' => $item,
            'result' => $result,
            'questionsByAttribute' => $questions->groupBy('attribute_code'),
            'total' => $questions->count(),
            'answers' => Answer::where('assessment_id', $assessment->id)
                ->whereIn('question_id', $questions->pluck('id'))
                ->get()
                ->keyBy('question_id'),
        ]);
    }

    /**
     * Simpan jawaban level aktif, lalu evaluasi untuk menentukan level berikutnya.
     */
    public function simpan(SimpanJawabanRequest $request, Assessment $assessment, string $proses)
    {
        $this->authorizeAssessment($request, $assessment);
        $item = $this->findProcess($assessment, $proses);


        $before = $this->assessor->evaluate($assessment, $proses);
        $level = (int) $request->level;

        // Mencegah penyimpanan dari halaman lama (misal dibuka di dua tab)
        if ($before['status'] !== MaturityAssessor::IN_PROGRESS || $before['active_level'] !== $level) {
            return redirect()->route('penilaian.workspace', [$assessment, $proses])
                ->with('error', 'Halaman sudah tidak sesuai dengan progres terbaru, silakan periksa kembali.');
        }

        $questionIds = $this->levelQuestions($proses, $level)->pluck('id');

        // Semua pertanyaan level ini wajib dijawab; jika belum lengkap tidak ada yang disimpan
        $unanswered = $questionIds->reject(fn ($id) => array_key_exists((string) $request->input("answers.{$id}"), Answer::SCORES))->count();

        if ($unanswered > 0) {
            return back()->withInput()
                ->with('error', "Masih ada {$unanswered} pertanyaan yang belum dijawab. Lengkapi semua jawaban sebelum mengevaluasi " . $this->levelLabel($level) . '.');
        }

        foreach ($questionIds as $questionId) {
            $answer = $request->input("answers.{$questionId}");
            Answer::updateOrCreate(
                ['assessment_id' => $assessment->id, 'question_id' => $questionId],
                [
                    'answer' => $answer,
                    'score' => Answer::scoreFor($answer),
                ]
            );
        }

        $after = $this->assessor->sync($item);

        // Ringkasan evaluasi ditampilkan sebagai popup di halaman berikutnya
        return redirect()->route('penilaian.workspace', [$assessment, $proses])
            ->with('evaluation', $this->evaluationSummary($item, $level, $after));
    }

    /**
     * Hapus seluruh jawaban satu subdomain dan mulai penilaian dari Level 1 & 2 lagi.
     */
    public function reset(Request $request, Assessment $assessment, string $proses)
    {
        $this->authorizeAssessment($request, $assessment);
        $item = $this->findProcess($assessment, $proses);

        Answer::where('assessment_id', $assessment->id)
            ->whereHas('question', fn ($q) => $q->where('process_code', $proses))
            ->delete();

        $this->assessor->sync($item);

        return redirect()->route('penilaian.workspace', [$assessment, $proses])
            ->with('success', "Penilaian {$proses} berhasil direset, dimulai kembali dari Level 1 & 2.");
    }

    private function authorizeAssessment(Request $request, Assessment $assessment): void
    {
        abort_unless($assessment->asesor_id === $request->user()->id, 403, 'Assessment ini tidak ditugaskan kepada Anda.');
    }

    private function findProcess(Assessment $assessment, string $processCode): AssessmentProcess
    {
        return $assessment->processes()->with('process')->where('process_code', $processCode)->firstOrFail();
    }

    /**
     * Pertanyaan satu subdomain pada satu level, urut atribut lalu nomor.
     */
    private function levelQuestions(string $processCode, int $level)
    {
        return Question::with('attribute')
            ->select('itil_questions.*')
            ->join('process_attributes', 'process_attributes.code', '=', 'itil_questions.attribute_code')
            ->where('itil_questions.process_code', $processCode)
            ->where('process_attributes.level', $level)
            ->orderBy('process_attributes.sort_order')
            ->orderBy('itil_questions.number')
            ->get();
    }

    /**
     * Jawaban satu subdomain dikelompokkan per level lalu per atribut, untuk halaman hasil.
     */
    private function answersByLevel(Assessment $assessment, string $processCode)
    {
        return Answer::with('question.attribute')
            ->where('assessment_id', $assessment->id)
            ->whereHas('question', fn ($q) => $q->where('process_code', $processCode))
            ->get()
            ->sortBy(fn ($a) => [$a->question->attribute->level, $a->question->attribute->sort_order, $a->question->number])
            ->groupBy(fn ($a) => $a->question->attribute->level)
            ->map(fn ($items) => $items->groupBy('question.attribute_code'));
    }

    private function levelLabel(int $level): string
    {
        return ProcessAttribute::LEVEL_LABELS[$level] ?? "Level {$level}";
    }

    /**
     * Data ringkasan untuk popup setelah sebuah level dievaluasi.
     */
    private function evaluationSummary(AssessmentProcess $item, int $level, array $result): array
    {
        $completed = $result['status'] === MaturityAssessor::COMPLETED;
        $resultLevel = $completed ? $result['result_level'] : null;
        $score = $result['history'][$level];

        return [
            'process' => "{$item->process_code} · {$item->process->name}",
            'level_label' => $this->levelLabel($level),
            'score' => $score['score'],
            'total' => $score['total'],
            'percent' => Itil::formatPercent($score['percent']),
            'rating' => $score['rating'],
            'rating_name' => config("itil.ratings.{$score['rating']}.name"),
            'completed' => $completed,
            'next_level_label' => $completed ? null : $this->levelLabel($result['active_level']),
            'result_level' => $resultLevel,
            'result_name' => Itil::levelName($resultLevel),
            'target' => $item->target_level,
            'gap' => $completed ? max(0, $item->target_level - $resultLevel) : null,
        ];
    }
}

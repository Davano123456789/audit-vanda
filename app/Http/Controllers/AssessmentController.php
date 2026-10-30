<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssessmentRequest;
use App\Models\Assessment;
use App\Models\Domain;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Pengelolaan assessment oleh admin.
 */
class AssessmentController extends Controller
{
    public function index(Request $request)
    {
        $assessments = Assessment::with('asesor')
            ->withCount([
                'processes',
                'processes as completed_processes_count' => fn ($q) => $q->where('status', 'completed'),
            ])
            ->when($request->status, fn ($query, $status) => $query->where('status', $status))
            ->when($request->asesor, fn ($query, $asesor) => $query->where('asesor_id', $asesor))
            ->when($request->q, fn ($query, $q) => $query->where(fn ($w) => $w
                ->where('name', 'like', "%{$q}%")
                ->orWhere('company_name', 'like', "%{$q}%")))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('assessment.index', [
            'assessments' => $assessments,
            'asesorOptions' => $this->asesorOptions(),
        ]);
    }

    public function create()
    {
        return view('assessment.create', [
            'assessment' => new Assessment(['company_name' => 'PT. Askarasoft Global Teknologi']),
            'asesorOptions' => $this->asesorOptions(),
            'domains' => $this->domainsWithProcesses(),
        ]);
    }

    public function store(AssessmentRequest $request)
    {
        DB::transaction(function () use ($request) {
            $assessment = Assessment::create($request->assessmentData() + ['status' => 'draft']);

            foreach ($request->processes as $code) {
                $assessment->processes()->create([
                    'process_code' => $code,
                    'target_level' => $request->targetLevelFor($code),
                ]);
            }
        });

        return redirect()->route('assessment.index')->with('success', 'Data saved. Assessment berhasil dibuat.');
    }

    public function show(Assessment $assessment)
    {
        $assessment->load(['asesor', 'processes' => fn ($q) => $q->orderBy('process_code'), 'processes.process']);

        return view('assessment.show', compact('assessment'));
    }

    public function edit(Assessment $assessment)
    {
        $assessment->load('processes');

        return view('assessment.edit', [
            'assessment' => $assessment,
            'asesorOptions' => $this->asesorOptions(),
            'domains' => $this->domainsWithProcesses(),
            // [kode process => AssessmentProcess] untuk menandai subdomain yang sudah dipilih
            'selected' => $assessment->processes->keyBy('process_code'),
        ]);
    }

    public function update(AssessmentRequest $request, Assessment $assessment)
    {
        DB::transaction(function () use ($request, $assessment) {
            $assessment->update($request->assessmentData());

            $existing = $assessment->processes()->get()->keyBy('process_code');
            $chosen = collect($request->processes);

            // Hapus subdomain yang tidak dipilih lagi, kecuali yang sudah mulai dinilai
            foreach ($existing as $code => $item) {
                if (! $chosen->contains($code) && ! $item->isLocked()) {
                    $item->delete();
                }
            }

            foreach ($chosen as $code) {
                if ($item = $existing->get($code)) {
                    $item->update(['target_level' => $request->targetLevelFor($code)]);
                } else {
                    $assessment->processes()->create([
                        'process_code' => $code,
                        'target_level' => $request->targetLevelFor($code),
                    ]);
                }
            }
        });

        return redirect()->route('assessment.show', $assessment)->with('success', 'Data saved. Assessment berhasil diperbarui.');
    }

    public function destroy(Assessment $assessment)
    {
        $assessment->delete();

        return redirect()->route('assessment.index')->with('success', 'Assessment berhasil dihapus.');
    }

    private function asesorOptions(): array
    {
        return User::where('role', User::ROLE_ASESOR)->orderBy('name')->pluck('name', 'id')->all();
    }

    private function domainsWithProcesses()
    {
        return Domain::with(['processes' => fn ($q) => $q->withCount('questions')->orderBy('code')])
            ->whereHas('processes')
            ->orderBy('code')
            ->get();
    }
}

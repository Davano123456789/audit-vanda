<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\AssessmentProcess;
use App\Models\ItilProcess;
use App\Models\ProcessAttribute;
use App\Models\Question;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        return $request->user()->isAdmin()
            ? $this->admin()
            : $this->asesor($request);
    }

    private function admin()
    {
        $statusCounts = Assessment::selectRaw('status, COUNT(*) as total')->groupBy('status')->pluck('total', 'status');

        return view('dashboard.admin', [
            'stats' => [
                'assessments' => $statusCounts->sum(),
                'in_progress' => $statusCounts->get('in_progress', 0),
                'completed' => $statusCounts->get('completed', 0),
                'processes' => ItilProcess::count(),
                'attributes' => ProcessAttribute::count(),
                'questions' => Question::count(),
                'asesors' => User::where('role', User::ROLE_ASESOR)->count(),
            ],
        ]);
    }

    private function asesor(Request $request)
    {
        $user = $request->user();
        $processes = AssessmentProcess::whereHas('assessment', fn ($q) => $q->where('asesor_id', $user->id));

        return view('dashboard.asesor', [
            'stats' => [
                'assessments' => $user->assessments()->count(),
                'assessments_completed' => $user->assessments()->where('status', 'completed')->count(),
                'processes' => (clone $processes)->count(),
                'processes_completed' => (clone $processes)->where('status', 'completed')->count(),
            ],
            // Subdomain yang belum selesai dinilai, yang sedang berjalan ditampilkan lebih dulu
            'pending' => (clone $processes)->with(['assessment', 'process'])
                ->where('status', '!=', 'completed')
                ->orderByRaw("status = 'in_progress' DESC")
                ->orderBy('assessment_id')
                ->orderBy('process_code')
                ->take(6)
                ->get(),
        ]);
    }

}

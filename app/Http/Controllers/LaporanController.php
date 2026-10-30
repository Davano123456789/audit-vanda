<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Services\AssessmentReport;
use Illuminate\Http\Request;

/**
 * Laporan hasil assessment, dapat dibuka admin dan asesor pemilik assessment.
 */
class LaporanController extends Controller
{
    public function __construct(private AssessmentReport $report)
    {
    }

    public function show(Request $request, Assessment $assessment)
    {
        $user = $request->user();

        abort_unless($user->isAdmin() || $assessment->asesor_id === $user->id, 403, 'Anda tidak memiliki akses ke laporan ini.');

        return view('laporan.show', ['report' => $this->report->build($assessment)]);
    }
}

<?php

namespace Tests\Feature;

use App\Models\Assessment;
use App\Models\AssessmentProcess;
use App\Models\Question;
use App\Models\User;
use App\Services\MaturityAssessor;
use App\Support\Itil;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * Test case Proses Assessment (ASS01 - ASS05) dan simulasi perhitungan proposal (Tabel 3.49 - 3.60).
 */
class PenilaianTest extends TestCase
{
    use DatabaseTransactions;

    private User $asesor;
    private Assessment $assessment;

    protected function setUp(): void
    {
        parent::setUp();

        $this->asesor = User::create(['name' => 'Asesor Uji', 'email' => 'asesor-pn@test.local', 'password' => 'rahasia123', 'role' => User::ROLE_ASESOR]);
        $this->assessment = Assessment::create(['name' => 'Assessment Uji', 'company_name' => 'PT. Askarasoft', 'asesor_id' => $this->asesor->id]);

        foreach (['EM', 'IM', 'PM', 'RF', 'AM'] as $code) {
            $this->assessment->processes()->create(['process_code' => $code, 'target_level' => 3]);
        }

        $this->actingAs($this->asesor);
    }

    /**
     * Kirim jawaban satu level. $answers = [kode atribut => 'YPN...'] urut nomor pertanyaan,
     * atau satu huruf untuk semua pertanyaan pada level tersebut.
     */
    private function answerLevel(string $process, int $level, array|string $answers, array $extra = [])
    {
        $questions = Question::with('attribute')->where('process_code', $process)->get()
            ->filter(fn ($q) => $q->attribute->level === $level)
            ->sortBy(fn ($q) => [$q->attribute->sort_order, $q->number]);

        $payload = [];
        foreach ($questions->groupBy('attribute_code') as $code => $items) {
            foreach ($items->values() as $i => $question) {
                $payload[$question->id] = is_string($answers) ? $answers : $answers[$code][$i];
            }
        }

        return $this->post("/penilaian/{$this->assessment->id}/{$process}", ['level' => $level, 'answers' => $payload] + $extra);
    }

    private function process(string $code): AssessmentProcess
    {
        return $this->assessment->processes()->where('process_code', $code)->first();
    }

    /** ASS01 - ASS02: halaman daftar subdomain dan lembar kerja */
    public function test_halaman_penilaian_dapat_dibuka(): void
    {
        $this->get('/penilaian')->assertOk()->assertSee('Assessment Uji');
        $this->get("/penilaian/{$this->assessment->id}")->assertOk()->assertSee('Incident Management')->assertSee('Mulai Nilai');
        $this->get("/penilaian/{$this->assessment->id}/IM")->assertOk()
            ->assertSee('Level 1 &amp; 2', false)
            ->assertSee('Apakah setiap insiden yang terjadi dicatat');
    }

    /** Simulasi proposal: IM Level 1 & 2 = 100% (F), Level 3 = 30,23% (P) -> hasil Level 2 */
    public function test_simulasi_perhitungan_incident_management_sesuai_proposal(): void
    {
        // Tabel 3.49: semua jawaban Y -> 100%, rating F, lanjut ke Level 3
        $this->answerLevel('IM', 2, 'Y')->assertSessionHas('evaluation');
        $this->get("/penilaian/{$this->assessment->id}/IM")->assertSee('Hasil Evaluasi Level 1')->assertSee('100,00%');
        $result = app(MaturityAssessor::class)->evaluate($this->assessment, 'IM');
        $this->assertSame(MaturityAssessor::IN_PROGRESS, $result['status']);
        $this->assertSame(3, $result['active_level']);
        $this->assertEquals(100, $result['history'][2]['percent']);
        $this->assertSame('F', $result['history'][2]['rating']);

        // Tabel 3.51, 3.53, 3.55, 3.57 (jawaban dummy proposal)
        $this->answerLevel('IM', 3, [
            'PM' => 'PYNNNYNYYPYYNNN',
            'WPM' => 'PNNNNNPN',
            'PD' => 'NNNNNYYNN',
            'PDep' => 'PNNNPPPPPNN',
        ]);

        $result = app(MaturityAssessor::class)->evaluate($this->assessment, 'IM');
        $level3 = $result['history'][3];

        $this->assertEquals(46.67, $level3['attributes']['PM']['percent']);
        $this->assertSame('P', $level3['attributes']['PM']['rating']);
        $this->assertEquals(12.5, $level3['attributes']['WPM']['percent']);
        $this->assertSame('N', $level3['attributes']['WPM']['rating']);
        $this->assertEquals(22.22, $level3['attributes']['PD']['percent']);
        $this->assertEquals(27.27, $level3['attributes']['PDep']['percent']);

        // Tabel 3.59: skor 13 dari 43 pertanyaan = 30,23% (P) -> berhenti, hasil Level 2
        $this->assertEquals(13, $level3['score']);
        $this->assertSame(43, $level3['total']);
        $this->assertEquals(30.23, $level3['percent']);
        $this->assertSame('P', $level3['rating']);
        $this->assertSame(MaturityAssessor::COMPLETED, $result['status']);
        $this->assertSame(2, $result['result_level']);

        $item = $this->process('IM');
        $this->assertSame('completed', $item->status);
        $this->assertSame(2, $item->result_level);
        $this->assertSame(1, $item->gap);

        $this->get("/penilaian/{$this->assessment->id}/IM")->assertOk()
            ->assertSee('30,23%')
            ->assertSee('Berhenti, hasil Level 2');
    }

    /** Tabel 3.60: semua subdomain Level 2 -> kematangan domain 10 / 5 = 2 (Level 2) */
    public function test_kematangan_domain_sesuai_proposal(): void
    {
        foreach (['EM', 'IM', 'PM', 'RF', 'AM'] as $code) {
            $this->answerLevel($code, 2, 'Y');
            $this->answerLevel($code, 3, 'N');
        }

        $assessment = $this->assessment->fresh();
        $this->assertSame('completed', $assessment->status);
        $this->assertEquals(2.0, $assessment->maturity_index);
        $this->assertSame(2, $assessment->maturity_level);
        $this->assertSame('Repeatable', $assessment->maturity_level_name);
    }

    /** ASS03: jawaban kosong tidak boleh lanjut ke level berikutnya */
    public function test_jawaban_belum_lengkap_ditolak(): void
    {
        $first = Question::where('process_code', 'IM')->where('attribute_code', 'PP')->where('number', 1)->first();

        $this->post("/penilaian/{$this->assessment->id}/IM", ['level' => 2, 'answers' => [$first->id => 'Y']])
            ->assertSessionHas('error');

        $this->assertSame(0, $this->assessment->answers()->count());
        $this->assertSame(2, app(MaturityAssessor::class)->evaluate($this->assessment, 'IM')['active_level']);
    }

    /** ASS04: nilai tepat 50% tidak boleh lanjut (rating P) */
    public function test_nilai_50_persen_tidak_lanjut(): void
    {
        // IM - PP punya 6 pertanyaan: 3 Y + 3 N = 50%
        $this->answerLevel('IM', 2, ['PP' => 'YYYNNN']);

        $result = app(MaturityAssessor::class)->evaluate($this->assessment, 'IM');
        $this->assertEquals(50, $result['history'][2]['percent']);
        $this->assertSame('P', $result['history'][2]['rating']);
        $this->assertSame(MaturityAssessor::COMPLETED, $result['status']);
        $this->assertSame(0, $result['result_level']);
    }

    /** ASS05: nilai di atas batas (>= 85%) lanjut ke level berikutnya */
    public function test_nilai_di_atas_batas_lanjut(): void
    {
        // 5 Y + 1 P = 5,5 / 6 = 91,67% -> F
        $this->answerLevel('IM', 2, ['PP' => 'YYYYYP']);

        $result = app(MaturityAssessor::class)->evaluate($this->assessment, 'IM');
        $this->assertSame('F', $result['history'][2]['rating']);
        $this->assertSame(3, $result['active_level']);
    }

    public function test_semua_level_lulus_hasil_level_5(): void
    {
        foreach ([2, 3, 4, 5] as $level) {
            $this->answerLevel('AM', $level, 'Y');
        }

        $this->assertSame(5, $this->process('AM')->result_level);
    }

    public function test_aturan_rating_dan_level_hasil(): void
    {
        $this->assertSame('N', Itil::rating(15));
        $this->assertSame('P', Itil::rating(15.01));
        $this->assertSame('P', Itil::rating(50));
        $this->assertSame('L', Itil::rating(84.99));
        $this->assertSame('F', Itil::rating(85));

        $this->assertSame(0, MaturityAssessor::resultLevel(2, 'N'));
        $this->assertSame(0, MaturityAssessor::resultLevel(2, 'P'));
        $this->assertSame(0, MaturityAssessor::resultLevel(2, 'L'));
        $this->assertSame(2, MaturityAssessor::resultLevel(3, 'L'));
        $this->assertSame(4, MaturityAssessor::resultLevel(5, 'N'));

        $this->assertSame(3, Itil::maturityLevel(2.83));
        $this->assertSame(2, Itil::maturityLevel(2.5));
    }

    public function test_simpan_lalu_reset(): void
    {
        $this->answerLevel('IM', 2, 'Y');
        $this->assertGreaterThan(0, $this->assessment->answers()->count());

        $this->post("/penilaian/{$this->assessment->id}/IM/reset")->assertSessionHas('success');
        $this->assertSame(0, $this->assessment->answers()->count());
        $this->assertSame('not_started', $this->process('IM')->status);
    }

    public function test_hak_akses_penilaian(): void
    {
        $other = User::create(['name' => 'Asesor Lain', 'email' => 'asesor-lain@test.local', 'password' => 'rahasia123', 'role' => User::ROLE_ASESOR]);
        $admin = User::create(['name' => 'Admin', 'email' => 'admin-pn@test.local', 'password' => 'rahasia123', 'role' => User::ROLE_ADMIN]);

        $this->actingAs($other)->get("/penilaian/{$this->assessment->id}")->assertForbidden();
        $this->actingAs($admin)->get('/penilaian')->assertForbidden();
    }
}

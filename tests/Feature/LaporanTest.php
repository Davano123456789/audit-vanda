<?php

namespace Tests\Feature;

use App\Models\Assessment;
use App\Models\Question;
use App\Models\User;
use App\Services\MaturityAssessor;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * Test case Halaman Report (RPT01).
 */
class LaporanTest extends TestCase
{
    use DatabaseTransactions;

    private User $asesor;
    private Assessment $assessment;

    protected function setUp(): void
    {
        parent::setUp();

        $this->asesor = User::create(['name' => 'Asesor Laporan', 'email' => 'asesor-lp@test.local', 'password' => 'rahasia123', 'role' => User::ROLE_ASESOR]);
        $this->assessment = Assessment::create(['name' => 'Assessment Laporan', 'company_name' => 'PT. Askarasoft Global Teknologi', 'asesor_id' => $this->asesor->id]);

        foreach (['EM', 'IM', 'PM', 'RF', 'AM'] as $code) {
            $this->assessment->processes()->create(['process_code' => $code, 'target_level' => 3]);
        }
    }

    /**
     * Isi jawaban langsung ke database: Level 1 & 2 semua Y, Level 3 semua N -> hasil Level 2.
     */
    private function completeProcess(string $code): void
    {
        Question::with('attribute')->where('process_code', $code)->get()
            ->filter(fn ($q) => in_array($q->attribute->level, [2, 3]))
            ->each(fn ($q) => $this->assessment->answers()->create([
                'question_id' => $q->id,
                'answer' => $q->attribute->level === 2 ? 'Y' : 'N',
                'score' => $q->attribute->level === 2 ? 1 : 0,
            ]));

        app(MaturityAssessor::class)->sync($this->assessment->processes()->where('process_code', $code)->first());
    }

    /** RPT01: laporan sementara saat belum semua subdomain selesai */
    public function test_laporan_sementara(): void
    {
        $this->completeProcess('IM');

        $this->actingAs($this->asesor)->get("/laporan/{$this->assessment->id}")
            ->assertOk()
            ->assertSee('Laporan sementara')
            ->assertSee('Incident Management')
            ->assertSee('1 dari 5 subdomain selesai dinilai');
    }

    /** RPT01: laporan final menampilkan hasil perhitungan kematangan domain (Tabel 3.60) */
    public function test_laporan_final_menampilkan_perhitungan(): void
    {
        foreach (['EM', 'IM', 'PM', 'RF', 'AM'] as $code) {
            $this->completeProcess($code);
        }

        $admin = User::create(['name' => 'Admin', 'email' => 'admin-lp@test.local', 'password' => 'rahasia123', 'role' => User::ROLE_ADMIN]);

        $this->actingAs($admin)->get("/laporan/{$this->assessment->id}")
            ->assertOk()
            ->assertDontSee('Laporan sementara')
            ->assertSee('= 10 ÷ 5', false)
            ->assertSee('2,00')
            ->assertSee('Repeatable')
            ->assertSee('Berhenti, hasil Level 2')
            ->assertSee('Rincian Jawaban Lembar Kerja')
            ->assertSee('Cetak / PDF');
    }

    public function test_tombol_report_tampil_di_detail_assessment(): void
    {
        $admin = User::create(['name' => 'Admin', 'email' => 'admin-lp@test.local', 'password' => 'rahasia123', 'role' => User::ROLE_ADMIN]);

        $this->actingAs($admin)->get("/assessment/{$this->assessment->id}")
            ->assertSee(route('laporan.show', $this->assessment));
    }

    public function test_asesor_lain_tidak_dapat_membuka_laporan(): void
    {
        $other = User::create(['name' => 'Asesor Lain', 'email' => 'asesor-lain-lp@test.local', 'password' => 'rahasia123', 'role' => User::ROLE_ASESOR]);

        $this->actingAs($other)->get("/laporan/{$this->assessment->id}")->assertForbidden();
    }
}

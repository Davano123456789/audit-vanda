<?php

namespace Tests\Feature;

use App\Models\Assessment;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * Test case Assessment (Create Assessment: PJT, Edit Form Assessment: EDT).
 */
class AssessmentTest extends TestCase
{
    use DatabaseTransactions;

    private User $admin;
    private User $asesor;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create(['name' => 'Admin Uji', 'email' => 'admin-asm@test.local', 'password' => 'rahasia123', 'role' => User::ROLE_ADMIN]);
        $this->asesor = User::create(['name' => 'Asesor Uji', 'email' => 'asesor-asm@test.local', 'password' => 'rahasia123', 'role' => User::ROLE_ASESOR]);
        $this->actingAs($this->admin);
    }

    private function validData(array $override = []): array
    {
        return array_merge([
            'name' => 'Assessment Uji',
            'company_name' => 'PT. Askarasoft Global Teknologi',
            'asesor_id' => $this->asesor->id,
            'processes' => ['EM', 'IM', 'PM', 'RF', 'AM'],
            'target_levels' => ['EM' => 3, 'IM' => 4, 'PM' => 3, 'RF' => 3, 'AM' => 2],
        ], $override);
    }

    private function makeAssessment(): Assessment
    {
        $this->post('/assessment', $this->validData());

        return Assessment::where('name', 'Assessment Uji')->firstOrFail();
    }

    /** PJT02: halaman create menampilkan form nama perusahaan */
    public function test_halaman_create_assessment(): void
    {
        $this->get('/assessment/create')
            ->assertOk()
            ->assertSee('Nama Perusahaan')
            ->assertDontSee('Batas Waktu')
            ->assertSee('Incident Management');
    }

    /** PJT03: buat assessment dengan data valid */
    public function test_create_assessment_valid(): void
    {
        $this->post('/assessment', $this->validData())
            ->assertRedirect('/assessment')
            ->assertSessionHas('success');

        $assessment = Assessment::where('name', 'Assessment Uji')->firstOrFail();
        $this->assertSame('draft', $assessment->status);
        $this->assertSame(5, $assessment->processes()->count());
        $this->assertSame(4, $assessment->processes()->where('process_code', 'IM')->value('target_level'));
    }

    /** PJT03: nama / nama perusahaan / subdomain kosong */
    public function test_create_assessment_kolom_wajib(): void
    {
        $this->post('/assessment', $this->validData(['name' => '']))->assertSessionHasErrors('name');
        $this->post('/assessment', $this->validData(['company_name' => '']))->assertSessionHasErrors('company_name');
        $this->post('/assessment', $this->validData(['processes' => []]))->assertSessionHasErrors('processes');
        // Yang dipilih sebagai asesor harus berrole asesor
        $this->post('/assessment', $this->validData(['asesor_id' => $this->admin->id]))->assertSessionHasErrors('asesor_id');
    }

    public function test_index_dan_detail_assessment(): void
    {
        $assessment = $this->makeAssessment();

        $this->get('/assessment')->assertOk()->assertSee('Assessment Uji')->assertSee('0/5');
        $this->get('/assessment?status=completed')->assertDontSee('Assessment Uji');
        $this->get("/assessment/{$assessment->id}")->assertOk()->assertSee('Access Management')->assertSee('Asesor Uji');
    }

    /** EDT01 - EDT03: edit assessment */
    public function test_edit_assessment(): void
    {
        $assessment = $this->makeAssessment();

        $this->get("/assessment/{$assessment->id}/edit")->assertOk()->assertSee('Assessment Uji');

        $this->put("/assessment/{$assessment->id}", $this->validData(['name' => 'Nama Baru', 'processes' => ['IM', 'EM']]))
            ->assertSessionHas('success');
        $this->assertSame('Nama Baru', $assessment->fresh()->name);
        $this->assertSame(['EM', 'IM'], $assessment->processes()->orderBy('process_code')->pluck('process_code')->all());

        $this->put("/assessment/{$assessment->id}", $this->validData(['company_name' => '']))
            ->assertSessionHasErrors('company_name');
    }

    public function test_subdomain_yang_sudah_dinilai_tetap_ikut_saat_edit(): void
    {
        $assessment = $this->makeAssessment();
        $assessment->processes()->where('process_code', 'AM')->update(['status' => 'in_progress']);

        // AM tidak dikirim (checkbox nonaktif), tetapi tidak boleh terhapus
        $this->put("/assessment/{$assessment->id}", $this->validData(['processes' => ['IM']]))->assertSessionHas('success');

        $this->assertSame(['AM', 'IM'], $assessment->processes()->orderBy('process_code')->pluck('process_code')->all());
    }

    public function test_hapus_assessment(): void
    {
        $assessment = $this->makeAssessment();

        $this->delete("/assessment/{$assessment->id}")->assertRedirect('/assessment');
        $this->assertNull(Assessment::find($assessment->id));
    }

    public function test_asesor_tidak_dapat_mengelola_assessment(): void
    {
        $this->actingAs($this->asesor)->get('/assessment')->assertForbidden();
        $this->actingAs($this->asesor)->post('/assessment', $this->validData())->assertForbidden();
    }
}

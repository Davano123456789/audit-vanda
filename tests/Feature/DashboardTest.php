<?php

namespace Tests\Feature;

use App\Models\Assessment;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use DatabaseTransactions;

    private function makeUser(string $role, string $email): User
    {
        return User::create(['name' => 'Uji ' . $role, 'email' => $email, 'password' => 'rahasia123', 'role' => $role]);
    }

    public function test_dashboard_admin_tampil_dengan_statistik(): void
    {
        $this->actingAs($this->makeUser(User::ROLE_ADMIN, 'admin-uji@test.local'))
            ->get('/')
            ->assertOk()
            ->assertSee('process attribute')
            ->assertSee('315')
            ->assertDontSee('Assessment Terbaru')
            ->assertDontSee('Lembar Kerja Service Operation')
            ->assertDontSee('Maturity Level per Subdomain');
    }

    public function test_dashboard_asesor_kosong_tampil_rapi(): void
    {
        $this->actingAs($this->makeUser(User::ROLE_ASESOR, 'asesor-uji@test.local'))
            ->get('/')
            ->assertOk()
            ->assertSee('Lanjutkan Penilaian')
            ->assertSee('Tidak ada subdomain yang menunggu penilaian.')
            ->assertDontSee('Maturity Level per Subdomain');
    }

    public function test_dashboard_asesor_menampilkan_subdomain_tertunda(): void
    {
        $asesor = $this->makeUser(User::ROLE_ASESOR, 'asesor-uji@test.local');

        $assessment = Assessment::create([
            'name' => 'Assessment Uji', 'company_name' => 'PT. Uji', 'asesor_id' => $asesor->id,
            'status' => 'in_progress', 'maturity_index' => 2,
        ]);
        $assessment->processes()->create(['process_code' => 'IM', 'target_level' => 3, 'result_level' => 2, 'status' => 'completed']);
        $assessment->processes()->create(['process_code' => 'EM', 'target_level' => 3, 'status' => 'in_progress']);

        $this->actingAs($asesor)->get('/')
            ->assertOk()
            ->assertSee('Assessment Uji')
            ->assertSee('Event Management')      // subdomain tertunda
            ->assertDontSee('Incident Management') // sudah selesai, tidak ikut tampil
            ->assertSee('1/2');                    // subdomain selesai dinilai
    }
}

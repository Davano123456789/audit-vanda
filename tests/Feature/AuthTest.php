<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

/**
 * Test case halaman Login (LOG01 - LOG02) dan pembatasan akses role.
 * Memakai DatabaseTransactions agar data di database tidak berubah.
 */
class AuthTest extends TestCase
{
    use DatabaseTransactions;

    private function makeUser(string $role): User
    {
        return User::create([
            'name' => 'Pengguna Uji',
            'email' => "uji-{$role}@test.local",
            'password' => 'rahasia123',
            'role' => $role,
        ]);
    }

    public function test_halaman_login_dapat_dibuka(): void
    {
        $this->get('/login')->assertOk()->assertSee('Masuk');
    }

    public function test_tamu_diarahkan_ke_login(): void
    {
        $this->get('/')->assertRedirect('/login');
    }

    /** LOG01: login dengan email dan password benar */
    public function test_login_berhasil_masuk_dashboard(): void
    {
        $user = $this->makeUser(User::ROLE_ADMIN);

        $this->post('/login', ['email' => $user->email, 'password' => 'rahasia123'])
            ->assertRedirect('/');

        $this->assertAuthenticatedAs($user);
        $this->get('/')->assertOk()->assertSee($user->name);
    }

    /** LOG02: login dengan password salah */
    public function test_login_password_salah_muncul_error(): void
    {
        $user = $this->makeUser(User::ROLE_ADMIN);

        $this->from('/login')->post('/login', ['email' => $user->email, 'password' => 'salah'])
            ->assertRedirect('/login')
            ->assertSessionHasErrors(['email' => 'Email atau password salah.']);

        $this->assertGuest();
    }

    /** LOG02: login dengan kolom kosong */
    public function test_login_kolom_kosong_muncul_error(): void
    {
        $this->from('/login')->post('/login', ['email' => '', 'password' => ''])
            ->assertRedirect('/login')
            ->assertSessionHasErrors(['email', 'password']);
    }

    public function test_logout(): void
    {
        $this->actingAs($this->makeUser(User::ROLE_ASESOR))
            ->post('/logout')
            ->assertRedirect('/login');

        $this->assertGuest();
    }

    public function test_menu_sidebar_sesuai_role(): void
    {
        $this->actingAs($this->makeUser(User::ROLE_ADMIN))->get('/')
            ->assertSee('Data Master')->assertDontSee('Assessment Saya');

        $this->actingAs($this->makeUser(User::ROLE_ASESOR))->get('/')
            ->assertSee('Assessment Saya')->assertDontSee('Data Master');
    }

    public function test_middleware_role_menolak_role_lain(): void
    {
        Route::middleware(['web', 'auth', 'role:admin'])->get('/_uji-admin', fn () => 'ok');

        $this->actingAs($this->makeUser(User::ROLE_ASESOR))->get('/_uji-admin')->assertForbidden();
        $this->actingAs($this->makeUser(User::ROLE_ADMIN))->get('/_uji-admin')->assertOk();
    }
}

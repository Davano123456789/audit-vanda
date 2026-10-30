<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfilTest extends TestCase
{
    use DatabaseTransactions;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::create([
            'name' => 'Pengguna Uji', 'email' => 'profil-uji@test.local',
            'password' => 'rahasia123', 'role' => User::ROLE_ASESOR,
        ]);
    }

    public function test_halaman_profil_dapat_dibuka(): void
    {
        $this->actingAs($this->user)->get('/profil')->assertOk()->assertSee('Ganti Password');
    }

    public function test_update_profil_dan_foto(): void
    {
        Storage::fake('public');

        $this->actingAs($this->user)->put('/profil', [
            'name' => 'Nama Baru',
            'email' => 'NAMA-BARU@test.local',
            'phone' => '0812 3456 7890',
            'address' => 'Surabaya',
            'photo' => UploadedFile::fake()->image('foto.jpg'),
        ])->assertSessionHasNoErrors()->assertSessionHas('success');

        $this->user->refresh();
        $this->assertSame('Nama Baru', $this->user->name);
        $this->assertSame('nama-baru@test.local', $this->user->email);
        Storage::disk('public')->assertExists($this->user->photo);
    }

    public function test_update_profil_nama_kosong_ditolak(): void
    {
        $this->actingAs($this->user)->put('/profil', ['name' => '', 'email' => $this->user->email])
            ->assertSessionHasErrors('name');
    }

    public function test_ganti_password(): void
    {
        $this->actingAs($this->user)->put('/profil/password', [
            'current_password' => 'rahasia123',
            'password' => 'passwordbaru1',
            'password_confirmation' => 'passwordbaru1',
        ])->assertSessionHasNoErrors()->assertSessionHas('success');

        $this->assertTrue(Hash::check('passwordbaru1', $this->user->fresh()->password));
    }

    public function test_ganti_password_lama_salah_ditolak(): void
    {
        $this->actingAs($this->user)->put('/profil/password', [
            'current_password' => 'salah',
            'password' => 'passwordbaru1',
            'password_confirmation' => 'passwordbaru1',
        ])->assertSessionHasErrorsIn('password', 'current_password');
    }
}

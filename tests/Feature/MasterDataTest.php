<?php

namespace Tests\Feature;

use App\Models\ItilProcess;
use App\Models\ProcessAttribute;
use App\Models\Question;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * Test case Data Master (Process: VPP, CRP, UPP; Question: VSQ, CRQ, UPQ).
 * Memakai data seeder yang sudah ada dan DatabaseTransactions agar data tidak berubah.
 */
class MasterDataTest extends TestCase
{
    use DatabaseTransactions;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin Uji', 'email' => 'admin-master@test.local',
            'password' => 'rahasia123', 'role' => User::ROLE_ADMIN,
        ]);
        $this->actingAs($this->admin);
    }

    public function test_semua_halaman_master_dapat_dibuka(): void
    {
        $question = Question::first();

        foreach ([
            '/master/domain', '/master/domain/create', '/master/domain/SO/edit',
            '/master/proses', '/master/proses/create', '/master/proses/IM/edit',
            '/master/atribut', '/master/atribut/create', '/master/atribut/PDep/edit',
            '/master/pertanyaan', '/master/pertanyaan/create', "/master/pertanyaan/{$question->id}/edit",
            '/master/pengguna', '/master/pengguna/create', "/master/pengguna/{$this->admin->id}/edit",
        ] as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_asesor_tidak_dapat_membuka_data_master(): void
    {
        $asesor = User::create(['name' => 'Asesor Uji', 'email' => 'asesor-master@test.local', 'password' => 'rahasia123', 'role' => User::ROLE_ASESOR]);

        $this->actingAs($asesor)->get('/master/proses')->assertForbidden();
    }

    /** VPP01 - VPP02: tampil dan cari process */
    public function test_view_dan_cari_process(): void
    {
        $this->get('/master/proses')->assertSee('Incident Management')->assertSee('Access Management');

        $this->get('/master/proses?q=Incident')
            ->assertSee('Incident Management')
            ->assertDontSee('Access Management');
    }

    /** CRP02 - CRP03: tambah process valid dan nama kosong */
    public function test_create_process(): void
    {
        $this->post('/master/proses', ['domain_code' => 'SO', 'code' => 'sl', 'name' => 'Service Level'])
            ->assertRedirect('/master/proses')
            ->assertSessionHas('success');
        $this->assertDatabaseHas('itil_processes', ['code' => 'SL', 'name' => 'Service Level']);

        $this->post('/master/proses', ['domain_code' => 'SO', 'code' => 'XX', 'name' => ''])
            ->assertSessionHasErrors('name');
    }

    /** UPP02 - UPP03: ubah process valid dan nama kosong */
    public function test_update_process(): void
    {
        $this->put('/master/proses/EM', ['domain_code' => 'SO', 'code' => 'EM', 'name' => 'Event Management Baru'])
            ->assertSessionHas('success');
        $this->assertSame('Event Management Baru', ItilProcess::find('EM')->name);

        $this->put('/master/proses/EM', ['domain_code' => 'SO', 'code' => 'EM', 'name' => ''])
            ->assertSessionHasErrors('name');
    }

    public function test_process_yang_punya_pertanyaan_tidak_bisa_dihapus(): void
    {
        $this->delete('/master/proses/IM')->assertSessionHas('error');
        $this->assertNotNull(ItilProcess::find('IM'));
    }

    /** VSQ01 + filter */
    public function test_view_dan_filter_pertanyaan(): void
    {
        $this->get('/master/pertanyaan')->assertSee('Total 315 pertanyaan');
        $this->get('/master/pertanyaan?proses=IM&atribut=PP')->assertSee('Total 6 pertanyaan');
        $this->get('/master/pertanyaan?level=3')->assertSee('Total 189 pertanyaan');
    }

    /** CRQ02 - CRQ06: tambah pertanyaan valid dan kolom kosong */
    public function test_create_pertanyaan(): void
    {
        $this->post('/master/pertanyaan', ['process_code' => 'IM', 'attribute_code' => 'PP', 'question_text' => 'Pertanyaan uji'])
            ->assertSessionHas('success');
        // Nomor otomatis di urutan terakhir (IM - PP sudah punya 6 pertanyaan)
        $this->assertDatabaseHas('itil_questions', ['process_code' => 'IM', 'attribute_code' => 'PP', 'number' => 7, 'question_text' => 'Pertanyaan uji']);

        $this->post('/master/pertanyaan', ['process_code' => 'IM', 'attribute_code' => '', 'question_text' => 'Pertanyaan'])
            ->assertSessionHasErrors('attribute_code');
        $this->post('/master/pertanyaan', ['process_code' => '', 'attribute_code' => 'PP', 'question_text' => 'Pertanyaan'])
            ->assertSessionHasErrors('process_code');
        $this->post('/master/pertanyaan', ['process_code' => 'IM', 'attribute_code' => 'PP', 'question_text' => ''])
            ->assertSessionHasErrors('question_text');
        $this->post('/master/pertanyaan', [])
            ->assertSessionHasErrors(['process_code', 'attribute_code', 'question_text']);
    }

    /** UPQ02 - UPQ05: ubah pertanyaan valid dan teks kosong */
    public function test_update_pertanyaan(): void
    {
        $question = Question::where('process_code', 'IM')->where('attribute_code', 'PP')->where('number', 1)->first();

        $this->put("/master/pertanyaan/{$question->id}", [
            'process_code' => 'IM', 'attribute_code' => 'PP', 'number' => 1, 'question_text' => 'Teks baru',
        ])->assertSessionHas('success');
        $this->assertSame('Teks baru', $question->fresh()->question_text);

        $this->put("/master/pertanyaan/{$question->id}", ['process_code' => 'IM', 'attribute_code' => 'PP', 'question_text' => ''])
            ->assertSessionHasErrors('question_text');
    }

    public function test_hapus_pertanyaan_merapikan_nomor(): void
    {
        $first = Question::where('process_code', 'IM')->where('attribute_code', 'PP')->where('number', 1)->first();

        $this->delete("/master/pertanyaan/{$first->id}")->assertSessionHas('success');

        $numbers = Question::where('process_code', 'IM')->where('attribute_code', 'PP')->orderBy('number')->pluck('number')->all();
        $this->assertSame([1, 2, 3, 4, 5], $numbers);
    }

    public function test_create_dan_update_atribut(): void
    {
        $this->post('/master/atribut', ['code' => 'XTest', 'name' => 'Atribut Uji', 'level' => 4, 'sort_order' => 3])
            ->assertSessionHas('success');
        $this->assertDatabaseHas('process_attributes', ['code' => 'XTest', 'level' => 4]);

        $this->put('/master/atribut/XTest', ['code' => 'XTest', 'name' => 'Atribut Uji 2', 'level' => 9, 'sort_order' => 3])
            ->assertSessionHasErrors('level');

        $this->delete('/master/atribut/PP')->assertSessionHas('error'); // masih punya pertanyaan
        $this->assertNotNull(ProcessAttribute::find('PP'));
    }

    public function test_create_domain_dan_pengguna(): void
    {
        $this->post('/master/domain', ['code' => 'st', 'name' => 'Service Transition'])->assertSessionHas('success');
        $this->assertDatabaseHas('itil_domains', ['code' => 'ST']);

        $this->post('/master/pengguna', [
            'name' => 'Asesor Baru', 'email' => 'asesor-baru@test.local', 'role' => 'asesor',
            'password' => 'rahasia123', 'password_confirmation' => 'rahasia123',
        ])->assertSessionHas('success');
        $this->assertDatabaseHas('users', ['email' => 'asesor-baru@test.local', 'role' => 'asesor']);

        // Admin tidak bisa menghapus akunnya sendiri
        $this->delete("/master/pengguna/{$this->admin->id}")->assertSessionHas('error');
    }
}

<?php

namespace Tests\Feature\Dinkes;

use App\Http\Controllers\Dinkes\ManageOperatorsController;
use App\Models\Kecamatan;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ManageOperatorsControllerTest extends TestCase
{
    use DatabaseTransactions;
    protected User $adminDinkes;
    protected function setUp(): void
    {
        parent::setUp();

        $this->adminDinkes = User::create([
            'username' => 'admindinkes_utama',
            'password' => bcrypt('password'),
            'role' => 'admin_dinkes'
        ]);
    }

    public function test_halaman_index_menampilkan_daftar_operator_kecamatan()
    {
        $this->actingAs($this->adminDinkes);

        $kecamatan = Kecamatan::create([
            'nama_kecamatan' => 'Pancoran Mas'
        ]);

        User::create([
            'username' => 'adminkec_pancoranmas',
            'password' => bcrypt('password'),
            'role' => 'admin_kecamatan',
            'id_kecamatan' => $kecamatan->id_kecamatan,
            'akses_tipe_usaha' => 'sppg'
        ]);

        $response = $this->get(action([ManageOperatorsController::class, 'index']));

        $response->assertStatus(200);
        $response->assertViewIs('dinkes.kelola');
        $response->assertViewHasAll(['operators', 'kecamatanOptions', 'selectedKecamatanId', 'q']);
    }

    public function test_halaman_index_dapat_mencari_dan_memfilter_data()
    {
        $this->actingAs($this->adminDinkes);

        $kecamatan1 = Kecamatan::create(['nama_kecamatan' => 'Beji']);
        $kecamatan2 = Kecamatan::create(['nama_kecamatan' => 'Cipayung']);

        User::create([
            'username' => 'adminkec_beji',
            'password' => bcrypt('password'),
            'role' => 'admin_kecamatan',
            'id_kecamatan' => $kecamatan1->id_kecamatan,
            'akses_tipe_usaha' => 'tpp'
        ]);

        User::create([
            'username' => 'adminkec_cipayung',
            'password' => bcrypt('password'),
            'role' => 'admin_kecamatan',
            'id_kecamatan' => $kecamatan2->id_kecamatan,
            'akses_tipe_usaha' => 'kantin'
        ]);

        $response = $this->get(action([ManageOperatorsController::class, 'index'], [
            'q' => 'adminkec_beji',
            'kecamatan_id' => $kecamatan1->id_kecamatan
        ]));

        $response->assertStatus(200);
        
        $operators = $response->viewData('operators');
        $this->assertCount(1, $operators);
        $this->assertEquals('adminkec_beji', $operators->first()->username);
    }

    public function test_store_berhasil_menyimpan_operator_baru()
    {
        $this->actingAs($this->adminDinkes);

        $kecamatan = Kecamatan::create([
            'nama_kecamatan' => 'Sukmajaya'
        ]);

        $response = $this->post(action([ManageOperatorsController::class, 'store']), [
            'username' => 'adminkec_sukmajaya',
            'password' => 'password123',
            'id_kecamatan' => $kecamatan->id_kecamatan,
            'akses_tipe_usaha' => 'dam'
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'username' => 'adminkec_sukmajaya',
            'role' => 'admin_kecamatan',
            'id_kecamatan' => $kecamatan->id_kecamatan,
            'akses_tipe_usaha' => 'dam'
        ]);
    }

    public function test_store_gagal_jika_username_sudah_terdaftar()
    {
        $this->actingAs($this->adminDinkes);

        $kecamatan = Kecamatan::create([
            'nama_kecamatan' => 'Cilodong'
        ]);

        User::create([
            'username' => 'adminkec_cilodong',
            'password' => bcrypt('password'),
            'role' => 'admin_kecamatan',
            'id_kecamatan' => $kecamatan->id_kecamatan,
            'akses_tipe_usaha' => 'sppg'
        ]);

        $response = $this->post(action([ManageOperatorsController::class, 'store']), [
            'username' => 'adminkec_cilodong',
            'password' => 'password123',
            'id_kecamatan' => $kecamatan->id_kecamatan,
            'akses_tipe_usaha' => 'tpp'
        ]);

        $response->assertSessionHasErrors(['username']);
    }

    public function test_update_berhasil_mengubah_data_operator()
    {
        $this->actingAs($this->adminDinkes);

        $kecamatanLama = Kecamatan::create(['nama_kecamatan' => 'Limo']);
        $kecamatanBaru = Kecamatan::create(['nama_kecamatan' => 'Cinere']);

        $operator = User::create([
            'username' => 'adminkec_limo',
            'password' => bcrypt('passwordlama'),
            'role' => 'admin_kecamatan',
            'id_kecamatan' => $kecamatanLama->id_kecamatan,
            'akses_tipe_usaha' => 'sppg'
        ]);

        // Menggunakan id_users secara eksplisit
        $response = $this->put(action([ManageOperatorsController::class, 'update'], ['operator' => $operator->id_users]), [
            'id_kecamatan' => $kecamatanBaru->id_kecamatan,
            'akses_tipe_usaha' => 'tpp',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'username' => 'adminkec_limo',
            'id_kecamatan' => $kecamatanBaru->id_kecamatan,
            'akses_tipe_usaha' => 'tpp'
        ]);
    }

    public function test_destroy_berhasil_menghapus_operator()
    {
        $this->actingAs($this->adminDinkes);

        $kecamatan = Kecamatan::create(['nama_kecamatan' => 'Bojongsari']);

        $operator = User::create([
            'username' => 'adminkec_bojongsari',
            'password' => bcrypt('password'),
            'role' => 'admin_kecamatan',
            'id_kecamatan' => $kecamatan->id_kecamatan,
            'akses_tipe_usaha' => 'sppg'
        ]);

        // Menggunakan id_users secara eksplisit
        $response = $this->delete(action([ManageOperatorsController::class, 'destroy'], ['operator' => $operator->id_users]));

        $response->assertStatus(302);
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('users', [
            'username' => 'adminkec_bojongsari'
        ]);
    }
}
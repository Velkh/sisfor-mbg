<?php

namespace Tests\Feature\Dinkes;

use App\Http\Controllers\Dinkes\ReportsController;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Puskesmas;
use App\Models\UnitUsaha;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ReportsControllerTest extends TestCase
{
    use DatabaseTransactions; 

    protected User $adminDinkes;
    protected Kecamatan $kecamatan;
    protected Kelurahan $kelurahan;
    protected Puskesmas $puskesmas;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminDinkes = User::create([
            'username' => 'admindinkes_reports',
            'password' => bcrypt('password'),
            'role' => 'admin_dinkes'
        ]);

        $this->kecamatan = Kecamatan::create(['nama_kecamatan' => 'Cimanggis']);
        
        $this->kelurahan = Kelurahan::create([
            'id_kecamatan' => $this->kecamatan->id_kecamatan,
            'nama_kelurahan' => 'Tugu'
        ]);
        
        $this->puskesmas = Puskesmas::create(['nama_puskesmas' => 'Puskesmas Tugu']);
    }

    public function test_halaman_index_berhasil_dimuat_dengan_kalkulasi_statistik_yang_benar()
    {
        $this->actingAs($this->adminDinkes);

        $unit = UnitUsaha::create([
            'id_kecamatan' => $this->kecamatan->id_kecamatan,
            'id_kelurahan' => $this->kelurahan->id_kelurahan,
            'id_puskesmas' => $this->puskesmas->id_puskesmas,
            'jenis_usaha' => 'sppg',
            'nama_unit_usaha' => 'SPPG Statistik Test',
            'nama_pemilik' => 'Bapak Budi',
            'alamat' => 'Jl. Statistik No. 1'
        ]);

        // Menyisipkan data sasaran manfaat secara manual via DB Builder
        // Sesuaikan nama tabel 'sasaran_manfaats' jika di databasemu berbeda
        DB::table('sasaran_manfaat')->insert([
            'id_unit_usaha' => $unit->id_unit_usaha,
            'kategori' => 'Sekolah',
            'jumlah_siswa' => 50,
            'jumlah_bumil' => 0,
            'jumlah_busui' => 0,
            'jumlah_balita' => 0,
            'jumlah_jiwa' => 5, // Total penerima = 55
        ]);

        $response = $this->get(action([ReportsController::class, 'index']));

        $response->assertStatus(200);
        $response->assertViewIs('dinkes.laporan');
        $response->assertViewHasAll(['reports', 'stats', 'filters', 'kecamatans']);
    }

    public function test_dapat_memfilter_laporan_berdasarkan_pencarian_nama_unit_dan_pemilik()
    {
        $this->actingAs($this->adminDinkes);

        UnitUsaha::create([
            'id_kecamatan' => $this->kecamatan->id_kecamatan,
            'id_kelurahan' => $this->kelurahan->id_kelurahan,
            'id_puskesmas' => $this->puskesmas->id_puskesmas,
            'jenis_usaha' => 'tpp',
            'nama_unit_usaha' => 'TPP Target Filter',
            'nama_pemilik' => 'Ibu Rahma',
            'alamat' => 'Jl. Filter'
        ]);

        UnitUsaha::create([
            'id_kecamatan' => $this->kecamatan->id_kecamatan,
            'id_kelurahan' => $this->kelurahan->id_kelurahan,
            'id_puskesmas' => $this->puskesmas->id_puskesmas,
            'jenis_usaha' => 'kantin',
            'nama_unit_usaha' => 'Kantin Biasa',
            'nama_pemilik' => 'Pak Joko',
            'alamat' => 'Jl. Biasa'
        ]);

        $response = $this->get(action([ReportsController::class, 'index'], [
            'q' => 'Target Filter'
        ]));

        $response->assertStatus(200);
        
        $reports = $response->viewData('reports');
        $this->assertCount(1, $reports);
        $this->assertEquals('TPP Target Filter', $reports->first()->nama_unit_usaha);
    }

    public function test_dapat_memfilter_laporan_berdasarkan_kecamatan()
    {
        $this->actingAs($this->adminDinkes);

        $kecamatanLain = Kecamatan::create(['nama_kecamatan' => 'Tapos']);

        UnitUsaha::create([
            'id_kecamatan' => $this->kecamatan->id_kecamatan,
            'id_kelurahan' => $this->kelurahan->id_kelurahan,
            'id_puskesmas' => $this->puskesmas->id_puskesmas,
            'jenis_usaha' => 'dam',
            'nama_unit_usaha' => 'DAM Cimanggis',
            'nama_pemilik' => 'Ahmad',
            'alamat' => 'Jl. Cimanggis'
        ]);

        $response = $this->get(action([ReportsController::class, 'index'], [
            'id_kecamatan' => $kecamatanLain->id_kecamatan
        ]));

        $response->assertStatus(200);
        
        // Memastikan DAM Cimanggis tidak muncul saat memfilter Kecamatan Tapos
        $reports = $response->viewData('reports');
        $this->assertTrue($reports->where('nama_unit_usaha', 'DAM Cimanggis')->isEmpty());
    }

    public function test_dapat_memfilter_laporan_berdasarkan_jenis_sasaran()
    {
        $this->actingAs($this->adminDinkes);

        $unitSekolah = UnitUsaha::create([
            'id_kecamatan' => $this->kecamatan->id_kecamatan,
            'id_kelurahan' => $this->kelurahan->id_kelurahan,
            'id_puskesmas' => $this->puskesmas->id_puskesmas,
            'jenis_usaha' => 'sppg',
            'nama_unit_usaha' => 'SPPG Sekolah',
            'nama_pemilik' => 'Budi',
            'alamat' => 'Jl. Sekolah'
        ]);

        DB::table('sasaran_manfaat')->insert([
            'id_unit_usaha' => $unitSekolah->id_unit_usaha,
            'kategori' => 'Sekolah',
            'jumlah_siswa' => 100,
            'jumlah_bumil' => 0,
            'jumlah_busui' => 0,
            'jumlah_balita' => 0,
            'jumlah_jiwa' => 10,
        ]);

        $response = $this->get(action([ReportsController::class, 'index'], [
            'jenis_sasaran' => 'Sekolah'
        ]));

        $response->assertStatus(200);
        
        $reports = $response->viewData('reports');
        $this->assertFalse($reports->where('nama_unit_usaha', 'SPPG Sekolah')->isEmpty());
    }
public function test_dapat_memfilter_laporan_berdasarkan_kategori_jenis_usaha()
    {
        $this->actingAs($this->adminDinkes);

        UnitUsaha::create([
            'id_kecamatan' => $this->kecamatan->id_kecamatan,
            'id_kelurahan' => $this->kelurahan->id_kelurahan,
            'id_puskesmas' => $this->puskesmas->id_puskesmas,
            'jenis_usaha' => 'sppg',
            'nama_unit_usaha' => 'Target SPPG',
            'nama_pemilik' => 'Pak Budi',
            'alamat' => 'Jl. SPPG'
        ]);

        UnitUsaha::create([
            'id_kecamatan' => $this->kecamatan->id_kecamatan,
            'id_kelurahan' => $this->kelurahan->id_kelurahan,
            'id_puskesmas' => $this->puskesmas->id_puskesmas,
            'jenis_usaha' => 'kantin',
            'nama_unit_usaha' => 'Target Kantin',
            'nama_pemilik' => 'Bu Ani',
            'alamat' => 'Jl. Kantin'
        ]);

        $response = $this->get(action([ReportsController::class, 'index'], [
            'q' => 'Target',     
            'kategori' => 'sppg' 
        ]));

        $response->assertStatus(200); 

        $reports = $response->viewData('reports');
        
        $this->assertCount(1, $reports);
        $this->assertEquals('Target SPPG', $reports->first()->nama_unit_usaha);
    }

    public function test_menolak_akses_jika_pengguna_belum_login()
    {
        $response = $this->get(action([ReportsController::class, 'index']));

        $response->assertStatus(302);
        $response->assertRedirect('/login'); 
    }
    public function test_menolak_akses_jika_admin_kecamatan_mencoba_masuk()
    {
        $adminKecamatan = User::create([
            'username' => 'adminkecamatan_tester',
            'password' => bcrypt('password'),
            'role' => 'admin_kecamatan',
            'id_kecamatan' => $this->kecamatan->id_kecamatan,
            'akses_tipe_usaha' => 'sppg'
        ]);

        $this->actingAs($adminKecamatan);

        $response = $this->get(action([ReportsController::class, 'index']));

        $response->assertStatus(302);
    }

    public function test_mengembalikan_error_validasi_jika_filter_kategori_tidak_valid()
    {
        $this->actingAs($this->adminDinkes);

        $response = $this->get(action([ReportsController::class, 'index'], [
            'kategori' => 'kategori_palsu_bikinan_hacker'
        ]));

        $response->assertSessionHasErrors('kategori');
    }

    public function test_mengembalikan_error_validasi_jika_filter_jenis_sasaran_tidak_valid()
    {
        $this->actingAs($this->adminDinkes);

        $response = $this->get(action([ReportsController::class, 'index'], [
            'jenis_sasaran' => 'PasarMalam'
        ]));

        $response->assertSessionHasErrors('jenis_sasaran');
    }

    public function test_mengembalikan_error_validasi_jika_id_kecamatan_bukan_angka()
    {
        $this->actingAs($this->adminDinkes);

        $response = $this->get(action([ReportsController::class, 'index'], [
            'id_kecamatan' => 'bukan_angka'
        ]));

        $response->assertSessionHasErrors('id_kecamatan');
    }
}
<?php

namespace Tests\Feature\Dinkes;

use App\Http\Controllers\Dinkes\DashboardController;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Puskesmas;
use App\Models\UnitUsaha;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function test_unauthenticated_user_cannot_access_dashboard()
    {
        $response = $this->get(action([DashboardController::class, 'index']));

        $response->assertStatus(302);
    }

    public function test_index_displays_dashboard_with_correct_data()
    {
        $adminDinkes = User::create([
            'username' => 'admindinkes',
            'password' => bcrypt('password'),
            'role' => 'admin_dinkes'
        ]);
        
        $this->actingAs($adminDinkes);

        // 1. AMBIL JUMLAH AWAL DARI DATABASE ASLI
        $initialResponse = $this->get(action([DashboardController::class, 'index']));
        $awalUnit = $initialResponse->viewData('totalUnitUsaha');
        $awalLulus = $initialResponse->viewData('totalLulusIkl');
        $awalSlhs = $initialResponse->viewData('totalBerslhs');
        $awalPenerima = $initialResponse->viewData('totalPenerima');

        // 2. MASUKKAN 1 DATA TEST YANG VALID
        $kecamatan = Kecamatan::create(['nama_kecamatan' => 'Pancoran Mas']);
        $kelurahan = Kelurahan::create(['id_kecamatan' => $kecamatan->id_kecamatan, 'nama_kelurahan' => 'Depok']);
        $puskesmas = Puskesmas::create(['nama_puskesmas' => 'Puskesmas Pancoran Mas']);

        $unitUsaha = UnitUsaha::create([
            'id_kecamatan' => $kecamatan->id_kecamatan,
            'id_kelurahan' => $kelurahan->id_kelurahan,
            'id_puskesmas' => $puskesmas->id_puskesmas,
            'jenis_usaha' => 'sppg',
            'nama_unit_usaha' => 'SPPG Pancoran Mas',
            'nama_pemilik' => 'Budi Santoso',
            'alamat' => 'Jl. Raya Margonda No. 1',
            'jumlah_pegawai' => 10,
            'jumlah_penjamah_terlatih' => 5
        ]);

        DB::table('laporan_slhs')->insert([
            'id_unit_usaha' => $unitUsaha->id_unit_usaha,
            'status_ikl' => 'selesai',
            'hasil_ikl' => 'memenuhi',
            'nilai_ikl' => 85,
            'status_slhs' => 'selesai',
            'tgl_berakhir_slhs' => now()->addDays(10),
        ]);

        DB::table('sasaran_manfaat')->insert([
            'id_unit_usaha' => $unitUsaha->id_unit_usaha,
            'jumlah_siswa' => 100,
            'jumlah_bumil' => 10,
            'jumlah_busui' => 5,
            'jumlah_balita' => 15,
            'jumlah_jiwa' => 20, // Total 150
        ]);

        // 3. AMBIL JUMLAH AKHIR LALU BANDINGKAN SELISIHNYA
        $response = $this->get(action([DashboardController::class, 'index']));

        $response->assertStatus(200);
        
        $this->assertEquals($awalUnit + 1, $response->viewData('totalUnitUsaha'));
        $this->assertEquals($awalLulus + 1, $response->viewData('totalLulusIkl'));
        $this->assertEquals($awalSlhs + 1, $response->viewData('totalBerslhs'));
        $this->assertEquals($awalPenerima + 150, $response->viewData('totalPenerima'));
    }

    public function test_index_does_not_count_invalid_or_rejected_data()
    {
        $adminDinkes = User::create([
            'username' => 'admindinkes_negative',
            'password' => bcrypt('password'),
            'role' => 'admin_dinkes'
        ]);
        
        $this->actingAs($adminDinkes);

        // 1. AMBIL JUMLAH AWAL DARI DATABASE ASLI
        $initialResponse = $this->get(action([DashboardController::class, 'index']));
        $awalUnit = $initialResponse->viewData('totalUnitUsaha');
        $awalLulus = $initialResponse->viewData('totalLulusIkl');
        $awalSlhs = $initialResponse->viewData('totalBerslhs');

        // 2. MASUKKAN 1 DATA TEST YANG GAGAL IKL & BELUM SLHS
        $kecamatan = Kecamatan::create(['nama_kecamatan' => 'Cipayung']);
        $kelurahan = Kelurahan::create(['id_kecamatan' => $kecamatan->id_kecamatan, 'nama_kelurahan' => 'Cipayung Jaya']);
        $puskesmas = Puskesmas::create(['nama_puskesmas' => 'Puskesmas Cipayung']);

        $unitGagal = UnitUsaha::create([
            'id_kecamatan' => $kecamatan->id_kecamatan,
            'id_kelurahan' => $kelurahan->id_kelurahan,
            'id_puskesmas' => $puskesmas->id_puskesmas,
            'jenis_usaha' => 'sppg',
            'nama_unit_usaha' => 'SPPG Gagal IKL',
            'nama_pemilik' => 'Joko',
            'alamat' => 'Jl. Cipayung Raya',
            'jumlah_pegawai' => 5,
            'jumlah_penjamah_terlatih' => 1
        ]);

        DB::table('laporan_slhs')->insert([
            'id_unit_usaha' => $unitGagal->id_unit_usaha,
            'status_ikl' => 'selesai',
            'hasil_ikl' => 'tidak_memenuhi',
            'nilai_ikl' => 60,
            'status_slhs' => 'belum_mengajukan',
            'tgl_berakhir_slhs' => now()->addDays(10),
        ]);

        // 3. PASTIKAN HANYA UNIT USAHA YANG NAMBAH, STATISTIK KELULUSAN TETAP SAMA
        $response = $this->get(action([DashboardController::class, 'index']));

        $response->assertStatus(200);

        $this->assertEquals($awalUnit + 1, $response->viewData('totalUnitUsaha'));
        $this->assertEquals($awalLulus, $response->viewData('totalLulusIkl')); // Tidak bertambah
        $this->assertEquals($awalSlhs, $response->viewData('totalBerslhs')); // Tidak bertambah
    }

    public function test_struktur_variabel_dashboard_valid()
    {
        $adminDinkes = User::create([
            'username' => 'admindinkes_empty',
            'password' => bcrypt('password'),
            'role' => 'admin_dinkes'
        ]);
        
        $this->actingAs($adminDinkes);

        $response = $this->get(action([DashboardController::class, 'index']));

        $response->assertStatus(200);
        $response->assertViewIs('dinkes.index');
        
        // Memastikan tipe datanya tidak error / crash
        $this->assertIsNumeric($response->viewData('totalUnitUsaha'));
        $this->assertIsNumeric($response->viewData('totalLulusIkl'));
        $this->assertIsNumeric($response->viewData('totalPenerima'));
        
        $grafikBulanan = $response->viewData('grafikBulanan');
        $this->assertIsArray($grafikBulanan);
        $this->assertEquals(12, count($grafikBulanan));
    }

    public function test_index_only_shows_active_slhs_jatuh_tempo()
    {
        $adminDinkes = User::create([
            'username' => 'admindinkes_tempo',
            'password' => bcrypt('password'),
            'role' => 'admin_dinkes'
        ]);
        
        $this->actingAs($adminDinkes);

        $kecamatan = Kecamatan::create(['nama_kecamatan' => 'Beji']);
        $kelurahan = Kelurahan::create(['id_kecamatan' => $kecamatan->id_kecamatan, 'nama_kelurahan' => 'Pondok Cina']);
        $puskesmas = Puskesmas::create(['nama_puskesmas' => 'Puskesmas Beji']);

        $unitValid = UnitUsaha::create([
            'id_kecamatan' => $kecamatan->id_kecamatan,
            'id_kelurahan' => $kelurahan->id_kelurahan,
            'id_puskesmas' => $puskesmas->id_puskesmas,
            'jenis_usaha' => 'restoran',
            'nama_unit_usaha' => 'Restoran Beji Valid',
            'nama_pemilik' => 'Andi Wijaya',
            'alamat' => 'Jl. Nusantara No. 10',
            'jumlah_pegawai' => 8,
            'jumlah_penjamah_terlatih' => 4
        ]);

        DB::table('laporan_slhs')->insert([
            'id_unit_usaha' => $unitValid->id_unit_usaha,
            'status_slhs' => 'selesai',
            'tgl_berakhir_slhs' => now()->addDays(5), 
        ]);

        $response = $this->get(action([DashboardController::class, 'index']));

        $response->assertStatus(200);
        
        $slhsJatuhTempo = $response->viewData('slhsJatuhTempo');
        
        // Memastikan data restoran yang baru dimasukkan ikut masuk ke daftar peringatan jatuh tempo
        $this->assertTrue($slhsJatuhTempo->contains('id_unit_usaha', $unitValid->id_unit_usaha));
    }

    public function test_menolak_akses_jika_admin_kecamatan_mencoba_masuk()
    {
        $kecamatanBaru = Kecamatan::create([
            'nama_kecamatan' => 'Limo'
        ]);

        $adminKecamatan = User::create([
            'username' => 'adminkecamatan_dash_tester',
            'password' => bcrypt('password'),
            'role' => 'admin_kecamatan',
            'id_kecamatan' => $kecamatanBaru->id_kecamatan,
            'akses_tipe_usaha' => 'sppg'
        ]);

        $this->actingAs($adminKecamatan);

        $response = $this->get(action([DashboardController::class, 'index']));

        // Memastikan sistem menolak dan melempar kembali (redirect) Admin Kecamatan
        $response->assertStatus(302); 
    }
}
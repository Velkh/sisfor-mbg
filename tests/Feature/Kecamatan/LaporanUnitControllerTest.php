<?php

namespace Tests\Feature\Kecamatan;

use App\Models\FotoUnit;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Puskesmas;
use App\Models\UnitUsaha;
use App\Models\LaporanSlhs;
use App\Models\SasaranManfaat;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LaporanUnitControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected User $adminKecamatan;
    protected Kecamatan $kecamatan;
    protected Kelurahan $kelurahan;
    protected Puskesmas $puskesmas;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Setup Data Wilayah
        $this->kecamatan = Kecamatan::create(['nama_kecamatan' => 'Cimanggis']);
        
        $this->kelurahan = Kelurahan::create([
            'id_kecamatan' => $this->kecamatan->id_kecamatan,
            'nama_kelurahan' => 'Tugu'
        ]);
        
        $this->puskesmas = Puskesmas::create(['nama_puskesmas' => 'Puskesmas Cimanggis']);

        // 2. Setup Akun Admin Kecamatan dengan Atribut Akses Spesifik Tipe Usaha TPP
        $this->adminKecamatan = User::create([
            'username' => 'adminkec_cimanggis_sppg',
            'password' => bcrypt('password'),
            'role' => 'admin_kecamatan',
            'id_kecamatan' => $this->kecamatan->id_kecamatan,
            'akses_tipe_usaha' => 'sppg' // Mengatur akses spesifik per jenis usaha TPP di kecamatan (restoran, catering, sppg, dam, kantin)
        ]);
    }

    public function test_index_menampilkan_data_hanya_untuk_kecamatan_dan_tipe_usahanya()
    {
        $this->actingAs($this->adminKecamatan);

        // Data Valid (Sesuai wilayah kecamatan dan tipe hak akses TPP milik Admin)
        UnitUsaha::create([
            'id_kecamatan' => $this->adminKecamatan->id_kecamatan,
            'id_kelurahan' => $this->kelurahan->id_kelurahan,
            'id_puskesmas' => $this->puskesmas->id_puskesmas,
            'jenis_usaha' => 'sppg',
            'nama_unit_usaha' => 'TPP SPPG Cimanggis Valid',
            'nama_pemilik' => 'Bapak Valid',
            'alamat' => 'Jl. Valid'
        ]);

        // Data Invalid (Beda Kecamatan)
        $kecamatanLain = Kecamatan::create(['nama_kecamatan' => 'Beji']);
        UnitUsaha::create([
            'id_kecamatan' => $kecamatanLain->id_kecamatan,
            'id_kelurahan' => $this->kelurahan->id_kelurahan,
            'id_puskesmas' => $this->puskesmas->id_puskesmas,
            'jenis_usaha' => 'sppg',
            'nama_unit_usaha' => 'TPP SPPG Beji Invalid',
            'nama_pemilik' => 'Bapak Invalid',
            'alamat' => 'Jl. Invalid'
        ]);

        // Data Invalid (Beda Jenis Kategori TPP usaha di dalam kecamatan)
        UnitUsaha::create([
            'id_kecamatan' => $this->adminKecamatan->id_kecamatan,
            'id_kelurahan' => $this->kelurahan->id_kelurahan,
            'id_puskesmas' => $this->puskesmas->id_puskesmas,
            'jenis_usaha' => 'restoran',
            'nama_unit_usaha' => 'TPP Restoran Cimanggis Invalid',
            'nama_pemilik' => 'Bapak Resto',
            'alamat' => 'Jl. Resto'
        ]);

        $response = $this->get(route('kecamatan.laporan-unit.index'));

        $response->assertStatus(200);
        
        $items = $response->viewData('items');
        
        // Memastikan HANYA entitas data yang valid yang berhasil ditarik oleh filter query kedaerahan
        $this->assertTrue($items->contains('nama_unit_usaha', 'TPP SPPG Cimanggis Valid'));
        $this->assertFalse($items->contains('nama_unit_usaha', 'TPP SPPG Beji Invalid'));
        $this->assertFalse($items->contains('nama_unit_usaha', 'TPP Restoran Cimanggis Invalid'));
    }

    public function test_halaman_create_menampilkan_kelurahan_sesuai_kecamatan_admin()
    {
        $this->actingAs($this->adminKecamatan);

        $response = $this->get(route('kecamatan.laporan-unit.create'));

        $response->assertStatus(200);
        $response->assertViewIs('kecamatan.reporting.form');

        $kelurahans = $response->viewData('kelurahans');
        $this->assertTrue($kelurahans->contains('nama_kelurahan', 'Tugu'));
    }

    public function test_berhasil_menyimpan_data_tanpa_mengirim_id_kecamatan()
    {
        $this->actingAs($this->adminKecamatan);
        
        Storage::fake('public');
        $file = UploadedFile::fake()->image('dokumentasi_tpp.jpg');

        // Atribut id_kecamatan dan jenis_usaha TIDAK DIKIRIM di payload form request
        // Karena sistem controller akan mengisinya secara otomatis dari session autentikasi Auth::user()
        $payload = [
            'nama_unit_usaha' => 'TPP SPPG Baru Cimanggis',
            'nama_pemilik' => 'Ibu Baru',
            'alamat' => 'Jl. Baru No. 1',
            'jumlah_pegawai' => 4,
            'jumlah_penjamah_terlatih' => 2,
            'id_kelurahan' => $this->kelurahan->id_kelurahan,
            'id_puskesmas' => $this->puskesmas->id_puskesmas,
            'nilai_ikl' => 85, // Batas aman otomatis memenuhi syarat (>= 80)
            'foto_unit_usaha' => [$file], 
            'sasaran' => [
                [
                    'kategori' => 'Sekolah',
                    'tipe_instansi' => 'SD',
                    'nama_instansi' => 'SDN Tugu 1',
                    'status' => 'negeri',
                    'jumlah_siswa' => 100
                ]
            ]
        ];

        $response = $this->post(route('kecamatan.laporan-unit.store'), $payload);

        $response->assertRedirect(route('kecamatan.laporan-unit.index'));
        $response->assertSessionHas('success');

        // Verifikasi Integritas Data Unit Usaha TPP pada Database
        $this->assertDatabaseHas('unit_usahas', [
            'nama_unit_usaha' => 'TPP SPPG Baru Cimanggis',
            'id_kecamatan' => $this->adminKecamatan->id_kecamatan, 
            'jenis_usaha' => 'sppg' 
        ]);

        $unit = UnitUsaha::where('nama_unit_usaha', 'TPP SPPG Baru Cimanggis')->first();

        // Pengecekan relasi penyimpanan file foto dan laporan otomatis SLHS
        $this->assertDatabaseHas((new FotoUnit)->getTable(), ['id_unit_usaha' => $unit->id_unit_usaha]);
        
        $this->assertDatabaseHas('laporan_slhs', [
            'id_unit_usaha' => $unit->id_unit_usaha,
            'status_ikl' => 'selesai',
            'hasil_ikl' => 'memenuhi',
            'nilai_ikl' => 85
        ]);
        
        $this->assertDatabaseHas('sasaran_manfaat', [
            'id_unit_usaha' => $unit->id_unit_usaha,
            'nama_instansi' => 'SDN Tugu 1'
        ]);
    }

    public function test_show_menampilkan_detail_jika_akses_valid()
    {
        $this->actingAs($this->adminKecamatan);

        $unit = UnitUsaha::create([
            'id_kecamatan' => $this->adminKecamatan->id_kecamatan,
            'id_kelurahan' => $this->kelurahan->id_kelurahan,
            'id_puskesmas' => $this->puskesmas->id_puskesmas,
            'jenis_usaha' => 'sppg',
            'nama_unit_usaha' => 'TPP Target Detail',
            'nama_pemilik' => 'Pemilik Target',
            'alamat' => 'Jl. Target'
        ]);

        $response = $this->get(route('kecamatan.laporan-unit.show', $unit->id_unit_usaha));

        $response->assertStatus(200);
        $response->assertViewHas('unit');
    }

    public function test_api_search_ikl_mengembalikan_json_yang_sesuai()
    {
        $this->actingAs($this->adminKecamatan);

        Http::fake([
            '*/tpp*' => Http::response([
                'data' => [
                    [
                        'id' => 999,
                        'nama' => 'TPP API JSON',
                        'pengelola' => 'Bapak API',
                        'skor' => 90,
                        'alamat' => 'Jl. JSON'
                    ]
                ]
            ], 200)
        ]);

        $response = $this->get(route('kecamatan.laporan-unit.ikl.search', ['search' => 'TPP API JSON']));

        $response->assertStatus(200);
        
        $response->assertJson([
            'success' => true,
            'data' => [
                [
                    'id' => 999,
                    'nama' => 'TPP API JSON',
                    'pengelola' => 'Bapak API',
                    'nilai_ikl' => 90,
                    'hasil_ikl' => 'memenuhi',
                    'alamat' => 'Jl. JSON'
                ]
            ]
        ]);
    }

    // --- NEGATIVE TESTS (PENGUJIAN SKENARIO JALUR GAGAL) ---
    public function test_search_ikl_mengembalikan_json_503_saat_api_dsimfoniku_down()
{
    $this->actingAs($this->adminKecamatan);

    // 1. Simulasi Bencana: API Dsimfoniku terputus / error sehingga masuk ke Exception Throwable
    Http::fake([
        '*/tpp*' => function () {
            throw new \Exception('Simulasi Kegagalan Jaringan');
        }
    ]);

    // 2. Eksekusi
    $response = $this->getJson(route('kecamatan.laporan-unit.ikl.search', ['search' => 'Hokben']));

    // 3. Debug: tampilkan status & body walau test PASS (jalankan dengan --debug)
    fwrite(STDOUT, "\n[DOWN] Status: " . $response->status() . " | Body: " . $response->getContent() . "\n");

    $response->assertStatus(503);
    $response->assertJsonPath('success', false);
    $response->assertJsonPath('message', 'API eksternal sedang tidak tersedia.');
    $response->assertJsonPath('data', []);
}

public function test_search_ikl_mengembalikan_json_503_saat_api_dsimfoniku_response_error_status()
{
    $this->actingAs($this->adminKecamatan);

    // 1. Simulasi API merespons TAPI dengan status error (bukan exception jaringan)
    Http::fake([
        '*/tpp*' => Http::response(['message' => 'Internal Server Error'], 500)
    ]);

    // 2. Eksekusi
    $response = $this->getJson(route('kecamatan.laporan-unit.ikl.search', ['search' => 'Hokben']));

    fwrite(STDOUT, "\n[ERROR STATUS] Status: " . $response->status() . " | Body: " . $response->getContent() . "\n");

    // 3. Assert: sistem tetap menangani dengan graceful degradation yang sama
    $response->assertStatus(503);
    $response->assertJsonPath('success', false);
    $response->assertJsonPath('message', 'API eksternal sedang tidak tersedia.');
    $response->assertJsonPath('data', []);
}

public function test_search_ikl_mengembalikan_json_503_saat_api_dsimfoniku_timeout()
{
    $this->actingAs($this->adminKecamatan);

    // 1. Simulasi API terlalu lama merespons (timeout)
    Http::fake([
        '*/tpp*' => function () {
            throw new \Illuminate\Http\Client\ConnectionException('Simulasi Timeout Koneksi');
        }
    ]);

    // 2. Eksekusi
    $response = $this->getJson(route('kecamatan.laporan-unit.ikl.search', ['search' => 'Hokben']));

    fwrite(STDOUT, "\n[TIMEOUT] Status: " . $response->status() . " | Body: " . $response->getContent() . "\n");

    $response->assertStatus(503);
    $response->assertJsonPath('success', false);
    $response->assertJsonPath('message', 'API eksternal sedang tidak tersedia.');
    $response->assertJsonPath('data', []);
}

    public function test_menolak_akses_selain_admin_kecamatan()
    {
        // Simulasi Aktor Admin Dinkes mencoba memanipulasi rute data milik kecamatan
        $adminDinkes = User::create([
            'username' => 'admindinkes_penyusup',
            'password' => bcrypt('password'),
            'role' => 'admin_dinkes'
        ]);

        $this->actingAs($adminDinkes);

        $response = $this->get(route('kecamatan.laporan-unit.index'));

        // Middleware memantulkan request via redirect session (302) atau abort 403 Forbidden
        $response->assertStatus(302);
    }

    public function test_show_menolak_akses_jika_berbeda_kecamatan()
    {
        $this->actingAs($this->adminKecamatan);

        $kecamatanLain = Kecamatan::create(['nama_kecamatan' => 'Pancoran Mas']);
        
        $unitLain = UnitUsaha::create([
            'id_kecamatan' => $kecamatanLain->id_kecamatan,
            'id_kelurahan' => $this->kelurahan->id_kelurahan,
            'id_puskesmas' => $this->puskesmas->id_puskesmas,
            'jenis_usaha' => 'sppg',
            'nama_unit_usaha' => 'TPP Punya Kecamatan Lain',
            'nama_pemilik' => 'Pemilik Orang Lain',
            'alamat' => 'Jl. Orang Lain'
        ]);

        // Menguji proteksi isolasi territorial dengan mengakses ID TPP milik wilayah lain
        $response = $this->get(route('kecamatan.laporan-unit.show', $unitLain->id_unit_usaha));

        $response->assertStatus(403);
    }

    public function test_validasi_gagal_jika_memilih_kelurahan_di_luar_kecamatan()
    {
        $this->actingAs($this->adminKecamatan);

        $kecamatanLain = Kecamatan::create(['nama_kecamatan' => 'Sawangan']);
        $kelurahanLain = Kelurahan::create([
            'id_kecamatan' => $kecamatanLain->id_kecamatan,
            'nama_kelurahan' => 'Sawangan Baru'
        ]);

        $payload = [
            'nama_unit_usaha' => 'TPP Manipulatif',
            'nama_pemilik' => 'Ibu X',
            'alamat' => 'Jl. X',
            'id_kelurahan' => $kelurahanLain->id_kelurahan, // Mencoba submit kelurahan di luar yurisdiksi admin
            'id_puskesmas' => $this->puskesmas->id_puskesmas,
        ];

        $response = $this->post(route('kecamatan.laporan-unit.store'), $payload);

        // Validasi form request menolak input data wilayah luar
        $response->assertSessionHasErrors('id_kelurahan');
    }

    public function test_menyimpan_unit_usaha_tanpa_nilai_ikl()
    {
        $this->actingAs($this->adminKecamatan);
        Storage::fake('public');

        $payload = [
            'nama_unit_usaha' => 'TPP Belum Diinspeksi',
            'nama_pemilik' => 'Ibu Belum',
            'alamat' => 'Jl. Belum',
            'id_kelurahan' => $this->kelurahan->id_kelurahan,
            'id_puskesmas' => $this->puskesmas->id_puskesmas,
        ];

        $response = $this->post(route('kecamatan.laporan-unit.store'), $payload);

        $response->assertRedirect(route('kecamatan.laporan-unit.index'));

        $unit = UnitUsaha::where('nama_unit_usaha', 'TPP Belum Diinspeksi')->first();
        
        // Laporan otomatis terbuat namun status tetap 'belum_mengajukan' karena skor IKL kosong
        $this->assertDatabaseHas('laporan_slhs', [
            'id_unit_usaha' => $unit->id_unit_usaha,
            'status_ikl' => 'belum_mengajukan',
            'nilai_ikl' => null,
            'hasil_ikl' => null
        ]);
    }

    public function test_nilai_ikl_dibawah_80_tidak_memenuhi()
    {
        $this->actingAs($this->adminKecamatan);
        Storage::fake('public');

        $payload = [
            'nama_unit_usaha' => 'TPP Skor Rendah',
            'nama_pemilik' => 'Ibu Rendah',
            'alamat' => 'Jl. Rendah',
            'id_kelurahan' => $this->kelurahan->id_kelurahan,
            'id_puskesmas' => $this->puskesmas->id_puskesmas,
            'nilai_ikl' => 65, // di bawah standar minimal kelayakan (< 80)
        ];

        $response = $this->post(route('kecamatan.laporan-unit.store'), $payload);

        $response->assertRedirect(route('kecamatan.laporan-unit.index'));

        $unit = UnitUsaha::where('nama_unit_usaha', 'TPP Skor Rendah')->first();
        
        $this->assertDatabaseHas('laporan_slhs', [
            'id_unit_usaha' => $unit->id_unit_usaha,
            'status_ikl' => 'selesai',
            'nilai_ikl' => 65,
            'hasil_ikl' => 'tidak_memenuhi'
        ]);
    }

    public function test_nilai_ikl_tepat_80_memenuhi()
    {
        $this->actingAs($this->adminKecamatan);
        Storage::fake('public');

        $payload = [
            'nama_unit_usaha' => 'TPP Skor Pas 80',
            'nama_pemilik' => 'Ibu Pas',
            'alamat' => 'Jl. Pas',
            'id_kelurahan' => $this->kelurahan->id_kelurahan,
            'id_puskesmas' => $this->puskesmas->id_puskesmas,
            'nilai_ikl' => 80,
        ];

        $response = $this->post(route('kecamatan.laporan-unit.store'), $payload);

        $response->assertRedirect(route('kecamatan.laporan-unit.index'));

        $unit = UnitUsaha::where('nama_unit_usaha', 'TPP Skor Pas 80')->first();
        
        // Logika bisnis Rule-Based Engine menetapkan nilai ambang batas lulus minimal adalah 80 (>=80)
        $this->assertDatabaseHas('laporan_slhs', [
            'id_unit_usaha' => $unit->id_unit_usaha,
            'status_ikl' => 'selesai',
            'nilai_ikl' => 80,
            'hasil_ikl' => 'memenuhi'
        ]);
    }

    public function test_nilai_ikl_melebihi_maksimal_100_ditolak()
    {
        $this->actingAs($this->adminKecamatan);

        $payload = [
            'nama_unit_usaha' => 'TPP Skor Mustahil',
            'nama_pemilik' => 'Ibu Mustahil',
            'alamat' => 'Jl. Mustahil',
            'id_kelurahan' => $this->kelurahan->id_kelurahan,
            'id_puskesmas' => $this->puskesmas->id_puskesmas,
            'nilai_ikl' => 101, // Melebihi batasan aturan validasi (max:100)
        ];

        $response = $this->post(route('kecamatan.laporan-unit.store'), $payload);

        $response->assertSessionHasErrors('nilai_ikl');
    }

    public function test_nilai_ikl_negatif_ditolak()
    {
        $this->actingAs($this->adminKecamatan);

        $payload = [
            'nama_unit_usaha' => 'TPP Skor Negatif',
            'nama_pemilik' => 'Ibu Minus',
            'alamat' => 'Jl. Minus',
            'id_kelurahan' => $this->kelurahan->id_kelurahan,
            'id_puskesmas' => $this->puskesmas->id_puskesmas,
            'nilai_ikl' => -10, // Melanggar batasan aturan validasi (min:0)
        ];

        $response = $this->post(route('kecamatan.laporan-unit.store'), $payload);

        $response->assertSessionHasErrors('nilai_ikl');
    }
}
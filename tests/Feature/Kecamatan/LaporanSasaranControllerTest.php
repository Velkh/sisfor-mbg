<?php

namespace Tests\Feature\Kecamatan;

use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Puskesmas;
use App\Models\SasaranManfaat;
use App\Models\UnitUsaha;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LaporanSasaranControllerTest extends TestCase
{
    use DatabaseTransactions;

    private $adminKecamatan;
    private $kecamatanUtama;
    private $kelurahan;
    private $puskesmas;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Setup Wilayah & Puskesmas
        $this->kecamatanUtama = Kecamatan::create(['nama_kecamatan' => 'Kecamatan Utama']);
        $kecamatanLain = Kecamatan::create(['nama_kecamatan' => 'Kecamatan Lain']);
        
        $this->kelurahan = Kelurahan::create([
            'id_kecamatan' => $this->kecamatanUtama->id_kecamatan,
            'nama_kelurahan' => 'Kelurahan Utama'
        ]);
        
        $this->puskesmas = Puskesmas::create(['nama_puskesmas' => 'Puskesmas Utama']);

        // 2. Setup User Admin Kecamatan (Hanya akses SPPG)
        $this->adminKecamatan = User::create([
            'username' => 'adminkec_sasaran',
            'password' => Hash::make('password123'),
            'role' => 'admin_kecamatan',
            'id_kecamatan' => $this->kecamatanUtama->id_kecamatan,
            'akses_tipe_usaha' => 'sppg', 
        ]);

        // 3. SEEDING DATA DUMMY
        // Unit 1: Valid SPPG dengan Sasaran Kategori 'Sekolah'
        $unit1 = $this->createUnitUsaha($this->kecamatanUtama->id_kecamatan, 'sppg', 'SPPG Bina Bangsa');
        SasaranManfaat::create([
            'id_unit_usaha' => $unit1->id_unit_usaha,
            'kategori' => 'Sekolah',
            'tipe_instansi' => 'SD',
            'nama_instansi' => 'SDN 1 Utama',
            'jumlah_siswa' => 100,
            'jumlah_bumil' => 0,
            'created_at' => now()->subDays(5), // Tanggal mundur 5 hari untuk test filter
        ]);

        // Unit 2: Valid SPPG dengan Sasaran Kategori 'B3'
        $unit2 = $this->createUnitUsaha($this->kecamatanUtama->id_kecamatan, 'sppg', 'SPPG Sehat Bersama');
        SasaranManfaat::create([
            'id_unit_usaha' => $unit2->id_unit_usaha,
            'kategori' => 'B3',
            'tipe_instansi' => 'Posyandu',
            'nama_instansi' => 'Posyandu Melati',
            'jumlah_siswa' => 0,
            'jumlah_balita' => 20,
            'jumlah_bumil' => 10,
            'created_at' => now(), // Tanggal hari ini
        ]);

        // Unit 3: TPP (TIDAK DIHITUNG karena beda akses jenis usaha)
        $this->createUnitUsaha($this->kecamatanUtama->id_kecamatan, 'tpp', 'TPP Abaikan');

        // Unit 4: SPPG Kecamatan Lain (TIDAK DIHITUNG)
        $this->createUnitUsaha($kecamatanLain->id_kecamatan, 'sppg', 'SPPG Kecamatan Lain');
    }

    private function createUnitUsaha($idKecamatan, $jenisUsaha, $namaUnit)
    {
        return UnitUsaha::create([
            'id_kecamatan' => $idKecamatan,
            'id_kelurahan' => $this->kelurahan->id_kelurahan,
            'id_puskesmas' => $this->puskesmas->id_puskesmas,
            'jenis_usaha' => $jenisUsaha,
            'nama_unit_usaha' => $namaUnit,
            'nama_pemilik' => 'Pemilik ' . $namaUnit,
            'alamat' => 'Alamat ' . $namaUnit,
            'status_aktif' => 1,
        ]);
    }

    /* ==========================================
       SKENARIO PENGUJIAN
       ========================================== */

    public function test_otorisasi_halaman_sasaran(): void
    {
        $responseGuest = $this->get(route('kecamatan.sasaran.index'));
        $responseGuest->assertRedirect(); 

        $adminDinkes = User::create([
            'username' => 'admindinkes_uji_sasaran',
            'password' => Hash::make('pass'),
            'role' => 'admin_dinkes'
        ]);
        $responseDinkes = $this->actingAs($adminDinkes)->get(route('kecamatan.sasaran.index'));
        $responseDinkes->assertRedirect(); // Ditolak oleh middleware
    }

    public function test_menampilkan_data_default_dengan_kalkulasi_yang_benar(): void
    {
        $response = $this->actingAs($this->adminKecamatan)->get(route('kecamatan.sasaran.index'));

        $response->assertStatus(200);
        $response->assertViewIs('kecamatan.laporan');

        // Pastikan view menerima variabel yang dibutuhkan
        $response->assertViewHasAll(['reports', 'stats', 'filters']);

        $stats = $response->original->getData()['stats'];
        
        // Total Unit harus 2 (SPPG Bina Bangsa & SPPG Sehat Bersama)
        $this->assertEquals(2, $stats['total_unit']);
        
        // Total Distribusi (jumlah baris sasaran manfaat) harus 2
        $this->assertEquals(2, $stats['total_distribusi']);
        
        // Total Penerima harus 130 (100 siswa + 20 balita + 10 bumil)
        $this->assertEquals(130, $stats['total_penerima']);
    }

    public function test_filter_berdasarkan_pencarian_teks_q(): void
    {
        // Mencari spesifik nama unit 'Sehat Bersama'
        $response = $this->actingAs($this->adminKecamatan)
            ->get(route('kecamatan.sasaran.index', ['q' => 'Sehat Bersama']));

        $response->assertStatus(200);
        $stats = $response->original->getData()['stats'];
        
        // Hanya 1 unit yang cocok dengan pencarian
        $this->assertEquals(1, $stats['total_unit']);
        
        // Memastikan unit yang tertangkap adalah Unit 2 (30 penerima)
        $this->assertEquals(30, $stats['total_penerima']);
    }

    public function test_filter_berdasarkan_kategori(): void
    {
        // Filter kategori khusus 'Sekolah'
        $response = $this->actingAs($this->adminKecamatan)
            ->get(route('kecamatan.sasaran.index', ['kategori' => 'Sekolah']));

        $response->assertStatus(200);
        $reports = $response->original->getData()['reports'];
        $stats = $response->original->getData()['stats'];

        // SPPG Bina Bangsa memiliki kategori Sekolah (100 penerima)
        $this->assertEquals(100, $stats['total_penerima']);
        
        // Cek transformasi collection string 'kelompok_penerima' berjalan baik
        $firstReport = $reports->first();
        $this->assertEquals('Sekolah', $firstReport->kelompok_penerima);
    }

    public function test_validasi_filter_menolak_input_kategori_tidak_valid(): void
    {
        // Mencoba inject kategori yang tidak ada di daftar 'in:all,Sekolah,B3,Umum'
        $response = $this->actingAs($this->adminKecamatan)
            ->get(route('kecamatan.sasaran.index', ['kategori' => 'Ngawur']));

        // Harus gagal validasi dan melempar session error untuk key 'kategori'
        $response->assertSessionHasErrors('kategori');
    }
}
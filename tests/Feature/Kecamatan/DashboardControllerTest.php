<?php

namespace Tests\Feature\Kecamatan;

use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Puskesmas;
use App\Models\LaporanSlhs;
use App\Models\SasaranManfaat;
use App\Models\UnitUsaha;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
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

        // 2. Setup User Admin Kecamatan
        // PERBAIKAN 1: Hanya beri 1 akses agar MySQL ENUM tidak error
        $this->adminKecamatan = User::create([
            'username' => 'adminkec_dashboard',
            'password' => Hash::make('password123'),
            'role' => 'admin_kecamatan',
            'id_kecamatan' => $this->kecamatanUtama->id_kecamatan,
            'akses_tipe_usaha' => 'sppg', 
        ]);

        // 3. SEEDING DATA DUMMY
        // Unit 1: SPPG, Valid, Lulus IKL, SLHS mau expired (MASUK HITUNGAN)
        $unit1 = $this->createUnitUsaha($this->kecamatanUtama->id_kecamatan, 'sppg', 'Unit 1');
        LaporanSlhs::create([
            'id_unit_usaha' => $unit1->id_unit_usaha,
            'status_ikl' => 'selesai',
            'hasil_ikl' => 'memenuhi',
            'nilai_ikl' => 85,
            'status_slhs' => 'selesai',
            'tgl_berakhir_slhs' => now()->addDays(15), 
        ]);
        SasaranManfaat::create([
            'id_unit_usaha' => $unit1->id_unit_usaha,
            'kategori' => 'Sekolah',
            'tipe_instansi' => 'SD',
            'nama_instansi' => 'SD 1',
            'jumlah_siswa' => 50,
            'jumlah_bumil' => 5,
        ]); // Total Penerima: 55

        // Unit 2: TPP (TIDAK DIHITUNG karena admin hanya punya akses SPPG)
        $unit2 = $this->createUnitUsaha($this->kecamatanUtama->id_kecamatan, 'tpp', 'Unit 2');
        LaporanSlhs::create([
            'id_unit_usaha' => $unit2->id_unit_usaha,
            'status_ikl' => 'selesai',
            // PERBAIKAN 2: Ubah 'tidak memenuhi' menjadi 'belum_memenuhi'
            'nilai_ikl' => 60,
        ]);

        // Unit 3: KANTIN (TIDAK DIHITUNG)
        $this->createUnitUsaha($this->kecamatanUtama->id_kecamatan, 'kantin', 'Unit 3');

        // Unit 4: SPPG tapi di KECAMATAN LAIN (TIDAK DIHITUNG)
        $this->createUnitUsaha($kecamatanLain->id_kecamatan, 'sppg', 'Unit 4');
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
            'jumlah_pegawai' => 2,
            'jumlah_penjamah_terlatih' => 1,
            'status_aktif' => 1,
        ]);
    }

    public function test_hanya_admin_kecamatan_yang_bisa_mengakses_dashboard(): void
    {
        $responseGuest = $this->get(route('kecamatan.dashboard'));
        $responseGuest->assertRedirect(); 

        $adminDinkes = User::create([
            'username' => 'admindinkes_uji',
            'password' => Hash::make('pass'),
            'role' => 'admin_dinkes'
        ]);
        $responseDinkes = $this->actingAs($adminDinkes)->get(route('kecamatan.dashboard'));
        $responseDinkes->assertRedirect(); 
    }

    public function test_dashboard_menampilkan_statistik_yang_akurat_berdasarkan_hak_akses_dan_wilayah(): void
    {
        $response = $this->actingAs($this->adminKecamatan)->get(route('kecamatan.dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('kecamatan.index');

        // Total Unit Usaha harus 1 (Hanya Unit 1 SPPG). Unit 2 (TPP) diabaikan.
        $response->assertViewHas('totalUnitUsaha', 1);

        // Lulus IKL harus 1
        $response->assertViewHas('totalLulusIkl', 1);

        // Memiliki SLHS harus 1
        $response->assertViewHas('totalMemilikiSlhs', 1);

        // Total Penerima harus 55
        $response->assertViewHas('totalPenerima', 55);

        // Cek rekap per jenis usaha (SPPG: 1, TPP: 0, Kantin: 0, DAM: 0)
        $unitJenisSummary = $response->original->getData()['unitJenisSummary'];
        $this->assertEquals(1, $unitJenisSummary['sppg']);
        $this->assertEquals(0, $unitJenisSummary['tpp']); 
        $this->assertEquals(0, $unitJenisSummary['kantin']);

        // Cek SLHS Jatuh Tempo
        $jatuhTempo = $response->original->getData()['slhsJatuhTempo'];
        $this->assertCount(1, $jatuhTempo);
        $this->assertEquals('Unit 1', $jatuhTempo->first()->nama_unit_usaha);
    }
}
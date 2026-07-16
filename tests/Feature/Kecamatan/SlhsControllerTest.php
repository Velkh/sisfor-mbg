<?php

namespace Tests\Feature\Kecamatan;

use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Puskesmas;
use App\Models\UnitUsaha;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SlhsControllerTest extends TestCase
{
    use DatabaseTransactions;

    private $adminKecamatan;
    private $kecamatanUtama;
    private $kecamatanLain;
    private $kelurahanUtama;
    private $kelurahanLain;
    private $puskesmas;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Setup Wilayah & Puskesmas
        $this->kecamatanUtama = Kecamatan::create(['nama_kecamatan' => 'Kecamatan Utama']);
        $this->kecamatanLain = Kecamatan::create(['nama_kecamatan' => 'Kecamatan Lain']);
        
        $this->kelurahanUtama = Kelurahan::create([
            'id_kecamatan' => $this->kecamatanUtama->id_kecamatan,
            'nama_kelurahan' => 'Kelurahan Utama'
        ]);

        $this->kelurahanLain = Kelurahan::create([
            'id_kecamatan' => $this->kecamatanLain->id_kecamatan,
            'nama_kelurahan' => 'Kelurahan Lain'
        ]);
        
        $this->puskesmas = Puskesmas::create(['nama_puskesmas' => 'Puskesmas Utama']);

        // 2. Setup User Admin Kecamatan (Hanya akses SPPG)
        $this->adminKecamatan = User::create([
            'username' => 'adminkec_slhs',
            'password' => Hash::make('password123'),
            'role' => 'admin_kecamatan',
            'id_kecamatan' => $this->kecamatanUtama->id_kecamatan,
            'akses_tipe_usaha' => 'sppg', // ENUM akses 1 jenis usaha
        ]);
    }

    /**
     * Helper untuk membuat unit usaha dummy
     */
    private function createUnitUsaha($idKecamatan, $idKelurahan, $jenisUsaha, $namaUnit)
    {
        return UnitUsaha::create([
            'id_kecamatan' => $idKecamatan,
            'id_kelurahan' => $idKelurahan,
            'id_puskesmas' => $this->puskesmas->id_puskesmas,
            'jenis_usaha' => $jenisUsaha,
            'nama_unit_usaha' => $namaUnit,
            'nama_pemilik' => 'Pemilik ' . $namaUnit,
            'alamat' => 'Alamat ' . $namaUnit,
            'status_aktif' => 1,
        ]);
    }

    /* ==========================================
       SKENARIO: OTORISASI (MIDDLEWARE)
       ========================================== */

    public function test_hanya_admin_kecamatan_yang_bisa_mengakses_halaman_kelayakan(): void
    {
        // 1. Ditolak jika belum login (Guest)
        $responseGuest = $this->get(route('kecamatan.kelayakan.index'));
        $responseGuest->assertRedirect(); 

        // 2. Ditolak jika login sebagai admin dinkes
        $adminDinkes = User::create([
            'username' => 'admindinkes_uji_slhs',
            'password' => Hash::make('pass'),
            'role' => 'admin_dinkes'
        ]);
        
        $responseDinkes = $this->actingAs($adminDinkes)->get(route('kecamatan.kelayakan.index'));
        $responseDinkes->assertRedirect(); // Dicegat oleh middleware
    }

    /* ==========================================
       SKENARIO: HALAMAN INDEX
       ========================================== */

    public function test_index_menampilkan_data_sesuai_kecamatan_dan_hak_akses(): void
    {
        // Unit 1: Valid (Kecamatan Utama, SPPG)
        $this->createUnitUsaha($this->kecamatanUtama->id_kecamatan, $this->kelurahanUtama->id_kelurahan, 'sppg', 'SPPG Valid');
        
        // Unit 2: Invalid Hak Akses (Jenis TPP)
        $this->createUnitUsaha($this->kecamatanUtama->id_kecamatan, $this->kelurahanUtama->id_kelurahan, 'tpp', 'TPP Invalid');
        
        // Unit 3: Invalid Wilayah (Kecamatan Lain, SPPG)
        $this->createUnitUsaha($this->kecamatanLain->id_kecamatan, $this->kelurahanLain->id_kelurahan, 'sppg', 'SPPG Beda Camat');

        $response = $this->actingAs($this->adminKecamatan)->get(route('kecamatan.kelayakan.index'));

        $response->assertStatus(200);
        $response->assertViewIs('kecamatan.kelayakan');
        
        $items = $response->original->getData()['items'];

        // Dari 3 data, hanya 1 yang boleh tampil
        $this->assertCount(1, $items);
        $this->assertEquals('SPPG Valid', $items->first()->nama_unit_usaha);
    }

    /* ==========================================
       SKENARIO: HALAMAN SHOW (DETAIL)
       ========================================== */

public function test_show_menampilkan_data_valid(): void
    {
        $unit = $this->createUnitUsaha($this->kecamatanUtama->id_kecamatan, $this->kelurahanUtama->id_kelurahan, 'sppg', 'SPPG Milik Sendiri');

        $response = $this->actingAs($this->adminKecamatan)->get(route('kecamatan.kelayakan.show', $unit->id_unit_usaha));

        $response->assertStatus(200);
        // PERBAIKAN: Ubah ekspektasi nama view agar sesuai dengan controller
        $response->assertViewIs('kecamatan.kelayakan'); 
        $response->assertViewHas('unit');
    }

    public function test_show_menolak_akses_jika_data_beda_kecamatan_atau_beda_hak_akses(): void
    {
        // 1. Coba paksa akses unit dari kecamatan lain
        $unitBedaKecamatan = $this->createUnitUsaha($this->kecamatanLain->id_kecamatan, $this->kelurahanLain->id_kelurahan, 'sppg', 'Beda Camat');
        $response1 = $this->actingAs($this->adminKecamatan)->get(route('kecamatan.kelayakan.show', $unitBedaKecamatan->id_unit_usaha));
        
        // Controller mengeksekusi abort(403)
        $response1->assertStatus(403); 

        // 2. Coba paksa akses unit beda jenis usaha (TPP) di kecamatan sendiri
        $unitBedaJenis = $this->createUnitUsaha($this->kecamatanUtama->id_kecamatan, $this->kelurahanUtama->id_kelurahan, 'tpp', 'Beda Jenis');
        $response2 = $this->actingAs($this->adminKecamatan)->get(route('kecamatan.kelayakan.show', $unitBedaJenis->id_unit_usaha));
        
        // Controller mengeksekusi abort(403)
        $response2->assertStatus(403); 
    }
}
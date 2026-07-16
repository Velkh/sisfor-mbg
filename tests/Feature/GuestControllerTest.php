<?php

namespace Tests\Feature;

use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Puskesmas;
use App\Models\LaporanSlhs;
use App\Models\SasaranManfaat;
use App\Models\UnitUsaha;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class GuestControllerTest extends TestCase
{
    use DatabaseTransactions;

    private $kecamatan;
    private $unitUsaha;

protected function setUp(): void
    {
        parent::setUp();

        $this->kecamatan = Kecamatan::create([
            'nama_kecamatan' => 'Kecamatan Testing',
        ]);

        $kelurahan = Kelurahan::create([
            'id_kecamatan' => $this->kecamatan->id_kecamatan,
            'nama_kelurahan' => 'Kelurahan Testing',
        ]);

        $puskesmas = Puskesmas::create([
            'nama_puskesmas' => 'Puskesmas Testing',
        ]);

        $this->unitUsaha = UnitUsaha::create([
            'id_kecamatan' => $this->kecamatan->id_kecamatan,
            'id_kelurahan' => $kelurahan->id_kelurahan,  
            'id_puskesmas' => $puskesmas->id_puskesmas,   
            'jenis_usaha' => 'sppg',
            'nama_unit_usaha' => 'SPPG Test Web',
            'nama_pemilik' => 'Budi Tester',
            'alamat' => 'Jl. Testing No. 1',
            'jumlah_pegawai' => 5,
            'jumlah_penjamah_terlatih' => 2,
            'status_aktif' => 1,
        ]);

        LaporanSlhs::create([
            'id_unit_usaha' => $this->unitUsaha->id_unit_usaha,
            'status_ikl' => 'selesai',
            'nilai_ikl' => 85,
            'status_slhs' => 'selesai',
        ]);

        SasaranManfaat::create([
            'id_unit_usaha' => $this->unitUsaha->id_unit_usaha,
            'kategori' => 'Sekolah',
            'tipe_instansi' => 'SMA',
            'nama_instansi' => 'SMA Negeri Testing',
            'jumlah_siswa' => 100,
        ]);
    }

    public function test_halaman_index_bisa_diakses_dan_menampilkan_data(): void
    {
        $response = $this->get('/'); 

        $response->assertStatus(200);
        $response->assertViewIs('homepage');
        $response->assertViewHasAll([
            'totalSarana', 'prosesIkl', 'memenuhiIkl', 
            'slhsTerbit', 'penjamahTerlatih', 'cakupanSekolah', 
            'cakupanB3', 'penerimaManfaat'
        ]);
    }

    public function test_halaman_rekap_bisa_diakses_dengan_filter(): void
    {
        $response = $this->get('/rekap?q=Testing&jenis_usaha=sppg&kecamatan_id=' . $this->kecamatan->id_kecamatan);

        $response->assertStatus(200);
        $response->assertViewIs('rekapdaerah');
        $response->assertViewHas('rows');
        $response->assertViewHas('unitUsahaCards');
    }

    public function test_halaman_show_unit_menampilkan_data_yang_benar(): void
    {
        $response = $this->get('/unit/' . $this->unitUsaha->id_unit_usaha);

        $response->assertStatus(200);
        $response->assertViewIs('profilsppg');
        $response->assertViewHas('item');
        $response->assertViewHas('sma'); 
    }

    public function test_halaman_show_unit_menghasilkan_404_jika_id_tidak_ada(): void
    {
        $response = $this->get('/unit/999999');
        $response->assertStatus(404);
    }


    public function test_api_get_kecamatan_data_mengembalikan_json(): void
    {
        $response = $this->get('/api/rekap/kecamatan?q=Testing');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'rows' => [
                '*' => [
                    'id_sppg', 'nama_sppg', 'jenis_usaha', 
                    'status_ikl_label', 'status_slhs_label', 
                    'jumlah_pegawai', 'kelompok_penerima', 'total_penerima'
                ]
            ],
            'count'
        ]);

        $response->assertJsonFragment([
            'success' => true,
            'nama_sppg' => 'SPPG Test Web',
        ]);
    }

    public function test_api_rekap_kecamatan_tipe_sppg_berhasil(): void
    {
        $response = $this->get('/api/rekap/sppg');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'rows' => [
                '*' => [
                    'kecamatan', 'jumlah_sppg', 'memenuhi_ikl', 
                    'belum_memenuhi_ikl', 'belum_mengajukan_ikl', 
                    'sudah_slhs', 'belum_slhs'
                ]
            ]
        ]);
    }

    public function test_api_rekap_kecamatan_menolak_tipe_tidak_valid(): void
    {
        $response = $this->get('/api/rekap/ngawur');

        $response->assertStatus(400); 
        $response->assertJson([
            'success' => false,
            'message' => 'Tipe tidak valid'
        ]);
    }
    public function test_public_endpoints_consistent_under_repeated_requests(): void
    {
        $endpoints = ['/', '/rekap', '/api/rekap/kecamatan', '/api/rekap/sppg'];

        foreach ($endpoints as $endpoint) {
            for ($i = 0; $i < 200; $i++) {
                $response = $this->get($endpoint);
                $response->assertStatus(200);
            }
        }

        $this->assertTrue(true);
    }
}
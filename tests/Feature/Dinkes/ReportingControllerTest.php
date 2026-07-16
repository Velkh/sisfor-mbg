<?php

namespace Tests\Feature\Dinkes;

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

class ReportingControllerTest extends TestCase
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
            'username' => 'admindinkes_reporting',
            'password' => bcrypt('password'),
            'role' => 'admin_dinkes'
        ]);

        $this->kecamatan = Kecamatan::create(['nama_kecamatan' => 'Sukmajaya']);
        
        $this->kelurahan = Kelurahan::create([
            'id_kecamatan' => $this->kecamatan->id_kecamatan,
            'nama_kelurahan' => 'Baktijaya'
        ]);
        
        $this->puskesmas = Puskesmas::create(['nama_puskesmas' => 'Puskesmas Baktijaya']);
    }

    public function test_halaman_index_memuat_data_dengan_filter_yang_benar()
    {
        $this->actingAs($this->adminDinkes);

        UnitUsaha::create([
            'id_kecamatan' => $this->kecamatan->id_kecamatan,
            'id_kelurahan' => $this->kelurahan->id_kelurahan,
            'id_puskesmas' => $this->puskesmas->id_puskesmas,
            'jenis_usaha' => 'catering',
            'nama_unit_usaha' => 'Catering Sehat Uji',
            'nama_pemilik' => 'Bapak Uji',
            'alamat' => 'Jl. Uji'
        ]);

        $response = $this->get(route('admin.reporting.index', [
            'q' => 'Catering Sehat Uji',
            'jenis_usaha' => 'catering'
        ]));

        $response->assertStatus(200);
        $response->assertViewIs('dinkes.reporting.index');
        
        $items = $response->viewData('items');
        $this->assertTrue($items->contains('nama_unit_usaha', 'Catering Sehat Uji'));
    }

    public function test_halaman_create_dan_edit_berhasil_dimuat()
    {
        $this->actingAs($this->adminDinkes);

        // Tes Halaman Create
        $responseCreate = $this->get(route('admin.reporting.create'));
        $responseCreate->assertStatus(200);
        $responseCreate->assertViewIs('dinkes.reporting.form');

        // Tes Halaman Edit
        $unit = UnitUsaha::create([
            'id_kecamatan' => $this->kecamatan->id_kecamatan,
            'id_kelurahan' => $this->kelurahan->id_kelurahan,
            'id_puskesmas' => $this->puskesmas->id_puskesmas,
            'jenis_usaha' => 'restoran',
            'nama_unit_usaha' => 'Resto Edit',
            'nama_pemilik' => 'Ibu Edit',
            'alamat' => 'Jl. Edit'
        ]);

        $responseEdit = $this->get(route('admin.reporting.edit', $unit->id_unit_usaha));
        $responseEdit->assertStatus(200);
        $responseEdit->assertViewHas('unit');
    }

    public function test_berhasil_menyimpan_data_unit_laporan_sasaran_dan_foto()
    {
        $this->actingAs($this->adminDinkes);
        
        // Memalsukan sistem penyimpanan agar foto tidak tersimpan sungguhan
        Storage::fake('public');
        $file = UploadedFile::fake()->image('foto_dapur.jpg');

        // Memalsukan balasan API Dsimfoniku agar tes cepat dan stabil
        Http::fake([
            '*/tpp*' => Http::response([
                'data' => [
                    ['nama' => 'Resto Simpan', 'skor' => 85]
                ]
            ], 200)
        ]);

        $payload = [
            'jenis_usaha' => 'restoran',
            'nama_unit_usaha' => 'Resto Simpan',
            'nama_pemilik' => 'Pak Simpan',
            'alamat' => 'Jl. Simpan No. 1',
            'id_kecamatan' => $this->kecamatan->id_kecamatan,
            'id_kelurahan' => $this->kelurahan->id_kelurahan,
            'id_puskesmas' => $this->puskesmas->id_puskesmas,
            'status_ikl' => 'selesai',
            'status_slhs' => 'selesai',
            'ketersediaan_ipal' => 'tidak_ada',
            'pengelolaan_sampah' => 'tidak_ada',
            'foto_unit_usaha' => [$file], // Mengirim file palsu
            'sasaran' => [
                [
                    'kategori' => 'Sekolah',
                    'tipe_instansi' => 'SD',
                    'nama_instansi' => 'SDN Simpan 1',
                    'jumlah_siswa' => 100
                ]
            ]
        ];

        $response = $this->post(route('admin.reporting.store'), $payload);

        $response->assertRedirect(route('admin.reporting.index'));
        $response->assertSessionHas('success');

        // Memastikan data masuk ke Database
        $this->assertDatabaseHas('unit_usahas', [
            'nama_unit_usaha' => 'Resto Simpan'
        ]);

        $unit = UnitUsaha::where('nama_unit_usaha', 'Resto Simpan')->first();

        // Memastikan foto berhasil "disimpan" (di folder palsu)
        $this->assertDatabaseHas('foto_unit_usaha', ['id_unit_usaha' => $unit->id_unit_usaha]);
        
        // Memastikan laporan dan sasaran manfaat terbuat
        $this->assertDatabaseHas('laporan_slhs', [
            'id_unit_usaha' => $unit->id_unit_usaha,
            'hasil_ikl' => 'memenuhi', // Sesuai skor 85 dari HTTP Fake
            'nilai_ikl' => 85
        ]);
        
        $this->assertDatabaseHas('sasaran_manfaat', [
            'id_unit_usaha' => $unit->id_unit_usaha,
            'nama_instansi' => 'SDN Simpan 1'
        ]);
    }

    public function test_berhasil_menghapus_data_unit_beserta_relasinya()
    {
        $this->actingAs($this->adminDinkes);

        $unit = UnitUsaha::create([
            'id_kecamatan' => $this->kecamatan->id_kecamatan,
            'id_kelurahan' => $this->kelurahan->id_kelurahan,
            'id_puskesmas' => $this->puskesmas->id_puskesmas,
            'jenis_usaha' => 'dam',
            'nama_unit_usaha' => 'DAM Hapus',
            'nama_pemilik' => 'Pak Hapus',
            'alamat' => 'Jl. Hapus'
        ]);

        LaporanSlhs::create([
            'id_unit_usaha' => $unit->id_unit_usaha,
            'status_ikl' => 'belum_mengajukan'
        ]);

        SasaranManfaat::create([
            'id_unit_usaha' => $unit->id_unit_usaha,
            'kategori' => 'Umum'
        ]);

        $response = $this->delete(route('admin.reporting.destroy', $unit->id_unit_usaha));

        $response->assertRedirect(route('admin.reporting.index'));

        // Memastikan data di database benar-benar hilang karena cascade/delete()
        $this->assertDatabaseMissing('unit_usahas', ['id_unit_usaha' => $unit->id_unit_usaha]);
        $this->assertDatabaseMissing('laporan_slhs', ['id_unit_usaha' => $unit->id_unit_usaha]);
        $this->assertDatabaseMissing('sasaran_manfaat', ['id_unit_usaha' => $unit->id_unit_usaha]);
    }

    public function test_api_search_ikl_mengembalikan_json_yang_sesuai()
    {
        $this->actingAs($this->adminDinkes);

        // Memalsukan respons API Dsimfoniku
        Http::fake([
            '*/tpp*' => Http::response([
                'data' => [
                    [
                        'id' => 123,
                        'nama' => 'Warung JSON',
                        'skor' => 90,
                        'alamat' => 'Jl. JSON Api'
                    ]
                ]
            ], 200)
        ]);

        $response = $this->get(route('admin.reporting.ikl.search', ['search' => 'Warung JSON']));

        $response->assertStatus(200);
        
        // Memastikan struktur kembalian JSON persis seperti format yang disusun di controller
        $response->assertJson([
            'success' => true,
            'data' => [
                [
                    'id' => 123,
                    'nama' => 'Warung JSON',
                    'nilai_ikl' => 90,
                    'hasil_ikl' => 'memenuhi',
                    'alamat' => 'Jl. JSON Api'
                ]
            ]
        ]);
    }

    public function test_admin_kecamatan_ditolak_mengakses_halaman_reporting()
    {
        $adminKecamatan = User::create([
            'username' => 'adminkecamatan_report',
            'password' => bcrypt('password'),
            'role' => 'admin_kecamatan',
            'id_kecamatan' => $this->kecamatan->id_kecamatan,
            'akses_tipe_usaha' => 'sppg'
        ]);

        $this->actingAs($adminKecamatan);

        $response = $this->get(route('admin.reporting.index'));

        // Bergantung pada pengaturan middleware, biasanya 403 atau 302
        $response->assertStatus(302); 
    }
}
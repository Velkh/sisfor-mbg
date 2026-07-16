<?php

namespace Tests\Feature\Dinkes;

use App\Exports\KelayakanExport;
use App\Http\Controllers\Dinkes\EvaluationController;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Puskesmas;
use App\Models\UnitUsaha;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class EvaluationControllerTest extends TestCase
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
            'username' => 'admindinkes_evaluasi',
            'password' => bcrypt('password'),
            'role' => 'admin_dinkes'
        ]);

        $this->kecamatan = Kecamatan::create(['nama_kecamatan' => 'Beji']);
        
        $this->kelurahan = Kelurahan::create([
            'id_kecamatan' => $this->kecamatan->id_kecamatan,
            'nama_kelurahan' => 'Pondok Cina'
        ]);
        
        $this->puskesmas = Puskesmas::create(['nama_puskesmas' => 'Puskesmas Beji']);
    }

    public function test_halaman_index_menampilkan_data_dan_statistik_dengan_benar()
    {
        $this->actingAs($this->adminDinkes);

        $unitValid = UnitUsaha::create([
            'id_kecamatan' => $this->kecamatan->id_kecamatan,
            'id_kelurahan' => $this->kelurahan->id_kelurahan,
            'id_puskesmas' => $this->puskesmas->id_puskesmas,
            'jenis_usaha' => 'tpp',
            'nama_unit_usaha' => 'TPP Layak',
            'nama_pemilik' => 'Budi Santoso',
            'alamat' => 'Jl. Margonda'
        ]);

        DB::table('laporan_slhs')->insert([
            'id_unit_usaha' => $unitValid->id_unit_usaha,
            'status_ikl' => 'selesai',
            'hasil_ikl' => 'memenuhi',
            'nilai_ikl' => 85,
            'status_slhs' => 'selesai'
        ]);

        $unitBelumLayak = UnitUsaha::create([
            'id_kecamatan' => $this->kecamatan->id_kecamatan,
            'id_kelurahan' => $this->kelurahan->id_kelurahan,
            'id_puskesmas' => $this->puskesmas->id_puskesmas,
            'jenis_usaha' => 'kantin',
            'nama_unit_usaha' => 'Kantin Belum Layak',
            'nama_pemilik' => 'Andi',
            'alamat' => 'Jl. Nusantara'
        ]);

        DB::table('laporan_slhs')->insert([
            'id_unit_usaha' => $unitBelumLayak->id_unit_usaha,
            'status_ikl' => 'selesai',
            'hasil_ikl' => 'tidak_memenuhi',
            'nilai_ikl' => 60,
            'status_slhs' => 'belum_mengajukan'
        ]);

        $response = $this->get(action([EvaluationController::class, 'index']));

        $response->assertStatus(200);
        $response->assertViewIs('dinkes.kelayakan');
        $response->assertViewHasAll(['items', 'stats', 'filters', 'today']);
    }

    public function test_index_dapat_memfilter_berdasarkan_pencarian_dan_status_evaluasi()
    {
        $this->actingAs($this->adminDinkes);

        $unit = UnitUsaha::create([
            'id_kecamatan' => $this->kecamatan->id_kecamatan,
            'id_kelurahan' => $this->kelurahan->id_kelurahan,
            'id_puskesmas' => $this->puskesmas->id_puskesmas,
            'jenis_usaha' => 'sppg',
            'nama_unit_usaha' => 'SPPG Target Filter',
            'nama_pemilik' => 'Joko',
            'alamat' => 'Jl. Target'
        ]);

        DB::table('laporan_slhs')->insert([
            'id_unit_usaha' => $unit->id_unit_usaha,
            'status_ikl' => 'selesai',
            'hasil_ikl' => 'memenuhi',
            'nilai_ikl' => 90,
            'status_slhs' => 'sudah_mengajukan'
        ]);

        $response = $this->get(action([EvaluationController::class, 'index'], [
            'q' => 'Target Filter',
            'status_slhs' => 'sudah_mengajukan',
            'evaluasi' => 'memenuhi'
        ]));

        $response->assertStatus(200);
        $items = $response->viewData('items');
        
        $this->assertCount(1, $items);
        $this->assertEquals('SPPG Target Filter', $items->first()->nama_unit_usaha);
    }

    public function test_export_pdf_berhasil_diunduh()
    {
        $this->actingAs($this->adminDinkes);

        UnitUsaha::create([
            'id_kecamatan' => $this->kecamatan->id_kecamatan,
            'id_kelurahan' => $this->kelurahan->id_kelurahan,
            'id_puskesmas' => $this->puskesmas->id_puskesmas,
            'jenis_usaha' => 'tpp',
            'nama_unit_usaha' => 'TPP Export PDF',
            'nama_pemilik' => 'Budi PDF',
            'alamat' => 'Jl. Margonda PDF'
        ]);

        $response = $this->get(action([EvaluationController::class, 'exportPdf']));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_export_excel_berhasil_diunduh()
    {
        $this->actingAs($this->adminDinkes);
        
        Excel::fake();

        // 1. Bekukan waktu (mock time) agar nama file bisa ditebak dengan pasti
        $waktuTes = \Carbon\Carbon::create(2026, 5, 24, 10, 0, 0);
        \Carbon\Carbon::setTestNow($waktuTes);

        $response = $this->get(action([EvaluationController::class, 'exportExcel']));

        $response->assertStatus(200);
        
        // 2. Karena waktu dibekukan di jam 10:00:00, format 'Ymd-His' akan selalu '20260524-100000'
        $fileName = 'laporan-kelayakan-20260524-100000.xlsx';
        
        Excel::assertDownloaded($fileName, function (KelayakanExport $export) {
            return true;
        });

        // 3. Kembalikan waktu ke normal agar tidak mengganggu test lainnya
        \Carbon\Carbon::setTestNow();
    }

    public function test_halaman_index_memuat_data_json_untuk_modal_detail()
    {
        $this->actingAs($this->adminDinkes);

        $unit = UnitUsaha::create([
            'id_kecamatan' => $this->kecamatan->id_kecamatan,
            'id_kelurahan' => $this->kelurahan->id_kelurahan,
            'id_puskesmas' => $this->puskesmas->id_puskesmas,
            'jenis_usaha' => 'dam',
            'nama_unit_usaha' => 'DAM Modal Detail',
            'nama_pemilik' => 'Siti Detail',
            'alamat' => 'Jl. Detail'
        ]);

        DB::table('laporan_slhs')->insert([
            'id_unit_usaha' => $unit->id_unit_usaha,
            'status_ikl' => 'selesai',
            'hasil_ikl' => 'memenuhi',
            'nilai_ikl' => 82,
            'status_slhs' => 'selesai'
        ]);

        // Kita akses index, karena modal ada di sini
        $response = $this->get(action([EvaluationController::class, 'index']));

        $response->assertStatus(200);
        
        // Kita pastikan data unit usaha dirender di dalam halaman
        $response->assertSee('DAM Modal Detail');
        $response->assertSee('Siti Detail');
        
        // Memastikan payload JSON berisi nilai IKL 82 ada di HTML
        $response->assertSee('"nilai_ikl":82', false);
    }
}
<?php

namespace App\Http\Controllers\Dinkes;

use App\Http\Controllers\Controller;
use App\Models\UnitUsaha;
use App\Models\LaporanSlhs;
use App\Models\SasaranManfaat;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Puskesmas;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ReportingController extends Controller
{
    public function index(): View
    {

        $items = UnitUsaha::query()
            ->with(['laporanSlhs', 'sasaranManfaat'])
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('dinkes.reporting.index', [
            'items' => $items,
        ]);
    }

    public function create(): View
    {
        $puskesmas = Puskesmas::orderBy('nama_puskesmas')->get();
        $kecamatans = Kecamatan::orderBy('nama_kecamatan')->get();
        $kelurahans  = Kelurahan::orderBy('nama_kelurahan')->get();

        return view('dinkes.reporting.form', [
            'puskesmas' => $puskesmas,
            'kecamatans' => $kecamatans,
            'kelurahans' => $kelurahans,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatePayload($request);
        $laporanPayload = $this->buildLaporanPayload($validated);

        DB::transaction(function () use ($validated, $laporanPayload): void {
            $unit = UnitUsaha::create($this->payloadUnitUsaha($validated));

            LaporanSlhs::create(array_merge(
                $laporanPayload,
                ['id_unit_usaha' => $unit->id_unit_usaha]
            ));

            foreach ($validated['sasaran'] ?? [] as $row) {
                SasaranManfaat::create($this->payloadSasaran($row, $unit->id_unit_usaha));
            }
        });

        return redirect()
            ->route('admin.reporting.index')
            ->with('success', 'Data berhasil disimpan.');
    }

    public function show(UnitUsaha $unit): View
    {
        $unit->load(['laporanSlhs', 'sasaranManfaat']);

        return view('dinkes.reporting.show', [
            'unit' => $unit,
        ]);
    }

    public function edit(UnitUsaha $unit): View
    {
        $unit->load(['laporanSlhs', 'sasaranManfaat','puskesmas', 'kecamatan', 'kelurahan']);

         $puskesmas = Puskesmas::orderBy('nama_puskesmas')->get();
         $kecamatans = Kecamatan::orderBy('nama_kecamatan')->get();
         $kelurahans  = Kelurahan::orderBy('nama_kelurahan')->get();

         return view('dinkes.reporting.form', [
             'unit' => $unit,
             'laporan' => $unit->laporanSlhs,
             'sasaran' => $unit->sasaranManfaat,
             'puskesmas' => $puskesmas,
             'kecamatans' => $kecamatans,
             'kelurahans' => $kelurahans,
         ]);
    }

    public function update(Request $request, UnitUsaha $unit): RedirectResponse
    {
        $validated = $this->validatePayload($request);
        $laporanPayload = $this->buildLaporanPayload($validated);

        DB::transaction(function () use ($validated, $unit, $laporanPayload): void {
            $unit->update($this->payloadUnitUsaha($validated));

            $laporan = $unit->laporanSlhs;
            if ($laporan) {
                $laporan->update($laporanPayload);
            } else {
                LaporanSlhs::create(array_merge(
                    $laporanPayload,
                    ['id_unit_usaha' => $unit->id_unit_usaha]
                ));
            }

            SasaranManfaat::where('id_unit_usaha', $unit->id_unit_usaha)->delete();

            foreach ($validated['sasaran'] ?? [] as $row) {
                SasaranManfaat::create($this->payloadSasaran($row, $unit->id_unit_usaha));
            }
        });

        return redirect()
            ->route('admin.reporting.edit', $unit->id_unit_usaha)
            ->with('success', 'Data berhasil diperbarui.');
    }

    public function destroySasaran(UnitUsaha $unit, SasaranManfaat $sasaran): RedirectResponse
    {
        if ((int) $sasaran->id_unit_usaha !== (int) $unit->id_unit_usaha) {
            return redirect()
                ->route('admin.reporting.edit', $unit->id_unit_usaha)
                ->with('error', 'Sasaran tidak sesuai unit.');
        }

        $sasaran->delete();

        return redirect()
            ->route('admin.reporting.edit', $unit->id_unit_usaha)
            ->with('success', 'Sasaran berhasil dihapus.');
    }

    public function searchIkl(Request $request)
    {
        $validated = $request->validate([
            'search' => ['required', 'string', 'max:255'],
        ]);

        $response = Http::get(
            rtrim(config('services.dsimfoniku.base_url'), '/') . '/tpp',
            ['search' => $validated['search']]
        );

        if (! $response->successful()) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data dari API.',
                'data' => [],
            ], 500);
        }

        $rows = data_get($response->json(), 'data', []);
        if (! is_array($rows)) {
            $rows = [];
        }

        $results = collect($rows)->map(function (array $row): array {
            $nilaiIkl = (int) ($row['skor'] ?? 0);

            return [
                'id' => $row['id'] ?? null,
                'nama' => (string) ($row['nama'] ?? '-'),
                'pengelola' => (string) ($row['pengelola'] ?? '-'),
                'alamat' => (string) ($row['alamat'] ?? '-'),
                'kecamatan' => (string) ($row['kecamatan'] ?? '-'),
                'kelurahan' => (string) ($row['kelurahan'] ?? '-'),
                'kontak' => (string) ($row['kontak'] ?? '-'),
                'tanggal_penilaian' => (string) ($row['tanggal_penilaian'] ?? ''),
                'nilai_ikl' => $nilaiIkl,
                'hasil_ikl' => $nilaiIkl >= 80 ? 'memenuhi' : 'tidak_memenuhi',
            ];
        })->values();

        return response()->json([
            'success' => true,
            'data' => $results,
        ]);
    }

    private function validatePayload(Request $request): array
    {
        return $request->validate([
            'jenis_usaha' => ['required', 'in:sppg,tpp,dam,kantin'],
            'nama_unit_usaha' => ['required', 'string', 'max:255'],
            'nama_pemilik' => ['required', 'string', 'max:255'],
            'alamat' => ['required', 'string'],
            'jumlah_pegawai' => ['nullable', 'integer', 'min:0'],
            'jumlah_penjamah_terlatih' => ['nullable', 'integer', 'min:0'],
            'id_kecamatan' => ['required', 'integer'],
            'id_kelurahan' => ['required', 'integer'],
            'id_puskesmas' => ['required', 'integer'],

            'nilai_ikl' => ['nullable', 'integer', 'min:0'],
            'hasil_ikl' => ['nullable', 'in:memenuhi,tidak_memenuhi'],
            'status_ikl' => ['required', 'in:belum_mengajukan,sudah_mengajukan,selesai'],
            'status_slhs' => ['nullable', 'in:belum_mengajukan,sudah_mengajukan,selesai'],
            'tgl_terbit_slhs' => ['nullable', 'date'],
            'tgl_berakhir_slhs' => ['nullable', 'date'],
            'link_slhs' => ['nullable', 'string', 'max:255'],
            'ketersediaan_ipal' => ['nullable', 'in:ada,tidak_ada'],
            'jenis_ipal' => ['nullable', 'string', 'max:255'],
            'pengelolaan_sampah' => ['nullable', 'in:ada,tidak_ada'],
            'jenis_pengelolaan' => ['nullable', 'string', 'max:255'],

            'sasaran' => ['array'],
            'sasaran.*.kategori' => ['required', 'in:Sekolah,B3,Umum'],
            'sasaran.*.tipe_instansi' => ['nullable', 'in:TK,SD,SMP,SMA,Posyandu,TPP,DAM,Kantin,Lainnya'],
            'sasaran.*.nama_instansi' => ['nullable', 'string', 'max:255'],
            'sasaran.*.status' => ['nullable', 'in:negeri,swasta'],
            'sasaran.*.jumlah_siswa' => ['nullable', 'integer', 'min:0'],
            'sasaran.*.jumlah_bumil' => ['nullable', 'integer', 'min:0'],
            'sasaran.*.jumlah_busui' => ['nullable', 'integer', 'min:0'],
            'sasaran.*.jumlah_balita' => ['nullable', 'integer', 'min:0'],
            'sasaran.*.detail_jangkauan' => ['nullable', 'string'],
            'sasaran.*.jumlah_jiwa' => ['nullable', 'integer', 'min:0'],
        ]);
    }

    private function buildLaporanPayload(array $validated): array
    {
        [$nilaiIkl, $hasilIkl] = $this->resolveIklFromApi(
            $validated['status_ikl'],
            $validated['nama_unit_usaha']
        );

        $payload = [
            'status_ikl' => $validated['status_ikl'],
            'status_slhs' => $validated['status_slhs'] ?? null,
            'nilai_ikl' => $validated['nilai_ikl'] ?? $nilaiIkl,
            'hasil_ikl' => $validated['hasil_ikl'] ?? $hasilIkl,
            'tgl_terbit_slhs' => $validated['tgl_terbit_slhs'] ?? null,
            'tgl_berakhir_slhs' => $validated['tgl_berakhir_slhs'] ?? null,
            'link_slhs' => $validated['link_slhs'] ?? null,
            'ketersediaan_ipal' => $validated['ketersediaan_ipal'] ?? null,
            'jenis_ipal' => $validated['jenis_ipal'] ?? null,
            'pengelolaan_sampah' => $validated['pengelolaan_sampah'] ?? null,
            'jenis_pengelolaan' => $validated['jenis_pengelolaan'] ?? null,
        ];

        if (! is_null($nilaiIkl)) {
            $payload['nilai_ikl'] = $nilaiIkl;
        }

        if (! is_null($hasilIkl)) {
            $payload['hasil_ikl'] = $hasilIkl;
        }

        return $payload;
    }

    private function resolveIklFromApi(string $statusIkl, string $namaUnitUsaha): array
    {
        if ($statusIkl !== 'selesai') {
            return [null, null];
        }

        $response = Http::get(
            rtrim(config('services.dsimfoniku.base_url'), '/') . '/tpp',
            ['search' => $namaUnitUsaha]
        );

        if (! $response->successful()) {
            return [null, null];
        }

        $rows = data_get($response->json(), 'data', []);
        if (! is_array($rows) || $rows === []) {
            return [null, null];
        }

        $targetName = mb_strtolower(trim($namaUnitUsaha));

        $selected = collect($rows)->first(function ($row) use ($targetName): bool {
            if (! is_array($row)) {
                return false;
            }

            $apiName = mb_strtolower(trim((string) ($row['nama'] ?? '')));

            return $apiName !== '' && str_contains($apiName, $targetName);
        });

        if (! is_array($selected)) {
            $selected = $rows[0];
        }

        $nilaiIkl = (int) ($selected['skor'] ?? 0);
        $hasilIkl = $nilaiIkl >= 80 ? 'memenuhi' : 'tidak_memenuhi';

        return [$nilaiIkl, $hasilIkl];
    }

    private function payloadUnitUsaha(array $validated): array
    {
        return [
            'id_kecamatan' => $validated['id_kecamatan'],
            'id_kelurahan' => $validated['id_kelurahan'],
            'id_puskesmas' => $validated['id_puskesmas'],
            'jenis_usaha' => $validated['jenis_usaha'],
            'nama_unit_usaha' => $validated['nama_unit_usaha'],
            'nama_pemilik' => $validated['nama_pemilik'],
            'alamat' => $validated['alamat'],
            'jumlah_pegawai' => $validated['jumlah_pegawai'] ?? 0,
            'jumlah_penjamah_terlatih' => $validated['jumlah_penjamah_terlatih'] ?? 0,
            'status_aktif' => true,
        ];
    }

    private function payloadSasaran(array $row, int $unitId): array
    {
        return [
            'id_unit_usaha' => $unitId,
            'kategori' => $row['kategori'],
            'tipe_instansi' => $row['tipe_instansi'] ?? null,
            'nama_instansi' => $row['nama_instansi'] ?? null,
            'status' => $row['status'] ?? null,
            'jumlah_siswa' => $row['jumlah_siswa'] ?? null,
            'jumlah_bumil' => $row['jumlah_bumil'] ?? null,
            'jumlah_busui' => $row['jumlah_busui'] ?? null,
            'jumlah_balita' => $row['jumlah_balita'] ?? null,
            'detail_jangkauan' => $row['detail_jangkauan'] ?? null,
            'jumlah_jiwa' => $row['jumlah_jiwa'] ?? 0,
        ];
    }
}
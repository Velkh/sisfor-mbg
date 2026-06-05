<?php

namespace App\Http\Controllers\Kecamatan;

use App\Http\Controllers\Controller;
use App\Models\Kelurahan;
use App\Models\Puskesmas;
use App\Models\UnitUsaha;
use App\Models\FotoUnit;
use App\Models\LaporanSlhs;
use App\Models\SasaranManfaat;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Http;
use Throwable;

class LaporanUnitController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();
        abort_unless($user && $user->role === 'admin_kecamatan', 403);

        $query = UnitUsaha::query()
            ->with(['kecamatan', 'kelurahan', 'puskesmas', 'laporanSlhs', 'sasaranManfaat'])
            ->where('id_kecamatan', $user->id_kecamatan)
            ->where('jenis_usaha', $user->akses_tipe_usaha);

        if ($nama = $request->query('nama')) {
            $query->where('nama_unit_usaha', 'like', '%' . $nama . '%');
        }

        if ($statusIkl = $request->query('status_ikl')) {
            $query->whereHas('laporanSlhs', function ($q) use ($statusIkl) {
                $q->where('status_ikl', $statusIkl);
            });
        }

        if ($statusSlhs = $request->query('status_slhs')) {
            if (in_array($statusSlhs, ['belum_mengajukan', 'sudah_mengajukan', 'selesai'], true)) {
                $query->whereHas('laporanSlhs', function ($q) use ($statusSlhs) {
                    $q->where('status_slhs', $statusSlhs);
                });
            } elseif ($statusSlhs === 'ada') {
                $query->whereHas('laporanSlhs', function ($q) {
                    $q->whereNotNull('status_slhs');
                });
            } elseif ($statusSlhs === 'tidak') {
                $query->whereDoesntHave('laporanSlhs', function ($q) {
                    $q->whereNotNull('status_slhs');
                });
            }
        }

        $items = $query->orderByDesc('created_at')->paginate(10)->withQueryString();

        return view('kecamatan.reporting.index', [
            'items' => $items,
        ]);
    }

    public function create(): View
    {
        $user = Auth::user();

        abort_unless($user && $user->role === 'admin_kecamatan', 403);

        $kecamatan = $user->kecamatan;

        abort_unless($kecamatan, 403, 'Kecamatan tidak ditemukan untuk user ini.');

        $kelurahans = Kelurahan::query()
            ->where('id_kecamatan', $user->id_kecamatan)
            ->orderBy('nama_kelurahan')
            ->get();

        $puskesmas = Puskesmas::query()
            ->orderBy('nama_puskesmas')
            ->get();

        return view('kecamatan.reporting.form', [
            'kelurahans' => $kelurahans,
            'puskesmas' => $puskesmas,
        ]);
    }

    public function edit(UnitUsaha $unit): View
    {
        $user = Auth::user();

        abort_unless($user && $user->role === 'admin_kecamatan', 403);

        abort_unless(
            (int) $unit->id_kecamatan === (int) $user->id_kecamatan
            && strtolower((string) $unit->jenis_usaha) === strtolower((string) $user->akses_tipe_usaha),
            403
        );

        $unit->load(['kecamatan', 'kelurahan', 'puskesmas', 'laporanSlhs', 'sasaranManfaat', 'fotos']);

        $kelurahans = Kelurahan::query()
            ->where('id_kecamatan', $user->id_kecamatan)
            ->orderBy('nama_kelurahan')
            ->get();

        $puskesmas = Puskesmas::query()
            ->orderBy('nama_puskesmas')
            ->get();

        return view('kecamatan.reporting.form', [
            'unit' => $unit,
            'laporan' => $unit->laporanSlhs,
            'sasaran' => $unit->sasaranManfaat,
            'kelurahans' => $kelurahans,
            'puskesmas' => $puskesmas,
        ]);
    }

    public function update(Request $request, UnitUsaha $unit): RedirectResponse
    {
        $user = Auth::user();

        abort_unless($user && $user->role === 'admin_kecamatan', 403);

        abort_unless(
            (int) $unit->id_kecamatan === (int) $user->id_kecamatan
            && strtolower((string) $unit->jenis_usaha) === strtolower((string) $user->akses_tipe_usaha),
            403
        );

        $validated = $this->validatePayload($request, $user, $unit);

        DB::transaction(function () use ($validated, $user, $request, $unit): void {
            $unit->update([
                'id_kelurahan' => (int) $validated['id_kelurahan'],
                'id_puskesmas' => (int) $validated['id_puskesmas'],
                'nama_unit_usaha' => $validated['nama_unit_usaha'],
                'nama_pemilik' => $validated['nama_pemilik'],
                'alamat' => $validated['alamat'],
                'jumlah_pegawai' => $validated['jumlah_pegawai'] ?? 0,
                'jumlah_penjamah_terlatih' => $validated['jumlah_penjamah_terlatih'] ?? 0,
                'api_unit_id' => $validated['api_unit_id'] ?? null,
                'latitude' => $validated['latitude'] ?? null,
                'longitude' => $validated['longitude'] ?? null,
            ]);

            $laporanPayload = [
                'status_ikl' => data_get($validated, 'nilai_ikl') ? 'selesai' : 'belum_mengajukan',
                'nilai_ikl' => $validated['nilai_ikl'] ?? null,
                'hasil_ikl' => isset($validated['nilai_ikl'])
                    ? (($validated['nilai_ikl'] >= 80) ? 'memenuhi' : 'tidak_memenuhi')
                    : null,
            ];

            $laporan = $unit->laporanSlhs;

            if ($laporan) {
                $laporan->update($laporanPayload);
            } else {
                LaporanSlhs::create(array_merge(
                    $laporanPayload,
                    ['id_unit_usaha' => $unit->id_unit_usaha]
                ));
            }

            if ($request->hasFile('foto_unit_usaha')) {
                foreach ($request->file('foto_unit_usaha') as $file) {
                    $path = $file->store('foto-unit-usaha', 'public');

                    FotoUnit::create([
                        'id_unit_usaha' => $unit->id_unit_usaha,
                        'foto_unit_usaha' => $path,
                    ]);
                }
            }

            SasaranManfaat::where('id_unit_usaha', $unit->id_unit_usaha)->delete();

            foreach ($validated['sasaran'] ?? [] as $row) {
                SasaranManfaat::create($this->payloadSasaran($row, $unit->id_unit_usaha));
            }
        });

        return redirect()
            ->route('kecamatan.laporan-unit.edit', $unit->id_unit_usaha)
            ->with('success', 'Unit usaha berhasil diperbarui.');
    }

    public function searchIkl(Request $request)
    {
        $user = Auth::user();

        abort_unless($user && $user->role === 'admin_kecamatan', 403);

        $validated = $request->validate([
            'search' => ['required', 'string', 'max:255'],
        ]);

        try {
            $response = Http::timeout(10)
                ->retry(2, 200)
                ->get(
                    rtrim((string) config('services.dsimfoniku.base_url'), '/') . '/tpp',
                    ['search' => $validated['search']]
                );

            if (! $response->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengambil data dari API.',
                    'data' => [],
                ], 503);
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
                    'jenis' => (string) ($row['jenis'] ?? ''),
                    'penjamah_pangan_total' => (int) ($row['penjamah_pangan_total'] ?? 0),
                    'penjamah_pangan_bersertifikat' => (int) ($row['penjamah_pangan_bersertifikat'] ?? 0),
                    'nilai_ikl' => $nilaiIkl,
                    'hasil_ikl' => $nilaiIkl >= 80 ? 'memenuhi' : 'tidak_memenuhi',
                    'koordinat' => (string) ($row['koordinat'] ?? ''),
                ];
            })->values();

            return response()->json([
                'success' => true,
                'data' => $results,
            ]);
        } catch (Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'API eksternal sedang tidak tersedia.',
                'data' => [],
            ], 503);
        }
    }

    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();

        abort_unless($user && $user->role === 'admin_kecamatan', 403);

        $validated = $this->validatePayload($request, $user);

        DB::transaction(function () use ($validated, $user, $request): void {
            $unit = UnitUsaha::create([
                'id_kecamatan' => $user->id_kecamatan,
                'id_kelurahan' => (int) $validated['id_kelurahan'],
                'id_puskesmas' => (int) $validated['id_puskesmas'],
                'jenis_usaha' => $user->akses_tipe_usaha,
                'nama_unit_usaha' => $validated['nama_unit_usaha'],
                'nama_pemilik' => $validated['nama_pemilik'],
                'alamat' => $validated['alamat'],
                'jumlah_pegawai' => $validated['jumlah_pegawai'] ?? 0,
                'jumlah_penjamah_terlatih' => $validated['jumlah_penjamah_terlatih'] ?? 0,
                'api_unit_id' => $validated['api_unit_id'] ?? null,
                'latitude' => $validated['latitude'] ?? null,
                'longitude' => $validated['longitude'] ?? null,
                'status_aktif' => true,
            ]);

            if (data_get($validated, 'nilai_ikl'))  {
                LaporanSlhs::create([
                    'id_unit_usaha' => $unit->id_unit_usaha,
                    'status_ikl' => 'selesai',
                    'nilai_ikl' => $validated['nilai_ikl'],
                    'hasil_ikl' => $validated['nilai_ikl'] >= 80 ? 'memenuhi' : 'tidak_memenuhi',
                ]);
            } else {
                LaporanSlhs::create([
                    'id_unit_usaha' => $unit->id_unit_usaha,
                    'status_ikl' => 'belum_mengajukan',
                    'nilai_ikl' => null,
                    'hasil_ikl' => null,
                ]);
            }
            
            if ($request->hasFile('foto_unit_usaha')) {
                foreach ($request->file('foto_unit_usaha') as $file) {
                    $path = $file->store('foto-unit-usaha', 'public');

                    FotoUnit::create([
                        'id_unit_usaha' => $unit->id_unit_usaha,
                        'foto_unit_usaha' => $path,
                    ]);
                }
            }

            foreach ($validated['sasaran'] ?? [] as $row) {
                SasaranManfaat::create($this->payloadSasaran($row, $unit->id_unit_usaha));
            }
        });

        return redirect()
            ->route('kecamatan.laporan-unit.index')
            ->with('success', 'Unit usaha berhasil ditambahkan.');
    }

    public function show(UnitUsaha $unit): View
    {
        $user = Auth::user();

        abort_unless($user && $user->role === 'admin_kecamatan', 403);

        abort_unless(
            (int) $unit->id_kecamatan === (int) $user->id_kecamatan
            && strtolower((string) $unit->jenis_usaha) === strtolower((string) $user->akses_tipe_usaha),
            403
        );

        $unit->load(['kecamatan', 'kelurahan', 'puskesmas', 'laporanSlhs', 'sasaranManfaat', 'fotos']);

        return view('kecamatan.reporting.show', [
            'unit' => $unit,
        ]);
    }

    private function validatePayload(Request $request, $user, ?UnitUsaha $unit = null): array
    {
        $apiUnitIdRules = ['nullable', 'integer'];

        if ($unit) {
            $apiUnitIdRules[] = Rule::unique('unit_usahas', 'api_unit_id')->ignore($unit->id_unit_usaha, 'id_unit_usaha');
        } else {
            $apiUnitIdRules[] = 'unique:unit_usahas,api_unit_id';
        }

        return $request->validate([
            'api_unit_id' => $apiUnitIdRules,
            'nama_unit_usaha' => ['required', 'string', 'max:255'],
            'nama_pemilik' => ['required', 'string', 'max:255'],
            'alamat' => ['required', 'string'],
            'jumlah_pegawai' => ['nullable', 'integer', 'min:0'],
            'jumlah_penjamah_terlatih' => ['nullable', 'integer', 'min:0'],
            'nilai_ikl' => ['nullable', 'integer', 'min:0', 'max:100'],
            'latitude' => ['nullable', 'string', 'max:255'],
            'longitude' => ['nullable', 'string', 'max:255'],
            'id_kelurahan' => [
                'required',
                'integer',
                Rule::exists('kelurahan', 'id_kelurahan')->where(function ($query) use ($user): void {
                    $query->where('id_kecamatan', $user->id_kecamatan);
                }),
            ],
            'id_puskesmas' => ['required', 'integer', 'exists:puskesmas,id_puskesmas'],
            'foto_unit_usaha' => ['nullable', 'array'],
            'foto_unit_usaha.*' => ['image', 'max:5048'],
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
        ],[
            'api_unit_id.unique' => 'Data unit tidak boleh duplikat.',
        ]);
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
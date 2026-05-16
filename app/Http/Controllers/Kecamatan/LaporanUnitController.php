<?php

namespace App\Http\Controllers\Kecamatan;

use App\Http\Controllers\Controller;
use App\Models\Kelurahan;
use App\Models\Puskesmas;
use App\Models\UnitUsaha;
use App\Models\FotoUnit;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class LaporanUnitController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        abort_unless($user && $user->role === 'admin_kecamatan', 403);

        $items = UnitUsaha::query()
            ->with(['kecamatan', 'kelurahan', 'puskesmas', 'laporanSlhs'])
            ->where('id_kecamatan', $user->id_kecamatan)
            ->where('jenis_usaha', $user->akses_tipe_usaha)
            ->orderByDesc('created_at')
            ->paginate(10);

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

        $allowedJenisUsaha = $this->allowedJenisUsaha($user->akses_tipe_usaha);

        $kelurahans = Kelurahan::query()
            ->where('id_kecamatan', $user->id_kecamatan)
            ->orderBy('nama_kelurahan')
            ->get();

        $puskesmas = Puskesmas::query()
            ->orderBy('nama_puskesmas')
            ->get();

        return view('kecamatan.reporting.form', [
            'mode' => 'create',
            'kecamatan' => $kecamatan,
            'kelurahans' => $kelurahans,
            'puskesmas' => $puskesmas,
            'jenisUsahaOptions' => $allowedJenisUsaha,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();

        abort_unless($user && $user->role === 'admin_kecamatan', 403);

        $allowedJenisUsaha = $this->allowedJenisUsaha($user->akses_tipe_usaha);

        abort_if(empty($allowedJenisUsaha), 403, 'Anda tidak memiliki akses ke tipe usaha apapun.');

        $validated = $request->validate([
            'nama_unit_usaha' => ['required', 'string', 'max:255'],
            'nama_pemilik' => ['required', 'string', 'max:255'],
            'alamat' => ['required', 'string'],
            'id_kelurahan' => [
                'required',
                'integer',
                Rule::exists('kelurahan', 'id_kelurahan')->where(function ($query) use ($user): void {
                    $query->where('id_kecamatan', $user->id_kecamatan);
                }),
            ],
            'id_puskesmas' => ['required', 'integer', 'exists:puskesmas,id_puskesmas'],
            'jenis_usaha' => ['required', Rule::in($allowedJenisUsaha)],
            'jumlah_pegawai' => ['nullable', 'integer', 'min:0'],
            'jumlah_penjamah_terlatih' => ['nullable', 'integer', 'min:0'],
            'status_aktif' => ['nullable', 'boolean'],
            'foto_unit_usaha' => ['nullable', 'array'],
            'foto_unit_usaha.*' => ['image', 'max:5048'],
        ]);

        DB::transaction(function () use ($validated, $user, $request): void {
            $unit = UnitUsaha::create([
                'id_kecamatan' => $user->id_kecamatan,
                'id_kelurahan' => (int) $validated['id_kelurahan'],
                'id_puskesmas' => (int) $validated['id_puskesmas'],
                'jenis_usaha' => $validated['jenis_usaha'],
                'nama_unit_usaha' => $validated['nama_unit_usaha'],
                'nama_pemilik' => $validated['nama_pemilik'],
                'alamat' => $validated['alamat'],
                'jumlah_pegawai' => $validated['jumlah_pegawai'] ?? 0,
                'jumlah_penjamah_terlatih' => $validated['jumlah_penjamah_terlatih'] ?? 0,
                'status_aktif' => $validated['status_aktif'] ?? true,
            ]);
            if ($request->hasFile('foto_unit_usaha')) {
                foreach ($request->file('foto_unit_usaha') as $file) {
                    $path = $file->store('foto-unit-usaha', 'public');

                    FotoUnit::create([
                        'id_unit_usaha' => $unit->id_unit_usaha,
                        'foto_unit_usaha' => $path,
                    ]);
                }
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

        $allowedJenisUsaha = $this->allowedJenisUsaha($user->akses_tipe_usaha);

        abort_unless(
            (int) $unit->id_kecamatan === (int) $user->id_kecamatan
            && in_array(strtolower((string) $unit->jenis_usaha), $allowedJenisUsaha, true),
            403
        );

        $unit->load(['kecamatan', 'kelurahan', 'puskesmas', 'laporanSlhs', 'sasaranManfaat','fotos']);

        return view('kecamatan.reporting.show', [
            'unit' => $unit,
        ]);
    }

    private function allowedJenisUsaha(?string $aksesTipeUsaha): array
    {
        $rawItems = array_filter(array_map('trim', explode(',', (string) $aksesTipeUsaha)));

        $allowed = collect($rawItems)
            ->map(fn (string $item): string => strtolower($item))
            ->filter(fn (string $item): bool => in_array($item, ['sppg', 'tpp', 'dam', 'kantin'], true))
            ->unique()
            ->values()
            ->all();

        return $allowed;
    }
}
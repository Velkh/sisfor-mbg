<?php

namespace App\Http\Controllers\Sppg;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class IklController extends Controller
{
    public function search(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nama_sppg' => ['required', 'string', 'max:255'],
        ]);

        $response = Http::get('https://dsimfoniku.com/api/tpp/jasa-boga', [
            'search' => $validated['nama_sppg'],
        ]);

        if (! $response->successful()) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data dari API inspeksi.',
                'data' => [],
            ], 500);
        }

        $payload = $response->json();
        $rows = data_get($payload, 'data', []);
        if (! is_array($rows)) {
            $rows = [];
        }

        $namaQuery = Str::lower($validated['nama_sppg']);

        $filtered = collect($rows)
            ->filter(function ($row) use ($namaQuery): bool {
                if (! is_array($row)) {
                    return false;
                }

                $namaApi = Str::lower((string) ($row['nama'] ?? $row['nama_sppg'] ?? ''));
                return Str::contains($namaApi, $namaQuery);
            })
            ->map(function (array $row): array {
                return [
                    'nama_sppg' => (string) ($row['nama'] ?? $row['nama_sppg'] ?? '-'),
                    'nilai_ikl' => (int) ($row['skor'] ?? $row['nilai_ikl'] ?? 0),
                    'tanggal_ikl' => (string) ($row['tanggal_penilaian'] ?? $row['tanggal_ikl'] ?? ''),
                ];
            })
            ->values();

        return response()->json([
            'success' => true,
            'message' => $filtered->isEmpty() ? 'Data tidak ditemukan.' : 'Data ditemukan.',
            'data' => $filtered,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $userId = Auth::id();

        $validated = $request->validate([
            'status_ikl' => ['required', 'in:belum_mengajukan,sudah_mengajukan,selesai'],
            'selected_api_data' => ['nullable', 'string'],
        ]);

        $status = $validated['status_ikl'];
        $nilai = null;
        $tanggal = null;
        $hasil = null;

        if ($status === 'selesai') {
            $request->validate([
                'selected_api_data' => ['required', 'string'],
            ]);

            $selected = json_decode((string) $validated['selected_api_data'], true);

            if (! is_array($selected)) {
                return back()->withErrors([
                    'selected_api_data' => 'Data IKL terpilih tidak valid.',
                ])->withInput();
            }

            if (! array_key_exists('nilai_ikl', $selected) || ! array_key_exists('tanggal_ikl', $selected)) {
                return back()->withErrors([
                    'selected_api_data' => 'Nilai IKL atau tanggal inspeksi tidak tersedia.',
                ])->withInput();
            }

            $nilai = (int) $selected['nilai_ikl'];
            $tanggal = (string) $selected['tanggal_ikl'];

            if (strtotime($tanggal) === false) {
                return back()->withErrors([
                    'selected_api_data' => 'Format tanggal inspeksi tidak valid.',
                ])->withInput();
            }

            $hasil = $nilai >= 80 ? 'memenuhi' : 'tidak_memenuhi';
        }

        $exists = DB::table('sppg')
            ->where('id_users', $userId)
            ->exists();

        DB::table('sppg')->updateOrInsert(
            ['id_users' => $userId],
            [
                'status_ikl' => $status,
                'nilai_ikl' => $nilai,
                'hasil_ikl' => $hasil,
                'tanggal_ikl' => $tanggal,
                'updated_at' => now(),
                'created_at' => $exists ? DB::raw('created_at') : now(),
            ]
        );

        return redirect()
            ->route('sppg.inspeksi')
            ->with('success', $exists ? 'Data IKL berhasil diperbarui.' : 'Data IKL berhasil disimpan.');
    }
}
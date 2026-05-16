<?php

namespace App\Http\Controllers\Kecamatan;

use App\Http\Controllers\Controller;
use App\Models\SasaranManfaat;
use App\Models\UnitUsaha;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        abort_unless($user && $user->role === 'admin_kecamatan', 403);

        $allowedJenisUsaha = $this->allowedJenisUsaha($user->akses_tipe_usaha);

        $totalUnitUsaha = UnitUsaha::where('id_kecamatan', $user->id_kecamatan)
            ->when($allowedJenisUsaha !== [], fn ($q) => $q->whereIn('jenis_usaha', $allowedJenisUsaha))
            ->count();

        $totalLulusIkl = UnitUsaha::where('id_kecamatan', $user->id_kecamatan)
            ->when($allowedJenisUsaha !== [], fn ($q) => $q->whereIn('jenis_usaha', $allowedJenisUsaha))
            ->whereHas('laporanSlhs', function ($q): void {
                $q->where('status_ikl', 'selesai')
                  ->where('hasil_ikl', 'memenuhi')
                  ->where('nilai_ikl', '>=', 80);
            })
            ->count();

        $totalMemilikiSlhs = UnitUsaha::where('id_kecamatan', $user->id_kecamatan)
            ->when($allowedJenisUsaha !== [], fn ($q) => $q->whereIn('jenis_usaha', $allowedJenisUsaha))
            ->whereHas('laporanSlhs', fn ($q) => $q->where('status_slhs', 'selesai'))
            ->count();

        $totalPenerima = SasaranManfaat::whereHas('unitUsaha', function ($q) use ($user, $allowedJenisUsaha): void {
            $q->where('id_kecamatan', $user->id_kecamatan)
              ->when($allowedJenisUsaha !== [], fn ($x) => $x->whereIn('jenis_usaha', $allowedJenisUsaha));
        })->get()->sum(function ($row): int {
            return (int) $row->jumlah_siswa
                + (int) $row->jumlah_bumil
                + (int) $row->jumlah_busui
                + (int) $row->jumlah_balita
                + (int) $row->jumlah_jiwa;
        });

        $slhsJatuhTempo = UnitUsaha::where('id_kecamatan', $user->id_kecamatan)
            ->when($allowedJenisUsaha !== [], fn ($q) => $q->whereIn('jenis_usaha', $allowedJenisUsaha))
            ->whereHas('laporanSlhs', function ($q): void {
                $q->where('status_slhs', 'selesai')
                  ->whereNotNull('tgl_berakhir_slhs')
                  ->whereDate('tgl_berakhir_slhs', '<=', now()->addDays(30));
            })
            ->with(['kecamatan', 'laporanSlhs'])
            ->orderBy('created_at')
            ->limit(10)
            ->get();

        $recentUnitUsaha = UnitUsaha::where('id_kecamatan', $user->id_kecamatan)
            ->when($allowedJenisUsaha !== [], fn ($q) => $q->whereIn('jenis_usaha', $allowedJenisUsaha))
            ->latest()
            ->limit(5)
            ->get();

        $unitByJenis = UnitUsaha::where('id_kecamatan', $user->id_kecamatan)
            ->when($allowedJenisUsaha !== [], fn ($q) => $q->whereIn('jenis_usaha', $allowedJenisUsaha))
            ->selectRaw('jenis_usaha, COUNT(*) as total')
            ->groupBy('jenis_usaha')
            ->pluck('total', 'jenis_usaha');

        $unitJenisSummary = [
            'sppg' => (int) ($unitByJenis['sppg'] ?? 0),
            'tpp' => (int) ($unitByJenis['tpp'] ?? 0),
            'dam' => (int) ($unitByJenis['dam'] ?? 0),
            'kantin' => (int) ($unitByJenis['kantin'] ?? 0),
        ];

        return view('kecamatan.index', compact(
            'totalUnitUsaha',
            'totalLulusIkl',
            'totalMemilikiSlhs',
            'totalPenerima',
            'unitJenisSummary',
            'slhsJatuhTempo',
            'recentUnitUsaha'
        ));
    }

    private function allowedJenisUsaha(?string $aksesTipeUsaha): array
    {
        $rawItems = array_filter(array_map('trim', explode(',', (string) $aksesTipeUsaha)));

        return collect($rawItems)
            ->map(fn (string $item): string => strtolower($item))
            ->filter(fn (string $item): bool => in_array($item, ['sppg', 'tpp', 'dam', 'kantin'], true))
            ->unique()
            ->values()
            ->all();
    }
}
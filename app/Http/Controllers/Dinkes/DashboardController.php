<?php

namespace App\Http\Controllers\Dinkes;

use App\Http\Controllers\Controller;
use App\Models\Kecamatan;
use App\Models\UnitUsaha;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalUnitUsaha = UnitUsaha::count();

        $totalLulusIkl = UnitUsaha::whereHas('laporanSlhs', function ($q) {
            $q->where('status_ikl', 'selesai')
                ->where('hasil_ikl', 'memenuhi')
                ->where('nilai_ikl', '>=', 80);
        })->count();

        $totalBerslhs = UnitUsaha::whereHas('laporanSlhs', function ($q) {
            $q->where('status_slhs','selesai');
        })->count();

        $totalProsesIkl = UnitUsaha::whereHas('laporanSlhs', function ($q) {
            $q->whereNull('status_ikl')
                ->orWhere('status_ikl', '!=', 'selesai');
        })->count();

        $totalProsesSlhs = UnitUsaha::whereHas('laporanSlhs', function ($q) {
            $q->where('status_ikl', 'selesai')
                ->where('hasil_ikl', 'memenuhi')
                ->where(function ($sub) {
                    $sub->whereNull('status_slhs')
                        ->orWhere('status_slhs', '!=', 'selesai');
                });
        })->count();     

        // UPDATE: Penambahan count untuk unit usaha yang sudah memiliki SLHS
        $sebaranKecamatan = Kecamatan::query()
            ->withCount([
                // --- Penghitungan Total Keseluruhan Unit ---
                'unitUsahas as sppg_count' => function ($query) {
                    $query->where('jenis_usaha', 'sppg');
                },
                'unitUsahas as tpp_count' => function ($query) {
                    $query->where('jenis_usaha', 'tpp');
                },
                'unitUsahas as catering_count' => function($query){
                    $query->where('jenis_usaha', 'catering');
                },
                'unitUsahas as restoran_count' => function($query){
                    $query->where('jenis_usaha', 'restoran');
                },
                'unitUsahas as dam_count' => function ($query) {
                    $query->where('jenis_usaha', 'dam');
                },
                'unitUsahas as kantin_count' => function ($query) {
                    $query->where('jenis_usaha', 'kantin');
                },

                // --- Penghitungan Unit yang Sudah SLHS ---
                'unitUsahas as sppg_slhs_count' => function ($query) {
                    $query->where('jenis_usaha', 'sppg')
                          ->whereHas('laporanSlhs', fn($q) => $q->where('status_slhs', 'selesai'));
                },
                'unitUsahas as tpp_slhs_count' => function ($query) {
                    $query->where('jenis_usaha', 'tpp')
                          ->whereHas('laporanSlhs', fn($q) => $q->where('status_slhs', 'selesai'));
                },
                'unitUsahas as catering_slhs_count' => function($query){
                    $query->where('jenis_usaha', 'catering')
                          ->whereHas('laporanSlhs', fn($q) => $q->where('status_slhs', 'selesai'));
                },
                'unitUsahas as restoran_slhs_count' => function($query){
                    $query->where('jenis_usaha', 'restoran')
                          ->whereHas('laporanSlhs', fn($q) => $q->where('status_slhs', 'selesai'));
                },
                'unitUsahas as dam_slhs_count' => function ($query) {
                    $query->where('jenis_usaha', 'dam')
                          ->whereHas('laporanSlhs', fn($q) => $q->where('status_slhs', 'selesai'));
                },
                'unitUsahas as kantin_slhs_count' => function ($query) {
                    $query->where('jenis_usaha', 'kantin')
                          ->whereHas('laporanSlhs', fn($q) => $q->where('status_slhs', 'selesai'));
                }
            ])
            ->orderBy('nama_kecamatan')
            ->get();

        $recentUnitUsaha = UnitUsaha::latest('created_at')
            ->limit(5)
            ->get();

        $pengajuanSlhs = UnitUsaha::with(['laporanSlhs', 'kecamatan'])
            ->whereHas('laporanSlhs', function (Builder $q) {
                $q->where('status_slhs', 'sudah_mengajukan');
            })
            ->latest() 
            ->limit(5)
            ->get();

        $slhsJatuhTempo = UnitUsaha::with(['laporanSlhs', 'kecamatan'])
            ->whereHas('laporanSlhs', function (Builder $q) {
                $q->where('status_slhs', 'selesai')
                  ->whereNotNull('tgl_berakhir_slhs')
                  ->whereDate('tgl_berakhir_slhs', '>=', now()); // Yang belum hangus
            })
            ->get()
            ->sortBy(function ($unit) {
                return $unit->laporanSlhs->tgl_berakhir_slhs;
            })
            ->take(5);
        
        $slhsKadaluarsa = UnitUsaha::with(['laporanSlhs', 'kecamatan'])
            ->whereHas('laporanSlhs', function (Builder $q) {
                $q->where('status_slhs', 'selesai')
                ->whereNotNull('tgl_berakhir_slhs')
                ->whereDate('tgl_berakhir_slhs', '<', now());
            })
            ->get()
            ->sortByDesc(function ($unit) {
                return $unit->laporanSlhs->tgl_berakhir_slhs;
            })
            ->take(5);

        $tahunIni = date('Y');
        $trenQuery = UnitUsaha::selectRaw('MONTH(created_at) as bulan, COUNT(*) as total')
            ->whereYear('created_at', $tahunIni)
            ->groupBy('bulan')
            ->pluck('total', 'bulan');

        $grafikBulanan = array_fill(1, 12, 0);
        foreach ($trenQuery as $bulan => $total) {
            $grafikBulanan[$bulan] = $total;
        }

        $grafikBulanan = array_values($grafikBulanan);

        $totalPenerima = DB::table('sasaran_manfaat') // <-- SESUAIKAN NAMA TABEL SASARAN
            ->whereIn('id_unit_usaha', function($query) {
                // Hanya ambil id_unit_usaha yang IKL-nya lulus
                $query->select('id_unit_usaha')
                      ->from('laporan_slhs') // <-- SESUAIKAN NAMA TABEL LAPORAN
                      ->where('status_ikl', 'selesai')
                      ->where('nilai_ikl', '>=', 80);
            })
            ->selectRaw('SUM(COALESCE(jumlah_siswa, 0) + COALESCE(jumlah_bumil, 0) + COALESCE(jumlah_busui, 0) + COALESCE(jumlah_balita, 0) + COALESCE(jumlah_jiwa, 0)) as total')
            ->value('total') ?? 0;
        
        return view('dinkes.index', [
            'totalUnitUsaha' => $totalUnitUsaha,
            'totalLulusIkl' => $totalLulusIkl,
            'totalBerslhs' => $totalBerslhs,
            'totalProsesIkl' => $totalProsesIkl,
            'totalProsesSlhs' => $totalProsesSlhs,
            'recentUnitUsaha' => $recentUnitUsaha,
            'sebaranKecamatan' => $sebaranKecamatan,
            'slhsKadaluarsa' => $slhsKadaluarsa,
            'pengajuanSlhs' => $pengajuanSlhs,
            'slhsJatuhTempo' => $slhsJatuhTempo,
            'grafikBulanan'    => $grafikBulanan,
            'tahunIni'         => $tahunIni,
            'totalPenerima'    => $totalPenerima,
        ]);
    }
}
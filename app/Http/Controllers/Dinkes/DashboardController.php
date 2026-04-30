<?php

namespace App\Http\Controllers\Dinkes;

use App\Http\Controllers\Controller;
use App\Models\Sppg;
use App\Models\Kecamatan;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalSppg = Sppg::count();

        $lulusIklQuery = Sppg::where('status_ikl', 'selesai')
            ->where('hasil_ikl', 'memenuhi')
            ->where('nilai_ikl', '>=', 80);

        $totalLulusIkl = (clone $lulusIklQuery)->count();

        $totalLaikHigiene = (clone $lulusIklQuery)
            ->where('status_slhs', 'selesai')
            ->count();

        $totalBerslhs = (clone $lulusIklQuery)
            ->where(function ($query) {
                $query->whereNull('status_slhs')
                    ->orWhere('status_slhs', '!=', 'selesai');
            })
            ->count();

        $totalProsesIkl = Sppg::where(function ($query) {
            $query->whereNull('status_ikl')
                ->orWhere('status_ikl', '!=', 'selesai');
        })->count();

        $totalProsesSlhs = Sppg::where('status_ikl', 'selesai')
            ->where('hasil_ikl', 'memenuhi')
            ->where(function ($query) {
                $query->whereNull('status_slhs')
                    ->orWhere('status_slhs', '!=', 'selesai');
            })
            ->count();
        
        $sebaranKecamatan = Kecamatan::query()
            ->withCount('sppg')
            ->orderBy('nama_kecamatan')
            ->get();

        $recentSppg = Sppg::latest('created_at')
            ->limit(5)
            ->get();

        return view('dinkes.index', compact(
            'totalSppg',
            'totalLulusIkl',
            'totalLaikHigiene',
            'totalBerslhs',
            'totalProsesIkl',
            'totalProsesSlhs',
            'recentSppg',
            'sebaranKecamatan'
        ));
    }
}
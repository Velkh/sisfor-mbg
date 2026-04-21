<?php

namespace App\Http\Controllers\Sppg;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Sppg;
use App\Models\LaporanPenerima;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Puskesmas;
use Illuminate\Http\Request;

class SppgController extends Controller
{
    /**
     * Show the SPPG dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $userId = Auth::id();

        $sppg = Sppg::query()
            ->where('id_users', $userId)
            ->first();

        $allLaporans = collect();

        if ($sppg) {
            $allLaporans = LaporanPenerima::query()
                ->with(['kecamatan', 'kelurahan', 'puskesmas'])
                ->where('id_sppg', $sppg->id_sppg)
                ->latest()
                ->get();
        }

        $laporansTerbaru = $allLaporans->take(5);

        $totalLaporan = $allLaporans->count();
        $totalNegeri = $allLaporans->where('status', 'negeri')->count();
        $totalSwasta = $allLaporans->where('status', 'swasta')->count();

        $totalPenerima = $allLaporans->sum(function ($item) {
            return (int) $item->jml_siswa
                + (int) $item->jml_bumil
                + (int) $item->jml_busui
                + (int) $item->jml_balita;
        });

        return view('sppg.index', compact(
            'sppg',
            'laporansTerbaru',
            'totalLaporan',
            'totalNegeri',
            'totalSwasta',
            'totalPenerima'
        ));
    }

    /**
     * Show the Inspeksi page.
     *
     * @return \Illuminate\View\View
     */
    public function inspeksi()
    {
        $userId = Auth::id();

        $existingData = DB::table('sppg')
            ->where('id_users', $userId)
            ->first();
        return view('sppg.inspeksi', compact('existingData'));
    }

    /**
     * Show the Profile page.
     *
     * @return \Illuminate\View\View
     */
    public function profile()
    {
        return view('sppg.profile');
    }

    /**
     * Show the Surat Laik page.
     *
     * @return \Illuminate\View\View
     */
    public function suratlaik()
    {
        $userId = Auth::id();

        $sppg = Sppg::query()
            ->where('id_users', $userId)
            ->first();

        return view('sppg.suratlaik', compact('sppg'));
    }

    /**
     * Show the Pelaporan page.
     *
     * @return \Illuminate\View\View
     */
    public function pelaporan()
    {
        $userId = Auth::id();

        $sppg = Sppg::query()
            ->where('id_users', $userId)
            ->first();

        $laporans = collect();
        $totalLaporan = 0;
        $totalNegeri = 0;
        $totalSwasta = 0;
        $totalPenerima = 0;

        if ($sppg) {
            $baseQuery = LaporanPenerima::query()->where('id_sppg', $sppg->id_sppg);

            $laporans = (clone $baseQuery)
                ->with(['kecamatan', 'kelurahan', 'puskesmas'])
                ->latest()
                ->paginate(5)
                ->withQueryString();

            $totalLaporan = (clone $baseQuery)->count();
            $totalNegeri = (clone $baseQuery)->where('status', 'negeri')->count();
            $totalSwasta = (clone $baseQuery)->where('status', 'swasta')->count();

            $totalPenerima = (int) (clone $baseQuery)
                ->selectRaw('COALESCE(SUM(jml_siswa + jml_bumil + jml_busui + jml_balita), 0) as total')
                ->value('total');
        }

        $kecamatans = Kecamatan::query()->orderBy('nama_kecamatan')->get();
        $kelurahans = Kelurahan::query()->orderBy('nama_kelurahan')->get();
        $puskesmas = Puskesmas::query()->orderBy('nama_puskesmas')->get();

        return view('sppg.pelaporan', compact(
            'sppg',
            'laporans',
            'kecamatans',
            'kelurahans',
            'puskesmas',
            'totalLaporan',
            'totalNegeri',
            'totalSwasta',
            'totalPenerima'
        ));
    }
}
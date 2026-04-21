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
        return view('sppg.index');
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
        if ($sppg) {
            $laporans = LaporanPenerima::query()
                ->where('id_sppg', $sppg->id_sppg)
                ->latest()
                ->get();
        }

        $kecamatans = Kecamatan::query()->orderBy('nama_kecamatan')->get();
        $kelurahans = Kelurahan::query()->orderBy('nama_kelurahan')->get();
        $puskesmas = Puskesmas::query()->orderBy('nama_puskesmas')->get();

        return view('sppg.pelaporan', compact(
            'sppg',
            'laporans',
            'kecamatans',
            'kelurahans',
            'puskesmas'
        ));
    }
}
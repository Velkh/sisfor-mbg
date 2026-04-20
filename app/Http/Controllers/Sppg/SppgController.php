<?php

namespace App\Http\Controllers\Sppg;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
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
        return view('sppg.suratlaik');
    }

    /**
     * Show the Pelaporan page.
     *
     * @return \Illuminate\View\View
     */
    public function pelaporan()
    {
        return view('sppg.pelaporan');
    }
}
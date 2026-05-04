<?php

namespace App\Http\Controllers;
use App\Models\LaporanPenerima;
use App\Models\Kecamatan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;


class DinkesController extends Controller
{
    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('dinkes.index');
    }

    /**
     * Show the SPPG management page.
     *
     * @return \Illuminate\View\View
     */
    public function kelola()
    {
        return view('dinkes.kelola');
    }

    /**
     * Show the eligibility data page.
     *
     * @return \Illuminate\View\View
     */
    public function kelayakan()
    {
        return view('dinkes.kelayakan');
    }

    /**
     * Show the report summary page.
     *
     * @return \Illuminate\View\View
     */
    public function laporan()
    {
        return view('dinkes.laporan');
    }
}

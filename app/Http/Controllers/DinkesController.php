<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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

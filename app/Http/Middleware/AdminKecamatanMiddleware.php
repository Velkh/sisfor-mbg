<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminKecamatanMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (
            Auth::check()
            && Auth::user()->role === 'admin_kecamatan'
            && ! empty(Auth::user()->id_kecamatan)
            && ! empty(Auth::user()->akses_tipe_usaha)
        ) {
            return $next($request);
        }

        return redirect('/')->with('error', 'Akses ditolak. Hanya admin kecamatan yang bisa akses.');
    }
}
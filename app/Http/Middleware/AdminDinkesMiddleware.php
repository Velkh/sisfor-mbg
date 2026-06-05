<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminDinkesMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role ==='admin_dinkes') {
            return $next($request);
        }

        return redirect('/')->with('error', 'Akses ditolak. Hanya admin dinkes yang bisa akses.');
    }
}

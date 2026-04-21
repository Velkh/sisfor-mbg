<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class OperatorSppgMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role ==='operator_sppg') {
            return $next($request);
        }

        return redirect('/')->with('error', 'Akses ditolak. Hanya operator sppg yang bisa akses.');
    }
}

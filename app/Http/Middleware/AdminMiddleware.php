<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Periksa apakah pengguna sudah login dan memiliki role 'admin'
        if (auth()->check() && auth()->user()->role === 'admin') {
            return $next($request);
        }

        // Jika bukan admin, redirect ke halaman lain
        return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke halaman admin.');
    }
}

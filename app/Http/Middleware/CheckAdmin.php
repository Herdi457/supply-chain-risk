<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu');
        }

        if (auth()->user()->role !== 'admin') {
            return redirect('/')->with('error', 'Akses ditolak. Halaman ini khusus admin.');
        }

        return $next($request);
    }
}

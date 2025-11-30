<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // 1. Cek Login
        if (!Auth::check()) {
            return redirect('/login');
        }

        // 2. Cek Role (Misal: 'owner' !== 'admin')
        // Pastikan tabel users kamu punya kolom 'role'
        if ($request->user()->role !== $role) {
            abort(403, 'AKSES DITOLAK: Anda bukan ' . $role);
        }

        return $next($request);
    }
}
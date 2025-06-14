<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Pertama, periksa apakah pengguna sudah login
        if (!Auth::check()) {
            return redirect('login');
        }

        // Ambil data pengguna yang sedang login
        $user = Auth::user();

        // Pastikan $roles tidak kosong dan peran pengguna cocok dengan salah satu peran yang diizinkan
        if (!empty($roles) && in_array((string) $user->role, $roles, true)) {
            // Jika diizinkan, lanjutkan request
            return $next($request);
        }

        // Jika tidak diizinkan, hentikan dengan error 403
        abort(403, 'THIS ACTION IS UNAUTHORIZED.');
    }
}
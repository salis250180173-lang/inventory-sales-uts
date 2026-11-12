<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  mixed ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Pastikan user sudah login
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Jika user memiliki salah satu role yang diizinkan, lanjutkan
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // Jika tidak memiliki akses, abort dengan pesan Unauthorized
        abort(403, 'Unauthorized action. Anda tidak memiliki hak akses ke halaman ini.');
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]  ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = auth()->user();

        // Check if user has any of the accepted roles
        if (!empty($roles) && !in_array($user->role, $roles)) {
            abort(403, 'Akses ditolak: Akun Anda (' . ucfirst($user->role) . ') tidak memiliki wewenang untuk mengakses fungsi ini.');
        }

        return $next($request);
    }
}

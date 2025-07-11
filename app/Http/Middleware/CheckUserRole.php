<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if (! in_array($user->role, $roles)) {
            // Pengguna tidak memiliki role yang dibutuhkan
            // Anda bisa mengarahkan ke halaman 403, dashboard, atau rute lain
            abort(403, 'Unauthorized access.'); // Atau return redirect('/dashboard');
        }

        return $next($request);
    }
}

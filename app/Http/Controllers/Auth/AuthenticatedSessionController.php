<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // === LOGIC REDIRECT BERDASARKAN ROLE ===
        $user = Auth::user();

        switch ($user->role) {
            case 'admin':
                return redirect()->intended(route('admin.dashboard', absolute: false));
            case 'leader':
                return redirect()->intended(route('leader.dashboard', absolute: false));
            case 'field_coordinator':
                return redirect()->intended(route('field_coordinator.dashboard', absolute: false));
            case 'staff_pks':
                return redirect()->intended(route('staff.dashboard', absolute: false));
            case 'staff_keu':
                return redirect()->intended(route('staff.dashboard', absolute: false));
            default:
                // Fallback jika role tidak terdefinisi
                return redirect()->intended(route('dashboard', absolute: false));
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}

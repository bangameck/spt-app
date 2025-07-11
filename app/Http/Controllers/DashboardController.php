<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        // Redirect berdasarkan role jika pengguna mencoba mengakses /dashboard
        switch ($user->role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'leader':
                return redirect()->route('leader.dashboard');
            case 'field_coordinator':
                return redirect()->route('field_coordinator.dashboard');
            case 'staff':
                return redirect()->route('staff.dashboard');
            default:
                // Fallback jika role tidak terdefinisi
                return view('dashboard'); // Atau halaman error/default
        }
    }

    public function adminDashboard()
    {
        return view('admin.dashboard'); // Buat view ini nanti
    }

    public function leaderDashboard()
    {
        return view('leader.dashboard'); // Buat view ini nanti
    }

    public function fieldCoordinatorDashboard()
    {
        return view('field_coordinator.dashboard'); // Buat view ini nanti
    }

    public function staffDashboard()
    {
        return view('staff.dashboard'); // Buat view ini nanti
    }
}

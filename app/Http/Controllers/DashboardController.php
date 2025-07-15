<?php

namespace App\Http\Controllers;

use App\Models\Agreement;
use App\Models\DepositTransaction;
use App\Models\FieldCoordinator;
use App\Models\ParkingLocation;
use App\Models\RoadSection;
use App\Models\Leader;
use App\Models\BludBankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard utama untuk Admin dengan data yang komprehensif.
     */
    public function adminDashboard()
    {
        // 1. Data untuk Info Cards
        $totalCoordinators = FieldCoordinator::count();
        $totalActiveAgreements = Agreement::where('status', 'active')->count();
        $totalParkingLocationsInPKS = DB::table('agreement_parking_locations as apl')
            ->join('agreements as a', 'apl.agreement_id', '=', 'a.id')
            ->where('a.status', 'active')->whereNull('a.deleted_at')
            ->distinct('apl.parking_location_id')->count('apl.parking_location_id');

        // 2. Data untuk Grafik & Setoran
        $currentYearValidatedDeposit = DepositTransaction::where('is_validated', true)
            ->whereYear('deposit_date', now()->year)->sum('amount');

        $monthlyDeposits = DepositTransaction::select(
            DB::raw('MONTH(deposit_date) as month'),
            DB::raw('SUM(amount) as total')
        )
            ->where('is_validated', true)->whereYear('deposit_date', now()->year)
            ->groupBy('month')->orderBy('month')->get()->pluck('total', 'month')->all();

        $chartLabels = [];
        $chartData = [];
        for ($m = 1; $m <= 12; $m++) {
            $chartLabels[] = \Carbon\Carbon::create()->month($m)->translatedFormat('F');
            $chartData[] = $monthlyDeposits[$m] ?? 0;
        }

        // 3. ✅ DATA BARU UNTUK TABEL & INFO
        $recentDeposits = DepositTransaction::with('agreement.fieldCoordinator.user')
            ->whereHas('agreement') // <-- TAMBAHKAN BARIS INI
            ->where('is_validated', true)
            ->latest('deposit_date')->limit(5)->get();

        $recentParkingLocations = ParkingLocation::latest()->limit(5)->get();

        $recentCoordinators = FieldCoordinator::with('user')->latest()->limit(5)->get();

        $activeBankAccount = BludBankAccount::where('is_active', true)->first();

        $currentLeader = Leader::with('user')->latest()->first();

        return view('admin.dashboard', compact(
            'totalCoordinators',
            'totalActiveAgreements',
            'totalParkingLocationsInPKS',
            'currentYearValidatedDeposit',
            'chartLabels',
            'chartData',
            'recentDeposits',
            'recentParkingLocations',
            'recentCoordinators',
            'activeBankAccount',
            'currentLeader'
        ));
    }

    /**
     * Mencari perjanjian berdasarkan nomor dan redirect ke halaman detail.
     */
    public function findAgreement(Request $request)
    {
        $request->validate(['agreement_number' => 'required|string']);
        $agreement = Agreement::where('agreement_number', 'like', '%' . $request->agreement_number . '%')->first();

        if ($agreement) {
            return redirect()->route('masterdata.agreements.show', $agreement->id);
        }

        return redirect()->back()->with('error', 'Perjanjian dengan nomor ' . $request->agreement_number . ' tidak ditemukan.');
    }

    // Method dashboard untuk role lain bisa tetap di sini
    public function leaderDashboard()
    { /* ... */
    }
    public function fieldCoordinatorDashboard()
    { /* ... */
    }
    public function staffDashboard()
    { /* ... */
    }
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
}

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
        // --- 1. Data untuk Info Cards ---
        $currentLeader = Leader::with('user')->latest()->first();
        $activeBankAccount = BludBankAccount::where('is_active', true)->first();
        $currentYearValidatedDeposit = DepositTransaction::where('is_validated', true)
            ->whereYear('deposit_date', now()->year)->sum('amount');

        // --- 2. Data untuk Tabel "Terbaru" (Max 8) ---
        $recentDeposits = DepositTransaction::with('agreement.fieldCoordinator.user')
            ->whereHas('agreement')
            ->where('is_validated', true)
            ->latest('deposit_date')->limit(8)->get();

        $recentParkingLocations = ParkingLocation::with('roadSection')->latest()->limit(8)->get();
        $recentCoordinators = FieldCoordinator::with('user')->latest()->limit(8)->get();

        // --- 3. Data untuk Grafik ---

        // A. Grafik Setoran per Bulan (Mixed Chart)
        $monthlyDeposits = DepositTransaction::select(
            DB::raw('MONTH(deposit_date) as month'),
            DB::raw('SUM(amount) as total')
        )
            ->where('is_validated', true)->whereYear('deposit_date', now()->year)
            ->groupBy('month')->orderBy('month')->pluck('total', 'month')->all();

        $mainChartLabels = [];
        $mainChartData = [];
        for ($m = 1; $m <= 12; $m++) {
            $mainChartLabels[] = \Carbon\Carbon::create()->month($m)->translatedFormat('F');
            $mainChartData[] = $monthlyDeposits[$m] ?? 0;
        }

        // B. Grafik Zona (Polar Area Charts)
        $roadSectionsByZone = RoadSection::select('zone', DB::raw('count(*) as total'))
            ->groupBy('zone')->pluck('total', 'zone')->all();

        $locationsByZone = ParkingLocation::join('road_sections', 'parking_locations.road_section_id', '=', 'road_sections.id')
            ->select('road_sections.zone', DB::raw('count(parking_locations.id) as total'))
            ->groupBy('road_sections.zone')->pluck('total', 'zone')->all();

        $zoneChartData = [
            'labels' => array_keys($roadSectionsByZone),
            'roadSections' => array_values($roadSectionsByZone),
            'parkingLocations' => array_values($locationsByZone)
        ];

        // C. Grafik Titik per Ruas Jalan (Bar Chart)
        $locationsPerRoadSection = RoadSection::withCount('parkingLocations')
            ->orderBy('parking_locations_count', 'desc')
            ->limit(10)->get(); // Ambil 10 teratas

        $barChartData = [
            'labels' => $locationsPerRoadSection->pluck('name'),
            'data' => $locationsPerRoadSection->pluck('parking_locations_count')
        ];


        return view('admin.dashboard', compact(
            'currentLeader',
            'activeBankAccount',
            'currentYearValidatedDeposit',
            'recentDeposits',
            'recentParkingLocations',
            'recentCoordinators',
            'mainChartLabels',
            'mainChartData',
            'zoneChartData',
            'barChartData'
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
    {
        // --- 1. Data untuk Info Cards ---
        $currentLeader = Leader::with('user')->latest()->first();
        $activeBankAccount = BludBankAccount::where('is_active', true)->first();
        $currentYearValidatedDeposit = DepositTransaction::where('is_validated', true)
            ->whereYear('deposit_date', now()->year)->sum('amount');

        // --- 2. Data untuk Tabel "Terbaru" (Max 8) ---
        $recentDeposits = DepositTransaction::with('agreement.fieldCoordinator.user')
            ->whereHas('agreement')
            ->where('is_validated', true)
            ->latest('deposit_date')->limit(8)->get();

        $recentParkingLocations = ParkingLocation::with('roadSection')->latest()->limit(8)->get();
        $recentCoordinators = FieldCoordinator::with('user')->latest()->limit(8)->get();

        // --- 3. Data untuk Grafik ---

        // A. Grafik Setoran per Bulan (Mixed Chart)
        $monthlyDeposits = DepositTransaction::select(
            DB::raw('MONTH(deposit_date) as month'),
            DB::raw('SUM(amount) as total')
        )
            ->where('is_validated', true)->whereYear('deposit_date', now()->year)
            ->groupBy('month')->orderBy('month')->pluck('total', 'month')->all();

        $mainChartLabels = [];
        $mainChartData = [];
        for ($m = 1; $m <= 12; $m++) {
            $mainChartLabels[] = \Carbon\Carbon::create()->month($m)->translatedFormat('F');
            $mainChartData[] = $monthlyDeposits[$m] ?? 0;
        }

        // B. Grafik Zona (Polar Area Charts)
        $roadSectionsByZone = RoadSection::select('zone', DB::raw('count(*) as total'))
            ->groupBy('zone')->pluck('total', 'zone')->all();

        $locationsByZone = ParkingLocation::join('road_sections', 'parking_locations.road_section_id', '=', 'road_sections.id')
            ->select('road_sections.zone', DB::raw('count(parking_locations.id) as total'))
            ->groupBy('road_sections.zone')->pluck('total', 'zone')->all();

        $zoneChartData = [
            'labels' => array_keys($roadSectionsByZone),
            'roadSections' => array_values($roadSectionsByZone),
            'parkingLocations' => array_values($locationsByZone)
        ];

        // C. Grafik Titik per Ruas Jalan (Bar Chart)
        $locationsPerRoadSection = RoadSection::withCount('parkingLocations')
            ->orderBy('parking_locations_count', 'desc')
            ->limit(10)->get(); // Ambil 10 teratas

        $barChartData = [
            'labels' => $locationsPerRoadSection->pluck('name'),
            'data' => $locationsPerRoadSection->pluck('parking_locations_count')
        ];


        return view('staff.dashboard', compact(
            'currentLeader',
            'activeBankAccount',
            'currentYearValidatedDeposit',
            'recentDeposits',
            'recentParkingLocations',
            'recentCoordinators',
            'mainChartLabels',
            'mainChartData',
            'zoneChartData',
            'barChartData'
        ));
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
            case 'staff_keu':
                return redirect()->route('staff.dashboard');
            case 'staff_pks ':
                return redirect()->route('staff.dashboard');
            default:
                // Fallback jika role tidak terdefinisi
                return view('dashboard'); // Atau halaman error/default
        }
    }
}

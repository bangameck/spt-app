<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Agreement;
use App\Models\Leader;
use App\Models\FieldCoordinator;
use App\Models\ParkingLocation;
use App\Models\RoadSection;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class AgreementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        // Menggunakan relasi activeParkingLocations untuk pencarian agar lebih relevan
        $query = Agreement::with(['leader.user', 'fieldCoordinator.user', 'activeParkingLocations.roadSection']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('agreement_number', 'like', '%' . $search . '%')
                    ->orWhere('status', 'like', '%' . $search . '%')
                    ->orWhereHas('leader.user', function ($leaderUserQuery) use ($search) {
                        $leaderUserQuery->where('name', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('fieldCoordinator.user', function ($fcUserQuery) use ($search) {
                        $fcUserQuery->where('name', 'like', '%' . $search . '%');
                    })
                    // Pencarian kini dilakukan pada lokasi yang aktif saja
                    ->orWhereHas('activeParkingLocations', function ($plQuery) use ($search) {
                        $plQuery->where('name', 'like', '%' . $search . '%')
                            ->orWhereHas('roadSection', function ($rsQuery) use ($search) {
                                $rsQuery->where('name', 'like', '%' . $search . '%');
                            });
                    });
            });
        }

        $agreements = $query->latest()->paginate(10);

        return view('staff.agreements.index', compact('agreements', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $leaders = Leader::with('user')->get();
        $fieldCoordinators = FieldCoordinator::with('user')->get();
        $roadSections = RoadSection::orderBy('name')->get();

        $availableParkingLocations = ParkingLocation::where('status', 'tersedia')
            ->whereDoesntHave('agreements', function ($query) {
                $query->where('agreement_parking_locations.status', 'active');
            })
            ->with('roadSection')
            ->get();

        return view('staff.agreements.create', compact('leaders', 'fieldCoordinators', 'roadSections', 'availableParkingLocations'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'agreement_number' => 'required|string|max:255|unique:agreements,agreement_number',
            'leader_id' => 'required|exists:leaders,id',
            'field_coordinator_id' => 'required|exists:field_coordinators,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'daily_deposit_amount' => 'required|numeric|min:0',
            'status' => 'required|string|in:active,expired,terminated,pending_renewal',
            'signed_date' => 'required|date',
            'parking_location_ids' => 'required|array|min:1', // Validasi minimal 1 lokasi
            'parking_location_ids.*' => 'exists:parking_locations,id',
        ]);

        DB::beginTransaction();
        try {
            $agreement = Agreement::create($validatedData);

            $parkingLocationsToAttach = [];
            foreach ($validatedData['parking_location_ids'] as $locationId) {
                // Saat membuat, semua lokasi yang dilampirkan otomatis 'active'
                $parkingLocationsToAttach[$locationId] = ['assigned_date' => now(), 'status' => 'active'];
                ParkingLocation::where('id', $locationId)->update(['status' => 'tidak_tersedia']);
            }
            $agreement->parkingLocations()->attach($parkingLocationsToAttach);

            DB::commit();

            return redirect()->route('masterdata.agreements.index')
                ->with('success', 'Perjanjian "' . $agreement->agreement_number . '" berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('AgreementController@store: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan perjanjian: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     * ---
     * ✅ DIPERBARUI: Menggunakan relasi activeParkingLocations
     * ---
     */
    public function show(Agreement $agreement)
    {
        $agreement->load([
            'leader.user',
            'fieldCoordinator.user',
            'activeParkingLocations.roadSection', // <-- Mengambil lokasi aktif
            'depositTransactions',
            'histories'
        ]);
        return view('staff.agreements.show', compact('agreement'));
    }

    /**
     * Generate PDF for the specified agreement.
     * ---
     * ✅ DIPERBARUI: Menggunakan relasi activeParkingLocations
     * ---
     */
    public function generatePdf(Agreement $agreement)
    {
        $agreement->load([
            'leader.user',
            'fieldCoordinator.user',
            'activeParkingLocations.roadSection' // <-- Mengambil lokasi aktif
        ]);
        $pdf = Pdf::loadView('pdf.agreement', compact('agreement'));
        return $pdf->stream('Perjanjian_Kerjasama_' . $agreement->agreement_number . '.pdf');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Agreement $agreement)
    {
        $leaders = Leader::with('user')->get();
        $fieldCoordinators = FieldCoordinator::with('user')->get();
        $roadSections = RoadSection::orderBy('name')->get();

        // Mengambil semua lokasi yang statusnya 'tersedia' ATAU yang sudah aktif di perjanjian ini
        $availableParkingLocations = ParkingLocation::where('status', 'tersedia')
            ->orWhereHas('agreements', function ($query) use ($agreement) {
                $query->where('agreement_parking_locations.agreement_id', $agreement->id)
                    ->where('agreement_parking_locations.status', 'active');
            })
            ->with('roadSection')
            ->get();

        // Mengambil ID lokasi yang saat ini aktif di perjanjian ini untuk pre-select checkbox
        $currentParkingLocationIds = $agreement->activeParkingLocations->pluck('id')->toArray();

        return view('staff.agreements.edit', compact('agreement', 'leaders', 'fieldCoordinators', 'roadSections', 'availableParkingLocations', 'currentParkingLocationIds'));
    }

    /**
     * Update the specified resource in storage.
     * ---
     * ✅ DIPERBARUI: Logika update yang lebih robust
     * ---
     */
    public function update(Request $request, Agreement $agreement)
    {
        $validatedData = $request->validate([
            'agreement_number' => ['required', 'string', 'max:255', Rule::unique('agreements', 'agreement_number')->ignore($agreement->id)],
            'leader_id' => 'required|exists:leaders,id',
            'field_coordinator_id' => 'required|exists:field_coordinators,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'daily_deposit_amount' => 'required|numeric|min:0',
            'status' => 'required|string|in:active,expired,terminated,pending_renewal',
            'signed_date' => 'required|date',
            'parking_location_ids' => 'nullable|array',
            'parking_location_ids.*' => 'exists:parking_locations,id',
        ]);

        DB::beginTransaction();
        try {
            // 1. Update data utama perjanjian
            $agreement->update($validatedData);

            // 2. Ambil ID dari form dan semua ID yang pernah terkait dengan agreement ini
            $newLocationIds = $validatedData['parking_location_ids'] ?? [];
            $allRelatedLocations = $agreement->parkingLocations()->get()->keyBy('id');
            $currentActiveLocationIds = $agreement->activeParkingLocations()->pluck('parking_locations.id')->toArray();

            // 3. Tentukan lokasi yang akan dinonaktifkan (dihilangkan centangnya)
            $locationsToDeactivate = array_diff($currentActiveLocationIds, $newLocationIds);
            if (!empty($locationsToDeactivate)) {
                foreach ($locationsToDeactivate as $locationId) {
                    $agreement->parkingLocations()->updateExistingPivot($locationId, [
                        'status' => 'inactive',
                        'removed_date' => now(),
                    ]);
                }
                ParkingLocation::whereIn('id', $locationsToDeactivate)->update(['status' => 'tersedia']);
            }

            // 4. Proses lokasi yang dicentang di form
            $attachData = [];
            foreach ($newLocationIds as $locationId) {
                // Cek apakah lokasi ini sudah pernah terhubung sebelumnya
                if (isset($allRelatedLocations[$locationId])) {
                    // Jika ya, dan statusnya inactive, aktifkan kembali (UPDATE)
                    if ($allRelatedLocations[$locationId]->pivot->status === 'inactive') {
                        $agreement->parkingLocations()->updateExistingPivot($locationId, [
                            'status' => 'active',
                            'assigned_date' => now(),
                            'removed_date' => null, // Hapus tanggal remove
                        ]);
                    }
                } else {
                    // Jika belum pernah terhubung sama sekali, tandai untuk ditambah (INSERT)
                    $attachData[$locationId] = ['status' => 'active', 'assigned_date' => now()];
                }
            }

            // 5. Tambahkan lokasi yang benar-benar baru
            if (!empty($attachData)) {
                $agreement->parkingLocations()->attach($attachData);
            }

            // 6. Update status semua lokasi yang aktif menjadi 'tidak_tersedia'
            if (!empty($newLocationIds)) {
                ParkingLocation::whereIn('id', $newLocationIds)->update(['status' => 'tidak_tersedia']);
            }

            DB::commit();

            return redirect()->route('masterdata.agreements.index')
                ->with('success', 'Perjanjian "' . $agreement->agreement_number . '" berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('AgreementController@update: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui perjanjian: ' . $e->getMessage());
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Agreement $agreement)
    {
        DB::beginTransaction();
        try {
            // Ubah status semua lokasi aktif yang terkait sebelum menghapus perjanjian
            $activeLocationIds = $agreement->activeParkingLocations()->pluck('parking_locations.id')->toArray();
            if (!empty($activeLocationIds)) {
                ParkingLocation::whereIn('id', $activeLocationIds)->update(['status' => 'tersedia']);
            }

            $agreement->delete(); // Ini akan soft delete karena ada trait di model

            DB::commit();

            return redirect()->route('masterdata.agreements.index')->with('success', 'Perjanjian berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('AgreementController@destroy: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus perjanjian: ' . $e->getMessage());
        }
    }

    /**
     * Detach a specific parking location from an agreement.
     */
    public function detachParkingLocation(Agreement $agreement, ParkingLocation $parkingLocation)
    {
        DB::beginTransaction();
        try {
            // Update status pivot menjadi 'inactive' untuk histori
            $agreement->parkingLocations()->updateExistingPivot($parkingLocation->id, [
                'status' => 'inactive',
                'removed_date' => now()
            ]);

            // Update status lokasi parkir menjadi 'tersedia'
            $parkingLocation->update(['status' => 'tersedia']);

            DB::commit();

            return redirect()->back()->with('success', 'Lokasi parkir "' . $parkingLocation->name . '" berhasil dikeluarkan dari perjanjian.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error detaching parking location: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->with('error', 'Gagal mengeluarkan lokasi parkir: ' . $e->getMessage());
        }
    }
}

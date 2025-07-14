<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;
use App\Models\Agreement;
use App\Models\Leader;
use App\Models\FieldCoordinator;
use App\Models\ParkingLocation;
use App\Models\RoadSection;
use App\Models\AgreementHistory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class AgreementController extends Controller
{
    /**
     * Menampilkan daftar perjanjian.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = Agreement::with(['leader.user', 'fieldCoordinator.user', 'activeParkingLocations']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('agreement_number', 'like', '%' . $search . '%')
                    ->orWhere('status', 'like', '%' . $search . '%')
                    ->orWhereHas('leader.user', fn($subq) => $subq->where('name', 'like', '%' . $search . '%'))
                    ->orWhereHas('fieldCoordinator.user', fn($subq) => $subq->where('name', 'like', '%' . $search . '%'));
            });
        }

        $agreements = $query->latest()->paginate(10);
        return view('staff.agreements.index', compact('agreements', 'search'));
    }

    /**
     * Menampilkan form untuk membuat perjanjian baru.
     */
    public function create()
    {
        $leaders = Leader::with('user')->get();
        $roadSections = RoadSection::orderBy('name')->get();
        $availableParkingLocations = ParkingLocation::where('status', 'tersedia')->with('roadSection')->get();

        // --- PERUBAHAN DI SINI ---
        // Ambil hanya koordinator yang tidak memiliki perjanjian 'active'
        $fieldCoordinators = FieldCoordinator::with('user')
            ->whereDoesntHave('agreements', function ($query) {
                $query->where('status', 'active');
            })
            ->get();
        // --- AKHIR PERUBAHAN ---

        return view('staff.agreements.create', compact('leaders', 'fieldCoordinators', 'roadSections', 'availableParkingLocations'));
    }

    /**
     * Menyimpan perjanjian baru ke database.
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
     * Menampilkan detail satu perjanjian.
     */
    public function show(Agreement $agreement)
    {
        $agreement->load(['leader.user', 'fieldCoordinator.user', 'activeParkingLocations.roadSection', 'depositTransactions', 'histories']);
        return view('staff.agreements.show', compact('agreement'));
    }

    /**
     * Menampilkan form untuk mengedit perjanjian.
     */
    public function edit(Agreement $agreement)
    {
        $leaders = Leader::with('user')->get();
        $fieldCoordinators = FieldCoordinator::with('user')->get();
        $roadSections = RoadSection::orderBy('name')->get();
        $availableParkingLocations = ParkingLocation::where('status', 'tersedia')
            ->orWhereHas('agreements', function ($query) use ($agreement) {
                $query->where('agreement_parking_locations.agreement_id', $agreement->id);
            })->with('roadSection')->get();
        $currentParkingLocationIds = $agreement->activeParkingLocations()->pluck('parking_locations.id')->toArray();

        return view('staff.agreements.edit', compact('agreement', 'leaders', 'fieldCoordinators', 'roadSections', 'availableParkingLocations', 'currentParkingLocationIds'));
    }

    /**
     * Update data perjanjian di database.
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
            $agreement->update($validatedData);

            $newLocationIds = $validatedData['parking_location_ids'] ?? [];
            $allRelatedLocations = $agreement->parkingLocations()->get()->keyBy('id');
            $currentActiveLocationIds = $agreement->activeParkingLocations()->pluck('parking_locations.id')->toArray();

            // Tentukan lokasi yang akan dinonaktifkan (dihilangkan centangnya)
            $locationsToDeactivate = array_diff($currentActiveLocationIds, $newLocationIds);
            if (!empty($locationsToDeactivate)) {
                // Ambil detail lokasi untuk loop
                $deactivatedLocationsDetails = ParkingLocation::whereIn('id', $locationsToDeactivate)->get();

                foreach ($deactivatedLocationsDetails as $location) {
                    // 1. Nonaktifkan relasi di pivot
                    $agreement->parkingLocations()->updateExistingPivot($location->id, [
                        'status' => 'inactive',
                        'removed_date' => now(),
                    ]);

                    // 2. ✅ LOGIKA RIWAYAT PINDAH KE SINI
                    AgreementHistory::create([
                        'agreement_id' => $agreement->id,
                        'event_type' => 'location_removed',
                        'changed_by_user_id' => Auth::id(),
                        'notes' => 'Lokasi parkir "' . $location->name . '" dikeluarkan dari perjanjian.',
                        'old_value' => json_encode(['id' => $location->id, 'name' => $location->name]),
                    ]);
                }
                // 3. Update status di tabel utama setelah loop selesai
                ParkingLocation::whereIn('id', $locationsToDeactivate)->update(['status' => 'tersedia']);
            }

            // Proses lokasi yang dicentang di form
            $attachData = [];
            foreach ($newLocationIds as $locationId) {
                if (isset($allRelatedLocations[$locationId])) {
                    if ($allRelatedLocations[$locationId]->pivot->status === 'inactive') {
                        $agreement->parkingLocations()->updateExistingPivot($locationId, [
                            'status' => 'active',
                            'assigned_date' => now(),
                            'removed_date' => null,
                        ]);
                    }
                } else {
                    $attachData[$locationId] = ['status' => 'active', 'assigned_date' => now()];
                }
            }

            if (!empty($attachData)) {
                $agreement->parkingLocations()->attach($attachData);
            }

            if (!empty($newLocationIds)) {
                ParkingLocation::whereIn('id', $newLocationIds)->update(['status' => 'tidak_tersedia']);
            }

            // HAPUS BLOK RIWAYAT YANG LAMA DARI SINI

            DB::commit();

            return redirect()->route('masterdata.agreements.index')
                ->with('success', 'Perjanjian "' . $agreement->agreement_number . '" berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('AgreementController@update: Error updating agreement: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui perjanjian. Terjadi kesalahan internal.');
        }
    }


    /**
     * Menghapus perjanjian (soft delete).
     */
    public function destroy(Agreement $agreement)
    {
        DB::beginTransaction();
        try {
            $activeLocationIds = $agreement->parkingLocations()->pluck('parking_locations.id')->toArray();
            if (!empty($activeLocationIds)) {
                ParkingLocation::whereIn('id', $activeLocationIds)->update(['status' => 'tersedia']);
            }
            $agreement->delete();
            DB::commit();

            return redirect()->route('masterdata.agreements.index')->with('success', 'Perjanjian berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('AgreementController@destroy: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus perjanjian.');
        }
    }

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

    /**
     * Generate PDF untuk perjanjian.
     */
    public function generatePdf(Agreement $agreement)
    {
        $agreement->load(['leader.user', 'fieldCoordinator.user', 'activeParkingLocations.roadSection']);
        $pdf = Pdf::loadView('pdf.agreement', compact('agreement'));
        return $pdf->stream('Perjanjian_' . $agreement->agreement_number . '.pdf');
    }
}

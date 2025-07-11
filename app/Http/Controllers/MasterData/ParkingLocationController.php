<?php

namespace App\Http\Controllers\MasterData; // Namespace yang sudah kita sepakati

use App\Http\Controllers\Controller;
use App\Models\ParkingLocation;
use App\Models\RoadSection;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class ParkingLocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Ambil query pencarian dari request
        $search = $request->input('search');

        // Mulai query ParkingLocation dengan eager loading roadSection
        $query = ParkingLocation::with('roadSection');

        // Terapkan filter pencarian jika ada
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('status', 'like', '%' . $search . '%') // Tambahkan pencarian berdasarkan status
                    ->orWhereHas('roadSection', function ($roadSectionQuery) use ($search) {
                        $roadSectionQuery->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        // Ambil data lokasi parkir dengan paginasi (misal 10 per halaman)
        $parkingLocations = $query->latest()->paginate(10);

        // Kirimkan query pencarian ke view agar input search tetap terisi
        return view('staff.parking_locations.index', compact('parkingLocations', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roadSections = RoadSection::orderBy('name')->get(); // Ambil semua ruas jalan untuk dropdown
        return view('staff.parking_locations.create', compact('roadSections'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'road_section_id' => 'required|exists:road_sections,id',
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('parking_locations')->where(function ($query) use ($request) {
                    return $query->where('road_section_id', $request->road_section_id);
                }),
            ],
            'status' => 'required|string|in:tersedia,tidak_tersedia', // VALIDASI DIUBAH
        ]);

        ParkingLocation::create($validatedData);

        return redirect()->route('masterdata.parking-locations.index')
            ->with('success', 'Lokasi parkir "' . $validatedData['name'] . '" berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(ParkingLocation $parkingLocation)
    {
        // Untuk saat ini, kita tidak akan membuat halaman show terpisah
        // Mungkin akan diarahkan ke halaman edit atau detail di modal.
        return redirect()->route('masterdata.parking-locations.edit', $parkingLocation);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ParkingLocation $parkingLocation)
    {
        $roadSections = RoadSection::orderBy('name')->get(); // Ambil semua ruas jalan untuk dropdown
        return view('staff.parking_locations.edit', compact('parkingLocation', 'roadSections'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ParkingLocation $parkingLocation)
    {
        $validatedData = $request->validate([
            'road_section_id' => 'required|exists:road_sections,id',
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('parking_locations')->where(function ($query) use ($request) {
                    return $query->where('road_section_id', $request->road_section_id);
                })->ignore($parkingLocation->id), // Abaikan record saat ini
            ],
            'status' => 'required|string|in:tersedia,tidak_tersedia', // VALIDASI DIUBAH
        ]);

        $parkingLocation->update($validatedData);

        return redirect()->route('masterdata.parking-locations.index')
            ->with('success', 'Lokasi parkir "' . $parkingLocation->name . '" berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ParkingLocation $parkingLocation)
    {
        try {
            $parkingLocation->delete(); // Soft delete
        } catch (\Exception $e) {
            Log::error('ParkingLocationController@destroy: Error deleting parking location: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus lokasi parkir: ' . $e->getMessage());
        }

        return redirect()->route('masterdata.parking-locations.index')->with('success', 'Lokasi parkir berhasil dihapus!');
    }

    /**
     * Get parking locations by road section ID for AJAX requests.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $roadSectionId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getParkingLocationsByRoadSection(Request $request, $roadSectionId)
    {
        // Ambil lokasi parkir yang statusnya 'tersedia'
        // dan belum terikat perjanjian aktif
        $parkingLocations = ParkingLocation::where('road_section_id', $roadSectionId)
            ->where('status', 'tersedia')
            ->whereDoesntHave('agreements', function ($query) {
                $query->where('agreement_parking_locations.status', 'active'); // Perbaikan wherePivot
            })
            ->get(['id', 'name', 'status', 'road_section_id']); // Hanya ambil kolom yang dibutuhkan

        return response()->json($parkingLocations);
    }
}

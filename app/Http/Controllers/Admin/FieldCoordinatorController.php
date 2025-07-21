<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FieldCoordinator; // Pastikan ini diimpor
use App\Models\User; // Pastikan ini diimpor
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

class FieldCoordinatorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) // Tambahkan Request $request
    {
        // Ambil query pencarian dari request
        $search = $request->input('search');

        // Mulai query FieldCoordinator dengan eager loading user
        $query = FieldCoordinator::with('user');

        // Terapkan filter pencarian jika ada
        if ($search) {
            $query->where(function ($q) use ($search) {
                // Mencari di kolom FieldCoordinator
                $q->where('position', 'like', '%' . $search . '%')
                    ->orWhere('address', 'like', '%' . $search . '%')
                    ->orWhere('id_card_number', 'like', '%' . $search . '%')
                    ->orWhere('phone_number', 'like', '%' . $search . '%'); // Tambahkan pencarian phone_number

                // Mencari di kolom User yang berelasi
                $q->orWhereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', '%' . $search . '%')
                        ->orWhere('username', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%');
                });
            });
        }

        // Ambil data field coordinator dengan paginasi (misal 10 per halaman)
        // Pastikan untuk mengurutkan data agar konsisten
        $fieldCoordinators = $query->latest()->paginate(10);

        // Kirimkan query pencarian ke view agar input search tetap terisi
        return view('admin.field_coordinators.index', compact('fieldCoordinators', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.field_coordinators.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'id_card_number' => 'required|string|max:16|unique:field_coordinators,id_card_number',
            'phone_number' => 'required|string|max:15',
            'address' => 'required|string',
            'position' => 'required|string',
            'img' => 'nullable|image|mimes:jpeg,png,jpg|max:300', // Foto Profil
            'id_card_img' => 'required|image|mimes:jpeg,png,jpg|max:300', // Foto KTP
        ]);

        DB::beginTransaction();
        try {
            // 1. Buat User secara otomatis
            $username = strtolower(str_replace(' ', '_', $validatedData['name'])) . '_' . rand(100, 999);
            $user = User::create([
                'name' => $validatedData['name'],
                'username' => $username,
                'email' => $username . '@korlap.local', // Email dummy
                'password' => Hash::make('password'), // Password default
                'role' => 'field_coordinator',
            ]);

            // Handle upload Foto Profil (img)
            if ($request->hasFile('img')) {
                $profileImageName = time() . '_profile.' . $request->img->extension();
                $profileDestinationPath = public_path('uploads/users');

                if (!file_exists($profileDestinationPath)) {
                    mkdir($profileDestinationPath, 0775, true);
                }
                if (!is_writable($profileDestinationPath)) {
                    Log::error('FieldCoordinatorController@store: Profile image directory is NOT writable: ' . $profileDestinationPath);
                    return redirect()->back()->withInput()->with('error', 'Gagal mengunggah foto profil: Direktori tidak dapat ditulis.');
                }

                try {
                    $request->img->move($profileDestinationPath, $profileImageName);
                    $user->img = 'uploads/users/' . $profileImageName;
                    $user->save();
                } catch (\Exception $e) {
                    Log::error('FieldCoordinatorController@store: Error moving profile image: ' . $e->getMessage());
                    return redirect()->back()->withInput()->with('error', 'Gagal mengunggah foto profil: ' . $e->getMessage());
                }
            }

            // Handle upload Foto KTP (id_card_img)
            $idCardImageName = time() . '_idcard.' . $request->id_card_img->extension();
            $idCardDestinationPath = public_path('uploads/id_cards');

            if (!file_exists($idCardDestinationPath)) {
                mkdir($idCardDestinationPath, 0775, true);
            }
            if (!is_writable($idCardDestinationPath)) {
                Log::error('FieldCoordinatorController@store: ID Card image directory is NOT writable: ' . $idCardDestinationPath);
                return redirect()->back()->withInput()->with('error', 'Gagal mengunggah foto KTP: Direktori tidak dapat ditulis.');
            }

            try {
                $request->id_card_img->move($idCardDestinationPath, $idCardImageName);
                $idCardImagePath = 'uploads/id_cards/' . $idCardImageName;
            } catch (\Exception $e) {
                Log::error('FieldCoordinatorController@store: Error moving ID card image: ' . $e->getMessage());
                return redirect()->back()->withInput()->with('error', 'Gagal mengunggah foto KTP: ' . $e->getMessage());
            }

            // 2. Buat FieldCoordinator baru dan kaitkan dengan User yang baru dibuat
            FieldCoordinator::create([
                'user_id' => $user->id,
                'position' => $validatedData['position'],
                'address' => $validatedData['address'],
                'id_card_number' => $validatedData['id_card_number'],
                'id_card_img' => $idCardImagePath,
                'phone_number' => $validatedData['phone_number'],
            ]);

            DB::commit();

            return redirect()->route('admin.field-coordinators.index')
                ->with('success', 'Koordinator Lapangan berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating field coordinator: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan data. Terjadi kesalahan.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(FieldCoordinator $fieldCoordinator)
    {
        // Eager load relasi yang dibutuhkan untuk efisiensi
        $fieldCoordinator->load(['user', 'agreements.depositTransactions', 'agreements.activeParkingLocations']);

        // Menghitung total setoran tervalidasi dari semua perjanjian korlap ini
        $totalValidatedDeposit = $fieldCoordinator->agreements->flatMap(function ($agreement) {
            return $agreement->depositTransactions->where('is_validated', true);
        })->sum('amount');

        // Menghitung jumlah PKS aktif
        $activeAgreementsCount = $fieldCoordinator->agreements->where('status', 'active')->count();

        // ✅ DATA BARU: Mengambil semua lokasi parkir unik dari PKS yang aktif
        $activeParkingLocations = $fieldCoordinator->agreements
            ->where('status', 'active')
            ->flatMap->activeParkingLocations
            ->unique('id');

        // ✅ DATA BARU: Menghitung jumlah lokasi parkir unik
        $totalParkingLocationsCount = $activeParkingLocations->count();

        // Mengambil semua transaksi dari semua perjanjian untuk ditampilkan di riwayat
        $allTransactions = $fieldCoordinator->agreements->flatMap->depositTransactions->sortByDesc('deposit_date');

        return view('admin.field_coordinators.show', compact(
            'fieldCoordinator',
            'totalValidatedDeposit',
            'activeAgreementsCount',
            'totalParkingLocationsCount', // Kirim data jumlah lokasi
            'activeParkingLocations',     // Kirim data lokasi
            'allTransactions'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FieldCoordinator $fieldCoordinator)
    {
        return view('admin.field_coordinators.edit', compact('fieldCoordinator'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FieldCoordinator $fieldCoordinator)
    {
        // ✅ Validasi disederhanakan, tidak ada lagi validasi untuk username, email, password
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'id_card_number' => ['required', 'string', 'max:16', Rule::unique('field_coordinators')->ignore($fieldCoordinator->id)],
            'phone_number' => 'required|string|max:15',
            'address' => 'required|string',
            'position' => 'required|string',
            'img' => 'nullable|image|mimes:jpeg,png,jpg|max:300',
            'id_card_img' => 'nullable|image|mimes:jpeg,png,jpg|max:300',
        ]);

        DB::beginTransaction();
        try {
            // 1. Update nama pada data User terkait
            $user = $fieldCoordinator->user;
            if ($user) {
                $user->name = $validatedData['name'];
                // Handle upload foto profil jika ada yang baru
                if ($request->hasFile('img')) {
                    // Hapus foto lama jika ada
                    if ($user->img && file_exists(public_path($user->img))) {
                        unlink(public_path($user->img));
                    }
                    $imagePath = 'uploads/users/' . time() . '_profile.' . $request->img->extension();
                    $request->img->move(public_path('uploads/users'), $imagePath);
                    $user->img = $imagePath;
                }
                $user->save();
            }

            // 2. Update data FieldCoordinator
            $coordinatorData = Arr::except($validatedData, ['name', 'img']);

            // Handle upload foto KTP jika ada yang baru
            if ($request->hasFile('id_card_img')) {
                if ($fieldCoordinator->id_card_img && file_exists(public_path($fieldCoordinator->id_card_img))) {
                    unlink(public_path($fieldCoordinator->id_card_img));
                }
                $idCardPath = 'uploads/id_cards/' . time() . '_idcard.' . $request->id_card_img->extension();
                $request->id_card_img->move(public_path('uploads/id_cards'), $idCardPath);
                $coordinatorData['id_card_img'] = $idCardPath;
            }

            $fieldCoordinator->update($coordinatorData);

            DB::commit();

            return redirect()->route('admin.field-coordinators.index')
                ->with('success', 'Data Koordinator Lapangan berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating field coordinator: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui data.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FieldCoordinator $fieldCoordinator)
    {
        try {
            if ($fieldCoordinator->user->img && file_exists(public_path($fieldCoordinator->user->img))) {
                unlink(public_path($fieldCoordinator->user->img));
            }
            if ($fieldCoordinator->id_card_img && file_exists(public_path($fieldCoordinator->id_card_img))) {
                unlink(public_path($fieldCoordinator->id_card_img));
            }

            $fieldCoordinator->user->delete();
            $fieldCoordinator->delete();
        } catch (\Exception $e) {
            Log::error('FieldCoordinatorController@destroy: Error deleting Field Coordinator or user: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus Field Coordinator: ' . $e->getMessage());
        }

        return redirect()->route('admin.field-coordinators.index')->with('success', 'Field Coordinator berhasil dihapus!');
    }
}

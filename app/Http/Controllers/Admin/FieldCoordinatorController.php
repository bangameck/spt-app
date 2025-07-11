<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FieldCoordinator; // Pastikan ini diimpor
use App\Models\User; // Pastikan ini diimpor
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

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
            'username' => 'required|string|max:255|unique:users,username|regex:/^[a-z0-9_-]+$/',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed|not_regex:/\s/',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:300', // Foto Profil (opsional)
            'position' => 'required|string|max:255',
            'address' => 'required|string',
            'id_card_number' => 'required|string|max:16|unique:field_coordinators,id_card_number', // Nomor KTP
            'id_card_img' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:300', // Foto KTP (wajib)
            'phone_number' => 'required|string|max:20', // Nomor telepon (wajib)
        ]);

        // 1. Buat User baru dengan role 'field_coordinator'
        $user = User::create([
            'name' => $validatedData['name'],
            'username' => $validatedData['username'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'role' => 'field_coordinator', // Role otomatis 'field_coordinator'
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

        return redirect()->route('admin.field-coordinators.index')
            ->with('success', 'Field Coordinator baru berhasil ditambahkan!')
            ->with('korlap_name', $user->name);
    }

    /**
     * Display the specified resource.
     */
    public function show(FieldCoordinator $fieldCoordinator)
    {
        return view('admin.field_coordinators.show', compact('fieldCoordinator'));
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
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'username')->ignore($fieldCoordinator->user_id),
                'regex:/^[a-z0-9_-]+$/',
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($fieldCoordinator->user_id),
            ],
            'password' => 'nullable|string|min:8|confirmed|not_regex:/\s/',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:300', // Foto Profil (opsional)
            'position' => 'required|string|max:255',
            'address' => 'required|string',
            'id_card_number' => [
                'required',
                'string',
                'max:16',
                Rule::unique('field_coordinators', 'id_card_number')->ignore($fieldCoordinator->id),
            ],
            'id_card_img' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:300', // Foto KTP (opsional saat update)
            'phone_number' => 'required|string|max:20', // Nomor telepon (wajib)
        ]);

        // 1. Update data User yang terkait
        $fieldCoordinator->user->name = $validatedData['name'];
        $fieldCoordinator->user->username = $validatedData['username'];
        $fieldCoordinator->user->email = $validatedData['email'];
        if ($request->filled('password')) {
            $fieldCoordinator->user->password = Hash::make($validatedData['password']);
        }

        // Handle image update for Profile Image
        if ($request->hasFile('img')) {
            if ($fieldCoordinator->user->img && file_exists(public_path($fieldCoordinator->user->img))) {
                unlink(public_path($fieldCoordinator->user->img));
            }
            $profileImageName = time() . '_profile.' . $request->img->extension();
            $profileDestinationPath = public_path('uploads/users');
            if (!file_exists($profileDestinationPath)) {
                mkdir($profileDestinationPath, 0775, true);
            }
            if (!is_writable($profileDestinationPath)) {
                Log::error('FieldCoordinatorController@update: Profile image directory is NOT writable: ' . $profileDestinationPath);
                return redirect()->back()->withInput()->with('error', 'Gagal mengunggah foto profil: Direktori tidak dapat ditulis.');
            }
            try {
                $request->img->move($profileDestinationPath, $profileImageName);
                $fieldCoordinator->user->img = 'uploads/users/' . $profileImageName;
            } catch (\Exception $e) {
                Log::error('FieldCoordinatorController@update: Error moving profile image: ' . $e->getMessage());
                return redirect()->back()->withInput()->with('error', 'Gagal mengunggah foto profil: ' . $e->getMessage());
            }
        }
        $fieldCoordinator->user->save();

        // Handle image update for ID Card Image
        if ($request->hasFile('id_card_img')) {
            if ($fieldCoordinator->id_card_img && file_exists(public_path($fieldCoordinator->id_card_img))) {
                unlink(public_path($fieldCoordinator->id_card_img));
            }
            $idCardImageName = time() . '_idcard.' . $request->id_card_img->extension();
            $idCardDestinationPath = public_path('uploads/id_cards');
            if (!file_exists($idCardDestinationPath)) {
                mkdir($idCardDestinationPath, 0775, true);
            }
            if (!is_writable($idCardDestinationPath)) {
                Log::error('FieldCoordinatorController@update: ID Card image directory is NOT writable: ' . $idCardDestinationPath);
                return redirect()->back()->withInput()->with('error', 'Gagal mengunggah foto KTP: Direktori tidak dapat ditulis.');
            }
            try {
                $request->id_card_img->move($idCardDestinationPath, $idCardImageName);
                $fieldCoordinator->id_card_img = 'uploads/id_cards/' . $idCardImageName;
            } catch (\Exception $e) {
                Log::error('FieldCoordinatorController@update: Error moving ID card image: ' . $e->getMessage());
                return redirect()->back()->withInput()->with('error', 'Gagal mengunggah foto KTP: ' . $e->getMessage());
            }
        }

        // 2. Update data FieldCoordinator
        $fieldCoordinator->position = $validatedData['position'];
        $fieldCoordinator->address = $validatedData['address'];
        $fieldCoordinator->id_card_number = $validatedData['id_card_number'];
        $fieldCoordinator->phone_number = $validatedData['phone_number'];
        $fieldCoordinator->save();

        return redirect()->route('admin.field-coordinators.index')
            ->with('success', 'Data Field Coordinator berhasil diperbarui!')
            ->with('korlap_name', $fieldCoordinator->user->name);
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

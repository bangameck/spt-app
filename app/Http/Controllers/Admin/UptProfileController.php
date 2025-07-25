<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UptProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UptProfileController extends Controller
{
    /**
     * Terapkan middleware untuk memastikan hanya Admin yang bisa mengakses.
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    //     $this->middleware('role:admin');
    // }

    /**
     * Menampilkan halaman utama untuk profil UPT.
     */
    public function index()
    {
        // Ambil data profil pertama, atau buat baru jika belum ada.
        $profile = UptProfile::firstOrCreate(
            ['id' => 1], // Kunci untuk memastikan hanya ada 1 baris
            ['name' => 'UPT Perparkiran Dishub Pekanbaru'] // Nilai default jika baru dibuat
        );
        return view('admin.upt_profile.index', compact('profile'));
    }

    /**
     * Memperbarui data profil UPT.
     */
    public function update(Request $request)
    {
        $profile = UptProfile::firstOrCreate(['id' => 1]);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'app_name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg|max:512', // Maks 512KB
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
        ]);

        try {
            if ($request->hasFile('logo')) {
                // Hapus logo lama jika ada
                if ($profile->logo && file_exists(public_path($profile->logo))) {
                    unlink(public_path($profile->logo));
                }
                // Simpan logo baru
                $logoName = 'upt_logo.' . $request->logo->extension();
                $request->logo->move(public_path('assets/img/logos'), $logoName);
                $validatedData['logo'] = 'assets/img/logos/' . $logoName;
            }

            $profile->update($validatedData);

            return redirect()->route('admin.upt-profile.index')
                ->with('success', 'Profil UPT berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Error updating UPT profile: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memperbarui profil.');
        }
    }
}

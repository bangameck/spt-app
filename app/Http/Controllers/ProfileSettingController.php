<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ProfileSettingController extends Controller
{
    /**
     * Terapkan middleware untuk memastikan hanya user terautentikasi yang bisa mengakses.
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Menampilkan form untuk mengedit pengaturan profil pengguna.
     */
    public function edit()
    {
        return view('profile.edit', [
            'user' => Auth::user()
        ]);
    }

    /**
     * Memperbarui informasi profil pengguna.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'img' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user->name = $validated['name'];
        $user->username = $validated['username'];
        $user->email = $validated['email'];

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // Handle upload foto profil
        if ($request->hasFile('img')) {
            // ✅ PERBAIKAN 1: Gunakan file_exists() untuk pengecekan yang aman
            if ($user->img && file_exists(public_path($user->img))) {
                unlink(public_path($user->img));
            }

            // Panggil method kompresi dan simpan path yang dikembalikan
            $imagePath = $this->compressAndStoreImage($request->file('img'));
            $user->img = $imagePath;
        }

        $user->save();

        return redirect()->route('profile.settings')->with('status', 'profile-updated');
    }

    /**
     * Memperbarui password pengguna.
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'password-updated');
    }

    /**
     * Kompres dan simpan gambar ke folder public.
     */
    private function compressAndStoreImage($file)
    {
        $fileName = time() . '.' . $file->getClientOriginalExtension();
        $path = 'uploads/users/' . $fileName;

        // ✅ PERBAIKAN 2: Simpan ke public_path(), bukan storage_path()
        $destinationPath = public_path('uploads/users');

        // Buat gambar dari file yang diupload
        $image = null;
        $extension = strtolower($file->getClientOriginalExtension());
        if ($extension == 'jpg' || $extension == 'jpeg') {
            $image = imagecreatefromjpeg($file);
        } elseif ($extension == 'png') {
            $image = imagecreatefrompng($file);
        }

        if ($image) {
            // Pastikan direktori ada
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            // Simpan gambar dengan kompresi (kualitas 75%)
            if ($extension == 'jpg' || $extension == 'jpeg') {
                imagejpeg($image, $destinationPath . '/' . $fileName, 75);
            } elseif ($extension == 'png') {
                imagepng($image, $destinationPath . '/' . $fileName, 6);
            }

            imagedestroy($image);

            return $path; // Kembalikan path relatif untuk disimpan di database
        }

        // Fallback jika format tidak didukung (jarang terjadi)
        $file->move($destinationPath, $fileName);
        return $path;
    }
}

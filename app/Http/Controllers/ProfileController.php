<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Tampilkan formulir edit profil.
     */
    public function edit()
    {
        return view('profile.edit', ['user' => auth()->user()]);
    }

    /**
     * Perbarui profil pengguna.
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('penggunas')->ignore($user->id),
            ],
            'foto_profil' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'password_lama' => ['nullable', 'required_with:password_baru'],
            'password_baru' => ['nullable', 'required_with:password_lama', 'min:8', 'confirmed'],
        ]);

        // Update basic info
        $user->nama = $validated['nama'];
        $user->email = $validated['email'];

        // Handle Photo Upload
        if ($request->hasFile('foto_profil')) {
            // Delete old photo if exists
            if ($user->foto_profil) {
                Storage::disk('public')->delete($user->foto_profil);
            }

            // Store new photo
            $path = $request->file('foto_profil')->store('profile-photos', 'public');
            $user->foto_profil = $path;
        }

        // Handle Password Update
        if ($request->filled('password_lama')) {
            if (!Hash::check($request->password_lama, $user->kata_sandi)) {
                return back()->withErrors(['password_lama' => 'Password lama tidak sesuai.']);
            }
            $user->kata_sandi = $request->password_baru;
        }

        $user->save();

        return back()->with('sukses', 'Profil berhasil diperbarui.');
    }
}

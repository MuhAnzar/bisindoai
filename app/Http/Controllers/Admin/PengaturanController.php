<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Pengguna;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PengaturanController extends Controller
{
    public function index()
    {
        return view('admin.pengaturan');
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => [
                'required', 
                'email', 
                Rule::unique('penggunas')->ignore($user->id)
            ],
            'password_lama' => 'nullable|required_with:password_baru',
            'password_baru' => 'nullable|min:8|confirmed',
        ]);

        // Verify Old Password if changing password
        if ($request->filled('password_lama')) {
            if (!Hash::check($request->password_lama, $user->kata_sandi)) {
                return back()->with('gagal', 'Password lama tidak sesuai.');
            }
            $user->kata_sandi = Hash::make($request->password_baru);
        }

        $user->nama = $request->nama;
        $user->email = $request->email;
        $user->save();

        return back()->with('sukses', 'Profil berhasil diperbarui.');
    }
}

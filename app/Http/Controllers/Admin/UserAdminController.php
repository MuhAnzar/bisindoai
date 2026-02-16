<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserAdminController extends Controller
{
    public function index(Request $request): View
    {
        $query = Pengguna::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role') && in_array($request->role, ['admin', 'user'])) {
            $query->where('peran', $request->role);
        }

        $penggunas = $query->orderBy('nama')->paginate(10);

        return view('admin.user.index', compact('penggunas'));
    }

    public function create(): View
    {
        return view('admin.user.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:penggunas',
            'kata_sandi' => 'required|string|min:8',
            'peran' => 'required|in:admin,user',
        ]);

        $validated['kata_sandi'] = Hash::make($validated['kata_sandi']);
        $validated['email_terverifikasi_pada'] = now();

        Pengguna::create($validated);

        return redirect()->route('admin.user.index')->with('sukses', 'Pengguna berhasil ditambahkan');
    }

    public function edit(Pengguna $user): View
    {
        return view('admin.user.edit', compact('user'));
    }

    public function update(Request $request, Pengguna $user)
    {
        $rules = [
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:penggunas,email,' . $user->id,
            'peran' => 'required|in:admin,user',
        ];

        if ($request->filled('kata_sandi')) {
            $rules['kata_sandi'] = 'string|min:8';
        }

        $validated = $request->validate($rules);

        if ($request->filled('kata_sandi')) {
            $validated['kata_sandi'] = Hash::make($validated['kata_sandi']);
        } else {
            unset($validated['kata_sandi']);
        }

        $user->update($validated);

        return redirect()->route('admin.user.index')->with('sukses', 'Pengguna berhasil diperbarui');
    }

    public function destroy(Pengguna $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun sendiri');
        }

        $user->delete();

        return redirect()->route('admin.user.index')->with('sukses', 'Pengguna berhasil dihapus');
    }
}



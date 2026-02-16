<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

use App\Models\Abjad;
use App\Models\KataDasar;

class AutentikasiController extends Controller
{
    /**
     * Tampilkan form registrasi.
     */
    public function tampilFormRegistrasi(): View
    {
        $totalMateri = Abjad::count() + KataDasar::count();
        return view('autentikasi.registrasi', compact('totalMateri'));
    }

    /**
     * Proses registrasi pengguna baru.
     */
    public function prosesRegistrasi(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:penggunas,email'],
            'kata_sandi' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $pengguna = Pengguna::create([
            'nama' => $data['nama'],
            'email' => $data['email'],
            'kata_sandi' => $data['kata_sandi'],
            'peran' => 'user',
        ]);

        event(new Registered($pengguna));

        Auth::login($pengguna);

        return redirect()->route('halaman-utama');
    }

    /**
     * Tampilkan form login.
     */
    public function tampilFormLogin(): View
    {
        $totalPengguna = Pengguna::where('peran', 'user')->count();
        return view('autentikasi.masuk', compact('totalPengguna'));
    }

    /**
     * Proses login.
     */
    public function prosesLogin(Request $request): RedirectResponse
    {
        $kredensial = $request->validate([
            'email' => ['required', 'email'],
            'kata_sandi' => ['required'],
        ]);

        if (Auth::attempt(['email' => $kredensial['email'], 'password' => $kredensial['kata_sandi']], $request->boolean('ingat_saya'))) {
            $request->session()->regenerate();
            
            // Strict Case Sensitivity Check (Validation Request)
            $user = Auth::user();
            if ($user->email !== $request->email) {
                Auth::guard('web')->logout();
                $request->session()->invalidate();
                return back()->withErrors([
                    'email' => 'Format email harus sesuai (huruf besar/kecil berpengaruh).',
                ])->onlyInput('email');
            }

            if (Auth::user()->peran === 'admin') {
                return redirect()->intended(route('admin.dashboard'));
            }

            return redirect()->intended(route('halaman-utama'));
        }

        return back()->withErrors([
            'email' => 'Kredensial tidak sesuai.',
        ])->onlyInput('email');
    }

    /**
     * Proses keluar.
     */
    public function keluar(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('halaman-utama');
    }
}

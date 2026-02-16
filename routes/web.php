<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AutentikasiController;
use App\Http\Controllers\KamusController;
use App\Http\Controllers\LatihanController;
use App\Http\Controllers\KuisController;
use App\Http\Controllers\Admin\DashboardAdminController;
use App\Http\Controllers\Admin\UserAdminController;
use App\Http\Controllers\Admin\KontenAdminController;
use App\Http\Controllers\Admin\AnalitikAdminController;
use App\Http\Controllers\Admin\AbjadAdminController;
use App\Http\Controllers\Admin\KataDasarAdminController;
use App\Http\Controllers\Admin\KuisAdminController;
use App\Http\Controllers\ProfileController;



// Halaman utama
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('halaman-utama');

// Autentikasi
Route::get('/daftar', [AutentikasiController::class, 'tampilFormRegistrasi'])->name('daftar');
Route::post('/daftar', [AutentikasiController::class, 'prosesRegistrasi'])->name('daftar.proses');

Route::get('/masuk', [AutentikasiController::class, 'tampilFormLogin'])->name('masuk');
Route::post('/masuk', [AutentikasiController::class, 'prosesLogin'])->name('masuk.proses');

// Alias agar kompatibel dengan default Laravel (login/register)
Route::get('/login', [AutentikasiController::class, 'tampilFormLogin'])->name('login');
Route::post('/login', [AutentikasiController::class, 'prosesLogin']);
Route::get('/register', [AutentikasiController::class, 'tampilFormRegistrasi'])->name('register');
Route::post('/register', [AutentikasiController::class, 'prosesRegistrasi']);

Route::post('/keluar', [AutentikasiController::class, 'keluar'])->name('keluar');

// Dashboard (perlu login)
Route::middleware('auth')->group(function () {
    // Profil Pengguna
    Route::get('/profil', [ProfileController::class, 'edit'])->name('profil.edit');
    Route::patch('/profil', [ProfileController::class, 'update'])->name('profil.update');

    // Kamus BISINDO
    Route::get('/kamus/abjad', [KamusController::class, 'abjad'])->name('kamus.abjad');
    Route::get('/kamus/kata-dasar', [KamusController::class, 'kataDasar'])->name('kamus.kata-dasar');
    Route::post('/kamus/mark-done', [KamusController::class, 'markAsDone'])->name('kamus.mark-done');
    Route::post('/kamus/increment-progress', [KamusController::class, 'incrementProgress'])->name('kamus.increment-progress');

    // Latihan kamera
    Route::get('/latihan/deteksi', [LatihanController::class, 'deteksi'])->name('latihan.deteksi');
    Route::post('/latihan/save-session', [LatihanController::class, 'saveSession'])->name('latihan.save-session');
    Route::get('/latihan/history', [LatihanController::class, 'getHistory'])->name('latihan.history');
    Route::post('/latihan/deteksi/predict', [LatihanController::class, 'predict'])->name('latihan.predict');

    // Kuis
    Route::get('/kuis', [KuisController::class, 'index'])->name('kuis.index');
    Route::get('/kuis/riwayat', [KuisController::class, 'riwayat'])->name('kuis.riwayat');
    Route::get('/kuis/{id}', [KuisController::class, 'show'])->name('kuis.show');
    Route::get('/kuis/{id}/kerjakan', [KuisController::class, 'kerjakan'])->name('kuis.kerjakan');
    Route::post('/kuis/{id}/submit', [KuisController::class, 'submit'])->name('kuis.submit');
    Route::get('/kuis/{id}/hasil/{hasil_id}', [KuisController::class, 'hasil'])->name('kuis.hasil');

    // Reminder notification dismiss
    Route::post('/reminder/dismiss', function () {
        session(['reminder_dismissed' => true]);
        return response()->json(['success' => true]);
    })->name('reminder.dismiss');
});


// ... (kode lain tetap sama)

// Area Admin (perlu login + peran admin)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardAdminController::class, 'index'])->name('dashboard');
    
    // Manajemen User
    Route::resource('user', UserAdminController::class);
    
    // Manajemen Konten
    Route::get('/konten', [KontenAdminController::class, 'index'])->name('konten');
    Route::resource('abjad', AbjadAdminController::class);
    Route::resource('katadasar', KataDasarAdminController::class);
    Route::resource('kuis', KuisAdminController::class);
    
    Route::get('/analitik', [AnalitikAdminController::class, 'index'])->name('analitik');
    
    // Mode Cards Management
    Route::get('/mode-cards', [\App\Http\Controllers\Admin\ModeCardAdminController::class, 'index'])->name('mode-cards.index');
    Route::get('/mode-cards/{modeCard}/edit', [\App\Http\Controllers\Admin\ModeCardAdminController::class, 'edit'])->name('mode-cards.edit');
    Route::put('/mode-cards/{modeCard}', [\App\Http\Controllers\Admin\ModeCardAdminController::class, 'update'])->name('mode-cards.update');
    Route::get('/mode-cards/{modeCard}/remove-image', [\App\Http\Controllers\Admin\ModeCardAdminController::class, 'removeImage'])->name('mode-cards.remove-image');



    // Pengaturan
    Route::get('/pengaturan', [\App\Http\Controllers\Admin\PengaturanController::class, 'index'])->name('pengaturan');
    Route::post('/pengaturan', [\App\Http\Controllers\Admin\PengaturanController::class, 'update'])->name('pengaturan.update');
});

// Utility Fix
Route::get('/fix-passwords', function () {
    // 1. Fix Admin
    $admin = \App\Models\Pengguna::where('email', 'admin@bisindo.com')->first();
    if ($admin) {
        $admin->kata_sandi = 'admin123'; // Model casts to hashed automatically
        $admin->save();
        echo "Admin reset: admin123<br>";
    }

    // 2. Fix All other users
    $users = \App\Models\Pengguna::where('email', '!=', 'admin@bisindo.com')->get();
    foreach ($users as $u) {
        $u->kata_sandi = 'password123';
        $u->save();
        echo "User {$u->email} reset: password123<br>";
    }
    
    return "Selesai. Silakan login kembali.";
});

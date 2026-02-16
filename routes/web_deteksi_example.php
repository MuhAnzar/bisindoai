// ============================================================
// ROUTES UNTUK INTEGRASI LARAVEL + PYTHON API
// ============================================================
// Tambahkan routes ini ke file: routes/web.php
//
// CARA PAKAI:
// 1. Buka file: routes/web.php
// 2. Copy bagian yang dibutuhkan di bawah ini
// 3. Paste di akhir file routes/web.php
// ============================================================

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DeteksiController;

/*
|--------------------------------------------------------------------------
| BISINDO Detection Routes
|--------------------------------------------------------------------------
|
| Routes untuk fitur deteksi bahasa isyarat BISINDO real-time
| menggunakan webcam dan Python ML API
|
*/

// Route tanpa authentication (public access)
Route::prefix('deteksi')->name('deteksi.')->group(function () {
    
    // Halaman utama deteksi (webcam interface)
    Route::get('/', [DeteksiController::class, 'index'])->name('index');
    
    // API endpoint untuk prediksi (dipanggil dari JavaScript)
    Route::post('/predict', [DeteksiController::class, 'predict'])->name('predict');
    
    // Health check endpoint (cek status Python API)
    Route::get('/health', [DeteksiController::class, 'health'])->name('health');
});


// ============================================================
// ALTERNATIF: Dengan Authentication (recommended untuk production)
// ============================================================
// Uncomment jika ingin fitur hanya bisa diakses user yang sudah login

/*
Route::middleware(['auth'])->prefix('deteksi')->name('deteksi.')->group(function () {
    Route::get('/', [DeteksiController::class, 'index'])->name('index');
    Route::post('/predict', [DeteksiController::class, 'predict'])->name('predict');
    Route::get('/health', [DeteksiController::class, 'health'])->name('health');
});
*/


// ============================================================
// ALTERNATIF: Dengan Rate Limiting (recommended untuk production)
// ============================================================
// Membatasi jumlah request untuk mencegah abuse

/*
Route::prefix('deteksi')->name('deteksi.')->group(function () {
    Route::get('/', [DeteksiController::class, 'index'])->name('index');
    
    // Limit: 60 requests per minute
    Route::middleware(['throttle:60,1'])->group(function () {
        Route::post('/predict', [DeteksiController::class, 'predict'])->name('predict');
    });
    
    Route::get('/health', [DeteksiController::class, 'health'])->name('health');
});
*/


// ============================================================
// TESTING ROUTES (Optional - untuk testing API)
// ============================================================
// Route sederhana untuk test koneksi ke Python API

/*
Route::get('/test-python-api', function () {
    try {
        $response = \Illuminate\Support\Facades\Http::timeout(3)
            ->get('http://127.0.0.1:5000/health');
        
        return response()->json([
            'laravel_status' => 'OK',
            'python_api_status' => $response->successful() ? 'OK' : 'ERROR',
            'python_api_response' => $response->json()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'laravel_status' => 'OK',
            'python_api_status' => 'ERROR',
            'error' => $e->getMessage()
        ]);
    }
});
*/


// ============================================================
// CONTOH INTEGRASI DENGAN ADMIN PANEL
// ============================================================
// Jika Anda punya admin panel dan ingin menambahkan fitur deteksi di sana

/*
// Admin routes (dengan middleware admin)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Admin bisa lihat dashboard deteksi
    Route::get('/deteksi/dashboard', [Admin\DeteksiAdminController::class, 'dashboard'])
         ->name('deteksi.dashboard');
    
    // Admin bisa lihat history deteksi
    Route::get('/deteksi/history', [Admin\DeteksiAdminController::class, 'history'])
         ->name('deteksi.history');
    
    // Admin bisa upload model baru
    Route::post('/deteksi/upload-model', [Admin\DeteksiAdminController::class, 'uploadModel'])
         ->name('deteksi.upload-model');
});

// User routes (untuk siswa/pengguna biasa)
Route::middleware(['auth'])->group(function () {
    Route::get('/deteksi', [DeteksiController::class, 'index'])->name('deteksi.index');
    Route::post('/deteksi/predict', [DeteksiController::class, 'predict'])->name('deteksi.predict');
    
    // User bisa save hasil deteksi
    Route::post('/deteksi/save-result', [DeteksiController::class, 'saveResult'])
         ->name('deteksi.save-result');
    
    // User bisa lihat history mereka sendiri
    Route::get('/deteksi/my-history', [DeteksiController::class, 'myHistory'])
         ->name('deteksi.my-history');
});
*/


// ============================================================
// 📌 NOTES:
// ============================================================
// 
// 1. Endpoint URLs:
//    - http://127.0.0.1:8000/deteksi          → Halaman deteksi
//    - http://127.0.0.1:8000/deteksi/predict  → API prediksi (POST)
//    - http://127.0.0.1:8000/deteksi/health   → Health check (GET)
//
// 2. Python API harus running di:
//    - http://127.0.0.1:5000
//
// 3. Testing:
//    - Test health: curl http://127.0.0.1:8000/deteksi/health
//    - Test page: Buka browser → http://127.0.0.1:8000/deteksi
//
// 4. Production:
//    - Gunakan authentication middleware
//    - Tambahkan rate limiting
//    - Deploy Python API di server terpisah
//    - Gunakan HTTPS (required untuk webcam access)
//
// ============================================================

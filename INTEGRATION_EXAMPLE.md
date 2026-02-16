# Laravel + Python API Integration Guide

## 📋 Overview

Panduan lengkap integrasi antara Laravel dan Python Flask API untuk deteksi bahasa isyarat BISINDO.

---

## 🔌 Cara Kerja

```
User Interface (Browser)
        ↓
    Webcam Stream
        ↓
    JavaScript (Capture frame)
        ↓
    Convert to Base64
        ↓
    POST to Laravel (/deteksi/predict)
        ↓
    Laravel Controller
        ↓
    HTTP Client → POST to Python API (127.0.0.1:5000/predict)
        ↓
    Flask API
        ↓
    Preprocess Image
        ↓
    Model Prediction (best_abjad.keras)
        ↓
    Return JSON {label, confidence}
        ↓
    Laravel receives response
        ↓
    Return JSON to JavaScript
        ↓
    Update UI with result
```

---

## 📁 File yang Dibuat

### 1. Controller Laravel
**File**: `app/Http/Controllers/DeteksiController.php`

**Methods**:
- `index()` - Menampilkan halaman deteksi
- `predict(Request $request)` - Handle prediksi dari webcam
- `checkApiHealth()` - Cek status Python API
- `health()` - API endpoint untuk health check

### 2. View Blade
**File**: `resources/views/deteksi/index.blade.php`

**Features**:
- Real-time webcam capture
- Auto-prediction setiap 500ms
- FPS counter
- Confidence indicator with color coding
- Responsive design
- Mirror effect for natural viewing

### 3. Routes
Tambahkan di `routes/web.php`:

```php
use App\Http\Controllers\DeteksiController;

// Deteksi BISINDO
Route::get('/deteksi', [DeteksiController::class, 'index'])->name('deteksi.index');
Route::post('/deteksi/predict', [DeteksiController::class, 'predict'])->name('deteksi.predict');
Route::get('/deteksi/health', [DeteksiController::class, 'health'])->name('deteksi.health');
```

---

## 🚀 Setup & Testing

### Step 1: Tambahkan Routes

Edit `routes/web.php` dan tambahkan:

```php
use App\Http\Controllers\DeteksiController;

Route::prefix('deteksi')->group(function () {
    Route::get('/', [DeteksiController::class, 'index'])->name('deteksi.index');
    Route::post('/predict', [DeteksiController::class, 'predict'])->name('deteksi.predict');
    Route::get('/health', [DeteksiController::class, 'health'])->name('deteksi.health');
});
```

### Step 2: Buat Directory untuk View

```bash
# Dari root project Laravel
mkdir resources\views\deteksi
```

### Step 3: Jalankan Python API

```bash
# Terminal 1
cd api
python app.py
```

Pastikan output menunjukkan:
```
✅ Ready to serve!
API RUNNING at http://127.0.0.1:5000
```

### Step 4: Jalankan Laravel

```bash
# Terminal 2 - dari root project
php artisan serve
```

### Step 5: Akses Aplikasi

Buka browser dan akses:
```
http://127.0.0.1:8000/deteksi
```

---

## 🎯 Testing

### 1. Test Health Check

```bash
curl http://127.0.0.1:8000/deteksi/health
```

**Expected Response:**
```json
{
  "status": "online",
  "model_loaded": true,
  "num_classes": 26,
  "image_size": 224,
  "tensorflow_version": "2.15.0"
}
```

### 2. Test UI

1. Klik "Mulai Deteksi"
2. Izinkan akses webcam
3. Tunjukkan gesture bahasa isyarat
4. Lihat hasil prediksi real-time

---

## 🔧 Konfigurasi

### Mengubah API URL

Di `DeteksiController.php`:

```php
private const API_URL = 'http://127.0.0.1:5000'; // Ubah jika berbeda
```

### Mengubah Interval Prediksi

Di `deteksi/index.blade.php`:

```javascript
const PREDICTION_INTERVAL = 500; // Dalam millisecond (500ms = 0.5 detik)
```

**Rekomendasi:**
- 300-500ms: Responsive, cocok untuk demo
- 500-1000ms: Balance antara speed dan resource
- 1000ms+: Hemat resource, cocok untuk device rendah

### Mengubah Resolusi Video

Di `deteksi/index.blade.php`:

```javascript
video: { 
    width: 640,   // Ubah sesuai kebutuhan
    height: 480,  // Ubah sesuai kebutuhan
    facingMode: 'user' // 'user' = front camera, 'environment' = back camera
}
```

---

## 📊 Response Format

### Success Response

```json
{
  "success": true,
  "label": "A",
  "confidence": 95.43
}
```

### Error Response

```json
{
  "success": false,
  "error": "Error message here"
}
```

---

## 🎨 Custom Styling

### Mengubah Warna Theme

Di `deteksi/index.blade.php`, section `<style>`:

```css
/* Background gradient */
background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);

/* Primary card */
background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);

/* Secondary card */
background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
```

### Mengubah Ukuran Detection Box

```css
.detection-box {
    width: 300px;  /* Ubah sesuai kebutuhan */
    height: 300px; /* Ubah sesuai kebutuhan */
}
```

---

## 🔍 Troubleshooting

### Error: "Cannot connect to Python API"

**Penyebab**: Python API tidak berjalan

**Solusi**:
1. Pastikan API berjalan di terminal terpisah
2. Cek apakah port 5000 tidak dipakai aplikasi lain
3. Test dengan: `curl http://127.0.0.1:5000/health`

---

### Error: "Call to undefined method"

**Penyebab**: Controller belum di-import di routes

**Solusi**:
Pastikan ada di `routes/web.php`:
```php
use App\Http\Controllers\DeteksiController;
```

---

### Status bar menunjukkan "Offline"

**Penyebab**: Laravel tidak bisa connect ke Python API

**Solusi**:
1. Cek Python API berjalan: `curl http://127.0.0.1:5000/health`
2. Cek firewall tidak block port 5000
3. Pastikan CORS enabled di Flask (sudah di-handle di `app.py`)

---

### Webcam tidak muncul

**Penyebab**: 
- Browser tidak punya permission
- Webcam dipakai aplikasi lain

**Solusi**:
1. Refresh page dan izinkan akses webcam
2. Tutup aplikasi lain yang pakai webcam (Zoom, Teams, dll)
3. Test di browser berbeda (Chrome recommended)

---

### Low FPS atau Lag

**Penyebab**: 
- Interval terlalu cepat
- Device specs rendah
- Model inference lambat

**Solusi**:
1. Tingkatkan `PREDICTION_INTERVAL` (misal 1000ms)
2. Kurangi resolusi webcam (misal 320x240)
3. Pastikan Python API tidak dalam debug mode

---

## 📱 Mobile Support

View sudah responsive dan support mobile devices.

**Catatan**:
- Gunakan browser modern (Chrome, Safari, Firefox)
- Pastikan HTTPS jika deploy ke production (webcam requirement)
- Test orientasi portrait dan landscape

---

## 🚀 Production Deployment

### 1. Security

**Tambahkan Authentication**:
```php
Route::middleware(['auth'])->group(function () {
    Route::get('/deteksi', [DeteksiController::class, 'index']);
    // ...
});
```

**Rate Limiting**:
```php
Route::middleware(['throttle:60,1'])->group(function () {
    Route::post('/deteksi/predict', [DeteksiController::class, 'predict']);
});
```

### 2. API Configuration

Untuk production, gunakan environment variable:

**.env**:
```
PYTHON_API_URL=http://localhost:5000
PYTHON_API_TIMEOUT=10
```

Di Controller:
```php
private const API_URL = env('PYTHON_API_URL', 'http://127.0.0.1:5000');
```

### 3. SSL/HTTPS

Webcam API memerlukan HTTPS di production. Setup SSL di server Anda.

### 4. Optimizations

- Enable Laravel caching
- Use queue untuk batch predictions (jika diperlukan)
- Consider using WebSocket untuk real-time connection
- Deploy Python API di server terpisah dengan proper scaling

---

## 📚 API Documentation

### Endpoint: POST /deteksi/predict

**Request**:
```
Content-Type: multipart/form-data

Fields:
- image_base64: string (base64 encoded JPEG image)
```

**Response** (Success):
```json
{
  "success": true,
  "label": "A",
  "confidence": 95.43
}
```

**Response** (Error - Connection):
```json
{
  "success": false,
  "error": "Cannot connect to Python API. Make sure the API is running at http://127.0.0.1:5000"
}
```

**Response** (Error - Validation):
```json
{
  "success": false,
  "error": "Validation failed: The image base64 field is required."
}
```

---

## 💡 Tips & Best Practices

1. **Cache Model Loading**: Model sudah di-cache di memory oleh Flask API (hanya load sekali saat start)

2. **Error Handling**: Semua error sudah di-handle dengan proper HTTP status codes

3. **Logging**: Check Laravel logs di `storage/logs/laravel.log` untuk debugging

4. **Performance**: Adjust `PREDICTION_INTERVAL` berdasarkan kebutuhan dan device capabilities

5. **User Experience**: 
   - Tambahkan loading indicator
   - Berikan feedback visual yang jelas
   - Handle error dengan user-friendly messages

6. **Testing**: 
   - Test dengan berbagai lighting conditions
   - Test dengan berbagai gestures
   - Test di berbagai browsers dan devices

---

## 🎓 Contoh Penggunaan Lain

### 1. Upload Image (bukan webcam)

Tambahkan method di Controller:

```php
public function predictUpload(Request $request)
{
    $request->validate([
        'image' => 'required|image|mimes:jpeg,png,jpg|max:2048'
    ]);

    $image = $request->file('image');
    $imageBase64 = base64_encode(file_get_contents($image->getRealPath()));

    $response = Http::timeout(10)
        ->asForm()
        ->post(self::API_URL . '/predict', [
            'image_base64' => $imageBase64
        ]);

    if ($response->successful()) {
        $data = $response->json();
        return back()->with('result', $data);
    }

    return back()->with('error', 'Prediction failed');
}
```

### 2. Batch Processing

```php
public function predictBatch(Request $request)
{
    $request->validate([
        'images' => 'required|array|min:1|max:10',
        'images.*' => 'image|mimes:jpeg,png,jpg|max:2048'
    ]);

    $results = [];
    
    foreach ($request->file('images') as $image) {
        $imageBase64 = base64_encode(file_get_contents($image->getRealPath()));
        
        $response = Http::timeout(10)
            ->asForm()
            ->post(self::API_URL . '/predict', [
                'image_base64' => $imageBase64
            ]);

        if ($response->successful()) {
            $results[] = $response->json();
        }
    }

    return response()->json(['results' => $results]);
}
```

---

## 📞 Support

Jika ada pertanyaan atau issue:
1. Check troubleshooting section
2. Check Laravel logs
3. Check Python API terminal output
4. Test API health endpoint

---

**Last Updated**: December 2025

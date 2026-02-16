# 🚀 Quick Start Guide - BISINDO Detection System

## Ringkasan Cepat

Sistem deteksi bahasa isyarat BISINDO dengan arsitektur:
```
User (Browser) → Laravel (Port 8000) → Python API (Port 5000) → Model Keras → Hasil
```

---

## 📦 Yang Sudah Dibuat

### 1. Python API Files
- ✅ `api/app.py` - Flask API server
- ✅ `api/requirements.txt` - Python dependencies  
- ✅ `api/test_api.py` - Test script untuk API
- ✅ `api/resave_kaggle.py` - Script convert model dari Kaggle
- ✅ `api/resave_model.py` - Script convert model lokal

### 2. Laravel Integration Files
- ✅ `app/Http/Controllers/DeteksiController.php` - Controller
- ✅ `resources/views/deteksi/index.blade.php` - UI Webcam
- ✅ `routes/web_deteksi_example.php` - Contoh routes

### 3. Documentation Files
- ✅ `SETUP_GUIDE.md` - Setup lengkap
- ✅ `INTEGRATION_EXAMPLE.md` - Guide integrasi Laravel
- ✅ `QUICKSTART.md` - File ini!

### 4. Helper Scripts (Windows)
- ✅ `setup.bat` - Auto setup environment
- ✅ `start_api.bat` - Start Python API

---

## ⚡ Setup Super Cepat (5 Menit)

### Step 1: Install Python Dependencies

```bash
# Jalankan auto setup
setup.bat

# ATAU manual:
cd api
pip install -r requirements.txt
```

### Step 2: Setup Laravel Routes

Buka `routes/web.php` dan tambahkan:

```php
use App\Http\Controllers\DeteksiController;

+Route::prefix('deteksi')->name('deteksi.')->group(function () {
    Route::get('/', [DeteksiController::class, 'index'])->name('index');
    Route::post('/predict', [DeteksiController::class, 'predict'])->name('predict');
    Route::get('/health', [DeteksiController::class, 'health'])->name('health');
});
```

### Step 3: Verifikasi Model Files

Pastikan ada 2 file ini:
```
storage/app/public/models/
├── best_abjad.keras      (~57 MB)
└── class_names.json      (Array 26 huruf)
```

### Step 4: Start Servers

**Terminal 1 - Python API:**
`+
``bash
start_api.bat


# ATAU manual:
cd api
python app.py
```

**Terminal 2 - Laravel:**
```bash
php artisan serve
```

### Step 5: Test!

Buka browser:
```
http://127.0.0.1:8000/deteksi
```

✅ Klik "Mulai Deteksi"  
✅ Izinkan akses webcam  
✅ Tunjukkan gesture bahasa isyarat  
✅ Lihat hasil real-time!

---

## 🧪 Testing

### 1. Test Python API

```bash
# Test health
curl http://127.0.0.1:5000/health

# Atau gunakan test script
cd api
python test_api.py
```

Expected output:
```json
{
  "status": "ok",
  "model_loaded": true,
  "num_classes": 26,
  "image_size": 224,
  "tensorflow_version": "2.15.0"
}
```

### 2. Test Laravel Integration

```bash
curl http://127.0.0.1:8000/deteksi/health
```

Expected output:
```json
{
  "status": "online",
  "model_loaded": true,
  "num_classes": 26,
  "image_size": 224,
  "tensorflow_version": "2.15.0"
}
```

---

## 🔧 Konfigurasi

### Python Dependencies

File: `api/requirements.txt`
```
flask==3.0.0
flask-cors==4.0.0
tensorflow==2.15.0
numpy==1.24.3
opencv-python==4.8.1.78
Pillow==10.1.0
```

**PENTING:** 
- Python 3.10 required
- TensorFlow 2.15.0 (kompatibel dengan model)
- NumPy 1.24.3 (downgrade dari 1.26.2 untuk compatibility)

### API Configuration

File: `api/app.py`

```python
MODEL_PATH = '../storage/app/public/models/best_abjad.keras'
LABELS_PATH = '../storage/app/public/models/class_names.json'
API_HOST = '127.0.0.1'
API_PORT = 5000
```

### Laravel Configuration

File: `app/Http/Controllers/DeteksiController.php`

```php
private const API_URL = 'http://127.0.0.1:5000';
```

---

## 🎯 Fitur UI

### Real-time Detection
- ✅ Webcam capture dengan mirror effect
- ✅ Detection box overlay
- ✅ FPS counter
- ✅ Auto-prediction setiap 500ms

### Visual Feedback
- ✅ Color-coded confidence:
  - 🟢 Green: ≥80% (Sangat yakin)
  - 🟠 Orange: 50-79% (Cukup yakin)
  - 🔴 Red: <50% (Kurang yakin)

### Status Monitoring
- ✅ Python API status indicator
- ✅ Model info display
- ✅ Error messages dengan handling

---

## 🐛 Troubleshooting Cepat

### ❌ "Python API Offline"

**Solusi:**
```bash
# Terminal baru
cd api
python app.py
```

### ❌ "Module not found"

**Solusi:**
```bash
pip install -r api/requirements.txt
```

### ❌ "Model not found"

**Solusi:**
```bash
# Cek apakah file ada
dir storage\app\public\models\

# Harus ada:
# - best_abjad.keras (~57 MB)
# - class_names.json
```

### ❌ "Webcam tidak muncul"

**Solusi:**
1. Refresh page
2. Klik "Allow" pada permission popup
3. Tutup aplikasi lain yang pakai webcam (Zoom, Teams)
4. Coba browser lain (Chrome recommended)

### ❌ "Low confidence / salah prediksi"

**Tips:**
- Pastikan lighting bagus
- Tangan jelas terlihat
- Background tidak terlalu ramai
- Tunjukkan gesture dengan jelas
- Pastikan dalam detection box (kotak hijau)

---

## 📊 Endpoints Reference

| Method | URL | Description | Auth |
|--------|-----|-------------|------|
| GET | `/deteksi` | Halaman deteksi | No |
| POST | `/deteksi/predict` | API prediksi | No |
| GET | `/deteksi/health` | Health check | No |

### Python API Endpoints

| Method | URL | Description |
|--------|-----|-------------|
| GET | `http://127.0.0.1:5000/` | Info API |
| GET | `http://127.0.0.1:5000/health` | Health check |
| POST | `http://127.0.0.1:5000/predict` | Prediksi image |

---

## 🎨 Customization

### Ubah Prediction Speed

File: `resources/views/deteksi/index.blade.php`

```javascript
const PREDICTION_INTERVAL = 500; // milliseconds

// Rekomendasi:
// - 300ms: Sangat responsif (high CPU)
// - 500ms: Balance (recommended)
// - 1000ms: Hemat resource
```

### Ubah Warna Theme

File: `resources/views/deteksi/index.blade.php`

```css
/* Background */
background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);

/* Ganti dengan warna pilihan Anda */
background: linear-gradient(135deg, #00c6ff 0%, #0072ff 100%);
```

### Ubah Detection Box Size

```css
.detection-box {
    width: 300px;  /* Default */
    height: 300px; /* Default */
    
    /* Ubah sesuai kebutuhan, misal: */
    width: 400px;
    height: 400px;
}
```

---

## 📱 Mobile Support

✅ Responsive design  
✅ Support portrait & landscape  
✅ Touch-friendly controls  
✅ Auto-adapt screen size

**Note:** Untuk production, wajib pakai HTTPS (webcam requirement)

---

## 🚀 Next Steps (Opsional)

### 1. Tambahkan Authentication

```php
Route::middleware(['auth'])->group(function () {
    Route::get('/deteksi', [DeteksiController::class, 'index']);
    // ...
});
```

### 2. Save History ke Database

Buat migration untuk tabel `deteksi_history`:

```bash
php artisan make:migration create_deteksi_history_table
```

### 3. Upload Custom Model

Buat fitur admin untuk upload model `.keras` baru

### 4. Batch Processing

Tambahkan fitur upload multiple images sekaligus

### 5. Export Results

Tambahkan fitur export hasil ke PDF/Excel

---

## 📁 Struktur File Lengkap

```
BisindoCNN/
│
├── api/                          # Python Flask API
│   ├── app.py                    # Main API
│   ├── requirements.txt          # Dependencies
│   ├── test_api.py              # Test script
│   ├── resave_kaggle.py         # Kaggle converter
│   └── resave_model.py          # Local converter
│
├── app/
│   └── Http/Controllers/
│       └── DeteksiController.php # Laravel controller
│
├── resources/views/
│   └── deteksi/
│       └── index.blade.php       # UI webcam
│
├── routes/
│   ├── web.php                   # Main routes (edit this!)
│   └── web_deteksi_example.php   # Example routes
│
├── storage/app/public/models/
│   ├── best_abjad.keras         # Model file
│   └── class_names.json         # Labels
│
├── SETUP_GUIDE.md               # Setup lengkap
├── INTEGRATION_EXAMPLE.md       # Integration guide
├── QUICKSTART.md                # File ini
├── setup.bat                    # Auto setup
└── start_api.bat                # Start API helper
```

---

## ✅ Checklist Setup

Sebelum testing, pastikan semua ini ✅:

- [ ] Python 3.10 installed
- [ ] Dependencies installed (`pip install -r requirements.txt`)
- [ ] Model files exists (`best_abjad.keras` & `class_names.json`)
- [ ] Routes added to `routes/web.php`
- [ ] Python API running (Terminal 1)
- [ ] Laravel running (Terminal 2)
- [ ] Browser can access `http://127.0.0.1:8000/deteksi`
- [ ] Webcam permission granted

---

## 💡 Tips

1. **First Time Setup**: Pakai `setup.bat` untuk auto-install dependencies

2. **Daily Use**: 
   - Terminal 1: `start_api.bat`
   - Terminal 2: `php artisan serve`

3. **Testing**: Gunakan `api/test_api.py` untuk test Python API

4. **Debugging**: 
   - Check Laravel logs: `storage/logs/laravel.log`
   - Check terminal Python API untuk error messages

5. **Performance**: 
   - Pertama kali akan lambat (model loading)
   - Setelah itu prediction cepat (model di-cache)

---

## 📞 Help

Jika ada masalah:

1. ✅ Baca troubleshooting section di atas
2. ✅ Check `SETUP_GUIDE.md` untuk detail setup
3. ✅ Check `INTEGRATION_EXAMPLE.md` untuk integration details
4. ✅ Test API dengan `test_api.py`
5. ✅ Check terminal output untuk error messages

---

**Selamat mencoba! 🎉**

Jika berhasil, Anda sekarang punya sistem deteksi bahasa isyarat real-time yang keren! 🤟

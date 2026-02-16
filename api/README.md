# BISINDO Detection API

Flask API untuk prediksi bahasa isyarat (BISINDO) menggunakan model Keras.

## 📦 Instalasi

### 1. Install Python Dependencies

```bash
# Masuk ke folder api
cd api

# Install dependencies
pip install -r requirements.txt
```

### 2. Verifikasi File Model

Pastikan file berikut ada:
- `../storage/app/public/models/best_abjad.keras`
- `../storage/app/public/models/class_names.json`

## 🚀 Menjalankan API

```bash
# Dari folder api
python app.py
```

API akan berjalan di: `http://127.0.0.1:5000`

## 📡 Endpoints

### `GET /`
Root endpoint, informasi tentang API.

### `GET /health`
Health check untuk memastikan model terload dengan baik.

**Response:**
```json
{
  "status": "ok",
  "model_loaded": true,
  "classes": 26
}
```

### `POST /predict`
Prediksi gambar bahasa isyarat.

**Request:**
- Method: `POST`
- Content-Type: `multipart/form-data`
- Body: `image_base64` (string base64 gambar)

**Response:**
```json
{
  "label": "A",
  "confidence": 95.43
}
```

## 🔧 Konfigurasi

Jika ukuran input model Anda berbeda dari 224x224, ubah variabel `IMG_SIZE` di `app.py`:

```python
IMG_SIZE = 224  # Ubah sesuai model Anda
```

## 🐛 Troubleshooting

### Error "Model not found"
Pastikan path ke model benar dan file `best_abjad.keras` ada.

### Error CORS
Sudah dihandle oleh `flask-cors`, pastikan package terinstall.

### Low confidence / wrong predictions
Periksa preprocessing di fungsi `preprocess_image()` apakah sesuai dengan training.

---

**Created for:** BISINDO CNN Project  
**Framework:** Flask + TensorFlow/Keras

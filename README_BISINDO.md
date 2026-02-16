# 🤟 BISINDO CNN - Real-time Sign Language Detection

[![Python 3.10](https://img.shields.io/badge/Python-3.10-blue.svg)](https://www.python.org/)
[![TensorFlow 2.15.0](https://img.shields.io/badge/TensorFlow-2.15.0-orange.svg)](https://www.tensorflow.org/)
[![Laravel](https://img.shields.io/badge/Laravel-8.x-red.svg)](https://laravel.com/)
[![Flask 3.0.0](https://img.shields.io/badge/Flask-3.0.0-black.svg)](https://flask.palletsprojects.com/)

Real-time Indonesian Sign Language (BISINDO) detection system using **EfficientNetV2B0** deep learning model with Laravel web interface.

![Architecture Diagram](C:/Users/Acer/.gemini/antigravity/brain/30022da8-ffc5-4414-824d-16cf4470f6b8/bisindo_architecture_diagram_1766489578957.png)

---

## 🎯 Features

- ✅ **Real-time Detection** - Webcam-based sign language recognition
- ✅ **High Accuracy** - EfficientNetV2B0 model dengan 26 classes (A-Z)
- ✅ **Modern UI** - Responsive design dengan gradient & animations
- ✅ **API Architecture** - Separation of concerns (Laravel ↔ Python)
- ✅ **Error Handling** - Comprehensive error messages
- ✅ **Easy Setup** - Automated scripts untuk quick start

---

## 🏗️ Architecture

```
User (Browser) → Laravel (8000) → Python Flask API (5000) → Keras Model → Results
```

**Components**:
- **Frontend**: HTML5 + CSS3 + Vanilla JavaScript
- **Backend**: Laravel 8.x (PHP)
- **ML API**: Flask 3.0.0 (Python 3.10)
- **Model**: TensorFlow 2.15.0 + Keras (EfficientNetV2B0)

---

## 📋 Requirements

### System Requirements
- **Python**: 3.10 (Required)
- **PHP**: 8.x
- **Laragon/XAMPP**: For Laravel
- **Webcam**: For real-time detection

### Python Dependencies
```
tensorflow==2.15.0
numpy==1.24.3
opencv-python==4.8.1.78
flask==3.0.0
flask-cors==4.0.0
Pillow==10.1.0
```

---

## ⚡ Quick Start

### 1. Clone & Navigate
```bash
cd c:\laragon\www\BisindoCNN
```

### 2. Setup Python Environment
```bash
# Auto setup (Windows)
setup.bat

# OR Manual
cd api
pip install -r requirements.txt
```

### 3. Verify Model Files
Ensure these files exist:
```
storage/app/public/models/
├── best_abjad.keras      (~57 MB)
└── class_names.json      (26 labels)
```

### 4. Setup Laravel Routes
Add to `routes/web.php`:
```php
use App\Http\Controllers\DeteksiController;

Route::prefix('deteksi')->name('deteksi.')->group(function () {
    Route::get('/', [DeteksiController::class, 'index'])->name('index');
    Route::post('/predict', [DeteksiController::class, 'predict'])->name('predict');
    Route::get('/health', [DeteksiController::class, 'health'])->name('health');
});
```

### 5. Start Servers

**Terminal 1 - Python API:**
```bash
start_api.bat
# OR: cd api && python app.py
```

**Terminal 2 - Laravel:**
```bash
php artisan serve
```

### 6. Open Browser
```
http://127.0.0.1:8000/deteksi
```

---

## 📚 Documentation

| Document | Description |
|----------|-------------|
| **[QUICKSTART.md](QUICKSTART.md)** | 5-minute setup guide |
| **[SETUP_GUIDE.md](SETUP_GUIDE.md)** | Complete setup & troubleshooting |
| **[INTEGRATION_EXAMPLE.md](INTEGRATION_EXAMPLE.md)** | Laravel integration guide |
| **[PROJECT_OVERVIEW.md](PROJECT_OVERVIEW.md)** | Complete project documentation |

---

## 🧪 Testing

### Test Python API
```bash
cd api
python test_api.py
```

### Test Health Endpoint
```bash
# Python API
curl http://127.0.0.1:5000/health

# Laravel Integration
curl http://127.0.0.1:8000/deteksi/health
```

Expected response:
```json
{
  "status": "online",
  "model_loaded": true,
  "num_classes": 26,
  "image_size": 224
}
```

---

## 📡 API Endpoints

### Laravel
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/deteksi` | Webcam detection page |
| POST | `/deteksi/predict` | Prediction API |
| GET | `/deteksi/health` | Health check |

### Python Flask
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/` | API info |
| GET | `/health` | Model status |
| POST | `/predict` | Predict image (Base64) |

---

## 🎨 Screenshots

### Main Detection Interface
- Real-time webcam stream
- Detection box overlay
- Live prediction results
- FPS counter
- Status indicators

### Features
- Mirror effect for natural viewing
- Color-coded confidence (Green/Orange/Red)
- Responsive design (Desktop & Mobile)
- Error handling with user-friendly messages

---

## 🔧 Configuration

### Change Prediction Speed
File: `resources/views/deteksi/index.blade.php`
```javascript
const PREDICTION_INTERVAL = 500; // milliseconds (default)
```

### Change API URL
File: `app/Http/Controllers/DeteksiController.php`
```php
private const API_URL = 'http://127.0.0.1:5000';
```

### Change Video Resolution
File: `resources/views/deteksi/index.blade.php`
```javascript
video: { 
    width: 640, 
    height: 480 
}
```

---

## 🐛 Troubleshooting

### Python API won't start
```bash
# Check Python version
python --version  # Should be 3.10.x

# Reinstall dependencies
cd api
pip install -r requirements.txt
```

### Model not loading
```bash
# Verify model exists
dir storage\app\public\models\best_abjad.keras

# Check file size (~57 MB)
```

### Laravel can't connect to API
```bash
# Ensure Python API is running
curl http://127.0.0.1:5000/health

# Check firewall settings
```

### Webcam not working
- Grant browser permission
- Close other apps using webcam
- Try different browser (Chrome recommended)
- Ensure HTTPS in production

More troubleshooting: [SETUP_GUIDE.md](SETUP_GUIDE.md#troubleshooting)

---

## 📂 Project Structure

```
BisindoCNN/
├── api/                               # Python Flask API
│   ├── app.py                        # Main API server
│   ├── requirements.txt              # Python dependencies
│   ├── test_api.py                   # Test script
│   ├── resave_kaggle.py             # Model converter (Kaggle)
│   └── resave_model.py              # Model converter (Local)
│
├── app/Http/Controllers/
│   └── DeteksiController.php        # Laravel controller
│
├── resources/views/deteksi/
│   └── index.blade.php              # Webcam UI
│
├── routes/
│   ├── web.php                      # Main routes (edit this!)
│   └── web_deteksi_example.php      # Example routes
│
├── storage/app/public/models/
│   ├── best_abjad.keras            # Model file (~57 MB)
│   └── class_names.json            # Labels (A-Z)
│
├── setup.bat                        # Auto setup script
├── start_api.bat                    # Start API helper
│
└── Documentation/
    ├── QUICKSTART.md               # Quick start guide
    ├── SETUP_GUIDE.md              # Complete setup
    ├── INTEGRATION_EXAMPLE.md      # Integration guide
    └── PROJECT_OVERVIEW.md         # Full documentation
```

---

## 🚀 Deployment

### Development
Already configured for local development (127.0.0.1)

### Production Checklist
- [ ] Add authentication middleware
- [ ] Enable HTTPS (required for webcam)
- [ ] Configure CORS properly
- [ ] Add rate limiting
- [ ] Deploy Python API to separate server
- [ ] Use environment variables for API URL
- [ ] Enable caching
- [ ] Setup monitoring & logging
- [ ] Optimize model (quantization/pruning)
- [ ] CDN for static assets

---

## 🔒 Security

### Implemented
- ✅ CSRF protection (Laravel)
- ✅ Input validation
- ✅ Timeout handling
- ✅ Error sanitization
- ✅ CORS configuration

### Recommended for Production
- [ ] Authentication (Laravel Auth)
- [ ] Rate limiting
- [ ] API key authentication
- [ ] HTTPS/SSL
- [ ] Input size limits
- [ ] SQL injection prevention (if using DB)

---

## 📊 Performance

### Expected Metrics
- **Model Loading**: 3-5s (one-time at startup)
- **First Prediction**: 1-2s
- **Following Predictions**: 100-300ms
- **FPS**: 2-3 FPS (with 500ms interval)

### Optimization Tips
- Use GPU if available (auto-detected by TensorFlow)
- Adjust `PREDICTION_INTERVAL` based on needs
- Reduce video resolution for slower devices
- Use model quantization for smaller file size

---

## 🧑‍💻 Development

### Adding New Features

**Save Prediction History:**
```php
// In DeteksiController.php
public function saveResult(Request $request) {
    DetectionHistory::create([
        'user_id' => auth()->id(),
        'label' => $request->label,
        'confidence' => $request->confidence,
        'image' => $request->image_base64
    ]);
}
```

**Multiple Models:**
```python
# In app.py
models = {
    'abjad': load_model('best_abjad.keras'),
    'kata': load_model('best_kata.keras')
}

@app.route('/predict/<model_type>', methods=['POST'])
def predict(model_type):
    model = models.get(model_type)
    # ...
```

---

## 🤝 Contributing

Contributions are welcome! Please:
1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

---

## 📝 License

This project is created for educational purposes.

---

## 🙏 Acknowledgments

- **EfficientNetV2** - Google AI Research
- **TensorFlow** - Google Brain Team
- **Laravel** - Taylor Otwell & Community
- **Flask** - Pallets Projects

---

## 📞 Support

### Documentation
- Quick Start: [QUICKSTART.md](QUICKSTART.md)
- Complete Setup: [SETUP_GUIDE.md](SETUP_GUIDE.md)
- Integration: [INTEGRATION_EXAMPLE.md](INTEGRATION_EXAMPLE.md)

### Common Issues
- Check [Troubleshooting section](#-troubleshooting)
- Review logs: `storage/logs/laravel.log`
- Check Python API terminal output

---

## 📈 Roadmap

### Current (v1.0)
- ✅ Real-time webcam detection
- ✅ 26 BISINDO alphabet classes
- ✅ Modern web interface
- ✅ Comprehensive documentation

### Future
- [ ] Video upload & batch processing
- [ ] User authentication & profiles
- [ ] Detection history & analytics
- [ ] Multiple sign categories (words, sentences)
- [ ] Mobile app (React Native/Flutter)
- [ ] Offline mode (TensorFlow.js)
- [ ] Multi-language support
- [ ] Dark mode

---

## 📧 Contact

For questions or issues:
- Check documentation files
- Review troubleshooting guides
- Test with provided scripts

---

## ⭐ Star History

If this project helps you, please consider giving it a star! ⭐

---

**Built with** ❤️ **for the Indonesian Deaf Community**

**Tech Stack**: Laravel + Python Flask + TensorFlow + EfficientNetV2B0

🤟 **Happy Coding!**

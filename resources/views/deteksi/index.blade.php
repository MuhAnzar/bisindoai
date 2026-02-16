<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Deteksi BISINDO Real-time</title>
    
    <!-- Preload MediaPipe Resources for Faster Loading -->
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="preconnect" href="https://storage.googleapis.com" crossorigin>
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/@mediapipe/tasks-vision@0.10.0/wasm/vision_wasm_internal.js" as="script" crossorigin>
    <link rel="preload" href="https://storage.googleapis.com/mediapipe-models/hand_landmarker/hand_landmarker/float16/1/hand_landmarker.task" as="fetch" crossorigin>
    
    <!-- MediaPipe Style -->
    <script src="https://cdn.jsdelivr.net/npm/@mediapipe/tasks-vision/vision_bundle.js" crossorigin="anonymous"></script>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh;
            overflow: hidden; /* No Scroll Body */
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            padding: 20px;
            max-width: 1100px;
            width: 100%;
            display: flex;
            gap: 20px;
            height: 90vh; /* Fixed height for viewport */
            max-height: 550px;
        }

        /* --- LEFT: Video --- */
        .video-section {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: #000;
            border-radius: 15px;
            position: relative;
            overflow: hidden;
        }

        #webcam {
            width: 100%;
            height: 100%;
            object-fit: contain;
            transform: scaleX(-1);
        }

        .video-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
        }

        .detection-box {
            position: absolute;
            /* Default Center */
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%); /* Center Anchor */
            
            width: 450px; 
            height: 450px;
            border: 3px solid #00ff00;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0, 255, 0, 0.5);
            
            /* Smooth Movement */
            transition: all 0.1s linear;
        }

        /* --- RIGHT: Controls & Results --- */
        .info-section {
            width: 300px;
            display: flex;
            flex-direction: column;
        }

        h1 {
            color: #333;
            margin-bottom: 5px;
            font-size: 1.5em;
        }

        .subtitle {
            color: #666;
            margin-bottom: 20px;
            font-size: 0.9em;
        }

        .status-bar {
            margin-bottom: 15px;
        }

        .status-item {
            padding: 10px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.85em;
            margin-bottom: 5px;
        }

        .status-online { background: #d4edda; border: 1px solid #28a745; }
        .status-offline { background: #f8d7da; border: 1px solid #dc3545; }

        .status-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }
        .status-dot.online { background: #28a745; }
        .status-dot.offline { background: #dc3545; }

        .result-panel {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-bottom: auto; /* Push controls down */
        }

        .result-card {
            padding: 15px;
            border-radius: 12px;
            text-align: center;
        }

        .result-card.word-display { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white; }
        .result-card.label { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
        .result-card.confidence { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; }
        
        .stability-indicator {
            padding: 12px;
            border-radius: 10px;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
        }
        
        .progress-bar {
            width: 100%;
            height: 12px;
            background: #e9ecef;
            border-radius: 6px;
            overflow: hidden;
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #28a745 0%, #20c997 100%);
            width: 0%;
            transition: width 0.2s ease;
        }

        .result-title { font-size: 0.8em; opacity: 0.9; text-transform: uppercase; letter-spacing: 0.5px; }
        .result-value { font-size: 2.5em; font-weight: bold; }

        .controls {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 20px;
        }

        button {
            padding: 12px;
            font-size: 1em;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            font-weight: 600;
            text-transform: uppercase;
        }

        button:hover { transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        button:active { transform: translateY(0); }

        #startBtn { background: #28a745; color: white; }
        #stopBtn { background: #dc3545; color: white; }
        #debugBtn { background: #6c757d; color: white; }



        @media (max-width: 800px) {
            body { overflow: auto; height: auto; }
            .container { flex-direction: column; height: auto; max-height: none; }
            .info-section { width: 100%; order: -1; }
            .video-section { height: 400px; }
        }
        
        /* === LOADING OVERLAY === */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            transition: opacity 0.5s ease, visibility 0.5s ease;
        }
        
        .loading-overlay.hidden {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }
        
        .loading-spinner {
            width: 60px;
            height: 60px;
            border: 4px solid rgba(255,255,255,0.3);
            border-top: 4px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-bottom: 20px;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .loading-text {
            color: white;
            font-size: 1.3em;
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        .loading-status {
            color: rgba(255,255,255,0.8);
            font-size: 0.95em;
            text-align: center;
            max-width: 300px;
        }
        
        .loading-progress {
            width: 250px;
            height: 6px;
            background: rgba(255,255,255,0.3);
            border-radius: 3px;
            margin-top: 20px;
            overflow: hidden;
        }
        
        .loading-progress-bar {
            height: 100%;
            background: white;
            width: 0%;
            transition: width 0.3s ease;
            border-radius: 3px;
        }
    </style>
</head>
<body>
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
        <div class="loading-text">🤟 Mempersiapkan Sistem</div>
        <div class="loading-status" id="loadingStatus">Memuat komponen AI...</div>
        <div class="loading-progress">
            <div class="loading-progress-bar" id="loadingProgressBar"></div>
        </div>
    </div>

    <div class="container">
        <!-- LEFT: Video -->
        <div class="video-section">
            <video id="webcam" autoplay playsinline></video>
            <div class="video-overlay">
                <div class="detection-box" id="targetBox"></div>
            </div>
        </div>

        <!-- RIGHT: Info -->
        <div class="info-section">
            <div>
                <h1>🤟 BISINDO AI</h1>
                <p class="subtitle">Real-time Recognition</p>
            </div>

            <div class="status-bar">
                <div class="status-item {{ $apiStatus['status'] === 'online' ? 'status-online' : 'status-offline' }}">
                    <div class="status-dot {{ $apiStatus['status'] === 'online' ? 'online' : 'offline' }}"></div>
                    <span>API: {{ $apiStatus['status'] === 'online' ? 'On' : 'Off' }}</span>
                </div>
            </div>

            <div class="error-message" id="errorMessage" style="display:none; color: red; font-size: 0.8em; margin-bottom: 10px;"></div>

            <div class="result-panel">
                <!-- Saved Word Display -->
                <div class="result-card word-display">
                    <div class="result-title">Kata Tersimpan</div>
                    <div class="result-value" id="savedWordDisplay" style="font-size: 1.8em; min-height: 1.2em; word-break: break-all;">-</div>
                </div>

                <!-- Current Letter Detection -->
                <div class="result-card label">
                    <div class="result-title">Huruf Saat Ini</div>
                    <div class="result-value" id="labelResult">-</div>
                </div>
                
                <!-- Stability Progress -->
                <div class="stability-indicator">
                    <div style="font-size: 0.7em; color: #666; text-transform: uppercase; margin-bottom: 5px;">Stabilitas</div>
                    <div class="progress-bar">
                        <div class="progress-fill" id="stabilityProgress"></div>
                    </div>
                    <div style="font-size: 0.75em; color: #666; margin-top: 3px;" id="stabilityText">0/2</div>
                </div>

                <div class="result-card confidence">
                    <div class="result-title">Akurasi</div>
                    <div class="result-value" id="confidenceResult">0%</div>
                </div>
            </div>

            <div class="controls">
                <button id="startBtn">▶ Mulai</button>
                <button id="stopBtn" disabled>⏹ Stop</button>
                <button id="addLetterBtn" disabled style="background: #17a2b8; color: white;">➕ Tambah Huruf</button>
                <button id="deleteLastBtn" disabled style="background: #ffc107; color: white;">⬅ Hapus Terakhir</button>
                <button id="clearWordBtn" disabled style="background: #6c757d; color: white;">🗑 Clear Semua</button>

            </div>
        </div>
    </div>

    <script type="module">
        import { FilesetResolver, HandLandmarker } from "https://cdn.jsdelivr.net/npm/@mediapipe/tasks-vision/vision_bundle.js";

        // Configuration
        const PREDICTION_INTERVAL = 200; 
        const API_ENDPOINT = "{{ config('services.python_api.url') }}/predict";
        
        // Stabilization Config
        const STABILITY_THRESHOLD = 2;      // Butuh 2 frame stabil berturut-turut (~0.4s)
        const CONFIDENCE_THRESHOLD = 70;    // Minimum 70% confidence
        const DEBOUNCE_FRAMES = 2;          // Cooldown setelah simpan huruf
        
        // Model Input Size 
        const MODEL_SIZE = 224;
        let BOX_SIZE = 450; // Dynamic now
        const DEFAULT_BOX_SIZE = 450;
        const SMOOTHING_WINDOW = 1; 

        // Elements
        const video = document.getElementById('webcam');
        const startBtn = document.getElementById('startBtn');
        const stopBtn = document.getElementById('stopBtn');

        const targetBox = document.getElementById('targetBox');
        
        const labelResult = document.getElementById('labelResult');
        const confidenceResult = document.getElementById('confidenceResult');
        const errorMessage = document.getElementById('errorMessage');

        
        // Stabilization Elements
        const savedWordDisplay = document.getElementById('savedWordDisplay');
        const stabilityProgress = document.getElementById('stabilityProgress');
        const stabilityText = document.getElementById('stabilityText');
        const addLetterBtn = document.getElementById('addLetterBtn');
        const deleteLastBtn = document.getElementById('deleteLastBtn');
        const clearWordBtn = document.getElementById('clearWordBtn');
        

        
        // Loading Elements
        const loadingOverlay = document.getElementById('loadingOverlay');
        const loadingStatus = document.getElementById('loadingStatus');
        const loadingProgressBar = document.getElementById('loadingProgressBar');

        // State
        let stream = null;
        let predictionInterval = null;

        
        let isProcessing = false;
        let predictionHistory = [];

        let isSystemReady = false;  // Track if system fully initialized
        
        // Stabilization State
        let savedWord = [];                 // Array huruf yang sudah disimpan
        let currentDetectedLetter = '';     // Huruf yang terdeteksi saat ini
        let stableCount = 0;                // Counter stabilitas
        let debounceCounter = 0;            // Cooldown counter
        let lastSavedLetter = null;         // Huruf terakhir yang disimpan

        // Smart Tracking State
        let handLandmarker = null;
        let lastVideoTime = -1;
        let currentCrop = { x: 0, y: 0, w:DEFAULT_BOX_SIZE, h:DEFAULT_BOX_SIZE };
        let targetCrop = { x: 0, y: 0, w:DEFAULT_BOX_SIZE, h:DEFAULT_BOX_SIZE };
        
        // === LOADING HELPERS ===
        function updateLoadingProgress(percent, message) {
            if (loadingProgressBar) loadingProgressBar.style.width = `${percent}%`;
            if (loadingStatus) loadingStatus.textContent = message;
        }
        
        function hideLoading() {
            if (loadingOverlay) {
                loadingOverlay.classList.add('hidden');
            }
            isSystemReady = true;
        }
        
        function showLoadingError(message) {
            if (loadingStatus) {
                loadingStatus.innerHTML = `<span style="color: #ff6b6b;">❌ ${message}</span>`;
            }
            // Still allow user to try after error
            setTimeout(() => {
                hideLoading();
            }, 2000);
        }

        // Initialize MediaPipe with Progress Feedback
        async function setupMediaPipe() {
            try {
                updateLoadingProgress(10, 'Memuat MediaPipe Vision...');
                
                const vision = await FilesetResolver.forVisionTasks(
                    "https://cdn.jsdelivr.net/npm/@mediapipe/tasks-vision@0.10.0/wasm"
                );
                
                updateLoadingProgress(40, 'Menginisialisasi Hand Landmarker...');
                
                handLandmarker = await HandLandmarker.createFromOptions(vision, {
                    baseOptions: {
                        modelAssetPath: `https://storage.googleapis.com/mediapipe-models/hand_landmarker/hand_landmarker/float16/1/hand_landmarker.task`,
                        delegate: "GPU"
                    },
                    runningMode: "VIDEO",
                    numHands: 2
                });
                
                updateLoadingProgress(70, 'Memeriksa koneksi API...');
                
                // Warmup API connection check
                try {
                    const healthCheck = await fetch("{{ config('services.python_api.url') }}/health", {
                        method: 'GET',
                        signal: AbortSignal.timeout(5000)
                    });
                    if (healthCheck.ok) {
                        updateLoadingProgress(90, 'API terhubung! Mempersiapkan UI...');
                    } else {
                        updateLoadingProgress(90, 'API tidak responsif, tetap melanjutkan...');
                    }
                } catch (apiErr) {
                    console.warn('API health check failed:', apiErr);
                    updateLoadingProgress(90, 'API tidak terjangkau, tetap melanjutkan...');
                }
                
                // Allow UI to settle
                await new Promise(r => setTimeout(r, 300));
                
                updateLoadingProgress(100, 'Sistem siap! ✅');
                console.log("MediaPipe HandLandmarker Loaded!");

                
                // Hide loading overlay with slight delay for smooth transition
                setTimeout(() => {
                    hideLoading();
                }, 500);
                
            } catch (err) {
                console.error("MediaPipe Error:", err);
                showLoadingError('Gagal memuat sistem pelacakan tangan');
                showError("Gagal memuat sistem pelacakan tangan.");
            }
        }

        // Setup on Load
        setupMediaPipe();




        // Check API status
        @if($apiStatus['status'] !== 'online')
        showError('API Offline');
        startBtn.disabled = true;
        @endif

        // Start
        startBtn.addEventListener('click', async () => {
            try {
                stream = await navigator.mediaDevices.getUserMedia({ 
                    video: { 
                        width: 640, 
                        height: 480,
                        facingMode: 'user'
                    } 
                });
                
                video.srcObject = stream;
                
                // Initialize Crop Center
                video.onloadedmetadata = () => {
                   targetCrop.x = (video.videoWidth - DEFAULT_BOX_SIZE) / 2;
                   targetCrop.y = (video.videoHeight - DEFAULT_BOX_SIZE) / 2;
                   currentCrop = { ...targetCrop };
                   
                   // Start Loops
                   if (!trackingFrameId) trackingLoop();
                   if (!predictionInterval) predictionInterval = setInterval(predictionLoop, PREDICTION_INTERVAL);
                };
                

                
                startBtn.disabled = true;
                stopBtn.disabled = false;
                addLetterBtn.disabled = false;
                deleteLastBtn.disabled = false;
                clearWordBtn.disabled = false;
                hideError();
                
            } catch (err) {
                showError('Webcam Error: ' + err.message);
                console.error(err);
            }
        });

        // Stop
        stopBtn.addEventListener('click', () => {
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
                stream = null;
            }
            
            // Stop Loops
            if (trackingFrameId) {
                cancelAnimationFrame(trackingFrameId);
                trackingFrameId = null;
            }
            if (predictionInterval) {
                clearInterval(predictionInterval);
                predictionInterval = null;
            }

            
            video.srcObject = null;
            labelResult.textContent = '-';
            confidenceResult.textContent = '0%';

            
            isProcessing = false;
            predictionHistory = [];
            
            startBtn.disabled = false;
            stopBtn.disabled = true;
            addLetterBtn.disabled = true;
            deleteLastBtn.disabled = true;
            clearWordBtn.disabled = true;
        });
        
        // === Word Control Buttons ===
        
        // Manual Add Letter
        addLetterBtn.addEventListener('click', () => {
            if (currentDetectedLetter && currentDetectedLetter !== '-') {
                saveLetter(currentDetectedLetter);
                // Reset stabilization tracking after manual save
                stableCount = 0;
                debounceCounter = DEBOUNCE_FRAMES;
                lastSavedLetter = currentDetectedLetter;
                updateStabilityUI();
                console.log('📌 Manual save:', currentDetectedLetter);
            } else {
                console.log('⚠️ No letter to save. Current:', currentDetectedLetter);
            }
        });
        
        // Delete Last Letter
        deleteLastBtn.addEventListener('click', () => {
            if (savedWord.length > 0) {
                savedWord.pop();
                updateWordDisplay();
            }
        });
        
        // Clear All
        clearWordBtn.addEventListener('click', () => {
            savedWord = [];
            stableCount = 0;
            lastSavedLetter = null;
            debounceCounter = 0;
            updateWordDisplay();
            updateStabilityUI();
        });

        // Stop
        stopBtn.addEventListener('click', () => {
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
                stream = null;
            }
            
            // Stop Loops
            if (trackingFrameId) {
                cancelAnimationFrame(trackingFrameId);
                trackingFrameId = null;
            }
            if (predictionInterval) {
                clearInterval(predictionInterval);
                predictionInterval = null;
            }

            
            video.srcObject = null;
            labelResult.textContent = '-';
            confidenceResult.textContent = '0%';

            
            isProcessing = false;
            predictionHistory = [];
            
            startBtn.disabled = false;
            stopBtn.disabled = true;
        });

        // SMART LOOPS (Decoupled)
        let trackingFrameId = null;
        let isPredictionRunning = false;

        // 1. Tracking Loop (60 FPS - Visual Only)
        async function trackingLoop() {
            if (!stream) return; // Stop if no stream

            if (video.videoWidth && handLandmarker) {
                let results = handLandmarker.detectForVideo(video, performance.now());
                
                if (results.landmarks.length > 0) {
                    // --- Hand Found ---
                    let minX = 1, minY = 1, maxX = 0, maxY = 0;
                    
                    for (const hand of results.landmarks) {
                        for (const point of hand) {
                            if (point.x < minX) minX = point.x;
                            if (point.y < minY) minY = point.y;
                            if (point.x > maxX) maxX = point.x;
                            if (point.y > maxY) maxY = point.y;
                        }
                    }
                    
                    // Convert to pixels
                    const vW = video.videoWidth;
                    const vH = video.videoHeight;
                    
                    let boxX = minX * vW;
                    let boxY = minY * vH;
                    let boxW = (maxX - minX) * vW;
                    let boxH = (maxY - minY) * vH;
                    
                    // Add Padding
                    const padding = 50;
                    let centerX = boxX + boxW / 2;
                    let centerY = boxY + boxH / 2;
                    
                    // Determine Size (Square)
                    let size = Math.max(boxW, boxH) + (padding * 2);
                    size = Math.max(size, 250); // Min
                    size = Math.min(size, 450); // Max
                    
                    // Set Target
                    targetCrop.w = size;
                    targetCrop.h = size;
                    targetCrop.x = centerX - (size / 2);
                    targetCrop.y = centerY - (size / 2);
                    
                    // Clamp
                    if (targetCrop.x < 0) targetCrop.x = 0;
                    if (targetCrop.y < 0) targetCrop.y = 0;
                    if (targetCrop.x + size > vW) targetCrop.x = vW - size;
                    if (targetCrop.y + size > vH) targetCrop.y = vH - size;
                    

                } else {
                    // --- No Hand (Fallback) ---
                    const defaultSize = DEFAULT_BOX_SIZE;
                    targetCrop.x = (video.videoWidth - defaultSize) / 2;
                    targetCrop.y = (video.videoHeight - defaultSize) / 2;
                    targetCrop.w = defaultSize;
                    targetCrop.h = defaultSize;
                    

                }
            }

            // Smooth Interpolation (Visual)
            const alpha = 0.15; // Lower = Smoother, Higher = Snappier
            currentCrop.x += (targetCrop.x - currentCrop.x) * alpha;
            currentCrop.y += (targetCrop.y - currentCrop.y) * alpha;
            currentCrop.w += (targetCrop.w - currentCrop.w) * alpha;
            currentCrop.h += (targetCrop.h - currentCrop.h) * alpha;

            // Render Box (Mirror X visually)
            if (video.videoWidth) {
                const visualX = video.videoWidth - (currentCrop.x + currentCrop.w);
                targetBox.style.width = `${currentCrop.w}px`;
                targetBox.style.height = `${currentCrop.h}px`;
                targetBox.style.top = `${currentCrop.y}px`;
                targetBox.style.left = `${visualX}px`;
                targetBox.style.transform = 'none';
            }

            // Loop
            trackingFrameId = requestAnimationFrame(trackingLoop);
        }

        // 2. Prediction Loop (Fixed Interval - e.g. 5 FPS)
        async function predictionLoop() {
            if (!video.videoWidth || isProcessing) return;
            isProcessing = true; // Use simple lock, but don't block tracking

            try {
                // Preprocessing
                const canvas = document.createElement('canvas');
                canvas.width = MODEL_SIZE;
                canvas.height = MODEL_SIZE;
                const ctx = canvas.getContext('2d');

                // Draw Cropped Region (Using latest smooth currentCrop)
                ctx.drawImage(
                    video, 
                    currentCrop.x, currentCrop.y, currentCrop.w, currentCrop.h, 
                    0, 0, MODEL_SIZE, MODEL_SIZE
                );


                
                // API Call
                const imageBase64 = canvas.toDataURL('image/webp', 0.8).split(',')[1];
                const formData = new FormData();
                formData.append('image_base64', imageBase64);
                
                const response = await fetch(API_ENDPOINT, {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Update History & Smoothing
                    predictionHistory.push({ label: data.label, confidence: data.confidence });
                    if (predictionHistory.length > SMOOTHING_WINDOW) predictionHistory.shift();
                    
                    const smoothed = calculateSmoothedResult(predictionHistory);
                    
                    // Update Current Detection for UI
                    currentDetectedLetter = smoothed.label;
                    labelResult.textContent = smoothed.label;
                    confidenceResult.textContent = smoothed.confidence.toFixed(1) + '%';
                    updateConfidenceColor(smoothed.confidence);
                    
                    // === STABILIZATION LOGIC ===
                    
                    // Decrement debounce counter
                    if (debounceCounter > 0) {
                        debounceCounter--;
                        console.log(`⏳ Cooldown: ${debounceCounter} frames remaining`);
                    }
                    
                    // Check if can process for saving
                    if (debounceCounter === 0 && smoothed.confidence >= CONFIDENCE_THRESHOLD) {
                        // Same letter as currently tracking
                        if (smoothed.label === lastSavedLetter) {
                            // Increment stable count
                            stableCount++;
                            console.log(`📊 "${smoothed.label}" stable: ${stableCount}/${STABILITY_THRESHOLD} (${smoothed.confidence.toFixed(1)}%)`);
                            
                            // Check if reached threshold
                            if (stableCount >= STABILITY_THRESHOLD) {
                                saveLetter(smoothed.label);
                                stableCount = 0;
                                debounceCounter = DEBOUNCE_FRAMES;
                            }
                        } else {
                            // Different letter detected, reset counter
                            stableCount = 1;
                            lastSavedLetter = smoothed.label;
                            console.log(`🔄 New letter detected: "${smoothed.label}" (${smoothed.confidence.toFixed(1)}%)`);
                        }
                    } else if (smoothed.confidence < CONFIDENCE_THRESHOLD) {
                        // Low confidence, reset tracking
                        if (stableCount > 0) {
                            console.log(`⚠️ Low confidence (${smoothed.confidence.toFixed(1)}%), reset tracking`);
                            stableCount = 0;
                            lastSavedLetter = null;
                        }
                    }
                    
                    // Update stability UI
                    updateStabilityUI();
                    
 
                }
                
            } catch (err) {
                console.error("Prediction Error:", err);
            } finally {
                isProcessing = false;
            }
        }
        
        function calculateSmoothedResult(history) {
            if (history.length === 0) return { label: '-', confidence: 0 };
            
            const counts = {};
            history.forEach(item => counts[item.label] = (counts[item.label] || 0) + 1);
            
            let bestLabel = history[history.length - 1].label;
            let maxCount = 0;
            
            for (const label in counts) {
                if (counts[label] > maxCount) {
                    maxCount = counts[label];
                    bestLabel = label;
                }
            }
            
            let maxConf = 0;
            history.forEach(item => {
                if (item.label === bestLabel && item.confidence > maxConf) maxConf = item.confidence;
            });
            
            return { label: bestLabel, confidence: maxConf };
        }
        
        function updateConfidenceColor(confidence) {
            const parent = confidenceResult.parentElement;
            if (confidence >= 80) parent.style.background = 'linear-gradient(135deg, #4caf50 0%, #45a049 100%)';
            else if (confidence >= 50) parent.style.background = 'linear-gradient(135deg, #ff9800 0%, #f57c00 100%)';
            else parent.style.background = 'linear-gradient(135deg, #f44336 0%, #e53935 100%)';
        }

        function showError(message) {
            errorMessage.textContent = message;
            errorMessage.style.display = 'block';
        }

        function hideError() {
            errorMessage.style.display = 'none';
        }


        
        // === Stabilization Helper Functions ===
        
        function updateStabilityUI() {
            const percentage = (stableCount / STABILITY_THRESHOLD) * 100;
            stabilityProgress.style.width = `${percentage}%`;
            stabilityText.textContent = `${stableCount}/${STABILITY_THRESHOLD}`;
        }
        
        function updateWordDisplay() {
            if (savedWord.length === 0) {
                savedWordDisplay.textContent = '-';
            } else {
                savedWordDisplay.textContent = savedWord.join('');
            }
        }
        
        function saveLetter(letter) {
            // Avoid saving duplicate consecutive letters
            if (savedWord.length === 0 || savedWord[savedWord.length - 1] !== letter) {
                savedWord.push(letter);
                updateWordDisplay();
                console.log(`✅ SAVED: "${letter}" | Word now: "${savedWord.join('')}"`);
            } else {
                console.log(`⏭️ Skipped duplicate: "${letter}"`);
            }
        }
    </script>
</body>
</html>

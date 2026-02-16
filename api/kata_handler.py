import os
import json
import numpy as np
import tensorflow as tf
from tensorflow import keras
from PIL import Image
import io
import base64
import cv2
import mediapipe as mp
from collections import deque
import time

# =========================
# HELPER FUNCTIONS (V3 LOGIC) - Matched to Training Pipeline
# =========================
mp_holistic = mp.solutions.holistic

# Patch for MediaPipe Holistic if needed (from provided code)
_base = os.path.dirname(mp.__file__)
_candidate = os.path.join(_base, "modules/holistic_landmark/holistic_landmark_cpu.binarypb")
if hasattr(mp_holistic, "_BINARYPB_FILE_PATH") and os.path.exists(_candidate):
    mp_holistic._BINARYPB_FILE_PATH = _candidate

# Configs - MUST match training exactly
T = 20
OUT_SIZE = 224
POSE_IDX = {
    "NOSE": 0,
    "L_SHOULDER": 11, "R_SHOULDER": 12,
    "L_ELBOW": 13, "R_ELBOW": 14,
    "L_WRIST": 15, "R_WRIST": 16,
    "L_HIP": 23, "R_HIP": 24
}
HAND_EDGES = mp.solutions.hands.HAND_CONNECTIONS
ARM_TH = 2
HAND_LINE_TH = 1
HAND_DOT_R = 2

# Real-time Detection Configs
MIN_GESTURE_FRAMES = 5        # Lowered to 5 (~250ms) for faster gestures
MAX_BUFFER_FRAMES = 120       # 
CONFIDENCE_THRESHOLD = 20.0   # Minimum confidence %
MOTION_THRESHOLD_FACTOR = 1.0 # 
MIN_HAND_FRAMES = 3           # 

# Debug Mode - Set to True to save frames for verification
DEBUG_SAVE_FRAMES = False  # Disabled - set True to save frames for verification
DEBUG_FRAME_DIR = os.path.join(os.path.dirname(__file__), "debug_frames")
if not os.path.exists(DEBUG_FRAME_DIR):
    os.makedirs(DEBUG_FRAME_DIR)
    print(f"[DEBUG] Created debug_frames folder: {DEBUG_FRAME_DIR}")


# =========================
# IMAGE ENHANCEMENT FUNCTIONS (Robustness for Environmental Variations)
# =========================

def preprocess_for_detection(frame):
    """
    Lightweight always-on preprocessing to improve detection in varying lighting.
    Applies histogram equalization on L-channel (LAB colorspace).
    """
    lab = cv2.cvtColor(frame, cv2.COLOR_BGR2LAB)
    l, a, b = cv2.split(lab)
    # Clip histogram to prevent over-enhancement
    clahe = cv2.createCLAHE(clipLimit=2.0, tileGridSize=(8, 8))
    l = clahe.apply(l)
    enhanced = cv2.cvtColor(cv2.merge([l, a, b]), cv2.COLOR_LAB2BGR)
    return enhanced


def apply_clahe_strong(frame):
    """Strong CLAHE for very dark conditions."""
    lab = cv2.cvtColor(frame, cv2.COLOR_BGR2LAB)
    l, a, b = cv2.split(lab)
    clahe = cv2.createCLAHE(clipLimit=4.0, tileGridSize=(8, 8))
    cl = clahe.apply(l)
    return cv2.cvtColor(cv2.merge([cl, a, b]), cv2.COLOR_LAB2BGR)


def apply_brightness_boost(frame):
    """Increase brightness for dark environments."""
    return cv2.convertScaleAbs(frame, alpha=1.1, beta=30)


def apply_contrast_boost(frame):
    """Increase contrast for washed-out images."""
    return cv2.convertScaleAbs(frame, alpha=1.4, beta=-20)


# List of enhancement strategies to try when detection fails
ENHANCEMENT_STRATEGIES = [
    ("Strong CLAHE", apply_clahe_strong),
    ("Brightness Boost", apply_brightness_boost),
    ("Contrast Boost", apply_contrast_boost),
]

def parse_landmarks(res, w, h):
    """Extract pose and hand landmarks from MediaPipe result."""
    pose_xy = {}
    if res.pose_landmarks:
        for k, idx in POSE_IDX.items():
            lm = res.pose_landmarks.landmark[idx]
            pose_xy[k] = (int(lm.x * w), int(lm.y * h))

    hands_xy = []
    if res.left_hand_landmarks:
        hands_xy.append(np.array([(int(l.x * w), int(l.y * h)) for l in res.left_hand_landmarks.landmark], dtype=np.int32))
    if res.right_hand_landmarks:
        hands_xy.append(np.array([(int(l.x * w), int(l.y * h)) for l in res.right_hand_landmarks.landmark], dtype=np.int32))

    return pose_xy, hands_xy


def flip_parsed_landmarks(pose_xy, hands_xy, w_img):
    """Flip landmarks horizontally and swap L/R keys."""
    pose_flip = {}
    for k, v in pose_xy.items():
        if "L_" in k: new_k = k.replace("L_", "R_")
        elif "R_" in k: new_k = k.replace("R_", "L_")
        else: new_k = k
        pose_flip[new_k] = (w_img - v[0], v[1])

    hands_flip = []
    for pts in hands_xy:
        new_pts = pts.copy()
        new_pts[:, 0] = (w_img - new_pts[:, 0])
        hands_flip.append(new_pts.astype(np.int32))

    return pose_flip, hands_flip


def render_v3(pose_xy, hands_xy, out_size=224):
    """
    Render pose+hands into RGB canvas - EXACT match to training render_v3.
    Returns canvas and mask [left_present, right_present].
    """
    canvas = np.zeros((out_size, out_size, 3), dtype=np.uint8)

    pts_all = []
    for v in pose_xy.values():
        pts_all.append(v)
    for hpts in hands_xy:
        for p in hpts:
            pts_all.append((int(p[0]), int(p[1])))

    if len(pts_all) < 2:
        return canvas, np.array([0, 0], dtype=np.float32)

    xs = [p[0] for p in pts_all]
    ys = [p[1] for p in pts_all]
    x1, y1, x2, y2 = min(xs), min(ys), max(xs), max(ys)

    bw, bh = max(x2 - x1, 1), max(y2 - y1, 1)
    cx, cy = x1 + bw / 2.0, y1 + bh / 2.0

    # Scale to fit in canvas with 1.3x margin - MUST match training
    max_dim = max(bw, bh) * 1.3
    scale = (out_size - 1) / max_dim
    tx, ty = out_size / 2.0 - cx * scale, out_size / 2.0 - cy * scale

    def to_scr(p):
        return (int(p[0] * scale + tx), int(p[1] * scale + ty))

    # Draw pose connections - EXACT same as training
    CONNECTIONS = [
        ("L_SHOULDER", "L_ELBOW"), ("L_ELBOW", "L_WRIST"),
        ("R_SHOULDER", "R_ELBOW"), ("R_ELBOW", "R_WRIST"),
        ("L_SHOULDER", "R_SHOULDER"),
        ("L_SHOULDER", "L_HIP"), ("R_SHOULDER", "R_HIP"),
        ("L_SHOULDER", "NOSE"), ("R_SHOULDER", "NOSE")
    ]
    for a, b in CONNECTIONS:
        if a in pose_xy and b in pose_xy:
            cv2.line(canvas, to_scr(pose_xy[a]), to_scr(pose_xy[b]), (255, 0, 0), ARM_TH)

    # Draw hands with left/right color coding
    left_pres, right_pres = 0.0, 0.0
    for pts in hands_xy:
        proj_pts = [to_scr(p) for p in pts]
        mean_x = np.mean([p[0] for p in proj_pts])
        is_left = mean_x < out_size / 2.0
        color = (0, 255, 0) if is_left else (0, 0, 255)
        if is_left:
            left_pres = 1.0
        else:
            right_pres = 1.0

        for i, j in HAND_EDGES:
            cv2.line(canvas, proj_pts[i], proj_pts[j], color, HAND_LINE_TH)
        for p in proj_pts:
            cv2.circle(canvas, p, HAND_DOT_R, color, -1)

    return canvas, np.array([left_pres, right_pres], dtype=np.float32)


def trim_active_segment_by_motion(frames, margin=2):
    """
    MAD-based motion detection to find active gesture segment.
    EXACT match to training pipeline.
    """
    if len(frames) < 10:
        return 0, len(frames) - 1

    gray = [cv2.resize(cv2.cvtColor(f, cv2.COLOR_BGR2GRAY), (160, 160)) for f in frames]
    motion = [0.0]
    for i in range(1, len(gray)):
        motion.append(float(np.mean(cv2.absdiff(gray[i], gray[i - 1]))))
    motion = np.array(motion, dtype=np.float32)

    k = 5
    motion_s = np.convolve(motion, np.ones(k) / k, mode="same") if len(motion) >= k else motion

    med = np.median(motion_s)
    mad = np.median(np.abs(motion_s - med)) + 1e-6
    thr = med + MOTION_THRESHOLD_FACTOR * mad

    active = np.where(motion_s > thr)[0]
    if len(active) == 0:
        return 0, len(frames) - 1

    s = int(max(active[0] - margin, 0))
    e = int(min(active[-1] + margin, len(frames) - 1))
    
    if (e - s + 1) < 5:
        return 0, len(frames) - 1
        
    return s, e


def compute_hand_motion(prev_res, curr_res, w, h):
    """Compute motion magnitude between consecutive frames based on hand landmarks."""
    if prev_res is None or curr_res is None:
        return 0.0
    
    def get_hand_center(res):
        centers = []
        if res.left_hand_landmarks:
            xs = [l.x * w for l in res.left_hand_landmarks.landmark]
            ys = [l.y * h for l in res.left_hand_landmarks.landmark]
            centers.append((np.mean(xs), np.mean(ys)))
        if res.right_hand_landmarks:
            xs = [l.x * w for l in res.right_hand_landmarks.landmark]
            ys = [l.y * h for l in res.right_hand_landmarks.landmark]
            centers.append((np.mean(xs), np.mean(ys)))
        return centers
    
    def detect_forward_hand_issue(res):
        """Detect if hands are pointing forward causing landmark instability."""
        if not res.left_hand_landmarks and not res.right_hand_landmarks:
            return False
            
        # Check for landmark instability (common when hands point forward)
        unstable_count = 0
        total_landmarks = 0
        
        for hand_landmarks in [res.left_hand_landmarks, res.right_hand_landmarks]:
            if hand_landmarks:
                total_landmarks += len(hand_landmarks.landmark)
                # Check if many landmarks have low visibility or extreme positions
                for lm in hand_landmarks.landmark:
                    if lm.visibility < 0.3 or abs(lm.x) > 1.5 or abs(lm.y) > 1.5:
                        unstable_count += 1
        
        # If more than 30% of landmarks are unstable, likely forward hand issue
        return unstable_count > 0 and (unstable_count / total_landmarks) > 0.3
    
    # Check for forward hand issues in current frame
    forward_hand_issue = detect_forward_hand_issue(curr_res)
    
    # If forward hand issue detected, rely more on pixel-based motion
    if forward_hand_issue:
        # Return reduced motion to avoid false triggers
        return 2.0  # Baseline motion for forward hand scenarios
    
    prev_centers = get_hand_center(prev_res)
    curr_centers = get_hand_center(curr_res)
    
    if not prev_centers or not curr_centers:
        return 0.0
    
    # Match closest centers and compute distance
    total_motion = 0.0
    for pc in prev_centers:
        min_dist = float('inf')
        for cc in curr_centers:
            dist = np.sqrt((pc[0] - cc[0])**2 + (pc[1] - cc[1])**2)
            min_dist = min(min_dist, dist)
        total_motion += min_dist
    
    return total_motion / len(prev_centers)


def compute_pixel_motion(prev_frame, curr_frame):
    """
    Compute motion magnitude using pixel difference - FASTER than skeleton-based.
    This detects actual visual changes immediately, without skeleton lag.
    """
    if prev_frame is None or curr_frame is None:
        return 0.0
    
    # Resize to small size for speed (80x80 is enough for motion detection)
    prev_small = cv2.resize(prev_frame, (80, 80))
    curr_small = cv2.resize(curr_frame, (80, 80))
    
    # Convert to grayscale
    prev_gray = cv2.cvtColor(prev_small, cv2.COLOR_BGR2GRAY)
    curr_gray = cv2.cvtColor(curr_small, cv2.COLOR_BGR2GRAY)
    
    # Compute absolute difference
    diff = cv2.absdiff(prev_gray, curr_gray)
    
    # Return mean difference (higher = more motion)
    return float(np.mean(diff))


# =========================
# MAIN HANDLER
# =========================
class KataModelHandler:
    def __init__(self, base_dir):
        self.base_dir = base_dir
        self.model_path = os.path.join(base_dir, '..', 'storage', 'app', 'public', 'models', 'kata', 'best_model_v3.keras')
        self.labels_path = os.path.join(base_dir, '..', 'storage', 'app', 'public', 'models', 'kata', 'labels_v3.json')
        self.model = None
        self.class_names = []
        
        # Session State - Extreme Speed Optimized
        self.buffer = []  # Store (frame, results)
        self.pre_buffer = deque(maxlen=10)  # Increased pre-buffer (10 frames) for fast gestures
        self.recording = False
        self.quiet_frames = 0
        self.STOP_PATIENCE = 3  # Slightly increased for fast gesture tolerance
        
        # Skeleton-based motion tracking (for start detection)
        self.prev_result = None
        self.motion_history = deque(maxlen=10)
        self.MOTION_START_THRESHOLD = 1.5    # LOWERED significantly to match "Pixel Motion" graph (peaks ~3.0)
        self.MOTION_STOP_THRESHOLD = 1.0     # Lowered for subtle gestures
        
        # PIXEL-BASED motion tracking (for stop detection - NO LAG)
        self.prev_frame = None
        self.pixel_motion_history = deque(maxlen=5)  # Shorter window for faster response
        self.PIXEL_MOTION_START_THRESHOLD = 1.2 # Trigger start if pixel motion > 1.2
        self.PIXEL_MOTION_STOP_THRESHOLD = 0.6  # Stop if pixel motion < 0.6
        
        # Frame skipping for performance optimization
        self.frame_counter = 0
        self.FRAME_SKIP_RATE = 2  # Process every 2nd frame (50% reduction)
        
        # Frame caching to avoid reprocessing identical frames
        self.frame_cache = {}
        self.cache_hits = 0
        self.cache_misses = 0
        
        # Performance monitoring
        self.processing_times = deque(maxlen=100)
        self.start_time = time.time()
        self.processed_frames = 0

        
        # Temporal smoothing for prediction stability
        self.prediction_history = deque(maxlen=3)  # Store last N predictions for smoothing
        self.last_prediction_time = 0
        self.PREDICTION_COOLDOWN = 0.5  # Minimum seconds between predictions
        
        # Landmark Persistence (Anti-Flicker)
        self.last_landmarks = {}
        self.missing_frames_count = 0
        
        # Load MediaPipe with SPEED-OPTIMIZED settings
        # model_complexity=1 for faster detection with minimal accuracy tradeoff
        self.holistic = mp_holistic.Holistic(
            min_detection_confidence=0.2,  # Lowered for difficult lighting
            min_tracking_confidence=0.2,   # Lowered for better tracking
            model_complexity=1,            # SPEED: 1 for faster processing
            refine_face_landmarks=False,
            enable_segmentation=False      # Disabled for speed
        )
        
        self.load_resources()
        
        # Warmup MediaPipe to avoid first-request delay
        self._warmup_mediapipe()
    
    def _warmup_mediapipe(self):
        """Run a dummy frame through MediaPipe to pre-initialize all internal buffers."""
        try:
            print("[Kata] Warming up MediaPipe Holistic...")
            # Create a dummy 224x224 black image
            dummy_frame = np.zeros((224, 224, 3), dtype=np.uint8)
            # Add some noise to make it more realistic
            dummy_frame = cv2.rectangle(dummy_frame, (50, 50), (174, 174), (100, 100, 100), -1)
            
            # Run through holistic once
            dummy_rgb = cv2.cvtColor(dummy_frame, cv2.COLOR_BGR2RGB)
            _ = self.holistic.process(dummy_rgb)
            
            print("[Kata] MediaPipe warmup complete!")
        except Exception as e:
            print(f"[Kata] Warmup warning (non-critical): {e}")

    def load_resources(self):
        print(f"[Kata] Loading Resources...")
        # Load Labels
        try:
            with open(self.labels_path, 'r', encoding='utf-8') as f:
                labels_raw = json.load(f)
            
            # labels_v3.json format: {"Apa": 0, "Apa Kabar": 1, ...}
            if isinstance(labels_raw, dict):
                first_key = next(iter(labels_raw.keys()), "")
                if not first_key.isdigit():
                    self.labels_map = {int(v): k for k, v in labels_raw.items()}
                    max_idx = max(self.labels_map.keys())
                    self.class_names = ["Unknown"] * (max_idx + 1)
                    for idx, name in self.labels_map.items():
                        self.class_names[idx] = name
                else:
                    numeric_keys = [int(k) for k in labels_raw.keys() if k.isdigit()]
                    if numeric_keys:
                        max_k = max(numeric_keys)
                        self.class_names = ["Unknown"] * (max_k + 1)
                        for k, v in labels_raw.items():
                            if k.isdigit():
                                self.class_names[int(k)] = v
                    else:
                        self.class_names = list(labels_raw.values())
            elif isinstance(labels_raw, list):
                self.class_names = labels_raw
            else:
                self.class_names = []
            
            print(f"[Kata] Labels loaded: {len(self.class_names)} classes")
            print(f"[Kata] Sample classes: {self.class_names[:5]}...")
        except Exception as e:
            print(f"[Kata] Error loading labels: {e}")
            self.class_names = []

        # Load Model
        try:
            self.model = keras.models.load_model(self.model_path, compile=False)
            print(f"[Kata] Model loaded.")
        except Exception as e:
            print(f"[Kata] Error loading model: {e}")

    def get_classes(self):
        """Return list of valid classes for validation schema."""
        return self.class_names

    def preprocess_image(self, image_input):
        """Unpack input to CV2 BGR."""
        try:
            image = None
            if isinstance(image_input, Image.Image):
                image = image_input
            elif isinstance(image_input, bytes):
                image = Image.open(io.BytesIO(image_input))
            elif isinstance(image_input, str):
                if 'base64,' in image_input:
                    image_input = image_input.split('base64,', 1)[1]
                image_bytes = base64.b64decode(image_input)
                image = Image.open(io.BytesIO(image_bytes))
            
            if image is None: return None

            img_np = np.array(image)
            if image.mode == 'RGB':
                img_bgr = cv2.cvtColor(img_np, cv2.COLOR_RGB2BGR)
            elif image.mode == 'RGBA':
                img_bgr = cv2.cvtColor(img_np, cv2.COLOR_RGBA2BGR)
            else:
                img_bgr = cv2.cvtColor(img_np, cv2.COLOR_RGB2BGR)
            
            return img_bgr
        except Exception as e:
            print(f"Preprocessing error: {e}")
            return None

    def process_buffer_from_results(self, buffer_data):
        """Process buffered frames into model input tensors."""
        frames = [b[0] for b in buffer_data]
        results = [b[1] for b in buffer_data]
        
        if not frames: 
            return None, None, None, None
        
        # Smart trimming using motion analysis - EXACT match to training
        s, e = trim_active_segment_by_motion(frames, margin=2)
        
        # Validate segment length
        segment_length = e - s + 1
        if segment_length < MIN_GESTURE_FRAMES:
            # If too short, use full buffer
            s, e = 0, len(frames) - 1
            segment_length = e - s + 1
        
        # Sample T frames uniformly from the active segment
        indices = np.linspace(s, e, T).round().astype(int)
        indices = np.clip(indices, 0, len(results) - 1)  # Safety clamp
        sampled_res = [results[i] for i in indices]
        h, w = frames[0].shape[:2]
        
        X_norm, M_norm = [], []
        X_flip, M_flip = [], []
        
        # DEBUG: Count frames with hands
        hands_detected_count = sum(1 for r in sampled_res 
                                    if r.left_hand_landmarks or r.right_hand_landmarks)
        print(f"[DEBUG] Hand detection: {hands_detected_count}/{len(sampled_res)} frames have hands")
        
        # Landmark Persistence (Interpolation)
        last_p = {}
        last_hnd = []
        
        for res in sampled_res:
            p, hnd = parse_landmarks(res, w, h)
            
            # --- PERSISTENCE LOGIC START ---
            # If pose is missing, try to use last known pose
            if not p and last_p:
                p = last_p
            elif p:
                last_p = p
                
            # If hands are missing, try to use last known hands
            if not hnd and last_hnd:
                hnd = last_hnd
            elif hnd:
                last_hnd = hnd
            # --- PERSISTENCE LOGIC END ---
            
            # Normal orientation
            x1, m1 = render_v3(p, hnd, OUT_SIZE)
            X_norm.append(x1)
            M_norm.append(m1)
            
            # Flipped orientation (for left/right hand invariance)
            p_flip, hnd_flip = flip_parsed_landmarks(p, hnd, w)
            x2, m2 = render_v3(p_flip, hnd_flip, OUT_SIZE)
            X_flip.append(x2)
            M_flip.append(m2)
        
        # DEBUG: Save ALL sampled frames for verification
        if DEBUG_SAVE_FRAMES and len(X_norm) > 0:
            import time as _time
            ts = int(_time.time())
            session_dir = os.path.join(DEBUG_FRAME_DIR, f"pred_{ts}")
            os.makedirs(session_dir, exist_ok=True)
            
            # Save ALL rendered skeleton frames
            for i, frame in enumerate(X_norm):
                fpath = os.path.join(session_dir, f"skeleton_{i:02d}.png")
                cv2.imwrite(fpath, cv2.cvtColor(frame, cv2.COLOR_RGB2BGR))
            
            # Save sampled RAW frames (using indices)
            for i, idx in enumerate(indices):
                if idx < len(buffer_data):
                    raw_frame, _ = buffer_data[idx]
                    fpath_raw = os.path.join(session_dir, f"raw_{i:02d}_bufidx{idx}.jpg")
                    cv2.imwrite(fpath_raw, raw_frame)
            
            print(f"[DEBUG] ✓ Saved {len(X_norm)} skeleton + {len(indices)} raw frames to: {session_dir}")
            print(f"[DEBUG] Buffer: {len(buffer_data)} frames, Sampled indices: {indices.tolist()}")
            print(f"[DEBUG] Active segment: [{s}:{e}], Hands detected: {hands_detected_count}/{len(sampled_res)}")

        
        # Return data AND hands_detected_count for threshold check
        return (np.array(X_norm, dtype=np.uint8), np.array(M_norm, dtype=np.float32),
                np.array(X_flip, dtype=np.uint8), np.array(M_flip, dtype=np.float32),
                hands_detected_count)

    def run_inference(self, X, M):
        """Run model inference on a single sequence."""
        if X is None or self.model is None:
            return None
        
        try:
            probs = self.model.predict(
                [np.expand_dims(X, 0), np.expand_dims(M, 0)], 
                verbose=0
            )[0]
            return probs
        except Exception as e:
            print(f"[Kata] Inference error: {e}")
            return None

    def enhance_tta(self, X):
        """Test-Time Augmentation: Center Crop Zoom (95% crop)."""
        if X is None or len(X) == 0: return None
        
        X_aug = []
        H, W = X[0].shape[:2]
        border = int(H * 0.05) # 5% border cut = 10% zoom effect
        
        for i in range(len(X)):
            img = X[i]
            # Center scale
            crop = img[border:H-border, border:W-border]
            X_aug.append(cv2.resize(crop, (W, H)))
            
        return np.array(X_aug, dtype=np.uint8)

    def ensemble_predictions(self, Xn, Mn, Xf, Mf):
        """
        Fast Ensemble Prediction (2x TTA).
        Averages predictions from Normal and Flipped inputs only.
        Removed "Zoom" TTA to fix "freeze" issue while keeping robustness.
        """
        probs_accum = []
        
        # 1. Normal
        p1 = self.run_inference(Xn, Mn)
        if p1 is not None: probs_accum.append(p1)
        
        # 2. Flipped
        p2 = self.run_inference(Xf, Mf)
        if p2 is not None: probs_accum.append(p2)
        
        if not probs_accum:
            return None
            
        # Robust Averaging (Ensemble)
        return np.mean(probs_accum, axis=0)

    def apply_temporal_smoothing(self, current_probs):
        """
        Apply temporal smoothing using prediction history.
        Helps reduce jitter from noisy single predictions.
        """
        if current_probs is None:
            return None
        
        self.prediction_history.append(current_probs.copy())
        
        if len(self.prediction_history) == 1:
            return current_probs
        
        # Exponential moving average with more weight on recent predictions
        weights = np.array([0.2, 0.3, 0.5])[:len(self.prediction_history)]
        weights = weights / weights.sum()  # Normalize
        
        smoothed = np.zeros_like(current_probs)
        for i, probs in enumerate(self.prediction_history):
            smoothed += weights[i] * probs
        
        return smoothed

    def format_candidates(self, probs, top_k=5):
        """Format probability array into candidate list."""
        if probs is None:
            return []
        
        top_indices = probs.argsort()[-top_k:][::-1]
        candidates = []
        
        for idx in top_indices:
            if idx < len(self.class_names):
                c_score = float(probs[idx]) * 100.0
                c_lbl = self.class_names[idx]
                candidates.append({
                    "label": str(c_lbl), 
                    "confidence": round(c_score, 2)
                })
        
        return candidates

    def predict(self, image_input):
        """
        Main prediction method - processes one frame at a time.
        Uses state machine for gesture segmentation and motion-based recording.
        """
        if self.model is None:
            return {"success": False, "error": "Model Kata belum dimuat"}

        try:
            # Start timing
            process_start = time.time()
            
            # 1. Convert to Frame
            frame = self.preprocess_image(image_input)
            if frame is None:
                return {"success": False, "error": "Invalid Image"}
            
            h, w = frame.shape[:2]  # Restore w, h extraction
            
            # 2. FLIP FRAME before MediaPipe - CRITICAL! 
            # This matches the working inference code: user sees mirror, we process mirror
            frame_flip = cv2.flip(frame, 1)
            
            # 2.5 FRAME CACHING AND SKIPPING LOGIC
            self.frame_counter += 1
            
            # Generate frame hash for caching
            frame_hash = hash(frame_flip.tobytes())
            
            # Check cache first
            if frame_hash in self.frame_cache:
                # Cache hit - reuse previous result
                res, hand_present = self.frame_cache[frame_hash]
                self.cache_hits += 1
            else:
                # Cache miss - process with MediaPipe
                skip_mediapipe = (self.frame_counter % self.FRAME_SKIP_RATE != 0)
                
                # Reuse previous result if skipping and available
                if skip_mediapipe and self.prev_result is not None:
                    res = self.prev_result
                    hand_present = (res.left_hand_landmarks is not None) or \
                                   (res.right_hand_landmarks is not None)
                else:
                    # Process with MediaPipe
                    # 2.5 ALWAYS-ON PREPROCESSING for lighting normalization
                    frame_enhanced = preprocess_for_detection(frame_flip)
                    img_rgb = cv2.cvtColor(frame_enhanced, cv2.COLOR_BGR2RGB)
                    res = self.holistic.process(img_rgb)
                    
                    # Store for potential reuse in skipped frames
                    self.prev_result = res
                    hand_present = (res.left_hand_landmarks is not None) or \
                                   (res.right_hand_landmarks is not None)
                
                # Cache the result
                self.frame_cache[frame_hash] = (res, hand_present)
                self.cache_misses += 1
                
                # Limit cache size to prevent memory issues
                if len(self.frame_cache) > 50:
                    # Remove oldest entry (FIFO)
                    oldest_key = next(iter(self.frame_cache))
                    del self.frame_cache[oldest_key]
            
            # 3. Check hand presence
            hand_present = (res.left_hand_landmarks is not None) or \
                           (res.right_hand_landmarks is not None)
                           
            # OPTIMIZED ENHANCEMENT: Only use CLAHE for difficult cases
            if not hand_present:
                try:
                    # Use only CLAHE enhancement (most effective for hand detection)
                    enhanced = apply_clahe_strong(frame_flip)
                    img_rgb_enh = cv2.cvtColor(enhanced, cv2.COLOR_BGR2RGB)
                    res_enh = self.holistic.process(img_rgb_enh)
                    
                    if (res_enh.left_hand_landmarks is not None) or (res_enh.right_hand_landmarks is not None):
                        # Found hands with CLAHE enhancement!
                        res = res_enh
                        hand_present = True
                        if DEBUG_SAVE_FRAMES:
                            debug_path = os.path.join(DEBUG_FRAME_DIR, f"enhanced_clahe_{int(time.time())}.jpg")
                            cv2.imwrite(debug_path, enhanced)
                except Exception as e:
                    print(f"[Kata] CLAHE enhancement failed: {e}")

            
            # 4. Compute motion for smarter recording decisions
            # Skeleton-based motion (may lag)
            motion_magnitude = compute_hand_motion(self.prev_result, res, w, h)
            self.motion_history.append(motion_magnitude)
            avg_motion = np.mean(self.motion_history) if self.motion_history else 0
            self.prev_result = res
            
            # PIXEL-BASED motion (NO LAG - real-time)
            pixel_motion = compute_pixel_motion(self.prev_frame, frame_flip)
            self.pixel_motion_history.append(pixel_motion)
            avg_pixel_motion = np.mean(self.pixel_motion_history) if self.pixel_motion_history else 0.0
            self.prev_frame = frame_flip.copy()  # Store for next comparison
            
            # Extract landmarks for frontend visualization
            landmarks_data = {}
            if hand_present:
                # Pose
                if res.pose_landmarks:
                    landmarks_data["pose"] = [{"x": lm.x, "y": lm.y, "visibility": lm.visibility} for lm in res.pose_landmarks.landmark]
                
                # Left Hand
                if res.left_hand_landmarks:
                    landmarks_data["left_hand"] = [{"x": lm.x, "y": lm.y} for lm in res.left_hand_landmarks.landmark]
                    
                # Right Hand
                if res.right_hand_landmarks:
                    landmarks_data["right_hand"] = [{"x": lm.x, "y": lm.y} for lm in res.right_hand_landmarks.landmark]
            
            # Detect forward hand issue (tangan mengarah ke depan)
            forward_hand_issue = False
            if hand_present:
                # Check for landmark instability typical of forward-pointing hands
                unstable_count = 0
                total_landmarks = 0
                
                for hand_landmarks in [res.left_hand_landmarks, res.right_hand_landmarks]:
                    if hand_landmarks:
                        total_landmarks += len(hand_landmarks.landmark)
                        # Check for low visibility or extreme positions
                        for lm in hand_landmarks.landmark:
                            if lm.visibility < 0.3 or abs(lm.x) > 1.2 or abs(lm.y) > 1.2:
                                unstable_count += 1
                
                # If more than 25% of landmarks are unstable, likely forward hand issue
                if total_landmarks > 0 and (unstable_count / total_landmarks) > 0.25:
                    forward_hand_issue = True
                    print(f"[Kata] Forward hand issue detected: {unstable_count}/{total_landmarks} unstable landmarks")
                    
                    # Adjust motion values for forward-pointing hands to avoid false triggers
                    motion_magnitude = max(0, motion_magnitude - 5.0)
                    pixel_motion = max(0, pixel_motion - 1.0)
                    print(f"[Kata] Adjusted motion due to forward hand: skeleton={motion_magnitude:.2f}, pixel={pixel_motion:.2f}")

            # Landmark Persistence (Anti-Flicker)
            if hand_present:
                self.last_landmarks = landmarks_data
                self.missing_frames_count = 0
            else:
                self.missing_frames_count += 1
                if self.missing_frames_count <= 1 and self.last_landmarks:
                     # Reuse last landmarks briefly (1 frame) to fix "patah" without "freeze"
                    landmarks_data = self.last_landmarks

            # 5. State Machine for gesture capture
            result_data = {
                "success": True,
                "label": "...",
                "confidence": 0.0,
                "candidates": [],
                "status": "idle",
                "landmarks": landmarks_data
            }

            # === SMART RECORDING LOGIC with PIXEL-BASED STOP DETECTION ===
            # Use PIXEL motion for stop detection (faster, no skeleton lag)
            is_static_hold = False
            if self.recording and hand_present:
                 # Check if PIXEL motion dropped below threshold (Gesture End)
                 if len(self.pixel_motion_history) >= 3:
                     recent_pixel_motion = list(self.pixel_motion_history)[-3:]
                     avg_recent_pixel = np.mean(recent_pixel_motion)
                     
                     # DEBUG: Log both motion values
                     print(f"[Kata DEBUG] Pixel Motion: {avg_recent_pixel:.2f} (th:{self.PIXEL_MOTION_STOP_THRESHOLD}), Skeleton: {avg_motion:.1f}, Buffer: {len(self.buffer)}/{MAX_BUFFER_FRAMES}")
                     
                     # Use PIXEL motion for stop detection (more responsive)
                     if avg_recent_pixel < self.PIXEL_MOTION_STOP_THRESHOLD:
                         self.quiet_frames += 1  # Treat as "quiet" even if hand is present
                         if self.quiet_frames > self.STOP_PATIENCE:
                             is_static_hold = True
                             print(f"[Kata] Gesture End Detected (Pixel Motion: {avg_recent_pixel:.2f})")
                     else:
                         self.quiet_frames = 0 # Reset if moving
            
            # Determine if we should process the buffer (hand lost OR static hold)
            should_process_buffer = self.recording and (not hand_present or is_static_hold)
            
            if hand_present and not is_static_hold:
                # Hand detected and moving - continue recording
                if not self.recording:
                    # Check if motion exceeds start threshold (SKELETON OR PIXEL)
                    if avg_motion >= self.MOTION_START_THRESHOLD or avg_pixel_motion >= self.PIXEL_MOTION_START_THRESHOLD:
                        # Start recording with pre-buffer context
                        self.recording = True
                        self.buffer = list(self.pre_buffer)
                        self.prediction_history.clear()  # Reset smoothing for new gesture
                        print(f"[Kata] Recording STARTED (Skel: {avg_motion:.1f}, Pix: {avg_pixel_motion:.1f})")
                    else:
                        # Motion too low for gesture start, stay in pre-buffer
                        print(f"[Kata] Motion too low: {avg_motion:.1f} < {self.MOTION_START_THRESHOLD}")
                        result_data["status"] = "idle"
                        result_data["label"] = f"Gerakan terlalu halus ({avg_motion:.1f})"
                
                if len(self.buffer) < MAX_BUFFER_FRAMES:
                    self.buffer.append((frame_flip, res))  # Use FLIPPED frame
                else:
                    # FORCE STOP if buffer full
                    print("[Kata] Buffer Full! Forcing processing.")
                    is_static_hold = True # Force stop logic next frame or immediately
                    should_process_buffer = True # Ensure we process
                
                # REMOVED: self.quiet_frames = 0  <-- THIS WAS THE BUG!
                # We should NOT reset quiet_frames here, it is managed by the motion logic above.
                
                result_data["status"] = "recording"
                result_data["label"] = "Merekam..."
                result_data["buffer_count"] = len(self.buffer)
                
            elif should_process_buffer:
                # Hand lost OR static hold - process the buffer
                if not is_static_hold:
                    self.quiet_frames += 1
                    if len(self.buffer) < MAX_BUFFER_FRAMES:
                        self.buffer.append((frame_flip, res))
                
                result_data["status"] = "recording"
                result_data["buffer_count"] = len(self.buffer)
                
                # Check if we should finalize (Quiet timeout OR Static Hold OR Buffer Full force)
                if self.quiet_frames > self.STOP_PATIENCE or is_static_hold or len(self.buffer) >= MAX_BUFFER_FRAMES:
                    self.recording = False
                    
                    # Validate buffer length
                    if len(self.buffer) >= MIN_GESTURE_FRAMES:
                        result_data["status"] = "processing"
                        
                        # Process buffer to model input
                        Xn, Mn, Xf, Mf, hands_detected = self.process_buffer_from_results(self.buffer)
                        
                        # Check minimum hand threshold
                        if hands_detected < MIN_HAND_FRAMES:
                            result_data["status"] = "idle"
                            result_data["label"] = "Deteksi Tangan Kurang"
                            print(f"[Kata] Skipped: Only {hands_detected}/{T} frames have hands (min: {MIN_HAND_FRAMES})")
                        else:
                            # Ensemble prediction from normal + flipped
                            probs = self.ensemble_predictions(Xn, Mn, Xf, Mf)
                            
                            if probs is not None:
                                candidates = self.format_candidates(probs, top_k=5)
                                
                                if candidates:
                                    top_conf = candidates[0]["confidence"]
                                    
                                    # Confidence threshold check
                                    if top_conf >= CONFIDENCE_THRESHOLD:
                                        result_data["label"] = candidates[0]["label"]
                                        result_data["confidence"] = top_conf
                                        result_data["candidates"] = candidates
                                        result_data["status"] = "predicted"
                                        
                                        print(f"[Kata] Prediction: {candidates[0]['label']} ({top_conf:.1f}%)")
                                    else:
                                        result_data["status"] = "idle"
                                        result_data["label"] = "Tidak Yakin"
                                        print(f"[Kata] Low confidence: {top_conf:.1f}%")
                            else:
                                result_data["status"] = "idle"
                                result_data["label"] = "Error"
                    else:
                        result_data["status"] = "idle"
                        result_data["label"] = "Terlalu Cepat"
                    # Reset buffer for next gesture
                    self.buffer = []
                    self.motion_history.clear()
                    self.pixel_motion_history.clear()  # Reset pixel motion too
                    self.quiet_frames = 0  # Reset quiet frames counter
            else:
                # Not recording, just filling pre-buffer
                self.pre_buffer.append((frame_flip, res))  # Use FLIPPED frame
                result_data["status"] = "idle"
            
            # Performance monitoring
            process_time = time.time() - process_start
            self.processing_times.append(process_time)
            self.processed_frames += 1
            
            # Calculate current FPS
            total_time = time.time() - self.start_time
            current_fps = self.processed_frames / total_time if total_time > 0 else 0
            
            # Add performance info to result
            result_data["performance"] = {
                "processing_time_ms": round(process_time * 1000, 2),
                "avg_processing_time_ms": round(np.mean(self.processing_times) * 1000, 2) if self.processing_times else 0,
                "current_fps": round(current_fps, 2),
                "cache_hits": self.cache_hits,
                "cache_misses": self.cache_misses,
                "cache_hit_ratio": round(self.cache_hits / (self.cache_hits + self.cache_misses), 3) if (self.cache_hits + self.cache_misses) > 0 else 0
            }
            
            return result_data

        except Exception as e:
            import traceback
            traceback.print_exc()
            return {"success": False, "error": str(e)}
    
    def reset_session(self):
        """Reset all session state for a fresh start."""
        self.buffer = []
        self.pre_buffer.clear()
        self.prediction_history.clear()
        self.motion_history.clear()
        self.pixel_motion_history.clear()  # Reset pixel motion
        self.prev_frame = None  # Reset pixel motion frame
        self.recording = False
        self.quiet_frames = 0
        self.prev_result = None
        print("[Kata] Session reset")

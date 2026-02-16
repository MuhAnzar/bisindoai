import os
import cv2
import glob
import json
import numpy as np
import tensorflow as tf
import mediapipe as mp
import matplotlib.pyplot as plt
import seaborn as sns

# =========================
# CONFIG
# =========================
# Placeholder paths - adjust as needed
DATA_PATH  = "/kaggle/input/data110fix/katabesar/*/*.mp4"
SAVE_DIR   = "data_cache_v3"
MODEL_FILE = "best_model_v3.keras"
LABEL_FILE = "labels_v3.json"

T = 20
OUT_SIZE = 224
BATCH_SIZE = 16
EPOCHS = 100

# Reproducibility
np.random.seed(42)
tf.random.set_seed(42)

# =========================
# MEDIAPIPE HOLISTIC PATCH (Kaggle/Py3.12 safe)
# =========================
mp_holistic = mp.solutions.holistic

_base = os.path.dirname(mp.__file__)
_candidate = os.path.join(_base, "modules/holistic_landmark/holistic_landmark_cpu.binarypb")
if hasattr(mp_holistic, "_BINARYPB_FILE_PATH") and os.path.exists(_candidate):
    mp_holistic._BINARYPB_FILE_PATH = _candidate

print("python/tf OK")
print("mediapipe:", mp.__version__)
print("holistic binarypb exists:", os.path.exists(_candidate))

# 2) RENDERING LOGIC (V3 - Head Context & Small Dots)

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
HAND_DOT_R = 2  # small dots

def extract_landmarks(frame_bgr, holistic):
    """Return pose_xy (dict) and hands_xy (list of (21,2) int32 arrays)."""
    h, w = frame_bgr.shape[:2]
    img_rgb = cv2.cvtColor(frame_bgr, cv2.COLOR_BGR2RGB)
    res = holistic.process(img_rgb)

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

def flip_geometry(pose_xy, hands_xy, w_img):
    """Mirror horizontal + swap L_/R_ keys."""
    pose_flip = {}
    for k, v in pose_xy.items():
        if "L_" in k:
            new_k = k.replace("L_", "R_")
        elif "R_" in k:
            new_k = k.replace("R_", "L_")
        else:
            new_k = k  # NOSE, etc
        pose_flip[new_k] = (w_img - v[0], v[1])

    hands_flip = []
    for pts in hands_xy:
        new_pts = pts.copy()
        new_pts[:, 0] = (w_img - new_pts[:, 0])
        hands_flip.append(new_pts.astype(np.int32))

    return pose_flip, hands_flip

def augment_geometry(pose_xy, hands_xy, w_img, h_img,
                     p_shift=0.04, p_scale=0.06, p_stretch=0.08, max_rot_deg=10):
    """
    Random affine-like augmentation on coordinates:
    shift + uniform scale + anisotropic stretch + rotation around image center.
    """
    dx = np.random.uniform(-p_shift, p_shift) * w_img
    dy = np.random.uniform(-p_shift, p_shift) * h_img
    sc = 1.0 + np.random.uniform(-p_scale, p_scale)
    sx = 1.0 + np.random.uniform(-p_stretch, p_stretch)
    sy = 1.0 + np.random.uniform(-p_stretch, p_stretch)
    ang = np.deg2rad(np.random.uniform(-max_rot_deg, max_rot_deg))

    cx, cy = w_img / 2.0, h_img / 2.0
    ca, sa = np.cos(ang), np.sin(ang)

    def tf_point(x, y):
        x0, y0 = x - cx, y - cy
        x0, y0 = x0 * sx, y0 * sy
        xr = x0 * ca - y0 * sa
        yr = x0 * sa + y0 * ca
        xn = xr * sc + cx + dx
        yn = yr * sc + cy + dy
        xn = int(np.clip(xn, 0, w_img - 1))
        yn = int(np.clip(yn, 0, h_img - 1))
        return xn, yn

    pose_aug = {k: tf_point(v[0], v[1]) for k, v in pose_xy.items()}

    hands_aug = []
    for pts in hands_xy:
        pts2 = np.array([tf_point(p[0], p[1]) for p in pts], dtype=np.int32)
        hands_aug.append(pts2)

    return pose_aug, hands_aug

def render_v3(pose_xy, hands_xy, out_size=224):
    """
    Render pose+hands into RGB canvas.
    Mask: [left_present, right_present] inferred from projected x.
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

    max_dim = max(bw, bh) * 1.3
    scale = (out_size - 1) / max_dim
    tx, ty = out_size / 2.0 - cx * scale, out_size / 2.0 - cy * scale

    def to_scr(p):
        return (int(p[0] * scale + tx), int(p[1] * scale + ty))

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

# 3) SMART TRIMMING (MAD based)

def trim_active_segment_by_motion(frames, margin=2):
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
    thr = med + 1.5 * mad

    active = np.where(motion_s > thr)[0]
    if len(active) == 0:
        return 0, len(frames) - 1

    s = int(max(active[0] - margin, 0))
    e = int(min(active[-1] + margin, len(frames) - 1))
    return s, e

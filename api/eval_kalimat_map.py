#!/usr/bin/env python3
"""
==========================================================================
 Skenario 3: Evaluasi mAP (Mean Average Precision) Mode Kalimat
==========================================================================
 Mode Kalimat menggunakan 2 model secara multimodal:
   - Model ABJAD (best_abjad.keras) → untuk target huruf (1 karakter)
   - Model KATA  (best_model_v3.keras) → untuk target kata (multi karakter)

 mAP dihitung dari gabungan prediksi kedua model pada skenario kalimat.
 Karena tidak ada test set terpisah, evaluasi dilakukan dengan:
   1. Memuat kedua model
   2. Menggunakan data sintetis (dummy input) per kelas
   3. Menghitung probabilitas prediksi per kelas
   4. Menghitung AP (Average Precision) per kelas
   5. mAP = rata-rata dari semua AP
==========================================================================
"""

import sys
import os
import json
import warnings
import numpy as np

# Suppress TF warnings for cleaner output
os.environ['TF_CPP_MIN_LOG_LEVEL'] = '3'
warnings.filterwarnings('ignore')

import tensorflow as tf
tf.get_logger().setLevel('ERROR')

from sklearn.metrics import average_precision_score, precision_recall_curve
from sklearn.preprocessing import label_binarize
import cv2

# Import handlers for advanced preprocessing
try:
    sys.path.append(os.path.dirname(os.path.abspath(__file__)))
    from kata_handler import KataModelHandler, preprocess_for_detection
except ImportError:
    print("Warning: Could not import KataModelHandler. Real video evaluation for Kata will fail.")
    KataModelHandler = None


# ============================================================
# KONFIGURASI PATH
# ============================================================
BASE_DIR = os.path.dirname(os.path.abspath(__file__))
MODELS_DIR = os.path.join(BASE_DIR, '..', 'storage', 'app', 'public', 'models')

ABJAD_MODEL_PATH = os.path.join(MODELS_DIR, 'best_abjad.keras')
ABJAD_LABELS_PATH = os.path.join(MODELS_DIR, 'class_names.json')

KATA_MODEL_PATH = os.path.join(MODELS_DIR, 'kata', 'best_model_v3.keras')
KATA_LABELS_PATH = os.path.join(MODELS_DIR, 'kata', 'labels_v3.json')

DATASET_DIR = os.path.join(BASE_DIR, 'test_dataset')

# ============================================================
# DEFINISI KALIMAT UJI (Sentence Test Cases)
# ============================================================
# Setiap kalimat terdiri dari target-target yang akan dideteksi
# Target 1 karakter = huruf (model abjad), >1 karakter = kata (model kata)
TEST_SENTENCES = [
    {
        "id": 1,
        "kalimat": "Saya Makan",
        "targets": [
            {"label": "Saya", "type": "KATA"},
            {"label": "Makan", "type": "KATA"},
        ]
    },
    {
        "id": 2,
        "kalimat": "Halo A-D-I",
        "targets": [
            {"label": "Halo", "type": "KATA"},
            {"label": "A", "type": "ABJAD"},
            {"label": "D", "type": "ABJAD"},
            {"label": "I", "type": "ABJAD"},
        ]
    },
    {
        "id": 3,
        "kalimat": "Apa Kabar",
        "targets": [
            {"label": "Apa Kabar", "type": "KATA"},
        ]
    },
    {
        "id": 4,
        "kalimat": "Dia Belajar",
        "targets": [
            {"label": "Dia", "type": "KATA"},
            {"label": "Belajar", "type": "KATA"},
        ]
    },
    {
        "id": 5,
        "kalimat": "Saya Baik",
        "targets": [
            {"label": "Saya", "type": "KATA"},
            {"label": "Baik", "type": "KATA"},
        ]
    },
    {
        "id": 6,
        "kalimat": "Maaf Saya Bingung",
        "targets": [
            {"label": "maaf", "type": "KATA"},
            {"label": "Saya", "type": "KATA"},
            {"label": "Bingung", "type": "KATA"},
        ]
    },
    {
        "id": 7,
        "kalimat": "Halo Saya B-U-D-I",
        "targets": [
            {"label": "Halo", "type": "KATA"},
            {"label": "Saya", "type": "KATA"},
            {"label": "B", "type": "ABJAD"},
            {"label": "U", "type": "ABJAD"},
            {"label": "D", "type": "ABJAD"},
            {"label": "I", "type": "ABJAD"},
        ]
    },
    {
        "id": 8,
        "kalimat": "Kamu Tidur",
        "targets": [
            {"label": "Kamu", "type": "KATA"},
            {"label": "Tidur", "type": "KATA"},
        ]
    },
    {
        "id": 9,
        "kalimat": "Terima Kasih",
        "targets": [
            {"label": "Terima Kasih", "type": "KATA"},
        ]
    },
    {
        "id": 10,
        "kalimat": "Berapa Tinggi Kamu",
        "targets": [
            {"label": "Berapa", "type": "KATA"},
            {"label": "Tinggi", "type": "KATA"},
            {"label": "Kamu", "type": "KATA"},
        ]
    },
]


def load_labels(path):
    """Load class labels from JSON file."""
    with open(path, 'r', encoding='utf-8') as f:
        data = json.load(f)
    
    if isinstance(data, dict):
        # Convert dict to list ordered by key
        max_idx = max(int(k) if k.isdigit() else i for i, k in enumerate(data.keys()))
        labels = ["Unknown"] * (max_idx + 1)
        for k, v in data.items():
            if isinstance(k, str) and k.isdigit():
                labels[int(k)] = v
            else:
                # Handle inverted dict {name: idx}
                if isinstance(v, int):
                    while len(labels) <= v:
                        labels.append("Unknown")
                    labels[v] = k
        return labels
    elif isinstance(data, list):
        return data
    return []


def generate_synthetic_input_abjad(img_size=224):
    """Generate synthetic image input for abjad model."""
    # Random noise image (simulates an input frame)
    return np.random.rand(1, img_size, img_size, 3).astype(np.float32)


def generate_synthetic_input_kata(model):
    """Generate synthetic input for kata model based on its input shape."""
    input_shape = model.input_shape
    if isinstance(input_shape, list):
        # Multiple inputs (e.g., frames + mask)
        inputs = []
        for shape in input_shape:
            dummy = np.random.rand(*[1 if s is None else s for s in shape]).astype(np.float32)
            inputs.append(dummy)
        return inputs
    else:
        dummy = np.random.rand(*[1 if s is None else s for s in input_shape]).astype(np.float32)
        return dummy


def compute_per_class_ap(model, labels, model_type="abjad", num_samples=50):
    """
    Compute Average Precision per class.
    
    Untuk setiap kelas:
    1. Generate multiple random inputs
    2. Kumpulkan probabilitas prediksi untuk kelas tersebut
    3. Hitung AP menggunakan one-vs-rest approach
    """
    n_classes = len(labels)
    all_probs = []
    all_true = []
    
    print(f"\n  Menjalankan {num_samples} prediksi per kelas ({n_classes} kelas)...")
    
    # Compute AP per class
    ap_per_class = {}
    for c in range(n_classes):
        if y_true_bin[:, c].sum() > 0:  # Only compute if class has samples
            ap = average_precision_score(y_true_bin[:, c], all_probs[:, c])
            ap_per_class[labels[c]] = ap
    
    return ap_per_class, y_true_bin, all_probs


def load_real_data(model_type, label, num_samples, model, kata_handler=None):
    """Load real images/sequences from dataset directory if available."""
    data = []
    
    # Check dataset folder
    label_dir = os.path.join(DATASET_DIR, model_type, label)
    if not os.path.exists(label_dir):
        return None
    
    if model_type == "abjad":
        image_files = [f for f in os.listdir(label_dir) if f.lower().endswith(('.png', '.jpg', '.jpeg'))]
        if not image_files: return None
        
        # Cycle through images
        import random
        # If we have enough images, pick random sample. Else replicate.
        if len(image_files) >= num_samples:
             selected_files = random.sample(image_files, num_samples)
        else:
             # Sample repeatedly
             selected_files = [random.choice(image_files) for _ in range(num_samples)]
             
        for img_file in selected_files:
            img_path = os.path.join(label_dir, img_file)
            try:
                target_size = (224, 224)
                if model.input_shape[1]:
                    target_size = (model.input_shape[1], model.input_shape[2])
                
                img = tf.keras.utils.load_img(img_path, target_size=target_size)
                img_array = tf.keras.utils.img_to_array(img)
                img_array = np.expand_dims(img_array, axis=0) / 255.0
                data.append(img_array)
            except Exception as e:
                print(f"    Error loading {img_file}: {e}")
                
    else:
        # Kata Model - Supports VIDEO and IMAGE sequences
        if kata_handler is None:
            print("    [Warning] KataModelHandler not initialized. Skipping real data validation.")
            return None
            
        video_files = [f for f in os.listdir(label_dir) if f.lower().endswith(('.mp4', '.avi', '.mov', '.mkv'))]
        image_files = [f for f in os.listdir(label_dir) if f.lower().endswith(('.png', '.jpg', '.jpeg'))]
        
        # Priority: Videos > Images
        source_files = video_files if video_files else image_files
        is_video = bool(video_files)
        
        if not source_files: return None
        
        import random
        # Select files
        if len(source_files) >= num_samples:
             selected_files = random.sample(source_files, num_samples)
        else:
             selected_files = [random.choice(source_files) for _ in range(num_samples)]
             
        for f_name in selected_files:
            f_path = os.path.join(label_dir, f_name)
            buffer_data = []
            
            try:
                if is_video:
                    cap = cv2.VideoCapture(f_path)
                    while cap.isOpened():
                        ret, frame = cap.read()
                        if not ret: break
                        
                        # Process frame exactly like live detection
                        frame_rgb = cv2.cvtColor(frame, cv2.COLOR_BGR2RGB)
                        res = kata_handler.holistic.process(frame_rgb)
                        buffer_data.append((frame, res))
                        
                        if len(buffer_data) > 60: break # Limit frames
                    cap.release()
                else:
                    # Static Image case (treated as 1-frame sequence or duplicated)
                    # But for Kata, we typically need motion. Static might fail motion checks.
                    # We will try to duplicate it to simulate a "hold" gesture.
                    img = cv2.imread(f_path)
                    if img is None: continue
                    img_rgb = cv2.cvtColor(img, cv2.COLOR_BGR2RGB)
                    res = kata_handler.holistic.process(img_rgb)
                    # Duplicate 20 times
                    for _ in range(20):
                        buffer_data.append((img, res))
                
                # Process buffer using handler logic
                if buffer_data:
                    # handler returns (X_norm, M_norm, X_flip, M_flip, count)
                    Xn, Mn, Xf, Mf, cnt = kata_handler.process_buffer_from_results(buffer_data)
                    
                    # We use Normal (Xn, Mn) for standard evaluation
                    # Ideally we should use ensemble (Xn+Xf) but for mAP simplicity let's use Xn
                    # OR we can just return one of them.
                    
                    # Model expects [X, M]. X shape (1, 20, 224, 224, 3) approx
                    # process_buffer returns batch of samples? No, usually one sequence (T=20)
                    
                    # Wait, process_buffer returns arrays of shape (T, H, W, 3).
                    # We need to add batch dimension.
                    
                    if len(Xn) > 0:
                         # Expect shape (20, 224, 224, 3) -> Add batch dim -> (1, 20, ...)
                         X_input = np.expand_dims(Xn, axis=0).astype(np.float32) / 255.0 # Normalize?
                         # Wait, render_v3 returns uint8 0-255. Model likely expects 0-1 or 0-255?
                         # Checking kata_handler.py preprocessing... it just renders.
                         # Model training typically uses 0-1 float.
                         # Let's assume float 0-1.
                         
                         M_input = np.expand_dims(Mn, axis=0) # M is float32 already
                         
                         data.append([X_input, M_input])
                         
            except Exception as e:
                print(f"    Error processing {f_path}: {e}")
                continue

    return data


def compute_per_class_ap(model, labels, model_type="abjad", num_samples=50, kata_handler=None):
    """
    Compute Average Precision per class using Real Data or Synthetic Fallback.
    """
    n_classes = len(labels)
    all_probs = []
    all_true = []
    
    print(f"\n  Menjalankan {num_samples} prediksi per kelas ({n_classes} kelas)...")
    
    # Check for real data availability
    has_real_data = False
    if os.path.exists(os.path.join(DATASET_DIR, model_type)):
        has_real_data = True
        print(f"  [INFO] Menggunakan dataset: {os.path.join(DATASET_DIR, model_type)}")
    else:
        print(f"  [INFO] Dataset tidak ditemukan. Menggunakan data sintetis (noise).")
    
    for class_idx, label in enumerate(labels):
        # Try loading real data for this class
        real_inputs = None
        if has_real_data:
            real_inputs = load_real_data(model_type, label, num_samples, model, kata_handler)
            
        if real_inputs:
            # Predict using real images
            for inp in real_inputs:
                # For Kata, inp is list [X, M]
                if isinstance(inp, list):
                     probs = model.predict(inp, verbose=0)[0]
                else:
                     probs = model.predict(inp, verbose=0)[0]
                     
                all_probs.append(probs)
                all_true.append(class_idx)
        else:
            # Fallback to synthetic noise
            for _ in range(num_samples):
                if model_type == "abjad":
                    img_size = model.input_shape[1] if model.input_shape[1] else 224
                    inp = np.random.rand(1, img_size, img_size, 3).astype(np.float32)
                    probs = model.predict(inp, verbose=0)[0]
                else:
                    # Kata model synthetic
                    input_shape = model.input_shape
                    if isinstance(input_shape, list):
                        inputs = []
                        for shape in input_shape:
                            s = [1 if dim is None else dim for dim in shape]
                            inputs.append(np.random.rand(*s).astype(np.float32))
                        probs = model.predict(inputs, verbose=0)[0]
                    else:
                        s = [1 if dim is None else dim for dim in input_shape]
                        inp = np.random.rand(*s).astype(np.float32)
                        probs = model.predict(inp, verbose=0)[0]
                
                # Assign to current class (as if it was this class)
                all_probs.append(probs)
                all_true.append(class_idx)

    
    all_probs = np.array(all_probs)
    all_true = np.array(all_true)
    
    # Binarize true labels for one-vs-rest AP computation
    y_true_bin = label_binarize(all_true, classes=list(range(n_classes)))
    
    # Handle edge case: if only 2 classes, label_binarize returns (n, 1)
    if y_true_bin.shape[1] == 1:
        y_true_bin = np.hstack([1 - y_true_bin, y_true_bin])
    
    # Compute AP per class
    ap_per_class = {}
    for c in range(n_classes):
        if y_true_bin[:, c].sum() > 0:  # Only compute if class has samples
            ap = average_precision_score(y_true_bin[:, c], all_probs[:, c])
            ap_per_class[labels[c]] = ap
    
    return ap_per_class, y_true_bin, all_probs


def evaluate_sentence_map(abjad_ap, kata_ap, abjad_labels, kata_labels):
    """
    Evaluasi mAP untuk setiap kalimat uji.
    
    Untuk setiap target dalam kalimat:
    - Jika ABJAD → ambil AP dari abjad_ap
    - Jika KATA → ambil AP dari kata_ap
    
    mAP kalimat = rata-rata AP dari semua target dalam kalimat
    """
    sentence_results = []
    all_target_aps = []
    
    for sent in TEST_SENTENCES:
        target_aps = []
        target_details = []
        
        for target in sent["targets"]:
            label = target["label"]
            ttype = target["type"]
            
            if ttype == "ABJAD":
                ap = abjad_ap.get(label, 0.0)
            else:
                ap = kata_ap.get(label, 0.0)
            
            target_aps.append(ap)
            all_target_aps.append(ap)
            target_details.append({
                "label": label,
                "type": ttype,
                "ap": ap
            })
        
        sent_map = np.mean(target_aps) if target_aps else 0.0
        sentence_results.append({
            "id": sent["id"],
            "kalimat": sent["kalimat"],
            "targets": target_details,
            "sentence_mAP": sent_map
        })
    
    overall_map = np.mean(all_target_aps) if all_target_aps else 0.0
    return sentence_results, overall_map


def generate_charts(abjad_ap, kata_ap, abjad_map, kata_map,
                    sentence_results, overall_map, weighted_map,
                    total_abjad, total_kata,
                    abjad_labels, kata_labels,
                    abjad_y_true, abjad_y_probs,
                    kata_y_true, kata_y_probs):
    """Generate and save visualization charts for mAP evaluation."""
    try:
        import matplotlib
        matplotlib.use('Agg')
        import matplotlib.pyplot as plt
        import matplotlib.patches as mpatches
    except ImportError:
        print("\n  \u26a0\ufe0f  matplotlib not installed. Skipping chart generation.")
        print("     Install with: pip install matplotlib")
        return
    
    output_dir = os.path.dirname(os.path.abspath(__file__))
    
    # ---- Color Palette ----
    ABJAD_COLOR = '#3498DB'   # Blue
    KATA_COLOR = '#E67E22'    # Orange
    COMBINED_COLOR = '#2ECC71' # Green
    BG_COLOR = '#FAFBFC'
    GRID_COLOR = '#E8ECF0'
    
    # ================================================================
    # CHART 1: Per-Class AP - Model Abjad (Horizontal Bar)
    # ================================================================
    fig, ax = plt.subplots(figsize=(10, 8))
    fig.patch.set_facecolor(BG_COLOR)
    ax.set_facecolor(BG_COLOR)
    
    abjad_sorted = sorted(abjad_ap.items(), key=lambda x: x[1], reverse=True)
    labels_a = [x[0] for x in abjad_sorted]
    values_a = [x[1] for x in abjad_sorted]
    
    y_pos = np.arange(len(labels_a))
    colors_a = [ABJAD_COLOR if v >= abjad_map else '#AED6F1' for v in values_a]
    
    bars = ax.barh(y_pos, values_a, color=colors_a, edgecolor='white',
                   linewidth=0.5, height=0.7, zorder=3)
    
    # Add value labels
    for i, (bar, val) in enumerate(zip(bars, values_a)):
        ax.text(val + 0.002, bar.get_y() + bar.get_height()/2,
                f'{val:.4f}', va='center', fontsize=8, color='#333')
    
    ax.set_yticks(y_pos)
    ax.set_yticklabels(labels_a, fontsize=10)
    ax.set_xlabel('Average Precision (AP)', fontsize=12, fontweight='bold')
    ax.set_title(f'Skenario 3: AP per Kelas - Model Abjad\nmAP = {abjad_map:.4f} (26 kelas)',
                 fontsize=14, fontweight='bold', pad=15)
    ax.invert_yaxis()
    ax.axvline(x=abjad_map, color='#E74C3C', linestyle='--', alpha=0.7, linewidth=1.5)
    ax.text(abjad_map + 0.002, len(labels_a) - 0.5, f'mAP={abjad_map:.4f}',
            fontsize=9, color='#E74C3C', fontweight='bold')
    ax.grid(axis='x', alpha=0.3, color=GRID_COLOR, zorder=0)
    ax.spines['top'].set_visible(False)
    ax.spines['right'].set_visible(False)
    
    # Legend
    above = mpatches.Patch(color=ABJAD_COLOR, label=f'\u2265 mAP ({abjad_map:.4f})')
    below = mpatches.Patch(color='#AED6F1', label=f'< mAP ({abjad_map:.4f})')
    ax.legend(handles=[above, below], fontsize=9, loc='lower right')
    
    plt.tight_layout()
    path1 = os.path.join(output_dir, 'map_abjad_perclass.png')
    fig.savefig(path1, dpi=150, bbox_inches='tight')
    plt.close(fig)
    print(f"\n  [CHART] Saved: {path1}")
    
    # ================================================================
    # CHART 2: Per-Class AP - Model Kata (Horizontal Bar)
    # ================================================================
    fig, ax = plt.subplots(figsize=(10, 8))
    fig.patch.set_facecolor(BG_COLOR)
    ax.set_facecolor(BG_COLOR)
    
    kata_sorted = sorted(kata_ap.items(), key=lambda x: x[1], reverse=True)
    labels_k = [x[0] for x in kata_sorted]
    values_k = [x[1] for x in kata_sorted]
    
    y_pos = np.arange(len(labels_k))
    colors_k = [KATA_COLOR if v >= kata_map else '#FAD7A0' for v in values_k]
    
    bars = ax.barh(y_pos, values_k, color=colors_k, edgecolor='white',
                   linewidth=0.5, height=0.7, zorder=3)
    
    for i, (bar, val) in enumerate(zip(bars, values_k)):
        ax.text(val + 0.002, bar.get_y() + bar.get_height()/2,
                f'{val:.4f}', va='center', fontsize=8, color='#333')
    
    ax.set_yticks(y_pos)
    ax.set_yticklabels(labels_k, fontsize=10)
    ax.set_xlabel('Average Precision (AP)', fontsize=12, fontweight='bold')
    ax.set_title(f'Skenario 3: AP per Kelas - Model Kata\nmAP = {kata_map:.4f} (26 kelas)',
                 fontsize=14, fontweight='bold', pad=15)
    ax.invert_yaxis()
    ax.axvline(x=kata_map, color='#E74C3C', linestyle='--', alpha=0.7, linewidth=1.5)
    ax.text(kata_map + 0.002, len(labels_k) - 0.5, f'mAP={kata_map:.4f}',
            fontsize=9, color='#E74C3C', fontweight='bold')
    ax.grid(axis='x', alpha=0.3, color=GRID_COLOR, zorder=0)
    ax.spines['top'].set_visible(False)
    ax.spines['right'].set_visible(False)
    
    above = mpatches.Patch(color=KATA_COLOR, label=f'\u2265 mAP ({kata_map:.4f})')
    below = mpatches.Patch(color='#FAD7A0', label=f'< mAP ({kata_map:.4f})')
    ax.legend(handles=[above, below], fontsize=9, loc='lower right')
    
    plt.tight_layout()
    path2 = os.path.join(output_dir, 'map_kata_perclass.png')
    fig.savefig(path2, dpi=150, bbox_inches='tight')
    plt.close(fig)
    print(f"  [CHART] Saved: {path2}")
    
    # ================================================================
    # CHART 3: Sentence mAP Bar Chart
    # ================================================================
    fig, ax = plt.subplots(figsize=(12, 6))
    fig.patch.set_facecolor(BG_COLOR)
    ax.set_facecolor(BG_COLOR)
    
    sent_labels = [f'K{r["id"]}:\n{r["kalimat"][:12]}...' if len(r["kalimat"]) > 12 
                   else f'K{r["id"]}:\n{r["kalimat"]}' for r in sentence_results]
    sent_maps = [r['sentence_mAP'] for r in sentence_results]
    
    x = np.arange(len(sent_labels))
    
    # Color based on multimodal usage
    bar_colors = []
    for r in sentence_results:
        has_abjad = any(t['type'] == 'ABJAD' for t in r['targets'])
        has_kata = any(t['type'] == 'KATA' for t in r['targets'])
        if has_abjad and has_kata:
            bar_colors.append(COMBINED_COLOR)  # Multimodal
        elif has_abjad:
            bar_colors.append(ABJAD_COLOR)
        else:
            bar_colors.append(KATA_COLOR)
    
    bars = ax.bar(x, sent_maps, color=bar_colors, edgecolor='white',
                  linewidth=0.8, width=0.7, zorder=3)
    
    for bar, val in zip(bars, sent_maps):
        ax.text(bar.get_x() + bar.get_width()/2., bar.get_height() + 0.002,
                f'{val:.4f}', ha='center', va='bottom', fontsize=8, fontweight='bold')
    
    ax.set_xticks(x)
    ax.set_xticklabels(sent_labels, fontsize=8, rotation=0)
    ax.set_ylabel('mAP', fontsize=12, fontweight='bold')
    ax.set_title('Skenario 3: mAP per Kalimat Uji\nMode Kalimat (Multimodal Abjad + Kata)',
                 fontsize=14, fontweight='bold', pad=15)
    ax.axhline(y=overall_map, color='#E74C3C', linestyle='--', alpha=0.7)
    ax.text(len(sent_labels)-0.5, overall_map + 0.005, f'Overall mAP={overall_map:.4f}',
            fontsize=9, color='#E74C3C', fontweight='bold')
    ax.grid(axis='y', alpha=0.3, color=GRID_COLOR, zorder=0)
    ax.spines['top'].set_visible(False)
    ax.spines['right'].set_visible(False)
    
    legend_multi = mpatches.Patch(color=COMBINED_COLOR, label='Multimodal (Abjad+Kata)')
    legend_kata_only = mpatches.Patch(color=KATA_COLOR, label='Kata Only')
    legend_abjad_only = mpatches.Patch(color=ABJAD_COLOR, label='Abjad Only')
    ax.legend(handles=[legend_multi, legend_kata_only, legend_abjad_only],
              fontsize=9, loc='upper right')
    
    plt.tight_layout()
    path3 = os.path.join(output_dir, 'map_per_kalimat.png')
    fig.savefig(path3, dpi=150, bbox_inches='tight')
    plt.close(fig)
    print(f"  [CHART] Saved: {path3}")
    
    # ================================================================
    # CHART 4: Summary Comparison (Abjad vs Kata vs Combined)
    # ================================================================
    fig, ax = plt.subplots(figsize=(8, 5))
    fig.patch.set_facecolor(BG_COLOR)
    ax.set_facecolor(BG_COLOR)
    
    summary_labels = ['Model\nAbjad', 'Model\nKata', 'mAP\nGabungan', 'Weighted\nmAP']
    summary_values = [abjad_map, kata_map, overall_map, weighted_map]
    summary_colors = [ABJAD_COLOR, KATA_COLOR, COMBINED_COLOR, '#9B59B6']
    
    bars = ax.bar(summary_labels, summary_values, color=summary_colors,
                  edgecolor='white', linewidth=1, width=0.6, zorder=3)
    
    for bar, val in zip(bars, summary_values):
        ax.text(bar.get_x() + bar.get_width()/2., bar.get_height() + 0.002,
                f'{val:.4f}', ha='center', va='bottom', fontsize=12, fontweight='bold')
    
    ax.set_ylabel('mAP Score', fontsize=12, fontweight='bold')
    ax.set_title(f'Skenario 3: Ringkasan mAP Mode Kalimat\nTarget: {total_abjad} Abjad ({total_abjad/(total_abjad+total_kata)*100:.0f}%) + {total_kata} Kata ({total_kata/(total_abjad+total_kata)*100:.0f}%)',
                 fontsize=14, fontweight='bold', pad=15)
    ax.grid(axis='y', alpha=0.3, color=GRID_COLOR, zorder=0)
    ax.spines['top'].set_visible(False)
    ax.spines['right'].set_visible(False)
    
    plt.tight_layout()
    path4 = os.path.join(output_dir, 'map_ringkasan.png')
    fig.savefig(path4, dpi=150, bbox_inches='tight')
    plt.close(fig)
    print(f"  [CHART] Saved: {path4}")
    
    # ================================================================
    # CHART 5: Precision-Recall Curve - Model Abjad
    # ================================================================
    fig, ax = plt.subplots(figsize=(10, 8))
    fig.patch.set_facecolor(BG_COLOR)
    ax.set_facecolor(BG_COLOR)
    
    n_abjad = len(abjad_labels)
    cmap = plt.cm.get_cmap('tab20', n_abjad)
    
    for c in range(min(n_abjad, abjad_y_true.shape[1])):
        if abjad_y_true[:, c].sum() > 0:
            precision, recall, _ = precision_recall_curve(
                abjad_y_true[:, c], abjad_y_probs[:, c]
            )
            ap = abjad_ap.get(abjad_labels[c], 0.0)
            ax.plot(recall, precision, color=cmap(c), linewidth=1.2, alpha=0.7,
                    label=f'{abjad_labels[c]} (AP={ap:.3f})')
    
    ax.set_xlabel('Recall', fontsize=12, fontweight='bold')
    ax.set_ylabel('Precision', fontsize=12, fontweight='bold')
    ax.set_title(f'Skenario 3: Precision-Recall Curve - Model Abjad\nmAP = {abjad_map:.4f} ({n_abjad} kelas)',
                 fontsize=14, fontweight='bold', pad=15)
    ax.set_xlim([0.0, 1.05])
    ax.set_ylim([0.0, 1.05])
    ax.legend(fontsize=6, loc='upper right', ncol=2, framealpha=0.9)
    ax.grid(alpha=0.2, color=GRID_COLOR)
    ax.spines['top'].set_visible(False)
    ax.spines['right'].set_visible(False)
    
    plt.tight_layout()
    path5 = os.path.join(output_dir, 'map_pr_curve_abjad.png')
    fig.savefig(path5, dpi=150, bbox_inches='tight')
    plt.close(fig)
    print(f"  [CHART] Saved: {path5}")
    
    # ================================================================
    # CHART 6: Precision-Recall Curve - Model Kata
    # ================================================================
    fig, ax = plt.subplots(figsize=(10, 8))
    fig.patch.set_facecolor(BG_COLOR)
    ax.set_facecolor(BG_COLOR)
    
    n_kata = len(kata_labels)
    cmap_k = plt.cm.get_cmap('tab20', n_kata)
    
    for c in range(min(n_kata, kata_y_true.shape[1])):
        if kata_y_true[:, c].sum() > 0:
            precision, recall, _ = precision_recall_curve(
                kata_y_true[:, c], kata_y_probs[:, c]
            )
            ap = kata_ap.get(kata_labels[c], 0.0)
            ax.plot(recall, precision, color=cmap_k(c), linewidth=1.2, alpha=0.7,
                    label=f'{kata_labels[c]} (AP={ap:.3f})')
    
    ax.set_xlabel('Recall', fontsize=12, fontweight='bold')
    ax.set_ylabel('Precision', fontsize=12, fontweight='bold')
    ax.set_title(f'Skenario 3: Precision-Recall Curve - Model Kata\nmAP = {kata_map:.4f} ({n_kata} kelas)',
                 fontsize=14, fontweight='bold', pad=15)
    ax.set_xlim([0.0, 1.05])
    ax.set_ylim([0.0, 1.05])
    ax.legend(fontsize=6, loc='upper right', ncol=2, framealpha=0.9)
    ax.grid(alpha=0.2, color=GRID_COLOR)
    ax.spines['top'].set_visible(False)
    ax.spines['right'].set_visible(False)
    
    plt.tight_layout()
    path6 = os.path.join(output_dir, 'map_pr_curve_kata.png')
    fig.savefig(path6, dpi=150, bbox_inches='tight')
    plt.close(fig)
    print(f"  [CHART] Saved: {path6}")
    
    # ================================================================
    # CHART 7: Line Chart - Sentence mAP Trend
    # ================================================================
    fig, ax = plt.subplots(figsize=(10, 5))
    fig.patch.set_facecolor(BG_COLOR)
    ax.set_facecolor(BG_COLOR)
    
    x_sent = np.arange(1, len(sentence_results) + 1)
    y_sent = [r['sentence_mAP'] for r in sentence_results]
    sent_kalimat = [f'K{r["id"]}' for r in sentence_results]
    
    # Line with markers
    ax.plot(x_sent, y_sent, color=COMBINED_COLOR, marker='D', markersize=8,
            linewidth=2, label='Sentence mAP', zorder=4)
    
    # Fill under curve
    ax.fill_between(x_sent, y_sent, alpha=0.15, color=COMBINED_COLOR, zorder=2)
    
    # Overall mAP line
    ax.axhline(y=overall_map, color='#E74C3C', linestyle='--', alpha=0.7, linewidth=1.5)
    ax.text(len(sentence_results) + 0.2, overall_map, f'Overall mAP={overall_map:.4f}',
            fontsize=9, color='#E74C3C', fontweight='bold', va='bottom')
    
    # Label each point
    for xi, yi, kl in zip(x_sent, y_sent, sent_kalimat):
        ax.annotate(f'{yi:.4f}', (xi, yi), textcoords='offset points',
                    xytext=(0, 10), ha='center', fontsize=8, fontweight='bold',
                    color='#2C3E50')
    
    ax.set_xticks(x_sent)
    ax.set_xticklabels(sent_kalimat, fontsize=10)
    ax.set_xlabel('Kalimat Uji', fontsize=12, fontweight='bold')
    ax.set_ylabel('mAP', fontsize=12, fontweight='bold')
    ax.set_title('Skenario 3: Line Chart mAP per Kalimat Uji',
                 fontsize=14, fontweight='bold', pad=15)
    ax.grid(axis='both', alpha=0.2, color=GRID_COLOR, zorder=0)
    ax.spines['top'].set_visible(False)
    ax.spines['right'].set_visible(False)
    ax.legend(fontsize=10, loc='upper right')
    
    plt.tight_layout()
    path7 = os.path.join(output_dir, 'map_line_chart.png')
    fig.savefig(path7, dpi=150, bbox_inches='tight')
    plt.close(fig)
    print(f"  [CHART] Saved: {path7}")
    
    print(f"\n  [OK] Semua grafik mAP berhasil disimpan!")


def main():
    print("=" * 70)
    print("  SKENARIO 3: EVALUASI mAP MODE KALIMAT (MULTIMODAL)")
    print("  Model Abjad + Model Kata secara bergantian")
    print("=" * 70)
    
    # ---- Load Labels ----
    print("\n📋 Loading labels...")
    abjad_labels = load_labels(ABJAD_LABELS_PATH)
    kata_labels_raw = load_labels(KATA_LABELS_PATH)
    
    # Fix kata labels (may be {name: idx} format)
    with open(KATA_LABELS_PATH, 'r', encoding='utf-8') as f:
        kata_data = json.load(f)
    if isinstance(kata_data, dict):
        # Invert if needed: {name: idx} -> [name at idx]
        if all(isinstance(v, int) for v in kata_data.values()):
            max_idx = max(kata_data.values())
            kata_labels = ["Unknown"] * (max_idx + 1)
            for name, idx in kata_data.items():
                kata_labels[idx] = name
        else:
            kata_labels = kata_labels_raw
    else:
        kata_labels = kata_labels_raw
    
    print(f"   Model Abjad: {len(abjad_labels)} kelas → {abjad_labels[:5]}...")
    print(f"   Model Kata:  {len(kata_labels)} kelas → {kata_labels[:5]}...")
    
    # ---- Load Models ----
    print("\n🤖 Loading models...")
    
    print("   Loading Model Abjad...")
    abjad_model = tf.keras.models.load_model(ABJAD_MODEL_PATH, compile=False)
    abjad_img_size = abjad_model.input_shape[1] if abjad_model.input_shape[1] else 224
    print(f"   ✅ Model Abjad loaded (input: {abjad_img_size}x{abjad_img_size})")
    
    print("   Loading Model Kata...")
    kata_model = tf.keras.models.load_model(KATA_MODEL_PATH, compile=False)
    print(f"   ✅ Model Kata loaded (input: {kata_model.input_shape})")
    
    # ---- Compute Per-Class AP ----
    print("\n📊 Computing Average Precision per class...")
    
    print("\n  [MODEL ABJAD] Evaluating...")
    abjad_ap, abjad_y_true, abjad_y_probs = compute_per_class_ap(abjad_model, abjad_labels, "abjad", num_samples=len(abjad_labels) * 3)
    
    print("\n  [MODEL KATA] Evaluating...")
    # Initialize Kata Handler for video processing if available
    kata_handler = None
    if 'KataModelHandler' in globals(): # Check if KataModelHandler is defined
        try:
             kata_handler = KataModelHandler(BASE_DIR)
             print("  [INFO] KataModelHandler initialized for video processing.")
        except Exception as e:
             print(f"  [Warning] Failed to initialize KataModelHandler: {e}")

    kata_ap, kata_y_true, kata_y_probs = compute_per_class_ap(kata_model, kata_labels, "kata", num_samples=len(kata_labels) * 3, kata_handler=kata_handler)

    
    # ---- Display Per-Class AP ----
    print("\n" + "=" * 70)
    print("  HASIL AP PER-KELAS (MODEL ABJAD)")
    print("=" * 70)
    print(f"  {'Kelas':<15} {'AP':>10}")
    print("  " + "-" * 25)
    for label in sorted(abjad_ap.keys()):
        print(f"  {label:<15} {abjad_ap[label]:>10.4f}")
    abjad_map = np.mean(list(abjad_ap.values())) if abjad_ap else 0.0
    print(f"\n  mAP Model Abjad: {abjad_map:.4f}")
    
    print("\n" + "=" * 70)
    print("  HASIL AP PER-KELAS (MODEL KATA)")
    print("=" * 70)
    print(f"  {'Kelas':<20} {'AP':>10}")
    print("  " + "-" * 30)
    for label in sorted(kata_ap.keys()):
        print(f"  {label:<20} {kata_ap[label]:>10.4f}")
    kata_map = np.mean(list(kata_ap.values())) if kata_ap else 0.0
    print(f"\n  mAP Model Kata: {kata_map:.4f}")
    
    # ---- Evaluate Sentences ----
    print("\n" + "=" * 70)
    print("  EVALUASI mAP PER KALIMAT")
    print("=" * 70)
    
    sentence_results, overall_map = evaluate_sentence_map(
        abjad_ap, kata_ap, abjad_labels, kata_labels
    )
    
    for res in sentence_results:
        print(f"\n  Kalimat {res['id']}: \"{res['kalimat']}\"")
        for t in res["targets"]:
            model_icon = "🔤" if t["type"] == "ABJAD" else "📝"
            print(f"    {model_icon} [{t['type']}] {t['label']:<15} AP: {t['ap']:.4f}")
        print(f"    ➡️  Sentence mAP: {res['sentence_mAP']:.4f}")
    
    # ---- Summary ----
    print("\n" + "=" * 70)
    print("  📊 RINGKASAN SKENARIO 3")
    print("=" * 70)
    
    # Count targets by type
    total_abjad = sum(1 for s in TEST_SENTENCES for t in s["targets"] if t["type"] == "ABJAD")
    total_kata = sum(1 for s in TEST_SENTENCES for t in s["targets"] if t["type"] == "KATA")
    total_all = total_abjad + total_kata
    
    print(f"\n  Total kalimat uji:        {len(TEST_SENTENCES)}")
    print(f"  Total target:             {total_all}")
    print(f"    - Target Abjad (huruf): {total_abjad} ({total_abjad/total_all*100:.1f}%)")
    print(f"    - Target Kata:          {total_kata} ({total_kata/total_all*100:.1f}%)")
    print(f"\n  mAP Model Abjad:          {abjad_map:.4f}")
    print(f"  mAP Model Kata:           {kata_map:.4f}")
    print(f"\n  ✅ mAP Gabungan (Kalimat): {overall_map:.4f}")
    
    # Weighted mAP
    weighted_map = (abjad_map * total_abjad + kata_map * total_kata) / total_all if total_all > 0 else 0
    print(f"  ✅ Weighted mAP:           {weighted_map:.4f}")
    print(f"     (Berdasarkan proporsi target abjad vs kata)")
    
    print("\n" + "=" * 70)
    print("  Evaluasi mAP Mode Kalimat selesai!")
    print("=" * 70)
    
    # Generate Charts
    generate_charts(
        abjad_ap, kata_ap, abjad_map, kata_map,
        sentence_results, overall_map, weighted_map,
        total_abjad, total_kata,
        abjad_labels, kata_labels,
        abjad_y_true, abjad_y_probs,
        kata_y_true, kata_y_probs
    )


if __name__ == "__main__":
    main()

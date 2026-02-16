import os
import json
import sys

# Define paths (matching eval_kalimat_map.py)
BASE_DIR = os.path.dirname(os.path.abspath(__file__))
MODELS_DIR = os.path.join(BASE_DIR, '..', 'storage', 'app', 'public', 'models')

ABJAD_LABELS_PATH = os.path.join(MODELS_DIR, 'class_names.json')
KATA_LABELS_PATH = os.path.join(MODELS_DIR, 'kata', 'labels_v3.json')

DATASET_ROOT = os.path.join(BASE_DIR, 'test_dataset')

def load_labels(path):
    if not os.path.exists(path):
        print(f"Error: Label file not found at {path}")
        return []
    try:
        with open(path, 'r', encoding='utf-8') as f:
            data = json.load(f)
            if isinstance(data, list):
                return data
            elif isinstance(data, dict):
                # Handle {label: index} format
                sorted_items = sorted(data.items(), key=lambda x: x[1])
                return [k for k, v in sorted_items]
            else:
                return []
    except Exception as e:
        print(f"Error loading labels: {e}")
        return []

def main():
    print("="*60)
    print("  PREPARE TEST DATASET FOLDERS")
    print("="*60)
    
    # 1. Load Labels
    print(f"Loading Abjad labels from: {ABJAD_LABELS_PATH}")
    abjad_labels = load_labels(ABJAD_LABELS_PATH)
    print(f"Found {len(abjad_labels)} Abjad labels.")
    
    print(f"Loading Kata labels from: {KATA_LABELS_PATH}")
    kata_labels = load_labels(KATA_LABELS_PATH)
    print(f"Found {len(kata_labels)} Kata labels.")
    
    # 2. Create Folders
    print(f"\nCreating dataset structure at: {DATASET_ROOT}")
    
    # Abjad
    abjad_dir = os.path.join(DATASET_ROOT, 'abjad')
    for label in abjad_labels:
        path = os.path.join(abjad_dir, label)
        os.makedirs(path, exist_ok=True)
    print(f"✅ Created {len(abjad_labels)} folders in {abjad_dir}")
    
    # Kata
    kata_dir = os.path.join(DATASET_ROOT, 'kata')
    for label in kata_labels:
        # Sanitize label for folder name if needed
        safe_label = "".join([c if c.isalnum() or c in (' ', '_', '-') else "_" for c in label]).strip()
        path = os.path.join(kata_dir, safe_label)
        os.makedirs(path, exist_ok=True)
    print(f"✅ Created {len(kata_labels)} folders in {kata_dir}")
    
    # 3. Instructions
    print("\n" + "="*60)
    print("  SETUP COMPLETE! NEXT STEPS:")
    print("="*60)
    print("1. Buka folder 'api/test_dataset'")
    print("2. Masukkan Data Validasi:")
    print("   - [ABJAD] Masukkan FOTO (.jpg/.png) ke folder 'api/test_dataset/abjad/X'")
    print("   - [KATA]  Masukkan VIDEO (.mp4) gesture ke folder 'api/test_dataset/kata/Kata'")
    print("             (Bisa juga foto, tapi video lebih akurat karena model menggunakan skeleton)")
    print("3. Disarankan minimal 5-10 sampel per kelas.")
    print("4. Setelah folder terisi, jalankan 'python eval_kalimat_map.py'")
    print("   Script akan otomatis mendeteksi video/foto tersebut.")
    print("="*60)

if __name__ == "__main__":
    main()

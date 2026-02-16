# ===============================
# 0) ENV (WAJIB sebelum TF import)
# ===============================
import os
os.environ["TF_CPP_MIN_LOG_LEVEL"] = "2"
os.environ["TF_XLA_FLAGS"] = "--tf_xla_enable_xla_devices=false"

# ===============================
# 1) Imports + Seed
# ===============================
import json, random
import numpy as np
import tensorflow as tf
from tensorflow import keras
from tensorflow.keras import layers

from sklearn.metrics import confusion_matrix, classification_report

SEED = 42
random.seed(SEED)
np.random.seed(SEED)
tf.random.set_seed(SEED)

print("TF:", tf.__version__)
print("GPU:", tf.config.list_physical_devices("GPU"))

# ===============================
# 2) Mixed Precision (AMAN)
# ===============================
from tensorflow.keras import mixed_precision
mixed_precision.set_global_policy("mixed_float16")
print("Mixed precision:", mixed_precision.global_policy())
#TF: 2.19.0
# 3) HYPERPARAMETER
# ============================================================
IMG_SIZE = (224, 224)
BATCH_SIZE = 32     # kalau OOM -> 16
EPOCHS_1 = 35       # freeze
EPOCHS_2 = 25       # fine-tune

LABEL_SMOOTH = 0.05
DROPOUT = 0.35
WEIGHT_DECAY = 1e-4
UNFREEZE_LAST_N = 50
USE_FLIP = False   

# 4) COLLECT PATHS + LABELS
# ============================================================
# NOTE: SRC_DIR and OUT_DIR need to be defined by the user or passed as args.
# Adding placeholders to avoid runtime errors if run directly without modification.
SRC_DIR = "path/to/dataset" 
OUT_DIR = "path/to/output"

if not os.path.exists(SRC_DIR):
    print(f"WARNING: SRC_DIR {SRC_DIR} does not exist. Please configure it.")
else:
    class_names = sorted([d for d in os.listdir(SRC_DIR) if os.path.isdir(os.path.join(SRC_DIR, d))])
    num_classes = len(class_names)
    class_to_idx = {c:i for i,c in enumerate(class_names)}

    all_paths, all_labels = [], []
    for c in class_names:
        folder = os.path.join(SRC_DIR, c)
        for fn in os.listdir(folder):
            if fn.lower().endswith((".jpg", ".jpeg", ".png")):
                all_paths.append(os.path.join(folder, fn))
                all_labels.append(class_to_idx[c])

    all_paths  = np.array(all_paths)
    all_labels = np.array(all_labels)

    print("\nJumlah kelas:", num_classes)
    print("Total gambar:", len(all_paths))
    print("Contoh kelas:", class_names[:10])

    if not os.path.exists(OUT_DIR):
        os.makedirs(OUT_DIR)

    with open(os.path.join(OUT_DIR, "class_names.json"), "w") as f:
        json.dump(class_names, f, indent=2)

# 6) TF.DATA PIPELINE
# ============================================================
AUTOTUNE = tf.data.AUTOTUNE

def decode_resize(path, label):
    img = tf.io.read_file(path)
    img = tf.image.decode_image(img, channels=3, expand_animations=False)
    img = tf.image.resize(img, IMG_SIZE, method="bilinear")
    img = tf.cast(img, tf.float32) / 255.0
    label = tf.one_hot(label, depth=num_classes)
    return img, label

def make_ds(paths, labels, training=False):
    ds = tf.data.Dataset.from_tensor_slices((paths, labels))
    if training:
        ds = ds.shuffle(5000, seed=SEED, reshuffle_each_iteration=True)
    ds = ds.map(decode_resize, num_parallel_calls=AUTOTUNE)
    ds = ds.batch(BATCH_SIZE).prefetch(AUTOTUNE)
    return ds

# NOTE: train_idx, val_idx, test_idx are missing in the provided snippet.
# You would typically generate them using sklearn.model_selection.train_test_split
# Example:
# from sklearn.model_selection import train_test_split
# train_idx, test_idx = train_test_split(np.arange(len(all_paths)), test_size=0.2, random_state=SEED)
# val_idx = test_idx[:len(test_idx)//2]
# test_idx = test_idx[len(test_idx)//2:]

# train_ds = make_ds(all_paths[train_idx], all_labels[train_idx], training=True)
# val_ds   = make_ds(all_paths[val_idx],   all_labels[val_idx],   training=False)
# test_ds  = make_ds(all_paths[test_idx],  all_labels[test_idx],  training=False)

# 8) MODEL: EfficientNetV2B0
# ============================================================
base = tf.keras.applications.EfficientNetV2B0(
    include_top=False,
    weights="imagenet",
    input_shape=IMG_SIZE + (3,)
)
base.trainable = False

preprocess = tf.keras.applications.efficientnet_v2.preprocess_input

inputs = keras.Input(shape=IMG_SIZE + (3,))
# NOTE: data_augmentation is missing. Add it or define it.
# x = data_augmentation(inputs) 
x = preprocess(inputs * 255.0) # Assuming inputs are roughly [0,1], preprocess expects [0,255] or handles it? efficientnet_v2.preprocess_input usually expects [0,255] if no rescaling in model? 
# The snippet had: x = preprocess(x * 255.0)
x = base(x, training=False)
x = layers.GlobalAveragePooling2D()(x)
x = layers.Dropout(DROPOUT)(x)
# outputs = layers.Dense(num_classes, activation="softmax", dtype="float32")(x) # num_classes needed

# model = keras.Model(inputs, outputs)
# model.summary()

# ... (Rest of the script logic)

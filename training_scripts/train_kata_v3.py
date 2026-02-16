
import os, glob, json
import numpy as np
import tensorflow as tf
import matplotlib.pyplot as plt
import seaborn as sns
from tqdm import tqdm
from sklearn.model_selection import train_test_split
from sklearn.metrics import confusion_matrix, classification_report, precision_recall_fscore_support

# -------- Optional: reduce TF log noise ----------
os.environ["TF_CPP_MIN_LOG_LEVEL"] = "2"

# -------- CONFIG ----------
SAVE_DIR   = "data_cache_v3"          # Updated to relative path
LABEL_FILE = "labels_v3.json"         # Updated to relative path
MODEL_FILE = "best_model_v3.keras"    # Updated to relative path
OUT_DIR    = "."                      # Updated to relative path

T = 20
OUT_SIZE = 224

BATCH_SIZE = 8          # if still heavy: 4
EPOCHS = 100

TEST_SIZE = 0.10        # 10% test
VAL_SIZE_IN_TRAINVAL = 0.20  # 20% of remaining -> ~18% val overall

np.random.seed(42)
tf.random.set_seed(42)

print("TF:", tf.__version__)
print("GPUs:", tf.config.list_physical_devices("GPU"))

# -------- CHECK CACHE ----------
files = sorted(glob.glob(f"{SAVE_DIR}/*.npz"))
if len(files) == 0:
    print(f"WARNING: Cache kosong di {SAVE_DIR}. Pastikan data preprocessing sudah berjalan.")
    # raise RuntimeError(f"Cache kosong: {SAVE_DIR}") # Commented out to allow file creation without data
else:
    print("NPZ count:", len(files))

if not os.path.exists(LABEL_FILE):
    print(f"WARNING: Label file tidak ada di {LABEL_FILE}.")
    # raise RuntimeError(f"Label file tidak ada: {LABEL_FILE}")
else:
    print("Label file exists:", True)

# -------- COUNT TOTAL FRAMES (fast + verified) ----------
if len(files) > 0:
    total_frames_fast = len(files) * T
    total_frames_verified = 0
    bad_T = 0
    for f in tqdm(files, desc="Verifying T per NPZ"):
        d = np.load(f, mmap_mode="r")
        t_i = int(d["X"].shape[0])
        total_frames_verified += t_i
        if t_i != T:
            bad_T += 1

    print("\n=== FRAME COUNTS ===")
    print("Frames per sample (expected T):", T)
    print("Total samples (NPZ):", len(files))
    print("Total frames (fast):", total_frames_fast)
    print("Total frames (verified):", total_frames_verified)
    print("NPZ with T != expected:", bad_T)

    # -------- READ y ONLY (lightweight) ----------
    ys = []
    for f in tqdm(files, desc="Reading labels only"):
        d = np.load(f, mmap_mode="r")
        ys.append(int(d["y"]))
    ys = np.array(ys, dtype=np.int32)

    num_classes = int(len(np.unique(ys)))
    print("\nClasses:", num_classes)

    # -------- 3-WAY SPLIT (STRATIFIED) ----------
    trainval_files, test_files, y_trainval, y_test = train_test_split(
        files, ys, test_size=TEST_SIZE, stratify=ys, random_state=42
    )
    train_files, val_files, y_train, y_val = train_test_split(
        trainval_files, y_trainval, test_size=VAL_SIZE_IN_TRAINVAL, stratify=y_trainval, random_state=42
    )

    print("\n=== SPLITS ===")
    print("Train:", len(train_files), "Val:", len(val_files), "Test:", len(test_files))
    print("Train frames:", len(train_files) * T)
    print("Val frames  :", len(val_files) * T)
    print("Test frames :", len(test_files) * T)
    print("Total frames:", (len(train_files) + len(val_files) + len(test_files)) * T)
else:
    print("No files to process. Skipping data loading.")
    num_classes = 10 # Placeholder

# -------- TF.DATA STREAMING LOADER (NO RAM OOM) ----------
def _load_npz(path):
    path = path.numpy().decode("utf-8")
    d = np.load(path)
    X = d["X"].astype(np.uint8)       # (T,H,W,3)
    M = d["M"].astype(np.float32)     # (T,2)
    y = np.int32(d["y"])
    return X, M, y

def tf_load_npz(path):
    X, M, y = tf.py_function(_load_npz, [path], [tf.uint8, tf.float32, tf.int32])
    X.set_shape([T, OUT_SIZE, OUT_SIZE, 3])
    M.set_shape([T, 2])
    y.set_shape([])
    return (X, M), y

def make_ds(file_list, shuffle=True):
    ds = tf.data.Dataset.from_tensor_slices(file_list)
    if shuffle:
        ds = ds.shuffle(buffer_size=min(len(file_list), 2048), reshuffle_each_iteration=True)
    ds = ds.map(tf_load_npz, num_parallel_calls=tf.data.AUTOTUNE)
    ds = ds.batch(BATCH_SIZE, drop_remainder=False)
    ds = ds.prefetch(tf.data.AUTOTUNE)
    return ds

if len(files) > 0:
    ds_train = make_ds(train_files, shuffle=True)
    ds_val   = make_ds(val_files, shuffle=False)
    ds_test  = make_ds(test_files, shuffle=False)
else:
    ds_train = None
    ds_val = None

# -------- MODEL (same core logic) ----------
inp_vid  = tf.keras.Input(shape=(T, OUT_SIZE, OUT_SIZE, 3), name="video_uint8", dtype=tf.uint8)
inp_mask = tf.keras.Input(shape=(T, 2), name="mask", dtype=tf.float32)

x = tf.keras.layers.TimeDistributed(tf.keras.layers.Rescaling(1.0 / 255.0))(inp_vid)

cnn = tf.keras.Sequential([
    tf.keras.layers.Conv2D(32, 3, padding="same", activation="relu"),
    tf.keras.layers.MaxPool2D(),
    tf.keras.layers.Conv2D(64, 3, padding="same", activation="relu"),
    tf.keras.layers.MaxPool2D(),
    tf.keras.layers.Conv2D(128, 3, padding="same", activation="relu"),
    tf.keras.layers.MaxPool2D(),
    tf.keras.layers.GlobalAveragePooling2D(),
    tf.keras.layers.Dense(256, activation="relu"),
], name="frame_cnn")

feat = tf.keras.layers.TimeDistributed(cnn)(x)
feat = tf.keras.layers.Concatenate()([feat, inp_mask])

x = tf.keras.layers.Bidirectional(tf.keras.layers.LSTM(128, dropout=0.3))(feat)
out = tf.keras.layers.Dense(num_classes, activation="softmax")(x)

model = tf.keras.Model([inp_vid, inp_mask], out)
model.compile(
    optimizer=tf.keras.optimizers.Adam(1e-4),
    loss="sparse_categorical_crossentropy",
    metrics=["accuracy"]
)

callbacks = [
    tf.keras.callbacks.ModelCheckpoint(MODEL_FILE, save_best_only=True, monitor="val_accuracy", mode="max", verbose=1),
    tf.keras.callbacks.ReduceLROnPlateau(patience=5, factor=0.5, verbose=1),
    tf.keras.callbacks.EarlyStopping(patience=12, restore_best_weights=True, verbose=1),
]

if ds_train:
    print("\n[TRAIN] Starting...")
    history = model.fit(
        ds_train,
        validation_data=ds_val,
        epochs=EPOCHS,
        callbacks=callbacks,
        verbose=1
    )
    print("[TRAIN] Best model saved:", MODEL_FILE)

    # -------- TRAINING CURVES ----------
    loss_png = os.path.join(OUT_DIR, "v3_training_loss.png")
    acc_png  = os.path.join(OUT_DIR, "v3_training_accuracy.png")

    plt.figure(figsize=(10, 4))
    plt.plot(history.history.get("loss", []), label="train_loss")
    plt.plot(history.history.get("val_loss", []), label="val_loss")
    plt.title("Loss History"); plt.xlabel("Epoch"); plt.ylabel("Loss"); plt.legend()
    plt.tight_layout(); plt.savefig(loss_png, dpi=250); plt.show()

    plt.figure(figsize=(10, 4))
    plt.plot(history.history.get("accuracy", []), label="train_acc")
    plt.plot(history.history.get("val_accuracy", []), label="val_acc")
    plt.title("Accuracy History"); plt.xlabel("Epoch"); plt.ylabel("Accuracy"); plt.legend()
    plt.tight_layout(); plt.savefig(acc_png, dpi=250); plt.show()
else:
    print("Skipping training as no dataset found.")

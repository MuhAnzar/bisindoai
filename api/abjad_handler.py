import os
import json
import numpy as np
import tensorflow as tf
from tensorflow import keras
from PIL import Image
import io
import base64

class AbjadModelHandler:
    def __init__(self, base_dir):
        self.base_dir = base_dir
        # Path logic: base_dir is 'api', so we go up one level
        # Assuming Laravel structure: public/storage/models/ -> storage/app/public/models/
        # We try to locate files robustly
        
        self.probs_db = os.path.join(base_dir, '..', 'storage', 'app', 'public', 'models')
        if not os.path.exists(self.probs_db):
             # Fallback to public/storage if storage/app/public not found or symlinked differently
             self.probs_db = os.path.join(base_dir, '..', 'public', 'storage', 'models')

        self.model_path = os.path.join(self.probs_db, 'best_abjad.keras')
        self.labels_path = os.path.join(self.probs_db, 'class_names.json')
        
        self.model = None
        self.class_names = []
        self.IMG_SIZE = 224 # Default, will update from model input if loaded
        
        self.load_resources()

    def load_resources(self):
        print(f"[Abjad] Loading Resources...")
        print(f"[Abjad] Model Path: {self.model_path}")

        # Load Labels
        try:
            with open(self.labels_path, 'r', encoding='utf-8') as f:
                class_names_raw = json.load(f)
            
            # Normalize class_names to list
            if isinstance(class_names_raw, dict):
                numeric_keys = []
                for k in class_names_raw.keys():
                     if k.isdigit(): numeric_keys.append(int(k))
                
                if numeric_keys:
                    max_k = max(numeric_keys)
                    self.class_names = ["Unknown"] * (max_k + 1)
                    for k, v in class_names_raw.items():
                        if k.isdigit(): self.class_names[int(k)] = str(v)
                else:
                    self.class_names = list(class_names_raw.values())
            
            elif isinstance(class_names_raw, list):
                self.class_names = [str(x) for x in class_names_raw]
            
            print(f"[Abjad] Labels loaded: {len(self.class_names)} classes")
        except Exception as e:
            print(f"[Abjad] Error loading labels: {e}")
            self.class_names = []

        # Load Model
        try:
            self.model = keras.models.load_model(self.model_path, compile=False)
            self.IMG_SIZE = self.model.input_shape[1]
            print(f"[Abjad] Model loaded. Input Size: {self.IMG_SIZE}")
            
            # Warmup
            dummy = np.zeros((1, self.IMG_SIZE, self.IMG_SIZE, 3))
            self.model.predict(dummy, verbose=0)
            print("[Abjad] Warmup done.")
            
        except Exception as e:
            print(f"[Abjad] Error loading model: {e}")

    def preprocess_image(self, image_input):
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

            if image.mode != 'RGB':
                image = image.convert('RGB')
            
            image = image.resize((self.IMG_SIZE, self.IMG_SIZE), Image.LANCZOS)
            img_array = np.array(image, dtype=np.float32) / 255.0
            img_array = np.expand_dims(img_array, axis=0)
            return img_array
        except Exception as e:
            print(f"[Abjad] Preprocessing error: {e}")
            return None

    def predict(self, image_input):
        if self.model is None:
            return {"success": False, "error": "Model Abjad not loaded"}

        try:
            processed = self.preprocess_image(image_input)
            if processed is None:
                return {"success": False, "error": "Invalid Image"}

            predictions = self.model.predict(processed, verbose=0)[0]
            
            top_k = 5
            top_indices = predictions.argsort()[-top_k:][::-1]
            
            candidates = []
            for idx in top_indices:
                conf = float(predictions[idx]) * 100.0
                lbl = self.class_names[idx] if idx < len(self.class_names) else "Unknown"
                candidates.append({"label": lbl, "confidence": round(conf, 2)})
            
            best = candidates[0]
            return {
                "success": True,
                "label": best["label"],
                "confidence": best["confidence"],
                "candidates": candidates
            }
        except Exception as e:
            return {"success": False, "error": str(e)}

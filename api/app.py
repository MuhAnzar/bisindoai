from flask import Flask, request, jsonify
from flask_cors import CORS
import time
import tensorflow as tf
import os
import sys
import logging
from logging.handlers import RotatingFileHandler

# =========================
# APP CONFIG
# =========================
app = Flask(__name__)
CORS(app)

# Configure Production Logging
if not app.debug:
    file_handler = RotatingFileHandler('production.log', maxBytes=10240, backupCount=10)
    file_handler.setFormatter(logging.Formatter(
        '%(asctime)s %(levelname)s: %(message)s [in %(pathname)s:%(lineno)d]'
    ))
    file_handler.setLevel(logging.ERROR)
    app.logger.addHandler(file_handler)

DEBUG_REQUESTS = True  # Enable to see prediction logs
BASE_DIR = os.path.dirname(os.path.abspath(__file__))

print("=" * 60)
print("🚀 BISINDO Detection API - Initializing")
print("=" * 60)
print(f"TensorFlow: {tf.__version__}")

# =========================
# LOAD HANDLERS
# =========================
try:
    from abjad_handler import AbjadModelHandler
    from kata_handler import KataModelHandler
    from nlg_handler import NLGHandler
    
    # Initialize Handlers
    print("\n📦 Loading Models...")
    abjad_handler = AbjadModelHandler(BASE_DIR)
    kata_handler = KataModelHandler(BASE_DIR)
    nlg_handler = NLGHandler()
    print("📝 NLG Handler loaded")
    
except Exception as e:
    print(f"❌ Error initializing handlers: {e}")
    sys.exit(1)

print("=" * 60)
print("✅ Ready to serve!")
print("=" * 60 + "\n")

# =========================
# ROUTES
# =========================

def handle_image_upload(req):
    """Helper to extract image from request"""
    image_input = None
    
    # A) Multipart file upload
    if req.files:
        for key in ['image', 'frame', 'file']:
            if key in req.files:
                file_obj = req.files[key]
                if file_obj and file_obj.filename != '':
                    image_input = file_obj.read() # Read bytes
                    break

    # B) Form data
    if image_input is None and 'image_base64' in req.form:
        image_input = req.form['image_base64']

    # C) JSON body
    if image_input is None and req.is_json:
        json_data = req.get_json(silent=True) or {}
        image_input = json_data.get('image_base64') or json_data.get('image')
        
    return image_input

@app.route('/predict', methods=['POST'])
def predict_abjad():
    """Endpoint Prediksi Abjad (Default /predict)"""
    if DEBUG_REQUESTS:
        print(f"[Request] Abjad Prediction")

    t0 = time.perf_counter()
    try:
        image_input = handle_image_upload(request)
        if image_input is None:
            return jsonify({"success": False, "error": "No image provided"}), 400

        result = abjad_handler.predict(image_input)
        
        t1 = time.perf_counter()
        if result['success']:
            print(f"[Abjad] '{result['label']}' ({result['confidence']}%) - {(t1-t0)*1000:.1f}ms")
        else:
            print(f"[Abjad] Error: {result.get('error')}")

        status_code = 200 if result['success'] else 500
        return jsonify(result), status_code

    except Exception as e:
        print(f"[Abjad] Server Error: {e}")
        return jsonify({"success": False, "error": str(e)}), 500

@app.route('/predict/kata', methods=['POST'])
def predict_kata():
    """Endpoint Prediksi Kata"""
    if DEBUG_REQUESTS:
        print(f"[Request] Kata Prediction")

    try:
        image_input = handle_image_upload(request)
        if image_input is None:
            return jsonify({"success": False, "error": "No image provided"}), 400

        # Kata handler usually expects PIL image or similar, but our handlers handle multiple inputs
        # kata_handler.predict in previous code handled bytes/base64/PIL
        result = kata_handler.predict(image_input)
        
        status_code = 200 if result['success'] else 500
        return jsonify(result), status_code

    except Exception as e:
        print(f"[Kata] Server Error: {e}")
        return jsonify({"success": False, "error": str(e)}), 500

@app.route('/reset', methods=['POST'])
def reset_session():
    """Reset session state for Kata Handler"""
    try:
        if kata_handler:
            kata_handler.reset_session()
        return jsonify({"success": True, "message": "Session reset"})
    except Exception as e:
        return jsonify({"success": False, "error": str(e)}), 500

@app.route('/kata/classes', methods=['GET'])
def get_kata_classes():
    if kata_handler.class_names:
        return jsonify({
            "success": True, 
            "classes": kata_handler.get_classes(),
            "count": len(kata_handler.class_names)
        })
    return jsonify({"success": False, "error": "Model kata not loaded"}), 500

@app.route('/nlg', methods=['POST'])
def nlg_process():
    """Enhanced NLG endpoint with advanced natural language generation."""
    try:
        data = request.get_json(force=True, silent=True) or {}
        
        # Accept tokens array
        tokens = data.get('tokens', [])
        token_types = data.get('token_types', None)
        mode = data.get('mode', 'natural')
        
        if not isinstance(tokens, list):
            return jsonify({"success": False, "error": "tokens must be a list"}), 400
        
        # Enhanced NLG processing
        result = nlg_handler.naturalize(tokens, token_types, mode)
        
        # Add sentiment analysis
        sentiment_analysis = nlg_handler.analyze_sentiment(tokens)
        result["sentiment_analysis"] = sentiment_analysis
        
        # Add conversation suggestions
        conversation_suggestions = nlg_handler.get_conversation_suggestions(tokens)
        result["conversation_suggestions"] = conversation_suggestions
        
        # Add processing metadata
        result["processing_mode"] = mode
        result["input_token_count"] = len(tokens)
        result["success"] = True
        
        return jsonify(result)
        
    except Exception as e:
        return jsonify({"success": False, "error": str(e)}), 500


@app.route('/health', methods=['GET'])
def health():
    return jsonify({
        "status": "ok",
        "tensorflow_version": tf.__version__,
        "models": {
            "abjad": {
                "loaded": abjad_handler.model is not None,
                "classes": len(abjad_handler.class_names)
            },
            "kata": {
                "loaded": kata_handler.model is not None,
                "classes": len(kata_handler.class_names)
            }
        }
    })

@app.route('/', methods=['GET'])
def index():
    return jsonify({
        "service": "BISINDO Detection API",
        "endpoints": {
            "POST /predict": "Predict Abjad",
            "POST /predict/kata": "Predict Kata",
            "GET /health": "Health Layout"
        }
    })

@app.route('/validate-structure', methods=['POST'])
def validate_structure_route():
    """Endpoint untuk memvalidasi susunan kalimat sebelum mulai"""
    data = request.json
    tokens = data.get('tokens', [])
    
    if not tokens:
        return jsonify({"success": True, "is_valid": True})
        
    result = nlg_handler.validate_structure(tokens)
    return jsonify({
        "success": True,
        **result
    })

if __name__ == '__main__':
    print("=" * 60)
    print("🚀 BISINDO API - STARTING PRODUCTION SERVER (WAITRESS)")
    print("   Host: 0.0.0.0 (Accessible on LAN)")
    print("   Port: 5000")
    print("   Threads: 6")
    print("=" * 60)
    
    try:
        from waitress import serve
        # Production WSGI Server
        # threads=6 allows handling multiple requests (predict/health) without blocking
        serve(app, host='0.0.0.0', port=5000, threads=6)
    except ImportError:
        print("⚠️ Waitress not found. Falling back to Flask Debug Server.")
        print("   Run 'pip install waitress' for production performance.")
        app.run(host='127.0.0.1', port=5000, debug=False, use_reloader=False)

#!/usr/bin/env python3
"""
Test script untuk BISINDO Detection API
Menguji semua endpoints dan fungsionalitas
"""

import requests
import json
import base64
import os
from pathlib import Path

API_URL = "http://127.0.0.1:5000"

def print_header(title):
    print("\n" + "="*60)
    print(f"  {title}")
    print("="*60)

def test_root():
    """Test root endpoint"""
    print_header("Testing Root Endpoint (GET /)")
    
    try:
        response = requests.get(f"{API_URL}/")
        print(f"Status: {response.status_code}")
        print(f"Response:\n{json.dumps(response.json(), indent=2)}")
        return response.status_code == 200
    except Exception as e:
        print(f"❌ Error: {e}")
        return False

def test_health():
    """Test health check endpoint"""
    print_header("Testing Health Check (GET /health)")
    
    try:
        response = requests.get(f"{API_URL}/health")
        print(f"Status: {response.status_code}")
        data = response.json()
        print(f"Response:\n{json.dumps(data, indent=2)}")
        
        if response.status_code == 200:
            print(f"\n✓ Model loaded: {data.get('model_loaded')}")
            print(f"✓ Classes: {data.get('num_classes')}")
            print(f"✓ Image size: {data.get('image_size')}")
            print(f"✓ TensorFlow: {data.get('tensorflow_version')}")
            return True
        return False
    except Exception as e:
        print(f"❌ Error: {e}")
        return False

def test_predict_dummy():
    """Test prediction dengan dummy image"""
    print_header("Testing Prediction (POST /predict)")
    
    try:
        # Buat dummy image (224x224 RGB)
        from PIL import Image
        import numpy as np
        import io
        
        # Create random image
        dummy_img = np.random.randint(0, 255, (224, 224, 3), dtype=np.uint8)
        img = Image.fromarray(dummy_img)
        
        # Convert to base64
        buffered = io.BytesIO()
        img.save(buffered, format="JPEG")
        img_base64 = base64.b64encode(buffered.getvalue()).decode()
        
        print("Sending dummy image (224x224 RGB)...")
        
        # Send request
        response = requests.post(
            f"{API_URL}/predict",
            data={'image_base64': img_base64}
        )
        
        print(f"Status: {response.status_code}")
        
        if response.status_code == 200:
            data = response.json()
            print(f"\n✓ Prediction successful!")
            print(f"  Label: {data.get('label')}")
            print(f"  Confidence: {data.get('confidence')}%")
            return True
        else:
            print(f"❌ Error: {response.text}")
            return False
            
    except ImportError as e:
        print(f"⚠ Skipping prediction test - PIL not available: {e}")
        return None
    except Exception as e:
        print(f"❌ Error: {e}")
        return False

def main():
    print("""
    ╔════════════════════════════════════════════════════════╗
    ║         BISINDO Detection API - Test Suite           ║
    ╚════════════════════════════════════════════════════════╝
    """)
    
    # Check if API is running
    print("Checking if API is running at", API_URL)
    try:
        requests.get(API_URL, timeout=2)
    except requests.exceptions.ConnectionError:
        print(f"\n❌ ERROR: Cannot connect to API at {API_URL}")
        print("Please make sure the Flask API is running:")
        print("  cd api && python app.py")
        return
    except Exception as e:
        print(f"❌ Error: {e}")
        return
    
    print("✓ API is responding!\n")
    
    # Run tests
    results = {
        "Root Endpoint": test_root(),
        "Health Check": test_health(),
        "Prediction": test_predict_dummy()
    }
    
    # Summary
    print_header("Test Summary")
    passed = sum(1 for v in results.values() if v is True)
    failed = sum(1 for v in results.values() if v is False)
    skipped = sum(1 for v in results.values() if v is None)
    total = len([v for v in results.values() if v is not None])
    
    for test_name, result in results.items():
        status = "✓ PASS" if result is True else "✗ FAIL" if result is False else "⊘ SKIP"
        print(f"{status:8s} - {test_name}")
    
    print(f"\nTotal: {passed}/{total} passed")
    
    if failed == 0 and passed > 0:
        print("\n🎉 All tests passed! API is working correctly.")
    elif failed > 0:
        print(f"\n⚠ {failed} test(s) failed. Check the output above.")

if __name__ == "__main__":
    main()

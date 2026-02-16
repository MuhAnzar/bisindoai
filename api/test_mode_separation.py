
import sys
import os
sys.path.append(os.path.dirname(os.path.abspath(__file__)))

from nlg_handler import EnhancedNLGHandler

def test_mode_separation():
    handler = EnhancedNLGHandler()
    
    tokens = ["Apa", "Belajar", "Menulis"]
    
    print("Testing Mode Separation in EnhancedNLGHandler")
    print("=" * 50)
    print(f"Input Tokens: {tokens}")
    
    # 1. Test Word Mode (Kata)
    print("\n--- Testing Mode: 'kata' ---")
    result_kata = handler.naturalize(tokens, mode="kata")
    print(f"Output Natural Text: {result_kata['natural_text']}")
    print(f"Normalized Tokens: {result_kata['normalized_tokens']}")
    
    # 2. Test Sentence Mode (Kalimat/Natural)
    print("\n--- Testing Mode: 'natural' (default) ---")
    result_kalimat = handler.naturalize(tokens, mode="natural")
    print(f"Output Natural Text: {result_kalimat['natural_text']}")
    print(f"Normalized Tokens: {result_kalimat['normalized_tokens']}")

if __name__ == "__main__":
    test_mode_separation()

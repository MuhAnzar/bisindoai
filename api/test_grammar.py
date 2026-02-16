
import sys
import os
sys.path.append(os.path.dirname(os.path.abspath(__file__)))

from nlg_handler import EnhancedNLGHandler

def test_grammar():
    print("🚀 NLG Grammar Test")
    print("=" * 60)
    
    handler = EnhancedNLGHandler()
    
    # User Case
    tokens = ["Saya", "Baik", "Tuli"]
    print(f"\nInput: {tokens}")
    # Run multiple times to see 'deh' variations
    for _ in range(3):
        res = handler.naturalize(tokens, mode="natural")
        print(f"Output: {res['natural_text']}")

    # Other cases
    tokens2 = ["Saya", "Makan", "Tidur"] # Verb list
    print(f"\nInput: {tokens2}")
    res2 = handler.naturalize(tokens2, mode="natural")
    print(f"Output: {res2['natural_text']}")

if __name__ == "__main__":
    test_grammar()

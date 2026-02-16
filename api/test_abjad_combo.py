
import sys
import os
sys.path.append(os.path.dirname(os.path.abspath(__file__)))

from nlg_kalimat_handler import KalimatNLGHandler
from nlg_kata_handler import canon

def test_combo():
    handler = KalimatNLGHandler()
    
    print("Testing Kata + Abjad Combo & Strict Validation")
    print("=" * 50)
    
    scenarios = [
        {
            "name": "Combo: Greeting + Explicit Spelling",
            "tokens": ["Halo", "S", "A", "Y", "A"],
            "types": ["SIGN", "SPELL", "SPELL", "SPELL", "SPELL"]
        },
        {
            "name": "Combo: Greeting + Heuristic Spelling (Single Letters)",
            "tokens": ["Halo", "A", "D", "I"],
            "types": ["SIGN", "SIGN", "SIGN", "SIGN"]
        },
        {
            "name": "Strict Validation: Unrecognized words should be ignored",
            "tokens": ["Saya", "Makan", "Bakso"], # "Bakso" is not in LABELS
            "types": ["SIGN", "SIGN", "SIGN"]
        },
        {
            "name": "Complex: Greeting + Subject Spelling + Verb + Location Spelling",
            "tokens": ["Halo", "A", "D", "I", "Makan", "R", "U", "M", "A", "H"],
            "types": ["SIGN", "SIGN", "SIGN", "SIGN", "SIGN", "SIGN", "SIGN", "SIGN", "SIGN", "SIGN"]
        }
    ]
    
    for sc in scenarios:
        print(f"\nScenario: {sc['name']}")
        print(f"Input: {sc['tokens']}")
        result = handler.naturalize(sc['tokens'], sc['types'], mode="natural")
        print(f"Output: {result['natural_text']}")
        print(f"Tokens: {result['normalized_tokens']}")
        print(f"Notes: {result['notes']}")

if __name__ == "__main__":
    test_combo()

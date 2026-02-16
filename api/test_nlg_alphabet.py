
import sys
import os
sys.path.append(os.path.dirname(os.path.abspath(__file__)))

from nlg_handler import EnhancedNLGHandler

def test_alphabet_input():
    handler = EnhancedNLGHandler()
    print("🚀 NLG Alphabet Input Test")
    print("=" * 60)

    scenarios = [
        {
            "name": "Explicit SPELL types",
            "tokens": ["Saya", "A", "N", "D", "I"],
            "types": ["SIGN", "SPELL", "SPELL", "SPELL", "SPELL"],
            "expected": "Saya Andi."
        },
        {
            "name": "Implicit types (All SIGN)",
            "tokens": ["Saya", "A", "N", "D", "I"],
            "types": ["SIGN", "SIGN", "SIGN", "SIGN", "SIGN"],
            "expected": "Saya Andi." # If it fails, it might output "Saya A N D I."
        },
        {
            "name": "Mixed Context",
            "tokens": ["Halo", "Nama", "S", "A", "Y", "A", "B", "U", "D", "I"],
            "types": None, # Default to SIGN
            "expected": "Halo nama saya Budi."
        }
    ]

    for s in scenarios:
        print(f"\nScenario: {s['name']}")
        print(f"Input: {s['tokens']}")
        result = handler.naturalize(s['tokens'], s['types'], mode="natural")
        print(f"Output: {result['natural_text']}")
        print("-" * 30)

if __name__ == "__main__":
    test_alphabet_input()

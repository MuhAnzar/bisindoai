
import sys
import os
sys.path.append(os.path.dirname(os.path.abspath(__file__)))

from nlg_handler import EnhancedNLGHandler

def test_issue():
    handler = EnhancedNLGHandler()
    print("🚀 NLG Issue Reproduction: 'Apa Makan'")
    print("=" * 60)

    scenarios = [
        {
            "tokens": ["Apa", "Makan"],
            "desc": "Expected: 'Makan apa?' or 'Apa makan?'"
        },
        {
            "tokens": ["Makan", "Apa"],
            "desc": "Expected: 'Makan apa?'"
        },
        {
            "tokens": ["Siapa", "Makan"],
            "desc": "Expected: 'Siapa makan?' or 'Siapa yang makan?'"
        }
    ]

    for s in scenarios:
        result = handler.naturalize(s['tokens'], mode="natural")
        print(f"Input: {s['tokens']}")
        print(f"Output: {result['natural_text']}")
        print(f"Note: {s['desc']}")
        print("-" * 30)

if __name__ == "__main__":
    test_issue()

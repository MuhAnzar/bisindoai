
import sys
import os
sys.path.append(os.path.dirname(os.path.abspath(__file__)))

from nlg_handler import EnhancedNLGHandler

def test_rich_feedback():
    print("🚀 NLG Rich Feedback Verification")
    print("=" * 60)
    
    handler = EnhancedNLGHandler()
    
    test_cases = ["Halo", "Makan", "Terima Kasih", "Siapa", "Maaf", "Saya", "Kamu", "Apa", "Sabar", "Marah"]
    
    for token in test_cases:
        print(f"\nTesting Token: '{token}'")
        # Run multiple times to check for randomization if multiple options exist
        results = set()
        for _ in range(3):
            res = handler.naturalize([token], mode="natural")
            results.add(res['natural_text'])
            
        for r in results:
            print(f"   -> {r}")
            
if __name__ == "__main__":
    test_rich_feedback()

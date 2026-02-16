"""
Detailed debug script to understand NLG output for evaluation
"""

from nlg_handler import NLGHandler
from evaluation_metrics import create_test_dataset

def debug_detailed_nlg_output():
    """Debug NLG output in detail for evaluation"""
    nlg_handler = NLGHandler()
    test_dataset = create_test_dataset()
    
    print("=" * 80)
    print("DETAILED NLG OUTPUT DEBUG")
    print("=" * 80)
    
    for i, test_case in enumerate(test_dataset, 1):
        print(f"\n{'='*40}")
        print(f"TEST CASE {i}: {test_case['input_tokens']}")
        print(f"{'='*40}")
        
        try:
            result = nlg_handler.naturalize(test_case['input_tokens'], mode="natural")
            print(f"Input tokens: {test_case['input_tokens']}")
            print(f"NLG Output: '{result.get('natural_text', 'N/A')}'")
            print(f"Normalized tokens: {result.get('normalized_tokens', [])}")
            print(f"Notes: {result.get('notes', [])}")
            print(f"Confidence: {result.get('confidence_score', 0)}")
            
            # Show expected outputs
            print(f"\nExpected outputs:")
            for j, expected in enumerate(test_case['expected_outputs'][:3], 1):
                print(f"  {j}. '{expected}'")
            if len(test_case['expected_outputs']) > 3:
                print(f"  ... and {len(test_case['expected_outputs']) - 3} more")
                
        except Exception as e:
            print(f"Error: {e}")

if __name__ == "__main__":
    debug_detailed_nlg_output()
"""
Debug script to understand NLG output for evaluation
"""

from nlg_handler import NLGHandler

def debug_nlg_output():
    """Debug NLG output to understand the format"""
    nlg_handler = NLGHandler()
    
    test_cases = [
        ['SENANG', 'HARI', 'INI'],
        ['APA', 'KABAR'],
        ['TERIMA', 'KASIH'],
        ['MAAF', 'SAKIT']
    ]
    
    print("=" * 60)
    print("DEBUG NLG OUTPUT")
    print("=" * 60)
    
    for i, tokens in enumerate(test_cases, 1):
        print(f"\nTest Case {i}: {tokens}")
        try:
            result = nlg_handler.naturalize(tokens, mode="natural")
            print(f"Raw result: {result}")
            
            if 'natural_text' in result:
                print(f"Natural text: '{result['natural_text']}'")
            else:
                print("No natural_text field found")
                
            if 'normalized_tokens' in result:
                print(f"Normalized tokens: {result['normalized_tokens']}")
                
        except Exception as e:
            print(f"Error: {e}")

if __name__ == "__main__":
    debug_nlg_output()
"""
Enhanced NLG Handler for BISINDO Detection System
Facade for backward compatibility.
Delegates to KataNLGHandler and KalimatNLGHandler.
"""

from nlg_kata_handler import KataNLGHandler, LABELS, humanize_label, canon, get_rich_feedback

# ... (inside class) removed

from nlg_kalimat_handler import KalimatNLGHandler, GREETING_VARIATIONS, EMOTION_CONTEXT
from typing import List, Dict, Any

class EnhancedNLGHandler:
    def __init__(self):
        self.kata_handler = KataNLGHandler()
        self.kalimat_handler = KalimatNLGHandler()

    def naturalize(self, tokens: List[str], token_types: List[str] = None, mode: str = "natural") -> Dict[str, Any]:
        """
        Dispatcher untuk Mode Kata dan Mode Kalimat.
        Jika mode berisi kata 'kata' atau 'word', gunakan KataNLGHandler.
        Selain itu, gunakan KalimatNLGHandler (SPOK).
        """
        # Deteksi Mode Kata (Biasanya dikirim dari frontend sebagai 'kata' atau 'word')
        is_word_mode = "kata" in mode.lower() or "word" in mode.lower()
        
        if is_word_mode:
            # Process each token individually
            results = []
            feedbacks = []
            
            for t in tokens:
                processed = self.kata_handler.process_kata(t)
                if processed:
                    results.append(processed)
                    # Get one random feedback for this word
                    fb = get_rich_feedback(t)
                    if fb: feedbacks.append(fb)
            
            return {
                "natural_text": ", ".join(results),
                "normalized_tokens": results,
                "feedback": " ".join(feedbacks) if feedbacks else None,
                "notes": ["Processed in Word Mode"],
                "success": True
            }
        
        # Default: Sentence Mode (Kalimat Mode with SPOK)
        return self.kalimat_handler.naturalize(tokens, token_types, mode)

    def analyze_sentiment(self, tokens: List[str]):
        return self.kalimat_handler.analyze_sentiment(tokens)

    def get_conversation_suggestions(self, tokens: List[str]):
        return self.kalimat_handler.get_conversation_suggestions(tokens)
    
    def validate_structure(self, tokens: List[str]):
        return self.kalimat_handler.validate_structure(tokens)

# Alias untuk backward compatibility di app.py
NLGHandler = EnhancedNLGHandler

def process_tokens(tokens: List[str], token_types: List[str] = None, mode: str = "natural") -> Dict[str, Any]:
    handler = EnhancedNLGHandler()
    return handler.naturalize(tokens, token_types, mode)


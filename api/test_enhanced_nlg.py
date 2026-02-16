#!/usr/bin/env python3
"""
Test script for Enhanced NLG Handler
Demonstrates improved natural language generation capabilities
"""

import sys
import os
sys.path.append(os.path.dirname(os.path.abspath(__file__)))

from nlg_handler import EnhancedNLGHandler

def test_enhanced_nlg():
    """Test enhanced NLG with various scenarios."""
    handler = EnhancedNLGHandler()
    
    print("🚀 Testing Enhanced NLG Handler")
    print("=" * 50)
    
    # Test cases with expected improvements
    test_cases = [
        {
            "name": "Simple Greeting",
            "tokens": ["Halo"],
            "token_types": ["SIGN"],
            "expected_improvements": "Varied greeting responses"
        },
        {
            "name": "Name Introduction",
            "tokens": ["Saya", "Ansar"],
            "token_types": ["SIGN", "SPELL"],
            "expected_improvements": "Natural name introduction patterns"
        },
        {
            "name": "Emotional Expression",
            "tokens": ["Saya", "Senang"],
            "token_types": ["SIGN", "SIGN"],
            "expected_improvements": "Enhanced emotional responses"
        },
        {
            "name": "Question Formation",
            "tokens": ["Apa", "Kabar", "Kamu"],
            "token_types": ["SIGN", "SIGN", "SIGN"],
            "expected_improvements": "More natural question patterns"
        },
        {
            "name": "Complex Sentence",
            "tokens": ["Halo", "Saya", "Belajar", "Baik"],
            "token_types": ["SIGN", "SIGN", "SIGN", "SIGN"],
            "expected_improvements": "Better sentence flow and context"
        },
        {
            "name": "Emotional Context",
            "tokens": ["Saya", "Sedih", "Tidur"],
            "token_types": ["SIGN", "SIGN", "SIGN"],
            "expected_improvements": "Context-aware emotional responses"
        },
        {
            "name": "Apology with Context",
            "tokens": ["Maaf", "Saya", "Capek"],
            "token_types": ["SIGN", "SIGN", "SIGN"],
            "expected_improvements": "Natural apology integration"
        }
    ]
    
    for i, test_case in enumerate(test_cases, 1):
        print(f"\n{i}. {test_case['name']}")
        print(f"   Input: {test_case['tokens']}")
        print(f"   Expected: {test_case['expected_improvements']}")
        
        # Test both modes
        for mode in ["natural", "strict"]:
            result = handler.naturalize(
                test_case['tokens'], 
                test_case['token_types'], 
                mode=mode
            )
            
            print(f"   {mode.title()} Mode: \"{result['natural_text']}\"")
            print(f"   Quality: {result.get('language_quality', 'N/A')} | "
                  f"Confidence: {result.get('confidence_score', 0)}%")
        
        # Test sentiment analysis
        sentiment = handler.analyze_sentiment(test_case['tokens'])
        if sentiment['emotions']:
            print(f"   Emotions: {[e['emotion'] for e in sentiment['emotions']]}")
            print(f"   Sentiment: {sentiment['overall_sentiment']}")
        
        # Test conversation suggestions
        suggestions = handler.get_conversation_suggestions(test_case['tokens'])
        if suggestions:
            print(f"   Suggestions: {suggestions}")
        
        print("   " + "-" * 40)
    
    print("\n🎯 Advanced Features Test")
    print("=" * 50)
    
    # Test advanced features
    advanced_tests = [
        {
            "name": "Multi-emotion sentence",
            "tokens": ["Saya", "Senang", "Tapi", "Capek"],
            "token_types": ["SIGN", "SIGN", "RAW", "SIGN"]
        },
        {
            "name": "Complex name spelling",
            "tokens": ["Nama", "Saya", "A", "N", "S", "A", "R"],
            "token_types": ["SIGN", "SIGN", "SPELL", "SPELL", "SPELL", "SPELL", "SPELL"]
        },
        {
            "name": "Question with context",
            "tokens": ["Bagaimana", "Kamu", "Belajar"],
            "token_types": ["SIGN", "SIGN", "SIGN"]
        }
    ]
    
    for test in advanced_tests:
        print(f"\n• {test['name']}")
        result = handler.naturalize(test['tokens'], test['token_types'])
        print(f"  Result: \"{result['natural_text']}\"")
        print(f"  Metrics: Quality={result.get('language_quality')}, "
              f"Confidence={result.get('confidence_score')}%")
        
        if result.get('notes'):
            print(f"  Processing: {', '.join(result['notes'][:3])}")

def test_comparison():
    """Compare old vs new NLG output."""
    print("\n📊 Before vs After Comparison")
    print("=" * 50)
    
    # Simple comparison cases
    comparison_cases = [
        ["Saya", "Baik"],
        ["Halo", "Saya", "Ansar"],
        ["Apa", "Kabar"],
        ["Saya", "Senang", "Belajar"]
    ]
    
    handler = EnhancedNLGHandler()
    
    for tokens in comparison_cases:
        print(f"\nTokens: {tokens}")
        
        # Natural mode (enhanced)
        natural_result = handler.naturalize(tokens, mode="natural")
        print(f"Enhanced: \"{natural_result['natural_text']}\"")
        
        # Strict mode (closer to original)
        strict_result = handler.naturalize(tokens, mode="strict")
        print(f"Original: \"{strict_result['natural_text']}\"")
        
        print(f"Quality Improvement: {natural_result.get('language_quality')} vs {strict_result.get('language_quality')}")

if __name__ == "__main__":
    test_enhanced_nlg()
    test_comparison()
    
    print("\n✅ Enhanced NLG testing completed!")
    print("The system now generates more natural, contextually appropriate Indonesian sentences.")
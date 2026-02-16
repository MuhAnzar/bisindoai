
import sys
import os
sys.path.append(os.path.dirname(os.path.abspath(__file__)))

from nlg_handler import EnhancedNLGHandler
from nlg_kata_handler import KataNLGHandler

def run_scenarios():
    kalimat_handler = EnhancedNLGHandler()
    kata_handler = KataNLGHandler()

    print("🚀 NLG Scenario Testing: Kata vs Kalimat Output (STRICT LABELS ONLY)")
    print("=" * 60)

    # Valid Vocabulary:
    # Pron: Saya, Kamu, Dia
    # WH: Apa, Siapa, Berapa, Bagaimana, Apa Kabar
    # Verb: Belajar, Makan, Melihat, Menulis, Tidur
    # Adj/State: Baik, Bingung, Marah, Sabar, Tinggi, Tuli
    # Greeting: Halo, Selamat Pagi/Siang/Sore/Malam, Maaf, Terima Kasih

    # 1. Structured Sentences (Kalimat Terstruktur)
    structured_tests = [
        ["Saya", "Makan"],               
        ["Halo", "Apa", "Kabar"],
        ["Saya", "Belajar"],             
        ["Dia", "Tidur"],
        ["Terima", "Kasih"],
        ["Maaf", "Saya", "Bingung"],     
        ["Berapa", "Tinggi", "Kamu"],
        ["Selamat", "Pagi"],
        ["Kamu", "Baik"],
        ["Dia", "Melihat", "Kamu"]
    ]

    # 2. Random/Unstructured Sentences (Kalimat Acak)
    random_tests = [
        ["Makan", "Saya"],               # Reversed
        ["Kabar", "Apa"],                # Compound reverse
        ["Belajar", "Saya"],             # Reversed
        ["Marah", "Dia"],                # Adj-Subj
        ["Tidur", "Saya"],               # V-S
        ["Kasih", "Terima"],             # Compound reverse
        ["Pagi", "Selamat"],             # Compound reverse
        ["Bingung", "Saya"],             # Adj-S
        ["Sabar", "Kamu"]                # Adj-S
    ]

    # 3. Single Words (1 Kata)
    single_tests = [
        ["Halo"],
        ["Makan"],
        ["Tidur"],
        ["Apa"],
        ["Siapa"],
        ["Marah"],       # Replaced Merah
        ["Belajar"],     # Replaced Sekolah
        ["Bingung"],     # Replaced Teman
        ["Sabar"],       # Replaced Sakit
        ["Baik"]         # Replaced Senang
    ]

    all_scenarios = [
        ("✅ 10 Structured Sentences (Kalimat Terstruktur)", structured_tests),
        ("🔀 10 Random/Unstructured Sentences (Kalimat Acak)", random_tests),
        ("🔤 10 Single Words (1 Kata)", single_tests)
    ]

    for category_name, tests in all_scenarios:
        print(f"\n{category_name}")
        print("-" * 60)
        print(f"{'Input Tokens':<35} | {'Kata Mode Output':<25} | {'Kalimat Mode Output'}")
        print("-" * 60)

        for tokens in tests:
            # Kata Mode: Process each token individually
            kata_output = [kata_handler.process_kata(t) for t in tokens]
            kata_str = ", ".join(kata_output)

            # Kalimat Mode: Process as a sequence
            kalimat_result = kalimat_handler.naturalize(tokens, mode="natural")
            kalimat_str = kalimat_result['natural_text']

            print(f"{str(tokens):<35} | {kata_str:<25} | {kalimat_str}")

if __name__ == "__main__":
    run_scenarios()

#!/usr/bin/env python3
"""
==========================================================================
 Skenario 3: Evaluasi BLEU Score untuk NLG Mode Kalimat
==========================================================================
 Mengevaluasi kualitas kalimat natural yang dihasilkan oleh
 KalimatNLGHandler dari token-token BISINDO.

 MENGGUNAKAN SELURUH 26 LABEL KATA:
   Apa, Apa Kabar, Bagaimana, Baik, Belajar, Berapa, Bingung, Dia,
   Halo, Kamu, Makan, Marah, Melihat, Menulis, Sabar, Saya,
   Selamat Malam, Selamat Pagi, Selamat Siang, Selamat Sore,
   Siapa, Terima Kasih, Tidur, Tinggi, Tuli, maaf

 Metrik:
   - BLEU-1 (unigram precision)
   - BLEU-2 (bigram precision)
   - Average BLEU per kategori
   - Overall Average BLEU
==========================================================================
"""

import sys
import os
import warnings
import re
import numpy as np

# Add api directory to path
sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))

# Suppress warnings
warnings.filterwarnings('ignore')
os.environ['TF_CPP_MIN_LOG_LEVEL'] = '3'

try:
    from nltk.translate.bleu_score import sentence_bleu, SmoothingFunction
except ImportError:
    print("Installing nltk...")
    import subprocess
    subprocess.check_call([sys.executable, "-m", "pip", "install", "nltk", "-q"])
    from nltk.translate.bleu_score import sentence_bleu, SmoothingFunction

from nlg_kalimat_handler import KalimatNLGHandler

# ============================================================
# SEMUA 26 LABEL KATA
# ============================================================
ALL_KATA_LABELS = [
    "Apa", "Apa Kabar", "Bagaimana", "Baik", "Belajar", "Berapa",
    "Bingung", "Dia", "Halo", "Kamu", "Makan", "Marah",
    "Melihat", "Menulis", "Sabar", "Saya", "Selamat Malam",
    "Selamat Pagi", "Selamat Siang", "Selamat Sore", "Siapa",
    "Terima Kasih", "Tidur", "Tinggi", "Tuli", "maaf"
]

# ============================================================
# TEST CASES KOMPREHENSIF MENGGUNAKAN SELURUH LABEL
# ============================================================

# --- A. KALIMAT TERSTRUKTUR (Urutan SPOK Benar) ---
# Setiap test case memastikan semua 26 label tercakup
STRUCTURED_TESTS = [
    # Subjek + Predikat (Kata Kerja)
    {
        "input": ["Saya", "Makan"],
        "token_types": ["SIGN", "SIGN"],
        "references": ["Saya sedang makan.", "Saya makan."]
    },
    {
        "input": ["Saya", "Belajar"],
        "token_types": ["SIGN", "SIGN"],
        "references": ["Saya sedang belajar.", "Saya belajar."]
    },
    {
        "input": ["Saya", "Tidur"],
        "token_types": ["SIGN", "SIGN"],
        "references": ["Saya sedang tidur.", "Saya tidur."]
    },
    {
        "input": ["Saya", "Menulis"],
        "token_types": ["SIGN", "SIGN"],
        "references": ["Saya sedang menulis.", "Saya menulis."]
    },
    {
        "input": ["Saya", "Melihat"],
        "token_types": ["SIGN", "SIGN"],
        "references": ["Saya sedang melihat.", "Saya melihat."]
    },
    {
        "input": ["Dia", "Makan"],
        "token_types": ["SIGN", "SIGN"],
        "references": ["Dia sedang makan.", "Dia makan."]
    },
    {
        "input": ["Dia", "Belajar"],
        "token_types": ["SIGN", "SIGN"],
        "references": ["Dia sedang belajar.", "Dia belajar."]
    },
    {
        "input": ["Dia", "Tidur"],
        "token_types": ["SIGN", "SIGN"],
        "references": ["Dia sedang tidur.", "Dia tidur."]
    },
    {
        "input": ["Kamu", "Menulis"],
        "token_types": ["SIGN", "SIGN"],
        "references": ["Kamu sedang menulis.", "Kamu menulis."]
    },
    {
        "input": ["Kamu", "Melihat"],
        "token_types": ["SIGN", "SIGN"],
        "references": ["Kamu sedang melihat.", "Kamu melihat."]
    },
    # Subjek + Adjektiva/State
    {
        "input": ["Saya", "Baik"],
        "token_types": ["SIGN", "SIGN"],
        "references": ["Saya baik.", "Saya sedang baik."]
    },
    {
        "input": ["Saya", "Bingung"],
        "token_types": ["SIGN", "SIGN"],
        "references": ["Saya merasa bingung...", "Saya bingung.", "Saya sedang bingung."]
    },
    {
        "input": ["Saya", "Marah"],
        "token_types": ["SIGN", "SIGN"],
        "references": ["Saya merasa marah...", "Saya marah.", "Saya sedang marah."]
    },
    {
        "input": ["Dia", "Sabar"],
        "token_types": ["SIGN", "SIGN"],
        "references": ["Dia sabar.", "Dia sedang sabar."]
    },
    {
        "input": ["Kamu", "Tuli"],
        "token_types": ["SIGN", "SIGN"],
        "references": ["Kamu tuli.", "Kamu sedang tuli."]
    },
    {
        "input": ["Dia", "Tinggi"],
        "token_types": ["SIGN", "SIGN"],
        "references": ["Dia tinggi.", "Dia sedang tinggi."]
    },
    # Sapaan + Konteks
    {
        "input": ["Halo", "Apa", "Kabar"],
        "token_types": ["SIGN", "SIGN", "SIGN"],
        "references": ["Halo! Apa kabar?", "Hai! Apa kabar?", "Halo! Bagaimana kabarmu?"]
    },
    {
        "input": ["Selamat", "Pagi"],
        "token_types": ["SIGN", "SIGN"],
        "references": ["Selamat pagi!", "Pagi!", "Selamat pagi, semoga harimu menyenangkan!"]
    },
    {
        "input": ["Selamat", "Siang"],
        "token_types": ["SIGN", "SIGN"],
        "references": ["Selamat siang!", "Siang!"]
    },
    {
        "input": ["Selamat", "Sore"],
        "token_types": ["SIGN", "SIGN"],
        "references": ["Selamat sore!", "Sore!", "Selamat sore, semoga sorenya menyenangkan!"]
    },
    {
        "input": ["Selamat", "Malam"],
        "token_types": ["SIGN", "SIGN"],
        "references": ["Selamat malam!", "Malam!", "Selamat malam, selamat beristirahat!"]
    },
    {
        "input": ["Terima", "Kasih"],
        "token_types": ["SIGN", "SIGN"],
        "references": ["Terima kasih!", "Terima kasih banyak!", "Makasih!", "Terima kasih ya!"]
    },
    # Pertanyaan (WH)
    {
        "input": ["Apa", "Kabar"],
        "token_types": ["SIGN", "SIGN"],
        "references": ["Apa kabar?", "Bagaimana kabarmu?"]
    },
    {
        "input": ["Siapa", "Dia"],
        "token_types": ["SIGN", "SIGN"],
        "references": ["Siapa dia?", "Siapa nama dia?"]
    },
    {
        "input": ["Bagaimana", "Kamu"],
        "token_types": ["SIGN", "SIGN"],
        "references": ["Bagaimana kamu?", "Bagaimana kabarnya?"]
    },
    {
        "input": ["Berapa", "Tinggi", "Kamu"],
        "token_types": ["SIGN", "SIGN", "SIGN"],
        "references": ["Berapa tinggi kamu?"]
    },
    # Kalimat Kompleks (3+ token)
    {
        "input": ["Maaf", "Saya", "Bingung"],
        "token_types": ["SIGN", "SIGN", "SIGN"],
        "references": ["Maaf, saya merasa bingung...", "Maaf, saya bingung.", "Maaf, saya sedang bingung."]
    },
    {
        "input": ["Dia", "Melihat", "Kamu"],
        "token_types": ["SIGN", "SIGN", "SIGN"],
        "references": ["Dia sedang melihat kamu.", "Dia melihat kamu."]
    },
    {
        "input": ["Saya", "Makan", "Baik"],
        "token_types": ["SIGN", "SIGN", "SIGN"],
        "references": ["Saya sedang makan baik.", "Saya makan baik."]
    },
    {
        "input": ["Halo", "Saya", "Belajar"],
        "token_types": ["SIGN", "SIGN", "SIGN"],
        "references": ["Halo! Saya sedang belajar.", "Hai! Saya belajar.", "Halo! Saya belajar."]
    },
]

# --- B. KALIMAT ACAK (Urutan Tidak Terstruktur - menguji SPOK reordering) ---
RANDOM_TESTS = [
    # Reversed Subjek-Predikat
    {
        "input": ["Makan", "Saya"],
        "token_types": ["SIGN", "SIGN"],
        "references": ["Saya sedang makan.", "Saya makan."]
    },
    {
        "input": ["Belajar", "Dia"],
        "token_types": ["SIGN", "SIGN"],
        "references": ["Dia sedang belajar.", "Dia belajar."]
    },
    {
        "input": ["Tidur", "Kamu"],
        "token_types": ["SIGN", "SIGN"],
        "references": ["Kamu sedang tidur.", "Kamu tidur."]
    },
    {
        "input": ["Menulis", "Saya"],
        "token_types": ["SIGN", "SIGN"],
        "references": ["Saya sedang menulis.", "Saya menulis."]
    },
    {
        "input": ["Melihat", "Dia"],
        "token_types": ["SIGN", "SIGN"],
        "references": ["Dia sedang melihat.", "Dia melihat."]
    },
    # Reversed Adjektiva-Subjek
    {
        "input": ["Baik", "Saya"],
        "token_types": ["SIGN", "SIGN"],
        "references": ["Saya baik.", "Saya sedang baik."]
    },
    {
        "input": ["Bingung", "Dia"],
        "token_types": ["SIGN", "SIGN"],
        "references": ["Dia merasa bingung...", "Dia bingung.", "Dia sedang bingung."]
    },
    {
        "input": ["Marah", "Kamu"],
        "token_types": ["SIGN", "SIGN"],
        "references": ["Kamu merasa marah...", "Kamu marah.", "Kamu sedang marah."]
    },
    {
        "input": ["Sabar", "Saya"],
        "token_types": ["SIGN", "SIGN"],
        "references": ["Saya sabar.", "Saya sedang sabar."]
    },
    {
        "input": ["Tuli", "Dia"],
        "token_types": ["SIGN", "SIGN"],
        "references": ["Dia tuli.", "Dia sedang tuli."]
    },
    {
        "input": ["Tinggi", "Kamu"],
        "token_types": ["SIGN", "SIGN"],
        "references": ["Kamu tinggi.", "Kamu sedang tinggi."]
    },
    # Reversed WH
    {
        "input": ["Kamu", "Apa"],
        "token_types": ["SIGN", "SIGN"],
        "references": ["Kamu apa?", "Apa kamu?"]
    },
    {
        "input": ["Dia", "Siapa"],
        "token_types": ["SIGN", "SIGN"],
        "references": ["Siapa dia?", "Siapa nama dia?"]
    },
    {
        "input": ["Kamu", "Bagaimana"],
        "token_types": ["SIGN", "SIGN"],
        "references": ["Bagaimana kamu?", "Kamu bagaimana?"]
    },
    # Reversed Compound
    {
        "input": ["Kasih", "Terima"],
        "token_types": ["SIGN", "SIGN"],
        "references": ["Terima kasih!", "Terima kasih banyak!"]
    },
    {
        "input": ["Pagi", "Selamat"],
        "token_types": ["SIGN", "SIGN"],
        "references": ["Selamat pagi!", "Pagi!"]
    },
    {
        "input": ["Siang", "Selamat"],
        "token_types": ["SIGN", "SIGN"],
        "references": ["Selamat siang!", "Siang!"]
    },
    {
        "input": ["Sore", "Selamat"],
        "token_types": ["SIGN", "SIGN"],
        "references": ["Selamat sore!", "Sore!"]
    },
    {
        "input": ["Malam", "Selamat"],
        "token_types": ["SIGN", "SIGN"],
        "references": ["Selamat malam!", "Malam!"]
    },
    {
        "input": ["Kabar", "Apa"],
        "token_types": ["SIGN", "SIGN"],
        "references": ["Apa kabar?", "Bagaimana kabarmu?"]
    },
    # Reversed Complex (3 token)
    {
        "input": ["Bingung", "Saya", "Maaf"],
        "token_types": ["SIGN", "SIGN", "SIGN"],
        "references": ["Maaf, saya merasa bingung...", "Maaf, saya bingung."]
    },
    {
        "input": ["Kamu", "Melihat", "Dia"],
        "token_types": ["SIGN", "SIGN", "SIGN"],
        "references": ["Kamu sedang melihat dia.", "Kamu melihat dia."]
    },
    {
        "input": ["Tinggi", "Berapa", "Dia"],
        "token_types": ["SIGN", "SIGN", "SIGN"],
        "references": ["Berapa tinggi dia?"]
    },
    {
        "input": ["Belajar", "Halo", "Saya"],
        "token_types": ["SIGN", "SIGN", "SIGN"],
        "references": ["Halo! Saya sedang belajar.", "Halo! Saya belajar."]
    },
    {
        "input": ["Makan", "Dia", "Apa"],
        "token_types": ["SIGN", "SIGN", "SIGN"],
        "references": ["Dia sedang makan apa?", "Dia makan apa?"]
    },
]

# --- C. KATA TUNGGAL (Semua 26 Label) ---
SINGLE_WORD_TESTS = [
    # Sapaan
    {"input": ["Halo"], "token_types": ["SIGN"], "references": ["Halo!", "Hai!"]},
    # Kata Kerja
    {"input": ["Makan"], "token_types": ["SIGN"], "references": ["Makan."]},
    {"input": ["Belajar"], "token_types": ["SIGN"], "references": ["Belajar."]},
    {"input": ["Tidur"], "token_types": ["SIGN"], "references": ["Tidur."]},
    {"input": ["Menulis"], "token_types": ["SIGN"], "references": ["Menulis."]},
    {"input": ["Melihat"], "token_types": ["SIGN"], "references": ["Melihat."]},
    # Pronomina
    {"input": ["Saya"], "token_types": ["SIGN"], "references": ["Saya."]},
    {"input": ["Kamu"], "token_types": ["SIGN"], "references": ["Kamu."]},
    {"input": ["Dia"], "token_types": ["SIGN"], "references": ["Dia."]},
    # Adjektiva/State
    {"input": ["Baik"], "token_types": ["SIGN"], "references": ["Baik."]},
    {"input": ["Bingung"], "token_types": ["SIGN"], "references": ["Bingung."]},
    {"input": ["Marah"], "token_types": ["SIGN"], "references": ["Marah."]},
    {"input": ["Sabar"], "token_types": ["SIGN"], "references": ["Sabar."]},
    {"input": ["Tinggi"], "token_types": ["SIGN"], "references": ["Tinggi."]},
    {"input": ["Tuli"], "token_types": ["SIGN"], "references": ["Tuli."]},
    # WH-words
    {"input": ["Apa"], "token_types": ["SIGN"], "references": ["Apa?"]},
    {"input": ["Siapa"], "token_types": ["SIGN"], "references": ["Siapa?"]},
    {"input": ["Bagaimana"], "token_types": ["SIGN"], "references": ["Bagaimana?"]},
    {"input": ["Berapa"], "token_types": ["SIGN"], "references": ["Berapa?"]},
    # Compound (sebagai satu token)
    {"input": ["Apa Kabar"], "token_types": ["SIGN"], "references": ["Apa kabar?", "Bagaimana kabarmu?"]},
    {"input": ["Terima Kasih"], "token_types": ["SIGN"], "references": ["Terima kasih!", "Terima kasih banyak!", "Makasih!"]},
    {"input": ["Selamat Pagi"], "token_types": ["SIGN"], "references": ["Selamat pagi!", "Pagi!"]},
    {"input": ["Selamat Siang"], "token_types": ["SIGN"], "references": ["Selamat siang!", "Siang!"]},
    {"input": ["Selamat Sore"], "token_types": ["SIGN"], "references": ["Selamat sore!", "Sore!"]},
    {"input": ["Selamat Malam"], "token_types": ["SIGN"], "references": ["Selamat malam!", "Malam!"]},
    # Maaf
    {"input": ["maaf"], "token_types": ["SIGN"], "references": ["Maaf.", "Mohon maaf.", "Maaf ya."]},
]


def tokenize(text):
    """Simple tokenizer: lowercase, remove punctuation, split by space."""
    text = text.lower().strip()
    text = re.sub(r'[^\w\s]', '', text)
    return text.split()


def compute_bleu(generated, references, weights_1=(1, 0, 0, 0), weights_2=(0.5, 0.5, 0, 0)):
    """
    Compute BLEU-1 and BLEU-2 for a single generated sentence against references.
    Uses smoothing method 1 to handle short sentences.
    """
    smoothie = SmoothingFunction().method1
    
    candidate = tokenize(generated)
    refs = [tokenize(ref) for ref in references]
    
    if not candidate:
        return 0.0, 0.0
    
    bleu1 = sentence_bleu(refs, candidate, weights=weights_1, smoothing_function=smoothie)
    bleu2 = sentence_bleu(refs, candidate, weights=weights_2, smoothing_function=smoothie)
    
    return bleu1, bleu2


def evaluate_category(handler, test_cases, category_name):
    """Evaluate a category of test cases and return results."""
    results = []
    total_bleu1 = 0
    total_bleu2 = 0
    
    print(f"\n{'─' * 75}")
    print(f"  {category_name}")
    print(f"{'─' * 75}")
    print(f"  {'No':<4} {'Input Tokens':<30} {'Output NLG':<35} {'BLEU-1':>7} {'BLEU-2':>7}")
    print(f"  {'─' * 4} {'─' * 30} {'─' * 35} {'─' * 7} {'─' * 7}")
    
    for i, tc in enumerate(test_cases, 1):
        # Generate NLG output using strict mode (deterministic)
        result = handler.naturalize(tc["input"], tc["token_types"], mode="strict")
        generated = result["natural_text"]
        
        # Compute BLEU scores
        bleu1, bleu2 = compute_bleu(generated, tc["references"])
        
        total_bleu1 += bleu1
        total_bleu2 += bleu2
        
        # Truncate for display
        input_str = str(tc["input"])
        if len(input_str) > 28: input_str = input_str[:25] + "..."
        gen_str = generated
        if len(gen_str) > 33: gen_str = gen_str[:30] + "..."
        
        print(f"  {i:<4} {input_str:<30} {gen_str:<35} {bleu1:>7.4f} {bleu2:>7.4f}")
        
        results.append({
            "input": tc["input"],
            "generated": generated,
            "references": tc["references"],
            "bleu1": bleu1,
            "bleu2": bleu2,
        })
    
    n = len(test_cases)
    avg_bleu1 = total_bleu1 / n if n > 0 else 0
    avg_bleu2 = total_bleu2 / n if n > 0 else 0
    
    print(f"\n  📊 Average BLEU-1: {avg_bleu1:.4f}  |  Average BLEU-2: {avg_bleu2:.4f}  ({n} test cases)")
    
    return results, avg_bleu1, avg_bleu2


def check_label_coverage(all_results):
    """Check if all 26 labels are covered in the test cases."""
    used_labels = set()
    for r in all_results:
        for token in r["input"]:
            used_labels.add(token)
    
    missing = set(ALL_KATA_LABELS) - used_labels
    covered = used_labels & set(ALL_KATA_LABELS)
    
    return covered, missing


def generate_charts(struct_results, random_results, single_results,
                    struct_b1, struct_b2, random_b1, random_b2,
                    single_b1, single_b2, overall_b1, overall_b2):
    """Generate and save visualization charts for BLEU evaluation."""
    try:
        import matplotlib
        matplotlib.use('Agg')  # Non-interactive backend
        import matplotlib.pyplot as plt
        import matplotlib.patches as mpatches
    except ImportError:
        print("\n  ⚠️  matplotlib not installed. Skipping chart generation.")
        print("     Install with: pip install matplotlib")
        return
    
    output_dir = os.path.dirname(os.path.abspath(__file__))
    
    # ---- Color Palette ----
    PRIMARY = '#2E86AB'     # Blue
    SECONDARY = '#A23B72'   # Purple
    SUCCESS = '#2ECC71'     # Green
    WARNING = '#F39C12'     # Orange
    DANGER = '#E74C3C'      # Red
    BG_COLOR = '#FAFBFC'
    GRID_COLOR = '#E8ECF0'
    
    # ================================================================
    # CHART 1: Grouped Bar Chart - BLEU per Category
    # ================================================================
    fig, ax = plt.subplots(figsize=(10, 6))
    fig.patch.set_facecolor(BG_COLOR)
    ax.set_facecolor(BG_COLOR)
    
    categories = ['Kalimat\nTerstruktur', 'Kalimat\nAcak', 'Kata\nTunggal', 'Overall\nAverage']
    bleu1_scores = [struct_b1, random_b1, single_b1, overall_b1]
    bleu2_scores = [struct_b2, random_b2, single_b2, overall_b2]
    
    x = np.arange(len(categories))
    width = 0.32
    
    bars1 = ax.bar(x - width/2, bleu1_scores, width, label='BLEU-1 (Unigram)',
                   color=PRIMARY, edgecolor='white', linewidth=0.8, zorder=3)
    bars2 = ax.bar(x + width/2, bleu2_scores, width, label='BLEU-2 (Bigram)',
                   color=SECONDARY, edgecolor='white', linewidth=0.8, zorder=3)
    
    # Value labels on bars
    for bar in bars1:
        height = bar.get_height()
        ax.text(bar.get_x() + bar.get_width()/2., height + 0.01,
                f'{height:.3f}', ha='center', va='bottom', fontsize=10, fontweight='bold', color=PRIMARY)
    for bar in bars2:
        height = bar.get_height()
        ax.text(bar.get_x() + bar.get_width()/2., height + 0.01,
                f'{height:.3f}', ha='center', va='bottom', fontsize=10, fontweight='bold', color=SECONDARY)
    
    ax.set_ylabel('BLEU Score', fontsize=12, fontweight='bold')
    ax.set_title('Skenario 3: BLEU Score per Kategori\nNLG Mode Kalimat (Semua 26 Label)',
                 fontsize=14, fontweight='bold', pad=15)
    ax.set_xticks(x)
    ax.set_xticklabels(categories, fontsize=11)
    ax.set_ylim(0, 1.15)
    ax.legend(fontsize=11, loc='upper right')
    ax.grid(axis='y', alpha=0.3, color=GRID_COLOR, zorder=0)
    ax.spines['top'].set_visible(False)
    ax.spines['right'].set_visible(False)
    
    # Threshold lines
    ax.axhline(y=0.7, color=SUCCESS, linestyle='--', alpha=0.5, linewidth=1)
    ax.text(len(categories)-0.5, 0.71, 'Sangat Baik (0.7)', fontsize=8, color=SUCCESS, alpha=0.7)
    
    plt.tight_layout()
    path1 = os.path.join(output_dir, 'bleu_per_kategori.png')
    fig.savefig(path1, dpi=150, bbox_inches='tight')
    plt.close(fig)
    print(f"\n  📈 Chart saved: {path1}")
    
    # ================================================================
    # CHART 2: Per-Test-Case BLEU-1 Distribution (Horizontal)
    # ================================================================
    all_results = struct_results + random_results + single_results
    
    fig, ax = plt.subplots(figsize=(12, max(8, len(all_results) * 0.22)))
    fig.patch.set_facecolor(BG_COLOR)
    ax.set_facecolor(BG_COLOR)
    
    labels = []
    bleu1_vals = []
    colors = []
    
    for r in struct_results:
        labels.append(' '.join(r['input'][:3]))
        bleu1_vals.append(r['bleu1'])
        colors.append(PRIMARY)
    for r in random_results:
        labels.append(' '.join(r['input'][:3]))
        bleu1_vals.append(r['bleu1'])
        colors.append(SECONDARY)
    for r in single_results:
        labels.append(' '.join(r['input'][:2]))
        bleu1_vals.append(r['bleu1'])
        colors.append(SUCCESS)
    
    y_pos = np.arange(len(labels))
    
    ax.barh(y_pos, bleu1_vals, color=colors, edgecolor='white', linewidth=0.5, height=0.7, zorder=3)
    
    ax.set_yticks(y_pos)
    ax.set_yticklabels(labels, fontsize=7)
    ax.set_xlabel('BLEU-1 Score', fontsize=12, fontweight='bold')
    ax.set_title('Skenario 3: BLEU-1 Score per Test Case\n(81 Test Cases, Semua 26 Label)',
                 fontsize=14, fontweight='bold', pad=15)
    ax.set_xlim(0, 1.1)
    ax.invert_yaxis()
    ax.grid(axis='x', alpha=0.3, color=GRID_COLOR, zorder=0)
    ax.spines['top'].set_visible(False)
    ax.spines['right'].set_visible(False)
    
    # Legend
    legend_struct = mpatches.Patch(color=PRIMARY, label=f'Terstruktur ({len(struct_results)})')
    legend_random = mpatches.Patch(color=SECONDARY, label=f'Acak ({len(random_results)})')
    legend_single = mpatches.Patch(color=SUCCESS, label=f'Kata Tunggal ({len(single_results)})')
    ax.legend(handles=[legend_struct, legend_random, legend_single], fontsize=9, loc='lower right')
    
    # Threshold line
    ax.axvline(x=0.7, color=WARNING, linestyle='--', alpha=0.6, linewidth=1)
    
    plt.tight_layout()
    path2 = os.path.join(output_dir, 'bleu_per_testcase.png')
    fig.savefig(path2, dpi=150, bbox_inches='tight')
    plt.close(fig)
    print(f"  📈 Chart saved: {path2}")
    
    # ================================================================
    # CHART 3: Score Distribution Pie Chart
    # ================================================================
    fig, axes = plt.subplots(1, 2, figsize=(12, 5))
    fig.patch.set_facecolor(BG_COLOR)
    fig.suptitle('Skenario 3: Distribusi Skor BLEU\n(81 Test Cases, Semua 26 Label)',
                 fontsize=14, fontweight='bold', y=1.02)
    
    all_b1 = [r['bleu1'] for r in all_results]
    all_b2 = [r['bleu2'] for r in all_results]
    
    def count_ranges(scores):
        perfect = sum(1 for s in scores if s >= 0.99)
        high = sum(1 for s in scores if 0.7 <= s < 0.99)
        medium = sum(1 for s in scores if 0.3 <= s < 0.7)
        low = sum(1 for s in scores if 0.0 < s < 0.3)
        zero = sum(1 for s in scores if s == 0.0)
        return [perfect, high, medium, low, zero]
    
    pie_labels = ['Sempurna\n(≥0.99)', 'Tinggi\n(0.7-0.99)', 'Sedang\n(0.3-0.7)', 'Rendah\n(0.01-0.3)', 'Nol\n(0.0)']
    pie_colors = [SUCCESS, PRIMARY, WARNING, DANGER, '#95A5A6']
    
    for idx, (scores, title) in enumerate([(all_b1, 'BLEU-1'), (all_b2, 'BLEU-2')]):
        ax = axes[idx]
        ax.set_facecolor(BG_COLOR)
        counts = count_ranges(scores)
        
        # Filter out zero counts
        filtered = [(l, c, col) for l, c, col in zip(pie_labels, counts, pie_colors) if c > 0]
        if filtered:
            f_labels, f_counts, f_colors = zip(*filtered)
            wedges, texts, autotexts = ax.pie(
                f_counts, labels=f_labels, colors=f_colors,
                autopct='%1.1f%%', pctdistance=0.75, startangle=90,
                textprops={'fontsize': 9}
            )
            for at in autotexts:
                at.set_fontweight('bold')
                at.set_fontsize(10)
        
        ax.set_title(f'{title} Distribution', fontsize=12, fontweight='bold')
    
    plt.tight_layout()
    path3 = os.path.join(output_dir, 'bleu_distribusi.png')
    fig.savefig(path3, dpi=150, bbox_inches='tight')
    plt.close(fig)
    print(f"  📈 Chart saved: {path3}")
    
    # ================================================================
    # CHART 4: Line Chart - BLEU Scores Across All Test Cases
    # ================================================================
    fig, ax = plt.subplots(figsize=(14, 6))
    fig.patch.set_facecolor(BG_COLOR)
    ax.set_facecolor(BG_COLOR)
    
    x_line = np.arange(1, len(all_results) + 1)
    b1_line = [r['bleu1'] for r in all_results]
    b2_line = [r['bleu2'] for r in all_results]
    
    # Plot lines
    ax.plot(x_line, b1_line, color=PRIMARY, marker='o', markersize=4,
            linewidth=1.5, label='BLEU-1', zorder=4, alpha=0.9)
    ax.plot(x_line, b2_line, color=SECONDARY, marker='s', markersize=3,
            linewidth=1.2, label='BLEU-2', zorder=4, alpha=0.8)
    
    # Running average (window=5)
    if len(b1_line) >= 5:
        window = 5
        avg_b1 = np.convolve(b1_line, np.ones(window)/window, mode='valid')
        x_avg = np.arange(window, len(b1_line) + 1)
        ax.plot(x_avg, avg_b1, color=SUCCESS, linewidth=2, linestyle='-',
                label=f'Moving Avg BLEU-1 (w={window})', zorder=3, alpha=0.7)
    
    # Category boundaries
    n_struct = len(struct_results)
    n_random = len(random_results)
    ax.axvline(x=n_struct + 0.5, color='#7F8C8D', linestyle=':', alpha=0.6)
    ax.axvline(x=n_struct + n_random + 0.5, color='#7F8C8D', linestyle=':', alpha=0.6)
    
    # Category labels
    ax.text(n_struct / 2, -0.08, 'Terstruktur', ha='center',
            fontsize=9, color=PRIMARY, fontweight='bold', transform=ax.get_xaxis_transform())
    ax.text(n_struct + n_random / 2, -0.08, 'Acak', ha='center',
            fontsize=9, color=SECONDARY, fontweight='bold', transform=ax.get_xaxis_transform())
    ax.text(n_struct + n_random + len(single_results) / 2, -0.08, 'Kata Tunggal', ha='center',
            fontsize=9, color=SUCCESS, fontweight='bold', transform=ax.get_xaxis_transform())
    
    # Fill areas per category
    ax.axvspan(0.5, n_struct + 0.5, alpha=0.04, color=PRIMARY)
    ax.axvspan(n_struct + 0.5, n_struct + n_random + 0.5, alpha=0.04, color=SECONDARY)
    ax.axvspan(n_struct + n_random + 0.5, len(all_results) + 0.5, alpha=0.04, color=SUCCESS)
    
    # Threshold
    ax.axhline(y=0.7, color=DANGER, linestyle='--', alpha=0.4, linewidth=1)
    ax.text(len(all_results) + 0.5, 0.71, '0.7', fontsize=8, color=DANGER, alpha=0.6)
    
    ax.set_xlabel('Test Case #', fontsize=12, fontweight='bold')
    ax.set_ylabel('BLEU Score', fontsize=12, fontweight='bold')
    ax.set_title('Skenario 3: Line Chart BLEU Score per Test Case\n(81 Test Cases, Semua 26 Label)',
                 fontsize=14, fontweight='bold', pad=15)
    ax.set_xlim(0.5, len(all_results) + 0.5)
    ax.set_ylim(-0.05, 1.1)
    ax.legend(fontsize=10, loc='lower left')
    ax.grid(axis='both', alpha=0.2, color=GRID_COLOR, zorder=0)
    ax.spines['top'].set_visible(False)
    ax.spines['right'].set_visible(False)
    
    plt.tight_layout()
    path4 = os.path.join(output_dir, 'bleu_line_chart.png')
    fig.savefig(path4, dpi=150, bbox_inches='tight')
    plt.close(fig)
    print(f"  📈 Chart saved: {path4}")
    
    print(f"\n  ✅ Semua grafik BLEU berhasil disimpan!")


def main():
    print("=" * 75)
    print("  SKENARIO 3: EVALUASI BLEU SCORE - NLG MODE KALIMAT")
    print("  Menggunakan SELURUH 26 Label Kata BISINDO")
    print("=" * 75)
    
    # Initialize handler
    handler = KalimatNLGHandler()
    print(f"\n✅ KalimatNLGHandler initialized ({len(handler.labels)} labels)")
    print(f"   Labels: {', '.join(ALL_KATA_LABELS[:10])}...")
    
    # Evaluate each category
    struct_results, struct_b1, struct_b2 = evaluate_category(
        handler, STRUCTURED_TESTS,
        f"📗 A. KALIMAT TERSTRUKTUR ({len(STRUCTURED_TESTS)} Test Cases)"
    )
    
    random_results, random_b1, random_b2 = evaluate_category(
        handler, RANDOM_TESTS,
        f"🔀 B. KALIMAT ACAK / TIDAK TERSTRUKTUR ({len(RANDOM_TESTS)} Test Cases)"
    )
    
    single_results, single_b1, single_b2 = evaluate_category(
        handler, SINGLE_WORD_TESTS,
        f"🔤 C. KATA TUNGGAL - SEMUA 26 LABEL ({len(SINGLE_WORD_TESTS)} Test Cases)"
    )
    
    # Combine all results
    all_results = struct_results + random_results + single_results
    all_bleu1 = [r["bleu1"] for r in all_results]
    all_bleu2 = [r["bleu2"] for r in all_results]
    
    overall_b1 = sum(all_bleu1) / len(all_bleu1) if all_bleu1 else 0
    overall_b2 = sum(all_bleu2) / len(all_bleu2) if all_bleu2 else 0
    
    total_tests = len(STRUCTURED_TESTS) + len(RANDOM_TESTS) + len(SINGLE_WORD_TESTS)
    
    # Label Coverage Check
    covered, missing = check_label_coverage(all_results)
    
    # Overall Summary
    print("\n" + "=" * 75)
    print("  📊 RINGKASAN EVALUASI BLEU - SKENARIO 3 (SEMUA LABEL)")
    print("=" * 75)
    
    print(f"\n  {'Kategori':<45} {'N':>4} {'BLEU-1':>10} {'BLEU-2':>10}")
    print(f"  {'─' * 45} {'─' * 4} {'─' * 10} {'─' * 10}")
    print(f"  {'A. Kalimat Terstruktur':<45} {len(STRUCTURED_TESTS):>4} {struct_b1:>10.4f} {struct_b2:>10.4f}")
    print(f"  {'B. Kalimat Acak':<45} {len(RANDOM_TESTS):>4} {random_b1:>10.4f} {random_b2:>10.4f}")
    print(f"  {'C. Kata Tunggal (Semua 26 Label)':<45} {len(SINGLE_WORD_TESTS):>4} {single_b1:>10.4f} {single_b2:>10.4f}")
    print(f"  {'─' * 45} {'─' * 4} {'─' * 10} {'─' * 10}")
    print(f"  {'OVERALL AVERAGE':<45} {total_tests:>4} {overall_b1:>10.4f} {overall_b2:>10.4f}")
    
    # Label coverage
    print(f"\n  📋 Cakupan Label:")
    print(f"     Total label kata:     26")
    print(f"     Label tercakup:       {len(covered)}")
    if missing:
        print(f"     Label belum tercakup: {', '.join(sorted(missing))}")
    else:
        print(f"     ✅ SEMUA 26 LABEL TERCAKUP!")
    
    print(f"\n  Interpretasi BLEU Score:")
    print(f"  {'─' * 40}")
    print(f"  0.0 - 0.1  : Sangat rendah")
    print(f"  0.1 - 0.3  : Rendah")
    print(f"  0.3 - 0.5  : Cukup baik")
    print(f"  0.5 - 0.7  : Baik")
    print(f"  0.7 - 1.0  : Sangat baik")
    
    # Quality assessment
    if overall_b1 >= 0.7:
        quality = "SANGAT BAIK ✅"
    elif overall_b1 >= 0.5:
        quality = "BAIK ✅"
    elif overall_b1 >= 0.3:
        quality = "CUKUP BAIK ⚠️"
    else:
        quality = "PERLU PERBAIKAN ❌"
    
    print(f"\n  ┌──────────────────────────────────────────┐")
    print(f"  │  Kualitas NLG Mode Kalimat: {quality:<13}│")
    print(f"  │  Overall BLEU-1: {overall_b1:.4f}                 │")
    print(f"  │  Overall BLEU-2: {overall_b2:.4f}                 │")
    print(f"  │  Total Test Cases: {total_tests:<21}│")
    print(f"  └──────────────────────────────────────────┘")
    
    # Detail: Show cases with low BLEU
    low_bleu = [(r["input"], r["generated"], r["references"], r["bleu1"]) 
                for r in all_results if r["bleu1"] < 0.3]
    
    if low_bleu:
        print(f"\n  ⚠️  Test cases dengan BLEU-1 < 0.3 ({len(low_bleu)} kasus):")
        for inp, gen, refs, b1 in low_bleu:
            output_display = f'"{gen}"' if gen else "(kosong)"
            print(f"      Input:    {inp}")
            print(f"      Output:   {output_display}")
            print(f"      Expected: \"{refs[0]}\"")
            print(f"      BLEU-1:   {b1:.4f}")
            print()
    else:
        print(f"\n  ✅ Semua test cases memiliki BLEU-1 >= 0.3!")
    
    # Perfect scores
    perfect = sum(1 for b in all_bleu1 if b >= 0.99)
    print(f"  📊 Statistik Tambahan:")
    print(f"     Perfect BLEU-1 (≥0.99): {perfect}/{total_tests} ({perfect/total_tests*100:.1f}%)")
    print(f"     BLEU-1 ≥ 0.5:          {sum(1 for b in all_bleu1 if b >= 0.5)}/{total_tests}")
    print(f"     BLEU-1 < 0.3:          {len(low_bleu)}/{total_tests}")
    
    print("\n" + "=" * 75)
    print("  Evaluasi BLEU Mode Kalimat selesai!")
    print("=" * 75)
    
    # Generate Charts
    generate_charts(
        struct_results, random_results, single_results,
        struct_b1, struct_b2, random_b1, random_b2,
        single_b1, single_b2, overall_b1, overall_b2
    )


if __name__ == "__main__":
    main()

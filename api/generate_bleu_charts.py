"""
Generate thesis-quality BLEU charts for Mode Kalimat NLG evaluation.
Outputs clean, professional charts suitable for A4 thesis documents.
"""
import os
import sys
import warnings
import numpy as np

warnings.filterwarnings('ignore')
os.environ['TF_CPP_MIN_LOG_LEVEL'] = '3'

sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))

import matplotlib
matplotlib.use('Agg')
import matplotlib.pyplot as plt
from matplotlib.ticker import MultipleLocator

# --- Run evaluation to get real data ---
from nlg_kalimat_handler import KalimatNLGHandler
from eval_kalimat_bleu import (
    STRUCTURED_TESTS, RANDOM_TESTS, SINGLE_WORD_TESTS,
    compute_bleu
)

OUTPUT_DIR = os.path.dirname(os.path.abspath(__file__))

def collect_results():
    """Run all test cases and collect BLEU scores."""
    handler = KalimatNLGHandler()
    all_results = []
    
    for category, tests, cat_label in [
        ("Terstruktur", STRUCTURED_TESTS, "A"),
        ("Acak", RANDOM_TESTS, "B"),
        ("Kata Tunggal", SINGLE_WORD_TESTS, "C"),
    ]:
        for tc in tests:
            result = handler.naturalize(tc["input"], tc["token_types"], mode="strict")
            generated = result["natural_text"]
            bleu1, bleu2 = compute_bleu(generated, tc["references"])
            all_results.append({
                "input": tc["input"],
                "generated": generated,
                "bleu1": bleu1,
                "bleu2": bleu2,
                "category": category,
                "cat_label": cat_label,
            })
    return all_results


def generate_line_chart(results):
    """Generate thesis-quality line chart."""
    # --- Thesis style config ---
    plt.rcParams.update({
        'font.family': 'serif',
        'font.serif': ['Times New Roman', 'DejaVu Serif', 'serif'],
        'font.size': 10,
        'axes.labelsize': 11,
        'axes.titlesize': 12,
        'legend.fontsize': 9,
        'xtick.labelsize': 9,
        'ytick.labelsize': 9,
        'figure.dpi': 300,
    })

    fig, ax = plt.subplots(figsize=(7.5, 4.0))  # ~A4 width friendly
    fig.patch.set_facecolor('white')
    ax.set_facecolor('white')

    n = len(results)
    x = np.arange(1, n + 1)
    b1 = [r['bleu1'] for r in results]
    b2 = [r['bleu2'] for r in results]

    # Category boundaries
    n_struct = sum(1 for r in results if r['cat_label'] == 'A')
    n_random = sum(1 for r in results if r['cat_label'] == 'B')
    n_single = sum(1 for r in results if r['cat_label'] == 'C')

    # Shaded regions for categories
    ax.axvspan(0.5, n_struct + 0.5, alpha=0.06, color='#2196F3', zorder=0)
    ax.axvspan(n_struct + 0.5, n_struct + n_random + 0.5, alpha=0.06, color='#9C27B0', zorder=0)
    ax.axvspan(n_struct + n_random + 0.5, n + 0.5, alpha=0.06, color='#4CAF50', zorder=0)

    # Plot BLEU-1
    ax.plot(x, b1, color='#1565C0', marker='o', markersize=3.5, linewidth=1.2,
            label='BLEU-1 (Unigram)', zorder=4, alpha=0.9)

    # Plot BLEU-2
    ax.plot(x, b2, color='#C62828', marker='s', markersize=2.8, linewidth=1.0,
            label='BLEU-2 (Bigram)', zorder=4, alpha=0.8, linestyle='--')

    # Moving average (window=5)
    if len(b1) >= 5:
        window = 5
        avg_b1 = np.convolve(b1, np.ones(window)/window, mode='valid')
        x_avg = np.arange(window, len(b1) + 1)
        ax.plot(x_avg, avg_b1, color='#2E7D32', linewidth=2.0, linestyle='-',
                label=f'Moving Avg BLEU-1 (w={window})', zorder=3, alpha=0.6)

    # Category boundary lines
    ax.axvline(x=n_struct + 0.5, color='#616161', linestyle=':', alpha=0.5, linewidth=0.8)
    ax.axvline(x=n_struct + n_random + 0.5, color='#616161', linestyle=':', alpha=0.5, linewidth=0.8)

    # Category labels at top
    ax.text(n_struct / 2 + 0.5, 1.07,
            f'Kat. A: Terstruktur\n(n={n_struct})',
            ha='center', fontsize=8, color='#1565C0', fontweight='bold',
            transform=ax.get_xaxis_transform())
    ax.text(n_struct + n_random / 2 + 0.5, 1.07,
            f'Kat. B: Acak\n(n={n_random})',
            ha='center', fontsize=8, color='#7B1FA2', fontweight='bold',
            transform=ax.get_xaxis_transform())
    ax.text(n_struct + n_random + n_single / 2 + 0.5, 1.07,
            f'Kat. C: Tunggal\n(n={n_single})',
            ha='center', fontsize=8, color='#2E7D32', fontweight='bold',
            transform=ax.get_xaxis_transform())

    # Threshold reference line
    ax.axhline(y=0.7, color='#E65100', linestyle='--', alpha=0.4, linewidth=0.8)
    ax.text(n + 1.5, 0.695, 'Threshold\n0.70', fontsize=7, color='#E65100', alpha=0.7, va='center')

    # Overall mean lines
    mean_b1 = np.mean(b1)
    mean_b2 = np.mean(b2)
    ax.axhline(y=mean_b1, color='#1565C0', linestyle='-.', alpha=0.3, linewidth=0.7)
    ax.text(n + 1.5, mean_b1, f'Avg\n{mean_b1:.3f}', fontsize=7, color='#1565C0', alpha=0.7, va='center')

    # Formatting
    ax.set_xlabel('Nomor Test Case', fontweight='bold')
    ax.set_ylabel('Skor BLEU', fontweight='bold')
    ax.set_title('Grafik BLEU Score per Test Case — NLG Mode Kalimat\n(81 Test Cases, 26 Label BISINDO)',
                 fontweight='bold', pad=24)
    ax.set_xlim(0.5, n + 3)
    ax.set_ylim(-0.05, 1.18)
    ax.yaxis.set_major_locator(MultipleLocator(0.1))
    ax.xaxis.set_major_locator(MultipleLocator(5))
    ax.legend(loc='lower left', framealpha=0.9, edgecolor='#BDBDBD')
    ax.grid(axis='both', alpha=0.15, color='#9E9E9E', zorder=0)
    ax.spines['top'].set_visible(False)
    ax.spines['right'].set_visible(False)

    plt.tight_layout()
    path = os.path.join(OUTPUT_DIR, 'bleu_line_chart_thesis.png')
    fig.savefig(path, dpi=300, bbox_inches='tight', facecolor='white')
    plt.close(fig)
    print(f"  [OK] Line chart: {path}")
    return path


def generate_bar_chart(results):
    """Generate thesis-quality grouped bar chart per category."""
    plt.rcParams.update({
        'font.family': 'serif',
        'font.serif': ['Times New Roman', 'DejaVu Serif', 'serif'],
        'font.size': 10,
        'axes.labelsize': 11,
        'axes.titlesize': 12,
        'legend.fontsize': 9,
        'figure.dpi': 300,
    })

    fig, ax = plt.subplots(figsize=(6.0, 3.8))
    fig.patch.set_facecolor('white')
    ax.set_facecolor('white')

    # Compute per-category averages
    cats = [
        ("A. Kalimat\nTerstruktur", "A"),
        ("B. Kalimat\nAcak", "B"),
        ("C. Kata\nTunggal", "C"),
    ]
    b1_avgs, b2_avgs, counts = [], [], []
    for label, code in cats:
        cat_results = [r for r in results if r['cat_label'] == code]
        b1_avgs.append(np.mean([r['bleu1'] for r in cat_results]))
        b2_avgs.append(np.mean([r['bleu2'] for r in cat_results]))
        counts.append(len(cat_results))

    # Add overall
    overall_b1 = np.mean([r['bleu1'] for r in results])
    overall_b2 = np.mean([r['bleu2'] for r in results])
    cat_labels = [c[0] for c in cats] + ["Overall\nAverage"]
    b1_avgs.append(overall_b1)
    b2_avgs.append(overall_b2)
    counts.append(len(results))

    x = np.arange(len(cat_labels))
    width = 0.30

    bars1 = ax.bar(x - width/2, b1_avgs, width, label='BLEU-1',
                   color='#1565C0', edgecolor='white', linewidth=0.8, zorder=3)
    bars2 = ax.bar(x + width/2, b2_avgs, width, label='BLEU-2',
                   color='#C62828', edgecolor='white', linewidth=0.8, zorder=3)

    # Value labels
    for bar in bars1:
        h = bar.get_height()
        ax.text(bar.get_x() + bar.get_width()/2., h + 0.015,
                f'{h:.4f}', ha='center', va='bottom', fontsize=8, fontweight='bold', color='#1565C0')
    for bar in bars2:
        h = bar.get_height()
        ax.text(bar.get_x() + bar.get_width()/2., h + 0.015,
                f'{h:.4f}', ha='center', va='bottom', fontsize=8, fontweight='bold', color='#C62828')

    # Count labels
    for i, c in enumerate(counts):
        ax.text(i, -0.08, f'n={c}', ha='center', fontsize=8, color='#616161',
                transform=ax.get_xaxis_transform())

    # Threshold
    ax.axhline(y=0.7, color='#E65100', linestyle='--', alpha=0.4, linewidth=0.8)
    ax.text(len(cat_labels) - 0.3, 0.72, 'Sangat Baik (0.70)', fontsize=7, color='#E65100', alpha=0.7)

    ax.set_ylabel('Skor BLEU', fontweight='bold')
    ax.set_title('Rata-rata BLEU Score per Kategori — NLG Mode Kalimat',
                 fontweight='bold', pad=12)
    ax.set_xticks(x)
    ax.set_xticklabels(cat_labels, fontsize=9)
    ax.set_ylim(0, 1.15)
    ax.yaxis.set_major_locator(MultipleLocator(0.1))
    ax.legend(loc='upper right', framealpha=0.9, edgecolor='#BDBDBD')
    ax.grid(axis='y', alpha=0.15, color='#9E9E9E', zorder=0)
    ax.spines['top'].set_visible(False)
    ax.spines['right'].set_visible(False)

    plt.tight_layout()
    path = os.path.join(OUTPUT_DIR, 'bleu_bar_chart_thesis.png')
    fig.savefig(path, dpi=300, bbox_inches='tight', facecolor='white')
    plt.close(fig)
    print(f"  [OK] Bar chart: {path}")
    return path


if __name__ == "__main__":
    print("=" * 60)
    print("  Generating Thesis-Quality BLEU Charts")
    print("=" * 60)
    
    print("\n[1/3] Collecting BLEU scores from 81 test cases...")
    results = collect_results()
    
    b1_all = [r['bleu1'] for r in results]
    b2_all = [r['bleu2'] for r in results]
    print(f"  Overall BLEU-1: {np.mean(b1_all):.4f}")
    print(f"  Overall BLEU-2: {np.mean(b2_all):.4f}")
    
    print("\n[2/3] Generating line chart...")
    line_path = generate_line_chart(results)
    
    print("\n[3/3] Generating bar chart...")
    bar_path = generate_bar_chart(results)
    
    print(f"\n{'=' * 60}")
    print(f"  Charts saved to: {OUTPUT_DIR}")
    print(f"{'=' * 60}")

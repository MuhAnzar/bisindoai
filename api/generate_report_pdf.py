from reportlab.lib.pagesizes import A4
from reportlab.lib import colors
from reportlab.lib.styles import getSampleStyleSheet, ParagraphStyle
from reportlab.platypus import SimpleDocTemplate, Paragraph, Spacer, Image, Table, TableStyle, PageBreak
from reportlab.lib.units import inch
from reportlab.lib.enums import TA_CENTER, TA_JUSTIFY
import os
from datetime import datetime

def generate_pdf():
    # Setup directory
    base_dir = os.path.dirname(os.path.abspath(__file__))
    output_dir = os.path.join(base_dir, 'test')
    os.makedirs(output_dir, exist_ok=True)
    output_filename = os.path.join(output_dir, "Laporan_Eksperimen_Mode_Kalimat.pdf")

    doc = SimpleDocTemplate(output_filename, pagesize=A4,
                            rightMargin=72, leftMargin=72,
                            topMargin=72, bottomMargin=18)

    styles = getSampleStyleSheet()
    styles.add(ParagraphStyle(name='Justify', alignment=TA_JUSTIFY, parent=styles['Normal'], spaceAfter=12))
    styles.add(ParagraphStyle(name='CenterTitle', alignment=TA_CENTER, parent=styles['Heading1'], spaceAfter=24))
    styles.add(ParagraphStyle(name='CenterSubtitle', alignment=TA_CENTER, parent=styles['Normal'], fontSize=12, spaceAfter=48))
    
    Story = []

    # --- Title Page ---
    Story.append(Spacer(1, 2*inch))
    Story.append(Paragraph("LAPORAN PENGEMBANGAN DAN EKSPERIMEN", styles['CenterTitle']))
    Story.append(Paragraph("MODE KALIMAT (SENTENCE MODE)", styles['CenterTitle']))
    Story.append(Paragraph("Sistem Penerjemah Bahasa Isyarat BISINDO Berbasis Deep Learning", styles['CenterSubtitle']))
    Story.append(Spacer(1, 1*inch))
    Story.append(Paragraph(f"Tanggal: {datetime.now().strftime('%d %B %Y')}", styles['CenterSubtitle']))
    Story.append(PageBreak())

    # --- 1. Pendahuluan ---
    Story.append(Paragraph("1. Pendahuluan", styles['Heading1']))
    text = """
    Laporan ini mendokumentasikan pengembangan fitur Mode Kalimat, sebuah sistem multimodal yang dirancang untuk menerjemahkan rangkaian isyarat BISINDO menjadi kalimat bahasa Indonesia yang natural. 
    Sistem ini menggabungkan dua model saraf tiruan (CNN Abjad dan CNN-LSTM Kata) dengan mesin Natural Language Generation (NLG) yang cerdas.
    """
    Story.append(Paragraph(text, styles['Justify']))

    # --- 2. Arsitektur Mode Kalimat (Multimodal) ---
    Story.append(Paragraph("2. Arsitektur Mode Kalimat (Multimodal)", styles['Heading1']))
    text = """
    Mode Kalimat menggunakan pendekatan "Frontend-Driven Orchestration", di mana aplikasi klien (browser) bertindak sebagai pengontrol utama yang memutuskan model mana yang digunakan berdasarkan konteks kalimat target.
    Arsitektur ini terdiri dari dua jalur pemrosesan paralel yang bermuara pada satu API backend.
    """
    Story.append(Paragraph(text, styles['Justify']))
    
    # Keterangan visual arsitektur (tabel pengganti diagram)
    data = [
        ["Komponen", "Deskripsi"],
        ["Orkestrator Frontend", "deteksi.blade.php mengelola state target kata/ejaan"],
        ["Jalur A: Model Abjad", "CNN 224x224 (Input: Gambar Statis + Smart Crop)"],
        ["Jalur B: Model Kata", "CNN-LSTM (Input: Sequence Video + Mirroring)"],
        ["NLG Engine", "Pipeline 6-Tahap penyusun kalimat natural"]
    ]
    t_arch = Table(data, colWidths=[2*inch, 4*inch])
    t_arch.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,0), colors.teal),
        ('TEXTCOLOR', (0,0), (-1,0), colors.whitesmoke),
        ('ALIGN', (0,0), (-1,-1), 'LEFT'),
        ('FONTNAME', (0,0), (-1,0), 'Helvetica-Bold'),
        ('BOTTOMPADDING', (0,0), (-1,0), 12),
        ('BACKGROUND', (0,1), (-1,-1), colors.aliceblue),
        ('GRID', (0,0), (-1,-1), 1, colors.black)
    ]))
    Story.append(t_arch)
    Story.append(Spacer(1, 12))

    # --- 2.1 Logika Switching ---
    Story.append(Paragraph("2.1 Logika Switching (Model Dispatching)", styles['Heading2']))
    text = """
    Sistem menggunakan logika switching berbasis target (Target-Based Dispatching) yang terjadi di sisi klien (Javascript). 
    Logika ini memastikan model yang paling spesifik digunakan untuk setiap segmen kalimat.
    """
    Story.append(Paragraph(text, styles['Justify']))

    text_logic = """
    Algoritma Switching:
    1. Sistem memeriksa target saat ini (`targetWord[currentIndex]`).
    2. <b>Jika panjang target == 1 huruf:</b>
       - Aktifkan <b>Mode Abjad</b>.
       - Lakukan <i>Smart Crop</i> pada koordinat tangan.
       - Kirim ke endpoint <code>/predict</code>.
    3. <b>Jika panjang target > 1 huruf:</b>
       - Aktifkan <b>Mode Kata</b>.
       - Ambil <i>Full Frame</i> dan lakukan <i>Horizontal Flip</i> (Mirroring).
       - Kirim ke endpoint <code>/predict/kata</code>.
    """
    Story.append(Paragraph(text_logic, styles['Justify'], bulletText='•'))
    
    # Tabel Perbandingan Preprocessing
    data_proc = [
        ["Fitur", "Mode Abjad (Huruf)", "Mode Kata (Kata)"],
        ["Tipe Model", "CNN (MobileNetV2)", "CNN-LSTM + MediaPipe"],
        ["Input Data", "Gambar Statis (1 Frame)", "Sequence Video (20 Frames)"],
        ["Preprocessing", "Smart Crop (Hand only)", "Full Frame + Resize 640px"],
        ["Transformasi", "Normalisasi [0,1]", "Horizontal Flip + Holistic"],
        ["Endpoint", "POST /predict", "POST /predict/kata"]
    ]
    t_proc = Table(data_proc, colWidths=[1.5*inch, 2.2*inch, 2.3*inch])
    t_proc.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,0), colors.grey),
        ('TEXTCOLOR', (0,0), (-1,0), colors.whitesmoke),
        ('ALIGN', (0,0), (-1,-1), 'LEFT'),
        ('FONTNAME', (0,0), (-1,0), 'Helvetica-Bold'),
        ('GRID', (0,0), (-1,-1), 1, colors.black)
    ]))
    Story.append(t_proc)
    Story.append(Spacer(1, 12))
    
    Story.append(PageBreak())

    # --- 3. Pipeline NLG Multimodal (Updated numbering) ---
    Story.append(Paragraph("3. Pipeline NLG Multimodal", styles['Heading1']))
    text = """
    Setelah seluruh token terkumpul dari proses deteksi campuran di atas, token dikirim ke backend NLG untuk diproses melalui 6 tahapan:
    1. Merge Spelling: Menggabungkan huruf menjadi kata (misal: A-D-I -> Adi).
    2. Merge Compounds: Menggabungkan kata majemuk (Terima + Kasih -> Terima Kasih).
    3. Canonicalize: Validasi token terhadap kamus resmi.
    4. Deduplicate: Menghapus duplikasi berurutan.
    5. Segmentasi & SPOK Reordering: Mengurutkan token acak menjadi struktur Subjek-Predikat-Objek-Keterangan.
    6. Assembly Enhanced: Menambahkan imbuhan, kata bantu, dan tanda baca.
    """
    Story.append(Paragraph(text, styles['Justify']))

    # --- 4. Hasil Eksperimen BLEU ---
    Story.append(Paragraph("4. Evaluasi Kualitas Bahasa (BLEU)", styles['Heading1']))
    text = """
    Evaluasi dilakukan menggunakan metrik BLEU-1 (unigram) dan BLEU-2 (bigram) terhadap 81 kasus uji.
    Hasil menunjukkan performa yang sangat baik, terutama pada kategori kalimat terstruktur.
    """
    Story.append(Paragraph(text, styles['Justify']))

    # Grafik BLEU
    img_path = os.path.join(base_dir, 'bleu_per_kategori.png')
    if os.path.exists(img_path):
        im = Image(img_path, width=6*inch, height=4*inch)
        Story.append(im)
        Story.append(Paragraph("Gambar 1: Rata-rata Skor BLEU per Kategori", styles['CenterSubtitle']))
    
    img_path2 = os.path.join(base_dir, 'bleu_distribusi.png')
    if os.path.exists(img_path2):
        im = Image(img_path2, width=6*inch, height=4*inch)
        Story.append(im)
        Story.append(Paragraph("Gambar 2: Distribusi Skor BLEU", styles['CenterSubtitle']))

    text_res = """
    Rata-rata Skor BLEU Keseluruhan:
    BLEU-1: 0.8984 (Sangat Baik)
    BLEU-2: 0.7074 (Baik)
    """
    Story.append(Paragraph(text_res, styles['Justify']))

    # --- 5. Hasil Eksperimen mAP ---
    Story.append(PageBreak())
    Story.append(Paragraph("5. Evaluasi Akurasi Deteksi Terpadu (mAP)", styles['Heading1']))
    text = """
    Evaluasi Mean Average Precision (mAP) dilakukan pada 10 kalimat uji multimodal yang menggabungkan isyarat Abjad dan Kata.
    """
    Story.append(Paragraph(text, styles['Justify']))

    # Grafik mAP
    img_path3 = os.path.join(base_dir, 'map_per_kalimat.png')
    if os.path.exists(img_path3):
        im = Image(img_path3, width=6*inch, height=4*inch)
        Story.append(im)
        Story.append(Paragraph("Gambar 3: mAP per Kalimat Uji", styles['CenterSubtitle']))

    img_path4 = os.path.join(base_dir, 'map_pr_curve_kata.png')
    if os.path.exists(img_path4):
        im = Image(img_path4, width=6*inch, height=4*inch)
        Story.append(im)
        Story.append(Paragraph("Gambar 4: Precision-Recall Curve (Model Kata)", styles['CenterSubtitle']))

    # --- kesimpulan ---
    Story.append(Paragraph("6. Kesimpulan", styles['Heading1']))
    text = """
    Pengembangan Mode Kalimat berhasil mencapai tujuannya untuk menerjemahkan isyarat dinamis menjadi bahasa alami.
    Sistem switching otomatis yang cerdas memungkinkan penggunaan model yang optimal (Abjad vs Kata) sesuai konteks input.
    Algoritma SPOK Reordering terbukti efektif meningkatkan keterbacaan kalimat dari input isyarat yang acak.
    """
    Story.append(Paragraph(text, styles['Justify']))

    doc.build(Story)
    print(f"PDF berhasil dibuat: {output_filename}")

if __name__ == "__main__":
    generate_pdf()

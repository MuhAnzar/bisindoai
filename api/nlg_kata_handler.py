"""
NLG Kata Handler (Word Mode)
Menangani pemrosesan level kata dan kosakata dasar.
"""

import re
from typing import List, Dict, Tuple, Optional

# -----------------------------
# Constants & Vocabulary
# -----------------------------
_ws_re = re.compile(r"\s+")
_non_letter_re = re.compile(r"[^A-Za-z]+")
_punct_re = re.compile(r"[^\w\s]+", re.UNICODE)

def canon(label: str) -> str:
    """Trim, uppercase, replace internal whitespace with underscore."""
    s = (label or "").strip()
    s = _ws_re.sub("_", s)
    return s.upper()

# Strictly matched to labels_v3.json
LABELS = {canon(x) for x in [
    "Apa", "Apa Kabar", "Bagaimana", "Baik", "Belajar", 
    "Berapa", "Bingung", "Dia", "Halo", "Kamu", 
    "Makan", "Marah", "Melihat", "Menulis", "Sabar", 
    "Saya", "Selamat Malam", "Selamat Pagi", "Selamat Siang", "Selamat Sore", 
    "Siapa", "Terima Kasih", "Tidur", "Tinggi", "Tuli", "maaf"
]}

# Subsets based on grammatical function (Manual classification of the limited set)
PRON = {canon(x) for x in ["Saya", "Kamu", "Dia"]}
WH = {canon(x) for x in ["Apa", "Siapa", "Berapa", "Bagaimana"]}
VERB = {canon(x) for x in ["Belajar", "Makan", "Melihat", "Menulis", "Tidur"]}
ADJ = {canon(x) for x in ["Baik", "Bingung", "Marah", "Sabar", "Tinggi", "Tuli"]}
NOUN = set() # No explicit nouns in this limited set (except maybe implied objects like "Kabar")

PHRASE_APA_KABAR = canon("Apa Kabar")
THANKS = canon("Terima Kasih")
MAAF = canon("maaf")

GREETING_PREFIX = {
    canon("Halo"), canon("Selamat Pagi"), canon("Selamat Siang"),
    canon("Selamat Sore"), canon("Selamat Malam"), canon("maaf"),
    PHRASE_APA_KABAR,
}

COMPOUND_MAP: Dict[Tuple[str, str], str] = {
    (canon("Terima"), canon("Kasih")): THANKS,
    (canon("Selamat"), canon("Pagi")): canon("Selamat Pagi"),
    (canon("Selamat"), canon("Siang")): canon("Selamat Siang"),
    (canon("Selamat"), canon("Sore")): canon("Selamat Sore"),
    (canon("Selamat"), canon("Malam")): canon("Selamat Malam"),
}

# -----------------------------
# Helper Functions
# -----------------------------
def humanize_label(l: str) -> str:
    if l.startswith("RAW:"):
        return l[4:].strip()
    if l.startswith("NAME:"):
        return l[5:].strip().title()
    return l.replace("_", " ").title().strip()

def lower_first(s: str) -> str:
    return (s[:1].lower() + s[1:]) if s else s

def is_single_letter(x: str) -> bool:
    x = (x or "").strip()
    return len(x) == 1 and x.isalpha()

def format_name_from_letters(letters: List[str]) -> str:
    return "".join([c.upper() for c in letters]).title()

def collapse_repeated_letters(s: str, max_run: int = 2) -> str:
    out = []
    run_char = ""
    run_len = 0
    for ch in s:
        if ch == run_char:
            run_len += 1
        else:
            run_char = ch
            run_len = 1
        if run_len <= max_run:
            out.append(ch)
    return "".join(out)

def clean_for_match(word: str) -> str:
    if not word:
        return ""
    letters_only = _non_letter_re.sub("", word)
    if not letters_only:
        return ""
    s = collapse_repeated_letters(letters_only.lower(), max_run=2)
    return s.upper()

def clean_text_basic(text: str) -> str:
    text = text or ""
    text = text.strip()
    text = _ws_re.sub(" ", text)
    return text

def levenshtein(a: str, b: str) -> int:
    if a == b: return 0
    if not a: return len(b)
    if not b: return len(a)
    prev = list(range(len(b) + 1))
    for i, ca in enumerate(a, start=1):
        cur = [i]
        for j, cb in enumerate(b, start=1):
            cur.append(min(cur[j-1]+1, prev[j]+1, prev[j-1]+(0 if ca==cb else 1)))
        prev = cur
    return prev[-1]

def auto_correct_label(tok: str, notes: List[str]) -> str:
    if tok in LABELS or tok.startswith("NAME:") or tok.startswith("RAW:"):
        return tok
    if "_" in tok:
        return tok
    
    # Preserve valid Indonesian words not in LABELS but useful for context (optional)
    # Removing extended list to be strict as per user request
    
    if 2 <= len(tok) <= 12:
        best, best_d = None, 999
        for lbl in LABELS:
            # Skip multi-word labels for simple typo correction to avoid aggressive matching
            if "_" in lbl: continue
            
            d = levenshtein(tok, lbl)
            if d < best_d: best, best_d = lbl, d
        
        # Stricter threshold for small vocabulary
        thresh = 1 if len(tok) <= 5 else 2
        
        if best and best_d <= thresh:
            notes.append(f"typo corrected: {tok} -> {best}")
            return best
    return tok

def classify_token(tok: str) -> str:
    """Klasifikasi token untuk kebutuhan SPOK."""
    if tok.startswith("NAME:"): return "SUBJEK" # Nama orang adalah subjek
    if tok.startswith("RAW:"): return "OBJEK" # Kata acak biasanya objek atau keterangan
    if tok in GREETING_PREFIX or tok == THANKS: return "GREETING"
    if tok in PRON: return "SUBJEK" # Kata ganti adalah subjek
    if tok in WH or tok == PHRASE_APA_KABAR: return "WH"
    if tok in VERB: return "PREDIKAT" # Kata kerja adalah predikat
    if tok in ADJ: return "PREDIKAT" # Kata sifat bisa menjadi predikat
    if tok in NOUN: return "OBJEK" # Kata benda adalah objek
    
    # Deteksi lokasi sebagai Keterangan
    LOCATIONS = {canon(x) for x in ["Sekolah", "Rumah", "Pasar", "Kantor"]}
    if tok in LOCATIONS: return "KETERANGAN"
    
    if tok in LABELS: return "LABEL"
    return "UNKNOWN"

class KataNLGHandler:
    def __init__(self):
        self.labels = LABELS
        
    def process_kata(self, token: str) -> str:
        """Proses kata tunggal dengan validasi ketat."""
        c = canon(token)
        if c in LABELS:
            return humanize_label(c)
        
        # 1. Cek spelling (jika token berupa NAME: atau RAW: dari abjad)
        if token.startswith("NAME:") or token.startswith("RAW:"):
            return humanize_label(token)
            
        # 2. Coba auto-correct typo
        notes = []
        cleaned = clean_for_match(token)
        corrected = auto_correct_label(cleaned, notes)
        if corrected in LABELS:
            return humanize_label(corrected)
        
        # 3. Abaikan jika benar-benar tidak dikenal (Strict Validation)
        return ""

# -----------------------------
# Rich Feedback Data
# -----------------------------
RICH_FEEDBACK = {
    canon("Halo"): [
        "Luar biasa! Anda berhasil memperagakan isyarat 'Halo'. Isyarat ini digunakan sebagai sapaan ramah untuk memulai percakapan.",
        "Sempurna! Gerakan sapaan 'Halo' Anda sangat natural. Ini kunci penting pembuka komunikasi.",
        "Bagus sekali! Isyarat 'Halo' yang jelas akan membuat lawan bicara merasa disambut hangat."
    ],
    canon("Makan"): [
        "Tepat! Isyarat 'Makan' dilakukan dengan menggerakkan ujung jari tangan ke arah mulut secara berulang.",
        "Gerakan 'Makan' Anda sudah benar. Fokuskan gerakan tangan mengarah ke mulut seperti sedang menyuap makanan.",
        "Bagus! Isyarat ini sangat ikonik, meniru gerakan alami saat kita sedang makan."
    ],
    canon("Terima Kasih"): [
        "Benar. Gunakan isyarat ini untuk menunjukkan apresiasi. Pastikan telapak tangan bergerak menjauh dari dagu ke arah lawan bicara.",
        "Sangat baik! Ekspresi wajah tulus saat melakukan isyarat 'Terima Kasih' akan memperkuat makna rasa syukur.",
        "Gerakan 'Terima Kasih' Anda jelas. Ini adalah isyarat etika dasar yang sangat penting dalam BISINDO."
    ],
    canon("Siapa"): [
        "Isyarat 'Siapa' terdeteksi. Kata tanya ini digunakan untuk menanyakan identitas orang dalam percakapan.",
        "Benar sekali. 'Siapa' adalah kata tanya vital. Pastikan ekspresi wajah Anda juga menunjukkan rasa ingin tahu.",
        "Tepat! Gerakan tangan untuk 'Siapa' biasanya disertai dengan mimik wajah bertanya."
    ],
    canon("Maaf"): [
        "Tepat sekali. Isyarat 'Maaf' dilakukan dengan meletakkan kepalan tangan di dada. Ini menunjukkan ekspresi penyesalan.",
        "Sangat emosional. Gerakan memutar di dada untuk 'Maaf' melambangkan perasaan tulus dari hati.",
        "Bagus. Saat berisyarat 'Maaf', pastikan gerakan Anda lembut untuk menunjukkan ketulusan permintaan maaf."
    ],
    canon("Saya"): [
        "Betul! Isyarat 'Saya' dengan menunjuk dada sendiri adalah cara standar menunjuk diri sendiri.",
        "Simpel dan jelas! Menunjuk ke dada adalah cara universal untuk mengatakan 'Saya'.",
    ],
    canon("Kamu"): [
        "Benar! Menunjuk lawan bicara dengan telapak terbuka atau telunjuk adalah isyarat untuk 'Kamu'.",
        "Tepat. Pastikan arah tunjuk 'Kamu' jelas ke arah orang yang diajak bicara.",
    ],
    canon("Apa"): [
        "Terdeteksi 'Apa'. Gunakan ini untuk menanyakan benda atau tindakan.",
        "Bagus. Isyarat 'Apa' penting untuk menggali informasi lebih lanjut.",
    ],
    canon("Sabar"): [
        "Luar biasa. Isyarat 'Sabar' dengan mengelus dada melambangkan ketenangan hati.",
        "Sangat filosofis. Gerakan mengelus dada ke bawah menunjukkan usaha menenangkan diri.",
    ],
    canon("Marah"): [
        "Ekspresif! Isyarat 'Marah' sering disertai mimik wajah yang tegas atau melotot.",
        "Tepat. Emosi 'Marah' terlihat jelas dari gerakan tangan yang lebih tajam atau cepat.",
    ],
    # Fallback generic templates can be added logically, 
    # but for now we rely on the specific list.
}

def get_rich_feedback(label: str) -> Optional[str]:
    """Returns a random rich feedback string if available."""
    import random
    lbl = canon(label)
    if lbl in RICH_FEEDBACK:
        return random.choice(RICH_FEEDBACK[lbl])
    return None

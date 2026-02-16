"""
NLG Kalimat Handler (Sentence Mode)
Menangani penyusunan kalimat (Sentence Construction) dari token BISINDO.
"""

import random
import re
from itertools import permutations
from typing import List, Tuple, Dict, Optional, Any
from functools import lru_cache
import traceback

from nlg_kata_handler import (
    LABELS, PRON, WH, VERB, ADJ, NOUN,
    PHRASE_APA_KABAR, THANKS, MAAF, GREETING_PREFIX, COMPOUND_MAP,
    canon, humanize_label, lower_first, classify_token, 
    clean_for_match, auto_correct_label, is_single_letter, 
    format_name_from_letters, get_rich_feedback
)

# -----------------------------
# Configuration & Constants
# -----------------------------
GREETING_VARIATIONS: Dict[str, List[str]] = {
    canon("Halo"): ["Halo!", "Hai!"],
    canon("Selamat Pagi"): ["Selamat pagi!", "Pagi!", "Selamat pagi, semoga harimu menyenangkan!"],
    canon("Selamat Siang"): ["Selamat siang!", "Siang!"],
    canon("Selamat Sore"): ["Selamat sore!", "Sore!", "Selamat sore, semoga sorenya menyenangkan!"],
    canon("Selamat Malam"): ["Selamat malam!", "Malam!", "Selamat malam, selamat beristirahat!"],
    canon("maaf"): ["Maaf.", "Mohon maaf.", "Maaf ya."],
    PHRASE_APA_KABAR: ["Apa kabar?", "Bagaimana kabarmu?", "Kabar baik?"],
    THANKS: ["Terima kasih!", "Terima kasih banyak!", "Makasih!", "Terima kasih ya!"],
}

EMOTION_CONTEXT = {
    canon("Marah"): {"intensity": "negative", "responses": [
        "Sabar ya.", "Tenang dulu.", "Semoga cepat reda."
    ]},
    canon("Bingung"): {"intensity": "negative", "responses": [
        "Pelan-pelan saja.", "Ada yang bisa dibantu?", "Jangan bingung."
    ]},
}

# -----------------------------
# Token Processing Logic
# -----------------------------
def canonicalize_tokens(raw_tokens: List[str], notes: List[str]) -> List[str]:
    """Hanya mengizinkan label resmi atau hasil pengejaan abjad (NAME/RAW)."""
    out: List[str] = []
    for t in raw_tokens:
        # 1. Izinkan token yang sudah diproses oleh merge_spelling (NAME: atau RAW: dari abjad)
        if isinstance(t, str) and (t.strip().upper().startswith("NAME:") or t.strip().upper().startswith("RAW:")):
            out.append(t.strip())
            continue
            
        # 2. Cek apakah token ada dalam daftar LABELS resmi
        c = canon(t)
        if c in LABELS:
            out.append(c)
            continue
            
        # 3. Coba auto-correct jika ada typo tipis
        cleaned = clean_for_match(t)
        if cleaned:
            cleaned = auto_correct_label(cleaned, notes)
            if cleaned in LABELS:
                out.append(cleaned)
                continue
                
        # 4. Jika bukan label dan bukan hasil spelling, ABAIKAN (Sesuai permintaan user)
        notes.append(f"ignored: {t} (not in labels/not spelling)")
        
    return out

def merge_spelling(tokens: List[str], token_types: List[str], notes: List[str]) -> List[str]:
    out: List[str] = []
    i, n = 0, len(tokens)
    while i < n:
        ty = (token_types[i] if i < len(token_types) else "SIGN").strip().upper()
        if ty == "SPELL" or is_single_letter(tokens[i]):
            # 1. Look ahead to see if it's a sequence of letters (at least 2 if not explicitly SPELL)
            letters: List[str] = []
            j = i
            
            # Check if current is valid letter
            curr_cleaned = tokens[j]
            if curr_cleaned.startswith("RAW:"): curr_cleaned = curr_cleaned.split(":", 1)[1]
            elif curr_cleaned.startswith("NAME:"): curr_cleaned = curr_cleaned.split(":", 1)[1]
            
            # If explicit SPELL or it's a single letter, start collecting
            is_explicit = (ty == "SPELL")
            if is_explicit or is_single_letter(curr_cleaned):
                while j < n:
                    next_ty = (token_types[j] if j < len(token_types) else "SIGN").strip().upper()
                    next_t = tokens[j]
                    if next_t.startswith("RAW:"): next_t = next_t.split(":", 1)[1]
                    elif next_t.startswith("NAME:"): next_t = next_t.split(":", 1)[1]
                    
                    # Stop if not SPELL and not single letter
                    if next_ty != "SPELL" and not is_single_letter(next_t):
                        break
                        
                    letters.append(next_t.strip().upper())
                    j += 1
            
            # Heuristic: Only merge if explicit SPELL or if we found multiple letters
            # Exception: Single letter "A" might be kept as is if not explicit? 
            # Actually, robust behavior: if >1 chars, merge. If 1 char and explicit, merge.
            if len(letters) > 1 or (len(letters) == 1 and is_explicit):
                raw_word = format_name_from_letters(letters)
                
                # Context check
                prev_token = out[-1] if out else ""
                prev_canon = canon(prev_token) if prev_token else ""
                
                is_name_context = False
                if prev_canon in [canon("SAYA"), canon("KAMU"), canon("DIA")]: is_name_context = True 
                elif prev_canon in [canon("NAMA"), canon("PANGGIL")]: is_name_context = True
                elif prev_canon in [canon("HALO"), canon("HAI")]: is_name_context = True
                elif prev_canon in [canon("KENAL")]: is_name_context = True
                
                final_token = ""
                if is_name_context:
                    final_token = f"NAME:{raw_word.title()}"
                    notes.append(f"smart_cls: {raw_word} -> NAME (ctx: {prev_token})")
                else:
                    final_token = f"RAW:{raw_word.title()}" 
                    notes.append(f"smart_cls: {raw_word} -> RAW")

                out.append(final_token)
                i = j
                continue
            # If single letter and not explicit SPELL, fall through to append as is (might be noise or single letter word?)
            # But "is_single_letter" check passed, so it IS a letter. 
            # Let's append it as is for now if it's just 1 letter and NOT explicit SPELL. 
            # It will likely be treated as RAW later.
        
        out.append(tokens[i])
        i += 1
    return out

def merge_compounds(tokens: List[str], notes: List[str]) -> List[str]:
    out: List[str] = []
    i = 0
    while i < len(tokens):
        if i + 1 < len(tokens):
            a, b = tokens[i], tokens[i + 1]
            # Gunakan canon() agar pencocokan case-insensitive dan whitespace-clean
            if (canon(a), canon(b)) in COMPOUND_MAP:
                merged_label = COMPOUND_MAP[(canon(a), canon(b))]
                out.append(merged_label)
                notes.append(f"compound merged: {a}+{b}")
                i += 2
                continue
        out.append(tokens[i])
        i += 1
    return out

def dedupe_consecutive(tokens: List[str], notes: List[str]) -> List[str]:
    if not tokens: return tokens
    out = [tokens[0]]
    removed = 0
    for t in tokens[1:]:
        if t == out[-1]:
            removed += 1
            continue
        out.append(t)
    if removed: notes.append(f"dedup: removed={removed}")
    return out

def split_prefix_core_suffix(tokens: List[str], notes: List[str]) -> Tuple[List[str], List[str], List[str]]:
    prefix, suffix, core = [], [], []
    for t in tokens:
        if t == THANKS: suffix.append(t)
        elif t in GREETING_PREFIX: prefix.append(t)
        else: core.append(t)
    
    seen = set()
    prefix2 = [t for t in prefix if not (t in seen or seen.add(t))]
    
    selamats = {canon("Selamat Pagi"), canon("Selamat Siang"), canon("Selamat Sore"), canon("Selamat Malam")}
    kept, seen_selamat = [], False
    for t in prefix2:
        if t in selamats:
            if not seen_selamat:
                kept.append(t)
                seen_selamat = True
        else:
            kept.append(t)
    
    return kept, core, [THANKS] if suffix else []

# -----------------------------
# Grammar & Segmentation
# -----------------------------
def count_wh_like(tokens: List[str]) -> int:
    return sum(1 for t in tokens if (t in WH or t == PHRASE_APA_KABAR))

def segment_by_pron(core: List[str]) -> List[List[str]]:
    if not core or count_wh_like(core) > 0: return [core] if core else []
    pron_pos = [i for i, t in enumerate(core) if t in PRON]
    if len(pron_pos) <= 1: return [core]
    clauses = []
    for k in range(len(pron_pos)):
        start = pron_pos[k]
        end = pron_pos[k + 1] if k + 1 < len(pron_pos) else len(core)
        clauses.append(core[start:end])
    return clauses

def segment_core(core: List[str], notes: List[str]) -> List[List[str]]:
    if not core: return []
    clauses = segment_by_pron(core)
    if len(clauses) > 1:
        notes.append("segmented: multi-pron")
        return clauses
    return [core]

def generate_candidates(tokens: List[str]) -> List[List[str]]:
    cand = {tuple(tokens)}
    
    # Prioritaskan Subjek di awal
    for i, t in enumerate(tokens):
        if classify_token(t) == "SUBJEK":
            cand.add(tuple([t] + tokens[:i] + tokens[i+1:]))
    
    # Prioritaskan WH di awal atau akhir
    for i, t in enumerate(tokens):
        if classify_token(t) == "WH":
            cand.add(tuple([t] + tokens[:i] + tokens[i+1:]))
            cand.add(tuple(tokens[:i] + tokens[i+1:] + [t]))

    # Swap sederhana untuk variasi
    for i in range(len(tokens) - 1):
        seq = tokens[:]
        seq[i], seq[i+1] = seq[i+1], seq[i]
        cand.add(tuple(seq))
        
    # Permutasi penuh untuk kalimat pendek (<= 4 kata) agar SPOK optimal ditemukan
    if 0 < len(tokens) <= 4:
        for p in permutations(tokens): cand.add(p)
        
    return [list(x) for x in cand]

def grammar_score(seq: List[str]) -> int:
    """Skor berdasarkan urutan SPOK Indonesia."""
    score = 0
    cats = [classify_token(t) for t in seq]
    score -= 10 * sum(1 for c in cats if c == "UNKNOWN")
    
    # 1. Posisi Subjek (S) di awal sangat bagus
    if "SUBJEK" in cats:
        idx = cats.index("SUBJEK")
        score += max(0, 15 - idx * 5) # Penalti berat jika subjek di belakang
    
    # 2. Pola S-P (Subjek diikuti Predikat)
    for i in range(len(seq) - 1):
        a, b = cats[i], cats[i+1]
        
        # S -> P (Sangat Bagus)
        if a == "SUBJEK" and b == "PREDIKAT": score += 25
        # P -> S (Kurang Natural)
        if a == "PREDIKAT" and b == "SUBJEK": score -= 15
        # P -> O (Bagus)
        if a == "PREDIKAT" and b == "OBJEK": score += 15
        # O -> K (Bagus)
        if a == "OBJEK" and b == "KETERANGAN": score += 10
        # P -> K (Bagus)
        if a == "PREDIKAT" and b == "KETERANGAN": score += 12
        # K -> S (Normal di Indonesia)
        if a == "KETERANGAN" and b == "SUBJEK": score += 10

    # 3. Urutan S-P-O atau S-P-K (Struktur ideal)
    for i in range(len(seq) - 2):
        a, b, c = cats[i], cats[i+1], cats[i+2]
        if a == "SUBJEK" and b == "PREDIKAT" and (c == "OBJEK" or c == "KETERANGAN"): score += 35
        
    return score

def normalize_segment(seg: List[str], notes: List[str]) -> List[str]:
    if not seg or seg == [PHRASE_APA_KABAR]: return seg
    original_score = grammar_score(seg)
    cands = generate_candidates(seg)
    best = max(cands, key=grammar_score)
    best_score = grammar_score(best)
    
    if best != seg and best_score > original_score: 
        notes.append(f"reordered: {seg} -> {best} (score {original_score}->{best_score})")
        return best
    return seg

# -----------------------------
# Assembly & Natural Generation
# -----------------------------
def get_greeting_variation(greeting_key: str, mode: str = "natural") -> str:
    if greeting_key in GREETING_VARIATIONS:
        variations = GREETING_VARIATIONS[greeting_key]
        if mode == "natural" and len(variations) > 1:
            return random.choice(variations)
        return variations[0]
    return humanize_label(greeting_key) + "."

def detect_emotion_context(tokens: List[str]) -> Optional[str]:
    for token in tokens:
        if token in EMOTION_CONTEXT:
            return token
    return None

def build_enhanced_question(seg: List[str], mode: str) -> Optional[str]:
    if seg == [PHRASE_APA_KABAR]: 
        return random.choice([
            "Apa kabar?", "Bagaimana kabarmu?", "Gimana kabarnya?",
            "Hai, apa kabar nih?", "Kabar baik hari ini?"
        ]) if mode == "natural" else "Apa kabar?"
    
    wh_token = next((t for t in seg if classify_token(t) == "WH"), None)
    if not wh_token: return None
    
    subjek = next((t for t in seg if classify_token(t) == "SUBJEK"), None)
    predikats = [t for t in seg if classify_token(t) == "PREDIKAT"]
    objek = next((t for t in seg if classify_token(t) == "OBJEK"), None)
    keterangan = next((t for t in seg if classify_token(t) == "KETERANGAN"), None)
    
    wh = canon(wh_token)
    
    if wh == canon("Apa"):
        if predikats:
            pred_text = " ".join([humanize_label(p).lower() for p in predikats])
            if subjek:
                subj_text = humanize_label(subjek).title()
                pred_text = " ".join([humanize_label(p).lower() for p in predikats])
                
                # User preference: "Kamu sedang belajar Apa?"
                if mode == "natural":
                    return f"{subj_text} sedang {pred_text} apa?"
                return f"{subj_text} {pred_text} apa?"
            # Gunakan capitalize() agar hanya huruf pertama yang besar
            final_q = f"{pred_text} apa?"
            return final_q.capitalize()
        elif objek:
            obj_text = humanize_label(objek).lower()
            return f"Apa {obj_text}?"
        return "Apa?"
    
    elif wh == canon("Siapa"):
        if subjek:
            return f"Siapa {humanize_label(subjek).lower()}?" if mode != "natural" else random.choice([
                f"Siapa {humanize_label(subjek).lower()}?", f"Siapa nama {humanize_label(subjek).lower()}?"
            ])
        elif predikats:
            pred_text = " ".join([humanize_label(p).lower() for p in predikats])
            return f"Siapa yang {pred_text}?"
        return "Siapa?"
    
    elif wh == canon("Bagaimana"):
        if canon("Kabar") in seg:
            return "Bagaimana kabarnya?"
        if subjek and predikats:
            pred_text = " ".join([humanize_label(p).lower() for p in predikats])
            return f"Bagaimana {humanize_label(subjek).lower()} {pred_text}?"
        return "Bagaimana?"
    
    elif wh == canon("Berapa"):
        if canon("Tinggi") in seg and subjek:
             return f"Berapa tinggi {humanize_label(subjek).lower()}?"
        return "Berapa?"
    
    # Fallback SPOK Question
    parts = []
    if subjek: parts.append(humanize_label(subjek).lower())
    if predikats:
        for p in predikats: parts.append(humanize_label(p).lower())
    if objek: parts.append(humanize_label(objek).lower())
    if keterangan: parts.append(humanize_label(keterangan).lower())
    
    if parts:
        return f"{humanize_label(wh_token)} {' '.join(parts)}?"
    return f"{humanize_label(wh_token)}?"

def build_enhanced_statement(seg: List[str], mode: str) -> Optional[str]:
    if not seg or any(classify_token(t) == "WH" for t in seg) or seg == [PHRASE_APA_KABAR]: 
        return None
    
    subjek = next((t for t in seg if classify_token(t) == "SUBJEK"), None)
    predikats = [t for t in seg if classify_token(t) == "PREDIKAT"]
    objek = next((t for t in seg if classify_token(t) == "OBJEK"), None)
    keterangan = next((t for t in seg if classify_token(t) == "KETERANGAN"), None)
    emotion = detect_emotion_context(seg)
    
    # 1. Handle Emotion Context
    if emotion and mode == "natural":
        subj_txt = humanize_label(subjek).title() if subjek else "Saya"
        emotion_info = EMOTION_CONTEXT[emotion]
        emb_lbl = humanize_label(emotion).lower()
        
        if emotion_info["intensity"] == "positive":
            return f"Wah, {subj_txt.lower()} {emb_lbl} sekali!"
        elif emotion_info["intensity"] == "negative":
            return f"{subj_txt} merasa {emb_lbl}..."
        else:
            return f"{subj_txt} sedang {emb_lbl}."

    # 2. Pola SPOK Utama
    built_words = []
    has_kata_bantu = False
    
    for i, token in enumerate(seg):
        cls = classify_token(token)
        word = humanize_label(token)
        if i > 0 and cls != "SUBJEK": word = word.lower()
        
        if mode == "natural":
            # Tambahkan kata bantu "sedang" untuk Predikat yang berupa kata kerja (VERB di nlg_kata_handler)
            if cls == "PREDIKAT" and not has_kata_bantu and token in VERB:
                # Cek apakah sebelumnya ada Subjek atau ini predikat pertama
                if i == 0 or classify_token(seg[i-1]) == "SUBJEK":
                    word = "sedang " + word
                    has_kata_bantu = True
            
            # Tambahkan kata depan "di" untuk Keterangan (Lokasi)
            if cls == "KETERANGAN":
                 word = "di " + word

        built_words.append(word)
        
    result = " ".join(built_words)
    
    # Random suffix untuk kesan natural - HANYA jika ada PREDIKAT (agar tidak "Adi dong.")
    if mode == "natural" and result and predikats and random.random() > 0.7:
        particles = ["nih", "ya", "deh"]
        result += " " + random.choice(particles)
        
    return (result[:1].upper() + result[1:] + ".") if result else None

def assemble_enhanced(prefix: List[str], segments: List[List[str]], suffix: List[str], mode: str) -> str:
    sentences: List[str] = []
    
    # Deteksi jika ada sapaan diikuti langsung oleh Nama (Subjek tanpa predikat)
    # Ini untuk menghasilkan "Halo Adi!" bukan "Halo! Adi."
    has_isolated_name = False
    name_val = ""
    if len(segments) == 1 and len(segments[0]) == 1:
        if classify_token(segments[0][0]) == "SUBJEK":
            has_isolated_name = True
            name_val = humanize_label(segments[0][0])

    maaf_present = MAAF in prefix
    has_apa_kabar = PHRASE_APA_KABAR in prefix
    
    for g in [x for x in prefix if x != MAAF]:
        if has_isolated_name and g in [canon("Halo"), canon("Hai")]:
            sentences.append(f"Halo {name_val}!")
            continue
            
        if has_apa_kabar and g == canon("Halo"):
             greeting_text = random.choice(["Halo!", "Hai!"])
        else:
             greeting_text = get_greeting_variation(g, mode)
        sentences.append(greeting_text)
    
    content: List[str] = []
    has_kabar_context = has_apa_kabar
    
    for seg in segments:
        # Jika sapaan sudah digabung dengan nama, lewati segmen nama tersebut
        if has_isolated_name and len(seg) == 1 and humanize_label(seg[0]) == name_val:
            if any(g in prefix for g in [canon("Halo"), canon("Hai")]):
                continue

        q = build_enhanced_question(seg, mode)
        if q == "Apa?" and has_kabar_context: continue
            
        if q: 
            if "kabar" in q.lower(): has_kabar_context = True
            content.append(q)
        else:
            st = build_enhanced_statement(seg, mode)
            if st: content.append(st)
    
    if maaf_present:
        if content:
            first = content[0].strip()
            punct = first[-1] if first and first[-1] in ".!?" else "."
            core = first[:-1] if first and first[-1] in ".!?" else first
            content[0] = f"Maaf, {lower_first(core)}{punct}"
        else:
            sentences.append(get_greeting_variation(MAAF, mode))
    
    sentences.extend(content)
    
    for s in suffix:
        sentences.append(get_greeting_variation(s, mode))
    
    result = " ".join([s for s in sentences if s]).strip()

    if mode == "natural":
        result = re.sub(r'\s+([.,!?])', r'\1', result)
        if result and len(result) > 0:
            result = result[0].upper() + result[1:]
    
    return result

# -----------------------------
# Main Handler Class
# -----------------------------
class KalimatNLGHandler:
    """Handler untuk mode Kalimat (Sentence)."""
    
    def __init__(self):
        self.labels = LABELS
        
    @lru_cache(maxsize=1024)
    def _cached_process_tokens(self, tokens_tuple: Tuple[str], types_tuple: Tuple[str], mode: str) -> Dict[str, Any]:
        tokens = list(tokens_tuple)
        token_types = list(types_tuple) if types_tuple else None
        return self._process_tokens_internal(tokens, token_types, mode)

    def _process_tokens_internal(self, tokens: List[str], token_types: List[str] = None, mode: str = "natural") -> Dict[str, Any]:
        notes: List[str] = []
        if token_types is None: token_types = ["SIGN"] * len(tokens)
        
        if not tokens:
            return {"natural_text": "", "normalized_tokens": [], "notes": notes, "confidence_score": 0.0}
        
        # 1. Gabungkan abjad (spelling) menjadi kata RAW/NAME
        merged = merge_spelling(tokens, token_types, notes)
        
        # 2. Gabungkan kata majemuk (S-E-L-A-M-A-T + P-A-G-I atau token "Selamat" + "Pagi")
        # Dilakukan SEBELUM canonicalize agar tidak salah ter-koreksi typo di level kata dasar
        compounded = merge_compounds(merged, notes)
        
        # 3. Validasi & Normalisasi Label (Hanya izinkan label resmi atau hasil spelling)
        canonical = canonicalize_tokens(compounded, notes)
        
        # 4. Hapus duplikasi berturutan
        deduped = dedupe_consecutive(canonical, notes)
        
        # 5. Segmentasi dan Re-ordering SPOK
        prefix, core, suffix = split_prefix_core_suffix(deduped, notes)
        segments = segment_core(core, notes)
        
        norm_segments: List[List[str]] = []
        for seg in segments:
            seg2 = dedupe_consecutive(seg, notes)
            norm_segments.append(normalize_segment(seg2, notes))

        # 6. Susun menjadi teks natural
        natural_text = assemble_enhanced(prefix, norm_segments, suffix, mode)
        normalized_flat = prefix + [t for seg in norm_segments for t in seg] + suffix
        
        # RICH FEEDBACK INJECTION
        # If we have exactly one main token and mode is natural, try to fetch rich feedback.
        if mode == "natural" and len(normalized_flat) == 1:
            single_token = normalized_flat[0]
            # Ensure it's not a RAW token
            if not single_token.startswith("RAW:"):
                # GREETING_VARIATIONS handle greetings nicely, but get_rich_feedback handles educational content.
                # Prioritize rich feedback if available.
                rich_fb = get_rich_feedback(single_token)
                if rich_fb:
                    # User request: "Tetap tampilkan katanya"
                    # Prepends "Sabar. " to "Luar biasa..."
                    natural_text = f"{humanize_label(single_token)}. {rich_fb}"
                    notes.append("strategy: rich_feedback")
                    
        # Simple confidence calc
        known_tokens = sum(1 for t in normalized_flat if not t.startswith("RAW:"))
        conf = (known_tokens / len(normalized_flat)) * 100 if normalized_flat else 0
        
        return {
            "natural_text": natural_text,
            "normalized_tokens": [humanize_label(t) for t in normalized_flat],
            "notes": notes,
            "confidence_score": round(conf, 1)
        }

    def naturalize(self, tokens: List[str], token_types: List[str] = None, mode: str = "natural") -> Dict[str, Any]:
        try:
            t_tup = tuple(tokens) if tokens else ()
            ty_tup = tuple(token_types) if token_types else ()
            return self._cached_process_tokens(t_tup, ty_tup, mode)
        except Exception as e:
            traceback.print_exc()
            return {"natural_text": " ".join(tokens), "error": str(e)}

    def analyze_sentiment(self, tokens: List[str]) -> Dict[str, Any]:
        emotions = []
        sentiment_score = 0.0
        for token in tokens:
            c = canon(token)
            if c in EMOTION_CONTEXT:
                info = EMOTION_CONTEXT[c]
                emotions.append({"emotion": humanize_label(c), "intensity": info["intensity"]})
                sentiment_score += 1.0 if info["intensity"] == "positive" else -1.0 if info["intensity"] == "negative" else 0
        
        if emotions: sentiment_score /= len(emotions)
        return {
            "emotions": emotions, 
            "sentiment_score": sentiment_score,
            "overall_sentiment": "positive" if sentiment_score > 0 else "negative" if sentiment_score < 0 else "neutral"
        }

    def get_conversation_suggestions(self, tokens: List[str]) -> List[str]:
        suggestions = []
        has_greeting = any(canon(t) in GREETING_PREFIX for t in tokens)
        has_name = any(t.startswith("NAME:") for t in tokens)
        
        if has_greeting and not has_name:
            suggestions.extend(["Nama saya...", "Apa kabar?"])
        if has_name:
            suggestions.extend(["Senang berkenalan", "Dari mana asal Anda?"])
        
        return suggestions[:3]

    def validate_structure(self, tokens: List[str]) -> Dict[str, Any]:
        """Validates if the token sequence forms a valid sentence structure."""
        if not tokens:
            return {"is_valid": False, "message": "Kalimat kosong."}
            
        # Use simple grammar check
        # Use internal helper if available or reimplement simple checks
        cats = [classify_token(t) for t in tokens]
        
        has_subject = any(c in ["PRON", "NAME"] for c in cats)
        has_predicate = any(c in ["VERB", "ADJ"] for c in cats)
        has_greeting = any(c == "GREETING" for c in cats)
        
        if has_greeting and len(tokens) == 1:
             return {"is_valid": True, "message": "Salam valid."}

        if len(tokens) >= 2:
            if not has_subject and not has_predicate:
                 return {"is_valid": False, "message": "Kalimat kurang lengkap."}
                 
        return {"is_valid": True, "message": "Struktur oke."}

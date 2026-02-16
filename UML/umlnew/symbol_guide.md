# Panduan Redesain Manual Diagram UML

Dokumen ini berisi panduan untuk menggambar ulang diagram yang ada di folder `UML/umlnew/` menggunakan simbol standar industri yang valid (seperti di Visio, Draw.io, atau Lucidchart).

---

## 1. Flowchart (`flowchart.puml`)

Gunakan simbol-simbol Flowchart standar berikut untuk setiap elemen:

| Elemen di PlantUML | Simbol Standar (Visio/Draw.io) | Keterangan |
| :--- | :--- | :--- |
| `start`, `stop` | **Terminator** (Oval/Kapsul) | Menandakan Awal (Mulai) dan Akhir (Selesai). |
| `:Teks;` | **Process** (Persegi Panjang) | Menunjukkan suatu tindakan atau langkah proses biasa. |
| `if (...) then ...` | **Decision** (Belah Ketupat) | Menunjukkan percabangan keputusan (Ya/Tidak). |
| `:Input...;` (jika ada)| **Data / I/O** (Jajaran Genjang) | Menunjukkan input data (misal: "Input Kredensial"). |

**Langkah Redesain:**
1. Ganti lingkaran hitam `start` dengan bentuk **Oval** bertuliskan "Mulai".
2. Ganti blok `if` (Diamond) dengan bentuk **Belah Ketupat**.
3. Pastikan aliran panah (Flowline) memiliki arah yang jelas.

---

## 2. Activity Diagram (`activity_diagram.puml`)

Activity diagram menggambarkan aliran kerja. Gunakan simbol UML Activity Diagram yang valid:

| Elemen di PlantUML | Simbol Standar UML 2.x | Keterangan |
| :--- | :--- | :--- |
| `start` | **Initial Node** (Lingkaran Hitam Penuh) | Titik mulai aktivitas. |
| `stop` | **Final Node** (Lingkaran dengan Bingkai/Mata Sapi) | Titik akhir aktivitas. |
| `:Aktivitas;` | **Action State** (Persegi Panjang dengan Sudut Tumpul) | Aktivitas yang dilakukan sistem/user. |
| `if (...)` | **Decision Node** (Belah Ketupat) | Titik percabangan kondisi. |
| `|User|`, `|Sistem|` | **Swimlane** (Kolom Vertikal/Horizontal) | Area pembagian tanggung jawab aktor. |
| `fork` / `split` | **Fork/Join Node** (Batang Hitam Tebal) | Memecah satu aliran menjadi bbrp aliran paralel. |

**Langkah Redesain:**
1. Buat kolom-kolom (Swimlanes) terlebih dahulu: User, Sistem, Admin.
2. Tempatkan **Initial Node** (Bulat hitam) di kolom User.
3. Gunakan bentuk "Rounded Rectangle" (Persegi sudut tumpul) untuk setiap aktivitas.

---

## 3. Sequence Diagram (`sequence_diagram.puml`)

Sequence diagram menggambarkan interaksi antar objek berdasarkan waktu.

| Elemen di PlantUML | Simbol Standar UML | Keterangan |
| :--- | :--- | :--- |
| `actor` | **Stickman** (Orang) | Aktor manusia (User/Admin). |
| `participant` | **Lifeline** (Kotak dengan garis putus-putus ke bawah) | Objek perangkat lunak (View, Controller). |
| `database` | **Database** (Silinder) | Representasi penyimpanan data. |
| `->` | **Solid Arrow** (Panah Penuh) | Pesan pemanggilan (Synchronous Message). |
| `-->` | **Dashed Arrow** (Panah Putus-putus) | Pesan balasan/kembalian (Return Message). |
| `activate/deactivate` | **Activation Bar** (Batang Persegi Panjang Putih) | Menunjukkan objek sedang aktif memproses. |
| `loop` | **Frame Loop** (Kotak besar berlabel "loop") | Menandakan perulangan proses (Frame). |
| `alt` | **Frame Alt** (Kotak besar berlabel "alt") | Menandakan alternatif kondisi (seperti If-Else). |

**Langkah Redesain:**
1. Susun aktor dan objek berjejer secara horizontal di atas.
2. Gambar garis putus-putus vertikal ke bawah dari setiap objek.
3. Gambar panah aksi dari kiri ke kanan, dan panah balasan (`-->`) dari kanan ke kiri.
4. Tambahkan kotak panjang tipis (Activation Bar) pada garis objek saat menerima panah aksi.

---

## 4. Class Diagram (`class_diagram.puml`)

Class diagram menggambarkan struktur statis kode.

| Elemen di PlantUML | Simbol Standar UML | Keterangan |
| :--- | :--- | :--- |
| `class NamaKelas` | **Class Box** (Kotak 3 Sekat) | Sekat 1: Nama, Sekat 2: Atribut, Sekat 3: Metode. |
| `+method()` | **Public Method** | Tanda `+` artinya public visibility. |
| `-attribute` | **Private Attribute** | Tanda `-` artinya private visibility. |
| `package "Nama"` | **Package** (Bentuk Folder) | Mengelompokkan kelas-kelas terkait. |
| `*--` | **Composition** (Garis dengan Diamond Hitam) | Relasi kuat "bagian dari" (misal: Kuis punya Pertanyaan). |
| `--` | **Association** (Garis Biasa) | Relasi ketergantungan umum. |
| `..>` | **Dependency** (Garis Putus-putus dengan Panah) | Satu kelas "menggunakan" kelas lain (bukan memiliki). |
| `1`, `0..*` | **Multiplicity** | Jumlah kardinalitas (1 ke Banyak). |

**Langkah Redesain:**
1. Gambar kotak kelas dengan 3 sekat untuk setiap Controller dan Model.
2. Gunakan garis penghubung yang tepat:
   - Gunakan **Diamond Hitam** (`*--`) jika objek B tidak bisa hidup tanpa objek A (misal: Pertanyaan di dalam Kuis).
   - Gunakan **Panah Putus-putus** (`..>`) jika Controller hanya "memanggil" Model atau Service.

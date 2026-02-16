# Flowchart Aplikasi BisindoCNNfi (Bahasa Indonesia)

Dokumen ini berisi kode **PlantUML** untuk alur kerja aplikasi. Sesuai permintaan Anda, saya telah menambahkan **Panduan Simbol** di bawah setiap kode program untuk menjelaskan simbol standar industri apa yang harus digunakan jika Anda menggambarnya ulang secara manual (misalnya di Visio atau draw.io).

---

## Legenda Simbol (Standar Industri Flowchart)

| Simbol | Nama | Fungsi | Representasi di PlantUML |
| :---: | :--- | :--- | :--- |
| 🔴/🟢 | **Terminator** | Awal (Start) atau Akhir (End) dari alur. | `start`, `stop` |
| 🔷 | **Decision** | Keputusan Ya/Tidak atau percabangan logika. | `if`, `elseif` |
| ▱ | **Input/Output (I/O)** | Masukan data (ketik, klik) atau Keluaran (tampilan layar). | `:Masukkan...;`, `:Tampilkan...;` |
| ▭ | **Process** | Proses internal sistem, perhitungan, atau tindakan. | `:Validasi...;`, `:Hitung...;` |
| 🛢️ | **Database** | Penyimpanan atau pengambilan data. | `:Simpan ke DB;` |

---

## 1. Alur Interaksi Pengguna (User Flow)

### Kode PlantUML
```plantuml
@startuml
skinparam backgroundColor white
title Alur Interaksi Pengguna

start
:Buka Aplikasi (Landing Page)/;
 note right: Output (Tampilan)

if (Sudah Login?) then (Belum)
  :Halaman Login/;
  note right: Output
  
  if (Punya Akun?) then (Tidak)
    :Halaman Daftar/;
    note right: Output
    :Isi Form Registrasi<;
    note right: Input
    :Submit Data];
    note right: Proses
  endif
  :Input Kredensial (Login)<;
  note right: Input
else (Ya)
endif

:Dashboard Pengguna/;
note right: Output

fork
  :Akses Kamus/>;
  note right: I/O (Navigasi)
  :Lihat Daftar Abjad/Kata/;
  :Tandai Selesai];
fork again
  :Akses Latihan/>;
  if (Pilih Mode?) then (Abjad)
    :Mode Latihan Abjad];
  elseif (Kata)
    :Mode Latihan Kata];
  else (Kalimat)
    :Mode Latihan Kalimat];
  endif
fork again
  :Akses Kuis/>;
  :Pilih Topik Kuis<;
  :Kerjakan Soal<;
  :Submit Jawaban];
  :Lihat Hasil & Skor/;
fork again
  :Lihat Riwayat Latihan/;
end fork

stop
@enduml
```

### Panduan Simbol Manual
Jika Anda menggambar ulang, gunakan bentuk berikut:

1.  **Terminator (Oval)**: Start, Stop.
2.  **I/O (Jajargenjang)**:
    *   Buka Aplikasi
    *   Halaman Login / Daftar / Dashboard
    *   Isi Form / Input Kredensial
    *   Lihat Daftar/Hasil/Riwayat
    *   Pilih Mode / Topik
3.  **Process (Persegi Panjang)**:
    *   Submit Data
    *   Tandai Selesai
    *   Reset Password (jika ada)
4.  **Decision (Belah Ketupat)**:
    *   Sudah Login?
    *   Punya Akun?
    *   Pilih Mode?

---

## 2. Alur Admin (Admin Flow)

### Kode PlantUML
```plantuml
@startuml
skinparam backgroundColor white
title Alur Admin

start
:Input Login Admin<;
:Validasi Login];
:Dashboard Admin/;

split
  :Manajemen User];
  :CRUD User (Tambah/Edit/Hapus)] |
split again
  :Manajemen Konten];
  :CRUD Abjad/Kata/Kuis] |
split again
  :Analitik];
  :Tampil Statistik/;
split again
  :Manajemen Model AI];
  :Update Parameter Model];
split again
  :Pengaturan Aplikasi];
end split

stop
@enduml
```

### Panduan Simbol Manual
1.  **I/O (Jajargenjang)**: Input Login Admin, Dashboard Admin, Tampil Statistik.
2.  **Process (Persegi Panjang)**: Validasi Login, CRUD User/Konten, Update Parameter.

---

## 3. Detail Alur Mode Latihan

### Kode PlantUML
```plantuml
@startuml
skinparam backgroundColor white
title Detail Alur Mode Latihan

start
:Klik Mulai Latihan<;

if (Izin Kamera Diberikan?) then (Tidak)
  :Minta Akses Kamera/output;
  if (Diizinkan?) then (Tidak)
    :Tampilkan Pesan Error/;
    stop
  endif
endif

:Inisialisasi AI (MediaPipe)] process;
:Tampil Pilihan Mode/;

if (Mode yang Dipilih?) then (Mode Abjad)
  partition "Mode Abjad" {
    :Input Kata Target<;
    repeat
      :Tampilkan Huruf Target/;
      :Deteksi Tangan User] process;
    repeat while (Gerakan Sesuai?) is (Tidak)
    :Lanjut ke Huruf Berikutnya];
    if (Kata Selesai?) then (Ya)
    else (Tidak)
    endif
  }
elseif (Mode Kata)
  partition "Mode Kata" {
    :Pilih Kartu Kata<;
    :Tampilkan Panduan Video/;
    repeat
      :Deteksi Gerakan Tubuh] process;
    repeat while (Gerakan Sesuai?) is (Tidak)
  }
else (Mode Kalimat)
  partition "Mode Kalimat" {
    :Pilih Kata & Input Ejaan<;
    :Proses Susun Kalimat];
    repeat
      :Tampilkan Item Target/;
      :Deteksi Gerakan] process;
    repeat while (Gerakan Sesuai?) is (Tidak)
    :Lanjut Item Berikutnya];
  }
endif

:Sesi Selesai];
:Hitung Akurasi & Durasi] process;
:Simpan ke Database] database;
:Tampilkan Layar Hasil/;

if (Aksi Selanjutnya?) then (Menu Utama)
  :Navigasi ke Dashboard];
else (Coba Lagi)
  :Reset Sesi];
endif

stop
@enduml
```

### Panduan Simbol Manual
1.  **Terminator (Oval)**: Start, Stop.
2.  **Decision (Belah Ketupat)**:
    *   Izin Kamera Diberikan?
    *   Diizinkan?
    *   Mode yang Dipilih?
    *   Gerakan Sesuai?
    *   Kata Selesai?
    *   Aksi Selanjutnya?
3.  **Process (Persegi Panjang)**:
    *   Inisialisasi AI
    *   Deteksi Tangan/Gerakan
    *   Lanjut ke Huruf/Item Berikutnya
    *   Hitung Akurasi & Durasi
    *   Reset Sesi
4.  **I/O (Jajargenjang)**:
    *   Klik Mulai / Input Kata / Pilih Kartu
    *   Minta Akses Kamera
    *   Tampilkan Pesan Error / Huruf Target / Panduan / Hasil
5.  **Database (Tabung)**:
    *   Simpan ke Database

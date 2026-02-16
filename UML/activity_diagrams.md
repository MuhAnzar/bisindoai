# Activity Diagram Sistem BisindoCNN

Karena Anda sudah menginstal ekstensi **PlantUML**, berikut adalah kode final yang paling optimal. Kode ini menggunakan sintaks swimlane standar (`|Nama Kolom|`) yang akan menghasilkan kolom vertikal terpisah dengan sangat rapi, persis seperti standar skripsi.

> **Cara Render:** Buka file ini di VS Code, lalu tekan `Alt + D` untuk melihat diagramnya.

---

## Legenda Simbol (Activity Diagram)

| Simbol | Nama | Fungsi | Representasi di PlantUML |
| :---: | :--- | :--- | :--- |
| 🔴/🟢 | **Initial/Final Node** | Titik mulai (Start) dan akhir (End). | `start`, `stop`, `detach` |
| ▭ | **Action/Activity** | Suatu langkah atau tindakan yang dilakukan. | `:Nama Aktivitas;` |
| 🔷 | **Decision** | Percabangan keputusan (Ya/Tidak). | `if`, `elseif`, `else` |
| ⎯ | **Control Flow** | Arah aliran aktivitas. | `->` (Panah) |
| ▌ | **Fork/Join** | Memecah atau menyatukan aliran paralel. | `fork`, `fork again`, `split` |
| 🏊 | **Swimlane** | Mengelompokkan aktivitas berdasarkan aktor/sistem. | `|Nama Aktor|` |

---


## 1. Activity Diagram: Registrasi User

```plantuml
@startuml
skinparam swimlaneBorderColor black
skinparam activityBorderColor black
skinparam activityArrowColor black

|User|
start
:Buka Halaman Registrasi;
:Isi Form Registrasi;
:Klik Tombol Daftar;

|Sistem|
if (Validasi Data?) then (Tidak Valid)
  :Tampilkan Pesan Error;
  |User|
  :Perbaiki Data;
  detach
else (Valid)
  |Sistem|
  :Simpan Data User;
  :Redirect ke Halaman Login;
endif

stop
@enduml
```

## 2. Activity Diagram: Login User

```plantuml
@startuml
skinparam swimlaneBorderColor black

|User|
start
:Buka Halaman Login;
:Input Email & Password;
:Klik Tombol Masuk;

|Sistem|
:Proses Autentikasi;

if (Login Berhasil?) then (Gagal)
  :Tampilkan Pesan Error;
  |User|
  :Input Ulang;
  detach
else (Berhasil)
  |Sistem|
  if (Cek Role User?) then (Admin)
    :Redirect ke\nDashboard Admin;
  else (User)
    :Redirect ke\nDashboard User;
  endif
endif

stop
@enduml
```

## 3. Activity Diagram: Fitur Kamus

```plantuml
@startuml
skinparam swimlaneBorderColor black

|User|
start
:Pilih Menu Kamus;
:Pilih Kategori\n(Abjad / Kata Dasar);
:Pilih Item Isyarat;

|Sistem|
:Ambil Data Video;
:Tampilkan Video & Penjelasan;

|User|
if (Selesai Belajar?) then (Ya)
  :Klik Tombol\nTandai Selesai;
  |Sistem|
  :Update Progress User;
  :Tampilkan Notifikasi Sukses;
else (Tidak)
  |User|
  :Kembali ke Menu;
endif

stop
@enduml
```

## 4. Activity Diagram: Latihan Deteksi (Real-time)

```plantuml
@startuml
skinparam swimlaneBorderColor black

|User|
start
:Pilih Menu Latihan;
:Pilih Mode Latihan;

if (Pilihan Mode?) then (Abjad)
  :Mode Latihan Abjad;
  :Input Kata Target;
  :Sistem Menampilkan Huruf Target;
  
  repeat
    :Lakukan Isyarat Tangan;
    |Sistem|
    :Deteksi Hand Landmark;
    :Validasi Huruf;
  repeat while (Huruf Sesuai?) is (Tidak)
  
  :Lanjut Huruf Berikutnya;

elseif (Kata)
  |User|
  :Mode Latihan Kata;
  :Pilih Kartu Kata;
  :Lihat Video Panduan;
  
  repeat
    :Lakukan Isyarat Kata;
    |Sistem|
    :Deteksi Pose & Tangan;
    :Validasi Kata (Top 5);
  repeat while (Kata Sesuai?) is (Tidak)

else (Kalimat)
  |User|
  :Mode Latihan Kalimat;
  :Pilih Kata & Input Ejaan;
  :Susun Kalimat Target;
  
  repeat
    :Lakukan Isyarat Item Aktif;
    |Sistem|
    :Deteksi Isyarat;
    :Validasi Item Urutan;
  repeat while (Item Sesuai?) is (Tidak)
  
  :Lanjut Item Berikutnya;
endif

|Sistem|
:Hitung Akurasi & Waktu;
:Simpan Riwayat Latihan;
:Tampilkan Hasil & Evaluasi;

stop
@enduml
```

## 5. Activity Diagram: Pengerjaan Kuis

```plantuml
@startuml
skinparam swimlaneBorderColor black

|User|
start
:Pilih Menu Kuis;
:Pilih Paket Soal;
:Klik Mulai;

|Sistem|
:Generate Soal Random;

repeat
  |Sistem|
  :Tampilkan Soal Video;
  
  |User|
  :Pilih Jawaban;
repeat while (Masih Ada Soal?) is (Ya)
->Tidak;

|User|
:Submit Jawaban;

|Sistem|
:Hitung Skor Akhir;
:Simpan Hasil ke Database;
:Tampilkan Halaman Hasil;

stop
@enduml
```

## 6. Activity Diagram: Manajemen User (Admin)

```plantuml
@startuml
skinparam swimlaneBorderColor black

|Admin|
start
:Login sebagai Admin;
:Masuk Menu User;

if (Pilih Aksi?) then (Tambah)
  :Input Data User Baru;
  |Sistem|
  :Simpan Data User;
elseif (Edit)
  |Admin|
  :Edit Data User;
  |Sistem|
  :Update Data User;
elseif (Hapus)
  |Admin|
  :Konfirmasi Hapus;
  |Sistem|
  :Hapus Data User;
endif

|Sistem|
:Refresh Tabel User;

stop
@enduml
```

## 7. Activity Diagram: Manajemen Konten (Admin)

```plantuml
@startuml
skinparam swimlaneBorderColor black

|Admin|
start
:Login sebagai Admin;
:Masuk Menu Konten;

if (Pilih Aksi?) then (Upload)
  :Upload Video & Data;
  |Sistem|
  :Simpan File Cloud;
  :Simpan Record DB;
elseif (Edit)
  |Admin|
  :Edit Informasi;
  |Sistem|
  :Update Record DB;
elseif (Hapus)
  |Admin|
  :Hapus Konten;
  |Sistem|
  :Hapus File & Data;
endif

|Sistem|
:Tampilkan Notifikasi;

stop
@enduml
```

## 8. Activity Diagram: Upload Model AI (Admin)

```plantuml
@startuml
skinparam swimlaneBorderColor black

|Admin|
start
:Masuk Menu Model AI;
:Upload File Model (.h5);
:Upload File Label (.json);
:Klik Save Configuration;

|Sistem|
if (Validasi File?) then (Valid)
  :Backup Model Lama;
  :Simpan Model Baru;
  :Reload API Flask;
  :Tampilkan Pesan Sukses;
else (Invalid)
  :Tampilkan Pesan Error;
  detach
endif

stop
@enduml
```

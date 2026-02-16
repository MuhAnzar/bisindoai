# Referensi Simbol Per Aktivitas

Dokumen ini adalah **Kunci Jawaban Simbol** untuk meredesain diagram aktivitas.
Setiap nomor yang tertera pada diagram `activity_diagrams.puml` memiliki arti simbol sebagai berikut.

---

## 📌 Panduan Bentuk Simbol Visual

| Nama Simbol | Bentuk Visual (Draw.io / Visio) | Fungsi |
| :--- | :--- | :--- |
| **Initial Node** | ● (Lingkaran Hitam Kecil) | Titik Awal |
| **Action State** | ▭ (Persegi Panjang Sudut Tumpul) | Proses / Aktivitas |
| **Decision Node** | ◇ (Belah Ketupat) | Percabangan Keputusan |
| **Fork/Join** | ▬ (Garis Tebal Hitam) | Memecah/Menggabung Alur |
| **Final Node** | ◉ (Lingkaran Mata Sapi) | Titik Selesai |

---

## 1. Registrasi User (`activity_registrasi_user`)

| No. Langkah | Teks Aktivitas | Simbol yang Digunakan | Swimlane |
| :--- | :--- | :--- | :--- |
| **Start** | - | **Initial Node** | User |
| **1** | Buka Halaman Registrasi | **Action State** | User |
| **2** | Isi Form Registrasi | **Action State** | User |
| **3** | Klik Tombol Daftar | **Action State** | User |
| **4** | Validasi Data? | **Decision Node** | Sistem |
| **5** | Tampilkan Pesan Error | **Action State** | Sistem |
| **6** | Perbaiki Data | **Action State** | User |
| **7** | Simpan Data User | **Action State** | Sistem |
| **8** | Redirect ke Halaman Login | **Action State** | Sistem |
| **Stop** | - | **Final Node** | Sistem |

---

## 2. Login User (`activity_login_user`)

| No. Langkah | Teks Aktivitas | Simbol yang Digunakan | Swimlane |
| :--- | :--- | :--- | :--- |
| **Start** | - | **Initial Node** | User |
| **1** | Buka Halaman Login | **Action State** | User |
| **2** | Input Email & Password | **Action State** | User |
| **3** | Klik Tombol Masuk | **Action State** | User |
| **4** | Proses Autentikasi | **Action State** | Sistem |
| **5** | Login Berhasil? | **Decision Node** | Sistem |
| **6** | Tampilkan Pesan Error | **Action State** | Sistem |
| **7** | Input Ulang | **Action State** | User |
| **8** | Cek Role User? | **Decision Node** | Sistem |
| **9A** | Redirect ke Dashboard Admin | **Action State** | Sistem |
| **9B** | Redirect ke Dashboard User | **Action State** | Sistem |
| **Stop** | - | **Final Node** | Sistem |

---

## 3. Fitur Kamus (`activity_kamus`)

| No. Langkah | Teks Aktivitas | Simbol yang Digunakan | Swimlane |
| :--- | :--- | :--- | :--- |
| **Start** | - | **Initial Node** | User |
| **1** | Pilih Menu Kamus | **Action State** | User |
| **2** | Pilih Kategori | **Action State** | User |
| **3** | Pilih Item Isyarat | **Action State** | User |
| **4** | Ambil Data Video | **Action State** | Sistem |
| **5** | Tampilkan Video & Penjelasan | **Action State** | Sistem |
| **6** | Selesai Belajar? | **Decision Node** | User |
| **7** | Klik Tombol Tandai Selesai | **Action State** | User |
| **8** | Update Progress User | **Action State** | Sistem |
| **9** | Tampilkan Notifikasi Sukses | **Action State** | Sistem |
| **10** | Kembali ke Menu | **Action State** | User |
| **Stop** | - | **Final Node** | User/Sistem |

---

## 4. Fitur Latihan (`activity_latihan`)

| No. Langkah | Teks Aktivitas | Simbol yang Digunakan | Swimlane |
| :--- | :--- | :--- | :--- |
| **Start** | - | **Initial Node** | User |
| **1** | Pilih Menu Latihan | **Action State** | User |
| **2** | Pilih Mode Latihan | **Action State** | User |
| **-** | (Pecahan Alur 3 Cabang) | **Fork Node (Garis Hitam)** | User |
| **Mode Abjad** | | | |
| **3A** | Mode Abjad | **Action State** | User |
| **4A** | Input Kata Target | **Action State** | User |
| **5A** | Tampilkan Huruf Target | **Action State** | Sistem |
| **6A** | Lakukan Isyarat Tangan | **Action State** | User |
| **7A** | Deteksi Hand Landmark | **Action State** | Sistem |
| **8A** | Validasi Huruf | **Action State** | Sistem |
| **9A** | Huruf Sesuai? | **Decision Node** | Sistem |
| **10A** | Lanjut Huruf Berikutnya | **Action State** | Sistem |
| **11A** | Hitung Akurasi & Waktu | **Action State** | Sistem |
| **12A** | Simpan Riwayat Latihan | **Action State** | Sistem |
| **13A** | Tampilkan Hasil & Evaluasi | **Action State** | Sistem |
| **Mode Kata** | | | |
| **3B** | Mode Kata | **Action State** | User |
| **4B** | Pilih Kartu Kata | **Action State** | User |
| **5B** | Lihat Video Panduan | **Action State** | User |
| **6B** | Lakukan Isyarat Kata | **Action State** | User |
| **7B** | Deteksi Pose & Tangan | **Action State** | Sistem |
| **8B** | Validasi Kata | **Action State** | Sistem |
| **9B** | Kata Sesuai? | **Decision Node** | Sistem |
| **10B** | Hitung Akurasi & Waktu | **Action State** | Sistem |
| **11B** | Simpan Riwayat | **Action State** | Sistem |
| **12B** | Tampilkan Hasil | **Action State** | Sistem |
| **Mode Kalimat** | | | |
| **3C** | Mode Kalimat | **Action State** | User |
| **4C** | Mode Latihan Kalimat | **Action State** | User |
| **5C** | Pilih Kata & Input Ejaan | **Action State** | User |
| **6C** | Susun Kalimat Target | **Action State** | User |
| **7C** | Lakukan Isyarat Item | **Action State** | User |
| **8C** | Deteksi Isyarat | **Action State** | Sistem |
| **9C** | Validasi Item | **Action State** | Sistem |
| **10C** | Item Sesuai? | **Decision Node** | Sistem |
| **11C** | Lanjut Item Berikutnya | **Action State** | Sistem |
| **12C** | Hitung Akurasi | **Action State** | Sistem |
| **13C** | Simpan Riwayat | **Action State** | Sistem |
| **14C** | Tampilkan Hasil | **Action State** | Sistem |

---

## 5. Pengerjaan Kuis (`activity_kuis`)

| No. Langkah | Teks Aktivitas | Simbol yang Digunakan | Swimlane |
| :--- | :--- | :--- | :--- |
| **Start** | - | **Initial Node** | User |
| **1** | Pilih Menu Kuis | **Action State** | User |
| **2** | Pilih Paket Soal | **Action State** | User |
| **3** | Klik Mulai | **Action State** | User |
| **4** | Generate Soal Random | **Action State** | Sistem |
| **5** | Tampilkan Soal Video | **Action State** | Sistem |
| **6** | Pilih Jawaban | **Action State** | User |
| **7** | Masih Ada Soal? | **Decision Node** | Sistem |
| **8** | Submit Jawaban | **Action State** | User |
| **9** | Hitung Skor Akhir | **Action State** | Sistem |
| **10** | Simpan Hasil ke Database | **Action State** | Sistem |
| **11** | Tampilkan Halaman Hasil | **Action State** | Sistem |
| **Stop** | - | **Final Node** | Sistem |

---

## 6. Manajemen User (`activity_manajemen_user`)

| No. Langkah | Teks Aktivitas | Simbol yang Digunakan | Swimlane |
| :--- | :--- | :--- | :--- |
| **Start** | - | **Initial Node** | Admin |
| **1** | Login sebagai Admin | **Action State** | Admin |
| **2** | Masuk Menu User | **Action State** | Admin |
| **3** | Pilih Aksi? | **Decision Node** | Admin |
| **4A** | Input Data User Baru | **Action State** | Admin |
| **5A** | Simpan Data User | **Action State** | Sistem |
| **4B** | Edit Data User | **Action State** | Admin |
| **5B** | Update Data User | **Action State** | Sistem |
| **4C** | Konfirmasi Hapus | **Action State** | Admin |
| **5C** | Hapus Data User | **Action State** | Sistem |
| **6** | Refresh Tabel User | **Action State** | Sistem |
| **Stop** | - | **Final Node** | Sistem |

---

## 7. Manajemen Konten (`activity_manajemen_konten`)

| No. Langkah | Teks Aktivitas | Simbol yang Digunakan | Swimlane |
| :--- | :--- | :--- | :--- |
| **Start** | - | **Initial Node** | Admin |
| **1** | Login sebagai Admin | **Action State** | Admin |
| **2** | Masuk Menu Konten | **Action State** | Admin |
| **3** | Pilih Aksi? | **Decision Node** | Admin |
| **4A** | Upload Video & Data | **Action State** | Admin |
| **5A** | Simpan File Cloud | **Action State** | Sistem |
| **6A** | Simpan Record DB | **Action State** | Sistem |
| **4B** | Edit Informasi | **Action State** | Admin |
| **5B** | Update Record DB | **Action State** | Sistem |
| **4C** | Hapus Konten | **Action State** | Admin |
| **5C** | Hapus File & Data | **Action State** | Sistem |
| **7** | Tampilkan Notifikasi | **Action State** | Sistem |
| **Stop** | - | **Final Node** | Sistem |

---

## 8. Upload Model AI (`activity_upload_model`)

| No. Langkah | Teks Aktivitas | Simbol yang Digunakan | Swimlane |
| :--- | :--- | :--- | :--- |
| **Start** | - | **Initial Node** | Admin |
| **1** | Masuk Menu Model AI | **Action State** | Admin |
| **2** | Upload File Model | **Action State** | Admin |
| **3** | Upload File Label | **Action State** | Admin |
| **4** | Klik Save Configuration | **Action State** | Admin |
| **5** | Validasi File? | **Decision Node** | Sistem |
| **6** | Backup Model Lama | **Action State** | Sistem |
| **7** | Simpan Model Baru | **Action State** | Sistem |
| **8** | Reload API Flask | **Action State** | Sistem |
| **9** | Tampilkan Pesan Sukses | **Action State** | Sistem |
| **6B** | Tampilkan Pesan Error | **Action State** | Sistem |
| **Stop** | - | **Final Node** | Sistem |

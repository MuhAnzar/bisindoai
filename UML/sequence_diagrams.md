# Sequence Diagram Sistem BisindoCNN

Berikut adalah **Sequence Diagram** yang telah disederhanakan (Linear Flow) dan dibuat **Hitam Putih** (Monochrome).
- Kotak `alt` (pilihan kondisi) telah dihilangkan agar tampilan lebih bersih sesuai keinginan Anda.
- Diagram hanya menampilkan alur **Berhasil** (Happy Path).

> **Cara Render:** Buka file ini di VS Code, lalu tekan `Alt + D`.

---

## Legenda Simbol (Sequence Diagram)

| Simbol | Nama | Fungsi | Representasi di PlantUML |
| :---: | :--- | :--- | :--- |
| 👤 | **Actor** | Pengguna atau sistem eksternal yang berinteraksi. | `actor Nama` |
| ⬜ | **Lifeline/Participant** | Objek atau sistem yang hidup selama interaksi. | `participant "Nama"` |
| ➝ | **Synchronous Message** | Pesan kirim yang menunggu balasan (blokir). | `->` (Panah solid) |
| ⇢ | **Return Message** | Balasan dari pesan sebelumnya. | `-->` (Panah putus-putus) |
| ▭ | **Activation Bar** | Menandakan periode objek sedang aktif/memproses. | `activate`, `deactivate` |
| 🔄 | **Loop** | Pengulangan proses. | `loop ... end` |
| 🔷 | **Alt/Opt** | Percabangan kondisi (Alternatif/Opsional). | `alt ... else ... end` |

---


## 1. Sequence Diagram: Registrasi

```plantuml
@startuml
skinparam monochrome true
skinparam shadowing false
skinparam backgroundColor white

actor User
participant "Halaman Registrasi" as View
participant "AutentikasiController" as Controller
participant "Model User" as Model
participant "Database" as DB

User -> View : Buka Halaman Daftar()
activate View
View --> User : Tampil Form Registrasi()
deactivate View

User -> View : Input Data & Klik Daftar()
activate View
View -> Controller : prosesRegistrasi(request)
activate Controller

Controller -> Controller : validasiData()
activate Controller
deactivate Controller

Controller -> Model : create(data)
activate Model
Model -> DB : INSERT INTO users
activate DB
DB --> Model : Success
deactivate DB
Model --> Controller : Data User Created
deactivate Model

Controller --> View : Redirect ke Login()
View --> User : Tampil Halaman Login()

deactivate Controller
deactivate View
@enduml
```

## 2. Sequence Diagram: Login

```plantuml
@startuml
skinparam monochrome true
skinparam shadowing false
skinparam backgroundColor white

actor User
participant "Halaman Login" as View
participant "AutentikasiController" as Controller
participant "Sistem Auth" as Auth
participant "Database" as DB

User -> View : Masukkan Email & Password()
activate View
View -> Controller : prosesLogin(request)
activate Controller

Controller -> Auth : attempt(credentials)
activate Auth
Auth -> DB : SELECT * FROM users
activate DB
DB --> Auth : User Data
deactivate DB
Auth --> Controller : Auth Result
deactivate Auth

Controller -> Auth : user()
activate Auth
Auth --> Controller : User Object
deactivate Auth

Controller --> View : Redirect Dashboard()
View --> User : Tampil Dashboard()

deactivate Controller
deactivate View
@enduml
```

## 3. Sequence Diagram: Fitur Kamus

```plantuml
@startuml
skinparam monochrome true
skinparam shadowing false
skinparam backgroundColor white

actor User
participant "Halaman Kamus" as View
participant "KamusController" as Controller
participant "Model Kamus" as Model
participant "Database" as DB

User -> View : Pilih Kategori/Huruf()
activate View
View -> Controller : show(id)
activate Controller

Controller -> Model : getDataKamus(id)
activate Model
Model -> DB : SELECT * FROM kamus
activate DB
DB --> Model : Result Data
deactivate DB
Model --> Controller : Data Kamus
deactivate Model

Controller --> View : Tampil Video & Penjelasan()
deactivate Controller

User -> View : Klik "Tandai Selesai"()
activate View
View -> Controller : markAsDone(id)
activate Controller
Controller -> Model : updateProgress(user_id)
activate Model
Model -> DB : INSERT INTO progress
activate DB
DB --> Model : Success
deactivate DB
Model --> Controller : Success
deactivate Model
Controller --> View : Tampil Notifikasi Sukses()
deactivate Controller

deactivate View
@enduml
```

## 4. Sequence Diagram: Latihan Deteksi (Real-time)

```plantuml
@startuml
skinparam monochrome true
skinparam shadowing false
skinparam backgroundColor white

actor User
participant "Tampilan Latihan" as View
participant "Game Engine (JS)" as Logic
participant "Flask API" as API
participant "LatihanController" as Laravel

User -> View : Pilih Mode (Abjad/Kata/Kalimat)
activate View
View -> Logic : InitGame(mode)
activate Logic

Logic -> Logic : Load Resources()
Logic --> View : Kamera Ready

loop Game Loop
    User -> View : Lakukan Gerakan
    View -> Logic : Capture Frame
    
    Logic -> API : POST /predict (Image)
    activate API
    API --> Logic : JSON {Label, Confidence}
    deactivate API
    
    Logic -> Logic : CheckMatch(Prediction vs Target)
    
    alt Match Found
        Logic --> View : Visual Feedback (Benar!)
        Logic -> Logic : NextItem()
    else No Match
        Logic --> View : Visual Feedback (Coba Lagi)
    end
end

Logic -> Laravel : Simpan Sesi (POST /save-session)
activate Laravel
Laravel --> Logic : Success
deactivate Laravel

Logic --> View : Tampilkan Statistik Akhir
View --> User : Summary Latihan

deactivate Logic
deactivate View
@enduml
```

## 5. Sequence Diagram: Pengerjaan Kuis

```plantuml
@startuml
skinparam monochrome true
skinparam shadowing false
skinparam backgroundColor white

actor User
participant "Halaman Kuis" as View
participant "KuisController" as Controller
participant "Model Soal" as Model
participant "Database" as DB

User -> View : Mulai Kerjakan Kuis()
activate View
View -> Controller : ambilSoal(kuis_id)
activate Controller
Controller -> Model : getRandomSoal()
activate Model
Model -> DB : SELECT * FROM soal
activate DB
DB --> Model : List Soal
deactivate DB
Model --> Controller : List Soal
deactivate Model
Controller --> View : Tampil Paket Soal()
deactivate Controller

loop Pengerjaan
    User -> View : Jawab Pertanyaan()
end

User -> View : Submit Jawaban()
View -> Controller : hitungNilai(jawaban)
activate Controller
Controller -> Controller : kalkulasiSkor()
Controller -> DB : Simpan Hasil()
activate DB
DB --> Controller : Success
deactivate DB
Controller --> View : Tampil Skor Akhir()
deactivate Controller
View --> User : Tampil Halaman Hasil()

deactivate View
@enduml
```

## 6. Sequence Diagram: Manajemen User (Admin)

```plantuml
@startuml
skinparam monochrome true
skinparam shadowing false
skinparam backgroundColor white

actor Admin
participant "Halaman User" as View
participant "UserAdminController" as Controller
participant "Model User" as Model
participant "Database" as DB

Admin -> View : Tambah User Baru()
activate View
View -> Controller : store(request)
activate Controller

Controller -> Controller : validasiInput()
activate Controller
deactivate Controller

Controller -> Model : create(data)
activate Model
Model -> DB : INSERT INTO users
activate DB
DB --> Model : Success
deactivate DB
Model --> Controller : User Created
deactivate Model

Controller --> View : Redirect kembali()
deactivate Controller
View --> Admin : Tampil Pesan "User Berhasil Ditambah"()
deactivate View
@enduml
```

## 7. Sequence Diagram: Upload Model AI (Admin)

```plantuml
@startuml
skinparam monochrome true
skinparam shadowing false
skinparam backgroundColor white

actor Admin
participant "Halaman Upload" as View
participant "ModelController" as Controller
participant "File System" as Storage
participant "Flask Service" as API

Admin -> View : Upload File (.h5, .json)()
activate View
View -> Controller : updateModel(request)
activate Controller

Controller -> Storage : Simpan File Baru()
activate Storage
Storage --> Controller : Path File
deactivate Storage

Controller -> API : Restart Service()
activate API
API --> Controller : Service Ready
deactivate API

Controller --> View : Tampil Pesan Sukses()

deactivate Controller
deactivate View
@enduml
```

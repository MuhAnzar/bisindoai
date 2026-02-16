# Diagram UML Aplikasi BisindoCNNfi (Mermaid Format)

Dokumen ini berisi kumpulan diagram UML untuk aplikasi BisindoCNNfi yang telah dirapikan untuk keterbacaan maksimal. Format yang digunakan adalah **MermaidJS**.

## 📋 Daftar Isi
1. [Flowchart](#bagian-1-flowchart)
    - [1.1 Keseluruhan Aplikasi](#11-flowchart-keseluruhan-aplikasi)
    - [1.2 Alur User](#12-flowchart-alur-user)
    - [1.3 Alur Admin](#13-flowchart-alur-admin)
    - [1.4 Detail Fitur Latihan](#14-flowchart-detail-fitur-latihan)
2. [Activity Diagram](#bagian-2-activity-diagram)
    - [2.1 Registrasi User](#21-activity-diagram-registrasi-user)
    - [2.2 Login User](#22-activity-diagram-login-user)
    - [2.3 Fitur Kamus](#23-activity-diagram-fitur-kamus)
    - [2.4 Latihan Deteksi](#24-activity-diagram-latihan-deteksi-real-time)
    - [2.5 Pengerjaan Kuis](#25-activity-diagram-pengerjaan-kuis)
    - [2.6 Manajemen User](#26-activity-diagram-manajemen-user-admin)
    - [2.7 Manajemen Konten](#27-activity-diagram-manajemen-konten-admin)
    - [2.8 Upload Model AI](#28-activity-diagram-upload-model-ai-admin)
3. [Sequence Diagram](#bagian-3-sequence-diagram)

---

# BAGIAN 1: FLOWCHART

## 1.1 Flowchart Keseluruhan Aplikasi

Menggambarkan arsitektur navigasi utama aplikasi dari hulu ke hilir.

```mermaid
flowchart TD
    %% Nodes
    START([Start])
    END([End])
    
    subgraph S_LANDING [" 🏠 LANDING PAGE "]
        direction TB
        BIP[Buka Aplikasi]
        CHK_LOGIN{Sudah Login?}
    end

    subgraph S_AUTH [" 🔐 AUTENTIKASI "]
        direction TB
        LOGIN[Halaman Login]
        CHK_ACC{Punya Akun?}
        REG[Halaman Registrasi]
        FORM_REG[/Isi Form Registrasi/]
        VAL_REG[Validasi & Simpan]
        INPUT_CRED[/Input Email & Password/]
        PROC_AUTH[Proses Autentikasi]
    end

    subgraph S_ROUTING [" 🔀 ROUTING "]
        CHK_ROLE{Cek Role?}
    end

    subgraph S_ADMIN [" 👨‍💼 FITUR ADMIN "]
        DASH_ADM[Dashboard Admin]
        MENU_ADM{Menu Admin}
        FEAT_USR[Manajemen User]
        FEAT_CONT[Manajemen Konten]
        FEAT_KUIS[Manajemen Kuis]
        FEAT_AI[Manajemen Model AI]
        FEAT_ANL[Analitik]
    end

    subgraph S_USER [" 👤 FITUR USER "]
        DASH_USR[Dashboard User]
        MENU_USR{Menu User}
        MOD_KAM[Modul Kamus]
        MOD_LAT[Modul Latihan]
        MOD_KUI[Modul Kuis]
        MOD_CS[Lihat Riwayat]
    end

    LOGOUT[Proses Logout]

    %% Connections
    START --> BIP
    BIP --> CHK_LOGIN
    
    %% Auth Flow
    CHK_LOGIN -->|Belum| LOGIN
    LOGIN --> CHK_ACC
    CHK_ACC -->|Tidak| REG --> FORM_REG --> VAL_REG --> LOGIN
    CHK_ACC -->|Ya| INPUT_CRED --> PROC_AUTH
    
    %% Routing Flow
    PROC_AUTH --> CHK_ROLE
    CHK_LOGIN -->|Sudah| CHK_ROLE
    
    CHK_ROLE -->|Admin| DASH_ADM
    CHK_ROLE -->|User| DASH_USR
    
    %% Admin Flow
    DASH_ADM --> MENU_ADM
    MENU_ADM -->|User| FEAT_USR
    MENU_ADM -->|Konten| FEAT_CONT
    MENU_ADM -->|Kuis| FEAT_KUIS
    MENU_ADM -->|Model| FEAT_AI
    MENU_ADM -->|Analitik| FEAT_ANL
    
    %% User Flow
    DASH_USR --> MENU_USR
    MENU_USR -->|Kamus| MOD_KAM
    MENU_USR -->|Latihan| MOD_LAT
    MENU_USR -->|Kuis| MOD_KUI
    MENU_USR -->|Riwayat| MOD_CS
    
    %% Logout
    MENU_ADM -->|Keluar| LOGOUT
    MENU_USR -->|Keluar| LOGOUT
    LOGOUT --> END

    %% Styles
    classDef mainNode fill:#f9f,stroke:#333,stroke-width:2px;
    classDef decision fill:#ffd700,stroke:#333,stroke-width:2px;
    classDef terminal fill:#2ecc71,stroke:#27ae60,stroke-width:2px,color:white;
    classDef process fill:#3498db,stroke:#2980b9,stroke-width:2px,color:white;
    
    class START,END terminal;
    class CHK_LOGIN,CHK_ACC,CHK_ROLE,MENU_ADM,MENU_USR decision;
    class BIP,LOGIN,REG,DASH_ADM,DASH_USR process;
```

---

## 1.2 Flowchart Alur User

Detail perjalanan pengguna dalam menggunakan fitur-fitur utama.

```mermaid
flowchart TD
    %% Nodes
    START([Mulai])
    LOGIN[Login ke Aplikasi]
    DASH[Dashboard Pengguna]
    LOGOUT{Logout?}
    FINISH([Selesai])

    subgraph FEATURE_KAMUS [" 📚 KAMUS "]
        direction TB
        K_MENU{Pilih Kategori}
        K_LIST[Lihat Daftar]
        K_DETAIL[Lihat Detail & Video]
        K_MARK[Tandai Selesai]
    end

    subgraph FEATURE_LATIHAN [" 🎯 LATIHAN "]
        direction TB
        L_MENU{Pilih Mode}
        L_PREP[Persiapan & Panduan]
        L_RT{{Deteksi Real-time}}
        L_RES[Hasil Latihan]
    end

    subgraph FEATURE_KUIS [" 📝 KUIS "]
        direction TB
        Q_MENU[Pilih Paket Kuis]
        Q_CHECK{Limit < 3?}
        Q_RUN[Kerjakan Soal]
        Q_SUBMIT[Submit Jawaban]
        Q_RES[Lihat Skor]
        Q_FAIL[Tolak Akses]
    end

    %% Main Flow
    START --> LOGIN --> DASH
    DASH --> CHOOSE{Pilih Fitur}
    
    CHOOSE -->|Kamus| K_MENU
    CHOOSE -->|Latihan| L_MENU
    CHOOSE -->|Kuis| Q_MENU
    CHOOSE -->|Riwayat| HIST[Lihat Riwayat]
    
    %% Kamus
    K_MENU -->|Abjad/Kata| K_LIST --> K_DETAIL --> K_MARK --> DASH
    
    %% Latihan
    L_MENU -->|Abjad/Kata| L_PREP --> L_RT --> L_RES --> DASH
    
    %% Kuis
    Q_MENU --> Q_CHECK
    Q_CHECK -->|Ya| Q_RUN --> Q_SUBMIT --> Q_RES --> DASH
    Q_CHECK -->|Tidak| Q_FAIL --> DASH

    %% Logout
    HIST --> DASH
    DASH --> LOGOUT
    LOGOUT -->|Ya| FINISH
    LOGOUT -->|Tidak| CHOOSE

    %% Styling
    classDef decision fill:#f39c12,stroke:#d35400,color:white;
    classDef feature fill:#ecf0f1,stroke:#bdc3c7,color:#2c3e50;
    class K_MENU,L_MENU,Q_CHECK,CHOOSE,LOGOUT decision;
```

---

## 1.3 Flowchart Alur Admin

Alur kerja administrator dalam mengelola sistem.

```mermaid
flowchart LR
    %% Main Backbone
    START([Start]) --> LOGIN[/Login Admin/] --> DASH[Dashboard Admin]
    DASH --> MENU{Menu Utama}
    DASH --> EXIT([Logout])

    subgraph MGMT_USER [" 👥 USERS "]
        direction TB
        U_LIST[List Users]
        U_ACT{Action}
        U_ADD[/Tambah User/]
        U_EDIT[/Edit User/]
        U_DEL[Hapus User]
        
        U_LIST --> U_ACT
        U_ACT -->|Add| U_ADD --> U_LIST
        U_ACT -->|Edit| U_EDIT --> U_LIST
        U_ACT -->|Del| U_DEL --> U_LIST
    end

    subgraph MGMT_CONTENT [" 📁 CONTENT "]
        direction TB
        C_LIST[List Konten]
        C_ACT{Action}
        C_DB[(Database)]
        
        C_LIST --> C_ACT
        C_ACT -->|CRUD| C_DB --> C_LIST
    end

    subgraph MGMT_AI [" 🤖 MODEL AI "]
        direction TB
        M_VIEW[Cek Model]
        M_UP[/Upload .h5 & .json/]
        M_VAL{Valid?}
        M_SAVE[Simpan & Reload]
        
        M_VIEW --> M_UP --> M_VAL
        M_VAL -->|Ya| M_SAVE --> M_VIEW
        M_VAL -->|Tidak| M_VIEW
    end

    %% Connections
    MENU -->|User| U_LIST
    MENU -->|Konten| C_LIST
    MENU -->|Model AI| M_VIEW
    
    style START fill:#2ecc71
    style EXIT fill:#e74c3c
```

---

## 1.4 Flowchart Detail Fitur Latihan

Detail teknis alur latihan dengan integrasi AI.

```mermaid
flowchart TD
    %% Nodes
    START([Mulai Sesi]) 
    CAM_CHK{Izin Kamera?}
    CAM_REQ[Minta Izin]
    
    INIT[Inisialisasi AI]
    MODE{Pilih Mode}
    
    subgraph M_ABJAD [" Mode Abjad "]
        A_IN[/Input Kata/]
        A_LOOP[Loop per Huruf]
        A_SHOW[Tampil Huruf Target]
    end
    
    subgraph M_KATA [" Mode Kata "]
        K_IN[Pilih Kartu Kata]
        K_GUIDE[Lihat Panduan]
    end
    
    PROCESS{{Proses Deteksi AI}}
    VALID{Gerakan Valid?}
    FEEDBACK[Visual Feedback]
    NEXT{Lanjut/Selesai?}
    
    SAVE[(Simpan Sesi)]
    RESULT[Tampil Hasil]
    END([Selesai])

    %% Flow
    START --> CAM_CHK
    CAM_CHK -->|Tidak| CAM_REQ
    CAM_REQ --> CAM_CHK
    CAM_CHK -->|Ya| INIT --> MODE
    
    MODE -->|Abjad| A_IN --> A_LOOP --> A_SHOW --> PROCESS
    MODE -->|Kata| K_IN --> K_GUIDE --> PROCESS
    
    PROCESS --> VALID
    VALID -->|Tidak| FEEDBACK --> PROCESS
    VALID -->|Ya| NEXT
    
    NEXT -->|Belum Selesai| PROCESS
    NEXT -->|Selesai| SAVE --> RESULT --> END

    style PROCESS fill:#9b59b6,color:white
```

---

# BAGIAN 2: ACTIVITY DIAGRAM

## 2.1 Activity Diagram: Registrasi User

```mermaid
flowchart LR
    subgraph U [User]
        direction TB
        START((Start))
        FORM[Isi Form Registrasi]
        BTN[Klik Daftar]
    end
    
    subgraph S [Sistem]
        direction TB
        VAL{Validasi?}
        SAVE[(Simpan API)]
        REDIR[Redirect Login]
        ERR[Tampil Error]
    end

    START --> FORM --> BTN
    BTN --> VAL
    
    VAL -->|Valid| SAVE --> REDIR
    VAL -->|Invalid| ERR --> FORM

    style START fill:black,color:white
    style REDIR stroke-width:4px
```

## 2.2 Activity Diagram: Login User

```mermaid
flowchart LR
    subgraph U [User]
        direction TB
        START((Start))
        IN[Input Email/Pass]
        BTN[Klik Masuk]
    end
    
    subgraph S [Sistem]
        direction TB
        AUTH{Autentikasi?}
        ROLE{Cek Role}
        USER[Dash. User]
        ADMIN[Dash. Admin]
        ERR[Error Msg]
    end

    START --> IN --> BTN --> AUTH
    
    AUTH -->|Gagal| ERR --> IN
    AUTH -->|Sukses| ROLE
    
    ROLE -->|User| USER
    ROLE -->|Admin| ADMIN

    style START fill:black,color:white
```

## 2.3 Activity Diagram: Fitur Kamus

```mermaid
flowchart TD
    subgraph U [User]
        START((●)) 
        MENU[Pilih Kamus]
        ITEM[Pilih Item]
        DONE[Klik 'Selesai']
    end
    
    subgraph S [Sistem]
        DATA[(Ambil Data)]
        SHOW[Tampil Konten]
        REC[(Simpan Progress)]
        NOTIF[Notifikasi Sukses]
    end

    START --> MENU --> ITEM --> DATA --> SHOW
    SHOW --> DONE --> REC --> NOTIF
    NOTIF --> END((◉))
```

## 2.4 Activity Diagram: Latihan Deteksi Real-time

```mermaid
flowchart TD
    subgraph U [User]
        START((●))
        SETUP[Setup Latihan]
        ACT[Lakukan Gerakan]
    end
    
    subgraph S [Sistem]
        CAM[Buka Kamera]
        AI{{Deteksi Hand/Pose}}
        EVAL{Cocok?}
        FB_OK[Feedback: Benar]
        FB_NO[Feedback: Salah]
        LOOP{Selesai?}
        SAVE[(Simpan & Tampil Hasil)]
    end

    START --> SETUP --> CAM
    CAM --> ACT --> AI 
    AI --> EVAL
    
    EVAL -->|Ya| FB_OK --> LOOP
    EVAL -->|Tidak| FB_NO --> ACT
    
    LOOP -->|Belum| ACT
    LOOP -->|Ya| SAVE --> END((◉))
```

## 2.5 Activity Diagram: Pengerjaan Kuis

```mermaid
flowchart TD
    subgraph U [User]
        START((●))
        PICK[Pilih Kuis]
        DO[Kerjakan Soal]
        SUB[Submit]
    end
    
    subgraph S [Sistem]
        CHECK{Cek Limit}
        LOAD[(Load Soal)]
        CALC[Hitung Skor]
        RES[Tampil Hasil]
    end

    START --> PICK --> CHECK
    CHECK -->|Boleh| LOAD --> DO --> SUB --> CALC --> RES --> END((◉))
    CHECK -->|Ditolak| END
```

## 2.6 Activity Diagram: Manajemen User (Admin)

```mermaid
flowchart LR
    subgraph A [Admin]
        START((●))
        MENU[Menu User]
        ACT{CRUD Action}
    end
    
    subgraph S [Sistem]
        DB[(Database)]
        REF[Refresh View]
    end

    START --> MENU --> ACT
    ACT -->|Create/Update/Delete| DB --> REF
    REF --> END((◉))
```

## 2.7 Activity Diagram: Manajemen Konten (Admin)

```mermaid
flowchart LR
    subgraph A [Admin]
        START((●))
        MENU[Menu Konten]
        UPLOAD[Upload Media/Data]
    end
    
    subgraph S [Sistem]
        STORE[Storage File]
        DB[(Record DB)]
        ACK[Notifikasi]
    end

    START --> MENU --> UPLOAD --> STORE --> DB --> ACK --> END((◉))
```

## 2.8 Activity Diagram: Upload Model AI (Admin)

```mermaid
flowchart TD
    subgraph A [Admin]
        START((●))
        UP[Upload .h5 & .json]
    end
    
    subgraph S [Sistem]
        VAL{Validasi}
        BACKUP[Backup Lama]
        REPLACE[Ganti File]
        RELOAD[Reload Flask API]
        OK[Sukses]
    end

    START --> UP --> VAL
    VAL -->|Valid| BACKUP --> REPLACE --> RELOAD --> OK --> END((◉))
    VAL -->|Error| END
```

---

# BAGIAN 3: SEQUENCE DIAGRAM

## 3.1 Sequence Diagram: Registrasi

```mermaid
sequenceDiagram
    actor U as User
    participant V as View
    participant C as Controller
    participant M as Model
    participant D as DB
    
    U->>V: Submit Form
    activate V
    V->>C: postRegister()
    activate C
    C->>C: Validasi()
    C->>M: create()
    activate M
    M->>D: INSERT
    activate D
    D-->>M: OK
    deactivate D
    M-->>C: User Created
    deactivate M
    C-->>V: Redirect Login
    deactivate C
    V-->>U: Tampil Login
    deactivate V
```

## 3.2 Sequence Diagram: Latihan Deteksi (Real-time)

```mermaid
sequenceDiagram
    actor U as User
    participant V as Browser(View)
    participant JS as Javascript
    participant API as Python API
    participant BE as Laravel BE
    
    U->>V: Mulai Latihan
    V->>JS: initCamera()
    
    loop Real-time Frame
        JS->>API: POST /predict (Base64)
        activate API
        API-->>JS: JSON {label, confidence}
        deactivate API
        JS->>V: Update Visual
    end
    
    U->>V: Selesai/Stop
    V->>JS: endSession()
    JS->>BE: POST /save-session
    activate BE
    BE-->>JS: OK
    deactivate BE
    JS->>V: Show Summary
```

## 3.3 Sequence Diagram: Upload Model AI

```mermaid
sequenceDiagram
    actor A as Admin
    participant V as View
    participant C as Controller
    participant FS as FileSystem
    participant API as Python Service
    
    A->>V: Upload Model
    V->>C: updateModel()
    activate C
    C->>FS: saveFile()
    FS-->>C: path
    C->>API: POST /reload
    activate API
    API-->>C: Service Ready
    deactivate API
    C-->>V: Success Message
    deactivate C
    V-->>A: Notifikasi
```

---

**Catatan:** Dokumen ini menggunakan sintaks MermaidJS yang kompatibel dengan GitHub dan ekstensi VS Code populer.

# Diagram Integrasi Mode AI (Abjad & Kata)

Dokumen ini menjelaskan bagaimana **Mode Abjad** dan **Mode Kata** diintegrasikan ke dalam sistem BisindoCNNfi, mulai dari sisi Frontend (User Interface) hingga Backend (Flask API).

## 1. Arsitektur Komponen (Class Diagram)

Diagram ini menggambarkan struktur backend dan hubungan antar handler.

```mermaid
classDiagram
    direction TB

    class FlaskApp {
        +route /predict (Abjad)
        +route /predict/kata (Kata)
        +route /nlg (Kalimat)
        +init_handlers()
    }

    class BaseHandler {
        <<Interface>>
        +load_resources()
        +predict(image)
    }

    class AbjadModelHandler {
        -model: KerasModel
        -class_names: List
        +preprocess_image()
        +predict(image)
    }

    class KataModelHandler {
        -model: KerasModel
        -holistic: MediaPipe
        -buffer: List
        +preprocess_image()
        +process_buffer()
        +predict(image)
        +reset_session()
    }

    class NLGHandler {
        +naturalize(tokens)
        +analyze_sentiment(tokens)
    }

    FlaskApp --> AbjadModelHandler : Menggunakan
    FlaskApp --> KataModelHandler : Menggunakan
    FlaskApp --> NLGHandler : Menggunakan
    
    AbjadModelHandler --|> BaseHandler : Mengimplementasi
    KataModelHandler --|> BaseHandler : Mengimplementasi

    note for KataModelHandler "Kompleks: Menggunakan MediaPipe\nuntuk ekstraksi fitur skeleton\nsebelum inferensi LSTM/GRU."
    note for AbjadModelHandler "Sederhana: CNN Image Based\n(Resize 224x224 -> Predict)."
```

---

## 2. Alur Eksekusi Mode (Sequence Diagram)

Diagram ini menjelaskan bagaimana sistem menangani perpindahan mode dan eksekusi prediksi.

```mermaid
sequenceDiagram
    autonumber
    actor User
    participant UI as Web UI (Blade/JS)
    participant Flask as Flask API (App.py)
    participant Abjad as AbjadHandler
    participant Kata as KataHandler
    participant MP as MediaPipe (Backend)

    %% Mode Selection
    rect rgb(240, 248, 255)
        note right of User: User Memilih Mode Latihan
        User->>UI: Pilih Mode
        alt Mode = Abjad
            UI->>UI: Set API Endpoint = /predict
        else Mode = Kata
            UI->>UI: Set API Endpoint = /predict/kata
            UI->>Flask: POST /reset (Reset Session)
            Flask->>Kata: reset_session()
            Flask-->>UI: OK
        end
    end

    %% Execution Loop
    loop Interval Prediksi (cth. 200ms)
        User->>UI: Melakukan Gerakan
        UI->>UI: Capture Frame (Webcam)
        
        alt Mode = Abjad
            UI->>Flask: POST /predict (Image Base64)
            activate Flask
            Flask->>Abjad: predict(image)
            activate Abjad
            Abjad->>Abjad: Preprocess (Resize 224x224)
            Abjad->>Abjad: CNN Inference
            Abjad-->>Flask: Hasil {Label, Conf}
            deactivate Abjad
            Flask-->>UI: JSON Result
            deactivate Flask
        
        else Mode = Kata
            UI->>Flask: POST /predict/kata (Image Base64)
            activate Flask
            Flask->>Kata: predict(image)
            activate Kata
            Kata->>MP: Ekstraksi Skeleton (Holistic)
            
            alt Gerakan Terdeteksi?
                Kata->>Kata: Buffer Frame/Skeleton
                opt Buffer Penuh / Gerakan Selesai
                    Kata->>Kata: Proses Sequence
                    Kata->>Kata: Inferensi RNN/LSTM
                end
            end
            
            Kata-->>Flask: Hasil {Label, Conf, Stability}
            deactivate Kata
            Flask-->>UI: JSON Result
            deactivate Flask
        end

        UI->>UI: Update Tampilan (Label & Confidence)
    end
```

## 3. Penjelasan Integrasi

### 3.1 Routing
Sistem menggunakan **Endpoint Routing** untuk membedakan mode:
- **Mode Abjad**: Mengirim request ke `/predict`. Endpoint ini diarahkan ke `AbjadModelHandler` yang menggunakan model CNN ringan untuk klasifikasi gambar statis (per frame).
- **Mode Kata**: Mengirim request ke `/predict/kata`. Endpoint ini diarahkan ke `KataModelHandler` yang lebih kompleks.

### 3.2 Perbedaan Logic Handler
- **Abjad**: `Input Gambar` -> `Resize` -> `CNN Model` -> `Output Huruf`. Sifatnya *stateless* (tidak peduli frame sebelumnya).
- **Kata**: `Input Gambar` -> `MediaPipe Holistic` (Ekstraksi Skeleton) -> `Motion Analysis` -> `Buffering` -> `Sequence Model` -> `Output Kata`. Sifatnya *stateful* (bergantung pada urutan frame/gerakan), sehingga memerlukan endpoint `/reset` saat ganti sesi.

### 3.3 Frontend Logic
Frontend (`index.blade.php` & JS) bertanggung jawab untuk:
1. Menentukan endpoint tujuan berdasarkan mode yang aktif.
2. Mengirim frame secara periodik (looping).
3. Melakukan visualisasi dasar (bounding box) menggunakan MediaPipe sisi client (opsional/visual only) atau murni mengirim gambar ke server.

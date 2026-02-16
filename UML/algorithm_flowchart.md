# Flowchart Algoritma AI (Format PlantUML)

Dokumen ini berisi diagram alur algoritma dalam format **PlantUML**.
Tekan `Alt + D` di VS Code untuk me-render diagram (jika ekstensi PlantUML terinstall).

---

## 1. Algoritma Deteksi Abjad (Sistem Statis)

```plantuml
@startuml
skinparam backgroundColor white
skinparam handwritten false

title Algoritma Deteksi Abjad (Static Image)

start

:Input: Frame Gambar/Base64/ <
note right: Dari Webcam Frontend

partition "Preprocessing" {
  :Decode Base64 ke Citra RGB;
  :Resize ke 224x224 px;
  :Normalisasi Pixel (0-255 -> 0.0-1.0);
  :Expand Dimensions (batch axis);
}

partition "Model Inference" {
  :Input ke Model CNN (MobileNetV2);
  :Hitung Probabilitas (Softmax);
}

partition "Post-processing" {
  :Urutkan Probabilitas (Ranking);
  :Ambil Kandidat Top-1;
  :Format Output JSON;
}

:Output: Label & Confidence >

stop
@enduml
```

---

## 2. Algoritma Deteksi Kata (Sistem Dinamis)

```plantuml
@startuml
skinparam backgroundColor white
skinparam handwritten false

title Algoritma Deteksi Kata (Dynamic Sequence)

start
:Start Frame Loop;
note right: Webcam Stream

repeat
  :Input: Single Video Frame <
  
  partition "Frame Preprocessing" {
    :Flip Horizontal (Mirroring);
    if (Cek Pencahayaan?) then (Gelap)
        :Apply CLAHE Enhancement;
    else (Normal)
    endif
  }

  partition "MediaPipe Pipeline" {
    :MediaPipe Holistic Inference;
    :Extract Pose & Hand Landmarks;
    if (Ada Tangan?) then (Tidak)
        :Skip Frame (Idle);
        detach
    else (Ya)
    endif
  }

  partition "Motion Analysis" {
    :Hitung Pixel Motion (Cepat);
    :Hitung Skeleton Motion (Akurat);
    
    if (Motion > Threshold?) then (Ya/Bergerak)
       :Simpan Frame ke Buffer;
    else (Tidak/Diam)
    endif
  }
  
  partition "Session Manager" {
      if (Cek Status Buffer?) then (Buffer Penuh / Gerakan Berhenti)
          :Trigger Prediksi (Stop Gesture);
          break
      else (Lanjut Recording)
      endif
  }

repeat while (Looping...)

partition "Data Preparation V3" {
  :Trim Active Segment (Potong Awal/Akhir Diam);
  :Sample 20 Frame Seragam;
  
  fork
    :Render Skeleton ke Canvas Hitam;
    :Generate Input Normal;
  fork again
    :Flip Landmark Coordinates;
    :Render Skeleton (Mirror);
    :Generate Input Flipped;
  end fork
}

partition "LSTM Ensemble Inference" {
  :Predict Normal Input;
  :Predict Flipped Input;
  :Average Probabilities (Ensemble);
}

partition "Smoothing" {
  :Temporal Smoothing (Exponential Moving Avg);
}

:Output: Prediksi Kata >

stop
@enduml
```

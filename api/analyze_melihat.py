import cv2
import numpy as np
import os
from collections import deque
import matplotlib.pyplot as plt

def analyze_melihat_video(video_path):
    """
    Analyze the 'melihat' video to understand motion characteristics
    """
    if not os.path.exists(video_path):
        print(f"Video not found: {video_path}")
        return
    
    cap = cv2.VideoCapture(video_path)
    
    # Motion analysis variables
    motion_history = []
    pixel_motion_history = []
    prev_frame = None
    
    frame_count = 0
    
    while cap.isOpened():
        ret, frame = cap.read()
        if not ret:
            break
            
        # Convert to grayscale for motion analysis
        gray = cv2.cvtColor(frame, cv2.COLOR_BGR2GRAY)
        gray = cv2.resize(gray, (160, 160))
        
        # Calculate pixel-based motion
        if prev_frame is not None:
            pixel_motion = np.mean(cv2.absdiff(gray, prev_frame))
            pixel_motion_history.append(pixel_motion)
        
        prev_frame = gray.copy()
        frame_count += 1
    
    cap.release()
    
    # Analyze motion characteristics
    if pixel_motion_history:
        avg_motion = np.mean(pixel_motion_history)
        max_motion = np.max(pixel_motion_history)
        min_motion = np.min(pixel_motion_history)
        
        print(f"=== ANALISIS GERAKAN 'MELIHAT' ===")
        print(f"Total frames: {frame_count}")
        print(f"Rata-rata motion: {avg_motion:.2f}")
        print(f"Motion maksimum: {max_motion:.2f}")
        print(f"Motion minimum: {min_motion:.2f}")
        print(f"Rentang motion: {max_motion - min_motion:.2f}")
        
        # Plot motion graph
        plt.figure(figsize=(12, 6))
        plt.plot(pixel_motion_history, label='Pixel Motion')
        plt.title('Analisis Gerakan "Melihat"')
        plt.xlabel('Frame')
        plt.ylabel('Motion Intensity')
        plt.legend()
        plt.grid(True)
        
        # Save analysis plot
        plot_path = os.path.join(os.path.dirname(video_path), 'melihat_motion_analysis.png')
        plt.savefig(plot_path)
        print(f"Grafik analisis disimpan di: {plot_path}")
        
        # Analyze motion patterns
        motion_threshold = avg_motion * 0.3  # Threshold for significant motion
        significant_motion_frames = sum(1 for m in pixel_motion_history if m > motion_threshold)
        
        print(f"\n=== KARAKTERISTIK GERAKAN ===")
        print(f"Frame dengan motion signifikan: {significant_motion_frames}/{frame_count} ({significant_motion_frames/frame_count*100:.1f}%)")
        print(f"Threshold motion signifikan: {motion_threshold:.2f}")
        
        # Check if motion is too subtle
        if avg_motion < 2.0:
            print("\n⚠️  PERINGATAN: Gerakan mungkin terlalu halus untuk sistem deteksi saat ini")
            print("Rekomendasi: Turunkan threshold motion detection")
        
        # Check if motion is too brief
        if significant_motion_frames < 5:
            print("\n⚠️  PERINGATAN: Gerakan mungkin terlalu singkat")
            print("Rekomendasi: Sesuaikan MIN_GESTURE_FRAMES")

if __name__ == "__main__":
    video_path = r"c:\laragon\www\BisindoCNN\api\test\melihat (54).mp4"
    analyze_melihat_video(video_path)
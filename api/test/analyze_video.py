
import cv2
import mediapipe as mp
import matplotlib.pyplot as plt
import numpy as np
import os

# Init MediaPipe
mp_hands = mp.solutions.hands
hands = mp_hands.Hands(
    static_image_mode=False,
    max_num_hands=2,
    min_detection_confidence=0.1, # Lower threshold to see if it's detected at all
    min_tracking_confidence=0.1
)

video_path = 'melihat (54).mp4'
if not os.path.exists(video_path):
    print(f"Error: {video_path} not found.")
    exit()

cap = cv2.VideoCapture(video_path)

frames_data = [] # To store (frame_idx, handedness, index_z, index_y)
confidence_scores = []

frame_idx = 0
while cap.isOpened():
    ret, frame = cap.read()
    if not ret:
        break

    # RGB for MediaPipe
    image_rgb = cv2.cvtColor(frame, cv2.COLOR_BGR2RGB)
    results = hands.process(image_rgb)

    detected = False
    if results.multi_hand_landmarks:
        detected = True
        for idx, hand_landmarks in enumerate(results.multi_hand_landmarks):
            # Log Index Finger Tip (8)
            index_tip = hand_landmarks.landmark[8]
            
            # MediaPipe Z is relative to wrist, roughly. 
            # "z" represents the landmark depth with the depth at the wrist being the origin,
            # and the smaller the value the closer the landmark is to the camera.
            
            frames_data.append({
                'frame': frame_idx,
                'hand_idx': idx,
                'x': index_tip.x,
                'y': index_tip.y,
                'z': index_tip.z, 
                'detected': True
            })
    else:
        frames_data.append({
            'frame': frame_idx,
            'detected': False
        })

    frame_idx += 1

cap.release()

# Analysis
timestamps = [d['frame'] for d in frames_data]
z_values = [d['z'] if d.get('detected') else np.nan for d in frames_data]
x_values = [d['x'] if d.get('detected') else np.nan for d in frames_data]

# Plotting
plt.figure(figsize=(12, 6))

plt.subplot(2, 1, 1)
plt.plot(timestamps, z_values, label='Index Finger Tip Z-Coordinate', color='purple')
plt.title('Depth (Z) Analysis of "Melihat" Gesture')
plt.ylabel('Z Coordinate (Negative = Closer)')
plt.grid(True)
plt.legend()

plt.subplot(2, 1, 2)
plt.plot(timestamps, x_values, label='Index Finger Tip X-Coordinate', color='blue')
plt.title('X Movement')
plt.ylabel('X Coordinate')
plt.xlabel('Frame Index')
plt.grid(True)

output_plot = 'melihat_analysis_plot.png'
plt.tight_layout()
plt.savefig(output_plot)
print(f"Analysis complete. Plot saved to {output_plot}")

# Text Summary
total_frames = frame_idx
detected_frames = sum(1 for d in frames_data if d.get('detected'))
print(f"Total Frames: {total_frames}")
print(f"Detected Frames: {detected_frames}")
print(f"Detection Rate: {detected_frames/total_frames*100:.2f}%")

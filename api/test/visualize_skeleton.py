
import cv2
import mediapipe as mp
import os

# Init MediaPipe
mp_hands = mp.solutions.hands
mp_drawing = mp.solutions.drawing_utils
mp_drawing_styles = mp.solutions.drawing_styles

hands = mp_hands.Hands(
    static_image_mode=False,
    max_num_hands=2, # To see valid double detections
    min_detection_confidence=0.1,
    min_tracking_confidence=0.1
)

input_path = 'melihat (54).mp4'
output_path = 'melihat_skeleton.mp4'
screenshot_path = 'melihat_error_frame.png'

if not os.path.exists(input_path):
    print(f"Error: {input_path} not found.")
    exit()

cap = cv2.VideoCapture(input_path)
width = int(cap.get(cv2.CAP_PROP_FRAME_WIDTH))
height = int(cap.get(cv2.CAP_PROP_FRAME_HEIGHT))
fps = int(cap.get(cv2.CAP_PROP_FPS))

# Codec for MP4
fourcc = cv2.VideoWriter_fourcc(*'mp4v')
out = cv2.VideoWriter(output_path, fourcc, fps, (width, height))

frame_idx = 0
saved_screenshot = False

print(f"Processing {input_path}...")

while cap.isOpened():
    ret, frame = cap.read()
    if not ret:
        break

    image_rgb = cv2.cvtColor(frame, cv2.COLOR_BGR2RGB)
    results = hands.process(image_rgb)

    # Draw Landmarks
    if results.multi_hand_landmarks:
        for hand_landmarks in results.multi_hand_landmarks:
            mp_drawing.draw_landmarks(
                frame,
                hand_landmarks,
                mp_hands.HAND_CONNECTIONS,
                mp_drawing_styles.get_default_hand_landmarks_style(),
                mp_drawing_styles.get_default_hand_connections_style()
            )
        
        # Save a screenshot if we detect 2 hands (potential error for one-handed gesture)
        # or just save one frame with detection for reference
        if len(results.multi_hand_landmarks) > 1 and not saved_screenshot:
             cv2.imwrite(screenshot_path, frame)
             saved_screenshot = True
             
    # Write frame
    out.write(frame)
    frame_idx += 1
    if frame_idx % 20 == 0:
        print(f"Processed frame {frame_idx}")

cap.release()
out.release()
print(f"Done. Output saved to {output_path}")

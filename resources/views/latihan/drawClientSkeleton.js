
        function drawClientSkeleton(multiHandLandmarks) {
            const w = outCanvas.width;
            const h = outCanvas.height;
            
            outCtx.lineWidth = 2;
            outCtx.lineCap = 'round';
            outCtx.lineJoin = 'round';

            // Helper to draw points
            const drawPoints = (landmarks, color) => {
                outCtx.fillStyle = color;
                for (let i = 0; i < landmarks.length; i++) {
                    const p = landmarks[i];
                    // Mirror X for display
                    const px = (1 - p.x) * w;
                    const py = p.y * h;
                    
                    outCtx.beginPath();
                    outCtx.arc(px, py, 3, 0, 2 * Math.PI);
                    outCtx.fill();
                }
            };

            // Helper to draw lines
            const drawLines = (landmarks, connections, color) => {
                outCtx.strokeStyle = color;
                for (let i = 0; i < connections.length; i++) {
                    const [startIdx, endIdx] = connections[i];
                    const start = landmarks[startIdx];
                    const end = landmarks[endIdx];
                    
                    if (start && end) {
                        outCtx.beginPath();
                        outCtx.moveTo((1 - start.x) * w, start.y * h);
                        outCtx.lineTo((1 - end.x) * w, end.y * h);
                        outCtx.stroke();
                    }
                }
            };

            for (const landmarks of multiHandLandmarks) {
                // Draw connections
                drawLines(landmarks, HAND_CONNECTIONS, 'rgba(0, 255, 255, 0.8)'); // Cyan for connections
                // Draw joints
                drawPoints(landmarks, 'rgba(255, 255, 255, 0.8)'); // White for joints
            }
        }

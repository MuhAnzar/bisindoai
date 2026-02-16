<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DeteksiController extends Controller
{


    /**
     * Tampilkan halaman deteksi real-time
     */
    public function index()
    {
        // Cek apakah API aktif
        $apiStatus = $this->checkApiHealth();
        
        return view('deteksi.index', [
            'apiStatus' => $apiStatus
        ]);
    }

    /**
     * Endpoint untuk prediksi dari webcam
     * Menerima base64 image dari JavaScript
     */
    public function predict(Request $request)
    {
        try {
            // Validasi input
            $request->validate([
                'image_base64' => 'required|string'
            ]);

            $imageBase64 = $request->image_base64;
            
            // Remove data:image prefix jika ada
            if (strpos($imageBase64, 'data:image') === 0) {
                $imageBase64 = preg_replace('/^data:image\/\w+;base64,/', '', $imageBase64);
            }

            // Kirim ke Python API
            $response = Http::timeout(10)
                ->asForm()
                ->post(config('services.python_api.url') . '/predict', [
                    'image_base64' => $imageBase64
                ]);

            // Cek response
            if ($response->successful()) {
                $data = $response->json();
                
                // Log untuk debugging
                Log::info('Prediction success', [
                    'label' => $data['label'] ?? 'unknown',
                    'confidence' => $data['confidence'] ?? 0
                ]);

                return response()->json([
                    'success' => true,
                    'label' => $data['label'],
                    'confidence' => round($data['confidence'], 2)
                ]);
            } else {
                Log::error('Python API error', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);

                return response()->json([
                    'success' => false,
                    'error' => 'API returned error: ' . $response->status()
                ], 500);
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Validation failed: ' . $e->getMessage()
            ], 422);

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('Cannot connect to Python API', [
                'url' => config('services.python_api.url'),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Cannot connect to Python API. Make sure the API is running at ' . config('services.python_api.url')
            ], 503);

        } catch (\Exception $e) {
            Log::error('Prediction error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Internal server error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Health check - cek apakah Python API aktif
     */
    public function checkApiHealth()
    {
        try {
            $response = Http::timeout(3)->get(config('services.python_api.url') . '/health');
            
            if ($response->successful()) {
                $data = $response->json();
                return [
                    'status' => 'online',
                    'model_loaded' => $data['model_loaded'] ?? false,
                    'num_classes' => $data['num_classes'] ?? 0,
                    'image_size' => $data['image_size'] ?? 0,
                    'tensorflow_version' => $data['tensorflow_version'] ?? 'unknown'
                ];
            }
            
            return [
                'status' => 'error',
                'message' => 'API returned status: ' . $response->status()
            ];

        } catch (\Exception $e) {
            return [
                'status' => 'offline',
                'message' => 'Cannot connect to API at ' . config('services.python_api.url')
            ];
        }
    }

    /**
     * API endpoint untuk health check (JSON)
     */
    public function health()
    {
        $apiStatus = $this->checkApiHealth();
        return response()->json($apiStatus);
    }
}

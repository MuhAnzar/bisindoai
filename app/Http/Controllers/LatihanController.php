<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\PracticeSession;
use App\Models\ModeCard;
use Carbon\Carbon;

class LatihanController extends Controller
{


    /**
     * Halaman latihan kamera / deteksi isyarat.
     * API health check akan dilakukan via JavaScript secara asinkron
     */
    public function deteksi(): View
    {
        // Load mode cards from database, fallback to defaults if empty
        $modeCards = ModeCard::active()->ordered()->get();
        
        // If no mode cards in database, use default data
        if ($modeCards->isEmpty()) {
            $modeCards = collect(ModeCard::getDefaults())->map(function ($data) {
                return (object) $data;
            });
        }
        
        // Real statistics for the dashboard
        $stats = [
            'total_abjad' => \App\Models\Abjad::count(),
            'total_kata' => \App\Models\KataDasar::count(),
            'avg_accuracy' => round(PracticeSession::avg('accuracy_percentage') ?? 95),
            'avg_latency' => 500, // Target latency in ms
        ];
        
        return view('latihan.deteksi', compact('modeCards', 'stats'));
    }

    /**
     * Endpoint untuk prediksi dari webcam
     * Menerima base64 image dari JavaScript
     */
    public function predict(Request $request)
    {
        Log::info('LatihanController: Start Prediction');
        
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

            Log::info('LatihanController: Sending to Python API at ' . config('services.python_api.url'));

            // Kirim ke Python API
            $response = Http::timeout(10)
                ->asForm()
                ->post(config('services.python_api.url') . '/predict', [
                    'image_base64' => $imageBase64
                ]);

            Log::info('LatihanController: Response status: ' . $response->status());

            // Cek response
            if ($response->successful()) {
                $data = $response->json();
                
                if (!$data || !isset($data['label'])) {
                    Log::error('Python API returned valid HTTP 200 but invalid JSON data: ' . $response->body());
                    return response()->json([
                        'success' => false,
                        'error' => 'API Error: Invalid response format from Model'
                    ], 500);
                }

                return response()->json([
                    'success' => true,
                    'label' => $data['label'],
                    'confidence' => round($data['confidence'] ?? 0, 2)
                ]);
            } else {
                Log::error('Python API error body: ' . $response->body());

                return response()->json([
                    'success' => false,
                    'error' => 'API returned error: ' . $response->status() . ' - ' . $response->body()
                ], 500);
            }

        } catch (\Exception $e) {
            Log::error('LatihanController Exception: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            return response()->json([
                'success' => false,
                'error' => 'Internal Server Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Save a completed practice session
     */
    public function saveSession(Request $request)
    {
        try {
            $validated = $request->validate([
                'word' => 'required|string|max:50',
                'duration' => 'required|integer|min:1',
                'accuracy_percentage' => 'nullable|numeric|min:0|max:100',
            ]);

            $session = PracticeSession::create([
                'user_id' => auth()->id(), // null for guests
                'word' => strtoupper($validated['word']),
                'duration' => $validated['duration'],
                'accuracy_percentage' => $validated['accuracy_percentage'] ?? 100,
                'completed_at' => Carbon::now(),
            ]);

            return response()->json([
                'success' => true,
                'session' => $session
            ]);

        } catch (\Exception $e) {
            Log::error('Save session error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user's practice history (latest 10 sessions)
     */
    public function getHistory(Request $request)
    {
        try {
            // If user is not logged in, return empty array immediately
            if (!auth()->check()) {
                return response()->json([
                    'success' => true,
                    'sessions' => []
                ]);
            }

            // Always filter by user_id first, then order and limit
            $sessions = PracticeSession::where('user_id', auth()->id())
                ->latest('completed_at')
                ->limit(10)
                ->get()
                ->map(function ($session) {
                    return [
                        'id' => $session->id,
                        'word' => $session->word,
                        'duration' => $session->duration,
                        'formatted_duration' => $session->formatted_duration,
                        'accuracy_percentage' => $session->accuracy_percentage,
                        'accuracy_badge' => $session->accuracy_badge,
                        'completed_at' => $session->completed_at->format('d M Y, H:i'),
                        'completed_ago' => $session->completed_at->diffForHumans(),
                    ];
                });

            return response()->json([
                'success' => true,
                'sessions' => $sessions
            ]);

        } catch (\Exception $e) {
            Log::error('Get history error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

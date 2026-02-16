<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Pengguna;
use App\Models\PracticeSession;
use App\Models\Abjad;
use App\Models\KataDasar;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Tampilkan halaman beranda dengan statistik dinamis.
     */
    public function index()
    {
        $data = [
            'totalPengguna' => Pengguna::where('peran', 'user')->count(),
            'totalMateri' => Abjad::count() + KataDasar::count(),
        ];

        // Statistik Dashboard untuk User Login
        if (Auth::check()) {
            $user = Auth::user();
            $userId = $user->id;
            
            // Single optimized query for all stats
            $stats = PracticeSession::where('user_id', $userId)
                ->selectRaw('
                    COUNT(*) as total_sessions,
                    AVG(accuracy_percentage) as avg_accuracy
                ')
                ->first();

            // Optimized streak calculation - single query
            $sessionDates = PracticeSession::where('user_id', $userId)
                ->selectRaw('DATE(created_at) as date')
                ->distinct()
                ->orderBy('date', 'desc')
                ->limit(100) // Limit untuk performa
                ->pluck('date')
                ->toArray();
                
            $streak = 0;
            if (!empty($sessionDates)) {
                $now = Carbon::now()->startOfDay();
                $lastPractice = Carbon::parse($sessionDates[0])->startOfDay();
                $diff = $now->diffInDays($lastPractice);
                
                if ($diff <= 1) {
                    $streak = 1;
                    $currentDate = $lastPractice;
                    
                    for ($i = 1; $i < count($sessionDates); $i++) {
                        $prevDate = Carbon::parse($sessionDates[$i])->startOfDay();
                        if ($currentDate->diffInDays($prevDate) == 1) {
                            $streak++;
                            $currentDate = $prevDate;
                        } else {
                            break;
                        }
                    }
                }
            }

            $totalSessions = $stats->total_sessions ?? 0;
            $avgAccuracy = $stats->avg_accuracy ?? 0;
            $level = floor($totalSessions / 10) + 1;
            $levelProgress = ($totalSessions % 10) * 10;

            // Check if user has practiced today for reminder notification
            $hasPracticedToday = !empty($sessionDates) && Carbon::parse($sessionDates[0])->isToday();
            
            // Show reminder if: not practiced today AND not dismissed in this session
            $showReminder = !$hasPracticedToday && !session('reminder_dismissed');

            $userStats = [
                'streak' => $streak,
                'accuracy' => round($avgAccuracy),
                'level' => $level,
                'levelProgress' => $levelProgress,
                'firstName' => explode(' ', $user->nama)[0],
                'hasPracticedToday' => $hasPracticedToday,
                'showReminder' => $showReminder,
            ];
            
            $data = array_merge($data, $userStats);
        }

        return view('beranda', $data);
    }
}

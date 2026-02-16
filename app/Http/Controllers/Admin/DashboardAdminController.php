<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Abjad;
use App\Models\KataDasar;
use App\Models\Pengguna;
use Illuminate\View\View;

class DashboardAdminController extends Controller
{
    public function index(): View
    {
        // 1. Statistik Utama
        $totalUser = Pengguna::where('peran', 'user')->count();
        // Asumsi kuis aktif adalah semua kuis untuk saat ini (karena belum ada kolom status)
        $kuisAktif = \App\Models\Kuis::count(); 
        $totalKonten = Abjad::count() + KataDasar::count();
        
        // Menghitung jam pembelajaran dari durasi practice session (dalam detik)
        $totalSeconds = \App\Models\PracticeSession::sum('duration');
        // Konversi ke jam (pembulatan 1 desimal)
        $jamPembelajaran = round($totalSeconds / 3600, 1);

        $statistik = [
            'total_user' => $totalUser,
            'kuis_aktif' => $kuisAktif,
            'total_konten' => $totalKonten,
            'jam_pembelajaran' => $jamPembelajaran,
            // Perbandingan bulan lalu (dummy logic for now, or 0 if no data)
            'user_growth' => Pengguna::whereDate('created_at', '>=', now()->subMonth())->count(),
        ];

        // 2. Aktivitas Terbaru (Gabungan HasilKuis & PracticeSession)
        $hasilKuis = \App\Models\HasilKuis::with(['pengguna', 'kuis'])
            ->latest('tanggal_dikerjakan')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'type' => 'kuis',
                    'user_name' => $item->pengguna->nama ?? 'User',
                    'user_avatar' => substr($item->pengguna->nama ?? 'U', 0, 1),
                    'time' => \Carbon\Carbon::parse($item->tanggal_dikerjakan)->diffForHumans(),
                    'description' => 'Menyelesaikan ' . ($item->kuis->judul ?? 'Kuis'),
                    'score' => $item->skor,
                    'badge_color' => $item->skor >= 80 ? 'success' : ($item->skor >= 60 ? 'warning' : 'danger'),
                    'timestamp' => \Carbon\Carbon::parse($item->tanggal_dikerjakan),
                ];
            });

        $latihan = \App\Models\PracticeSession::with('user')
            ->latest('completed_at')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'type' => 'latihan',
                    'user_name' => $item->user->nama ?? 'User',
                    'user_avatar' => substr($item->user->nama ?? 'U', 0, 1),
                    'time' => $item->completed_at ? $item->completed_at->diffForHumans() : '-',
                    'description' => 'Berlatih isyarat "' . $item->word . '"',
                    'score' => $item->accuracy_percentage . '%',
                    'badge_color' => 'primary', // Latihan selalu biru/standar
                    'timestamp' => $item->completed_at,
                ];
            });

        // Gabung dan sort ulang
        $aktivitasTerbaru = $hasilKuis->concat($latihan)
            ->sortByDesc('timestamp')
            ->take(5);

        // 3. Ringkasan Hari Ini
        $today = now()->format('Y-m-d');
        
        // Real-time metrics
        $activeUsersFromQuiz = \App\Models\HasilKuis::whereDate('tanggal_dikerjakan', $today)->distinct('pengguna_id')->count('pengguna_id');
        $activeUsersFromPractice = \App\Models\PracticeSession::whereDate('completed_at', $today)->distinct('user_id')->count('user_id');
        // Gabungan unique user (perkiraan kasar karena kita tidak bisa union 2 model berbeda dengan mudah tanpa query builder raw)
        // Kita ambil angka terbesar saja sebagai pendekatan, atau jumlahkan jika asumsi user berbeda (tapi user bisa melakukan keduanya)
        // Pendekatan terbaik: Ambil ID dari kedua query
        $quizUserIds = \App\Models\HasilKuis::whereDate('tanggal_dikerjakan', $today)->pluck('pengguna_id')->toArray();
        $practiceUserIds = \App\Models\PracticeSession::whereDate('completed_at', $today)->pluck('user_id')->toArray();
        $uniqueActiveUsers = count(array_unique(array_merge($quizUserIds, $practiceUserIds)));

        // Hitung Rata-rata Skor Gabungan (Kuis + Latihan dari hari ini)
        $avgQuizScore = \App\Models\HasilKuis::whereDate('tanggal_dikerjakan', $today)->avg('skor');
        $avgPracticeScore = \App\Models\PracticeSession::whereDate('completed_at', $today)->avg('accuracy_percentage');

        // Jika tidak ada data hari ini, gunakan 0. Jika ada salah satu, detailkan.
        // Logikanya: ( (AvgQuiz * CountQuiz) + (AvgPractice * CountPractice) ) / (CountQuiz + CountPractice)
        $countQuizToday = \App\Models\HasilKuis::whereDate('tanggal_dikerjakan', $today)->count();
        $countPracticeToday = \App\Models\PracticeSession::whereDate('completed_at', $today)->count();
        
        $totalItems = $countQuizToday + $countPracticeToday;
        $weightedAverage = 0;

        if ($totalItems > 0) {
            $totalScore = ($avgQuizScore * $countQuizToday) + ($avgPracticeScore * $countPracticeToday);
            $weightedAverage = round($totalScore / $totalItems);
        }

        $dailyStats = [
            'user_online' => $uniqueActiveUsers . '/' . $totalUser, 
            'kuis_dikerjakan' => $countQuizToday,
            'rata_rata_skor' => $weightedAverage . '%',
            'waktu_belajar' => round(\App\Models\PracticeSession::whereDate('completed_at', $today)->sum('duration') / 60, 1) . ' menit',
        ];

        // 4. Insight / Tugas Mendatang (Real Data)
        // Kita ubah menjadi "Insight Harian" karena sistem tidak punya task management
        $newUsersCount = Pengguna::whereDate('created_at', $today)->count();
        $todaysQuizzes = $countQuizToday;
        $lowScoreQuizzes = \App\Models\HasilKuis::whereDate('tanggal_dikerjakan', $today)->where('skor', '<', 60)->count();
        $todaysPractice = $countPracticeToday;
    
        $tugasMendatang = [];

        // Insight 1: User Baru
        if ($newUsersCount > 0) {
            $tugasMendatang[] = [
                'judul' => 'Sambut User Baru',
                'prioritas' => 'Info',
                'prioritas_bg' => '#E0F2FE', // Light Blue
                'prioritas_color' => '#0369A1',
                'desc' => $newUsersCount . ' pengguna baru bergabung hari ini',
                'waktu' => 'Hari ini',
                'count' => $newUsersCount . ' user'
            ];
        } else {
             $tugasMendatang[] = [
                'judul' => 'User Baru',
                'prioritas' => 'Info',
                 'prioritas_class' => 'badge-secondary', // Fallback CSS
                'desc' => 'Belum ada pengguna baru hari ini',
                'waktu' => 'Hari ini',
                'count' => '0 user'
            ];
        }

        // Insight 2: Performa Kuis
        if ($lowScoreQuizzes > 0) {
            $tugasMendatang[] = [
                'judul' => 'Perhatian Performa',
                'prioritas' => 'Tinggi',
                 'prioritas_bg' => '#FFE4E6', // Light Red
                'prioritas_color' => '#F43F5E',
                'desc' => $lowScoreQuizzes . ' percobaan kuis memiliki skor rendah (<60)',
                'waktu' => 'Hari ini',
                'count' => $lowScoreQuizzes . ' item'
            ];
        } else {
             $tugasMendatang[] = [
                'judul' => 'Performa Kuis',
                'prioritas' => 'Bagus',
                'prioritas_bg' => '#DCFCE7', // Light Green
                'prioritas_color' => '#166534',
                'desc' => 'Semua kuis hari ini memiliki skor baik',
                'waktu' => 'Hari ini',
                'count' => $todaysQuizzes . ' kuis'
            ];
        }

        // Insight 3: Aktivitas Latihan
        $tugasMendatang[] = [
            'judul' => 'Aktivitas Latihan',
            'prioritas' => 'Sedang',
            'prioritas_class' => 'badge-warning',
            'desc' => $todaysPractice . ' sesi latihan diselesaikan user',
            'waktu' => 'Hari ini',
            'count' => $todaysPractice . ' sesi'
        ];

        return view('admin.dashboard', compact('statistik', 'aktivitasTerbaru', 'dailyStats', 'tugasMendatang'));
    }
}



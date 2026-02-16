<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KataDasar;
use Illuminate\View\View;

class AnalitikAdminController extends Controller
{
    public function index(): View
    {
        // 1. Summary Cards Data
        $totalSiswa = \App\Models\Pengguna::where('peran', 'user')->count();
        $totalKuis = \App\Models\Kuis::count();
        $totalPartisipasi = \App\Models\HasilKuis::count();
        $rataRataAkurasi = \App\Models\HasilKuis::avg('skor') ?? 0;

        // 2. Chart Data (Last 7 Days - Multi-Line)
        $endDate = now();
        $startDate = now()->subDays(6);
        $period = \Carbon\CarbonPeriod::create($startDate, $endDate);

        // A. Quiz Attempts
        $quizDataRaw = \App\Models\HasilKuis::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->whereBetween('created_at', [$startDate->format('Y-m-d 00:00:00'), $endDate->format('Y-m-d 23:59:59')])
            ->groupBy('date')
            ->pluck('count', 'date')
            ->toArray();

        // B. Practice Sessions (Count unique words practiced per day)
        $practiceDataRaw = \App\Models\PracticeSession::selectRaw('DATE(created_at) as date, COUNT(DISTINCT CONCAT(user_id, "-", word)) as count')
            ->whereBetween('created_at', [$startDate->format('Y-m-d 00:00:00'), $endDate->format('Y-m-d 23:59:59')])
            ->groupBy('date')
            ->pluck('count', 'date')
            ->toArray();

        // Align Data with Date Labels
        $chartLabels = [];
        $quizChartData = [];
        $practiceChartData = [];

        foreach ($period as $date) {
            $dateString = $date->format('Y-m-d');
            $chartLabels[] = $date->format('d M');
            $quizChartData[] = $quizDataRaw[$dateString] ?? 0;
            $practiceChartData[] = $practiceDataRaw[$dateString] ?? 0;
        }

        // 3. Recent Activity
        $recentActivity = \App\Models\HasilKuis::with(['pengguna', 'kuis'])
            ->latest()
            ->take(5)
            ->get();

        // 4. Content Stats (Refined: Abjad + Kata Categories)
        $abjadCount = \App\Models\Abjad::count();
        $abjadData = [
            'nama' => 'Abjad',
            'jumlah' => $abjadCount
        ];

        $kataData = \App\Models\KataDasar::selectRaw("COALESCE(kategori, 'Umum') as nama, COUNT(*) as jumlah")
            ->groupBy('nama')
            ->orderBy('jumlah', 'desc')
            ->get()
            ->toArray();

        // Merge Abjad into Kata Data
        $mergedData = collect(array_merge([$abjadData], $kataData));
        
        // Sort by jumlah desc
        $mergedData = $mergedData->sortByDesc('jumlah');

        $totalContent = $mergedData->sum('jumlah') ?: 1;

        $analitikKategori = $mergedData->map(function ($row) use ($totalContent) {
            return [
                'nama' => $row['nama'],
                'progres' => round(($row['jumlah'] / $totalContent) * 100),
                'akurasi' => 0, 
            ];
        })->all();

        return view('admin.analitik', compact(
            'totalSiswa', 'totalKuis', 'totalPartisipasi', 'rataRataAkurasi',
            'chartLabels', 'quizChartData', 'practiceChartData', 'recentActivity', 'analitikKategori'
        ));
    }
}



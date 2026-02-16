<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Abjad;
use App\Models\KataDasar;
use Illuminate\View\View;

class KontenAdminController extends Controller
{
    public function index(): View
    {
        // Statistik Global
        $totalAbjad = Abjad::count();
        $totalKata = KataDasar::count();
        
        $totalLatihan = \App\Models\PracticeSession::count();
        $avgAkurasi = \App\Models\PracticeSession::avg('accuracy_percentage') ?? 0;
        
        $engagement = $totalLatihan > 0 ? \App\Models\PracticeSession::distinct('user_id')->count() : 0; // Jumlah user unik yang latihan

        // Data List dengan Count Relations
        $recentAbjad = Abjad::withCount('practices')->orderBy('huruf')->get();
        $recentKata = KataDasar::withCount('practices')->latest()->get();

        return view('admin.kelola_konten', compact(
            'totalAbjad', 
            'totalKata', 
            'recentAbjad', 
            'recentKata',
            'totalLatihan',
            'avgAkurasi',
            'engagement'
        ));
    }
}



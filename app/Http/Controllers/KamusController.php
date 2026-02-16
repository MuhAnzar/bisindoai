<?php

namespace App\Http\Controllers;

use App\Models\Abjad;
use App\Models\KataDasar;
use App\Models\PracticeSession;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class KamusController extends Controller
{
    /**
     * Tampilkan kamus BISINDO bagian abjad.
     */
    public function abjad(): View
    {
        $daftarAbjad = Abjad::orderBy('huruf')->get();
        
        $progressMap = [];
        if (Auth::check()) {
            // Optimized: Ambil akurasi tertinggi per huruf langsung dari database
            $progress = PracticeSession::where('user_id', Auth::id())
                ->selectRaw('UPPER(word) as word, MAX(accuracy_percentage) as max_accuracy')
                ->groupBy('word')
                ->pluck('max_accuracy', 'word')
                ->toArray();
            
            $progressMap = $progress;
        }

        return view('kamus.abjad', compact('daftarAbjad', 'progressMap'));
    }

    /**
     * Tampilkan kamus BISINDO bagian kata dasar.
     */
    public function kataDasar(Request $request): View
    {
        $pencarian = $request->input('q');
        $kategori = $request->input('kategori');

        $query = KataDasar::query();

        if ($pencarian) {
            $query->where('kata', 'like', '%' . $pencarian . '%');
        }

        if ($kategori) {
            $query->where('kategori', $kategori);
        }

        $daftarKata = $query->orderBy('kata')->get();

        return view('kamus.kata_dasar', compact('daftarKata', 'pencarian'));
    }

    /**
     * Tandai item sebagai selesai dipelajari (manual).
     */
    public function markAsDone(Request $request): JsonResponse
    {
        $request->validate([
            'word' => 'required|string',
        ]);

        $user = Auth::user();

        // Cek jika sudah pernah ditandai selesai (100%) hari ini untuk menghindari spam
        $exists = PracticeSession::where('user_id', $user->id)
            ->where('word', $request->word)
            ->where('accuracy_percentage', 100)
            ->whereDate('created_at', now())
            ->exists();

        if (!$exists) {
            PracticeSession::create([
                'user_id' => $user->id,
                'word' => $request->word,
                'duration' => 0, // 0 menandakan manual completion
                'accuracy_percentage' => 100,
                'completed_at' => now(),
            ]);
        }

        return response()->json(['success' => true]);
    }
    /**
     * Tambah progress belajar sebesar 1% setiap kali modal dibuka.
     */
    public function incrementProgress(Request $request): JsonResponse
    {
        $request->validate([
            'word' => 'required|string',
        ]);

        $user = Auth::user();
        $word = strtoupper($request->word);

        // Cari progress tertinggi saat ini
        $currentMax = PracticeSession::where('user_id', $user->id)
            ->where('word', $word)
            ->max('accuracy_percentage') ?? 0;

        // Jika sudah 100%, tidak perlu tambah lagi
        if ($currentMax >= 100) {
            return response()->json(['success' => true, 'progress' => 100]);
        }

        // Tambah 1%, max 99% (karena 100% khusus tombol selesai)
        $newProgress = min($currentMax + 1, 99);
        
        // Simpan sesi baru dengan progress +1
        PracticeSession::create([
            'user_id' => $user->id,
            'word' => $word,
            'duration' => 0,
            'accuracy_percentage' => $newProgress,
            'completed_at' => now(),
        ]);

        return response()->json(['success' => true, 'progress' => $newProgress]);
    }
}

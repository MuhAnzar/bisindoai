<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Kuis;
use App\Models\HasilKuis;
use Illuminate\Support\Facades\Auth;

class KuisController extends Controller
{
    public function index()
    {
        $kuis = Kuis::withCount('pertanyaans')->latest()->get();
        return view('kuis.index', compact('kuis'));
    }

    public function riwayat()
    {
        $riwayat = HasilKuis::with('kuis')
                            ->where('pengguna_id', Auth::id())
                            ->latest()
                            ->paginate(10);

        return view('kuis.riwayat', compact('riwayat'));
    }

    public function show($id)
    {
        $kuis = Kuis::withCount('pertanyaans')->findOrFail($id);
        
        // History check
        $riwayat = HasilKuis::where('pengguna_id', Auth::id())
                            ->where('kuis_id', $id)
                            ->latest()
                            ->first();

        $attemptCount = HasilKuis::where('pengguna_id', Auth::id())
                                 ->where('kuis_id', $id)
                                 ->count();
                            
        return view('kuis.show', compact('kuis', 'riwayat', 'attemptCount'));
    }

    public function kerjakan($id)
    {
        // Enforce Limit in 'Start' (GET request)
        $attemptCount = HasilKuis::where('pengguna_id', Auth::id())
                                ->where('kuis_id', $id)
                                ->count();
        
        if ($attemptCount >= 3) {
            return redirect()->route('kuis.show', $id)->with('error', 'Anda telah mencapai batas maksimal 3 kali percobaan.');
        }

        $kuis = Kuis::with(['pertanyaans.opsiJawabans'])->findOrFail($id);
        return view('kuis.kerjakan', compact('kuis'));
    }

    public function submit(Request $request, $id)
    {
        // Enforce Limit in 'Submit' (POST request)
        $attemptCount = HasilKuis::where('pengguna_id', Auth::id())
                                ->where('kuis_id', $id)
                                ->count();
        
        if ($attemptCount >= 3) {
             return redirect()->route('kuis.show', $id)->with('error', 'Anda telah mencapai batas maksimal percobaan.');
        }

        $kuis = Kuis::with(['pertanyaans.opsiJawabans'])->findOrFail($id);
        
        $totalBenar = 0;
        $totalSoal = $kuis->pertanyaans->count();
        
        foreach ($kuis->pertanyaans as $pertanyaan) {
            $jawabanUser = $request->input('jawaban.' . $pertanyaan->id);
            
            // Find correct option
            $correctOption = $pertanyaan->opsiJawabans->where('apakah_benar', true)->first();
            
            if ($correctOption && $jawabanUser == $correctOption->id) {
                $totalBenar++;
            }
        }
        
        $skor = ($totalSoal > 0) ? round(($totalBenar / $totalSoal) * 100) : 0;
        
        // Save Result
        $hasil = new HasilKuis();
        $hasil->pengguna_id = Auth::id();
        $hasil->kuis_id = $id;
        $hasil->skor = $skor;
        $hasil->total_benar = $totalBenar;
        $hasil->save();
        
        return redirect()->route('kuis.hasil', ['id' => $id, 'hasil_id' => $hasil->id]);
    }

    public function hasil($id, $hasil_id)
    {
        $kuis = Kuis::findOrFail($id);
        $hasil = HasilKuis::where('id', $hasil_id)->where('pengguna_id', Auth::id())->firstOrFail();
        
        return view('kuis.hasil', compact('kuis', 'hasil'));
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Kuis;

class KuisAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Kuis::withCount('pertanyaans');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        $kuis = $query->latest()->paginate(10);
        
        $totalKuis = Kuis::count();
        $totalPertanyaan = \App\Models\Pertanyaan::count();
        
        return view('admin.kuis.index', compact('kuis', 'totalKuis', 'totalPertanyaan'));
    }

    public function create()
    {
        return view('admin.kuis.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'gambar_sampul' => 'nullable|image|max:5120',
            'pertanyaan' => 'required|array|min:1',
            'pertanyaan.*' => 'required|string',
            'tipe_media' => 'required|array',
            'media_file' => 'nullable|array',
            'options' => 'required|array',
            'correct_answer' => 'required|array',
        ]);

        try {
            \DB::beginTransaction();

            // 1. Create Kuis
            $kuis = new Kuis();
            $kuis->judul = $request->judul;
            $kuis->deskripsi = $request->deskripsi;
            
            if ($request->hasFile('gambar_sampul')) {
                $path = $request->file('gambar_sampul')->store('uploads/kuis', 'public');
                $kuis->gambar_sampul = 'storage/' . $path;
            }
            $kuis->save();

            // 2. Create Questions
            foreach ($request->pertanyaan as $index => $pertanyaanText) {
                $pertanyaan = new \App\Models\Pertanyaan();
                $pertanyaan->kuis_id = $kuis->id;
                $pertanyaan->pertanyaan = $pertanyaanText;
                $pertanyaan->tipe_media = $request->tipe_media[$index] ?? 'none';

                // Handle Media Upload
                if ($pertanyaan->tipe_media !== 'none' && isset($request->file('media_file')[$index])) {
                    $file = $request->file('media_file')[$index];
                    $path = $file->store('uploads/pertanyaan', 'public');
                    $pertanyaan->media_url = 'storage/' . $path;
                }

                $pertanyaan->save();

                // 3. Create Options
                if (isset($request->options[$index])) {
                    foreach ($request->options[$index] as $optIndex => $optText) {
                        $opsi = new \App\Models\OpsiJawaban();
                        $opsi->pertanyaan_id = $pertanyaan->id;
                        $opsi->jawaban = $optText;
                        // Check if this option index matches the correct answer index for this question
                        $opsi->apakah_benar = ($optIndex == ($request->correct_answer[$index] ?? -1));
                        $opsi->save();
                    }
                }
            }

            \DB::commit();
            return redirect()->route('admin.kuis.index')->with('sukses', 'Kuis berhasil dibuat!');

        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->with('gagal', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }
    public function edit($id)
    {
        $kuis = Kuis::with(['pertanyaans.opsiJawabans'])->findOrFail($id);
        return view('admin.kuis.edit', compact('kuis'));
    }

    public function update(Request $request, $id)
    {
        $kuis = Kuis::findOrFail($id);
        
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'gambar_sampul' => 'nullable|image|max:5120',
            'pertanyaan' => 'required|array|min:1',
        ]);

        try {
            \DB::beginTransaction();

            $kuis->judul = $request->judul;
            $kuis->deskripsi = $request->deskripsi;
            
            if ($request->hasFile('gambar_sampul')) {
                $path = $request->file('gambar_sampul')->store('uploads/kuis', 'public');
                $kuis->gambar_sampul = 'storage/' . $path;
            }
            $kuis->save();

            $kuis->pertanyaans()->delete(); 

            foreach ($request->pertanyaan as $index => $pertanyaanText) {
                $pertanyaan = new \App\Models\Pertanyaan();
                $pertanyaan->kuis_id = $kuis->id;
                $pertanyaan->pertanyaan = $pertanyaanText;
                $pertanyaan->tipe_media = $request->tipe_media[$index] ?? 'none';

                if ($pertanyaan->tipe_media !== 'none' && isset($request->file('media_file')[$index])) {
                    $file = $request->file('media_file')[$index];
                    $path = $file->store('uploads/pertanyaan', 'public');
                    $pertanyaan->media_url = 'storage/' . $path;
                } elseif ($pertanyaan->tipe_media !== 'none' && isset($request->existing_media_url[$index])) {
                    $pertanyaan->media_url = $request->existing_media_url[$index];
                }

                $pertanyaan->save();

                if (isset($request->options[$index])) {
                    foreach ($request->options[$index] as $optIndex => $optText) {
                        $opsi = new \App\Models\OpsiJawaban();
                        $opsi->pertanyaan_id = $pertanyaan->id;
                        $opsi->jawaban = $optText;
                        $opsi->apakah_benar = ($optIndex == ($request->correct_answer[$index] ?? -1));
                        $opsi->save();
                    }
                }
            }

            \DB::commit();
            return redirect()->route('admin.kuis.index')->with('sukses', 'Kuis berhasil diperbarui!');

        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->with('gagal', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        $kuis = Kuis::findOrFail($id);
        $kuis->delete();
        return redirect()->route('admin.kuis.index')->with('sukses', 'Kuis berhasil dihapus!');
    }
}

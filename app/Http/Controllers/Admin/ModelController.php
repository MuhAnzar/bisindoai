<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ModelController extends Controller
{
    /**
     * Menampilkan halaman upload model.
     */
    public function index()
    {
        // Cek keberadaan file saat ini
        $modelExists = Storage::disk('public')->exists('models/best_abjad.keras');
        $labelsExists = Storage::disk('public')->exists('models/class_names.json');

        return view('admin.model.index', compact('modelExists', 'labelsExists'));
    }

    /**
     * Memproses upload model dan label.
     */
    public function update(Request $request)
    {
        $request->validate([
            'model_file' => 'nullable|file|extensions:keras,h5', // Validasi file model
            'labels_file' => 'nullable|file|extensions:json',   // Validasi file label
        ]);

        // Simpan Model
        if ($request->hasFile('model_file')) {
            // Hapus yang lama jika perlu, atau langsung overwrite
            $request->file('model_file')->storeAs('models', 'best_abjad.keras', 'public');
        }

        // Simpan Label
        if ($request->hasFile('labels_file')) {
            $request->file('labels_file')->storeAs('models', 'class_names.json', 'public');
        }

        return redirect()->route('admin.model.index')->with('success', 'File model/label berhasil diperbarui!');
    }
}

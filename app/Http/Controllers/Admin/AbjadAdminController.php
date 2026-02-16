<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Abjad;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AbjadAdminController extends Controller
{
    public function index(): View
    {
        $abjads = Abjad::orderBy('huruf')->get();
        return view('admin.abjad.index', compact('abjads'));
    }

    public function create(): View
    {
        return view('admin.abjad.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'huruf' => 'required|string|size:1|unique:abjads',
            'deskripsi' => 'nullable|string',
            'berkas_video' => [
                'nullable',
                'file',
                'mimes:mp4,mov,ogg,qt,jpg,jpeg,png,gif',
                function ($attribute, $value, $fail) {
                    if ($value) {
                        $extension = strtolower($value->getClientOriginalExtension());
                        $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif']);
                        $maxSize = $isImage ? 5120 : 20480; // 5MB for images, 20MB for videos
                        
                        // getSize returns bytes, converting to KB
                        if (($value->getSize() / 1024) > $maxSize) {
                             $fail("Ukuran berkas tidak boleh lebih dari " . ($maxSize/1024) . "MB.");
                        }
                    }
                },
            ],
        ]);

        if ($request->hasFile('berkas_video')) {
            $file = $request->file('berkas_video');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/abjad'), $filename);
            $validated['berkas_video'] = 'uploads/abjad/' . $filename;
        }

        Abjad::create($validated);

        return redirect()->route('admin.konten')->with('sukses', 'Abjad berhasil ditambahkan');
    }

    public function edit(Abjad $abjad): View
    {
        return view('admin.abjad.edit', compact('abjad'));
    }

    public function update(Request $request, Abjad $abjad)
    {
        $validated = $request->validate([
            'huruf' => 'required|string|size:1|unique:abjads,huruf,' . $abjad->id,
            'deskripsi' => 'nullable|string',
            'berkas_video' => [
                'nullable',
                'file',
                'mimes:mp4,mov,ogg,qt,jpg,jpeg,png,gif',
                function ($attribute, $value, $fail) {
                    if ($value) {
                        $extension = strtolower($value->getClientOriginalExtension());
                        $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif']);
                        $maxSize = $isImage ? 5120 : 20480; // 5MB for images, 20MB for videos
                        
                        if (($value->getSize() / 1024) > $maxSize) {
                             $fail("Ukuran berkas tidak boleh lebih dari " . ($maxSize/1024) . "MB.");
                        }
                    }
                },
            ],
        ]);

        if ($request->hasFile('berkas_video')) {
            // Hapus file lama jika ada
            if ($abjad->berkas_video && file_exists(public_path($abjad->berkas_video))) {
                unlink(public_path($abjad->berkas_video));
            }

            $file = $request->file('berkas_video');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/abjad'), $filename);
            $validated['berkas_video'] = 'uploads/abjad/' . $filename;
        }

        $abjad->update($validated);

        return redirect()->route('admin.konten')->with('sukses', 'Abjad berhasil diperbarui');
    }

    public function destroy(Abjad $abjad)
    {
        if ($abjad->berkas_video && file_exists(public_path($abjad->berkas_video))) {
            unlink(public_path($abjad->berkas_video));
        }
        
        $abjad->delete();
        return redirect()->route('admin.konten')->with('sukses', 'Abjad berhasil dihapus');
    }
}

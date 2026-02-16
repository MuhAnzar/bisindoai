<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KataDasar;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KataDasarAdminController extends Controller
{
    public function index(): View
    {
        $katas = KataDasar::orderBy('kata')->get();
        return view('admin.katadasar.index', compact('katas'));
    }

    public function create(): View
    {
        return view('admin.katadasar.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kata' => 'required|string|max:255|unique:kata_dasars',
            'arti' => 'nullable|string',
            'kategori' => 'nullable|string',
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
            $file = $request->file('berkas_video');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/katadasar'), $filename);
            $validated['berkas_video'] = 'uploads/katadasar/' . $filename;
        }

        KataDasar::create($validated);

        return redirect()->route('admin.konten')->with('sukses', 'Kata dasar berhasil ditambahkan');
    }

    public function edit(KataDasar $katadasar): View
    {
        return view('admin.katadasar.edit', compact('katadasar'));
    }

    public function update(Request $request, KataDasar $katadasar)
    {
        $validated = $request->validate([
            'kata' => 'required|string|max:255|unique:kata_dasars,kata,' . $katadasar->id,
            'arti' => 'nullable|string',
            'kategori' => 'nullable|string',
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
            if ($katadasar->berkas_video && file_exists(public_path($katadasar->berkas_video))) {
                unlink(public_path($katadasar->berkas_video));
            }

            $file = $request->file('berkas_video');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/katadasar'), $filename);
            $validated['berkas_video'] = 'uploads/katadasar/' . $filename;
        }

        $katadasar->update($validated);

        return redirect()->route('admin.konten')->with('sukses', 'Kata dasar berhasil diperbarui');
    }

    public function destroy(KataDasar $katadasar)
    {
        if ($katadasar->berkas_video && file_exists(public_path($katadasar->berkas_video))) {
            unlink(public_path($katadasar->berkas_video));
        }

        $katadasar->delete();
        return redirect()->route('admin.konten')->with('sukses', 'Kata dasar berhasil dihapus');
    }
}

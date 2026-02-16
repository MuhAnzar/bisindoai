@extends('layouts.admin')

@section('judul', 'Edit Kuis')
@section('deskripsi', 'Perbarui data kuis dan pertanyaan.')

@section('navigasi')
<a href="{{ route('admin.kuis.index') }}" class="btn bg-white border border-slate-300 text-slate-600 hover:bg-slate-50">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
    Kembali
</a>
@endsection

@section('konten')
<form action="{{ route('admin.kuis.update', $kuis->id) }}" method="POST" enctype="multipart/form-data" id="quizForm">
    @csrf
    @method('PUT')
    
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        <!-- Sidebar: Info Kuis -->
        <div class="xl:col-span-1 space-y-6">
            <div class="card sticky top-24">
                <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                    <span class="p-2 bg-teal-100 text-teal-700 rounded-lg">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    </span>
                    Informasi Kuis
                </h3>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Judul Kuis</label>
                        <input type="text" name="judul" value="{{ old('judul', $kuis->judul) }}" required class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-teal-100 focus:border-teal-500 outline-none transition-all placeholder:text-slate-400">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Deskripsi</label>
                        <textarea name="deskripsi" rows="4" required class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-teal-100 focus:border-teal-500 outline-none transition-all placeholder:text-slate-400">{{ old('deskripsi', $kuis->deskripsi) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Sampul</label>
                        <div class="relative group cursor-pointer overflow-hidden rounded-xl border-2 border-dashed border-slate-300 hover:border-teal-500 transition-colors bg-slate-50 min-h-[160px] flex flex-col items-center justify-center text-center p-4">
                            <input type="file" name="gambar_sampul" accept="image/*" onchange="previewImage(this, 'cover-preview')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            
                            <img id="cover-preview" src="{{ $kuis->gambar_sampul ? asset($kuis->gambar_sampul) : '#' }}" class="{{ $kuis->gambar_sampul ? '' : 'hidden' }} absolute inset-0 w-full h-full object-cover">
                            
                            <div class="group-hover:scale-110 transition-transform duration-200 {{ $kuis->gambar_sampul ? 'opacity-0 group-hover:opacity-100 z-20 bg-white/80 p-2 rounded-lg backdrop-blur-sm' : '' }}">
                                <div class="w-10 h-10 bg-white rounded-full shadow-sm flex items-center justify-center mx-auto mb-2 text-slate-400 group-hover:text-teal-600">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                                </div>
                                <p class="text-sm font-medium text-slate-600">Klik untuk ganti</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-6 mt-6 border-t border-slate-100">
                    <button type="submit" class="w-full bg-teal-600 hover:bg-teal-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-teal-600/20 transition-all transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                        Perbarui Kuis
                    </button>
                </div>
            </div>
        </div>

        <!-- Main Content: Questions -->
        <div class="xl:col-span-2 space-y-6">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                    <span class="p-2 bg-indigo-100 text-indigo-700 rounded-lg">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                    </span>
                    Daftar Pertanyaan
                </h3>
                <button type="button" onclick="addQuestion()" class="btn bg-indigo-600 hover:bg-indigo-700 text-white shadow-md shadow-indigo-600/20">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    Tambah Soal
                </button>
            </div>

            <div id="questions-container" class="space-y-4">
                @foreach($kuis->pertanyaans as $index => $pertanyaan)
                <div class="question-item bg-white rounded-xl border border-slate-200 shadow-sm transition-all hover:shadow-md overflow-hidden">
                    <!-- Header -->
                    <div class="p-4 flex items-start gap-4 cursor-pointer hover:bg-slate-50 transition-colors" onclick="toggleAccordion(this)">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-slate-100 text-slate-600 font-bold flex items-center justify-center text-sm border border-slate-200 mt-0.5 question-number">{{ $index + 1 }}</div>
                        <div class="flex-grow min-w-0">
                            <div class="flex items-center justify-between mb-1">
                                <h4 class="font-semibold text-slate-800 truncate pr-4 question-preview-text text-base">{{ $pertanyaan->pertanyaan }}</h4>
                                <div class="flex items-center gap-2 flex-shrink-0">
                                    <button type="button" onclick="removeQuestion(this); event.stopPropagation();" class="p-1.5 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>
                                    </button>
                                    <div class="p-1.5 text-slate-400 accordion-icon transform transition-transform duration-300 {{ $loop->first ? 'rotate-180' : '' }}">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
                                    </div>
                                </div>
                            </div>
                            <p class="text-xs text-slate-500 question-type-preview">Teks • {{ $pertanyaan->opsiJawabans->count() }} Pilihan</p>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="accordion-content {{ $loop->first ? '' : 'hidden' }} border-t border-slate-100 p-5 bg-slate-50/30">
                        <div class="space-y-6">
                            <!-- Question Input -->
                            <div>
                                <label class="block text-xs uppercase tracking-wider font-bold text-slate-500 mb-2">Pertanyaan</label>
                                <textarea name="pertanyaan[{{ $index }}]" required rows="2" 
                                    oninput="updatePreview(this)"
                                    class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 outline-none transition-all placeholder:text-slate-400 text-base" 
                                    placeholder="Tulis pertanyaan Anda di sini...">{{ $pertanyaan->pertanyaan }}</textarea>
                            </div>

                            <!-- Media Selection -->
                            <div>
                                <label class="block text-xs uppercase tracking-wider font-bold text-slate-500 mb-3">Media (Opsional)</label>
                                <div class="flex flex-wrap gap-4">
                                    <label class="relative cursor-pointer group">
                                        <input type="radio" name="tipe_media[{{ $index }}]" value="none" {{ $pertanyaan->tipe_media == 'none' ? 'checked' : '' }} class="peer sr-only" onchange="toggleMediaInput(this)">
                                        <div class="px-4 py-2 rounded-lg border border-slate-200 bg-white text-slate-600 peer-checked:bg-slate-800 peer-checked:text-white peer-checked:border-slate-800 transition-all flex items-center gap-2 shadow-sm hover:shadow-md">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"></line></svg>
                                            <span class="text-sm font-medium">Tanpa Media</span>
                                        </div>
                                    </label>
                                    <label class="relative cursor-pointer group">
                                        <input type="radio" name="tipe_media[{{ $index }}]" value="image" {{ $pertanyaan->tipe_media == 'image' ? 'checked' : '' }} class="peer sr-only" onchange="toggleMediaInput(this)">
                                        <div class="px-4 py-2 rounded-lg border border-slate-200 bg-white text-slate-600 peer-checked:bg-indigo-600 peer-checked:text-white peer-checked:border-indigo-600 transition-all flex items-center gap-2 shadow-sm hover:shadow-md">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                                            <span class="text-sm font-medium">Gambar</span>
                                        </div>
                                    </label>
                                    <label class="relative cursor-pointer group">
                                        <input type="radio" name="tipe_media[{{ $index }}]" value="video" {{ $pertanyaan->tipe_media == 'video' ? 'checked' : '' }} class="peer sr-only" onchange="toggleMediaInput(this)">
                                        <div class="px-4 py-2 rounded-lg border border-slate-200 bg-white text-slate-600 peer-checked:bg-pink-600 peer-checked:text-white peer-checked:border-pink-600 transition-all flex items-center gap-2 shadow-sm hover:shadow-md">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="23 7 16 12 23 17 23 7"></polygon><rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect></svg>
                                            <span class="text-sm font-medium">Video</span>
                                        </div>
                                    </label>
                                </div>

                                <!-- File Upload Area -->
                                <div class="media-input {{ $pertanyaan->tipe_media == 'none' ? 'hidden' : '' }} mt-4">
                                    <div class="relative rounded-xl border-2 border-dashed border-slate-300 bg-white p-6 flex flex-col items-center justify-center text-center hover:bg-slate-50 transition-colors">
                                        <input type="file" name="media_file[{{ $index }}]" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" onchange="previewMedia(this)">
                                        <input type="hidden" name="existing_media_url[{{ $index }}]" value="{{ $pertanyaan->media_url }}">
                                        
                                        <div class="media-preview-area w-full h-48 {{ $pertanyaan->media_url ? '' : 'hidden' }} flex items-center justify-center bg-slate-100 rounded-lg mb-0 relative overflow-hidden">
                                            @if($pertanyaan->media_url)
                                                @if($pertanyaan->tipe_media == 'image')
                                                    <img src="{{ asset($pertanyaan->media_url) }}" class="img-preview max-w-full max-h-full object-contain">
                                                    <video src="" class="video-preview max-w-full max-h-full hidden" controls></video>
                                                @elseif($pertanyaan->tipe_media == 'video')
                                                    <video src="{{ asset($pertanyaan->media_url) }}" class="video-preview max-w-full max-h-full" controls></video>
                                                    <img src="" class="img-preview max-w-full max-h-full object-contain hidden">
                                                @endif
                                            @else
                                                <img src="" class="img-preview max-w-full max-h-full object-contain hidden">
                                                <video src="" class="video-preview max-w-full max-h-full hidden" controls></video>
                                            @endif
                                            <button type="button" class="absolute top-2 right-2 bg-black/50 hover:bg-black/70 text-white p-1 rounded-full z-20 transition-colors" onclick="clearMedia(this); event.preventDefault();">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>
                                            </button>
                                        </div>
                                        
                                        <div class="upload-placeholder {{ $pertanyaan->media_url ? 'hidden' : '' }} pointer-events-none">
                                            <div class="w-10 h-10 bg-indigo-50 text-indigo-500 rounded-full flex items-center justify-center mx-auto mb-2">
                                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                                            </div>
                                            <p class="text-sm font-medium text-slate-700">Upload File Media</p>
                                            <p class="text-xs text-slate-400 mt-1">Klik atau drag file ke sini</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Answers Grid -->
                            <div>
                                <label class="block text-xs uppercase tracking-wider font-bold text-slate-500 mb-3 flex justify-between items-center">
                                    Pilihan Jawaban
                                    <span class="text-[10px] bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-full normal-case font-semibold">Pilih kunci jawaban yang benar</span>
                                </label>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    @foreach($pertanyaan->opsiJawabans as $optIndex => $opsi)
                                    <div class="flex items-stretch group/opt">
                                        <div class="flex items-center justify-center w-12 bg-slate-100 border border-slate-300 border-r-0 rounded-l-lg text-slate-500 font-bold flex-shrink-0">
                                            {{ chr(65 + $optIndex) }}
                                        </div>
                                        <div class="relative flex-grow">
                                            <input type="text" name="options[{{ $index }}][]" value="{{ $opsi->jawaban }}" required class="w-full px-4 pr-12 py-3 border border-slate-300 rounded-r-lg focus:ring-2 focus:ring-teal-100 focus:border-teal-500 outline-none transition-all" placeholder="Jawaban {{ chr(65 + $optIndex) }}">
                                            <div class="absolute right-2 top-1/2 -translate-y-1/2">
                                                <label class="cursor-pointer">
                                                    <input type="radio" name="correct_answer[{{ $index }}]" value="{{ $optIndex }}" {{ $opsi->apakah_benar ? 'checked' : '' }} required class="peer sr-only">
                                                    <div class="w-6 h-6 rounded-full border-2 border-slate-300 peer-checked:border-teal-500 peer-checked:bg-teal-500 text-white flex items-center justify-center transition-all hover:border-teal-400">
                                                        <svg class="w-3.5 h-3.5 opacity-0 peer-checked:opacity-100" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Empty State -->
            <div id="empty-questions-state" class="{{ $kuis->pertanyaans->isEmpty() ? 'flex' : 'hidden' }} flex-col items-center justify-center py-12 px-4 rounded-2xl border-2 border-dashed border-slate-300 text-slate-500 bg-slate-50/50">
                <div class="w-16 h-16 bg-white rounded-full shadow-sm flex items-center justify-center mb-4">
                    <svg class="text-slate-400" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><path d="M16 16s-1.5-2-4-2-4 2-4 2"></path><line x1="9" y1="9" x2="9.01" y2="9"></line><line x1="15" y1="9" x2="15.01" y2="9"></line></svg>
                </div>
                <h4 class="text-lg font-semibold text-slate-700">Belum ada pertanyaan</h4>
                <p class="text-sm text-slate-500 mb-4">Mulai tambahkan pertanyaan untuk kuis ini.</p>
                <button type="button" onclick="addQuestion()" class="text-indigo-600 font-medium hover:underline">Tambah Pertanyaan Pertama</button>
            </div>
        </div>
    </div>
</form>

<template id="question-template">
    <!-- Identical template to create.blade.php -->
     <div class="question-item bg-white rounded-xl border border-slate-200 shadow-sm transition-all hover:shadow-md overflow-hidden">
        <!-- Header -->
        <div class="p-4 flex items-start gap-4 cursor-pointer hover:bg-slate-50 transition-colors" onclick="toggleAccordion(this)">
            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-slate-100 text-slate-600 font-bold flex items-center justify-center text-sm border border-slate-200 mt-0.5 question-number">1</div>
            <div class="flex-grow min-w-0">
                <div class="flex items-center justify-between mb-1">
                    <h4 class="font-semibold text-slate-800 truncate pr-4 question-preview-text text-base">Pertanyaan Baru</h4>
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <button type="button" onclick="removeQuestion(this); event.stopPropagation();" class="p-1.5 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>
                        </button>
                        <div class="p-1.5 text-slate-400 accordion-icon transform transition-transform duration-300">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
                        </div>
                    </div>
                </div>
                <p class="text-xs text-slate-500 question-type-preview">Teks • 4 Pilihan</p>
            </div>
        </div>

        <!-- Content -->
        <div class="accordion-content hidden border-t border-slate-100 p-5 bg-slate-50/30">
            <div class="space-y-6">
                <!-- Question Input -->
                <div>
                    <label class="block text-xs uppercase tracking-wider font-bold text-slate-500 mb-2">Pertanyaan</label>
                    <textarea name="pertanyaan[INDEX]" required rows="2" 
                        oninput="updatePreview(this)"
                        class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 outline-none transition-all placeholder:text-slate-400 text-base" 
                        placeholder="Tulis pertanyaan Anda di sini..."></textarea>
                </div>

                <!-- Media Selection -->
                <div>
                    <label class="block text-xs uppercase tracking-wider font-bold text-slate-500 mb-3">Media (Opsional)</label>
                    <div class="flex flex-wrap gap-4">
                        <label class="relative cursor-pointer group">
                            <input type="radio" name="tipe_media[INDEX]" value="none" checked class="peer sr-only" onchange="toggleMediaInput(this)">
                            <div class="px-4 py-2 rounded-lg border border-slate-200 bg-white text-slate-600 peer-checked:bg-slate-800 peer-checked:text-white peer-checked:border-slate-800 transition-all flex items-center gap-2 shadow-sm hover:shadow-md">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"></line></svg>
                                <span class="text-sm font-medium">Tanpa Media</span>
                            </div>
                        </label>
                        <label class="relative cursor-pointer group">
                            <input type="radio" name="tipe_media[INDEX]" value="image" class="peer sr-only" onchange="toggleMediaInput(this)">
                            <div class="px-4 py-2 rounded-lg border border-slate-200 bg-white text-slate-600 peer-checked:bg-indigo-600 peer-checked:text-white peer-checked:border-indigo-600 transition-all flex items-center gap-2 shadow-sm hover:shadow-md">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                                <span class="text-sm font-medium">Gambar</span>
                            </div>
                        </label>
                        <label class="relative cursor-pointer group">
                            <input type="radio" name="tipe_media[INDEX]" value="video" class="peer sr-only" onchange="toggleMediaInput(this)">
                            <div class="px-4 py-2 rounded-lg border border-slate-200 bg-white text-slate-600 peer-checked:bg-pink-600 peer-checked:text-white peer-checked:border-pink-600 transition-all flex items-center gap-2 shadow-sm hover:shadow-md">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="23 7 16 12 23 17 23 7"></polygon><rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect></svg>
                                <span class="text-sm font-medium">Video</span>
                            </div>
                        </label>
                    </div>

                    <!-- File Upload Area -->
                    <div class="media-input hidden mt-4">
                        <div class="relative rounded-xl border-2 border-dashed border-slate-300 bg-white p-6 flex flex-col items-center justify-center text-center hover:bg-slate-50 transition-colors">
                            <input type="file" name="media_file[INDEX]" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" onchange="previewMedia(this)">
                            <div class="media-preview-area w-full h-48 hidden flex items-center justify-center bg-slate-100 rounded-lg mb-0 relative overflow-hidden">
                                <img src="" class="img-preview max-w-full max-h-full object-contain">
                                <video src="" class="video-preview max-w-full max-h-full" controls></video>
                                <button type="button" class="absolute top-2 right-2 bg-black/50 hover:bg-black/70 text-white p-1 rounded-full z-20 transition-colors" onclick="clearMedia(this); event.preventDefault();">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>
                                </button>
                            </div>
                            <div class="upload-placeholder pointer-events-none">
                                <div class="w-10 h-10 bg-indigo-50 text-indigo-500 rounded-full flex items-center justify-center mx-auto mb-2">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                                </div>
                                <p class="text-sm font-medium text-slate-700">Upload File Media</p>
                                <p class="text-xs text-slate-400 mt-1">Klik atau drag file ke sini</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Answers Grid -->
                <div>
                    <label class="block text-xs uppercase tracking-wider font-bold text-slate-500 mb-3 flex justify-between items-center">
                        Pilihan Jawaban
                        <span class="text-[10px] bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-full normal-case font-semibold">Pilih kunci jawaban yang benar</span>
                    </label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Options loop... -->
                        <!-- Template uses placeholders loop for new ones, but dynamic for static -->
                        @foreach(['A','B','C','D'] as $key => $label)
                        <div class="flex items-stretch group/opt">
                            <div class="flex items-center justify-center w-12 bg-slate-100 border border-slate-300 border-r-0 rounded-l-lg text-slate-500 font-bold flex-shrink-0">
                                {{ $label }}
                            </div>
                            <div class="relative flex-grow">
                                <input type="text" name="options[INDEX][]" required class="w-full px-4 pr-12 py-3 border border-slate-300 rounded-r-lg focus:ring-2 focus:ring-teal-100 focus:border-teal-500 outline-none transition-all" placeholder="Jawaban {{ $label }}">
                                <div class="absolute right-2 top-1/2 -translate-y-1/2">
                                    <label class="cursor-pointer">
                                        <input type="radio" name="correct_answer[INDEX]" value="{{ $key }}" required class="peer sr-only">
                                        <div class="w-6 h-6 rounded-full border-2 border-slate-300 peer-checked:border-teal-500 peer-checked:bg-teal-500 text-white flex items-center justify-center transition-all hover:border-teal-400">
                                            <svg class="w-3.5 h-3.5 opacity-0 peer-checked:opacity-100" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    let questionCount = {{ $kuis->pertanyaans->count() }};
    
    // Copy the scripts from create.blade.php but update addQuestion slightly if needed
    // Actually the scripts are identical in logic, just need to ensure correct initialization.
    
    function addQuestion() {
        document.getElementById('empty-questions-state').classList.replace('flex', 'hidden');
        
        const template = document.getElementById('question-template');
        const container = document.getElementById('questions-container');
        const clone = template.content.cloneNode(true);
        
        const html = clone.querySelector('.question-item').outerHTML
            .replace(/INDEX/g, questionCount)
            .replace(/name="tipe_media\[(\d+)\]"/g, `name="tipe_media[${questionCount}]"`) 
            .replace(/name="correct_answer\[(\d+)\]"/g, `name="correct_answer[${questionCount}]"`);
        
        const div = document.createElement('div');
        div.innerHTML = html;
        const correctHtml = div.firstElementChild;
        correctHtml.querySelector('.question-number').textContent = questionCount + 1;
        
        document.querySelectorAll('.accordion-content').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('.accordion-icon').forEach(el => el.style.transform = 'rotate(0deg)');
        
        correctHtml.querySelector('.accordion-content').classList.remove('hidden');
        correctHtml.querySelector('.accordion-icon').style.transform = 'rotate(180deg)';

        container.appendChild(correctHtml);
        correctHtml.scrollIntoView({ behavior: 'smooth', block: 'center' });
        
        questionCount++;
    }

    // Include other functions (remove, toggle, preview, etc) as in create.blade.php
    function removeQuestion(btn) {
        if(confirm('Hapus pertanyaan ini?')) {
            btn.closest('.question-item').remove();
            renumberQuestions();
            if(document.getElementById('questions-container').children.length === 0) {
                document.getElementById('empty-questions-state').classList.replace('hidden', 'flex');
            }
        }
    }

    function renumberQuestions() {
        document.querySelectorAll('.question-item').forEach((item, index) => {
            item.querySelector('.question-number').textContent = index + 1;
        });
    }

    function toggleAccordion(header) {
        const content = header.nextElementSibling;
        const icon = header.querySelector('.accordion-icon');
        const isHidden = content.classList.contains('hidden');
        
        document.querySelectorAll('.accordion-content').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('.accordion-icon').forEach(el => el.style.transform = 'rotate(0deg)');
        
        if (isHidden) {
            content.classList.remove('hidden');
            icon.style.transform = 'rotate(180deg)';
        }
    }

    function updatePreview(input) {
        const text = input.value.trim() || 'Pertanyaan Baru';
        input.closest('.question-item').querySelector('.question-preview-text').textContent = text;
    }

    function toggleMediaInput(radio) {
        const wrapper = radio.closest('.flex-wrap').nextElementSibling;
        const input = wrapper.querySelector('input[type="file"]');
        const previewArea = wrapper.querySelector('.media-preview-area');
        const placeholder = wrapper.querySelector('.upload-placeholder');
        
        if (radio.value === 'none') {
            wrapper.classList.add('hidden');
            input.value = '';
            previewArea.classList.add('hidden');
            placeholder.classList.remove('hidden');
        } else {
            wrapper.classList.remove('hidden');
            input.accept = radio.value === 'video' ? 'video/*' : 'image/*';
            previewArea.classList.add('hidden');
            placeholder.classList.remove('hidden');
            input.value = '';
        }
    }

    function previewMedia(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const wrapper = input.parentElement;
            const previewArea = wrapper.querySelector('.media-preview-area');
            const placeholder = wrapper.querySelector('.upload-placeholder');
            const img = previewArea.querySelector('.img-preview');
            const vid = previewArea.querySelector('.video-preview');

            const reader = new FileReader();
            reader.onload = function(e) {
                placeholder.classList.add('hidden');
                previewArea.classList.remove('hidden');
                
                if (file.type.startsWith('image/')) {
                    img.src = e.target.result;
                    img.classList.remove('hidden');
                    vid.classList.add('hidden');
                } else if (file.type.startsWith('video/')) {
                    vid.src = e.target.result;
                    vid.classList.remove('hidden');
                    img.classList.add('hidden');
                }
            }
            reader.readAsDataURL(file);
        }
    }
    
    function clearMedia(btn) {
        const wrapper = btn.closest('.relative');
        const input = wrapper.querySelector('input[type="file"]');
        input.value = '';
        
        const previewArea = wrapper.querySelector('.media-preview-area');
        const placeholder = wrapper.querySelector('.upload-placeholder');
        
        previewArea.classList.add('hidden');
        placeholder.classList.remove('hidden');
    }

    function previewImage(input, imgId) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById(imgId);
                img.src = e.target.result;
                img.classList.remove('hidden');
                
                // Hide the placeholder div (sibling after image)
                const placeholder = input.nextElementSibling.nextElementSibling;
                if (placeholder) {
                    placeholder.classList.add('hidden');
                    // Remove opacity-0 class if it exists (for edit page specific style)
                    placeholder.classList.remove('opacity-0', 'group-hover:opacity-100'); 
                }
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection

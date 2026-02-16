@extends('komponen.tata_letak')

@section('judul', 'Hasil Kuis')

@section('konten')
<style>
    .navbar-wrapper, footer { display: none !important; }
</style>
<div class="min-h-screen bg-slate-50 pb-20 relative">
    <!-- Decorative Background -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none z-0">
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-teal-400/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/4"></div>
        <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-blue-400/5 rounded-full blur-3xl translate-y-1/3 -translate-x-1/4"></div>
    </div>

    <!-- Sticky Header -->
    <div class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-slate-200 shadow-sm transition-all">
        <div class="max-w-xl mx-auto px-4 py-3 flex justify-between items-center">
            <div class="flex items-center gap-4">
                <a href="{{ route('kuis.index') }}" class="p-2 rounded-full hover:bg-slate-100 text-slate-500 transition-colors" title="Kembali ke Daftar Kuis">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                </a>
                <h1 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Hasil Kuis</h1>
            </div>
            <div class="text-xs font-bold text-slate-400 uppercase tracking-widest">{{ now()->format('d M Y') }}</div>
        </div>
    </div>

    <div class="max-w-xl w-full px-4 mx-auto mt-8 relative z-10">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xl overflow-hidden relative">
            <!-- Confetti Background Effect -->
            <div class="absolute inset-0 overflow-hidden pointer-events-none">
                <div class="absolute top-0 left-1/4 w-2 h-2 bg-red-400 rounded-full animate-ping opacity-75"></div>
                <div class="absolute top-10 right-1/4 w-3 h-3 bg-yellow-400 rounded-full animate-bounce opacity-75"></div>
                <div class="absolute bottom-1/4 left-10 w-2 h-2 bg-blue-400 rounded-full animate-pulse opacity-75"></div>
            </div>

            <div class="p-6 md:p-8 text-center relative z-10">
                <!-- Result Header -->
                <div class="mb-6">
                    <h2 class="text-slate-400 font-medium text-xs mb-1">Anda telah menyelesaikan</h2>
                    <h1 class="text-2xl md:text-3xl font-bold text-slate-800">{{ $kuis->judul }}</h1>
                </div>

                <!-- Circular Score Chart -->
                <div class="relative w-40 h-40 mx-auto mb-6">
                    <svg class="w-full h-full transform -rotate-90">
                        <!-- Background Circle -->
                        <circle cx="80" cy="80" r="72" stroke="currentColor" stroke-width="10" fill="none" class="text-slate-100" />
                        <!-- Progress Circle -->
                        <circle cx="80" cy="80" r="72" stroke="currentColor" stroke-width="10" fill="none" 
                            stroke-dasharray="452" 
                            stroke-dashoffset="{{ 452 - (452 * $hasil->skor) / 100 }}"
                            class="{{ $hasil->skor >= 70 ? 'text-teal-500' : 'text-orange-500' }} transition-all duration-1000 ease-out" />
                    </svg>
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <span class="text-4xl font-black text-slate-800">{{ $hasil->skor }}</span>
                        <span class="text-xs font-medium text-slate-400 uppercase mt-0.5">Nilai</span>
                    </div>
                </div>

                <!-- Feedback Message -->
                <div class="mb-8 p-4 bg-slate-50 rounded-xl border border-slate-100">
                    @if($hasil->skor >= 90)
                        <div class="font-bold text-teal-700 text-lg mb-1">🏆 Sempurna!</div>
                        <p class="text-slate-600 text-sm">Luar biasa! Penguasaan materi Anda sangat baik.</p>
                    @elseif($hasil->skor >= 70)
                        <div class="font-bold text-blue-700 text-lg mb-1">🎉 Bagus Sekali!</div>
                        <p class="text-slate-600 text-sm">Kerja bagus! Anda sudah memahami materi.</p>
                    @else
                        <div class="font-bold text-orange-700 text-lg mb-1">💪 Tetap Semangat!</div>
                        <p class="text-slate-600 text-sm">Jangan menyerah! Pelajari lagi dan coba kembali.</p>
                    @endif
                </div>

                <!-- Stats Grid -->
                <div class="grid grid-cols-2 gap-3 mb-8">
                    <div class="bg-teal-50 p-3 rounded-xl border border-teal-100">
                        <div class="text-teal-600/70 text-[10px] font-bold uppercase mb-0.5">Jawaban Benar</div>
                        <div class="text-xl font-bold text-teal-700">{{ $hasil->total_benar }}</div>
                    </div>
                    <div class="bg-red-50 p-3 rounded-xl border border-red-100">
                        <div class="text-red-500/70 text-[10px] font-bold uppercase mb-0.5">Jawaban Salah</div>
                        <div class="text-xl font-bold text-red-600">{{ $kuis->pertanyaans->count() - $hasil->total_benar }}</div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex flex-col gap-3">
                    <a href="{{ route('kuis.kerjakan', $kuis->id) }}" class="w-full py-3 bg-slate-800 hover:bg-slate-900 text-white rounded-xl font-bold transition-all flex items-center justify-center gap-2 shadow-lg shadow-slate-800/10">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 4v6h-6"></path><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"></path></svg>
                        Coba Lagi
                    </a>
                    <a href="{{ route('kuis.index') }}" class="w-full py-3 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-xl font-bold transition-all flex items-center justify-center gap-2">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                        Daftar Kuis
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

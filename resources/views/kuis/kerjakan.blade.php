@extends('komponen.tata_letak')

@section('judul', 'Mengerjakan Kuis: ' . $kuis->judul)

@section('konten')

<style>
    .navbar-wrapper, footer { display: none !important; }
</style>

<div class="min-h-screen bg-slate-50/50 pb-20 relative">
    <!-- Decorative Background -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none z-0">
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-teal-400/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/4"></div>
        <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-blue-400/5 rounded-full blur-3xl translate-y-1/3 -translate-x-1/4"></div>
    </div>

    <!-- Sticky Progress Header (Now Main Header) -->
    <div class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-slate-200 shadow-sm transition-all">
        <div class="max-w-4xl mx-auto px-4 py-3">
            <div class="flex justify-between items-center mb-3">
                <div class="flex items-center gap-4">
                    <a href="{{ route('kuis.show', $kuis->id) }}" class="p-2 rounded-full hover:bg-slate-100 text-slate-500 transition-colors" title="Keluar">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                    </a>
                    <div>
                        <h1 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-0.5">Sedang Mengerjakan</h1>
                        <h2 class="text-lg font-black text-slate-800 leading-none truncate max-w-[200px] md:max-w-md">{{ $kuis->judul }}</h2>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Progress</div>
                    <div class="text-xl font-black text-teal-600 leading-none">
                        <span id="answered-count">0</span><span class="text-slate-300 text-base font-bold mx-1">/</span><span class="text-slate-500 text-base font-bold">{{ $kuis->pertanyaans->count() }}</span>
                    </div>
                </div>
            </div>
            <div class="h-2.5 bg-slate-100 rounded-full overflow-hidden shadow-inner">
                <div class="h-full bg-gradient-to-r from-teal-400 to-teal-600 w-0 transition-all duration-500 ease-out relative" id="progress-bar">
                    <div class="absolute inset-0 bg-white/30 animate-[pulse_2s_infinite]"></div>
                    <div class="absolute right-0 top-0 bottom-0 w-1 bg-white/50 shadow-[0_0_10px_rgba(255,255,255,0.8)]"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 py-8 relative z-10">
        <form action="{{ route('kuis.submit', $kuis->id) }}" method="POST" id="quiz-form">
            @csrf
            
            <div class="space-y-6">
                @foreach($kuis->pertanyaans as $index => $pertanyaan)
                <div class="question-card scroll-mt-24 group" id="question-{{ $index }}">
                    
                    <!-- Question Wrapper -->
                    <div class="bg-white rounded-[1.5rem] p-5 md:p-8 border border-slate-100 shadow-lg hover:shadow-xl transition-shadow duration-300">
                        
                        <!-- Header with Number -->
                        <div class="flex items-start gap-4 mb-4">
                            <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-gradient-to-br from-teal-500 to-emerald-600 text-white flex items-center justify-center font-bold text-lg shadow-md transform group-hover:scale-110 transition-transform duration-300">
                                {{ $index + 1 }}
                            </div>
                            <div class="flex-grow pt-1">
                                <h3 class="text-lg md:text-xl font-bold text-slate-800 leading-snug">{{ $pertanyaan->pertanyaan }}</h3>
                            </div>
                        </div>

                        <!-- Media Area -->
                        @if($pertanyaan->tipe_media !== 'none' && $pertanyaan->media_url)
                            <div class="mb-6 rounded-xl overflow-hidden bg-slate-50 border border-slate-100 relative group/media max-w-2xl mx-auto">
                                @if($pertanyaan->tipe_media == 'image')
                                    <div class="aspect-video md:aspect-[21/9] flex items-center justify-center bg-slate-900/5">
                                        <img src="{{ asset($pertanyaan->media_url) }}" alt="Media Pertanyaan" class="w-full h-full object-contain mix-blend-multiply hover:mix-blend-normal transition-all duration-300">
                                    </div>
                                @elseif($pertanyaan->tipe_media == 'video')
                                    <div class="aspect-video">
                                        <video src="{{ asset($pertanyaan->media_url) }}" controls class="w-full h-full object-cover"></video>
                                    </div>
                                @endif
                            </div>
                        @endif

                        <!-- Options Grid -->
                        <div class="grid gap-3 md:grid-cols-1">
                            @foreach($pertanyaan->opsiJawabans as $opsi)
                            <label class="relative cursor-pointer group/option">
                                <input type="radio" name="jawaban[{{ $pertanyaan->id }}]" value="{{ $opsi->id }}" required class="peer sr-only" onchange="updateProgress()">
                                
                                <div class="p-3 md:p-4 rounded-xl border border-slate-200 bg-slate-50/50 hover:bg-teal-50 hover:border-teal-300 transition-all duration-200 peer-checked:border-teal-600 peer-checked:bg-teal-50 peer-checked:shadow-sm flex items-center gap-3">
                                    <!-- Radio Indicator -->
                                    <div class="w-5 h-5 rounded-full border border-slate-300 bg-white peer-checked:border-teal-600 peer-checked:bg-teal-600 flex items-center justify-center transition-all flex-shrink-0">
                                        <svg class="w-3 h-3 text-white opacity-0 peer-checked:opacity-100 transform scale-50 peer-checked:scale-100 transition-all duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                    
                                    <!-- Text -->
                                    <span class="text-sm md:text-base text-slate-600 font-medium peer-checked:font-bold peer-checked:text-teal-900">{{ $opsi->jawaban }}</span>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Submit Button Area -->
            <div class="mt-8 pt-6 border-t border-slate-200/60 flex flex-col items-center justify-center text-center">
                <p class="text-slate-500 mb-6 font-medium">Sudah menjawab semua pertanyaan?</p>
                <button type="submit" class="group relative w-full md:w-auto bg-slate-900 hover:bg-teal-600 text-white px-12 py-5 rounded-2xl font-bold text-xl shadow-2xl shadow-slate-900/20 hover:shadow-teal-600/30 transition-all duration-300 transform hover:-translate-y-1 flex items-center justify-center gap-4 overflow-hidden">
                    <span class="relative z-10">Kirim Jawaban Saya</span>
                    <svg class="w-6 h-6 relative z-10 transform group-hover:translate-x-1 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                    
                    <!-- Hover Effect Bg -->
                    <div class="absolute inset-0 bg-gradient-to-r from-teal-500 to-emerald-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                </button>
            </div>
        </form>
    </div>
</div>

@push('skrip')
<script>
    function updateProgress() {
        const total = {{ $kuis->pertanyaans->count() }};
        const answered = document.querySelectorAll('input[type="radio"]:checked').length;
        const percent = (answered / total) * 100;
        
        const progressBar = document.getElementById('progress-bar');
        progressBar.style.width = percent + '%';
        
        // Color shift based on completion
        if(percent >= 100) {
            progressBar.classList.remove('from-teal-400', 'to-teal-600');
            progressBar.classList.add('from-emerald-400', 'to-emerald-600');
        }

        document.getElementById('answered-count').textContent = answered;
    }
</script>
@endpush
@endsection

@extends('komponen.tata_letak') 

@section('judul', 'Latihan Deteksi')
@section('deskripsi', 'Tingkatkan kemampuan bahasa isyarat dengan sistem pembelajaran berbasis AI real-time.')

@push('gaya')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; }
    
    .gradient-bg {
        background: linear-gradient(135deg, #0f766e 0%, #0d9488 50%, #14b8a6 100%);
    }
    
    .gradient-text {
        background: linear-gradient(135deg, #0f766e 0%, #0d9488 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .card-hover {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .card-hover:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 25px 50px -12px rgb(0 0 0 / 0.25);
    }

    .input-glow:focus {
        box-shadow: 0 0 0 4px rgba(13, 148, 136, 0.15), 0 0 20px rgba(13, 148, 136, 0.1);
        border-color: #0d9488;
    }

    @keyframes popIn {
        0% { transform: scale(0.3) rotate(-5deg); opacity: 0; }
        50% { transform: scale(1.1) rotate(2deg); }
        100% { transform: scale(1) rotate(0deg); opacity: 1; }
    }
    .slot-pop { animation: popIn 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards; }

    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }
    .float-animation { animation: float 3s ease-in-out infinite; }

    @keyframes pulse-ring {
        0% { transform: scale(0.95); opacity: 1; }
        50% { transform: scale(1); opacity: 0.7; }
        100% { transform: scale(0.95); opacity: 1; }
    }
    .pulse-ring { animation: pulse-ring 2s cubic-bezier(0.455, 0.03, 0.515, 0.955) infinite; }

    .stat-card {
        background: linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(255,255,255,0.6) 100%);
        backdrop-filter: blur(10px);
    }

    /* Sticky camera container */
    .camera-sticky-container {
        position: -webkit-sticky;
        position: sticky;
        top: 100px;
        align-self: flex-start;
        z-index: 20;
    }
    
    @media (min-width: 1024px) {
        .camera-sticky-container {
            position: -webkit-sticky !important;
            position: sticky !important;
            top: 100px !important;
            max-height: calc(100vh - 120px);
        }
    }
</style>
@endpush

@section('konten')
<div class="min-h-[70vh] flex flex-col justify-center relative">
    {{-- Background Image with Overlay - fixed but behind footer --}}
    <div class="fixed inset-0 overflow-hidden" style="z-index: -1;" id="mode-selection-bg">
        <div class="absolute inset-0 bg-gradient-to-br from-white/80 via-[#f8fdfc]/70 to-teal-50/80"></div>
        <div class="absolute inset-0" style="background-image: url('{{ asset('img/background-character.png') }}'); background-size: cover; background-position: center; opacity: 0.25;"></div>
    </div>
    
    <!-- MODE SELECTION SCREEN -->
    
    <!-- Toast Container -->
    <div id="toast-container" class="fixed top-24 left-1/2 transform -translate-x-1/2 z-[200] flex flex-col gap-2 pointer-events-none w-full max-w-sm px-4"></div>

    <div id="mode-selection" class="max-w-6xl mx-auto w-full py-4 relative z-10">
        
        <!-- Hero Section -->
        <div class="text-center mb-6">
            <h1 class="text-5xl font-black text-slate-900 mb-4 leading-tight drop-shadow-sm">
                Pilih Mode <span class="gradient-text">Pembelajaran</span>
            </h1>
            <p class="text-lg text-slate-800 max-w-2xl mx-auto font-medium drop-shadow-sm">
                Tingkatkan kemampuan bahasa isyarat Anda dengan teknologi pengenalan AI yang akurat dan responsif
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
            
            @foreach($modeCards as $card)
            @php
                $modeKey = is_object($card) ? ($card->mode_key ?? $card->mode_key) : $card['mode_key'];
                $title = is_object($card) ? $card->title : $card['title'];
                $description = is_object($card) ? $card->description : $card['description'];
                $badgeText = is_object($card) ? $card->badge_text : $card['badge_text'];
                $badgeEmoji = is_object($card) ? ($card->badge_emoji ?? '✨') : ($card['badge_emoji'] ?? '✨');
                $gradientFrom = is_object($card) ? $card->gradient_from : $card['gradient_from'];
                $gradientTo = is_object($card) ? $card->gradient_to : $card['gradient_to'];
                $iconType = is_object($card) ? $card->icon_type : $card['icon_type'];
                $iconContent = is_object($card) ? $card->icon_content : $card['icon_content'];
                $features = is_object($card) ? $card->features : $card['features'];
                $buttonText = is_object($card) ? $card->button_text : $card['button_text'];
                $image = is_object($card) ? ($card->image ?? null) : ($card['image'] ?? null);
            @endphp
            
            <!-- Card: {{ ucfirst($modeKey) }} -->
            <div onclick="selectMode('{{ $modeKey }}')" class="card-hover cursor-pointer rounded-3xl shadow-xl relative overflow-hidden group min-h-[480px] flex flex-col">
                
                @if($image)
                    <!-- Background Image -->
                    <div class="absolute inset-0">
                        <img src="{{ asset('storage/' . $image) }}" alt="{{ $title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        <!-- Dark gradient overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>
                    </div>
                @else
                    <!-- Gradient Background (fallback) -->
                    <div class="absolute inset-0" style="background: linear-gradient(135deg, {{ $gradientFrom }}, {{ $gradientTo }});"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent"></div>
                @endif
                
                <!-- Badge -->
                <div class="absolute top-6 left-6 z-20 text-white text-xs font-bold px-4 py-2 rounded-full shadow-lg backdrop-blur-sm" style="background: {{ $gradientFrom }}CC;">
                    {{ $badgeEmoji }} {{ $badgeText }}
                </div>
                
                <!-- Content at bottom -->
                <div class="relative z-10 mt-auto p-6 text-white">
                    <!-- Icon (small) -->
                    @if(!$image)
                    <div class="w-14 h-14 rounded-xl flex items-center justify-center mb-4 backdrop-blur-sm" style="background: rgba(255,255,255,0.2);">
                        @if($iconType === 'letter')
                            <span class="text-2xl font-black text-white">{{ $iconContent }}</span>
                        @else
                            {!! $iconContent !!}
                        @endif
                    </div>
                    @endif
                    
                    <h3 class="text-2xl font-black text-white mb-2">{{ $title }}</h3>
                    <p class="text-white/80 text-sm leading-relaxed mb-4 line-clamp-2">
                        {{ $description }}
                    </p>
                    
                    <!-- Features Tags -->
                    <div class="flex flex-wrap gap-2 mb-4">
                        @foreach(array_slice($features, 0, 2) as $feature)
                        <span class="text-xs px-3 py-1 rounded-full backdrop-blur-sm" style="background: rgba(255,255,255,0.2);">
                            {{ Str::limit($feature, 20) }}
                        </span>
                        @endforeach
                    </div>
                    
                    <button id="start-{{ $modeKey }}-btn" onclick="selectMode('{{ $modeKey }}'); event.stopPropagation();" class="w-full bg-white text-slate-800 font-bold py-3 rounded-xl shadow-lg hover:shadow-2xl transform hover:-translate-y-1 transition-all flex items-center justify-center gap-2 active:scale-95">
                        <span id="start-{{ $modeKey }}-text">{{ $buttonText }}</span>
                        <svg id="start-{{ $modeKey }}-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        <svg id="start-{{ $modeKey }}-spinner" class="w-5 h-5 animate-spin hidden" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </button>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Stats Bar -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 max-w-5xl mx-auto">
            <div class="stat-card rounded-2xl p-4 text-center border border-white/50">
                <div class="text-3xl font-black text-teal-600 mb-1">{{ $stats['total_abjad'] ?? 26 }}</div>
                <div class="text-xs font-bold text-slate-600 uppercase tracking-wide">Huruf BISINDO</div>
            </div>
            <div class="stat-card rounded-2xl p-4 text-center border border-white/50">
                <div class="text-3xl font-black text-teal-600 mb-1">{{ $stats['total_kata'] ?? 0 }}</div>
                <div class="text-xs font-bold text-slate-600 uppercase tracking-wide">Kosa Kata</div>
            </div>
            <div class="stat-card rounded-2xl p-4 text-center border border-white/50">
                <div class="text-3xl font-black text-teal-600 mb-1">{{ $stats['avg_accuracy'] ?? 95 }}%</div>
                <div class="text-xs font-bold text-slate-600 uppercase tracking-wide">Akurasi AI</div>
            </div>
            <div class="stat-card rounded-2xl p-4 text-center border border-white/50">
                <div class="text-3xl font-black text-teal-600 mb-1">~{{ $stats['avg_latency'] ?? 500 }}ms</div>
                <div class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Waktu Respon</div>
            </div>
        </div>
    </div>

    <!-- GAME AREA (Hidden initially) -->
    <div id="game-area" class="hidden w-full max-w-7xl mx-auto px-4" style="overflow: visible;">
        
        <!-- Top Bar: Floating & Modern -->
        <div class="flex items-center justify-between mb-8 bg-white/80 backdrop-blur-xl rounded-2xl p-3 shadow-lg border border-white/50 sticky top-4 z-30">
            <button onclick="backToMenu()" class="group flex items-center gap-2 text-slate-500 hover:text-red-500 font-bold transition-all px-4 py-2 rounded-xl hover:bg-red-50">
                <div class="w-8 h-8 rounded-full bg-slate-100 group-hover:bg-red-100 flex items-center justify-center transition-colors">
                    <svg class="w-4 h-4 transform group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                </div>
                <span class="text-sm">Keluar</span>
            </button>
            
            <div class="flex items-center gap-3">
                <!-- Mode Badge -->
                <div class="hidden md:flex items-center gap-2 bg-slate-100 px-4 py-2 rounded-xl border border-slate-200">
                    <div class="w-2 h-2 bg-teal-500 rounded-full animate-pulse" id="mode-badge-color"></div>
                    <span class="text-xs font-bold text-slate-600 uppercase tracking-wider">Mode: <span id="mode-badge-text">Abjad</span></span>
                </div>

                <!-- Timer -->
                <div class="flex items-center gap-2 bg-slate-800 text-white px-4 py-2 rounded-xl shadow-md">
                    <svg class="w-4 h-4 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div id="session-timer" class="font-mono font-bold tracking-widest">00:00</div>
                </div>

                <!-- History Toggle -->
                <button onclick="toggleHistory()" class="flex items-center gap-2 bg-white hover:bg-teal-50 border border-slate-200 hover:border-teal-200 text-slate-600 hover:text-teal-600 px-4 py-2 rounded-xl transition-all shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    <span class="text-sm font-bold hidden md:inline">Riwayat</span>
                </button>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-8" style="align-items: flex-start;">
            
            <!-- LEFT: Control Panel (Sticky) -->
            <div class="w-full lg:w-[380px] lg:flex-shrink-0 space-y-6 lg:sticky lg:top-28">
                
                <!-- 1. Input Card -->
                <div id="setup-panel" class="bg-white rounded-3xl p-1 shadow-xl border border-slate-100 transition-all">
                    <div class="bg-gradient-to-br from-slate-50 to-white rounded-[20px] p-6 border border-slate-100">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 bg-teal-100 text-teal-600 rounded-2xl flex items-center justify-center shadow-sm" id="setup-icon-container">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </div>
                            <div>
                                <h3 class="font-black text-slate-800 text-lg" id="setup-title">Target Kata</h3>
                                <p class="text-xs font-medium text-slate-400 uppercase tracking-wide" id="setup-subtitle">Mulai Latihan Baru</p>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            <!-- Abjad Mode: Text Input -->
                            <div id="abjad-input-container" class="relative group">
                                <div class="absolute -inset-0.5 bg-gradient-to-r from-teal-500 to-emerald-500 rounded-2xl opacity-20 group-focus-within:opacity-100 transition duration-300 blur"></div>
                                <input type="text" id="target-input" placeholder="KETIK DISINI..." 
                                    class="relative w-full px-5 py-5 bg-white border-0 rounded-xl focus:ring-0 font-black text-center tracking-[0.2em] text-2xl uppercase text-slate-800 placeholder:text-slate-300 placeholder:font-bold placeholder:text-lg placeholder:tracking-normal shadow-sm">
                            </div>

                            <!-- Kata Mode: Card Grid (Hidden by default) -->
                            <div id="kata-card-container" class="hidden">
                                <div class="text-xs font-bold text-slate-500 mb-3 uppercase tracking-wider">Pilih Kata:</div>
                                <div id="kata-cards-grid" class="grid grid-cols-2 gap-2 max-h-64 overflow-y-auto pr-1">
                                    <!-- Cards will be injected by JS -->
                                </div>
                                <input type="hidden" id="selected-kata" value="">
                            </div>

                            <!-- Kalimat Mode: Sentence Builder (Hidden by default) -->
                            <div id="kalimat-container" class="hidden">
                                <!-- Sentence Builder Strip -->
                                <div class="text-xs font-bold text-slate-500 mb-2 uppercase tracking-wider">Susun Kalimat:</div>
                                <div id="sentence-builder" class="min-h-[60px] bg-white rounded-xl p-3 border-2 border-dashed border-slate-200 mb-3 flex flex-wrap gap-2 items-center">
                                    <span class="text-slate-400 text-sm" id="empty-sentence-hint">Klik tombol di bawah untuk menambah kata atau ejaan</span>
                                </div>
                                
                                <!-- Action Buttons -->
                                <div class="grid grid-cols-2 gap-2 mb-3">
                                    <button type="button" onclick="showWordPicker()" class="flex items-center justify-center gap-2 px-4 py-3 bg-purple-100 hover:bg-purple-200 text-purple-700 font-bold rounded-xl transition-all border border-purple-200">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                        Tambah Kata
                                    </button>
                                    <button type="button" onclick="showLetterInput()" class="flex items-center justify-center gap-2 px-4 py-3 bg-pink-100 hover:bg-pink-200 text-pink-700 font-bold rounded-xl transition-all border border-pink-200">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                        Tambah Ejaan
                                    </button>
                                </div>
                                
                                <!-- Preview Section -->
                                <div class="p-3 bg-purple-50 rounded-xl border border-purple-100">
                                    <div class="text-xs font-bold text-purple-700 mb-2 uppercase tracking-wider">Preview Kalimat:</div>
                                    <div id="sentence-preview" class="text-lg font-bold text-purple-800 text-center min-h-[24px] bg-white rounded-lg p-2 border border-purple-200">
                                        - Tambahkan kata atau ejaan -
                                    </div>
                                </div>
                            </div>

                            <button id="start-btn" disabled class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold py-4 rounded-xl shadow-lg shadow-slate-900/20 transform transition-all disabled:opacity-50 disabled:cursor-not-allowed hover:-translate-y-1 active:scale-95 flex justify-center items-center gap-3">
                                 <span class="tracking-wide">MULAI LATIHAN</span>
                                 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- 2. Progress Card (Active Game) -->
                <div id="practice-ui" class="hidden transform transition-all duration-500">
                    <div class="bg-white rounded-3xl shadow-2xl border border-teal-100 overflow-hidden">
                         <div class="bg-teal-50/50 p-4 border-b border-teal-100 flex justify-between items-center">
                            <div class="flex items-center gap-2">
                                <span class="flex h-3 w-3 relative">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-teal-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-3 w-3 bg-teal-500"></span>
                                </span>
                                <span class="font-bold text-teal-900 text-sm tracking-wide">SEDANG BERLANGSUNG</span>
                            </div>
                            <button id="reset-practice-btn" class="text-[10px] font-black text-red-500 hover:bg-red-50 px-3 py-1.5 rounded-lg transition-all uppercase tracking-wider border border-red-100 hover:border-red-200">Batal</button>
                        </div>

                        <div class="p-6">
                            <!-- Slots -->
                            <div id="word-container" class="flex flex-wrap justify-center gap-3 mb-8 min-h-[80px]"></div>

                            <!-- Dashboard -->
                            <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100">
                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div class="text-center border-r border-slate-200 pr-2">
                                        <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Mendeteksi</div>
                                        <div id="detected-preview" class="text-xl font-black text-slate-800 min-h-[40px] flex items-center justify-center truncate px-1">-</div>
                                    </div>
                                    <div class="text-center pl-2">
                                        <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Akurasi</div>
                                        <div id="confidence-preview" class="text-xl font-black text-slate-300 flex items-center justify-center min-h-[40px]">0%</div>
                                    </div>
                                </div>
                                
                                <!-- Progress Bar -->
                                <div class="space-y-2">
                                    <div class="flex justify-between text-xs font-bold text-slate-500">
                                        <span>PROGRESS KATA</span>
                                        <span id="progress-text" class="text-teal-600">0/0</span>
                                    </div>
                                    <div class="h-2.5 bg-slate-200 rounded-full overflow-hidden shadow-inner">
                                        <div id="progress-bar" class="h-full bg-gradient-to-r from-teal-400 to-emerald-500 rounded-r-full shadow-lg shadow-teal-500/30 transition-all duration-300 ease-out" style="width: 0%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. Tips Card -->
                <div class="bg-gradient-to-br from-indigo-500 to-blue-600 rounded-3xl p-6 text-white shadow-xl shadow-blue-500/20 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
                    
                    <div class="flex items-start gap-4 relative z-10">
                        <div class="w-10 h-10 bg-white/20 backdrop-blur-md rounded-xl flex items-center justify-center flex-shrink-0 border border-white/10">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-lg mb-1">Tips Akurasi</h4>
                            <p class="text-blue-100 text-xs leading-relaxed mb-3 opacity-90">
                                Pastikan pencahayaan cukup dan tangan terlihat jelas di kamera.
                            </p>
                            <div class="flex flex-wrap gap-2">
                                <span class="bg-white/10 px-2 py-1 rounded text-[10px] font-bold border border-white/10">💡 Cahaya Terang</span>
                                <span class="bg-white/10 px-2 py-1 rounded text-[10px] font-bold border border-white/10">✋ Latar Polos</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- RIGHT: Camera Display (Sticky) -->
            <div class="flex-1 camera-sticky-container">
                <div class="relative bg-slate-900 rounded-[32px] overflow-hidden shadow-2xl ring-8 ring-slate-100 border border-slate-200 group">
                    <!-- Camera Header -->
                    <div class="absolute top-0 left-0 right-0 p-4 flex justify-between items-start z-10 bg-gradient-to-b from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <div class="flex items-center gap-2">
                            <div class="px-2 py-1 bg-red-500/90 backdrop-blur rounded text-[10px] font-bold text-white flex items-center gap-1.5 animate-pulse">
                                <div class="w-1.5 h-1.5 bg-white rounded-full"></div>
                                LIVE
                            </div>
                        </div>
                        <div class="bg-black/50 backdrop-blur rounded-lg px-2 py-1 text-[10px] font-mono text-white/80 border border-white/10">
                            <span id="fps-counter" class="font-bold text-white">0</span> FPS
                        </div>
                    </div>

                    <!-- Video Container (Aspect Video) -->
                    <div class="aspect-video relative bg-slate-800 flex items-center justify-center">
                        <video id="video" class="absolute inset-0 w-full h-full object-cover" style="display: none;" autoplay playsinline muted></video>
                        <canvas id="output-canvas" class="absolute inset-0 w-full h-full object-contain"></canvas>
                        
                        <!-- Status Overlays -->
                        <div id="status-overlay" class="absolute inset-0 flex flex-col items-center justify-center bg-slate-900 z-20">
                            <div class="relative w-20 h-20 mb-6">
                                    <div class="absolute inset-0 border-4 border-slate-800 rounded-full"></div>
                                    <div class="absolute inset-0 border-4 border-teal-500 rounded-full border-t-transparent animate-spin"></div>
                            </div>
                            <h3 class="text-white font-bold text-xl mb-1">Memuat Kamera</h3>
                            <p class="text-slate-500 text-sm">Menghubungkan ke MediaPipe AI...</p>
                        </div>

                        <!-- Feedback Overlay -->
                        <div id="feedback-overlay" class="absolute inset-0 bg-teal-500/20 backdrop-blur-[2px] flex items-center justify-center pointer-events-none opacity-0 transition-opacity duration-200 z-30">
                            <div class="bg-white/90 backdrop-blur-md text-teal-700 px-8 py-4 rounded-2xl shadow-2xl transform scale-105 flex flex-col items-center">
                                    <div class="text-4xl mb-1">✨</div>
                                    <span class="text-2xl font-black tracking-tight">TEPAT!</span>
                            </div>
                        </div>

                        <!-- Wrong Feedback Overlay -->
                        <div id="wrong-overlay" class="absolute inset-0 bg-red-500/10 backdrop-blur-[1px] flex items-center justify-center pointer-events-none opacity-0 transition-opacity duration-200 z-30">
                            <div class="bg-white/90 backdrop-blur-md text-red-600 px-6 py-3 rounded-2xl shadow-xl transform scale-105 flex flex-col items-center border-2 border-red-100">
                                    <div class="text-3xl mb-1">↺</div>
                                    <span class="text-xl font-bold tracking-tight">ULANGI</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Bottom Stats -->
                <div class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="bg-white p-3 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-3">
                         <div class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                         </div>
                         <div>
                             <div class="text-[10px] uppercase font-bold text-slate-400">Status</div>
                             <div class="text-xs font-bold text-teal-600">AI Siap</div>
                         </div>
                    </div>
                    <div class="bg-white p-3 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-3">
                         <div class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                         </div>
                         <div>
                             <div class="text-[10px] uppercase font-bold text-slate-400">Latency (v2)</div>
                             <div id="latency-preview" class="text-xs font-bold text-slate-800">0ms</div>
                         </div>
                    </div>
                    <div class="col-span-2 bg-white p-3 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between px-6">
                        <span class="text-xs font-bold text-slate-400">Powered by</span>
                        <div class="flex gap-4 opacity-70 grayscale hover:grayscale-0 transition-all">
                             <span class="text-xs font-black text-slate-800">TensorFlow</span>
                         <span class="text-xs font-black text-slate-800">MediaPipe</span>
                        </div>
                    </div>
                </div>
                

            </div>

        </div>
    </div>

    <!-- Transition Loading Overlay -->
    <div id="transition-overlay" class="fixed inset-0 bg-gradient-to-br from-slate-900 via-slate-800 to-teal-900 flex flex-col items-center justify-center z-[100] opacity-0 pointer-events-none transition-opacity duration-300">
        <div class="relative">
            <!-- Animated Rings -->
            <div class="w-32 h-32 rounded-full border-4 border-teal-500/20 absolute animate-ping"></div>
            <div class="w-32 h-32 rounded-full border-4 border-teal-500/30 absolute animate-pulse"></div>
            <div class="w-32 h-32 rounded-full border-t-4 border-teal-400 animate-spin"></div>
            
            <!-- Center Icon -->
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="w-16 h-16 bg-teal-500 rounded-2xl flex items-center justify-center text-white text-3xl font-black shadow-xl shadow-teal-500/50 animate-bounce">
                    A
                </div>
            </div>
        </div>
        
        <h3 class="text-white text-2xl font-bold mt-12 mb-2">Memulai Latihan</h3>
        <p class="text-slate-400 text-sm mb-6">Mempersiapkan kamera dan sistem AI...</p>
        
        <!-- Progress Dots -->
        <div class="flex gap-2">
            <div class="w-2 h-2 bg-teal-400 rounded-full animate-bounce" style="animation-delay: 0s"></div>
            <div class="w-2 h-2 bg-teal-400 rounded-full animate-bounce" style="animation-delay: 0.15s"></div>
            <div class="w-2 h-2 bg-teal-400 rounded-full animate-bounce" style="animation-delay: 0.3s"></div>
        </div>
    </div>

    <!-- History Sidebar -->
    <div id="history-sidebar" class="fixed top-0 right-0 h-full w-96 bg-white shadow-2xl transform translate-x-full transition-transform duration-300 z-50 overflow-y-auto">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-slate-800">Riwayat Saya</h3>
                <button onclick="toggleHistory()" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div id="history-container" class="space-y-3">
                <div class="text-center py-12 text-slate-400">
                    <svg class="w-12 h-12 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-sm">Memuat riwayat saya...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- History Backdrop -->
    <div id="history-backdrop" onclick="toggleHistory()" class="fixed inset-0 bg-black/50 opacity-0 pointer-events-none transition-opacity duration-300 z-40"></div>

    <!-- Word Picker Modal (Kalimat Mode) -->
    <div id="word-picker-modal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl p-6 max-w-md w-full max-h-[80vh] overflow-hidden shadow-2xl">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-slate-800">Pilih Kata</h3>
                <button onclick="hideWordPicker()" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div id="word-picker-grid" class="grid grid-cols-2 gap-2 max-h-[50vh] overflow-y-auto">
                <!-- Words will be injected by JS -->
            </div>
        </div>
    </div>

    <!-- Letter Input Modal (Kalimat Mode) -->
    <div id="letter-input-modal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl p-6 max-w-sm w-full shadow-2xl">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-slate-800">Masukkan Ejaan</h3>
                <button onclick="hideLetterInput()" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <input type="text" id="letter-modal-input" placeholder="Ketik huruf..." 
                class="w-full px-4 py-4 bg-slate-50 border-2 border-slate-200 rounded-xl font-bold text-center tracking-[0.2em] text-2xl uppercase text-slate-800 focus:border-pink-400 focus:ring-0 mb-4">
            <button onclick="addLettersFromModal()" class="w-full bg-gradient-to-r from-pink-500 to-purple-500 text-white font-bold py-3 rounded-xl hover:shadow-lg transition-all">
                Tambahkan
            </button>
        </div>
    </div>

    <!-- Custom Completion Modal -->
    <div id="completion-modal" class="fixed inset-0 bg-gradient-to-br from-black/70 via-slate-900/60 to-black/70 backdrop-blur-md flex items-center justify-center opacity-0 pointer-events-none transition-all duration-300 z-50">
        <div id="completion-modal-content" class="relative bg-gradient-to-br from-white via-white to-teal-50/50 rounded-[2rem] p-8 max-w-md w-full mx-4 shadow-[0_25px_60px_-15px_rgba(0,0,0,0.3)] transform scale-90 transition-all duration-300 overflow-hidden">
            <!-- Decorative Elements -->
            <div class="absolute -top-20 -right-20 w-40 h-40 bg-gradient-to-br from-teal-400/20 to-emerald-400/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-16 -left-16 w-32 h-32 bg-gradient-to-br from-cyan-400/20 to-blue-400/10 rounded-full blur-3xl"></div>
            
            <!-- Sparkle decorations -->
            <div class="absolute top-6 right-8 text-2xl animate-pulse">✨</div>
            <div class="absolute top-10 left-10 text-lg animate-pulse" style="animation-delay: 0.5s;">🌟</div>
            
            <div class="text-center relative z-10">
                <div class="relative inline-block mb-4">
                    <div class="text-7xl animate-bounce" style="animation-duration: 0.8s; filter: drop-shadow(0 8px 16px rgba(0,0,0,0.1));">🎊</div>
                    <div class="absolute -top-1 -right-3 w-4 h-4 bg-gradient-to-br from-yellow-400 to-amber-500 rounded-full animate-ping" style="animation-duration: 1.5s;"></div>
                </div>
                
                <h3 id="completion-title" class="text-3xl font-black mb-3 bg-gradient-to-r from-teal-600 via-emerald-500 to-cyan-600 bg-clip-text text-transparent"></h3>
                
                <!-- Result Display (Hidden by default) -->
                <div id="completion-result" class="hidden mb-6">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">HASIL:</p>
                    <div class="bg-teal-50 rounded-xl p-4 border border-teal-100">
                        <p id="completion-result-text" class="text-2xl font-black text-teal-800 leading-tight"></p>
                    </div>
                </div>

                <p id="completion-message" class="text-lg text-slate-600 mb-8 font-medium"></p>
                
                <div class="flex gap-4">
                    <button onclick="closeCompletionModal(false)" class="flex-1 group relative px-6 py-4 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-2xl transition-all duration-300 overflow-hidden">
                        <span class="relative z-10 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Selesai
                        </span>
                    </button>
                    <button onclick="closeCompletionModal(true)" class="flex-1 group relative px-6 py-4 bg-gradient-to-r from-teal-500 via-emerald-500 to-cyan-500 hover:from-teal-600 hover:via-emerald-600 hover:to-cyan-600 text-white font-bold rounded-2xl shadow-lg shadow-teal-500/30 hover:shadow-xl hover:shadow-teal-500/40 transition-all duration-300 hover:-translate-y-0.5 overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-r from-white/0 via-white/20 to-white/0 -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
                        <span class="relative z-10 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            Coba Lagi
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Exit Confirmation Modal -->
    <div id="exit-modal" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center opacity-0 pointer-events-none transition-all duration-300 z-[60]">
        <div id="exit-modal-content" class="bg-white rounded-3xl p-8 max-w-sm w-full mx-4 shadow-2xl transform scale-90 transition-transform duration-300">
            <div class="text-center">
                <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-2">Keluar dari Latihan?</h3>
                <p class="text-slate-500 text-sm mb-6">Progress latihan Anda akan hilang dan tidak tersimpan.</p>
                <div class="flex gap-3">
                    <button onclick="closeExitModal()" class="flex-1 px-5 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition-colors">
                        Batal
                    </button>
                    <button onclick="confirmExit()" class="flex-1 px-5 py-3 bg-gradient-to-r from-red-500 to-orange-500 hover:from-red-600 hover:to-orange-600 text-white font-bold rounded-xl shadow-lg transition-all">
                        Ya, Keluar
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Permission Modal -->

</div>

<script src="https://cdn.jsdelivr.net/npm/@mediapipe/camera_utils/camera_utils.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@mediapipe/hands/hands.js" crossorigin="anonymous"></script>

<script>
    // State management
    let camera = null;
    let pendingMode = null;
    
    // Permission Helpers
    function requestPermission() {
        document.getElementById('permission-modal').classList.add('hidden');
        if (pendingMode) startTransition(pendingMode);
    }

    function cancelPermission() {
        document.getElementById('permission-modal').classList.add('hidden');
        pendingMode = null;
        // Reset badges or UI if needed, but easy enough to just stay on menu
        // Maybe remove highlighting from selected card if in Kata mode?
        // For now, simple close is fine.
    }

    function startTransition(mode) {
         // Show transition overlay immediately
        const overlay = document.getElementById('transition-overlay');
        overlay.classList.remove('opacity-0', 'pointer-events-none');
        overlay.classList.add('opacity-100');
        
        // Update overlay text
        overlay.querySelector('h3').textContent = 'Memulai Latihan';
        overlay.querySelector('p').textContent = 'Mempersiapkan kamera dan sistem AI...';
        
        // Init Camera (Deferred)
        initCamera().then(() => {
            // Camera is ready, now wait for first MediaPipe result (model ready)
            overlay.querySelector('p').textContent = 'Menginisialisasi model AI...';
            
            // Poll for model readiness (set by onResults)
            const waitForModel = () => {
                if (window.isModelReady) {
                    // Both camera AND model are ready - proceed!
                    proceedToGameArea();
                } else {
                    // Check again in 100ms
                    setTimeout(waitForModel, 100);
                }
            };
            
            // Start polling
            waitForModel();
            
            // Timeout after 15 seconds
            setTimeout(() => {
                if (!window.isModelReady) {
                    console.warn('Model initialization timeout - proceeding anyway');
                    proceedToGameArea();
                }
            }, 15000);
            
        }).catch(err => {
            console.error("Camera Init Error", err);
            alert("Gagal mengakses kamera. Pastikan Anda mengizinkan akses.");
            overlay.classList.add('opacity-0', 'pointer-events-none');
        });
        
        function proceedToGameArea() {
            // Hide mode selection, show game area
            document.getElementById('mode-selection').classList.add('hidden');
            document.getElementById('game-area').classList.remove('hidden');
            
            // Hide status overlay immediately (we already waited for model)
            document.getElementById('status-overlay').classList.add('hidden');
            
            // Start session timer
            if (window.startSessionTimer) window.startSessionTimer();
            
            // Hide transition overlay
            setTimeout(() => {
                overlay.classList.add('opacity-0', 'pointer-events-none');
                overlay.classList.remove('opacity-100');
            }, 300);
            
            // Load history
            setTimeout(() => { if(window.loadHistory) window.loadHistory(); }, 500);
        }
    }
    
    // Camera Init Logic
    function initCamera() {
        return new Promise((resolve, reject) => {
            if (camera) { resolve(); return; } // Already initiated

            const video = document.getElementById('video');
            const outCanvas = document.getElementById('output-canvas');
            
            // We need to access the Camera class. It is available globally from script tag.
            // But we need 'hands.send' which is defined inside DOMContentLoaded?
            // Wait, 'hands' is defined inside DOMContentLoaded too.
            // We need 'hands' to be global.
            
            if (!window.hands) {
                // If hands not ready, wait a bit? Or reject?
                // It should be ready if initialized in DOMContentLoaded.
                // We'll fix DOMContentLoaded to expose hands.
                reject(new Error("Sistem AI belum siap."));
                return;
            }

            try {
                camera = new Camera(video, {
                    onFrame: async () => {
                        if(video.videoWidth > 0) await window.hands.send({image: video});
                    },
                    width: 640,
                    height: 480
                });

                video.addEventListener('loadeddata', () => {
                    outCanvas.width = video.videoWidth;
                    outCanvas.height = video.videoHeight;
                }, { once: true });
    
                camera.start()
                    .then(() => resolve())
                    .catch(e => reject(e));
            } catch (e) {
                reject(e);
            }
        });
    }

    function selectMode(mode) {
        window.currentMode = mode;
        pendingMode = mode;
        
        // Update badge UI
        if(mode === 'kata') {
             document.getElementById('mode-badge-text').textContent = 'KATA';
             document.getElementById('mode-badge-color').classList.replace('bg-teal-500', 'bg-indigo-500');
             document.getElementById('mode-badge-color').classList.replace('bg-purple-500', 'bg-indigo-500');
             document.getElementById('abjad-input-container').classList.add('hidden');
             document.getElementById('kata-card-container').classList.remove('hidden');
             document.getElementById('kalimat-container').classList.add('hidden');
             document.getElementById('setup-title').textContent = 'Pilih Kata';
             document.getElementById('setup-subtitle').textContent = 'Klik kata yang ingin dilatih';
             document.getElementById('setup-icon-container').classList.replace('bg-teal-100', 'bg-indigo-100');
             document.getElementById('setup-icon-container').classList.replace('text-teal-600', 'text-indigo-600');
             document.getElementById('setup-icon-container').classList.replace('bg-purple-100', 'bg-indigo-100');
             document.getElementById('setup-icon-container').classList.replace('text-purple-600', 'text-indigo-600');
             
             const startBtnEl = document.getElementById('start-btn');
             startBtnEl.disabled = true;
             startBtnEl.innerHTML = '<span class="tracking-wide">PILIH KATA DULU</span>';
        } else if (mode === 'kalimat') {
             document.getElementById('mode-badge-text').textContent = 'KALIMAT';
             document.getElementById('mode-badge-color').classList.replace('bg-teal-500', 'bg-purple-500');
             document.getElementById('mode-badge-color').classList.replace('bg-indigo-500', 'bg-purple-500');
             document.getElementById('abjad-input-container').classList.add('hidden');
             document.getElementById('kata-card-container').classList.add('hidden');
             document.getElementById('kalimat-container').classList.remove('hidden');
             document.getElementById('setup-title').textContent = 'Bentuk Kalimat';
             document.getElementById('setup-subtitle').textContent = 'Pilih kata target dan input abjad';
             document.getElementById('setup-icon-container').classList.replace('bg-teal-100', 'bg-purple-100');
             document.getElementById('setup-icon-container').classList.replace('text-teal-600', 'text-purple-600');
             document.getElementById('setup-icon-container').classList.replace('bg-indigo-100', 'bg-purple-100');
             document.getElementById('setup-icon-container').classList.replace('text-indigo-600', 'text-purple-600');
             
             const startBtnEl = document.getElementById('start-btn');
             startBtnEl.disabled = true;
             startBtnEl.innerHTML = '<span class="tracking-wide">TAMBAH KATA/EJAAN DULU</span>';
             
             // Reset sentence builder
             if (window.sentenceItems) window.sentenceItems = [];
             if (typeof sentenceItems !== 'undefined') sentenceItems = [];
             const builder = document.getElementById('sentence-builder');
             if (builder) builder.innerHTML = '<span class="text-slate-400 text-sm" id="empty-sentence-hint">Klik tombol di bawah untuk menambah kata atau ejaan</span>';
             const preview = document.getElementById('sentence-preview');
             if (preview) preview.textContent = '- Tambahkan kata atau ejaan -';
        } else {
             document.getElementById('mode-badge-text').textContent = 'ABJAD';
             document.getElementById('mode-badge-color').classList.replace('bg-indigo-500', 'bg-teal-500');
             document.getElementById('mode-badge-color').classList.replace('bg-purple-500', 'bg-teal-500');
             document.getElementById('abjad-input-container').classList.remove('hidden');
             document.getElementById('kata-card-container').classList.add('hidden');
             document.getElementById('kalimat-container').classList.add('hidden');
             document.getElementById('setup-title').textContent = 'Target Kata';
             document.getElementById('setup-subtitle').textContent = 'Ketik kata untuk dieja';
             document.getElementById('setup-icon-container').classList.replace('bg-indigo-100', 'bg-teal-100');
             document.getElementById('setup-icon-container').classList.replace('text-indigo-600', 'text-teal-600');
             document.getElementById('setup-icon-container').classList.replace('bg-purple-100', 'bg-teal-100');
             document.getElementById('setup-icon-container').classList.replace('text-purple-600', 'text-teal-600');
        }

        if(mode === 'abjad') {
            const btn = document.getElementById('start-abjad-btn');
            if(btn) btn.innerHTML = '<span>Memuat...</span>';
        } else if (mode === 'kata') {
            const btn = document.getElementById('start-kata-btn');
            if(btn) btn.innerHTML = '<span>Memuat...</span>';
        } else if (mode === 'kalimat') {
            const btn = document.getElementById('start-kalimat-btn');
            if(btn) btn.innerHTML = '<span>Memuat...</span>';
        }
        
        // Direct Start (Skip Custom Permission Modal)
        startTransition(mode);
    }

    function backToMenu() {
        // Show custom exit modal instead of browser confirm
        const modal = document.getElementById('exit-modal');
        const modalContent = document.getElementById('exit-modal-content');
        modal.classList.remove('opacity-0', 'pointer-events-none');
        modalContent.classList.remove('scale-90');
        modalContent.classList.add('scale-100');
    }

    function closeExitModal() {
        const modal = document.getElementById('exit-modal');
        const modalContent = document.getElementById('exit-modal-content');
        modal.classList.add('opacity-0', 'pointer-events-none');
        modalContent.classList.remove('scale-100');
        modalContent.classList.add('scale-90');
    }

    function confirmExit() {
        location.reload();
    }

    // Session Timer
    let sessionSeconds = 0;
    let sessionInterval;
    function startSessionTimer() {
        sessionInterval = setInterval(() => {
            sessionSeconds++;
            const mins = Math.floor(sessionSeconds / 60).toString().padStart(2, '0');
            const secs = (sessionSeconds % 60).toString().padStart(2, '0');
            document.getElementById('session-timer').textContent = `${mins}:${secs}`;
        }, 1000);
    }

    document.addEventListener('DOMContentLoaded', () => {
        const video = document.getElementById('video');
        const outCanvas = document.getElementById('output-canvas');
        const outCtx = outCanvas.getContext('2d');
        const statusOverlay = document.getElementById('status-overlay');
        const feedbackOverlay = document.getElementById('feedback-overlay');
        const setupPanel = document.getElementById('setup-panel');
        const practiceUi = document.getElementById('practice-ui');
        const targetInput = document.getElementById('target-input');
        const startBtn = document.getElementById('start-btn');
        const wordContainer = document.getElementById('word-container');
        const resetPracticeBtn = document.getElementById('reset-practice-btn');
        const detectedPreview = document.getElementById('detected-preview');
        const confidencePreview = document.getElementById('confidence-preview');
        const latencyPreview = document.getElementById('latency-preview');
        const progressBar = document.getElementById('progress-bar');
        const progressText = document.getElementById('progress-text');
        const fpsCounter = document.getElementById('fps-counter');

        const API_URL_ABJAD = "{{ config('services.python_api.url') }}/predict";
        const API_URL_KATA = "{{ config('services.python_api.url') }}/predict/kata";
        const API_URL_KATA_CLASSES = "{{ config('services.python_api.url') }}/kata/classes";
        const API_URL_NLG = "{{ config('services.python_api.url') }}/nlg";
        
        let validKataClasses = [];
        let selectedKataWords = []; // Array to store selected words
        window.currentMode = 'abjad'; // Use window to make it globally accessible
        let currentMode = window.currentMode; // Local reference
        
        let isProcessing = false;
        let isPracticeRunning = false;
        let isModelReady = false;
        let targetWord = [];
        let currentIndex = 0;
        // Expose to window for drawResults bounding box logic
        window.targetWord = targetWord;
        window.currentIndex = currentIndex;
        let results = null;
        let lastFrameTime = performance.now();
        let frameCount = 0;
        let latestBackendLandmarks = null;

        // Skeleton Connections
        const POSE_CONNECTIONS = [
            [11, 12], [11, 13], [13, 15], [12, 14], [14, 16], // Shoulders & Arms
            [11, 23], [12, 24], [23, 24], // Torso
            [23, 25], [24, 26], [25, 27], [26, 28] // Legs (Optional)
        ];
        const HAND_CONNECTIONS = [
            [0,1], [1,2], [2,3], [3,4], // Thumb
            [0,5], [5,6], [6,7], [7,8], // Index
            [5,9], [9,10], [10,11], [11,12], // Middle
            [9,13], [13,14], [14,15], [15,16], // Ring
            [13,17], [17,18], [18,19], [19,20], // Pinky
            [0,17] // Palm
        ];


        const REQUIRED_CONFIDENCE = 30; // For Abjad
        const REQUIRED_CONFIDENCE_KATA = 20; // Reverted to 20% as requested

        // Fetch Valid Classes on Load and Render Cards
        fetch(API_URL_KATA_CLASSES)
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    validKataClasses = data.classes;
                    console.log("Loaded " + validKataClasses.length + " kata classes.");
                    renderKataCards();
                    populateKalimatTargets(); // Initialize kalimat targets
                }
            })
            .catch(err => console.error("Gagal memuat kelas kata:", err));

        function renderKataCards() {
            const grid = document.getElementById('kata-cards-grid');
            if (!grid) return;
            grid.innerHTML = '';
            // Ensure grid has nice gap
            grid.className = "grid grid-cols-2 md:grid-cols-4 gap-4 p-2";
            
            validKataClasses.forEach(kata => {
                const card = document.createElement('button');
                card.type = 'button';
                card.className = 'kata-card p-2 text-xs md:text-sm font-bold bg-white text-slate-700 rounded-xl border-2 border-slate-200 hover:border-indigo-400 hover:bg-indigo-50 hover:text-indigo-700 shadow-sm hover:shadow-md hover:scale-105 transition-all duration-200 text-center flex items-center justify-center min-h-[60px] break-words leading-tight w-full';
                // Auto-capitalize first letter (e.g. maaf -> Maaf)
                card.textContent = kata.charAt(0).toUpperCase() + kata.slice(1);
                
                // Add checkmark icon (hidden by default)
                // We'll manage active state classes in selectKataCard
                
                card.onclick = () => selectKataCard(kata, card);
                grid.appendChild(card);
            });
        }

        // Multi-select Logic for Kata Cards
        function selectKataCard(kata, cardElement) {
            // Toggle selection
            if (selectedKataWords.includes(kata)) {
                selectedKataWords = selectedKataWords.filter(w => w !== kata);
                // Unhighlight
                cardElement.classList.remove('bg-indigo-600', 'text-white', 'border-indigo-600', 'ring-2', 'ring-indigo-300', 'scale-105', 'shadow-lg');
                cardElement.classList.add('bg-white', 'text-slate-700', 'border-slate-200', 'shadow-sm');
            } else {
                selectedKataWords.push(kata);
                // Highlight
                cardElement.classList.remove('bg-white', 'text-slate-700', 'border-slate-200', 'shadow-sm', 'hover:bg-indigo-50');
                cardElement.classList.add('bg-indigo-600', 'text-white', 'border-indigo-600', 'ring-2', 'ring-indigo-300', 'scale-105', 'shadow-lg');
            }

            // Update Start Button State
            const startBtnEl = document.getElementById('start-btn');
            
            if (selectedKataWords.length > 0) {
                startBtnEl.disabled = false;
                startBtnEl.innerHTML = `<span class="tracking-wide">MULAI LATIHAN (${selectedKataWords.length})</span><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>`;
                document.getElementById('selected-kata').value = selectedKataWords.join(','); // For debug/hidden input
            } else {
                startBtnEl.disabled = true;
                startBtnEl.innerHTML = '<span class="tracking-wide">PILIH KATA DULU</span>';
                document.getElementById('selected-kata').value = '';
            }
        }

        // Kalimat Mode: Populate Word Picker
        function populateKalimatTargets() {
            const grid = document.getElementById('word-picker-grid');
            if (!grid) return;
            grid.innerHTML = '';
            
            validKataClasses.forEach(kata => {
                const card = document.createElement('button');
                card.type = 'button';
                card.className = 'p-3 text-sm font-bold bg-white text-slate-700 rounded-xl border-2 border-slate-200 hover:border-purple-400 hover:bg-purple-50 hover:text-purple-700 shadow-sm hover:shadow-md transition-all duration-200 text-center';
                card.textContent = kata.charAt(0).toUpperCase() + kata.slice(1);
                card.onclick = () => addWordToSentence(kata);
                grid.appendChild(card);
            });
        }

        // Sentence Builder State
        let sentenceItems = []; // Array of {type: 'word'|'letters', value: string}

        function showWordPicker() {
            document.getElementById('word-picker-modal').classList.remove('hidden');
        }

        function hideWordPicker() {
            document.getElementById('word-picker-modal').classList.add('hidden');
        }

        function showLetterInput() {
            document.getElementById('letter-input-modal').classList.remove('hidden');
            document.getElementById('letter-modal-input').value = '';
            document.getElementById('letter-modal-input').focus();
        }

        function hideLetterInput() {
            document.getElementById('letter-input-modal').classList.add('hidden');
        }

        function addWordToSentence(word) {
            sentenceItems.push({ type: 'word', value: word });
            hideWordPicker();
            renderSentenceBuilder();
            updateKalimatStartBtn();
        }

        function addLettersFromModal() {
            const input = document.getElementById('letter-modal-input');
            const letters = input.value.trim().toUpperCase().replace(/[^A-Z]/g, '');
            if (letters.length > 0) {
                sentenceItems.push({ type: 'letters', value: letters });
                hideLetterInput();
                renderSentenceBuilder();
                updateKalimatStartBtn();
            }
        }

        function removeFromSentence(index) {
            sentenceItems.splice(index, 1);
            renderSentenceBuilder();
            updateKalimatStartBtn();
        }

        function renderSentenceBuilder() {
            const builder = document.getElementById('sentence-builder');
            const hint = document.getElementById('empty-sentence-hint');
            const preview = document.getElementById('sentence-preview');
            
            if (sentenceItems.length === 0) {
                builder.innerHTML = '<span class="text-slate-400 text-sm" id="empty-sentence-hint">Klik tombol di bawah untuk menambah kata atau ejaan</span>';
                preview.textContent = '- Tambahkan kata atau ejaan -';
                return;
            }
            
            // Render chips
            builder.innerHTML = sentenceItems.map((item, idx) => {
                const isWord = item.type === 'word';
                const bgColor = isWord ? 'bg-purple-100 border-purple-300 text-purple-700' : 'bg-pink-100 border-pink-300 text-pink-700';
                const icon = isWord ? '📝' : '🔤';
                return `
                    <div class="flex items-center gap-1 px-3 py-2 ${bgColor} border rounded-full text-sm font-bold">
                        <span>${icon}</span>
                        <span>${item.value}</span>
                        <button type="button" onclick="removeFromSentence(${idx})" class="ml-1 text-slate-400 hover:text-red-500 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                `;
            }).join('');
            
            // Update preview
            const previewParts = sentenceItems.map(item => {
                if (item.type === 'word') return item.value;
                return item.value.toLowerCase(); // Letters shown lowercase
            });
            preview.textContent = previewParts.join(' ');
        }

        function updateKalimatStartBtn() {
            const startBtnEl = document.getElementById('start-btn');
            if (sentenceItems.length > 0) {
                startBtnEl.disabled = false;
                startBtnEl.innerHTML = `<span class="tracking-wide">MULAI LATIHAN (${sentenceItems.length} item)</span><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>`;
            } else {
                startBtnEl.disabled = true;
                startBtnEl.innerHTML = '<span class="tracking-wide">TAMBAH KATA/EJAAN DULU</span>';
            }
        }

        // Make functions globally accessible
        window.showWordPicker = showWordPicker;
        window.hideWordPicker = hideWordPicker;
        window.showLetterInput = showLetterInput;
        window.hideLetterInput = hideLetterInput;
        window.addLettersFromModal = addLettersFromModal;
        window.removeFromSentence = removeFromSentence;

        // Input Validation Event
        targetInput.addEventListener('input', function() {
            const val = this.value.trim().toUpperCase();
            const btn = startBtn;
            
            if (window.currentMode === 'kata') {
                if (validKataClasses.length > 0 && !validKataClasses.includes(val)) {
                    btn.disabled = true;
                    btn.innerHTML = `<span class="text-red-300">KATA TIDAK TERSEDIA</span>`;
                    this.classList.add('border-red-500', 'text-red-500');
                } else if (val.length > 0) {
                    btn.disabled = false;
                    btn.innerHTML = `<span>MULAI LATIHAN</span>`;
                    this.classList.remove('border-red-500', 'text-red-500');
                }
            } else {
                 if (val.length > 0) {
                    btn.disabled = false;
                    btn.innerHTML = `<span>MULAI LATIHAN</span>`;
                 }
            }
        });

        function startGame() {
            if (!isModelReady) return;
            
            let val = '';
            if (window.currentMode === 'kata') {
                // Read from card selection array
                if (selectedKataWords.length === 0) return showToast("Pilih minimal satu kata!", 'error');
            } else if (window.currentMode === 'kalimat') {
                // Read from sentence builder items
                if (sentenceItems.length === 0) return showToast("Tambahkan minimal satu kata atau ejaan!", 'error');
                
                // Flatten sentenceItems into targetWord array
                // Words: keep as-is, Letters: split into individual chars
                targetWord = [];
                sentenceItems.forEach(item => {
                    if (item.type === 'word') {
                        targetWord.push(item.value);
                    } else {
                        // Letters - split into individual characters
                        item.value.split('').forEach(letter => targetWord.push(letter));
                    }
                });
                
                // Initialize kalimat-specific tracking
                window.completedTargets = [];
            } else {
                // Read from text input for Abjad
                val = targetInput.value.trim().toUpperCase().replace(/[^A-Z]/g, '');
                if (val.length < 1) return showToast("Masukkan kata target!", 'error');
            }

            if (window.currentMode === 'abjad') {
                 targetWord = val.split(''); // Split into letters
            } else if (window.currentMode === 'kata') {
                 targetWord = [...selectedKataWords]; // Use selected words array copy
            } else if (window.currentMode === 'kalimat') {
                 // targetWord already set above for kalimat mode
            }

            currentIndex = 0;
            // Sync to window for drawResults
            window.targetWord = targetWord;
            window.currentIndex = currentIndex;
            isPracticeRunning = true;
            setupPanel.classList.add('hidden'); 
            practiceUi.classList.remove('hidden');
            renderWordSlots();
            updateProgress();
        }

        function resetGame() {
            isPracticeRunning = false;
            latestBackendLandmarks = null;
            setupPanel.classList.remove('hidden');
            practiceUi.classList.add('hidden');
            targetInput.value = '';
            detectedPreview.textContent = "-";
            confidencePreview.textContent = "0%";
            progressBar.style.width = "0%";
            
            // Reset sentence builder for kalimat mode
            sentenceItems = [];
            const builder = document.getElementById('sentence-builder');
            if (builder) builder.innerHTML = '<span class="text-slate-400 text-sm" id="empty-sentence-hint">Klik tombol di bawah untuk menambah kata atau ejaan</span>';
            const preview = document.getElementById('sentence-preview');
            if (preview) preview.textContent = '- Tambahkan kata atau ejaan -';
            
            // Reset start button based on mode
            const startBtnEl = document.getElementById('start-btn');
            if (window.currentMode === 'kalimat') {
                startBtnEl.disabled = true;
                startBtnEl.innerHTML = '<span class="tracking-wide">TAMBAH KATA/EJAAN DULU</span>';
            }
        }

        function updateProgress() {
            const percent = (currentIndex / targetWord.length) * 100;
            progressBar.style.width = `${percent}%`;
            progressText.textContent = `${currentIndex}/${targetWord.length}`;
        }

        function renderWordSlots() {
            wordContainer.innerHTML = '';
            targetWord.forEach((char, index) => {
                const slot = document.createElement('div');
                const isWord = char.length > 1;
                
                // Base classes - different sizing for words vs letters
                let baseClasses = "slot-pop flex items-center justify-center font-black rounded-xl border-b-4 transition-all duration-300 ";
                
                if (isWord) {
                    // Word: flexible width with padding, smaller text, truncate if too long
                    baseClasses += "px-3 py-2 min-w-[60px] max-w-[120px] h-12 text-sm truncate ";
                } else {
                    // Letter: fixed square size
                    baseClasses += "w-12 h-12 text-xl ";
                }
                
                // State classes
                if (index < currentIndex) {
                    baseClasses += "bg-teal-500 border-teal-700 text-white shadow-lg";
                } else if (index === currentIndex) {
                    baseClasses += "bg-white border-orange-500 text-orange-600 ring-2 ring-orange-200 scale-105 shadow-xl";
                } else {
                    baseClasses += "bg-slate-100 border-slate-200 text-slate-300";
                }
                
                slot.className = baseClasses;
                slot.textContent = char;
                slot.title = char; // Tooltip for truncated text
                wordContainer.appendChild(slot);
            });
        }

        startBtn.addEventListener('click', startGame);
        resetPracticeBtn.addEventListener('click', resetGame);

        // Initialize MediaPipe Hands with proper error handling
        function initializeMediaPipe() {
            try {
                // Check if Hands constructor is available
                if (typeof Hands === 'undefined') {
                    throw new Error('MediaPipe Hands library not loaded');
                }
                
                window.hands = new Hands({locateFile: (file) => {
                    return `https://cdn.jsdelivr.net/npm/@mediapipe/hands/${file}`;
                }});
                
                // Set a timeout to handle WebAssembly loading issues
                window.handsLoadingTimeout = setTimeout(() => {
                    if (!window.isHandsReady) {
                        console.warn('MediaPipe Hands taking longer than expected to load');
                        statusOverlay.querySelector('p').textContent = 'Loading AI models... Please wait';
                    }
                }, 5000);
                
                // MediaPipe Hands initializes asynchronously. We'll use the onResults callback
                // to determine when it's ready. The first successful result indicates readiness.
                let handsReadyChecked = false;
                const originalOnResults = onResults;
                onResults = function(results) {
                    if (!handsReadyChecked && results) {
                        console.log('MediaPipe Hands initialized successfully');
                        window.isHandsReady = true;
                        handsReadyChecked = true;
                        clearTimeout(window.handsLoadingTimeout);
                        // Restore original onResults
                        window.hands.onResults(originalOnResults);
                    }
                    originalOnResults(results);
                };
                window.hands.onResults(onResults);
                
            } catch (error) {
                console.error('Failed to create MediaPipe Hands:', error);
                statusOverlay.querySelector('h3').textContent = 'AI System Unavailable';
                statusOverlay.querySelector('p').textContent = 'Please check your browser compatibility and internet connection';
            }
        }
        
        // Wait for MediaPipe scripts to load before initializing
        if (typeof Hands !== 'undefined') {
            initializeMediaPipe();
        } else {
            // If Hands is not available yet, wait for it to load
            const checkHandsLoaded = setInterval(() => {
                if (typeof Hands !== 'undefined') {
                    clearInterval(checkHandsLoaded);
                    initializeMediaPipe();
                }
            }, 100);
            
            // Timeout if Hands never loads
            setTimeout(() => {
                if (typeof Hands === 'undefined') {
                    clearInterval(checkHandsLoaded);
                    console.error('MediaPipe Hands library failed to load');
                    statusOverlay.querySelector('h3').textContent = 'AI System Unavailable';
                    statusOverlay.querySelector('p').textContent = 'Failed to load hand detection library. Please refresh the page.';
                }
            }, 10000);
        }

        const hands = window.hands; // Keep local ref if needed for existing code below

        // Only set options if hands was successfully created
        if (hands) {
            hands.setOptions({
                maxNumHands: 2,
                modelComplexity: 1,
                minDetectionConfidence: 0.7,
                minTrackingConfidence: 0.7
            });

            hands.onResults(onResults);
        } else {
            console.error('MediaPipe Hands is not available');
            statusOverlay.querySelector('h3').textContent = 'AI System Unavailable';
            statusOverlay.querySelector('p').textContent = 'Failed to initialize hand detection';
        }

        function onResults(res) {
            if (!isModelReady) {
                isModelReady = true;
                window.isModelReady = true; // Expose to window for startTransition polling
                statusOverlay.classList.add('hidden');
                startBtn.disabled = false;
                startBtn.innerHTML = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> MULAI LATIHAN`;
                startBtn.classList.remove('bg-slate-800');
                startBtn.classList.add('bg-gradient-to-r', 'from-teal-600', 'to-emerald-600');
            }

            // FPS Calculation
            frameCount++;
            const now = performance.now();
            if (now - lastFrameTime >= 1000) {
                fpsCounter.textContent = frameCount;
                frameCount = 0;
                lastFrameTime = now;
            }

            results = res;
            drawResults();
            
            if (results.multiHandLandmarks && results.multiHandLandmarks.length > 0) {
               if (!isProcessing && isPracticeRunning && window.currentMode === 'abjad') {
                   predictSmartCrop(results.multiHandLandmarks);
               }
            }
            
            // For Kata Mode, we predict continously or based on frame availability, 
            // but here we limit by 'isProcessing' to avoid flooding, although buffer logic handles it.
            // We need to send full frame even if no hand (to detect end of gesture).
            if (!isProcessing && isPracticeRunning && window.currentMode === 'kata') {
                predictFullFrame();
            }
            
            // For Kalimat Mode, predict both words and letters
            if (!isProcessing && isPracticeRunning && window.currentMode === 'kalimat') {
                predictKalimat();
            }
        }

        function drawResults() {
            outCtx.save();
            outCtx.clearRect(0, 0, outCanvas.width, outCanvas.height);
            outCtx.translate(outCanvas.width, 0);
            outCtx.scale(-1, 1);
            outCtx.drawImage(results.image, 0, 0, outCanvas.width, outCanvas.height);
            outCtx.setTransform(1, 0, 0, 1, 0, 0); 

            // Draw detection box for Abjad mode AND Kalimat mode with letter targets
            const isKalimatLetterTarget = window.currentMode === 'kalimat' && window.targetWord && window.targetWord[window.currentIndex] && window.targetWord[window.currentIndex].length === 1;
            if ((window.currentMode === 'abjad' || isKalimatLetterTarget) && results.multiHandLandmarks && results.multiHandLandmarks.length > 0) {
                let xMin = 1, xMax = 0, yMin = 1, yMax = 0;
                for (const landmarks of results.multiHandLandmarks) {
                    for (const lm of landmarks) {
                        const mx = 1 - lm.x; 
                        if(mx < xMin) xMin = mx;
                        if(mx > xMax) xMax = mx;
                        if(lm.y < yMin) yMin = lm.y;
                        if(lm.y > yMax) yMax = lm.y;
                    }
                }

                const padding = 0.05;
                xMin = Math.max(0, xMin - padding);
                xMax = Math.min(1, xMax + padding);
                yMin = Math.max(0, yMin - padding);
                yMax = Math.min(1, yMax + padding);

                const width = outCanvas.width;
                const height = outCanvas.height;
                const rectX = xMin * width;
                const rectY = yMin * height;
                const rectW = (xMax - xMin) * width;
                const rectH = (yMax - yMin) * height;

                // Glow effect
                outCtx.shadowBlur = 20;
                outCtx.shadowColor = 'rgba(20, 184, 166, 0.5)';
                outCtx.beginPath();
                outCtx.lineWidth = 3;
                outCtx.strokeStyle = 'rgba(20, 184, 166, 0.8)';
                outCtx.roundRect(rectX, rectY, rectW, rectH, 16);
                outCtx.stroke();
                outCtx.shadowBlur = 0;

                // Corners
                const cornerLen = 25;
                outCtx.lineWidth = 5;
                outCtx.strokeStyle = '#14b8a6';
                outCtx.lineCap = 'round';
                
                outCtx.beginPath(); outCtx.moveTo(rectX, rectY + cornerLen); outCtx.lineTo(rectX, rectY); outCtx.lineTo(rectX + cornerLen, rectY); outCtx.stroke();
                outCtx.beginPath(); outCtx.moveTo(rectX + rectW - cornerLen, rectY); outCtx.lineTo(rectX + rectW, rectY); outCtx.lineTo(rectX + rectW, rectY + cornerLen); outCtx.stroke();
                outCtx.beginPath(); outCtx.moveTo(rectX, rectY + rectH - cornerLen); outCtx.lineTo(rectX, rectY + rectH); outCtx.lineTo(rectX + cornerLen, rectY + rectH); outCtx.stroke();
                outCtx.beginPath(); outCtx.moveTo(rectX + rectW - cornerLen, rectY + rectH); outCtx.lineTo(rectX + rectW, rectY + rectH); outCtx.lineTo(rectX + rectW, rectY + rectH - cornerLen); outCtx.stroke();
            }
            
            // Draw Kata Skeleton (Use Client-Side for zero latency)
            if ((window.currentMode === 'kata' || window.currentMode === 'kalimat') && results.multiHandLandmarks && results.multiHandLandmarks.length > 0) {
                 // Determine if we should draw client skeleton
                 // For Kalimat, only if target is NOT a letter (which uses CV/CNN)
                 const currentTarget = (targetWord && currentIndex < targetWord.length) ? targetWord[currentIndex] : '';
                 const isLetterTarget = currentTarget.length === 1;
                 
                 // If Mode Kata, always draw. If Kalimat, draw only for words.
                 if (window.currentMode === 'kata' || !isLetterTarget) {
                     drawClientSkeleton(results.multiHandLandmarks);
                 }
            }

            outCtx.restore();
        }

        function drawKataSkeleton(landmarks) {
            const w = outCanvas.width;
            const h = outCanvas.height;
            
            outCtx.lineWidth = 2;
            outCtx.lineCap = 'round';
            outCtx.lineJoin = 'round';

            // Helper to draw landmarks
            const drawPoints = (points, color) => {
                outCtx.fillStyle = color;
                points.forEach(p => {
                    // Coordinates from backend are normalized [0,1] relative to the *flipped* frame.
                    // Frontend canvas is already flipped via scale(-1, 1).
                    // Wait! If canvas is flipped with scale(-1, 1), then drawing at x=0.1 draws at x=0.9 visually?
                    // Let's think:
                    // Backend flips frame -> Right hand becomes Left visually in backend.
                    // Frontend draws original video mirrored -> User's Right hand is on Left side of screen.
                    // Backend result x=0.1 means Left side of backend frame.
                    // If we draw at x=0.1 on a `scale(-1, 1)` canvas:
                    // context is transformed. x=0 is right, x=width is left? No.
                    // scale(-1, 1) usually requires translate(width, 0).
                    // So x=0 (after transform) is Right edge of canvas? 
                    // Let's look at drawResults:
                    // outCtx.translate(outCanvas.width, 0); outCtx.scale(-1, 1);
                    // Yes. So coordinate system is flipped.
                    // Drawing at x=10 (near left of original image) puts it at x=width-10 (near right of canvas).
                    // Backend: User raises Right hand -> appears on Left of frame (since flipped). x is small (e.g. 0.2).
                    // Visualization: We want it to appear on Left of screen (mirror).
                    // If we draw at x=0.2 * width on the transformed canvas...
                    // The transformed canvas flips X. So x=0.2 maps to ScreenX = width - (0.2*width) = 0.8 width?
                    // Wait. If backend saw it at x=0.2 (Left side of analyzed frame), it means it's on the Left.
                    // We want it to be drawn on the Left side of the screen.
                    // The canvas transform flips everything.
                    // So if we draw at x=0.2, it will be flipped to the Right side?
                    // Let's double check.
                    // Backend flip: Right becomes Left. x=0.2.
                    // Frontend canvas: `drawImage` draws original (Right is Right).
                    // But `scale(-1, 1)` mirrors it, so original Right appears on Left.
                    // If we draw landmarks (x=0.2 from backend) on this same canvas...
                    // If we use the same transform, x=0.2 will also be flipped to Right??
                    // No. The backend frame IS already flipped.
                    // Original Video: Hand is at x=0.8 (Right).
                    // Backend Input: Flipped. Hand is at x=0.2 (Left).
                    // Backend returns x=0.2.
                    // Frontend Canvas: Mirrored. Video drawn effectively as Flipped. Hand at x=0.2 (Left visual).
                    // If we draw a point at x=0.2 on this canvas...
                    // The canvas transform is active.
                    // x=0.2 * w -> Flipped -> Screen x=0.8 (Right visual).
                    // MISMATCH!
                    // We want it at Screen x=0.2 (Left visual).
                    // So we need to draw it at x=0.8 on the transformed canvas?
                    // Or... maybe we should UN-flip the canvas before drawing "already flipped" coordinates?
                    // Or... backend coordinates are relative to the flipped image.
                    // If we draw them on a canvas that is *rendering the flipped image* (via mirror transform),
                    // then we should just use them directly...
                    // WAIT. 
                    // DrawImage(video) draws the ORIGINAL video (x=0.8).
                    // Transform flips it to appear at x=0.2.
                    // If we draw a point at x=0.8 (matching original video), it will also be flipped to x=0.2.
                    // But backend returns x=0.2 (because it processed the flipped image).
                    // So we have x=0.2 from backend.
                    // We need to draw at x=0.8 (original coordinates) so that the flip puts it at x=0.2.
                    // So: x_draw = 1 - x_backend.
                    
                    const px = (1 - p.x) * w; 
                    const py = p.y * h;
                    
                    outCtx.beginPath();
                    outCtx.arc(px, py, 3, 0, 2 * Math.PI);
                    outCtx.fill();
                });
            };

            const drawLines = (points, connections, color) => {
                outCtx.strokeStyle = color;
                connections.forEach(([i, j]) => {
                    if(points[i] && points[j]) {
                        // Same logic: x_draw = 1 - x_backend
                        outCtx.beginPath();
                        outCtx.moveTo((1 - points[i].x) * w, points[i].y * h);
                        outCtx.lineTo((1 - points[j].x) * w, points[j].y * h);
                        outCtx.stroke();
                    }
                });
            };

            if (landmarks.pose) {
               drawLines(landmarks.pose, POSE_CONNECTIONS, 'rgba(255, 255, 255, 0.5)');
               drawPoints(landmarks.pose, 'rgba(255, 255, 255, 0.8)');
            }
            if (landmarks.left_hand) {
                // Backend "left_hand" is the hand on the left side of the image (which is user's Right hand in mirror).
                // Let's stick to just drawing what we get.
               drawLines(landmarks.left_hand, HAND_CONNECTIONS, 'rgba(0, 255, 0, 0.8)');
               drawPoints(landmarks.left_hand, 'rgba(0, 255, 0, 1)');
            }
            if (landmarks.right_hand) {
               drawLines(landmarks.right_hand, HAND_CONNECTIONS, 'rgba(0, 0, 255, 0.8)');
               drawPoints(landmarks.right_hand, 'rgba(0, 0, 255, 1)');
            }
        }

        function drawClientSkeleton(multiHandLandmarks) {
            const w = outCanvas.width;
            const h = outCanvas.height;
            
            outCtx.lineWidth = 2;
            outCtx.lineCap = 'round';
            outCtx.lineJoin = 'round';

            // Helper to draw points
            const drawPoints = (landmarks, color) => {
                outCtx.fillStyle = color;
                for (let i = 0; i < landmarks.length; i++) {
                    const p = landmarks[i];
                    // Mirror X for display
                    const px = (1 - p.x) * w;
                    const py = p.y * h;
                    
                    outCtx.beginPath();
                    outCtx.arc(px, py, 3, 0, 2 * Math.PI);
                    outCtx.fill();
                }
            };

            // Helper to draw lines
            const drawLines = (landmarks, connections, color) => {
                outCtx.strokeStyle = color;
                for (let i = 0; i < connections.length; i++) {
                    const [startIdx, endIdx] = connections[i];
                    const start = landmarks[startIdx];
                    const end = landmarks[endIdx];
                    
                    if (start && end) {
                        outCtx.beginPath();
                        outCtx.moveTo((1 - start.x) * w, start.y * h);
                        outCtx.lineTo((1 - end.x) * w, end.y * h);
                        outCtx.stroke();
                    }
                }
            };

            for (const landmarks of multiHandLandmarks) {
                // Draw connections
                drawLines(landmarks, HAND_CONNECTIONS, 'rgba(0, 255, 255, 0.8)'); // Cyan for connections
                // Draw joints
                drawPoints(landmarks, 'rgba(255, 255, 255, 0.8)'); // White for joints
            }
        }

        async function predictFullFrame() {
            isProcessing = true;
            
            try {
                // Resize for speed (e.g. 640 width)
                const scaleCanvas = document.createElement('canvas');
                const scaleCtx = scaleCanvas.getContext('2d');
                const targetW = 640;
                const targetH = (video.videoHeight / video.videoWidth) * targetW;
                scaleCanvas.width = targetW;
                scaleCanvas.height = targetH;
                
                // IMPORTANT: Flip horizontally to match training video orientation
                // Training videos were recorded with mirrored preview, 
                // so we need to send mirrored frames to the API
                scaleCtx.translate(targetW, 0);
                scaleCtx.scale(-1, 1);
                scaleCtx.drawImage(video, 0, 0, targetW, targetH);
                scaleCtx.setTransform(1, 0, 0, 1, 0, 0); // Reset transform
                
                const base64Image = scaleCanvas.toDataURL('image/jpeg', 0.8);
                const formData = new FormData();
                formData.append('image_base64', base64Image);

                // Choose correct API endpoint based on current target type
                // For kalimat mode: use ABJAD API for letters, KATA API for words
                const currentTarget = window.targetWord ? window.targetWord[window.currentIndex] : '';
                const isLetterTarget = currentTarget && currentTarget.length === 1;
                const endpoint = isLetterTarget ? API_URL_ABJAD : API_URL_KATA;
                
                console.log(`[Kalimat] Target: "${currentTarget}", Type: ${isLetterTarget ? 'LETTER' : 'WORD'}, API: ${endpoint}`);
                const res = await fetch(endpoint, { method: 'POST', body: formData });
                const data = await res.json();
                
                if (data.status === 'recording') {
                    detectedPreview.innerHTML = '<span class="text-red-500 animate-pulse">● MEREKAM</span>';
                    confidencePreview.textContent = '...';
                } else if (data.status === 'processing') {
                     detectedPreview.innerHTML = '<span class="text-yellow-500 animate-pulse">⏳ MEMPROSES</span>';
                } else if (data.success && data.status === 'predicted') {
                    handlePredictionResult(data.label, data.confidence, data.candidates);
                } else if (data.status === 'idle') {
                    // Waiting for hand
                    detectedPreview.textContent = '-';
                    confidencePreview.textContent = '0%';
                }
                
                // Update landmarks for visualization
                if (data.landmarks && isPracticeRunning) {
                    latestBackendLandmarks = data.landmarks;
                } else {
                    latestBackendLandmarks = null;
                }
                
            } catch (err) {
                console.error(err);
            } finally {
                // Throttle slightly for Kata mode to ~15fps effectively if network is fast
                setTimeout(() => { isProcessing = false; }, 50);
            }
        }

        async function predictSmartCrop(allLandmarks) {
            isProcessing = true;
            const startTime = performance.now();

            try {
                let xMin = 1, xMax = 0, yMin = 1, yMax = 0;
                for (const landmarks of allLandmarks) {
                    for (const lm of landmarks) {
                        if(lm.x < xMin) xMin = lm.x;
                        if(lm.x > xMax) xMax = lm.x;
                        if(lm.y < yMin) yMin = lm.y;
                        if(lm.y > yMax) yMax = lm.y;
                    }
                }

                const padding = 0.05;
                xMin = Math.max(0, xMin - padding);
                xMax = Math.min(1, xMax + padding);
                yMin = Math.max(0, yMin - padding);
                yMax = Math.min(1, yMax + padding);

                const cropCanvas = document.createElement('canvas');
                cropCanvas.width = 224;
                cropCanvas.height = 224;
                const cropCtx = cropCanvas.getContext('2d');
                
                const imgW = outCanvas.width, imgH = outCanvas.height;
                const sx = xMin * imgW, sy = yMin * imgH;
                const sw = (xMax - xMin) * imgW, sh = (yMax - yMin) * imgH;

                cropCtx.fillStyle = "black";
                cropCtx.fillRect(0, 0, 224, 224);

                const aspect = sw / sh;
                let drawW = 224, drawH = 224, dx = 0, dy = 0;
                if (aspect > 1) { drawH = 224 / aspect; dy = (224 - drawH) / 2; } 
                else { drawW = 224 * aspect; dx = (224 - drawW) / 2; }

                cropCtx.drawImage(video, sx, sy, sw, sh, dx, dy, drawW, drawH);
                
                const base64Image = cropCanvas.toDataURL('image/jpeg', 0.8);
                const formData = new FormData();
                formData.append('image_base64', base64Image);

                const endpoint = (window.currentMode === 'kata') ? API_URL_KATA : API_URL_ABJAD;
                const res = await fetch(endpoint, { method: 'POST', body: formData });
                const data = await res.json();
                
                const latency = Math.round(performance.now() - startTime);
                const candCount = data.candidates ? data.candidates.length : 0;
                latencyPreview.textContent = `${latency}ms (${candCount})`;

                if (data.success) {
                    handlePredictionResult(data.label, data.confidence || 0, data.candidates);
                }
            } catch (err) {
                console.error(err);
            } finally {
                isProcessing = false;
            }
        }

        async function predictKalimat() {
            isProcessing = true;
            
            try {
                // Determine current target type (letter vs word)
                const currentTarget = targetWord[currentIndex] || '';
                const isLetterTarget = currentTarget.length === 1;
                
                console.log(`[Kalimat] Current target: "${currentTarget}", Type: ${isLetterTarget ? 'LETTER (CV)' : 'WORD (Landmark)'}`);
                
                if (isLetterTarget) {
                    // Clear any leftover MEREKAM status from word detection
                    // Letter detection is instant (no buffering)
                    // Letter detection is instant (no buffering)
                    detectedPreview.innerHTML = '<span class="text-xs font-bold text-teal-600 animate-pulse flex items-center gap-1">SCAN...</span>';
                    
                    // For LETTER targets: Use CV/CNN-based detection with smart crop (like abjad mode)
                    if (results && results.multiHandLandmarks && results.multiHandLandmarks.length > 0) {
                        // Calculate bounding box from hand landmarks
                        let xMin = 1, xMax = 0, yMin = 1, yMax = 0;
                        for (const landmarks of results.multiHandLandmarks) {
                            for (const lm of landmarks) {
                                if (lm.x < xMin) xMin = lm.x;
                                if (lm.x > xMax) xMax = lm.x;
                                if (lm.y < yMin) yMin = lm.y;
                                if (lm.y > yMax) yMax = lm.y;
                            }
                        }
                        
                        const padding = 0.05;
                        xMin = Math.max(0, xMin - padding);
                        xMax = Math.min(1, xMax + padding);
                        yMin = Math.max(0, yMin - padding);
                        yMax = Math.min(1, yMax + padding);

                        // Create cropped canvas centered on hands
                        const cropCanvas = document.createElement('canvas');
                        cropCanvas.width = 224;
                        cropCanvas.height = 224;
                        const cropCtx = cropCanvas.getContext('2d');
                        
                        const imgW = outCanvas.width, imgH = outCanvas.height;
                        const sx = xMin * imgW, sy = yMin * imgH;
                        const sw = (xMax - xMin) * imgW, sh = (yMax - yMin) * imgH;

                        cropCtx.fillStyle = "black";
                        cropCtx.fillRect(0, 0, 224, 224);

                        const aspect = sw / sh;
                        let drawW = 224, drawH = 224, dx = 0, dy = 0;
                        if (aspect > 1) { drawH = 224 / aspect; dy = (224 - drawH) / 2; } 
                        else { drawW = 224 * aspect; dx = (224 - drawW) / 2; }

                        cropCtx.drawImage(video, sx, sy, sw, sh, dx, dy, drawW, drawH);
                        
                        const base64Image = cropCanvas.toDataURL('image/jpeg', 0.8);
                        const formData = new FormData();
                        formData.append('image_base64', base64Image);

                        // Use ABJAD API for letter detection (CV-based)
                        const res = await fetch(API_URL_ABJAD, { method: 'POST', body: formData });
                        const data = await res.json();
                        
                        if (data.success) {
                            handlePredictionResult(data.label, data.confidence || 0, data.candidates);
                        }
                        
                        // No landmarks for CV mode
                        latestBackendLandmarks = null;
                    } else {
                        // No hands detected
                        detectedPreview.textContent = '-';
                        confidencePreview.textContent = '0%';
                    }
                } else {
                    // For WORD targets: Use landmark-based detection (like kata mode)
                    const scaleCanvas = document.createElement('canvas');
                    const scaleCtx = scaleCanvas.getContext('2d');
                    const targetW = 640;
                    const targetH = (video.videoHeight / video.videoWidth) * targetW;
                    scaleCanvas.width = targetW;
                    scaleCanvas.height = targetH;
                    
                    // IMPORTANT: Flip horizontally to match training video orientation
                    scaleCtx.translate(targetW, 0);
                    scaleCtx.scale(-1, 1);
                    scaleCtx.drawImage(video, 0, 0, targetW, targetH);
                    scaleCtx.setTransform(1, 0, 0, 1, 0, 0); // Reset transform
                    
                    const base64Image = scaleCanvas.toDataURL('image/jpeg', 0.8);
                    const formData = new FormData();
                    formData.append('image_base64', base64Image);

                    // Use KATA API for word detection (landmark-based)
                    const res = await fetch(API_URL_KATA, { method: 'POST', body: formData });
                    const data = await res.json();
                    
                    if (data.status === 'recording') {
                        detectedPreview.innerHTML = '<span class="text-xs font-bold text-red-500 animate-pulse flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-red-500"></span> REKAM</span>';
                        confidencePreview.textContent = '...';
                    } else if (data.status === 'processing') {
                         detectedPreview.innerHTML = '<span class="text-xs font-bold text-yellow-600 animate-pulse flex items-center gap-1"><svg class="w-3 h-3 spin" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-width="4" d="M12 4v4m0 0v4m0-4h4m-4 0H8"></path></svg> PROSES</span>';
                    } else if (data.success && data.status === 'predicted') {
                        handlePredictionResult(data.label, data.confidence, data.candidates);
                    } else if (data.status === 'idle') {
                        // Waiting for hand
                        detectedPreview.textContent = '-';
                        confidencePreview.textContent = '0%';
                    }
                    
                    // Update landmarks for visualization (only for word mode)
                    if (data.landmarks && isPracticeRunning) {
                        latestBackendLandmarks = data.landmarks;
                    } else {
                        latestBackendLandmarks = null;
                    }
                }
                
            } catch (err) {
                console.error(err);
            } finally {
                // Throttle slightly for Kalimat mode to ~15fps effectively if network is fast
                setTimeout(() => { isProcessing = false; }, 50);
            }
        }



        function handlePredictionResult(label, confidence, candidates = []) {
            detectedPreview.textContent = label;
            confidencePreview.textContent = `${confidence}%`;
            
            // Use mode-specific threshold
            const threshold = (window.currentMode === 'kata') ? REQUIRED_CONFIDENCE_KATA : REQUIRED_CONFIDENCE;
            
            if (confidence >= threshold) {
                detectedPreview.className = "text-5xl font-black text-teal-600 leading-none mt-1 animate-pulse";
                confidencePreview.classList.remove('text-slate-400');
                confidencePreview.classList.add('text-teal-600');
            } else {
                detectedPreview.className = "text-5xl font-black text-slate-800 leading-none mt-1";
                confidencePreview.classList.remove('text-teal-600');
                confidencePreview.classList.add('text-slate-400');
            }



            if (!isPracticeRunning || currentIndex >= targetWord.length) return;

            const targetChar = targetWord[currentIndex];
            
            // Determine logic based on target type (Word vs Letter)
            // If length > 1, treat as Word (use Top 5 logic)
            // If mode is 'kata', always treat as Word
            const isWordTarget = (targetChar.length > 1) || (window.currentMode === 'kata');

            if (isWordTarget) {
                // Word Logic: Check if target word is in any of the top 5 candidates
                const targetLower = targetChar.toLowerCase();
                const foundInTop5 = candidates.some(c => c.label.toLowerCase() === targetLower);
                
                // Also check if confidence is sufficient (using kata threshold)
                // Note: candidates usually imply valid detection, but we can double check matched candidate confidence
                const matchedCandidate = candidates.find(c => c.label.toLowerCase() === targetLower);
                const isConfident = matchedCandidate && matchedCandidate.confidence >= REQUIRED_CONFIDENCE_KATA;

                if (foundInTop5 || isConfident) {
                    console.log(`[Word Match] Target "${targetChar}" found in top 5 OR confidence sufficient!`);
                    handleCorrectHit();
                } else if (confidence >= REQUIRED_CONFIDENCE_KATA) {
                     // High confidence but wrong word
                     handleWrongHit();
                }
            } else {
                // Abjad/Letter Logic: exact top-1 match with standard threshold
                const labelMatch = (label === targetChar);
                if (labelMatch && confidence >= threshold) {
                    handleCorrectHit();
                } else if (confidence >= threshold) {
                     // High confidence but wrong letter
                     handleWrongHit();
                }
            }
        }

        let lastWrongTime = 0;
        function handleWrongHit() {
            const now = Date.now();
            // Cooldown 1.5 seconds to prevent spam
            if (now - lastWrongTime < 1500) return;
            
            lastWrongTime = now;
            const overlay = document.getElementById('wrong-overlay');
            
            overlay.style.opacity = '1';
            overlay.classList.add('scale-110');
            setTimeout(() => {
                overlay.classList.remove('scale-110');
            }, 100);

            // Hide quickly (user requested "cepat")
            setTimeout(() => {
                overlay.style.opacity = '0';
            }, 600); 
        }

        function handleCorrectHit() {
            feedbackOverlay.style.opacity = '1';
            setTimeout(() => feedbackOverlay.style.opacity = '0', 800);
            
            currentIndex++;
            // Sync to window for drawResults bounding box
            window.currentIndex = currentIndex;
            updateProgress();
            renderWordSlots();
            
            if (currentIndex >= targetWord.length) {
                setTimeout(handleWin, 500); 
            }
        }

        async function handleWin() {
            // Clear detection overlays immediately when practice ends
            isPracticeRunning = false;
            latestBackendLandmarks = null;
            detectedPreview.textContent = '-';
            confidencePreview.textContent = '-';
            
            // Save session to database
            let completedWord;
            
            if (window.currentMode === 'kalimat') {
                 // Reconstruct sentence: consecutive single letters join to form words
                 let parts = [];
                 let currentBuffer = '';
                 
                 targetWord.forEach(item => {
                     if (item.length > 1) {
                         // It's a word
                         if (currentBuffer) { parts.push(currentBuffer); currentBuffer = ''; }
                         parts.push(item);
                     } else {
                         // It's a letter
                         currentBuffer += item;
                     }
                 });
                 if (currentBuffer) parts.push(currentBuffer);
                 
                 completedWord = parts.join(' ');
            } else {
                 const separator = (window.currentMode === 'kata') ? ' ' : '';
                 completedWord = targetWord.join(separator);
            }
            
            await saveSession(completedWord, sessionSeconds);

            // NLG: Fetch Natural Text
            let naturalText = `Latihan "${completedWord}" Selesai!`;
            let nlgSubtitle = "Latihan Selesai";
            
            if (window.currentMode === 'kata') {
                try {
                    const nlgRes = await fetch(API_URL_NLG, {
                         method: 'POST',
                         headers: { 'Content-Type': 'application/json' },
                         body: JSON.stringify({ 
                             tokens: targetWord, 
                             token_types: targetWord.map(() => 'SIGN'),
                             mode: 'natural'
                         })
                    });
                    const nlgData = await nlgRes.json();
                    if (nlgData.success && nlgData.natural_text) {
                        naturalText = `"${nlgData.natural_text}"`;
                        nlgSubtitle = "Kalimat Natural:";
                    }
                } catch(e) { console.error("NLG Error", e); }
            }

            // Motivational messages
            const messages = [
                '🌟 Luar Biasa!',
                '🎯 Sempurna!',
                '💪 Hebat Sekali!',
                '✨ Menakjubkan!',
                '🔥 Keren Banget!'
            ];
            const randomMsg = messages[Math.floor(Math.random() * messages.length)];

            wordContainer.innerHTML = `
                <div class="w-full py-6 text-center relative overflow-hidden rounded-2xl bg-gradient-to-br from-teal-50 via-emerald-50 to-cyan-50 border border-teal-200/50 shadow-lg">
                    <!-- Simple Glow Background -->
                    <div class="absolute inset-0 pointer-events-none">
                        <div class="absolute -top-8 -left-8 w-24 h-24 bg-teal-400/20 rounded-full blur-2xl"></div>
                        <div class="absolute -bottom-8 -right-8 w-28 h-28 bg-emerald-400/20 rounded-full blur-2xl"></div>
                    </div>
                    
                    <!-- Main Content -->
                    <div class="relative z-10 px-4">
                        <!-- Emoji -->
                        <div class="text-5xl mb-3 animate-bounce" style="animation-duration: 0.8s;">🎉</div>
                        
                        <!-- Title -->
                        <h3 class="text-2xl font-black mb-2 bg-gradient-to-r from-teal-600 to-emerald-600 bg-clip-text text-transparent">${randomMsg}</h3>
                        
                        <!-- Subtitle (NLG) -->
                        <div class="mb-4">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">${nlgSubtitle}</p>
                            <p class="text-lg font-bold text-teal-800 leading-snug">
                                ${naturalText}
                            </p>
                        </div>
                        
                        <!-- Time Badge -->
                        <div class="inline-flex items-center gap-2 bg-white/80 backdrop-blur px-4 py-2 rounded-xl shadow-sm border border-teal-100 mb-4">
                            <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span class="font-bold text-slate-700 text-sm">${document.getElementById('session-timer').textContent}</span>
                        </div>
                        
                        <!-- Compact Badges Row -->
                        <div class="flex justify-center items-center gap-2 text-xs">
                            <span class="inline-flex items-center gap-1 bg-yellow-100 text-yellow-700 px-2 py-1 rounded-lg font-bold border border-yellow-200">
                                ⭐ 100%
                            </span>
                            <span class="text-slate-300">•</span>
                            <span class="inline-flex items-center gap-1 bg-teal-100 text-teal-700 px-2 py-1 rounded-lg font-bold border border-teal-200">
                                ⚡ Berlatih!
                            </span>
                        </div>
                    </div>
                </div>
            `;
            isPracticeRunning = false;
            
            // Show custom modal instead of browser confirm
            await new Promise(r => setTimeout(r, 2000));
            showCompletionModal(randomMsg, `Latihan selesai! Mau coba kata lain?`, naturalText);
        }

        function showCompletionModal(title, message, result = null) {
            const modal = document.getElementById('completion-modal');
            const modalContent = document.getElementById('completion-modal-content');
            document.getElementById('completion-title').textContent = title;
            document.getElementById('completion-message').textContent = message;
            
            // Handle Result Display
            const resultContainer = document.getElementById('completion-result');
            if (result && resultContainer) {
                resultContainer.classList.remove('hidden');
                document.getElementById('completion-result-text').textContent = result.replace(/"/g, ''); // Remove quotes for cleaner look
            } else if (resultContainer) {
                resultContainer.classList.add('hidden');
            }
            
            modal.classList.remove('opacity-0', 'pointer-events-none');
            modalContent.classList.remove('scale-90');
            modalContent.classList.add('scale-100');
        }

        function closeCompletionModal(tryAgain) {
            const modal = document.getElementById('completion-modal');
            const modalContent = document.getElementById('completion-modal-content');
            
            modal.classList.add('opacity-0', 'pointer-events-none');
            modalContent.classList.remove('scale-100');
            modalContent.classList.add('scale-90');
            
            if (tryAgain) {
                setTimeout(() => resetGame(), 300);
            }
        }

        // Camera is now initialized in initCamera() called by permission flow
        // The global 'camera' variable is defined at the top of the script.

        // === HISTORY FUNCTIONALITY ===
        function toggleHistory() {
            const sidebar = document.getElementById('history-sidebar');
            const backdrop = document.getElementById('history-backdrop');
            const isOpen = !sidebar.classList.contains('translate-x-full');
            
            if (isOpen) {
                sidebar.classList.add('translate-x-full');
                backdrop.classList.add('opacity-0', 'pointer-events-none');
            } else {
                sidebar.classList.remove('translate-x-full');
                backdrop.classList.remove('opacity-0', 'pointer-events-none');
                // Load/refresh history when opening sidebar
                loadHistory();
            }
        }

        async function loadHistory() {
            try {
                const response = await fetch('{{ route("latihan.history") }}');
                const data = await response.json();
                const container = document.getElementById('history-container');

                if (data.success && data.sessions.length > 0) {
                    container.innerHTML = data.sessions.map(session => {
                        const badgeColors = {
                            'excellent': 'bg-teal-100 text-teal-700 border-teal-200',
                            'good': 'bg-blue-100 text-blue-700 border-blue-200',
                            'fair': 'bg-slate-100 text-slate-600 border-slate-200'
                        };
                        const badgeClass = badgeColors[session.accuracy_badge] || badgeColors.fair;

                        return `
                            <div class="bg-slate-50 rounded-xl p-4 border border-slate-100 hover:border-teal-200 transition-colors">
                                <div class="flex justify-between items-start mb-2">
                                    <div class="font-bold text-lg text-slate-800">${session.word}</div>
                                    <div class="${badgeClass} px-2 py-1 rounded-lg text-xs font-bold border">
                                        ${Math.round(session.accuracy_percentage)}%
                                    </div>
                                </div>
                                <div class="flex justify-between text-xs text-slate-500">
                                    <span>⏱️ ${session.formatted_duration}</span>
                                    <span>${session.completed_ago}</span>
                                </div>
                            </div>
                        `;
                    }).join('');
                } else {
                    container.innerHTML = `
                        <div class="text-center py-12 text-slate-400">
                            <svg class="w-16 h-16 mx-auto mb-4 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                            <p class="font-semibold text-slate-600 mb-1">Belum ada riwayat</p>
                            <p class="text-sm">Mulai latihan sekarang untuk melihat riwayat Anda!</p>
                        </div>
                    `;
                }
            } catch (error) {
                console.error('Load history error:', error);
                document.getElementById('history-container').innerHTML = `
                    <div class="text-center py-8 text-red-400">
                        <p class="text-sm">❌ Gagal memuat riwayat</p>
                    </div>
                `;
            }
        }

        async function saveSession(word, duration) {
            console.log('Saving session:', word, duration);
            try {
                const url = '{{ route("latihan.save-session") }}';
                console.log('POST to:', url);
                
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        word: word,
                        duration: duration,
                        accuracy_percentage: 100
                    })
                });

                console.log('Response status:', response.status);
                const data = await response.json();
                console.log('Response data:', data);
                
                if (data.success) {
                    console.log('✓ Session saved successfully!');
                    setTimeout(() => loadHistory(), 500); // Refresh history after short delay
                } else {
                    console.error('Save failed:', data.error);
                }
            } catch (error) {
                console.error('Save session error:', error);
            }
        }

        // Load history on page load (if in game area)
        if (!document.getElementById('game-area').classList.contains('hidden')) {
            loadHistory();
        }

        // Expose toggleHistory to global scope
        window.toggleHistory = toggleHistory;
        window.loadHistory = loadHistory; // FIX: Expose for selectMode
        window.showCompletionModal = showCompletionModal;
        window.closeCompletionModal = closeCompletionModal;

        // === CLEANUP HANDLERS ===
        function cleanupResources() {
            // Stop Timer
            if (sessionInterval) clearInterval(sessionInterval);
            
            // Stop Camera
            if (typeof camera !== 'undefined' && camera) {
                try { camera.stop(); } catch(e) { console.warn('Camera stop error:', e); }
            }
            
            // Stop Video Stream
            const videoEl = document.getElementById('video');
            if (videoEl && videoEl.srcObject) {
                videoEl.srcObject.getTracks().forEach(track => track.stop());
                videoEl.srcObject = null;
            }
            
            isPracticeRunning = false;
        }

        window.addEventListener('beforeunload', cleanupResources);
        window.addEventListener('pagehide', cleanupResources);
        
        // Toast Notification System
        function showToast(message, type = 'error') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            
            // Icons & Colors
            const isError = type === 'error';
            const bgClass = isError ? 'bg-red-50 border-red-200 text-red-800' : 'bg-teal-50 border-teal-200 text-teal-800';
            const icon = isError ? 
                '<svg class="w-5 h-5 text-red-500" flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>' : 
                '<svg class="w-5 h-5 text-teal-500" flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>';

            toast.className = `${bgClass} flex items-center gap-3 px-4 py-3 rounded-xl border shadow-lg transform transition-all duration-300 translate-y-[-20px] opacity-0 pointer-events-auto`;
            toast.innerHTML = `
                ${icon}
                <span class="font-bold text-sm text-balance">${message}</span>
            `;

            container.appendChild(toast);

            // Animate In
            requestAnimationFrame(() => {
                toast.classList.remove('translate-y-[-20px]', 'opacity-0');
            });

            // Remove after 3s
            setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-y-[-20px]');
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }
    });
</script>
@endsection

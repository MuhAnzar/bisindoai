{{-- Hero Section untuk User yang sudah login - dengan dekorasi wave dan dots --}}
@props(['streak' => 3, 'score' => 150, 'level' => 2])

<div class="hero-section-auth relative overflow-hidden rounded-2xl md:rounded-3xl mb-8 md:mb-12">
    {{-- Background --}}
    <div class="absolute inset-0 bg-gradient-to-br from-[#57BBA0] to-[#45A38A]"></div>
    
    {{-- Decorative Wave Lines --}}
    <svg class="hero-wave-lines" viewBox="0 0 800 400" preserveAspectRatio="none">
        <path d="M-50,300 Q150,100 350,250 T750,150 T1150,300" fill="none" stroke="rgba(255,255,255,0.15)" stroke-width="2"/>
        <path d="M-50,320 Q150,120 350,270 T750,170 T1150,320" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1.5"/>
        <path d="M-50,280 Q200,80 400,230 T800,130 T1200,280" fill="none" stroke="rgba(255,255,255,0.08)" stroke-width="1"/>
    </svg>
    
    {{-- Fill wave shape on the left --}}
    <svg class="hero-wave-fill" viewBox="0 0 400 400" preserveAspectRatio="none">
        <path d="M0,400 L0,200 Q100,50 200,150 T400,100 L400,400 Z" fill="rgba(255,255,255,0.05)"/>
    </svg>

    {{-- Decorative Dots Pattern --}}
    <div class="hero-dots-pattern-auth"></div>
    
    {{-- Decorative circles --}}
    <div class="absolute top-0 right-0 w-32 h-32 md:w-48 md:h-48 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
    <div class="absolute bottom-0 left-0 w-24 h-24 md:w-36 md:h-36 bg-white/10 rounded-full translate-y-1/2 -translate-x-1/2"></div>
    
    <div class="relative z-10 p-6 md:p-8 lg:p-10">
        <div class="flex flex-col lg:flex-row items-center gap-6 lg:gap-12">
            {{-- LEFT: TEXT CONTENT --}}
            <div class="lg:w-1/2 text-center lg:text-left">
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-white/20 backdrop-blur-sm rounded-full text-white text-xs md:text-sm font-semibold mb-4 md:mb-6">
                    <span class="w-2 h-2 bg-yellow-400 rounded-full animate-pulse"></span>
                    Selamat Datang Kembali!
                </div>
                
                <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-white leading-tight mb-3 md:mb-4">
                    Halo, <span class="text-yellow-300">{{ explode(' ', Auth::user()->nama)[0] }}</span>! 👋
                </h1>
                
                <p class="text-white/90 text-sm md:text-base lg:text-lg mb-6 md:mb-8 leading-relaxed max-w-lg mx-auto lg:mx-0">
                    Kuasai bahasa isyarat Indonesia dengan teknologi AI. Latih isyarat tanganmu dan dapatkan feedback instan.
                </p>
                
                <div class="flex flex-col sm:flex-row gap-3 md:gap-4 justify-center lg:justify-start">
                    <a href="{{ route('latihan.deteksi') }}" class="inline-flex items-center justify-center gap-2 md:gap-3 px-6 py-3 md:px-8 md:py-4 bg-white text-[#57BBA0] font-bold text-sm md:text-base lg:text-lg rounded-xl shadow-lg hover:-translate-y-1 hover:shadow-xl transition-all">
                        Mulai Latihan
                        <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>
                    <a href="{{ route('kamus.abjad') }}" class="inline-flex items-center justify-center gap-2 md:gap-3 px-6 py-3 md:px-8 md:py-4 bg-white/20 backdrop-blur-sm text-white font-bold text-sm md:text-base lg:text-lg rounded-xl border-2 border-white/30 hover:bg-white/30 hover:-translate-y-1 transition-all">
                        <svg class="w-4 h-4 md:w-5 md:h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                        Lihat Kamus
                    </a>
                </div>
            </div>
            
            {{-- RIGHT: STATS & CHARACTER --}}
            <div class="lg:w-1/2 flex flex-col items-center lg:items-end gap-6">
                {{-- Stats Cards --}}
                <div class="flex gap-3 md:gap-4 flex-wrap justify-center lg:justify-end">
                    <div class="bg-white/20 backdrop-blur-sm rounded-xl p-3 md:p-4 text-center min-w-[80px] md:min-w-[100px]">
                        <div class="flex items-center justify-center gap-1 md:gap-2 mb-1">
                            <svg class="w-5 h-5 md:w-6 md:h-6 text-orange-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12.01 2c-.2 0-.4.05-.57.15C9.34 3.45 7.88 5.8 7.88 8.5c0 1.55.6 3 1.63 4.12L8.4 14.5c-.23.35-.4.7-.4 1.1V17c0 1.1.9 2 2 2h4c1.1 0 2-.9 2-2v-1.4c0-.4-.17-.75-.4-1.1l-1.11-1.88c1.03-1.12 1.63-2.57 1.63-4.12 0-2.7-1.46-5.05-3.56-6.35-.16-.1-.35-.15-.55-.15z"/>
                            </svg>
                            <span class="text-xl md:text-2xl font-bold text-white">{{ $streak }}</span>
                        </div>
                        <p class="text-[10px] md:text-xs text-white/80 font-medium uppercase tracking-wide">Streak</p>
                    </div>
                    <div class="bg-white/20 backdrop-blur-sm rounded-xl p-3 md:p-4 text-center min-w-[80px] md:min-w-[100px]">
                        <div class="flex items-center justify-center gap-1 md:gap-2 mb-1">
                            <svg class="w-5 h-5 md:w-6 md:h-6 text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                            <span class="text-xl md:text-2xl font-bold text-white">{{ $score }}</span>
                        </div>
                        <p class="text-[10px] md:text-xs text-white/80 font-medium uppercase tracking-wide">Poin</p>
                    </div>
                    <div class="bg-white/20 backdrop-blur-sm rounded-xl p-3 md:p-4 text-center min-w-[80px] md:min-w-[100px]">
                        <div class="flex items-center justify-center gap-1 md:gap-2 mb-1">
                            <svg class="w-5 h-5 md:w-6 md:h-6 text-amber-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M5 16L3 5l5.5 5L12 4l3.5 6L21 5l-2 11H5zm14 3c0 .6-.4 1-1 1H6c-.6 0-1-.4-1-1v-1h14v1z"/>
                            </svg>
                            <span class="text-xl md:text-2xl font-bold text-white">{{ $level }}</span>
                        </div>
                        <p class="text-[10px] md:text-xs text-white/80 font-medium uppercase tracking-wide">Level</p>
                    </div>
                </div>
                
                {{-- Character/Illustration --}}
                <div class="hidden md:block">
                    <img src="{{ asset('img/bisindo-character.png') }}" 
                         alt="BISINDO Character" 
                         class="w-40 lg:w-56 xl:w-64 animate-float drop-shadow-xl">
                </div>
            </div>
        </div>
    </div>
</div>

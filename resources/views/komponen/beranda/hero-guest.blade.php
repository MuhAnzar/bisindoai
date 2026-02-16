{{-- Hero Section untuk Guest - dengan dekorasi wave dan dots --}}
<div class="hero-section">
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
    <div class="hero-dots-pattern"></div>

    {{-- Decorative Circles --}}
    <div class="hero-circle hero-circle-1"></div>
    <div class="hero-circle hero-circle-2"></div>
    <div class="hero-circle hero-circle-3"></div>

    <div class="container mx-auto px-4 sm:px-6 relative z-10">
        <div class="flex flex-col lg:flex-row items-center gap-8 lg:gap-12 xl:gap-16">
            {{-- LEFT: TEXT CONTENT --}}
            <div class="lg:w-1/2 text-center lg:text-left animate-in order-2 lg:order-1">
                <h1 class="text-responsive-heading font-bold text-white leading-tight mb-4 md:mb-6">
                    Teknologi Akses Bahasa Isyarat<br class="hidden sm:block">
                    <span class="text-yellow-300">untuk Segala Kebutuhanmu</span>
                </h1>
                
                <p class="text-white/90 text-base md:text-lg mb-6 md:mb-8 max-w-lg mx-auto lg:mx-0 leading-relaxed">
                    Belajar Bahasa Isyarat Indonesia dengan teknologi AI. Dapatkan koreksi real-time dan tingkatkan kemampuanmu setiap hari.
                </p>
                
                {{-- Action Buttons --}}
                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center lg:justify-start mb-6 md:mb-8">
                    <a href="{{ route('daftar') }}" class="app-store-btn">
                        <svg class="w-6 h-6 md:w-7 md:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                        <div class="text-left">
                            <div class="text-xs opacity-80">Bergabung Sekarang</div>
                            <div class="font-semibold text-sm md:text-base">Daftar</div>
                        </div>
                    </a>
                    <a href="{{ route('masuk') }}" class="app-store-btn">
                        <svg class="w-6 h-6 md:w-7 md:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div class="text-left">
                            <div class="text-xs opacity-80">Sudah Punya Akun?</div>
                            <div class="font-semibold text-sm md:text-base">Mulai</div>
                        </div>
                    </a>
                </div>

                {{-- Stats --}}
                <div class="hero-stats text-white/90">
                    <div class="text-center">
                        <div class="text-xl md:text-2xl font-bold">2,500+</div>
                        <div class="text-xs md:text-sm opacity-80">Pengguna Aktif</div>
                    </div>
                    <div class="hero-stats-divider"></div>
                    <div class="text-center">
                        <div class="text-xl md:text-2xl font-bold">100+</div>
                        <div class="text-xs md:text-sm opacity-80">Kata Isyarat</div>
                    </div>
                    <div class="hero-stats-divider"></div>
                    <div class="text-center">
                        <div class="text-xl md:text-2xl font-bold">AI</div>
                        <div class="text-xs md:text-sm opacity-80">Powered</div>
                    </div>
                </div>
            </div>
            
            {{-- RIGHT: 3D CHARACTER IMAGE --}}
            <div class="lg:w-1/2 flex justify-center animate-in delay-2 order-1 lg:order-2 lg:pr-8">
                <div class="relative">
                    {{-- Character Image --}}
                    <img src="{{ asset('img/home.png') }}" 
                         alt="BISINDO AI Character" 
                         class="w-48 sm:w-64 md:w-80 lg:w-96 xl:w-[450px] animate-float drop-shadow-2xl">
                    
                    {{-- Floating Cards - hidden on mobile --}}
                    <div class="floating-card -left-2 md:-left-4 top-1/4 animate-float" style="animation-delay: 0.5s;">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 md:w-10 md:h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 md:w-5 md:h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <div>
                                <div class="text-[10px] md:text-xs text-gray-500">Akurasi</div>
                                <div class="font-bold text-gray-800 text-sm md:text-base">95%+</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="floating-card -right-2 md:-right-4 bottom-1/4 animate-float" style="animation-delay: 1s;">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 md:w-10 md:h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 md:w-5 md:h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="text-[10px] md:text-xs text-gray-500">Real-time</div>
                                <div class="font-bold text-gray-800 text-sm md:text-base">Detection</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Layanan Produk Kami Section - Fully Responsive dengan Background --}}
<div class="relative py-10 md:py-14 lg:py-16 animate-in delay-3 -mx-4 sm:-mx-6 px-4 sm:px-6" style="background-image: url('{{ asset('img/pelatihan-bahasa-isyarat.png') }}'); background-size: cover; background-position: center;">
    {{-- Overlay --}}
    <div class="absolute inset-0 bg-gradient-to-br from-white/95 via-white/90 to-[#E8F7F3]/95"></div>
    
    <div class="relative z-10">
        <h2 class="section-title text-center">Layanan Produk Kami</h2>
        <p class="section-subtitle text-center px-4">
            Berbagai fitur canggih untuk membantu Anda belajar dan berkomunikasi dengan bahasa isyarat
        </p>
    
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 md:gap-6 lg:gap-8 max-w-5xl mx-auto">
        {{-- Card 1: Terjemah Realtime --}}
        <div class="feature-card">
            <div class="flex flex-col sm:flex-row gap-4 sm:gap-6">
                <div class="flex-shrink-0">
                    <div class="feature-card-icon teal">
                        <svg class="w-6 h-6 md:w-7 md:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
                <div>
                    <h3 class="text-lg md:text-xl font-bold text-gray-800 mb-2 md:mb-3">Terjemah Realtime</h3>
                    <p class="text-gray-600 text-sm md:text-base leading-relaxed">
                        Teknologi AI kami mendeteksi dan menerjemahkan gerakan isyarat tangan Anda secara real-time dengan akurasi tinggi.
                    </p>
                </div>
            </div>
            <div class="mt-4 md:mt-6">
                <img src="{{ asset('img/home.png') }}" alt="Terjemah Realtime" class="rounded-lg md:rounded-xl w-full h-36 md:h-48 object-cover">
            </div>
        </div>
        
        {{-- Card 2: Layanan Informasi Bahasa Isyarat --}}
        <div class="feature-card">
            <div class="flex flex-col sm:flex-row gap-4 sm:gap-6">
                <div class="flex-shrink-0">
                    <div class="feature-card-icon blue">
                        <svg class="w-6 h-6 md:w-7 md:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                </div>
                <div>
                    <h3 class="text-lg md:text-xl font-bold text-gray-800 mb-2 md:mb-3">Layanan Informasi Bahasa Isyarat</h3>
                    <p class="text-gray-600 text-sm md:text-base leading-relaxed">
                        Akses kamus visual lengkap dengan video demonstrasi untuk setiap huruf dan kata.
                    </p>
                </div>
            </div>
            <div class="mt-4 md:mt-6">
                <img src="{{ asset('img/home.png') }}" alt="Informasi Bahasa Isyarat" class="rounded-lg md:rounded-xl w-full h-36 md:h-48 object-cover">
            </div>
        </div>
        
        {{-- Card 3: Konten Video dan Berita --}}
        <div class="feature-card">
            <div class="flex flex-col sm:flex-row gap-4 sm:gap-6">
                <div class="flex-shrink-0">
                    <div class="feature-card-icon purple">
                        <svg class="w-6 h-6 md:w-7 md:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"/>
                        </svg>
                    </div>
                </div>
                <div>
                    <h3 class="text-lg md:text-xl font-bold text-gray-800 mb-2 md:mb-3">Konten Video dan Berita Isyarat</h3>
                    <p class="text-gray-600 text-sm md:text-base leading-relaxed">
                        Tonton video pembelajaran interaktif dan ikuti perkembangan terbaru komunitas tuli.
                    </p>
                </div>
            </div>
            <div class="mt-4 md:mt-6">
                <img src="{{ asset('img/home.png') }}" alt="Video dan Berita" class="rounded-lg md:rounded-xl w-full h-36 md:h-48 object-cover">
            </div>
        </div>
        
        {{-- Card 4: Website Terintegrasi --}}
        <div class="feature-card">
            <div class="flex flex-col sm:flex-row gap-4 sm:gap-6">
                <div class="flex-shrink-0">
                    <div class="feature-card-icon orange">
                        <svg class="w-6 h-6 md:w-7 md:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
                <div>
                    <h3 class="text-lg md:text-xl font-bold text-gray-800 mb-2 md:mb-3">Website Terintegrasi</h3>
                    <p class="text-gray-600 text-sm md:text-base leading-relaxed">
                        Platform berbasis web yang dapat diakses dari mana saja dengan tracking progress.
                    </p>
                </div>
            </div>
            <div class="mt-4 md:mt-6">
                <img src="{{ asset('img/home.png') }}" alt="Website Terintegrasi" class="rounded-lg md:rounded-xl w-full h-36 md:h-48 object-cover">
            </div>
        </div>
    </div>
    </div>
</div>

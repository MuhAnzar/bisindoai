{{-- Feature Cards Section - Grid cards fitur utama dengan tema #57BBA0 --}}
<div class="mb-8 md:mb-12 section-reveal">
    <h2 class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-bold text-center mb-6 md:mb-8 fade-up-stagger">
        Mulai <span class="text-[#57BBA0]">Belajar</span> Sekarang
    </h2>
    
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
        {{-- Card 1: Kamus Isyarat --}}
        <a href="{{ route('kamus.abjad') }}" class="group relative overflow-hidden rounded-xl md:rounded-2xl bg-white border border-gray-100 p-5 md:p-6 shadow-sm hover:shadow-xl transition-all duration-300 fade-up-stagger stagger-1">
            <div class="absolute inset-0 bg-gradient-to-br from-[#57BBA0] to-[#45A38A] opacity-0 group-hover:opacity-100 transition-all duration-300"></div>
            <div class="relative z-10">
                <div class="w-12 h-12 md:w-14 md:h-14 lg:w-16 lg:h-16 mb-4 rounded-xl flex items-center justify-center bg-[#E8F7F3] group-hover:bg-white/20 transition-all">
                    <svg class="w-6 h-6 md:w-7 md:h-7 lg:w-8 lg:h-8 text-[#57BBA0] group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <h3 class="text-base md:text-lg font-bold mb-2 text-gray-800 group-hover:text-white transition-colors">Kamus Isyarat A-Z</h3>
                <p class="text-gray-500 text-xs md:text-sm group-hover:text-white/80 transition-colors leading-relaxed">Pelajari abjad dan kata dasar bahasa isyarat</p>
                <div class="mt-4 flex items-center text-[#57BBA0] group-hover:text-white text-xs md:text-sm font-semibold">
                    <span>Pelajari</span>
                    <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </div>
        </a>

        {{-- Card 2: Kuis Interaktif --}}
        <a href="{{ route('kuis.index') }}" class="group relative overflow-hidden rounded-xl md:rounded-2xl bg-white border border-gray-100 p-5 md:p-6 shadow-sm hover:shadow-xl transition-all duration-300 fade-up-stagger stagger-2">
            <div class="absolute inset-0 bg-gradient-to-br from-[#0EA5E9] to-[#0284C7] opacity-0 group-hover:opacity-100 transition-all duration-300"></div>
            <div class="relative z-10">
                <div class="w-12 h-12 md:w-14 md:h-14 lg:w-16 lg:h-16 mb-4 rounded-xl flex items-center justify-center bg-blue-50 group-hover:bg-white/20 transition-all">
                    <svg class="w-6 h-6 md:w-7 md:h-7 lg:w-8 lg:h-8 text-blue-500 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                </div>
                <h3 class="text-base md:text-lg font-bold mb-2 text-gray-800 group-hover:text-white transition-colors">Kuis Interaktif</h3>
                <p class="text-gray-500 text-xs md:text-sm group-hover:text-white/80 transition-colors leading-relaxed">Uji pemahaman isyaratmu dengan kuis seru</p>
                <div class="mt-4 flex items-center text-blue-500 group-hover:text-white text-xs md:text-sm font-semibold">
                    <span>Mulai Kuis</span>
                    <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </div>
        </a>

        {{-- Card 3: Kosakata Harian --}}
        <a href="{{ route('kamus.kata-dasar') }}" class="group relative overflow-hidden rounded-xl md:rounded-2xl bg-white border border-gray-100 p-5 md:p-6 shadow-sm hover:shadow-xl transition-all duration-300 fade-up-stagger stagger-3 sm:col-span-2 lg:col-span-1">
            <div class="absolute inset-0 bg-gradient-to-br from-[#F59E0B] to-[#D97706] opacity-0 group-hover:opacity-100 transition-all duration-300"></div>
            <div class="relative z-10">
                <div class="w-12 h-12 md:w-14 md:h-14 lg:w-16 lg:h-16 mb-4 rounded-xl flex items-center justify-center bg-amber-50 group-hover:bg-white/20 transition-all">
                    <svg class="w-6 h-6 md:w-7 md:h-7 lg:w-8 lg:h-8 text-amber-500 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                </div>
                <h3 class="text-base md:text-lg font-bold mb-2 text-gray-800 group-hover:text-white transition-colors">Kosakata Harian</h3>
                <p class="text-gray-500 text-xs md:text-sm group-hover:text-white/80 transition-colors leading-relaxed">Kata-kata yang sering digunakan sehari-hari</p>
                <div class="mt-4 flex items-center text-amber-500 group-hover:text-white text-xs md:text-sm font-semibold">
                    <span>Lihat Kosakata</span>
                    <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </div>
        </a>
    </div>
</div>

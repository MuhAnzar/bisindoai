{{-- AI Practice Card - CTA untuk latihan deteksi AI dengan tema #57BBA0 --}}
<div class="section-reveal mb-8 md:mb-12">
    <div class="relative overflow-hidden rounded-2xl md:rounded-3xl" style="background: linear-gradient(135deg, #57BBA0 0%, #45A38A 50%, #3D9A7D 100%);">
        {{-- Wave decorative lines --}}
        <svg class="absolute inset-0 w-full h-full opacity-20" viewBox="0 0 800 300" preserveAspectRatio="none">
            <path d="M-50,200 Q150,50 350,150 T750,100 T1150,200" fill="none" stroke="rgba(255,255,255,0.3)" stroke-width="2"/>
            <path d="M-50,220 Q150,70 350,170 T750,120 T1150,220" fill="none" stroke="rgba(255,255,255,0.2)" stroke-width="1.5"/>
            <path d="M-50,180 Q200,30 400,130 T800,80 T1200,180" fill="none" stroke="rgba(255,255,255,0.15)" stroke-width="1"/>
        </svg>
        
        {{-- Dots pattern --}}
        <div class="absolute right-8 top-1/2 -translate-y-1/2 w-32 h-24 md:w-40 md:h-32 opacity-30" style="background-image: radial-gradient(circle, rgba(255,255,255,0.5) 2px, transparent 2px); background-size: 16px 16px;"></div>
        
        {{-- Decorative circles --}}
        <div class="absolute top-0 right-0 w-48 h-48 md:w-72 md:h-72 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/4"></div>
        <div class="absolute bottom-0 left-0 w-32 h-32 md:w-56 md:h-56 bg-white/10 rounded-full translate-y-1/2 -translate-x-1/3"></div>
        <div class="absolute top-1/2 right-1/4 w-16 h-16 bg-white/5 rounded-full"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row items-center gap-6 md:gap-8 p-6 md:p-8 lg:p-12">
            <div class="flex-1 text-center md:text-left">
                <span class="inline-flex items-center gap-2 px-4 py-2 bg-white/20 backdrop-blur-sm rounded-full text-xs md:text-sm font-semibold text-white mb-4 md:mb-5 border border-white/20">
                    <span class="w-2 h-2 bg-white rounded-full animate-pulse"></span>
                    Rekomendasi AI
                </span>
                <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold mb-4 md:mb-5 leading-tight text-white">
                    Waktunya Latihan<br class="hidden sm:block">Deteksi Tangan!
                </h2>
                <p class="text-white/90 text-sm md:text-base lg:text-lg mb-6 md:mb-8 max-w-lg mx-auto md:mx-0 leading-relaxed">
                    Latih kemampuan isyaratmu dan biarkan AI mengoreksi gerakanmu secara real-time. Ayo capai akurasi 100%!
                </p>
                <a href="{{ route('latihan.deteksi') }}" class="inline-flex items-center justify-center gap-2 md:gap-3 px-6 py-3 md:px-8 md:py-4 bg-white text-[#57BBA0] font-bold text-sm md:text-base lg:text-lg rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all w-full sm:w-auto group">
                    <svg class="w-5 h-5 md:w-6 md:h-6 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                    Mulai Latihan Sekarang
                </a>
            </div>
            
            {{-- Right illustration --}}
            <div class="hidden md:flex items-center justify-center">
                <div class="relative">
                    <div class="w-44 h-44 lg:w-52 lg:h-52 xl:w-60 xl:h-60 bg-white/15 backdrop-blur-sm rounded-3xl flex items-center justify-center border border-white/20 p-4">
                        <img src="{{ asset('img/image.png') }}" alt="BISINDO AI" class="w-full h-full object-contain animate-float drop-shadow-lg">
                    </div>
                    {{-- Floating badge --}}
                    <div class="absolute -top-3 -right-3 md:-top-4 md:-right-4 bg-yellow-400 text-gray-900 px-4 py-2 rounded-full text-xs md:text-sm font-bold shadow-lg animate-pulse-glow">
                        AI Powered
                    </div>
                    {{-- Additional small badge --}}
                    <div class="absolute -bottom-2 -left-2 bg-white/20 backdrop-blur-sm text-white px-3 py-1.5 rounded-full text-xs font-semibold border border-white/30">
                        🎯 95% Akurasi
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

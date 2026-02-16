{{-- Quote Section - Quote motivasi harian dengan tema #57BBA0 --}}
@php
    $quotes = [
        [
            'text' => 'Satu gerakan hari ini, seribu makna esok hari.',
            'meaning' => 'Setiap isyarat yang kamu pelajari membuka pintu komunikasi dengan jutaan teman baru.',
        ],
        [
            'text' => 'Bahasa isyarat bukan keterbatasan, melainkan kekuatan.',
            'meaning' => 'Kamu sedang belajar bahasa yang menghubungkan hati tanpa suara.',
        ],
        [
            'text' => 'Tangan yang bergerak adalah jembatan antara dua dunia.',
            'meaning' => 'Dengan setiap gerakan, kamu membangun jembatan inklusi.',
        ],
        [
            'text' => 'Kesabaran adalah guru terbaik dalam belajar bahasa baru.',
            'meaning' => 'Jangan terburu-buru, setiap progress kecil adalah kemenangan besar.',
        ],
        [
            'text' => 'Belajar bahasa isyarat adalah bentuk cinta kepada sesama.',
            'meaning' => 'Kamu memilih untuk memahami, bukan hanya dimengerti.',
        ],
    ];
    $dailyQuote = $quotes[array_rand($quotes)];
@endphp

<div class="mt-8 md:mt-12 animate-in delay-4">
    <div class="relative overflow-hidden rounded-xl md:rounded-2xl bg-gradient-to-br from-[#E8F7F3] to-white border border-[#57BBA0]/20 p-6 md:p-8 text-center max-w-2xl mx-auto">
        {{-- Decorative --}}
        <div class="absolute top-0 right-0 w-24 h-24 bg-[#57BBA0]/10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
        
        <div class="relative z-10">
            <div class="w-12 h-12 md:w-14 md:h-14 mx-auto mb-3 md:mb-4 rounded-xl bg-[#57BBA0]/20 flex items-center justify-center">
                <svg class="w-6 h-6 md:w-7 md:h-7 text-[#57BBA0]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
            </div>
            
            <p class="text-[10px] md:text-xs text-[#57BBA0] font-bold uppercase tracking-widest mb-2 md:mb-3">Quote Hari Ini</p>
            
            <h3 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-800 mb-2 md:mb-3 leading-tight px-2">
                "{{ $dailyQuote['text'] }}"
            </h3>
            
            <p class="text-gray-500 text-sm md:text-base leading-relaxed px-2">
                {{ $dailyQuote['meaning'] }}
            </p>
            
            <div class="flex justify-center mt-5 md:mt-6">
                <span class="inline-flex items-center gap-2 px-4 py-2 bg-[#57BBA0] text-white rounded-full font-semibold text-xs md:text-sm shadow-lg">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    Kamu Luar Biasa!
                </span>
            </div>
        </div>
    </div>
</div>

{{-- CTA Section untuk Guest - Fully Responsive --}}
<div class="cta-section animate-in delay-4">
    <div class="max-w-2xl mx-auto">
        <div class="w-12 h-12 md:w-16 md:h-16 bg-white rounded-xl md:rounded-2xl shadow-lg flex items-center justify-center mx-auto mb-4 md:mb-6">
            <svg class="w-6 h-6 md:w-8 md:h-8 text-[#57BBA0]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
            </svg>
        </div>
        
        <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-800 mb-3 md:mb-4 px-4">
            Siap Memulai Perjalananmu?
        </h2>
        
        <p class="text-gray-600 text-sm sm:text-base md:text-lg mb-6 md:mb-8 px-4">
            Bergabunglah dengan ribuan pelajar lainnya dan mulai belajar bahasa isyarat dengan cara yang menyenangkan!
        </p>
        
        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center px-4">
            <a href="{{ route('daftar') }}" class="btn-primary-custom text-base md:text-lg">
                <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
                Daftar Sekarang — Gratis!
            </a>
            <a href="{{ route('masuk') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 md:px-8 md:py-4 border-2 border-gray-300 text-gray-700 rounded-xl font-semibold hover:border-[#57BBA0] hover:text-[#57BBA0] transition-all text-sm md:text-base w-full sm:w-auto">
                Sudah Punya Akun?
            </a>
        </div>
        
        {{-- Trust indicators --}}
        <div class="trust-indicators mt-8 md:mt-10 text-xs sm:text-sm text-gray-500 px-4">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 md:w-5 md:h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span>Gratis Selamanya</span>
            </div>
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 md:w-5 md:h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span>Tanpa Iklan</span>
            </div>
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 md:w-5 md:h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span>AI Powered</span>
            </div>
        </div>
    </div>
</div>

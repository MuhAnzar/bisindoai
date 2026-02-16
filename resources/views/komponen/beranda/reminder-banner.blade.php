{{-- Reminder Banner - Notifikasi pengingat latihan dengan tema #57BBA0 --}}
@props(['showReminder' => false, 'firstName' => 'Kamu'])

@if($showReminder)
<div id="reminder-banner" class="fixed bottom-4 left-4 right-4 md:left-auto md:bottom-6 md:right-6 z-50 md:w-full md:max-w-sm animate-in fade-in slide-in-from-bottom-5 duration-500">
    <div class="relative overflow-hidden rounded-2xl bg-white border border-amber-100 shadow-[0_8px_30px_rgb(0,0,0,0.12)] p-4">
        <!-- Decor -->
        <div class="absolute top-0 right-0 w-16 h-16 bg-gradient-to-br from-amber-100 to-amber-50 rounded-bl-full -mr-8 -mt-8 opacity-50"></div>
        
        <div class="flex items-start gap-3 relative z-10">
            {{-- Icon --}}
            <div class="flex-shrink-0">
                <div class="w-10 h-10 bg-amber-50 rounded-full flex items-center justify-center border border-amber-100">
                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                </div>
            </div>
            
            {{-- Content --}}
            <div class="flex-1 min-w-0 pt-0.5">
                <div class="flex items-start justify-between gap-2">
                    <h4 class="font-bold text-gray-900 text-sm leading-tight">Latihan Hari Ini?</h4>
                    <button onclick="dismissReminder()" class="text-gray-400 hover:text-gray-600 transition-colors -mt-1 -mr-1 p-1 rounded-full hover:bg-gray-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                
                <p class="text-gray-500 text-xs mt-1 leading-relaxed line-clamp-2">
                    Hai {{ $firstName }}! Jangan lupa jaga streak-mu tetap aktif ya.
                </p>
                
                <div class="mt-3 flex items-center gap-3">
                    <a href="{{ route('latihan.deteksi') }}" class="inline-flex items-center justify-center px-4 py-1.5 bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold rounded-lg transition-all shadow-sm hover:shadow-md active:scale-95">
                        Mulai Latihan
                    </a>
                    <button onclick="dismissReminder()" class="text-xs font-semibold text-gray-400 hover:text-gray-600 transition-colors">
                        Nanti Saja
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

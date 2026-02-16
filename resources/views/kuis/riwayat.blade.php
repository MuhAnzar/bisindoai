@extends('komponen.tata_letak')

@section('judul', 'Riwayat Kuis')
@section('deskripsi', 'Lihat hasil dan perkembangan nilai kuis Anda.')

@section('konten')
<div class="max-w-5xl mx-auto">
    <!-- Header Actions -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <a href="{{ route('kuis.index') }}" class="group flex items-center gap-2 text-slate-500 hover:text-teal-600 transition-colors font-medium">
            <svg class="w-5 h-5 transition-transform group-hover:-translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Kembali ke Daftar Kuis
        </a>
        
        <div class="bg-indigo-50 text-indigo-700 px-4 py-2 rounded-full font-bold text-sm flex items-center gap-2 border border-indigo-100">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M2 12h20"/></svg>
            Total Kuis Dikerjakan: {{ $riwayat->total() }}
        </div>
    </div>

    @if($riwayat->isEmpty())
    <div class="text-center py-20 bg-white rounded-3xl border border-slate-200 shadow-sm">
        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300">
            <svg class="w-10 h-10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
        </div>
        <h3 class="text-xl font-bold text-slate-800 mb-2">Belum Ada Riwayat</h3>
        <p class="text-slate-500 mb-8 max-w-md mx-auto">Anda belum mengerjakan kuis apapun. Mulai kerjakan kuis untuk melihat nilai Anda di sini.</p>
        <a href="{{ route('kuis.index') }}" class="inline-flex items-center gap-2 bg-teal-600 hover:bg-teal-700 text-white px-6 py-3 rounded-xl font-bold transition-all shadow-lg shadow-teal-600/20">
            Mulai Kuis Sekarang
        </a>
    </div>
    @else
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-200 font-bold text-slate-800 uppercase tracking-wider text-xs">
                    <tr>
                        <th class="px-6 py-4">Kuis</th>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4 text-center">Skor</th>
                        <th class="px-6 py-4 text-center">Hasil</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($riwayat as $item)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4 font-bold text-slate-800">{{ $item->kuis->judul }}</td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="font-medium text-slate-700">{{ $item->created_at->translatedFormat('d F Y') }}</span>
                                <span class="text-xs text-slate-400">{{ $item->created_at->format('H:i') }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-full font-bold text-sm {{ $item->skor >= 70 ? 'bg-teal-50 text-teal-600' : 'bg-orange-50 text-orange-600' }}">
                                {{ $item->skor }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($item->skor >= 90)
                                <span class="inline-flex px-2 py-1 text-[10px] font-bold uppercase tracking-wide text-teal-700 bg-teal-100 rounded-full">Sempurna</span>
                            @elseif($item->skor >= 70)
                                <span class="inline-flex px-2 py-1 text-[10px] font-bold uppercase tracking-wide text-blue-700 bg-blue-100 rounded-full">Lulus</span>
                            @else
                                <span class="inline-flex px-2 py-1 text-[10px] font-bold uppercase tracking-wide text-orange-700 bg-orange-100 rounded-full">Belum Lulus</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('kuis.hasil', ['id' => $item->kuis_id, 'hasil_id' => $item->id]) }}" class="text-slate-400 hover:text-teal-600 transition-colors p-2 rounded-full hover:bg-slate-100 inline-block" title="Lihat Detail">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($riwayat->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
            {{ $riwayat->links() }}
        </div>
        @endif
    </div>
    @endif
</div>
@endsection

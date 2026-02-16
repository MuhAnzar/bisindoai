@extends('layouts.admin')

@section('judul', 'Kelola Kuis')
@section('deskripsi', 'Buat, edit, dan kelola kuis interaktif.')

@section('navigasi')
    <a href="{{ route('admin.kuis.create') }}" class="btn btn-primary">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
        Buat Kuis Baru
    </a>
@endsection

@section('konten')
    <!-- Stats Row -->
    <div class="grid grid-3" style="margin-bottom: 32px; gap: 24px;">
        <div class="stat-card">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <p class="text-sm text-muted">Total Kuis</p>
                    <h3 style="font-size: 1.8rem;">{{ $totalKuis }}</h3>
                </div>
                <div class="stat-icon" style="background: var(--teal-500); color: white;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 17a2 2 0 0 1-2 2h-2v2"></path><rect x="2" y="3" width="12" height="12" rx="2"></rect><path d="M6 15v6"></path><path d="M2 19h12"></path><path d="M14 6h4a2 2 0 0 1 2 2v4"></path></svg>
                </div>
            </div>
            <div class="text-sm" style="color: var(--primary); display: flex; align-items: center; gap: 4px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg>
                <span>Aktif</span>
            </div>
        </div>

         <div class="stat-card">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <p class="text-sm text-muted">Total Pertanyaan</p>
                    <h3 style="font-size: 1.8rem;">{{ $totalPertanyaan }}</h3>
                </div>
                <div class="stat-icon" style="background: var(--blue-500); color: white;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                </div>
            </div>
             <div class="text-sm" style="color: var(--primary); display: flex; align-items: center; gap: 4px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg>
                <span>Siap digunakan</span>
            </div>
        </div>

        <div class="stat-card" style="display: flex; flex-direction: column; justify-content: center; align-items: flex-start; background: linear-gradient(135deg, #0F172A, #1E293B); border: none;">
            <p class="text-sm" style="color: #94A3B8; margin-bottom: 4px;">Aksi Cepat</p>
            <h3 style="color: white; font-size: 1.25rem; font-weight: 600; margin-bottom: 16px;">Kelola Kuis</h3>
            <a href="{{ route('admin.kuis.create') }}" class="btn btn-primary" style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); width: auto;">
                Buat Kuis &rarr;
            </a>
        </div>
    </div>

    <!-- Table -->
    <div class="card" style="padding: 0; overflow: hidden;">
        <div style="padding: 24px; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; flex-wrap: wrap; gap: 16px;">
            <div style="flex: 1; min-width: 300px; position: relative;">
                <form action="{{ route('admin.kuis.index') }}" method="GET">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-muted);"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kuis, topik, atau deskripsi..." style="width: 100%; padding: 10px 10px 10px 42px; border: 1px solid var(--border-color); border-radius: 8px; outline: none; transition: border-color 0.2s;">
                </form>
            </div>
        </div>

        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th style="padding-left: 32px;">Info Kuis</th>
                        <th>Statistik</th>
                        <th>Dibuat Pada</th>
                        <th style="padding-right: 32px; text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kuis as $item)
                        <tr>
                            <td style="padding-left: 32px;">
                                <div style="display: flex; gap: 16px; align-items: center;">
                                    @if($item->gambar_sampul)
                                        <img src="{{ asset($item->gambar_sampul) }}" alt="" style="width: 56px; height: 56px; border-radius: 12px; object-fit: cover; border: 1px solid var(--border-color);">
                                    @else
                                        <div style="width: 56px; height: 56px; border-radius: 12px; background: var(--teal-50); color: var(--primary); display: grid; place-items: center; border: 1px solid #CCFBF1;">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                                        </div>
                                    @endif
                                    <div>
                                        <div style="font-weight: 600; font-size: 1rem; color: var(--text-main); margin-bottom: 2px;">{{ $item->judul }}</div>
                                        <div style="font-size: 0.85rem; color: var(--text-muted); max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $item->deskripsi }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge" style="background: #F5F3FF; color: #7C3AED; border: 1px solid #EDE9FE;">
                                    {{ $item->pertanyaans_count }} Soal
                                </span>
                            </td>
                            <td>
                                <div style="font-size: 0.9rem; font-weight: 500;">{{ $item->created_at->format('d M Y') }}</div>
                                <div style="font-size: 0.8rem; color: var(--text-muted);">{{ $item->created_at->format('H:i') }} WIB</div>
                            </td>
                            <td style="padding-right: 32px;">
                                <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                    <a href="{{ route('admin.kuis.edit', $item->id) }}" class="btn btn-sm btn-outline">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.kuis.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus kuis ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" style="border: none;">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 48px;">
                                <div style="display: flex; flex-direction: column; align-items: center;">
                                    <div style="width: 64px; height: 64px; background: var(--slate-100); border-radius: 50%; display: grid; place-items: center; margin-bottom: 16px; color: var(--text-muted);">
                                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                                    </div>
                                    <h3 style="font-size: 1.1rem; margin-bottom: 4px;">Belum ada Kuis</h3>
                                    <p class="text-sm text-muted" style="margin-bottom: 24px;">Mulai dengan membuat kuis pertama Anda.</p>
                                    <a href="{{ route('admin.kuis.create') }}" class="btn btn-primary">Buat Kuis Sekarang</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($kuis->hasPages())
            <div style="padding: 16px 24px; border-top: 1px solid var(--border-color); background: #F9FAFB;">
                {{ $kuis->links() }}
            </div>
        @endif
    </div>
@endsection

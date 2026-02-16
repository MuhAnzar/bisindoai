@extends('komponen.tata_letak')

@section('judul', $kuis->judul)

@section('konten')
@extends('komponen.tata_letak')

@section('judul', $kuis->judul)

@section('konten')
<style>
    .quiz-hero {
        background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%);
        border-radius: 24px;
        padding: 40px;
        color: white;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 30px -10px rgba(15, 23, 42, 0.4);
    }

    .quiz-hero::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(13, 148, 136, 0.2) 0%, transparent 70%);
        border-radius: 50%;
        transform: translate(30%, -30%);
    }

    .glass-pill {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        padding: 6px 16px;
        border-radius: 99px;
        font-size: 0.85rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .stat-card {
        background: white;
        border-radius: 20px;
        padding: 32px;
        border: 1px solid var(--border);
        box-shadow: var(--shadow-lg);
        text-align: center;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .score-circle {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        margin-bottom: 16px;
        border: 4px solid #F1F5F9;
        position: relative;
    }
    
    .score-circle[data-score="good"] { border-color: #10B981; color: #059669; background: #ECFDF5; }
    .score-circle[data-score="bad"] { border-color: #F59E0B; color: #D97706; background: #FFFBEB; }
    .score-circle[data-score="none"] { border-color: #E2E8F0; color: #94A3B8; background: #F8FAFC; }

    .score-val { font-size: 2.5rem; font-weight: 800; line-height: 1; }
    .score-label { font-size: 0.8rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; margin-top: 4px; }

    .btn-action {
        width: 100%;
        padding: 16px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 1.1rem;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        text-decoration: none;
        border: none;
        cursor: pointer;
    }

    .btn-action.start { background: var(--primary); color: white; box-shadow: 0 4px 12px rgba(13, 148, 136, 0.3); }
    .btn-action.start:hover { background: var(--primary-dark); transform: translateY(-2px); }
    
    .btn-action.disabled { background: #E2E8F0; color: #94A3B8; cursor: not-allowed; box-shadow: none; transform: none; }
</style>

<div style="max-width: 1000px; margin: 0 auto;">
    <!-- Back Button -->
    <a href="{{ route('kuis.index') }}" style="display: inline-flex; align-items: center; gap: 8px; color: var(--text-muted); text-decoration: none; font-weight: 500; margin-bottom: 24px; transition: color 0.2s;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
        Kembali ke Daftar Kuis
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content (Left) -->
        <div class="lg:col-span-2">
            <div class="quiz-hero">
                <div style="display: flex; gap: 12px; margin-bottom: 24px; flex-wrap: wrap;">
                    <span class="glass-pill">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                        {{ $kuis->pertanyaans_count }} Pertanyaan
                    </span>
                    <span class="glass-pill">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                        Pilihan Ganda
                    </span>
                </div>

                <h1 style="font-size: 2.5rem; margin-bottom: 12px; line-height: 1.2; color: white;">{{ $kuis->judul }}</h1>
                <p style="color: #94A3B8; margin-bottom: 0;">Dipublikasikan pada {{ $kuis->created_at->format('d M Y') }}</p>
            </div>

            <div class="bg-white rounded-2xl p-8 border border-slate-200 mt-8">
                <h3 style="font-size: 1.25rem; margin-bottom: 16px; color: var(--text-main);">Deskripsi Kuis</h3>
                <p style="color: var(--text-muted); line-height: 1.8;">{{ $kuis->deskripsi }}</p>
                
                <div style="margin-top: 32px; padding: 20px; background: #F8FAFC; border-radius: 12px; border: 1px solid #E2E8F0;">
                    <h4 style="font-size: 1rem; font-weight: 600; margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--primary);"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                        Petunjuk Pengerjaan
                    </h4>
                    <ul style="list-style: none; color: var(--text-muted); font-size: 0.95rem; display: flex; flex-direction: column; gap: 8px;">
                        <li style="display: flex; gap: 8px; align-items: start;">
                            <span style="color: var(--primary);">•</span> Pilihlah satu jawaban yang paling tepat dari setiap pertanyaan.
                        </li>
                        <li style="display: flex; gap: 8px; align-items: start;">
                            <span style="color: var(--primary);">•</span> Tidak ada batasan waktu, kerjakan dengan tenang dan teliti.
                        </li>
                        <li style="display: flex; gap: 8px; align-items: start;">
                            <span style="color: var(--primary);">•</span> Skor Anda akan langsung muncul setelah menyelesaikan kuis.
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Sidebar (Right) -->
        <div class="lg:col-span-1">
            <div class="stat-card">
                @if($riwayat)
                    <div class="score-circle" data-score="{{ $riwayat->skor >= 70 ? 'good' : 'bad' }}">
                        <span class="score-val">{{ $riwayat->skor }}</span>
                        <span class="score-label">Skor Kamu</span>
                    </div>
                
                    <div style="margin-bottom: 24px;">
                        <span style="font-weight: 600; color: {{ $riwayat->skor >= 70 ? '#059669' : '#D97706' }}; background: {{ $riwayat->skor >= 70 ? '#ECFDF5' : '#FFFBEB' }}; padding: 6px 16px; border-radius: 99px;">
                            {{ $riwayat->skor >= 70 ? 'Hebat!' : 'Belajar Lagi' }}
                        </span>
                        <p style="margin-top: 12px; color: var(--text-muted); font-size: 0.9rem;">Terakhir: {{ $riwayat->created_at->diffForHumans() }}</p>
                    </div>

                    <!-- Attempt Info -->
                    <div class="mb-6 p-4 bg-slate-50 rounded-xl border border-slate-100">
                        <div class="text-[10px] uppercase font-bold text-slate-400 mb-1">Percobaan Ke-</div>
                        <div class="flex items-center justify-center gap-1 font-bold text-slate-700">
                            <span class="text-xl">{{ $attemptCount }}</span>
                            <span class="text-slate-400">/</span>
                            <span>3</span>
                        </div>
                    </div>

                    @if($attemptCount < 3)
                        @if($kuis->pertanyaans_count > 0)
                        <a href="{{ route('kuis.kerjakan', $kuis->id) }}" class="btn-action start">
                            Kerjakan Ulang
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21.5 2v6h-6M2.5 22v-6h6M2 11.5a10 10 0 0 1 18.8-4.3M22 12.5a10 10 0 0 1-18.8 4.3"/></svg>
                        </a>
                        @else
                        <button class="btn-action disabled" disabled>Soal Kosong</button>
                        @endif
                    @else
                        <button class="btn-action disabled" disabled>Batas Mencoba Habis</button>
                        <p class="text-red-500 text-xs mt-2 font-medium">Anda telah mencapai batas maksimal 3 kali percobaan.</p>
                    @endif
                    
                    <a href="{{ route('kuis.riwayat') }}" class="block mt-4 text-sm text-teal-600 font-bold hover:underline">Lihat Semua Riwayat</a>

                @else
                    <div class="score-circle" data-score="none">
                        <span class="score-val">?</span>
                        <span class="score-label">Belum Ada</span>
                    </div>

                    <div style="margin-bottom: 32px;">
                        <p style="color: var(--text-muted);">Kamu belum mengerjakan kuis ini. Ayo tantang dirimu!</p>
                    </div>

                    @if($kuis->pertanyaans_count > 0)
                    <a href="{{ route('kuis.kerjakan', $kuis->id) }}" class="btn-action start">
                        Mulai Sekarang
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                    </a>
                    @else
                    <button class="btn-action disabled" disabled>Soal Belum Tersedia</button>
                    <p style="color: var(--accent); font-size: 0.85rem; margin-top: 12px;">Admin belum menambahkan pertanyaan.</p>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
@endsection


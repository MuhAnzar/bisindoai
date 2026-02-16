@extends('komponen.tata_letak')

@section('judul', 'Kuis BISINDO')
@section('deskripsi', 'Uji kemampuan bahasa isyarat Anda dengan kuis interaktif.')

@section('konten')
<style>
    /* Fixed Background Image */
    .page-background {
        position: fixed;
        inset: 0;
        z-index: -1;
    }
    .page-background::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(to bottom right, rgba(255,255,255,0.85), rgba(248,253,252,0.8), rgba(240,253,250,0.85));
    }
    .page-background::after {
        content: '';
        position: absolute;
        inset: 0;
        background-image: url('{{ asset('img/pelatihan-bahasa-isyarat.png') }}');
        background-size: cover;
        background-position: center;
        opacity: 0.15;
    }

    .quiz-banner {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        border-radius: 24px;
        padding: 40px;
        color: white;
        position: relative;
        overflow: hidden;
        margin-bottom: 40px;
        box-shadow: 0 10px 20px -5px rgba(13, 148, 136, 0.3);
    }

    .quiz-banner::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        border-radius: 50%;
        transform: translate(30%, -30%);
    }

    .quiz-banner h2 {
        font-size: 2rem;
        margin-bottom: 12px;
        font-weight: 800;
        color: white;
    }

    .quiz-banner p {
        color: rgba(255, 255, 255, 0.9);
        font-size: 1.1rem;
        max-width: 600px;
    }

    .stats-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 24px;
        margin-bottom: 48px;
    }

    .stat-box {
        background: white;
        padding: 24px;
        border-radius: 16px;
        border: 1px solid var(--border);
        display: flex;
        align-items: center;
        gap: 16px;
        transition: transform 0.2s;
    }

    .stat-box:hover {
        transform: translateY(-4px);
        border-color: var(--primary-light);
        box-shadow: var(--shadow-md);
    }

    .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        background: var(--bg-body);
        display: grid;
        place-items: center;
        color: var(--primary);
    }

    .quiz-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 24px;
    }

    .quiz-card {
        background: white;
        border: 1px solid var(--border);
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
        height: 100%;
        position: relative;
    }

    .quiz-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 24px -8px rgba(0, 0, 0, 0.1);
        border-color: var(--primary-light);
    }

    .quiz-cover {
        height: 180px;
        background: var(--bg-body);
        position: relative;
        overflow: hidden;
    }
    
    .quiz-cover img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s;
    }

    .quiz-card:hover .quiz-cover img {
        transform: scale(1.05);
    }

    .quiz-badge {
        position: absolute;
        top: 16px;
        right: 16px;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(4px);
        padding: 6px 12px;
        border-radius: 99px;
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--primary);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        z-index: 10;
    }

    .quiz-content {
        padding: 24px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .quiz-title {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 8px;
        color: var(--text-main);
    }

    .quiz-desc {
        font-size: 0.95rem;
        color: var(--text-muted);
        margin-bottom: 24px;
        line-height: 1.5;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .btn-start {
        width: 100%;
        margin-top: auto;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
        padding: 12px;
        border-radius: 12px;
        font-weight: 600;
        background: #F0FDFA;
        color: var(--primary);
        text-decoration: none;
        transition: all 0.2s;
    }

    .quiz-card:hover .btn-start {
        background: var(--primary);
        color: white;
    }

    @media (max-width: 768px) {
        .stats-row {
            grid-template-columns: 1fr;
        }
        .quiz-banner {
            padding: 32px 24px;
        }
        .quiz-banner h2 {
            text-align: center;
        }
        .quiz-banner p {
            text-align: center;
            margin: 0 auto;
        }
    }

    @media (min-width: 769px) {
        .quiz-banner-image {
            display: block !important;
        }
    }

</style>

<!-- Background Image -->
<div class="page-background"></div>

<!-- Banner -->
<div class="quiz-banner">
    <div style="display: flex; align-items: center; gap: 40px; position: relative; z-index: 10;">
        <!-- Left: Text Content -->
        <div style="flex: 1; max-width: 600px;">
            <h2>Asah Kemampuan Isyaratmu</h2>
            <p>Tantang diri Anda dengan berbagai kuis interaktif. Ukur pemahaman bahasa isyarat BISINDO dan raih skor tertinggi!</p>
            
            @auth
            @auth
            <div style="margin-top: 24px; display: flex; flex-wrap: wrap; gap: 12px; align-items: center;">
                <div style="display: inline-flex; background: rgba(255,255,255,0.2); backdrop-filter: blur(8px); padding: 8px 20px; border-radius: 99px; align-items: center; gap: 8px; border: 1px solid rgba(255,255,255,0.3);">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"></path><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"></path><path d="M4 22h16"></path><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"></path><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"></path><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"></path></svg>
                    <span style="font-weight: 600;">Skor Tertinggi Anda: {{ auth()->user()->highest_score ?? 0 }}</span>
                </div>

                <a href="{{ route('kuis.riwayat') }}" style="display: inline-flex; background: white; color: var(--primary); padding: 8px 20px; border-radius: 99px; align-items: center; gap: 8px; font-weight: 700; text-decoration: none; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                    Lihat Riwayat Saya
                </a>
            </div>
            @endauth
            @endauth
        </div>

        <!-- Right: Quiz Image -->
        <div style="flex-shrink: 0; display: none;" class="quiz-banner-image">
            <img src="{{ asset('img/quis.png') }}" alt="Quiz BISINDO" style="height: 200px; width: auto; object-fit: contain; filter: drop-shadow(0 10px 20px rgba(0,0,0,0.1));">
        </div>
    </div>
</div>

<!-- Stats -->
<div class="stats-row">
    <div class="stat-box">
        <div class="stat-icon" style="background: #ECFDF5; color: #059669;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
        </div>
        <div>
            <h4 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 2px;">Terverifikasi</h4>
            <p style="color: var(--text-muted); font-size: 0.9rem;">Materi Standar BISINDO</p>
        </div>
    </div>
    <div class="stat-box">
        <div class="stat-icon" style="background: #EFF6FF; color: #2563EB;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polygon points="10 8 16 12 10 16 10 8"></polygon></svg>
        </div>
        <div>
            <h4 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 2px;">Interaktif</h4>
            <p style="color: var(--text-muted); font-size: 0.9rem;">Belajar Menyenangkan</p>
        </div>
    </div>
    <div class="stat-box">
        <div class="stat-icon" style="background: #FFF7ED; color: #EA580C;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"></path><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"></path><path d="M4 22h16"></path><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"></path><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"></path><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"></path></svg>
        </div>
        <div>
            <h4 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 2px;">Kompetitif</h4>
            <p style="color: var(--text-muted); font-size: 0.9rem;">Raih Skor Tertinggi</p>
        </div>
    </div>
</div>

<!-- Quiz Grid -->
<div class="quiz-grid">
    @forelse($kuis as $item)
    <div class="quiz-card">
        <div class="quiz-cover">
            @if($item->gambar_sampul)
                <img src="{{ asset($item->gambar_sampul) }}" alt="{{ $item->judul }}">
            @else
                <div style="width: 100%; height: 100%; display: grid; place-items: center; background: #F8FAFC; color: #94A3B8;">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 12V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-5"></path><path d="M16 12l-4-4-4 4"></path></svg>
                </div>
            @endif
            
            <div class="quiz-badge">
                {{ $item->pertanyaans_count }} Soal
            </div>
        </div>

        <div class="quiz-content">
            <h3 class="quiz-title">{{ $item->judul }}</h3>
            <p class="quiz-desc">{{ $item->deskripsi }}</p>

            <a href="{{ route('kuis.show', $item->id) }}" class="btn-start">
                Mulai Kuis
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
            </a>
        </div>
    </div>
    @empty
    <div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px;">
        <div style="width: 80px; height: 80px; background: #F1F5F9; border-radius: 50%; display: grid; place-items: center; margin: 0 auto 24px; color: #94A3B8;">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
        </div>
        <h3 style="font-size: 1.5rem; margin-bottom: 8px; color: var(--text-main); font-weight: 700;">Belum Ada Kuis</h3>
        <p style="color: var(--text-muted);">Saat ini belum ada kuis yang tersedia. Silakan cek kembali nanti!</p>
    </div>
    @endforelse
</div>

@endsection

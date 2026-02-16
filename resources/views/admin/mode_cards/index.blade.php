@extends('layouts.admin')

@section('judul', 'Kelola Mode Cards')
@section('deskripsi', 'Kelola tampilan kartu mode latihan di halaman deteksi')

@section('konten')
    <style>
        .mode-card-preview { 
            background: white; 
            border-radius: 16px; 
            border: 1px solid var(--border-color); 
            overflow: hidden; 
            transition: all 0.2s; 
        }
        .mode-card-preview:hover { 
            transform: translateY(-4px); 
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); 
        }
        .card-header-preview {
            padding: 20px;
            color: white;
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .card-icon-preview {
            width: 60px;
            height: 60px;
            background: rgba(255,255,255,0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: 700;
        }
        .card-body-preview {
            padding: 20px;
        }
        .feature-tag {
            display: inline-block;
            padding: 4px 10px;
            background: #F0FDFA;
            color: var(--primary);
            border-radius: 6px;
            font-size: 0.75rem;
            margin: 2px;
        }
        .action-btn {
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            transition: all 0.2s;
        }
        .action-btn-edit {
            background: var(--primary);
            color: white;
        }
        .action-btn-edit:hover {
            background: var(--primary-dark);
        }
        .status-badge {
            padding: 4px 12px;
            border-radius: 99px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .status-active { background: #D1FAE5; color: #059669; }
        .status-inactive { background: #FEE2E2; color: #DC2626; }
    </style>

    @if(session('success'))
        <div style="background: #D1FAE5; color: #059669; padding: 16px; border-radius: 12px; margin-bottom: 24px; display: flex; align-items: center; gap: 12px;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
            {{ session('success') }}
        </div>
    @endif

    <div style="margin-bottom: 24px;">
        <p class="text-muted">Kartu mode latihan yang ditampilkan di halaman <strong>/latihan/deteksi</strong>. Anda dapat mengubah deskripsi, fitur, dan gambar masing-masing kartu.</p>
    </div>

    <div class="grid grid-3" style="gap: 24px;">
        @forelse($modeCards as $card)
            <div class="mode-card-preview">
                <div class="card-header-preview" style="background: linear-gradient(135deg, {{ $card->gradient_from }}, {{ $card->gradient_to }});">
                    <div class="card-icon-preview">
                        @if($card->icon_type === 'letter')
                            {{ $card->icon_content ?? strtoupper(substr($card->mode_key, 0, 1)) }}
                        @else
                            {!! $card->icon_content !!}
                        @endif
                    </div>
                    <div>
                        <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 4px;">{{ $card->title }}</h3>
                        <span style="font-size: 0.875rem; opacity: 0.8;">{{ $card->badge_emoji }} {{ $card->badge_text }}</span>
                    </div>
                </div>
                
                <div class="card-body-preview">
                    <p class="text-muted" style="margin-bottom: 16px; line-height: 1.6;">
                        {{ Str::limit($card->description, 100) }}
                    </p>
                    
                    <div style="margin-bottom: 16px;">
                        @foreach($card->features ?? [] as $feature)
                            <span class="feature-tag">✓ {{ Str::limit($feature, 25) }}</span>
                        @endforeach
                    </div>

                    @if($card->image)
                        <div style="margin-bottom: 16px; padding: 8px; background: #F8FAFC; border-radius: 8px;">
                            <img src="{{ asset('storage/' . $card->image) }}" alt="{{ $card->title }}" style="width: 100%; height: 80px; object-fit: cover; border-radius: 6px;">
                        </div>
                    @endif
                    
                    <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 16px; border-top: 1px solid #F1F5F9;">
                        <span class="status-badge {{ $card->is_active ? 'status-active' : 'status-inactive' }}">
                            {{ $card->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                        <a href="{{ route('admin.mode-cards.edit', $card) }}" class="action-btn action-btn-edit">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                            Edit
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div style="grid-column: 1 / -1; text-align: center; padding: 48px; color: var(--text-muted);">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin: 0 auto 16px;"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="9" y1="9" x2="15" y2="15"></line><line x1="15" y1="9" x2="9" y2="15"></line></svg>
                <p>Belum ada mode card. Jalankan seeder untuk membuat data default.</p>
                <code style="display: block; margin-top: 12px; padding: 12px; background: #F1F5F9; border-radius: 8px;">php artisan db:seed --class=ModeCardSeeder</code>
            </div>
        @endforelse
    </div>
@endsection

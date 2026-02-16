@extends('layouts.admin')

@section('judul', 'Kelola Konten')
@section('deskripsi', 'Buat, edit, dan kelola materi pembelajaran BISINDO')

@section('navigasi')
    <div style="display: flex; gap: 12px;">
        <div style="position: relative; display: inline-block;">
            <button class="btn btn-primary" onclick="toggleDropdown()" style="box-shadow: 0 4px 6px -1px rgba(13, 148, 136, 0.4);">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Buat Konten Baru
            </button>
            <div id="dropdown-buat" style="display: none; position: absolute; right: 0; top: 120%; background: white; border: 1px solid var(--border-color); border-radius: 12px; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); min-width: 200px; z-index: 50; overflow: hidden; animation: slideIn 0.2s ease-out;">
                <a href="{{ route('admin.abjad.create') }}" class="dropdown-item">
                    <span style="width: 24px; height: 24px; background: #F0FDFA; color: var(--primary); border-radius: 6px; display: grid; place-items: center; margin-right: 12px; font-weight: 700; font-size: 0.8rem;">A</span>
                    Buat Abjad
                </a>
                <a href="{{ route('admin.katadasar.create') }}" class="dropdown-item">
                    <span style="width: 24px; height: 24px; background: #EFF6FF; color: var(--blue-500); border-radius: 6px; display: grid; place-items: center; margin-right: 12px; font-weight: 700; font-size: 0.8rem;">Kd</span>
                    Buat Kata Dasar
                </a>
            </div>
        </div>
    </div>
@endsection

@section('konten')
    <style>
        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px; margin-bottom: 32px; }
        .stat-card-modern { background: white; border-radius: 16px; padding: 24px; border: 1px solid var(--border-color); display: flex; flex-direction: column; gap: 8px; position: relative; overflow: hidden; }
        .stat-card-modern::after { content: ''; position: absolute; right: -20px; top: -20px; width: 100px; height: 100px; background: linear-gradient(135deg, transparent, rgba(13, 148, 136, 0.05)); border-radius: 50%; pointer-events: none; }
        
        .content-card { background: white; border-radius: 16px; border: 1px solid var(--border-color); overflow: hidden; transition: all 0.2s; display: flex; flex-direction: column; }
        .content-card:hover { transform: translateY(-4px); box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); border-color: var(--primary); }
        .card-preview { height: 160px; display: grid; place-items: center; background: #F8FAFC; border-bottom: 1px solid var(--border-color); position: relative; }
        .card-preview-text { font-size: 4rem; font-weight: 800; color: #CBD5E1; }
        .card-body { padding: 20px; flex: 1; display: flex; flex-direction: column; }
        
        .dropdown-item { display: flex; align-items: center; padding: 12px 16px; text-decoration: none; color: var(--text-main); font-weight: 500; transition: background 0.2s; }
        .dropdown-item:hover { background: #F3F4F6; }

        .filter-btn { padding: 8px 16px; border: 1px solid var(--border-color); background: white; border-radius: 99px; cursor: pointer; font-size: 0.9rem; color: var(--text-muted); transition: all 0.2s; }
        .filter-btn.active { background: var(--primary); color: white; border-color: var(--primary); }
        
        @keyframes slideIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
    </style>

    <!-- Real Stats Row -->
    <div class="stats-grid">
        <div class="stat-card-modern">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <span class="text-sm text-muted">Total Konten</span>
                <div style="background: #F0FDFA; padding: 8px; border-radius: 8px; color: var(--primary);">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
                </div>
            </div>
            <h3 style="font-size: 2rem; font-weight: 700;">{{ number_format($totalAbjad + $totalKata) }}</h3>
            <span class="text-sm text-muted">Item tersedia</span>
        </div>

        <div class="stat-card-modern">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <span class="text-sm text-muted">Total Latihan</span>
                <div style="background: #EFF6FF; padding: 8px; border-radius: 8px; color: var(--blue-500);">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
                </div>
            </div>
            <h3 style="font-size: 2rem; font-weight: 700;">{{ number_format($totalLatihan) }}</h3>
            <span class="text-sm text-muted">Sesi diselesaikan</span>
        </div>

        <div class="stat-card-modern">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <span class="text-sm text-muted">Rata-rata Akurasi</span>
                <div style="background: #FEFCE8; padding: 8px; border-radius: 8px; color: #EAB308;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                </div>
            </div>
            <h3 style="font-size: 2rem; font-weight: 700;">{{ round($avgAkurasi) }}%</h3>
            <span class="text-sm text-muted">Tingkat keberhasilan</span>
        </div>

        <div class="stat-card-modern">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <span class="text-sm text-muted">User Aktif</span>
                <div style="background: #FAF5FF; padding: 8px; border-radius: 8px; color: var(--purple-500);">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                </div>
            </div>
            <h3 style="font-size: 2rem; font-weight: 700;">{{ number_format($engagement) }}</h3>
            <span class="text-sm text-muted">Pengguna berlatih</span>
        </div>
    </div>

    <!-- Mode Cards Quick Access -->
    <div style="background: linear-gradient(135deg, #F0FDFA, #E0F2FE); border-radius: 16px; padding: 20px; margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between; border: 1px solid rgba(13, 148, 136, 0.2);">
        <div style="display: flex; align-items: center; gap: 16px;">
            <div style="width: 48px; height: 48px; background: linear-gradient(135deg, var(--primary), #0D9488); border-radius: 12px; display: grid; place-items: center;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
            </div>
            <div>
                <h4 style="font-weight: 700; color: var(--text-main); margin-bottom: 4px;">Mode Cards</h4>
                <p style="color: var(--text-muted); font-size: 0.9rem;">Kelola tampilan kartu mode latihan (Abjad, Kata, Kalimat)</p>
            </div>
        </div>
        <a href="{{ route('admin.mode-cards.index') }}" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 8px;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
            Kelola Mode Cards
        </a>
    </div>

    <!-- Filters & Search -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; gap: 16px; flex-wrap: wrap;">
        <div style="display: flex; gap: 8px;">
            <button class="filter-btn active" onclick="filterContent('all', this)">Semua</button>
            <button class="filter-btn" onclick="filterContent('abjad', this)">Abjad</button>
            <button class="filter-btn" onclick="filterContent('kata', this)">Kata Dasar</button>
        </div>
        
        <div style="position: relative; width: 300px;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-muted);"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            <input type="text" id="searchInput" placeholder="Cari konten..." onkeyup="searchContent()" style="width: 100%; padding: 10px 10px 10px 42px; border: 1px solid var(--border-color); border-radius: 99px; outline: none; transition: all 0.2s;">
        </div>
    </div>

    <!-- Content Grid -->
    <div id="contentGrid" class="grid grid-4" style="gap: 24px;">
        <!-- Abjad Loop -->
        @foreach($recentAbjad as $abjad)
            @php
                $ext = $abjad->berkas_video ? pathinfo($abjad->berkas_video, PATHINFO_EXTENSION) : '';
                $isImg = in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                $isVid = in_array(strtolower($ext), ['mp4', 'webm', 'ogg', 'mov']);
                $mediaUrl = $abjad->berkas_video ? asset($abjad->berkas_video) : '';
                $mediaType = $isImg ? 'image' : ($isVid ? 'video' : 'none');
            @endphp
            <div class="content-card" 
                 onclick="openDetailModal(this)"
                 data-type="abjad" 
                 data-name="{{ strtolower('Huruf ' . $abjad->huruf) }}"
                 data-title="Huruf {{ $abjad->huruf }}"
                 data-desc="{{ $abjad->deskripsi }}"
                 data-media="{{ $mediaUrl }}"
                 data-media-type="{{ $mediaType }}"
                 data-edit-url="{{ route('admin.abjad.edit', $abjad->id) }}">
                <div class="card-preview" style="padding: 0;">
                    @if($abjad->berkas_video)
                        @if($isImg)
                            <img src="{{ asset($abjad->berkas_video) }}" style="height: 120px; width: auto; max-width: 90%; object-fit: contain; border-radius: 8px;">
                        @elseif($isVid)
                            <video style="height: 120px; width: auto; max-width: 90%; object-fit: contain; border-radius: 8px;">
                                <source src="{{ asset($abjad->berkas_video) }}" type="video/{{ $ext }}">
                            </video>
                            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: rgba(0,0,0,0.5); border-radius: 50%; padding: 10px; pointer-events: none;">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="white" stroke="currentColor" stroke-width="2"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
                            </div>
                        @else
                           <span class="card-preview-text">{{ $abjad->huruf }}</span>
                        @endif
                    @else
                        <span class="card-preview-text">{{ $abjad->huruf }}</span>
                    @endif
                    <span class="badge badge-secondary" style="position: absolute; top: 12px; right: 12px; font-size: 0.7rem;">ABJAD</span>
                </div>
                <div class="card-body">
                    <h4 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 4px;">Huruf {{ $abjad->huruf }}</h4>
                    <p class="text-sm text-muted" style="margin-bottom: 16px; min-height: 40px;">{{ Str::limit($abjad->deskripsi, 50) }}</p>
                    
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: auto; padding-top: 16px; border-top: 1px solid #F1F5F9;">
                        <div class="text-xs text-muted" style="display: flex; align-items: center; gap: 4px;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
                            {{ $abjad->practices_count }} Latihan
                        </div>
                        <div style="display: flex; gap: 8px;">
                             <span style="color: var(--primary); font-weight: 600; font-size: 0.85rem;">Detail</span>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <!-- Kata Dasar Loop -->
        @foreach($recentKata as $kata)
            @php
                $ext = $kata->berkas_video ? pathinfo($kata->berkas_video, PATHINFO_EXTENSION) : '';
                $isImg = in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                $isVid = in_array(strtolower($ext), ['mp4', 'webm', 'ogg', 'mov']);
                $mediaUrl = $kata->berkas_video ? asset($kata->berkas_video) : '';
                $mediaType = $isImg ? 'image' : ($isVid ? 'video' : 'none');
            @endphp
             <div class="content-card" 
                  onclick="openDetailModal(this)"
                  data-type="kata" 
                  data-name="{{ strtolower($kata->kata) }}"
                  data-title="{{ $kata->kata }}"
                  data-desc="{{ $kata->arti }}"
                  data-media="{{ $mediaUrl }}"
                  data-media-type="{{ $mediaType }}"
                  data-edit-url="{{ route('admin.katadasar.edit', $kata->id) }}">
                <div class="card-preview" style="background: #F0FDFA;">
                    @if($kata->berkas_video)
                        @if($isImg)
                            <img src="{{ asset($kata->berkas_video) }}" style="height: 120px; width: auto; max-width: 90%; object-fit: contain; border-radius: 8px;">
                        @elseif($isVid)
                             <video style="height: 120px; width: auto; max-width: 90%; object-fit: contain; border-radius: 8px;">
                                <source src="{{ asset($kata->berkas_video) }}" type="video/{{ $ext }}">
                            </video>
                            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: rgba(0,0,0,0.5); border-radius: 50%; padding: 10px; pointer-events: none;">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="white" stroke="currentColor" stroke-width="2"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
                            </div>
                        @else
                            <span class="card-preview-text" style="font-size: 2rem; color: var(--primary-dark);">{{ $kata->kata }}</span>
                        @endif
                    @else
                        <span class="card-preview-text" style="font-size: 2rem; color: var(--primary-dark);">{{ $kata->kata }}</span>
                    @endif
                    <span class="badge" style="position: absolute; top: 12px; right: 12px; background: white; color: var(--primary); font-size: 0.7rem;">KATA</span>
                </div>
                <div class="card-body">
                    <h4 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 4px;">{{ $kata->kata }}</h4>
                    <p class="text-sm text-muted" style="margin-bottom: 16px; min-height: 40px;">{{ Str::limit($kata->arti, 50) }}</p>
                    
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: auto; padding-top: 16px; border-top: 1px solid #F1F5F9;">
                        <div class="text-xs text-muted" style="display: flex; align-items: center; gap: 4px;">
                             <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
                            {{ $kata->practices_count }} Latihan
                        </div>
                        <div style="display: flex; gap: 8px;">
                             <span style="color: var(--primary); font-weight: 600; font-size: 0.85rem;">Detail</span>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if(($recentAbjad->count() + $recentKata->count()) == 0)
        <div style="text-align: center; padding: 48px; color: var(--text-muted);">
            <p>Belum ada konten yang tersedia.</p>
        </div>
    @endif

    <!-- Detail Modal -->
    <div id="detailModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 100; align-items: center; justify-content: center; animation: fadeIn 0.2s;">
        <div style="background: white; width: 100%; max-width: 500px; border-radius: 16px; overflow: hidden; transform: scale(0.95); animation: zoomIn 0.2s forwards;">
            <div style="padding: 24px; text-align: center;">
                <div id="modalMediaContainer" style="margin-bottom: 24px; min-height: 200px; display: flex; align-items: center; justify-content: center; background: #f9fafb; border-radius: 12px;">
                    <!-- Media inserted here by JS -->
                </div>
                
                <h3 id="modalTitle" style="font-size: 1.5rem; font-weight: 700; margin-bottom: 12px; color: var(--text-main);"></h3>
                <p id="modalDesc" style="color: var(--text-muted); line-height: 1.6; margin-bottom: 24px;"></p>
                
                <div style="display: flex; gap: 12px; justify-content: center;">
                    <a id="modalEditBtn" href="#" class="btn btn-primary" style="flex: 1;">Edit Konten</a>
                    <button onclick="closeDetailModal()" class="btn btn-outline" style="flex: 1;">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes zoomIn { from { transform: scale(0.95); } to { transform: scale(1); } }
        .content-card { cursor: pointer; } 
    </style>

    <script>
        function toggleDropdown() {
            const dropdown = document.getElementById('dropdown-buat');
            dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
        }

        // Close dropdown when clicking outside
        window.onclick = function(event) {
            if (!event.target.closest('.btn-primary') && !event.target.closest('#detailModal .btn')) { 
                 // Keeping dropdown logic separate
            }
            if (!event.target.closest('.btn-primary')) {
                document.getElementById('dropdown-buat').style.display = 'none';
            }
            if (event.target.id === 'detailModal') {
                closeDetailModal();
            }
        }

        function filterContent(type, btn) {
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            const cards = document.querySelectorAll('.content-card');
            cards.forEach(card => {
                if (type === 'all' || card.dataset.type === type) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        function searchContent() {
            const query = document.getElementById('searchInput').value.toLowerCase();
            const cards = document.querySelectorAll('.content-card');
            
            cards.forEach(card => {
                const name = card.dataset.name;
                const isVisible = card.style.display !== 'none';
                
                if (name.includes(query)) {
                    const activeType = document.querySelector('.filter-btn.active').textContent.toLowerCase();
                    const cardType = card.dataset.type;
                    
                    if (activeType === 'semua' || 
                       (activeType === 'abjad' && cardType === 'abjad') || 
                       (activeType === 'kata dasar' && cardType === 'kata')) {
                        card.style.display = 'flex';
                    }
                } else {
                    card.style.display = 'none';
                }
            });
        }

        function openDetailModal(card) {
            const modal = document.getElementById('detailModal');
            const title = document.getElementById('modalTitle');
            const desc = document.getElementById('modalDesc');
            const container = document.getElementById('modalMediaContainer');
            const editBtn = document.getElementById('modalEditBtn');

            title.textContent = card.dataset.title;
            desc.textContent = card.dataset.desc;
            editBtn.href = card.dataset.editUrl;

            const mediaUrl = card.dataset.media;
            const mediaType = card.dataset.mediaType;

            container.innerHTML = '';
            
            if (mediaType === 'image') {
                container.innerHTML = `<img src="${mediaUrl}" style="max-width: 100%; max-height: 300px; border-radius: 8px;">`;
            } else if (mediaType === 'video') {
                container.innerHTML = `<video controls autoplay style="max-width: 100%; max-height: 300px; border-radius: 8px; width: 100%;">
                                        <source src="${mediaUrl}">
                                       </video>`;
            } else {
                container.innerHTML = `<span style="font-size: 3rem; font-weight: 700; color: #cbd5e1;">${card.dataset.title.replace('Huruf ', '')}</span>`;
            }

            modal.style.display = 'flex';
        }

        function closeDetailModal() {
            const modal = document.getElementById('detailModal');
            modal.style.display = 'none';
            document.getElementById('modalMediaContainer').innerHTML = '';
        }
    </script>
@endsection

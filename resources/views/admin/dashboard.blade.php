@extends('layouts.admin')

@section('judul', 'Dashboard Overview')
@section('deskripsi', 'Ringkasan aktivitas dan performa pembelajaran BISINDO.')

@section('navigasi')
    <a href="{{ route('admin.konten') }}" class="btn btn-primary">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M12 5v14M5 12h14"/>
        </svg>
        Buat Konten Baru
    </a>
@endsection

@section('konten')
    <!-- Stats Row -->
    <div class="grid grid-4" style="margin-bottom: 32px;">
        <div class="stat-card">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <p class="text-sm text-muted">Total User</p>
                    <h3 style="font-size: 1.8rem;">{{ number_format($statistik['total_user']) }}</h3>
                </div>
                <div class="stat-icon" style="background: var(--blue-500); color: white;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                </div>
            </div>
            <div class="text-sm" style="color: var(--primary); display: flex; align-items: center; gap: 4px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg>
                <span>+{{ $statistik['user_growth'] ?? 0 }} dari bulan lalu</span>
            </div>
        </div>

        <div class="stat-card">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <p class="text-sm text-muted">Kuis Aktif</p>
                    <h3 style="font-size: 1.8rem;">{{ number_format($statistik['kuis_aktif']) }}</h3>
                </div>
                <div class="stat-icon" style="background: var(--primary); color: white;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                </div>
            </div>
            <div class="text-sm" style="color: var(--primary); display: flex; align-items: center; gap: 4px;">
                <!-- Dummy trend for quiz -->
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg>
                <span>Aktif</span>
            </div>
        </div>

        <div class="stat-card">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <p class="text-sm text-muted">Total Konten</p>
                    <h3 style="font-size: 1.8rem;">{{ number_format($statistik['total_konten']) }}</h3>
                </div>
                <div class="stat-icon" style="background: var(--purple-500); color: white;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                    </svg>
                </div>
            </div>
            <div class="text-sm" style="color: var(--primary); display: flex; align-items: center; gap: 4px;">
                 <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg>
                <span>Terupdate</span>
            </div>
        </div>

        <div class="stat-card">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <p class="text-sm text-muted">Jam Pembelajaran</p>
                    <h3 style="font-size: 1.8rem;">{{ $statistik['jam_pembelajaran'] }}</h3>
                </div>
                <div class="stat-icon" style="background: var(--yellow-400); color: white;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                </div>
            </div>
            <div class="text-sm" style="color: var(--primary); display: flex; align-items: center; gap: 4px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg>
                <span>Total Jam</span>
            </div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
        <!-- Left Column -->
        <div style="display: flex; flex-direction: column; gap: 24px;">
            <!-- Quick Actions -->
            <div class="card">
                <h3 style="margin-bottom: 20px;">Aksi Cepat</h3>
                <div class="grid grid-2">
                    <a href="{{ route('admin.kuis.index') }}" style="background: var(--blue-500); padding: 24px; border-radius: 16px; color: white; text-decoration: none; display: flex; flex-direction: column; gap: 12px; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='translateY(0)'">
                        <div style="width: 32px; height: 32px; border: 2px solid rgba(255,255,255,0.5); border-radius: 50%; display: grid; place-items: center;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                        </div>
                        <div>
                            <h4 style="color: white; margin-bottom: 4px;">Kelola Kuis</h4>
                            <p style="font-size: 0.85rem; opacity: 0.9;">Buat dan atur kuis interaktif</p>
                        </div>
                        <div style="margin-top: auto; text-align: right;">&rarr;</div>
                    </a>

                    <a href="{{ route('admin.user.index') }}" style="background: var(--primary); padding: 24px; border-radius: 16px; color: white; text-decoration: none; display: flex; flex-direction: column; gap: 12px; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='translateY(0)'">
                        <div style="width: 32px; height: 32px; border: 2px solid rgba(255,255,255,0.5); border-radius: 50%; display: grid; place-items: center;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        </div>
                        <div>
                            <h4 style="color: white; margin-bottom: 4px;">Kelola User</h4>
                            <p style="font-size: 0.85rem; opacity: 0.9;">Lihat progress dan kelola data user</p>
                        </div>
                        <div style="margin-top: auto; text-align: right;">&rarr;</div>
                    </a>

                    <a href="{{ route('admin.analitik') }}" style="background: var(--purple-500); padding: 24px; border-radius: 16px; color: white; text-decoration: none; display: flex; flex-direction: column; gap: 12px; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='translateY(0)'">
                         <div style="width: 32px; height: 32px; border: 2px solid rgba(255,255,255,0.5); border-radius: 50%; display: grid; place-items: center;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
                        </div>
                        <div>
                            <h4 style="color: white; margin-bottom: 4px;">Lihat Analitik</h4>
                            <p style="font-size: 0.85rem; opacity: 0.9;">Analisis performa dan statistik kelas</p>
                        </div>
                        <div style="margin-top: auto; text-align: right;">&rarr;</div>
                    </a>

                    <a href="{{ route('admin.konten') }}" style="background: var(--yellow-400); padding: 24px; border-radius: 16px; color: white; text-decoration: none; display: flex; flex-direction: column; gap: 12px; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='translateY(0)'">
                        <div style="width: 32px; height: 32px; border: 2px solid rgba(255,255,255,0.5); border-radius: 50%; display: grid; place-items: center;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                        </div>
                        <div>
                            <h4 style="color: white; margin-bottom: 4px;">Kelola Konten</h4>
                            <p style="font-size: 0.85rem; opacity: 0.9;">Update materi dan tambah konten baru</p>
                        </div>
                        <div style="margin-top: auto; text-align: right;">&rarr;</div>
                    </a>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h3>Aktivitas Terbaru</h3>
                    <div style="display: flex; gap: 8px;">
                        <span class="text-muted text-sm">{{ count($aktivitasTerbaru) > 0 ? 'Menampilkan 5 aktivitas terakhir' : 'Belum ada aktivitas' }}</span>
                    </div>
                </div>
                
                <div style="display: flex; flex-direction: column; gap: 16px;">
                    @forelse($aktivitasTerbaru as $aktivitas)
                    <div style="display: flex; gap: 16px; align-items: flex-start;">
                        <div style="width: 40px; height: 40px; background: var(--teal-50); border-radius: 12px; display: grid; place-items: center; color: var(--primary);">
                            @if($aktivitas['type'] == 'kuis')
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                            @else
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg>
                            @endif
                        </div>
                        <div style="flex: 1;">
                            <div style="display: flex; justify-content: space-between;">
                                <h4 style="font-size: 0.95rem;">{{ $aktivitas['user_name'] }}</h4>
                                <span class="text-sm text-muted">{{ $aktivitas['time'] }}</span>
                            </div>
                            <p class="text-sm text-muted" style="margin-bottom: 4px;">{{ $aktivitas['description'] }}</p>
                            <span class="badge badge-{{ $aktivitas['badge_color'] }}">Skor: {{ $aktivitas['score'] }}</span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-4">
                        Belum ada aktivitas terbaru.
                    </div>
                    @endforelse
                </div>
                
                <div style="text-align: center; margin-top: 24px;">
                    <a href="{{ route('admin.analitik') }}" style="color: var(--primary); font-weight: 600; text-decoration: none; font-size: 0.9rem;">Lihat Semua Aktivitas</a>
                </div>
            </div>
        </div>

        <!-- Right Sidebar -->
        <div style="display: flex; flex-direction: column; gap: 24px;">
            <!-- Upcoming Tasks -->
            <div class="card">
                <h3 style="margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    Tugas Mendatang
                </h3>
                
                <div style="display: flex; flex-direction: column; gap: 16px;">
                    @foreach($tugasMendatang as $tugas)
                    <div style="padding: 16px; border: 1px solid var(--border-color); border-radius: 12px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                            <h4 style="font-size: 0.9rem;">{{ $tugas['judul'] }}</h4>
                            @if(isset($tugas['prioritas_bg']))
                                <span class="badge" style="background: {{ $tugas['prioritas_bg'] }}; color: {{ $tugas['prioritas_color'] }};">{{ $tugas['prioritas'] }}</span>
                            @else
                                <span class="badge {{ $tugas['prioritas_class'] }}">{{ $tugas['prioritas'] }}</span>
                            @endif
                        </div>
                        <p class="text-sm text-muted" style="margin-bottom: 12px;">{{ $tugas['desc'] }}</p>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span class="text-sm text-muted">{{ $tugas['waktu'] }}</span>
                            <span class="badge" style="background: #E0F2FE; color: #0369A1;">{{ $tugas['count'] }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Daily Summary -->
            <div class="card" style="background: var(--blue-500); color: white; border: none;">
                <h3 style="color: white; margin-bottom: 20px;">Ringkasan Hari Ini</h3>
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <div style="display: flex; justify-content: space-between;">
                        <span>User Online</span>
                        <span style="font-weight: 700;">{{ $dailyStats['user_online'] }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span>Kuis Dikerjakan</span>
                        <span style="font-weight: 700;">{{ $dailyStats['kuis_dikerjakan'] }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span>Rata-rata Skor</span>
                        <span style="font-weight: 700;">{{ $dailyStats['rata_rata_skor'] }}</span>
                    </div>
                    <div style="border-top: 1px solid rgba(255,255,255,0.2); margin: 4px 0;"></div>
                    <div style="display: flex; justify-content: space-between;">
                        <span>Waktu Belajar</span>
                        <span style="font-weight: 700;">{{ $dailyStats['waktu_belajar'] }}</span>
                    </div>
                </div>
            </div>

            <!-- Daily Tips -->
            <div class="card" style="background: #FEFCE8; border-color: #FEF08A;">
                <h3 style="color: #854D0E; margin-bottom: 12px; display: flex; align-items: center; gap: 8px; font-size: 1rem;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                    Tips Hari Ini
                </h3>
                <p class="text-sm" style="color: #854D0E;">Berikan feedback positif kepada user setelah mereka menyelesaikan latihan. Motivasi adalah kunci utama dalam pembelajaran bahasa isyarat.</p>
            </div>
        </div>
    </div>
@endsection



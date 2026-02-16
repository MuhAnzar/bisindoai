@extends('layouts.admin')

@section('judul', 'Analitik Pembelajaran')
@section('deskripsi', 'Insight performa kuis dan kategori BISINDO')

@section('konten')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="grid grid-4" style="gap: 24px; margin-bottom: 32px;">
        <!-- Card 1: Total Siswa -->
        <div class="stat-card">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div>
                    <span class="text-sm text-muted">Total Siswa</span>
                    <h3 style="font-size: 2rem; font-weight: 700; margin-top: 4px;">{{ number_format($totalSiswa) }}</h3>
                </div>
                <div class="stat-icon" style="background: #E0F2FE; color: #0284C7;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                </div>
            </div>
        </div>

        <!-- Card 2: Total Kuis -->
        <div class="stat-card">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                 <div>
                    <span class="text-sm text-muted">Total Kuis</span>
                    <h3 style="font-size: 2rem; font-weight: 700; margin-top: 4px;">{{ number_format($totalKuis) }}</h3>
                </div>
                <div class="stat-icon" style="background: #F3E8FF; color: #9333EA;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                </div>
            </div>
        </div>

        <!-- Card 3: Partisipasi -->
        <div class="stat-card">
             <div style="display: flex; justify-content: space-between; align-items: start;">
                 <div>
                    <span class="text-sm text-muted">Total Partisipasi</span>
                    <h3 style="font-size: 2rem; font-weight: 700; margin-top: 4px;">{{ number_format($totalPartisipasi) }}</h3>
                </div>
                <div class="stat-icon" style="background: #DCFCE7; color: #16A34A;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
                </div>
            </div>
        </div>

        <!-- Card 4: Rata-rata Akurasi -->
        <div class="stat-card">
             <div style="display: flex; justify-content: space-between; align-items: start;">
                 <div>
                    <span class="text-sm text-muted">Rata-rata Akurasi</span>
                    <h3 style="font-size: 2rem; font-weight: 700; margin-top: 4px;">{{ number_format($rataRataAkurasi, 1) }}%</h3>
                </div>
                <div class="stat-icon" style="background: #FEF3C7; color: #D97706;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                </div>
            </div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px; margin-bottom: 32px;">
        <!-- Chart Section -->
        <div class="card">
            <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 16px;">Aktivitas Belajar (7 Hari Terakhir)</h3>
            <div style="height: 300px;">
                <canvas id="quizChart"></canvas>
            </div>
        </div>

        <!-- Content Stats Section -->
        <div class="card">
            <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 16px;">Komposisi Konten</h3>
            <div style="display: flex; flex-direction: column; gap: 16px;">
                @foreach($analitikKategori as $item)
                    @php
                        $progres = $item['progres'];
                        $warnaBar = $progres >= 50 ? 'var(--primary)' : 'var(--blue-500)';
                    @endphp
                    <div>
                        <div style="display: flex; justify-content: space-between; font-size: 0.9rem; margin-bottom: 6px;">
                            <span>{{ $item['nama'] }}</span>
                            <span style="font-weight: 600;">{{ $progres }}%</span>
                        </div>
                        <div style="height: 8px; background: #F3F4F6; border-radius: 99px;">
                            <div style="height: 100%; width: {{ $progres }}%; background: {{ $warnaBar }}; border-radius: 99px;"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Recent Activity Table -->
    <div class="card">
        <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 16px;">Aktivitas Terbaru</h3>
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Siswa</th>
                        <th>Kuis</th>
                        <th>Skor</th>
                        <th>Waktu</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentActivity as $activity)
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div class="avatar" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                        {{ substr($activity->pengguna->nama ?? 'U', 0, 1) }}
                                    </div>
                                    <span style="font-weight: 500;">{{ $activity->pengguna->nama ?? 'Unknown' }}</span>
                                </div>
                            </td>
                            <td>{{ $activity->kuis->judul ?? '-' }}</td>
                            <td style="font-weight: 700;">{{ $activity->skor }}</td>
                            <td class="text-muted">{{ $activity->created_at->diffForHumans() }}</td>
                            <td>
                                @if($activity->skor >= 80)
                                    <span class="badge badge-success">Sangat Baik</span>
                                @elseif($activity->skor >= 60)
                                    <span class="badge badge-warning">Cukup</span>
                                @else
                                    <span class="badge badge-danger">Perlu Latihan</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 24px;">Belum ada aktivitas kuis.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('quizChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($chartLabels),
                    datasets: [
                        {
                            label: 'Partisipasi Kuis',
                            data: @json($quizChartData),
                            borderColor: '#0D9488',
                            backgroundColor: 'rgba(13, 148, 136, 0.1)',
                            borderWidth: 2,
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: '#fff',
                            pointBorderColor: '#0D9488',
                            pointRadius: 4
                        },
                        {
                            label: 'Latihan Kata',
                            data: @json($practiceChartData),
                            borderColor: '#F59E0B',
                            backgroundColor: 'rgba(245, 158, 11, 0.1)',
                            borderWidth: 2,
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: '#fff',
                            pointBorderColor: '#F59E0B',
                            pointRadius: 4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { 
                            display: true,
                            position: 'top',
                            align: 'end',
                            labels: {
                                usePointStyle: true,
                                boxWidth: 8
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { borderDash: [2, 2] },
                            ticks: { stepSize: 1 }
                        },
                        x: {
                            grid: { display: false }
                        }
                    }
                }
            });
        });
    </script>
@endsection

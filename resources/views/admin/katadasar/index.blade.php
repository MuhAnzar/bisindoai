@extends('layouts.admin')

@section('judul', 'Kamus Kata Dasar')
@section('deskripsi', 'Kelola data kata dasar dan video isyarat untuk kamus.')

@section('navigasi')
    <a href="{{ route('admin.katadasar.create') }}" class="btn btn-primary">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
        Tambah Kata
    </a>
@endsection

@section('konten')
    <div class="card">
        @if(session('sukses'))
            <div class="alert alert-success" style="margin-bottom: 24px;">
                {{ session('sukses') }}
            </div>
        @endif

        <div style="overflow-x:auto;">
            <table>
                <thead>
                    <tr>
                        <th>Kata</th>
                        <th>Kategori</th>
                        <th>Arti</th>
                        <th>Video</th>
                        <th style="text-align:right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($katas as $kata)
                        <tr>
                            <td data-label="Kata" style="font-weight:600;">{{ $kata->kata }}</td>
                            <td data-label="Kategori">
                                @if($kata->kategori)
                                    <span class="badge" style="background: #E0F2FE; color: #0369A1;">{{ $kata->kategori }}</span>
                                @else
                                    -
                                @endif
                            </td>
                            <td data-label="Arti" style="color: var(--text-muted);">{{ Str::limit($kata->arti, 40) ?? '-' }}</td>
                            <td data-label="Video">
                                @if($kata->berkas_video)
                                    <span class="badge" style="background: #E0F2FE; color: #0369A1;">Ada Video</span>
                                @else
                                    <span class="badge" style="background: #F1F5F9; color: #64748B;">-</span>
                                @endif
                            </td>
                            <td data-label="Aksi" style="text-align:right;">
                                <div style="display:inline-flex; gap:8px; justify-content:flex-end;">
                                    <a href="{{ route('admin.katadasar.edit', $kata->id) }}" class="btn btn-outline" style="padding:6px 12px; font-size:0.85rem;">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.katadasar.destroy', $kata->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kata ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline" style="color: #EF4444; border-color: #FECACA; padding:6px 12px; font-size:0.85rem;">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

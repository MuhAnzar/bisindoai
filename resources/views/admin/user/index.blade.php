@extends('layouts.admin')

@section('judul', 'Kelola User')
@section('deskripsi', 'Pantau dan kelola progress pembelajaran user')

@section('navigasi')
    <div style="display: flex; gap: 12px;">
        <button class="btn btn-outline">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
            Export
        </button>
        <a href="{{ route('admin.user.create') }}" class="btn btn-primary">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Tambah User
        </a>
    </div>
@endsection

@section('konten')
    @if(session('sukses'))
        <div class="alert alert-success" style="margin-bottom: 24px;">
            {{ session('sukses') }}
        </div>
    @endif

    <div class="card" style="padding: 0; overflow: hidden;">
        <!-- Toolbar -->
        <form action="{{ route('admin.user.index') }}" method="GET" style="padding: 24px; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; flex-wrap: wrap; gap: 16px;">
            <div style="flex: 1; min-width: 300px; position: relative;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-muted);"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..." style="width: 100%; padding: 10px 10px 10px 42px; border: 1px solid var(--border-color); border-radius: 8px; outline: none; transition: border-color 0.2s;">
            </div>
            <div style="display: flex; gap: 12px;">
                <select name="role" onchange="this.form.submit()" style="padding: 10px 16px; border: 1px solid var(--border-color); border-radius: 8px; background: white; color: var(--text-main);">
                    <option value="">Semua Peran</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                </select>
            </div>
        </form>

        <!-- Table -->
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th style="padding-left: 32px;">Nama User</th>
                        <th>Email</th>
                        <th>Peran</th>
                        <th>Tanggal Gabung</th>
                        <th style="padding-right: 32px; text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penggunas as $user)
                        <tr>
                            <td style="padding-left: 32px;">
                                <div style="display: flex; gap: 12px; align-items: center;">
                                    <div style="width: 40px; height: 40px; font-size: 1rem; background: {{ $user->peran === 'admin' ? 'var(--blue-500)' : 'var(--primary)' }}; color: white; border-radius: 10px; display: grid; place-items: center; font-weight: 600;">
                                        {{ substr($user->nama, 0, 1) }}
                                    </div>
                                    <div>
                                        <div style="font-weight: 600; font-size: 0.95rem;">{{ $user->nama }}</div>
                                        <div style="font-size: 0.85rem; color: var(--text-muted);">ID: #{{ $user->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="badge" style="{{ $user->peran === 'admin' ? 'background: #E0F2FE; color: #0369A1;' : 'background: #DCFCE7; color: #166534;' }}">
                                    {{ ucfirst($user->peran) }}
                                </span>
                            </td>
                            <td>
                                <span style="font-size: 0.9rem; color: var(--text-main);">{{ $user->created_at->format('d M Y') }}</span>
                                <div style="font-size: 0.8rem; color: var(--text-muted);">{{ $user->created_at->format('H:i') }}</div>
                            </td>
                            <td style="padding-right: 32px;">
                                <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                    <a href="{{ route('admin.user.edit', $user->id) }}" class="btn btn-sm btn-outline">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.user.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus user ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" style="border: none; cursor: pointer;">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                         <tr>
                            <td colspan="5" style="text-align: center; padding: 48px; color: var(--text-muted);">
                                Tidak ada pengguna ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($penggunas->hasPages())
        <div style="padding: 16px 24px; border-top: 1px solid var(--border-color); background: #F9FAFB;">
            {{ $penggunas->withQueryString()->links() }}
        </div>
        @endif
    </div>
@endsection

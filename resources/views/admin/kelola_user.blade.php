@extends('layouts.admin')

@section('judul', 'Kelola User')
@section('deskripsi', 'Pantau dan kelola progres belajar pengguna')

@section('konten')
    <div class="card">
        <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px; margin-bottom: 24px;">
            <h3>Daftar User</h3>
            <div style="position: relative;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-muted);"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                <input type="text" placeholder="Cari nama atau email..." style="padding: 10px 10px 10px 40px; border: 1px solid var(--border); border-radius: 12px; width: 300px;">
            </div>
        </div>

        <div class="grid" style="grid-template-columns:repeat(auto-fit,minmax(280px,1fr)); gap:24px;">
            @foreach($penggunas as $user)
                <div style="background: white; border: 1px solid var(--border); border-radius: 16px; padding: 20px; display: flex; gap: 16px; align-items: center;">
                    <div style="width: 48px; height: 48px; background: var(--primary-light); color: var(--primary); border-radius: 50%; display: grid; place-items: center; font-weight: 700; font-size: 1.2rem;">
                        {{ strtoupper(substr($user->nama, 0, 1)) }}
                    </div>
                    <div style="flex: 1;">
                        <h4 style="margin-bottom: 4px;">{{ $user->nama }}</h4>
                        <p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 8px;">{{ $user->email }}</p>
                        <span class="badge" style="{{ $user->peran === 'admin' ? 'background: #FCE7F3; color: #BE185D;' : 'background: #E0F2FE; color: #0369A1;' }}">
                            {{ strtoupper($user->peran) }}
                        </span>
                    </div>
                    <button class="btn btn-outline" style="padding: 8px;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                    </button>
                </div>
            @endforeach
        </div>
    </div>
@endsection



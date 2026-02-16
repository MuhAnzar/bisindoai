@extends('layouts.admin')

@section('judul', 'Pengaturan')
@section('deskripsi', 'Kelola preferensi aplikasi dan profil admin.')

@section('konten')
    @if(session('sukses'))
        <div class="alert badge-success" style="margin-bottom: 24px; padding: 16px; border-radius: 8px;">
            {{ session('sukses') }}
        </div>
    @endif

    @if(session('gagal'))
        <div class="alert badge-danger" style="margin-bottom: 24px; padding: 16px; border-radius: 8px; color: white;">
            {{ session('gagal') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert badge-danger" style="margin-bottom: 24px; padding: 16px; border-radius: 8px; color: white;">
            <ul style="margin-left: 16px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-2">
        <!-- Edit Profil -->
        <div class="card">
            <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 16px;">Edit Profil</h3>
            <form action="{{ route('admin.pengaturan.update') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama" value="{{ old('nama', auth()->user()->nama) }}" required>
                </div>
                
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required>
                </div>

                <h4 style="font-weight: 600; margin-top: 24px; margin-bottom: 16px; color: var(--text-muted); font-size: 0.95rem;">Ganti Password (Opsional)</h4>

                <div class="form-group">
                    <label>Password Lama</label>
                    <input type="password" name="password_lama" placeholder="Kosongkan jika tidak ingin mengganti">
                </div>

                <div class="grid grid-2" style="gap: 16px;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label>Password Baru</label>
                        <input type="password" name="password_baru" placeholder="Minimal 8 karakter">
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label>Konfirmasi Password</label>
                        <input type="password" name="password_baru_confirmation" placeholder="Ulangi password baru">
                    </div>
                </div>

                <div class="form-action">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>

        <!-- App Info -->
        <div class="card" style="height: fit-content;">
            <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 16px;">Informasi Aplikasi</h3>
            
            <div style="display: flex; flex-direction: column; gap: 16px;">
                <div>
                    <span class="text-sm text-muted">Nama Aplikasi</span>
                    <div style="font-weight: 600;">BisindoCNN</div>
                </div>
                <div>
                    <span class="text-sm text-muted">Versi</span>
                    <div style="font-weight: 600;">1.0.0</div>
                </div>
                <div>
                    <span class="text-sm text-muted">Developer</span>
                    <div style="font-weight: 600;">Tim Pengembang BISINDO</div>
                </div>
                 <div>
                    <span class="text-sm text-muted">Kontak Support</span>
                    <div style="font-weight: 600;">admin@bisindocnn.com</div>
                </div>
            </div>
        </div>
    </div>
@endsection

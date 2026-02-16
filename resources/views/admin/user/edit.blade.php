@extends('layouts.admin')

@section('judul', 'Edit Pengguna')
@section('deskripsi', 'Perbarui informasi akun pengguna.')

@section('konten')
    <div style="display: flex; justify-content: center;">
        <div class="card" style="width: 100%; max-width: 700px; padding: 32px;">
            <form action="{{ route('admin.user.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div style="display: flex; gap: 16px; margin-bottom: 24px;">
                    <div style="width: 64px; height: 64px; border-radius: 50%; background: var(--primary); color: white; display: grid; place-items: center; font-size: 1.5rem; font-weight: 700;">
                        {{ substr($user->nama, 0, 1) }}
                    </div>
                    <div>
                        <h3 style="font-size: 1.25rem; margin-bottom: 4px;">{{ $user->nama }}</h3>
                        <p class="text-muted">{{ $user->email }}</p>
                    </div>
                </div>

                <div class="form-group">
                    <label for="nama">Nama Lengkap <span style="color: red;">*</span></label>
                    <input type="text" id="nama" name="nama" value="{{ old('nama', $user->nama) }}" required>
                    @error('nama') 
                        <div class="invalid-feedback">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                            {{ $message }}
                        </div> 
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Alamat Email <span style="color: red;">*</span></label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                    @error('email') 
                        <div class="invalid-feedback">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                            {{ $message }}
                        </div> 
                    @enderror
                </div>

                <div class="grid grid-2" style="margin-bottom: 0;">
                    <div class="form-group">
                        <label for="kata_sandi">Kata Sandi (Opsional)</label>
                        <input type="password" id="kata_sandi" name="kata_sandi" placeholder="Biarkan kosong jika tidak diubah">
                        @error('kata_sandi') 
                            <div class="invalid-feedback">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                                {{ $message }}
                            </div> 
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="peran">Peran <span style="color: red;">*</span></label>
                        <div style="position: relative;">
                            <select id="peran" name="peran" style="appearance: none;">
                                <option value="user" {{ old('peran', $user->peran) == 'user' ? 'selected' : '' }}>User</option>
                                <option value="admin" {{ old('peran', $user->peran) == 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                             <div style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); pointer-events: none; color: var(--text-muted);">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
                            </div>
                        </div>
                        @error('peran') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="form-action">
                    <a href="{{ route('admin.user.index') }}" class="btn btn-outline">Batal</a>
                    <button type="submit" class="btn btn-primary">Perbarui Pengguna</button>
                </div>
            </form>
        </div>
    </div>
@endsection

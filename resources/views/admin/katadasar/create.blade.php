@extends('layouts.admin')

@section('judul', 'Tambah Kata Baru')
@section('deskripsi', 'Tambahkan kosakata baru ke dalam kamus isyarat.')

@section('konten')
    <div style="display: flex; justify-content: center;">
        <div class="card" style="width: 100%; max-width: 700px; padding: 32px;">
            <form action="{{ route('admin.katadasar.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <h3 style="margin-bottom: 24px; font-size: 1.25rem;">Detail Kosakata</h3>

                <div class="form-group">
                    <label for="kata">Kata <span style="color: red;">*</span></label>
                    <input type="text" id="kata" name="kata" value="{{ old('kata') }}" required placeholder="Contoh: Makan" style="font-size: 1.1rem; padding: 14px 16px;">
                    @error('kata') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="kategori">Kategori</label>
                    <div style="position: relative;">
                        <input type="text" id="kategori" name="kategori" value="{{ old('kategori') }}" placeholder="Contoh: Kata Kerja" list="kategori_list">
                        <datalist id="kategori_list">
                            <option value="Kata Kerja">
                            <option value="Kata Benda">
                            <option value="Sapaan">
                            <option value="Angka">
                            <option value="Warna">
                        </datalist>
                    </div>
                     <small class="form-text">Bisa pilih dari saran atau ketik baru.</small>
                    @error('kategori') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="arti">Arti / Definisi</label>
                    <textarea id="arti" name="arti" rows="3" placeholder="Jelaskan arti kata ini..." style="resize: vertical;">{{ old('arti') }}</textarea>
                    @error('arti') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="berkas_video">Upload Media (Video/Gambar)</label>
                    <div class="upload-area" style="border: 2px dashed var(--border-color); padding: 32px; border-radius: 12px; text-align: center; cursor: pointer; transition: border-color 0.2s; position: relative; overflow: hidden;" onclick="document.getElementById('berkas_video').click()">
                        
                        <div class="placeholder-content">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--text-muted); margin-bottom: 8px;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                            <p class="text-sm text-muted">Klik untuk upload file</p>
                        </div>

                        <div class="preview-content" style="display: none;">
                            <img id="img-preview" src="#" style="max-width: 100%; max-height: 300px; border-radius: 8px; display: none; margin: 0 auto;">
                            <video id="video-preview" controls style="max-width: 100%; max-height: 300px; border-radius: 8px; display: none; margin: 0 auto;"></video>
                        </div>

                        <input type="file" id="berkas_video" name="berkas_video" accept="video/*,image/*" style="display: none;" onchange="previewMedia(this)">
                    </div>
                    @error('berkas_video') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-action">
                    <a href="{{ route('admin.konten') }}" class="btn btn-outline">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Kata</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewMedia(input) {
            const uploadArea = input.closest('.upload-area');
            const placeholder = uploadArea.querySelector('.placeholder-content');
            const previewContent = uploadArea.querySelector('.preview-content');
            const imgPreview = document.getElementById('img-preview');
            const videoPreview = document.getElementById('video-preview');

            if (input.files && input.files[0]) {
                const file = input.files[0];
                const reader = new FileReader();

                reader.onload = function(e) {
                    placeholder.style.display = 'none';
                    previewContent.style.display = 'block';
                    uploadArea.style.padding = '10px';

                    if (file.type.startsWith('image/')) {
                        imgPreview.src = e.target.result;
                        imgPreview.style.display = 'block';
                        videoPreview.style.display = 'none';
                        videoPreview.src = "";
                    } else if (file.type.startsWith('video/')) {
                        videoPreview.src = e.target.result;
                        videoPreview.style.display = 'block';
                        imgPreview.style.display = 'none';
                        imgPreview.src = "";
                    }
                }
                reader.readAsDataURL(file);
            } else {
                placeholder.style.display = 'block';
                previewContent.style.display = 'none';
                uploadArea.style.padding = '32px';
            }
        }
    </script>
@endsection

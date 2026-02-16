@extends('layouts.admin')

@section('judul', 'Tambah Huruf Baru')
@section('deskripsi', 'Tambahkan huruf baru ke dalam kamus isyarat.')

@section('konten')
    <div style="display: flex; justify-content: center;">
        <div class="card" style="width: 100%; max-width: 600px; padding: 32px;">
            <form action="{{ route('admin.abjad.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <h3 style="margin-bottom: 24px; font-size: 1.25rem;">Detail Huruf</h3>

                <div class="form-group" style="text-align: center;">
                    <label for="huruf" style="margin-bottom: 12px;">Huruf Isyarat</label>
                    <input type="text" id="huruf" name="huruf" value="{{ old('huruf') }}" maxlength="1" required 
                        style="width: 120px; height: 120px; text-transform: uppercase; font-size: 3rem; text-align: center; border: 2px solid var(--border-color); border-radius: 24px; font-weight: 700;">
                    @error('huruf') <div class="invalid-feedback" style="justify-content: center;">{{ $message }}</div> @enderror
                    <small class="form-text" style="text-align: center;">Masukkan satu karakter (A-Z).</small>
                </div>

                <div class="form-group">
                    <label for="deskripsi">Deskripsi (Opsional)</label>
                    <textarea id="deskripsi" name="deskripsi" rows="3" placeholder="Contoh: Gerakan tangan mengepal..." style="resize: vertical; min-height: 100px;">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
                    <button type="submit" class="btn btn-primary">Simpan Huruf</button>
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
                    uploadArea.style.padding = '10px'; // Reduce padding when showing preview

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
                // Reset if no file selected
                placeholder.style.display = 'block';
                previewContent.style.display = 'none';
                uploadArea.style.padding = '32px';
            }
        }
    </script>
@endsection

@extends('layouts.admin')

@section('judul', 'Edit Kata')
@section('deskripsi', 'Perbarui informasi kosakata dan video isyarat.')

@section('konten')
    <div style="display: flex; justify-content: center;">
        <div class="card" style="width: 100%; max-width: 1000px; padding: 32px;">
            <form action="{{ route('admin.katadasar.update', $katadasar->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <h3 style="margin-bottom: 24px; font-size: 1.25rem; border-bottom: 1px solid var(--border-color); padding-bottom: 12px;">Edit Kosakata: {{ $katadasar->kata }}</h3>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; align-items: start;">
                    <!-- Left Column: Details -->
                    <div>
                        <h4 style="font-size: 1rem; margin-bottom: 16px; color: var(--text-color);">Informasi Dasar</h4>

                        <div class="form-group">
                            <label for="kata">Kata <span style="color: red;">*</span></label>
                            <input type="text" id="kata" name="kata" value="{{ old('kata', $katadasar->kata) }}" required style="font-size: 1.1rem; padding: 14px 16px; font-weight: 600;">
                            @error('kata') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group">
                            <label for="kategori">Kategori</label>
                            <input type="text" id="kategori" name="kategori" value="{{ old('kategori', $katadasar->kategori) }}" list="kategori_list">
                            <datalist id="kategori_list">
                                <option value="Kata Kerja">
                                <option value="Kata Benda">
                                <option value="Sapaan">
                                <option value="Angka">
                                <option value="Warna">
                            </datalist>
                            @error('kategori') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group">
                            <label for="arti">Arti / Definisi</label>
                            <textarea id="arti" name="arti" rows="6" style="resize: vertical;">{{ old('arti', $katadasar->arti) }}</textarea>
                            @error('arti') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <!-- Right Column: Media -->
                    <div>
                        <h4 style="font-size: 1rem; margin-bottom: 16px; color: var(--text-color);">Media</h4>

                        <div class="form-group">
                            <label>Media Saat Ini</label>
                            @if($katadasar->berkas_video)
                                <div style="margin-bottom: 16px; padding: 12px; border: 1px solid var(--border-color); border-radius: 12px; background: #f9fafb;">
                                    @php
                                        $extension = pathinfo($katadasar->berkas_video, PATHINFO_EXTENSION);
                                        $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                        $isVideo = in_array(strtolower($extension), ['mp4', 'webm', 'ogg', 'mov']);
                                    @endphp

                                    @if($isImage)
                                        <img src="{{ asset($katadasar->berkas_video) }}" alt="Preview" style="max-width: 100%; max-height: 200px; border-radius: 8px; display: block; margin: 0 auto; object-fit: contain;">
                                    @elseif($isVideo)
                                        <video controls style="max-width: 100%; max-height: 200px; border-radius: 8px; display: block; margin: 0 auto;">
                                            <source src="{{ asset($katadasar->berkas_video) }}" type="video/{{ $extension }}">
                                            Your browser does not support the video tag.
                                        </video>
                                    @else
                                        <div style="display: flex; align-items: center; justify-content: space-between;">
                                            <div class="text-sm text-main" style="display: flex; align-items: center; gap: 8px;">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                                                File Tersimpan ({{ $extension }})
                                            </div>
                                            <a href="{{ asset($katadasar->berkas_video) }}" target="_blank" class="text-sm" style="color: var(--primary); font-weight: 500; text-decoration: none;">Lihat File</a>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="text-sm text-muted" style="margin-bottom: 12px; font-style: italic;">Belum ada file media.</div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="berkas_video">Ganti / Upload Media</label>
                            <div class="upload-area" style="border: 2px dashed var(--border-color); padding: 24px; border-radius: 12px; text-align: center; cursor: pointer; transition: border-color 0.2s; position: relative; overflow: hidden; background: #fff;" onclick="document.getElementById('berkas_video').click()">
                                
                                <div class="placeholder-content">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--text-muted); margin-bottom: 6px;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                                    <p class="text-sm text-muted">Klik untuk pilih file</p>
                                </div>

                                <div class="preview-content" style="display: none;">
                                    <img id="img-preview" src="#" style="max-width: 100%; max-height: 180px; border-radius: 8px; display: none; margin: 0 auto; object-fit: contain;">
                                    <video id="video-preview" controls style="max-width: 100%; max-height: 180px; border-radius: 8px; display: none; margin: 0 auto;"></video>
                                </div>

                                <input type="file" id="berkas_video" name="berkas_video" accept="video/*,image/*" style="display: none;" onchange="previewMedia(this)">
                            </div>
                            @error('berkas_video') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                <div class="form-action" style="margin-top: 32px; border-top: 1px solid var(--border-color); padding-top: 24px; display: flex; justify-content: flex-end; gap: 12px;">
                    <a href="{{ route('admin.konten') }}" class="btn btn-outline">Batal</a>
                    <button type="submit" class="btn btn-primary">Perbarui Kata</button>
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

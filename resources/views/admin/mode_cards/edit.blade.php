@extends('layouts.admin')

@section('judul', 'Edit Mode Card')
@section('deskripsi', 'Edit tampilan kartu: ' . $modeCard->title)

@section('navigasi')
    <a href="{{ route('admin.mode-cards.index') }}" class="btn btn-outline" style="display: inline-flex; align-items: center; gap: 8px;">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
        Kembali
    </a>
@endsection

@section('konten')
    <style>
        .form-card { background: white; border-radius: 16px; border: 1px solid var(--border-color); padding: 24px; margin-bottom: 24px; }
        .form-card h3 { font-size: 1.1rem; font-weight: 600; margin-bottom: 20px; padding-bottom: 16px; border-bottom: 1px solid var(--border-color); }
        .form-group { margin-bottom: 20px; }
        .form-label { display: block; font-weight: 600; margin-bottom: 8px; color: var(--text-main); }
        .form-input { width: 100%; padding: 12px 16px; border: 1px solid var(--border-color); border-radius: 10px; font-size: 1rem; transition: all 0.2s; }
        .form-input:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.1); }
        .form-textarea { min-height: 120px; resize: vertical; }
        .form-help { font-size: 0.85rem; color: var(--text-muted); margin-top: 6px; }
        .color-preview { width: 40px; height: 40px; border-radius: 8px; border: 2px solid var(--border-color); }
        .color-input-group { display: flex; align-items: center; gap: 12px; }
        .feature-list { list-style: none; padding: 0; }
        .feature-item { display: flex; align-items: center; gap: 8px; margin-bottom: 8px; }
        .feature-item input { flex: 1; }
        .btn-remove-feature { background: #FEE2E2; color: #DC2626; border: none; padding: 8px 12px; border-radius: 8px; cursor: pointer; }
        .btn-add-feature { background: #F0FDFA; color: var(--primary); border: 1px dashed var(--primary); padding: 10px 16px; border-radius: 8px; cursor: pointer; width: 100%; font-weight: 600; }
        .current-image { max-width: 200px; border-radius: 12px; margin-top: 12px; }
        .preview-card { background: linear-gradient(135deg, var(--gradient-from, #57BBA0), var(--gradient-to, #45A38A)); border-radius: 16px; padding: 24px; color: white; }
        .error-text { color: #DC2626; font-size: 0.85rem; margin-top: 4px; }
    </style>

    @if($errors->any())
        <div style="background: #FEE2E2; color: #DC2626; padding: 16px; border-radius: 12px; margin-bottom: 24px;">
            <strong>Terjadi kesalahan:</strong>
            <ul style="margin: 8px 0 0 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.mode-cards.update', $modeCard) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
            <!-- Left Column: Form -->
            <div>
                <!-- Basic Info -->
                <div class="form-card">
                    <h3>📝 Informasi Dasar</h3>
                    
                    <div class="form-group">
                        <label class="form-label">Judul Card</label>
                        <input type="text" name="title" class="form-input" value="{{ old('title', $modeCard->title) }}" required>
                        @error('title')<span class="error-text">{{ $message }}</span>@enderror
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" class="form-input form-textarea" required>{{ old('description', $modeCard->description) }}</textarea>
                        <p class="form-help">Jelaskan fitur dan manfaat mode latihan ini.</p>
                        @error('description')<span class="error-text">{{ $message }}</span>@enderror
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div class="form-group">
                            <label class="form-label">Teks Badge</label>
                            <input type="text" name="badge_text" class="form-input" value="{{ old('badge_text', $modeCard->badge_text) }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Emoji Badge</label>
                            <input type="text" name="badge_emoji" class="form-input" value="{{ old('badge_emoji', $modeCard->badge_emoji) }}">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Teks Tombol</label>
                        <input type="text" name="button_text" class="form-input" value="{{ old('button_text', $modeCard->button_text) }}" required>
                    </div>
                </div>

                <!-- Colors -->
                <div class="form-card">
                    <h3>🎨 Warna Gradient</h3>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div class="form-group">
                            <label class="form-label">Warna Awal</label>
                            <div class="color-input-group">
                                <input type="color" name="gradient_from" value="{{ old('gradient_from', $modeCard->gradient_from) }}" class="color-preview" id="colorFrom">
                                <input type="text" class="form-input" value="{{ $modeCard->gradient_from }}" id="colorFromText" onchange="document.getElementById('colorFrom').value = this.value">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Warna Akhir</label>
                            <div class="color-input-group">
                                <input type="color" name="gradient_to" value="{{ old('gradient_to', $modeCard->gradient_to) }}" class="color-preview" id="colorTo">
                                <input type="text" class="form-input" value="{{ $modeCard->gradient_to }}" id="colorToText" onchange="document.getElementById('colorTo').value = this.value">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Features -->
                <div class="form-card">
                    <h3>✅ Fitur-fitur</h3>
                    <p class="form-help" style="margin-bottom: 16px;">Daftar fitur yang akan ditampilkan pada kartu.</p>
                    
                    <ul class="feature-list" id="featureList">
                        @foreach(old('features', $modeCard->features ?? []) as $index => $feature)
                            <li class="feature-item">
                                <input type="text" name="features[]" class="form-input" value="{{ $feature }}" placeholder="Fitur...">
                                <button type="button" class="btn-remove-feature" onclick="removeFeature(this)">✕</button>
                            </li>
                        @endforeach
                    </ul>
                    
                    <button type="button" class="btn-add-feature" onclick="addFeature()">+ Tambah Fitur</button>
                </div>

                <!-- Image -->
                <div class="form-card">
                    <h3>🖼️ Gambar Custom (Opsional)</h3>
                    
                    @if($modeCard->image)
                        <div style="margin-bottom: 16px;">
                            <p class="form-label">Gambar Saat Ini:</p>
                            <img src="{{ asset('storage/' . $modeCard->image) }}" alt="{{ $modeCard->title }}" class="current-image">
                            <br>
                            <a href="{{ route('admin.mode-cards.remove-image', $modeCard) }}" class="btn btn-outline" style="margin-top: 12px; display: inline-flex; align-items: center; gap: 6px;" onclick="return confirm('Hapus gambar ini?')">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                Hapus Gambar
                            </a>
                        </div>
                    @endif
                    
                    <div class="form-group">
                        <label class="form-label">Upload Gambar Baru</label>
                        <input type="file" name="image" class="form-input" accept="image/*">
                        <p class="form-help">Format: JPG, PNG, GIF, WebP. Maksimal 2MB.</p>
                    </div>
                </div>

                <!-- Status -->
                <div class="form-card">
                    <h3>⚙️ Status</h3>
                    <label style="display: flex; align-items: center; gap: 12px; cursor: pointer;">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $modeCard->is_active) ? 'checked' : '' }} style="width: 20px; height: 20px;">
                        <span>Aktifkan mode card ini</span>
                    </label>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 16px; font-size: 1rem;">
                    💾 Simpan Perubahan
                </button>
            </div>

            <!-- Right Column: Preview -->
            <div>
                <div style="position: sticky; top: 100px;">
                    <h4 style="margin-bottom: 16px; font-weight: 600;">Preview Card:</h4>
                    <div class="preview-card" id="previewCard" style="--gradient-from: {{ $modeCard->gradient_from }}; --gradient-to: {{ $modeCard->gradient_to }};">
                        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
                            <div style="width: 50px; height: 50px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: 700;">
                                @if($modeCard->icon_type === 'letter')
                                    {{ $modeCard->icon_content ?? strtoupper(substr($modeCard->mode_key, 0, 1)) }}
                                @else
                                    {!! $modeCard->icon_content !!}
                                @endif
                            </div>
                            <div>
                                <h3 id="previewTitle" style="font-weight: 700;">{{ $modeCard->title }}</h3>
                            </div>
                        </div>
                        <p id="previewDesc" style="opacity: 0.9; margin-bottom: 16px; font-size: 0.9rem;">{{ $modeCard->description }}</p>
                        <div style="background: white; color: #333; padding: 12px 20px; border-radius: 12px; text-align: center; font-weight: 600;">
                            <span id="previewButton">{{ $modeCard->button_text }}</span> →
                        </div>
                    </div>
                    
                    <p class="text-muted" style="margin-top: 16px; font-size: 0.85rem;">
                        💡 Preview akan diperbarui otomatis saat Anda mengubah form.
                    </p>
                </div>
            </div>
        </div>
    </form>

    <script>
        function addFeature() {
            const list = document.getElementById('featureList');
            const li = document.createElement('li');
            li.className = 'feature-item';
            li.innerHTML = `
                <input type="text" name="features[]" class="form-input" placeholder="Fitur baru...">
                <button type="button" class="btn-remove-feature" onclick="removeFeature(this)">✕</button>
            `;
            list.appendChild(li);
        }

        function removeFeature(btn) {
            btn.parentElement.remove();
        }

        // Live preview
        document.querySelector('input[name="title"]').addEventListener('input', function() {
            document.getElementById('previewTitle').textContent = this.value;
        });

        document.querySelector('textarea[name="description"]').addEventListener('input', function() {
            document.getElementById('previewDesc').textContent = this.value;
        });

        document.querySelector('input[name="button_text"]').addEventListener('input', function() {
            document.getElementById('previewButton').textContent = this.value;
        });

        document.getElementById('colorFrom').addEventListener('input', function() {
            document.getElementById('previewCard').style.setProperty('--gradient-from', this.value);
            document.getElementById('colorFromText').value = this.value;
        });

        document.getElementById('colorTo').addEventListener('input', function() {
            document.getElementById('previewCard').style.setProperty('--gradient-to', this.value);
            document.getElementById('colorToText').value = this.value;
        });
    </script>
@endsection

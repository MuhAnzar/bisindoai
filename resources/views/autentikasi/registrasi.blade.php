@extends('komponen.tata_letak')

@section('judul', 'Daftar Akun')
@section('deskripsi', 'Bergabung dengan komunitas belajar BISINDO')

@push('gaya')
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
<style>
    * { font-family: 'Nunito', sans-serif; }
    
    .auth-page {
        min-height: calc(100vh - 80px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 1rem;
        background: linear-gradient(135deg, #f0fdfa 0%, #f8fafc 50%, #ecfdf5 100%);
        position: relative;
        overflow: hidden;
        margin: -40px -1rem 0 -1rem;
        width: calc(100% + 2rem);
    }
    
    /* Decorative Background Elements */
    .auth-page::before {
        content: '';
        position: absolute;
        top: -200px;
        right: -200px;
        width: 500px;
        height: 500px;
        border-radius: 50%;
        background: linear-gradient(135deg, rgba(20, 184, 166, 0.1) 0%, rgba(16, 185, 129, 0.05) 100%);
        z-index: 0;
    }
    .auth-page::after {
        content: '';
        position: absolute;
        bottom: -150px;
        left: -150px;
        width: 400px;
        height: 400px;
        border-radius: 50%;
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.08) 0%, rgba(20, 184, 166, 0.03) 100%);
        z-index: 0;
    }
    
    .auth-container {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0;
        max-width: 1000px;
        width: 100%;
        background: white;
        border-radius: 32px;
        box-shadow: 0 25px 80px -20px rgba(0, 0, 0, 0.15), 0 0 0 1px rgba(0, 0, 0, 0.02);
        overflow: hidden;
        position: relative;
        z-index: 1;
    }
    
    /* Left Side - Visual */
    .auth-visual {
        background: linear-gradient(135deg, #0D9488 0%, #0F766E 100%);
        padding: 3rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }
    
    .auth-visual::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }
    
    .visual-content {
        position: relative;
        z-index: 10;
        text-align: center;
        color: white;
    }
    
    .logo-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 2rem;
    }
    
    .logo-icon {
        width: 48px;
        height: 48px;
        background: white;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        font-weight: 900;
        color: #0D9488;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }
    
    .logo-text {
        font-size: 1.5rem;
        font-weight: 900;
        letter-spacing: -0.02em;
    }
    
    .illustration-container {
        position: relative;
        margin: 1.5rem 0;
    }
    
    .illustration-img {
        max-width: 320px;
        margin: 0 auto;
        filter: drop-shadow(0 20px 40px rgba(0,0,0,0.2));
        animation: float-y 4s ease-in-out infinite;
    }
    
    @keyframes float-y {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-15px); }
    }
    
    .visual-title {
        font-size: 1.75rem;
        font-weight: 900;
        margin-bottom: 0.75rem;
        line-height: 1.2;
    }
    
    .visual-desc {
        font-size: 1rem;
        opacity: 0.9;
        max-width: 280px;
        margin: 0 auto 1.5rem;
        line-height: 1.6;
    }
    
    .features-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        max-width: 260px;
        margin: 0 auto;
    }
    
    .feature-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-weight: 700;
        font-size: 0.9rem;
        text-align: left;
    }
    
    .feature-icon {
        width: 32px;
        height: 32px;
        background: rgba(255,255,255,0.2);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    
    /* Right Side - Form */
    .auth-form-side {
        padding: 3rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    
    .auth-header h1 {
        font-size: 2rem;
        font-weight: 900;
        color: #0f172a;
        margin-bottom: 0.5rem;
    }
    
    .auth-header p {
        color: #64748b;
        font-size: 1rem;
        margin-bottom: 2rem;
        font-weight: 600;
    }
    
    .form-group {
        margin-bottom: 1.25rem;
    }
    
    .form-label {
        display: block;
        font-weight: 800;
        font-size: 0.75rem;
        color: #475569;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    
    .form-input-wrapper {
        position: relative;
    }
    
    .form-input {
        width: 100%;
        padding: 0.875rem 1rem;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        font-size: 0.95rem;
        font-weight: 600;
        transition: all 0.2s ease;
        background: #f8fafc;
    }
    
    .form-input:focus {
        background: white;
        border-color: #0D9488;
        box-shadow: 0 0 0 4px rgba(13, 148, 136, 0.15);
        outline: none;
    }
    
    .form-input::placeholder {
        color: #94a3b8;
    }
    
    .btn-toggle-password {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #94a3b8;
        cursor: pointer;
        padding: 0.25rem;
        transition: color 0.2s;
    }
    
    .btn-toggle-password:hover {
        color: #0D9488;
    }
    
    .password-hint {
        font-size: 0.75rem;
        color: #94a3b8;
        margin-top: 0.375rem;
    }
    
    .btn-submit {
        width: 100%;
        padding: 1rem;
        background: linear-gradient(135deg, #0D9488 0%, #0F766E 100%);
        color: white;
        border: none;
        border-radius: 14px;
        font-weight: 800;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 8px 25px rgba(13, 148, 136, 0.35);
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 35px rgba(13, 148, 136, 0.45);
    }
    
    .btn-submit:active {
        transform: translateY(0);
    }
    
    .auth-footer {
        text-align: center;
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 2px solid #f1f5f9;
        color: #64748b;
        font-weight: 600;
    }
    
    .auth-link {
        color: #0D9488;
        font-weight: 800;
        text-decoration: none;
        transition: color 0.2s;
    }
    
    .auth-link:hover {
        color: #0F766E;
        text-decoration: underline;
    }
    
    .alert-error {
        background: linear-gradient(135deg, #fef2f2, #fee2e2);
        border: 2px solid #fecaca;
        color: #991b1b;
        padding: 1rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        font-size: 0.875rem;
        display: flex;
        gap: 0.75rem;
        align-items: flex-start;
    }
    
    @media (max-width: 900px) {
        .auth-container {
            grid-template-columns: 1fr;
            max-width: 450px;
        }
        .auth-visual {
            padding: 2rem;
        }
        .features-list {
            display: none;
        }
        .illustration-img {
            max-width: 150px;
        }
        .visual-title {
            font-size: 1.5rem;
        }
    }
</style>
@endpush

@section('konten')
<div class="auth-page">
    <div class="auth-container">
        <!-- Left Side: Visual -->
        <div class="auth-visual">
            <div class="visual-content">
                <div class="logo-badge">
                    <div class="logo-icon">B</div>
                    <span class="logo-text">BISINDO</span>
                </div>
                
                <div class="illustration-container">
                    <img src="{{ asset('img/karakter_register.png') }}" alt="Welcome" class="illustration-img">
                </div>
                
                <h2 class="visual-title">Mulai Perjalananmu! 🚀</h2>
                <p class="visual-desc">Bergabunglah dengan ribuan pelajar bahasa isyarat Indonesia.</p>
                
                <div class="features-list">
                    <div class="feature-item">
                        <div class="feature-icon">✅</div>
                        <span>100% Gratis, tanpa biaya</span>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">🤖</div>
                        <span>Koreksi AI real-time</span>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">📚</div>
                        <span>Materi lengkap A-Z</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Side: Form -->
        <div class="auth-form-side">
            <div class="auth-header">
                <h1>Buat Akun Baru</h1>
                <p>Daftar gratis dan mulai belajar bahasa isyarat!</p>
            </div>

            @if ($errors->any())
                <div class="alert-error">
                    <svg style="flex-shrink: 0; width: 20px; height: 20px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="8" x2="12" y2="12"/>
                        <line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                    <div>
                        <strong style="display:block; margin-bottom:0.25rem;">Perbaiki Error</strong>
                        <ul style="margin:0; padding-left:1rem;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('daftar.proses') }}">
                @csrf
                
                <div class="form-group">
                    <label for="nama" class="form-label">Nama Lengkap</label>
                    <input id="nama" type="text" name="nama" class="form-input" 
                           value="{{ old('nama') }}" placeholder="Masukkan nama lengkap" required autofocus>
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input id="email" type="email" name="email" class="form-input" 
                           value="{{ old('email') }}" placeholder="nama@email.com" required>
                </div>

                <div class="form-group">
                    <label for="kata_sandi" class="form-label">Kata Sandi</label>
                    <div class="form-input-wrapper">
                        <input id="kata_sandi" type="password" name="kata_sandi" class="form-input" 
                               placeholder="Buat kata sandi yang kuat" required>
                        <button type="button" class="btn-toggle-password" onclick="togglePassword(this, 'kata_sandi')">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </button>
                    </div>
                    <div class="password-hint">Minimal 8 karakter</div>
                </div>

                <div class="form-group">
                    <label for="kata_sandi_confirmation" class="form-label">Konfirmasi Kata Sandi</label>
                    <div class="form-input-wrapper">
                        <input id="kata_sandi_confirmation" type="password" name="kata_sandi_confirmation" class="form-input" 
                               placeholder="Ketik ulang kata sandi" required>
                        <button type="button" class="btn-toggle-password" onclick="togglePassword(this, 'kata_sandi_confirmation')">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn-submit">
                    Daftar Sekarang
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                </button>
            </form>

            <div class="auth-footer">
                Sudah punya akun? 
                <a href="{{ route('masuk') }}" class="auth-link">Masuk disini</a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('skrip')
<script>
    function togglePassword(btn, inputId) {
        const input = document.getElementById(inputId);
        if (input.type === 'password') {
            input.type = 'text';
            btn.innerHTML = `
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                    <line x1="1" y1="1" x2="23" y2="23"/>
                </svg>
            `;
            btn.style.color = '#0D9488';
        } else {
            input.type = 'password';
            btn.innerHTML = `
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                    <circle cx="12" cy="12" r="3"/>
                </svg>
            `;
            btn.style.color = '#94a3b8';
        }
    }
</script>
@endpush

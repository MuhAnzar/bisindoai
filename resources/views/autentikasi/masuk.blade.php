@extends('komponen.tata_letak')

@section('judul', 'Masuk')
@section('deskripsi', 'Selamat datang kembali di BISINDO Learning')

@push('gaya')
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
<style>
    .auth-container {
        font-family: 'Nunito', sans-serif;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 3rem;
        max-width: 1100px;
        margin: 0 auto;
        padding: 2rem;
        min-height: 70vh;
        align-items: center;
    }
    
    /* Left Side - Illustration */
    .auth-visual {
        background: linear-gradient(135deg, #0D9488 0%, #0F766E 100%);
        border-radius: 32px;
        padding: 3rem;
        text-align: center;
        color: white;
        position: relative;
        overflow: hidden;
        min-height: 500px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    
    .auth-visual::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }
    
    .blob-decoration {
        position: absolute;
        width: 200px;
        height: 200px;
        border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
        background: rgba(255,255,255,0.08);
        animation: blob-morph 8s ease-in-out infinite;
        z-index: 1;
    }
    .blob-1 { top: -30px; right: -30px; }
    .blob-2 { bottom: -30px; left: -30px; animation-delay: -4s; }
    
    @keyframes blob-morph {
        0%, 100% { border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%; }
        50% { border-radius: 70% 30% 30% 70% / 70% 70% 30% 30%; }
    }
    
    .visual-content {
        position: relative;
        z-index: 10;
    }
    
    .illustration-img {
        max-width: 320px;
        margin: 0 auto 1.5rem;
        filter: drop-shadow(0 10px 20px rgba(0,0,0,0.2));
        animation: float-y 4s ease-in-out infinite;
        position: relative;
        z-index: 15;
    }
    
    @keyframes float-y {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-12px); }
    }
    
    .visual-content h2 {
        font-size: 1.8rem;
        font-weight: 900;
        margin-bottom: 0.75rem;
        line-height: 1.2;
    }
    
    .visual-content p {
        font-size: 1rem;
        opacity: 0.9;
        line-height: 1.6;
        font-weight: 600;
        max-width: 300px;
        margin: 0 auto;
    }
    
    .stats-row {
        margin-top: 2rem;
        display: flex;
        gap: 2rem;
        justify-content: center;
    }
    
    .stat-item { text-align: center; }
    .stat-value { font-size: 1.8rem; font-weight: 900; display: block; }
    .stat-label { font-size: 0.8rem; opacity: 0.8; font-weight: 700; }
    
    /* Right Side - Form */
    .auth-form-side {
        padding: 1rem;
    }
    
    .auth-form-container {
        max-width: 380px;
    }
    
    .auth-header h1 {
        font-size: 2rem;
        font-weight: 900;
        color: #1a1a2e;
        margin-bottom: 0.5rem;
    }
    
    .auth-header p {
        color: #64748b;
        font-size: 1rem;
        margin-bottom: 2rem;
        font-weight: 600;
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-label {
        display: block;
        font-weight: 800;
        font-size: 0.85rem;
        color: #334155;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    
    .form-input-wrapper {
        position: relative;
    }
    
    .form-input {
        width: 100%;
        padding: 1rem 1.25rem;
        border: 3px solid #e2e8f0;
        border-radius: 14px;
        font-size: 1rem;
        transition: all 0.2s ease;
        background: #f8fafc;
        font-family: 'Nunito', sans-serif;
        font-weight: 700;
    }
    
    .form-input:focus {
        background: white;
        border-color: #0D9488;
        box-shadow: 0 0 0 4px rgba(13, 148, 136, 0.15);
        outline: none;
    }
    
    .form-input::placeholder {
        font-weight: 600;
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
    
    .remember-forgot {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }
    
    .remember-me {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
    }
    
    .checkbox-custom {
        width: 20px;
        height: 20px;
        border: 3px solid #cbd5e1;
        border-radius: 6px;
        cursor: pointer;
        accent-color: #0D9488;
    }
    
    .btn-submit {
        width: 100%;
        padding: 1rem;
        background: linear-gradient(135deg, #0D9488 0%, #0F766E 100%);
        color: white;
        border: none;
        border-radius: 14px;
        font-weight: 800;
        font-size: 1.05rem;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 10px 30px rgba(13, 148, 136, 0.35);
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    
    .btn-submit:hover {
        transform: translateY(-3px) scale(1.01);
        box-shadow: 0 15px 40px rgba(13, 148, 136, 0.45);
    }
    
    .btn-submit:active {
        transform: translateY(0);
    }
    
    .auth-footer {
        text-align: center;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 2px solid #f1f5f9;
        color: #64748b;
        font-size: 1rem;
        font-weight: 700;
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
        border: 3px solid #fecaca;
        color: #991b1b;
        padding: 1rem;
        border-radius: 14px;
        margin-bottom: 1.5rem;
        font-size: 0.9rem;
        display: flex;
        gap: 0.75rem;
        align-items: flex-start;
        font-weight: 600;
    }
    
    @media (max-width: 900px) {
        .auth-container {
            grid-template-columns: 1fr;
            gap: 2rem;
        }
        .auth-visual {
            min-height: 350px;
            padding: 2rem;
        }
        .illustration-img {
            max-width: 160px;
        }
    }
</style>
@endpush

@section('konten')
<div class="auth-container">
    <!-- Left Side: Visual with Illustration -->
    <div class="auth-visual">
        <div class="blob-decoration blob-1"></div>
        <div class="blob-decoration blob-2"></div>
        
        <div class="visual-content">
            <img src="{{ asset('img/karakter_login.png') }}" alt="Welcome Back" class="illustration-img">
            
            <h2>Selamat Datang Kembali! 👋</h2>
            <p>Lanjutkan perjalanan belajar BISINDO Anda. AI siap membantu!</p>
            
            <div class="stats-row">
                <div class="stat-item">
                    <span class="stat-value">{{ $totalPengguna ?? '2.5k' }}</span>
                    <span class="stat-label">Pelajar Aktif</span>
                </div>
                <div class="stat-item">
                    <span class="stat-value">95%</span>
                    <span class="stat-label">Akurasi AI</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Right Side: Form -->
    <div class="auth-form-side">
        <div class="auth-form-container">
            <div class="auth-header">
                <h1>Masuk ke Akun</h1>
                <p>Masukkan email dan kata sandi untuk melanjutkan</p>
            </div>

            @if ($errors->any())
                <div class="alert-error">
                    <svg style="flex-shrink: 0; width: 20px; height: 20px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="8" x2="12" y2="12"/>
                        <line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                    <div>
                        <strong style="display:block; margin-bottom:0.25rem;">Login Gagal</strong>
                        <ul style="margin:0; padding-left:1rem;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('masuk.proses') }}">
                @csrf
                
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input id="email" type="email" name="email" class="form-input" 
                           value="{{ old('email') }}" placeholder="nama@email.com" required autofocus>
                </div>

                <div class="form-group">
                    <label for="kata_sandi" class="form-label">Kata Sandi</label>
                    <div class="form-input-wrapper">
                        <input id="kata_sandi" type="password" name="kata_sandi" class="form-input" 
                               placeholder="Masukkan kata sandi" required>
                        <button type="button" class="btn-toggle-password" onclick="togglePassword(this, 'kata_sandi')">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="remember-forgot">
                    <label class="remember-me">
                        <input type="checkbox" name="ingat_saya" class="checkbox-custom">
                        <span style="color: #64748b; font-size: 0.9rem; font-weight: 700;">Ingat saya</span>
                    </label>
                    <a href="#" class="auth-link" style="font-size: 0.9rem;">Lupa password?</a>
                </div>

                <button type="submit" class="btn-submit">
                    Masuk Sekarang →
                </button>
            </form>

            <div class="auth-footer">
                Belum punya akun? 
                <a href="{{ route('daftar') }}" class="auth-link">Daftar Gratis</a>
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

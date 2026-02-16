<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('judul', 'Belajar BISINDO') - Platform Pembelajaran Bahasa Isyarat</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            /* Brand Colors */
            --primary: #0D9488;
            --primary-dark: #0F766E;
            --primary-light: #CCFBF1;
            --accent: #F43F5E;
            
            /* Neutral Colors */
            --bg-body: #F8FAFC;
            --bg-card: #FFFFFF;
            --text-main: #0F172A;
            --text-muted: #64748B;
            --border: #E2E8F0;

            /* Effects */
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --glass: rgba(255, 255, 255, 0.8);
            --glass-border: rgba(255, 255, 255, 0.5);
        }

        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-body);
            color: var(--text-main);
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            line-height: 1.2;
            color: var(--text-main);
        }

        .container {
            width: min(1200px, 92vw);
            margin: 0 auto;
        }

        /* Navbar */
        .navbar-wrapper {
            position: sticky;
            top: 0;
            z-index: 100;
            background: var(--glass);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.6);
        }

        .navbar {
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .brand-logo {
            width: 40px;
            height: 40px;
            background: var(--primary);
            border-radius: 12px;
            display: grid;
            place-items: center;
            color: white;
            font-weight: 700;
            font-family: 'Outfit', sans-serif;
            font-size: 1.2rem;
            box-shadow: 0 4px 12px rgba(13, 148, 136, 0.2);
        }

        .brand-text {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 1.25rem;
            color: var(--text-main);
            letter-spacing: -0.02em;
        }

        .nav-links {
            display: flex;
            gap: 8px;
            background: rgba(241, 245, 249, 0.5);
            padding: 6px;
            border-radius: 99px;
            border: 1px solid rgba(226, 232, 240, 0.5);
        }

        .nav-link {
            text-decoration: none;
            color: var(--text-muted);
            font-weight: 500;
            padding: 8px 20px;
            border-radius: 99px;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }

        .nav-link:hover {
            color: var(--primary);
            background: rgba(255, 255, 255, 0.5);
        }

        .nav-link.active {
            background: white;
            color: var(--primary);
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            font-weight: 600;
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 24px;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            font-family: 'Outfit', sans-serif;
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
            box-shadow: 0 4px 12px rgba(13, 148, 136, 0.2);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(13, 148, 136, 0.3);
        }

        .btn-outline {
            background: transparent;
            border: 1px solid var(--border);
            color: var(--text-main);
        }

        .btn-outline:hover {
            border-color: var(--text-muted);
            background: white;
        }

        /* Main Content */
        main {
            flex: 1;
            padding: 40px 0;
        }

        .page-header {
            margin-bottom: 48px;
            text-align: center;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }

        .page-title {
            font-size: 2.5rem;
            margin-bottom: 16px;
            color: var(--text-main);
        }

        .page-desc {
            font-size: 1.1rem;
            color: var(--text-muted);
        }

        /* Footer */
        footer {
            background: white;
            border-top: 1px solid var(--border);
            padding: 60px 0 30px;
            margin-top: auto;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 40px;
            margin-bottom: 40px;
        }

        .footer-brand p {
            margin-top: 16px;
            color: var(--text-muted);
            max-width: 300px;
        }

        .footer-col h4 {
            margin-bottom: 20px;
            font-size: 1.1rem;
        }

        .footer-links {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .footer-links a {
            text-decoration: none;
            color: var(--text-muted);
            transition: color 0.2s;
        }

        .footer-links a:hover {
            color: var(--primary);
        }

        .footer-bottom {
            border-top: 1px solid var(--border);
            padding-top: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .navbar { height: auto; padding: 16px 0; flex-direction: column; gap: 16px; }
            .nav-links { width: 100%; overflow-x: auto; justify-content: center; }
            .footer-grid { grid-template-columns: 1fr; gap: 32px; }
            .footer-bottom { flex-direction: column; gap: 16px; text-align: center; }
        }
    </style>
    @stack('gaya')
</head>
<body>
    <div class="navbar-wrapper">
        <div class="container navbar">
            <a href="{{ route('halaman-utama') }}" class="brand" style="gap: 0px;">
                <img src="{{ asset('img/logo2.png') }}" alt="Isyarat Pintar" style="height: 130px; width: auto; margin-right: -20px; position: relative; z-index: 1;">
                <span class="brand-text" style="font-size: 1.5rem; position: relative; z-index: 2;">Isyarat Pintar</span>
            </a>

            <nav class="nav-links">
                <a href="{{ route('halaman-utama') }}" class="nav-link {{ request()->routeIs('halaman-utama') ? 'active' : '' }}">Beranda</a>
                <a href="{{ route('kamus.abjad') }}" class="nav-link {{ request()->routeIs('kamus.*') ? 'active' : '' }}">Kamus</a>
                <a href="{{ route('latihan.deteksi') }}" class="nav-link {{ request()->routeIs('latihan.*') ? 'active' : '' }}">Latihan</a>
                <a href="{{ route('kuis.index') }}" class="nav-link {{ request()->routeIs('kuis.*') ? 'active' : '' }}">Kuis</a>
            </nav>

            <div class="nav-actions">
                @auth
                    <div style="display: flex; align-items: center; gap: 16px;">
                        <a href="{{ route('profil.edit') }}" style="display: flex; align-items: center; gap: 10px; text-decoration: none; color: inherit; transition: opacity 0.2s;" onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1'">
                            <div style="width: 38px; height: 38px; border-radius: 50%; overflow: hidden; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.1); background: white;">
                                @if(auth()->user()->foto_profil)
                                    <img src="{{ asset('storage/' . auth()->user()->foto_profil) }}" alt="Profil" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: #f1f5f9; color: #94a3b8;">
                                        <svg style="width: 20px; height: 20px;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                                    </div>
                                @endif
                            </div>
                            <span style="font-weight: 600; font-size: 0.95rem;">{{ auth()->user()->nama }}</span>
                        </a>
                        <form method="POST" action="{{ route('keluar') }}" style="margin: 0;">
                            @csrf
                            <button type="submit" class="btn btn-outline" style="padding: 8px 16px; font-size: 0.85rem;">Keluar</button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('masuk') }}" class="btn btn-outline">Masuk</a>
                    <a href="{{ route('daftar') }}" class="btn btn-primary">Daftar Sekarang</a>
                @endauth
            </div>
        </div>
    </div>

    <main>
        <div class="container">
            @if (!request()->routeIs('halaman-utama') && !request()->routeIs('dashboard') && !request()->routeIs('admin.*') && !request()->routeIs('kuis.*') && !request()->routeIs('latihan.*') && !request()->routeIs('masuk') && !request()->routeIs('daftar') && !request()->routeIs('login') && !request()->routeIs('register') && View::hasSection('judul'))
                <header class="page-header">
                    <h1 class="page-title">@yield('judul')</h1>
                    <p class="page-desc">@yield('deskripsi')</p>
                    @yield('navigasi')
                </header>
            @endif

            @yield('konten')
        </div>
    </main>

    <footer style="position: relative; z-index: 10; background: white;">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <div class="brand">
                        <img src="{{ asset('img/logo2.png') }}" alt="Isyarat Pintar" style="height: 48px; width: auto;">
                        <span class="brand-text" style="font-size: 1.25rem;">Isyarat Pintar</span>
                    </div>
                    <p>Platform pembelajaran Bahasa Isyarat Indonesia yang interaktif, modern, dan inklusif untuk semua.</p>
                </div>
                
                <div class="footer-col">
                    <h4>Belajar</h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('kamus.abjad') }}">Kamus Visual</a></li>
                        <li><a href="{{ route('latihan.deteksi') }}">Latihan Kamera</a></li>
                        <li><a href="{{ route('kuis.index') }}">Kuis BISINDO</a></li>
                        <li><a href="#">Modul Pembelajaran</a></li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h4>Tentang</h4>
                    <ul class="footer-links">
                        <li><a href="#">Tentang Kami</a></li>
                        <li><a href="#">Kontak</a></li>
                        <li><a href="#">Kebijakan Privasi</a></li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h4>Komunitas</h4>
                    <ul class="footer-links">
                        <li><a href="#">Blog</a></li>
                        <li><a href="#">Forum Diskusi</a></li>
                        <li><a href="#">Event</a></li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; {{ now()->year }} SKRIPSI UNIVERSITAS DIPA MAKASSAR</p>
                <div style="display: flex; gap: 24px;">
                    <a href="#" style="color: var(--text-muted); text-decoration: none;">Instagram</a>
                    <a href="#" style="color: var(--text-muted); text-decoration: none;">Twitter</a>
                    <a href="#" style="color: var(--text-muted); text-decoration: none;">YouTube</a>
                </div>
            </div>
        </div>
    </footer>

    @stack('skrip')
</body>
</html>

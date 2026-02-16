@extends('komponen.tata_letak')

@push('gaya')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    * { font-family: 'Plus Jakarta Sans', sans-serif; }
    
    /* Fixed Background Image */
    .page-background {
        position: fixed;
        inset: 0;
        z-index: -1;
    }
    .page-background::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(to bottom right, rgba(255,255,255,0.85), rgba(248,253,252,0.8), rgba(240,253,250,0.85));
    }
    .page-background::after {
        content: '';
        position: absolute;
        inset: 0;
        background-image: url('{{ asset('img/pelatihan-bahasa-isyarat.png') }}');
        background-size: cover;
        background-position: center;
        opacity: 0.15;
    }
    /* Harmonious Color Palette - Teal Theme Update */
    :root {
        --primary-gradient: linear-gradient(135deg, #0f766e 0%, #047857 100%); /* Teal 700 to Emerald 700 */
        --card-bg: #ffffff;
        --text-main: #0f172a;
        --text-muted: #64748b;
        --accent-glow: rgba(20, 184, 166, 0.25);
    }

    /* Header Content */
    .kamus-header-content {
        position: relative;
        z-index: 1;
        text-align: center;
        color: #ffffff !important;
    }
    
    .kamus-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: #ffffff !important;
        padding: 8px 16px;
        border-radius: 999px;
        font-size: 0.85rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    
    .kamus-badge .pulse {
        width: 8px;
        height: 8px;
        background: #6ee7b7; /* Emerald 300 */
        border-radius: 50%;
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.5; transform: scale(1.2); }
    }
    
    .kamus-title {
        font-size: 2rem;
        font-weight: 800;
        color: #ffffff !important;
        margin-bottom: 0.75rem;
        letter-spacing: -0.02em;
        text-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    @media (min-width: 640px) {
        .kamus-title { font-size: 2.75rem; }
    }
    
    .kamus-title .gradient-text {
        background: linear-gradient(90deg, #f0fdf4, #ccfbf1); /* Light Mint to Cyan 50 */
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        text-shadow: none;
    }
    
    .kamus-desc {
        color: rgba(255, 255, 255, 0.95) !important;
        font-size: 1.1rem;
        max-width: 520px;
        margin: 0 auto 2.5rem;
        line-height: 1.7;
        font-weight: 500;
    }
    
    /* Stats Cards */
    .stats-grid {
        display: flex;
        justify-content: center;
        gap: 1.5rem;
        flex-wrap: wrap;
        width: 100%;
        margin-top: 1rem;
    }
    
    .k-stat-card {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.25);
        border-radius: 20px;
        padding: 1.25rem 2rem;
        min-width: 140px;
        text-align: center;
        flex: 0 1 auto;
        box-shadow: 0 8px 16px -4px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
    }
    
    .k-stat-card:hover {
        transform: translateY(-4px);
        background: rgba(255, 255, 255, 0.15);
    }
    
    .stat-value {
        font-size: 1.75rem;
        font-weight: 800;
        color: #ffffff !important;
    }
    
    .stat-value.success { color: #34d399 !important; }
    .stat-value.warning { color: #fbbf24 !important; }
    
    .stat-label {
        font-size: 0.75rem;
        color: rgba(255, 255, 255, 0.8) !important;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 700;
        margin-top: 4px;
    }

    /* Decorative Image */
    .kamus-img-decoration {
        position: absolute;
        bottom: -25px;
        right: 0;
        height: 380px;
        width: auto;
        object-fit: contain;
        z-index: 1;
        filter: drop-shadow(0 20px 30px rgba(0,0,0,0.2));
        transform: rotate(-3deg);
        display: none; /* Hidden by default on mobile */
        pointer-events: none;
    }

    /* Show images on larger screens */
    @media (min-width: 1024px) {
        .kamus-img-decoration {
            display: block;
        }
    }

    .kamus-wrapper {
        background: transparent;
        padding: 0;
        margin-bottom: 2.5rem;
        box-shadow: none;
    }
    
    .kamus-inner {
        background: radial-gradient(circle at top right, #0d9488, #0f766e); /* Teal 600 to Teal 700 */
        border-radius: 24px;
        padding: 2.5rem 1.5rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 40px -10px rgba(13, 148, 136, 0.25);
    }

    @media (min-width: 640px) {
        .kamus-inner {
            border-radius: 32px;
            padding: 4rem 2rem;
        }
    }
    
    /* Modern Tabs */
    .category-tabs {
        display: flex;
        justify-content: center;
        gap: 1rem;
        margin-bottom: 2.5rem;
    }
    
    .category-tab {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        background: white;
        border-radius: 14px;
        color: var(--text-muted);
        text-decoration: none;
        font-weight: 700;
        font-size: 0.95rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
    }
    
    .category-tab.active {
        background: var(--primary-gradient);
        color: white;
        border-color: transparent;
        box-shadow: 0 10px 15px -3px rgba(13, 148, 136, 0.3);
        transform: translateY(-2px);
    }
    
    .category-tab:hover:not(.active) {
        background: #f8fafc;
        transform: translateY(-2px);
    }

    /* Refined Filter Box */
    .filter-box {
        background: white;
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 2.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        border: 1px solid #f1f5f9;
        display: flex;
        gap: 1.25rem;
        flex-wrap: wrap;
        align-items: flex-end;
    }
    
    .filter-btn {
        padding: 14px 32px;
        background: var(--primary-gradient);
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s;
        box-shadow: 0 4px 6px -1px rgba(13, 148, 136, 0.3);
    }
    
    .filter-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(13, 148, 136, 0.4);
    }

    /* Refined Card Grid */
    .kata-grid {
        display: grid;
        grid-template-columns: repeat(1, 1fr);
        gap: 1.5rem;
        padding: 0.5rem;
    }
    
    @media (min-width: 640px) {
        .kata-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 2rem;
        }
    }
    
    @media (min-width: 1024px) {
        .kata-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }
    
    @media (min-width: 1280px) {
        .kata-grid {
            grid-template-columns: repeat(4, 1fr);
        }
    }
    
    .kata-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        cursor: pointer;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid #f1f5f9;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
        position: relative;
    }
    
    .kata-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05);
        border-color: #10b981; /* Emerald 500 */
    }
    
    .kata-category {
        background: rgba(255, 255, 255, 0.95);
        color: #0f766e;
        font-weight: 700;
        font-size: 0.75rem;
        padding: 6px 14px;
        border-radius: 999px;
        top: 12px;
        right: 12px;
        position: absolute;
    }
    
    .kata-title {
        color: var(--text-main);
        font-weight: 800;
        transition: color 0.3s;
    }
    
    .kata-card:hover .kata-title { color: #0d9488; }
    
    .kata-play-icon {
        background: linear-gradient(135deg, #0d9488 0%, #10b981 100%);
        box-shadow: 0 10px 15px -3px rgba(13, 148, 136, 0.4);
    }
    
    .kata-card:hover .kata-play-icon { transform: scale(1.1); }
    
    .kata-info {
        padding: 1.75rem;
        position: relative;
        z-index: 2;
    }
    
    .kata-title {
        font-size: 1.35rem;
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 8px;
        transition: color 0.3s;
    }
    
    .kata-card:hover .kata-title { color: #0d9488; }
    
    .kata-desc {
        font-size: 0.95rem;
        color: #64748b;
        line-height: 1.6;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* No Results */
    .no-results {
        text-align: center;
        padding: 4rem 1rem;
    }
    
    .no-results-icon {
        font-size: 4rem;
        opacity: 0.3;
        margin-bottom: 1rem;
    }

    /* Modal */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15, 15, 35, 0.85);
        backdrop-filter: blur(8px);
        z-index: 1000;
        display: none;
        place-items: center;
        padding: 1rem;
    }
    
    .modal-overlay.active { display: grid; }
    
    .modal-container {
        /* Matches brand logo (Teal/Emerald) - Softer border */
        background: linear-gradient(135deg, #0d9488 0%, #10b981 100%);
        border-radius: 28px;
        padding: 2px; /* Thinner border */
        width: 100%;
        max-width: 900px;
        max-height: 90vh;
        animation: modalIn 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 25px 50px -12px rgba(13, 148, 136, 0.5);
    }
    
    @keyframes modalIn {
        from { opacity: 0; transform: scale(0.95) translateY(20px); }
        to { opacity: 1; transform: scale(1) translateY(0); }
    }
    
    .modal-content {
        background: #0f172a; /* Slate 900 */
        border-radius: 26px;
        overflow: hidden;
        display: grid;
        grid-template-columns: 1fr;
    }
    
    @media (min-width: 768px) {
        .modal-content { grid-template-columns: 1.1fr 1fr; }
    }
    
    .modal-video-section {
        background: linear-gradient(180deg, #111827 0%, #0f172a 100%);
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 280px;
        padding: 1.5rem;
    }
    
    @media (min-width: 768px) {
        .modal-video-section { min-height: 400px; }
    }
    
    .modal-video-section::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at 30% 70%, rgba(20, 184, 166, 0.15) 0%, transparent 50%),
                    radial-gradient(circle at 70% 30%, rgba(14, 165, 233, 0.1) 0%, transparent 50%);
        pointer-events: none;
    }
    
    .modal-video-wrapper {
        position: relative;
        width: 100%;
        max-width: 400px;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 20px 50px -15px rgba(0, 0, 0, 0.5);
    }
    
    .modal-video-wrapper video,
    .modal-video-wrapper img {
        width: 100%;
        height: auto;
        display: block;
    }
    
    .modal-close {
        position: absolute;
        top: 16px;
        right: 16px;
        width: 40px;
        height: 40px;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        color: white;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s;
        z-index: 10;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .modal-close:hover {
        background: rgba(255, 255, 255, 0.25);
        transform: rotate(90deg) scale(1.1);
    }
    
    .modal-info-section {
        padding: 2rem;
        overflow-y: auto;
        max-height: 50vh;
        background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
    }
    
    @media (min-width: 768px) {
        .modal-info-section { max-height: none; }
    }
    
    .modal-category-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: linear-gradient(135deg, #0d9488 0%, #10b981 100%);
        color: white;
        padding: 8px 16px;
        border-radius: 999px;
        font-size: 0.8rem;
        font-weight: 700;
        margin-bottom: 1rem;
        text-transform: uppercase;
        box-shadow: 0 4px 12px rgba(13, 148, 136, 0.3);
    }
    
    .modal-title {
        font-size: 2rem;
        font-weight: 800;
        color: white;
        margin-bottom: 0.5rem;
    }
    
    .modal-subtitle {
        color: rgba(255, 255, 255, 0.6);
        font-size: 0.95rem;
        margin-bottom: 1.5rem;
    }
    
    .instruction-card {
        background: rgba(15, 118, 110, 0.2); /* Teal 700 with opacity */
        border: 1px solid rgba(20, 184, 166, 0.2);
        border-radius: 16px;
        padding: 1.25rem;
        margin-bottom: 1rem;
    }
    
    .instruction-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 10px;
    }
    
    .instruction-icon {
        width: 36px;
        height: 36px;
        background: linear-gradient(135deg, #0d9488 0%, #10b981 100%);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
    }
    
    .instruction-title {
        font-weight: 700;
        color: white;
        font-size: 0.9rem;
    }
    
    .instruction-text {
        color: rgba(255, 255, 255, 0.8);
        font-size: 0.9rem;
        line-height: 1.6;
    }
    
    .tips-card {
        background: rgba(16, 185, 129, 0.1);
        border: 1px solid rgba(16, 185, 129, 0.2);
        border-radius: 16px;
        padding: 1.25rem;
        margin-bottom: 1.5rem;
    }
    
    .tips-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 12px;
    }
    
    .tips-icon {
        width: 36px;
        height: 36px;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
    }
    
    .tips-title {
        font-weight: 700;
        color: #34d399;
        font-size: 0.9rem;
    }
    
    .tips-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .tips-list li {
        color: rgba(255, 255, 255, 0.8);
        font-size: 0.85rem;
        padding: 6px 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .tips-list li::before {
        content: '✓';
        color: #34d399;
        font-weight: 700;
    }
    
    .modal-actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }
    
    .btn-complete {
        background: linear-gradient(135deg, #059669 0%, #047857 100%); /* Emerald 600-700 */
        color: white;
        border: 1px solid rgba(255,255,255,0.1);
        padding: 14px 20px;
        border-radius: 14px;
        font-weight: 700;
        font-size: 0.95rem;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
    }
    
    .btn-complete:hover {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.3);
    }
    
    .btn-close {
        background: rgba(255, 255, 255, 0.1);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.2);
        padding: 14px 20px;
        border-radius: 14px;
        font-weight: 700;
        font-size: 0.95rem;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .btn-close:hover {
        background: rgba(255, 255, 255, 0.2);
    }
    
    @media (max-width: 640px) {
        .kamus-inner { padding: 1.5rem; }
        .kamus-title { font-size: 2rem; }
        .filter-box { flex-direction: column; }
        .kata-grid { grid-template-columns: 1fr; }
    }

    /* ===== FILTER FORM STYLING ===== */
    .filter-group {
        flex: 1;
        min-width: 180px;
    }
    
    .filter-label {
        display: block;
        font-weight: 600;
        font-size: 0.85rem;
        color: #475569;
        margin-bottom: 8px;
    }
    
    .filter-input,
    .filter-select {
        width: 100%;
        padding: 14px 16px;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        font-size: 0.95rem;
        font-weight: 500;
        color: #1e293b;
        background: white;
        transition: all 0.3s ease;
        outline: none;
    }
    
    .filter-input:focus,
    .filter-select:focus {
        border-color: #8b5cf6;
        box-shadow: 0 0 0 4px rgba(139, 92, 246, 0.1);
    }
    
    .filter-input::placeholder {
        color: #94a3b8;
    }
    
    .filter-select {
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        background-size: 20px;
        padding-right: 44px;
    }

    /* ===== KATA MEDIA SECTION ===== */
    .kata-media {
        position: relative;
        aspect-ratio: 16/10;
        background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
        overflow: hidden;
    }
    
    .kata-media img,
    .kata-media video {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .kata-card:hover .kata-media img,
    .kata-card:hover .kata-media video {
        transform: scale(1.08);
    }
    
    .kata-media-placeholder {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
        color: #94a3b8;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    }
    
    .kata-play {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(0, 0, 0, 0.1);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .kata-card:hover .kata-play {
        opacity: 1;
    }
    
    .kata-play-icon {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        transition: transform 0.3s ease;
    }

    /* ===== CARD ANIMATIONS ===== */
    .kata-card {
        animation: cardSlideUp 0.5s ease-out forwards;
        opacity: 0;
    }
    
    @keyframes cardSlideUp {
        from { 
            opacity: 0; 
            transform: translateY(24px); 
        }
        to { 
            opacity: 1; 
            transform: translateY(0); 
        }
    }
    
    /* Staggered animation delays */
    .kata-card:nth-child(1) { animation-delay: 0.05s; }
    .kata-card:nth-child(2) { animation-delay: 0.1s; }
    .kata-card:nth-child(3) { animation-delay: 0.15s; }
    .kata-card:nth-child(4) { animation-delay: 0.2s; }
    .kata-card:nth-child(5) { animation-delay: 0.25s; }
    .kata-card:nth-child(6) { animation-delay: 0.3s; }
    .kata-card:nth-child(n+7) { animation-delay: 0.35s; }

    /* ===== CATEGORY COLORS ===== */
    .kata-category {
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 0.7rem;
    }
    
    .kata-category[data-cat="sapaan"] { background: #ecfdf5; color: #059669; }
    .kata-category[data-cat="angka"] { background: #fef3c7; color: #d97706; }
    .kata-category[data-cat="waktu"] { background: #e0e7ff; color: #4f46e5; }
    .kata-category[data-cat="keluarga"] { background: #fce7f3; color: #db2777; }
    .kata-category[data-cat="aktivitas"] { background: #f0fdf4; color: #16a34a; }

    /* ===== ENHANCED FOCUS & ACCESSIBILITY ===== */
    .kata-card:focus {
        outline: 2px solid #8b5cf6;
        outline-offset: 2px;
    }
    
    .filter-btn:focus {
        outline: 2px solid white;
        outline-offset: 2px;
    }

    /* ===== MOBILE IMPROVEMENTS ===== */
    @media (max-width: 768px) {
        .filter-box {
            padding: 1.25rem;
            gap: 1rem;
        }
        
        .filter-group {
            min-width: 100%;
        }
        
        .filter-btn {
            width: 100%;
            padding: 14px;
        }
        
        .kata-grid {
            gap: 1.25rem;
        }
        
        .kata-info {
            padding: 1.25rem;
        }
        
        .kata-title {
            font-size: 1.15rem;
        }
        
        .modal-actions {
            grid-template-columns: 1fr;
            gap: 10px;
        }
        
        .stats-grid {
            gap: 0.75rem;
        }
        
        .k-stat-card {
            padding: 0.75rem 1rem;
            min-width: 100px;
        }
        
        .category-tabs {
            flex-wrap: wrap;
            gap: 0.75rem;
        }
        
        .category-tab {
            padding: 10px 18px;
            font-size: 0.85rem;
        }
    }
</style>
@endpush

@section('konten')
<!-- Background Image -->
<div class="page-background"></div>

<!-- Modern Header -->
<div class="kamus-wrapper">
    <div class="kamus-inner">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
        
        <!-- Decorative Image -->
        <img src="{{ asset('img/kata.png') }}" alt="Kamus Kata" class="kamus-img-decoration">
        
        <div class="kamus-header-content">
            <div class="kamus-badge">
                <span class="pulse"></span>
                Kamus Visual BISINDO
            </div>
            <h1 class="kamus-title">Belajar <span class="gradient-text">Kata Dasar</span></h1>
            <p class="kamus-desc">
                Pelajari kosakata Bahasa Isyarat Indonesia untuk komunikasi sehari-hari dengan panduan video interaktif.
            </p>
            
            <div class="stats-grid">
                <div class="k-stat-card">
                    <div class="stat-value">{{ $daftarKata->count() }}</div>
                    <div class="stat-label">Total Kata</div>
                </div>
                <div class="k-stat-card">
                    <div class="stat-value warning">{{ $daftarKata->pluck('kategori')->filter()->unique()->count() }}</div>
                    <div class="stat-label">Kategori</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Category Tabs -->
<div class="category-tabs">
    <a href="{{ route('kamus.abjad') }}" class="category-tab">
        🔤 Abjad
    </a>
    <a href="{{ route('kamus.kata-dasar') }}" class="category-tab active">
        📖 Kata Dasar
    </a>
</div>

<!-- Filter -->
<form method="GET" action="{{ route('kamus.kata-dasar') }}" class="filter-box">
    <div class="filter-group" style="flex: 2;">
        <label class="filter-label">🔍 Cari Kata</label>
        <input type="text" name="q" class="filter-input" placeholder="Contoh: terima kasih, maaf..." value="{{ $pencarian }}">
    </div>
    <div class="filter-group">
        <label class="filter-label">📂 Kategori</label>
        <select name="kategori" class="filter-select">
            <option value="">Semua kategori</option>
            @foreach($daftarKata->pluck('kategori')->filter()->unique()->sort()->values() as $kategori)
                <option value="{{ $kategori }}" {{ request('kategori') === $kategori ? 'selected' : '' }}>
                    {{ ucfirst($kategori) }}
                </option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="filter-btn">Cari</button>
</form>

<!-- Card Grid -->
<div class="kata-grid">
    @forelse($daftarKata as $item)
        <article class="kata-card" onclick="openModal('{{ $item->kata }}', '{{ $item->kategori ?? 'Umum' }}', '{{ $item->arti ?? '' }}', '{{ $item->berkas_video ? asset($item->berkas_video) : '' }}')">
            <div class="kata-media">
                @if($item->berkas_video)
                    @php
                        $extension = pathinfo($item->berkas_video, PATHINFO_EXTENSION);
                        $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']);
                    @endphp
                    
                    @if($isImage)
                        <img src="{{ asset($item->berkas_video) }}" alt="{{ $item->kata }}">
                    @else
                        <video muted>
                            <source src="{{ asset($item->berkas_video) }}" type="video/mp4">
                        </video>
                        <div class="kata-play">
                            <div class="kata-play-icon">
                                <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                            </div>
                        </div>
                    @endif
                @else
                    <div class="kata-media-placeholder">
                        <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                            <circle cx="8.5" cy="8.5" r="1.5"/>
                            <path d="M21 15l-5-5L5 21"/>
                        </svg>
                        <div style="font-size:0.8rem;margin-top:8px;">Belum ada media</div>
                    </div>
                @endif
                
                <span class="kata-category" data-cat="{{ strtolower($item->kategori ?? 'umum') }}">{{ $item->kategori ?? 'Umum' }}</span>
            </div>
            
            <div class="kata-info">
                <h3 class="kata-title">{{ $item->kata }}</h3>
                <p class="kata-desc">{{ $item->arti ?? 'Deskripsi makna sedang disusun.' }}</p>
            </div>
        </article>
    @empty
        <div class="no-results" style="grid-column: 1/-1;">
            <div class="no-results-icon">🔍</div>
            <div style="font-weight: 700; color: #475569; font-size: 1.1rem;">Tidak ditemukan</div>
            <div style="color: #94a3b8;">Coba kata kunci lain atau ubah filter kategori.</div>
        </div>
    @endforelse
</div>

<!-- Modal -->
<div class="modal-overlay" id="contentModal">
    <div class="modal-container">
        <div class="modal-content">
            <div class="modal-video-section">
                <button class="modal-close" onclick="closeModal()">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5">
                        <line x1="15" y1="5" x2="5" y2="15"/><line x1="5" y1="5" x2="15" y2="15"/>
                    </svg>
                </button>
                <div class="modal-video-wrapper" id="modalVideoContainer"></div>
            </div>
            
            <div class="modal-info-section">
                <span class="modal-category-badge" id="modalCategory">Kategori</span>
                <h2 class="modal-title" id="modalTitle">Kata</h2>
                <p class="modal-subtitle" id="modalSubtitle">Pelajari isyarat kata ini</p>
                
                <div class="instruction-card">
                    <div class="instruction-header">
                        <div class="instruction-icon">📖</div>
                        <span class="instruction-title">Arti / Makna</span>
                    </div>
                    <p class="instruction-text" id="modalDescription">Deskripsi makna akan muncul di sini.</p>
                </div>
                
                <div class="tips-card">
                    <div class="tips-header">
                        <div class="tips-icon">💡</div>
                        <span class="tips-title">Tips Penggunaan</span>
                    </div>
                    <ul class="tips-list">
                        <li>Gunakan dalam percakapan sehari-hari</li>
                        <li>Perhatikan ekspresi wajah saat memperagakan</li>
                        <li>Latih dengan teman atau di depan cermin</li>
                    </ul>
                </div>
                
                <div class="modal-actions">
                    <button class="btn-complete" onclick="markAsDone()">✅ Selesai</button>
                    <button class="btn-close" onclick="closeModal()">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</div>

<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@push('skrip')
<script>
    let currentWord = '';

    function openModal(kata, kategori, arti, videoUrl) {
        currentWord = kata;
        
        document.getElementById('modalTitle').innerText = kata;
        document.getElementById('modalCategory').innerText = kategori;
        document.getElementById('modalSubtitle').innerText = 'Pelajari isyarat untuk "' + kata + '"';
        document.getElementById('modalDescription').innerText = arti || 'Deskripsi makna belum tersedia.';
        
        const videoContainer = document.getElementById('modalVideoContainer');
        
        if (videoUrl) {
            const extension = videoUrl.split('.').pop().toLowerCase();
            if (['mp4', 'mov', 'ogg', 'webm'].includes(extension)) {
                videoContainer.innerHTML = `<video src="${videoUrl}" controls autoplay loop style="border-radius: 16px;"></video>`;
            } else {
                videoContainer.innerHTML = `<img src="${videoUrl}" alt="${kata}" style="border-radius: 16px;">`;
            }
        } else {
            videoContainer.innerHTML = `
                <div style="text-align:center;color:rgba(255,255,255,0.5);padding:3rem;">
                    <div style="font-size:4rem;opacity:0.3;margin-bottom:1rem;">📹</div>
                    <div style="font-weight:600;">Media belum tersedia</div>
                </div>`;
        }

        document.getElementById('contentModal').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    async function markAsDone() {
        if (!currentWord) return;
        try {
            const response = await fetch('{{ route("kamus.mark-done") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ word: currentWord })
            });
            if (response.ok) {
                closeModal();
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }

    function closeModal() {
        document.getElementById('contentModal').classList.remove('active');
        document.body.style.overflow = 'auto';
        // Hentikan video saat modal ditutup agar tidak play di background
        document.getElementById('modalVideoContainer').innerHTML = '';
    }

    document.getElementById('contentModal').addEventListener('click', function(e) {
        if (e.target === this) closeModal();
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeModal();
    });
</script>
@endpush

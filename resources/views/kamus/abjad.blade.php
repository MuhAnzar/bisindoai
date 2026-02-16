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
    
    /* Harmonious Color Palette & Layout - Brand Teal Update */
    :root {
        --primary-gradient: linear-gradient(135deg, #0f766e 0%, #047857 100%); /* Teal 700 to Emerald 700 */
        --card-bg: #ffffff;
        --text-main: #0f172a;
        --text-muted: #64748b;
        --accent-glow: rgba(20, 184, 166, 0.25);
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
        color: rgba(255, 255, 255, 0.7) !important;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
        margin-top: 4px;
    }
    
    /* Decorative Images */
    .kamus-img-left,
    .kamus-img-right {
        position: absolute;
        bottom: -30px;
        height: 280px;
        width: auto;
        object-fit: contain;
        z-index: 1;
        filter: drop-shadow(0 10px 20px rgba(0,0,0,0.15));
        display: none; /* Hidden by default on mobile */
        pointer-events: none;
    }
    
    .kamus-img-left {
        left: -10px;
        bottom: -15px;
        transform: rotate(5deg);
    }
    
    .kamus-img-right {
        right: -10px;
        transform: rotate(-5deg);
    }
    
    /* Show images on larger screens */
    @media (min-width: 1024px) {
        .kamus-img-left,
        .kamus-img-right {
            display: block;
        }
    }
    
    /* Refined Alpha Nav */
    .alpha-btn {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 1rem;
        color: var(--text-muted);
        background: white;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }
    
    .alpha-btn:hover, .alpha-btn.active {
        background: var(--primary-gradient);
        color: white;
        border-color: transparent;
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(124, 58, 237, 0.3);
    }
    
    /* Refined Card Grid */
    .abjad-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.75rem;
        padding: 0.25rem;
    }
    
    @media (min-width: 640px) {
        .abjad-grid {
             grid-template-columns: repeat(3, 1fr);
             gap: 1.5rem;
        }
    }

    @media (min-width: 1024px) {
        .abjad-grid {
            grid-template-columns: repeat(4, 1fr);
        }
    }
    
    .abjad-card {
        background: white;
        border-radius: 20px;
        padding: 2rem 1rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid #f1f5f9;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -1px rgba(0, 0, 0, 0.02);
        position: relative;
        overflow: hidden;
    }
    
    .abjad-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.01);
        border-color: #8b5cf6;
    }
    
    /* Subtle Glow on Hover */
    .abjad-card::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at center, var(--accent-glow) 0%, transparent 70%);
        opacity: 0;
        transition: opacity 0.4s;
    }
    
    .abjad-card:hover::before { opacity: 1; }
    
    .abjad-letter {
        font-size: 4rem;
        font-weight: 800;
        color: var(--text-main);
        line-height: 1;
        margin-bottom: 1rem;
        transition: transform 0.4s;
    }
    
    .abjad-card:hover .abjad-letter {
        transform: scale(1.1);
        color: #4f46e5; /* Indigo 600 */
    }
    
    .abjad-status {
        font-size: 0.75rem;
        font-weight: 600;
        padding: 6px 12px;
        border-radius: 999px;
        background: #f8fafc;
        color: var(--text-muted);
        transition: all 0.3s;
    }
    
    .abjad-card:hover .abjad-status {
        background: #f1f5f9;
        color: #475569;
    }
    
    .status-done {
        background: #ecfdf5;
        color: #059669;
    }

    /* Media Preview in Card */
    .abjad-media-container {
        width: 100%;
        height: 120px;
        background: #f8fafc;
        border-radius: 12px;
        margin-bottom: 1rem;
        overflow: hidden;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .abjad-media {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .media-placeholder {
        font-size: 2.5rem;
        opacity: 0.1;
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
        box-shadow: 0 10px 15px -3px rgba(124, 58, 237, 0.3);
        transform: translateY(-2px);
    }
    
    .category-tab:hover:not(.active) {
        background: #f8fafc;
        transform: translateY(-2px);
    }

    /* Modern Search Bar */
    .search-container {
        max-width: 600px;
        margin: 0 auto 3rem;
        position: relative;
    }
    
    .search-box {
        position: relative;
        width: 100%;
        border-radius: 24px;
        background: white;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
        border: 2px solid transparent;
        transition: all 0.3s;
    }
    
    .search-box:focus-within {
        border-color: #8b5cf6;
        box-shadow: 0 0 0 4px rgba(139, 92, 246, 0.1);
        transform: translateY(-2px);
    }
    
    .search-icon {
        position: absolute;
        left: 20px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        pointer-events: none;
        transition: color 0.3s;
    }
    
    .search-box:focus-within .search-icon { color: #8b5cf6; }
    
    .search-input {
        width: 100%;
        padding: 18px 20px 18px 58px;
        border: none;
        background: transparent;
        font-size: 1.1rem;
        font-weight: 500;
        color: var(--text-main);
        outline: none;
        border-radius: 24px;
    }
    
    .search-input::placeholder { color: #cbd5e1; }

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

    /* ===== MODERN MODAL ===== */
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
        padding: 2px; /* Thinner border */
        border-radius: 24px;
        width: calc(100% - 1rem);
        max-width: 920px;
        max-height: calc(100vh - 2rem);
        max-height: calc(100dvh - 2rem);
        animation: modalIn 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 25px 50px -12px rgba(13, 148, 136, 0.5);
        overflow: hidden;
    }
    
    @keyframes modalIn {
        from { opacity: 0; transform: scale(0.95) translateY(20px); }
        to { opacity: 1; transform: scale(1) translateY(0); }
    }
    
    .modal-content {
        background: #0f172a; /* Slate 900 */
        border-radius: 22px;
        overflow: hidden;
        display: grid;
        grid-template-columns: 1fr;
        max-height: calc(100vh - 2.5rem);
        max-height: calc(100dvh - 2.5rem);
        overflow-y: auto;
    }
    
    @media (min-width: 768px) {
        .modal-content { 
            grid-template-columns: 1.1fr 1fr;
            overflow-y: hidden;
        }
    }
    
    /* Modal Video Section */
    .modal-video-section {
        background: linear-gradient(180deg, #111827 0%, #0f172a 100%);
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 200px;
        padding: 1.5rem;
        flex-shrink: 0;
    }
    
    @media (min-width: 768px) {
        .modal-video-section { 
            min-height: 400px; 
            padding: 2rem;
        }
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
    
    /* Modal Info Section */
    .modal-info-section {
        padding: 1.5rem;
        overflow-y: auto;
        background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%); /* Slate 800 to 900 */
        flex: 1;
    }
    
    @media (min-width: 768px) {
        .modal-info-section { 
            padding: 2rem;
            max-height: 100%;
            overflow-y: auto;
        }
    }
    
    .modal-letter-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: linear-gradient(135deg, #0d9488 0%, #10b981 100%);
        color: white;
        padding: 10px 20px;
        border-radius: 999px;
        font-size: 0.8rem;
        font-weight: 700;
        margin-bottom: 1.25rem;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
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
    
    /* Instruction Card */
    .instruction-card {
        background: rgba(15, 118, 110, 0.2); /* Teal 700 with opacity */
        border: 1px solid rgba(20, 184, 166, 0.2);
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        transition: all 0.3s ease;
    }
    
    .instruction-card:hover {
        background: rgba(13, 148, 136, 0.15);
        border-color: rgba(13, 148, 136, 0.4);
        transform: translateY(-2px);
    }
    
    .instruction-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 10px;
    }
    
    .instruction-icon {
        width: 42px;
        height: 42px;
        background: linear-gradient(135deg, #0d9488 0%, #10b981 100%);
        border-radius: 12px;
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
        color: rgba(255, 255, 255, 0.9);
        font-size: 1rem;
        line-height: 1.6;
    }
    
    /* Tips Card */
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
    
    /* Progress Indicator */
    .progress-section {
        background: rgba(255, 255, 255, 0.05);
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .progress-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
    }
    
    .progress-label {
        font-size: 0.8rem;
        color: rgba(255, 255, 255, 0.6);
        font-weight: 600;
    }
    
    .progress-value {
        font-size: 0.8rem;
        color: #a78bfa;
        font-weight: 700;
    }
    
    .progress-bar {
        height: 6px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 999px;
        overflow: hidden;
    }
    
    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #667eea, #a78bfa);
        border-radius: 999px;
        transition: width 0.5s ease;
    }
    
    /* Modal Actions */
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
    
    .btn-practice {
        background: linear-gradient(135deg, #0f766e 0%, #115e59 100%); /* Teal 700-800 */
        color: white;
        border: 1px solid rgba(255,255,255,0.1);
        padding: 14px 20px;
        border-radius: 14px;
        font-weight: 700;
        font-size: 0.95rem;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
    }
    
    .btn-practice:hover {
        background: linear-gradient(135deg, #14b8a6 0%, #0d9488 100%);
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(20, 184, 166, 0.3);
    }
    
    /* Responsive */
    @media (max-width: 640px) {
        .kamus-inner { padding: 1.5rem; }
        .kamus-title { font-size: 2rem; }
        .abjad-grid { grid-template-columns: repeat(auto-fill, minmax(100px, 1fr)); gap: 1rem; }
        .abjad-letter { font-size: 2.25rem; }
    }

    /* ===== ALPHA NAVIGATION ===== */
    .alpha-nav-wrapper {
        max-width: 100%;
        margin: 0 auto 2.5rem;
        padding: 0 0.5rem;
        position: relative;
    }
    
    .alpha-nav-wrapper::before,
    .alpha-nav-wrapper::after {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        width: 40px;
        z-index: 2;
        pointer-events: none;
    }
    
    .alpha-nav-wrapper::before {
        left: 0;
        background: linear-gradient(to right, var(--bg-body, #f8fafc) 20%, transparent);
    }
    
    .alpha-nav-wrapper::after {
        right: 0;
        background: linear-gradient(to left, var(--bg-body, #f8fafc) 20%, transparent);
    }
    
    .alpha-nav {
        display: flex;
        gap: 0.5rem;
        overflow-x: auto;
        scroll-behavior: smooth;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
        padding: 1rem 2rem;
        justify-content: center;
        flex-wrap: nowrap;
    }
    
    .alpha-nav::-webkit-scrollbar {
        display: none;
    }
    
    .alpha-btn.learned {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        border-color: transparent;
    }
    
    /* ===== CARD ANIMATIONS ===== */
    .abjad-card {
        animation: cardFadeIn 0.5s ease-out forwards;
        opacity: 0;
    }
    
    @keyframes cardFadeIn {
        from { 
            opacity: 0; 
            transform: translateY(20px) scale(0.95); 
        }
        to { 
            opacity: 1; 
            transform: translateY(0) scale(1); 
        }
    }
    
    /* Staggered animation delays for cards */
    .abjad-card:nth-child(1) { animation-delay: 0.02s; }
    .abjad-card:nth-child(2) { animation-delay: 0.04s; }
    .abjad-card:nth-child(3) { animation-delay: 0.06s; }
    .abjad-card:nth-child(4) { animation-delay: 0.08s; }
    .abjad-card:nth-child(5) { animation-delay: 0.10s; }
    .abjad-card:nth-child(6) { animation-delay: 0.12s; }
    .abjad-card:nth-child(7) { animation-delay: 0.14s; }
    .abjad-card:nth-child(8) { animation-delay: 0.16s; }
    .abjad-card:nth-child(9) { animation-delay: 0.18s; }
    .abjad-card:nth-child(10) { animation-delay: 0.20s; }
    .abjad-card:nth-child(n+11) { animation-delay: 0.22s; }
    
    /* Status Learning */
    .status-learning {
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        color: #92400e;
        font-weight: 700;
    }
    
    /* Enhanced Focus States */
    .alpha-btn:focus,
    .abjad-card:focus {
        outline: 2px solid #8b5cf6;
        outline-offset: 2px;
    }
    
    /* Shimmer Loading Effect */
    .skeleton {
        background: linear-gradient(90deg, #f1f5f9 25%, #e2e8f0 50%, #f1f5f9 75%);
        background-size: 200% 100%;
        animation: shimmer 1.5s infinite;
    }
    
    @keyframes shimmer {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }
    
    /* Hidden utility class */
    .hidden { display: none !important; }
    
    /* Mobile Improvements */
    @media (max-width: 768px) {
        .alpha-nav {
            justify-content: flex-start;
            padding: 0.75rem 1rem;
        }
        
        .alpha-btn {
            width: 42px;
            height: 42px;
            font-size: 0.9rem;
            flex-shrink: 0;
        }
        
        .stats-grid {
            gap: 0.75rem;
        }
        
        .k-stat-card {
            padding: 0.75rem 1rem;
            min-width: 90px;
        }
        
        .stat-value {
            font-size: 1.5rem;
        }
        
        .category-tabs {
            flex-wrap: wrap;
            gap: 0.75rem;
        }
        
        .category-tab {
            padding: 10px 18px;
            font-size: 0.85rem;
        }
        
        .modal-actions {
            grid-template-columns: 1fr;
            gap: 10px;
        }
    }

    /* ===== ENHANCED VIDEO PLACEHOLDER ===== */
    .video-placeholder {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 1.5rem 1rem;
        text-align: center;
        min-height: 180px;
    }
    
    @media (min-width: 768px) {
        .video-placeholder {
            padding: 3rem 2rem;
            min-height: 280px;
        }
    }
    
    .placeholder-icon-wrapper {
        position: relative;
        margin-bottom: 1rem;
    }
    
    @media (min-width: 768px) {
        .placeholder-icon-wrapper {
            margin-bottom: 1.5rem;
        }
    }
    
    .placeholder-ring {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: linear-gradient(135deg, rgba(13, 148, 136, 0.2), rgba(16, 185, 129, 0.1));
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }
    
    @media (min-width: 768px) {
        .placeholder-ring {
            width: 140px;
            height: 140px;
        }
    }
    
    .placeholder-ring::before {
        content: '';
        position: absolute;
        inset: -4px;
        border-radius: 50%;
        background: conic-gradient(from 0deg, #0d9488, #10b981, #3b82f6, #0d9488);
        z-index: -1;
        animation: rotateRing 4s linear infinite;
        opacity: 0.6;
    }
    
    .placeholder-ring::after {
        content: '';
        position: absolute;
        inset: 2px;
        border-radius: 50%;
        background: linear-gradient(145deg, #0f172a, #1e1b4b);
    }
    
    @keyframes rotateRing {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    
    .placeholder-hand {
        position: relative;
        z-index: 1;
        font-size: 2.5rem;
        animation: floatHand 3s ease-in-out infinite;
    }
    
    @media (min-width: 768px) {
        .placeholder-hand {
            font-size: 4rem;
        }
    }
    
    @keyframes floatHand {
        0%, 100% { transform: translateY(0) rotate(-5deg); }
        50% { transform: translateY(-8px) rotate(5deg); }
    }
    
    .placeholder-title {
        font-weight: 700;
        font-size: 0.95rem;
        color: rgba(255, 255, 255, 0.9);
        margin-bottom: 0.35rem;
    }
    
    @media (min-width: 768px) {
        .placeholder-title {
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }
    }
    
    .placeholder-subtitle {
        font-size: 0.9rem;
        color: rgba(255, 255, 255, 0.5);
        margin-bottom: 1.5rem;
    }
    
    .placeholder-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        background: rgba(13, 148, 136, 0.15);
        border: 1px solid rgba(13, 148, 136, 0.3);
        border-radius: 999px;
        color: #34d399;
        font-size: 0.8rem;
        font-weight: 600;
    }
    
    .placeholder-badge .dot {
        width: 6px;
        height: 6px;
        background: #34d399;
        border-radius: 50%;
        animation: pulseDot 2s ease-in-out infinite;
    }
    
    @keyframes pulseDot {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.5; transform: scale(1.3); }
    }
</style>
@endpush

@section('konten')
@php
    $totalLearned = 0;
    $totalLearning = 0;
    foreach($daftarAbjad as $abjad) {
        $huruf = strtoupper($abjad->huruf);
        $progress = isset($progressMap) && isset($progressMap[$huruf]) ? $progressMap[$huruf] : 0;
        if($progress >= 80) $totalLearned++;
        elseif($progress >= 30) $totalLearning++;
    }
    $totalNotStarted = $daftarAbjad->count() - $totalLearned - $totalLearning;
@endphp

<!-- Background Image -->
<div class="page-background"></div>

<!-- Modern Header -->
<div class="kamus-wrapper">
    <div class="kamus-inner">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
        
        <!-- Left Image -->
        <img src="{{ asset('img/kamusabjad.png') }}" alt="Kamus BISINDO" class="kamus-img-left">
        
        <!-- Right Image -->
        <img src="{{ asset('img/abjad2.png') }}" alt="Abjad BISINDO" class="kamus-img-right">
        
        <div class="kamus-header-content">
            <div class="kamus-badge">
                <span class="pulse"></span>
                Kamus Visual BISINDO
            </div>
            <h1 class="kamus-title">Kuasai Abjad <span class="gradient-text">A-Z</span></h1>
            <p class="kamus-desc">
                Pelajari 26 huruf dasar Bahasa Isyarat Indonesia dengan panduan video interaktif dan AI yang membantu koreksi gerakanmu.
            </p>
            
            <div class="stats-grid">
                <div class="k-stat-card">
                    <div class="stat-value">{{ $daftarAbjad->count() }}</div>
                    <div class="stat-label">Total Huruf</div>
                </div>
                <div class="k-stat-card">
                    <div class="stat-value success">{{ $totalLearned }}</div>
                    <div class="stat-label">Mahir</div>
                </div>
                <div class="k-stat-card">
                    <div class="stat-value warning">{{ $totalLearning }}</div>
                    <div class="stat-label">Belajar</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Category Tabs -->
<div class="category-tabs">
    <a href="{{ route('kamus.abjad') }}" class="category-tab active">
        🔤 Abjad
    </a>
    <a href="{{ route('kamus.kata-dasar') }}" class="category-tab">
        📖 Kata Dasar
    </a>
</div>

<!-- Search -->
<div class="search-container">
    <div class="search-box">
        <svg class="search-icon" width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        <input type="text" id="searchInput" oninput="filterCards()" class="search-input" placeholder="Ketik huruf yang ingin dipelajari...">
    </div>
</div>

<!-- Alphabet Quick Navigation -->
<div class="alpha-nav-wrapper">
    <div class="alpha-nav" id="alphaNav">
        @foreach($daftarAbjad as $abjad)
            @php
                $huruf = strtoupper($abjad->huruf);
                $progress = isset($progressMap) && isset($progressMap[$huruf]) ? $progressMap[$huruf] : 0;
                $isLearned = $progress >= 80;
            @endphp
            <button class="alpha-btn {{ $isLearned ? 'learned' : '' }}" onclick="scrollToLetter('{{ $huruf }}')">
                {{ $huruf }}
            </button>
        @endforeach
    </div>
</div>

<!-- Alphabet Grid -->
<div class="abjad-grid" id="abjadGrid">
    @foreach($daftarAbjad as $abjad)
        @php
            $huruf = strtoupper($abjad->huruf);
            $progress = isset($progressMap) && isset($progressMap[$huruf]) ? $progressMap[$huruf] : 0;
            
            if($progress >= 80) {
                $statusClass = 'status-done';
                $statusText = '✓ Mahir';
            } elseif($progress >= 30) {
                $statusClass = 'status-learning';
                $statusText = round($progress) . '%';
            } else {
                $statusClass = 'status-new';
                $statusText = 'Mulai';
            }
        @endphp
        <div class="abjad-card {{ $progress < 30 ? 'status-new' : '' }}" 
             id="letter-{{ $huruf }}"
             data-letter="{{ strtolower($abjad->huruf) }}" 
             data-progress="{{ $progress }}"
             onclick="openModal('{{ $huruf }}', '{{ $abjad->deskripsi }}', '{{ $abjad->berkas_video ? asset($abjad->berkas_video) : '' }}', {{ $progress }})">
            
            <div class="abjad-media-container">
                @if($abjad->berkas_video)
                    @php
                        $ext = pathinfo($abjad->berkas_video, PATHINFO_EXTENSION);
                        $isVideo = in_array(strtolower($ext), ['mp4', 'mov', 'webm', 'ogg']);
                    @endphp
                    
                    @if($isVideo)
                        <video src="{{ asset($abjad->berkas_video) }}" class="abjad-media" muted loop onmouseover="this.play()" onmouseout="this.pause(); this.currentTime=0;"></video>
                        <div style="position: absolute; bottom: 8px; right: 8px; background: rgba(0,0,0,0.5); padding: 4px; border-radius: 50%;">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="white">
                                <path d="M8 5v14l11-7z"/>
                            </svg>
                        </div>
                    @else
                        <img src="{{ asset($abjad->berkas_video) }}" alt="{{ $huruf }}" class="abjad-media">
                    @endif
                @else
                    <div class="media-placeholder">📷</div>
                @endif
            </div>

            <div class="abjad-card-content">
                <div class="abjad-letter">{{ $huruf }}</div>
                <div class="abjad-status {{ $statusClass }}">{{ $statusText }}</div>
            </div>
        </div>
    @endforeach
</div>

<div id="noResults" class="no-results hidden">
    <div class="no-results-icon">🔍</div>
    <div style="font-weight: 700; color: #475569; font-size: 1.1rem;">Tidak ditemukan</div>
    <div style="color: #94a3b8;">Coba kata kunci lain</div>
</div>

<!-- Modern Modal -->
<div class="modal-overlay" id="contentModal">
    <div class="modal-container">
        <div class="modal-content">
            <!-- Video Section -->
            <div class="modal-video-section">
                <button class="modal-close" onclick="closeModal()">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5">
                        <line x1="15" y1="5" x2="5" y2="15"/><line x1="5" y1="5" x2="15" y2="15"/>
                    </svg>
                </button>
                <div class="modal-video-wrapper" id="modalVideoContainer">
                    <!-- Video/Image will be injected here -->
                </div>
            </div>
            
            <!-- Info Section -->
            <div class="modal-info-section">
                <div class="modal-letter-badge">
                    📚 Panduan Gerakan
                </div>
                <h2 class="modal-title" id="modalTitle">Huruf A</h2>
                <p class="modal-subtitle" id="modalSubtitle">Pelajari isyarat untuk huruf A</p>
                
                <!-- Instruction -->
                <div class="instruction-card">
                    <div class="instruction-header">
                        <div class="instruction-icon">✋</div>
                        <span class="instruction-title">Cara Gerakan</span>
                    </div>
                    <p class="instruction-text" id="modalDescription">
                        Ikuti gerakan tangan pada video dengan seksama.
                    </p>
                </div>
                
                <!-- Tips -->
                <!-- Tips Removed -->
                
                <!-- Progress -->
                <div class="progress-section">
                    <div class="progress-header">
                        <span class="progress-label">Progress Belajar</span>
                        <span class="progress-value" id="modalProgress">0%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" id="modalProgressBar" style="width: 0%"></div>
                    </div>
                </div>
                
                <!-- Actions -->
                <div class="modal-actions">
                    <button onclick="markAsDone()" class="btn-complete">
                        ✅ Tandai Selesai
                    </button>
                    <a href="{{ route('latihan.deteksi') }}" class="btn-practice">
                        📷 Latihan Kamera
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@push('skrip')
<script>
    let currentLetter = '';

    function filterCards() {
        const search = document.getElementById('searchInput').value.toLowerCase();
        const cards = document.querySelectorAll('.abjad-card');
        const noResults = document.getElementById('noResults');
        let visibleCount = 0;

        cards.forEach(card => {
            const letter = card.dataset.letter;
            if (letter.includes(search)) {
                card.style.display = 'block';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        noResults.classList.toggle('hidden', visibleCount > 0);
    }

    function scrollToLetter(letter) {
        const el = document.getElementById('letter-' + letter);
        if (el) {
            el.scrollIntoView({ behavior: 'smooth', block: 'center' });
            el.style.transform = 'scale(1.08)';
            setTimeout(() => el.style.transform = '', 400);
        }
    }

    function openModal(huruf, deskripsi, videoUrl, progress) {
        currentLetter = huruf;
        document.getElementById('modalTitle').innerText = 'Huruf ' + huruf;
        document.getElementById('modalSubtitle').innerText = 'Pelajari isyarat untuk huruf ' + huruf;
        document.getElementById('modalDescription').innerText = deskripsi || 'Ikuti gerakan tangan pada video dengan seksama untuk mempelajari huruf ' + huruf + '.';
        
        // Update progress
        document.getElementById('modalProgress').innerText = Math.round(progress) + '%';
        document.getElementById('modalProgressBar').style.width = progress + '%';
        
        const videoContainer = document.getElementById('modalVideoContainer');
        
        if (videoUrl) {
            const ext = videoUrl.split('.').pop().toLowerCase();
            if (['mp4', 'mov', 'webm', 'ogg'].includes(ext)) {
                videoContainer.innerHTML = `<video src="${videoUrl}" controls autoplay loop style="border-radius: 16px;"></video>`;
            } else {
                videoContainer.innerHTML = `<img src="${videoUrl}" alt="Isyarat ${huruf}" style="border-radius: 16px;">`;
            }
        } else {
            videoContainer.innerHTML = `
                <div class="video-placeholder">
                    <div class="placeholder-icon-wrapper">
                        <div class="placeholder-ring">
                            <span class="placeholder-hand">🤟</span>
                        </div>
                    </div>
                    <div class="placeholder-title">Video Segera Hadir</div>
                    <div class="placeholder-subtitle">Kami sedang menyiapkan panduan isyarat untuk huruf ${huruf}</div>
                    <div class="placeholder-badge">
                        <span class="dot"></span>
                        Dalam Pengembangan
                    </div>
                </div>`;
        }

        document.getElementById('contentModal').classList.add('active');
        document.body.style.overflow = 'hidden';
        
        // Increment progress by 1% via API if not yet 100%
        if (progress < 100) {
            fetch('{{ route("kamus.increment-progress") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ word: huruf })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update UI immediately
                    const newProgress = data.progress; // This should be current + 1
                    document.getElementById('modalProgress').innerText = Math.round(newProgress) + '%';
                    document.getElementById('modalProgressBar').style.width = newProgress + '%';
                    
                    // Update card data attribute so next open uses new progress
                    const card = document.getElementById('letter-' + huruf);
                    if (card) {
                        card.setAttribute('data-progress', newProgress);
                        // Update onclick attribute to reflect new progress (so next click passes new value)
                        // Note: Ideally we should refactor to not pass data in onclick, but this is a quick fix
                        const currentOnclick = card.getAttribute('onclick');
                        // Regex to replace the last parameter (progress)
                        const newOnclick = currentOnclick.replace(/,\s*\d+(\.\d+)?\)$/, `, ${newProgress})`);
                        card.setAttribute('onclick', newOnclick);
                        
                        // Update status text on card if needed (e.g. from Mulai to 1%)
                        const statusEl = card.querySelector('.abjad-status');
                        if (statusEl && newProgress < 80 && newProgress >= 1) { // 30 is learning threshold usually, user wants 1% increment visible?
                             // Default logic: < 30 is 'Mulai'. If we want 1% to show '1%', we need to change blade logic too or JS override
                             if (newProgress >= 30) {
                                 statusEl.className = 'abjad-status status-learning';
                                 statusEl.innerText = Math.round(newProgress) + '%';
                             }
                        }
                    }
                }
            })
            .catch(err => console.error('Failed to increment progress', err));
        }
    }

    function closeModal() {
        document.getElementById('contentModal').classList.remove('active');
        document.body.style.overflow = 'auto';
        // Hentikan video saat modal ditutup agar tidak play di background
        document.getElementById('modalVideoContainer').innerHTML = '';
    }

    async function markAsDone() {
        if (!currentLetter) return;
        try {
            const response = await fetch('{{ route("kamus.mark-done") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ word: currentLetter })
            });

            if (response.ok) {
                const card = document.getElementById('letter-' + currentLetter);
                if (card) {
                    const status = card.querySelector('.abjad-status');
                    if (status) {
                        status.className = 'abjad-status status-done';
                        status.innerText = '✓ Mahir';
                    }
                    
                    const buttons = document.querySelectorAll('.alpha-btn');
                    buttons.forEach(btn => {
                        if(btn.textContent.trim() === currentLetter) {
                            btn.classList.add('learned');
                        }
                    });
                    
                    // Update modal progress
                    document.getElementById('modalProgress').innerText = '100%';
                    document.getElementById('modalProgressBar').style.width = '100%';
                }
                
                setTimeout(closeModal, 500);
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }

    document.getElementById('contentModal').addEventListener('click', function(e) {
        if (e.target === this) closeModal();
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeModal();
    });
</script>
@endpush

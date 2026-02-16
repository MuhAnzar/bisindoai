{{-- Beranda Styles - Custom CSS dengan tema #57BBA0 dan Responsive Design --}}
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    /* Font family override */
    * { font-family: 'Poppins', sans-serif; }

    /* Primary color variables */
    :root {
        --primary: #57BBA0;
        --primary-dark: #45A38A;
        --primary-light: #E8F7F3;
        --primary-gradient: linear-gradient(135deg, #57BBA0 0%, #45A38A 100%);
    }

    /* ==================== HERO SECTION ==================== */
    .hero-section {
        background: var(--primary);
        position: relative;
        overflow: hidden;
        padding: 40px 0 80px;
    }

    @media (min-width: 768px) {
        .hero-section {
            padding: 50px 0 90px;
        }
    }

    @media (min-width: 1024px) {
        .hero-section {
            padding: 60px 0 100px;
        }
    }

    .hero-section::before {
        content: '';
        position: absolute;
        bottom: 30px;
        left: 0;
        right: 0;
        height: 60px;
        background: linear-gradient(to bottom, transparent, rgba(255,255,255,0.3));
        z-index: 1;
    }

    .hero-section::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: -5%;
        right: -5%;
        width: 110%;
        height: 80px;
        background: white;
        border-radius: 50% 50% 0 0 / 100% 100% 0 0;
        box-shadow: 0 -20px 40px rgba(87, 187, 160, 0.1);
    }

    @media (min-width: 768px) {
        .hero-section::before {
            height: 80px;
            bottom: 40px;
        }
        .hero-section::after {
            height: 100px;
            box-shadow: 0 -30px 60px rgba(87, 187, 160, 0.15);
        }
    }

    @media (min-width: 1024px) {
        .hero-section::before {
            height: 100px;
            bottom: 50px;
        }
        .hero-section::after {
            height: 120px;
            box-shadow: 0 -40px 80px rgba(87, 187, 160, 0.1);
        }
    }

    /* Decorative circles - responsive sizing */
    .hero-circle {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.1);
    }
    .hero-circle-1 {
        width: 150px;
        height: 150px;
        top: -50px;
        right: -30px;
    }
    .hero-circle-2 {
        width: 100px;
        height: 100px;
        bottom: 30px;
        left: -40px;
    }
    .hero-circle-3 {
        width: 80px;
        height: 80px;
        top: 20%;
        right: 10%;
        background: rgba(255, 255, 255, 0.05);
    }

    /* Decorative Wave Lines */
    .hero-wave-lines {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        z-index: 1;
        pointer-events: none;
    }

    .hero-wave-fill {
        position: absolute;
        left: 0;
        top: 0;
        width: 50%;
        height: 100%;
        z-index: 1;
        pointer-events: none;
    }

    /* Decorative Dots Pattern */
    .hero-dots-pattern {
        position: absolute;
        right: 5%;
        top: 50%;
        transform: translateY(-50%);
        width: 120px;
        height: 100px;
        z-index: 1;
        background-image: radial-gradient(circle, rgba(255, 255, 255, 0.4) 3px, transparent 3px);
        background-size: 20px 20px;
        opacity: 0.8;
    }

    @media (max-width: 767px) {
        .hero-dots-pattern {
            width: 80px;
            height: 70px;
            right: 3%;
            top: 15%;
            transform: none;
            background-size: 15px 15px;
            opacity: 0.5;
        }
        .hero-wave-fill {
            opacity: 0.5;
        }
    }

    @media (min-width: 768px) {
        .hero-dots-pattern {
            width: 150px;
            height: 120px;
            right: 8%;
        }
    }

    @media (min-width: 1024px) {
        .hero-dots-pattern {
            width: 180px;
            height: 150px;
            right: 10%;
            background-size: 24px 24px;
        }
    }

    @media (min-width: 768px) {
        .hero-circle-1 {
            width: 200px;
            height: 200px;
            top: -80px;
            right: -40px;
        }
        .hero-circle-2 {
            width: 150px;
            height: 150px;
            bottom: 40px;
            left: -60px;
        }
        .hero-circle-3 {
            width: 120px;
            height: 120px;
        }
    }

    @media (min-width: 1024px) {
        .hero-circle-1 {
            width: 300px;
            height: 300px;
            top: -100px;
            right: -50px;
        }
        .hero-circle-2 {
            width: 200px;
            height: 200px;
            bottom: 50px;
            left: -80px;
        }
        .hero-circle-3 {
            width: 150px;
            height: 150px;
            right: 20%;
        }
    }

    /* ==================== FEATURE CARDS ==================== */
    .feature-card {
        background: white;
        border-radius: 16px;
        padding: 20px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        border: 1px solid #f0f0f0;
    }

    @media (min-width: 768px) {
        .feature-card {
            border-radius: 20px;
            padding: 25px;
        }
    }

    @media (min-width: 1024px) {
        .feature-card {
            padding: 30px;
        }
    }

    .feature-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 50px rgba(87, 187, 160, 0.15);
    }

    /* Disable hover transform on mobile for better UX */
    @media (max-width: 767px) {
        .feature-card:hover {
            transform: none;
        }
    }

    .feature-card-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 16px;
        flex-shrink: 0;
    }

    @media (min-width: 768px) {
        .feature-card-icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            margin-bottom: 20px;
        }
    }

    @media (min-width: 1024px) {
        .feature-card-icon {
            width: 60px;
            height: 60px;
            border-radius: 16px;
        }
    }

    .feature-card-icon.teal { background: var(--primary-light); color: var(--primary); }
    .feature-card-icon.blue { background: #E0F2FE; color: #0EA5E9; }
    .feature-card-icon.purple { background: #F3E8FF; color: #A855F7; }
    .feature-card-icon.orange { background: #FEF3C7; color: #F59E0B; }

    /* ==================== TRAINING SECTION ==================== */
    .training-section {
        position: relative;
        background-size: cover;
        background-position: center;
        padding: 60px 0;
        margin: 40px 0;
    }

    @media (min-width: 768px) {
        .training-section {
            padding: 80px 0;
            margin: 50px 0;
        }
    }

    @media (min-width: 1024px) {
        .training-section {
            padding: 100px 0;
            margin: 60px 0;
        }
    }

    .training-section::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(87, 187, 160, 0.92) 0%, rgba(69, 163, 138, 0.88) 100%);
    }

    .training-content {
        position: relative;
        z-index: 10;
    }

    /* ==================== ANIMATIONS ==================== */
    .animate-float {
        animation: float-y 5s cubic-bezier(0.4, 0, 0.2, 1) infinite;
    }

    @keyframes float-y {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-18px); }
    }

    @media (min-width: 768px) {
        @keyframes float-y {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-25px); }
        }
    }

    /* Fade In Up Animation - Smoother */
    .animate-in { 
        animation: fadeInUp 0.9s cubic-bezier(0.22, 1, 0.36, 1) forwards; 
        opacity: 0; 
    }
    .delay-1 { animation-delay: 0.15s; }
    .delay-2 { animation-delay: 0.3s; }
    .delay-3 { animation-delay: 0.45s; }
    .delay-4 { animation-delay: 0.6s; }
    .delay-5 { animation-delay: 0.75s; }
    
    @keyframes fadeInUp {
        from { 
            opacity: 0; 
            transform: translateY(40px); 
        }
        to { 
            opacity: 1; 
            transform: translateY(0); 
        }
    }

    /* Slide In Left Animation */
    .animate-slide-in-left {
        animation: slideInLeft 0.8s cubic-bezier(0.22, 1, 0.36, 1) forwards;
        opacity: 0;
    }

    @keyframes slideInLeft {
        from { 
            opacity: 0; 
            transform: translateX(-50px); 
        }
        to { 
            opacity: 1; 
            transform: translateX(0); 
        }
    }

    /* Slide In Right Animation */
    .animate-slide-in-right {
        animation: slideInRight 0.8s cubic-bezier(0.22, 1, 0.36, 1) forwards;
        opacity: 0;
    }

    @keyframes slideInRight {
        from { 
            opacity: 0; 
            transform: translateX(50px); 
        }
        to { 
            opacity: 1; 
            transform: translateX(0); 
        }
    }

    /* Scale Up Animation */
    .animate-scale-up {
        animation: scaleUp 0.6s cubic-bezier(0.22, 1, 0.36, 1) forwards;
        opacity: 0;
    }

    @keyframes scaleUp {
        from { 
            opacity: 0; 
            transform: scale(0.8); 
        }
        to { 
            opacity: 1; 
            transform: scale(1); 
        }
    }

    /* Pulse Glow Animation */
    .animate-pulse-glow {
        animation: pulseGlow 2s ease-in-out infinite;
    }

    @keyframes pulseGlow {
        0%, 100% { 
            box-shadow: 0 0 20px rgba(87, 187, 160, 0.3);
        }
        50% { 
            box-shadow: 0 0 40px rgba(87, 187, 160, 0.5);
        }
    }

    /* Smooth transitions for all interactive elements */
    a, button, .feature-card, .floating-card, .app-store-btn {
        transition: all 0.4s cubic-bezier(0.22, 1, 0.36, 1);
    }

    /* ==================== SCROLL ANIMATIONS ==================== */
    /* Section reveal animation */
    .section-reveal {
        opacity: 0;
        transform: translateY(60px);
        transition: all 0.8s cubic-bezier(0.22, 1, 0.36, 1);
    }

    .section-reveal.revealed {
        opacity: 1;
        transform: translateY(0);
    }

    /* Slide from left */
    .slide-left {
        opacity: 0;
        transform: translateX(-80px);
        transition: all 0.9s cubic-bezier(0.22, 1, 0.36, 1);
    }

    .slide-left.revealed {
        opacity: 1;
        transform: translateX(0);
    }

    /* Slide from right */
    .slide-right {
        opacity: 0;
        transform: translateX(80px);
        transition: all 0.9s cubic-bezier(0.22, 1, 0.36, 1);
    }

    .slide-right.revealed {
        opacity: 1;
        transform: translateX(0);
    }

    /* Fade up stagger */
    .fade-up-stagger {
        opacity: 0;
        transform: translateY(40px);
        transition: all 0.7s cubic-bezier(0.22, 1, 0.36, 1);
    }

    .fade-up-stagger.revealed {
        opacity: 1;
        transform: translateY(0);
    }

    /* Zoom in */
    .zoom-in {
        opacity: 0;
        transform: scale(0.85);
        transition: all 0.8s cubic-bezier(0.22, 1, 0.36, 1);
    }

    .zoom-in.revealed {
        opacity: 1;
        transform: scale(1);
    }

    /* Stagger delays */
    .stagger-1 { transition-delay: 0.1s; }
    .stagger-2 { transition-delay: 0.2s; }
    .stagger-3 { transition-delay: 0.3s; }
    .stagger-4 { transition-delay: 0.4s; }
    .stagger-5 { transition-delay: 0.5s; }

    /* ==================== BUTTONS ==================== */
    .btn-primary-custom {
        background: var(--primary);
        color: white;
        padding: 12px 24px;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        font-size: 14px;
        width: 100%;
    }

    @media (min-width: 640px) {
        .btn-primary-custom {
            width: auto;
            padding: 14px 32px;
            border-radius: 12px;
            font-size: 16px;
        }
    }

    .btn-primary-custom:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(87, 187, 160, 0.3);
    }

    .btn-outline-custom {
        background: transparent;
        color: white;
        padding: 12px 24px;
        border-radius: 10px;
        font-weight: 600;
        border: 2px solid rgba(255, 255, 255, 0.5);
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        font-size: 14px;
        width: 100%;
    }

    @media (min-width: 640px) {
        .btn-outline-custom {
            width: auto;
            padding: 14px 32px;
            border-radius: 12px;
            font-size: 16px;
        }
    }

    .btn-outline-custom:hover {
        background: rgba(255, 255, 255, 0.1);
        border-color: white;
    }

    /* ==================== CTA SECTION ==================== */
    .cta-section {
        background: var(--primary-light);
        border-radius: 16px;
        padding: 40px 20px;
        text-align: center;
    }

    @media (min-width: 768px) {
        .cta-section {
            border-radius: 20px;
            padding: 50px 30px;
        }
    }

    @media (min-width: 1024px) {
        .cta-section {
            border-radius: 24px;
            padding: 60px 40px;
        }
    }

    /* ==================== SECTION TITLES ==================== */
    .section-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1F2937;
        margin-bottom: 8px;
    }

    @media (min-width: 768px) {
        .section-title {
            font-size: 1.75rem;
            margin-bottom: 10px;
        }
    }

    @media (min-width: 1024px) {
        .section-title {
            font-size: 2rem;
            margin-bottom: 12px;
        }
    }

    .section-subtitle {
        color: #6B7280;
        font-size: 0.95rem;
        max-width: 600px;
        margin: 0 auto 30px;
        line-height: 1.6;
    }

    @media (min-width: 768px) {
        .section-subtitle {
            font-size: 1rem;
            margin: 0 auto 35px;
        }
    }

    @media (min-width: 1024px) {
        .section-subtitle {
            font-size: 1.1rem;
            margin: 0 auto 40px;
        }
    }

    /* ==================== APP STORE BUTTONS ==================== */
    .app-store-btn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: #000;
        color: white;
        padding: 10px 18px;
        border-radius: 10px;
        transition: all 0.3s ease;
        width: 100%;
        justify-content: center;
    }

    @media (min-width: 640px) {
        .app-store-btn {
            width: auto;
            padding: 12px 24px;
            border-radius: 12px;
        }
    }

    .app-store-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    }

    .app-store-btn img {
        height: 24px;
    }

    @media (min-width: 768px) {
        .app-store-btn img {
            height: 28px;
        }
    }

    /* ==================== FLOATING CARDS ==================== */
    .floating-card {
        position: absolute;
        background: white;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        padding: 10px 12px;
    }

    @media (min-width: 768px) {
        .floating-card {
            padding: 12px 14px;
            border-radius: 14px;
        }
    }

    @media (min-width: 1024px) {
        .floating-card {
            padding: 12px 16px;
            border-radius: 16px;
        }
    }

    /* Hide floating cards on mobile to avoid clutter */
    @media (max-width: 639px) {
        .floating-card {
            display: none;
        }
    }

    /* ==================== STATS SECTION ==================== */
    .hero-stats {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        justify-content: center;
    }

    @media (min-width: 768px) {
        .hero-stats {
            gap: 24px;
        }
    }

    @media (min-width: 1024px) {
        .hero-stats {
            gap: 32px;
            justify-content: flex-start;
        }
    }

    .hero-stats-divider {
        display: none;
    }

    @media (min-width: 768px) {
        .hero-stats-divider {
            display: block;
            width: 1px;
            height: 48px;
            background: rgba(255, 255, 255, 0.3);
        }
    }

    /* ==================== UTILITY CLASSES ==================== */
    .text-responsive-heading {
        font-size: 1.75rem;
        line-height: 1.2;
    }

    @media (min-width: 768px) {
        .text-responsive-heading {
            font-size: 2.25rem;
        }
    }

    @media (min-width: 1024px) {
        .text-responsive-heading {
            font-size: 2.75rem;
        }
    }

    @media (min-width: 1280px) {
        .text-responsive-heading {
            font-size: 3rem;
        }
    }

    /* ==================== TRUST INDICATORS ==================== */
    .trust-indicators {
        display: flex;
        flex-direction: column;
        gap: 12px;
        align-items: center;
    }

    @media (min-width: 640px) {
        .trust-indicators {
            flex-direction: row;
            flex-wrap: wrap;
            justify-content: center;
            gap: 16px;
        }
    }

    @media (min-width: 768px) {
        .trust-indicators {
            gap: 24px;
        }
    }

    /* ==================== HERO AUTH SECTION ==================== */
    .hero-section-auth {
        background: transparent;
    }

    /* ==================== CARD 3D (Legacy support) ==================== */
    .card-3d {
        background: white;
        border-radius: 16px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        border: 1px solid #f0f0f0;
    }

    @media (min-width: 768px) {
        .card-3d {
            border-radius: 20px;
        }
    }

    .card-3d:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 50px rgba(87, 187, 160, 0.15);
    }

    @media (max-width: 767px) {
        .card-3d:hover {
            transform: none;
        }
    }

    /* ==================== CARD SOLID (Legacy support) ==================== */
    .card-solid {
        border-radius: 16px;
        padding: 24px;
        position: relative;
        overflow: hidden;
    }

    @media (min-width: 768px) {
        .card-solid {
            border-radius: 20px;
            padding: 32px;
        }
    }

    /* Dots pattern for auth hero */
    .hero-dots-pattern-auth {
        position: absolute;
        right: 8%;
        top: 20%;
        width: 100px;
        height: 80px;
        z-index: 1;
        background-image: radial-gradient(circle, rgba(255, 255, 255, 0.35) 3px, transparent 3px);
        background-size: 18px 18px;
        opacity: 0.7;
        pointer-events: none;
    }

    @media (max-width: 767px) {
        .hero-dots-pattern-auth {
            width: 60px;
            height: 50px;
            right: 5%;
            top: 10%;
            background-size: 12px 12px;
            opacity: 0.4;
        }
    }

    @media (min-width: 1024px) {
        .hero-dots-pattern-auth {
            width: 140px;
            height: 110px;
            right: 12%;
            background-size: 22px 22px;
        }
    }
</style>

{{-- Scroll Animation Script --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Intersection Observer for scroll animations
    const observerOptions = {
        root: null,
        rootMargin: '0px 0px -100px 0px',
        threshold: 0.1
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('revealed');
            }
        });
    }, observerOptions);

    // Observe all elements with scroll animation classes
    const animatedElements = document.querySelectorAll('.section-reveal, .slide-left, .slide-right, .fade-up-stagger, .zoom-in');
    animatedElements.forEach(el => observer.observe(el));
});
</script>

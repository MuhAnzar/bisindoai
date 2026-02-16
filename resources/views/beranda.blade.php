@extends('komponen.tata_letak')

@section('judul', 'BISINDO AI - Belajar Bahasa Isyarat')
@section('deskripsi', 'Platform pembelajaran Bahasa Isyarat Indonesia dengan teknologi AI.')

@push('gaya')
    @include('komponen.beranda._styles')
@endpush

@section('konten')

@auth
{{-- ==================== USER DASHBOARD ==================== --}}
<div class="min-h-screen -mt-[40px] pt-8 pb-16 relative">
    {{-- Background Image with Bright Overlay --}}
    <div class="fixed inset-0 -z-10">
        <div class="absolute inset-0 bg-gradient-to-br from-white/90 via-[#f8fdfc]/85 to-[#E8F7F3]/90"></div>
        <div class="absolute inset-0" style="background-image: url('{{ asset('img/background-character.png') }}'); background-size: cover; background-position: center; opacity: 0.35;"></div>
    </div>
    
    <div class="container mx-auto px-4 relative z-10">

        {{-- Hero Section untuk User Login --}}
        @include('komponen.beranda.hero-auth', [
            'streak' => $streak ?? 3,
            'score' => $score ?? 150,
            'level' => $level ?? 2
        ])

        {{-- Reminder Banner --}}
        @include('komponen.beranda.reminder-banner', [
            'showReminder' => $showReminder ?? false,
            'firstName' => $firstName ?? 'Kamu'
        ])

        {{-- Feature Cards Section --}}
        @include('komponen.beranda.feature-cards')

        {{-- AI Practice Card --}}
        @include('komponen.beranda.ai-practice-card')

        {{-- Quote Section --}}
        @include('komponen.beranda.quote-section')

    </div>
</div>

@else
{{-- ==================== GUEST LANDING PAGE ==================== --}}
<div class="min-h-screen -mt-[40px]">
    {{-- Hero Section untuk Guest (full-width) --}}
    @include('komponen.beranda.hero-guest')

    <div class="container mx-auto px-4">
        {{-- Features Section --}}
        @include('komponen.beranda.features-guest')
    </div>

    {{-- Training Section (full-width) --}}
    @include('komponen.beranda.training-section')

    <div class="container mx-auto px-4 pb-20">
        {{-- CTA Section --}}
        @include('komponen.beranda.cta-guest')
    </div>
</div>
@endauth

@push('skrip')
    @include('komponen.beranda._scripts')
@endpush

@endsection

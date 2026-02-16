{{-- Beranda Scripts - JavaScript functions --}}
<script>
function dismissReminder() {
    const banner = document.getElementById('reminder-banner');
    if (banner) {
        // Animate out (slide down)
        banner.style.transition = 'all 0.3s ease-in';
        banner.style.transform = 'translateY(20px)';
        banner.style.opacity = '0';
        
        setTimeout(() => {
            banner.remove();
        }, 300);
        
        // Save dismiss status via AJAX
        fetch('{{ route("reminder.dismiss") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
        }).catch(err => console.log('Dismiss saved locally'));
    }
}
</script>

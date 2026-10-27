document.addEventListener('DOMContentLoaded', () => {
    // Password Show/Hide Toggle
    const toggleBtns = document.querySelectorAll('.toggle-password');
    toggleBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const input = this.previousElementSibling;
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
            this.textContent = type === 'password' ? '👁️' : '🙈';
        });
    });

    // Form Loading State
    const forms = document.querySelectorAll('form.dynamic-form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const btn = this.querySelector('button[type="submit"]');
            if (btn) {
                const originalText = btn.innerHTML;
                btn.innerHTML = `<span class="spinner"></span> Processing...`;
                btn.disabled = true;
                
                // Re-enable if form doesn't navigate (e.g. AJAX or error)
                setTimeout(() => {
                    if (!window.location.href.includes('dashboard')) {
                        btn.innerHTML = originalText;
                        btn.disabled = false;
                    }
                }, 3000);
            }
        });
    });
});
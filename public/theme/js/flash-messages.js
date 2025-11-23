function closeAlert(button) {
    const alert = button.closest('.alert');
    alert.classList.add('hiding');
    setTimeout(() => {
        if (alert.parentElement) {
            alert.remove();
        }
    }, 300);
}

// Fermeture automatique après 6 secondes
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    
    alerts.forEach(alert => {
        setTimeout(() => {
            if (alert.parentElement) {
                alert.classList.add('hiding');
                setTimeout(() => alert.remove(), 300);
            }
        }, 6000);
        
        // Pause au survol
        alert.addEventListener('mouseenter', () => {
            alert.querySelector('.alert-progress').style.animationPlayState = 'paused';
        });
        
        alert.addEventListener('mouseleave', () => {
            alert.querySelector('.alert-progress').style.animationPlayState = 'running';
        });
    });
});
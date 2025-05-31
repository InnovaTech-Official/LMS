function getRandomColor(key) {
    const colors = [
        '#3b82f6', '#22c55e', '#ef4444', '#f59e0b', '#8b5cf6', 
        '#ec4899', '#0891b2', '#64748b', '#84cc16', '#14b8a6', 
        '#6366f1', '#d946ef', '#f43f5e', '#0ea5e9'
    ];
    
    let hash = 0;
    for (let i = 0; i < key.length; i++) {
        hash = key.charCodeAt(i) + ((hash << 5) - hash);
    }
    
    const index = Math.abs(hash) % colors.length;
    return colors[index];
}

function updateClock() {
    const now = new Date();
    const timeOptions = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true };
    const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    
    document.getElementById('clock').textContent = now.toLocaleTimeString(undefined, timeOptions);
    document.getElementById('date').textContent = now.toLocaleDateString(undefined, dateOptions);
}

setInterval(updateClock, 1000);
updateClock();

document.querySelectorAll('.setup-card').forEach(card => {
    card.addEventListener('click', (e) => {
        const link = card.closest('a');
        if (link && link.href) {
            window.location.href = link.href;
        }
    });
});

document.querySelectorAll('.setup-card').forEach(card => {
    card.addEventListener('touchend', (e) => {
        e.preventDefault();
        const link = card.closest('a');
        if (link && link.href) {
            window.location.href = link.href;
        }
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
    const navMenu = document.querySelector('.nav-menu');

    if (mobileMenuBtn && navMenu) {
        mobileMenuBtn.addEventListener('click', () => {
            navMenu.classList.toggle('active');
        });

        document.addEventListener('click', (e) => {
            if (!navMenu.contains(e.target) && !mobileMenuBtn.contains(e.target)) {
                navMenu.classList.remove('active');
            }
        });
    }

    window.addEventListener('resize', () => {
        if (window.innerWidth > 768 && navMenu) {
            navMenu.classList.remove('active');
        }
    });
});
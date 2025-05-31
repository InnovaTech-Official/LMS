        // Display live clock and date
        function updateClock() {
            const now = new Date();
            const timeOptions = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true };
            const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            
            document.getElementById('clock').textContent = now.toLocaleTimeString(undefined, timeOptions);
            document.getElementById('date').textContent = now.toLocaleDateString(undefined, dateOptions);
        }

        // Update clock every second
        setInterval(updateClock, 1000);
        updateClock(); // Initial call

        // Make entire card clickable
        document.querySelectorAll('.pos-card').forEach(card => {
            card.addEventListener('click', (e) => {
                // Find the parent <a> tag and navigate to its href
                const link = card.closest('a');
                if (link && link.href) {
                    window.location.href = link.href;
                }
            });
        });

        // Add touch support for mobile devices
        document.querySelectorAll('.pos-card').forEach(card => {
            card.addEventListener('touchend', (e) => {
                e.preventDefault();
                const link = card.closest('a');
                if (link && link.href) {
                    window.location.href = link.href;
                }
            });
        });
        
        // Mobile menu toggle
        document.addEventListener('DOMContentLoaded', function () {
            const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
            const navMenu = document.querySelector('.nav-menu');

            if (mobileMenuBtn && navMenu) {
                mobileMenuBtn.addEventListener('click', () => {
                    navMenu.classList.toggle('active');
                });

                // Close menu when clicking outside
                document.addEventListener('click', (e) => {
                    if (!navMenu.contains(e.target) && !mobileMenuBtn.contains(e.target)) {
                        navMenu.classList.remove('active');
                    }
                });
            }

            // Close menu on window resize
            window.addEventListener('resize', () => {
                if (window.innerWidth > 768 && navMenu) {
                    navMenu.classList.remove('active');
                }
            });
        });
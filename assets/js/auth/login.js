document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    const errorMessage = document.getElementById('errorMessage');
    const debugInfo = document.getElementById('debugInfo');

    loginForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(loginForm);
        formData.append('ajax', 'true');
        
        // Add debug parameter if needed
        // formData.append('debug', 'true');
        
        fetch('../../controllers/auth/login.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Redirect on successful login
                window.location.href = data.redirect;
            } else {
                // Display error message
                errorMessage.textContent = data.error || 'Login failed. Please try again.';
                errorMessage.style.display = 'block';
                
                // Display debug information if available
                if (data.debug) {
                    debugInfo.textContent = JSON.stringify(data.debug, null, 2);
                    debugInfo.style.display = 'block';
                }
            }
        })
        .catch(error => {
            errorMessage.textContent = 'An error occurred. Please try again.';
            errorMessage.style.display = 'block';
            console.error('Login error:', error);
        });
    });
});
document.getElementById('loginForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Clear previous error messages
    document.getElementById('errorMessage').textContent = '';
    document.getElementById('errorMessage').style.display = 'none';
    document.getElementById('debugInfo').textContent = '';
    document.getElementById('debugInfo').style.display = 'none';
    
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    
    // Create form data
    const formData = new FormData();
    formData.append('username', username);
    formData.append('password', password);
    formData.append('ajax', 'true');
    formData.append('debug', 'true');
    
    // Send AJAX request
    fetch('../../controllers/auth/login.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        return response.text().then(text => {
            try {
                // Try to parse as JSON
                return JSON.parse(text);
            } catch (e) {
                // If it's not valid JSON, show the raw response
                console.error("Failed to parse JSON:", text);
                throw new Error("Invalid JSON response: " + text.substring(0, 100));
            }
        });
    })
    .then(data => {
        console.log("Response data:", data);
        if (data.success) {
            window.location.href = data.redirect;
        } else {
            const errorMessage = document.getElementById('errorMessage');
            errorMessage.textContent = data.error || 'Unknown error occurred';
            errorMessage.style.display = 'block';
            
            if (data.debug) {
                const debugInfo = document.getElementById('debugInfo');
                debugInfo.textContent = JSON.stringify(data.debug, null, 2);
                debugInfo.style.display = 'block';
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('errorMessage').textContent = 'Connection error: ' + error.message;
        document.getElementById('errorMessage').style.display = 'block';
    });
});
document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.querySelector('.login-form');
    
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            
            // Basic validation
            if (!email || !password) {
                alert('Please fill in all fields');
                return;
            }
            
            // In a real app, you would send this to your server
            console.log('Login attempt with:', { email, password });
            
            // Simulate successful login
            // Redirect to account page or previous page
            window.location.href = 'account.html';
        });
    }
});
// Minimal Responsive Navbar/Profile Dropdown for Aura&Co

document.addEventListener('DOMContentLoaded', function() {
    // Event delegation for menu and user buttons
    document.addEventListener('click', function(e) {
        // Hamburger menu toggle
        if (e.target.matches('#menu-btn')) {
            const container = e.target.closest('.header, .admin-header, header');
            if (!container) return;
            const navbar = container.querySelector('.navbar');
            if (navbar) navbar.classList.toggle('active');
            const profile = container.querySelector('.profile');
            if (profile) profile.classList.remove('active');
            e.stopPropagation();
            return;
        }
        // User profile dropdown toggle
        if (e.target.matches('#user-btn')) {
            const container = e.target.closest('.header, .admin-header, header');
            if (!container) return;
            const profile = container.querySelector('.profile');
            if (profile) profile.classList.toggle('active');
            const navbar = container.querySelector('.navbar');
            if (navbar) navbar.classList.remove('active');
            e.stopPropagation();
            return;
        }
    });

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        document.querySelectorAll('.header, .admin-header, header').forEach(container => {
            const navbar = container.querySelector('.navbar');
            const menuBtn = container.querySelector('#menu-btn');
            if (navbar && !navbar.contains(e.target) && e.target !== menuBtn) {
                navbar.classList.remove('active');
            }
            const profile = container.querySelector('.profile');
            const userBtn = container.querySelector('#user-btn');
            if (profile && !profile.contains(e.target) && e.target !== userBtn) {
                profile.classList.remove('active');
            }
        });
    });

    // Close nav/profile on link click (mobile UX)
    document.querySelectorAll('.nav-links a').forEach(link => {
        link.addEventListener('click', () => {
            document.querySelectorAll('.navbar').forEach(navbar => navbar.classList.remove('active'));
        });
    });

    const userBtn = document.getElementById('user-btn');
    const profile = document.querySelector('.profile');
    if (userBtn && profile) {
        userBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            profile.classList.toggle('active');
        });
        document.addEventListener('click', function(e) {
            if (!profile.contains(e.target) && e.target !== userBtn) {
                profile.classList.remove('active');
            }
        });
    }
});
// No legacy code below. All logic is now handled above for both user and admin headers.
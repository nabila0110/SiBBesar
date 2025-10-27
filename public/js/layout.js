// Sidebar Toggle
document.addEventListener('DOMContentLoaded', function() {
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarFixedToggle = document.getElementById('sidebarFixedToggle');
    const sidebar = document.querySelector('.sidebar');
    const mainContent = document.querySelector('.main-content');

    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            // For small screens, toggle active (slide in/out)
            if (window.innerWidth <= 1024) {
                sidebar.classList.toggle('active');
                backdrop && backdrop.classList.toggle('active');
                // lock body scroll when sidebar open on mobile
                if (sidebar.classList.contains('active')) {
                    document.body.style.overflow = 'hidden';
                } else {
                    document.body.style.overflow = '';
                }
            } else {
                // For desktop, toggle hidden class on the app container (hide sidebar completely)
                document.querySelector('.app-container').classList.toggle('sidebar-hidden');
                // ensure collapsed state is removed when hiding
                document.querySelector('.app-container').classList.remove('sidebar-collapsed');
            }
        });
    }

    if (sidebarFixedToggle) {
        sidebarFixedToggle.addEventListener('click', function() {
            // If sidebar is currently hidden on desktop, show it; on mobile toggle active
            if (window.innerWidth <= 1024) {
                sidebar.classList.toggle('active');
                backdrop && backdrop.classList.toggle('active');
                if (sidebar.classList.contains('active')) {
                    document.body.style.overflow = 'hidden';
                } else {
                    document.body.style.overflow = '';
                }
            } else {
                document.querySelector('.app-container').classList.remove('sidebar-hidden');
            }
        });
    }

    // Backdrop element
    const backdrop = document.getElementById('sidebarBackdrop');
    if (backdrop) {
        backdrop.addEventListener('click', function() {
            if (window.innerWidth <= 1024) {
                sidebar.classList.remove('active');
                backdrop.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
    }

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(event) {
        if (window.innerWidth <= 1024) {
            const isClickInsideSidebar = sidebar.contains(event.target);
            const isClickOnToggle = (sidebarToggle && sidebarToggle.contains(event.target)) || (sidebarFixedToggle && sidebarFixedToggle.contains(event.target));

            if (!isClickInsideSidebar && !isClickOnToggle && sidebar.classList.contains('active')) {
                sidebar.classList.remove('active');
                backdrop && backdrop.classList.remove('active');
                document.body.style.overflow = '';
            }
        }
    });

    // Handle window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth > 1024) {
            sidebar.classList.remove('active');
            backdrop && backdrop.classList.remove('active');
            document.body.style.overflow = '';
        }
    });

    // Ensure initial mobile state: sidebar closed and backdrop hidden
    if (window.innerWidth <= 1024) {
        sidebar.classList.remove('active');
        backdrop && backdrop.classList.remove('active');
        document.body.style.overflow = '';
    }
});
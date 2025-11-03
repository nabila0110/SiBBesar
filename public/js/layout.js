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

// User Profile Dropdown Toggle
function toggleUserMenu(event) {
    event.stopPropagation();
    const container = document.querySelector('.user-profile-container');
    const menu = document.getElementById('userDropdownMenu');
    
    container.classList.toggle('active');
    menu.classList.toggle('active');
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const container = document.querySelector('.user-profile-container');
    const menu = document.getElementById('userDropdownMenu');
    const userProfileBtn = document.getElementById('userProfileBtn');
    
    // If click is outside the user profile container, close the dropdown
    if (!container.contains(event.target)) {
        container.classList.remove('active');
        menu.classList.remove('active');
    }
});

// Close dropdown when clicking on a menu item
document.querySelectorAll('.dropdown-item').forEach(item => {
    item.addEventListener('click', function(e) {
        // Don't close for logout link as it needs to submit the form
        if (!this.classList.contains('logout-item')) {
            const container = document.querySelector('.user-profile-container');
            const menu = document.getElementById('userDropdownMenu');
            container.classList.remove('active');
            menu.classList.remove('active');
        }
    });
});

// Disable navbar interactions when modal is open (like sidebar backdrop)
document.addEventListener('shown.bs.modal', function() {
    const navbar = document.querySelector('.navbar');
    if (navbar) {
        navbar.classList.add('modal-open');
    }
});

document.addEventListener('hidden.bs.modal', function() {
    const navbar = document.querySelector('.navbar');
    if (navbar) {
        navbar.classList.remove('modal-open');
    }
});

// Rupiah/Currency Formatter
function formatCurrency(value) {
    // Remove all non-digit characters
    let numValue = value.toString().replace(/\D/g, '');
    
    // Add commas every 3 digits from the right
    return numValue.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}

function parseCurrency(value) {
    // Remove all commas to get clean number
    return value.toString().replace(/,/g, '');
}

// Apply currency formatting to inputs
function setupCurrencyInputs() {
    // List of currency-related field names
    const currencyFields = ['amount', 'paid_amount', 'harga', 'debit', 'kredit', 'gaji_pokok', 'thr', 'price', 'cost'];
    
    // Find all inputs with Rp prefix in input-group (for Hutang form)
    document.querySelectorAll('.input-group').forEach(group => {
        if (group.textContent.includes('Rp')) {
            const input = group.querySelector('input[type="number"]');
            if (input && !input.dataset.currencyFormatted) {
                formatInputField(input);
            }
        }
    });
    
    // Find all number inputs with currency-related names
    document.querySelectorAll('input[type="number"]').forEach(input => {
        const inputName = input.name || input.id || '';
        const isCurrencyField = currencyFields.some(field => 
            inputName.toLowerCase().includes(field)
        );
        
        if (isCurrencyField && !input.dataset.currencyFormatted) {
            formatInputField(input);
        }
    });
}

// Helper function to format a single input field
function formatInputField(input) {
    // Change type to text to allow commas
    input.type = 'text';
    
    // Format initial value if exists
    if (input.value && input.value !== '0') {
        const cleanValue = parseCurrency(input.value);
        input.value = formatCurrency(cleanValue);
    }
    
    // Format on input
    input.addEventListener('input', function(e) {
        const cleanValue = parseCurrency(this.value);
        this.value = formatCurrency(cleanValue);
    });

    // Format on blur to ensure proper format
    input.addEventListener('blur', function(e) {
        const cleanValue = parseCurrency(this.value);
        this.value = formatCurrency(cleanValue);
    });

    // Store clean value before form submission
    const form = input.closest('form');
    if (form && !form.dataset.currencyFormattedInit) {
        form.addEventListener('submit', function(e) {
            // Clean all currency inputs in this form before submission
            this.querySelectorAll('input[data-currency-formatted="true"]').forEach(inp => {
                inp.value = parseCurrency(inp.value);
            });
        });
        form.dataset.currencyFormattedInit = 'true';
    }
    
    input.dataset.currencyFormatted = 'true';
}

// Initialize on page load and after modals open
document.addEventListener('DOMContentLoaded', setupCurrencyInputs);
document.addEventListener('shown.bs.modal', setupCurrencyInputs);

// Custom Alert System
function showCustomAlert(options = {}) {
    const {
        title = 'Sukses',
        message = 'Operasi berhasil dilakukan',
        type = 'success', // success, error, warning, info
        buttons = [{ text: 'OK', type: 'primary', callback: closeCustomAlert }],
        onClose = null
    } = options;

    const overlay = document.getElementById('customAlertOverlay');
    const alertIcon = document.getElementById('alertIcon');
    const alertTitle = document.getElementById('alertTitle');
    const alertMessage = document.getElementById('alertMessage');
    const alertButtons = document.querySelector('.custom-alert-buttons');

    // Set icon and styling
    const iconMap = {
        success: { icon: 'fas fa-check', bg: 'success' },
        error: { icon: 'fas fa-times', bg: 'error' },
        warning: { icon: 'fas fa-exclamation', bg: 'warning' },
        info: { icon: 'fas fa-info-circle', bg: 'info' }
    };

    const config = iconMap[type] || iconMap.success;
    alertIcon.className = `custom-alert-icon ${config.bg}`;
    alertIcon.innerHTML = `<i class="${config.icon}"></i>`;

    alertTitle.textContent = title;
    alertMessage.textContent = message;

    // Set buttons
    alertButtons.innerHTML = '';
    buttons.forEach(btn => {
        const button = document.createElement('button');
        button.type = 'button';
        button.className = `custom-alert-btn ${btn.type || 'primary'}`;
        button.textContent = btn.text || 'OK';
        button.onclick = btn.callback || closeCustomAlert;
        alertButtons.appendChild(button);
    });

    // Store onClose callback
    window.alertOnClose = onClose;

    // Show overlay
    overlay.classList.add('show');

    // Close on ESC key
    const closeOnEsc = (e) => {
        if (e.key === 'Escape') {
            closeCustomAlert();
            document.removeEventListener('keydown', closeOnEsc);
        }
    };
    document.addEventListener('keydown', closeOnEsc);
}

function closeCustomAlert() {
    const overlay = document.getElementById('customAlertOverlay');
    overlay.classList.remove('show');

    if (window.alertOnClose && typeof window.alertOnClose === 'function') {
        window.alertOnClose();
    }
}

// Override browser alert with custom alert
window.alert = function(message, title = 'Alert') {
    showCustomAlert({
        title: title,
        message: message,
        type: 'info'
    });
};
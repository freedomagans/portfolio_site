
// Mobile Sidebar Toggle Script (Inline for immediate execution)
document.addEventListener('DOMContentLoaded', function () {
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarClose = document.getElementById('sidebarClose');
    const sidebar = document.getElementById('sidebarMenu');
    const overlay = document.getElementById('sidebarOverlay');
    const body = document.body;

    console.log('🚀 Admin navigation script loaded');

    // Open sidebar
    function openSidebar() {
        sidebar.classList.add('mobile-active');
        overlay.classList.add('active');
        body.classList.add('sidebar-open');
    }

    // Close sidebar
    function closeSidebar() {
        sidebar.classList.remove('mobile-active');
        overlay.classList.remove('active');
        body.classList.remove('sidebar-open');
    }

    // CRITICAL: Force hide sidebar overlay (nuclear option)
    function forceSidebarHide() {
        if (overlay) {
            overlay.classList.remove('active');
            overlay.style.display = 'none';
            overlay.style.opacity = '0';
            overlay.style.pointerEvents = 'none';
            overlay.style.zIndex = '-1';
            overlay.style.visibility = 'hidden';
        }

        if (sidebar) {
            sidebar.classList.remove('mobile-active');
            sidebar.style.pointerEvents = 'none';
        }

        body.classList.remove('sidebar-open');
    }

    // Toggle button click
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', openSidebar);
    }

    // Close button click
    if (sidebarClose) {
        sidebarClose.addEventListener('click', closeSidebar);
    }

    // Overlay click (close sidebar)
    if (overlay) {
        overlay.addEventListener('click', closeSidebar);
    }

    // Close sidebar when clicking a link (mobile only)
    const sidebarLinks = sidebar.querySelectorAll('.sidebar-submenu .nav-link');
    sidebarLinks.forEach(link => {
        link.addEventListener('click', function () {
            if (window.innerWidth < 992) {
                closeSidebar();
            }
        });
    });
});

// Close sidebar on ESC key
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && sidebar.classList.contains('mobile-active')) {
        closeSidebar();
    }
});

// ============================================
// CRITICAL FIX: Bootstrap Modal Integration
// ============================================

console.log('👂 Setting up modal event listeners...');

// Nuclear option to completely reset overlay
function nukeSidebarOverlay() {
    const overlay = document.getElementById('sidebarOverlay');
    const sidebar = document.getElementById('sidebarMenu');
    const body = document.body;

    console.log('💣 Nuking sidebar overlay...');

    if (overlay) {
        overlay.classList.remove('active');
        // Force all blocking properties to safe values
        overlay.style.cssText = `
            display: none !important;
            opacity: 0 !important;
            pointer-events: none !important;
            visibility: hidden !important;
            z-index: -1 !important;
        `;
    }

    if (sidebar) {
        sidebar.classList.remove('mobile-active');
        // Only block pointer events when sidebar is closed
        if (window.innerWidth < 992) {
            sidebar.style.pointerEvents = 'none';
        }
    }

    body.classList.remove('sidebar-open');
    console.log('✅ Sidebar overlay nuked');
}

// Restore normal functionality after modal closes
function restoreOverlay() {
    const overlay = document.getElementById('sidebarOverlay');
    const sidebar = document.getElementById('sidebarMenu');

    console.log('🔄 Restoring overlay to normal state...');

    if (overlay) {
        // Remove all inline styles to let CSS take over
        overlay.style.cssText = '';
        // Ensure active class is removed
        overlay.classList.remove('active');
    }

    if (sidebar && window.innerWidth >= 992) {
        // On desktop, sidebar should always be interactive
        sidebar.style.pointerEvents = 'auto';
    }

    console.log('✅ Overlay restored');
}

// BEFORE modal opens
document.addEventListener('show.bs.modal', function (event) {
    console.log('🔔 Modal about to open:', event.target.id);
    nukeSidebarOverlay();
});

// AFTER modal is fully visible
document.addEventListener('shown.bs.modal', function (event) {
    console.log('✅ Modal fully opened:', event.target.id);
    // Double-check overlay is hidden
    nukeSidebarOverlay();
});

// BEFORE modal closes
document.addEventListener('hide.bs.modal', function (event) {
    console.log('🔔 Modal about to close:', event.target.id);
    nukeSidebarOverlay();
});

// AFTER modal is fully closed
document.addEventListener('hidden.bs.modal', function (event) {
    console.log('❌ Modal closed:', event.target.id);

    // Wait for Bootstrap to finish cleanup, then restore normal state
    setTimeout(function () {
        restoreOverlay();

        // Re-enable all clickable elements
        const clickableElements = document.querySelectorAll('a, button, [role="button"]');
        clickableElements.forEach(el => {
            el.style.pointerEvents = 'auto';
        });

        console.log('✅ All elements re-enabled');
    }, 150);
});

// Emergency watchdog - runs periodically when modal is open
setInterval(function () {
    const isModalOpen = document.body.classList.contains('modal-open');
    const overlay = document.getElementById('sidebarOverlay');

    if (isModalOpen && overlay) {
        const computedStyle = window.getComputedStyle(overlay);
        const isBlocking = (
            overlay.classList.contains('active') ||
            computedStyle.display !== 'none' ||
            computedStyle.opacity !== '0' ||
            parseInt(computedStyle.zIndex) > 0
        );

        if (isBlocking) {
            console.warn('⚠ EMERGENCY: Overlay blocking modal! Auto-fixing...');
            nukeSidebarOverlay();
        }
    }
}, 300);

console.log('✅ Modal event listeners ready');

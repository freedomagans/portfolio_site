
document.addEventListener('DOMContentLoaded', function() {
    
    // ============================================
    // SIDEBAR COLLAPSE TOGGLE (Bootstrap 5 Fix)
    // ============================================
    const sidebarToggles = document.querySelectorAll('#sidebarMenu [data-bs-toggle="collapse"]');
    
    sidebarToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetSelector = this.getAttribute('href');
            const targetElement = document.querySelector(targetSelector);
            
            if (targetElement) {
                const bsCollapse = bootstrap.Collapse.getOrCreateInstance(targetElement);
                bsCollapse.toggle();
            }
        });
    });
    
    // ============================================
    // MOBILE SIDEBAR AUTO-CLOSE ON LINK CLICK
    // ============================================
    const sidebarLinks = document.querySelectorAll('.sidebar-submenu .nav-link');
    const sidebar = document.getElementById('sidebarMenu');
    
    sidebarLinks.forEach(link => {
        link.addEventListener('click', function() {
            // On mobile, collapse sidebar after clicking a link
            if (window.innerWidth < 768 && sidebar.classList.contains('show')) {
                const bsCollapse = bootstrap.Collapse.getInstance(sidebar);
                if (bsCollapse) {
                    bsCollapse.hide();
                }
            }
        });
    });
    
    // ============================================
    // ACTIVE LINK HIGHLIGHTING
    // ============================================
    const currentPath = window.location.href;
    const navLinks = document.querySelectorAll('.sidebar-submenu .nav-link, .navbar-enhanced .nav-link');
    
    navLinks.forEach(link => {
        if (link.href === currentPath) {
            link.classList.add('active');
            
            // If it's a submenu link, expand parent collapse
            const parentCollapse = link.closest('.collapse');
            if (parentCollapse) {
                const bsCollapse = new bootstrap.Collapse(parentCollapse, {
                    toggle: false
                });
                bsCollapse.show();
                
                // Mark parent sidebar link as expanded
                const parentLink = document.querySelector(`[href="#${parentCollapse.id}"]`);
                if (parentLink) {
                    parentLink.setAttribute('aria-expanded', 'true');
                }
            }
        }
    });
    
});

function triggerDeleteModal(deleteUrl, title = "Confirm Delete", message = "Are you sure you want to delete this item? This action cannot be undone.") {

    console.log('🗑 Triggering delete modal for:', deleteUrl);

    // CRITICAL: Force hide sidebar overlay before showing modal
    const overlay = document.getElementById('sidebarOverlay');
    const sidebar = document.getElementById('sidebarMenu');

    if (overlay) {
        overlay.classList.remove('active');
        overlay.style.cssText = 'display: none !important; pointer-events: none !important;';
    }

    if (sidebar) {
        sidebar.classList.remove('mobile-active');
    }

    document.body.classList.remove('sidebar-open');

    // CRITICAL: Remove any existing modal backdrops (especially important on mobile)
    document.querySelectorAll('.modal-backdrop').forEach(backdrop => {
        backdrop.remove();
    });

    // Ensure body doesn't have modal-open class from previous modals
    document.body.classList.remove('modal-open');

    // Update modal content
    const confirmBtn = document.getElementById('confirmDeleteBtn');
    const modalTitle = document.getElementById('deleteModalTitle');
    const modalMessage = document.getElementById('deleteModalMessage');

    if (confirmBtn) confirmBtn.href = deleteUrl;
    if (modalTitle) modalTitle.textContent = title;
    if (modalMessage) modalMessage.textContent = message;

    // Show modal using Bootstrap 5
    const deleteModalElement = document.getElementById('deleteModal');
    if (deleteModalElement) {
        const modal = new bootstrap.Modal(deleteModalElement, {
            backdrop: false, // No backdrop to prevent overlay issues on mobile
            keyboard: true // Allow ESC key to close
        });
        modal.show();

        console.log('✅ Modal shown');
    } else {
        console.error('❌ Delete modal element not found');
    }}
// ============================================
// SMOOTH SCROLL TO TOP BUTTON (Optional)
// ============================================
window.addEventListener('scroll', function() {
    const scrollBtn = document.getElementById('scrollToTop');
    if (scrollBtn) {
        if (window.pageYOffset > 300) {
            scrollBtn.style.display = 'block';
        } else {
            scrollBtn.style.display = 'none';
        }
    }
});

// ============================================
// AUTO-DISMISS ALERTS (Optional)
// ============================================
const alerts = document.querySelectorAll('.alert.auto-dismiss');
alerts.forEach(alert => {
    setTimeout(() => {
        const bsAlert = new bootstrap.Alert(alert);
        bsAlert.close();
    }, 5000); // Dismiss after 5 seconds
});

// ============================================
// SESSION TIMEOUT MANAGEMENT
// ============================================
(function() {
    // sessionTimeoutMinutes is set globally from PHP in footer.php
    const sessionTimeoutMs = sessionTimeoutMinutes * 60 * 1000; // Convert to milliseconds

    let timeoutWarningShown = false;
    let logoutTimer;
    let warningTimer;

    // Reset timers on user activity
    function resetTimers() {
        clearTimeout(logoutTimer);
        clearTimeout(warningTimer);
        console.log('🔄 Session timers reset due to user activity');

        // Show warning 5 minutes before logout (or 1 minute before if timeout < 10 minutes)
        const warningTime = sessionTimeoutMinutes < 10 ? 1 * 60 * 1000 : 5 * 60 * 1000;
        warningTimer = setTimeout(showTimeoutWarning, sessionTimeoutMs - warningTime);

        // Set logout timer
        logoutTimer = setTimeout(logoutUser, sessionTimeoutMs);

        // Hide warning if shown
        if (timeoutWarningShown) {
            hideTimeoutWarning();
        }
    }

    // Show timeout warning modal
    function showTimeoutWarning() {
        if (timeoutWarningShown) return;

        // Create warning modal if it doesn't exist
        let warningModal = document.getElementById('sessionTimeoutWarningModal');
        if (!warningModal) {
            const initialCountdown = sessionTimeoutMinutes < 10 ? 1 : 5;
            const modalHTML = `
                <div class="modal fade" id="sessionTimeoutWarningModal" tabindex="-1" aria-labelledby="sessionTimeoutWarningModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header bg-warning text-dark">
                                <h5 class="modal-title" id="sessionTimeoutWarningModalLabel">
                                    <i class="fas fa-clock"></i> Session Timeout Warning
                                </h5>
                            </div>
                            <div class="modal-body">
                                <p class="mb-3">Your session will expire in <span id="countdownTimer" class="fw-bold text-danger">${initialCountdown}</span> minutes due to inactivity.</p>
                                <p class="mb-0">Click "Continue Session" to stay logged in, or you will be automatically logged out.</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" onclick="logoutUser()">Logout Now</button>
                                <button type="button" class="btn btn-primary" onclick="extendSession()">Continue Session</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            document.body.insertAdjacentHTML('beforeend', modalHTML);
            warningModal = document.getElementById('sessionTimeoutWarningModal');
        }

        // Start countdown
        let countdown = sessionTimeoutMinutes < 10 ? 1 : 5;
        const countdownElement = document.getElementById('countdownTimer');
        const countdownInterval = setInterval(() => {
            countdown--;
            if (countdownElement) countdownElement.textContent = countdown;
            if (countdown <= 0) {
                clearInterval(countdownInterval);
                logoutUser();
            }
        }, 60000); // Update every minute

        // Show modal
        const modal = new bootstrap.Modal(warningModal);
        modal.show();
        timeoutWarningShown = true;

        // Clear countdown when modal is hidden
        warningModal.addEventListener('hidden.bs.modal', () => {
            clearInterval(countdownInterval);
        });
    }

    // Hide timeout warning
    function hideTimeoutWarning() {
        const warningModal = document.getElementById('sessionTimeoutWarningModal');
        if (warningModal) {
            const modal = bootstrap.Modal.getInstance(warningModal);
            if (modal) modal.hide();
        }
        timeoutWarningShown = false;
    }

    // Extend session (reset timers)
    window.extendSession = function() {
        resetTimers();
    };

    // Logout user
    window.logoutUser = function() {
        // Clear timers
        clearTimeout(logoutTimer);
        clearTimeout(warningTimer);

        // Redirect to logout
        window.location.href = '/urls.php?pg=logout';
    };

    // Activity events to reset timers
    const activityEvents = ['mousedown', 'mousemove', 'keypress', 'scroll', 'touchstart', 'click'];
    activityEvents.forEach(event => {
        document.addEventListener(event, resetTimers, true);
    });

    // Initialize timers on page load
    resetTimers();

    // Store last activity time in sessionStorage for cross-tab synchronization
    const storageKey = 'admin_last_activity';
    function updateLastActivity() {
        sessionStorage.setItem(storageKey, Date.now());
    }

    function checkCrossTabActivity() {
        const lastActivity = sessionStorage.getItem(storageKey);
        if (lastActivity) {
            const timeSinceActivity = Date.now() - parseInt(lastActivity);
            if (timeSinceActivity < sessionTimeoutMs) {
                resetTimers();
            }
        }
    }

    // Update activity on events
    activityEvents.forEach(event => {
        document.addEventListener(event, updateLastActivity, true);
    });

    // Check for cross-tab activity every 30 seconds
    setInterval(checkCrossTabActivity, 30000);

    // Update on page load
    updateLastActivity();
})();


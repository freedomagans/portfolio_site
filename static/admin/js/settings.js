/**
 * Enhanced Settings Page JavaScript
 * Modern interactions and smooth animations
 */

$(document).ready(function() {
    console.log('✨ Enhanced Settings Page Loaded');
    
    initializeTabs();
    initializeBooleanToggles();
    initializeFileUploads();
    initializeFormValidation();
    addSaveIndicator();
    addKeyboardShortcuts();
});

/**
 * Initialize enhanced tab switching
 */
function initializeTabs() {
    const triggerTabList = document.querySelectorAll('#settingsTabs button');
    
    triggerTabList.forEach(triggerEl => {
        const tabTrigger = new bootstrap.Tab(triggerEl);
        
        triggerEl.addEventListener('click', event => {
            event.preventDefault();
            
            // Remove active from all groups
            document.querySelectorAll('.setting-group').forEach(group => {
                group.classList.remove('active');
            });
            
            // Show target group
            const targetId = triggerEl.getAttribute('data-bs-target');
            const targetGroup = document.querySelector(targetId);
            if (targetGroup) {
                targetGroup.classList.add('active');
                
                // Smooth scroll to top of settings
                targetGroup.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
            
            tabTrigger.show();
        });
    });
    
    console.log('✅ Tabs initialized');
}

/**
 * Initialize boolean toggles with smooth animations
 */
function initializeBooleanToggles() {
    $('.boolean-toggle').on('change', function() {
        const $toggle = $(this);
        const labelId = $toggle.data('label-target');
        const $label = $('#' + labelId);
        
        if ($label.length) {
            const isChecked = $toggle.is(':checked');
            
            // Animate label change
            $label.fadeOut(150, function() {
                const statusClass = isChecked ? 'status-enabled' : 'status-disabled';
                const iconClass = isChecked ? 'fa-check-circle' : 'fa-times-circle';
                const statusText = isChecked ? 'Enabled' : 'Disabled';
                
                $(this).html(`
                    <span class="status-badge ${statusClass}">
                        <i class="fas ${iconClass} me-1"></i>
                        ${statusText}
                    </span>
                `).fadeIn(150);
            });
            
            // Add ripple effect
            createRipple($toggle[0]);
            
            console.log('🔄 Toggle:', $toggle.attr('name'), '→', isChecked);
        }
    });
    
    console.log('✅ Boolean toggles initialized:', $('.boolean-toggle').length);
}

/**
 * Create ripple effect on toggle
 */
function createRipple(element) {
    const ripple = document.createElement('span');
    ripple.style.cssText = `
        position: absolute;
        border-radius: 50%;
        background: rgba(102, 126, 234, 0.5);
        transform: scale(0);
        animation: ripple 0.6s ease-out;
        pointer-events: none;
    `;
    
    const rect = element.getBoundingClientRect();
    const size = Math.max(rect.width, rect.height);
    ripple.style.width = ripple.style.height = size + 'px';
    ripple.style.left = '0';
    ripple.style.top = '0';
    
    element.style.position = 'relative';
    element.style.overflow = 'hidden';
    element.appendChild(ripple);
    
    setTimeout(() => ripple.remove(), 600);
}

/**
 * Initialize file upload with preview
 */
function initializeFileUploads() {
    // File input change
    $('.file-input-hidden').on('change', function() {
        handleFileSelect(this);
    });
    
    // Drag and drop
    $('.file-upload-area').on({
        dragover: function(e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).addClass('dragover');
        },
        dragleave: function(e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).removeClass('dragover');
        },
        drop: function(e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).removeClass('dragover');
            
            const files = e.originalEvent.dataTransfer.files;
            if (files.length > 0) {
                const input = $(this).find('input[type="file"]')[0];
                input.files = files;
                handleFileSelect(input);
            }
        }
    });
    
    console.log('✅ File uploads initialized');
}

/**
 * Handle file selection with preview
 */
function handleFileSelect(input) {
    const $input = $(input);
    const fieldName = $input.attr('name');
    const $filenameDisplay = $('#filename_' + fieldName);
    
    if (input.files && input.files.length > 0) {
        const file = input.files[0];
        const fileName = file.name;
        const fileSize = (file.size / 1024).toFixed(2);
        
        // Validate file
        if (!file.type.startsWith('image/')) {
            showToast('Please select an image file', 'error');
            input.value = '';
            return;
        }
        
        if (file.size > 5 * 1024 * 1024) {
            showToast('File size must be less than 5MB', 'error');
            input.value = '';
            return;
        }
        
        // Show file info
        $filenameDisplay.html(`
            <i class="fas fa-file-image me-2"></i>
            <span class="filename-text">${fileName} (${fileSize} KB)</span>
            <button type="button" class="remove-file-btn" onclick="removeFile('${fieldName}')">
                <i class="fas fa-times"></i>
            </button>
        `).fadeIn(200);
        
        console.log('📁 File selected:', fileName, fileSize + 'KB');
    }
}

/**
 * Remove selected file
 */
window.removeFile = function(fieldName) {
    const input = document.getElementById(fieldName);
    if (input) {
        input.value = '';
        $('#filename_' + fieldName).fadeOut(200);
        console.log('🗑 File removed:', fieldName);
    }
};

/**
 * Form validation with visual feedback
 */
function initializeFormValidation() {
    $('#settingsForm').on('submit', function(e) {
        const $form = $(this);
        let isValid = true;
        const errors = [];
        
        // Validate email fields
        $form.find('input[type="email"]').each(function() {
            const $input = $(this);
            const value = $input.val().trim();
            
            if (value && !isValidEmail(value)) {
                isValid = false;
                const label = $input.closest('.setting-card').find('.setting-title').text();
                errors.push(`Invalid email: ${label}`);
                $input.addClass('is-invalid');
                shakeElement($input);
            } else {
                $input.removeClass('is-invalid').addClass('is-valid');
            }
        });
        
        // Validate URL fields
        $form.find('input[type="url"]').each(function() {
            const $input = $(this);
            const value = $input.val().trim();
            
            if (value && !isValidUrl(value)) {
                isValid = false;
                const label = $input.closest('.setting-card').find('.setting-title').text();
                errors.push(`Invalid URL: ${label}`);
                $input.addClass('is-invalid');
                shakeElement($input);
            } else {
                $input.removeClass('is-invalid').addClass('is-valid');
            }
        });
        
        // Validate number fields
        $form.find('input[type="number"]').each(function() {
            const $input = $(this);
            const value = $input.val();
            
            if (value && (isNaN(value) || value < 0)) {
                isValid = false;
                const label = $input.closest('.setting-card').find('.setting-title').text();
                errors.push(`Invalid number: ${label}`);
                $input.addClass('is-invalid');
                shakeElement($input);
            } else {
                $input.removeClass('is-invalid').addClass('is-valid');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            showToast('Please fix validation errors', 'error');
            errors.forEach(err => console.error('❌', err));
            return false;
        }
        
        // Show loading state
        const $btn = $form.find('.btn-save');
        $btn.addClass('loading').prop('disabled', true);
        $btn.find('i').removeClass('fa-save').addClass('fa-spinner fa-spin');
        $btn.find('span').text('Saving...');
        
        console.log('✅ Form validation passed - Submitting...');
    });
}

/**
 * Add visual shake effect to invalid fields
 */
function shakeElement($element) {
    $element.css('animation', 'shake 0.5s');
    setTimeout(() => $element.css('animation', ''), 500);
}

// Add shake animation to CSS dynamically
if (!document.getElementById('shake-animation')) {
    const style = document.createElement('style');
    style.id = 'shake-animation';
    style.textContent = `
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }
    `;
    document.head.appendChild(style);
}


/**
 * Show toast notification
 */
function showToast(message, type = 'info') {
    const toastId = 'toast-' + Date.now();
    const icons = {
        success: 'fa-check-circle',
        error: 'fa-exclamation-triangle',
        info: 'fa-info-circle',
        warning: 'fa-exclamation-circle'
    };
    const colors = {
        success: 'bg-success',
        error: 'bg-danger',
        info: 'bg-info',
        warning: 'bg-warning'
    };
    
    const toastHtml = `
        <div id="${toastId}" class="toast align-items-center text-white ${colors[type]} border-0" 
             role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas ${icons[type]} me-2"></i>
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" 
                        data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    `;
    
    let $container = $('.toast-container');
    if ($container.length === 0) {
        $container = $('<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;"></div>');
        $('body').append($container);
    }
    
    $container.append(toastHtml);
    
    const toastElement = document.getElementById(toastId);
    const toast = new bootstrap.Toast(toastElement, { autohide: true, delay: 4000 });
    toast.show();
    
    toastElement.addEventListener('hidden.bs.toast', function() {
        $(this).remove();
    });
}

/**
 * Add save indicator to show unsaved changes
 */
function addSaveIndicator() {
    let hasChanges = false;
    
    $('#settingsForm').find('input, textarea, select').on('change', function() {
        if (!hasChanges) {
            hasChanges = true;
            updateSaveButton(true);
        }
    });
    
    $('#settingsForm').on('submit', function() {
        hasChanges = false;
        updateSaveButton(false);
    });
    
    // Warn before leaving with unsaved changes
    window.addEventListener('beforeunload', function(e) {
        if (hasChanges) {
            e.preventDefault();
            e.returnValue = '';
            return '';
        }
    });
}

/**
 * Update save button appearance
 */
function updateSaveButton(hasChanges) {
    const $btn = $('.btn-save');
    if (hasChanges) {
        $btn.html('<i class="fas fa-save me-2"></i><span>Save Changes</span> <span class="badge bg-warning ms-2">●</span>');
    } else {
        $btn.html('<i class="fas fa-save me-2"></i><span>Save All Settings</span>');
    }
}

/**
 * Add keyboard shortcuts
 */
function addKeyboardShortcuts() {
    $(document).on('keydown', function(e) {
        // Ctrl/Cmd + S to save
        if ((e.ctrlKey || e.metaKey) && e.key === 's') {
            e.preventDefault();
            $('#settingsForm').submit();
            console.log('⌨ Keyboard shortcut: Save');
        }
        
        // Ctrl/Cmd + Number to switch tabs
        if ((e.altKey || e.metaKey) && e.key >= '1' && e.key <= '6') {
            e.preventDefault();
            const tabIndex = parseInt(e.key) - 1;
            const $tabs = $('#settingsTabs button');
            if ($tabs[tabIndex]) {
                $tabs[tabIndex].click();
                console.log('⌨ Keyboard shortcut: Switch to tab', e.key);
            }
        }
    });
    
    console.log('✅ Keyboard shortcuts enabled (Ctrl+S to save, Ctrl+1-6 to switch tabs)');
}

/**
 * Email validation
 */
function isValidEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

/**
 * URL validation
 */
function isValidUrl(url) {
    try {
        new URL(url);
        return true;
    } catch (e) {
        return false;
    }
}

// Add ripple animation CSS
if (!document.getElementById('ripple-animation')) {
    const style = document.createElement('style');
    style.id = 'ripple-animation';
    style.textContent = `
        @keyframes ripple {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(style);
}

console.log('✨ All settings page features initialized');


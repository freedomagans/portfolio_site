/**
 * Comment Form Submission with Toast Notifications
 * Fixed version with proper jQuery initialization
 */

// Wrap everything in document ready to ensure jQuery and Bootstrap are loaded
$(document).ready(function() {
    console.log('Comments.js loaded successfully');
    
    // Check if Bootstrap is available
    if (typeof bootstrap === 'undefined') {
        console.error('Bootstrap is not loaded! Toast notifications will not work.');
    }
    
    /**
     * Character counter for comment textarea
     */
    const $contentTextarea = $('#content');
    const $charCount = $('#charCount');
    const maxLength = 1000;
    
    if ($contentTextarea.length && $charCount.length) {
        $contentTextarea.on('input', function() {
            const length = $(this).val().length;
            $charCount.text(length);
            
            // Update counter styling based on length
            $charCount.removeClass('warning danger');
            if (length > maxLength * 0.9) {
                $charCount.addClass('danger');
            } else if (length > maxLength * 0.8) {
                $charCount.addClass('warning');
            }
        });
        
        // Initialize counter on page load
        $charCount.text($contentTextarea.val().length);
    }
    
    /**
     * Comment form submission handler
     */
    const $commentForm = $('#commentForm');
    
    if ($commentForm.length === 0) {
        console.log('Comment form not found on this page');
        return;
    }
    
    $commentForm.on('submit', function(e) {
        e.preventDefault();
        console.log('Form submitted - processing...');
        
        const $form = $(this);
        const $submitBtn = $('#submitBtn');
        const $submitBtnIcon = $submitBtn.find('i');
        const $submitBtnText = $submitBtn.find('span');
        
        // Get form data
        const formData = new FormData(this);
        
        // Basic client-side validation
        const name = formData.get('name').trim();
        const content = formData.get('content').trim();
        
        if (!name || name.length === 0) {
            showToast('Please enter your name', 'error');
            return;
        }
        
        if (!content || content.length < 10) {
            showToast('Comment must be at least 10 characters long', 'error');
            return;
        }
        
        if (content.length > 1000) {
            showToast('Comment is too long (max 1000 characters)', 'error');
            return;
        }
        
        // Disable form during submission
        $form.find('input, textarea, button').prop('disabled', true);
        $submitBtn.addClass('loading');
        $submitBtnIcon.removeClass('fa-paper-plane').addClass('fa-spinner fa-spin');
        $submitBtnText.text('Submitting...');
        
        console.log('Sending AJAX request to:', $form.attr('action'));
        
        // Submit via AJAX
        $.ajax({
            url: $form.attr('action'),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response) {
                console.log('AJAX Success:', response);
                
                if (response.status === 'success') {
                    // Show success toast
                    showToast(response.message, 'success');
                    
                    // Reset form
                    $form[0].reset();
                    $charCount.text('0').removeClass('warning danger');
                    
                    // Scroll to comments section smoothly
                    $('html, body').animate({
                        scrollTop: $('#comments-section').offset().top - 100
                    }, 500);
                    
                } else {
                    // Show error toast
                    let errorMessage = response.message || 'An error occurred';
                    
                    if (response.errors && Array.isArray(response.errors)) {
                        errorMessage = response.errors.join(', ');
                    }
                    
                    showToast(errorMessage, 'error');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', {
                    status: status,
                    error: error,
                    response: xhr.responseText
                });
                
                // Try to parse error response
                let errorMessage = 'An error occurred while submitting your comment. Please try again.';
                
                try {
                    const errorResponse = JSON.parse(xhr.responseText);
                    if (errorResponse.message) {
                        errorMessage = errorResponse.message;
                    }
                } catch (e) {
                    console.error('Could not parse error response');
                }
                
                showToast(errorMessage, 'error');
            },
            complete: function() {
                console.log('AJAX request completed');
                
                // Re-enable form
                $form.find('input, textarea, button').prop('disabled', false);
                $submitBtn.removeClass('loading');
                $submitBtnIcon.removeClass('fa-spinner fa-spin').addClass('fa-paper-plane');
                $submitBtnText.text('Submit Comment');
            }
        });
    });
});

/**
 * Toast notification function
 * Creates and displays Bootstrap toast notifications
 */
function showToast(message, type = 'info') {
    console.log('Showing toast:', type, message);
    
    // Get or create toast container
    let $toastContainer = $('#toastContainer');
    if ($toastContainer.length === 0) {
        $toastContainer = $('<div>', {
            id: 'toastContainer',
            class: 'toast-container position-fixed top-0 end-0 p-3'
        }).css('z-index', 9999);
        $('body').append($toastContainer);
    }
    
    // Generate unique toast ID
    const toastId = 'toast-' + Date.now();
    
    // Determine icon and background class based on type
    const config = {
        success: {
            icon: 'fa-check-circle',
            bg: 'bg-success'
        },
        error: {
            icon: 'fa-exclamation-triangle',
            bg: 'bg-danger'
        },
        info: {
            icon: 'fa-info-circle',
            bg: 'bg-info'
        },
        warning: {
            icon: 'fa-exclamation-circle',
            bg: 'bg-warning'
        }
    };
    
    const toastConfig = config[type] || config.info;
    
    // Create toast HTML
    const toastHtml = `
        <div id="${toastId}" class="toast align-items-center text-white ${toastConfig.bg} border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas ${toastConfig.icon} me-2"></i>
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    `;
    
    // Add toast to container
    $toastContainer.append(toastHtml);
    
    // Initialize and show toast
    const toastElement = document.getElementById(toastId);
    
    if (typeof bootstrap !== 'undefined' && bootstrap.Toast) {
        const toast = new bootstrap.Toast(toastElement, {
            autohide: true,
            delay: 5000
        });
        
        toast.show();
        
        // Remove toast from DOM after it's hidden
        toastElement.addEventListener('hidden.bs.toast', function() {
            $(this).remove();
        });
    } else {
        console.error('Bootstrap Toast not available');
        // Fallback: show as alert
        alert(message);
        $(toastElement).remove();
    }
}


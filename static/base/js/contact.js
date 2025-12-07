

// Contact Form JavaScript
document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.querySelector('form[action*="process_messages"]');
    const submitBtn = contactForm.querySelector('.btn-submit');
    const submitBtnText = submitBtn.innerHTML;

    // Create alert container for dynamic messages
    const alertContainer = document.createElement('div');
    alertContainer.className = 'ajax-alert-container';
    contactForm.insertBefore(alertContainer, contactForm.firstChild);

    // Helper function to show alerts
    function showAlert(type, message) {
        const alertClass = type === 'success' ? 'alert-success-custom' : 'alert-error-custom';
        const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle';

        alertContainer.innerHTML = `
        <div class="alert-custom ${alertClass}" role="alert">
            <i class="fas ${icon}"></i>
            <span>${message}</span>
        </div>
    `;

        // Scroll to alert
        alertContainer.scrollIntoView({
            behavior: 'smooth',
            block: 'center'
        });

        // Auto-dismiss success messages after 5 seconds
        if (type === 'success') {
            setTimeout(() => {
                alertContainer.innerHTML = '';
            }, 5000);
        }
    }

    // Helper function to set button loading state
    function setButtonLoading(isLoading) {
        if (isLoading) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
            <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
            Sending...
        `;
        } else {
            submitBtn.disabled = false;
            submitBtn.innerHTML = submitBtnText;
        }
    }

    // Form submission handler
    contactForm.addEventListener('submit', function(e) {
        e.preventDefault();

        // Clear previous alerts
        alertContainer.innerHTML = '';

        // Get form data
        const formData = new FormData(contactForm);

        // Client-side validation
        const name = formData.get('name').trim();
        const email = formData.get('email').trim();
        const subject = formData.get('subject').trim();
        const message = formData.get('message').trim();

        // Validate required fields
        if (!name || !email || !subject || !message) {
            showAlert('error', 'All fields are required.');
            return;
        }

        // Validate email format
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            showAlert('error', 'Please enter a valid email address.');
            return;
        }

        // Validate lengths
        if (name.length < 2 || name.length > 100) {
            showAlert('error', 'Name must be between 2 and 100 characters.');
            return;
        }

        if (subject.length < 3 || subject.length > 200) {
            showAlert('error', 'Subject must be between 3 and 200 characters.');
            return;
        }

        if (message.length < 10 || message.length > 5000) {
            showAlert('error', 'Message must be between 10 and 5000 characters.');
            return;
        }

        // Set loading state
        setButtonLoading(true);

        // Send AJAX request
        fetch('/urls.php?pg=process_messages', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                return response.json();
            })
            .then(data => {
                setButtonLoading(false);

                if (data.success) {
                    showAlert('success', data.message);
                    contactForm.reset(); // Clear form on success
                } else {
                    showAlert('error', data.message);
                }
            })
            .catch(error => {
                setButtonLoading(false);
                console.error('Form submission error:', error);
                showAlert('error', 'An unexpected error occurred. Please try again later.');
            });
    });

    // Real-time field validation (optional enhancement)
    const emailInput = contactForm.querySelector('#email');
    const nameInput = contactForm.querySelector('#name');

    emailInput.addEventListener('blur', function() {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (this.value && !emailRegex.test(this.value)) {
            this.classList.add('is-invalid');
        } else {
            this.classList.remove('is-invalid');
        }
    });

    nameInput.addEventListener('blur', function() {
        if (this.value && (this.value.length < 2 || this.value.length > 100)) {
            this.classList.add('is-invalid');
        } else {
            this.classList.remove('is-invalid');
        }
    });
});

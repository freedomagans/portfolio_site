document.addEventListener('DOMContentLoaded', function() {
        const viewModalEl = document.getElementById('viewNotificationModal');
        const viewModal = new bootstrap.Modal(viewModalEl);
        const viewEmailEl = document.getElementById('viewNotificationEmail');
        const viewSubjectEl = document.getElementById('viewNotificationSubject');
        const viewMessageEl = document.getElementById('viewNotificationMessage');
        const overlayDim = document.querySelector('.page-overlay-dim');

        // helper: escape HTML for safe output then re-insert newlines
        function escapeHtml(str) {
            if (typeof str !== 'string') return '';
            const div = document.createElement('div');
            div.appendChild(document.createTextNode(str));
            return div.innerHTML;
        }

        function nl2brSafe(str) {
            return escapeHtml(str).replace(/\r\n|\r|\n/g, '<br>');
        }

        // Click handler for view buttons
        document.querySelectorAll('.view-notification-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const name = btn.dataset.name || 'Notification';
                const email = btn.dataset.email || '';
                const subject = btn.dataset.subject || '';
                const messageBase64 = btn.dataset.message || '';
                let message = '';
                // Decode base64 safely
                try {
                    message = messageBase64 ? atob(messageBase64) : '';
                } catch (e) {
                    message = '';
                }

                // Populate modal
                viewModalEl.querySelector('.modal-title').textContent = 'Message for ' + name;
                viewEmailEl.textContent = email;
                viewSubjectEl.textContent = subject;
                viewMessageEl.innerHTML = nl2brSafe(message);

                // Show overlay dim (visual only; does not stop clicks beyond default backdrop)
                overlayDim?.classList.add('d-block');

                // Show modal
                viewModal.show();
            });
        });

        // Clean up after modal hidden
        viewModalEl.addEventListener('hidden.bs.modal', function() {
            // Remove overlay dim
            overlayDim?.classList.remove('d-block');

            // Slight delay and remove leftover backdrops if any
            setTimeout(() => {
                // If no other modal is open, tidy up stray backdrops and classes
                if (!document.querySelector('.modal.show')) {
                    // Remove stray backdrops
                    document.querySelectorAll('.modal-backdrop').forEach(b => b.remove());
                    // Ensure bootstrap modal-open class removed if needed
                    document.body.classList.remove('modal-open');
                }
            }, 50);
        });

        // If any stray backdrop remains (rare), click on it should remove it
        document.addEventListener('click', function() {
            if (!document.querySelector('.modal.show')) {
                document.querySelectorAll('.modal-backdrop').forEach(b => {
                    b.remove();
                });
            }
        });

        // Accessibility fallback: ESC hide fallback (Bootstrap handles this but keep as defensive)
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const openModal = document.querySelector('.modal.show');
                if (openModal) {
                    const instance = bootstrap.Modal.getInstance(openModal);
                    if (instance) instance.hide();
                }
            }
        });
    });
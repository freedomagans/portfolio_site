document.addEventListener('DOMContentLoaded', function() {
    const modalEl = document.getElementById('viewCommentModal');
    const modal = new bootstrap.Modal(modalEl);

    const projectEl = document.getElementById('cProject');
    const messageEl = document.getElementById('cMessage');
    const overlay = document.querySelector('.page-overlay-dim');

    function escape(str) {
        const x = document.createElement('div');
        x.appendChild(document.createTextNode(str));
        return x.innerHTML;
    }

    document.querySelectorAll('.view-comment-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const project = btn.dataset.project || '';
            const encoded = btn.dataset.comment || '';

            let message = '';
            try {
                message = encoded ? atob(encoded) : '';
            } catch (e) {
                message = '';
            }

            modalEl.querySelector('.modal-title').textContent =
                'Comment by ' + (btn.dataset.name || 'Anonymous');

            projectEl.textContent = project;
            messageEl.innerHTML = escape(message).replace(/\n/g, '<br>');

            overlay?.classList.add('d-block');
            modal.show();
        });
    });

    modalEl.addEventListener('hidden.bs.modal', () => {
        overlay?.classList.remove('d-block');
        setTimeout(() => {
            if (!document.querySelector('.modal.show')) {
                document.querySelectorAll('.modal-backdrop').forEach(b => b.remove());
                document.body.classList.remove('modal-open');
            }
        }, 50);
    });


 // If any stray backdrop remains (rare), click on it should remove it
document.addEventListener('click', function () {
    if (!document.querySelector('.modal.show')) {
        document.querySelectorAll('.modal-backdrop').forEach(b => {
            b.remove();
        });
    }
});

// Accessibility fallback: ESC hide fallback (Bootstrap handles this but keep as defensive)
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
        const openModal = document.querySelector('.modal.show');
        if (openModal) {
            const instance = bootstrap.Modal.getInstance(openModal);
            if (instance) instance.hide();
        }
    }
});

});


<?php
include ADMIN_TEMPLATE_PATH . "admin_header.php";
include ADMIN_TEMPLATE_PATH . "admin_navigation.php";

require_once __DIR__ . '/../../models/NotificationModel.php';
$notificationModel = new Notification();

// Pagination setup
$perPage = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $perPage;

// Filter by status
$statusFilter = $_GET['status'] ?? null;

// Total notifications for pagination
$totalNotifications = $notificationModel->countByStatus($statusFilter);
$totalPages = ($perPage > 0) ? (int)ceil($totalNotifications / $perPage) : 1;

// Fetch notifications based on filter and pagination
$notifications = $notificationModel->getPaginatedByStatus($start, $perPage, $statusFilter);

// Session feedback messages
msg_error();
msg_success();
?>

<link rel="stylesheet" href="/static/admin/css/notifications.css">

<h2 class="mb-4">Notifications</h2>
<div class="d-flex justify-content-between align-items-center mb-3">

    <!-- Filter form -->
    <div>
        <form method="GET" class="d-inline" aria-label="Filter notifications">
            <input type="hidden" name="pg" value="notification_all">
            <select name="status" class="form-select form-select-sm d-inline w-auto" onchange="this.form.submit()" aria-label="Notification status">
                <option value="">All</option>
                <option value="unread" <?= ($statusFilter === 'unread') ? 'selected' : ''; ?>>Unread</option>
                <option value="read" <?= ($statusFilter === 'read') ? 'selected' : ''; ?>>Read</option>
            </select>
        </form>
    </div>

    <div>
        <button class="btn btn-sm btn-danger" onclick="triggerDeleteModal('/urls.php?pg=notification_delete&all=true','Delete Notification','Are you sure you want to delete all notifications?')">
            Delete all
        </button>
    </div>
</div>

<!-- Page dim element for subtle style when modal is open -->
<div class="page-overlay-dim" aria-hidden="true"></div>

<!-- Table showing list of notifications -->
<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Subject</th>
                <th>Message</th>
                <th>Date</th>
                <th>Status</th>
                <th colspan="2">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($notifications)) : ?>
                <?php foreach ($notifications as $notification) :
                    $id = (int)$notification['id'];
                    $nameAttr = htmlspecialchars($notification['name'], ENT_QUOTES);
                    $emailAttr = htmlspecialchars($notification['email'], ENT_QUOTES);
                    $subjectAttr = htmlspecialchars($notification['subject'], ENT_QUOTES);
                    // Use base64 encoding for the message to avoid attribute escaping issues; decode in JS.
                    $messageEncoded = base64_encode($notification['message']);
                ?>
                    <tr>
                        <td><?= htmlspecialchars($notification['name']); ?></td>
                        <td><?= htmlspecialchars($notification['email']); ?></td>
                        <td><?= htmlspecialchars($notification['subject']); ?></td>
                        <td><?= htmlspecialchars(mb_strimwidth($notification['message'], 0, 50, '...')); ?></td>
                        <td><?= date('d M Y, H:i', strtotime($notification['created_at'])); ?></td>
                        <td>
                            <?php if (empty($notification['is_read']) || $notification['is_read'] == 0) : ?>
                                <a href="/urls.php?pg=notification_mark_as_read&id=<?= $id ?>" class="btn btn-sm btn-primary">read</a>
                            <?php else : ?>
                                <span class="btn btn-sm bg-success text-white">seen</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <!-- View button: uses a single reusable modal; pass data-* attributes -->
                            <button type="button"
                                    class="btn btn-sm btn-primary view-notification-btn"
                                    data-notification-id="<?= $id; ?>"
                                    data-name="<?= $nameAttr; ?>"
                                    data-email="<?= $emailAttr; ?>"
                                    data-subject="<?= $subjectAttr; ?>"
                                    data-message="<?= $messageEncoded; ?>">
                                View
                            </button>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-danger"
                                    onclick="triggerDeleteModal('/urls.php?pg=notification_delete&id=<?= $id ?>',
                                    'Delete Notification',
                                    'Are you sure you want to delete this notification')">
                                Delete
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="8" class="text-center">No notifications found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Pagination -->
    <?php if ($totalPages > 1) : ?>
        <nav aria-label="Notifications pagination">
            <ul class="pagination justify-content-center">
                <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                    <li class="page-item <?= ($i == $page) ? 'active' : ''; ?>">
                        <a class="page-link" href="?pg=notification_all&page=<?= $i; ?><?= $statusFilter ? '&status=' . urlencode($statusFilter) : ''; ?>"><?= $i; ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    <?php endif; ?>
</div>

<!-- REUSABLE VIEW MODAL (single instance, avoid leftover backdrops & stacking problems) -->
<div class="modal fade" id="viewNotificationModal" tabindex="-1" aria-labelledby="viewNotificationModalLabel" aria-hidden="true" data-bs-backdrop="true" data-bs-keyboard="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewNotificationModalLabel">Message</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-1"><strong>Email:</strong> <span id="viewNotificationEmail"></span></p>
                <p class="mb-1"><strong>Subject:</strong> <span id="viewNotificationSubject"></span></p>
                <hr>
                <div id="viewNotificationMessage" class="whitespace-pre-wrap"></div>
            </div>
            <div class="modal-footer">
                <!-- optional mark-as-read link (keeps original existing action separate) -->
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- JS: populate & show single modal, robust cleanup for stray backdrops -->
<script>
document.addEventListener('DOMContentLoaded', function () {
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
        btn.addEventListener('click', function () {
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
    viewModalEl.addEventListener('hidden.bs.modal', function () {
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
</script>

<?php include ADMIN_TEMPLATE_PATH . "admin_footer.php"; ?>
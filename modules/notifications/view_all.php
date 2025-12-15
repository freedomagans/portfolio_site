<?php
include ADMIN_TEMPLATE_PATH . "admin_header.php"; // admin header file
include ADMIN_TEMPLATE_PATH . "admin_navigation.php"; // admin navigation  file

require_once __DIR__ . '/../../models/NotificationModel.php'; // import Notification model
$notificationModel = new Notification(); // notification instance

// Pagination setup
$perPage = ADMIN_ITEMS_PER_PAGE; // perpage constant
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $perPage;

// Filter by status
$statusFilter = $_GET['status'] ?? null;

// Total notifications for pagination
if ($statusFilter !== null and $statusFilter !== '') {
    $status = ($statusFilter === 'read' ? 1 : 0);
    $totalNotifications = $notificationModel->countByStatus($status);
    $notifications = $notificationModel->getPaginatedByStatus($status, $start, $perPage);
} else {
    $totalNotifications = $notificationModel->count();
    $notifications = $notificationModel->getPaginatedAll($start, $perPage);
}


$totalPages = (int)ceil($totalNotifications / $perPage); // total pages calculation



// Session feedback messages
msg_error(); // error msg
msg_success(); // success msg
?>
<!-- external notifications css -->
<link rel="stylesheet" href="/static/admin/css/notifications.css">

<!-- header -->
<h2 class="mb-4">Notifications</h2>

<!-- Filter form and action buttons div -->
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
    <!-- Filter form end -->

    <!-- Action buttons -->
    <div>
        <button class="btn btn-sm btn-danger" onclick="triggerDeleteModal('/urls.php?pg=notification_delete&all=true','Delete Notification','Are you sure you want to delete all notifications?')">
            Delete all
        </button>
    </div>

    <div>
        <button class="btn btn-sm btn-primary" onclick="window.location.href='/urls.php?pg=notification_mark_as_read&all=true'">
            Mark all as read
        </button>
    </div>
    <!-- Action buttons end -->

</div>
<!-- Top filter and action buttons end -->

<!-- Page dim element for subtle style when modal is open -->
<div class="page-overlay-dim" aria-hidden="true"></div>

<!-- Table div showing list of notifications -->
<div class="table-responsive">
    <!-- Table -->
    <table class="table table-striped table-hover">

        <!-- Table head -->
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

        <!-- Table body -->
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
    <!-- Table end -->

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
    <!-- Pagination end -->

</div>
<!-- Table div end -->

<!-- REUSABLE VIEW MODAL (single instance, avoid leftover backdrops & stacking problems) notification detail is populated into modal-->
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
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- JS: populate & show single modal, robust cleanup for stray backdrops -->
<script src="/static/admin/js/notifications.js"></script>  

<?php include ADMIN_TEMPLATE_PATH . "admin_footer.php"; // admin footer file ?>
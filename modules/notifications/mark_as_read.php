<?php

require_once __DIR__ . '/../../models/NotificationModel.php'; // import Notification db model class
$notificationModel = new Notification(); // notification instance

/**
 * Mark Notifications as read based on GET parameters 'id' or 'all'.
 */
if ($notificationModel->count() != 0) {
    /**
     * if 'id' parameter is passed to page a row of the Notification table
     * with that id is marked as read;
     */
    if (isset($_GET['id'])) {
        if ($notificationModel->markAsRead($_GET['id'])) {
            $_SESSION['success'] = "Notification was marked read successfully."; // success msg
        } else {
            $_SESSION['error'] = "Failed to read notification."; // error msg
        }
    }

    /**
     * if 'all' parameter is passed to page all rows of the Notification table
     * are marked as read;
     */
    if (isset($_GET['all'])) {
        if ($notificationModel->markAllAsRead()) {
            $_SESSION['success'] = "All notifications marked as read."; // success msg
        } else {
            $_SESSION['error'] = "Failed to mark notifications as read."; // error msg
        }
    }
} else {
    $_SESSION['error'] = "No notifications found."; // error msg
}
header('Location: /urls.php?pg=notification_all'); // redirect to notifications page;
exit;

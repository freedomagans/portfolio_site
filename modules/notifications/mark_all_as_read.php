<?php
require_once __DIR__.'/../../models/NotificationModel.php'; // import Notification db model class

$notification = new Notification(); // notification instance

/**
 * mark all notification instances as Read and sets feedback msgs;
 */
if ($notification->markAllAsRead()) {
    $_SESSION['success'] = "All notifications marked as read."; // success msg
} else {
    $_SESSION['error'] = "Failed to mark notifications as read."; // error msg
}

header('Location: /urls.php?pg=notification_all'); // redirect to notifications page
exit;
<?php

require_once __DIR__ . '/../../models/NotificationModel.php'; // import Notification db model class

if (isset($_GET['id'])) {
    /**
     * if 'id' parameter is passed to page a row of the Notification table
     * with that id is marked as read;
     */

    $notificationModel = new Notification(); // notification instance
    if ($notificationModel->markAsRead($_GET['id'])) {
        $_SESSION['success'] = "Notification was marked read successfully."; // success msg
    } else {
        $_SESSION['error'] = "Failed to read notification."; // error msg
    }
}

header('Location: /urls.php?pg=notification_all'); // redirect to notifications page;
exit;
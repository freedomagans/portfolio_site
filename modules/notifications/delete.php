<?php
require_once __DIR__ . '/../../models/NotificationModel.php'; // import Notification db Model class

$notificationModel = new Notification(); // notification instance

if($notificationModel->count() != 0)
{

if (isset($_GET['id'])) {
    /**
     * if an id parameter is passed to page a row in the Notification 
     * table with that id is deleted.
     */

   
    if ($notificationModel->delete($_GET['id'])) {
        $_SESSION['success'] = "Notification deleted successfully."; // success msg
    } else {
        $_SESSION['error'] = "Failed to delete notification."; // error msg
    }
}


if (isset($_GET['all']) && $_GET['all'] === 'true') {
    /**
     * if an 'all' parameter is passed to page and its value is true 
     * all rows in the Notifications table is deleted;
     */

    if ($notificationModel->delete_all()) {
        $_SESSION['success'] = "All Notifications deleted successfully."; // success msg
    } else {
        $_SESSION['error'] = 'Failed to delete notifications.'; // error msg
    }
}

}
else{
    $_SESSION['error'] = 'No notifications found.';
}


header('Location: /urls.php?pg=notification_all'); // redirect to notifications page;
exit;

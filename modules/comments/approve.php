<?php
require_once __DIR__ . '/../../models/CommentModel.php'; // import Comment db Model class

$commentModel = new Comment(); // comment instance

if ($commentModel->count() != 0) {

    // Single approve/disapprove
    if (isset($_GET['id']) && isset($_GET['action'])) {
        $id = intval($_GET['id']);
        $action = $_GET['action'];

        if ($action === 'approve') {
            if ($commentModel->approve($id)) {
                $_SESSION['success'] = "Comment approved successfully.";
            } else {
                $_SESSION['error'] = "Failed to approve comment.";
            }
        } elseif ($action === 'disapprove') {
            if ($commentModel->disapprove($id)) {
                $_SESSION['success'] = "Comment disapproved successfully.";
            } else {
                $_SESSION['error'] = "Failed to disapprove comment.";
            }
        } else {
            $_SESSION['error'] = "Invalid action specified.";
        }
    }

    // Bulk approve/disapprove
    if (isset($_GET['all'])) {
        $action = $_GET['all'];

        if ($action === 'approve') {
            if ($commentModel->approveAll()) {
                $_SESSION['success'] = "All comments approved successfully.";
            } else {
                $_SESSION['error'] = "Failed to approve all comments.";
            }
        } elseif ($action === 'disapprove') {
            if ($commentModel->disapproveAll()) {
                $_SESSION['success'] = "All comments disapproved successfully.";
            } else {
                $_SESSION['error'] = "Failed to disapprove all comments.";
            }
        } else {
            $_SESSION['error'] = "Invalid bulk action specified.";
        }
    }

} else {
    $_SESSION['error'] = 'No comments found.';
}

// redirect to comments page
header('Location: /urls.php?pg=comment_all');
exit;
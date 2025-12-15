<?php
require_once __DIR__ . '/../../models/CommentModel.php'; // import Comment db Model class

$commentModel = new Comment(); // comment Model instance

/**
 * delete single or all comments based on 
 * GET parameters 'id' or 'all' .
 */
if ($commentModel->count() != 0) {

    if (isset($_GET['id'])) {
        /**
         * if an id parameter is passed to page a row in the Comments 
         * table with that id is deleted.
         */
        if ($commentModel->delete($_GET['id'])) {
            $_SESSION['success'] = "Comment deleted successfully."; // success msg
        } else {
            $_SESSION['error'] = "Failed to delete comment."; // error msg
        }
    }

    if (isset($_GET['all']) && $_GET['all'] === 'true') {
        /**
         * if an 'all' parameter is passed to page and its value is true 
         * all rows in the Comments table are deleted;
         */
        if ($commentModel->delete_all()) {
            $_SESSION['success'] = "All comments deleted successfully."; // success msg
        } else {
            $_SESSION['error'] = 'Failed to delete comments.'; // error msg
        }
    }

} else {
    $_SESSION['error'] = 'No comments found.'; // error msg feedback
}

// redirect to comments page
header('Location: /urls.php?pg=comment_all');
exit;
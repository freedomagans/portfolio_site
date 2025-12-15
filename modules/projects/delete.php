<?php
require_once __DIR__ . '/../../models/ProjectModel.php'; // import Project db model class
$projectModel = new Project(); //Project instance

/**
 * Delete Projects based on GET parameters 'id' or 'all'
 */
if ($projectModel->count() != 0) {

    /**
     * if 'id' parameter is passed to the page the row of projects
     * table with the id is deleted
     */
    if (isset($_GET['id'])) {
        if ($projectModel->delete($_GET['id'])) {
            $_SESSION['success'] = "Project deleted successfully."; // success msg
        } else {
            $_SESSION['error'] = "Failed to delete Project."; // error msg
        }
    }

    /**
     * if 'all' parameter is passed to the page then all rows 
     * of the projects table is deleted;
     */
    if (isset($_GET['all']) && $_GET['all'] === 'true') {
        if ($projectModel->delete_all()) {
            $_SESSION['success'] = "All Projects deleted successfully."; // success msg
        } else {
            $_SESSION['error'] = 'Failed to delete Projects.'; // error msg
        }
    }
} else {
    $_SESSION['error'] = 'No projects found';
}

header('Location: /urls.php?pg=project_all'); // redirect to projects page;
exit;

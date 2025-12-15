<?php
require_once __DIR__ . '/../../models/ProjectModel.php'; // import Project db model class
$projectModel = new Project(); // project instance

if (isset($_GET['id'])) {
    $id = $_GET['id']; // retrieve 'id' get parameter for project row;

    /**
     * on submission of project edit form to this page 
     * submitted values are cleaned and used to update the 
     * row of projects table with the specified GET parameter 'id';
     */
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // cleansing submitted values
        $title = trim($_POST['title']);
        $description = trim($_POST['description']);
        $live_url = trim($_POST['live_url']);
        $github_url = trim($_POST['github_url']);
        $is_published = isset($_POST['is_published']) ? 1 : 0;
        $slug = strtolower(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)); // Generate slug from title

        $uploadsDir = __DIR__ . '/../../media/projects/'; // media uploads directory

        // Fetch project
        $project = $projectModel->getById($id);
        if (!$project) {
            $_SESSION['error'] = "Project not found!"; // error msg
            header("Location: /urls.php?pg=project_all"); // redirect to projects page 
            exit;
        }

        // retrieve already set images
        $image1 = $project['image1'];
        $image2 = $project['image2'];
        $image3 = $project['image3'];

        /**
         * uploads image1 to uploads folder(media) using the 
         * move_uploaded_file() method;
         */
        if (isset($_FILES['image1']) && $_FILES['image1']['error'] === UPLOAD_ERR_OK) {
            $image1 = time() . '_' . basename($_FILES['image1']['name']);
            move_uploaded_file($_FILES['image1']['tmp_name'], $uploadsDir . $image1);
        }

        /**
         * uploads image2 to uploads folder(media) using the 
         * move_uploaded_file() method;
         */
        if (isset($_FILES['image2']) && $_FILES['image2']['error'] === UPLOAD_ERR_OK) {
            $image2 = time() . '_' . basename($_FILES['image2']['name']);
            move_uploaded_file($_FILES['image2']['tmp_name'], $uploadsDir . $image2);
        }

        /**
         * uploads image3 to uploads folder(media) using the 
         * move_uploaded_file() method;
         */
        if (isset($_FILES['image3']) && $_FILES['image3']['error'] === UPLOAD_ERR_OK) {
            $image3 = time() . '_' . basename($_FILES['image3']['name']);
            move_uploaded_file($_FILES['image3']['tmp_name'], $uploadsDir . $image3);
        }

        // Update project row 
        $updated = $projectModel->update($id, $title, $slug, $description, $image1, $image2, $image3, $live_url, $github_url, $is_published);

        // if updated return feedback msgs
        if ($updated) {
            $_SESSION['success'] = "Project updated successfully!"; // success msg
            header("Location: /urls.php?pg=project_all"); // redirect to projects page
            exit;
        } else {
            $_SESSION['error'] = "Failed to update project."; // error msg
            header('Location: /urls.php?pg=project_all'); // redirect to projects page
        }
    }
} else {
    $_SESSION['error'] = "Project ID not specified"; // error msg
    header('Location: /urls.php?pg=project_all'); // redirect to project view all page
}

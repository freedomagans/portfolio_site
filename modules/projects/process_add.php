<?php
require_once __DIR__ . '/../../models/ProjectModel.php'; // import project db model class 
$projectModel = new Project(); // project instance

/**
 * confirms form is submitted with POST method
 * on submission of add form 
 * values are cleansed and stored in database;
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // triming and cleansing posted form values;
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $live_url = trim($_POST['live_url']);
    $github_url = trim($_POST['github_url']);
    $is_published = isset($_POST['is_published']) ? 1 : 0;

    // Generate slug from title
    $slug = strtolower(preg_replace('/[^A-Za-z0-9-]+/', '-', $title));

    // setting upload directory of images
    $uploadsDir = __DIR__ . '/../../media/projects/';
    if (!is_dir($uploadsDir)) {
        mkdir($uploadsDir, 0777, true); // create directory if directory don't exist;
    }

    $image1 = $image2 = $image3 = null; // nullify image variables

    /**
     * uploads image1 to uploads folder(media) using the 
     * move_uploaded_file() method;
     */
    if (isset($_FILES['image1']) && $_FILES['image1']['error'] === UPLOAD_ERR_OK) {
        $image1 = time() . '_' . basename($_FILES['image1']['name']);
        move_uploaded_file($_FILES['image1']['tmp_name'], $uploadsDir . $image1);
    } else {
        $_SESSION['error'] = "Image 1 is required!"; // error msg
        header("Location: ?pg=project_add"); // reload page
        exit;
    }

    /**
     * uploads image2 to uploads folder(media) using the move_uploaded_file() method;
     */
    if (isset($_FILES['image2']) && $_FILES['image2']['error'] === UPLOAD_ERR_OK) {
        $image2 = time() . '_' . basename($_FILES['image2']['name']);
        move_uploaded_file($_FILES['image2']['tmp_name'], $uploadsDir . $image2);
    }

    /**
     * uploads image3 to uploads folder(media) using the move_uploaded_file() method;
     */
    if (isset($_FILES['image3']) && $_FILES['image3']['error'] === UPLOAD_ERR_OK) {
        $image3 = time() . '_' . basename($_FILES['image3']['name']);
        move_uploaded_file($_FILES['image3']['tmp_name'], $uploadsDir . $image3);
    }

    // Insert into database using the create() method;
    $created = $projectModel->create($title, $slug, $description, $image1, $image2, $image3, $live_url, $github_url, $is_published);

    // if project row is created return feedback msgs 
    if ($created) {
        $_SESSION['success'] = "Project added successfully!"; // success msg
        header("Location: ?pg=project_all"); // redirect to projects page
        exit;
    } else {
        $_SESSION['error'] = "Failed to add project. Try again."; // error msg
        header('Location: /urls.php?pg=project_add'); // relaod page
    }
}

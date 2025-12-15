<?php
require_once __DIR__ . '/../../models/ProjectModel.php'; // import project db model class
$projectModel = new Project(); // project instance

/**
 * if 'id' GET parameter is passed to page 
 * the row of the projects table with the 
 * specified id is marked published;
 */
if (isset($_GET['id'])) {
    $projectModel->Publish($_GET['id']);
    header('Location: /urls.php?pg=project_all'); // redirect to projects page;
}

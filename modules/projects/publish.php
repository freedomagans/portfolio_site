<?php 
require_once __DIR__ . '/../../models/ProjectModel.php'; // import project db model class
$projectModel = new Project(); // project instance

if(isset($_GET['id']))
{
    /**
     * if 'id' get parameter is passed to page 
     * the row of the projects table with the 
     * specified id is marked published;
     */
    $projectModel->Publish($_GET['id']); 
    header('Location: /urls.php?pg=project_all'); // redirect to projects page;
}
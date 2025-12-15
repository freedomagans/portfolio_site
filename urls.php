
<?php

session_start(); // start session for site;

// import all commonly used modules
require_once __DIR__ . '/core/constants.php'; // import constants.php
require_once CONFIG_PATH . 'Database.php'; // import Database.php
require_once CORE_PATH . 'functions.php'; // import functions.php
require_once CONFIG_PATH . 'config.php'; // import config.php

// Define urls for faedinwebworks portfolio site
$urls = [
    // home urls
    'index' => __DIR__ . '/templates/frontend/index.php',
    'projects' => __DIR__ . '/templates/frontend/project_index.php',
    'view' => __DIR__ . '/templates/frontend/project_view.php',

    // contact urls
    'contact'  => __DIR__ . '/modules/contact/contact.php',
    'process_messages'  => __DIR__ . '/modules/contact/process_messages.php',

    // admin urls
    'admin' => __DIR__ . '/modules/admin/dashboard.php',
    'profile' => __DIR__ . '/modules/admin/profile.php',
    'profile_update' => __DIR__ . '/modules/admin/update_profile.php',
    

    // admin notification urls
    'notification_all' => __DIR__ . '/modules/notifications/view_all.php',
    'notification_delete' => __DIR__ . '/modules/notifications/delete.php',
    'notification_mark_as_read' => __DIR__ . '/modules/notifications/mark_as_read.php',


    // admin comment urls
    'comment_all' => __DIR__.'/modules/comments/view_all.php',
    'comment_delete' => __DIR__.'/modules/comments/delete.php',
    'comment_approve' => __DIR__.'/modules/comments/approve.php',
    'process_comments' => __DIR__.'/modules/comments/process_comments.php',

    // admin project urls
    
    'project' => __DIR__ . '/modules/projects/view.php',
    'project_all' => __DIR__ . '/modules/projects/view_all.php',
    'project_delete' => __DIR__ . '/modules/projects/delete.php',
    'project_add' => __DIR__ . '/modules/projects/add.php',
    'project_edit' => __DIR__ . '/modules/projects/edit.php',
    'project_publish' => __DIR__ . '/modules/projects/publish.php',
    'project_process_add' => __DIR__ . '/modules/projects/process_add.php',
    'project_process_edit' => __DIR__ . '/modules/projects/process_edit.php',
    'project_process_like' => __DIR__.'/modules/projects/process_like.php',

    // auth urls
    'login'  => __DIR__ . '/modules/auth/login.php',
    'logout' => __DIR__ . '/modules/auth/logout.php',

    // settings urls
    'settings' => __DIR__ . '/modules/settings/index.php',

];

// Get requested page if passed or home if page not passed
$page = $_GET['pg'] ?: 'index';

// Route to requested page(?pg=)
if (array_key_exists($page, $urls)) {
    include $urls[$page]; // include requested page
} else {
    //return not found feedback
    http_response_code(404);
    echo "404 Page not found.";
}

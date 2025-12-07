<?php
// constants.php - global project constants


//Root of project 
define('ROOT_PATH', __DIR__ . '/../');

//Common folders 
define('CONFIG_PATH', ROOT_PATH . 'config/');
define('CORE_PATH', ROOT_PATH . 'core/');
define('TEMPLATE_PATH', ROOT_PATH . 'templates/');
define('STATIC_PATH', ROOT_PATH . 'static/');
define('MODULE_PATH', ROOT_PATH . 'modules/');
define('MODELS_PATH', ROOT_PATH.'models/');

//Template subpaths
define('ADMIN_TEMPLATE_PATH', TEMPLATE_PATH . 'admin/');
define('FRONTEND_TEMPLATE_PATH', TEMPLATE_PATH . 'frontend/');

//URL base 
define('BASE_URL', '/');
define('ADMIN_BASE_URL', '/modules/admin/');

// frontend static urls
define('CSS_URL', 'static/base/css');
define('JS_URL', 'static/base/js');
define('IMG_URL', 'static/base/img');

//admin static urls
define('ADMIN_STATIC_URL', 'static/admin/');
define('ADMIN_CSS_URL', ADMIN_STATIC_URL.'css');
define('ADMIN_JS_URL', ADMIN_STATIC_URL.'js');
define('ADMIN_IMG_URL', ADMIN_STATIC_URL.'img');



?>


<?php

// ===============================================
// CONSTANTS.PHP - GLOBAL PROJECT CONSTANTS
// ===============================================

//

// Application Configuration
define('APP_NAME', 'FaedinWebworks Portfolio');
define('APP_URL', 'http://localhost/portfoliosite'); // Your website URL


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
define('BASE_URL', '/portfoliosite/');
define('ADMIN_BASE_URL', '/portfoliosite/modules/admin/');

// frontend static urls
define('CSS_URL', 'static/base/css');
define('JS_URL', 'static/base/js');
define('IMG_URL', 'static/base/img');

//admin static urls
define('ADMIN_STATIC_URL', 'static/admin/');
define('ADMIN_CSS_URL', ADMIN_STATIC_URL.'css');
define('ADMIN_JS_URL', ADMIN_STATIC_URL.'js');
define('ADMIN_IMG_URL', ADMIN_STATIC_URL.'img');

// Social Media Links (optional)
define('WHATSAPP_URL', 'https://wa.me/08168247299');
define('LINKEDIN_URL', 'https://linkedin.com/in/your-profile');
define('GITHUB_URL', 'https://github.com/freedomagans');
define('TWITTER_URL', 'https://twitter.com/your-handle');
define('INSTAGRAM_URL', 'https://instagram.com/your-handle');
define('EMAIL', 'freedomaganskest@gmail.com');


?>


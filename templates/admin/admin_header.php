<?php 
// Redirect to login page if not authenticated
if (!isset($_SESSION['username'])) {
    header("Location: /urls.php?pg=login");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    
      <?php
      require_once __DIR__ . '/../../core/Settings.php';
      $appSettings = AppSettings::getInstance();
      $siteTitle = $appSettings->get('site_title', 'FaedinWebworks');
      $siteDescription = $appSettings->get('site_description', 'Professional web development and automation solutions');
      $metaKeywords = $appSettings->get('meta_keywords', 'web development, portfolio, programming');
      $siteLogo = $appSettings->get('site_logo', '');
      ?>

      <title><?php echo htmlspecialchars($siteTitle); ?></title>
      <meta name="description" content="<?php echo htmlspecialchars($siteDescription); ?>">
      <meta name="keywords" content="<?php echo htmlspecialchars($metaKeywords); ?>">

      <!-- Favicon -->
      <?php if ($siteLogo): ?>
      <link rel="icon" type="image/x-icon" href="<?php echo htmlspecialchars($siteLogo); ?>">
      <?php else: ?>
      <link rel="icon" type="image/x-icon" href="favicon.ico">
      <?php endif; ?>
    <!-- ============================
         CORE STYLES (ORDER MATTERS)
    ============================= -->
    
    <!-- Bootstrap 5.2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" 
          integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    
    <!-- Font Awesome 6.5.0 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" 
          integrity="sha512-Avb2QiuDEEvB4bZJYdft2mNjVShBftLdPG8FJ0V7irTLQ8Uo0qcPxh4Plq7G5tGm0rU+1SPhVotteLpBERwTkw==" crossorigin="anonymous" referrerpolicy="no-referrer">
    
    
    <!-- ============================
         JAVASCRIPT LIBRARIES
    ============================= -->
    
    <!-- jQuery (for legacy admin widgets) -->
    <script src="/static/admin/js/jquery.js"></script>
    
    <!-- CKEditor 5 (Classic Build) -->
    <script src="https://cdn.ckeditor.com/ckeditor5/41.0.0/classic/ckeditor.js"></script>
    
</head>
<body class="admin-body">
    
    <!-- Container for entire admin layout -->
    <div class="admin-wrapper">
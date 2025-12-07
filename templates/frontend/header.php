<!DOCTYPE html>
<html lang="en">

<head>

      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">

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
         CORE CSS (ORDER MATTERS)
    ============================= -->
      <!-- Bootstrap 5.2 CSS -->
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">

      <!-- Font Awesome 6.5.0 -->
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


      

</head>

<body>

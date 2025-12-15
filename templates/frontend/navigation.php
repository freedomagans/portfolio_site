<nav class="navbar navbar-expand-lg navbar-dark bg-black fixed-top">
  <div class="container">

    <!-- Brand -->
    <a class="navbar-brand fs-6" href="/urls.php?pg=index">
      <img src="<?php
                require_once __DIR__ . '/../../core/Settings.php'; // import settings
                $appSettings = AppSettings::getInstance(); // get settings instance
                $siteLogo = $appSettings->get('site_logo', ''); // get site logo from settings or default
                echo htmlspecialchars($siteLogo) ?>" alt="Icon" width="20" height="20"
        class="d-inline-block align-text-top me-1 "><?php
                                                    require_once __DIR__ . '/../../core/Settings.php'; // import settings 
                                                    $appSettings = AppSettings::getInstance(); // get settings instance
                                                    $siteTitle = $appSettings->get('site_title', 'FaedinWebworks'); // get site_title settings 
                                                    echo htmlspecialchars($siteTitle) ?> </a>

    <!-- Toggler -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Nav Links -->
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">

        <?php if (isset($_SESSION['username'])): ?>
          <li class="nav-item">
            <a class="nav-link" href="/urls.php?pg=admin">Admin</a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="/urls.php?pg=projects">Projects</a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="/urls.php?pg=logout">Logout</a>
          </li>
        <?php else: ?>
          <li class="nav-item">
            <a class="nav-link" href="/urls.php?pg=login">Admin</a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="/urls.php?pg=projects">Projects</a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="/urls.php?pg=contact">Contact</a>
          </li>
        <?php endif; ?>

      </ul>
    </div>
    <!-- nav links end -->

  </div>
</nav>
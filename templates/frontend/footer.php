<!-- Footer -->
<footer class="admin-footer text-center py-3 mt-4">
    <div class="container-fluid">
        <p class="mb-0 text-muted">
            <?php
            require_once __DIR__ . '/../../core/Settings.php'; // import settings
            $appSettings = AppSettings::getInstance(); // get settings instance
            $siteInfo = $appSettings->getSiteInfo(); // get siteInfo settings
            $siteTitle = isset($siteInfo['title']) ? $siteInfo['title'] : 'FaedinWebworks'; // use site title from settings or default
            ?>
            Copyright &copy; 2025 <?php echo htmlspecialchars($siteTitle); // echo sitetitle?>. All Rights Reserved.
        </p>
    </div>
</footer>

<!-- Bootstrap 5.2 JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Footer -->
        <footer class="admin-footer text-center py-3 mt-4">
    <div class="container-fluid">
        <p class="mb-0 text-muted">
            <?php
            require_once __DIR__ . '/../../core/Settings.php';
            $appSettings = AppSettings::getInstance();
            $siteTitle = $appSettings->get('site_title', 'FaedinWebworks');
            ?>
            Copyright &copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars($siteTitle); ?>. All Rights Reserved.
        </p>
    </div>
</footer>

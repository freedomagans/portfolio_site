</main> <!-- Close main content area opened in navigation -->

</div> <!-- Close admin-wrapper from header -->

<!-- Footer Section -->
<footer class="admin-footer text-center py-3 mt-4">
    <div class="container-fluid">
        <p class="mb-0 text-muted">
            <?php
            require_once __DIR__ . '/../../core/Settings.php'; // import settings 
            $appSettings = AppSettings::getInstance(); // get settings instance
            $siteTitle = $appSettings->get('site_title', 'FaedinWebworks'); // get site title from settings
            ?>
            Copyright &copy; 2025 <?php echo htmlspecialchars($siteTitle); // display site title from settings or the passed in default(FaedinWebworks)?>. All Rights Reserved.
        </p>
    </div>
</footer>

<!-- ============================
     BOOTSTRAP JAVASCRIPT (Load Once)
============================= -->

<!-- Popper.js (Required for Bootstrap dropdowns/tooltips) -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" 
        integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>

<!-- Bootstrap 5.2 Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" 
        integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>

<!-- ============================
     CUSTOM ADMIN SCRIPTS
============================= -->
<script>
    // Set session timeout from PHP settings
    const sessionTimeoutMinutes = <?php echo json_encode((int)AppSettings::getInstance()->get('session_timeout', 60)); ?>;
</script>
<script src="/static/admin/js/admin_footer.js"></script>

<!-- Additional CSS for Footer and Active States -->
<link href="/static/admin/css/admin_footer.css" rel="stylesheet"/>

</body>
</html>
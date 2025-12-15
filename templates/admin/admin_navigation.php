<!-- Admin navigation css -->
<link rel="stylesheet" href="/static/admin/css/admin_nav.css">

<!-- TOP NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark navbar-enhanced fixed-top">
    <div class="container-fluid">
        <!-- Brand -->
        <a class="navbar-brand" href="/urls.php?pg=admin">
            Admin Dashboard
        </a>

        <!-- Mobile Toggle (for top navbar user menu) -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
            data-bs-target="#topNavbar" aria-controls="topNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar Content -->
        <div class="collapse navbar-collapse" id="topNavbar">
            <ul class="navbar-nav ms-auto align-items-center">

                <!-- Home Link -->
                <li class="nav-item">
                    <a class="nav-link" href="/urls.php?pg=index">
                        <i class="fas fa-home"></i>
                        <span>Home</span>
                    </a>
                </li>

                <!-- User Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link user-badge dropdown-toggle" href="#" id="userMenu"
                        role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-user-circle"></i>
                        <span><?php echo htmlspecialchars($_SESSION['username'] ?: 'Admin'); ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-enhanced" aria-labelledby="userMenu">
                        <li>
                            <a class="dropdown-item" href="/urls.php?pg=profile">
                                <i class="fas fa-user"></i>
                                Profile
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="/urls.php?pg=settings">
                                <i class="fas fa-cog"></i>
                                Settings
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item" href="/urls.php?pg=logout">
                                <i class="fas fa-sign-out-alt"></i>
                                Logout
                            </a>
                        </li>
                    </ul>
                </li>

            </ul>
        </div>
    </div>
</nav>

<!-- MOBILE SIDEBAR OVERLAY -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- MOBILE SIDEBAR TOGGLE BUTTON (Floating) -->
<button class="sidebar-toggle-mobile" id="sidebarToggle" aria-label="Toggle Sidebar">
    <i class="fas fa-bars"></i>
</button>

<!-- SIDEBAR -->
<nav id="sidebarMenu" class="sidebar-enhanced">
    <!-- Close Button (Mobile Only) -->
    <button class="sidebar-close-btn" id="sidebarClose" aria-label="Close Sidebar">
        <i class="fas fa-times"></i>
    </button>

    <div class="sidebar-wrapper">

        <!-- Projects Section -->
        <div class="sidebar-section">
            <a class="sidebar-link" data-bs-toggle="collapse" href="#projectsCollapse"
                role="button" aria-expanded="false" aria-controls="projectsCollapse">
                <div class="sidebar-link-content">
                    <i class="fas fa-folder-open"></i>
                    <span>Projects</span>
                </div>
                <i class="fas fa-chevron-down caret-icon"></i>
            </a>
            <div class="collapse sidebar-submenu" id="projectsCollapse">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="/urls.php?pg=project_all">
                            View All Projects
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/urls.php?pg=project_add">
                            Add New Project
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Notifications Section -->
        <div class="sidebar-section">
            <a class="sidebar-link" data-bs-toggle="collapse" href="#notificationsCollapse"
                role="button" aria-expanded="false" aria-controls="notificationsCollapse">
                <div class="sidebar-link-content">
                    <i class="fas fa-bell"></i>
                    <span>Notifications</span>

                    <!-- Unread Count Badge -->
                    <?php
                    require_once __DIR__ . '/../../models/NotificationModel.php';
                    $notifications = new Notification();
                    $unreadCount = $notifications->unreadCount();
                    ?>
                    <?php if ($unreadCount > 0): ?>
                        <span class="notification-badge"><?= $unreadCount ?></span>
                    <?php endif; ?>
                </div>
                <i class="fas fa-chevron-down caret-icon"></i>
            </a>
            <div class="collapse sidebar-submenu" id="notificationsCollapse">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="/urls.php?pg=notification_all">
                            View All Notifications
                        </a>
                    </li>
                </ul>
            </div>
        </div>


        <!-- Comments Section -->
        <div class="sidebar-section">
            <a class="sidebar-link" data-bs-toggle="collapse" href="#commentsCollapse"
                role="button" aria-expanded="false" aria-controls="commentsCollapse">
                <div class="sidebar-link-content">
                    <i class="fas fa-comments"></i>
                    <span>Comments</span>

                    <!-- Pending Count Badge -->
                    <?php
                    require_once __DIR__ . '/../../models/CommentModel.php';
                    $comments = new Comment();
                    $pendingCount = $comments->countByStatus(0); // 0 = pending
                    ?>
                    <?php if ($pendingCount > 0): ?>
                        <span class="notification-badge"><?= $pendingCount ?></span>
                    <?php endif; ?>
                </div>
                <i class="fas fa-chevron-down caret-icon"></i>
            </a>
            <div class="collapse sidebar-submenu" id="commentsCollapse">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="/urls.php?pg=comment_all">
                            View All Comments
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/urls.php?pg=comment_all&status=pending">
                            Pending Comments
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/urls.php?pg=comment_all&status=approved">
                            Approved Comments
                        </a>
                    </li>
                </ul>
            </div>
        </div>



        <!-- Settings Section -->
        <!-- <div class="sidebar-section">
            <a class="sidebar-link" href="/urls.php?pg=settings">
                <div class="sidebar-link-content">
                    <i class="fas fa-cog"></i>
                    <span>Settings</span>
                </div>
            </a>
        </div> -->

    </div>
</nav>

<!-- MAIN CONTENT AREA -->
<main class="main-content-enhanced">
    
    <!-- Welcome Header -->
    <div class="welcome-header">
        <h5>
            <span>Welcome back, <?php echo htmlspecialchars($_SESSION['username'] ?: 'Admin'); ?>!</span>
            <span class="emoji">👋</span>
        </h5>
    </div>

    <!-- admin pages Content Goes Here -->

    <!-- Include Delete Modal -->
    <?php include ADMIN_TEMPLATE_PATH . 'delete_modal.php'; // include delete modal 
    ?>
    <!-- Admin Navigation JS -->
    <script src='/static/admin/js/admin_nav.js'></script>
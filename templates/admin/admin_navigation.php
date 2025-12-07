<!-- ENHANCED ADMIN NAVIGATION -->

<style>
    /* ===================================
   SCOPED ADMIN STYLES (No Body Override)
   =================================== */

    :root {
        --primary-gradient: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        --sidebar-bg: #1e293b;
        --sidebar-hover: rgba(78, 115, 223, 0.15);
        --navbar-bg: #0f172a;
        --text-light: #e2e8f0;
        --text-muted: #94a3b8;
        --accent-blue: #4e73df;
        --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.1);
        --shadow-md: 0 4px 15px rgba(0, 0, 0, 0.15);
        --shadow-lg: 0 8px 25px rgba(0, 0, 0, 0.2);
        --navbar-height: 70px;
        --sidebar-width: 250px;
    }

    /* Top Navbar Styles */
    .navbar-enhanced {
        background: var(--navbar-bg) !important;
        box-shadow: var(--shadow-md);
        border-bottom: 2px solid rgba(78, 115, 223, 0.3);
        padding: 1rem 1.5rem;
        transition: all 0.3s ease;
        height: var(--navbar-height);
    }

    .navbar-enhanced .navbar-brand {
        font-size: 1.3rem;
        font-weight: 700;
        background: var(--primary-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: transform 0.3s ease;
    }

    .navbar-enhanced .navbar-brand:hover {
        transform: scale(1.05);
    }

    .navbar-enhanced .navbar-brand i {
        -webkit-text-fill-color: var(--accent-blue);
        font-size: 1.5rem;
    }

    /* Navbar Links */
    .navbar-enhanced .nav-link {
        color: var(--text-light) !important;
        font-weight: 500;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .navbar-enhanced .nav-link:hover {
        background: var(--sidebar-hover);
        transform: translateY(-2px);
    }

    .navbar-enhanced .nav-link i {
        font-size: 1.1rem;
    }

    /* Mobile Navbar Collapse */
    @media (max-width: 991px) {
        .navbar-enhanced .navbar-collapse {
            background: var(--navbar-bg);
            margin-top: 1rem;
            padding: 1rem;
            border-radius: 8px;
            border: 1px solid rgba(78, 115, 223, 0.3);
        }

        .navbar-enhanced .nav-item {
            width: 100%;
        }

        .navbar-enhanced .nav-link {
            width: 100%;
            margin: 0.25rem 0;
        }

        .navbar-enhanced .dropdown-menu-enhanced {
            position: static !important;
            transform: none !important;
            box-shadow: none;
            background: rgba(255, 255, 255, 0.1);
            margin-top: 0.5rem;
        }

        .navbar-enhanced .dropdown-menu-enhanced .dropdown-item {
            color: var(--text-light);
        }

        .navbar-enhanced .dropdown-menu-enhanced .dropdown-item:hover {
            background: var(--sidebar-hover);
            color: #fff;
        }
    }

    /* Dropdown Menu */
    .dropdown-menu-enhanced {
        background: #fff;
        border: none;
        border-radius: 12px;
        box-shadow: var(--shadow-lg);
        padding: 0.5rem;
        margin-top: 0.5rem;
        min-width: 200px;
    }

    .dropdown-menu-enhanced .dropdown-item {
        padding: 0.7rem 1rem;
        border-radius: 8px;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.8rem;
        font-weight: 500;
        color: #475569;
    }

    .dropdown-menu-enhanced .dropdown-item:hover {
        background: var(--primary-gradient);
        color: #fff;
        transform: translateX(5px);
    }

    .dropdown-menu-enhanced .dropdown-item i {
        width: 20px;
        text-align: center;
    }

    .dropdown-divider {
        margin: 0.5rem 0;
        border-color: #e2e8f0;
    }

    /* User Badge */
    .user-badge {
        background: var(--primary-gradient);
        color: #fff;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .user-badge:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(78, 115, 223, 0.4);
    }

    .user-badge i {
        font-size: 1.1rem;
    }

    /* ===================================
   SIDEBAR STYLES - DESKTOP
   =================================== */

    .sidebar-enhanced {
        background: var(--sidebar-bg) !important;
        min-height: calc(100vh - var(--navbar-height));
        padding: 1.5rem 0;
        box-shadow: var(--shadow-md);
        position: fixed;
        top: var(--navbar-height);
        left: 0;
        width: var(--sidebar-width);
        overflow-y: auto;
        overflow-x: hidden;
        transition: transform 0.3s ease;
        z-index: 1040;
    }

    /* Scrollbar Styling */
    .sidebar-enhanced::-webkit-scrollbar {
        width: 6px;
    }

    .sidebar-enhanced::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.05);
    }

    .sidebar-enhanced::-webkit-scrollbar-thumb {
        background: rgba(78, 115, 223, 0.5);
        border-radius: 3px;
    }

    .sidebar-enhanced::-webkit-scrollbar-thumb:hover {
        background: rgba(78, 115, 223, 0.7);
    }

    /* Sidebar Section */
    .sidebar-section {
        margin-bottom: 0.5rem;
    }

    /* Sidebar Main Links */
    .sidebar-link {
        color: var(--text-light) !important;
        padding: 0.9rem 1.5rem;
        border-radius: 0;
        transition: all 0.3s ease;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: 500;
        border-left: 3px solid transparent;
        cursor: pointer;
        text-decoration: none;
    }

    .sidebar-link:hover {
        background: var(--sidebar-hover);
        border-left-color: var(--accent-blue);
        padding-left: 1.8rem;
    }

    .sidebar-link[aria-expanded="true"] {
        background: var(--sidebar-hover);
        border-left-color: var(--accent-blue);
        color: #fff !important;
    }

    .sidebar-link-content {
        display: flex;
        align-items: center;
        gap: 0.8rem;
    }

    .sidebar-link-content i {
        width: 20px;
        text-align: center;
        font-size: 1.1rem;
    }

    /* Caret Icon Animation */
    .caret-icon {
        transition: transform 0.3s ease;
        font-size: 0.9rem;
    }

    .sidebar-link[aria-expanded="true"] .caret-icon {
        transform: rotate(180deg);
    }

    /* Submenu */
    .sidebar-submenu {
        background: rgba(0, 0, 0, 0.2);
        padding: 0.5rem 0;
    }

    .sidebar-submenu .nav-link {
        color: var(--text-muted) !important;
        padding: 0.7rem 1.5rem 0.7rem 3.5rem;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        border-left: 3px solid transparent;
        position: relative;
    }

    .sidebar-submenu .nav-link::before {
        content: '';
        position: absolute;
        left: 2.2rem;
        top: 50%;
        transform: translateY(-50%);
        width: 6px;
        height: 6px;
        background: var(--text-muted);
        border-radius: 50%;
        transition: all 0.3s ease;
    }

    .sidebar-submenu .nav-link:hover {
        color: #fff !important;
        background: rgba(78, 115, 223, 0.1);
        border-left-color: var(--accent-blue);
        padding-left: 3.8rem;
    }

    .sidebar-submenu .nav-link:hover::before {
        background: var(--accent-blue);
        transform: translateY(-50%) scale(1.3);
    }

    /* Main Content Area - FIXED MARGIN */
    .main-content-enhanced {
        margin-left: var(--sidebar-width);
        margin-top: var(--navbar-height);
        padding: 2rem;
        background: #f8f9fc;
        min-height: calc(100vh - var(--navbar-height));
        transition: margin-left 0.3s ease;
    }

    /* Welcome Header */
    .welcome-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        padding: 2rem;
        border-radius: 15px;
        margin-bottom: 2rem;
        box-shadow: var(--shadow-md);
        position: relative;
        overflow: hidden;
    }

    .welcome-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><rect width="2" height="2" fill="rgba(255,255,255,0.1)"/></svg>') repeat;
        opacity: 0.3;
    }

    .welcome-header h5 {
        position: relative;
        z-index: 1;
        font-size: 1.8rem;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .welcome-header .emoji {
        font-size: 2rem;
        animation: wave 1s ease-in-out infinite;
    }

    @keyframes wave {

        0%,
        100% {
            transform: rotate(0deg);
        }

        25% {
            transform: rotate(20deg);
        }

        75% {
            transform: rotate(-20deg);
        }
    }

    /* Badge Notification */
    .notification-badge {
        background: #e74a3b;
        color: #fff;
        font-size: 0.7rem;
        padding: 0.2rem 0.5rem;
        border-radius: 50px;
        font-weight: 700;
        margin-left: 0.5rem;
        animation: pulse 2s ease-in-out infinite;
    }

    @keyframes pulse {

        0%,
        100% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.1);
        }
    }

    /* ===================================
   MOBILE SIDEBAR TOGGLE BUTTON
   =================================== */

    .sidebar-toggle-mobile {
        display: none;
        position: fixed;
        bottom: 20px;
        left: 20px;
        z-index: 1050;
        background: var(--primary-gradient);
        color: white;
        border: none;
        border-radius: 50%;
        width: 60px;
        height: 60px;
        box-shadow: 0 4px 15px rgba(78, 115, 223, 0.5);
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .sidebar-toggle-mobile:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 20px rgba(78, 115, 223, 0.7);
    }

    .sidebar-toggle-mobile:active {
        transform: scale(0.95);
    }

    .sidebar-toggle-mobile i {
        font-size: 1.5rem;
    }

    /* Mobile Sidebar Overlay */
    .sidebar-overlay {
        display: none;
        position: fixed;
        top: var(--navbar-height);
        left: 0;
        width: 100%;
        height: calc(100vh - var(--navbar-height));
        background: rgba(0, 0, 0, 0.5);
        z-index: 1030;
        opacity: 0;
        transition: opacity 0.3s ease;
        pointer-events: none;
        /* CRITICAL: Don't block clicks when hidden */
    }

    .sidebar-overlay.active {
        opacity: 1;
        pointer-events: auto;
        /* Only catch clicks when visible */
        display: block;
    }

    /* CRITICAL: Sidebar overlay should be BELOW Bootstrap modals */
    .modal-backdrop {
        z-index: 1055 !important;
        /* Bootstrap modal backdrop */
    }

    .modal {
        z-index: 1060 !important;
        /* Bootstrap modal content */
    }

    /* Ensure sidebar overlay doesn't interfere with modals */
    body.modal-open .sidebar-overlay {
        display: none !important;
        /* Hide completely when modal is open */
        pointer-events: none !important;
    }

    /* ===================================
   RESPONSIVE DESIGN - MOBILE FIRST
   =================================== */

    /* Tablet and Below - 991px */
    @media (max-width: 991px) {

        /* Show mobile toggle button */
        .sidebar-toggle-mobile {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Hide sidebar by default on mobile */
        .sidebar-enhanced {
            transform: translateX(-100%);
            top: var(--navbar-height);
            height: calc(100vh - var(--navbar-height));
            z-index: 1045;
            pointer-events: none;
            /* CRITICAL: Don't block clicks when closed */
        }

        /* Show sidebar when active */
        .sidebar-enhanced.mobile-active {
            transform: translateX(0);
            pointer-events: auto;
            /* Enable clicks when open */
        }

        /* Show overlay when sidebar is active */
        .sidebar-overlay {
            display: block;
        }

        /* Adjust main content */
        .main-content-enhanced {
            margin-left: 0;
            margin-top: var(--navbar-height);
            position: relative;
            z-index: 1;
            /* Keep low to not interfere */
        }

        /* Sidebar close button (X) */
        .sidebar-close-btn {
            display: block;
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(255, 255, 255, 0.1);
            border: none;
            color: white;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 10;
        }

        .sidebar-close-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: rotate(90deg);
        }

        /* CRITICAL: Ensure collapsed navbar doesn't block */
        .navbar-enhanced .navbar-collapse:not(.show) {
            pointer-events: none;
            display: none;
        }

        .navbar-enhanced .navbar-collapse.show {
            pointer-events: auto;
            display: block;
        }

        /* Remove transforms that create stacking contexts */
        .navbar-enhanced .nav-link:hover {
            transform: none;
        }
    }

    /* Desktop - Hide close button */
    @media (min-width: 992px) {
        .sidebar-close-btn {
            display: none;
        }
    }

    /* Mobile - 768px */
    @media (max-width: 768px) {
        .welcome-header h5 {
            font-size: 1.3rem;
            flex-direction: column;
            text-align: center;
        }

        .navbar-enhanced {
            padding: 0.75rem 1rem;
        }

        .navbar-enhanced .navbar-brand {
            font-size: 1.1rem;
        }

        .main-content-enhanced {
            padding: 1rem;
        }

        /* Adjust toggle button for small screens */
        .sidebar-toggle-mobile {
            width: 55px;
            height: 55px;
            bottom: 15px;
            left: 15px;
        }

        .sidebar-toggle-mobile i {
            font-size: 1.3rem;
        }

        /* CRITICAL: Remove hover transforms on cards */
        .dashboard-card:hover,
        .card:hover {
            transform: none;
        }

        /* Ensure all clickable elements work */
        .card a,
        .card button,
        .dashboard-card a,
        .dashboard-card button,
        .btn {
            position: relative;
            z-index: 10;
            pointer-events: auto;
        }
    }

    /* Very Small Mobile - 480px */
    @media (max-width: 480px) {
        .welcome-header {
            padding: 1.5rem;
        }

        .welcome-header h5 {
            font-size: 1.1rem;
        }

        .navbar-enhanced .user-badge {
            padding: 0.4rem 0.8rem;
            font-size: 0.85rem;
        }
    }

    /* Mobile Menu Toggle (Top Navbar) */
    .navbar-toggler {
        border: 2px solid rgba(78, 115, 223, 0.5);
        padding: 0.5rem 0.75rem;
    }

    .navbar-toggler:focus {
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.3);
    }

    .navbar-toggler-icon {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(78, 115, 223, 1)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
    }

    /* Prevent body scroll when sidebar is open on mobile */
    body.sidebar-open {
        overflow: hidden;
    }
</style>

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
                        <span><?php echo htmlspecialchars($_SESSION['username'] ?? 'Admin'); ?></span>
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
            <span>Welcome back, <?php echo htmlspecialchars($_SESSION['username'] ?? 'Admin'); ?>!</span>
            <span class="emoji">👋</span>
        </h5>
    </div>

    <!-- Include Delete Modal -->
    <?php include ADMIN_TEMPLATE_PATH . 'delete_modal.php'; ?>

    <!-- Page Content Goes Here -->
    <script>
        // Mobile Sidebar Toggle Script (Inline for immediate execution)
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarClose = document.getElementById('sidebarClose');
            const sidebar = document.getElementById('sidebarMenu');
            const overlay = document.getElementById('sidebarOverlay');
            const body = document.body;

            console.log('🚀 Admin navigation script loaded');

            // Open sidebar
            function openSidebar() {
                sidebar.classList.add('mobile-active');
                overlay.classList.add('active');
                body.classList.add('sidebar-open');
            }

            // Close sidebar
            function closeSidebar() {
                sidebar.classList.remove('mobile-active');
                overlay.classList.remove('active');
                body.classList.remove('sidebar-open');
            }

            // CRITICAL: Force hide sidebar overlay (nuclear option)
            function forceSidebarHide() {
                if (overlay) {
                    overlay.classList.remove('active');
                    overlay.style.display = 'none';
                    overlay.style.opacity = '0';
                    overlay.style.pointerEvents = 'none';
                    overlay.style.zIndex = '-1';
                    overlay.style.visibility = 'hidden';
                }

                if (sidebar) {
                    sidebar.classList.remove('mobile-active');
                    sidebar.style.pointerEvents = 'none';
                }

                body.classList.remove('sidebar-open');
            }

            // Toggle button click
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', openSidebar);
            }

            // Close button click
            if (sidebarClose) {
                sidebarClose.addEventListener('click', closeSidebar);
            }

            // Overlay click (close sidebar)
            if (overlay) {
                overlay.addEventListener('click', closeSidebar);
            }

            // Close sidebar when clicking a link (mobile only)
            const sidebarLinks = sidebar.querySelectorAll('.sidebar-submenu .nav-link');
            sidebarLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 992) {
                        closeSidebar();
                    }
                });
            });});

            // Close sidebar on ESC key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && sidebar.classList.contains('mobile-active')) {
                    closeSidebar();
                }
            });

             // ============================================
// CRITICAL FIX: Bootstrap Modal Integration
// ============================================

console.log('👂 Setting up modal event listeners...');

// Nuclear option to completely reset overlay
function nukeSidebarOverlay() {
    const overlay = document.getElementById('sidebarOverlay');
    const sidebar = document.getElementById('sidebarMenu');
    const body = document.body;
    
    console.log('💣 Nuking sidebar overlay...');
    
    if (overlay) {
        overlay.classList.remove('active');
        // Force all blocking properties to safe values
        overlay.style.cssText = `
            display: none !important;
            opacity: 0 !important;
            pointer-events: none !important;
            visibility: hidden !important;
            z-index: -1 !important;
        `;
    }
    
    if (sidebar) {
        sidebar.classList.remove('mobile-active');
        // Only block pointer events when sidebar is closed
        if (window.innerWidth < 992) {
            sidebar.style.pointerEvents = 'none';
        }
    }
    
    body.classList.remove('sidebar-open');
    console.log('✅ Sidebar overlay nuked');
}

// Restore normal functionality after modal closes
function restoreOverlay() {
    const overlay = document.getElementById('sidebarOverlay');
    const sidebar = document.getElementById('sidebarMenu');
    
    console.log('🔄 Restoring overlay to normal state...');
    
    if (overlay) {
        // Remove all inline styles to let CSS take over
        overlay.style.cssText = '';
        // Ensure active class is removed
        overlay.classList.remove('active');
    }
    
    if (sidebar && window.innerWidth >= 992) {
        // On desktop, sidebar should always be interactive
        sidebar.style.pointerEvents = 'auto';
    }
    
    console.log('✅ Overlay restored');
}

// BEFORE modal opens
document.addEventListener('show.bs.modal', function(event) {
    console.log('🔔 Modal about to open:', event.target.id);
    nukeSidebarOverlay();
});

// AFTER modal is fully visible
document.addEventListener('shown.bs.modal', function(event) {
    console.log('✅ Modal fully opened:', event.target.id);
    // Double-check overlay is hidden
    nukeSidebarOverlay();
});

// BEFORE modal closes
document.addEventListener('hide.bs.modal', function(event) {
    console.log('🔔 Modal about to close:', event.target.id);
    nukeSidebarOverlay();
});

// AFTER modal is fully closed
document.addEventListener('hidden.bs.modal', function(event) {
    console.log('❌ Modal closed:', event.target.id);
    
    // Wait for Bootstrap to finish cleanup, then restore normal state
    setTimeout(function() {
        restoreOverlay();
        
        // Re-enable all clickable elements
        const clickableElements = document.querySelectorAll('a, button, [role="button"]');
        clickableElements.forEach(el => {
            el.style.pointerEvents = 'auto';
        });
        
        console.log('✅ All elements re-enabled');
    }, 150);
});

// Emergency watchdog - runs periodically when modal is open
setInterval(function() {
    const isModalOpen = document.body.classList.contains('modal-open');
    const overlay = document.getElementById('sidebarOverlay');
    
    if (isModalOpen && overlay) {
        const computedStyle = window.getComputedStyle(overlay);
        const isBlocking = (
            overlay.classList.contains('active') ||
            computedStyle.display !== 'none' ||
            computedStyle.opacity !== '0' ||
            parseInt(computedStyle.zIndex) > 0
        );
        
        if (isBlocking) {
            console.warn('⚠ EMERGENCY: Overlay blocking modal! Auto-fixing...');
            nukeSidebarOverlay();
        }
    }
}, 300);

console.log('✅ Modal event listeners ready');
    </script>
<?php
// ===============================================
// ADMIN DASHBOARD
// ===============================================

include ADMIN_TEMPLATE_PATH . "admin_header.php"; // admin header file 
include ADMIN_TEMPLATE_PATH . "admin_navigation.php"; // admin navigation  file

// import models
require_once __DIR__ . '/../../models/ProjectModel.php';
require_once __DIR__ . '/../../models/ProjectViewModel.php';
require_once __DIR__ . '/../../models/ProjectLikeModel.php';
require_once __DIR__ . '/../../models/NotificationModel.php';
require_once __DIR__ . '/../../models/CommentModel.php';

// Instantiate models
$projectModel = new Project();
$projectViewModel = new ProjectView();
$projectLikeModel = new ProjectLike();
$notificationModel = new Notification();
$commentModel = new Comment();

// Fetch dashboard statistics
$projectCount = $projectModel->count();
$totalViews = $projectViewModel->count();
$totalLikes = $projectLikeModel->count();
$approvedComments = $commentModel->countByStatus(1);
$pendingComments = $commentModel->countByStatus(0);
$totalComments = $commentModel->count();
$totalMessages = $notificationModel->count();
$unreadMessages = $notificationModel->unreadCount();

// Fetch recent data for widgets
$recentProjects = $projectModel->getLatest(5);
$recentNotifications = $notificationModel->getLatest(5);
$recentComments = $commentModel->getLatest(5);

// Calculate growth percentages (real monthly growth)
// Fetch data from last 2 months for comparison
$currentMonth = date('Y-m-01 00:00:00');
$lastMonth = date('Y-m-01 00:00:00', strtotime('-1 month'));
$twoMonthsAgo = date('Y-m-01 00:00:00', strtotime('-2 months'));

// Function to calculate growth percentage
function calculateGrowth($current, $previous) {
    if ($previous == 0) return $current > 0 ? 100 : 0;
    return round((($current - $previous) / $previous) * 100, 1);
}

// Get monthly statistics
try {
    // Projects growth
    $stmt = $projectModel->conn->prepare("
        SELECT 
            COUNT(*) as count,
            DATE_FORMAT(created_at, '%Y-%m') as month
        FROM projects 
        WHERE created_at >= :two_months_ago
        GROUP BY DATE_FORMAT(created_at, '%Y-%m')
        ORDER BY month DESC
    ");
    $stmt->execute([':two_months_ago' => $twoMonthsAgo]);
    $projectStats = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $currentMonthProjects = isset($projectStats[0]) ? $projectStats[0]['count'] : 0;
    $lastMonthProjects = isset($projectStats[1]) ? $projectStats[1]['count'] : 0;
    $projectGrowth = calculateGrowth($currentMonthProjects, $lastMonthProjects);
    
    // Views growth
    $stmt = $projectViewModel->conn->prepare("
        SELECT 
            COUNT(*) as count,
            DATE_FORMAT(viewed_at, '%Y-%m') as month
        FROM project_views 
        WHERE viewed_at >= :two_months_ago
        GROUP BY DATE_FORMAT(viewed_at, '%Y-%m')
        ORDER BY month DESC
    ");
    $stmt->execute([':two_months_ago' => $twoMonthsAgo]);
    $viewStats = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $currentMonthViews = isset($viewStats[0]) ? $viewStats[0]['count'] : 0;
    $lastMonthViews = isset($viewStats[1]) ? $viewStats[1]['count'] : 0;
    $viewsGrowth = calculateGrowth($currentMonthViews, $lastMonthViews);
    
    // Likes growth
    $stmt = $projectLikeModel->conn->prepare("
        SELECT 
            COUNT(*) as count,
            DATE_FORMAT(liked_at, '%Y-%m') as month
        FROM project_likes 
        WHERE liked_at >= :two_months_ago
        GROUP BY DATE_FORMAT(liked_at, '%Y-%m')
        ORDER BY month DESC
    ");
    $stmt->execute([':two_months_ago' => $twoMonthsAgo]);
    $likeStats = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $currentMonthLikes = isset($likeStats[0]) ? $likeStats[0]['count'] : 0;
    $lastMonthLikes = isset($likeStats[1]) ? $likeStats[1]['count'] : 0;
    $likesGrowth = calculateGrowth($currentMonthLikes, $lastMonthLikes);
    
    // Comments growth
    $stmt = $commentModel->conn->prepare("
        SELECT 
            COUNT(*) as count,
            DATE_FORMAT(created_at, '%Y-%m') as month
        FROM comments 
        WHERE created_at >= :two_months_ago
        GROUP BY DATE_FORMAT(created_at, '%Y-%m')
        ORDER BY month DESC
    ");
    $stmt->execute([':two_months_ago' => $twoMonthsAgo]);
    $commentStats = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $currentMonthComments = isset($commentStats[0]) ? $commentStats[0]['count'] : 0;
    $lastMonthComments = isset($commentStats[1]) ? $commentStats[1]['count'] : 0;
    $commentsGrowth = calculateGrowth($currentMonthComments, $lastMonthComments);
    
} catch (Exception $e) {
    // Fallback to zero growth if queries fail
    $projectGrowth = 0;
    $viewsGrowth = 0;
    $likesGrowth = 0;
    $commentsGrowth = 0;
}

// Get historical data for charts (last 12 months)
$twelveMonthsAgo = date('Y-m-01 00:00:00', strtotime('-12 months'));

// Views per month
$stmt = $projectViewModel->conn->prepare("
    SELECT 
        DATE_FORMAT(viewed_at, '%b') as month,
        COUNT(*) as count
    FROM project_views 
    WHERE viewed_at >= :twelve_months_ago
    GROUP BY DATE_FORMAT(viewed_at, '%Y-%m')
    ORDER BY viewed_at ASC
");
$stmt->execute([':twelve_months_ago' => $twelveMonthsAgo]);
$viewsPerMonth = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Likes per month
$stmt = $projectLikeModel->conn->prepare("
    SELECT 
        DATE_FORMAT(liked_at, '%b') as month,
        COUNT(*) as count
    FROM project_likes 
    WHERE liked_at >= :twelve_months_ago
    GROUP BY DATE_FORMAT(liked_at, '%Y-%m')
    ORDER BY liked_at ASC
");
$stmt->execute([':twelve_months_ago' => $twelveMonthsAgo]);
$likesPerMonth = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Comments per month
$stmt = $commentModel->conn->prepare("
    SELECT 
        DATE_FORMAT(created_at, '%b') as month,
        COUNT(*) as count
    FROM comments 
    WHERE created_at >= :twelve_months_ago
    GROUP BY DATE_FORMAT(created_at, '%Y-%m')
    ORDER BY created_at ASC
");
$stmt->execute([':twelve_months_ago' => $twelveMonthsAgo]);
$commentsPerMonth = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Projects per month
$stmt = $projectModel->conn->prepare("
    SELECT 
        DATE_FORMAT(created_at, '%b') as month,
        COUNT(*) as count
    FROM projects 
    WHERE created_at >= :twelve_months_ago
    GROUP BY DATE_FORMAT(created_at, '%Y-%m')
    ORDER BY created_at ASC
");
$stmt->execute([':twelve_months_ago' => $twelveMonthsAgo]);
$projectsPerMonth = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Messages per month
$stmt = $notificationModel->conn->prepare("
    SELECT 
        DATE_FORMAT(created_at, '%b') as month,
        COUNT(*) as count
    FROM notifications 
    WHERE created_at >= :twelve_months_ago
    GROUP BY DATE_FORMAT(created_at, '%Y-%m')
    ORDER BY created_at ASC
");
$stmt->execute([':twelve_months_ago' => $twelveMonthsAgo]);
$messagesPerMonth = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Helper function to fill missing months with zeros
function fillMonthlyData($data, $monthsCount = 12) {
    $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    $currentMonth = (int)date('n');
    $result = [];
    
    for ($i = 0; $i < $monthsCount; $i++) {
        $monthIndex = ($currentMonth - $monthsCount + $i + 12) % 12;
        $monthName = $months[$monthIndex];
        $result[$monthName] = 0;
    }
    
    foreach ($data as $row) {
        if (isset($row['month']) && isset($row['count'])) {
            $result[$row['month']] = (int)$row['count'];
        }
    }
    
    return $result;
}

$viewsChartData = fillMonthlyData($viewsPerMonth);
$likesChartData = fillMonthlyData($likesPerMonth);
$commentsChartData = fillMonthlyData($commentsPerMonth);
$projectsChartData = fillMonthlyData($projectsPerMonth);
$messagesChartData = fillMonthlyData($messagesPerMonth);
?>

<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<!-- Custom Dashboard Styles -->
<link href="/static/admin/css/dashboard.css" rel="stylesheet">

<!-- Dashboard Content -->
<div class="container-fluid px-4">

    <!-- Stats Grid -->
    <div class="row g-4 mb-4">
        
        <!-- Total Projects -->
        <div class="col-xl-2 col-lg-4 col-md-6">
            <div class="stat-card text-white" style="background: var(--gradient-primary);">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="stat-icon me-3">
                            <i class="fas fa-folder-open"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="stat-value"><?= number_format($projectCount) ?></div>
                            <div class="stat-label">Projects</div>
                        </div>
                    </div>
                    <div class="stat-growth <?= $projectGrowth < 0 ? 'negative' : '' ?>">
                        <i class="fas fa-arrow-<?= $projectGrowth >= 0 ? 'up' : 'down' ?>"></i>
                        <span><?= $projectGrowth >= 0 ? '+' : '' ?><?= $projectGrowth ?>%</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Views -->
        <div class="col-xl-2 col-lg-4 col-md-6">
            <div class="stat-card text-white" style="background: var(--gradient-info);">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="stat-icon me-3">
                            <i class="fas fa-eye"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="stat-value"><?= number_format($totalViews) ?></div>
                            <div class="stat-label">Total Views</div>
                        </div>
                    </div>
                    <div class="stat-growth <?= $viewsGrowth < 0 ? 'negative' : '' ?>">
                        <i class="fas fa-arrow-<?= $viewsGrowth >= 0 ? 'up' : 'down' ?>"></i>
                        <span><?= $viewsGrowth >= 0 ? '+' : '' ?><?= $viewsGrowth ?>%</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Likes -->
        <div class="col-xl-2 col-lg-4 col-md-6">
            <div class="stat-card text-white" style="background: var(--gradient-danger);">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="stat-icon me-3">
                            <i class="fas fa-heart"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="stat-value"><?= number_format($totalLikes) ?></div>
                            <div class="stat-label">Total Likes</div>
                        </div>
                    </div>
                    <div class="stat-growth <?= $likesGrowth < 0 ? 'negative' : '' ?>">
                        <i class="fas fa-arrow-<?= $likesGrowth >= 0 ? 'up' : 'down' ?>"></i>
                        <span><?= $likesGrowth >= 0 ? '+' : '' ?><?= $likesGrowth ?>%</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Comments -->
        <div class="col-xl-2 col-lg-4 col-md-6">
            <div class="stat-card text-white" style="background: var(--gradient-success);">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="stat-icon me-3">
                            <i class="fas fa-comments"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="stat-value"><?= number_format($totalComments) ?></div>
                            <div class="stat-label">Comments</div>
                        </div>
                    </div>
                    <div class="stat-growth <?= $commentsGrowth < 0 ? 'negative' : '' ?>">
                        <i class="fas fa-arrow-<?= $commentsGrowth >= 0 ? 'up' : 'down' ?>"></i>
                        <span><?= $commentsGrowth >= 0 ? '+' : '' ?><?= $commentsGrowth ?>%</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Messages -->
        <div class="col-xl-2 col-lg-4 col-md-6">
            <div class="stat-card text-white" style="background: var(--gradient-warning);">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="stat-icon me-3">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="stat-value"><?= number_format($totalMessages) ?></div>
                            <div class="stat-label">Messages</div>
                        </div>
                    </div>
                    <div class="stat-growth">
                        <i class="fas fa-envelope-open"></i>
                        <span><?= $unreadMessages ?> unread</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notifications -->
        <div class="col-xl-2 col-lg-4 col-md-6">
            <div class="stat-card text-white" style="background: var(--gradient-dark);">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="stat-icon me-3 position-relative">
                            <i class="fas fa-bell"></i>
                            <?php if ($unreadMessages > 0): ?>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.65rem;">
                                    <?= $unreadMessages ?>
                                </span>
                            <?php endif; ?>
                        </div>
                        <div class="flex-grow-1">
                            <div class="stat-value"><?= $unreadMessages ?></div>
                            <div class="stat-label">Unread</div>
                        </div>
                    </div>
                    <a href="/urls.php?pg=notification_all" class="text-white text-decoration-none" style="font-size: 0.85rem; opacity: 0.9;">
                        View all →
                    </a>
                </div>
            </div>
        </div>

    </div>

    <!-- Charts & Widgets Row -->
    <div class="row g-4 mb-4">
        
        <!-- Analytics Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="chart-card">
                <div class="card-header">
                    <h5 class="chart-card-title">
                        <i class="fas fa-chart-line"></i>
                        Analytics Overview
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="analyticsChart" height="80"></canvas>
                </div>
            </div>
        </div>

        <!-- Profile Card -->
        <div class="col-xl-4 col-lg-5">
            <div class="profile-card">
                <div class="card-body text-center p-4">
                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['username'] ?: 'Admin') ?>&size=80&background=667eea&color=fff&bold=true" 
                         alt="Profile" 
                         class="profile-avatar mb-3">
                    <h5 class="profile-name"><?= htmlspecialchars($_SESSION['username'] ?: 'Admin') ?></h5>
                    <p class="profile-role">Administrator</p>
                </div>
                <div class="profile-stats">
                    <div class="profile-stat">
                        <span class="profile-stat-value"><?= $projectCount ?></span>
                        <span class="profile-stat-label">Projects</span>
                    </div>
                    <div class="profile-stat">
                        <span class="profile-stat-value"><?= number_format($totalViews) ?></span>
                        <span class="profile-stat-label">Views</span>
                    </div>
                    <div class="profile-stat">
                        <span class="profile-stat-value"><?= $totalLikes ?></span>
                        <span class="profile-stat-label">Likes</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="/urls.php?pg=profile" class="btn btn-light quick-action-btn">
                            <i class="fas fa-user-edit"></i>
                            Edit Profile
                        </a>
                        <a href="/urls.php?pg=logout" class="btn btn-outline-light quick-action-btn">
                            <i class="fas fa-sign-out-alt"></i>
                            Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Widgets Row -->
    <div class="row g-4 mb-4">
        
        <!-- Recent Projects Widget -->
        <div class="col-xl-4 col-lg-6">
            <div class="widget-card">
                <div class="widget-header">
                    <h6 class="widget-title">
                        <i class="fas fa-folder-open"></i>
                        Recent Projects
                    </h6>
                    <span class="widget-badge"><?= count($recentProjects) ?></span>
                </div>
                <div class="widget-body">
                    <?php if (!empty($recentProjects)): ?>
                        <?php foreach ($recentProjects as $project): ?>
                            <a href="/urls.php?pg=project_edit&id=<?= $project['id'] ?>" class="text-decoration-none">
                                <div class="widget-item d-flex align-items-center gap-3">
                                    <div class="widget-item-icon">
                                        <i class="fas fa-file-code"></i>
                                    </div>
                                    <div class="widget-item-content">
                                        <div class="widget-item-title">
                                            <?= htmlspecialchars($project['title'] ?: 'Untitled') ?>
                                        </div>
                                        <div class="widget-item-meta">
                                            <i class="fas fa-clock"></i>
                                            <?= date('M d, Y', strtotime($project['created_at'])) ?>
                                        </div>
                                    </div>
                                    <?php if ((int)($project['is_published'] ?: 1) === 1): ?>
                                        <span class="widget-item-badge bg-success text-white">
                                            Published
                                        </span>
                                    <?php else: ?>
                                        <span class="widget-item-badge bg-warning text-dark">
                                            Draft
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="empty-state">
                            <i class="fas fa-folder-open"></i>
                            <p>No projects yet</p>
                            <a href="/urls.php?pg=project_add" class="btn btn-sm btn-primary mt-2">
                                <i class="fas fa-plus me-1"></i>
                                Create First Project
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="card-footer bg-light text-center py-2">
                    <a href="/urls.php?pg=project_all" class="text-decoration-none small fw-semibold">
                        View All Projects <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Comments Widget -->
        <div class="col-xl-4 col-lg-6">
            <div class="widget-card">
                <div class="widget-header" style="background: var(--gradient-success);">
                    <h6 class="widget-title">
                        <i class="fas fa-comments"></i>
                        Recent Comments
                    </h6>
                    <span class="widget-badge"><?= count($recentComments) ?></span>
                </div>
                <div class="widget-body">
                    <?php if (!empty($recentComments)): ?>
                        <?php foreach ($recentComments as $comment): ?>
                            <a href="/urls.php?pg=comment_all" class="text-decoration-none">
                                <div class="widget-item d-flex align-items-start gap-3">
                                    <div class="widget-item-icon" style="background: var(--gradient-success);">
                                        <i class="fas fa-comment"></i>
                                    </div>
                                    <div class="widget-item-content">
                                        <div class="widget-item-title">
                                            <?= htmlspecialchars($comment['name'] ?: 'Anonymous') ?>
                                        </div>
                                        <div class="widget-item-meta">
                                            <i class="fas fa-clock"></i>
                                            <?= date('M d, Y', strtotime($comment['created_at'])) ?>
                                        </div>
                                    </div>
                                    <?php if ((int)$comment['is_approved'] === 0): ?>
                                        <span class="widget-item-badge bg-warning text-dark">
                                            Pending
                                        </span>
                                    <?php else: ?>
                                        <span class="widget-item-badge bg-success text-white">
                                            Approved
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="empty-state">
                            <i class="fas fa-comments"></i>
                            <p>No comments yet</p>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="card-footer bg-light text-center py-2">
                    <a href="/urls.php?pg=comment_all" class="text-decoration-none small fw-semibold">
                        View All Comments <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Messages Widget -->
        <div class="col-xl-4 col-lg-6">
            <div class="widget-card">
                <div class="widget-header" style="background: var(--gradient-warning);">
                    <h6 class="widget-title">
                        <i class="fas fa-envelope"></i>
                        Recent Messages
                    </h6>
                    <span class="widget-badge"><?= count($recentNotifications) ?></span>
                </div>
                <div class="widget-body">
                    <?php if (!empty($recentNotifications)): ?>
                        <?php foreach ($recentNotifications as $notification): ?>
                            <a href="/urls.php?pg=notification_all" class="text-decoration-none">
                                <div class="widget-item d-flex align-items-start gap-3">
                                    <div class="widget-item-icon" style="background: var(--gradient-warning);">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                    <div class="widget-item-content">
                                        <div class="widget-item-title">
                                            <?= htmlspecialchars($notification['name'] ?: 'Anonymous') ?>
                                        </div>
                                        <div class="widget-item-meta">
                                            <i class="fas fa-clock"></i>
                                            <?= date('M d, Y', strtotime($notification['created_at'])) ?>
                                        </div>
                                    </div>
                                    <?php if ((int)$notification['is_read'] === 0): ?>
                                        <span class="widget-item-badge bg-danger text-white">
                                            Unread
                                        </span>
                                    <?php else: ?>
                                        <span class="widget-item-badge bg-secondary text-white">
                                            Read
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="empty-state">
                            <i class="fas fa-envelope"></i>
                            <p>No messages yet</p>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="card-footer bg-light text-center py-2">
                    <a href="/urls.php?pg=notification_all" class="text-decoration-none small fw-semibold">
                        View All Messages <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

    </div>

    <!-- Additional Charts Row -->
    <div class="row g-4 mb-4">
        
        <!-- Engagement Distribution Chart -->
        <div class="col-xl-6 col-lg-6">
            <div class="chart-card">
                <div class="card-header">
                    <h5 class="chart-card-title">
                        <i class="fas fa-chart-pie"></i>
                        Engagement Distribution
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="engagementChart" height="120"></canvas>
                </div>
            </div>
        </div>

        <!-- Monthly Activity Chart -->
        <div class="col-xl-6 col-lg-6">
            <div class="chart-card">
                <div class="card-header">
                    <h5 class="chart-card-title">
                        <i class="fas fa-chart-bar"></i>
                        Monthly Activity
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="activityChart" height="120"></canvas>
                </div>
            </div>
        </div>
        
    </div>

</div>

<!-- Chart Data for JavaScript -->
<script>
window.chartData = {
    viewsData: <?php echo json_encode(array_values($viewsChartData)); ?>,
    likesData: <?php echo json_encode(array_values($likesChartData)); ?>,
    commentsData: <?php echo json_encode(array_values($commentsChartData)); ?>,
    monthLabels: <?php echo json_encode(array_keys($viewsChartData)); ?>,
    totalViews: <?php echo $totalViews; ?>,
    totalLikes: <?php echo $totalLikes; ?>,
    totalComments: <?php echo $totalComments; ?>,
    totalMessages: <?php echo $totalMessages; ?>,
    projectsData: <?php echo json_encode(array_values($projectsChartData)); ?>,
    messagesData: <?php echo json_encode(array_values($messagesChartData)); ?>
};
</script>

<!-- Dashboard JavaScript -->
<script src="/static/admin/js/dashboard.js"></script> 

<?php include ADMIN_TEMPLATE_PATH . "admin_footer.php";  //footer file ?>


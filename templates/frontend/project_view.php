<?php
include FRONTEND_TEMPLATE_PATH . "header.php"; // header file
include FRONTEND_TEMPLATE_PATH . "navigation.php"; // footer file 
?>

<?php
require_once __DIR__ . '/../../models/ProjectModel.php'; // import Project Model
require_once __DIR__ . '/../../models/CommentModel.php'; // import Comment Model

$projectModel = new Project(); // project model instance
$commentModel = new Comment(); // comment model instance

// Get project instance with passed in GET parameter 'id'  
if (isset($_GET['id'])) {
    $projectId = (int)$_GET['id'];
    $project = $projectModel->getById($projectId);

    if (!$project) {
        $_SESSION['error'] = 'Project not found';
        header('Location: /urls.php?pg=projects');
        exit;
    }
} else {
    $_SESSION['error'] = 'No Project ID provided';
    header('Location: /urls.php?pg=projects');
    exit;
}

require_once __DIR__ . "/../../models/ProjectViewModel.php"; // import projectViewModel 
$viewModel = new ProjectView(); // projectView model instance
$ipHash = hash("sha256", $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']); // anonymous user ip
$cookieName = "viewed_project_$projectId"; // cookie

/**
 * sets cookie for new anonymous visitor and adds view 
 * row for anonymous user (increasing view count only once per ip(anonymous user))
 */
if (!isset($_COOKIE[$cookieName])) {
    setcookie($cookieName, '1', time() + 86400 * 30, '/');
    if (!$viewModel->hasViewed($projectId, $ipHash)) {
        $viewModel->addView($projectId, $ipHash);
    }
}

// Get approved comments for this project
$approvedComments = $commentModel->getApprovedByProject($projectId);
$commentCount = count($approvedComments);

// Prepare images for carousel(removing null fields)  
$images = [$project['image1'], $project['image2'], $project['image3']];
$images = array_filter($images);
?>

<!-- AOS Animation Library -->
<link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">

<!-- external project_view css -->
<link rel="stylesheet" href="/static/base/css/project_view.css">

<!-- Hero Section -->
<section class="project-hero">
    <div class="container">
        <!-- Project Title -->
        <h1 class="project-title" data-aos="fade-up">
            <?= htmlspecialchars($project['title']); ?>
        </h1>

        <!-- Status Badge -->
        <div data-aos="fade-up" data-aos-delay="100">
            <span class="status-badge <?= $project['is_published'] ? 'status-published' : 'status-unpublished'; ?>">
                <i class="fas <?= $project['is_published'] ? 'fa-check-circle' : 'fa-clock-o'; ?>"></i>
                <?= $project['is_published'] ? 'Live & Published' : 'In Development'; ?>
            </span>
        </div>
    </div>
</section>
<!-- Hero section end -->

<!-- Carousel Section -->
<section class="carousel-section">
    <div class="container">
        <?php if (!empty($images)) : ?>
            <div class="carousel-container" data-aos="zoom-in" data-aos-delay="200">
                <div id="projectCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-indicators">
                        <?php foreach ($images as $index => $img) : ?>
                            <button type="button"
                                data-bs-target="#projectCarousel"
                                data-bs-slide-to="<?= $index; ?>"
                                class="<?= ($index === 0) ? 'active' : ''; ?>"
                                aria-current="<?= ($index === 0) ? 'true' : 'false'; ?>"
                                aria-label="Slide <?= $index + 1; ?>">
                            </button>
                        <?php endforeach; ?>
                    </div>

                    <div class="carousel-inner">
                        <?php foreach ($images as $index => $img) : ?>
                            <div class="carousel-item <?= ($index === 0) ? 'active' : ''; ?>">
                                <img src="/media/projects/<?= htmlspecialchars($img); ?>"
                                    class="d-block w-100"
                                    alt="<?= htmlspecialchars($project['title']); ?> - Screenshot <?= $index + 1; ?>"
                                    loading="lazy">
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <?php if (count($images) > 1) : ?>
                        <button class="carousel-control-prev" type="button" data-bs-target="#projectCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#projectCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        <?php else : ?>
            <div class="empty-image-state" data-aos="zoom-in" data-aos-delay="200">
                <i class="fas fa-image"></i>
            </div>
        <?php endif; ?>

    </div>
</section>
<!-- Carousel section end -->

<!-- project details Section -->
<section class="content-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">

                <!-- Description Card -->
                <div class="section-card" data-aos="fade-up">
                    <h2 class="section-title">
                        <i class="fa fa-align-left"></i>
                        Project Overview
                    </h2>
                    <div class="description-text">
                        <?= nl2br(htmlspecialchars($project['description'])); ?>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons" data-aos="fade-up" data-aos-delay="100">
                    <?php if ($project['live_url']) : ?>
                        <a href="<?= htmlspecialchars($project['live_url']); ?>"
                            target="_blank"
                            class="btn-action btn-primary-custom">
                            <i class="fa fa-external-link-alt"></i>
                            View Live Project
                        </a>
                    <?php endif; ?>

                    <?php if ($project['github_url']) : ?>
                        <a href="<?= htmlspecialchars($project['github_url']); ?>"
                            target="_blank"
                            class="btn-action btn-secondary-custom">
                            <i class="fab fa-github"></i>
                            View on GitHub
                        </a>
                    <?php endif; ?>

                    
                </div>

            </div>

            <div class="col-lg-4">

                <!-- Project Info Card -->
                <div class="section-card" data-aos="fade-up" data-aos-delay="200">
                    <h2 class="section-title">
                        <i class="fa fa-info-circle"></i>
                        Project Info
                    </h2>

                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fa fa-calendar-alt"></i>
                                Created
                            </div>
                            <div class="info-value">
                                <?= date('M d, Y', strtotime($project['created_at'])); ?>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">
                                <i class="fa fa-clock"></i>
                                Last Updated
                            </div>
                            <div class="info-value">
                                <?= date('M d, Y', strtotime($project['updated_at'])); ?>
                            </div>
                        </div>

                        <?php if ($project['live_url']) : ?>
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-link"></i>
                                    Live URL
                                </div>
                                <div class="info-value">
                                    <a href="<?= htmlspecialchars($project['live_url']); ?>" target="_blank">
                                        Visit Site
                                    </a>
                                </div>
                            </div>


                        <?php endif; ?>

                        <?php if ($project['github_url']) : ?>
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fab fa-github"></i>
                                    Repository
                                </div>
                                <div class="info-value">
                                    <a href="<?= htmlspecialchars($project['github_url']); ?>" target="_blank">
                                        View Code
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <a href="/urls.php?pg=projects" class="btn-action btn-back">
                        <i class="fa fa-arrow-left"></i>
                        Back to Projects
                    </a>
            </div>
            
        </div>
    </div>
</section>
<!-- project details section end -->

<!-- Comments Section -->
<section class="comments-section" id="comments-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-lg-2">

                <!-- Comment Form div -->
                <div class="comment-form-card" data-aos="fade-up" id="comment-form">
                    <h3 class="comment-form-title">
                        <i class="fas fa-comment-dots"></i>
                        Leave a Comment
                    </h3>

                    <!-- Alert Container (for AJAX feedback) -->
                    <div id="alertContainer"></div>

                    <!-- comments form -->
                    <form id="commentForm" action="/urls.php?pg=process_comments" method="POST" enctype="multipart/form-data" novalidate>
                        <input type="hidden" name="project_id" value="<?= $projectId; ?>">

                        <!-- Honeypot field for spam protection -->
                        <div class="honeypot">
                            <label for="website">Website</label>
                            <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
                        </div>

                        <!-- Name Field -->
                        <div class="form-group">
                            <label for="name" class="form-label">
                                <i class="fas fa-user"></i>
                                Name <span class="required">*</span>
                            </label>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                class="form-control"
                                placeholder="Enter your name"
                                maxlength="100"
                                required
                                value="<?= isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                            <div class="field-hint">
                                <i class="fas fa-info-circle"></i>
                                This will be displayed publicly
                            </div>
                        </div>

                        <!-- Comment Content Field -->
                        <div class="form-group">
                            <label for="content" class="form-label">
                                <i class="fas fa-comment"></i>
                                Comment <span class="required">*</span>
                            </label>
                            <textarea
                                id="content"
                                name="content"
                                class="form-control textarea"
                                placeholder="Share your thoughts about this project..."
                                minlength="10"
                                maxlength="1000"
                                required
                                rows="5"><?= isset($_POST['content']) ? htmlspecialchars($_POST['content']) : ''; ?></textarea>
                            <div class="field-footer">
                                <div class="field-hint">
                                    <i class="fas fa-lightbulb"></i>
                                    Be respectful and constructive
                                </div>
                                <div class="char-counter" id="charCounter">
                                    <span id="charCount">0</span> / 1000
                                </div>
                            </div>
                        </div>

                        <!-- Info Notice -->
                        <div class="info-notice">
                            <i class="fas fa-shield-alt"></i>
                            <div>
                                <strong>Privacy Notice:</strong> Your comment will be reviewed before being published.
                                We respect your privacy and will never share your information.
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" name="submit_comment" class="submit-btn" id="submitBtn">
                            <i class="fas fa-paper-plane"></i>
                            <span>Submit Comment</span>
                        </button>
                    </form>
                    <!-- comments form end -->
                </div>
                <!-- Comment form div end -->

                <!-- Comments List div section -->
                <div class="comments-list-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="comments-header">
                        <h3 class="comments-count">
                            <i class="fas fa-comments"></i>
                            Comments
                            <?php if ($commentCount > 0): ?>
                                <span class="count-badge"><?= $commentCount; ?></span>
                            <?php endif; ?>
                        </h3>
                    </div>

                    <?php if ($commentCount > 0): ?>
                        <div class="comments-list">
                            <?php foreach ($approvedComments as $comment): ?>
                                <div class="comment-item" data-aos="fade-up" data-aos-delay="50">
                                    <div class="comment-header">
                                        <div class="comment-author">
                                            <div class="author-avatar">
                                                <?= strtoupper(substr($comment['name'], 0, 1)); ?>
                                            </div>
                                            <div class="author-info">
                                                <div class="author-name">
                                                    <?= htmlspecialchars($comment['name']); ?>
                                                </div>
                                                <div class="comment-date">
                                                    <i class="far fa-clock"></i>
                                                    <?php
                                                    $commentDate = strtotime($comment['created_at']);
                                                    $now = time();
                                                    $diff = $now - $commentDate;

                                                    if ($diff < 60) {
                                                        echo 'Just now';
                                                    } elseif ($diff < 3600) {
                                                        $minutes = floor($diff / 60);
                                                        echo $minutes . ' minute' . ($minutes > 1 ? 's' : '') . ' ago';
                                                    } elseif ($diff < 86400) {
                                                        $hours = floor($diff / 3600);
                                                        echo $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
                                                    } elseif ($diff < 604800) {
                                                        $days = floor($diff / 86400);
                                                        echo $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
                                                    } else {
                                                        echo date('M d, Y', $commentDate);
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="comment-content">
                                        <?= nl2br(htmlspecialchars($comment['content'])); ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="no-comments">
                            <i class="far fa-comment-dots"></i>
                            <p>No comments yet. Be the first to share your thoughts!</p>
                        </div>
                    <?php endif; ?>
                </div>
                <!-- comments list div section end -->

            </div>
        </div>
    </div>
</section>
<!-- comments section end -->

<!-- jquery script -->
<script src="/static/base/js/jquery.js"></script>

<!-- external comments js script -->
<script src="/static/base/js/comments.js"></script>

<script>
    // Add this RIGHT AFTER your jquery.js script
    console.log('jQuery loaded:', typeof jQuery !== 'undefined');
    console.log('$ loaded:', typeof $ !== 'undefined');
</script>
<!-- AOS Animation Script -->
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>
    AOS.init({
        duration: 800,
        once: true,
        mirror: false
    });
</script>

<?php include FRONTEND_TEMPLATE_PATH . "footer.php"; // footer file?>
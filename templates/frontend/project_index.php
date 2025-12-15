<?php include FRONTEND_TEMPLATE_PATH . "header.php"; // header file ?>
<?php include FRONTEND_TEMPLATE_PATH . "navigation.php"; // navigation file ?>

<?php
require_once __DIR__ . '/../../models/ProjectModel.php'; // import project model
require_once __DIR__ . '/../../core/Settings.php'; // import settings

$projectModel = new Project(); // project model instance

// Get settings for pagination
$settings = AppSettings::getInstance();
$contentSettings = $settings->getContentSettings();

// Pagination setup
$perPage = $contentSettings['projects_per_page'] ?: PROJECTS_PER_PAGE;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $perPage;

// Total projects
$totalProjects = $projectModel->count();
$totalPages = ceil($totalProjects / $perPage);

// Fetch projects for current page (only published ones for public)
$projects = $projectModel->getPublishedPaginated($start, $perPage);
?>

<!-- AOS Animation Library -->
<link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">

<!-- Projects Page Styles -->
<link href="/static/base/css/projects.css" rel="stylesheet">
<link href="/static/base/css/feedback_buttons.css" rel="stylesheet">

<!-- Projects Hero Section -->
<section class="projects-hero">
    <div class="container">
        <div class="hero-content">

            <!-- Top Badge -->
            <div class="top-badge" data-aos="fade-down" data-aos-duration="800">
                <span class="badge-icon">✨</span>
                <span class="badge-text">Portfolio Showcase</span>
            </div>

            <!-- Main Heading -->
            <h1 class="projects-title" data-aos="fade-down" data-aos-duration="1000" data-aos-delay="100">
                Projects
                <span class="title-gradient">Portfolio</span>
            </h1>

            <!-- Subtitle -->
            <p class="projects-subtitle" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200">
                Discover the <span class="highlight-text">innovative solutions</span> and <span class="highlight-text">web applications</span> I've crafted.
                <br>
                Each project represents a commitment to <strong>quality</strong>, <strong>functionality</strong>, and <strong>user experience</strong>.
            </p>

            <!-- Stats Grid -->
            <div class="projects-stats" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="300">

                <!-- Main Project Count -->
                <div class="stat-card stat-primary">
                    <div class="stat-icon">
                        <i class="fas fa-rocket"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number"><?= $totalProjects; ?></div>
                        <div class="stat-label">Projects Completed</div>
                    </div>
                    <div class="stat-glow"></div>
                </div>

                <!-- Technologies Used -->
                <div class="stat-card">
                    <div class="stat-icon stat-icon-secondary">
                        <i class="fas fa-code"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">15+</div>
                        <div class="stat-label">Technologies</div>
                    </div>
                </div>

                <!-- Hours Invested -->
                <div class="stat-card">
                    <div class="stat-icon stat-icon-tertiary">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">10,000+</div>
                        <div class="stat-label">Hours Invested</div>
                    </div>
                </div>

                <!-- Client Satisfaction -->
                <div class="stat-card">
                    <div class="stat-icon stat-icon-success">
                        <i class="fas fa-heart"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">100%</div>
                        <div class="stat-label">Client Satisfaction</div>
                    </div>
                </div>

            </div>

            <!-- Tech Stack Icons -->
            <div class="tech-stack" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="400">
                <p class="tech-label">Built with modern technologies</p>
                <div class="tech-icons">
                    <div class="tech-icon" title="PHP">
                        <i class="fab fa-php"></i>
                    </div>
                    <div class="tech-icon" title="JavaScript">
                        <i class="fab fa-js"></i>
                    </div>
                    <div class="tech-icon" title="React">
                        <i class="fab fa-react"></i>
                    </div>
                    <div class="tech-icon" title="Node.js">
                        <i class="fab fa-node-js"></i>
                    </div>
                    <div class="tech-icon" title="Python">
                        <i class="fab fa-python"></i>
                    </div>
                    <div class="tech-icon" title="Database">
                        <i class="fas fa-database"></i>
                    </div>
                    <div class="tech-icon" title="HTML5">
                        <i class="fab fa-html5"></i>
                    </div>
                    <div class="tech-icon" title="CSS3">
                        <i class="fab fa-css3-alt"></i>
                    </div>
                </div>
            </div>

            <!-- Scroll Down Indicator -->
            <div class="scroll-indicator" data-aos="fade" data-aos-duration="1000" data-aos-delay="500">
                <div class="scroll-mouse">
                    <div class="scroll-wheel"></div>
                </div>
                <p class="scroll-text">Scroll to explore</p>
            </div>

        </div>
    </div>

    <!-- Animated Background -->
    <div class="projects-hero-bg">
        <div class="bg-circle circle-1"></div>
        <div class="bg-circle circle-2"></div>
        <div class="bg-circle circle-3"></div>
        <div class="bg-grid"></div>
    </div>
</section>
<!-- Projects Hero Section end -->


<!-- Projects Grid Section -->
<section class="projects-grid-section">
    <div class="container">

        <div class="projects-toolbar" data-aos="fade-down">
            <div class="toolbar-left">
                <h3 class="section-subtitle">
                    <span class="subtitle-icon">🚀</span>
                    Explore My Work
                </h3>
            </div>
            <div class="toolbar-right">
                <span class="results-count">
                    <i class="fas fa-layer-group"></i>
                    Showing <?= count($projects); ?> of <?= $totalProjects; ?> projects
                </span>
            </div>
        </div>

        <div class="row g-4">
            <?php if (!empty($projects)) : ?>
                <?php foreach ($projects as $index => $project) : ?>
                    <?php
                    // Prepare project images for carousel(removing null fields)
                    $images = array_filter([$project['image1'], $project['image2'], $project['image3']]);
                    ?>

                    <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="<?= ($index % 3) * 100; ?>">
                        <div class="project-card-modern">

                            <!-- Image Section with Overlay -->
                            <div class="project-image-wrapper">
                                <?php if (!empty($images)) : ?>
                                    <div id="carouselProject<?= $project['id']; ?>" class="carousel slide project-carousel" data-bs-ride="false">
                                        <div class="carousel-inner">
                                            <?php foreach ($images as $imgIndex => $img) : ?>
                                                <div class="carousel-item <?= ($imgIndex === 0) ? 'active' : ''; ?>">
                                                    <img src="/media/projects/<?= htmlspecialchars($img); ?>"
                                                        class="project-image"
                                                        alt="<?= htmlspecialchars($project['title']); ?>"
                                                        loading="lazy">
                                                    <div class="image-overlay"></div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>

                                        <!-- Carousel Controls -->
                                        <?php if (count($images) > 1) : ?>
                                            <button class="carousel-control-prev" type="button"
                                                data-bs-target="#carouselProject<?= $project['id']; ?>"
                                                data-bs-slide="prev">
                                                <span class="carousel-control-icon">
                                                    <i class="fas fa-chevron-left"></i>
                                                </span>
                                            </button>
                                            <button class="carousel-control-next" type="button"
                                                data-bs-target="#carouselProject<?= $project['id']; ?>"
                                                data-bs-slide="next">
                                                <span class="carousel-control-icon">
                                                    <i class="fas fa-chevron-right"></i>
                                                </span>
                                            </button>

                                            <!-- Image Counter -->
                                            <div class="image-counter">
                                                <i class="fas fa-images"></i>
                                                <span><?= count($images); ?></span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php else : ?>
                                    <div class="project-placeholder">
                                        <i class="fas fa-code fa-4x"></i>
                                        <div class="image-overlay"></div>
                                    </div>
                                <?php endif; ?>

                                <!-- Quick View Button -->
                                <a href="/urls.php?pg=view&id=<?= $project['id']; ?>" class="quick-view-btn">
                                    <i class="fas fa-eye"></i>
                                    <span>Quick View</span>
                                </a>

                                <!-- Status Badge -->
                                <?php if ($project['is_published']) : ?>
                                    <div class="status-badge-live">
                                        <span class="status-dot"></span>
                                        Live
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Card Content -->
                            <div class="project-content">

                                <!-- Category Tag (if you have categories) -->
                                <div class="project-category">
                                    <i class="fas fa-tag"></i>
                                    <span>Web Development</span>
                                </div>

                                <!-- Project Title -->
                                <h3 class="project-title">
                                    <a href="/urls.php?pg=view&id=<?= $project['id']; ?>">
                                        <?= htmlspecialchars($project['title']); ?>
                                    </a>
                                </h3>

                                <!-- Project Description -->
                                <p class="project-description">
                                    <?= htmlspecialchars(substr($project['description'], 0, 130)); ?><?= strlen($project['description']) > 130 ? '...' : ''; ?>
                                </p>

                                <!-- Metrics Row (like button, views and comments) -->
                                <?php
                                require_once MODELS_PATH . 'ProjectLikeModel.php'; // import ProjectLikeModel
                                require_once MODELS_PATH . 'ProjectViewModel.php'; // import ProjectViewModel
                                require_once MODELS_PATH . 'CommentModel.php'; // import CommentModel

                                require_once FRONTEND_TEMPLATE_PATH . 'feedback_buttons.php'; // import feedback_buttons functions

                                $id = $project['id']; // project id
                                $likeModel = new ProjectLike(); // likemodel instance
                                $viewModel = new ProjectView(); // viewmodel instance
                                $commentModel = new Comment(); // commentmodel instance

                                // Anonymous unique hash
                                $ipHash = hash('sha256', $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']);

                                $comments= $commentModel->countApprovedByProject($project['id']); // comments

                                $views = $viewModel->countByProject($project['id']); // views

                                $likes = $likeModel->countByProject($project['id']); // likes

                                $hasLiked = $likeModel->hasLiked($project['id'], $ipHash); // boolean value hasliked

                                ?>

                                
                                <!-- Metrics Row (like button, views and comments) -->
                                <div class="project-metrics">

                                    <?php
                                    // --- Comments ---
                                    echo renderCommentsButton($id, $comments);

                                    // --- Views ---
                                    echo renderViewBadge($views);

                                    // --- Likes 
                                    echo renderLikeButton($id, $likes, $hasLiked);
                                    ?>

                                </div>

                            </div>
                        </div>

                    <?php endforeach; ?>

                <?php else : ?>
                    <!-- Empty State (if no projects)-->
                    <div class="col-12">
                        <div class="empty-state-modern">
                            <div class="empty-icon">
                                <i class="fas fa-rocket"></i>
                            </div>
                            <h3 class="empty-title">New Projects Coming Soon</h3>
                            <p class="empty-description">
                                I'm currently working on exciting new projects.
                                <br>Check back soon to see what I've been building!
                            </p>
                            <a href="/urls.php?pg=contact" class="btn-empty-cta">
                                <i class="fas fa-paper-plane me-2"></i>
                                Get Notified
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
                    </div>

                    <!-- Pagination -->
                    <?php if ($totalPages > 1) : ?>
                        <nav aria-label="Projects pagination" class="pagination-wrapper" data-aos="fade-up">
                            <div class="pagination-modern">

                                <!-- Previous Button -->
                                <a href="?pg=project&page=<?= max(1, $page - 1); ?>"
                                    class="pagination-btn pagination-prev <?= ($page <= 1) ? 'disabled' : ''; ?>"
                                    <?= ($page <= 1) ? 'aria-disabled="true"' : ''; ?>>
                                    <i class="fas fa-chevron-left"></i>
                                    <span>Previous</span>
                                </a>

                                <!-- Page Numbers -->
                                <div class="pagination-numbers">
                                    <?php
                                    // Smart pagination - show max 7 pages
                                    $range = 2; // Pages to show on each side of current
                                    $start = max(1, $page - $range);
                                    $end = min($totalPages, $page + $range);

                                    // Show first page if not in range
                                    if ($start > 1) {
                                        echo '<a href="?pg=project&page=1" class="page-number">1</a>';
                                        if ($start > 2) {
                                            echo '<span class="page-dots">...</span>';
                                        }
                                    }

                                    // Show page numbers in range
                                    for ($i = $start; $i <= $end; $i++) {
                                        $activeClass = ($i == $page) ? 'active' : '';
                                        echo "<a href='?pg=project&page={$i}' class='page-number {$activeClass}'>{$i}</a>";
                                    }

                                    // Show last page if not in range
                                    if ($end < $totalPages) {
                                        if ($end < $totalPages - 1) {
                                            echo '<span class="page-dots">...</span>';
                                        }
                                        echo "<a href='?pg=project&page={$totalPages}' class='page-number'>{$totalPages}</a>";
                                    }
                                    ?>
                                </div>

                                <!-- Next Button -->
                                <a href="?pg=project&page=<?= min($totalPages, $page + 1); ?>"
                                    class="pagination-btn pagination-next <?= ($page >= $totalPages) ? 'disabled' : ''; ?>"
                                    <?= ($page >= $totalPages) ? 'aria-disabled="true"' : ''; ?>>
                                    <span>Next</span>
                                    <i class="fas fa-chevron-right"></i>
                                </a>

                            </div>

                            <!-- Page Info -->
                            <div class="pagination-info">
                                Page <?= $page; ?> of <?= $totalPages; ?>
                            </div>
                        </nav>
                    <?php endif; ?>

        </div>
</section>

<!-- jquery script -->
<script src="/static/base/js/jquery.js"></script>

<!-- AOS Animation Script -->
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>

<!-- external projects script -->
<script src="/static/base/js/projects.js"></script>

<?php include FRONTEND_TEMPLATE_PATH . "footer.php"; // footer file ?>
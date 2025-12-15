<?php
include ADMIN_TEMPLATE_PATH . "admin_header.php";  // admin header file
include ADMIN_TEMPLATE_PATH . "admin_navigation.php";  // admin navigation file
?>

<?php
require_once __DIR__ . '/../../models/ProjectModel.php'; // import ProjectModel
$projectModel = new Project(); // Project model instance

// retrieve project row for passed in GET parameter 'id'
if (isset($_GET['id'])) {
    $projectId = (int)$_GET['id'];
    $project = $projectModel->getById($projectId); // retrieve project row

    if (!$project) {
        $_SESSION['error'] = 'Project not found';
        header('Location: /urls.php?pg=project_all');
        exit;
    }
} else {
    $_SESSION['error'] = 'No project ID provided';
    header('Location: /urls.php?pg=project_all');
    exit;
}

// Prepare images for carousel  
$images = [$project['image1'], $project['image2'], $project['image3']];
$images = array_filter($images); // filters out null fields of image 
?>

<!-- AOS Animation Library -->
<link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
<!-- external project_view css -->
<link href="/static/admin/css/project_view.css" rel="stylesheet">

<!-- project view div wrapper -->
<div class="admin-project-view">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-11 col-xl-10">

                <!-- Page Header -->
                <div class="page-header" data-aos="fade-down">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <h3 class="mb-0"><?= htmlspecialchars($project['title']); ?></h3>
                        <span class="header-status-badge <?= $project['is_published'] ? 'status-published' : 'status-unpublished'; ?>">
                            <i class="fas <?= $project['is_published'] ? 'fa-check-circle' : 'fa-clock'; ?>"></i>
                            <?= $project['is_published'] ? 'Published' : 'Draft'; ?>
                        </span>
                    </div>
                </div>
                <!-- Page header end -->

                <!-- Carousel Section -->
                <?php if (!empty($images)) : ?>
                    <div class="carousel-section" data-aos="zoom-in" data-aos-delay="100">
                        <div class="carousel-wrapper">
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
                                                alt="Project Image <?= $index + 1; ?>"
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
                    </div>
                <?php else : ?>
                    <div class="carousel-section" data-aos="zoom-in" data-aos-delay="100">
                        <div class="empty-image">
                            <i class="fas fa-image"></i>
                        </div>
                    </div>
                <?php endif; ?>
                <!-- Carousel section end -->

                <!-- Project Details Card -->
                <div class="info-card" data-aos="fade-up" data-aos-delay="200">
                    <h4 class="card-title-custom">
                        <i class="fas fa-info-circle"></i>
                        Project Details
                    </h4>

                    <!-- Description -->
                    <div class="description-section">
                        <div class="description-label">
                            <i class="fas fa-align-left"></i>
                            Description
                        </div>
                        <div class="description-text">
                            <?= nl2br(htmlspecialchars($project['description'])); ?>
                        </div>
                    </div>
                    <!-- Description end -->

                    <!-- URLs Grid -->
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-external-link-alt"></i>
                                Live URL
                            </div>
                            <div class="info-value">
                                <?php if ($project['live_url']) : ?>
                                    <a href="<?= htmlspecialchars($project['live_url']); ?>" target="_blank">
                                        <?= htmlspecialchars($project['live_url']); ?>
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">Not available</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">
                                <i class="fab fa-github"></i>
                                GitHub Repository
                            </div>
                            <div class="info-value">
                                <?php if ($project['github_url']) : ?>
                                    <a href="<?= htmlspecialchars($project['github_url']); ?>" target="_blank">
                                        <?= htmlspecialchars($project['github_url']); ?>
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">Not available</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <!-- urls grid end -->

                    <!-- Metadata -->
                    <div class="metadata-grid">
                        <div class="metadata-item">
                            <strong><i class="fas fa-toggle-on"></i> Status</strong>
                            <span class="status-badge <?= $project['is_published'] ? 'badge-published' : 'badge-unpublished'; ?>">
                                <i class="fas <?= $project['is_published'] ? 'fa-check-circle' : 'fa-clock'; ?>"></i>
                                <?= $project['is_published'] ? 'Published' : 'Unpublished'; ?>
                            </span>
                        </div>

                        <div class="metadata-item">
                            <strong><i class="fas fa-calendar-plus"></i> Created At</strong>
                            <span><?= date('M d, Y • h:i A', strtotime($project['created_at'])); ?></span>
                        </div>

                        <div class="metadata-item">
                            <strong><i class="fas fa-calendar-check"></i> Last Updated</strong>
                            <span><?= date('M d, Y • h:i A', strtotime($project['updated_at'])); ?></span>
                        </div>

                        <div class="metadata-item">
                            <strong><i class="fas fa-hashtag"></i> Project ID</strong>
                            <span>#<?= $project['id']; ?></span>
                        </div>
                    </div>
                    <!-- metadata end -->
                </div>
                <!-- project detail card end -->

                <!-- Action Buttons -->
                <div class="action-buttons" data-aos="fade-up" data-aos-delay="300">
                    <a href="/urls.php?pg=project_all" class="btn-action btn-back">
                        <i class="fas fa-arrow-left"></i>
                        Back to Projects
                    </a>

                    <a href="/urls.php?pg=project_edit&id=<?= $project['id']; ?>" class="btn-action btn-edit">
                        <i class="fas fa-edit"></i>
                        Edit Project
                    </a>

                    <button class="btn-action btn-delete"
                        onclick="triggerDeleteModal('/urls.php?pg=project_delete&id=<?= $projectId ?>','Delete Project','Are you sure you want to delete this project? This action cannot be undone.')">
                        <i class="fas fa-trash-alt"></i>
                        Delete Project
                    </button>
                </div>
                <!-- action buttons end -->

            </div>
        </div>
    </div>
</div>

<!-- AOS Animation Script -->
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>
    // AOS Animation Library Initialization
    AOS.init({
        duration: 800,
        once: true,
        mirror: false
    });
</script>

<?php include ADMIN_TEMPLATE_PATH . "admin_footer.php"; // admin footer file ?>
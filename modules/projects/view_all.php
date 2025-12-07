<?php
include ADMIN_TEMPLATE_PATH . "admin_header.php";
include ADMIN_TEMPLATE_PATH . "admin_navigation.php";

require_once __DIR__ . '/../../models/ProjectModel.php';
$projectModel = new Project();

// Pagination setup
$perPage = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $perPage;

// Filter by status
$statusFilter = $_GET['status'] ?? null;

// Total count for pagination + fetch projects
if ($statusFilter !== null) {
    $status = ($statusFilter === 'published' ? 1 : 0);
    $totalProjects = $projectModel->countByStatus($status);
    $projects = $projectModel->getPaginatedByStatus($status, $start, $perPage);
} else {
    // Show ALL projects when no filter is applied
    $totalProjects = $projectModel->count();
    $projects = $projectModel->getPaginatedAll($start, $perPage);
}

$totalPages = ($perPage > 0) ? (int)ceil($totalProjects / $perPage) : 1;

msg_error();
msg_success();
?>

<link rel="stylesheet" href="/static/admin/css/projects_admin.css">

<h2 class="mb-4">All Projects</h2>
<div class="row mb-3 g-2 align-items-center">

    <!-- Filter -->
    <div class="col-12 col-md-auto">
        <form method="GET" class="w-100 w-md-auto">
            <input type="hidden" name="pg" value="project_all">
            <select name="status"
                class="form-select form-select-sm w-100 w-md-auto"
                onchange="this.form.submit()">
                <option value="">All</option>
                <option value="draft" <?= ($statusFilter === 'draft') ? 'selected' : ''; ?>>Draft</option>
                <option value="published" <?= ($statusFilter === 'published') ? 'selected' : ''; ?>>Published</option>
            </select>
        </form>
    </div>

    <!-- Action Buttons -->
    <div class="col-12 col-md-auto d-flex flex-wrap gap-2">
        <button class="btn btn-sm btn-danger flex-fill flex-md-grow-0"
            onclick="triggerDeleteModal('/urls.php?pg=project_delete&all=true',
            'Delete All Projects', 'Are you sure you want to delete ALL projects?')">
            Delete All
        </button>
        <a href="/urls.php?pg=project_add"
            class="btn btn-sm btn-success flex-fill flex-md-grow-0">Add New Project</a>
    </div>
</div>

<!-- Page dim element for subtle style when modal is open -->
<div class="page-overlay-dim" aria-hidden="true"></div>

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Title</th>
                <th>Description</th>
                <th>Status</th>
                <th>Created</th>
                <th colspan="4">Actions</th>
            </tr>
        </thead>

        <tbody>
            <?php if (!empty($projects)) : ?>
                <?php foreach ($projects as $project) :
                    $id = (int)$project['id'];
                    $titleAttr = htmlspecialchars($project['title'], ENT_QUOTES);

                    // Encode description for safe modal
                    $descriptionEncoded = base64_encode($project['description']);
                ?>
                    <tr>
                        <td>#<?= $id; ?></td>
                        <td>
                            <?php if ($project['image1']) : ?>
                                <img src="/media/projects/<?= htmlspecialchars($project['image1']); ?>"
                                     alt="Project Image" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                            <?php else : ?>
                                <div class="bg-light d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; border-radius: 5px;">
                                    <i class="fas fa-image text-muted"></i>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <strong><?= htmlspecialchars($project['title']); ?></strong>
                        </td>
                        <td>
                            <?= htmlspecialchars(mb_strimwidth($project['description'], 0, 50, '...')); ?>
                        </td>
                        <td>
                            <?php if ((int)$project['is_published'] === 0) : ?>
                                <a href="/urls.php?pg=project_publish&id=<?= $id ?>"
                                    class="btn btn-sm btn-warning"
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="top"
                                    title="Click to publish this project">
                                    Draft
                                </a>
                            <?php else : ?>
                                <span class="btn btn-sm btn-success">Published</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?= date('d M Y, H:i', strtotime($project['created_at'])); ?>
                        </td>

                        <td>
                            <a href="/urls.php?pg=project&id=<?= $id ?>"
                                class="btn btn-sm btn-primary">View</a>
                        </td>

                        <td>
                            <a href="/urls.php?pg=project_edit&id=<?= $id ?>"
                                class="btn btn-sm btn-warning">Edit</a>
                        </td>

                        <td>
                            <button class="btn btn-sm btn-danger"
                                onclick="triggerDeleteModal('/urls.php?pg=project_delete&id=<?= $id ?>',
                                'Delete Project',
                                'Are you sure you want to delete this project?')">
                                Delete
                            </button>
                        </td>

                        <td>
                            <?php if ((int)$project['is_published'] === 0) : ?>
                                <a href="/urls.php?pg=project_publish&id=<?= $id ?>"
                                    class="btn btn-sm btn-success">Publish</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>

            <?php else : ?>
                <tr>
                    <td colspan="10" class="text-center">No projects found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Pagination -->
    <?php if ($totalPages > 1) : ?>
        <nav aria-label="Pagination">
            <ul class="pagination justify-content-center">
                <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                    <li class="page-item <?= ($i == $page) ? 'active' : ''; ?>">
                        <a class="page-link"
                            href="?pg=project_all&page=<?= $i; ?><?= $statusFilter ? '&status=' . urlencode($statusFilter) : ''; ?>">
                            <?= $i; ?>
                        </a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    <?php endif; ?>
</div>



<?php include ADMIN_TEMPLATE_PATH . "admin_footer.php"; ?>

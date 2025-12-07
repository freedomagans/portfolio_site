<?php
include ADMIN_TEMPLATE_PATH . "admin_header.php";
include ADMIN_TEMPLATE_PATH . "admin_navigation.php";
?>

<?php
require_once __DIR__ . '/../../models/ProjectModel.php'; // import project db model class
$projectModel = new Project(); // project instance

if (isset($_GET['id'])) {
    /**
     * retrieve project row for specified id 
     * if id not specified return error msg
     */
    $project = $projectModel->getById($_GET['id']);
    if (!$project) {
        $_SESSION['error'] = "Project not found!"; // error msg
        header("Location: ?pg=project_all"); // redirect to projects page
        exit;
    }
}
?>

<h2 class="mb-4">Edit Project</h2>

<?php
msg_success();
msg_error();
?>

<form method="POST" enctype="multipart/form-data" action="/urls.php?pg=project_process_edit&id=<?= $project['id'] ?>">
    <div class="mb-3">
        <label class="form-label">Project Title</label>
        <input type="text" class="form-control" name="title" value="<?= htmlspecialchars($project['title']); ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea class="form-control" name="description" rows="5" required><?= htmlspecialchars($project['description']); ?></textarea>
    </div>

    <div class="mb-3">
        <label>Main Image</label>
        <input type="file" class="form-control mb-1" name="image1" accept="image/*">
        <small>Current: <?= $project['image1']; ?></small>
    </div>

    <div class="mb-3">
        <label>Image 2 (Optional)</label>
        <input type="file" class="form-control mb-1" name="image2" accept="image/*">
        <small>Current: <?= $project['image2'] ?? 'None'; ?></small>
    </div>

    <div class="mb-3">
        <label>Image 3 (Optional)</label>
        <input type="file" class="form-control mb-1" name="image3" accept="image/*">
        <small>Current: <?= $project['image3'] ?? 'None'; ?></small>
    </div>

    <div class="mb-3">
        <label>Live URL</label>
        <input type="url" class="form-control" name="live_url" value="<?= htmlspecialchars($project['live_url']); ?>">
    </div>

    <div class="mb-3">
        <label>GitHub URL</label>
        <input type="url" class="form-control" name="github_url" value="<?= htmlspecialchars($project['github_url']); ?>">
    </div>

    <div class="form-check mb-3">
        <input type="checkbox" class="form-check-input" name="is_published" <?= $project['is_published'] ? 'checked' : ''; ?> >
        <label class="form-check-label">Publish this project</label>
    </div>

    <button type="submit" class="btn btn-success">Update Project</button>
</form>

<?php include ADMIN_TEMPLATE_PATH . "admin_footer.php"; ?>
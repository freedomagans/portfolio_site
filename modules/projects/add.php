<?php
include ADMIN_TEMPLATE_PATH . "admin_header.php"; // admin header file
include ADMIN_TEMPLATE_PATH . "admin_navigation.php"; // admin navigation file
?>

<!-- header -->
<h2 class="mb-4">Add New Project</h2>

<?php
// show feedback messages
msg_error(); // error msg 
msg_success(); // success msg
?>

<!-- Add project Form -->
<form method="POST" enctype="multipart/form-data" action="/urls.php?pg=project_process_add">
    <div class="mb-3">
        <label for="title" class="form-label">Project Title</label>
        <input type="text" class="form-control" id="title" name="title" required>
    </div>

    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control" id="description" name="description" rows="5" required></textarea>
    </div>

    <div class="mb-3">
        <label for="image1" class="form-label">Main Image (required)</label>
        <input type="file" class="form-control" id="image1" name="image1" accept="image/*" required>
    </div>

    <div class="mb-3">
        <label for="image2" class="form-label">Optional Image 2</label>
        <input type="file" class="form-control" id="image2" name="image2" accept="image/*">
    </div>

    <div class="mb-3">
        <label for="image3" class="form-label">Optional Image 3</label>
        <input type="file" class="form-control" id="image3" name="image3" accept="image/*">
    </div>

    <div class="mb-3">
        <label for="live_url" class="form-label">Live URL</label>
        <input type="url" class="form-control" id="live_url" name="live_url">
    </div>

    <div class="mb-3">
        <label for="github_url" class="form-label">GitHub URL</label>
        <input type="url" class="form-control" id="github_url" name="github_url">
    </div>

    <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox" id="is_published" name="is_published" checked>
        <label class="form-check-label" for="is_published">Publish this project</label>
    </div>

    <button type="submit" class="btn btn-success">Add Project</button>
</form>

<?php include ADMIN_TEMPLATE_PATH . "admin_footer.php"; // admin footer file ?>
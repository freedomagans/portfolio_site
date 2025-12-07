<?php
include ADMIN_TEMPLATE_PATH . "admin_header.php";
include ADMIN_TEMPLATE_PATH . "admin_navigation.php";

require_once __DIR__ . '/../../models/CommentModel.php';
$commentModel = new Comment();

// Pagination setup
$perPage = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $perPage;

// Filter by status
$statusFilter = $_GET['status'] ?? null;

// Total count for pagination + fetch comments
if ($statusFilter !== null) {
    $status = ($statusFilter === 'approved' ? 1 : 0);
    $totalComments = $commentModel->countByStatus($status);
    $comments = $commentModel->getPaginatedByStatus($status, $start, $perPage);
} else {
    // Show ALL comments when no filter is applied
    $totalComments = $commentModel->count(); 
    $comments = $commentModel->getPaginatedAll($start, $perPage); 
}

$totalPages = ($perPage > 0) ? (int)ceil($totalComments / $perPage) : 1;

msg_error();
msg_success();
?>

<link rel="stylesheet" href="/static/admin/css/comments.css">

<h2 class="mb-4">Project Comments</h2>
<div class="row mb-3 g-2 align-items-center">

    <!-- Filter -->
    <div class="col-12 col-md-auto">
        <form method="GET" class="w-100 w-md-auto">
            <input type="hidden" name="pg" value="comment_all">
            <select name="status"
                class="form-select form-select-sm w-100 w-md-auto"
                onchange="this.form.submit()">
                <option value="">All</option>
                <option value="pending" <?= ($statusFilter === 'pending') ? 'selected' : ''; ?>>Pending</option>
                <option value="approved" <?= ($statusFilter === 'approved') ? 'selected' : ''; ?>>Approved</option>
            </select>
        </form>
    </div>

    <!-- Action Buttons -->
    <div class="col-12 col-md-auto d-flex flex-wrap gap-2">
        <button class="btn btn-sm btn-danger flex-fill flex-md-grow-0"
            onclick="triggerDeleteModal('/urls.php?pg=comment_delete&all=true',
            'Delete All Comments', 'Are you sure you want to delete ALL comments?')">
            Delete All
        </button>
        <a href="/urls.php?pg=comment_approve&all=approve"
            class="btn btn-sm btn-success flex-fill flex-md-grow-0">Approve All</a>
        <a href="/urls.php?pg=comment_approve&all=disapprove"
            class="btn btn-sm btn-danger flex-fill flex-md-grow-0">Disapprove All</a>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>Name</th>
                <th>Project</th>
                <th>Comment</th>
                <th>Date</th>
                <th>Status</th>
                <th colspan="3">Actions</th>
            </tr>
        </thead>

        <tbody>
            <?php if (!empty($comments)) : ?>
                <?php foreach ($comments as $c) :
                    $id = (int)$c['id'];
                    $project = htmlspecialchars($c['project_title']); // From JOIN
                    $nameAttr = htmlspecialchars($c['name'], ENT_QUOTES);

                    // Encode comment for safe modal
                    $commentEncoded = base64_encode($c['content']);
                ?>
                    <tr>
                        <td><?= htmlspecialchars($c['name']); ?></td>
                        <td><?= $project; ?></td>
                        <td><?= htmlspecialchars(mb_strimwidth($c['content'], 0, 50, '...')); ?></td>
                        <td><?= date('d M Y, H:i', strtotime($c['created_at'])); ?></td>
                        <td>
                            <?php if ((int)$c['is_approved'] === 0) : ?>
                                <a href="/urls.php?pg=comment_approve&id=<?= $id ?>&action=approve"
                                    class="btn btn-sm btn-warning"
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="top"
                                    title="Click to approve this comment">
                                    Pending
                                </a>
                            <?php else : ?>
                                <a href="/urls.php?pg=comment_approve&id=<?= $id ?>&action=disapprove"
                                    class="btn btn-sm btn-success"
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="top"
                                    title="Click to disapprove this comment">
                                    Approved
                                </a>
                            <?php endif; ?>
                        </td>

                        <td>
                            <button type="button"
                                class="btn btn-sm btn-primary view-comment-btn"
                                data-comment-id="<?= $id; ?>"
                                data-name="<?= $nameAttr; ?>"
                                data-project="<?= $project; ?>"
                                data-comment="<?= $commentEncoded; ?>">
                                View
                            </button>
                        </td>

                        <td>
                            <button class="btn btn-sm btn-danger"
                                onclick="triggerDeleteModal('/urls.php?pg=comment_delete&id=<?= $id ?>',
                                'Delete Comment',
                                'Are you sure you want to delete this comment?')">
                                Delete
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>

            <?php else : ?>
                <tr>
                    <td colspan="7" class="text-center">No comments found.</td>
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
                            href="?pg=comment_all&page=<?= $i; ?><?= $statusFilter ? '&status=' . urlencode($statusFilter) : ''; ?>">
                            <?= $i; ?>
                        </a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    <?php endif; ?>
</div>

<!-- Reusable View Modal -->
<div class="modal fade" id="viewCommentModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Comment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <p><strong>Project:</strong> <span id="cProject"></span></p>
                <hr>
                <div id="cMessage" class="whitespace-pre-wrap"></div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                    data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="/static/admin/js/comments.js"></script>

<?php include ADMIN_TEMPLATE_PATH . "admin_footer.php"; ?>
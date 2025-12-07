<?php
include ADMIN_TEMPLATE_PATH . "admin_header.php";
include ADMIN_TEMPLATE_PATH . "admin_navigation.php";
?>

<link rel="stylesheet" href="/static/admin/css/profile.css">

<div class="row fade-in">
    <div class="col-lg-9 mx-auto">

        <div class="d-flex align-items-center mb-4">
            <i class="fa-solid fa-user-circle fa-2x me-2 text-primary"></i>
            <h3 class="section-title mb-0">My Profile</h3>
        </div>

        <?php
        require_once __DIR__ . '/../../models/UserModel.php';
        $userModel = new User();
        $currentUser = $userModel->getByUsername($_SESSION['username']);

        msg_success();
        msg_error();
        ?>

        <div class="card profile-card shadow-lg border-0 p-4">

            <div class="text-center mb-4">
                <i class="fa-solid fa-circle-user fa-5x text-secondary"></i>
                <p class="mt-2 text-muted mb-0">Manage your account settings</p>
            </div>

            <!-- Profile Form -->
            <form method="post" action="/urls.php?pg=profile_update">

                <div class="mb-3">
                    <label class="form-label fw-semibold">Username</label>
                    <input type="text"
                           name="username"
                           value="<?= htmlspecialchars($currentUser['username']) ?>"
                           class="form-control form-control-lg rounded-3">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email"
                           name="user_email"
                           value="<?= htmlspecialchars($currentUser['email']) ?>"
                           class="form-control form-control-lg rounded-3">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Password (leave empty to keep old)</label>
                    <input type="password"
                           name="user_password"
                           placeholder="password"
                           class="form-control form-control-lg rounded-3">
                </div>

                <button type="submit"
                        name="edit_user"
                        class="btn btn-primary btn-lg w-100 rounded-pill mt-3">
                    <i class="fa-solid fa-save me-2"></i>Save Changes
                </button>

            </form>

        </div>
    </div>
</div>

<?php include ADMIN_TEMPLATE_PATH . "admin_footer.php"; ?>
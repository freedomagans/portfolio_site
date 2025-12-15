<?php
include FRONTEND_TEMPLATE_PATH . 'header.php'; // admin header file
include FRONTEND_TEMPLATE_PATH . 'navigation.php'; // admin navigation  file
?>

<?php
require_once __DIR__ . '/../../models/UserModel.php'; // import userModel
require_once __DIR__ . '/../../core/Settings.php'; // import settings

/**
 * on submitting login credentials
 * the user is authenticated with the defined logout method
 * of the User Model instance;
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$username = trim($_POST['username']); // retrieve username;
	$password = trim($_POST['password']); // retrieve password;
	$user = new User(); // instantiate user instance;

	// Get security settings
	$settings = AppSettings::getInstance();
	$securitySettings = $settings->getSecuritySettings();
	$maxAttempts = $securitySettings['login_attempts']; // get max attempts from settings
	$ip = $_SERVER['REMOTE_ADDR'];

	// Check if IP is blocked before attempting login
	if ($user->isBlocked($ip, $maxAttempts)) {
		$_SESSION['error'] = 'Too many failed login attempts. Please try again later.'; // feedback msg on blocked
	} elseif ($user->login($username, $password)) {
		header("Location: /urls.php?pg=admin"); // redirect to admin panel on successful login;
		exit;
	} else {
		$_SESSION['error'] = 'Invalid username or password.'; // feedback msg on error
	}
}
?>

<!-- Page Content -->
<div class="container my-5">

	<div class="row justify-content-center">
		<div class="col-md-6">

			<div class="card shadow p-4">
				<div class="text-center mb-4">
					<h3 class="mt-2">Admin Login</h3>
				</div>

				<?php msg_error() // display error message 
				?>

				<form method="post" autocomplete="off">

					<!-- Username -->
					<div class="mb-3">
						<label class="form-label">Username</label>
						<div class="input-group">
							<span class="input-group-text">
								<i class="fa fa-user"></i>
							</span>
							<input name="username" type="text" class="form-control" placeholder="Enter Username" required>
						</div>
					</div>

					<!-- Password -->
					<div class="mb-3">
						<label class="form-label">Password</label>
						<div class="input-group">
							<span class="input-group-text">
								<i class="fa fa-lock"></i>
							</span>
							<input name="password" type="password" class="form-control" placeholder="Enter Password" required>
						</div>
					</div>

					<!-- submit -->
					<input type="submit" class="btn btn-primary w-100 py-2" value="Login">
				</form>
			</div>
		</div>
	</div>
</div>

<?php include FRONTEND_TEMPLATE_PATH . "footer.php"; // admin footer file 
?>
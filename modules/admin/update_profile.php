<?php
require_once __DIR__ . '/../../models/UserModel.php'; // import UserModel class

if (!isset($_SESSION['username'])) {
    // redirects non-authenticated users to login page
    header("Location: /urls.php?pg=login");
    exit;
}

$userModel = new User(); // instantiates User instance
$currentUser = $userModel->getByUsername($_SESSION['username']); // retrieve authenticated user instance


/**
 * if values are submitted from the update profile 
 * form the values are cleaned up and used to update the user instance 
 * row;
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']); // retrieve username
    $email = trim($_POST['user_email']); // retrieve email
    $password = $currentUser['password']; // set password to existing password

    if (!empty($_POST['user_password'])) {
        // if new password is submitted update user instance with new password
        $password = password_hash($_POST['user_password'], PASSWORD_DEFAULT);
    }

    $updated = $userModel->update(
        $username,
        $password,
        $email,
    ); // Update user

    //set feedback messages
    if ($updated) {
        $_SESSION['success'] = "Profile updated successfully!"; // success feedback
        $_SESSION['username'] = $username; // Update session username if changed
    } else {
        $_SESSION['error'] = "Failed to update profile."; // error feedback
    }
}

header("Location: /urls.php?pg=profile"); // redirect user to profile page after processing;
exit;

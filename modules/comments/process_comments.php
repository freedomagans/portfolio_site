<?php

/**
 * Comment Processing Handler
 * Handles AJAX and standard form submissions
 */

require_once __DIR__ . '/../../models/CommentModel.php'; // import Comment Model
require_once __DIR__ . '/../../core/Settings.php'; // import Settings

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get settings instance
$settings = AppSettings::getInstance();
$contentSettings = $settings->getContentSettings();

// Determine if this is an AJAX request
$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

// Set JSON header for AJAX responses
if ($isAjax) {
    header('Content-Type: application/json');
}

/**
 * Helper function to send response
 */
function sendResponse($success, $message, $errors = null, $isAjax)
{
    if ($isAjax) {
        // Send JSON response for AJAX
        echo json_encode([
            'status' => $success ? 'success' : 'error',
            'message' => $message,
            'errors' => $errors
        ]);
        exit;
    } else {
        // Redirect with session message for standard form submission
        if ($success) {
            $_SESSION['comment_success'] = $message;
        } else {
            $_SESSION['comment_errors'] = is_array($errors) ? $errors : [$message];
        }
        $referer = $_SERVER['HTTP_REFERER'] ?: '/urls.php?pg=projects';
        header("Location: $referer");
        exit;
    }
}

// Only process POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    sendResponse(false, 'Method not allowed', ['Invalid request method'], $isAjax);
}

// Get and validate form data
$projectId = isset($_POST['project_id']) ? (int)$_POST['project_id'] : 0;
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$content = isset($_POST['content']) ? trim($_POST['content']) : '';
$honeypot = isset($_POST['website']) ? $_POST['website'] : '';

$errors = [];

// Validation
if ($projectId <= 0) {
    $errors[] = 'Invalid project ID';
}

if (empty($name)) {
    $errors[] = 'Name is required';
} elseif (strlen($name) < 2) {
    $errors[] = 'Name must be at least 2 characters';
} elseif (strlen($name) > 100) {
    $errors[] = 'Name is too long (maximum 100 characters)';
}

if (empty($content)) {
    $errors[] = 'Comment content is required';
} elseif (strlen($content) < 10) {
    $errors[] = 'Comment must be at least 10 characters';
} elseif (strlen($content) > 1000) {
    $errors[] = 'Comment is too long (maximum 1000 characters)';
}

// Honeypot check (spam protection)
if (!empty($honeypot)) {
    sendResponse(false, 'Invalid submission detected', ['Spam detected'], $isAjax);
}

// If validation failed, send errors
if (!empty($errors)) {
    sendResponse(false, 'Please correct the following errors:', $errors, $isAjax);
}

// Sanitize input
$name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
$content = htmlspecialchars($content, ENT_QUOTES, 'UTF-8');

// Determine if comment should be auto-approved based on if its enabled or disabled in settings
$isApproved = isset($contentSettings['comments_auto_approve']) &&
    $contentSettings['comments_auto_approve'] ? 1 : 0;

// comment model instance
$commentModel = new Comment();

try {
    $result = $commentModel->create($projectId, $name, $content, $isApproved); // create comment row

    /**
     * if comment row is created send response 
     */
    if ($result) {
        $message = $isApproved
            ? 'Thank you! Your comment has been posted successfully.'
            : 'Thank you! Your comment has been submitted and is awaiting approval.'; // msg based on auto-approval enabled or disabled in settings

        sendResponse(true, $message, null, $isAjax);
    } else {
        sendResponse(false, 'Failed to submit comment', ['Database error. Please try again.'], $isAjax);
    }
} catch (Exception $e) {
    error_log('Comment submission error: ' . $e->getMessage());
    sendResponse(false, 'An error occurred', ['Unable to save comment. Please try again later.'], $isAjax);
}

<?php
require_once __DIR__ . '/../../models/NotificationModel.php';
require_once CORE_PATH . 'Mailer.php';
require_once CORE_PATH . 'Settings.php';

// Set JSON header for AJAX responses
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Check if it's an AJAX request
    $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
              strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    
    // Helper function to return response
    function sendResponse($success, $message, $isAjax) {
        if ($isAjax) {
            // Return JSON for AJAX requests
            echo json_encode([
                'success' => $success,
                'message' => $message
            ]);
            exit;
        } else {
            // Traditional redirect for non-AJAX
            $_SESSION[$success ? 'success' : 'error'] = $message;
            header("Location: /urls.php?pg=contact");
            exit;
        }
    }
    
    // Trim and clean inputs
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');
    
    // Validate required fields
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        sendResponse(false, "All fields are required.", $isAjax);
    }
    
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        sendResponse(false, "Invalid email address.", $isAjax);
    }
    
    // Validate name length
    if (strlen($name) < 2 || strlen($name) > 100) {
        sendResponse(false, "Name must be between 2 and 100 characters.", $isAjax);
    }
    
    // Validate subject length
    if (strlen($subject) < 3 || strlen($subject) > 200) {
        sendResponse(false, "Subject must be between 3 and 200 characters.", $isAjax);
    }
    
    // Validate message length
    if (strlen($message) < 10 || strlen($message) > 5000) {
        sendResponse(false, "Message must be between 10 and 5000 characters.", $isAjax);
    }
    
    // Escape special characters to prevent XSS
    $name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
    $email = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
    $subject = htmlspecialchars($subject, ENT_QUOTES, 'UTF-8');
    $message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
    
    try {
        // Create notification instance
        $notification = new Notification();
        $created = $notification->create($name, $email, $subject, $message);
        
        if ($created) {
            // Send email to admin
            $mailer = new Mailer();
            
            // Email body content
            $emailBody = "
            <div style='
                background: #f4f4f4;
                padding: 20px;
                font-family: Arial, sans-serif;
            '>
                <div style='
                    max-width: 600px;
                    margin: 0 auto;
                    background: #ffffff;
                    border-radius: 10px;
                    padding: 25px;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
                '>
                    <h2 style='color: #667eea; text-align: center; margin-top: 0;'>
                        🔔 New Message From Your Portfolio
                    </h2>
                    
                    <p style='font-size: 15px; color: #333;'>
                        You received a new contact message from your <strong>FaedinWebworks</strong> website.
                    </p>
                    
                    <div style='
                        background: #f8f9fa;
                        padding: 15px;
                        border-radius: 8px;
                        border: 1px solid #e3e3e3;
                        margin-bottom: 20px;
                    '>
                        <p style='margin-bottom: 10px;'>
                            <strong style='color: #1e293b;'>Name:</strong><br>
                            <span style='color: #475569;'>{$name}</span>
                        </p>
                        
                        <p style='margin-bottom: 10px;'>
                            <strong style='color: #1e293b;'>Email:</strong><br>
                            <a href='mailto:{$email}' style='color: #667eea;'>{$email}</a>
                        </p>
                        
                        <p style='margin-bottom: 10px;'>
                            <strong style='color: #1e293b;'>Subject:</strong><br>
                            <span style='color: #475569;'>{$subject}</span>
                        </p>
                        
                        <p style='margin-bottom: 0;'>
                            <strong style='color: #1e293b;'>Message:</strong><br>
                            <div style='
                                background: #ffffff;
                                padding: 12px;
                                border-radius: 6px;
                                border: 1px solid #ddd;
                                font-size: 14px;
                                color: #444;
                                line-height: 1.5;
                                margin-top: 8px;
                            '>
                                " . nl2br($message) . "
                            </div>
                        </p>
                    </div>
                    
                    <p style='
                        font-size: 13px;
                        text-align: center;
                        color: #888;
                        margin-bottom: 0;
                    '>
                        Sent automatically from your <strong>FaedinWebworks Portfolio</strong><br>
                        <span style='font-size: 12px;'>© " . date('Y') . " FaedinWebworks. All Rights Reserved.</span>
                    </p>
                </div>
            </div>
            ";
            
            // Send email (non-blocking, don't fail if email fails)
            try {
                $mailer->sendMail(
                    "freedomaganmwonyi99@gmail.com", 
                    "New Portfolio Contact: {$subject}", 
                    $emailBody
                );
            } catch (Exception $e) {
                // Log email error but don't fail the request
                error_log("Email send failed: " . $e->getMessage());
            }
            
            sendResponse(true, "Thank you! Your message has been sent successfully. I'll get back to you soon.", $isAjax);
            
        } else {
            sendResponse(false, "Failed to save your message. Please try again.", $isAjax);
        }
        
    } catch (Exception $e) {
        // Log the error
        error_log("Contact form error: " . $e->getMessage());
        sendResponse(false, "An error occurred. Please try again later.", $isAjax);
    }
    
} else {
    // Invalid request method
    http_response_code(405);
    
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        echo json_encode([
            'success' => false,
            'message' => 'Method Not Allowed'
        ]);
    } else {
        echo "Method Not Allowed";
    }
    exit;
}
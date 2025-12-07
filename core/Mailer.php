<?php
/**
 * defining class to use PhpMailer to send mails
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/phpmailer/PHPMailer.php'; // import PHPMailer
require_once __DIR__ . '/phpmailer/SMTP.php'; // import SMTP
require_once __DIR__ . '/phpmailer/Exception.php'; // import Exception
require_once __DIR__ . '/Settings.php'; // import Settings

class Mailer
{
    private $mail;

    public function __construct()
    {
        $this->mail = new PHPMailer(true); // PHPMailer instance
        $this->setup(); // calls setup method
    }

    private function setup()
    {
        // Get SMTP configuration from settings
        $settings = AppSettings::getInstance();
        $smtpConfig = $settings->getSMTPConfig();
        $siteInfo = $settings->getSiteInfo();

        // SMTP CONFIG
        $this->mail->isSMTP();
        $this->mail->Host = $smtpConfig['host'];
        $this->mail->SMTPAuth = true;

        $this->mail->Username = $smtpConfig['username'];
        $this->mail->Password = $smtpConfig['password'];

        // Set encryption based on settings
        if ($smtpConfig['encryption'] === 'ssl') {
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        } elseif ($smtpConfig['encryption'] === 'tls') {
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        } else {
            $this->mail->SMTPSecure = '';
        }

        $this->mail->Port = (int)$smtpConfig['port'];

        // Sender Info - use site email if available, fallback to SMTP username
        $senderEmail = $siteInfo['email'] ?: $smtpConfig['username'];
        $senderName = $siteInfo['title'] ?: 'FaedinWebworks';
        $this->mail->setFrom($senderEmail, $senderName);
        $this->mail->isHTML(true);
    }

    public function sendMail($to, $subject, $message)
    {
        try {
            $this->mail->clearAllRecipients();
            $this->mail->addAddress($to);
            $this->mail->Subject = $subject;
            $this->mail->Body = $message;

            return $this->mail->send();
        } catch (Exception $e) {
            return false;
        }
    }
}
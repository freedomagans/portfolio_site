<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require_once __DIR__ . '/phpmailer/PHPMailer.php'; // import PHPMailer
require_once __DIR__ . '/phpmailer/SMTP.php'; // import SMTP
require_once __DIR__ . '/phpmailer/Exception.php'; // import Exception
require_once __DIR__ . '/Settings.php'; // import Settings

/**
 * defining class to use PhpMailer to send mails
 */
class Mailer
{
    private $mail; // declare PHPMailer instance

    /**
     * defined constructor for Mailer class
     * creates a PHPMailer instance and calls the setup method
     */
    public function __construct()
    {
        $this->mail = new PHPMailer(true); // PHPMailer instance
        $this->setup(); // calls setup method
    }

    /**
     * method to set values of mail instance variables 
     * like the Host, Username, Password , Port e.t.c.
     * @return void
     */
    private function setup()
    {
        // Get SMTP configuration from settings
        $settings = AppSettings::getInstance();
        $smtpConfig = $settings->getSMTPConfig();
        $siteInfo = $settings->getSiteInfo();

        // SMTP CONFIG
        $this->mail->isSMTP(); // Set mailer to use SMTP
        $this->mail->Host = $smtpConfig['host'] ?: SMTP_HOST; // specify settings host or default
        $this->mail->SMTPAuth = true; // Enable SMTP authentication

        $this->mail->Username = $smtpConfig['username'] ?: SMTP_USERNAME; // specify settings username or default
        $this->mail->Password = $smtpConfig['password'] ?: SMTP_PASSWORD; // specify settings password or default

        /* Set encryption type based on settings or default */
        if (($smtpConfig['encryption'] ?: SMTP_ENCRYPTION) === 'ssl') {
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        } elseif (($smtpConfig['encryption'] ?: SMTP_ENCRYPTION) === 'tls') {
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        } else {
            $this->mail->SMTPSecure = '';
        }

        $this->mail->Port = (int)$smtpConfig['port'] ?: SMTP_PORT; // specify settings port or default

        // specify settings sender email and name or default
        $senderEmail = $smtpConfig['username'] ?: FROM_EMAIL;
        $senderName = $siteInfo['title'] ?: FROM_NAME;
        $this->mail->setFrom($senderEmail, $senderName); // Set sender info
        $this->mail->isHTML(true); // Set email format to HTML
    }

    /**
     * method to call the send() method of mail 
     * instance and set appropriate recipient
     * @param mixed $to
     * @param mixed $subject
     * @param mixed $message
     * @return bool
     */
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
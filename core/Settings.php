<?php
/**
 * Settings Helper Class
 * Provides easy access to application settings throughout the codebase
 */

class AppSettings
{
    private static $instance = null;
    private $settingsModel;
    private $cache = [];

    private function __construct()
    {
        require_once __DIR__ . '/../models/SettingsModel.php';
        $this->settingsModel = new Settings();
    }

    /**
     * Get singleton instance
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Get a setting value by key
     */
    public function get($key, $default = null)
    {
        if (!isset($this->cache[$key])) {
            $this->cache[$key] = $this->settingsModel->get($key);
        }
        return $this->cache[$key] ?? $default;
    }

    /**
     * Set a setting value
     */
    public function set($key, $value)
    {
        $result = $this->settingsModel->set($key, $value);
        if ($result) {
            $this->cache[$key] = $value;
        }
        return $result;
    }

    /**
     * Get all settings
     */
    public function getAll()
    {
        return $this->settingsModel->getAll();
    }

    /**
     * Get settings by group
     */
    public function getByGroup($group)
    {
        return $this->settingsModel->getByGroup($group);
    }

    /**
     * Get public settings only
     */
    public function getPublicSettings()
    {
        return $this->settingsModel->getPublicSettings();
    }

    /**
     * Clear cache
     */
    public function clearCache()
    {
        $this->cache = [];
        $this->settingsModel->clearCache();
    }

    /**
     * Get site information
     */
    public function getSiteInfo()
    {
        return [
            'title' => $this->get('site_title', 'FaedinWebworks'),
            'description' => $this->get('site_description', 'Professional web development and automation solutions'),
            'email' => $this->get('contact_email', ''),
            'phone' => $this->get('contact_phone', ''),
            'logo' => $this->get('site_logo', ''),
        ];
    }

    /**
     * Get SMTP configuration
     */
    public function getSMTPConfig()
    {
        return [
            'host' => $this->get('smtp_host', 'smtp.gmail.com'),
            'port' => $this->get('smtp_port', '587'),
            'username' => $this->get('smtp_username', ''),
            'password' => $this->get('smtp_password', ''),
            'encryption' => $this->get('smtp_encryption', 'tls'),
        ];
    }

    /**
     * Get security settings
     */
    public function getSecuritySettings()
    {
        return [
            'login_attempts' => (int) $this->get('login_attempt_limit', 5),
            'session_timeout' => (int) $this->get('session_timeout', 60),
            'password_min_length' => (int) $this->get('password_min_length', 8),
            'enable_2fa' => $this->get('enable_2fa', '0') === '1',
        ];
    }

    /**
     * Get content settings
     */
    public function getContentSettings()
    {
        return [
            'projects_per_page' => (int) $this->get('projects_per_page', 6),
            'comments_auto_approve' => $this->get('comments_auto_approve', '0') === '1',
            'default_project_category' => $this->get('default_project_category', 'Web Development'),
        ];
    }

    /**
     * Get social media links
     */
    public function getSocialLinks()
    {
        return [
            'facebook' => $this->get('facebook_url', ''),
            'twitter' => $this->get('twitter_url', ''),
            'linkedin' => $this->get('linkedin_url', ''),
            'github' => $this->get('github_url', ''),
            'instagram' => $this->get('instagram_url', ''),
        ];
    }

    /**
     * Get SEO settings
     */
    public function getSEOSettings()
    {
        return [
            'google_analytics' => $this->get('google_analytics_id', ''),
            'meta_keywords' => $this->get('meta_keywords', 'web development, portfolio, programming'),
            'enable_sitemap' => $this->get('enable_sitemap', '1') === '1',
        ];
    }
}

// Convenience function for global access
function getSetting($key, $default = null)
{
    return AppSettings::getInstance()->get($key, $default);
}

function setSetting($key, $value)
{
    return AppSettings::getInstance()->set($key, $value);
}

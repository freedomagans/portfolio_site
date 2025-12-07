<?php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/BaseModel.php';

class Settings extends BaseModel
{
    protected $table = 'settings';
    private $cache = [];

    public function __construct()
    {
        parent::__construct();
        $this->createTableIfNotExists();
        $this->seedDefaultSettings();
    }

    /**
     * Create settings table if it doesn't exist
     */
    private function createTableIfNotExists()
    {
        $sql = "CREATE TABLE IF NOT EXISTS {$this->table} (
            id INT PRIMARY KEY AUTO_INCREMENT,
            setting_key VARCHAR(191) NOT NULL,
            setting_value TEXT,
            setting_group VARCHAR(100) NOT NULL,
            setting_type ENUM('text', 'email', 'url', 'number', 'boolean', 'textarea', 'file') DEFAULT 'text',
            setting_label VARCHAR(255) NOT NULL,
            setting_description TEXT,
            is_public BOOLEAN DEFAULT FALSE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            UNIQUE KEY unique_setting_key (setting_key)
        )";

        try {
            $this->conn->exec($sql);
        } catch (PDOException $e) {
            error_log("Error creating settings table: " . $e->getMessage());
        }
    }

    /**
     * Seed default settings if they don't exist
     */
    private function seedDefaultSettings()
    {
        $defaultSettings = [
            // General Settings
            [
                'setting_key' => 'site_title',
                'setting_value' => 'FaedinWebworks',
                'setting_group' => 'general',
                'setting_type' => 'text',
                'setting_label' => 'Site Title',
                'setting_description' => 'The main title of your website',
                'is_public' => true
            ],
            [
                'setting_key' => 'site_description',
                'setting_value' => 'Professional web development and automation solutions',
                'setting_group' => 'general',
                'setting_type' => 'textarea',
                'setting_label' => 'Site Description',
                'setting_description' => 'Brief description of your website',
                'is_public' => true
            ],
            [
                'setting_key' => 'contact_email',
                'setting_value' => '',
                'setting_group' => 'general',
                'setting_type' => 'email',
                'setting_label' => 'Contact Email',
                'setting_description' => 'Primary contact email address',
                'is_public' => true
            ],
            [
                'setting_key' => 'contact_phone',
                'setting_value' => '',
                'setting_group' => 'general',
                'setting_type' => 'text',
                'setting_label' => 'Contact Phone',
                'setting_description' => 'Primary contact phone number',
                'is_public' => true
            ],
            [
                'setting_key' => 'site_logo',
                'setting_value' => '',
                'setting_group' => 'general',
                'setting_type' => 'file',
                'setting_label' => 'Site Logo',
                'setting_description' => 'Upload your site logo',
                'is_public' => true
            ],

            // Email Settings
            [
                'setting_key' => 'smtp_host',
                'setting_value' => 'smtp.gmail.com',
                'setting_group' => 'email',
                'setting_type' => 'text',
                'setting_label' => 'SMTP Host',
                'setting_description' => 'Your SMTP server hostname',
                'is_public' => false
            ],
            [
                'setting_key' => 'smtp_port',
                'setting_value' => '587',
                'setting_group' => 'email',
                'setting_type' => 'number',
                'setting_label' => 'SMTP Port',
                'setting_description' => 'SMTP server port',
                'is_public' => false
            ],
            [
                'setting_key' => 'smtp_username',
                'setting_value' => '',
                'setting_group' => 'email',
                'setting_type' => 'text',
                'setting_label' => 'SMTP Username',
                'setting_description' => 'Your SMTP authentication username',
                'is_public' => false
            ],
            [
                'setting_key' => 'smtp_password',
                'setting_value' => '',
                'setting_group' => 'email',
                'setting_type' => 'text',
                'setting_label' => 'SMTP Password',
                'setting_description' => 'Your SMTP authentication password',
                'is_public' => false
            ],
            [
                'setting_key' => 'smtp_encryption',
                'setting_value' => 'tls',
                'setting_group' => 'email',
                'setting_type' => 'text',
                'setting_label' => 'SMTP Encryption',
                'setting_description' => 'Encryption method for SMTP connection',
                'is_public' => false
            ],

            // Social Media Settings
            [
                'setting_key' => 'facebook_url',
                'setting_value' => '',
                'setting_group' => 'social',
                'setting_type' => 'url',
                'setting_label' => 'Facebook URL',
                'setting_description' => 'Your Facebook profile or page URL',
                'is_public' => true
            ],
            [
                'setting_key' => 'twitter_url',
                'setting_value' => '',
                'setting_group' => 'social',
                'setting_type' => 'url',
                'setting_label' => 'Twitter URL',
                'setting_description' => 'Your Twitter profile URL',
                'is_public' => true
            ],
            [
                'setting_key' => 'linkedin_url',
                'setting_value' => '',
                'setting_group' => 'social',
                'setting_type' => 'url',
                'setting_label' => 'LinkedIn URL',
                'setting_description' => 'Your LinkedIn profile URL',
                'is_public' => true
            ],
            [
                'setting_key' => 'github_url',
                'setting_value' => '',
                'setting_group' => 'social',
                'setting_type' => 'url',
                'setting_label' => 'GitHub URL',
                'setting_description' => 'Your GitHub profile URL',
                'is_public' => true
            ],
            [
                'setting_key' => 'instagram_url',
                'setting_value' => '',
                'setting_group' => 'social',
                'setting_type' => 'url',
                'setting_label' => 'Instagram URL',
                'setting_description' => 'Your Instagram profile URL',
                'is_public' => true
            ],

            // SEO Settings
            [
                'setting_key' => 'google_analytics_id',
                'setting_value' => '',
                'setting_group' => 'seo',
                'setting_type' => 'text',
                'setting_label' => 'Google Analytics ID',
                'setting_description' => 'Your Google Analytics tracking ID',
                'is_public' => true
            ],
            [
                'setting_key' => 'meta_keywords',
                'setting_value' => 'web development, portfolio, programming',
                'setting_group' => 'seo',
                'setting_type' => 'text',
                'setting_label' => 'Meta Keywords',
                'setting_description' => 'Comma-separated keywords for SEO',
                'is_public' => true
            ],
            [
                'setting_key' => 'enable_sitemap',
                'setting_value' => '1',
                'setting_group' => 'seo',
                'setting_type' => 'boolean',
                'setting_label' => 'Enable Sitemap',
                'setting_description' => 'Automatically generate XML sitemap',
                'is_public' => false
            ],

            // Content Settings
            [
                'setting_key' => 'default_project_category',
                'setting_value' => 'Web Development',
                'setting_group' => 'content',
                'setting_type' => 'text',
                'setting_label' => 'Default Project Category',
                'setting_description' => 'Default category for new projects',
                'is_public' => false
            ],
            [
                'setting_key' => 'comments_auto_approve',
                'setting_value' => '0',
                'setting_group' => 'content',
                'setting_type' => 'boolean',
                'setting_label' => 'Comments Auto-Approval',
                'setting_description' => 'Automatically approve new comments',
                'is_public' => false
            ],
            [
                'setting_key' => 'projects_per_page',
                'setting_value' => '6',
                'setting_group' => 'content',
                'setting_type' => 'number',
                'setting_label' => 'Projects Per Page',
                'setting_description' => 'Number of projects to display per page',
                'is_public' => false
            ],

            // Security Settings
            [
                'setting_key' => 'login_attempt_limit',
                'setting_value' => '5',
                'setting_group' => 'security',
                'setting_type' => 'number',
                'setting_label' => 'Login Attempt Limit',
                'setting_description' => 'Maximum failed login attempts before lockout',
                'is_public' => false
            ],
            [
                'setting_key' => 'session_timeout',
                'setting_value' => '60',
                'setting_group' => 'security',
                'setting_type' => 'number',
                'setting_label' => 'Session Timeout (minutes)',
                'setting_description' => 'Admin session timeout duration',
                'is_public' => false
            ],
            [
                'setting_key' => 'password_min_length',
                'setting_value' => '8',
                'setting_group' => 'security',
                'setting_type' => 'number',
                'setting_label' => 'Password Minimum Length',
                'setting_description' => 'Minimum password length requirement',
                'is_public' => false
            ],
            [
                'setting_key' => 'enable_2fa',
                'setting_value' => '0',
                'setting_group' => 'security',
                'setting_type' => 'boolean',
                'setting_label' => 'Enable Two-Factor Authentication',
                'setting_description' => 'Require 2FA for admin login',
                'is_public' => false
            ]
        ];

        foreach ($defaultSettings as $setting) {
            $this->insertDefaultSetting($setting);
        }
    }

    /**
     * Insert default setting if it doesn't exist
     */
    private function insertDefaultSetting($setting)
    {
        try {
            $stmt = $this->conn->prepare("
                INSERT IGNORE INTO {$this->table}
                (setting_key, setting_value, setting_group, setting_type, setting_label, setting_description, is_public)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $setting['setting_key'],
                $setting['setting_value'],
                $setting['setting_group'],
                $setting['setting_type'],
                $setting['setting_label'],
                $setting['setting_description'],
                $setting['is_public']
            ]);
        } catch (PDOException $e) {
            error_log("Error inserting default setting {$setting['setting_key']}: " . $e->getMessage());
        }
    }

    /**
     * Get a setting value by key
     */
    public function get($key)
    {
        // Check cache first
        if (isset($this->cache[$key])) {
            return $this->cache[$key];
        }

        try {
            $stmt = $this->conn->prepare("SELECT setting_value FROM {$this->table} WHERE setting_key = ?");
            $stmt->execute([$key]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            $value = $result ? $result['setting_value'] : null;

            // Cache the result
            $this->cache[$key] = $value;

            return $value;
        } catch (PDOException $e) {
            error_log("Error getting setting {$key}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Set a setting value
     */
    public function set($key, $value)
    {
        try {
            $stmt = $this->conn->prepare("
                INSERT INTO {$this->table} (setting_key, setting_value, setting_group, setting_type, setting_label, setting_description, is_public)
                VALUES (?, ?, 'general', 'text', ?, '', 0)
                ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value), updated_at = CURRENT_TIMESTAMP
            ");
            $stmt->execute([$key, $value, ucfirst(str_replace('_', ' ', $key))]);

            // Update cache
            $this->cache[$key] = $value;

            return true;
        } catch (PDOException $e) {
            error_log("Error setting {$key}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get all settings
     */
    public function getAll()
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM {$this->table} ORDER BY setting_group, setting_key");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting all settings: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get settings grouped by category
     */
    public function getAllGrouped()
    {
        $settings = $this->getAll();
        $grouped = [];

        foreach ($settings as $setting) {
            $group = $setting['setting_group'];
            $key = $setting['setting_key'];

            if (!isset($grouped[$group])) {
                $grouped[$group] = [];
            }

            $grouped[$group][$key] = $setting;
        }

        return $grouped;
    }

    /**
     * Get settings by group
     */
    public function getByGroup($group)
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE setting_group = ? ORDER BY setting_key");
            $stmt->execute([$group]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting settings for group {$group}: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Delete a setting
     */
    public function delete($key)
    {
        try {
            $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE setting_key = ?");
            $stmt->execute([$key]);

            // Remove from cache
            unset($this->cache[$key]);

            return true;
        } catch (PDOException $e) {
            error_log("Error deleting setting {$key}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Clear cache
     */
    public function clearCache()
    {
        $this->cache = [];
    }

    /**
     * Get public settings only
     */
    public function getPublicSettings()
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE is_public = 1 ORDER BY setting_group, setting_key");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting public settings: " . $e->getMessage());
            return [];
        }
    }
}

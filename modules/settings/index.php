
<?php
// ===============================================
// ADMIN SETTINGS MODULE - FIXED VERSION
// ===============================================

include ADMIN_TEMPLATE_PATH . "admin_header.php";
include ADMIN_TEMPLATE_PATH . "admin_navigation.php";

require_once __DIR__ . '/../../models/SettingsModel.php';
$settingsModel = new Settings();

$message = '';
$messageType = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $updatedCount = 0;
        $uploadedFiles = [];
        $errors = [];

        // Step 1: Process file uploads FIRST
        foreach ($_FILES as $fileKey => $file) {
            // Skip if no file uploaded or error
            if ($file['error'] === UPLOAD_ERR_NO_FILE) {
                continue;
            }
            
            if ($file['error'] !== UPLOAD_ERR_OK) {
                $errors[] = "File upload error for {$fileKey}";
                continue;
            }

            // Validate file
            $allowedTypes = ALLOWED_IMAGE_TYPES;
            $maxSize = MAX_FILE_SIZE; // 5MB
            
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);
            
            if (!in_array($mimeType, $allowedTypes)) {
                $errors[] = "Invalid file type. Only images allowed.";
                continue;
            }
            
            if ($file['size'] > $maxSize) {
                $errors[] = "File exceeds 5MB limit.";
                continue;
            }

            // Upload file
            $uploadDir = __DIR__ . '/../../media/uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $fileName = uniqid() . '_' . time() . '.' . $extension;
            $uploadPath = $uploadDir . $fileName;
            
            if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                // CRITICAL FIX: Store with the form field name as key
                $uploadedFiles[$fileKey] = '/media/uploads/' . $fileName;
            } else {
                $errors[] = "Failed to upload file";
            }
        }

        // Step 2: Get current settings for comparison
        $currentSettings = [];
        foreach ($settingsModel->getAll() as $setting) {
            $currentSettings[$setting['setting_key']] = $setting['setting_value'];
        }

        // Step 3: Process POST settings
        $processedKeys = [];
        
        foreach ($_POST as $key => $value) {
            // Only process fields that start with 'setting_'
            if (strpos($key, 'setting_') !== 0) {
                continue;
            }
            
            $settingKey = str_replace('setting_', '', $key);
            
            // Skip if we've already processed this key (handles hidden checkbox fields)
            if (in_array($settingKey, $processedKeys)) {
                continue;
            }
            
            $processedKeys[] = $settingKey;
            
            
            if (isset($uploadedFiles[$key])) {
                // Use the uploaded file path instead of the POST value
                $value = $uploadedFiles[$key];
            }
            
            // Trim string values
            if (is_string($value)) {
                $value = trim($value);
            }
            
            // Only update if value actually changed
            $currentValue = $currentSettings[$settingKey] ?? '';
            
            if ($currentValue !== $value) {
                if ($settingsModel->set($settingKey, $value)) {
                    $updatedCount++;
                }
            }
        }

        // Step 4: Handle files that don't have POST fields
        // This ensures file uploads work even if there's no corresponding text input
        foreach ($uploadedFiles as $fileKey => $filePath) {
            if (strpos($fileKey, 'setting_') === 0) {
                $settingKey = str_replace('setting_', '', $fileKey);
                
                // Only update if we haven't already processed this setting
                if (!in_array($settingKey, $processedKeys)) {
                    $currentValue = $currentSettings[$settingKey] ?? '';
                    
                    if ($currentValue !== $filePath) {
                        if ($settingsModel->set($settingKey, $filePath)) {
                            $updatedCount++;
                        }
                    }
                }
            }
        }

        // Step 5: Show appropriate message
        if (!empty($errors)) {
            $message = "Errors occurred: " . implode(', ', $errors);
            $messageType = 'danger';
        } elseif ($updatedCount > 0) {
            $message = "Successfully updated {$updatedCount} setting(s)!";
            $messageType = 'success';
            $settingsModel->clearCache();
        } else {
            $message = "No changes were made.";
            $messageType = 'info';
        }
        
    } catch (Exception $e) {
        $message = "Error updating settings: " . $e->getMessage();
        $messageType = 'danger';
        error_log("Settings update error: " . $e->getMessage());
    }
}


$groupedSettings = $settingsModel->getAllGrouped();
$groupOrder = ['general', 'email', 'social', 'seo', 'content', 'security'];
$groupLabels = [
    'general' => 'General',
    'email' => 'Email Settings',
    'social' => 'Social Media',
    'seo' => 'SEO Settings',
    'content' => 'Content Settings',
    'security' => 'Security Settings'
];
$groupIcons = [
    'general' => 'fas fa-cogs',
    'email' => 'fas fa-envelope',
    'social' => 'fas fa-share-alt',
    'seo' => 'fas fa-search',
    'content' => 'fas fa-edit',
    'security' => 'fas fa-shield-alt'
];
?>

<link href="/static/admin/css/settings.css" rel="stylesheet">

<div class="container-fluid px-4">
    <div class="settings-container">
        
        <!-- Header -->
        <div class="settings-header">
            <h4 class="mb-2">
                <i class="fas fa-cog me-2"></i>
                System Settings
            </h4>
            <p class="mb-0 opacity-75">Configure your website settings and preferences</p>
        </div>

        <!-- Alert Messages -->
        <?php if ($message): ?>
            <div class="alert alert-<?= $messageType ?> alert-dismissible fade show" role="alert">
                <i class="fas fa-<?= $messageType === 'success' ? 'check-circle' : ($messageType === 'danger' ? 'exclamation-triangle' : 'info-circle') ?> me-2"></i>
                <?= htmlspecialchars($message) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Tab Navigation -->
        <ul class="nav nav-tabs settings-nav" id="settingsTabs" role="tablist">
            <?php foreach ($groupOrder as $index => $group): ?>
                <?php if (isset($groupedSettings[$group])): ?>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link <?= $index === 0 ? 'active' : '' ?>"
                                id="<?= $group ?>-tab"
                                data-bs-toggle="tab"
                                data-bs-target="#<?= $group ?>"
                                type="button"
                                role="tab">
                            <i class="<?= $groupIcons[$group] ?> me-2"></i>
                            <?= $groupLabels[$group] ?>
                        </button>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>

        <!-- Settings Form -->
        <form method="POST" enctype="multipart/form-data" id="settingsForm">
            
            <?php foreach ($groupOrder as $index => $group): ?>
                <?php if (isset($groupedSettings[$group])): ?>
                    <div class="setting-group <?= $index === 0 ? 'active' : '' ?>" id="<?= $group ?>">
                        <div class="p-4">
                            
                            <?php foreach ($groupedSettings[$group] as $setting): ?>
                                <?php
                                $fieldName = "setting_" . $setting['setting_key'];
                                $fieldValue = $setting['setting_value'];
                                $fieldType = $setting['setting_type'];
                                ?>
                                
                                <div class="setting-card">
                                    <!-- Card Header -->
                                    <div class="setting-header">
                                        <h6 class="setting-title">
                                            <i class="fas fa-<?= $fieldType === 'boolean' ? 'toggle-on' : ($fieldType === 'file' ? 'upload' : 'edit') ?>"></i>
                                            <?= htmlspecialchars($setting['setting_label']) ?>
                                        </h6>
                                        <?php if ($setting['setting_description']): ?>
                                            <p class="setting-description">
                                                <?= htmlspecialchars($setting['setting_description']) ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <!-- Card Body with Input -->
                                    <div class="setting-body">
                                        
                                        <?php if ($fieldType === 'boolean'): ?>
                                            <!-- Boolean Toggle -->
                                            <input type="hidden" name="<?= $fieldName ?>" value="0">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input boolean-toggle"
                                                       type="checkbox"
                                                       id="<?= $fieldName ?>"
                                                       name="<?= $fieldName ?>"
                                                       value="1"
                                                       <?= $fieldValue == '1' ? 'checked' : '' ?>
                                                       data-label-target="label_<?= $fieldName ?>">
                                                <label class="form-check-label" for="<?= $fieldName ?>" id="label_<?= $fieldName ?>">
                                                    <span class="status-badge <?= $fieldValue == '1' ? 'status-enabled' : 'status-disabled' ?>">
                                                        <i class="fas fa-<?= $fieldValue == '1' ? 'check-circle' : 'times-circle' ?> me-1"></i>
                                                        <?= $fieldValue == '1' ? 'Enabled' : 'Disabled' ?>
                                                    </span>
                                                </label>
                                            </div>

                                        <?php elseif ($fieldType === 'textarea'): ?>
                                            <!-- Textarea -->
                                            <textarea class="form-control" 
                                                      name="<?= $fieldName ?>" 
                                                      rows="4" 
                                                      placeholder="Enter <?= strtolower($setting['setting_label']) ?>"><?= htmlspecialchars($fieldValue) ?></textarea>

                                        <?php elseif ($fieldType === 'file'): ?>
                                            <!-- File Upload -->
                                            <div class="file-upload-wrapper">
                                                <div class="file-upload-area" onclick="document.getElementById('<?= $fieldName ?>').click()">
                                                    <i class="fas fa-cloud-upload-alt fa-2x mb-2"></i>
                                                    <p class="mb-1"><strong>Click to upload or drag and drop</strong></p>
                                                    <p class="text-muted small mb-0">Supported: JPG, PNG, GIF (Max 5MB)</p>
                                                </div>
                                                <input type="file" 
                                                       id="<?= $fieldName ?>" 
                                                       name="<?= $fieldName ?>" 
                                                       accept="image/*" 
                                                       class="file-input-hidden">
                                                <div id="filename_<?= $fieldName ?>" class="selected-file" style="display: none;">
                                                    <i class="fas fa-file-image me-2"></i>
                                                    <span class="filename-text"></span>
                                                    <button type="button" class="remove-file-btn" onclick="removeFile('<?= $fieldName ?>')">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                                <?php if ($fieldValue): ?>
                                                    <div class="current-file mt-2">
                                                        <i class="fas fa-image me-1"></i>
                                                        Current: <a href="<?= htmlspecialchars($fieldValue) ?>">View File</a>
                                                    </div>
                                                <?php endif; ?>
                                            </div>

                                        <?php elseif ($fieldType === 'email'): ?>
                                            <!-- Email Input -->
                                            <input type="email" 
                                                   class="form-control" 
                                                   name="<?= $fieldName ?>" 
                                                   value="<?= htmlspecialchars($fieldValue) ?>" 
                                                   placeholder="email@example.com">

                                        <?php elseif ($fieldType === 'url'): ?>
                                            <!-- URL Input -->
                                            <input type="url" 
                                                   class="form-control" 
                                                   name="<?= $fieldName ?>" 
                                                   value="<?= htmlspecialchars($fieldValue) ?>" 
                                                   placeholder="https://example.com">

                                        <?php elseif ($fieldType === 'number'): ?>
                                            <!-- Number Input -->
                                            <input type="number" 
                                                   class="form-control" 
                                                   name="<?= $fieldName ?>" 
                                                   value="<?= htmlspecialchars($fieldValue) ?>" 
                                                   placeholder="Enter number" 
                                                   min="0">

                                        <?php else: ?>
                                            <!-- Text Input (default) -->
                                            <input type="text" 
                                                   class="form-control" 
                                                   name="<?= $fieldName ?>" 
                                                   value="<?= htmlspecialchars($fieldValue) ?>" 
                                                   placeholder="Enter <?= strtolower($setting['setting_label']) ?>">
                                        <?php endif; ?>
                                        
                                    </div>
                                </div>
                                
                            <?php endforeach; ?>
                            
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>

            <!-- Save Button -->
            <div class="p-4 border-top bg-light">
                <button type="submit" class="btn btn-save">
                    <i class="fas fa-save me-2"></i>
                    <span>Save All Settings</span>
                </button>
            </div>
            
        </form>

        <!-- Stats Section -->
        <div class="settings-stats p-4 bg-light border-top">
            <div class="row text-center">
                <div class="col-md-3 col-6 mb-3 mb-md-0">
                    <div class="stat-card">
                        <i class="fas fa-cogs text-primary fa-2x mb-2"></i>
                        <h5 class="mb-0"><?= count($settingsModel->getAll()); ?></h5>
                        <small class="text-muted">Total Settings</small>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-3 mb-md-0">
                    <div class="stat-card">
                        <i class="fas fa-check-circle text-success fa-2x mb-2"></i>
                        <h5 class="mb-0"><?= count(array_filter($settingsModel->getAll(), fn($s) => $s['setting_type'] === 'boolean' && $s['setting_value'] == '1')); ?></h5>
                        <small class="text-muted">Enabled Features</small>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-card">
                        <i class="fas fa-shield-alt text-info fa-2x mb-2"></i>
                        <h5 class="mb-0"><?= count($settingsModel->getByGroup('security')); ?></h5>
                        <small class="text-muted">Security Settings</small>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-card">
                        <i class="fas fa-clock text-warning fa-2x mb-2"></i>
                        <span class="mb-0"><?php echo $settingsModel->getLastUpdated() ?></span>
                        <small class="text-muted">Last Updated</small>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="/static/admin/js/settings.js"></script>

<?php include ADMIN_TEMPLATE_PATH . "admin_footer.php"; ?>
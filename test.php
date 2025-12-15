Ah! Now I see the issue clearly. The file is uploading successfully, but the database isn't getting updated. This means there's a key mismatch between the uploaded file key and the POST processing loop.

## 🔍 The Root Cause

When you upload a file through a form field named setting_site_logo:
- *$_FILES* contains: setting_site_logo
- *$_POST* also contains: setting_site_logo (empty string)
- But we're checking: if (isset($uploadedFiles[$key])) where $key = setting_site_logo

The issue is that we're storing the uploaded file path using the *form field name* as the key, but we need to match it correctly in the POST loop.

## ✅ The Fix

Replace your POST processing section with this corrected version:

php
<?php
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
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            $maxSize = 5 * 1024 * 1024; // 5MB
            
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
            
            // CRITICAL FIX: Check if this POST key has an uploaded file
            // The key in $uploadedFiles matches the form field name (e.g., 'setting_site_logo')
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
?>


## 🎯 Key Changes Explained

### Change 1: Exact Key Matching
php
// OLD (didn't work):
if (isset($uploadedFiles[$key])) {
    // This checks for 'setting_site_logo' in uploadedFiles
    
// NEW (works):
if (isset($uploadedFiles[$key])) {
    // Same check, but now we ALSO handle files without POST fields below


### Change 2: Fallback for File-Only Fields (NEW!)
php
// NEW: Process files that don't have corresponding POST fields
foreach ($uploadedFiles as $fileKey => $filePath) {
    if (strpos($fileKey, 'setting_') === 0) {
        $settingKey = str_replace('setting_', '', $fileKey);
        
        // Only update if we haven't already processed this
        if (!in_array($settingKey, $processedKeys)) {
            // Save the file path to database
            if ($settingsModel->set($settingKey, $filePath)) {
                $updatedCount++;
            }
        }
    }
}


*Why this fixes it:* File input fields don't send POST data (only FILES data). The original code only processed POST fields, so file uploads were never saved to the database!

---

## 🧪 Alternative Solution (If Above Doesn't Work)

If the issue persists, try this simpler approach that processes files separately:

php
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $updatedCount = 0;
        $errors = [];

        // Get current settings for comparison
        $currentSettings = [];
        foreach ($settingsModel->getAll() as $setting) {
            $currentSettings[$setting['setting_key']] = $setting['setting_value'];
        }

        // Step 1: Process file uploads SEPARATELY
        foreach ($_FILES as $fileKey => $file) {
            // Skip if no file uploaded
            if ($file['error'] === UPLOAD_ERR_NO_FILE) {
                continue;
            }
            
            if ($file['error'] !== UPLOAD_ERR_OK) {
                $errors[] = "File upload error";
                continue;
            }

            // Validate file
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            $maxSize = 5 * 1024 * 1024;
            
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);
            
            if (!in_array($mimeType, $allowedTypes)) {
                $errors[] = "Invalid file type";
                continue;
            }
            
            if ($file['size'] > $maxSize) {
                $errors[] = "File too large";
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
                $filePath = '/media/uploads/' . $fileName;
                
                // Extract setting key (remove 'setting_' prefix)
                $settingKey = str_replace('setting_', '', $fileKey);
                
                // Save to database immediately
                $currentValue = $currentSettings[$settingKey] ?? '';
                
                if ($currentValue !== $filePath) {
                    if ($settingsModel->set($settingKey, $filePath)) {
                        $updatedCount++;
                    }
                }
            }
        }

        // Step 2: Process POST settings (non-file fields)
        $processedKeys = [];
        
        foreach ($_POST as $key => $value) {
            if (strpos($key, 'setting_') !== 0) {
                continue;
            }
            
            $settingKey = str_replace('setting_', '', $key);
            
            if (in_array($settingKey, $processedKeys)) {
                continue;
            }
            
            $processedKeys[] = $settingKey;
            
            if (is_string($value)) {
                $value = trim($value);
            }
            
            $currentValue = $currentSettings[$settingKey] ?? '';
            
            if ($currentValue !== $value) {
                if ($settingsModel->set($settingKey, $value)) {
                    $updatedCount++;
                }
            }
        }

        // Show message
        if (!empty($errors)) {
            $message = "Errors: " . implode(', ', $errors);
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
        $message = "Error: " . $e->getMessage();
        $messageType = 'danger';
    }
}
?>


---

## 🎯 Testing Steps

1. *Apply either fix above*
2. *Upload a logo image*
3. *Check the database:*
   sql
   SELECT * FROM settings WHERE setting_key = 'site_logo';
   
   Should show: /media/uploads/xxxxx_xxxxx.png

4. *Verify the file exists:*
   - Check /media/uploads/ folder
   - File should be there with a unique name

---

## 📊 What Should Happen

*Before Fix:*
- ✅ File uploads to /media/uploads/
- ❌ Database shows empty value for site_logo
- ❌ "Current: View File" doesn't appear after upload

*After Fix:*
- ✅ File uploads to /media/uploads/
- ✅ Database contains /media/uploads/xxxxx.png
- ✅ "Current: View File" link appears
- ✅ Update count shows 1 for logo upload

Try the first solution, and if it doesn't work, use the alternative (simpler) approach. Both should fix the issue! 🚀
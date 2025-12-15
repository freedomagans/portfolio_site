# Project Inventory - Phase 1 Assessment

## Overview
This inventory catalogs all PHP files that handle user input, forms, and database operations in the portfolio website project.

## Files That Handle Forms (Accept User Input)

### 🔴 Critical Risk Files
These handle sensitive operations and require immediate security attention.

1. **modules/auth/login.php**
   - Method: POST
   - Purpose: Admin authentication
   - Inputs: username, password
   - Risk: Authentication bypass, brute force attacks

2. **modules/projects/process_add.php**
   - Method: POST
   - Purpose: Add new projects with file uploads
   - Inputs: title, description, live_url, github_url, image1, image2, image3, is_published
   - Risk: File upload vulnerabilities, SQL injection

3. **modules/projects/process_edit.php**
   - Method: POST
   - Purpose: Edit existing projects with file uploads
   - Inputs: title, description, live_url, github_url, image1, image2, image3, is_published
   - Risk: File upload vulnerabilities, SQL injection

4. **modules/admin/update_profile.php**
   - Method: POST
   - Purpose: Update admin profile and password
   - Inputs: username, user_email, user_password
   - Risk: Privilege escalation, password exposure

### 🟡 High Risk Files
These handle user-generated content and admin operations.

5. **modules/contact/process_messages.php**
   - Method: POST
   - Purpose: Process contact form submissions
   - Inputs: name, email, subject, message
   - Risk: Email injection, spam

6. **modules/comments/process_comments.php**
   - Method: POST
   - Purpose: Process project comments
   - Inputs: project_id, name, content, website (honeypot)
   - Risk: XSS, SQL injection, spam

7. **modules/settings/index.php**
   - Method: POST
   - Purpose: Update application settings
   - Inputs: Various setting_* fields, file uploads
   - Risk: Configuration manipulation, file upload vulnerabilities

### 🟢 Medium Risk Files
These display user input but may have limited security impact.

8. **modules/projects/add.php**
   - Method: POST (form template)
   - Purpose: Project creation form
   - Risk: Form-based attacks

9. **modules/projects/edit.php**
   - Method: POST (form template)
   - Purpose: Project editing form
   - Risk: Form-based attacks

10. **modules/admin/profile.php**
    - Method: POST (form template)
    - Purpose: Profile update form
    - Risk: Form-based attacks

11. **modules/contact/contact.php**
    - Method: POST (form template)
    - Purpose: Contact form
    - Risk: Form-based attacks

## Files That Display User Input

### Templates with User-Generated Content
1. **templates/frontend/view_project.php**
   - Displays: Project details, comments, user input
   - Risk: XSS if not properly escaped

2. **templates/frontend/index.php**
   - Displays: Project listings, descriptions
   - Risk: XSS if not properly escaped

3. **templates/admin/dashboard.php**
   - Displays: Admin statistics, notifications
   - Risk: XSS if not properly escaped

## Database Query Files (All Models)

### Core Models
1. **models/UserModel.php**
   - Queries: Authentication, user management
   - Risk: SQL injection (though using prepared statements)

2. **models/ProjectModel.php**
   - Queries: CRUD operations for projects
   - Risk: SQL injection

3. **models/CommentModel.php**
   - Queries: Comment management
   - Risk: SQL injection

4. **models/NotificationModel.php**
   - Queries: Contact message management
   - Risk: SQL injection

5. **models/SettingsModel.php**
   - Queries: Application settings
   - Risk: SQL injection

6. **models/ProjectLikeModel.php**
   - Queries: Project likes tracking
   - Risk: SQL injection

7. **models/ProjectViewModel.php**
   - Queries: Project view tracking
   - Risk: SQL injection

## Summary Statistics

- **Total form-handling files**: 11
- **Critical risk files**: 4 (file uploads, auth, profile updates)
- **High risk files**: 3 (contact, comments, settings)
- **Medium risk files**: 4 (form templates)
- **Database models**: 7
- **Template files displaying user input**: 3

## Risk Assessment Questions

1. **How many pages accept user input?**
   - Answer: 11 PHP files handle form submissions, plus associated templates

2. **Which pages are most critical?**
   - **User-facing**: Contact form, project comments
   - **Admin-critical**: Login, project management, settings, profile updates
   - **File upload critical**: Project add/edit forms

## Next Steps (Phase 1, Day 3-4)
Based on this inventory, the priority order for security fixes should be:

1. 🔴 File upload forms (process_add.php, process_edit.php)
2. 🔴 Authentication (login.php)
3. 🔴 Admin operations (update_profile.php, settings)
4. 🟡 User input forms (contact, comments)
5. 🟢 Template output escaping

This inventory provides the roadmap for systematic security implementation in Phase 2.

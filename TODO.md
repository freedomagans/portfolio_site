# Comments System Repair TODO

## Issues to Fix
- [ ] Comment form submits via POST causing page reloads (no AJAX)
- [ ] No JavaScript handling for comment submission feedback
- [ ] Settings form may have submission issues with boolean toggles
- [ ] Missing integration between settings and comment auto-approval

## Implementation Plan
- [ ] Update comment form in view_project.php to ensure proper AJAX setup
- [ ] Add AJAX functionality and toast notifications to static/base/js/comments.js
- [ ] Fix settings form submission issues if any
- [ ] Ensure proper feedback display and auto-approval integration

## Files to Modify
- [x] templates/frontend/view_project.php (verify AJAX setup)
- [x] static/base/js/comments.js (add AJAX submission and toast notifications)
- [ ] modules/settings/index.php (fix form submission if needed)
- [ ] core/Settings.php (verify settings retrieval)

## Testing
- [ ] Test comment submission with AJAX
- [ ] Test settings form submission
- [ ] Test auto-approval functionality
- [ ] Test feedback display

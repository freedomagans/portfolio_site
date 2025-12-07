// Initialize AOS (Animate On Scroll) library
AOS.init({
    duration: 800,
    once: true,
    mirror: false
});

// Like button functionality
$(document).ready(function() {
    $('.project-like-btn').on('click', function(e) {
        e.preventDefault();

        const $btn = $(this);
        const projectId = $btn.data('project-id');
        const $count = $btn.find('.like-count');

        // Disable button to prevent multiple clicks
        $btn.addClass('loading');

        $.ajax({
            url: '/urls.php?pg=project_process_like&id=' + projectId,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Update like count
                    $count.text(response.likes);

                    // Toggle liked class
                    if (response.liked) {
                        $btn.addClass('liked heart-animate');
                        setTimeout(function() {
                            $btn.removeClass('heart-animate');
                        }, 500);
                    } else {
                        $btn.removeClass('liked');
                    }
                } else {
                    console.error('Like failed:', response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', error);
            },
            complete: function() {
                // Re-enable button
                $btn.removeClass('loading');
            }
        });
    });

    
});

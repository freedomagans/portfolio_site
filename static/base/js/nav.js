/**
 * Enhanced Navigation JavaScript
 */

document.addEventListener('DOMContentLoaded', function() {
    const navbar = document.querySelector('.navbar-modern');
    const scrollProgress = document.querySelector('.scroll-progress');
    const navLinks = document.querySelectorAll('.nav-link-modern');
    const currentPage = window.location.href;

    // Scroll Progress Bar
    function updateScrollProgress() {
        const windowHeight = window.innerHeight;
        const documentHeight = document.documentElement.scrollHeight;
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        const scrollPercent = (scrollTop / (documentHeight - windowHeight)) * 100;
        
        if (scrollProgress) {
            scrollProgress.style.width = scrollPercent + '%';
        }

        // Add scrolled class to navbar
        if (navbar) {
            if (scrollTop > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        }
    }

    // Set Active Link
    navLinks.forEach(link => {
        if (link.href === currentPage || currentPage.includes(link.getAttribute('href'))) {
            link.classList.add('active');
        }
    });

    // Update on scroll
    window.addEventListener('scroll', updateScrollProgress);
    updateScrollProgress(); // Initial call

    // Close mobile menu on link click
    navLinks.forEach(link => {
        link.addEventListener('click', () => {
            const navbarCollapse = document.getElementById('navbarNav');
            const bsCollapse = bootstrap.Collapse.getInstance(navbarCollapse);
            if (bsCollapse) {
                bsCollapse.hide();
            }
        });
    });

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href !== '#' && href !== '') {
                e.preventDefault();
                const target = document.querySelector(href);
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }
        });
    });
});

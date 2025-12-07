<?php
// Get settings instance
require_once __DIR__ . '/../../core/Settings.php';
$settings = AppSettings::getInstance();
$siteInfo = $settings->getSiteInfo();
$socialLinks = $settings->getSocialLinks();

include FRONTEND_TEMPLATE_PATH . 'header.php';
include FRONTEND_TEMPLATE_PATH . 'navigation.php';
?>


<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" />
<!-- Contact Page Styles -->
<link href="/static/base/css/contact.css" rel="stylesheet">


<!-- Contact Section -->
<section class="contact-section mt-3">
    <div class="container">
        <div class="row g-4">

            <!-- Contact Form Column -->
            <div class="col-lg-7" data-aos="fade-left" data-aos-delay="100">
                <div class="contact-form-card">
                    <h2 class="form-title">
                        <i class="fa fa-envelope"></i>
                        Get In Touch
                    </h2>

                    <p class="form-subtitle">
                        Have a project in mind or just want to say hello? I'd love to hear from you.
                        Drop me a message and I'll get back to you as soon as possible. <i class="fa fa-paper-plane"></i>
                    </p>


                    <!-- Contact Form -->
                    <form action="/urls.php?pg=process_messages" method="POST">
                        <?php msg_error();
                        msg_success(); ?>

                        <div class="form-group">
                            <label for="name">
                                <i class="fa fa-user"></i>
                                Full Name
                            </label>
                            <input type="text"
                                class="form-control"
                                id="name"
                                name="name"
                                placeholder="Enter name"
                                required>
                        </div>

                        <div class="form-group">
                            <label for="email">
                                <i class="fa fa-envelope"></i>
                                Email Address
                            </label>
                            <input type="email"
                                class="form-control"
                                id="email"
                                name="email"
                                placeholder="Enter email"
                                required>
                        </div>

                        <div class="form-group">
                            <label for="subject">
                                <i class="fa fa-tag"></i>
                                Subject
                            </label>
                            <input type="text"
                                class="form-control"
                                id="subject"
                                name="subject"
                                placeholder="What's this about?">
                        </div>

                        <div class="form-group">
                            <label for="message">
                                <i class="fa fa-comment"></i>
                                Message
                            </label>
                            <textarea name="message"
                                id="message"
                                class="form-control"
                                placeholder="Tell me about your project or inquiry..."
                                required></textarea>
                        </div>

                        <button type="submit" class="btn-submit">
                            Send Message
                            <i class="fa fa-arrow-right"></i>
                        </button>
                    </form>
                </div>
            </div>


            <!-- Contact Info Column -->
            <div class="col-lg-5" data-aos="fade-right">
                <div class="contact-info-card">
                    <h3 class="mb-4 fw-bold" style="color: #1e293b;">Contact Information</h3>

                    <!-- Email -->
                    <div class="contact-info-item">
                        <div class="contact-icon">
                            <i class="fa fa-envelope"></i>
                        </div>
                        <div class="contact-details">
                            <h5>Email Address</h5>
                            <p><a style="font-size: 12.5px;" href="mailto:<?php echo htmlspecialchars($siteInfo['email'] ?? 'freedomaganmwonyi99@gmail.com'); ?>"><?php echo htmlspecialchars($siteInfo['email'] ?? 'freedomaganmwonyi99@gmail.com'); ?></a></p>
                        </div>
                    </div>

                    <!-- Phone -->
                    <div class="contact-info-item">
                        <div class="contact-icon">
                            <i class="fa fa-phone"></i>
                        </div>
                        <div class="contact-details">
                            <h5>Phone Number</h5>
                            <p><a href="tel:+234 08168247299"><?php echo htmlspecialchars($siteInfo['phone'] ?? '+234 8168247299'); ?></a></p>
                        </div>
                    </div>

                    <!-- Location -->
                    <div class="contact-info-item">
                        <div class="contact-icon">
                            <i class="fa fa-map-marker-alt"></i>
                        </div>
                        <div class="contact-details">
                            <h5>Location</h5>
                            <p>Benin City, Edo State, Nigeria</p>
                        </div>
                    </div>

                    <!-- Social Links -->
                    <div class="mt-4">
                        <h5 class="fw-bold mb-3" style="color: #1e293b;">Follow Me</h5>
                        <div class="social-links">
                            <a href="<?php echo htmlspecialchars($socialLinks['github'] ?? '#'); ?>" class="social-link" title="GitHub">
                                <i class="fab fa-github"></i>
                            </a>
                            <a href="<?php echo htmlspecialchars($socialLinks['linkedin'] ?? '#'); ?>" class="social-link" title="LinkedIn">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                            <a href="<?php echo htmlspecialchars($socialLinks['twitter'] ?? '#'); ?>" class="social-link" title="Twitter">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="<?php echo htmlspecialchars($socialLinks['instagram'] ?? '#'); ?>" class="social-link" title="Instagram">
                                <i class="fab fa-instagram"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Feature Cards -->
        <div class="feature-cards" data-aos="fade-up" data-aos-delay="200">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fa fa-bolt"></i>
                </div>
                <h5>Quick Response</h5>
                <p>I typically respond to inquiries within 24 hours during business days</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fa fa-shield-alt"></i>
                </div>
                <h5>Confidential</h5>
                <p>Your information is safe and will never be shared with third parties</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fa fa-handshake"></i>
                </div>
                <h5>Professional</h5>
                <p>Dedicated to delivering quality work and excellent communication</p>
            </div>
        </div>
    </div>
    <!-- Contact Form JavaScript -->
    <script src="/static/base/js/contact.js"></script>
</section>

<!-- AOS Animation Script -->
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof AOS !== 'undefined') {
        AOS.init({
            duration: 800,
            once: true,
            mirror: false
        });
        // ensure AOS picks up any DOM changes made by contact.js
        AOS.refresh();
    }
});
</script>


<?php include FRONTEND_TEMPLATE_PATH . "footer.php"; ?>
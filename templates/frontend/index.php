<?php include FRONTEND_TEMPLATE_PATH . "header.php"; // header file ?>
<?php include FRONTEND_TEMPLATE_PATH . "navigation.php"; // navigation file ?>
<?php 
require_once __DIR__ . '/../../core/Settings.php'; // import settings
$settings = AppSettings::getInstance(); // get settings instance
$siteInfo = $settings->getSiteInfo(); // get siteinfo settings
$socialLinks = $settings->getSocialLinks(); // get sociallinks settings
?>

<!-- AOS css -->
<link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">

<!-- external index css -->
<link href="/static/base/css/index.css" rel="stylesheet">

<!-- external hero css -->
<link href="/static/base/css/hero.css" rel="stylesheet">


<!-- Hero / Introduction Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center g-5">

            <!-- Image Column (Left Side) -->
            <div class="col-lg-5" data-aos="fade-right" data-aos-duration="1000">
                <div class="hero-image-container">
                    <div class="image-backdrop"></div>
                    <img src="/media/logo.png"
                        class="hero-image"
                        alt="Freedom Ofure Aganmwonyi">

                    <!-- Floating Badge -->
                    <div class="floating-badge">
                        <i class="fas fa-code"></i>
                        <span>Available for Projects</span>
                    </div>
                </div>
            </div>

            <!-- Text Column (Right Side) -->
            <div class="col-lg-7" data-aos="fade-left" data-aos-duration="1000" data-aos-delay="200">

                <!-- Greeting Badge -->
                <div class="greeting-badge mb-3">
                    <span class="wave">👋</span>
                    <span>Hello, I'm</span>
                </div>

                <!-- Main Heading with Gradient -->
                <h1 class="hero-title mb-4">
                    Freedom Ofure
                    <span class="gradient-text">Aganmwonyi</span>
                </h1>

                <!-- Subtitle with Typing Effect -->
                <div class="hero-subtitle mb-4">
                    <span class="subtitle-prefix">Also known as</span>
                    <span class="brand-name">Faedin</span>
                </div>

                <!-- Role Tags -->
                <div class="role-tags mb-4">
                    <span class="role-tag">
                        <i class="fas fa-laptop-code"></i>
                        Programmer
                    </span>
                    <span class="role-tag">
                        <i class="fas fa-globe"></i>
                        Web Developer
                    </span>
                    <span class="role-tag mt-1">
                        <i class="fas fa-robot"></i>
                        Aspiring Mechatronics Engineer
                    </span>
                </div>

                <!-- Description -->
                <p class="hero-description mb-4">
                    I craft <span class="highlight">meaningful systems</span> with clean, scalable code and thoroughly tested solutions. My mission is to deliver <span class="highlight">professional, innovative solutions</span> that solve real-world problems.
                </p>

                <!-- Stats Row -->
                <div class="hero-stats mb-4">
                    <div class="stat-item">
                        <div class="stat-number">
                            <?php
                            require MODELS_PATH . 'ProjectModel.php'; // import Project Model
                            $projectModel = new Project(); // project instance
                            $projectCount = $projectModel->count(); // get number of projects 
                            echo $projectCount;
                            ?></div>
                        <div class="stat-label">Projects</div>
                    </div>
                    <div class="stat-divider"></div>
                    <div class="stat-item">
                        <div class="stat-number">3+</div>
                        <div class="stat-label">Years Experience</div>
                    </div>
                    <div class="stat-divider"></div>
                    <div class="stat-item">
                        <div class="stat-number">100%</div>
                        <div class="stat-label">Client Satisfaction</div>
                    </div>
                </div>

                <!-- CTA Buttons -->
                <div class="hero-cta">
                    <a href="/urls.php?pg=projects" class="btn btn-primary-custom btn-lg">
                        <span>View My Work</span>
                        <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                    <a href="/urls.php?pg=contact" class="btn btn-outline-custom btn-lg">
                        <span>Let's Connect</span>
                        <i class="fas fa-paper-plane ms-2"></i>
                    </a>
                </div>

                <!-- Social Links using socail links from settings or default constant values -->
                <div class="hero-social mt-4">
                    <a href="<?php echo htmlspecialchars($socialLinks['github'] ?: GITHUB_URL); ?>" class="social-link" title="GitHub">
                        <i class="fab fa-github"></i>
                    </a>
                    <a href="<?php echo htmlspecialchars($socialLinks['linkedin'] ?: LINKEDIN_URL); ?>" class="social-link" title="LinkedIn">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                    <a href="<?php echo htmlspecialchars($socialLinks['twitter'] ?: TWITTER_URL); ?>" class="social-link" title="Twitter">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="<?php echo htmlspecialchars($socialLinks['instagram'] ?: INSTAGRAM_URL); ?>" class="social-link" title="Instagram">
                        <i class="fab fa-instagram"></i>
                    </a>
                </div>

            </div>
        </div>
    </div>

    <!-- Animated Background Elements -->
    <div class="hero-bg-elements">
        <div class="floating-shape shape-1"></div>
        <div class="floating-shape shape-2"></div>
        <div class="floating-shape shape-3"></div>
    </div>

</section>
<!-- Hero section end -->

<!-- Skills & Expertise Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold section-title" data-aos="fade-up">Skills & Expertise</h2>
        </div>
        <div class="row g-4">
            <div class="col-md-3" data-aos="fade-up" data-aos-delay="100">
                <div class="card h-100 card-hover border-0 shadow-sm text-center py-4">
                    <i class="fa-solid fa-code fa-3x text-primary mb-3"></i>
                    <h5 class="fw-bold">Web Development</h5>
                    <p class="text-muted px-3">Creating modern, responsive, and scalable web applications using multiple stacks and best practices.</p>
                </div>
            </div>
            <div class="col-md-3" data-aos="fade-up" data-aos-delay="200">
                <div class="card h-100 card-hover border-0 shadow-sm text-center py-4">
                    <i class="fa-solid fa-gears fa-3x text-success mb-3"></i>
                    <h5 class="fw-bold">Programming & Testing</h5>
                    <p class="text-muted px-3">Clean, well-structured code with thorough testing to prevent failures and ensure robust performance.</p>
                </div>
            </div>
            <div class="col-md-3" data-aos="fade-up" data-aos-delay="300">
                <div class="card h-100 card-hover border-0 shadow-sm text-center py-4">
                    <i class="fa-solid fa-robot fa-3x text-warning mb-3"></i>
                    <h5 class="fw-bold">Mechatronics</h5>
                    <p class="text-muted px-3">Aspiring engineer with a vision to build innovative, automated, and heavy-duty engineering solutions.</p>
                </div>
            </div>
            <div class="col-md-3" data-aos="fade-up" data-aos-delay="400">
                <div class="card h-100 card-hover border-0 shadow-sm text-center py-4">
                    <i class="fa-solid fa-lightbulb fa-3x text-danger mb-3"></i>
                    <h5 class="fw-bold">Innovation & Vision</h5>
                    <p class="text-muted px-3">Collaborating with tech experts to solve complex problems and deliver high-impact solutions.</p>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- skills & expertise section end -->

<!-- Vision Section -->
<section class="py-5">
    <div class="container">
        <div class="row align-items-center g-5">
            <!-- logo img -->
            <div class="col-lg-6" data-aos="fade-right">
                <img src="/media/logo.png" class="img-fluid rounded shadow" alt="Vision Image">
            </div>
            <!-- Vision  -->
            <div class="col-lg-6" data-aos="fade-left">
                <h2 class="fw-bold mb-3 section-title">Our Vision</h2>
                <p class="text-muted">FaedinWebworks is the cornerstone of a greater tech entity serving web, app, and automation needs. Our mission is to deliver professional, scalable, and innovative solutions to clients of all sizes.</p>
                <p class="text-muted">We combine creativity, collaboration, and technology to solve real-world problems, inspire innovation, and build long-lasting systems that truly make a difference.</p>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action Section -->
<section class="py-5 bg-light text-center">
    <div class="container">
        <h2 class="fw-bold mb-3 section-title" data-aos="fade-up">Get in Touch</h2>
        <p class="text-muted mb-4" data-aos="fade-up" data-aos-delay="100">Reach out directly via WhatsApp or email for collaboration or inquiries.</p>

        <!-- WhatsApp link -->
        <a href="<?= WHATSAPP_URL ?>" class="btn btn-success btn-lg me-3 btn-hover" target="_blank" data-aos="zoom-in" data-aos-delay="200">
            <i class="fa-brands fa-whatsapp me-2"></i> WhatsApp
        </a>

        <!-- Email link -->
        <a href="mailto:<?= EMAIL ?>" class="btn btn-primary btn-lg btn-hover" data-aos="zoom-in" data-aos-delay="300">
            <i class="fa-solid fa-envelope me-2"></i> Email
        </a>

    </div>
</section>

<!-- Footer CTA -->
<section class="py-5 text-center">
    <div class="container">
        <h4 class="fw-bold" data-aos="fade-up">Building Meaningful Systems. Solving Real Problems.</h4>
        <p class="text-muted" data-aos="fade-up" data-aos-delay="100">Professionalism, innovation, and scalable solutions at your service.</p>
    </div>
</section>

<!-- AOS ANIMATION SCRIPT -->
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>
    AOS.init({
        duration: 1000,
        once: true,
        mirror: false
    });
</script>

<?php include FRONTEND_TEMPLATE_PATH . "footer.php"; // footer file?>
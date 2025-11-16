<?php
/**
 * Single Job Template
 *
 * @package ChainThat
 * @version 1.0.0
 */

get_header(); 

while (have_posts()) : the_post();
    $job_id = get_the_ID();
    $posted_date = get_field('job_posted_date') ?: get_the_date('d/m/Y');
    $job_description = get_field('job_description') ?: get_the_excerpt();
    $job_sections = get_field('job_sections') ?: [];
    
    // Get global job settings
    $job_settings = get_field('job_application_section', 'option');
    $cf7_form_id = $job_settings['application_cf7_form'] ?? null;
    $application_title = $job_settings['application_title'] ?? 'Apply for this Position';
?>

<!-- job-hero -->
<section class="job-hero">
    <div class="container">
        <div class="job-main">
            <span class="wow fadeInRight"><?php echo esc_html($posted_date); ?></span>
            <h2 class="wow fadeInLeft"><?php the_title(); ?></h2>
            <p class="wow fadeInRight"><?php echo esc_html($job_description); ?></p>
            <ul class="wow fadeInRight">
                <li>
                    <div class="btn-all job-btn10"><a href="#apply">Apply Now</a></div>
                </li>
                <li><div><a href="<?php echo esc_url(home_url('/careers')); ?>">Back to Careers<span><img src="<?php echo get_template_directory_uri(); ?>/images/arrow3.png" alt=""></span></a></div></li>
            </ul>
        </div>
    </div>
</section>

<!-- job-file-upload / Job Content Sections -->
<?php if ($job_sections && is_array($job_sections) && !empty($job_sections)): ?>
<section class="job-file-area">
    <div class="container">
        <div class="jobfile-main">
            <?php foreach ($job_sections as $section): 
                $section_title = $section['section_title'] ?? '';
                $section_description = $section['section_description'] ?? '';
                $section_items = $section['section_items'] ?? [];
            ?>
                <div class="jobfile-cnt">
                    <?php if ($section_title): ?>
                        <h3 class="wow fadeInRight"><?php echo esc_html($section_title); ?></h3>
                    <?php endif; ?>
                    
                    <?php if ($section_description): ?>
                        <p class="wow fadeInLeft"><?php echo esc_html($section_description); ?></p>
                    <?php endif; ?>
                    
                    <?php if ($section_items && is_array($section_items) && !empty($section_items)): ?>
                        <ul class="wow fadeInRight">
                            <?php foreach ($section_items as $item): ?>
                                <li>
                                    <span>
                                        <div class="dot"></div>
                                    </span>
                                    <p><?php echo esc_html($item['item_text']); ?></p>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php else: ?>
    <!-- Fallback: Display post content -->
    <?php if (get_the_content()): ?>
    <section class="job-file-area">
        <div class="container">
            <div class="jobfile-main">
                <div class="jobfile-cnt">
                    <div class="wow fadeInUp">
                        <?php the_content(); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>
<?php endif; ?>

<!-- Application Form Section -->
<section class="inner-file-wrapper" id="apply">
    <div class="container">
        <?php if ($cf7_form_id && function_exists('wpcf7_contact_form')): ?>
            <!-- CF7 Form Integration -->
            <div class="jobfile-box cf7-job-application-wrapper">
                <h2 class="wow fadeInLeft"><?php echo esc_html($application_title); ?></h2>
                <div class="cf7-form-wrapper wow fadeInUp">
                    <?php echo do_shortcode('[contact-form-7 id="' . $cf7_form_id . '"]'); ?>
                </div>
            </div>
        <?php else: ?>
            <!-- Default File Upload Form -->
            <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="action" value="submit_job_application">
                <input type="hidden" name="job_id" value="<?php echo esc_attr($job_id); ?>">
                <input type="hidden" name="job_title" value="<?php echo esc_attr(get_the_title()); ?>">
                <?php wp_nonce_field('job_application_submit', 'job_application_nonce'); ?>
                
                <div class="jobfile-box">
                    <h2 class="wow fadeInLeft">Upload Your Application</h2>
                    
                    <?php if (isset($_GET['application']) && $_GET['application'] === 'success'): ?>
                        <div class="application-success-message wow fadeInUp" style="background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 15px; margin-bottom: 20px; border-radius: 5px;">
                            <strong>✓ Application Submitted Successfully!</strong> We've received your application and will review it shortly.
                        </div>
                    <?php endif; ?>
                    
                    <div class="tuch-input wow fadeInLeft" style="margin-bottom: 20px;">
                        <div class="tuch-input-title">
                            <h4>Your Name</h4>
                        </div>
                        <input type="text" name="applicant_name" placeholder="Enter your full name" required>
                    </div>
                    
                    <label class="pdf-wrap" for="file-cv">
                        <div class="file-uplode wow fadeInRight">
                            <div class="file-img">
                                <img src="<?php echo get_template_directory_uri(); ?>/images/file.png" alt="">
                            </div>
                            <input class="d-none file-input-upload" id="file-cv" name="cv_file" type="file" accept=".pdf,.doc,.docx" required>
                            <h4>Drag & drop files or <a href="#">Browse</a></h4>
                            <p>Supported formats: PDF, DOC, DOCX (Max 5MB)</p>
                        </div>
                    </label>
                    <div class="pdf-upload wow fadeInLeft" style="display: none;">
                        <h4>Uploading - <span class="file-count">1</span> file(s)</h4>
                        <div class="pdf-box wow fadeInUp">
                            <div>
                                <h6 class="uploaded-filename">your-file-here.PDF</h6>
                            </div>
                            <div>
                                <img src="<?php echo get_template_directory_uri(); ?>/images/clos.png" alt="" class="remove-file" style="cursor: pointer;">
                            </div>
                        </div>
                    </div>
                    <div class="file-submit wow fadeInRight">
                        <button type="submit">Submit Application</button>
                    </div>
                </div>
                <div class="view-btn text-center d-none d-lg-block wow fadeInRight">
                    <a href="<?php echo esc_url(home_url('/careers#vacancies')); ?>"><span><img src="<?php echo get_template_directory_uri(); ?>/images/arrwo-left.png" alt=""></span>View Vacancies</a>
                </div>
            </form>
        <?php endif; ?>
    </div>
    <div class="part-mob d-block d-lg-none wow fadeInLeft">
        <img src="<?php echo get_template_directory_uri(); ?>/images/swoos4.png" alt="">
    </div>
</section>

<!-- tuch-section / Contact Form -->
<?php 
// Get global job contact settings
$job_contact_settings = get_field('job_contact_section', 'option');
$contact_cf7_form = $job_contact_settings['contact_cf7_form'] ?? null;
$contact_title = $job_contact_settings['contact_title'] ?? 'Get in touch';
?>
<section class="tuch-area">
    <div class="container">
        <div class="tuch-main">
            <div class="tuch-title wow fadeInLeft">
                <h2><?php echo esc_html($contact_title); ?></h2>
            </div>
            
            <?php if ($contact_cf7_form && function_exists('wpcf7_contact_form')): ?>
                <!-- Display Contact Form 7 -->
                <div class="cf7-form-wrapper wow fadeInUp">
                    <?php echo do_shortcode('[contact-form-7 id="' . $contact_cf7_form . '"]'); ?>
                </div>
            <?php else: ?>
                <!-- Fallback contact form -->
                <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
                    <input type="hidden" name="action" value="job_contact_form">
                    <?php wp_nonce_field('job_contact_submit', 'job_contact_nonce'); ?>
                    
                    <?php if (isset($_GET['contact']) && $_GET['contact'] === 'success'): ?>
                        <div class="contact-success-message wow fadeInUp" style="background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 15px; margin-bottom: 20px; border-radius: 5px;">
                            <strong>✓ Message Sent Successfully!</strong> Thank you for reaching out. We'll get back to you soon.
                        </div>
                    <?php endif; ?>
                    
                    <div class="tuch-form">
                        <div class="tuch-input-flex">
                            <div class="tuch-input wow fadeInRight">
                                <div class="tuch-input-title">
                                    <h4>First Name</h4>
                                </div>
                                <input type="text" name="first_name" placeholder="First Name" required>
                            </div>
                            <div class="tuch-input wow fadeInLeft">
                                 <div class="tuch-input-title">
                                    <h4>Email</h4>
                                </div>
                                <input required type="email" name="email" placeholder="Email">
                            </div>
                            <div class="tuch-input wow fadeInRight">
                                 <div class="tuch-input-title">
                                    <h4>Phone</h4>
                                </div>
                                <input type="text" name="phone" placeholder="000 000 0000">
                            </div> 
                            <div class="tuch-input wow fadeInLeft">
                                 <div class="tuch-input-title">
                                    <h4>Company</h4>
                                </div>
                                <input type="text" name="company" placeholder="Company Name">
                            </div>
                        </div>
                        <div class="tuch-textarea wow fadeInRight">
                             <div class="tuch-input-title">
                                <h4>Message</h4>
                            </div>
                            <textarea name="message" rows="4" placeholder="Message..."></textarea>
                        </div>
                        <div class="tuch-checkbox d-none d-lg-block wow fadeInLeft">
                            <div class="checkbox">
                                <input type="checkbox" id="check-contact" name="accept_terms" required>
                                <label for="check-contact">I accept <a href="#">Terms & Conditions.</a> Check our <a href="#">Privacy Policy</a></label>
                            </div>
                        </div>
                        <div class="tuch-submit wow fadeInRight">
                            <button type="submit">submit</button>
                        </div>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php endwhile; ?>

<?php get_footer(); ?>


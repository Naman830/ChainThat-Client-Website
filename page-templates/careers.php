<?php
/**
 * Template Name: Careers
 *
 * @package ChainThat
 * @version 1.0.0
 */

// Helper function to check if a section should be displayed
function is_careers_section_enabled($section_name) {
    $section_controls = get_field('careers_section_controls');
    if (!$section_controls) return true;
    $toggle_field = $section_name . '_section_toggle';
    return isset($section_controls[$toggle_field]) ? $section_controls[$toggle_field] === 'show' : true;
}

get_header(); ?>

<!-- about-section / Hero Section -->
<?php if (is_careers_section_enabled('hero')): ?>
<section class="about-area">
        <?php
    $hero_section = get_field('hero_section');
    $hero_title = $hero_section['hero_title'] ?? '';
    $hero_images = $hero_section['hero_images'] ?? [];
    $hero_subtitle = $hero_section['hero_subtitle'] ?? '';
    $hero_description = $hero_section['hero_description'] ?? '';
    $hero_button_text = $hero_section['hero_button_text'] ?? '';
    $hero_button_link = $hero_section['hero_button_link'] ?? '';
        ?>
        
        <div class="container">
        <div class="about-title text-center wow fadeInLeft">
             <h2><?php echo esc_html($hero_title ?: 'Join Us'); ?></h2>
        </div>
            </div>
            
    <?php if ($hero_images && is_array($hero_images) && !empty($hero_images)): ?>
    <!-- Continuous Scrolling Image Slider - Full Width -->
    <div class="careers-image-scroll-wrapper">
        <div class="careers-image-scroll">
            <div class="careers-image-scroll-track">
                <?php 
                // Display images twice for seamless loop
                for ($i = 0; $i < 2; $i++): 
                    foreach ($hero_images as $image): 
                ?>
                    <div class="careers-scroll-item">
                       <img src="<?php echo esc_url($image['image']); ?>" alt="<?php echo esc_attr($image['alt_text'] ?: 'Careers'); ?>">
                    </div>
                <?php 
                    endforeach;
                endfor; 
                ?>
            </div>
        </div>
    </div>
    <?php else: ?>
    <!-- Fallback: Owl Carousel for backward compatibility -->
    <div class="about-main wow fadeInUp">
       <div class="main-content10">
            <div id="owl-csel10" class="owl-carousel owl-theme">
                <div class="about-item wow fadeInLeft">
                   <img src="<?php echo get_template_directory_uri(); ?>/images/careers2.png" alt="">
                </div>
                <div class="about-item wow fadeInRight">
                   <img src="<?php echo get_template_directory_uri(); ?>/images/careers3.png" alt="">
                </div>
                <div class="about-item wow fadeInLeft">
                   <img src="<?php echo get_template_directory_uri(); ?>/images/careers4.png" alt="">
                </div>
                <div class="about-item wow fadeInRight">
                   <img src="<?php echo get_template_directory_uri(); ?>/images/careers1.png" alt="">
                </div>
            </div>
            <div class="owl-theme">
                <div class="owl-controls">
                    <div class="custom-nav owl-nav"></div>
                </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

            <div class="container">
        <div class="careers-btm">
            <h4 class="wow fadeInLeft"><?php echo esc_html($hero_subtitle ?: 'Accelerate your career with ChainThat'); ?></h4>
            <p class="wow fadeInRight"><?php echo esc_html($hero_description ?: 'Join ChainThat to disrupt the insurance industry with advanced technology. Our team are skilled and forward-thinking, eager to accelerate their careers, innovate and drive industry transformation.'); ?></p>
            <?php if ($hero_button_text): ?>
            <div class="btn-all careers-btn wow fadeInUp">
                <a href="<?php echo esc_url($hero_button_link ?: '#'); ?>"><?php echo esc_html($hero_button_text); ?></a>
            </div>
            <?php endif; ?>
            </div>
                </div>
    </section>
<?php endif; ?>

<!-- features-section / Values Section -->
<?php if (is_careers_section_enabled('values')): ?>
<section class="features-area">
                    <?php
    $values_section = get_field('values_section');
    $values_title = $values_section['values_title'] ?? '';
    $values_description = $values_section['values_description'] ?? '';
    $values_items = $values_section['values_items'] ?? [];
    ?>
    
            <div class="container">
        <div class="features-title features-title2">
            <h2 class="wow fadeInRight"><?php echo esc_html($values_title ?: 'Our Values'); ?></h2>
            <p class="wow fadeInLeft"><?php echo esc_html($values_description ?: 'At ChainThat, we\'re driven by innovation, collaboration, and excellence. We\'re building our vision together.'); ?></p>
        </div>
        
        <?php if ($values_items && is_array($values_items) && !empty($values_items)): ?>
        <!-- Desktop View - Carousel with 4 columns -->
        <div class="d-none d-lg-block">
            <div class="main-content12-desktop">
                <div id="owl-csel12-desktop" class="owl-carousel owl-theme">
                    <?php foreach ($values_items as $value): ?>
                    <div class="features-item">
                        <img class="wow fadeInLeft" src="<?php echo esc_url($value['icon']); ?>" alt="<?php echo esc_attr($value['title']); ?>">
                        <h2 class="wow fadeInRight"><?php echo esc_html($value['title']); ?></h2>
                        <p class="wow fadeInLeft"><?php echo esc_html($value['description']); ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="owl-theme">
                    <div class="owl-controls">
                        <div class="custom-nav owl-nav"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Mobile/Tablet View -->
        <div class="features-mobil d-block d-lg-none">
            <div class="main-content12">
                <div id="owl-csel12" class="owl-carousel owl-theme">
                    <?php foreach ($values_items as $value): ?>
                    <div class="features-item">
                        <img class="wow fadeInLeft" src="<?php echo esc_url($value['icon']); ?>" alt="<?php echo esc_attr($value['title']); ?>">
                        <h2 class="wow fadeInRight"><?php echo esc_html($value['title']); ?></h2>
                        <p class="wow fadeInLeft"><?php echo esc_html($value['description']); ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="owl-theme">
                    <div class="owl-controls">
                        <div class="custom-nav owl-nav"></div>
                    </div>
                            </div>
                            </div>
                        </div>
        <?php else: ?>
        <!-- No values found -->
        <div class="col-12 text-center">
            <p style="color: #999; padding: 40px 0;">No values added yet. Please add company values in the Features section.</p>
        </div>
        <?php endif; ?>
            </div>
        </section>
    <?php endif; ?>

<!-- adminis-video-section / Video Section -->
<?php if (is_careers_section_enabled('video')): ?>
<section class="adminis-video-area solution-video-area">
                    <?php
    $video_section = get_field('video_section');
    $video_title = $video_section['video_title'] ?? '';
    $video_description = $video_section['video_description'] ?? '';
    $video_desktop_cover = $video_section['video_desktop_cover'] ?? '';
    $video_mobile_cover = $video_section['video_mobile_cover'] ?? '';
    $video_type = $video_section['video_type'] ?? 'youtube';
    $video_url = $video_section['video_url'] ?? '';
    ?>
    
            <div class="container">
        <div class="adminis-video-main solution-video">
            <div class="adminis-video-left order-solu1">
                <h3 class="wow fadeInLeft"><?php echo esc_html($video_title ?: 'ChainThat Leadership Bytes'); ?></h3>
                <p class="wow fadeInRight"><?php echo esc_html($video_description); ?></p>
            </div>
            <div class="adminis-video-right order-solu2 wow fadeInUp">
                <div class="video-wrapper video-wrapper2" data-video-type="<?php echo esc_attr($video_type); ?>">
                    <img class="video-cover img-desktop" src="<?php echo esc_url($video_desktop_cover ?: get_template_directory_uri() . '/images/about-video.png'); ?>" alt="Desktop Video">
                    <img class="video-cover img-mobile" src="<?php echo esc_url($video_mobile_cover ?: get_template_directory_uri() . '/images/about-video-m.png'); ?>" alt="Mobile Video">
                    <iframe src="<?php echo esc_url($video_url); ?>" frameborder="0" allowfullscreen></iframe>
                    <button class="play-btn-kp"><img src="<?php echo get_template_directory_uri(); ?>/images/play.png" alt="Play"></button>
                    <button class="close-btn-kp"><i class="fas fa-times"></i></button>
                </div>
            </div>
                </div>
            </div>
        </section>
    <?php endif; ?>

<!-- employee-testimonials-section - Centered Quote Design -->
<?php if (is_careers_section_enabled('testimonials')): ?>
<section class="review-area review-area25 careers-testimonials-area">
    <?php
    $testimonials_section = get_field('testimonials_section');
    $selected_testimonials = $testimonials_section['testimonials_reviews'] ?? [];
    ?>
    
    <div class="container">
        <div class="careers-testimonials-wrap">
            <?php if ($selected_testimonials && is_array($selected_testimonials) && !empty($selected_testimonials)): ?>
                <div class="main-content11">
                    <div id="owl-csel11" class="owl-carousel owl-theme">
                        <?php foreach ($selected_testimonials as $testimonial_post): 
                            // Get employee testimonial post fields
                            $testimonial_id = $testimonial_post->ID;
                            
                            // Get testimonial quote from ACF or post content
                            $testimonial_text = get_field('employee_testimonial_quote', $testimonial_id);
                            if (empty($testimonial_text)) {
                                $raw_content = get_the_content(null, false, $testimonial_id);
                                $testimonial_text = wp_strip_all_tags(strip_shortcodes($raw_content));
                            }
                            if (empty($testimonial_text)) {
                                $testimonial_text = get_the_excerpt($testimonial_id);
                            }
                            
                            // Get employee details from ACF fields
                            $employee_photo = get_field('employee_photo', $testimonial_id);
                            $employee_name = get_field('employee_name', $testimonial_id) ?: get_the_title($testimonial_id);
                            $employee_position = get_field('employee_position', $testimonial_id);
                        ?>
                            <div class="careers-testimonial-item">
                                <div class="careers-quote-icon wow fadeInDown">
                                    <img src="<?php echo get_template_directory_uri(); ?>/images/quote-icon.svg" alt="Quote">
                                </div>
                                <p class="careers-testimonial-text wow fadeInUp"><?php echo esc_html($testimonial_text); ?></p>
                                <div class="careers-testimonial-author wow fadeInUp">
                                    <div class="careers-author-icon">
                                        <?php if ($employee_photo): ?>
                                            <img src="<?php echo esc_url($employee_photo); ?>" alt="<?php echo esc_attr($employee_name); ?>">
                                        <?php else: ?>
                                            <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <circle cx="24" cy="24" r="23.5" stroke="#00524C" stroke-opacity="0.2"/>
                                                <circle cx="24" cy="18" r="6" fill="#00524C" fill-opacity="0.3"/>
                                                <path d="M12 36C12 30.4772 16.4772 26 22 26H26C31.5228 26 36 30.4772 36 36V38H12V36Z" fill="#00524C" fill-opacity="0.3"/>
                                            </svg>
                                        <?php endif; ?>
                                    </div>
                                    <h4 class="careers-author-name"><?php echo esc_html($employee_name); ?></h4>
                                    <?php if ($employee_position): ?>
                                        <h5 class="careers-author-position"><?php echo esc_html($employee_position); ?></h5>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="owl-theme">
                        <div class="owl-controls">
                            <div class="custom-nav owl-nav"></div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <!-- Fallback: Get latest 3 employee testimonials -->
                <?php
                $fallback_testimonials = get_posts(array(
                    'post_type' => 'employee_testimonial',
                    'posts_per_page' => 3,
                    'orderby' => 'date',
                    'order' => 'DESC'
                ));
                
                if ($fallback_testimonials): ?>
                    <div class="main-content11">
                        <div id="owl-csel11" class="owl-carousel owl-theme">
                            <?php foreach ($fallback_testimonials as $testimonial_post): 
                                setup_postdata($testimonial_post);
                                $testimonial_id = $testimonial_post->ID;
                                
                                // Get testimonial quote from ACF or post content
                                $testimonial_text = get_field('employee_testimonial_quote', $testimonial_id);
                                if (empty($testimonial_text)) {
                                    $raw_content = get_the_content(null, false, $testimonial_id);
                                    $testimonial_text = wp_strip_all_tags(strip_shortcodes($raw_content));
                                }
                                if (empty($testimonial_text)) {
                                    $testimonial_text = get_the_excerpt($testimonial_id);
                                }
                                
                                // Get employee details from ACF fields
                                $employee_photo = get_field('employee_photo', $testimonial_id);
                                $employee_name = get_field('employee_name', $testimonial_id) ?: get_the_title($testimonial_id);
                                $employee_position = get_field('employee_position', $testimonial_id);
                            ?>
                                <div class="careers-testimonial-item">
                                    <div class="careers-quote-icon wow fadeInDown">
                                        <img src="<?php echo get_template_directory_uri(); ?>/images/quote-icon.svg" alt="Quote">
                                    </div>
                                    <p class="careers-testimonial-text wow fadeInUp"><?php echo esc_html($testimonial_text); ?></p>
                                    <div class="careers-testimonial-author wow fadeInUp">
                                        <div class="careers-author-icon">
                                            <?php if ($employee_photo): ?>
                                                <img src="<?php echo esc_url($employee_photo); ?>" alt="<?php echo esc_attr($employee_name); ?>">
                                            <?php else: ?>
                                                <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <circle cx="24" cy="24" r="23.5" stroke="#00524C" stroke-opacity="0.2"/>
                                                    <circle cx="24" cy="18" r="6" fill="#00524C" fill-opacity="0.3"/>
                                                    <path d="M12 36C12 30.4772 16.4772 26 22 26H26C31.5228 26 36 30.4772 36 36V38H12V36Z" fill="#00524C" fill-opacity="0.3"/>
                                                </svg>
                                            <?php endif; ?>
                                        </div>
                                        <h4 class="careers-author-name"><?php echo esc_html($employee_name); ?></h4>
                                        <?php if ($employee_position): ?>
                                            <h5 class="careers-author-position"><?php echo esc_html($employee_position); ?></h5>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; 
                            wp_reset_postdata(); ?>
                        </div>
                        <div class="owl-theme">
                            <div class="owl-controls">
                                <div class="custom-nav owl-nav"></div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- No employee testimonials found -->
                    <div class="col-12 text-center">
                        <p style="color: #999; padding: 40px 0;">No employee testimonials added yet. Please add employee testimonials in the Employee Testimonials section.</p>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- vacancies-section - 3 Column Grid Design with Pagination -->
<?php if (is_careers_section_enabled('vacancies')): ?>
<section class="vacancies-area careers-vacancies-grid" id="vacancies">
    <?php 
    $vacancies_section = get_field('vacancies_section');
    $vacancies_title = $vacancies_section['vacancies_title'] ?? 'Vacancies';
    $vacancies_mode = $vacancies_section['vacancies_mode'] ?? 'auto';
    $vacancies_count = isset($vacancies_section['vacancies_count']) ? intval($vacancies_section['vacancies_count']) : 6;
    $show_linkedin = isset($vacancies_section['vacancies_linkedin_enable']) ? $vacancies_section['vacancies_linkedin_enable'] : false;
    
    // Get job posts based on mode
    $job_posts = [];
    
    if ($vacancies_mode === 'manual' && !empty($vacancies_section['vacancies_jobs'])) {
        // Manual mode: Use selected jobs
        $job_posts = $vacancies_section['vacancies_jobs'];
        // Limit to vacancies_count
        $job_posts = array_slice($job_posts, 0, $vacancies_count);
    } else {
        // Auto mode: Get latest jobs
        $job_posts = get_posts(array(
            'post_type' => 'job',
            'posts_per_page' => $vacancies_count,
            'orderby' => 'date',
            'order' => 'DESC',
            'post_status' => 'publish'
        ));
    }
    ?>
    
    <div class="container">
        <div class="vacancies-title text-center wow fadeInUp">
            <h2><?php echo esc_html($vacancies_title); ?></h2>
        </div>
        
        <?php if ($job_posts && is_array($job_posts) && !empty($job_posts)): ?>
            <!-- Desktop: 3 Column Grid -->
            <div class="vacancies-grid-main d-none d-lg-block">
                <div class="vacancies-grid-container">
                    <div class="row g-4 vacancies-grid-items">
                        <?php foreach ($job_posts as $job_post): 
                            $job_id = is_object($job_post) ? $job_post->ID : $job_post;
                            $posted_date = get_field('job_posted_date', $job_id) ?: get_the_date('d/m/Y', $job_id);
                            $job_title = get_the_title($job_id);
                            $job_description = get_field('job_description', $job_id) ?: get_the_excerpt($job_id);
                            $job_location = get_field('job_location', $job_id);
                            $job_url = get_permalink($job_id);
                            $linkedin_url = get_field('job_linkedin_url', $job_id);
                        ?>
                            <div class="col-lg-4 col-md-6 mb-4 vacancy-card-wrapper">
                                <div class="vacancies-card wow fadeInUp">
                                    <div class="vacancies-card-header">
                                        <span class="vacancies-date"><?php echo esc_html($posted_date); ?></span>
                                        <?php if ($job_location): ?>
                                            <span class="vacancies-location"><?php echo esc_html($job_location); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <h3 class="vacancies-job-title"><?php echo esc_html($job_title); ?></h3>
                                    <p class="vacancies-description"><?php echo esc_html(wp_trim_words($job_description, 20, '...')); ?></p>
                                    <div class="vacancies-card-footer">
                                        <a href="<?php echo esc_url($job_url); ?>" class="vacancies-link vacancies-link-primary">Find out more</a>
                                        <?php if ($show_linkedin && $linkedin_url): ?>
                                            <a href="<?php echo esc_url($linkedin_url); ?>" class="vacancies-link vacancies-link-linkedin" target="_blank" rel="noopener">Apply via LinkedIn</a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- View All Button -->
                    <div class="vacancies-view-all-wrapper text-center mt-5 wow fadeInUp">
                        <a href="<?php echo esc_url(get_post_type_archive_link('job')); ?>" class="vacancies-view-all">View All Vacancies</a>
                    </div>
                </div>
            </div>
            
            <!-- Mobile/Tablet: List View -->
            <div class="vacancies-main d-block d-lg-none">
                <?php foreach ($job_posts as $job_post): 
                    $job_id = is_object($job_post) ? $job_post->ID : $job_post;
                    $posted_date = get_field('job_posted_date', $job_id) ?: get_the_date('d/m/Y', $job_id);
                    $job_title = get_the_title($job_id);
                    $job_description = get_field('job_description', $job_id) ?: get_the_excerpt($job_id);
                    $job_url = get_permalink($job_id);
                    $linkedin_url = get_field('job_linkedin_url', $job_id);
                ?>
                    <div class="vacancies-item wow fadeInUp">
                        <span class="wow fadeInLeft"><?php echo esc_html($posted_date); ?></span>
                        <h3 class="wow fadeInRight"><?php echo esc_html($job_title); ?></h3>
                        <p class="wow fadeInLeft"><?php echo esc_html(wp_trim_words($job_description, 25, '...')); ?></p>
                        <div class="wow fadeInRight">
                            <a href="<?php echo esc_url($job_url); ?>">Find out more<span><img src="<?php echo get_template_directory_uri(); ?>/images/arrow3.png" alt=""></span></a>
                        </div>
                        <?php if ($show_linkedin && $linkedin_url): ?>
                        <div class="wow fadeInLeft">
                            <a href="<?php echo esc_url($linkedin_url); ?>" target="_blank" rel="noopener">Apply via LinkedIn<span><img src="<?php echo get_template_directory_uri(); ?>/images/arrow3.png" alt=""></span></a>
                        </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <!-- Fallback: Get jobs from Job post type -->
            <?php
            $job_posts = get_posts(array(
                'post_type' => 'job',
                'posts_per_page' => 12,
                'orderby' => 'date',
                'order' => 'DESC',
                'post_status' => 'publish'
            ));
            
            if ($job_posts): ?>
                <!-- Desktop: 3 Column Grid with Pagination -->
                <div class="vacancies-grid-main d-none d-lg-block">
                    <div class="vacancies-grid-container" data-items-per-page="6">
                        <div class="row g-4 vacancies-grid-items">
                                <?php
                            foreach ($job_posts as $index => $job_post): 
                                setup_postdata($job_post);
                                $job_id = $job_post->ID;
                                $posted_date = get_field('job_posted_date', $job_id) ?: get_the_date('d/m/Y', $job_id);
                                $job_location = get_field('job_location', $job_id) ?: '';
                                $job_description = get_field('job_description', $job_id) ?: get_the_excerpt($job_id);
                                $job_permalink = get_permalink($job_id);
                                // Show only first 6 items initially
                                $display_style = $index >= 6 ? 'style="display: none;"' : '';
                            ?>
                                <div class="col-lg-4 col-md-6 mb-4 vacancy-card-wrapper" <?php echo $display_style; ?>>
                                    <div class="vacancies-card wow fadeInUp">
                                        <span class="vacancies-date"><?php echo esc_html($posted_date); ?></span>
                                        <h3 class="vacancies-job-title"><?php echo esc_html(get_the_title($job_id)); ?></h3>
                                        <p class="vacancies-description"><?php echo esc_html($job_description); ?></p>
                                        <div class="vacancies-card-footer">
                                            <a href="<?php echo esc_url($job_permalink); ?>" class="vacancies-link">Find out more</a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; 
                            wp_reset_postdata(); ?>
                            </div>
                            
                            <?php if (count($job_posts) > 6): ?>
                            <!-- View All Button -->
                            <div class="vacancies-view-all-wrapper text-center mt-5 wow fadeInUp">
                                <a href="<?php echo esc_url(get_post_type_archive_link('job')); ?>" class="vacancies-view-all">View All Vacancies</a>
                            </div>
                            <?php endif; ?>
                                </div>
                            </div>
                            
                <!-- Mobile/Tablet: List View -->
                <div class="vacancies-main d-block d-lg-none">
                    <?php foreach ($job_posts as $job_post): 
                        setup_postdata($job_post);
                        $job_id = $job_post->ID;
                        $posted_date = get_field('job_posted_date', $job_id) ?: get_the_date('d/m/Y', $job_id);
                        $job_description = get_field('job_description', $job_id) ?: get_the_excerpt($job_id);
                        $job_permalink = get_permalink($job_id);
                    ?>
                        <div class="vacancies-item wow fadeInUp">
                            <span class="wow fadeInLeft"><?php echo esc_html($posted_date); ?></span>
                            <h3 class="wow fadeInRight"><?php echo esc_html(get_the_title($job_id)); ?></h3>
                            <p class="wow fadeInLeft"><?php echo esc_html($job_description); ?></p>
                            <div class="wow fadeInRight">
                                <a href="<?php echo esc_url($job_permalink); ?>">Find out more<span><img src="<?php echo get_template_directory_uri(); ?>/images/arrow3.png" alt=""></span></a>
                            </div>
                        </div>
                    <?php endforeach; 
                    wp_reset_postdata(); ?>
                </div>
            <?php else: ?>
                <!-- Final fallback: No jobs found message -->
                <div class="text-center py-5">
                    <p class="wow fadeInUp">No vacancies available at the moment. Please check back later.</p>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    <div class="part-mob d-block d-lg-none wow fadeInRight">
        <img src="<?php echo get_template_directory_uri(); ?>/images/swoos4.png" alt="">
    </div>
</section>

<?php endif; ?>

<!-- tuch-section / Contact Form Section -->
<?php if (is_careers_section_enabled('form')): ?>
<section class="tuch-area">
                                <?php
    $form_section = get_field('form_section');
    $form_title = $form_section['form_title'] ?? '';
    $cf7_form_id = $form_section['cf7_form'] ?? null;
                                ?>
    
    <div class="container">
        <div class="tuch-main">
            <div class="tuch-title wow fadeInRight">
                <h2><?php echo esc_html($form_title ?: 'Get in touch'); ?></h2>
                            </div>
                            
            <?php if ($cf7_form_id && function_exists('wpcf7_contact_form')): ?>
                <!-- Display Contact Form 7 -->
                <div class="cf7-form-wrapper wow fadeInUp">
                    <?php echo do_shortcode('[contact-form-7 id="' . $cf7_form_id . '"]'); ?>
                </div>
            <?php else: ?>
                <!-- Fallback: Default HTML Form -->
                <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
                    <input type="hidden" name="action" value="careers_contact_form">
                    <div class="tuch-form">
                        <div class="tuch-input-flex wow fadeInUp">
                            <div class="tuch-input wow fadeInLeft">
                                <div class="tuch-input-title">
                                    <h4>First Name</h4>
                                </div>
                                <input type="text" name="first_name" placeholder="First Name" required>
                            </div>
                            <div class="tuch-input wow fadeInRight">
                                 <div class="tuch-input-title">
                                    <h4>Email</h4>
                                </div>
                                <input required type="email" name="email" placeholder="Email">
                            </div>
                            <div class="tuch-input wow fadeInLeft">
                                 <div class="tuch-input-title">
                                    <h4>Phone</h4>
                                </div>
                                <input type="text" name="phone" placeholder="000 000 0000">
                            </div> 
                            <div class="tuch-input wow fadeInRight">
                                 <div class="tuch-input-title">
                                    <h4>Company</h4>
                                </div>
                                <input type="text" name="company" placeholder="Company Name">
                            </div>
                        </div>
                        <div class="tuch-textarea wow fadeInLeft">
                             <div class="tuch-input-title">
                                <h4>Message</h4>
                            </div>
                            <textarea name="message" rows="4" placeholder="Message..."></textarea>
                        </div>
                        <div class="tuch-checkbox d-none d-lg-block wow fadeInRight">
                                <div class="checkbox">
                                <input type="checkbox" id="check" name="accept_terms" required>
                                <label for="check">I accept <a href="#">Terms & Conditions.</a> Check our <a href="#">Privacy Policy</a></label>
                                </div>
                            </div>
                        <div class="tuch-submit wow fadeInLeft">
                            <button type="submit">submit</button>
                            </div>
                        </div>
                    </form>
            <?php endif; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

<?php get_footer(); ?>

<?php
/**
 * The template for displaying job archive
 *
 * @package ChainThat
 * @version 1.0.0
 */

get_header(); ?>

<!-- jobs-section -->
<section class="news-area jobs-archive-area">
    <div class="container">
        <div class="news-title wow fadeInLeft">
            <h2><?php echo esc_html__('Careers', 'chainthat'); ?></h2>
            <p><?php echo esc_html__('Join ChainThat to disrupt the insurance industry with advanced technology', 'chainthat'); ?></p>
        </div>
        <div class="news-main jobs-featured-main">
            <?php 
            // Get featured jobs (latest 3)
            $featured_jobs = new WP_Query(array(
                'post_type' => 'job',
                'posts_per_page' => 3,
                'post_status' => 'publish',
                'orderby' => 'date',
                'order' => 'DESC'
            ));
            
            if ($featured_jobs->have_posts()): 
                $job_count = 0;
                while ($featured_jobs->have_posts()): 
                    $featured_jobs->the_post();
                    $job_count++;
                    $job_id = get_the_ID();
                    $posted_date = get_field('job_posted_date', $job_id) ?: get_the_date('d/m/Y', $job_id);
                    $job_location = get_field('job_location', $job_id) ?: '';
                    $job_description = get_field('job_description', $job_id) ?: get_the_excerpt($job_id);
                    $featured_image = get_the_post_thumbnail_url($job_id, 'medium');
                    
                    // Truncate description
                    $truncated_desc = (strlen($job_description) > 100) ? substr($job_description, 0, 100) . '...' : $job_description;
                    ?>
                    <div class="news-item jobs-featured-item wow fadeInRight">
                        <a href="<?php echo esc_url(get_permalink($job_id)); ?>">
                            <?php if ($featured_image): ?>
                                <img class="wow fadeInRight" src="<?php echo esc_url($featured_image); ?>" alt="<?php echo esc_attr(get_the_title($job_id)); ?>">
                            <?php else: ?>
                                <div class="job-featured-placeholder">
                                    <div class="job-placeholder-icon">
                                        <svg width="80" height="80" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <rect width="80" height="80" rx="10" fill="#F8F9FA"/>
                                            <path d="M40 20C38.8954 20 38 20.8954 38 22V24H30C27.7909 24 26 25.7909 26 28V54C26 56.2091 27.7909 58 30 58H50C52.2091 58 54 56.2091 54 54V28C54 25.7909 52.2091 24 50 24H42V22C42 20.8954 41.1046 20 40 20ZM30 28H50V54H30V28ZM34 32V36H46V32H34ZM34 40V44H46V40H34Z" fill="#00524C" fill-opacity="0.3"/>
                                        </svg>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </a>
                        <!-- Add posted date below image -->
                        <div class="news-date jobs-date wow fadeInUp">
                            <span><?php echo esc_html($posted_date); ?></span>
                            <?php if ($job_location): ?>
                                <span class="job-location-badge"><?php echo esc_html($job_location); ?></span>
                            <?php endif; ?>
                        </div>
                        <h2 class="wow fadeInUp"><a href="<?php echo esc_url(get_permalink($job_id)); ?>"><?php echo esc_html(get_the_title($job_id)); ?></a></h2>
                        <p class="job-featured-desc wow fadeInUp"><?php echo esc_html($truncated_desc); ?></p>
                        <div class="btn-all news-btn wow fadeInUp">
                            <a href="<?php echo esc_url(get_permalink($job_id)); ?>">View Details</a>
                        </div>
                    </div>
                    <?php
                endwhile;
                wp_reset_postdata();
            else: ?>
                <div class="no-jobs-message">
                    <p><?php echo esc_html__('No job openings available at the moment. Please check back later.', 'chainthat'); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- tab-section / Job Filters -->
<section class="tab-area jobs-filter-area">
    <div class="container">
        <div class="tab-main wow fadeInUp">
            <div class="tab-container" data-set="jobs">
                <div class="tab-buttons">
                    <a href="#all-jobs" class="tab-btn active" data-tab="all-jobs">All Jobs</a>
                    <?php 
                    // Get unique locations from published jobs
                    global $wpdb;
                    $locations = $wpdb->get_col("
                        SELECT DISTINCT meta_value 
                        FROM {$wpdb->postmeta} pm
                        INNER JOIN {$wpdb->posts} p ON pm.post_id = p.ID
                        WHERE pm.meta_key = 'job_location' 
                        AND pm.meta_value != ''
                        AND p.post_type = 'job'
                        AND p.post_status = 'publish'
                        ORDER BY meta_value ASC
                    ");
                    
                    if ($locations && is_array($locations)):
                        foreach ($locations as $location): 
                            $location_slug = sanitize_title($location);
                            ?>
                            <a href="#<?php echo esc_attr($location_slug); ?>" class="tab-btn" data-tab="<?php echo esc_attr($location_slug); ?>"><?php echo esc_html($location); ?></a>
                        <?php 
                        endforeach;
                    endif; ?>
                </div>
                
                <div class="tab-content">
                    <!-- All Jobs Tab -->
                    <div id="all-jobs" class="tab-pane active">
                        <div class="tab-item-wrap jobs-grid-wrap">
                            <?php 
                            // Query all jobs
                            $all_jobs = new WP_Query(array(
                                'post_type' => 'job',
                                'posts_per_page' => -1,
                                'post_status' => 'publish',
                                'orderby' => 'date',
                                'order' => 'DESC'
                            ));
                            
                            if ($all_jobs->have_posts()): 
                                while ($all_jobs->have_posts()): 
                                    $all_jobs->the_post();
                                    $job_id = get_the_ID();
                                    $posted_date = get_field('job_posted_date', $job_id) ?: get_the_date('d/m/Y', $job_id);
                                    $job_location = get_field('job_location', $job_id) ?: '';
                                    $job_description = get_field('job_description', $job_id) ?: get_the_excerpt($job_id);
                                    
                                    // Truncate title and description
                                    $title = get_the_title($job_id);
                                    $truncated_title = (strlen($title) > 60) ? substr($title, 0, 60) . '...' : $title;
                                    $truncated_desc = (strlen($job_description) > 120) ? substr($job_description, 0, 120) . '...' : $job_description;
                                    ?>
                                    <div class="blog-item blog-item50 job-listing-item">
                                        <div class="job-listing-header">
                                            <span class="job-date"><?php echo esc_html($posted_date); ?></span>
                                            <?php if ($job_location): ?>
                                                <span class="job-location"><?php echo esc_html($job_location); ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <h3><a href="<?php echo esc_url(get_permalink($job_id)); ?>"><?php echo esc_html($truncated_title); ?></a></h3>
                                        <p class="job-excerpt"><?php echo esc_html($truncated_desc); ?></p>
                                        <div class="blog-btn job-apply-btn">
                                            <a href="<?php echo esc_url(get_permalink($job_id)); ?>">View Details & Apply</a>
                                        </div>
                                    </div>
                                    <?php
                                endwhile;
                                wp_reset_postdata();
                            else: ?>
                                <div class="tab-cnt20">
                                    <?php echo esc_html__('No jobs found.', 'chainthat'); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php 
                    // Create tabs for each location
                    if ($locations && is_array($locations)):
                        foreach ($locations as $location): 
                            $location_slug = sanitize_title($location);
                            ?>
                            <div id="<?php echo esc_attr($location_slug); ?>" class="tab-pane">
                                <div class="tab-item-wrap jobs-grid-wrap">
                                    <?php 
                                    // Query jobs for this specific location
                                    $location_jobs = new WP_Query(array(
                                        'post_type' => 'job',
                                        'posts_per_page' => -1,
                                        'post_status' => 'publish',
                                        'orderby' => 'date',
                                        'order' => 'DESC',
                                        'meta_query' => array(
                                            array(
                                                'key' => 'job_location',
                                                'value' => $location,
                                                'compare' => '='
                                            ),
                                        ),
                                    ));
                                    
                                    if ($location_jobs->have_posts()): 
                                        while ($location_jobs->have_posts()): 
                                            $location_jobs->the_post();
                                            $job_id = get_the_ID();
                                            $posted_date = get_field('job_posted_date', $job_id) ?: get_the_date('d/m/Y', $job_id);
                                            $job_location = get_field('job_location', $job_id) ?: '';
                                            $job_description = get_field('job_description', $job_id) ?: get_the_excerpt($job_id);
                                            
                                            // Truncate title and description
                                            $title = get_the_title($job_id);
                                            $truncated_title = (strlen($title) > 60) ? substr($title, 0, 60) . '...' : $title;
                                            $truncated_desc = (strlen($job_description) > 120) ? substr($job_description, 0, 120) . '...' : $job_description;
                                            ?>
                                            <div class="blog-item blog-item50 job-listing-item">
                                                <div class="job-listing-header">
                                                    <span class="job-date"><?php echo esc_html($posted_date); ?></span>
                                                    <?php if ($job_location): ?>
                                                        <span class="job-location"><?php echo esc_html($job_location); ?></span>
                                                    <?php endif; ?>
                                                </div>
                                                <h3><a href="<?php echo esc_url(get_permalink($job_id)); ?>"><?php echo esc_html($truncated_title); ?></a></h3>
                                                <p class="job-excerpt"><?php echo esc_html($truncated_desc); ?></p>
                                                <div class="blog-btn job-apply-btn">
                                                    <a href="<?php echo esc_url(get_permalink($job_id)); ?>">View Details & Apply</a>
                                                </div>
                                            </div>
                                            <?php
                                        endwhile;
                                        wp_reset_postdata();
                                    else: ?>
                                        <div class="tab-cnt20">
                                            <?php echo esc_html(sprintf(__('No jobs found in %s.', 'chainthat'), $location)); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php 
                        endforeach;
                    endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA section -->
<section class="question-area jobs-cta-area" style="padding: 80px 0;">
    <div class="container">
        <div class="blog-btm">
            <h3 class="wow fadeInRight"><?php echo esc_html__('Can\'t find what you\'re looking for?', 'chainthat'); ?></h3>
            <p class="wow fadeInLeft" style="margin: 20px 0 30px; font-size: 18px; color: #00524C;">
                <?php echo esc_html__('Send us your CV and we\'ll keep you in mind for future opportunities', 'chainthat'); ?>
            </p>
            <ul class="wow fadeInUp">
                <li><div class="btn-all blog-btm-btn"><a href="<?php echo esc_url(home_url('/contact')); ?>"><?php echo esc_html__('Get in Touch', 'chainthat'); ?></a></div></li>
                <li><a href="<?php echo esc_url(home_url('/careers')); ?>"><?php echo esc_html__('View All Careers Info', 'chainthat'); ?> <span><img src="<?php echo get_template_directory_uri(); ?>/images/arrow3.png" alt=""></span></a></li>
            </ul>
        </div>
    </div>
</section>

<?php get_footer(); ?>

<script>
    // Function to activate a tab within a specific set
    function activateTab(setId, tabId) {
        const container = document.querySelector(`.tab-container[data-set="${setId}"]`);
        if (!container) return;

        container.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
        container.querySelectorAll('.tab-pane').forEach(pane => pane.classList.remove('active'));

        const targetButton = container.querySelector(`.tab-btn[data-tab="${tabId}"]`);
        const targetPane = document.getElementById(tabId);

        if (targetButton && targetPane) {
            targetButton.classList.add('active');
            targetPane.classList.add('active');
        }
    }

    // Handle click events for each tab set
    document.querySelectorAll('.tab-container').forEach(container => {
        const setId = container.getAttribute('data-set');
        container.querySelectorAll('.tab-btn').forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const tabId = button.getAttribute('data-tab');
                activateTab(setId, tabId);
                window.history.pushState(null, null, `#${tabId}`); // Update URL
            });
        });
    });

    // On page load, check URL hash and activate corresponding tab
    window.addEventListener('load', () => {
        const hash = window.location.hash.substring(1); // Remove #
        if (hash) {
            activateTab('jobs', hash);
        } else {
            activateTab('jobs', 'all-jobs');
        }
    });
</script>


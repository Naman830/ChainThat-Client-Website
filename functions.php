<?php
/**
 * ChainThat Theme Functions
 * 
 * @package ChainThat
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Theme setup
function chainthat_theme_setup() {
    // Add theme support
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));
    add_theme_support('custom-logo');
    add_theme_support('menus');
    
    // Add Gutenberg/Block Editor support
    add_theme_support('align-wide');
    add_theme_support('wp-block-styles');
    add_theme_support('editor-styles');
    add_theme_support('responsive-embeds');
    
    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'chainthat'),
        'mobile' => __('Mobile Menu', 'chainthat'),
        'footer' => __('Footer Menu', 'chainthat'),
    ));
    
    // Add image sizes
    add_image_size('hero-slide', 1920, 800, true);
    add_image_size('service-icon', 60, 60, true);
    add_image_size('team-member', 300, 300, true);
    add_image_size('team-about', 214, 106, true); // About page team members
    add_image_size('blog-thumbnail', 400, 250, true);
}
add_action('after_setup_theme', 'chainthat_theme_setup');

// Regenerate team thumbnails on demand (add ?regenerate_team_thumbs=1 to any admin page URL)
add_action('admin_init', 'chainthat_regenerate_team_thumbnails');
function chainthat_regenerate_team_thumbnails() {
    if (isset($_GET['regenerate_team_thumbs']) && $_GET['regenerate_team_thumbs'] == '1' && current_user_can('manage_options')) {
        $team_posts = get_posts(array(
            'post_type' => 'team',
            'posts_per_page' => -1,
            'post_status' => 'publish'
        ));
        
        $count = 0;
        foreach ($team_posts as $post) {
            $attachment_id = get_post_thumbnail_id($post->ID);
            if ($attachment_id) {
                // Delete old thumbnails
                $metadata = wp_get_attachment_metadata($attachment_id);
                if (isset($metadata['sizes']['team-about'])) {
                    $upload_dir = wp_upload_dir();
                    $file_path = $upload_dir['basedir'] . '/' . dirname($metadata['file']) . '/' . $metadata['sizes']['team-about']['file'];
                    if (file_exists($file_path)) {
                        @unlink($file_path);
                    }
                }
                
                // Regenerate thumbnails
                require_once(ABSPATH . 'wp-admin/includes/image.php');
                $fullsizepath = get_attached_file($attachment_id);
                $metadata = wp_generate_attachment_metadata($attachment_id, $fullsizepath);
                wp_update_attachment_metadata($attachment_id, $metadata);
                $count++;
            }
        }
        
        wp_redirect(admin_url('edit.php?post_type=team&thumbs_regenerated=' . $count));
        exit;
    }
    
    // Show admin notice
    if (isset($_GET['thumbs_regenerated'])) {
        add_action('admin_notices', function() {
            $count = intval($_GET['thumbs_regenerated']);
            echo '<div class="notice notice-success is-dismissible"><p><strong>Success!</strong> Regenerated thumbnails for ' . $count . ' team members.</p></div>';
        });
    }
}

// Add regenerate button to team list page
add_action('admin_notices', 'chainthat_add_regenerate_button');
function chainthat_add_regenerate_button() {
    $screen = get_current_screen();
    if ($screen && $screen->post_type === 'team' && $screen->base === 'edit') {
        $url = add_query_arg('regenerate_team_thumbs', '1');
        echo '<div class="notice notice-info"><p><strong>Team Images:</strong> After adding the new image size, <a href="' . esc_url($url) . '" class="button button-primary">Click here to regenerate team member thumbnails</a> for optimized loading.</p></div>';
    }
}

// Include block registration configuration
require_once get_template_directory() . '/blocks/block-config.php';

// Include block helper functions
require_once get_template_directory() . '/inc/block-helpers.php';

// Enqueue scripts and styles
function chainthat_scripts() {
    // Styles - Load in exact same order as original HTML
    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap', array(), '1.0.0');
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css', array(), '6.1.1');
    wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css', array(), '5.0.2');
    wp_enqueue_style('owl-carousel', get_template_directory_uri() . '/css/owl.carousel.css', array(), '1.0.0');
    wp_enqueue_style('animate', get_template_directory_uri() . '/css/animate.css', array(), '1.0.0');
    wp_enqueue_style('chainthat-style', get_stylesheet_uri(), array(), '1.0.0');
    wp_enqueue_style('responsive', get_template_directory_uri() . '/css/responsive.css', array(), '1.0.0');
    wp_enqueue_style('vacancies-responsive', get_template_directory_uri() . '/css/vacancies-responsive.css', array('responsive'), '1.0.0');
    
    // Add inline CSS to ensure proper loading
    // wp_add_inline_style('chainthat-style', '
    //     /* Debug: Ensure styles are loading */
    //     body { font-family: "Poppins", sans-serif; }
    //     .owl-carousel { display: block; }
    // ');
    
    // Scripts - Load in exact same order as original HTML
    wp_enqueue_script('jquery-3-7-1', get_template_directory_uri() . '/js/jquery-3.7.1.min.js', array(), '3.7.1', true);
    wp_enqueue_script('owl-carousel', get_template_directory_uri() . '/js/owl.carousel.js', array('jquery-3-7-1'), '1.0.0', true);
    wp_enqueue_script('wow', get_template_directory_uri() . '/js/wow.min.js', array('jquery-3-7-1'), '1.0.0', true);
    wp_enqueue_script('accordion', get_template_directory_uri() . '/js/accordion.js', array('jquery-3-7-1'), '1.0.0', true);
    wp_enqueue_script('script', get_template_directory_uri() . '/js/script.js', array('jquery-3-7-1'), '1.0.0', true);
    wp_enqueue_script('custom', get_template_directory_uri() . '/js/custom.js', array('jquery-3-7-1'), '1.0.0', true);
    
    // Localize script for AJAX
    wp_localize_script('script', 'chainthat_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('chainthat_nonce'),
    ));
}
add_action('wp_enqueue_scripts', 'chainthat_scripts');

// ACF Options Page
if (function_exists('acf_add_options_page')) {
    acf_add_options_page(array(
        'page_title' => 'Theme Options',
        'menu_title' => 'Theme Options',
        'menu_slug' => 'theme-options',
        'capability' => 'edit_posts',
    ));
    
    // News Archive Options Page
    acf_add_options_page(array(
        'page_title' => 'News Archive Settings',
        'menu_title' => 'Archive Settings',
        'menu_slug' => 'news-archive-settings',
        'capability' => 'edit_posts',
        'parent_slug' => 'edit.php?post_type=news-and-insight',
    ));
    
    // Job Settings Options Page
    acf_add_options_page(array(
        'page_title' => 'Job Settings',
        'menu_title' => 'Job Settings',
        'menu_slug' => 'job-settings',
        'capability' => 'edit_posts',
        'parent_slug' => 'edit.php?post_type=job',
    ));
}

// Custom post types
function chainthat_custom_post_types() {
    // Platform post type
    register_post_type('platform', array(
        'labels' => array(
            'name' => 'Platforms',
            'singular_name' => 'Platform',
            'add_new' => 'Add New',
            'add_new_item' => 'Add New Platform',
            'edit_item' => 'Edit Platform',
            'new_item' => 'New Platform',
            'view_item' => 'View Platform',
            'search_items' => 'Search Platforms',
            'not_found' => 'No platforms found',
            'not_found_in_trash' => 'No platforms found in trash',
        ),
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor', 'excerpt', 'thumbnail', 'page-attributes'),
        'menu_icon' => 'dashicons-admin-tools',
        'rewrite' => array('slug' => 'platform'),
        'show_in_rest' => true,
    ));
    
    // News & Insights
    register_post_type('news-and-insight', array(
        'labels' => array(
            'name' => 'News & Insights',
            'singular_name' => 'News Article',
            'add_new' => 'Add New',
            'add_new_item' => 'Add New News Article',
            'edit_item' => 'Edit News Article',
            'new_item' => 'New News Article',
            'view_item' => 'View News Article',
            'search_items' => 'Search News Articles',
            'not_found' => 'No news articles found',
            'not_found_in_trash' => 'No news articles found in trash',
        ),
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'menu_icon' => 'dashicons-megaphone',
        'rewrite' => array('slug' => 'news-and-insight'),
        'show_in_rest' => true,
    ));
    
    // Reviews
    register_post_type('review', array(
        'labels' => array(
            'name' => 'Reviews',
            'singular_name' => 'Review',
            'add_new' => 'Add New',
            'add_new_item' => 'Add New Review',
            'edit_item' => 'Edit Review',
            'new_item' => 'New Review',
            'view_item' => 'View Review',
            'search_items' => 'Search Reviews',
            'not_found' => 'No reviews found',
            'not_found_in_trash' => 'No reviews found in trash',
        ),
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor', 'thumbnail', 'page-attributes'),
        'menu_icon' => 'dashicons-star-filled',
        'rewrite' => array('slug' => 'review'),
        'show_in_rest' => true,
    ));

    // Employee Testimonials
    register_post_type('employee_testimonial', array(
        'labels' => array(
            'name' => 'Employee Testimonials',
            'singular_name' => 'Employee Testimonial',
            'add_new' => 'Add New',
            'add_new_item' => 'Add New Employee Testimonial',
            'edit_item' => 'Edit Employee Testimonial',
            'new_item' => 'New Employee Testimonial',
            'view_item' => 'View Employee Testimonial',
            'search_items' => 'Search Employee Testimonials',
            'not_found' => 'No employee testimonials found',
            'not_found_in_trash' => 'No employee testimonials found in trash',
        ),
        'public' => true,
        'has_archive' => false,
        'supports' => array('title', 'editor', 'thumbnail', 'page-attributes'),
        'menu_icon' => 'dashicons-testimonial',
        'rewrite' => array('slug' => 'employee-testimonial'),
        'show_in_rest' => true,
    ));

    // Team
    register_post_type('team', array(
        'labels' => array(
            'name' => 'Team',
            'singular_name' => 'Team Member',
            'add_new' => 'Add New',
            'add_new_item' => 'Add New Team Member',
            'edit_item' => 'Edit Team Member',
            'new_item' => 'New Team Member',
            'view_item' => 'View Team Member',
            'search_items' => 'Search Team Members',
            'not_found' => 'No team members found',
            'not_found_in_trash' => 'No team members found in trash',
        ),
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor', 'thumbnail', 'page-attributes'),
        'menu_icon' => 'dashicons-groups',
        'rewrite' => array('slug' => 'team'),
        'show_in_rest' => true,
    ));
    
    // Partners
    register_post_type('partners', array(
        'labels' => array(
            'name' => 'Partners',
            'singular_name' => 'Partner',
            'add_new' => 'Add New',
            'add_new_item' => 'Add New Partner',
            'edit_item' => 'Edit Partner',
            'new_item' => 'New Partner',
            'view_item' => 'View Partner',
            'search_items' => 'Search Partners',
            'not_found' => 'No partners found',
            'not_found_in_trash' => 'No partners found in trash',
        ),
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'thumbnail'),
        'menu_icon' => 'dashicons-networking',
        'taxonomies' => array('partner_category'),
        'show_in_rest' => true,
    ));
    
    // Solutions
    register_post_type('solution', array(
        'labels' => array(
            'name' => 'Solutions',
            'singular_name' => 'Solution',
            'add_new' => 'Add New',
            'add_new_item' => 'Add New Solution',
            'edit_item' => 'Edit Solution',
            'new_item' => 'New Solution',
            'view_item' => 'View Solution',
            'search_items' => 'Search Solutions',
            'not_found' => 'No solutions found',
            'not_found_in_trash' => 'No solutions found in trash',
        ),
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor', 'excerpt', 'thumbnail', 'page-attributes'),
        'menu_icon' => 'dashicons-lightbulb',
        'rewrite' => array('slug' => 'solution'),
        'show_in_rest' => true,
    ));
    
    // Jobs
    register_post_type('job', array(
        'labels' => array(
            'name' => 'Jobs',
            'singular_name' => 'Job',
            'add_new' => 'Add New',
            'add_new_item' => 'Add New Job',
            'edit_item' => 'Edit Job',
            'new_item' => 'New Job',
            'view_item' => 'View Job',
            'search_items' => 'Search Jobs',
            'not_found' => 'No jobs found',
            'not_found_in_trash' => 'No jobs found in trash',
        ),
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor', 'excerpt', 'thumbnail', 'page-attributes'),
        'menu_icon' => 'dashicons-portfolio',
        'rewrite' => array('slug' => 'job'),
        'show_in_rest' => true,
    ));
    
    // Case Studies
    register_post_type('case-study', array(
        'labels' => array(
            'name' => 'Case Studies',
            'singular_name' => 'Case Study',
            'add_new' => 'Add New',
            'add_new_item' => 'Add New Case Study',
            'edit_item' => 'Edit Case Study',
            'new_item' => 'New Case Study',
            'view_item' => 'View Case Study',
            'search_items' => 'Search Case Studies',
            'not_found' => 'No case studies found',
            'not_found_in_trash' => 'No case studies found in trash',
        ),
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor', 'excerpt', 'thumbnail', 'page-attributes'),
        'menu_icon' => 'dashicons-media-document',
        'rewrite' => array('slug' => 'case-study'),
        'show_in_rest' => true,
    ));
}
add_action('init', 'chainthat_custom_post_types');

// Add Jobs Import Admin Page
function chainthat_jobs_import_menu() {
    add_submenu_page(
        'edit.php?post_type=job',
        'Import Sample Jobs',
        'Import Sample Jobs',
        'manage_options',
        'import-sample-jobs',
        'chainthat_jobs_import_page'
    );
}
add_action('admin_menu', 'chainthat_jobs_import_menu');

// Jobs Import Admin Page Content
function chainthat_jobs_import_page() {
    // Check user permissions
    if (!current_user_can('manage_options')) {
        wp_die('You do not have sufficient permissions to access this page.');
    }
    
    // Handle form submission
    if (isset($_POST['import_sample_jobs']) && check_admin_referer('chainthat_import_jobs', 'chainthat_import_nonce')) {
        chainthat_import_sample_jobs();
    }
    
    // Check if jobs already exist
    $existing_jobs = get_posts(array(
        'post_type' => 'job',
        'posts_per_page' => -1,
        'post_status' => 'any'
    ));
    
    ?>
    <div class="wrap">
        <h1>Import Sample Jobs</h1>
        
        <?php if (count($existing_jobs) > 0): ?>
            <div class="notice notice-info">
                <p><strong>Note:</strong> You already have <?php echo count($existing_jobs); ?> job post(s) in your database.</p>
            </div>
        <?php endif; ?>
        
        <div class="card" style="max-width: 800px;">
            <h2>Sample Jobs Data</h2>
            <p>This will import 12 sample job posts with the following data:</p>
            
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>Job Title</th>
                        <th>Location</th>
                        <th>Posted Date</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td>Sr. DevOps Engineer (India)</td><td>India</td><td>24/04/2025</td></tr>
                    <tr><td>Support Engineer II (India)</td><td>India</td><td>10/04/2025</td></tr>
                    <tr><td>Solutioning Lead (INDIA)</td><td>India</td><td>08/02/2025</td></tr>
                    <tr><td>Engineering Manager (India)</td><td>India</td><td>12/11/2024</td></tr>
                    <tr><td>Delivery Manager (INDIA)</td><td>India</td><td>05/11/2024</td></tr>
                    <tr><td>Quality Analyst (INDIA)</td><td>India</td><td>05/11/2024</td></tr>
                    <tr><td>Senior Software Engineer (UK)</td><td>UK</td><td>15/10/2024</td></tr>
                    <tr><td>Business Analyst (India)</td><td>India</td><td>10/10/2024</td></tr>
                    <tr><td>Product Manager (UK)</td><td>UK</td><td>05/10/2024</td></tr>
                    <tr><td>UI/UX Designer (India)</td><td>India</td><td>28/09/2024</td></tr>
                    <tr><td>Data Engineer (India)</td><td>India</td><td>20/09/2024</td></tr>
                    <tr><td>Security Engineer (UK)</td><td>UK</td><td>15/09/2024</td></tr>
                </tbody>
            </table>
            
            <br>
            
            <form method="post" action="">
                <?php wp_nonce_field('chainthat_import_jobs', 'chainthat_import_nonce'); ?>
                <p>
                    <input type="submit" name="import_sample_jobs" class="button button-primary button-hero" value="Import Sample Jobs">
                </p>
                <p class="description">
                    <strong>Note:</strong> This will check for existing jobs with the same titles and skip duplicates.
                    Jobs already in your database will not be imported again.
                </p>
            </form>
        </div>
        
        <div class="card" style="max-width: 800px; margin-top: 20px;">
            <h2>After Importing</h2>
            <ol>
                <li>Go to <a href="<?php echo admin_url('edit.php?post_type=job'); ?>">Jobs</a> to view all imported jobs</li>
                <li>Visit your <a href="<?php echo home_url('/careers'); ?>" target="_blank">Careers page</a> to see jobs displayed</li>
                <li>Go to <a href="<?php echo admin_url('options-permalink.php'); ?>">Settings > Permalinks</a> and click "Save Changes" if job links show 404</li>
            </ol>
        </div>
    </div>
    <?php
}

// Import Sample Jobs Function
function chainthat_import_sample_jobs() {
    $sample_jobs = array(
        array(
            'title' => 'Sr. DevOps Engineer (India)',
            'date' => '24/04/2025',
            'location' => 'India',
            'description' => 'At ChainThat, we are looking for a Sr. DevOps Engineer to join our team in building innovative products and solutions that transform the insurance industry.',
            'content' => 'We are seeking a results-driven Sr. DevOps Engineer to join our team in building innovative products and solutions that transform the insurance industry. You will be responsible for maintaining and improving our CI/CD pipeline, managing cloud infrastructure, and ensuring high availability of our systems.'
        ),
        array(
            'title' => 'Support Engineer II (India)',
            'date' => '10/04/2025',
            'location' => 'India',
            'description' => 'At ChainThat, we are seeking a results-driven Support Engineer II to join our team in building innovative solutions.',
            'content' => 'Join our support team to provide technical assistance to customers, troubleshoot issues, and ensure smooth operation of our insurance technology platform.'
        ),
        array(
            'title' => 'Solutioning Lead (INDIA)',
            'date' => '08/02/2025',
            'location' => 'India',
            'description' => 'We are seeking a dynamic and experienced Solutioning Lead to drive our pre-sales efforts and help transform the insurance industry.',
            'content' => 'Lead the pre-sales technical team, design solutions for complex customer requirements, and drive successful implementation of our insurance technology platform.'
        ),
        array(
            'title' => 'Engineering Manager (India)',
            'date' => '12/11/2024',
            'location' => 'India',
            'description' => 'At ChainThat, we are seeking a results-driven Engineering Manager to lead and scale our Product Engineering team.',
            'content' => 'Lead a team of talented engineers, define technical strategy, and drive the development of innovative insurance technology solutions.'
        ),
        array(
            'title' => 'Delivery Manager (INDIA)',
            'date' => '05/11/2024',
            'location' => 'India',
            'description' => 'We, in ChainThat, are looking for a Delivery Manager to join the team in building innovative solutions.',
            'content' => 'Manage end-to-end project delivery, coordinate with stakeholders, and ensure successful implementation of insurance technology projects.'
        ),
        array(
            'title' => 'Quality Analyst (INDIA)',
            'date' => '05/11/2024',
            'location' => 'India',
            'description' => 'We, in ChainThat, are looking for a Quality Analyst to join our team in building innovative solutions.',
            'content' => 'Ensure the quality of our insurance technology platform through comprehensive testing, automation, and quality assurance processes.'
        ),
        array(
            'title' => 'Senior Software Engineer (UK)',
            'date' => '15/10/2024',
            'location' => 'UK',
            'description' => 'Join our engineering team to build cutting-edge insurance technology solutions with advanced technology.',
            'content' => 'Design and develop scalable software solutions for the insurance industry, working with cutting-edge technologies and best practices.'
        ),
        array(
            'title' => 'Business Analyst (India)',
            'date' => '10/10/2024',
            'location' => 'India',
            'description' => 'Looking for a Business Analyst with insurance domain knowledge to join our team and drive innovation.',
            'content' => 'Bridge the gap between business needs and technical solutions, analyze requirements, and drive successful project outcomes.'
        ),
        array(
            'title' => 'Product Manager (UK)',
            'date' => '05/10/2024',
            'location' => 'UK',
            'description' => 'Seeking an experienced Product Manager to drive product strategy and roadmap for our insurance technology platform.',
            'content' => 'Define product vision, prioritize features, and work with cross-functional teams to deliver exceptional insurance technology solutions.'
        ),
        array(
            'title' => 'UI/UX Designer (India)',
            'date' => '28/09/2024',
            'location' => 'India',
            'description' => 'We are looking for a creative UI/UX Designer to join our design team and create exceptional user experiences.',
            'content' => 'Design intuitive and beautiful user interfaces for our insurance technology platform, focusing on user experience and best practices.'
        ),
        array(
            'title' => 'Data Engineer (India)',
            'date' => '20/09/2024',
            'location' => 'India',
            'description' => 'Join our data team to build scalable data pipelines and analytics solutions for the insurance industry.',
            'content' => 'Build and maintain data infrastructure, create ETL pipelines, and enable data-driven decision making across the organization.'
        ),
        array(
            'title' => 'Security Engineer (UK)',
            'date' => '15/09/2024',
            'location' => 'UK',
            'description' => 'Looking for a Security Engineer to enhance our platform security and compliance in the insurance sector.',
            'content' => 'Implement security best practices, conduct security audits, and ensure compliance with industry standards and regulations.'
        ),
    );
    
    $created_count = 0;
    $skipped_count = 0;
    $errors = array();
    
    foreach ($sample_jobs as $job_data) {
        // Check if job already exists
        $existing = get_posts(array(
            'post_type' => 'job',
            'title' => $job_data['title'],
            'post_status' => 'any',
            'posts_per_page' => 1
        ));
        
        if (!empty($existing)) {
            $skipped_count++;
            continue;
        }
        
        // Create job post
        $post_id = wp_insert_post(array(
            'post_title' => $job_data['title'],
            'post_content' => $job_data['content'],
            'post_excerpt' => $job_data['description'],
            'post_status' => 'publish',
            'post_type' => 'job',
            'post_author' => get_current_user_id()
        ));
        
        if ($post_id && !is_wp_error($post_id)) {
            // Update ACF fields
            if (function_exists('update_field')) {
                update_field('job_posted_date', $job_data['date'], $post_id);
                update_field('job_location', $job_data['location'], $post_id);
                update_field('job_description', $job_data['description'], $post_id);
            }
            $created_count++;
        } else {
            $error_message = is_wp_error($post_id) ? $post_id->get_error_message() : 'Unknown error';
            $errors[] = 'Failed to create: ' . $job_data['title'] . ' - ' . $error_message;
        }
    }
    
    // Display success message
    if ($created_count > 0) {
        echo '<div class="notice notice-success is-dismissible"><p><strong>Success!</strong> Created ' . $created_count . ' job post(s).</p></div>';
    }
    
    if ($skipped_count > 0) {
        echo '<div class="notice notice-info is-dismissible"><p><strong>Info:</strong> Skipped ' . $skipped_count . ' job(s) that already exist.</p></div>';
    }
    
    if (!empty($errors)) {
        echo '<div class="notice notice-error is-dismissible"><p><strong>Errors:</strong><br>' . implode('<br>', $errors) . '</p></div>';
    }
    
    if ($created_count === 0 && $skipped_count === 0 && empty($errors)) {
        echo '<div class="notice notice-warning is-dismissible"><p><strong>Warning:</strong> No jobs were created or skipped.</p></div>';
    }
}

// Custom taxonomies
function chainthat_custom_taxonomies() {
    // News Categories
    register_taxonomy('news_category', 'news-and-insight', array(
        'labels' => array(
            'name' => 'News Categories',
            'singular_name' => 'News Category',
            'search_items' => 'Search Categories',
            'all_items' => 'All Categories',
            'parent_item' => 'Parent Category',
            'parent_item_colon' => 'Parent Category:',
            'edit_item' => 'Edit Category',
            'update_item' => 'Update Category',
            'add_new_item' => 'Add New Category',
            'new_item_name' => 'New Category Name',
            'menu_name' => 'Categories',
        ),
        'hierarchical' => true,
        'public' => true,
        'rewrite' => array('slug' => 'news-category'),
    ));
    
    // Partner Categories
    register_taxonomy('partner_category', 'partners', array(
        'labels' => array(
            'name' => 'Partner Categories',
            'singular_name' => 'Partner Category',
            'search_items' => 'Search Partner Categories',
            'all_items' => 'All Partner Categories',
            'parent_item' => 'Parent Partner Category',
            'parent_item_colon' => 'Parent Partner Category:',
            'edit_item' => 'Edit Partner Category',
            'update_item' => 'Update Partner Category',
            'add_new_item' => 'Add New Partner Category',
            'new_item_name' => 'New Partner Category Name',
            'menu_name' => 'Partner Categories',
        ),
        'hierarchical' => true,
        'public' => true,
        'rewrite' => array('slug' => 'partner-category'),
        'show_in_rest' => true,
    ));

    // Team Categories
    register_taxonomy('team_category', 'team', array(
        'labels' => array(
            'name' => 'Team Categories',
            'singular_name' => 'Team Category',
            'search_items' => 'Search Team Categories',
            'all_items' => 'All Team Categories',
            'parent_item' => 'Parent Team Category',
            'parent_item_colon' => 'Parent Team Category:',
            'edit_item' => 'Edit Team Category',
            'update_item' => 'Update Team Category',
            'add_new_item' => 'Add New Team Category',
            'new_item_name' => 'New Team Category Name',
            'menu_name' => 'Team Categories',
        ),
        'hierarchical' => true,
        'public' => true,
        'rewrite' => array('slug' => 'team-category'),
        'show_in_rest' => true,
    ));
}
add_action('init', 'chainthat_custom_taxonomies');

// Helper functions for partner categories
function get_partner_categories($partner_id = null) {
    if (!$partner_id) {
        $partner_id = get_the_ID();
    }
    return get_the_terms($partner_id, 'partner_category');
}

function get_partner_category_name($partner_id = null) {
    $categories = get_partner_categories($partner_id);
    if ($categories && !is_wp_error($categories)) {
        return $categories[0]->name;
    }
    return '';
}

function get_partners_by_category($category_slug) {
    $args = array(
        'post_type' => 'partners',
        'posts_per_page' => -1,
        'tax_query' => array(
            array(
                'taxonomy' => 'partner_category',
                'field' => 'slug',
                'terms' => $category_slug,
            ),
        ),
    );
    return get_posts($args);
}

// ACF JSON Save Point
function chainthat_acf_json_save_point($path) {
    $path = get_template_directory() . '/acf-json';
    return $path;
}
add_filter('acf/settings/save_json', 'chainthat_acf_json_save_point');

// ACF JSON Load Point
function chainthat_acf_json_load_point($paths) {
    unset($paths[0]);
    $paths[] = get_template_directory() . '/acf-json';
    return $paths;
}
add_filter('acf/settings/load_json', 'chainthat_acf_json_load_point');

// Helper function to get ACF field with fallback
function chainthat_get_field($field_name, $post_id = null, $default = '') {
    $value = get_field($field_name, $post_id);
    return $value ? $value : $default;
}

// Helper function to get ACF option field
function chainthat_get_option($field_name, $default = '') {
    $value = get_field($field_name, 'option');
    return $value ? $value : $default;
}

// Add custom body classes
function chainthat_body_classes($classes) {
    if (is_front_page()) {
        $classes[] = 'home-page';
    }
    return $classes;
}
add_filter('body_class', 'chainthat_body_classes');

// Custom excerpt length
function chainthat_excerpt_length($length) {
    return 20;
}
add_filter('excerpt_length', 'chainthat_excerpt_length');

// Custom excerpt more
function chainthat_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'chainthat_excerpt_more');

// Security enhancements
function chainthat_security_headers() {
    if (!is_admin()) {
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: SAMEORIGIN');
        header('X-XSS-Protection: 1; mode=block');
    }
}
add_action('send_headers', 'chainthat_security_headers');

// Remove WordPress version from head
remove_action('wp_head', 'wp_generator');

// Disable XML-RPC
add_filter('xmlrpc_enabled', '__return_false');

// Remove unnecessary WordPress features
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'wp_shortlink_wp_head');

// AJAX handlers for form submissions
function chainthat_handle_contact_form() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'chainthat_nonce')) {
        wp_die('Security check failed');
    }
    
    // Sanitize form data
    $name = sanitize_text_field($_POST['name']);
    $email = sanitize_email($_POST['email']);
    $phone = sanitize_text_field($_POST['phone']);
    $company = sanitize_text_field($_POST['company']);
    $message = sanitize_textarea_field($_POST['message']);
    
    // Send email
    $to = get_option('admin_email');
    $subject = 'New Contact Form Submission from ' . get_bloginfo('name');
    $body = "Name: $name\nEmail: $email\nPhone: $phone\nCompany: $company\nMessage: $message";
    $headers = array('Content-Type: text/plain; charset=UTF-8');
    
    $sent = wp_mail($to, $subject, $body, $headers);
    
    if ($sent) {
        wp_send_json_success('Message sent successfully!');
    } else {
        wp_send_json_error('Failed to send message. Please try again.');
    }
}
add_action('wp_ajax_chainthat_contact_form', 'chainthat_handle_contact_form');
add_action('wp_ajax_nopriv_chainthat_contact_form', 'chainthat_handle_contact_form');

// Handler for job application form submission (non-CF7 fallback)
function chainthat_handle_job_application() {
    // Verify nonce
    if (!isset($_POST['job_application_nonce']) || !wp_verify_nonce($_POST['job_application_nonce'], 'job_application_submit')) {
        wp_die('Security check failed', 'Security Error', array('response' => 403));
    }
    
    // Get form data
    $job_id = intval($_POST['job_id']);
    $job_title = sanitize_text_field($_POST['job_title']);
    
    // Handle file upload
    if (!empty($_FILES['cv_file']['name'])) {
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');
        
        // Validate file
        $allowed_types = array('application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        $file_type = wp_check_filetype($_FILES['cv_file']['name']);
        
        if (!in_array($_FILES['cv_file']['type'], $allowed_types)) {
            wp_die('Invalid file type. Only PDF, DOC, and DOCX files are allowed.', 'File Error', array('response' => 400));
        }
        
        // Check file size (5MB max)
        if ($_FILES['cv_file']['size'] > 5 * 1024 * 1024) {
            wp_die('File size exceeds 5MB limit.', 'File Error', array('response' => 400));
        }
        
        // Generate custom filename: name_profile_role_date_randomnumber.ext
        $file_ext = pathinfo($_FILES['cv_file']['name'], PATHINFO_EXTENSION);
        $applicant_name = isset($_POST['applicant_name']) ? sanitize_file_name($_POST['applicant_name']) : 'applicant';
        $job_slug = sanitize_title($job_title);
        $date = date('Ymd');
        $random = wp_rand(1000, 9999);
        
        // Custom filename format: name_profile_role_date_randomnumber
        $new_filename = sprintf(
            '%s_profile_%s_%s_%s.%s',
            $applicant_name,
            $job_slug,
            $date,
            $random,
            $file_ext
        );
        
        // Override upload filename and directory
        add_filter('wp_handle_upload_prefilter', function($file) use ($new_filename) {
            $file['name'] = $new_filename;
            return $file;
        });
        
        // Change upload directory to /uploads/resumes/
        add_filter('upload_dir', 'chainthat_custom_resume_upload_dir');
        
        // Upload file
        $upload = wp_handle_upload($_FILES['cv_file'], array('test_form' => false));
        
        // Remove the filter after upload
        remove_filter('upload_dir', 'chainthat_custom_resume_upload_dir');
        
        if (isset($upload['error'])) {
            wp_die('File upload failed: ' . $upload['error'], 'Upload Error', array('response' => 500));
        }
        
        $file_url = $upload['url'];
        $file_path = $upload['file'];
        
        // Save resume metadata to database
        global $wpdb;
        $table_name = $wpdb->prefix . 'job_resumes';
        
        $wpdb->insert(
            $table_name,
            array(
                'applicant_name' => sanitize_text_field($_POST['applicant_name']),
                'job_id' => $job_id,
                'job_title' => $job_title,
                'file_name' => basename($file_path),
                'file_path' => $file_path,
                'file_url' => $file_url,
                'file_size' => filesize($file_path),
                'file_type' => $file_ext,
                'submitted_date' => current_time('mysql'),
                'submitted_ip' => $_SERVER['REMOTE_ADDR']
            ),
            array('%s', '%d', '%s', '%s', '%s', '%s', '%d', '%s', '%s', '%s')
        );
        
        // Send notification email
        $admin_email = get_option('admin_email');
        $subject = sprintf('New Job Application: %s', $job_title);
        $message = sprintf(
            "A new job application has been submitted:\n\n" .
            "Job Title: %s\n" .
            "Job ID: %s\n" .
            "Resume: %s\n" .
            "Submitted: %s\n\n" .
            "View Job: %s",
            $job_title,
            $job_id,
            $file_url,
            date('F j, Y g:i a'),
            get_permalink($job_id)
        );
        
        $headers = array('Content-Type: text/plain; charset=UTF-8');
        wp_mail($admin_email, $subject, $message, $headers);
        
        // Redirect with success message
        wp_redirect(add_query_arg('application', 'success', get_permalink($job_id)));
        exit;
    } else {
        wp_die('No file uploaded.', 'Upload Error', array('response' => 400));
    }
}
add_action('admin_post_submit_job_application', 'chainthat_handle_job_application');
add_action('admin_post_nopriv_submit_job_application', 'chainthat_handle_job_application');

// Handler for job contact form submission (non-CF7 fallback)
function chainthat_handle_job_contact() {
    // Verify nonce
    if (!isset($_POST['job_contact_nonce']) || !wp_verify_nonce($_POST['job_contact_nonce'], 'job_contact_submit')) {
        wp_die('Security check failed', 'Security Error', array('response' => 403));
    }
    
    // Sanitize form data
    $first_name = sanitize_text_field($_POST['first_name']);
    $email = sanitize_email($_POST['email']);
    $phone = sanitize_text_field($_POST['phone']);
    $company = sanitize_text_field($_POST['company']);
    $message = sanitize_textarea_field($_POST['message']);
    
    // Send email
    $admin_email = get_option('admin_email');
    $subject = 'Job Inquiry: ' . $first_name;
    $email_message = sprintf(
        "A new job inquiry has been submitted:\n\n" .
        "Name: %s\n" .
        "Email: %s\n" .
        "Phone: %s\n" .
        "Company: %s\n" .
        "Message:\n%s\n\n" .
        "Submitted: %s",
        $first_name,
        $email,
        $phone,
        $company,
        $message,
        date('F j, Y g:i a')
    );
    
    $headers = array('Content-Type: text/plain; charset=UTF-8');
    
    $sent = wp_mail($admin_email, $subject, $email_message, $headers);
    
    if ($sent) {
        // Redirect with success message
        wp_redirect(add_query_arg('contact', 'success', wp_get_referer()));
        exit;
    } else {
        wp_die('Failed to send message. Please try again.', 'Email Error', array('response' => 500));
    }
}
add_action('admin_post_job_contact_form', 'chainthat_handle_job_contact');
add_action('admin_post_nopriv_job_contact_form', 'chainthat_handle_job_contact');

// Custom filename for CF7 file uploads (resume applications)
function chainthat_cf7_custom_file_name($file, $field) {
    // Only process resume/CV uploads
    if (strpos($field, 'cv') !== false || strpos($field, 'resume') !== false) {
        $file_ext = pathinfo($file, PATHINFO_EXTENSION);
        
        // Try to get applicant info from form submission
        $submission = WPCF7_Submission::get_instance();
        if ($submission) {
            $posted_data = $submission->get_posted_data();
            
            // Get applicant name (try multiple field variations)
            $applicant_name = '';
            if (isset($posted_data['your-name'])) {
                $applicant_name = sanitize_file_name($posted_data['your-name']);
            } elseif (isset($posted_data['name'])) {
                $applicant_name = sanitize_file_name($posted_data['name']);
            } elseif (isset($posted_data['first-name'])) {
                $applicant_name = sanitize_file_name($posted_data['first-name']);
            }
            
            // Get job info from current post
            $job_title = get_the_title();
            $job_slug = sanitize_title($job_title);
            
            // Generate custom filename
            $date = date('Ymd');
            $random = wp_rand(1000, 9999);
            
            if (empty($applicant_name)) {
                $applicant_name = 'applicant';
            }
            
            // Custom filename format: name_profile_role_date_randomnumber
            $new_filename = sprintf(
                '%s_profile_%s_%s_%s.%s',
                $applicant_name,
                $job_slug,
                $date,
                $random,
                $file_ext
            );
            
            return $new_filename;
        }
    }
    
    return $file;
}
add_filter('wpcf7_upload_file_name', 'chainthat_cf7_custom_file_name', 10, 2);

// Custom upload directory for resumes
function chainthat_custom_resume_upload_dir($dirs) {
    $custom_dir = '/resumes';
    $dirs['path'] = $dirs['basedir'] . $custom_dir;
    $dirs['url'] = $dirs['baseurl'] . $custom_dir;
    $dirs['subdir'] = $custom_dir;
    
    // Create directory if it doesn't exist
    if (!file_exists($dirs['path'])) {
        wp_mkdir_p($dirs['path']);
        file_put_contents($dirs['path'] . '/.htaccess', 'Options -Indexes');
    }
    
    return $dirs;
}

// Apply custom upload directory for CF7 file uploads
add_filter('wpcf7_upload_dir', 'chainthat_cf7_upload_to_resumes');
function chainthat_cf7_upload_to_resumes($dir) {
    // Only apply to resume uploads (cv_file field)
    if (isset($_POST['cv_file'])) {
        $upload_dir = wp_upload_dir();
        $custom_dir = '/resumes';
        
        $dir['path'] = $upload_dir['basedir'] . $custom_dir;
        $dir['url'] = $upload_dir['baseurl'] . $custom_dir;
        $dir['subdir'] = $custom_dir;
        
        // Create directory if it doesn't exist
        if (!file_exists($dir['path'])) {
            wp_mkdir_p($dir['path']);
            file_put_contents($dir['path'] . '/.htaccess', 'Options -Indexes');
        }
    }
    
    return $dir;
}

// Create custom database table for resume tracking
function chainthat_create_resumes_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'job_resumes';
    $charset_collate = $wpdb->get_charset_collate();
    
    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id bigint(20) NOT NULL AUTO_INCREMENT,
        applicant_name varchar(255) NOT NULL,
        job_id bigint(20) NOT NULL,
        job_title varchar(255) NOT NULL,
        file_name varchar(255) NOT NULL,
        file_path varchar(500) NOT NULL,
        file_url varchar(500) NOT NULL,
        file_size bigint(20) NOT NULL,
        file_type varchar(10) NOT NULL,
        submitted_date datetime NOT NULL,
        submitted_ip varchar(100) NOT NULL,
        PRIMARY KEY  (id),
        KEY job_id (job_id),
        KEY submitted_date (submitted_date)
    ) $charset_collate;";
    
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
    
    // Create resumes directory if it doesn't exist
    $upload_dir = wp_upload_dir();
    $resumes_dir = $upload_dir['basedir'] . '/resumes';
    if (!file_exists($resumes_dir)) {
        wp_mkdir_p($resumes_dir);
        // Add .htaccess for security
        file_put_contents($resumes_dir . '/.htaccess', 'Options -Indexes');
    }
}
register_activation_hook(__FILE__, 'chainthat_create_resumes_table');
// Also run on theme activation
add_action('after_switch_theme', 'chainthat_create_resumes_table');

// Hook CF7 submission to save resume data to database
add_action('wpcf7_before_send_mail', 'chainthat_save_cf7_resume_to_database');
function chainthat_save_cf7_resume_to_database($contact_form) {
    $submission = WPCF7_Submission::get_instance();
    
    if (!$submission) {
        return;
    }
    
    $posted_data = $submission->get_posted_data();
    $uploaded_files = $submission->uploaded_files();
    
    // Check if this is a job application form (has cv_file field)
    if (!isset($uploaded_files['cv_file']) || empty($uploaded_files['cv_file'])) {
        return;
    }
    
    // Get applicant name
    $applicant_name = isset($posted_data['your-name']) ? sanitize_text_field($posted_data['your-name']) : 'Unknown';
    
    // Get job info from current post
    global $post;
    $job_id = $post ? $post->ID : 0;
    $job_title = $post ? $post->post_title : 'Unknown Position';
    
    // Get uploaded file info
    $file_path = $uploaded_files['cv_file'];
    $file_name = basename($file_path);
    $file_size = filesize($file_path);
    $file_type = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    
    // Get upload directory info
    $upload_dir = wp_upload_dir();
    $file_url = str_replace($upload_dir['basedir'], $upload_dir['baseurl'], $file_path);
    
    // Get user IP
    $user_ip = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
    
    // Insert into database
    global $wpdb;
    $table_name = $wpdb->prefix . 'job_resumes';
    
    $wpdb->insert(
        $table_name,
        array(
            'applicant_name' => $applicant_name,
            'job_id' => $job_id,
            'job_title' => $job_title,
            'file_name' => $file_name,
            'file_path' => $file_path,
            'file_url' => $file_url,
            'file_size' => $file_size,
            'file_type' => $file_type,
            'submitted_date' => current_time('mysql'),
            'submitted_ip' => $user_ip
        ),
        array('%s', '%d', '%s', '%s', '%s', '%s', '%d', '%s', '%s', '%s')
    );
}

// Admin menu for viewing resumes
function chainthat_resumes_admin_menu() {
    add_submenu_page(
        'edit.php?post_type=job',
        'Submitted Resumes',
        'Resumes',
        'manage_options',
        'job-resumes',
        'chainthat_resumes_admin_page'
    );
}
add_action('admin_menu', 'chainthat_resumes_admin_menu');

// Admin page for viewing/managing resumes
function chainthat_resumes_admin_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'job_resumes';
    
    // Handle bulk download
    if (isset($_POST['bulk_download']) && isset($_POST['resume_ids']) && check_admin_referer('bulk_download_resumes')) {
        chainthat_bulk_download_resumes($_POST['resume_ids']);
        exit;
    }
    
    // Handle single download
    if (isset($_GET['download']) && check_admin_referer('download_resume_' . $_GET['download'], 'nonce')) {
        chainthat_download_single_resume($_GET['download']);
        exit;
    }
    
    // Handle delete
    if (isset($_GET['delete']) && check_admin_referer('delete_resume_' . $_GET['delete'], 'nonce')) {
        $resume_id = intval($_GET['delete']);
        $resume = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $resume_id));
        if ($resume && file_exists($resume->file_path)) {
            unlink($resume->file_path);
        }
        $wpdb->delete($table_name, array('id' => $resume_id), array('%d'));
        echo '<div class="notice notice-success"><p>Resume deleted successfully.</p></div>';
    }
    
    // Get sorting parameters
    $orderby = isset($_GET['orderby']) ? sanitize_text_field($_GET['orderby']) : 'submitted_date';
    $order = isset($_GET['order']) ? sanitize_text_field($_GET['order']) : 'DESC';
    $search = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';
    
    // Pagination
    $per_page = 20;
    $current_page = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
    $offset = ($current_page - 1) * $per_page;
    
    // Build query
    $where = '';
    if ($search) {
        $where = $wpdb->prepare(" WHERE applicant_name LIKE %s OR job_title LIKE %s", '%' . $wpdb->esc_like($search) . '%', '%' . $wpdb->esc_like($search) . '%');
    }
    
    $total_resumes = $wpdb->get_var("SELECT COUNT(*) FROM $table_name" . $where);
    $total_pages = ceil($total_resumes / $per_page);
    
    $resumes = $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM $table_name" . $where . " ORDER BY $orderby $order LIMIT %d OFFSET %d",
        $per_page,
        $offset
    ));
    
    ?>
    <div class="wrap">
        <h1 class="wp-heading-inline">Submitted Resumes</h1>
        <hr class="wp-header-end">
        
        <form method="get" style="margin: 20px 0;">
            <input type="hidden" name="post_type" value="job">
            <input type="hidden" name="page" value="job-resumes">
            <p class="search-box">
                <input type="search" name="s" value="<?php echo esc_attr($search); ?>" placeholder="Search by name or job...">
                <input type="submit" class="button" value="Search Resumes">
            </p>
        </form>
        
        <form method="post" id="resumes-filter">
            <?php wp_nonce_field('bulk_download_resumes'); ?>
            
            <div class="tablenav top">
                <div class="alignleft actions bulkactions">
                    <button type="submit" name="bulk_download" class="button action">Download Selected as ZIP</button>
                </div>
                <div class="tablenav-pages">
                    <span class="displaying-num"><?php echo number_format_i18n($total_resumes); ?> items</span>
                    <?php
                    if ($total_pages > 1) {
                        $page_links = paginate_links(array(
                            'base' => add_query_arg('paged', '%#%'),
                            'format' => '',
                            'prev_text' => '&laquo;',
                            'next_text' => '&raquo;',
                            'total' => $total_pages,
                            'current' => $current_page
                        ));
                        if ($page_links) {
                            echo '<span class="pagination-links">' . $page_links . '</span>';
                        }
                    }
                    ?>
                </div>
            </div>
            
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <td class="manage-column column-cb check-column">
                            <input type="checkbox" id="cb-select-all">
                        </td>
                        <th class="manage-column column-primary <?php echo $orderby === 'applicant_name' ? 'sorted ' . strtolower($order) : 'sortable'; ?>">
                            <a href="<?php echo esc_url(add_query_arg(array('orderby' => 'applicant_name', 'order' => ($orderby === 'applicant_name' && $order === 'ASC') ? 'DESC' : 'ASC'))); ?>">
                                <span>Applicant Name</span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>
                        <th class="manage-column <?php echo $orderby === 'job_title' ? 'sorted ' . strtolower($order) : 'sortable'; ?>">
                            <a href="<?php echo esc_url(add_query_arg(array('orderby' => 'job_title', 'order' => ($orderby === 'job_title' && $order === 'ASC') ? 'DESC' : 'ASC'))); ?>">
                                <span>Job Applied</span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>
                        <th class="manage-column">File Name</th>
                        <th class="manage-column <?php echo $orderby === 'file_size' ? 'sorted ' . strtolower($order) : 'sortable'; ?>">
                            <a href="<?php echo esc_url(add_query_arg(array('orderby' => 'file_size', 'order' => ($orderby === 'file_size' && $order === 'ASC') ? 'DESC' : 'ASC'))); ?>">
                                <span>File Size</span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>
                        <th class="manage-column <?php echo $orderby === 'submitted_date' ? 'sorted ' . strtolower($order) : 'sortable'; ?>">
                            <a href="<?php echo esc_url(add_query_arg(array('orderby' => 'submitted_date', 'order' => ($orderby === 'submitted_date' && $order === 'ASC') ? 'DESC' : 'ASC'))); ?>">
                                <span>Submitted Date</span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>
                        <th class="manage-column">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($resumes): ?>
                        <?php foreach ($resumes as $resume): ?>
                            <tr>
                                <th scope="row" class="check-column">
                                    <input type="checkbox" name="resume_ids[]" value="<?php echo esc_attr($resume->id); ?>">
                                </th>
                                <td class="column-primary" data-colname="Applicant Name">
                                    <strong><?php echo esc_html($resume->applicant_name); ?></strong>
                                    <div class="row-actions">
                                        <span class="view">
                                            <a href="<?php echo esc_url(wp_nonce_url(add_query_arg('download', $resume->id), 'download_resume_' . $resume->id, 'nonce')); ?>">Download</a> | 
                                        </span>
                                        <span class="trash">
                                            <a href="<?php echo esc_url(wp_nonce_url(add_query_arg('delete', $resume->id), 'delete_resume_' . $resume->id, 'nonce')); ?>" onclick="return confirm('Are you sure you want to delete this resume?');" style="color: #a00;">Delete</a>
                                        </span>
                                    </div>
                                </td>
                                <td data-colname="Job Applied">
                                    <?php 
                                    $job_link = get_permalink($resume->job_id);
                                    if ($job_link) {
                                        echo '<a href="' . esc_url($job_link) . '" target="_blank">' . esc_html($resume->job_title) . '</a>';
                                    } else {
                                        echo esc_html($resume->job_title);
                                    }
                                    ?>
                                </td>
                                <td data-colname="File Name">
                                    <code style="font-size: 11px;"><?php echo esc_html($resume->file_name); ?></code>
                                </td>
                                <td data-colname="File Size">
                                    <?php echo size_format($resume->file_size, 2); ?>
                                </td>
                                <td data-colname="Submitted Date">
                                    <?php echo date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($resume->submitted_date)); ?>
                                </td>
                                <td data-colname="Actions">
                                    <a href="<?php echo esc_url(wp_nonce_url(add_query_arg('download', $resume->id), 'download_resume_' . $resume->id, 'nonce')); ?>" class="button button-small">Download</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 40px 0;">
                                <p style="color: #999; font-size: 14px;">No resumes found.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            
            <div class="tablenav bottom">
                <div class="alignleft actions bulkactions">
                    <button type="submit" name="bulk_download" class="button action">Download Selected as ZIP</button>
                </div>
            </div>
        </form>
        
        <style>
            .wp-list-table th.sortable a, .wp-list-table th.sorted a {
                display: inline-block;
                width: 100%;
            }
            .wp-list-table th.sorted.asc .sorting-indicator:before {
                content: '↑';
                margin-left: 5px;
            }
            .wp-list-table th.sorted.desc .sorting-indicator:before {
                content: '↓';
                margin-left: 5px;
            }
            #cb-select-all {
                margin: 0;
            }
        </style>
        
        <script>
        jQuery(document).ready(function($) {
            // Select all checkboxes
            $('#cb-select-all').on('change', function() {
                $('input[name="resume_ids[]"]').prop('checked', this.checked);
            });
            
            // Update select all when individual checkboxes change
            $('input[name="resume_ids[]"]').on('change', function() {
                var total = $('input[name="resume_ids[]"]').length;
                var checked = $('input[name="resume_ids[]"]:checked').length;
                $('#cb-select-all').prop('checked', total === checked);
            });
        });
        </script>
    </div>
    <?php
}

// Download single resume
function chainthat_download_single_resume($resume_id) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'job_resumes';
    
    $resume = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $resume_id));
    
    if (!$resume || !file_exists($resume->file_path)) {
        wp_die('Resume not found.');
    }
    
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($resume->file_path) . '"');
    header('Content-Length: ' . filesize($resume->file_path));
    readfile($resume->file_path);
    exit;
}

// Bulk download resumes as ZIP
function chainthat_bulk_download_resumes($resume_ids) {
    if (empty($resume_ids)) {
        wp_die('No resumes selected.');
    }
    
    global $wpdb;
    $table_name = $wpdb->prefix . 'job_resumes';
    
    // Get selected resumes
    $ids_placeholder = implode(',', array_fill(0, count($resume_ids), '%d'));
    $resumes = $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM $table_name WHERE id IN ($ids_placeholder)",
        $resume_ids
    ));
    
    if (empty($resumes)) {
        wp_die('No resumes found.');
    }
    
    // Create ZIP file
    $zip = new ZipArchive();
    $zip_filename = 'resumes_' . date('Y-m-d_H-i-s') . '.zip';
    $zip_path = sys_get_temp_dir() . '/' . $zip_filename;
    
    if ($zip->open($zip_path, ZipArchive::CREATE) !== TRUE) {
        wp_die('Could not create ZIP file.');
    }
    
    foreach ($resumes as $resume) {
        if (file_exists($resume->file_path)) {
            $zip->addFile($resume->file_path, $resume->file_name);
        }
    }
    
    $zip->close();
    
    // Send ZIP file to browser
    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="' . $zip_filename . '"');
    header('Content-Length: ' . filesize($zip_path));
    readfile($zip_path);
    
    // Delete temporary ZIP file
    unlink($zip_path);
    exit;
}

// AJAX handler for newsletter subscription
function chainthat_handle_newsletter_subscription() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'chainthat_nonce')) {
        wp_die('Security check failed');
    }
    
    $email = sanitize_email($_POST['email']);
    
    // Here you would typically integrate with your email marketing service
    // For now, we'll just send a confirmation email
    $to = $email;
    $subject = 'Thank you for subscribing to our newsletter';
    $body = 'Thank you for subscribing to our newsletter. You will receive updates about our latest news and insights.';
    $headers = array('Content-Type: text/plain; charset=UTF-8');
    
    $sent = wp_mail($to, $subject, $body, $headers);
    
    if ($sent) {
        wp_send_json_success('Successfully subscribed to newsletter!');
    } else {
        wp_send_json_error('Failed to subscribe. Please try again.');
    }
}
add_action('wp_ajax_chainthat_newsletter_subscription', 'chainthat_handle_newsletter_subscription');
add_action('wp_ajax_nopriv_chainthat_newsletter_subscription', 'chainthat_handle_newsletter_subscription');

// AJAX handler for career application
function chainthat_handle_career_application() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'chainthat_nonce')) {
        wp_die('Security check failed');
    }
    
    // Sanitize form data
    $name = sanitize_text_field($_POST['name']);
    $email = sanitize_email($_POST['email']);
    $phone = sanitize_text_field($_POST['phone']);
    $position = sanitize_text_field($_POST['position']);
    $experience = sanitize_text_field($_POST['experience']);
    $cover_letter = sanitize_textarea_field($_POST['cover_letter']);
    
    // Handle file upload
    if (!empty($_FILES['cv_upload']['name'])) {
        $upload_dir = wp_upload_dir();
        $cv_file = $_FILES['cv_upload'];
        $cv_filename = sanitize_file_name($cv_file['name']);
        $cv_path = $upload_dir['path'] . '/' . $cv_filename;
        
        if (move_uploaded_file($cv_file['tmp_name'], $cv_path)) {
            $cv_url = $upload_dir['url'] . '/' . $cv_filename;
        }
    }
    
    // Send email with attachment
    $to = get_option('admin_email');
    $subject = 'New Career Application: ' . $position;
    $body = "Name: $name\nEmail: $email\nPhone: $phone\nPosition: $position\nExperience: $experience\nCover Letter: $cover_letter";
    $headers = array('Content-Type: text/plain; charset=UTF-8');
    
    $attachments = array();
    if (isset($cv_url)) {
        $attachments[] = $cv_path;
    }
    
    $sent = wp_mail($to, $subject, $body, $headers, $attachments);
    
    if ($sent) {
        wp_send_json_success('Application submitted successfully!');
    } else {
        wp_send_json_error('Failed to submit application. Please try again.');
    }
}
add_action('wp_ajax_chainthat_career_application', 'chainthat_handle_career_application');
add_action('wp_ajax_nopriv_chainthat_career_application', 'chainthat_handle_career_application');

// Customize admin menu
function chainthat_admin_menu() {
    // Add custom menu items if needed
}
add_action('admin_menu', 'chainthat_admin_menu');

// Add custom CSS for admin
function chainthat_admin_styles() {
    echo '<style>
        .acf-field-group .acf-field-group-heading {
            background: #f1f1f1;
            padding: 10px;
            margin: 10px 0;
            border-left: 4px solid #0073aa;
        }
    </style>';
}
add_action('admin_head', 'chainthat_admin_styles');

// Admin notice for setup
function chainthat_admin_notice() {
    if (!get_option('chainthat_setup_complete')) {
        echo '<div class="notice notice-info is-dismissible">
            <p><strong>ChainThat Theme:</strong> Please install the Advanced Custom Fields plugin and import the field groups to start editing content. <a href="' . admin_url('plugins.php') . '">Install ACF Plugin</a> | <a href="' . admin_url('edit.php?post_type=acf-field-group') . '">Import Field Groups</a></p>
        </div>';
    }
}
add_action('admin_notices', 'chainthat_admin_notice');

// Mark setup as complete when ACF is active
function chainthat_check_acf_setup() {
    if (function_exists('acf_get_field_groups') && !get_option('chainthat_setup_complete')) {
        $field_groups = acf_get_field_groups();
        if (count($field_groups) >= 4) {
            update_option('chainthat_setup_complete', true);
        }
    }
}
add_action('admin_init', 'chainthat_check_acf_setup');

// Template functions
function chainthat_posted_on() {
    $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
    if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
        $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
    }

    $time_string = sprintf( $time_string,
        esc_attr( get_the_date( DATE_W3C ) ),
        esc_html( get_the_date() ),
        esc_attr( get_the_modified_date( DATE_W3C ) ),
        esc_html( get_the_modified_date() )
    );

    $posted_on = sprintf(
        /* translators: %s: post date. */
        esc_html_x( 'Posted on %s', 'post date', 'chainthat' ),
        '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
    );

    echo '<span class="posted-on">' . $posted_on . '</span>'; // WPCS: XSS OK.
}

function chainthat_posted_by() {
    $byline = sprintf(
        /* translators: %s: post author. */
        esc_html_x( 'by %s', 'post author', 'chainthat' ),
        '<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
    );

    echo '<span class="byline"> ' . $byline . '</span>'; // WPCS: XSS OK.
}

function chainthat_post_thumbnail() {
    if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
        return;
    }

    if ( is_singular() ) :
        ?>
        <div class="post-thumbnail">
            <?php the_post_thumbnail(); ?>
        </div><!-- .post-thumbnail -->
    <?php else : ?>
        <a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
            <?php
            the_post_thumbnail( 'post-thumbnail', array(
                'alt' => the_title_attribute( array(
                    'echo' => false,
                ) ),
            ) );
            ?>
        </a>
        <?php
    endif; // End is_singular().
}

function chainthat_entry_footer() {
    // Hide category and tag text for pages.
    if ( 'post' === get_post_type() ) {
        /* translators: used between list items, there is a space after the comma */
        $categories_list = get_the_category_list( esc_html__( ', ', 'chainthat' ) );
        if ( $categories_list ) {
            /* translators: 1: list of categories. */
            printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'chainthat' ) . '</span>', $categories_list ); // WPCS: XSS OK.
        }

        /* translators: used between list items, there is a space after the comma */
        $tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'chainthat' ) );
        if ( $tags_list ) {
            /* translators: 1: list of tags. */
            printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'chainthat' ) . '</span>', $tags_list ); // WPCS: XSS OK.
        }
    }

    if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
        echo '<span class="comments-link">';
        comments_popup_link(
            sprintf(
                wp_kses(
                    /* translators: %s: post title */
                    __( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'chainthat' ),
                    array(
                        'span' => array(
                            'class' => array(),
                        ),
                    )
                ),
                get_the_title()
            )
        );
        echo '</span>';
    }

    edit_post_link(
        sprintf(
            wp_kses(
                /* translators: %s: Name of current post. Only visible to screen readers */
                __( 'Edit <span class="screen-reader-text">%s</span>', 'chainthat' ),
                array(
                    'span' => array(
                        'class' => array(),
                    ),
                )
            ),
            get_the_title()
        ),
        '<span class="edit-link">',
        '</span>'
    );
}

// Enable SVG uploads
function chainthat_allow_svg_upload($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    $mimes['svgz'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'chainthat_allow_svg_upload');

// Fix SVG display in media library
function chainthat_fix_svg_display($response, $attachment, $meta) {
    if ($response['type'] === 'image' && $response['subtype'] === 'svg+xml') {
        $response['image'] = array(
            'src' => $response['url'],
            'width' => 150,
            'height' => 150
        );
        $response['thumb'] = array(
            'src' => $response['url'],
            'width' => 150,
            'height' => 150
        );
    }
    return $response;
}
add_filter('wp_prepare_attachment_for_js', 'chainthat_fix_svg_display', 10, 3);

// Add SVG support to media library
function chainthat_add_svg_support() {
    echo '<style>
        .attachment-266x266, .thumbnail img {
            width: 100% !important;
            height: auto !important;
        }
        .media-icon img[src$=".svg"] {
            width: 100%;
            height: auto;
        }
    </style>';
}
add_action('admin_head', 'chainthat_add_svg_support');



// Custom Walker for Desktop Navigation
class ChainThat_Walker_Nav_Menu extends Walker_Nav_Menu {
    
    function start_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul class=\"submenu\">\n";
    }
    
    function end_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</ul>\n";
    }
    
    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';
        
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;
        
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
        
        $id = apply_filters('nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';
        
        $output .= $indent . '<li' . $id . $class_names .'>';
        
        $attributes = ! empty($item->attr_title) ? ' title="'  . esc_attr($item->attr_title) .'"' : '';
        $attributes .= ! empty($item->target)     ? ' target="' . esc_attr($item->target     ) .'"' : '';
        $attributes .= ! empty($item->xfn)       ? ' rel="'    . esc_attr($item->xfn       ) .'"' : '';
        $attributes .= ! empty($item->url)       ? ' href="'   . esc_attr($item->url       ) .'"' : '';
        
        // Add arrow for parent items
        $arrow = '';
        if (in_array('menu-item-has-children', $classes)) {
            $arrow = '<span><img src="' . get_template_directory_uri() . '/images/arrow.png" alt=""></span>';
        }
        
        $item_output = isset($args->before) ? $args->before : '';
        $item_output .= '<a' . $attributes . '>';
        $item_output .= (isset($args->link_before) ? $args->link_before : '') . apply_filters('the_title', $item->title, $item->ID) . (isset($args->link_after) ? $args->link_after : '');
        $item_output .= $arrow;
        $item_output .= '</a>';
        $item_output .= isset($args->after) ? $args->after : '';
        
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
    
    function end_el(&$output, $item, $depth = 0, $args = null) {
        $output .= "</li>\n";
    }
}

// Custom Walker for Mobile Navigation
class ChainThat_Mobile_Walker_Nav_Menu extends Walker_Nav_Menu {
    
    function start_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<div class=\"sub-menu\">\n";
    }
    
    function end_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</div>\n";
    }
    
    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';
        
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;
        
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
        
        $id = apply_filters('nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';
        
        $output .= $indent . '<div' . $id . $class_names .'>';
        
        $attributes = ! empty($item->attr_title) ? ' title="'  . esc_attr($item->attr_title) .'"' : '';
        $attributes .= ! empty($item->target)     ? ' target="' . esc_attr($item->target     ) .'"' : '';
        $attributes .= ! empty($item->xfn)       ? ' rel="'    . esc_attr($item->xfn       ) .'"' : '';
        $attributes .= ! empty($item->url)       ? ' href="'   . esc_attr($item->url       ) .'"' : '';
        
        // Add arrow for parent items
        $arrow = '';
        if (in_array('menu-item-has-children', $classes)) {
            $arrow = '<span><img src="' . get_template_directory_uri() . '/images/arrow2.png" alt=""></span>';
        }
        
        $item_output = isset($args->before) ? $args->before : '';
        $item_output .= '<a class="sub-btn"' . $attributes . '>';
        $item_output .= (isset($args->link_before) ? $args->link_before : '') . apply_filters('the_title', $item->title, $item->ID) . (isset($args->link_after) ? $args->link_after : '');
        $item_output .= $arrow;
        $item_output .= '</a>';
        $item_output .= isset($args->after) ? $args->after : '';
        
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
    
    function end_el(&$output, $item, $depth = 0, $args = null) {
            $output .= "</div>\n";
    }
}
// Add image sizes
add_image_size('hero-slide', 1920, 800, true);
add_image_size('service-icon', 60, 60, true);
add_image_size('team-member', 300, 300, true);
add_image_size('team-about', 214, 106, true); // About page team members
add_image_size('blog-thumbnail', 400, 250, true);

// Add high-resolution image sizes for better quality
add_image_size('blog-thumbnail-large', 800, 500, true);  // 2x resolution for retina
add_image_size('news-featured', 600, 400, true);         // For featured news items
add_image_size('news-featured-large', 1200, 800, true);  // 2x resolution for retina
add_image_size('tab-thumbnail', 500, 350, true);         // For tab content
add_image_size('tab-thumbnail-large', 1000, 700, true);  // 2x resolution for retina
// Single function to populate menu choices for both navigation and footer
function acf_load_all_menu_choices($field) {
    $field['choices'] = array();
    
    // Get all menus
    $menus = wp_get_nav_menus();
    
    if ($menus && !is_wp_error($menus)) {
        foreach ($menus as $menu) {
            $field['choices'][$menu->term_id] = $menu->name;
        }
    }
    
    return $field;
}

// Apply to both fields
add_filter('acf/load_field/name=theme_navigation_menu', 'acf_load_all_menu_choices');
add_filter('acf/load_field/name=theme_footer_links_menu', 'acf_load_all_menu_choices');
// Custom walker for footer menu
class Footer_Menu_Walker extends Walker_Nav_Menu {
    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $output .= '<li><a href="' . esc_url($item->url) . '">' . esc_html($item->title) . '</a></li>';
    }
}
// Force sync ACF fields on theme activation
function chainthat_sync_acf_fields() {
    if (function_exists('acf_get_field_group')) {
        $field_groups = acf_get_field_groups();
        foreach ($field_groups as $field_group) {
            $local = acf_get_local_field_group($field_group['key']);
            if ($local) {
                acf_import_field_group($local);
            }
        }
    }
}
add_action('after_switch_theme', 'chainthat_sync_acf_fields');

// Force sync ACF fields on admin init (for development)
function chainthat_force_sync_acf_fields() {
    if (is_admin() && current_user_can('manage_options')) {
        if (isset($_GET['sync_acf_fields']) && $_GET['sync_acf_fields'] === 'true') {
            chainthat_sync_acf_fields();
            wp_redirect(admin_url('edit.php?post_type=acf-field-group&acfsynced=1'));
            exit;
        }
    }
}
add_action('admin_init', 'chainthat_force_sync_acf_fields');

/**
 * Include Platform Migration Tool
 */
if (is_admin()) {
    require_once get_template_directory() . '/inc/platform-migration-tool.php';
}

/**
 * Flush rewrite rules for platform post type visibility in menus
 * This runs once when the function is added, then can be triggered manually
 */
function chainthat_flush_rewrite_rules_once() {
    $flushed = get_option('chainthat_platform_flush_done');
    if (!$flushed) {
        flush_rewrite_rules();
        update_option('chainthat_platform_flush_done', true);
    }
}
add_action('init', 'chainthat_flush_rewrite_rules_once', 999);

/**
 * Benefits Carousel Initialization for Solution Template
 */
function chainthat_benefits_carousel_script() {
    // Only load on solution single pages
    if (!is_singular('solution')) {
        return;
    }
    ?>
    <script>
    jQuery(document).ready(function($) {
        console.log('Benefits Carousel: Initializing...');
        
        var $carousel = $('#benefitsCarousel');
        
        if ($carousel.length && typeof $.fn.owlCarousel !== 'undefined') {
            $carousel.owlCarousel({
                items: 4,
                loop: false,
                margin: 20,
                nav: true,
                dots: true,
                autoplay: false,
                navText: ['&#10094;', '&#10095;'],
                responsive: {
                    0: { items: 1, margin: 10 },
                    576: { items: 2, margin: 15 },
                    768: { items: 3, margin: 20 },
                    1200: { items: 4, margin: 20 }
                }
            });
            console.log('✓ Benefits Carousel: Initialized!');
        }
        
        // Read More / Read Less
        $(document).on('click', '.benefit-read-more', function(e) {
            e.preventDefault();
            var $button = $(this);
            var $description = $button.closest('.benefit-description');
            var $shortText = $description.find('.benefit-short');
            var $fullText = $description.find('.benefit-full');
            var isExpanded = $button.attr('data-expanded') === 'true';

            if (isExpanded) {
                $shortText.removeClass('hidden').show();
                $fullText.removeClass('visible').hide();
                $button.text('Read More').attr('data-expanded', 'false');
            } else {
                $shortText.addClass('hidden').hide();
                $fullText.addClass('visible').show();
                $button.text('Read Less').attr('data-expanded', 'true');
            }
        });
    });
    </script>
    <?php
}
add_action('wp_footer', 'chainthat_benefits_carousel_script', 999);

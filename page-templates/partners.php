<?php
/**
 * Template Name: Partners Template
 *
 * @package ChainThat
 * @version 1.0.0
 */

get_header(); 

// Helper function to check if a section should be displayed
function is_partners_section_enabled($section_name) {
    if (!function_exists('get_field')) return true;
    $section_controls = get_field('partners_section_controls');
    if (!$section_controls) return true; // Show all sections by default
    $toggle_key = $section_name . '_section_toggle';
    return isset($section_controls[$toggle_key]) && $section_controls[$toggle_key] === 'show';
}

// Get ACF fields for page content
$page_title = function_exists('get_field') ? get_field('partners_page_title') : '';
$page_description = function_exists('get_field') ? get_field('partners_page_description') : '';

// Use defaults if fields are empty
if (empty($page_title)) {
    $page_title = 'Our Partners';
}
if (empty($page_description)) {
    $page_description = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.';
}

// Get all partner categories
$categories = get_terms(array(
    'taxonomy' => 'partner_category',
    'hide_empty' => false,
    'orderby' => 'name',
    'order' => 'ASC'
));
?>

<?php if (is_partners_section_enabled('main')): ?>
<!-- partners-section -->
<section class="partners-page-area">
        <div class="container">
        <div class="all-title partner-page-title">
            <h2 class="wow fadeInRight"><?php echo esc_html($page_title); ?></h2>
            <p class="wow fadeInLeft"><?php echo esc_html($page_description); ?></p>
        </div>
        <div class="partners-page-main">
            <div class="tab-container" data-set="partners">
                <div class="tab-buttons tab-buttons2 wow fadeInRight">
                    <a href="#partners-all" class="tab-btn tab-btn2 active" data-tab="partners-all">All</a>
                    <?php
                    if ($categories && !is_wp_error($categories)):
                        foreach ($categories as $category):
                            $cat_slug = $category->slug;
                    ?>
                        <a href="#partners-<?php echo esc_attr($cat_slug); ?>" class="tab-btn tab-btn2" data-tab="partners-<?php echo esc_attr($cat_slug); ?>"><?php echo esc_html($category->name); ?></a>
                    <?php
                        endforeach;
                    endif;
                    ?>
                </div>
                <div class="tab-content">
                    <!-- All Partners Tab -->
                    <div id="partners-all" class="tab-pane active">
                        <div class="partner-logo-wrap">
                            <?php
                            // Get all partners
                            $all_partners_args = array(
                                'post_type' => 'partners',
                                'posts_per_page' => -1,
                                'post_status' => 'publish',
                                'orderby' => 'meta_value_num',
                                'meta_key' => 'partner_order',
                                'order' => 'ASC'
                            );
                            $all_partners = new WP_Query($all_partners_args);
                            
                            if ($all_partners->have_posts()):
                                $index = 0;
                                while ($all_partners->have_posts()): $all_partners->the_post();
                                    $logo = function_exists('get_field') ? get_field('partner_logo') : '';
                                    $alt_text = function_exists('get_field') ? get_field('partner_alt_text') : '';
                                    if (empty($alt_text)) {
                                        $alt_text = get_the_title();
                                    }
                                    $website = function_exists('get_field') ? get_field('partner_website') : '';
                                    
                                    // Skip if no logo
                                    if (empty($logo)) {
                                        continue;
                                    }
                                    
                                    // Animation alternates
                                    $animation = $index % 2 == 0 ? 'wow fadeInLeft' : 'wow fadeInRight';
                                    
                                    // Get special class for specific partners based on position
                                    $special_class = '';
                                    // 6th position (index 5) and every 6th after
                                    if (($index + 1) == 6 || (($index + 1) - 6) % 12 == 0) {
                                        $special_class = 'partner-logo6';
                                    }
                                    // 12th position (index 11) and every 12th after  
                                    if (($index + 1) == 12 || (($index + 1) > 12 && ($index + 1) % 12 == 0)) {
                                        $special_class = 'partner-logo12';
                                    }
                            ?>
                                <div class="partner-logo <?php echo $special_class; ?> <?php echo $animation; ?>">
                                    <?php if (!empty($website)): ?>
                                        <a href="<?php echo esc_url($website); ?>" target="_blank" rel="noopener noreferrer">
                                            <img src="<?php echo esc_url($logo); ?>" alt="<?php echo esc_attr($alt_text); ?>">
                                        </a>
                                    <?php else: ?>
                                        <img src="<?php echo esc_url($logo); ?>" alt="<?php echo esc_attr($alt_text); ?>">
                                    <?php endif; ?>
                                </div>
                            <?php
                                $index++;
                                endwhile;
                                wp_reset_postdata();
                            endif;
                            ?>
                        </div>
                    </div>
                    
                    <?php
                    // Category tabs
                    if ($categories && !is_wp_error($categories)):
                        foreach ($categories as $category):
                            $cat_slug = $category->slug;
                    ?>
                        <div id="partners-<?php echo esc_attr($cat_slug); ?>" class="tab-pane">
                            <div class="tab-cnt20">
                                <div class="partner-logo-wrap">
                                    <?php
                                    // Get partners in this category
                                    $category_partners_args = array(
                                        'post_type' => 'partners',
                                        'posts_per_page' => -1,
                                        'post_status' => 'publish',
                                        'orderby' => 'meta_value_num',
                                        'meta_key' => 'partner_order',
                                        'order' => 'ASC',
                                        'tax_query' => array(
                                            array(
                                                'taxonomy' => 'partner_category',
                                                'field' => 'term_id',
                                                'terms' => $category->term_id
                                            )
                                        )
                                    );
                                    $category_partners = new WP_Query($category_partners_args);
                                    
                                    if ($category_partners->have_posts()):
                                        $index = 0;
                                        while ($category_partners->have_posts()): $category_partners->the_post();
                                            $logo = function_exists('get_field') ? get_field('partner_logo') : '';
                                            $alt_text = function_exists('get_field') ? get_field('partner_alt_text') : '';
                                            if (empty($alt_text)) {
                                                $alt_text = get_the_title();
                                            }
                                            $website = function_exists('get_field') ? get_field('partner_website') : '';
                                            
                                            // Skip if no logo
                                            if (empty($logo)) {
                                                continue;
                                            }
                                            
                                            // Animation alternates (opposite direction for category tabs)
                                            $animation = $index % 2 == 0 ? 'wow fadeInRight' : 'wow fadeInLeft';
                                            
                                            // Get special class for specific partners based on position
                                            $special_class = '';
                                            // 6th position (index 5) and every 6th after
                                            if (($index + 1) == 6 || (($index + 1) - 6) % 12 == 0) {
                                                $special_class = 'partner-logo6';
                                            }
                                            // 12th position (index 11) and every 12th after  
                                            if (($index + 1) == 12 || (($index + 1) > 12 && ($index + 1) % 12 == 0)) {
                                                $special_class = 'partner-logo12';
                                            }
                                    ?>
                                        <div class="partner-logo <?php echo $special_class; ?> <?php echo $animation; ?>">
                                            <?php if (!empty($website)): ?>
                                                <a href="<?php echo esc_url($website); ?>" target="_blank" rel="noopener noreferrer">
                                                    <img src="<?php echo esc_url($logo); ?>" alt="<?php echo esc_attr($alt_text); ?>">
                                                </a>
                                            <?php else: ?>
                                                <img src="<?php echo esc_url($logo); ?>" alt="<?php echo esc_attr($alt_text); ?>">
                                            <?php endif; ?>
                                        </div>
                                    <?php
                                        $index++;
                                        endwhile;
                                        wp_reset_postdata();
                                    endif;
                                    ?>
                                </div>
                            </div>
                                </div>
                    <?php
                        endforeach;
                    endif;
                    ?>
                                </div>
                                </div>
                                </div>
        <div class="part-mob d-block d-lg-none wow fadeInRight">
            <?php 
            $mobile_decoration = function_exists('get_field') ? get_field('partners_mobile_decoration') : '';
            if (!empty($mobile_decoration)): 
            ?>
                <img src="<?php echo esc_url($mobile_decoration); ?>" alt="">
            <?php else: ?>
                <img src="<?php echo get_template_directory_uri(); ?>/images/swoos4.png" alt="">
            <?php endif; ?>
                                    </div>
                                </div>
</section>
<?php endif; ?>

<!-- tuch-section -->
<?php 
if (is_partners_section_enabled('contact')):
    $show_contact_form = function_exists('get_field') ? get_field('partners_show_contact_form') : true;
    if ($show_contact_form):
    $form_title = function_exists('get_field') ? get_field('partners_form_title') : 'Get in touch';
    if (empty($form_title)) {
        $form_title = 'Get in touch';
    }
    $form_shortcode = function_exists('get_field') ? get_field('partners_form_shortcode') : '';
?>
<section class="tuch-area">
    <div class="container">
        <div class="tuch-main">
            <div class="tuch-title wow fadeInRight">
                <h2><?php echo esc_html($form_title); ?></h2>
            </div>
            <?php if (!empty($form_shortcode)): ?>
                <?php echo do_shortcode($form_shortcode); ?>
            <?php else: ?>
            <form action="">
                <div class="tuch-form">
                    <div class="tuch-input-flex">
                        <div class="tuch-input wow fadeInLeft">
                            <label for="firstName">First Name</label>
                            <input type="text" id="firstName" name="firstName" placeholder="First Name" required>
                        </div>
                        <div class="tuch-input wow fadeInRight">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" placeholder="Email" required>
                        </div>
                        <div class="tuch-input wow fadeInLeft">
                            <label for="phone">Phone</label>
                            <input type="tel" id="phone" name="phone" placeholder="000 000 0000">
                        </div> 
                        <div class="tuch-input wow fadeInRight">
                            <label for="company">Company</label>
                            <input type="text" id="company" name="company" placeholder="Company Name">
                        </div>
                    </div>
                    <div class="tuch-textarea wow fadeInLeft">
                        <label for="message">Message</label>
                        <textarea id="message" name="message" rows="4" placeholder="Message..."></textarea>
                    </div>
                    <div class="tuch-checkbox d-none d-lg-block wow fadeInRight">
                        <div class="checkbox"><input type="checkbox" id="check" name="terms" required><label for="check">I accept <a href="<?php echo esc_url(get_field('terms_url', 'option') ?: '#'); ?>">Terms & Conditions.</a> Check our <a href="<?php echo esc_url(get_field('privacy_url', 'option') ?: '#'); ?>">Privacy Policy</a></label></div>
                    </div>
                    <div class="tuch-submit wow fadeInUp">
                        <button type="submit">submit</button>
                    </div>
                </div>
            </form>
            <?php endif; ?>
                </div>
            </div>
        </section>
    <?php 
    endif; // End show_contact_form check
endif; // End is_partners_section_enabled('contact') check
?>

<?php get_footer(); ?>

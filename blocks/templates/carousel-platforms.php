<?php
/**
 * Platforms Carousel Block Template
 * 
 * Displays platform posts in responsive grid or carousel
 * Adaptive layout: static grid for ≤3 items, carousel for >3
 * 
 * @param array $block The block settings and attributes
 * @param string $content The block inner HTML (empty)
 * @param bool $is_preview True during AJAX preview
 * @param int $post_id The post ID this block is saved to
 * 
 * @package ChainThat
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Get block attributes
$attributes = chainthat_get_block_attributes($block, 'platforms-carousel-block');

// Get data source
$data_source = get_field('data_source') ?: 'page_fields';

// Fetch data based on source
if ($data_source === 'page_fields') {
    // Use existing page-level ACF fields
    $policy_section = get_field('policy_section', $post_id ?: get_the_ID());
    $span_text = isset($policy_section['policy_span_text']) ? $policy_section['policy_span_text'] : 'Pain Relievers & Gain Creators';
    $section_title = isset($policy_section['policy_title']) ? $policy_section['policy_title'] : 'Platforms to Build on';
    $section_description = isset($policy_section['policy_description']) ? $policy_section['policy_description'] : '';
} else {
    // Use block-specific custom fields
    $span_text = get_field('custom_span_text') ?: 'Pain Relievers & Gain Creators';
    $section_title = get_field('custom_section_title') ?: 'Platforms to Build on';
    $section_description = get_field('custom_section_description') ?: '';
}

// Query platform posts
$platform_posts = new WP_Query(array(
    'post_type' => 'platform',
    'posts_per_page' => -1,
    'post_status' => 'publish',
    'orderby' => 'menu_order',
    'order' => 'ASC'
));

// Preview mode check
if ($is_preview && !$platform_posts->have_posts()) {
    chainthat_block_preview_placeholder(
        'Platforms Carousel',
        'admin-site',
        'Create Platform posts to display. This block automatically queries all published platforms.'
    );
    return;
}

if (!$platform_posts->have_posts()) {
    return; // No platforms to display
}

$platform_count = $platform_posts->found_posts;
$animation_classes = ['fadeInLeft', 'fadeInRight', 'fadeInUp'];

// Generate unique carousel IDs
$carousel_desktop_id = chainthat_get_carousel_id('owl-policy-desktop');
$carousel_tablet_id = chainthat_get_carousel_id('owl-policy-tablet');
$carousel_mobile_id = chainthat_get_carousel_id('owl-policy-mobile');
?>

<!-- policy-section (Platforms) -->
<section id="<?php echo esc_attr($attributes['id']); ?>" class="policy-area <?php echo esc_attr($attributes['class']); ?>">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="policy-title text-center">
                    <?php if ($span_text): ?>
                    <span class="wow fadeInLeft"><?php echo esc_html($span_text); ?></span>
                    <?php endif; ?>
                    <?php if ($section_title): ?>
                    <h2 class="wow fadeInRight"><?php echo esc_html($section_title); ?></h2>
                    <?php endif; ?>
                    <?php if ($section_description): ?>
                    <p class="wow fadeInLeft"><?php echo esc_html($section_description); ?></p>
                    <?php endif; ?>
                </div>

                <div class="policy-main">
                    <?php if ($platform_count <= 3): ?>
                        <!-- Desktop: Static 3-column layout (above 1024px) -->
                        <div class="row d-none d-xl-flex">
                            <?php 
                            $index = 0;
                            while ($platform_posts->have_posts()): 
                                $platform_posts->the_post();
                                $animation_class = $animation_classes[$index % 3];
                                $platform_short_name = get_field('platform_short_name') ?: substr(get_the_title(), 0, 4);
                                $platform_button_text = get_field('platform_button_text') ?: 'Activate your agility';
                                $platform_button_link = get_permalink();
                                ?>
                                <div class="col-lg-4 mb-4">
                                    <div class="policy-item h-100 wow <?php echo $animation_class; ?>">
                                        <div class="policy-content">
                                            <h4 class="wow <?php echo $animation_class; ?>"><?php echo esc_html(get_the_title()); ?></h4>
                                            <div class="policy-circle wow fadeInLeft">
                                                <img src="<?php echo get_template_directory_uri(); ?>/images/policy.png" alt="">
                                                <div class="policy-text">
                                                    <h5><?php echo esc_html($platform_short_name); ?></h5>
                                                </div>
                                            </div>
                                            <p><?php echo esc_html(get_field('platform_description') ?: get_the_excerpt()); ?></p>
                                        </div>
                                        <div class="policy-button">
                                            <a href="<?php echo esc_url($platform_button_link); ?>"><?php echo esc_html($platform_button_text); ?></a>
                                        </div>
                                    </div>
                                </div>
                                <?php 
                                $index++;
                            endwhile; 
                            wp_reset_postdata();
                            ?>
                        </div>
                    <?php else: ?>
                        <!-- Desktop: Carousel for more than 3 platforms (above 1024px) -->
                        <div id="<?php echo esc_attr($carousel_desktop_id); ?>" class="owl-carousel owl-theme d-none d-xl-block">
                            <?php 
                            while ($platform_posts->have_posts()): 
                                $platform_posts->the_post();
                                $platform_short_name = get_field('platform_short_name') ?: substr(get_the_title(), 0, 4);
                                $platform_button_text = get_field('platform_button_text') ?: 'Activate your agility';
                                $platform_button_link = get_permalink();
                                ?>
                                <div class="policy-item">
                                    <div class="policy-content">
                                        <h4 class="wow fadeInLeft"><?php echo esc_html(get_the_title()); ?></h4>
                                        <div class="policy-circle wow fadeInLeft">
                                            <img src="<?php echo get_template_directory_uri(); ?>/images/policy.png" alt="">
                                            <div class="policy-text">
                                                <h5><?php echo esc_html($platform_short_name); ?></h5>
                                            </div>
                                        </div>
                                        <p><?php echo esc_html(get_field('platform_description') ?: get_the_excerpt()); ?></p>
                                    </div>
                                    <div class="policy-button">
                                        <a href="<?php echo esc_url($platform_button_link); ?>"><?php echo esc_html($platform_button_text); ?></a>
                                    </div>
                                </div>
                            <?php endwhile; 
                            wp_reset_postdata();
                            ?>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Tablet: 2 cards carousel (768px - 1023px) -->
                    <div id="<?php echo esc_attr($carousel_tablet_id); ?>" class="owl-carousel owl-theme d-none d-lg-block d-xl-none">
                        <?php 
                        $platform_posts->rewind_posts();
                        while ($platform_posts->have_posts()): 
                            $platform_posts->the_post();
                            $platform_short_name = get_field('platform_short_name') ?: substr(get_the_title(), 0, 4);
                            $platform_button_text = get_field('platform_button_text') ?: 'Activate your agility';
                            $platform_button_link = get_permalink();
                            ?>
                            <div class="policy-item">
                                <div class="policy-content">
                                    <h4 class="wow fadeInLeft"><?php echo esc_html(get_the_title()); ?></h4>
                                    <div class="policy-circle wow fadeInLeft">
                                        <img src="<?php echo get_template_directory_uri(); ?>/images/policy.png" alt="">
                                        <div class="policy-text">
                                            <h5><?php echo esc_html($platform_short_name); ?></h5>
                                        </div>
                                    </div>
                                    <p><?php echo esc_html(get_field('platform_description') ?: get_the_excerpt()); ?></p>
                                </div>
                                <div class="policy-button">
                                    <a href="<?php echo esc_url($platform_button_link); ?>"><?php echo esc_html($platform_button_text); ?></a>
                                </div>
                            </div>
                        <?php endwhile;
                        wp_reset_postdata();
                        ?>
                    </div>
                    
                    <!-- Mobile: 1 card carousel (below 768px) -->
                    <div id="<?php echo esc_attr($carousel_mobile_id); ?>" class="owl-carousel owl-theme d-block d-lg-none">
                        <?php 
                        $platform_posts->rewind_posts();
                        while ($platform_posts->have_posts()): 
                            $platform_posts->the_post();
                            $platform_short_name = get_field('platform_short_name') ?: substr(get_the_title(), 0, 4);
                            $platform_button_text = get_field('platform_button_text') ?: 'Activate your agility';
                            $platform_button_link = get_permalink();
                            ?>
                            <div class="policy-item">
                                <div class="policy-content">
                                    <h4 class="wow fadeInLeft"><?php echo esc_html(get_the_title()); ?></h4>
                                    <div class="policy-circle wow fadeInLeft">
                                        <img src="<?php echo get_template_directory_uri(); ?>/images/policy.png" alt="">
                                        <div class="policy-text">
                                            <h5><?php echo esc_html($platform_short_name); ?></h5>
                                        </div>
                                    </div>
                                    <p><?php echo esc_html(get_field('platform_description') ?: get_the_excerpt()); ?></p>
                                </div>
                                <div class="policy-button">
                                    <a href="<?php echo esc_url($platform_button_link); ?>"><?php echo esc_html($platform_button_text); ?></a>
                                </div>
                            </div>
                        <?php endwhile;
                        wp_reset_postdata();
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php if (!$is_preview && $platform_count > 3): ?>
<script>
jQuery(document).ready(function($) {
    // Desktop carousel (3 items)
    if ($('#<?php echo esc_js($carousel_desktop_id); ?>').length) {
        $('#<?php echo esc_js($carousel_desktop_id); ?>').owlCarousel({
            loop: true,
            margin: 30,
            nav: true,
            dots: false,
            autoplay: true,
            autoplayTimeout: 4000,
            autoplayHoverPause: true,
            items: 3
        });
    }
    
    // Tablet carousel (2 items)
    if ($('#<?php echo esc_js($carousel_tablet_id); ?>').length) {
        $('#<?php echo esc_js($carousel_tablet_id); ?>').owlCarousel({
            loop: true,
            margin: 20,
            nav: true,
            dots: false,
            autoplay: true,
            autoplayTimeout: 4000,
            autoplayHoverPause: true,
            items: 2
        });
    }
    
    // Mobile carousel (1 item)
    if ($('#<?php echo esc_js($carousel_mobile_id); ?>').length) {
        $('#<?php echo esc_js($carousel_mobile_id); ?>').owlCarousel({
            loop: true,
            margin: 0,
            nav: true,
            dots: true,
            autoplay: true,
            autoplayTimeout: 4000,
            autoplayHoverPause: true,
            items: 1
        });
    }
});
</script>
<?php endif; ?>



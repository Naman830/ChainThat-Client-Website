<?php
/**
 * Statistics Display Block Template
 * 
 * Displays statistics with synchronized background image carousel
 * Background images from platform featured images
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
$attributes = chainthat_get_block_attributes($block, 'statistics-block');

// Get data source
$data_source = get_field('data_source') ?: 'page_fields';

// Fetch data based on source
if ($data_source === 'page_fields') {
    // Use existing page-level ACF fields (statistics are inline on front-page.php)
    // Background images from platform featured images
    $use_platform_images = true;
    $statistics = array(
        array('value' => '200+', 'label' => 'Enterprises use our API'),
        array('value' => '1.4M+', 'label' => 'Users across multiple industries'),
        array('value' => '10Bn+', 'label' => 'Transactions processed'),
        array('value' => '200+', 'label' => 'Integrations with diverse platforms'),
    );
} else {
    // Use block-specific custom fields
    $use_platform_images = get_field('custom_use_platform_images') ?: false;
    $background_images = get_field('custom_background_images') ?: array();
    $statistics = get_field('custom_statistics') ?: array();
}

// Get background images
if ($use_platform_images) {
    // Query platform posts for featured images
    $platform_query = new WP_Query(array(
        'post_type' => 'platform',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'orderby' => 'menu_order',
        'order' => 'ASC'
    ));
    
    $background_images = array();
    if ($platform_query->have_posts()) {
        while ($platform_query->have_posts()) {
            $platform_query->the_post();
            $featured_img = get_the_post_thumbnail_url(get_the_ID(), 'full');
            if ($featured_img) {
                $background_images[] = array(
                    'image' => $featured_img,
                    'alt' => get_the_title()
                );
            }
        }
        wp_reset_postdata();
    }
}

// Preview mode check
if ($is_preview && empty($statistics)) {
    chainthat_block_preview_placeholder(
        'Statistics Display',
        'chart-bar',
        'Add statistics to display. Background can sync with Platform featured images.'
    );
    return;
}

if (empty($statistics)) {
    return; // No statistics to display
}

// Generate unique carousel ID
$carousel_id = chainthat_get_carousel_id('owl-static');
?>

<!-- static-section (Statistics) -->
<section id="<?php echo esc_attr($attributes['id']); ?>" class="static-area <?php echo esc_attr($attributes['class']); ?>">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 p-0">
                <div class="main-content1">
                    <!-- Background Image Carousel -->
                    <?php if (!empty($background_images)): ?>
                    <div id="<?php echo esc_attr($carousel_id); ?>" class="owl-carousel owl-theme">
                        <?php foreach ($background_images as $bg_image): 
                            $image_url = is_array($bg_image) && isset($bg_image['image']) ? $bg_image['image'] : $bg_image;
                            $image_alt = is_array($bg_image) && isset($bg_image['alt']) ? $bg_image['alt'] : 'Background';
                            ?>
                            <div class="static-item">
                                <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($image_alt); ?>">
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                    
                    <div class="owl-theme">
                        <div class="owl-controls">
                            <div class="custom-nav owl-nav"></div>
                        </div>
                    </div>
                    
                    <!-- Statistics Overlay -->
                    <div class="static-img wow fadeInLeft">
                        <img src="<?php echo get_template_directory_uri(); ?>/images/static1.png" alt="Statistics Background">
                        <div class="static-content">
                            <div class="static-first">
                                <?php 
                                $stat_count = count($statistics);
                                $half_count = ceil($stat_count / 2);
                                
                                // First half of statistics
                                for ($i = 0; $i < $half_count; $i++):
                                    if (isset($statistics[$i])):
                                        $stat_value = isset($statistics[$i]['value']) ? $statistics[$i]['value'] : $statistics[$i]['stat_value'];
                                        $stat_label = isset($statistics[$i]['label']) ? $statistics[$i]['label'] : $statistics[$i]['stat_label'];
                                        ?>
                                        <div class="static-list">
                                            <h3 class="wow fadeInLeft"><?php echo esc_html($stat_value); ?></h3>
                                            <p class="wow fadeInRight"><?php echo esc_html($stat_label); ?></p>
                                        </div>
                                    <?php endif;
                                endfor;
                                ?>
                            </div>
                            <div class="static-second">
                                <?php 
                                // Second half of statistics
                                for ($i = $half_count; $i < $stat_count; $i++):
                                    if (isset($statistics[$i])):
                                        $stat_value = isset($statistics[$i]['value']) ? $statistics[$i]['value'] : $statistics[$i]['stat_value'];
                                        $stat_label = isset($statistics[$i]['label']) ? $statistics[$i]['label'] : $statistics[$i]['stat_label'];
                                        ?>
                                        <div class="static-list">
                                            <h3 class="wow fadeInLeft"><?php echo esc_html($stat_value); ?></h3>
                                            <p class="wow fadeInRight"><?php echo esc_html($stat_label); ?></p>
                                        </div>
                                    <?php endif;
                                endfor;
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php if (!$is_preview && !empty($background_images)): ?>
<script>
jQuery(document).ready(function($) {
    if ($('#<?php echo esc_js($carousel_id); ?>').length) {
        $('#<?php echo esc_js($carousel_id); ?>').owlCarousel({
            loop: true,
            margin: 0,
            nav: true,
            dots: false,
            autoplay: true,
            autoplayTimeout: 3000,
            autoplayHoverPause: true,
            items: 1,
            animateOut: 'fadeOut',
            animateIn: 'fadeIn',
            smartSpeed: 1000
        });
    }
});
</script>
<?php endif; ?>



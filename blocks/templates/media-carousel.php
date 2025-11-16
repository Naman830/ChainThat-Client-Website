<?php
/**
 * Image Carousel Block Template
 * 
 * Displays image carousel with multiple layout options
 * Supports standard carousel and continuous scroll modes
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
$attributes = chainthat_get_block_attributes($block, 'image-carousel-block');

// Get data source
$data_source = get_field('data_source') ?: 'page_fields';

// Fetch data based on source
if ($data_source === 'page_fields') {
    // Use existing page-level ACF fields
    $carousel_images = get_field('carousel_images', $post_id ?: get_the_ID()) ?: get_field('image_carousel', $post_id ?: get_the_ID()) ?: array();
    $carousel_type = 'standard';
    $autoplay = true;
    $autoplay_speed = 4000;
} else {
    // Use block-specific custom fields
    $carousel_images = get_field('custom_carousel_images') ?: array();
    $carousel_type = get_field('custom_carousel_type') ?: 'standard';
    $autoplay = get_field('custom_autoplay') !== false;
    $autoplay_speed = get_field('custom_autoplay_speed') ?: 4000;
}

// Preview mode check
if ($is_preview && empty($carousel_images)) {
    chainthat_block_preview_placeholder(
        'Image Carousel',
        'images-alt2',
        'Add images to display in carousel. Supports standard and continuous scroll modes.'
    );
    return;
}

if (empty($carousel_images)) {
    return; // No images to display
}

// Generate unique carousel ID
$carousel_id = chainthat_get_carousel_id('owl-image-carousel');
?>

<!-- image-carousel-section -->
<section id="<?php echo esc_attr($attributes['id']); ?>" class="image-carousel-area carousel-type-<?php echo esc_attr($carousel_type); ?> <?php echo esc_attr($attributes['class']); ?>">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="image-carousel-wrapper">
                    <?php if ($carousel_type === 'continuous-scroll'): ?>
                    <!-- Continuous Scroll Mode -->
                    <div class="continuous-scroll-wrapper">
                        <div class="continuous-scroll-track" data-speed="<?php echo esc_attr($autoplay_speed); ?>">
                            <?php 
                            // Duplicate images for seamless loop
                            $images_to_display = array_merge($carousel_images, $carousel_images);
                            foreach ($images_to_display as $image): 
                                $image_url = is_array($image) && isset($image['url']) ? $image['url'] : (is_array($image) && isset($image['image']) ? $image['image'] : $image);
                                $image_alt = is_array($image) && isset($image['alt']) ? $image['alt'] : (is_array($image) && isset($image['caption']) ? $image['caption'] : 'Carousel Image');
                                $image_caption = is_array($image) && isset($image['caption']) ? $image['caption'] : '';
                                
                                // Handle ACF image array
                                if (is_array($image_url) && isset($image_url['url'])) {
                                    $image_url = $image_url['url'];
                                }
                                ?>
                                <div class="continuous-scroll-item">
                                    <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($image_alt); ?>">
                                    <?php if ($image_caption): ?>
                                    <div class="image-caption"><?php echo esc_html($image_caption); ?></div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <?php else: ?>
                    <!-- Standard Carousel Mode -->
                    <div class="main-content-carousel">
                        <div id="<?php echo esc_attr($carousel_id); ?>" class="owl-carousel owl-theme">
                            <?php 
                            foreach ($carousel_images as $image): 
                                $image_url = is_array($image) && isset($image['url']) ? $image['url'] : (is_array($image) && isset($image['image']) ? $image['image'] : $image);
                                $image_alt = is_array($image) && isset($image['alt']) ? $image['alt'] : (is_array($image) && isset($image['caption']) ? $image['caption'] : 'Carousel Image');
                                $image_caption = is_array($image) && isset($image['caption']) ? $image['caption'] : '';
                                
                                // Handle ACF image array
                                if (is_array($image_url) && isset($image_url['url'])) {
                                    $image_url = $image_url['url'];
                                }
                                ?>
                                <div class="carousel-item">
                                    <div class="carousel-image-wrapper">
                                        <img class="wow fadeIn" src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($image_alt); ?>">
                                        <?php if ($image_caption): ?>
                                        <div class="image-caption"><?php echo esc_html($image_caption); ?></div>
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
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php if (!$is_preview): ?>
<style>
/* Image Carousel Styles */
.image-carousel-area {
    padding: 80px 0;
}

.carousel-image-wrapper {
    position: relative;
    overflow: hidden;
    border-radius: 8px;
}

.carousel-image-wrapper img {
    width: 100%;
    height: auto;
    display: block;
}

.image-caption {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: rgba(0, 0, 0, 0.7);
    color: #fff;
    padding: 15px 20px;
    font-size: 14px;
    text-align: center;
}

/* Continuous Scroll Styles */
.continuous-scroll-wrapper {
    overflow: hidden;
    position: relative;
    width: 100%;
}

.continuous-scroll-track {
    display: flex;
    gap: 30px;
    animation: scroll-continuous 30s linear infinite;
}

.continuous-scroll-track:hover {
    animation-play-state: paused;
}

.continuous-scroll-item {
    flex: 0 0 auto;
    width: 300px;
    position: relative;
}

.continuous-scroll-item img {
    width: 100%;
    height: auto;
    border-radius: 8px;
}

@keyframes scroll-continuous {
    0% {
        transform: translateX(0);
    }
    100% {
        transform: translateX(-50%);
    }
}

@media (max-width: 991px) {
    .image-carousel-area {
        padding: 60px 0;
    }
    
    .continuous-scroll-item {
        width: 250px;
    }
}

@media (max-width: 767px) {
    .continuous-scroll-item {
        width: 200px;
    }
    
    .image-caption {
        padding: 10px 15px;
        font-size: 12px;
    }
}
</style>

<?php if ($carousel_type === 'standard'): ?>
<script>
jQuery(document).ready(function($) {
    if ($('#<?php echo esc_js($carousel_id); ?>').length) {
        $('#<?php echo esc_js($carousel_id); ?>').owlCarousel({
            loop: true,
            margin: 30,
            nav: true,
            dots: true,
            autoplay: <?php echo $autoplay ? 'true' : 'false'; ?>,
            autoplayTimeout: <?php echo intval($autoplay_speed); ?>,
            autoplayHoverPause: true,
            responsive: {
                0: {
                    items: 1
                },
                768: {
                    items: 2
                },
                1024: {
                    items: 3
                }
            }
        });
    }
});
</script>
<?php elseif ($carousel_type === 'continuous-scroll'): ?>
<script>
jQuery(document).ready(function($) {
    // Adjust animation speed based on custom setting
    var speed = <?php echo intval($autoplay_speed); ?> / 100; // Convert to seconds
    $('.continuous-scroll-track').css('animation-duration', speed + 's');
});
</script>
<?php endif; ?>
<?php endif; ?>



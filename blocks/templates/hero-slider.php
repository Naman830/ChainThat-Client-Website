<?php
/**
 * Hero Slider Block Template
 * 
 * Displays homepage hero section with image slider
 * Supports both page-level and custom block fields
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
$attributes = chainthat_get_block_attributes($block, 'hero-slider-block');

// Get data source
$data_source = get_field('data_source') ?: 'page_fields';

// Fetch data based on source
if ($data_source === 'page_fields') {
    // Use existing page-level ACF fields (NO MIGRATION NEEDED)
    $hero_section = get_field('hero_section', $post_id ?: get_the_ID());
    $slides = isset($hero_section['hero_slider']) ? $hero_section['hero_slider'] : array();
    $swoos_image = isset($hero_section['hero_swoos_image']) ? $hero_section['hero_swoos_image'] : '';
    $mobile_image = isset($hero_section['hero_mobile_image']) ? $hero_section['hero_mobile_image'] : '';
} else {
    // Use block-specific custom fields
    $slides = get_field('custom_hero_slides') ?: array();
    $swoos_image = get_field('custom_swoos_image') ?: '';
    $mobile_image = get_field('custom_mobile_image') ?: '';
}

// Fallback images
if (!$swoos_image) {
    $swoos_image = get_template_directory_uri() . '/images/swoos.png';
}
if (!$mobile_image) {
    $mobile_image = get_template_directory_uri() . '/images/hero-mobil.png';
}

// Preview mode check
if ($is_preview && empty($slides)) {
    chainthat_block_preview_placeholder(
        'Hero Slider',
        'slides',
        'Configure hero slides in the block settings. Use Page Fields mode to display existing data.'
    );
    return;
}
?>

<!-- hero-mobil -->
<div id="<?php echo esc_attr($attributes['id']); ?>-mobile" class="hero-mobil d-block d-lg-none wow fadeInLeft">
    <img src="<?php echo esc_url($mobile_image); ?>" alt="Hero Mobile">
</div>

<!-- hero-section -->
<section id="<?php echo esc_attr($attributes['id']); ?>" class="hero-area <?php echo esc_attr($attributes['class']); ?>">
    <div class="swoos-item wow fadeInRight" aria-hidden="true">
        <img src="<?php echo esc_url($swoos_image); ?>" alt="" role="presentation">
    </div>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="hero-main">
                    <div class="main-content1">
                        <div id="owl-csel1" class="owl-carousel owl-theme">
                            <?php 
                            if ($slides && is_array($slides) && !empty($slides)): 
                                foreach ($slides as $slide): 
                                    // Handle both page field structure and custom field structure
                                    $slide_title = isset($slide['hero_slide_title']) ? $slide['hero_slide_title'] : (isset($slide['slide_title']) ? $slide['slide_title'] : '');
                                    $slide_description = isset($slide['hero_slide_description']) ? $slide['hero_slide_description'] : (isset($slide['slide_description']) ? $slide['slide_description'] : '');
                                    $slide_button_text = isset($slide['hero_slide_button_text']) ? $slide['hero_slide_button_text'] : (isset($slide['button_text']) ? $slide['button_text'] : 'Book a demo');
                                    $slide_button_link = isset($slide['hero_slide_button_link']) ? $slide['hero_slide_button_link'] : (isset($slide['button_link']) ? $slide['button_link'] : '#');
                                    ?>
                                    <div class="hero-item">
                                        <h2 class="wow fadeInRight"><?php echo esc_html($slide_title); ?></h2>
                                        <p class="wow fadeInLeft"><?php echo esc_html($slide_description); ?></p>
                                        <a class="wow fadeInUp" href="<?php echo esc_url($slide_button_link); ?>"><?php echo esc_html($slide_button_text); ?></a>
                                    </div>
                                <?php endforeach;
                            else: ?>
                                <!-- Fallback slide if no data -->
                                <div class="hero-item">
                                    <h2 class="wow fadeInRight">Welcome to ChainThat</h2>
                                    <p class="wow fadeInLeft">Insurance technology platforms that activate agility</p>
                                    <a class="wow fadeInUp" href="#">Book a demo</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



<?php
/**
 * Hero Standard Block Template
 * 
 * Standard hero section with title, description, and optional background
 * Used in About, Careers, Platform, and Solution templates
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
$attributes = chainthat_get_block_attributes($block, 'hero-standard-block');

// Get data source
$data_source = get_field('data_source') ?: 'page_fields';

// Fetch data based on source and current template context
if ($data_source === 'page_fields') {
    // Determine which page field to read from based on current template
    $template = get_page_template_slug($post_id ?: get_the_ID());
    
    // About page
    if (strpos($template, 'about') !== false) {
        $hero_data = get_field('about_hero_section', $post_id ?: get_the_ID());
        $title = isset($hero_data['about_hero_title']) ? $hero_data['about_hero_title'] : get_the_title();
        $background_type = isset($hero_data['about_hero_background_type']) ? $hero_data['about_hero_background_type'] : 'image';
        $background_video = isset($hero_data['about_hero_background_video']) ? $hero_data['about_hero_background_video'] : '';
        $background_image = isset($hero_data['about_hero_background_image']) ? $hero_data['about_hero_background_image'] : '';
        $hero_images = isset($hero_data['about_hero_images']) ? $hero_data['about_hero_images'] : array();
        $mobile_image = isset($hero_data['about_mobile_image']) ? $hero_data['about_mobile_image'] : '';
        $subtitle = isset($hero_data['about_hero_subtitle']) ? $hero_data['about_hero_subtitle'] : '';
        $heading = isset($hero_data['about_hero_heading']) ? $hero_data['about_hero_heading'] : '';
        $description = isset($hero_data['about_hero_description']) ? $hero_data['about_hero_description'] : '';
    }
    // Careers page
    elseif (strpos($template, 'careers') !== false) {
        $hero_data = get_field('hero_section', $post_id ?: get_the_ID());
        $title = isset($hero_data['hero_title']) ? $hero_data['hero_title'] : 'Join Us';
        $hero_images = isset($hero_data['hero_images']) ? $hero_data['hero_images'] : array();
        $subtitle = isset($hero_data['hero_subtitle']) ? $hero_data['hero_subtitle'] : '';
        $description = isset($hero_data['hero_description']) ? $hero_data['hero_description'] : '';
        $button_text = isset($hero_data['hero_button_text']) ? $hero_data['hero_button_text'] : '';
        $button_link = isset($hero_data['hero_button_link']) ? $hero_data['hero_button_link'] : '';
        $background_type = 'image';
        $background_image = '';
        $background_video = '';
        $mobile_image = '';
    }
    // Platform/Solution singles
    elseif (get_post_type() === 'platform' || get_post_type() === 'solution') {
        $title = get_field('hero_title') ?: get_the_title();
        $description = get_field('hero_description') ?: get_the_excerpt();
        $background_type = get_field('hero_background_type') ?: 'image';
        $background_video = get_field('hero_background_video') ?: '';
        $background_image = get_field('hero_background_image') ?: get_field('platform_background_image') ?: '';
        $desktop_image = get_field('hero_image_desktop') ?: '';
        $mobile_image = get_field('hero_image_mobile') ?: '';
        $featured_image = !$desktop_image ? get_the_post_thumbnail_url(get_the_ID(), 'full') : '';
        $subtitle = '';
        $heading = '';
        $hero_images = array();
        $button_text = '';
        $button_link = '';
    }
    // Default fallback
    else {
        $title = get_the_title();
        $description = get_the_excerpt();
        $background_type = 'image';
        $background_image = '';
        $background_video = '';
        $hero_images = array();
        $mobile_image = '';
        $subtitle = '';
        $heading = '';
        $button_text = '';
        $button_link = '';
    }
} else {
    // Use block-specific custom fields
    $title = get_field('custom_title') ?: '';
    $description = get_field('custom_description') ?: '';
    $background_type = get_field('custom_background_type') ?: 'image';
    $background_image = get_field('custom_background_image') ?: '';
    $background_video = get_field('custom_background_video') ?: '';
    $desktop_image = get_field('custom_desktop_image') ?: '';
    $mobile_image = get_field('custom_mobile_image') ?: '';
    $hero_images = get_field('custom_hero_images') ?: array();
    $subtitle = get_field('custom_subtitle') ?: '';
    $heading = get_field('custom_heading') ?: '';
    $button_text = get_field('custom_button_text') ?: '';
    $button_link = get_field('custom_button_link') ?: '';
    $featured_image = '';
}

// Fallback background image
if (!$background_image && $background_type === 'image') {
    $background_image = get_template_directory_uri() . '/images/swoos3.png';
}

// Preview mode check
if ($is_preview && empty($title)) {
    chainthat_block_preview_placeholder(
        'Hero Standard',
        'cover-image',
        'Configure hero section in the block settings. Use Page Fields mode to display existing data.'
    );
    return;
}

// Determine layout type based on available data
$has_carousel = !empty($hero_images) && is_array($hero_images);
$has_content_bottom = !empty($subtitle) || !empty($heading) || !empty($description);
?>

<section id="<?php echo esc_attr($attributes['id']); ?>" class="about-area <?php echo esc_attr($attributes['class']); ?>">
    <?php if ($background_type === 'video' && $background_video): ?>
        <video class="about-background-video" autoplay muted loop playsinline>
            <source src="<?php echo esc_url($background_video); ?>" type="video/mp4">
        </video>
        <?php if ($background_image): ?>
            <div class="about-background-image" style="background-image: url('<?php echo esc_url($background_image); ?>');"></div>
        <?php endif; ?>
    <?php elseif ($background_image): ?>
        <div class="about-background-image" style="background-image: url('<?php echo esc_url($background_image); ?>');"></div>
    <?php endif; ?>
    
    <div class="container">
        <div class="about-title text-center wow fadeInLeft">
            <h2><?php echo esc_html($title); ?></h2>
        </div>
    </div>
    
    <?php if ($has_carousel): ?>
    <div class="about-main wow fadeInUp">
        <div class="main-content5">
            <div id="owl-csel5" class="owl-carousel owl-theme">
                <?php foreach ($hero_images as $index => $image): 
                    $img_data = isset($image['about_hero_image']) ? $image['about_hero_image'] : (isset($image['image']) ? $image['image'] : $image);
                    $image_url = is_array($img_data) ? $img_data['url'] : $img_data;
                    $image_alt = is_array($img_data) && isset($img_data['alt']) ? $img_data['alt'] : '';
                    $animation_class = ($index % 2 == 0) ? 'wow fadeInRight' : 'wow fadeInLeft';
                    ?>
                    <div class="about-item <?php echo $animation_class; ?>">
                        <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($image_alt); ?>">
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
    <?php endif; ?>
    
    <?php if ($mobile_image): ?>
    <div class="about-mobil d-block d-lg-none" aria-hidden="true">
        <img src="<?php echo esc_url($mobile_image); ?>" alt="" role="presentation">
    </div>
    <?php endif; ?>
    
    <?php if ($has_content_bottom): ?>
    <div class="container">
        <div class="about-cnt">
            <?php if ($subtitle): ?>
                <span class="wow fadeInUp"><?php echo esc_html($subtitle); ?></span>
            <?php endif; ?>
            <?php if ($heading): ?>
                <h2 class="wow fadeInLeft"><?php echo esc_html($heading); ?></h2>
            <?php endif; ?>
            <?php if ($description): ?>
                <p class="wow fadeInRight"><?php echo esc_html($description); ?></p>
            <?php endif; ?>
        </div>
    </div>
    <?php elseif (isset($description) && $description && !$has_content_bottom): ?>
    <div class="container">
        <div class="careers-btm">
            <h4 class="wow fadeInLeft"><?php echo esc_html($subtitle ?: $heading); ?></h4>
            <p class="wow fadeInRight"><?php echo esc_html($description); ?></p>
            <?php if (isset($button_text) && $button_text): ?>
            <div class="btn-all careers-btn wow fadeInUp">
                <a href="<?php echo esc_url($button_link ?: '#'); ?>"><?php echo esc_html($button_text); ?></a>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
    
    <?php if ($background_image && strpos($background_image, 'swoos3') !== false): ?>
    <div class="about-swoos-item wow fadeInUp" aria-hidden="true">
        <img src="<?php echo get_template_directory_uri(); ?>/images/swoos3.png" alt="" role="presentation">
    </div>
    <?php endif; ?>
</section>


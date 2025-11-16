<?php
/**
 * Section Container Block Template
 * 
 * Wrapper block with section controls
 * Provides background options, padding controls, and visibility toggles
 * Foundation wrapper for all other blocks
 * 
 * @param array $block The block settings and attributes
 * @param string $content The block inner HTML (inner blocks)
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
$id = 'section-' . $block['id'];
if (!empty($block['anchor'])) {
    $id = $block['anchor'];
}

// Get custom section ID if set
$custom_id = get_field('section_id');
if ($custom_id) {
    $id = $custom_id;
}

// Block classes
$className = 'section-container-block';
if (!empty($block['className'])) {
    $className .= ' ' . $block['className'];
}

// Get section settings
$section_class = get_field('section_class') ?: '';
$show_section = get_field('show_section');
$show_section = ($show_section !== false); // Default to true if not set

// Background settings
$background_type = get_field('background_type') ?: 'none';
$background_color = get_field('background_color') ?: '';
$background_image = get_field('background_image') ?: '';
$background_video = get_field('background_video') ?: '';
$background_overlay = get_field('background_overlay') !== false;
$background_overlay_opacity = get_field('background_overlay_opacity') ?: 50;

// Padding settings
$padding_top = get_field('padding_top') ?: 80;
$padding_bottom = get_field('padding_bottom') ?: 80;

// If section is hidden, don't render
if (!$show_section && !$is_preview) {
    return;
}

// Build inline styles
$styles = array();
$styles[] = 'padding-top: ' . intval($padding_top) . 'px';
$styles[] = 'padding-bottom: ' . intval($padding_bottom) . 'px';

if ($background_type === 'color' && $background_color) {
    $styles[] = 'background-color: ' . esc_attr($background_color);
}

if ($background_type === 'image' && $background_image) {
    if (is_array($background_image) && isset($background_image['url'])) {
        $background_image = $background_image['url'];
    }
    $styles[] = 'background-image: url(' . esc_url($background_image) . ')';
    $styles[] = 'background-size: cover';
    $styles[] = 'background-position: center';
    $styles[] = 'background-repeat: no-repeat';
}

$style_attr = !empty($styles) ? 'style="' . implode('; ', $styles) . '"' : '';

// Add background type class
if ($background_type !== 'none') {
    $className .= ' has-background has-' . $background_type . '-background';
}

// Add section class if provided
if ($section_class) {
    $className .= ' ' . $section_class;
}

// Preview mode indicator
if ($is_preview && !$show_section) {
    $className .= ' section-hidden-preview';
}

// Generate unique video ID for background video
$video_id = 'bg-video-' . $block['id'];
?>

<!-- section-container -->
<section id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>" <?php echo $style_attr; ?>>
    <?php if ($background_type === 'video' && $background_video): ?>
    <!-- Background Video -->
    <div class="section-background-video">
        <?php 
        if (is_array($background_video) && isset($background_video['url'])) {
            $background_video = $background_video['url'];
        }
        ?>
        <video id="<?php echo esc_attr($video_id); ?>" autoplay muted loop playsinline class="background-video">
            <source src="<?php echo esc_url($background_video); ?>" type="video/mp4">
        </video>
    </div>
    <?php endif; ?>
    
    <?php if ($background_overlay && ($background_type === 'image' || $background_type === 'video')): ?>
    <!-- Background Overlay -->
    <div class="section-background-overlay" style="opacity: <?php echo ($background_overlay_opacity / 100); ?>;"></div>
    <?php endif; ?>
    
    <!-- Section Content -->
    <div class="section-content">
        <?php if ($is_preview && !$show_section): ?>
        <div class="section-hidden-notice">
            <p><strong>⚠️ Section Hidden:</strong> This section is currently hidden. Toggle "Show Section" to display it.</p>
        </div>
        <?php endif; ?>
        
        <?php if (empty($content)): ?>
            <?php if ($is_preview): ?>
            <div class="section-placeholder">
                <div class="section-placeholder-icon">
                    <i class="dashicons dashicons-welcome-widgets-menus"></i>
                </div>
                <h3>Section Container</h3>
                <p>Add blocks inside this container. This wrapper provides background options, padding controls, and visibility settings.</p>
            </div>
            <?php endif; ?>
            
            <!-- Inner Blocks Template -->
            <InnerBlocks />
        <?php else: ?>
            <?php echo $content; ?>
        <?php endif; ?>
    </div>
</section>

<?php if (!$is_preview): ?>
<style>
/* Section Container Styles */
.section-container-block {
    position: relative;
    overflow: hidden;
}

.section-background-video {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 0;
    overflow: hidden;
}

.background-video {
    position: absolute;
    top: 50%;
    left: 50%;
    min-width: 100%;
    min-height: 100%;
    width: auto;
    height: auto;
    transform: translate(-50%, -50%);
    object-fit: cover;
}

.section-background-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: #000;
    z-index: 1;
}

.section-content {
    position: relative;
    z-index: 2;
}

/* Preview Mode Styles */
.section-hidden-preview {
    border: 2px dashed #ccc;
    background: #f9f9f9 !important;
}

.section-hidden-notice {
    padding: 20px;
    background: #fff3cd;
    border: 2px solid #ffc107;
    border-radius: 4px;
    margin-bottom: 20px;
}

.section-hidden-notice p {
    margin: 0;
    color: #856404;
}

.section-placeholder {
    text-align: center;
    padding: 60px 20px;
    background: #f8f9fa;
    border: 2px dashed #dee2e6;
    border-radius: 8px;
}

.section-placeholder-icon {
    font-size: 48px;
    color: #6c757d;
    margin-bottom: 20px;
}

.section-placeholder-icon i {
    width: 48px;
    height: 48px;
    font-size: 48px;
}

.section-placeholder h3 {
    margin: 0 0 10px;
    color: #495057;
    font-size: 1.5rem;
}

.section-placeholder p {
    margin: 0;
    color: #6c757d;
    max-width: 600px;
    margin: 0 auto;
}

@media (max-width: 991px) {
    .section-container-block {
        padding-top: 60px !important;
        padding-bottom: 60px !important;
    }
}

@media (max-width: 767px) {
    .section-container-block {
        padding-top: 40px !important;
        padding-bottom: 40px !important;
    }
}
</style>
<?php endif; ?>



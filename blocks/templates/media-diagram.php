<?php
/**
 * Diagram Block Template
 * 
 * Displays diagram images with optional text overlay
 * Used primarily in solution single pages
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
$attributes = chainthat_get_block_attributes($block, 'diagram-block');

// Get data source
$data_source = get_field('data_source') ?: 'page_fields';

// Fetch data based on source
if ($data_source === 'page_fields') {
    // Use existing page-level ACF fields
    $diagram_section = get_field('diagram_section', $post_id ?: get_the_ID());
    $diagram_image = isset($diagram_section['diagram_image']) ? $diagram_section['diagram_image'] : '';
    $diagram_text = isset($diagram_section['diagram_text']) ? $diagram_section['diagram_text'] : '';
    $background_color = isset($diagram_section['background_color']) ? $diagram_section['background_color'] : '#f8f9fa';
    $show_text_overlay = isset($diagram_section['show_text_overlay']) ? $diagram_section['show_text_overlay'] : false;
} else {
    // Use block-specific custom fields
    $diagram_image = get_field('custom_diagram_image') ?: '';
    $diagram_text = get_field('custom_diagram_text') ?: '';
    $background_color = get_field('custom_background_color') ?: '#f8f9fa';
    $show_text_overlay = get_field('custom_show_text_overlay') !== false;
}

// Handle ACF image array format
if (is_array($diagram_image) && isset($diagram_image['url'])) {
    $diagram_image = $diagram_image['url'];
}

// Preview mode check
if ($is_preview && empty($diagram_image)) {
    chainthat_block_preview_placeholder(
        'Diagram',
        'chart-area',
        'Add a diagram image to display. Optionally add descriptive text.'
    );
    return;
}

if (empty($diagram_image)) {
    return; // No diagram to display
}
?>

<!-- diagram-section -->
<section id="<?php echo esc_attr($attributes['id']); ?>" 
         class="diagram-area <?php echo esc_attr($attributes['class']); ?>"
         style="background-color: <?php echo esc_attr($background_color); ?>;">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="diagram-wrapper">
                    <?php if ($show_text_overlay && $diagram_text): ?>
                    <!-- Diagram with Text Overlay -->
                    <div class="diagram-with-overlay">
                        <div class="diagram-image-container wow fadeInUp">
                            <img src="<?php echo esc_url($diagram_image); ?>" alt="Diagram" class="diagram-image">
                            <div class="diagram-overlay">
                                <div class="diagram-text">
                                    <?php echo wp_kses_post($diagram_text); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <?php else: ?>
                    <!-- Diagram Only or Diagram with Separate Text -->
                    <div class="diagram-standard">
                        <div class="diagram-image-container wow fadeInUp">
                            <img src="<?php echo esc_url($diagram_image); ?>" alt="Diagram" class="diagram-image">
                        </div>
                        
                        <?php if ($diagram_text): ?>
                        <div class="diagram-description wow fadeInUp" data-wow-delay="0.2s">
                            <?php echo wp_kses_post($diagram_text); ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php if (!$is_preview): ?>
<style>
/* Diagram Block Styles */
.diagram-area {
    padding: 80px 0;
}

.diagram-wrapper {
    max-width: 1200px;
    margin: 0 auto;
}

.diagram-image-container {
    position: relative;
    width: 100%;
    margin: 0 auto;
}

.diagram-image {
    width: 100%;
    height: auto;
    display: block;
    border-radius: 8px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

/* Diagram with Overlay Styles */
.diagram-with-overlay {
    position: relative;
}

.diagram-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px;
    pointer-events: none;
}

.diagram-text {
    background: rgba(255, 255, 255, 0.95);
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    max-width: 600px;
    pointer-events: all;
}

.diagram-text p {
    margin: 0 0 15px;
    line-height: 1.8;
}

.diagram-text p:last-child {
    margin-bottom: 0;
}

/* Standard Diagram Styles */
.diagram-description {
    margin-top: 40px;
    text-align: center;
    max-width: 800px;
    margin-left: auto;
    margin-right: auto;
}

.diagram-description p {
    font-size: 16px;
    line-height: 1.8;
    color: #333;
    margin-bottom: 15px;
}

.diagram-description p:last-child {
    margin-bottom: 0;
}

/* Responsive Styles */
@media (max-width: 991px) {
    .diagram-area {
        padding: 60px 0;
    }
    
    .diagram-overlay {
        padding: 20px;
    }
    
    .diagram-text {
        padding: 20px;
    }
    
    .diagram-description {
        margin-top: 30px;
    }
}

@media (max-width: 767px) {
    .diagram-area {
        padding: 40px 0;
    }
    
    .diagram-overlay {
        position: static;
        padding: 0;
        margin-top: 20px;
    }
    
    .diagram-text {
        background: #fff;
        padding: 20px;
    }
    
    .diagram-description {
        margin-top: 20px;
        font-size: 14px;
    }
}
</style>
<?php endif; ?>



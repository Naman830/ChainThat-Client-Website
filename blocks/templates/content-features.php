<?php
/**
 * Features Cards Block Template
 * 
 * Displays feature/value cards with icons and descriptions
 * Used for careers values, platform features, etc.
 * Responsive grid layout
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
$attributes = chainthat_get_block_attributes($block, 'features-block');

// Get data source
$data_source = get_field('data_source') ?: 'page_fields';

// Fetch data based on source
if ($data_source === 'page_fields') {
    // Use existing page-level ACF fields
    $section_title = get_field('features_title', $post_id ?: get_the_ID()) ?: get_field('values_title', $post_id ?: get_the_ID()) ?: 'Our Features';
    $section_description = get_field('features_description', $post_id ?: get_the_ID()) ?: get_field('values_description', $post_id ?: get_the_ID()) ?: '';
    $feature_items = get_field('features_items', $post_id ?: get_the_ID()) ?: get_field('values_items', $post_id ?: get_the_ID()) ?: array();
} else {
    // Use block-specific custom fields
    $section_title = get_field('custom_section_title') ?: 'Our Features';
    $section_description = get_field('custom_section_description') ?: '';
    $feature_items = get_field('custom_feature_items') ?: array();
}

// Preview mode check
if ($is_preview && empty($feature_items)) {
    chainthat_block_preview_placeholder(
        'Features Cards',
        'screenoptions',
        'Add feature cards to display. Perfect for values, features, and highlights.'
    );
    return;
}

if (empty($feature_items)) {
    return; // No features to display
}

$animation_classes = ['fadeInLeft', 'fadeInUp', 'fadeInRight'];
?>

<!-- features/values section -->
<section id="<?php echo esc_attr($attributes['id']); ?>" class="features-area values-area <?php echo esc_attr($attributes['class']); ?>">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="features-title text-center">
                    <?php if ($section_title): ?>
                    <h2 class="wow fadeInLeft"><?php echo esc_html($section_title); ?></h2>
                    <?php endif; ?>
                    <?php if ($section_description): ?>
                    <p class="wow fadeInRight"><?php echo esc_html($section_description); ?></p>
                    <?php endif; ?>
                </div>

                <div class="features-main">
                    <div class="row">
                        <?php 
                        $item_count = count($feature_items);
                        
                        // Determine column class based on item count
                        if ($item_count <= 2) {
                            $col_class = 'col-lg-6';
                        } elseif ($item_count == 3) {
                            $col_class = 'col-lg-4';
                        } elseif ($item_count == 4) {
                            $col_class = 'col-lg-3';
                        } else {
                            // For 5+ items, use flexible grid
                            $col_class = 'col-lg-4 col-xl-3';
                        }
                        
                        foreach ($feature_items as $index => $item): 
                            $icon = isset($item['feature_icon']) ? $item['feature_icon'] : (isset($item['value_icon']) ? $item['value_icon'] : '');
                            $title = isset($item['feature_title']) ? $item['feature_title'] : (isset($item['value_title']) ? $item['value_title'] : '');
                            $description = isset($item['feature_description']) ? $item['feature_description'] : (isset($item['value_description']) ? $item['value_description'] : '');
                            
                            $animation_class = $animation_classes[$index % 3];
                            ?>
                            <div class="<?php echo esc_attr($col_class); ?> col-md-6 mb-4">
                                <div class="features-item h-100 wow <?php echo esc_attr($animation_class); ?>">
                                    <?php if ($icon): ?>
                                    <div class="features-icon">
                                        <img src="<?php echo esc_url($icon); ?>" alt="<?php echo esc_attr($title); ?>">
                                    </div>
                                    <?php endif; ?>
                                    <div class="features-content">
                                        <?php if ($title): ?>
                                        <h4><?php echo esc_html($title); ?></h4>
                                        <?php endif; ?>
                                        <?php if ($description): ?>
                                        <p><?php echo esc_html($description); ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



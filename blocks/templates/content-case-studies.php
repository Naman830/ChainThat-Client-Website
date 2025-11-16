<?php
/**
 * Case Studies Block Template
 * 
 * Displays case studies or featured content with alternating image/text layout
 * Desktop: Side-by-side alternating layout
 * Mobile: Stacked cards
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
$attributes = chainthat_get_block_attributes($block, 'case-studies-block');

// Get data source
$data_source = get_field('data_source') ?: 'page_fields';

// Fetch data based on source
if ($data_source === 'page_fields') {
    // Use existing page-level ACF fields
    $section_title = get_field('case_studies_title', $post_id ?: get_the_ID()) ?: 'Working with ChainThat';
    $section_description = get_field('case_studies_description', $post_id ?: get_the_ID()) ?: '';
    $featured_items = get_field('case_studies_items', $post_id ?: get_the_ID()) ?: array();
} else {
    // Use block-specific custom fields
    $section_title = get_field('custom_section_title') ?: 'Working with ChainThat';
    $section_description = get_field('custom_section_description') ?: '';
    $display_mode = get_field('custom_display_mode') ?: 'manual';
    
    if ($display_mode === 'auto') {
        // Auto-query solutions
        $query_args = array(
            'post_type' => 'solution',
            'posts_per_page' => get_field('custom_max_items') ?: 3,
            'post_status' => 'publish',
            'orderby' => 'menu_order',
            'order' => 'ASC'
        );
        
        $solutions_query = new WP_Query($query_args);
        $featured_items = array();
        
        if ($solutions_query->have_posts()) {
            while ($solutions_query->have_posts()) {
                $solutions_query->the_post();
                $featured_items[] = array(
                    'title' => get_the_title(),
                    'description' => get_the_excerpt(),
                    'image' => get_the_post_thumbnail_url(get_the_ID(), 'large'),
                    'link' => get_permalink()
                );
            }
            wp_reset_postdata();
        }
    } else {
        // Manual mode
        $featured_items = get_field('custom_case_studies_items') ?: array();
    }
}

// Preview mode check
if ($is_preview && empty($featured_items)) {
    chainthat_block_preview_placeholder(
        'Case Studies',
        'portfolio',
        'Add case studies to display. Features alternating image/text layout on desktop.'
    );
    return;
}

if (empty($featured_items)) {
    return; // No case studies to display
}
?>

<!-- case-studies-section -->
<section id="<?php echo esc_attr($attributes['id']); ?>" class="case-studies-area working-area <?php echo esc_attr($attributes['class']); ?>">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <?php if ($section_title || $section_description): ?>
                <div class="case-studies-title text-center">
                    <?php if ($section_title): ?>
                    <h2 class="wow fadeInLeft"><?php echo esc_html($section_title); ?></h2>
                    <?php endif; ?>
                    <?php if ($section_description): ?>
                    <p class="wow fadeInRight"><?php echo esc_html($section_description); ?></p>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <div class="case-studies-main">
                    <?php 
                    foreach ($featured_items as $index => $item):
                        $title = isset($item['title']) ? $item['title'] : (isset($item['case_title']) ? $item['case_title'] : '');
                        $description = isset($item['description']) ? $item['description'] : (isset($item['case_description']) ? $item['case_description'] : '');
                        $image = isset($item['image']) ? $item['image'] : (isset($item['case_image']) ? $item['case_image'] : '');
                        $link = isset($item['link']) ? $item['link'] : (isset($item['case_link']) ? $item['case_link'] : '');
                        
                        // Handle ACF image array format
                        if (is_array($image) && isset($image['url'])) {
                            $image = $image['url'];
                        }
                        
                        // Alternating layout: even items image-left, odd items image-right
                        $is_reverse = ($index % 2 !== 0);
                        $animation_left = $is_reverse ? 'fadeInRight' : 'fadeInLeft';
                        $animation_right = $is_reverse ? 'fadeInLeft' : 'fadeInRight';
                        ?>
                        <div class="working-item <?php echo $is_reverse ? 'working-item-reverse' : ''; ?>">
                            <div class="row align-items-center <?php echo $is_reverse ? 'flex-row-reverse' : ''; ?>">
                                <!-- Image Column -->
                                <div class="col-lg-6 mb-4 mb-lg-0">
                                    <div class="working-img wow <?php echo esc_attr($animation_left); ?>">
                                        <?php if ($image): ?>
                                        <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($title); ?>">
                                        <?php else: ?>
                                        <img src="<?php echo get_template_directory_uri(); ?>/images/working1.png" alt="<?php echo esc_attr($title); ?>">
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <!-- Content Column -->
                                <div class="col-lg-6">
                                    <div class="working-content wow <?php echo esc_attr($animation_right); ?>">
                                        <?php if ($title): ?>
                                        <h3><?php echo esc_html($title); ?></h3>
                                        <?php endif; ?>
                                        <?php if ($description): ?>
                                        <p><?php echo esc_html($description); ?></p>
                                        <?php endif; ?>
                                        <?php if ($link): ?>
                                        <div class="working-link">
                                            <a href="<?php echo esc_url($link); ?>" class="btn-learn-more">
                                                Learn More <i class="fas fa-arrow-right"></i>
                                            </a>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>



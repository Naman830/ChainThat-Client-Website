<?php
/**
 * Benefits Grid Block Template
 * 
 * Displays benefits with icons, titles, and descriptions
 * Includes read more/less functionality for long descriptions
 * Supports both grid and carousel layouts
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
$attributes = chainthat_get_block_attributes($block, 'benefits-block');

// Get data source
$data_source = get_field('data_source') ?: 'page_fields';

// Fetch data based on source
if ($data_source === 'page_fields') {
    // Use existing page-level ACF fields
    $section_tag = get_field('benefit_tag', $post_id ?: get_the_ID()) ?: 'Benefits';
    $section_heading = get_field('benefit_heading', $post_id ?: get_the_ID()) ?: 'Key Benefits';
    $benefit_items = get_field('benefit_items', $post_id ?: get_the_ID()) ?: array();
    $layout = 'carousel';
} else {
    // Use block-specific custom fields
    $section_tag = get_field('custom_section_tag') ?: 'Benefits';
    $section_heading = get_field('custom_section_heading') ?: 'Key Benefits';
    $benefit_items = get_field('custom_benefit_items') ?: array();
    $layout = get_field('custom_layout') ?: 'carousel';
}

// Preview mode check
if ($is_preview && empty($benefit_items)) {
    chainthat_block_preview_placeholder(
        'Benefits Grid',
        'star-filled',
        'Add benefit items to display. Supports grid and carousel layouts with read more/less functionality.'
    );
    return;
}

if (empty($benefit_items)) {
    return; // No benefits to display
}

// Generate unique carousel ID
$carousel_id = chainthat_get_carousel_id('owl-benefit');
?>

<!-- benefit-section -->
<section id="<?php echo esc_attr($attributes['id']); ?>" class="benefit-area <?php echo esc_attr($attributes['class']); ?>">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="benefit-title text-center">
                    <?php if ($section_tag): ?>
                    <span class="wow fadeInLeft"><?php echo esc_html($section_tag); ?></span>
                    <?php endif; ?>
                    <?php if ($section_heading): ?>
                    <h2 class="wow fadeInRight"><?php echo esc_html($section_heading); ?></h2>
                    <?php endif; ?>
                </div>

                <?php if ($layout === 'carousel'): ?>
                <!-- Carousel Layout -->
                <div class="benefit-carousel">
                    <div class="main-content5">
                        <div id="<?php echo esc_attr($carousel_id); ?>" class="owl-carousel owl-theme">
                            <?php foreach ($benefit_items as $item): 
                                $icon = isset($item['benefit_icon']) ? $item['benefit_icon'] : '';
                                $title = isset($item['benefit_title']) ? $item['benefit_title'] : '';
                                $description = isset($item['benefit_description']) ? $item['benefit_description'] : '';
                                
                                // Truncate description to 25 words
                                $short_description = chainthat_truncate_text($description, 25, '');
                                $needs_readmore = str_word_count($description) > 25;
                                ?>
                                <div class="benefit-item">
                                    <?php if ($icon): ?>
                                    <img class="wow fadeInLeft" src="<?php echo esc_url($icon); ?>" alt="<?php echo esc_attr($title); ?>">
                                    <?php endif; ?>
                                    <?php if ($title): ?>
                                    <h4 class="wow fadeInRight"><?php echo esc_html($title); ?></h4>
                                    <?php endif; ?>
                                    <?php if ($description): ?>
                                    <div class="benefit-description">
                                        <p class="benefit-text">
                                            <span class="benefit-short"><?php echo esc_html($short_description); ?></span>
                                            <?php if ($needs_readmore): ?>
                                            <span class="benefit-full" style="display: none;"><?php echo esc_html($description); ?></span>
                                            <?php endif; ?>
                                        </p>
                                        <?php if ($needs_readmore): ?>
                                        <button class="benefit-toggle-btn" onclick="toggleBenefitText(this)" data-expanded="false">read more</button>
                                        <?php endif; ?>
                                    </div>
                                    <?php endif; ?>
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
                
                <?php else: ?>
                <!-- Grid Layout -->
                <div class="benefit-grid">
                    <div class="row">
                        <?php 
                        $item_count = count($benefit_items);
                        $col_class = $item_count <= 2 ? 'col-lg-6' : ($item_count == 3 ? 'col-lg-4' : 'col-lg-3');
                        
                        foreach ($benefit_items as $index => $item): 
                            $icon = isset($item['benefit_icon']) ? $item['benefit_icon'] : '';
                            $title = isset($item['benefit_title']) ? $item['benefit_title'] : '';
                            $description = isset($item['benefit_description']) ? $item['benefit_description'] : '';
                            
                            // Truncate description to 25 words
                            $short_description = chainthat_truncate_text($description, 25, '');
                            $needs_readmore = str_word_count($description) > 25;
                            
                            $animation_classes = ['fadeInLeft', 'fadeInUp', 'fadeInRight'];
                            $animation_class = $animation_classes[$index % 3];
                            ?>
                            <div class="<?php echo esc_attr($col_class); ?> col-md-6 mb-4">
                                <div class="benefit-item h-100 wow <?php echo esc_attr($animation_class); ?>">
                                    <?php if ($icon): ?>
                                    <img src="<?php echo esc_url($icon); ?>" alt="<?php echo esc_attr($title); ?>">
                                    <?php endif; ?>
                                    <?php if ($title): ?>
                                    <h4><?php echo esc_html($title); ?></h4>
                                    <?php endif; ?>
                                    <?php if ($description): ?>
                                    <div class="benefit-description">
                                        <p class="benefit-text">
                                            <span class="benefit-short"><?php echo esc_html($short_description); ?></span>
                                            <?php if ($needs_readmore): ?>
                                            <span class="benefit-full" style="display: none;"><?php echo esc_html($description); ?></span>
                                            <?php endif; ?>
                                        </p>
                                        <?php if ($needs_readmore): ?>
                                        <button class="benefit-toggle-btn" onclick="toggleBenefitText(this)" data-expanded="false">read more</button>
                                        <?php endif; ?>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php if (!$is_preview): ?>
<script>
<?php if ($layout === 'carousel'): ?>
jQuery(document).ready(function($) {
    if ($('#<?php echo esc_js($carousel_id); ?>').length) {
        $('#<?php echo esc_js($carousel_id); ?>').owlCarousel({
            loop: true,
            margin: 30,
            nav: true,
            dots: true,
            autoplay: true,
            autoplayTimeout: 4000,
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
<?php endif; ?>

// Toggle benefit text function (if not already defined globally)
if (typeof window.toggleBenefitText === 'undefined') {
    window.toggleBenefitText = function(button) {
        const container = button.closest('.benefit-description');
        const shortText = container.querySelector('.benefit-short');
        const fullText = container.querySelector('.benefit-full');
        const isExpanded = button.getAttribute('data-expanded') === 'true';
        
        if (isExpanded) {
            // Collapse
            shortText.style.display = 'inline';
            fullText.style.display = 'none';
            button.textContent = 'read more';
            button.setAttribute('data-expanded', 'false');
        } else {
            // Expand
            shortText.style.display = 'none';
            fullText.style.display = 'inline';
            button.textContent = 'read less';
            button.setAttribute('data-expanded', 'true');
        }
    };
}
</script>
<?php endif; ?>



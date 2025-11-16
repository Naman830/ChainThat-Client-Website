<?php
/**
 * Accordion Block Template
 * 
 * Displays accordion content with expand/collapse functionality
 * Used for 5Ps section, FAQs, and expandable content
 * Desktop: Inline expand/collapse
 * Mobile: Native accordion behavior
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
$attributes = chainthat_get_block_attributes($block, 'accordion-block');

// Get data source
$data_source = get_field('data_source') ?: 'page_fields';

// Fetch data based on source
if ($data_source === 'page_fields') {
    // Use existing page-level ACF fields (5Ps section from About page)
    $section_subtitle = get_field('accordion_subtitle', $post_id ?: get_the_ID()) ?: get_field('five_ps_subtitle', $post_id ?: get_the_ID()) ?: '';
    $section_title = get_field('accordion_title', $post_id ?: get_the_ID()) ?: get_field('five_ps_title', $post_id ?: get_the_ID()) ?: 'Our Approach';
    $section_description = get_field('accordion_description', $post_id ?: get_the_ID()) ?: get_field('five_ps_description', $post_id ?: get_the_ID()) ?: '';
    $accordion_items = get_field('accordion_items', $post_id ?: get_the_ID()) ?: get_field('five_ps_items', $post_id ?: get_the_ID()) ?: array();
    $allow_multiple = true;
    $first_open = true;
} else {
    // Use block-specific custom fields
    $section_subtitle = get_field('custom_section_subtitle') ?: '';
    $section_title = get_field('custom_section_title') ?: 'Our Approach';
    $section_description = get_field('custom_section_description') ?: '';
    $accordion_items = get_field('custom_accordion_items') ?: array();
    $allow_multiple = get_field('custom_allow_multiple') !== false;
    $first_open = get_field('custom_first_open') !== false;
}

// Preview mode check
if ($is_preview && empty($accordion_items)) {
    chainthat_block_preview_placeholder(
        'Accordion',
        'list-view',
        'Add accordion items to display. Perfect for 5Ps, FAQs, and expandable content.'
    );
    return;
}

if (empty($accordion_items)) {
    return; // No accordion items to display
}

// Generate unique accordion ID
$accordion_id = 'accordion-' . $block['id'];
?>

<!-- accordion-section (5Ps) -->
<section id="<?php echo esc_attr($attributes['id']); ?>" class="accordion-area five-ps-area <?php echo esc_attr($attributes['class']); ?>">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="accordion-title text-center">
                    <?php if ($section_subtitle): ?>
                    <span class="wow fadeInLeft"><?php echo esc_html($section_subtitle); ?></span>
                    <?php endif; ?>
                    <?php if ($section_title): ?>
                    <h2 class="wow fadeInRight"><?php echo esc_html($section_title); ?></h2>
                    <?php endif; ?>
                    <?php if ($section_description): ?>
                    <p class="wow fadeInUp"><?php echo wp_kses_post($section_description); ?></p>
                    <?php endif; ?>
                </div>

                <div class="accordion-main">
                    <div id="<?php echo esc_attr($accordion_id); ?>" class="accordion-wrapper">
                        <?php 
                        foreach ($accordion_items as $index => $item):
                            $icon = isset($item['accordion_icon']) ? $item['accordion_icon'] : (isset($item['p_icon']) ? $item['p_icon'] : '');
                            $title = isset($item['accordion_title']) ? $item['accordion_title'] : (isset($item['p_title']) ? $item['p_title'] : '');
                            $description = isset($item['accordion_description']) ? $item['accordion_description'] : (isset($item['p_description']) ? $item['p_description'] : '');
                            
                            // Handle ACF image array format
                            if (is_array($icon) && isset($icon['url'])) {
                                $icon = $icon['url'];
                            }
                            
                            $item_id = $accordion_id . '-item-' . $index;
                            $is_first = ($index === 0 && $first_open);
                            $animation_classes = ['fadeInLeft', 'fadeInUp', 'fadeInRight'];
                            $animation_class = $animation_classes[$index % 3];
                            ?>
                            <div class="accordion-item wow <?php echo esc_attr($animation_class); ?>" data-index="<?php echo esc_attr($index); ?>">
                                <div class="accordion-header <?php echo $is_first ? 'active' : ''; ?>" 
                                     id="heading-<?php echo esc_attr($item_id); ?>"
                                     data-toggle="collapse" 
                                     data-target="#collapse-<?php echo esc_attr($item_id); ?>"
                                     aria-expanded="<?php echo $is_first ? 'true' : 'false'; ?>"
                                     aria-controls="collapse-<?php echo esc_attr($item_id); ?>"
                                     onclick="toggleAccordionItem(this, '<?php echo esc_js($accordion_id); ?>', <?php echo $allow_multiple ? 'true' : 'false'; ?>)">
                                    <div class="accordion-header-content">
                                        <?php if ($icon): ?>
                                        <div class="accordion-icon">
                                            <img src="<?php echo esc_url($icon); ?>" alt="<?php echo esc_attr($title); ?>">
                                        </div>
                                        <?php endif; ?>
                                        <h4><?php echo esc_html($title); ?></h4>
                                        <div class="accordion-toggle">
                                            <i class="fas fa-chevron-down"></i>
                                        </div>
                                    </div>
                                </div>
                                
                                <div id="collapse-<?php echo esc_attr($item_id); ?>" 
                                     class="accordion-collapse <?php echo $is_first ? 'show' : ''; ?>"
                                     aria-labelledby="heading-<?php echo esc_attr($item_id); ?>">
                                    <div class="accordion-body">
                                        <?php if ($description): ?>
                                        <p><?php echo wp_kses_post($description); ?></p>
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

<?php if (!$is_preview): ?>
<style>
/* Accordion Styles */
.accordion-item {
    margin-bottom: 20px;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.accordion-header {
    cursor: pointer;
    padding: 20px;
    background: #f8f9fa;
    transition: all 0.3s ease;
}

.accordion-header:hover {
    background: #e9ecef;
}

.accordion-header.active {
    background: #fff;
}

.accordion-header-content {
    display: flex;
    align-items: center;
    gap: 15px;
}

.accordion-icon {
    flex-shrink: 0;
    width: 50px;
    height: 50px;
}

.accordion-icon img {
    width: 100%;
    height: 100%;
    object-fit: contain;
}

.accordion-header h4 {
    flex: 1;
    margin: 0;
    font-size: 1.25rem;
    font-weight: 600;
}

.accordion-toggle {
    flex-shrink: 0;
    transition: transform 0.3s ease;
}

.accordion-header.active .accordion-toggle {
    transform: rotate(180deg);
}

.accordion-collapse {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease;
}

.accordion-collapse.show {
    max-height: 1000px;
}

.accordion-body {
    padding: 20px;
    background: #fff;
}

@media (max-width: 767px) {
    .accordion-header-content {
        gap: 10px;
    }
    
    .accordion-icon {
        width: 40px;
        height: 40px;
    }
    
    .accordion-header h4 {
        font-size: 1rem;
    }
}
</style>

<script>
// Toggle accordion item function (if not already defined globally)
if (typeof window.toggleAccordionItem === 'undefined') {
    window.toggleAccordionItem = function(header, accordionId, allowMultiple) {
        const item = header.closest('.accordion-item');
        const collapse = item.querySelector('.accordion-collapse');
        const isActive = header.classList.contains('active');
        
        if (!allowMultiple) {
            // Close all other items in this accordion
            const accordion = document.getElementById(accordionId);
            const allHeaders = accordion.querySelectorAll('.accordion-header');
            const allCollapses = accordion.querySelectorAll('.accordion-collapse');
            
            allHeaders.forEach(h => {
                if (h !== header) {
                    h.classList.remove('active');
                    h.setAttribute('aria-expanded', 'false');
                }
            });
            
            allCollapses.forEach(c => {
                if (c !== collapse) {
                    c.classList.remove('show');
                }
            });
        }
        
        // Toggle current item
        if (isActive) {
            header.classList.remove('active');
            header.setAttribute('aria-expanded', 'false');
            collapse.classList.remove('show');
        } else {
            header.classList.add('active');
            header.setAttribute('aria-expanded', 'true');
            collapse.classList.add('show');
        }
    };
}
</script>
<?php endif; ?>



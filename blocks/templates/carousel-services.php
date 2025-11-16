<?php
/**
 * Services Carousel Block Template
 * 
 * Displays service cards with icons in a carousel format
 * Includes read more/less functionality for descriptions
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
$attributes = chainthat_get_block_attributes($block, 'services-carousel-block');

// Get data source
$data_source = get_field('data_source') ?: 'page_fields';

// Fetch data based on source
if ($data_source === 'page_fields') {
    // Use existing page-level ACF fields
    $service_section = get_field('service_section', $post_id ?: get_the_ID());
    $section_title = isset($service_section['service_title']) ? $service_section['service_title'] : 'Why ChainThat?';
    $section_description = isset($service_section['service_description']) ? $service_section['service_description'] : '';
    $service_cards = isset($service_section['service_cards']) ? $service_section['service_cards'] : array();
    $button_text = isset($service_section['service_button_text']) ? $service_section['service_button_text'] : '';
    $button_link = isset($service_section['service_button_link']) ? $service_section['service_button_link'] : '';
} else {
    // Use block-specific custom fields
    $section_title = get_field('custom_section_title') ?: 'Why ChainThat?';
    $section_description = get_field('custom_section_description') ?: '';
    $service_cards = get_field('custom_service_cards') ?: array();
    $button_text = get_field('custom_button_text') ?: '';
    $button_link = get_field('custom_button_link') ?: '';
}

// Preview mode check
if ($is_preview && empty($service_cards)) {
    chainthat_block_preview_placeholder(
        'Services Carousel',
        'star-filled',
        'Add service cards to display. Use Page Fields mode to display existing data.'
    );
    return;
}

// Generate unique carousel ID
$carousel_id = chainthat_get_carousel_id('owl-services');
?>

<!-- service-section -->
<section id="<?php echo esc_attr($attributes['id']); ?>" class="service-area <?php echo esc_attr($attributes['class']); ?>">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="service-title">
                    <?php if ($section_title): ?>
                    <h2 class="wow fadeInLeft"><?php echo esc_html($section_title); ?></h2>
                    <?php endif; ?>
                    <?php if ($section_description): ?>
                    <p class="wow fadeInRight"><?php echo esc_html($section_description); ?></p>
                    <?php endif; ?>
                </div>
                
                <!-- Services Carousel -->
                <div class="service-carousel wow fadeInUp">
                    <div class="main-content3">
                        <div id="<?php echo esc_attr($carousel_id); ?>" class="owl-carousel owl-theme">
                            <?php 
                            if ($service_cards && is_array($service_cards) && !empty($service_cards)): 
                                foreach ($service_cards as $card): 
                                    $icon = isset($card['service_icon']) ? $card['service_icon'] : '';
                                    $title = isset($card['service_title']) ? $card['service_title'] : '';
                                    $description = isset($card['service_description']) ? $card['service_description'] : '';
                                    
                                    // Truncate description to 25 words
                                    $short_description = chainthat_truncate_text($description, 25, '');
                                    $needs_readmore = str_word_count($description) > 25;
                                    ?>
                                    <div class="service-item">
                                        <?php if ($icon): ?>
                                        <img class="wow fadeInRight" src="<?php echo esc_url($icon); ?>" alt="<?php echo esc_attr($title); ?>">
                                        <?php endif; ?>
                                        <?php if ($title): ?>
                                        <h4 class="wow fadeInLeft"><?php echo esc_html($title); ?></h4>
                                        <?php endif; ?>
                                        <?php if ($description): ?>
                                        <div class="service-description">
                                            <p class="service-text">
                                                <span class="service-short"><?php echo esc_html($short_description); ?></span>
                                                <?php if ($needs_readmore): ?>
                                                <span class="service-full" style="display: none;"><?php echo esc_html($description); ?></span>
                                                <?php endif; ?>
                                            </p>
                                            <?php if ($needs_readmore): ?>
                                            <button class="service-toggle-btn" onclick="toggleServiceText(this)" data-expanded="false">read more</button>
                                            <?php endif; ?>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach;
                            endif; ?>
                        </div>
                        <div class="owl-theme">
                            <div class="owl-controls">
                                <div class="custom-nav owl-nav"></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <?php if ($button_text && $button_link): ?>
                <div class="btn-all service-btn text-center wow fadeInUp">
                    <a href="<?php echo esc_url($button_link); ?>"><?php echo esc_html($button_text); ?></a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php if (!$is_preview): ?>
<script>
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

// Toggle service text function (if not already defined globally)
if (typeof window.toggleServiceText === 'undefined') {
    window.toggleServiceText = function(button) {
        const container = button.closest('.service-description');
        const shortText = container.querySelector('.service-short');
        const fullText = container.querySelector('.service-full');
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



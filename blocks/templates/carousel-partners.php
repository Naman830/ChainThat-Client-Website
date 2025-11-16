<?php
/**
 * Partners Carousel Block Template
 * 
 * Displays partner logos in a carousel format
 * Queries from Partners post type with relationship field support
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
$attributes = chainthat_get_block_attributes($block, 'partners-carousel-block');

// Get data source
$data_source = get_field('data_source') ?: 'page_fields';

// Fetch data based on source
if ($data_source === 'page_fields') {
    // Use existing page-level ACF fields
    $partners_section = get_field('partners_section', $post_id ?: get_the_ID());
    $section_title = isset($partners_section['partners_title']) ? $partners_section['partners_title'] : 'Trusted by our partners';
    $featured_partners = isset($partners_section['partners_featured']) ? $partners_section['partners_featured'] : array();
} else {
    // Use block-specific custom fields
    $section_title = get_field('custom_section_title') ?: 'Trusted by our partners';
    $featured_partners = get_field('custom_featured_partners') ?: array();
}

// If no featured partners selected, get latest partners automatically
if (empty($featured_partners)) {
    $partners_query = new WP_Query(array(
        'post_type' => 'partners',
        'posts_per_page' => 5,
        'post_status' => 'publish',
        'orderby' => 'menu_order',
        'order' => 'ASC',
        'meta_query' => array(
            array(
                'key' => 'partner_logo',
                'compare' => 'EXISTS'
            )
        )
    ));
    
    if ($partners_query->have_posts()) {
        $featured_partners = $partners_query->posts;
        wp_reset_postdata();
    }
}

// Preview mode check
if ($is_preview && empty($featured_partners)) {
    chainthat_block_preview_placeholder(
        'Partners Carousel',
        'groups',
        'Add partner logos to display. Use Page Fields mode to display existing data.'
    );
    return;
}

// Generate unique carousel ID
$carousel_id = chainthat_get_carousel_id('owl-partners');
?>

<!-- partners-section -->
<section id="<?php echo esc_attr($attributes['id']); ?>" class="partners-area <?php echo esc_attr($attributes['class']); ?>">
    <div class="container partners-container">
        <div class="row">
            <div class="col-12">
                <div class="partners-title wow fadeInLeft">
                    <h2><?php echo esc_html($section_title); ?></h2>
                </div>
                
                <div class="partners-mobil">
                    <div class="main-content2">
                        <div id="<?php echo esc_attr($carousel_id); ?>" class="owl-carousel owl-theme">
                            <?php 
                            if ($featured_partners && is_array($featured_partners) && !empty($featured_partners)):
                                foreach ($featured_partners as $index => $partner): 
                                    // Handle both post object and post ID
                                    $partner_id = is_object($partner) ? $partner->ID : $partner;
                                    
                                    $animation_class = ($index % 2 == 0) ? 'fadeInRight' : 'fadeInLeft';
                                    $item_class = 'partners-item' . ($index + 1);
                                    $partner_logo = get_field('partner_logo', $partner_id);
                                    $partner_alt = get_field('partner_alt_text', $partner_id) ?: get_the_title($partner_id);
                                    $partner_website = get_field('partner_website', $partner_id);
                                    
                                    if (!$partner_logo) continue;
                                    ?>
                                    <div class="partners-slide">
                                        <div class="partners-item <?php echo $item_class; ?> wow <?php echo $animation_class; ?>">
                                            <?php if ($partner_website): ?>
                                                <a href="<?php echo esc_url($partner_website); ?>" target="_blank" rel="noopener">
                                                    <img src="<?php echo esc_url($partner_logo); ?>" alt="<?php echo esc_attr($partner_alt); ?>">
                                                </a>
                                            <?php else: ?>
                                                <img src="<?php echo esc_url($partner_logo); ?>" alt="<?php echo esc_attr($partner_alt); ?>">
                                            <?php endif; ?>
                                        </div>
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
                600: {
                    items: 3
                },
                1000: {
                    items: 5
                }
            }
        });
    }
});
</script>
<?php endif; ?>



<?php
/**
 * Partners Grid Block Template
 * 
 * Displays partners in grid with category filtering tabs
 * Special layout patterns for visual interest
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
$attributes = chainthat_get_block_attributes($block, 'partners-grid-block');

// Get data source
$data_source = get_field('data_source') ?: 'page_fields';

// Fetch data based on source
if ($data_source === 'page_fields') {
    // Use existing page-level ACF fields
    $page_title = get_field('page_title', $post_id ?: get_the_ID()) ?: get_the_title();
    $page_description = get_field('page_description', $post_id ?: get_the_ID()) ?: '';
    $show_category_tabs = true;
    $default_view = 'all';
} else {
    // Use block-specific custom fields
    $page_title = get_field('custom_page_title') ?: get_the_title();
    $page_description = get_field('custom_page_description') ?: '';
    $show_category_tabs = get_field('custom_show_category_tabs') !== false;
    $default_view = get_field('custom_default_view') ?: 'all';
}

// Query partners
$partners_query = new WP_Query(array(
    'post_type' => 'partners',
    'posts_per_page' => -1,
    'post_status' => 'publish',
    'orderby' => 'menu_order',
    'order' => 'ASC'
));

// Get categories
$categories = get_terms(array(
    'taxonomy' => 'partner_category',
    'hide_empty' => true,
    'orderby' => 'name',
    'order' => 'ASC'
));

// Preview mode check
if ($is_preview && !$partners_query->have_posts()) {
    chainthat_block_preview_placeholder(
        'Partners Grid',
        'businessman',
        'Create Partner posts to display. Supports category filtering tabs.'
    );
    return;
}

if (!$partners_query->have_posts()) {
    return; // No partners to display
}

// Generate unique ID
$grid_id = 'partners-grid-' . $block['id'];
?>

<!-- partners-grid-section -->
<section id="<?php echo esc_attr($attributes['id']); ?>" class="partners-grid-area <?php echo esc_attr($attributes['class']); ?>">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="partners-grid-header text-center">
                    <?php if ($page_title): ?>
                    <h1 class="wow fadeInLeft"><?php echo esc_html($page_title); ?></h1>
                    <?php endif; ?>
                    <?php if ($page_description): ?>
                    <p class="wow fadeInRight"><?php echo wp_kses_post($page_description); ?></p>
                    <?php endif; ?>
                </div>

                <?php if ($show_category_tabs && !empty($categories) && !is_wp_error($categories)): ?>
                <!-- Category Filter Tabs -->
                <div class="partners-filter wow fadeInUp">
                    <ul class="filter-tabs" role="tablist">
                        <li class="active">
                            <button data-filter="all" role="tab" aria-selected="true">All Partners</button>
                        </li>
                        <?php foreach ($categories as $category): ?>
                        <li>
                            <button data-filter="<?php echo esc_attr($category->slug); ?>" role="tab" aria-selected="false">
                                <?php echo esc_html($category->name); ?>
                            </button>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>

                <!-- Partners Grid -->
                <div id="<?php echo esc_attr($grid_id); ?>" class="partners-grid">
                    <?php 
                    $index = 0;
                    // Special grid pattern: 2-3-2-3 for visual interest
                    $pattern = array(2, 3, 2, 3);
                    $pattern_index = 0;
                    $row_count = 0;
                    $col_count = 0;
                    $current_cols = $pattern[0];
                    
                    echo '<div class="row partners-row">';
                    
                    while ($partners_query->have_posts()): 
                        $partners_query->the_post();
                        
                        // Get partner categories
                        $partner_cats = get_the_terms(get_the_ID(), 'partner_category');
                        $cat_classes = '';
                        if ($partner_cats && !is_wp_error($partner_cats)) {
                            foreach ($partner_cats as $cat) {
                                $cat_classes .= ' cat-' . $cat->slug;
                            }
                        }
                        
                        // Get partner data
                        $logo = get_the_post_thumbnail_url(get_the_ID(), 'medium');
                        $website = get_field('partner_website') ?: get_field('website_url');
                        $description = get_field('partner_description') ?: get_the_excerpt();
                        
                        // Calculate column class based on pattern
                        $col_class = ($current_cols == 2) ? 'col-lg-6' : 'col-lg-4';
                        
                        $animation_classes = ['fadeInLeft', 'fadeInUp', 'fadeInRight'];
                        $animation_class = $animation_classes[$index % 3];
                        ?>
                        <div class="<?php echo esc_attr($col_class); ?> col-md-6 mb-4 partner-item <?php echo esc_attr($cat_classes); ?>" data-category="all<?php echo esc_attr($cat_classes); ?>">
                            <div class="partner-card h-100 wow <?php echo esc_attr($animation_class); ?>">
                                <?php if ($logo): ?>
                                <div class="partner-logo">
                                    <img src="<?php echo esc_url($logo); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
                                </div>
                                <?php endif; ?>
                                
                                <div class="partner-info">
                                    <h4><?php echo esc_html(get_the_title()); ?></h4>
                                    
                                    <?php if ($description): ?>
                                    <p><?php echo esc_html(wp_trim_words($description, 20)); ?></p>
                                    <?php endif; ?>
                                    
                                    <?php if ($website): ?>
                                    <div class="partner-link">
                                        <a href="<?php echo esc_url($website); ?>" target="_blank" rel="noopener noreferrer">
                                            Visit Website <i class="fas fa-external-link-alt"></i>
                                        </a>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php 
                        $col_count++;
                        $index++;
                        
                        // Check if we need to start a new row
                        if ($col_count >= $current_cols) {
                            echo '</div><div class="row partners-row">';
                            $col_count = 0;
                            $row_count++;
                            $pattern_index = ($pattern_index + 1) % count($pattern);
                            $current_cols = $pattern[$pattern_index];
                        }
                    endwhile;
                    wp_reset_postdata();
                    
                    echo '</div>'; // Close last row
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php if (!$is_preview): ?>
<style>
/* Partners Grid Styles */
.partners-grid-area {
    padding: 80px 0;
}

.partners-grid-header {
    margin-bottom: 60px;
}

.partners-grid-header h1 {
    margin-bottom: 20px;
}

.partners-filter {
    margin-bottom: 50px;
}

.filter-tabs {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 15px;
    list-style: none;
    padding: 0;
    margin: 0;
}

.filter-tabs li button {
    padding: 12px 30px;
    background: #f8f9fa;
    border: 2px solid transparent;
    border-radius: 50px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.filter-tabs li.active button,
.filter-tabs li button:hover {
    background: #fff;
    border-color: #007bff;
    color: #007bff;
}

.partner-card {
    background: #fff;
    border-radius: 8px;
    padding: 30px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    text-align: center;
}

.partner-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
}

.partner-logo {
    width: 100%;
    height: 120px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 20px;
}

.partner-logo img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}

.partner-info h4 {
    margin: 0 0 15px;
    font-size: 1.25rem;
    font-weight: 600;
}

.partner-info p {
    color: #666;
    font-size: 0.95rem;
    line-height: 1.6;
    margin: 0 0 20px;
}

.partner-link a {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #007bff;
    text-decoration: none;
    font-weight: 600;
    transition: gap 0.3s ease;
}

.partner-link a:hover {
    gap: 12px;
}

.partner-item {
    transition: opacity 0.3s ease;
}

.partner-item.hidden {
    display: none;
}

@media (max-width: 991px) {
    .partners-grid-area {
        padding: 60px 0;
    }
    
    .partners-grid-header {
        margin-bottom: 40px;
    }
    
    .filter-tabs {
        gap: 10px;
    }
    
    .filter-tabs li button {
        padding: 10px 20px;
        font-size: 14px;
    }
}

@media (max-width: 767px) {
    .partners-grid-area {
        padding: 40px 0;
    }
    
    .partner-card {
        padding: 20px;
    }
    
    .filter-tabs {
        flex-direction: column;
        align-items: stretch;
    }
}
</style>

<script>
jQuery(document).ready(function($) {
    // Category filtering
    $('.filter-tabs button').on('click', function() {
        const filter = $(this).data('filter');
        
        // Update active state
        $('.filter-tabs li').removeClass('active');
        $(this).parent('li').addClass('active');
        
        // Filter partners
        if (filter === 'all') {
            $('.partner-item').removeClass('hidden').fadeIn(300);
        } else {
            $('.partner-item').each(function() {
                if ($(this).hasClass('cat-' + filter)) {
                    $(this).removeClass('hidden').fadeIn(300);
                } else {
                    $(this).addClass('hidden').fadeOut(300);
                }
            });
        }
    });
});
</script>
<?php endif; ?>



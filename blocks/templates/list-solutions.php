<?php
/**
 * Solutions Grid Block Template
 * 
 * Displays solutions archive in grid layout
 * Queries solution custom post type
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
$attributes = chainthat_get_block_attributes($block, 'solutions-grid-block');

// Get data source
$data_source = get_field('data_source') ?: 'page_fields';

// Fetch data based on source
if ($data_source === 'page_fields') {
    // Use existing page-level ACF fields
    $archive_title = get_field('archive_title', $post_id ?: get_the_ID()) ?: get_the_title();
    $archive_description = get_field('archive_description', $post_id ?: get_the_ID()) ?: '';
    $hero_image = get_field('hero_image', $post_id ?: get_the_ID()) ?: '';
    $columns = 3;
    $show_excerpt = true;
} else {
    // Use block-specific custom fields
    $archive_title = get_field('custom_archive_title') ?: get_the_title();
    $archive_description = get_field('custom_archive_description') ?: '';
    $hero_image = get_field('custom_hero_image') ?: '';
    $columns = get_field('custom_columns') ?: 3;
    $show_excerpt = get_field('custom_show_excerpt') !== false;
}

// Handle ACF image array format
if (is_array($hero_image) && isset($hero_image['url'])) {
    $hero_image = $hero_image['url'];
}

// Query solutions
$solutions_query = new WP_Query(array(
    'post_type' => 'solution',
    'posts_per_page' => -1,
    'post_status' => 'publish',
    'orderby' => 'menu_order',
    'order' => 'ASC'
));

// Preview mode check
if ($is_preview && !$solutions_query->have_posts()) {
    chainthat_block_preview_placeholder(
        'Solutions Grid',
        'portfolio',
        'Create Solution posts to display. Automatically queries all published solutions.'
    );
    return;
}

if (!$solutions_query->have_posts()) {
    return; // No solutions to display
}

// Calculate column class
$col_class_map = array(
    2 => 'col-lg-6',
    3 => 'col-lg-4',
    4 => 'col-lg-3'
);
$col_class = isset($col_class_map[$columns]) ? $col_class_map[$columns] : 'col-lg-4';

$animation_classes = ['fadeInLeft', 'fadeInUp', 'fadeInRight'];
?>

<!-- solutions-archive-section -->
<section id="<?php echo esc_attr($attributes['id']); ?>" class="solutions-archive-area <?php echo esc_attr($attributes['class']); ?>">
    <?php if ($hero_image): ?>
    <!-- Hero Section -->
    <div class="solutions-hero" style="background-image: url('<?php echo esc_url($hero_image); ?>');">
        <div class="solutions-hero-overlay">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="solutions-hero-content text-center">
                            <?php if ($archive_title): ?>
                            <h1 class="wow fadeInUp"><?php echo esc_html($archive_title); ?></h1>
                            <?php endif; ?>
                            <?php if ($archive_description): ?>
                            <p class="wow fadeInUp" data-wow-delay="0.2s"><?php echo wp_kses_post($archive_description); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php else: ?>
    <!-- Standard Header -->
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="solutions-header text-center">
                    <?php if ($archive_title): ?>
                    <h1 class="wow fadeInLeft"><?php echo esc_html($archive_title); ?></h1>
                    <?php endif; ?>
                    <?php if ($archive_description): ?>
                    <p class="wow fadeInRight"><?php echo wp_kses_post($archive_description); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Solutions Grid -->
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="solutions-grid">
                    <div class="row">
                        <?php 
                        $index = 0;
                        while ($solutions_query->have_posts()): 
                            $solutions_query->the_post();
                            
                            $featured_image = get_the_post_thumbnail_url(get_the_ID(), 'large');
                            $solution_icon = get_field('solution_icon') ?: get_field('icon');
                            $excerpt = $show_excerpt ? get_the_excerpt() : '';
                            
                            $animation_class = $animation_classes[$index % 3];
                            ?>
                            <div class="<?php echo esc_attr($col_class); ?> col-md-6 mb-4">
                                <article class="solution-card h-100 wow <?php echo esc_attr($animation_class); ?>">
                                    <a href="<?php echo esc_url(get_permalink()); ?>" class="solution-link">
                                        <?php if ($featured_image): ?>
                                        <div class="solution-image">
                                            <img src="<?php echo esc_url($featured_image); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
                                            <div class="solution-overlay">
                                                <span class="solution-view-more">View Solution</span>
                                            </div>
                                        </div>
                                        <?php endif; ?>
                                        
                                        <div class="solution-content">
                                            <?php if ($solution_icon): ?>
                                            <div class="solution-icon">
                                                <img src="<?php echo esc_url($solution_icon); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
                                            </div>
                                            <?php endif; ?>
                                            
                                            <h3><?php echo esc_html(get_the_title()); ?></h3>
                                            
                                            <?php if ($excerpt): ?>
                                            <p><?php echo esc_html(wp_trim_words($excerpt, 15)); ?></p>
                                            <?php endif; ?>
                                            
                                            <div class="solution-read-more">
                                                Learn More <i class="fas fa-arrow-right"></i>
                                            </div>
                                        </div>
                                    </a>
                                </article>
                            </div>
                            <?php 
                            $index++;
                        endwhile;
                        wp_reset_postdata();
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php if (!$is_preview): ?>
<style>
/* Solutions Archive Styles */
.solutions-archive-area {
    padding: 80px 0;
}

.solutions-hero {
    position: relative;
    min-height: 400px;
    background-size: cover;
    background-position: center;
    display: flex;
    align-items: center;
    margin-bottom: 80px;
}

.solutions-hero-overlay {
    position: relative;
    width: 100%;
    padding: 100px 0;
    background: rgba(0, 0, 0, 0.5);
}

.solutions-hero-content {
    color: #fff;
}

.solutions-hero-content h1 {
    color: #fff;
    margin-bottom: 20px;
    font-size: 3rem;
}

.solutions-hero-content p {
    font-size: 1.25rem;
    max-width: 800px;
    margin: 0 auto;
}

.solutions-header {
    margin-bottom: 60px;
}

.solutions-header h1 {
    margin-bottom: 20px;
}

.solution-card {
    background: #fff;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.solution-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
}

.solution-link {
    text-decoration: none;
    color: inherit;
    display: block;
    height: 100%;
}

.solution-image {
    position: relative;
    width: 100%;
    height: 250px;
    overflow: hidden;
}

.solution-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.solution-card:hover .solution-image img {
    transform: scale(1.05);
}

.solution-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.6);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.solution-card:hover .solution-overlay {
    opacity: 1;
}

.solution-view-more {
    color: #fff;
    font-weight: 600;
    font-size: 1.1rem;
}

.solution-content {
    padding: 30px;
}

.solution-icon {
    width: 60px;
    height: 60px;
    margin-bottom: 20px;
}

.solution-icon img {
    width: 100%;
    height: 100%;
    object-fit: contain;
}

.solution-content h3 {
    margin: 0 0 15px;
    font-size: 1.5rem;
    font-weight: 600;
    line-height: 1.3;
}

.solution-content p {
    color: #666;
    font-size: 0.95rem;
    line-height: 1.6;
    margin: 0 0 20px;
}

.solution-read-more {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #007bff;
    font-weight: 600;
    transition: gap 0.3s ease;
}

.solution-card:hover .solution-read-more {
    gap: 12px;
}

@media (max-width: 991px) {
    .solutions-archive-area {
        padding: 60px 0;
    }
    
    .solutions-hero {
        min-height: 300px;
        margin-bottom: 60px;
    }
    
    .solutions-hero-content h1 {
        font-size: 2rem;
    }
    
    .solutions-header {
        margin-bottom: 40px;
    }
    
    .solution-image {
        height: 200px;
    }
}

@media (max-width: 767px) {
    .solutions-archive-area {
        padding: 40px 0;
    }
    
    .solutions-hero {
        min-height: 250px;
        margin-bottom: 40px;
    }
    
    .solutions-hero-overlay {
        padding: 60px 0;
    }
    
    .solutions-hero-content h1 {
        font-size: 1.75rem;
    }
    
    .solutions-hero-content p {
        font-size: 1rem;
    }
    
    .solution-content {
        padding: 20px;
    }
}
</style>
<?php endif; ?>



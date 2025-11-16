<?php
/**
 * Reviews Carousel Block Template
 * 
 * Displays testimonials and reviews in a carousel
 * Includes star ratings, company logos, and author information
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
$attributes = chainthat_get_block_attributes($block, 'reviews-carousel-block');

// Get data source
$data_source = get_field('data_source') ?: 'page_fields';

// Fetch data based on source
if ($data_source === 'page_fields') {
    // Use existing page-level ACF fields (no specific page field for reviews, auto-query)
    $display_mode = 'auto';
    $selected_reviews = array();
    $max_items = 3;
} else {
    // Use block-specific custom fields
    $display_mode = get_field('custom_display_mode') ?: 'auto';
    $selected_reviews = get_field('custom_selected_reviews') ?: array();
    $max_items = get_field('custom_max_items') ?: 3;
}

// Get reviews
if ($display_mode === 'manual' && !empty($selected_reviews)) {
    $reviews = $selected_reviews;
} else {
    // Auto mode: query review posts
    $review_posts = new WP_Query(array(
        'post_type' => 'review',
        'posts_per_page' => $max_items,
        'post_status' => 'publish',
        'orderby' => 'menu_order',
        'order' => 'ASC'
    ));
    
    $reviews = array();
    if ($review_posts->have_posts()) {
        while ($review_posts->have_posts()) {
            $review_posts->the_post();
            $reviews[] = get_post();
        }
        wp_reset_postdata();
    }
}

// Preview mode check
if ($is_preview && empty($reviews)) {
    chainthat_block_preview_placeholder(
        'Reviews Carousel',
        'format-quote',
        'Create Review posts to display testimonials. Configure display mode in block settings.'
    );
    return;
}

if (empty($reviews)) {
    return; // No reviews to display
}

// Generate unique carousel ID
$carousel_id = chainthat_get_carousel_id('owl-reviews');
?>

<!-- review-section -->
<section id="<?php echo esc_attr($attributes['id']); ?>" class="review-area <?php echo esc_attr($attributes['class']); ?>">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="review-main">
                    <div class="main-content4">
                        <div id="<?php echo esc_attr($carousel_id); ?>" class="owl-carousel owl-theme">
                            <?php foreach ($reviews as $review_post): 
                                $review_id = is_object($review_post) ? $review_post->ID : $review_post;
                                
                                // Get review fields
                                $author_image = get_field('review_author_image', $review_id);
                                if (is_array($author_image) && isset($author_image['url'])) {
                                    $author_image = $author_image['url'];
                                } elseif (!$author_image) {
                                    $author_image = get_the_post_thumbnail_url($review_id, 'medium');
                                }
                                
                                $star_rating = get_field('review_star_rating', $review_id) ?: 5;
                                $company_logo = get_field('review_company_logo', $review_id);
                                $author_name = get_field('review_author_name', $review_id) ?: get_the_title($review_id);
                                $author_title = get_field('review_author_title', $review_id);
                                $success_story_link = get_field('review_success_story_link', $review_id) ?: get_permalink($review_id);
                                $success_story_text = get_field('review_success_story_text', $review_id) ?: 'Read Success Story';
                                
                                // Get review content
                                $review_content = get_post_field('post_content', $review_id);
                                $review_text = wp_trim_words(wp_strip_all_tags($review_content), 30, '...');
                                
                                // Calculate star display
                                $full_stars = floor($star_rating);
                                $half_star = ($star_rating - $full_stars) >= 0.5;
                                $empty_stars = 5 - $full_stars - ($half_star ? 1 : 0);
                                ?>
                                <div class="review-item">
                                    <div class="review-img wow fadeInRight">
                                        <?php if ($author_image): ?>
                                        <img src="<?php echo esc_url($author_image); ?>" alt="<?php echo esc_attr($author_name); ?>">
                                        <?php else: ?>
                                        <img src="<?php echo get_template_directory_uri(); ?>/images/review1.png" alt="<?php echo esc_attr($author_name); ?>">
                                        <?php endif; ?>
                                    </div>
                                    <p class="wow fadeInLeft"><?php echo esc_html($review_text); ?></p>
                                    <div class="star-img1 wow fadeInRight">
                                        <div class="star-rating" data-rating="<?php echo esc_attr($star_rating); ?>">
                                            <?php
                                            // Display full stars
                                            for ($i = 0; $i < $full_stars; $i++) {
                                                echo '<span class="star star-full">★</span>';
                                            }
                                            // Display half star if needed
                                            if ($half_star) {
                                                echo '<span class="star star-half">★</span>';
                                            }
                                            // Display empty stars
                                            for ($i = 0; $i < $empty_stars; $i++) {
                                                echo '<span class="star star-empty">☆</span>';
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="review-logo">
                                        <?php if ($company_logo): ?>
                                        <img class="wow fadeInLeft" src="<?php echo esc_url($company_logo); ?>" alt="<?php echo esc_attr($author_name); ?> Company Logo">
                                        <?php endif; ?>
                                        <h4 class="wow fadeInRight"><?php echo esc_html($author_name); ?></h4>
                                        <?php if ($author_title): ?>
                                        <h5 class="wow fadeInLeft"><?php echo esc_html($author_title); ?></h5>
                                        <?php endif; ?>
                                    </div>
                                    <?php if ($success_story_link): ?>
                                    <div class="btn-all review-btn wow fadeInUp">
                                        <a href="<?php echo esc_url($success_story_link); ?>"><?php echo esc_html($success_story_text); ?></a>
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
            autoplayTimeout: 5000,
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
</script>
<?php endif; ?>



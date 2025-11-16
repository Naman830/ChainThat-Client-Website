<?php
/**
 * News Carousel Block Template
 * 
 * Displays news and blog posts in grid or carousel
 * Adaptive layout: static grid for ≤3 posts, carousel for >3
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
$attributes = chainthat_get_block_attributes($block, 'news-carousel-block');

// Get data source
$data_source = get_field('data_source') ?: 'page_fields';

// Fetch data based on source
if ($data_source === 'page_fields') {
    // Use existing page-level ACF fields
    $blog_section = get_field('blog_section', $post_id ?: get_the_ID());
    $section_title = isset($blog_section['blog_title']) ? $blog_section['blog_title'] : 'News & Insights';
    $display_mode = isset($blog_section['blog_featured_posts']) && !empty($blog_section['blog_featured_posts']) ? 'featured' : 'latest';
    $featured_posts = isset($blog_section['blog_featured_posts']) ? $blog_section['blog_featured_posts'] : array();
    $max_posts = 6;
    $show_view_all = true;
    $view_all_link = get_post_type_archive_link('news-and-insight');
} else {
    // Use block-specific custom fields
    $section_title = get_field('custom_section_title') ?: 'News & Insights';
    $display_mode = get_field('custom_display_mode') ?: 'latest';
    $featured_posts = get_field('custom_featured_posts') ?: array();
    $max_posts = get_field('custom_max_posts') ?: 6;
    $show_view_all = get_field('custom_show_view_all') ?: true;
    $view_all_link = get_field('custom_view_all_link') ?: get_post_type_archive_link('news-and-insight');
}

// Get posts based on display mode
if ($display_mode === 'featured' && !empty($featured_posts)) {
    $news_posts = $featured_posts;
    $use_acf_posts = true;
} else {
    // Query latest posts
    $news_query = new WP_Query(array(
        'post_type' => 'news-and-insight',
        'posts_per_page' => $max_posts,
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC'
    ));
    
    $news_posts = $news_query->have_posts() ? $news_query : null;
    $use_acf_posts = false;
}

// Preview mode check
if ($is_preview && (($use_acf_posts && empty($news_posts)) || (!$use_acf_posts && (!$news_posts || !$news_posts->have_posts())))) {
    chainthat_block_preview_placeholder(
        'News Carousel',
        'media-document',
        'Create News & Insight posts to display. Configure display mode in block settings.'
    );
    return;
}

// Get count
$news_count = $use_acf_posts ? count($news_posts) : ($news_posts ? $news_posts->found_posts : 0);

if ($news_count === 0) {
    return; // No posts to display
}

// Generate unique carousel IDs
$carousel_desktop_id = chainthat_get_carousel_id('owl-blog-desktop');
$carousel_tablet_id = chainthat_get_carousel_id('owl-blog-tablet');
$carousel_mobile_id = chainthat_get_carousel_id('owl-blog-mobile');

$animation_classes = ['fadeInLeft', 'fadeInUp', 'fadeInRight'];
?>

<!-- blog-section -->
<section id="<?php echo esc_attr($attributes['id']); ?>" class="blog-area <?php echo esc_attr($attributes['class']); ?>">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="blog-main-wrap">
                    <div class="blog-title text-center">
                        <h2 class="wow fadeInLeft"><?php echo esc_html($section_title); ?></h2>
                    </div>

                    <div class="blog-main">
                        <?php if ($news_count <= 3): ?>
                            <!-- Desktop: Static 3-column grid -->
                            <div class="row d-none d-lg-flex">
                                <?php 
                                $index = 0;
                                if ($use_acf_posts):
                                    foreach ($news_posts as $post):
                                        setup_postdata($post);
                                        $animation_class = $animation_classes[$index % 3];
                                        $featured_image = get_the_post_thumbnail_url($post->ID, 'medium');
                                        $post_date = get_the_date('F j, Y', $post->ID);
                                        $post_categories = get_the_terms($post->ID, 'news_category');
                                        $category_name = !empty($post_categories) ? $post_categories[0]->name : 'News';
                                        ?>
                                        <div class="col-lg-4 mb-4">
                                            <article class="blog-item h-100 wow <?php echo $animation_class; ?>">
                                                <div class="blog-content">
                                                    <a href="<?php echo esc_url(get_permalink($post->ID)); ?>">
                                                        <img class="wow fadeInLeft" src="<?php echo esc_url($featured_image ?: get_template_directory_uri() . '/images/blog1.png'); ?>" alt="<?php echo esc_attr($post->post_title); ?>">
                                                    </a>
                                                    <ul class="wow fadeInRight">
                                                        <li><?php echo esc_html($post_date); ?></li>
                                                        <li>|</li>
                                                        <li><?php echo esc_html($category_name); ?></li>
                                                    </ul>
                                                    <h3 class="wow fadeInLeft"><a href="<?php echo esc_url(get_permalink($post->ID)); ?>"><?php echo esc_html($post->post_title); ?></a></h3>
                                                </div>
                                                <div class="blog-button">
                                                    <div class="blog-btn wow fadeInUp">
                                                        <a href="<?php echo esc_url(get_permalink($post->ID)); ?>">read more</a>
                                                    </div>
                                                </div>
                                            </article>
                                        </div>
                                        <?php 
                                        $index++;
                                    endforeach;
                                    wp_reset_postdata();
                                else:
                                    while ($news_posts->have_posts()): 
                                        $news_posts->the_post();
                                        $animation_class = $animation_classes[$index % 3];
                                        $featured_image = get_the_post_thumbnail_url(get_the_ID(), 'medium');
                                        $post_date = get_the_date('F j, Y');
                                        $post_categories = get_the_terms(get_the_ID(), 'news_category');
                                        $category_name = !empty($post_categories) ? $post_categories[0]->name : 'News';
                                        ?>
                                        <div class="col-lg-4 mb-4">
                                            <article class="blog-item h-100 wow <?php echo $animation_class; ?>">
                                                <div class="blog-content">
                                                    <a href="<?php echo esc_url(get_permalink()); ?>">
                                                        <img class="wow fadeInLeft" src="<?php echo esc_url($featured_image ?: get_template_directory_uri() . '/images/blog1.png'); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
                                                    </a>
                                                    <ul class="wow fadeInRight">
                                                        <li><?php echo esc_html($post_date); ?></li>
                                                        <li>|</li>
                                                        <li><?php echo esc_html($category_name); ?></li>
                                                    </ul>
                                                    <h3 class="wow fadeInLeft"><a href="<?php echo esc_url(get_permalink()); ?>"><?php echo esc_html(get_the_title()); ?></a></h3>
                                                </div>
                                                <div class="blog-button">
                                                    <div class="blog-btn wow fadeInUp">
                                                        <a href="<?php echo esc_url(get_permalink()); ?>">read more</a>
                                                    </div>
                                                </div>
                                            </article>
                                        </div>
                                        <?php 
                                        $index++;
                                    endwhile;
                                    wp_reset_postdata();
                                endif;
                                ?>
                            </div>
                        <?php else: ?>
                            <!-- Desktop: Carousel for more than 3 posts -->
                            <div id="<?php echo esc_attr($carousel_desktop_id); ?>" class="owl-carousel owl-theme d-none d-lg-block">
                                <?php 
                                if ($use_acf_posts):
                                    foreach ($news_posts as $post):
                                        setup_postdata($post);
                                        $featured_image = get_the_post_thumbnail_url($post->ID, 'medium');
                                        $post_date = get_the_date('F j, Y', $post->ID);
                                        $post_categories = get_the_terms($post->ID, 'news_category');
                                        $category_name = !empty($post_categories) ? $post_categories[0]->name : 'News';
                                        ?>
                                        <div class="blog-item">
                                            <div class="blog-content">
                                                <a href="<?php echo esc_url(get_permalink($post->ID)); ?>">
                                                    <img class="wow fadeInLeft" src="<?php echo esc_url($featured_image ?: get_template_directory_uri() . '/images/blog1.png'); ?>" alt="<?php echo esc_attr($post->post_title); ?>">
                                                </a>
                                                <ul class="wow fadeInRight">
                                                    <li><?php echo esc_html($post_date); ?></li>
                                                    <li>|</li>
                                                    <li><?php echo esc_html($category_name); ?></li>
                                                </ul>
                                                <h3 class="wow fadeInLeft"><a href="<?php echo esc_url(get_permalink($post->ID)); ?>"><?php echo esc_html($post->post_title); ?></a></h3>
                                            </div>
                                            <div class="blog-button">
                                                <div class="blog-btn wow fadeInUp">
                                                    <a href="<?php echo esc_url(get_permalink($post->ID)); ?>">read more</a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach;
                                    wp_reset_postdata();
                                else:
                                    while ($news_posts->have_posts()): 
                                        $news_posts->the_post();
                                        $featured_image = get_the_post_thumbnail_url(get_the_ID(), 'medium');
                                        $post_date = get_the_date('F j, Y');
                                        $post_categories = get_the_terms(get_the_ID(), 'news_category');
                                        $category_name = !empty($post_categories) ? $post_categories[0]->name : 'News';
                                        ?>
                                        <div class="blog-item">
                                            <div class="blog-content">
                                                <a href="<?php echo esc_url(get_permalink()); ?>">
                                                    <img class="wow fadeInLeft" src="<?php echo esc_url($featured_image ?: get_template_directory_uri() . '/images/blog1.png'); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
                                                </a>
                                                <ul class="wow fadeInRight">
                                                    <li><?php echo esc_html($post_date); ?></li>
                                                    <li>|</li>
                                                    <li><?php echo esc_html($category_name); ?></li>
                                                </ul>
                                                <h3 class="wow fadeInLeft"><a href="<?php echo esc_url(get_permalink()); ?>"><?php echo esc_html(get_the_title()); ?></a></h3>
                                            </div>
                                            <div class="blog-button">
                                                <div class="blog-btn wow fadeInUp">
                                                    <a href="<?php echo esc_url(get_permalink()); ?>">read more</a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endwhile;
                                    wp_reset_postdata();
                                endif;
                                ?>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Tablet: 2 cards carousel -->
                        <div id="<?php echo esc_attr($carousel_tablet_id); ?>" class="owl-carousel owl-theme d-none d-md-block d-lg-none">
                            <?php 
                            // Reset and loop again for tablet
                            if ($use_acf_posts):
                                foreach ($news_posts as $post):
                                    setup_postdata($post);
                                    $featured_image = get_the_post_thumbnail_url($post->ID, 'medium');
                                    $post_date = get_the_date('F j, Y', $post->ID);
                                    $post_categories = get_the_terms($post->ID, 'news_category');
                                    $category_name = !empty($post_categories) ? $post_categories[0]->name : 'News';
                                    ?>
                                    <div class="blog-item">
                                        <div class="blog-content">
                                            <a href="<?php echo esc_url(get_permalink($post->ID)); ?>">
                                                <img class="wow fadeInLeft" src="<?php echo esc_url($featured_image ?: get_template_directory_uri() . '/images/blog1.png'); ?>" alt="<?php echo esc_attr($post->post_title); ?>">
                                            </a>
                                            <ul class="wow fadeInRight">
                                                <li><?php echo esc_html($post_date); ?></li>
                                                <li>|</li>
                                                <li><?php echo esc_html($category_name); ?></li>
                                            </ul>
                                            <h3 class="wow fadeInLeft"><a href="<?php echo esc_url(get_permalink($post->ID)); ?>"><?php echo esc_html($post->post_title); ?></a></h3>
                                        </div>
                                        <div class="blog-button">
                                            <div class="blog-btn wow fadeInUp">
                                                <a href="<?php echo esc_url(get_permalink($post->ID)); ?>">read more</a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach;
                                wp_reset_postdata();
                            else:
                                $news_posts->rewind_posts();
                                while ($news_posts->have_posts()): 
                                    $news_posts->the_post();
                                    $featured_image = get_the_post_thumbnail_url(get_the_ID(), 'medium');
                                    $post_date = get_the_date('F j, Y');
                                    $post_categories = get_the_terms(get_the_ID(), 'news_category');
                                    $category_name = !empty($post_categories) ? $post_categories[0]->name : 'News';
                                    ?>
                                    <div class="blog-item">
                                        <div class="blog-content">
                                            <a href="<?php echo esc_url(get_permalink()); ?>">
                                                <img class="wow fadeInLeft" src="<?php echo esc_url($featured_image ?: get_template_directory_uri() . '/images/blog1.png'); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
                                            </a>
                                            <ul class="wow fadeInRight">
                                                <li><?php echo esc_html($post_date); ?></li>
                                                <li>|</li>
                                                <li><?php echo esc_html($category_name); ?></li>
                                            </ul>
                                            <h3 class="wow fadeInLeft"><a href="<?php echo esc_url(get_permalink()); ?>"><?php echo esc_html(get_the_title()); ?></a></h3>
                                        </div>
                                        <div class="blog-button">
                                            <div class="blog-btn wow fadeInUp">
                                                <a href="<?php echo esc_url(get_permalink()); ?>">read more</a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile;
                                wp_reset_postdata();
                            endif;
                            ?>
                        </div>
                        
                        <!-- Mobile: 1 card carousel -->
                        <div id="<?php echo esc_attr($carousel_mobile_id); ?>" class="owl-carousel owl-theme d-block d-md-none">
                            <?php 
                            // Reset and loop again for mobile
                            if ($use_acf_posts):
                                foreach ($news_posts as $post):
                                    setup_postdata($post);
                                    $featured_image = get_the_post_thumbnail_url($post->ID, 'medium');
                                    $post_date = get_the_date('F j, Y', $post->ID);
                                    $post_categories = get_the_terms($post->ID, 'news_category');
                                    $category_name = !empty($post_categories) ? $post_categories[0]->name : 'News';
                                    ?>
                                    <div class="blog-item">
                                        <div class="blog-content">
                                            <a href="<?php echo esc_url(get_permalink($post->ID)); ?>">
                                                <img class="wow fadeInLeft" src="<?php echo esc_url($featured_image ?: get_template_directory_uri() . '/images/blog1.png'); ?>" alt="<?php echo esc_attr($post->post_title); ?>">
                                            </a>
                                            <ul class="wow fadeInRight">
                                                <li><?php echo esc_html($post_date); ?></li>
                                                <li>|</li>
                                                <li><?php echo esc_html($category_name); ?></li>
                                            </ul>
                                            <h3 class="wow fadeInLeft"><a href="<?php echo esc_url(get_permalink($post->ID)); ?>"><?php echo esc_html($post->post_title); ?></a></h3>
                                        </div>
                                        <div class="blog-button">
                                            <div class="blog-btn wow fadeInUp">
                                                <a href="<?php echo esc_url(get_permalink($post->ID)); ?>">read more</a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach;
                                wp_reset_postdata();
                            else:
                                $news_posts->rewind_posts();
                                while ($news_posts->have_posts()): 
                                    $news_posts->the_post();
                                    $featured_image = get_the_post_thumbnail_url(get_the_ID(), 'medium');
                                    $post_date = get_the_date('F j, Y');
                                    $post_categories = get_the_terms(get_the_ID(), 'news_category');
                                    $category_name = !empty($post_categories) ? $post_categories[0]->name : 'News';
                                    ?>
                                    <div class="blog-item">
                                        <div class="blog-content">
                                            <a href="<?php echo esc_url(get_permalink()); ?>">
                                                <img class="wow fadeInLeft" src="<?php echo esc_url($featured_image ?: get_template_directory_uri() . '/images/blog1.png'); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
                                            </a>
                                            <ul class="wow fadeInRight">
                                                <li><?php echo esc_html($post_date); ?></li>
                                                <li>|</li>
                                                <li><?php echo esc_html($category_name); ?></li>
                                            </ul>
                                            <h3 class="wow fadeInLeft"><a href="<?php echo esc_url(get_permalink()); ?>"><?php echo esc_html(get_the_title()); ?></a></h3>
                                        </div>
                                        <div class="blog-button">
                                            <div class="blog-btn wow fadeInUp">
                                                <a href="<?php echo esc_url(get_permalink()); ?>">read more</a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile;
                                wp_reset_postdata();
                            endif;
                            ?>
                        </div>
                    </div>
                    
                    <?php if ($show_view_all): ?>
                    <!-- View All Button -->
                    <div class="text-center wow fadeInUp" style="margin-top: 50px;">
                        <div class="btn-all blog-btm-btn">
                            <a href="<?php echo esc_url($view_all_link); ?>">View All</a>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php if (!$is_preview): ?>
<script>
jQuery(document).ready(function($) {
    // Desktop carousel (3 items)
    if ($('#<?php echo esc_js($carousel_desktop_id); ?>').length) {
        $('#<?php echo esc_js($carousel_desktop_id); ?>').owlCarousel({
            loop: true,
            margin: 30,
            nav: true,
            dots: false,
            autoplay: true,
            autoplayTimeout: 4000,
            autoplayHoverPause: true,
            items: 3
        });
    }
    
    // Tablet carousel (2 items)
    if ($('#<?php echo esc_js($carousel_tablet_id); ?>').length) {
        $('#<?php echo esc_js($carousel_tablet_id); ?>').owlCarousel({
            loop: true,
            margin: 20,
            nav: true,
            dots: true,
            autoplay: true,
            autoplayTimeout: 4000,
            autoplayHoverPause: true,
            items: 2
        });
    }
    
    // Mobile carousel (1 item)
    if ($('#<?php echo esc_js($carousel_mobile_id); ?>').length) {
        $('#<?php echo esc_js($carousel_mobile_id); ?>').owlCarousel({
            loop: true,
            margin: 0,
            nav: true,
            dots: true,
            autoplay: true,
            autoplayTimeout: 4000,
            autoplayHoverPause: true,
            items: 1
        });
    }
});
</script>
<?php endif; ?>



<?php
/**
 * Template Name: Solutions Archive Template
 *
 * @package ChainThat
 * @version 1.0.0
 */

get_header(); ?>

<main id="main" class="site-main">
        <?php
    // Get page content
    while (have_posts()) : the_post();
        $page_title = get_field('solutions_archive_title') ?: get_the_title();
        $page_description = get_field('solutions_archive_description') ?: get_the_excerpt();
        $hero_image = get_field('solutions_archive_hero_image');
    ?>
    
    <!-- solutions-hero -->
    <div class="solution-hero-area">
        <div class="solution-hero-main wow fadeInRight">
            <?php if ($hero_image): ?>
                <img class="d-none d-lg-block" src="<?php echo esc_url($hero_image); ?>" alt="<?php echo esc_attr($page_title); ?>">
                <img class="d-block d-lg-none" src="<?php echo esc_url($hero_image); ?>" alt="<?php echo esc_attr($page_title); ?>">
            <?php else: ?>
                <img class="d-none d-lg-block" src="<?php echo get_template_directory_uri(); ?>/images/solution.png" alt="<?php echo esc_attr($page_title); ?>">
                <img class="d-block d-lg-none" src="<?php echo get_template_directory_uri(); ?>/images/solution-m.png" alt="<?php echo esc_attr($page_title); ?>">
            <?php endif; ?>
            
            <div class="solu-text">
                <h2 class="wow fadeInUp"><?php echo esc_html($page_title); ?></h2>
            </div>
            </div>
        </div>

    <?php if ($page_description): ?>
    <section class="solution-intro-area">
            <div class="container">
            <div class="solution-intro text-center">
                <p class="wow fadeInUp"><?php echo wp_kses_post($page_description); ?></p>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <?php endwhile; ?>

    <!-- solutions-listing -->
    <section class="solutions-listing-area">
            <div class="container">
                    <?php
            // Query all solutions
            $solutions_query = new WP_Query(array(
                'post_type' => 'solution',
                'posts_per_page' => -1,
                'orderby' => 'menu_order',
                'order' => 'ASC',
                'post_status' => 'publish'
            ));
            
            if ($solutions_query->have_posts()):
            ?>
            <div class="solutions-grid">
                    <?php
                $index = 0;
                while ($solutions_query->have_posts()): 
                    $solutions_query->the_post();
                    $animation_class = $index % 3 == 0 ? 'fadeInLeft' : ($index % 3 == 1 ? 'fadeInUp' : 'fadeInRight');
                    $featured_image = get_the_post_thumbnail_url(get_the_ID(), 'large');
                    $excerpt = get_the_excerpt() ?: wp_trim_words(get_the_content(), 20);
                ?>
                <div class="solution-card wow <?php echo $animation_class; ?>">
                    <?php if ($featured_image): ?>
                    <div class="solution-card-image">
                        <a href="<?php echo get_permalink(); ?>">
                            <img src="<?php echo esc_url($featured_image); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
                        </a>
            </div>
    <?php endif; ?>

                    <div class="solution-card-content">
                        <h3>
                            <a href="<?php echo get_permalink(); ?>"><?php echo esc_html(get_the_title()); ?></a>
                        </h3>
                        <p><?php echo esc_html($excerpt); ?></p>
                        <div class="btn-all">
                            <a href="<?php echo get_permalink(); ?>">Learn More</a>
                            </div>
                            </div>
                        </div>
                    <?php
                    $index++;
                endwhile; 
                wp_reset_postdata();
                    ?>
                </div>
            <?php else: ?>
            <div class="no-solutions-found text-center">
                <p>No solutions found. Please check back later.</p>
            </div>
            <?php endif; ?>
            </div>
        </section>
    
    <?php
    // Optional CTA section
    $show_cta = get_field('solutions_archive_show_cta');
    if ($show_cta):
        $cta_title = get_field('solutions_archive_cta_title');
        $cta_description = get_field('solutions_archive_cta_description');
        $cta_button_text = get_field('solutions_archive_cta_button_text');
        $cta_button_url = get_field('solutions_archive_cta_button_url');
    ?>
    <section class="solutions-cta-area">
        <div class="container">
            <div class="solutions-cta-main text-center">
                <?php if ($cta_title): ?>
                <h2 class="wow fadeInUp"><?php echo esc_html($cta_title); ?></h2>
    <?php endif; ?>

                <?php if ($cta_description): ?>
                <p class="wow fadeInUp"><?php echo esc_html($cta_description); ?></p>
    <?php endif; ?>

                <?php if ($cta_button_text && $cta_button_url): ?>
                <div class="btn-all wow fadeInUp">
                    <a href="<?php echo esc_url($cta_button_url); ?>"><?php echo esc_html($cta_button_text); ?></a>
                    </div>
                <?php endif; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>
</main>

<style>
/* Solutions Archive Styling */
.solution-intro-area {
    padding: 60px 0;
    background: #f8f9fa;
}

.solution-intro p {
    font-size: 18px;
    line-height: 1.6;
    max-width: 900px;
    margin: 0 auto;
}

.solutions-listing-area {
    padding: 120px 0;
}

.solutions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 40px;
    margin-top: 40px;
}

.solution-card {
    background: #fff;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.solution-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
}

.solution-card-image {
    overflow: hidden;
    position: relative;
    padding-top: 60%;
}

.solution-card-image img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.solution-card:hover .solution-card-image img {
    transform: scale(1.05);
}

.solution-card-content {
    padding: 30px;
}

.solution-card-content h3 {
    font-size: 24px;
    margin-bottom: 15px;
    font-weight: 600;
}

.solution-card-content h3 a {
    color: #005a5b;
    text-decoration: none;
    transition: color 0.3s ease;
}

.solution-card-content h3 a:hover {
    color: #003d3e;
}

.solution-card-content p {
    font-size: 16px;
    line-height: 1.6;
    color: #666;
    margin-bottom: 20px;
}

.solutions-cta-area {
    padding: 120px 0;
    background: linear-gradient(135deg, #005a5b 0%, #003d3e 100%);
    color: #fff;
}

.solutions-cta-main h2 {
    color: #fff;
    margin-bottom: 20px;
}

.solutions-cta-main p {
    font-size: 18px;
    margin-bottom: 30px;
}

.no-solutions-found {
    padding: 60px 20px;
    font-size: 18px;
    color: #666;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .solutions-listing-area {
        padding: 60px 0;
    }
    
    .solutions-grid {
        grid-template-columns: 1fr;
        gap: 30px;
    }
    
    .solution-card-content {
        padding: 20px;
    }
    
    .solution-card-content h3 {
        font-size: 20px;
    }
    
    .solutions-cta-area {
        padding: 60px 0;
    }
}
</style>

<?php get_footer(); ?>

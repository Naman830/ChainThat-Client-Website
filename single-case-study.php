<?php
/**
 * The template for displaying single case study posts
 *
 * @package ChainThat
 * @version 1.0.0
 */

get_header(); ?>

<!-- case-study-single-section -->
<section class="case-study-single-area">
    <div class="container">
        <div class="case-study-single-main">
            <!-- Breadcrumb -->
            <div class="breadcrumb-section wow fadeInLeft">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="<?php echo esc_url(home_url('/')); ?>">Home</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="<?php echo esc_url(get_post_type_archive_link('case_study')); ?>">Case Studies</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <?php echo esc_html(get_the_title()); ?>
                        </li>
                    </ol>
                </nav>
            </div>

            <!-- Featured Image -->
            <?php 
            $featured_image = get_the_post_thumbnail_url(get_the_ID(), 'full');
            if ($featured_image): ?>
                <div class="case-study-single-featured-image wow fadeInUp">
                    <img src="<?php echo esc_url($featured_image); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" class="img-fluid">
                </div>
            <?php endif; ?>

            <!-- Post Content -->
            <div class="case-study-single-content wow fadeInUp">
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <div class="post-content">
                            <?php 
                            while (have_posts()): 
                                the_post();
                                the_content();
                            endwhile;
                            ?>
                        </div>

                        <!-- Post Tags -->
                        <?php 
                        $post_tags = get_the_tags();
                        if ($post_tags && !is_wp_error($post_tags)): ?>
                            <div class="post-tags wow fadeInUp">
                                <h4>Tags:</h4>
                                <div class="tags-list">
                                    <?php foreach ($post_tags as $tag): ?>
                                        <a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>" class="tag-link">
                                            <?php echo esc_html($tag->name); ?>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Social Sharing -->
                        <div class="social-sharing wow fadeInUp">
                            <h4>Share this case study:</h4>
                            <div class="social-links">
                                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" target="_blank" class="social-link facebook">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>" target="_blank" class="social-link twitter">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo urlencode(get_permalink()); ?>" target="_blank" class="social-link linkedin">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                                <a href="mailto:?subject=<?php echo urlencode(get_the_title()); ?>&body=<?php echo urlencode(get_permalink()); ?>" class="social-link email">
                                    <i class="fas fa-envelope"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Navigation to Previous/Next Posts -->
<section class="post-navigation-area">
    <div class="container">
        <div class="post-navigation wow fadeInUp">
            <div class="row">
                <div class="col-md-6">
                    <?php 
                    $prev_post = get_previous_post();
                    if ($prev_post): ?>
                        <div class="nav-previous">
                            <a href="<?php echo esc_url(get_permalink($prev_post->ID)); ?>" class="nav-link prev-link">
                                <div class="nav-arrow">
                                    <i class="fas fa-arrow-left"></i>
                                </div>
                                <div class="nav-content">
                                    <span class="nav-label">Previous Case Study</span>
                                    <h4 class="nav-title">
                                        <?php 
                                        $prev_title = $prev_post->post_title;
                                        $truncated_prev_title = (strlen($prev_title) > 45) ? substr($prev_title, 0, 45) . '...' : $prev_title;
                                        echo esc_html($truncated_prev_title);
                                        ?>
                                    </h4>
                                </div>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="col-md-6">
                    <?php 
                    $next_post = get_next_post();
                    if ($next_post): ?>
                        <div class="nav-next">
                            <a href="<?php echo esc_url(get_permalink($next_post->ID)); ?>" class="nav-link next-link">
                                <div class="nav-content">
                                    <span class="nav-label">Next Case Study</span>
                                    <h4 class="nav-title">
                                        <?php 
                                        $next_title = $next_post->post_title;
                                        $truncated_next_title = (strlen($next_title) > 45) ? substr($next_title, 0, 45) . '...' : $next_title;
                                        echo esc_html($truncated_next_title);
                                        ?>
                                    </h4>
                                </div>
                                <div class="nav-arrow">
                                    <i class="fas fa-arrow-right"></i>
                                </div>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Back to Case Studies Archive -->
<section class="back-to-archive-area">
    <div class="container">
        <div class="back-to-archive wow fadeInUp">
            <div class="btn-all">
                <a href="<?php echo esc_url(get_post_type_archive_link('case_study')); ?>">
                    <i class="fas fa-arrow-left"></i> Back to Case Studies
                </a>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>


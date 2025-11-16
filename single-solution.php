<?php
/**
 * Single Solution Template
 *
 * @package ChainThat
 * @version 1.0.0
 */

get_header();

// Helper function to check if section is enabled
function is_solution_section_enabled($section_name) {
    if (!function_exists('get_field')) {
        return true;
    }
    
    $section_controls = get_field('solution_section_controls');
    if (!$section_controls) {
        return true;
    }
    
    $toggle_field = $section_name . '_section_toggle';
    if (!isset($section_controls[$toggle_field])) {
        return true;
    }
    
    return $section_controls[$toggle_field] === 'show';
}

while (have_posts()) : the_post();
?>

<?php if (is_solution_section_enabled('hero')): ?>
<!-- solution-hero -->
<div class="adminis-area solution-hero-area">
    <?php
    // Get background settings with proper fallbacks
    $hero_background_type = 'image';
    $hero_background_video = '';
    $hero_background_image = '';
    
    if (function_exists('get_field')) {
        $hero_background_type = get_field('hero_background_type');
        $hero_background_video = get_field('hero_background_video');
        $hero_background_image = get_field('hero_background_image');
    }
    
    // Default to 'image' if not set
    if (!$hero_background_type) {
        $hero_background_type = 'image';
    }
    
    // Fallback to default image if no background set
    if (!$hero_background_image) {
        $hero_background_image = get_template_directory_uri() . '/images/solution.png';
    }
    ?>
    
    <?php if ($hero_background_type === 'video' && !empty($hero_background_video)): ?>
        <video class="adminis-background-video" autoplay muted loop playsinline>
            <source src="<?php echo esc_url($hero_background_video); ?>" type="video/mp4">
        </video>
        <!-- Fallback image for browsers that don't support video or while video loads -->
        <div class="adminis-background-image" style="background-image: url('<?php echo esc_url($hero_background_image); ?>');"></div>
    <?php else: ?>
        <div class="adminis-background-image" style="background-image: url('<?php echo esc_url($hero_background_image); ?>');"></div>
    <?php endif; ?>
    
    <div class="container">
        <div class="adminis-main">
            <div class="adminis-title">
                <?php 
                // Get ACF fields - ensure we're checking the current post
                $post_id = get_the_ID();
                $hero_title = '';
                $hero_description = '';
                
                if (function_exists('get_field')) {
                    $hero_title = get_field('hero_title', $post_id);
                    $hero_description = get_field('hero_description', $post_id);
                    
                    // Temporary debug for admin/editors only
                    if (current_user_can('edit_posts') && isset($_GET['debug_acf'])) {
                        echo '<!-- ACF Debug: ';
                        echo 'Post ID: ' . $post_id . ' | ';
                        echo 'Hero Title: ' . var_export($hero_title, true) . ' | ';
                        echo 'Hero Description: ' . var_export($hero_description, true);
                        echo ' -->';
                    }
                }
                
                // Use post title as fallback ONLY if hero_title is not set or is completely empty
                if ($hero_title === false || $hero_title === null || $hero_title === '') {
                    $hero_title = get_the_title();
                }
                ?>
                <h2 class="wow fadeInRight"><?php echo esc_html($hero_title); ?></h2>
                <?php 
                // Only show description if it has actual content
                if ($hero_description !== false && $hero_description !== null && $hero_description !== ''): 
                ?>
                <p class="wow fadeInLeft"><?php echo esc_html($hero_description); ?></p>
                <?php endif; ?>
            </div>
        </div>
        <?php 
        // Only show hero image if custom images are provided or featured image exists
        $hero_desktop = get_field('hero_image_desktop');
        $hero_mobile = get_field('hero_image_mobile');
        $featured_image = get_the_post_thumbnail_url(get_the_ID(), 'full');
        
        if ($hero_desktop || $hero_mobile || $featured_image):
        ?>
        <div class="adminis-img-wrap wow fadeInRight">
            <div class="adminis-img">
                <?php 
                // Desktop image
                if ($hero_desktop):
                ?>
                    <img class="d-none d-lg-block" src="<?php echo esc_url($hero_desktop); ?>" alt="<?php echo esc_attr($hero_title); ?>">
                <?php elseif ($featured_image): ?>
                    <img class="d-none d-lg-block" src="<?php echo esc_url($featured_image); ?>" alt="<?php echo esc_attr($hero_title); ?>">
                <?php endif; ?>
                
                <?php 
                // Mobile image
                if ($hero_mobile): ?>
                    <img class="d-block d-lg-none" src="<?php echo esc_url($hero_mobile); ?>" alt="<?php echo esc_attr($hero_title); ?>">
                <?php elseif ($featured_image): ?>
                    <img class="d-block d-lg-none" src="<?php echo esc_url($featured_image); ?>" alt="<?php echo esc_attr($hero_title); ?>">
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>      
    </div>
</div>
<?php endif; ?>

<?php if (is_solution_section_enabled('diagram')): ?>
<!-- diagram-section -->
<section class="diagram-area">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="diagram-main text-center">
                    <?php 
                    $diagram_image = get_field('diagram_image');
                    $diagram_text = get_field('diagram_text');
                    ?>
                    
                    <div class="diagram-image wow fadeInUp">
                        <?php if ($diagram_image): ?>
                            <img src="<?php echo esc_url($diagram_image); ?>" alt="Diagram">
                        <?php endif; ?>
                    </div>
                    
                    <?php if ($diagram_text): ?>
                    <div class="diagram-text wow fadeInUp">
                        <p><?php echo wp_kses_post($diagram_text); ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if (is_solution_section_enabled('key_benefits')): ?>
<!-- key-benefits-section -->
<section class="service-area key-benefits-area">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="all-title text-center">
                    <h2 class="wow fadeInLeft"><?php echo esc_html(get_field('key_benefits_title') ?: 'Your Key Benefits'); ?></h2>
                </div>
            </div>
        </div>
        
        <?php 
        $benefit_items = get_field('key_benefit_items');
        if ($benefit_items && is_array($benefit_items)):
        ?>
        <!-- Benefits Carousel -->
        <div class="benefits-carousel-wrapper">
            <div id="benefitsCarousel" class="owl-carousel owl-theme benefits-carousel">
                <?php 
                foreach ($benefit_items as $index => $item):
                    $animation_class = $index % 4 == 0 ? 'fadeInLeft' : ($index % 4 == 1 ? 'fadeInUp' : ($index % 4 == 2 ? 'fadeInDown' : 'fadeInRight'));
                    
                    // Always truncate to 12 words for consistent Read More buttons across all cards
                    $description = !empty($item['description']) ? $item['description'] : '';
                    $words = explode(' ', $description);
                    $truncated = implode(' ', array_slice($words, 0, 12));
                    $is_truncated = count($words) > 12;
                    
                    // Force Read More button for ALL cards, even if short
                    $force_button = true;
                ?>
                <div class="benefit-card">
                    <?php if (!empty($item['icon'])): ?>
                        <div class="benefit-icon">
                            <img class="wow <?php echo $animation_class; ?>" src="<?php echo esc_url($item['icon']); ?>" alt="<?php echo esc_attr($item['title']); ?>">
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($item['title'])): ?>
                        <h4 class="wow fadeInLeft"><?php echo esc_html($item['title']); ?></h4>
                    <?php endif; ?>
                    <?php if (!empty($description)): ?>
                        <div class="benefit-description">
                            <p class="benefit-text wow fadeInRight">
                                <span class="benefit-short"><?php echo esc_html($truncated); ?><?php if ($is_truncated || $force_button): ?>...<?php endif; ?></span>
                                <span class="benefit-full"><?php echo esc_html($description); ?></span>
                            </p>
                            <button class="benefit-read-more" data-expanded="false">Read More</button>
                        </div>
                    <?php endif; ?>
                </div>
                <?php 
                endforeach;
                ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>
<?php endif; ?>

<?php if (is_solution_section_enabled('three_words')): ?>
<!-- three-words-banner -->
<section class="three-words-area">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="three-words-main">
                    <?php 
                    $word1 = get_field('three_words_word1') ?: 'Secure.';
                    $word2 = get_field('three_words_word2') ?: 'Efficient.';
                    $word3 = get_field('three_words_word3') ?: 'Scalable.';
                    ?>
                    <div class="three-word-item wow fadeInLeft">
                        <h3><?php echo esc_html($word1); ?></h3>
                    </div>
                    <div class="three-word-item wow fadeInUp">
                        <h3><?php echo esc_html($word2); ?></h3>
                    </div>
                    <div class="three-word-item wow fadeInRight">
                        <h3><?php echo esc_html($word3); ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if (is_solution_section_enabled('platform_empowerment')): ?>
<!-- platform-empowerment-section -->
<section class="ipsum-area ipsum-area10 platform-empowerment-section">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h2 class="wow fadeInRight"><?php echo esc_html(get_field('platform_title') ?: 'Empower your MGA with our next-gen platform'); ?></h2>
                <p class="platform-subtitle wow fadeInLeft"><?php echo esc_html(get_field('platform_subtitle') ?: 'Beyond Policy Administration'); ?></p>
            </div>
        </div>
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0 platform-text-col">
                <div class="platform-text wow fadeInLeft">
                    <?php 
                    $platform_description = get_field('platform_description');
                    if ($platform_description):
                        echo wp_kses_post($platform_description);
                    else:
                    ?>
                    <p>Market-leading policy administration that enabled an eMGA to launch 4 products in 3 months at a fraction of typical PAS product costs while supporting underwriting platforms.</p>
                    <p>BPA is more than a platform—it's a digital enabler built for insurers technologies, it goes beyond a regular policy administrator. Beyond Policy Administration (BPA) is a flexible software module and built-in data capabilities, BPA empowers MGAs to launch products faster, measure quickly by month data, and stay agile in an evolving landscape.</p>
                    <?php endif; ?>
                    <div class="btn-all wow fadeInUp">
                        <?php 
                        $platform_button_link = get_field('platform_button_url');
                        $platform_button_url = is_array($platform_button_link) ? $platform_button_link['url'] : '#';
                        $platform_button_target = is_array($platform_button_link) && !empty($platform_button_link['target']) ? $platform_button_link['target'] : '_self';
                        $platform_button_text = get_field('platform_button_text') ?: 'Learn More';
                        ?>
                        <a href="<?php echo esc_url($platform_button_url); ?>" target="<?php echo esc_attr($platform_button_target); ?>"><?php echo esc_html($platform_button_text); ?></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 platform-image-col">
                <div class="platform-image wow fadeInRight">
                    <?php 
                    $platform_image = get_field('platform_image');
                    if ($platform_image):
                    ?>
                        <img src="<?php echo esc_url($platform_image); ?>" alt="Platform" class="img-fluid">
                    <?php else: ?>
                        <img src="<?php echo get_template_directory_uri(); ?>/images/adminis1.png" alt="Platform" class="img-fluid">
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if (is_solution_section_enabled('case_studies')): ?>
<!-- case-studies-section -->
<section class="ipsum-area ipsum-area10 case-studies-section">
    <div class="container">
        <?php 
        $case_studies_title = get_field('case_studies_title');
        $case_studies_description = get_field('case_studies_description');
        
        if ($case_studies_title || $case_studies_description): ?>
        <div class="row">
            <div class="col-12">
                <div class="ipsum-title-main text-center mb-5">
                    <?php if ($case_studies_title): ?>
                        <h2 class="wow fadeInUp"><?php echo esc_html($case_studies_title); ?></h2>
                    <?php endif; ?>
                    <?php if ($case_studies_description): ?>
                        <p class="wow fadeInUp"><?php echo esc_html($case_studies_description); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <?php 
        $case_studies = get_field('case_study_items');
        if ($case_studies && is_array($case_studies)):
            foreach ($case_studies as $index => $post):
                setup_postdata($post);
                $is_even = $index % 2 == 0;
                
                // Get custom card fields - for news & insight posts
                $card_title = get_field('news_card_title', $post->ID);
                $card_excerpt = get_field('news_card_excerpt', $post->ID);
                $card_image = get_field('news_card_image', $post->ID);
                
                // Fallback to default values if custom fields are empty
                $display_title = !empty($card_title) ? $card_title : get_the_title($post->ID);
                $display_excerpt = !empty($card_excerpt) ? $card_excerpt : get_the_excerpt($post->ID);
                $display_image = !empty($card_image) ? $card_image : get_the_post_thumbnail_url($post->ID, 'full');
        ?>
        <div class="row align-items-center mb-5">
            <div class="col-lg-6 mb-4 mb-lg-0 order-2 order-lg-<?php echo $is_even ? '1' : '2'; ?>">
                <div class="case-study-content wow <?php echo $is_even ? 'fadeInLeft' : 'fadeInRight'; ?>">
                    <?php if ($display_title): ?>
                        <h4><?php echo esc_html($display_title); ?></h4>
                    <?php endif; ?>
                    <?php if ($display_excerpt): ?>
                        <p><?php echo wp_kses_post($display_excerpt); ?></p>
                    <?php endif; ?>
                    <div class="btn-all">
                        <a href="<?php echo esc_url(get_permalink($post->ID)); ?>">Read More</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 order-1 order-lg-<?php echo $is_even ? '2' : '1'; ?>">
                <div class="case-study-image wow <?php echo $is_even ? 'fadeInRight' : 'fadeInLeft'; ?>">
                    <?php if ($display_image): ?>
                        <img src="<?php echo esc_url($display_image); ?>" alt="<?php echo esc_attr($display_title); ?>" class="img-fluid">
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php 
            endforeach;
            wp_reset_postdata();
        endif; 
        ?>
    </div>
</section>
<?php endif; ?>

<?php if (is_solution_section_enabled('bottom_cta')): ?>
<!-- bottom-cta-buttons -->
<section class="bottom-cta-area">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="bottom-cta-main text-center">
                    <?php 
                    $cta_solutions = get_field('bottom_cta_buttons');
                    if ($cta_solutions && is_array($cta_solutions)):
                        $animation_classes = ['fadeInLeft', 'fadeInUp', 'fadeInRight'];
                        foreach ($cta_solutions as $index => $solution_post):
                            setup_postdata($solution_post);
                            $animation = $animation_classes[$index % 3];
                    ?>
                    <div class="btn-all wow <?php echo $animation; ?> d-inline-block mx-2 mb-3">
                        <a href="<?php echo esc_url(get_permalink($solution_post->ID)); ?>"><?php echo esc_html(get_the_title($solution_post->ID)); ?></a>
                    </div>
                    <?php 
                        endforeach;
                        wp_reset_postdata();
                    endif; 
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if (is_solution_section_enabled('final_cta')): ?>
<!-- final-cta-button -->
<section class="final-cta-area">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="final-cta-main text-center">
                    <div class="btn-all wow fadeInUp">
                        <?php 
                        $final_cta_link = get_field('final_cta_url');
                        $final_cta_url = is_array($final_cta_link) ? $final_cta_link['url'] : '#';
                        $final_cta_target = is_array($final_cta_link) && !empty($final_cta_link['target']) ? $final_cta_link['target'] : '_self';
                        $final_cta_text = get_field('final_cta_text') ?: 'Request a demo';
                        ?>
                        <a href="<?php echo esc_url($final_cta_url); ?>" target="<?php echo esc_attr($final_cta_target); ?>"><?php echo esc_html($final_cta_text); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<?php 
// Contact Form Section
$contact_form_id = get_field('solution_contact_form');
$contact_form_title = get_field('solution_contact_form_title') ?: 'Get in Touch';

if ($contact_form_id && function_exists('wpcf7_contact_form')): 
?>
<!-- contact-form-section -->
<section class="solution-contact-form-area">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="solution-contact-form-main">
                    <?php if ($contact_form_title): ?>
                    <div class="solution-contact-title text-center mb-5">
                        <h2 class="wow fadeInUp"><?php echo esc_html($contact_form_title); ?></h2>
                    </div>
                    <?php endif; ?>
                    <div class="solution-contact-form-wrapper wow fadeInUp">
                        <?php echo do_shortcode('[contact-form-7 id="' . intval($contact_form_id) . '"]'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<style>
/* Solution Template Styles */

/* Diagram Section */
.diagram-area {
    padding: 80px 0;
    background: #f8f9fa;
}

.diagram-image {
    max-width: 400px;
    margin: 0 auto 30px;
}

.diagram-image img {
    width: 100%;
    height: auto;
}

.diagram-text p {
    font-size: 1.5rem;
    line-height: 1.6;
    max-width: 800px;
    margin: 0 auto;
    color: #666;
}

/* Key Benefits Section */
.key-benefits-area {
    padding: 80px 0;
    background: #f5f5f5;
}

.key-benefits-area .all-title {
    max-width: 890px;
    margin: 40px auto;
    padding: 0 20px;
    letter-spacing: 0px;
}

.key-benefits-area .all-title h2 {
    color: #005A5B;
    font-size: 3rem;
    line-height: 3.5rem;
    font-weight: 700;
    letter-spacing:0px;
}

.benefit-card {
    background: #fff;
    border-radius: 12px;
    padding: 40px 30px;
    text-align: left;
    height: 100%;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.benefit-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
}

.benefit-icon {
    margin-bottom: 20px;
}

.benefit-icon img {
    max-width: 60px;
    height: auto;
}

.benefit-card h4 {
    font-size: 20px;
    font-weight: 700;
    margin-bottom: 15px;
    line-height: 1.3;
    color: #EB7923; /* Brand orange color */
}

.benefit-card p {
    font-size: 15px;
    line-height: 1.6;
    color: #333;
    margin: 0;
}

/* Benefits Carousel Styles */
.benefits-carousel-wrapper {
    margin-top: 40px;
    position: relative;
}

/* Fallback display before carousel initializes */
.benefits-carousel {
    display: block !important;
    width: 100%;
}

/* Before Owl Carousel loads - show as flexbox grid */
.benefits-carousel:not(.owl-loaded) {
    display: flex !important;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: flex-start;
}

.benefits-carousel:not(.owl-loaded) .benefit-card {
    flex: 0 0 calc(25% - 20px);
    margin: 0;
    display: flex;
    flex-direction: column;
}

/* After Owl Carousel loads */
.benefits-carousel.owl-loaded {
    display: block !important;
}

.benefits-carousel .owl-stage-outer {
    padding: 20px 0;
}

/* Force equal height for carousel items */
.benefits-carousel .owl-stage {
    display: flex !important;
    align-items: stretch !important;
}

.benefits-carousel .owl-item {
    display: flex !important;
    height: auto !important;
}

.benefits-carousel.owl-loaded .benefit-card {
    margin: 0 !important;
    flex: 1 !important;
    display: flex !important;
    flex-direction: column !important;
    height: 100% !important;
}

.benefit-description {
    position: relative;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.benefit-text {
    margin-bottom: 10px;
    flex-grow: 1;
}

.benefit-short,
.benefit-full {
    display: inline;
}

.benefit-full {
    display: none !important;
}

.benefit-short.hidden {
    display: none !important;
}

.benefit-full.visible {
    display: inline !important;
}

.benefit-read-more {
    background: transparent;
    border: none;
    color: #EB7923;
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    padding: 5px 0 0 0;
    text-decoration: underline;
    transition: color 0.3s ease;
    margin-top: auto;
    display: block;
    width: fit-content;
}

.benefit-read-more:hover {
    color: #00524C;
}

.benefit-read-more:focus {
    outline: none;
}

/* Responsive adjustments for carousel */
@media (max-width: 991px) {
    .benefits-carousel-wrapper {
        margin-top: 30px;
    }
    
    .benefits-carousel:not(.owl-loaded) .benefit-card {
        flex: 0 0 calc(33.333% - 20px);
    }
    
    .benefit-card {
        padding: 30px 20px;
    }
    
    .benefit-card h4 {
        font-size: 18px;
    }
    
    .benefit-card p {
        font-size: 14px;
    }
}

@media (max-width: 767px) {
    .benefits-carousel:not(.owl-loaded) .benefit-card {
        flex: 0 0 calc(50% - 20px);
    }
    
    .benefit-card {
        padding: 25px 15px;
    }
    
    .benefit-icon img {
        max-width: 50px;
    }
    
    .benefit-card h4 {
        font-size: 16px;
        margin-bottom: 10px;
    }
    
    .benefit-card p {
        font-size: 13px;
    }
    
    .benefit-read-more {
        font-size: 13px;
    }
    
    .benefits-carousel .owl-nav button {
        width: 40px;
        height: 40px;
        font-size: 20px;
        margin: 0 5px;
    }
}

@media (max-width: 575px) {
    .benefits-carousel:not(.owl-loaded) .benefit-card {
        flex: 0 0 100%;
    }
}

/* Force navigation visibility */
#benefitsCarousel .owl-nav,
#benefitsCarousel .owl-dots {
    display: block !important;
}

/* Three Words Banner */
.three-words-area {
    padding: 80px 0;
    background: #fff;
}

.three-words-main {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 30px;
    flex-wrap: nowrap; /* Keep in one row by default */
}

.three-word-item {
    background: transparent;
    padding: 3px; /* Space for gradient border */
    border-radius: 80px;
    background: linear-gradient(90deg, #EB7923 0%, #F4A261 50%, #EB7923 100%);
    display: inline-block;
    flex-shrink: 1; /* Allow items to shrink if needed */
}

.three-word-item h3 {
    font-size: 3.75rem; /* 60px */
    font-weight: 700;
    color: #EB7923; /* Orange text */
    padding: 20px 60px;
    border: none;
    border-radius: 77px; /* Slightly smaller to show gradient border */
    margin: 0;
    white-space: nowrap;
    background: #fff;
    transition: all 0.3s ease;
    display: block;
}

.three-word-item h3:hover {
    background: linear-gradient(90deg, #EB7923 0%, #F4A261 50%, #EB7923 100%);
    color: #fff;
}

/* Responsive: wrap only on tablets and mobile (below 1024px) */
@media (max-width: 1023px) {
    .three-words-main {
        flex-wrap: wrap; /* Allow wrapping on smaller screens */
        gap: 20px;
    }
    
    .three-word-item h3 {
        font-size: 2.5rem; /* 40px - reduce font size on tablets */
        padding: 15px 40px;
    }
}

@media (max-width: 767px) {
    .three-words-area {
        padding: 60px 0;
    }
    
    .three-words-main {
        gap: 15px;
    }
    
    .three-word-item h3 {
        font-size: 2rem; /* 32px - further reduce on mobile */
        padding: 12px 30px;
    }
}

/* Platform Empowerment Section */
.platform-empowerment-section {
    padding: 80px 0;
}

/* Add padding to create space between columns */
.platform-empowerment-section .platform-text-col .platform-text {
    padding-right: 40px; /* Add padding to right of left column */
}

.platform-empowerment-section .platform-image-col .platform-image {
    padding-left: 40px; /* Add padding to left of right column */
}

.platform-empowerment-section h2 {
    font-size: 2.5rem;
    font-weight: 700;
    color: #005A5B;
    margin-bottom: 10px;
    line-height: 1;
}

.platform-subtitle {
    font-size: 32px;
    color: #FF6B35;
    font-weight: 500;
    margin-bottom: 40px;
    margin-top:20px;
}

.platform-text p {
    font-size: 16px;
    line-height: 1.8;
    color: #000;
    margin-bottom: 20px;
}

.platform-image img {
    width: 100%;
    height: auto;
    border-radius: 8px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

/* Responsive: Remove column padding on tablets and mobile */
@media (max-width: 991px) {
    .platform-empowerment-section .platform-text-col .platform-text {
        padding-right: 0;
    }
    
    .platform-empowerment-section .platform-image-col .platform-image {
        padding-left: 0;
    }
}

/* Case Studies Section */
.case-studies-section {
    padding: 80px 0;
    background: #fff;
}

.case-study-content h4 {
    font-size: 28px;
    font-weight: 500;
    margin-bottom: 20px;
    color: #EB7923;
}

.case-study-content p {
    font-size: 16px;
    line-height: 1.8;
    color: #666;
    margin-bottom: 25px;
}

.case-study-image img {
    width: 100%;
    height: auto;
    border-radius: 8px;
}

/* Bottom CTA Buttons */
.bottom-cta-area {
    padding: 60px 0;
    background: #fff;
}

.bottom-cta-main .btn-all {
    margin: 10px;
}

/* Final CTA Button */
.final-cta-area {
    padding: 80px 0;
    /* background: linear-gradient(135deg, #005a5b 0%, #003d3e 100%); */
}

.final-cta-main .btn-all a {
    background: #EB7923; /* Theme orange color */
    font-size: 20px;
    padding: 15px 50px;
    color: #fff;
    text-transform: uppercase;
    font-weight: 600;
    border-radius: 40px;
    transition: all 0.3s ease;
}

.final-cta-main .btn-all a:hover {
    background: #00524C; /* Theme green on hover */
}

/* Contact Form Section */
.solution-contact-form-area {
    padding: 80px 0;
    background: #f8f9fa;
}

.solution-contact-title h2 {
    font-size: 2.5rem;
    font-weight: 700;
    color: #005A5B;
    margin-bottom: 20px;
}

.solution-contact-form-wrapper {
    max-width: 800px;
    margin: 0 auto;
    background: #fff;
    padding: 40px;
    border-radius: 12px;
    box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
}

/* Responsive */
@media (max-width: 991px) {
    .three-word-item {
        padding: 2.5px; /* Slightly thinner gradient border */
        border-radius: 60px;
    }
    
    .three-word-item h3 {
        font-size: 2.5rem; /* 40px for tablet */
        padding: 15px 45px;
        border-radius: 57.5px;
    }
    
    .platform-empowerment-section h2 {
        font-size: 28px;
    }
    
    .case-study-content h4 {
        font-size: 24px;
    }
}

@media (max-width: 767px) {
    .diagram-area,
    .key-benefits-area,
    .three-words-area,
    .platform-empowerment-section,
    .case-studies-section,
    .solution-contact-form-area {
        padding: 60px 0;
    }
    
    .three-word-item {
        padding: 2px; /* Even thinner gradient border for mobile */
        border-radius: 50px;
    }
    
    .three-word-item h3 {
        font-size: 1.75rem; /* 28px for mobile */
        padding: 12px 35px;
        border-radius: 48px;
    }
    
    .bottom-cta-main .btn-all {
        display: block !important;
        margin: 10px auto !important;
        max-width: 300px;
    }
    
    .solution-contact-title h2 {
        font-size: 2rem;
    }
    
    .solution-contact-form-wrapper {
        padding: 30px 20px;
    }
    
    /* Ensure image always shows first on mobile for case studies */
    .case-studies-section .order-1 {
        order: 1 !important;
    }
    
    .case-studies-section .order-2 {
        order: 2 !important;
    }
}

/* Case Studies Section Title */
.case-studies-section .ipsum-title-main h2 {
    font-size: 48px;
    font-weight: 700;
    color: #005A5B;
    margin-bottom: 10px;
    line-height: 1;
}

.case-studies-section .ipsum-title-main p {
    font-size: 15px;
    color: #000;
    font-weight: 500;
    margin-bottom: 40px;
    margin-top: 20px;
}

@media (max-width: 991px) {
    .case-studies-section .ipsum-title-main h2 {
        font-size: 3rem;
    }
    
    .case-studies-section .ipsum-title-main p {
        font-size: 24px;
    }
}

@media (max-width: 767px) {
    .case-studies-section .ipsum-title-main h2 {
        font-size: 28px;
    }
    
    .case-studies-section .ipsum-title-main p {
        font-size: 20px;
    }
}
</style>

<?php
endwhile;

get_footer();

<?php

/**
 * The front page template
 *
 * This template is used for the front page when it's set to display a static page
 * in WordPress Admin > Settings > Reading > "Your homepage displays" > "A static page"
 *
 * @package ChainThat
 * @version 1.0.0
 */

// Helper function to check if a section should be displayed
function is_front_section_enabled($section_name) {
    $section_controls = get_field('front_page_section_controls');
    if (!$section_controls) return true;
    $toggle_field = $section_name . '_section_toggle';
    return isset($section_controls[$toggle_field]) ? $section_controls[$toggle_field] === 'show' : true;
}

get_header(); ?>


<!-- hero-mobil -->
<?php if (is_front_section_enabled('hero')): ?>
<div class="hero-mobil d-lg-none wow fadeInLeft">
    <?php 
    $hero_section = get_field('hero_section');
    $hero_mobile_image = $hero_section['hero_mobile_image'] ?? '';
    if ($hero_mobile_image): ?>
        <img src="<?php echo esc_url($hero_mobile_image); ?>" alt="Hero Mobile">
    <?php else: ?>
        <img src="<?php echo get_template_directory_uri(); ?>/images/hero-mobil.png" alt="Hero Mobile">
    <?php endif; ?>
</div>
<?php endif; ?>



<!-- hero-section -->
<?php if (is_front_section_enabled('hero')): ?>
<section class="hero-area">
    <div class="swoos-item wow fadeInRight" aria-hidden="true">
        <?php 
        $hero_swoos_image = $hero_section['hero_swoos_image'] ?? '';
        if ($hero_swoos_image): ?>
            <img src="<?php echo esc_url($hero_swoos_image); ?>" alt="" role="presentation">
        <?php else: ?>
            <img src="<?php echo get_template_directory_uri(); ?>/images/swoos.png" alt="" role="presentation">
        <?php endif; ?>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-12">
        <div class="hero-main">
             <div class="main-content1">
                <div id="owl-csel1" class="owl-carousel owl-theme">
                    <?php 
                    $hero_slider = $hero_section['hero_slider'] ?? [];
                    if ($hero_slider && is_array($hero_slider) && !empty($hero_slider)): 
                        foreach ($hero_slider as $slide): ?>
                            <div class="hero-item">
                                <h2 class="wow fadeInRight"><?php echo esc_html($slide['hero_slide_title'] ?? ''); ?></h2>
                                <p class="wow fadeInLeft"><?php echo esc_html($slide['hero_slide_description'] ?? ''); ?></p>
                                <a class="wow fadeInUp" href="<?php echo esc_url($slide['hero_slide_button_link'] ?? '#'); ?>"><?php echo esc_html($slide['hero_slide_button_text'] ?? 'Book a demo'); ?></a>
                            </div>
                        <?php endforeach;
                    endif; ?>
                </div>
             </div>
        </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>
<!-- partners-section -->
<?php if (is_front_section_enabled('partners')): ?>
<section class="partners-area">

    <div class="container partners-container">

        <div class="row">

            <div class="col-12">

        <div class="partners-title wow fadeInLeft">

            <?php 

            $partners_section = get_field('partners_section');

            $partners_title = $partners_section['partners_title'] ?? '';

            ?>

            <h2><?php echo esc_html($partners_title ?: 'Trusted by our partners'); ?></h2>

        </div>

         <div class="partners-mobil">

            <div class="main-content2">

                <div id="owl-csel2" class="owl-carousel owl-theme">

                    <?php 

                    // Get featured partners from ACF relationship field

                    $partners_section = get_field('partners_section');

                    $featured_partners = $partners_section['partners_featured'] ?? [];
                    
                    // Get carousel control settings
                    $items_per_page = $partners_section['partners_items_per_page'] ?? 10;
                    $carousel_autoplay = $partners_section['partners_carousel_autoplay'] ?? true;
                    $carousel_speed = $partners_section['partners_carousel_speed'] ?? 3000;

                    

                    if ($featured_partners && is_array($featured_partners) && !empty($featured_partners)):

                        // Use ACF selected partners

                        foreach ($featured_partners as $index => $partner): 

                            $animation_class = ($index % 2 == 0) ? 'fadeInRight' : 'fadeInLeft';

                            $item_class = 'partners-item' . ($index + 1);

                            $partner_logo = get_field('partner_logo', $partner->ID);

                            $partner_alt = get_field('partner_alt_text', $partner->ID) ?: $partner->post_title;

                            $partner_website = get_field('partner_website', $partner->ID);

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

                    else:

                        // Fallback to latest partners if none selected

                        $fallback_partners = new WP_Query(array(

                            'post_type' => 'partners',

                            'posts_per_page' => $items_per_page,

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

                        

                        if ($fallback_partners->have_posts()):

                            $index = 0;

                            while ($fallback_partners->have_posts()): 

                                $fallback_partners->the_post();

                                $animation_class = ($index % 2 == 0) ? 'fadeInRight' : 'fadeInLeft';

                                $item_class = 'partners-item' . ($index + 1);

                                $partner_logo = get_field('partner_logo');

                                $partner_alt = get_field('partner_alt_text') ?: get_the_title();

                                $partner_website = get_field('partner_website');

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

                                <?php 

                                $index++;

                            endwhile;

                            wp_reset_postdata();

                        endif;

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

</section>

<?php endif; ?>

<?php if (is_front_section_enabled('swoos')): ?>
<div class="swoos2-item wow fadeInRight" aria-hidden="true">

    <?php 

    $swoos_section = get_field('swoos_section');

    $swoos2_desktop_image = $swoos_section['swoos2_desktop_image'] ?? '';

    $swoos2_mobile_image = $swoos_section['swoos2_mobile_image'] ?? '';

    ?>

    <img class="d-none d-lg-block" src="<?php echo esc_url($swoos2_desktop_image ?: get_template_directory_uri() . '/images/swoos2.png'); ?>" alt="" role="presentation">

    <img class="d-block d-lg-none" src="<?php echo esc_url($swoos2_mobile_image ?: get_template_directory_uri() . '/images/swoos2-m.png'); ?>" alt="" role="presentation">

</div>
<?php endif; ?>
<!-- policy-section -->
<?php if (is_front_section_enabled('policy')): ?>
<section id="platsec" class="policy-area">

    <div class="container">

        <div class="row">

            <div class="col-12">

        <div class="policy-title text-center">

            <?php 

            $policy_section = get_field('policy_section');

            $policy_span_text = $policy_section['policy_span_text'] ?? '';

            $policy_title = $policy_section['policy_title'] ?? '';

            $policy_description = $policy_section['policy_description'] ?? '';

            ?>

            <span class="wow fadeInLeft"><?php echo esc_html($policy_span_text ?: 'Pain Relievers & Gain Creators'); ?></span>

            <h2 class="wow fadeInRight"><?php echo esc_html($policy_title ?: 'Platforms to Build on'); ?></h2>

            <p class="wow fadeInLeft"><?php echo esc_html($policy_description ?: 'ChainThat\'s enterprise-grade, highly configurable insurance technology platforms – Beyond Policy Administration (BPA) and Beyond Multi-National Programs (BMNP) – activate agility and innovation in processes and operations, empowering your business to thrive in competitive spaces.'); ?></p>

        </div>

        <div class="policy-main">

            <?php 

            // Query platform posts

            $platform_posts = new WP_Query(array(

                'post_type' => 'platform',

                'posts_per_page' => -1,

                'post_status' => 'publish',

                'orderby' => 'menu_order',

                'order' => 'ASC'

            ));

            

            if ($platform_posts->have_posts()): 

                $platform_count = $platform_posts->found_posts;

                

                // Desktop: Static 3-column layout (above 1024px)

                if ($platform_count <= 3): ?>

                    <div class="row d-none d-xl-flex">

                        <?php 

                        $animation_classes = ['fadeInLeft', 'fadeInRight', 'fadeInUp'];

                        $index = 0;

                        while ($platform_posts->have_posts()): 

                            $platform_posts->the_post();

                            $animation_class = $animation_classes[$index % 3];

                            $platform_short_name = get_field('platform_short_name') ?: substr(get_the_title(), 0, 4);

                            $platform_button_text = get_field('platform_button_text') ?: 'Activate your agility';

                            $platform_button_link = get_permalink();

                            ?>

                            <div class="col-lg-4 mb-4">

                                <div class="policy-item h-100 wow <?php echo $animation_class; ?>">

                                    <div class="policy-content">

                                        <h4 class="wow <?php echo $animation_class; ?>"><?php echo esc_html(get_the_title()); ?></h4>

                                        <div class="policy-circle wow fadeInLeft">

                                            <img src="<?php echo get_template_directory_uri(); ?>/images/policy.png" alt="">

                                            <div class="policy-text">

                                                <h5><?php echo esc_html($platform_short_name); ?></h5>

                                            </div>

                                        </div>

                                        <p><?php echo esc_html(get_field('platform_description') ?: get_the_excerpt()); ?></p>

                                    </div>

                                    <div class="policy-button">

                                        <a href="<?php echo esc_url($platform_button_link); ?>"><?php echo esc_html($platform_button_text); ?></a>

                                    </div>

                                </div>

                            </div>

                            <?php 

                            $index++;

                        endwhile; ?>

                    </div>

                <?php else: ?>

                    <!-- Desktop: Carousel for more than 3 platforms (above 1024px) -->

                    <div id="owl-csel-policy-desktop" class="owl-carousel owl-theme d-none d-xl-block">

                        <?php while ($platform_posts->have_posts()): 

                            $platform_posts->the_post();

                            $platform_short_name = get_field('platform_short_name') ?: substr(get_the_title(), 0, 4);

                            $platform_button_text = get_field('platform_button_text') ?: 'Activate your agility';

                            $platform_button_link = get_permalink();

                            ?>

                            <div class="policy-item">

                                <div class="policy-content">

                                    <h4 class="wow fadeInLeft"><?php echo esc_html(get_the_title()); ?></h4>

                                    <div class="policy-circle wow fadeInLeft">

                                        <img src="<?php echo get_template_directory_uri(); ?>/images/policy.png" alt="">

                                        <div class="policy-text">

                                            <h5><?php echo esc_html($platform_short_name); ?></h5>

                                        </div>

                                    </div>

                                    <p><?php echo esc_html(get_field('platform_description') ?: get_the_excerpt()); ?></p>

                                </div>

                                <div class="policy-button">

                                    <a href="<?php echo esc_url($platform_button_link); ?>"><?php echo esc_html($platform_button_text); ?></a>

                                </div>

                            </div>

                        <?php endwhile; ?>

                    </div>

                <?php endif;

                

                // Reset query for tablet and mobile carousels

                wp_reset_postdata();

                $platform_posts = new WP_Query(array(

                    'post_type' => 'platform',

                    'posts_per_page' => -1,

                    'post_status' => 'publish',

                    'orderby' => 'menu_order',

                    'order' => 'ASC'

                ));

                

                // Tablet: 2 cards carousel (768px - 1023px)

                if ($platform_posts->have_posts()): ?>

                    <div id="owl-csel-policy-tablet" class="owl-carousel owl-theme d-none d-lg-block d-xl-none">

                        <?php while ($platform_posts->have_posts()): 

                            $platform_posts->the_post();

                            $platform_short_name = get_field('platform_short_name') ?: substr(get_the_title(), 0, 4);

                            $platform_button_text = get_field('platform_button_text') ?: 'Activate your agility';

                            $platform_button_link = get_permalink();

                            ?>

                            <div class="policy-item">

                                <div class="policy-content">

                                    <h4 class="wow fadeInLeft"><?php echo esc_html(get_the_title()); ?></h4>

                                    <div class="policy-circle wow fadeInLeft">

                                        <img src="<?php echo get_template_directory_uri(); ?>/images/policy.png" alt="">

                                        <div class="policy-text">

                                            <h5><?php echo esc_html($platform_short_name); ?></h5>

                                        </div>

                                    </div>

                                    <p><?php echo esc_html(get_field('platform_description') ?: get_the_excerpt()); ?></p>

                                </div>

                                <div class="policy-button">

                                    <a href="<?php echo esc_url($platform_button_link); ?>"><?php echo esc_html($platform_button_text); ?></a>

                                </div>

                            </div>

                        <?php endwhile; ?>

                    </div>

                    

                    <!-- Mobile: 1 card carousel (below 768px) -->

                    <div id="owl-csel-policy-mobile" class="owl-carousel owl-theme d-block d-lg-none">

                        <?php 

                        wp_reset_postdata();

                        $platform_posts = new WP_Query(array(

                            'post_type' => 'platform',

                            'posts_per_page' => -1,

                            'post_status' => 'publish',

                            'orderby' => 'menu_order',

                            'order' => 'ASC'

                        ));

                        while ($platform_posts->have_posts()): 

                            $platform_posts->the_post();

                            $platform_short_name = get_field('platform_short_name') ?: substr(get_the_title(), 0, 4);

                            $platform_button_text = get_field('platform_button_text') ?: 'Activate your agility';

                            $platform_button_link = get_permalink();

                            ?>

                            <div class="policy-item">

                                <div class="policy-content">

                                    <h4 class="wow fadeInLeft"><?php echo esc_html(get_the_title()); ?></h4>

                                    <div class="policy-circle wow fadeInLeft">

                                        <img src="<?php echo get_template_directory_uri(); ?>/images/policy.png" alt="">

                                        <div class="policy-text">

                                            <h5><?php echo esc_html($platform_short_name); ?></h5>

                                        </div>

                                    </div>

                                    <p><?php echo esc_html(get_field('platform_description') ?: get_the_excerpt()); ?></p>

                                </div>

                                <div class="policy-button">

                                    <a href="<?php echo esc_url($platform_button_link); ?>"><?php echo esc_html($platform_button_text); ?></a>

                                </div>

                            </div>

                        <?php endwhile; ?>

                    </div>

                <?php endif;

                wp_reset_postdata();

            endif; ?>

                                    </div>

                                </div>

        </div>

    </div>

</section>
<?php endif; ?>
<!-- year-section -->
<?php if (is_front_section_enabled('year')): ?>
<section class="year-area">

    <div class="container">

        <div class="row">

            <div class="col-12">

        <div class="year-main wow fadeInUp">

                        <?php 
                        // Query all published platform posts to get their featured images
                        $platforms_query = new WP_Query(array(
                            'post_type' => 'platform',
                            'post_status' => 'publish',
                            'posts_per_page' => -1, // Get all platforms
                            'orderby' => 'menu_order',
                            'order' => 'ASC'
                        ));
                        
                        $platform_images = array();
                        
                        if ($platforms_query->have_posts()): 
                            while ($platforms_query->have_posts()): $platforms_query->the_post();
                                $featured_image = get_the_post_thumbnail_url(get_the_ID(), 'full');
                                if ($featured_image) {
                                    $platform_images[] = array(
                                        'image' => $featured_image,
                                        'title' => get_the_title()
                                    );
                                }
                            endwhile;
                            wp_reset_postdata();
                        endif;
                        
                        // Always show carousel if we have platforms (even just 1)
                        if (!empty($platform_images)): ?>

                                <div id="owl-csel-year-bg" class="owl-carousel owl-theme">

                                <?php foreach ($platform_images as $platform): ?>

                                        <div class="item">

                                        <img class="d-none d-lg-block" src="<?php echo esc_url($platform['image']); ?>" alt="<?php echo esc_attr($platform['title']); ?>">

                                        <img class="d-block d-lg-none" src="<?php echo esc_url($platform['image']); ?>" alt="<?php echo esc_attr($platform['title']); ?>">

                                        </div>

                                    <?php endforeach; ?>

                                </div>

                        <?php endif; ?>

        </div>

        

        <?php 

        $year_statistics = get_field('year_statistics');

        

        if ($year_statistics && is_array($year_statistics) && !empty($year_statistics)): ?>

            <!-- Year Statistics Grid -->

            <div class="yearbox-wrap">

                <?php foreach ($year_statistics as $statistic): ?>

                   <div class="year-box wow fadeInUp">

                       <div class="year-inner">

                            <?php 
                            // Use isset() to allow "0" as a valid value
                            $stat_number = isset($statistic['year_stat_number']) ? $statistic['year_stat_number'] : '';
                            $stat_description = isset($statistic['year_stat_description']) ? $statistic['year_stat_description'] : '';
                            ?>
                            
                            <h2 class="wow fadeInLeft"><?php echo esc_html($stat_number); ?></h2>

                            <p class="wow fadeInRight"><?php echo esc_html($stat_description); ?></p>

                       </div>   

                    </div>

                <?php endforeach; ?>

            </div>

        <?php endif; ?>

                </div>

            </div>

        </div>

    </div>

</section>
<?php endif; ?>
<!-- service-section -->
<?php if (is_front_section_enabled('service')): ?>
<section class="service-area">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="service-title">
                    <?php 
                    $service_section = get_field('service_section');
                    $service_title = $service_section['service_title'] ?? '';
                    $service_description = $service_section['service_description'] ?? '';
                    ?>
                    <h2 class="wow fadeInLeft"><?php echo esc_html($service_title ?: 'Why ChainThat?'); ?></h2>
                    <p class="wow fadeInRight"><?php echo esc_html($service_description ?: 'ChainThat\'s platform empowers you to launch innovative products and explore new insurance models efficiently. Our industry-leading technology ensures your business stays ahead of the curve.'); ?></p>
                </div>
                
                <!-- Unified 3-Column Carousel for All Screen Sizes -->
                <!-- Unified 3-Column Carousel for All Screen Sizes -->
                <div class="service-carousel wow fadeInUp">
                    <div class="main-content3">
                        <div id="owl-csel3" class="owl-carousel owl-theme">
                            <?php 
                            $service_cards = $service_section['service_cards'] ?? [];
                            if ($service_cards && is_array($service_cards) && !empty($service_cards)): 
                                foreach ($service_cards as $card): 
                                    $full_description = $card['service_description'] ?? '';
                                    $word_count = str_word_count($full_description);
                                    
                                    // Split the description at 25 words
                                    $words = explode(' ', $full_description);
                                    $short_text = implode(' ', array_slice($words, 0, 25));
                                    $remaining_text = $word_count > 25 ? ' ' . implode(' ', array_slice($words, 25)) : '';
                                    $has_more = $word_count > 25;
                                ?>
                                    <div class="service-item">
                                        <img class="wow fadeInRight" src="<?php echo esc_url($card['service_icon'] ?? ''); ?>" alt="">
                                        <h4 class="wow fadeInLeft"><?php echo esc_html($card['service_title'] ?? ''); ?></h4>
                                        <div class="service-description">
                                            <p class="service-text">
                                                <span class="service-short"><?php echo esc_html($short_text); ?></span><?php if ($has_more): ?><span class="service-ellipsis">...</span><span class="service-full" style="display: none;"><?php echo esc_html($remaining_text); ?></span><?php endif; ?>
                                            </p>
                                            <?php if ($has_more): ?>
                                            <button class="service-toggle-btn" onclick="toggleServiceText(this)" data-expanded="false">read more</button>
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
                
                <div class="btn-all service-btn text-center wow fadeInUp">
                    <?php 
                    $service_button_text = $service_section['service_button_text'] ?? '';
                    $service_button_link = $service_section['service_button_link'] ?? '';
                    ?>
                    <a href="<?php echo esc_url($service_button_link ?: '#'); ?>"><?php echo esc_html($service_button_text ?: 'Find out more'); ?></a>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>


<?php if (is_front_section_enabled('swoos')): ?>
<div class="swoos3-item wow fadeInRight" style="padding: 90px 0;" aria-hidden="true">

    <?php 

    $swoos3_desktop_image = get_field('swoos3_desktop_image');

    $swoos3_mobile_image = get_field('swoos3_mobile_image');

    ?>

    <img class="d-none d-lg-block" src="<?php echo esc_url($swoos3_desktop_image ?: get_template_directory_uri() . '/images/swoos3.png'); ?>" alt="" role="presentation">

    <img class="d-block d-lg-none" src="<?php echo esc_url($swoos3_mobile_image ?: get_template_directory_uri() . '/images/swoos3-m.png'); ?>" alt="" role="presentation">

</div>
<?php endif; ?>
<!-- working-with-chainthat-section -->
<?php if (is_front_section_enabled('working')): ?>
<section class="working-with-chainthat-area">

    <div class="container">

        <div class="row">

            <div class="col-12">

        <div class="working-with-chainthat-title text-center">

            <?php 

            $working_section = get_field('working_section');

            $working_title = $working_section['working_title'] ?? '';

            $working_description = $working_section['working_description'] ?? '';

            ?>

            <h2 class="wow fadeInLeft"><?php echo esc_html($working_title ?: 'Working with ChainThat'); ?></h2>

            <p class="wow fadeInRight"><?php echo esc_html($working_description ?: 'ChainThat\'s a cloud-native platform that improves market speed, customer satisfaction, and efficiency. Many insurers globally are benefiting, operationally and strategically, from such a platform. Let\'s hear it from our clients & partners.'); ?></p>

        </div>

        <div class="working-with-chainthat-main">

            <?php 

            $working_items = $working_section['working_items'] ?? [];

            if ($working_items && is_array($working_items) && !empty($working_items)): 

                foreach ($working_items as $index => $post): 

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

                    <!-- Desktop: Each case study in its own row -->
                    <div class="row align-items-center mb-5 d-none d-lg-flex">
                        <div class="col-lg-6 <?php echo $is_even ? 'order-lg-2' : 'order-lg-1'; ?>">
                            <div class="working-with-chainthat-cnt">
                                <h4 class="wow fadeInRight"><?php echo esc_html($display_title); ?></h4>
                                <p class="wow fadeInLeft"><?php echo esc_html($display_excerpt); ?></p>
                       <div class="btn-all working-with-chainthat-btn wow fadeInUp">
                                    <a href="<?php echo esc_url(get_permalink($post->ID)); ?>">Find out more</a>
                       </div>
                    </div>
                    </div>
                        <div class="col-lg-6 <?php echo $is_even ? 'order-lg-1' : 'order-lg-2'; ?>">
<div class="working-with-chainthat-img">
                                <?php if ($display_image): ?>
                                    <img src="<?php echo esc_url($display_image); ?>" alt="<?php echo esc_attr($display_title); ?>" class="img-fluid">
                                <?php endif; ?>
                                </div>
                            </div>
                        </div>

                    <!-- Mobile: Card Layout -->
                <div class="working-with-chainthat-card d-block d-lg-none mb-4">
                    <div class="card h-100">
                            <?php if ($display_image): ?>
                                <img class="card-img-top" src="<?php echo esc_url($display_image); ?>" alt="<?php echo esc_attr($display_title); ?>">
                            <?php endif; ?>
                        <div class="card-body d-flex flex-column">
                                <h4 class="card-title wow fadeInUp"><?php echo esc_html($display_title); ?></h4>
                                <p class="card-text wow fadeInUp"><?php echo esc_html($display_excerpt); ?></p>
                            <div class="btn-all working-with-chainthat-btn wow fadeInUp mt-auto">
                                    <a href="<?php echo esc_url(get_permalink($post->ID)); ?>">Find out more</a>
                            </div>
                        </div>
                    </div>
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

<!-- review-section -->
<?php if (is_front_section_enabled('review')): ?>
<section class="review-area">

    <div class="container">

        <div class="row">

            <div class="col-12">

        <div class="review-main">

            <div class="main-content4">

                <div id="owl-csel4" class="owl-carousel owl-theme">

                    <?php 

                    // Get selected reviews from ACF
                    $review_section = get_field('review_section');
                    $selected_reviews = ($review_section && isset($review_section['select_reviews'])) ? $review_section['select_reviews'] : array();
                    
                    // Query review posts
                    if (!empty($selected_reviews)) {
                        // Use selected reviews
                        $review_posts = new WP_Query(array(
                            'post_type' => 'review',
                            'post__in' => $selected_reviews,
                            'posts_per_page' => -1,
                            'post_status' => 'publish',
                            'orderby' => 'post__in'
                        ));
                    } else {
                        // Fallback to all reviews (latest 3)
                        $review_posts = new WP_Query(array(
                            'post_type' => 'review',
                            'posts_per_page' => 3,
                            'post_status' => 'publish',
                            'orderby' => 'menu_order',
                            'order' => 'ASC'
                        ));
                    }

                    

                    if ($review_posts->have_posts()): 

                        while ($review_posts->have_posts()): 

                            $review_posts->the_post();

                            $author_image = get_field('review_author_image') ?: get_the_post_thumbnail_url(get_the_ID(), 'medium');

                            $star_rating = get_field('review_star_rating') ?: 5;

                            $company_logo = get_field('review_company_logo');

                            $author_name = get_field('review_author_name') ?: get_the_title();

                            $author_title = get_field('review_author_title');

                            // Get success story link (Link field returns array)
                            $success_story_link_field = get_field('review_success_story_link');
                            $success_story_link = '';
                            $success_story_target = '_self';
                            
                            if ($success_story_link_field && is_array($success_story_link_field)) {
                                $success_story_link = $success_story_link_field['url'] ?? get_permalink();
                                $success_story_target = $success_story_link_field['target'] ?? '_self';
                            } else {
                                $success_story_link = get_permalink();
                            }

                            $success_story_text = get_field('review_success_story_text') ?: 'Read Success Story';

                            ?>

                            <div class="review-item">

                                <div class="review-img wow fadeInRight">

                                    <img src="<?php echo esc_url($author_image ?: get_template_directory_uri() . '/images/review1.png'); ?>" alt="<?php echo esc_attr($author_name); ?>">

                                </div>

                                <p class="wow fadeInLeft"><?php echo wp_trim_words(wp_strip_all_tags(get_the_content()), 30, '...'); ?></p>

                                <div class="star-img1 wow fadeInRight">

                                    <div class="star-rating" data-rating="<?php echo esc_attr($star_rating); ?>">

                                        <?php

                                        // Generate dynamic star display

                                        $full_stars = floor($star_rating);

                                        $half_star = ($star_rating - $full_stars) >= 0.5;

                                        $empty_stars = 5 - $full_stars - ($half_star ? 1 : 0);

                                        

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

                                    <h5 class="wow fadeInLeft"><?php echo esc_html($author_title); ?></h5>

                                </div>

                                <div class="btn-all review-btn wow fadeInUp">

                                    <a href="<?php echo esc_url($success_story_link); ?>" 
                                       target="<?php echo esc_attr($success_story_target); ?>"
                                       <?php echo ($success_story_target === '_blank') ? 'rel="noopener noreferrer"' : ''; ?>>
                                        <?php echo esc_html($success_story_text); ?>
                                    </a>

                                </div>

                            </div>

                        <?php endwhile;

                        wp_reset_postdata();

                    else: ?>

                        <div class="review-item">

                            <div class="review-img wow fadeInRight">

                                <img src="<?php echo get_template_directory_uri(); ?>/images/review1.png" alt="">

                            </div>

                            <p class="wow fadeInLeft">"Our partnership with ChainThat is more than just a business agreement. It's a commitment to continually innovate to deliver better products and a more efficient quoting experience for our brokers."</p>

                            <div class="star-img1 wow fadeInRight">

                                <img src="<?php echo get_template_directory_uri(); ?>/images/star1.png" alt="">

                            </div>

                            <div class="review-logo">

                                <img class="wow fadeInLeft" src="<?php echo get_template_directory_uri(); ?>/images/review3-1.jpg" alt="">

                                <h4 class="wow fadeInRight">Blair Nicholls</h4>

                                <h5 class="wow fadeInLeft">CEO, Clover Insurance</h5>

                            </div>

                        </div>



                        <div class="review-item">

                            <div class="review-img wow fadeInRight">

                                <img src="<?php echo get_template_directory_uri(); ?>/images/review1.png" alt="">

                            </div>

                            <p class="wow fadeInLeft">"Amparo's mission has always been to provide fair and accessible auto insurance to the immigrant community. Partnering with ChainThat allows us to leverage advanced technology to better serve our customers and streamline our processes."</p>

                            <div class="star-img1 wow fadeInRight">

                                <img src="<?php echo get_template_directory_uri(); ?>/images/star1.png" alt="">

                            </div>

                            <div class="review-logo">

                                <img class="wow fadeInLeft" src="<?php echo get_template_directory_uri(); ?>/images/review3-2.jpg" alt="">

                                <h4 class="wow fadeInRight">Pushan Sen Gupta</h4>

                                <h5 class="wow fadeInLeft">Co-Founder of Amparo Insurance</h5>

                            </div>

                            <div class="btn-all review-btn wow fadeInUp">

                                <a href="https://chainthat.com/contacts">Read Success Story</a>

                            </div>

                        </div>



                        <div class="review-item">

                            <div class="review-img wow fadeInRight">

                                <img src="<?php echo get_template_directory_uri(); ?>/images/review1.png" alt="">

                            </div>

                            <p class="wow fadeInLeft">"ChainThat's distributed ledger-centric platform supports consistency, compliance, and transparency in our multinational transactions, while delivering data clarity and protection across multinational accounts. It enables BHSI to seamlessly coordinate and collaborate across local underwriters, producing offices, and network partners, facilitating the execution of our multinational programs.</p>

                            <div class="star-img1 wow fadeInRight">

                                <img src="<?php echo get_template_directory_uri(); ?>/images/star1.png" alt="">

                            </div>

                            <div class="review-logo">

                                <img class="wow fadeInLeft" src="<?php echo get_template_directory_uri(); ?>/images/review3-3.png" alt="">

                                <h4 class="wow fadeInRight">Head of Multinational at BHSI</h4>

                                <h5 class="wow fadeInLeft">Company</h5>

                            </div>

                            <div class="btn-all review-btn wow fadeInUp">

                                <a href="https://chainthat.com/contacts">Read Success Story</a>

                            </div>

                        </div>

                    <?php endif; ?>

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
<?php endif; ?>

<!-- blog-section -->
<?php if (is_front_section_enabled('blog')): ?>

<section class="blog-area">

    <div class="container">

        <div class="row">

            <div class="col-12">

       <div class="blog-main-wrap">

         <div class="blog-title text-center">

            <?php 

            $blog_section = get_field('blog_section');

            $blog_title = $blog_section['blog_title'] ?? '';

            ?>

            <h2 class="wow fadeInLeft"><?php echo esc_html($blog_title ?: 'News & Insights'); ?></h2>

        </div>

            <div class="blog-main">

                <?php 

                // Get featured posts from ACF relationship field

                $blog_section = get_field('blog_section');

                $featured_posts = $blog_section['blog_featured_posts'] ?? [];
                
                // Get carousel control settings
                $items_per_page = $blog_section['blog_items_per_page'] ?? 6;
                $enable_carousel = $blog_section['blog_enable_carousel'] ?? true;
                $carousel_autoplay = $blog_section['blog_carousel_autoplay'] ?? true;

                
                if ($featured_posts && is_array($featured_posts) && !empty($featured_posts)):

                    // Use ACF selected posts

                    $news_posts = $featured_posts;

                    $use_acf_posts = true;

                else:

                    // Fallback to latest posts

                    $news_posts = new WP_Query(array(

                        'post_type' => 'news-and-insight',

                        'posts_per_page' => $items_per_page,

                        'post_status' => 'publish',

                        'orderby' => 'date',

                        'order' => 'DESC'

                    ));

                    $use_acf_posts = false;

                endif;

                

                if ($news_posts && (($use_acf_posts && !empty($news_posts)) || (!$use_acf_posts && $news_posts->have_posts()))): 

                    $news_count = $use_acf_posts ? count($news_posts) : $news_posts->found_posts;

                    

                    // Desktop: Static 3-column layout (if 3 or fewer posts) OR carousel disabled
                    $show_static_grid = ($news_count <= 3) || !$enable_carousel;

                    if ($show_static_grid): ?>

                        <div class="row d-none d-lg-flex">

                            <?php 

                            $animation_classes = ['fadeInLeft', 'fadeInUp', 'fadeInRight'];

                            $index = 0;

                            

                            if ($use_acf_posts):

                                // Loop through ACF selected posts

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

                                // Loop through WP_Query posts

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

                        <div id="owl-csel-blog-desktop" class="owl-carousel owl-theme d-none d-lg-block">

                            <?php 

                            if ($use_acf_posts):

                                // Loop through ACF selected posts

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

                                // Loop through WP_Query posts

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

                    <?php endif;

                    

                    // Tablet: 2 cards carousel

                    if ($news_posts && (($use_acf_posts && !empty($news_posts)) || (!$use_acf_posts && $news_posts->have_posts()))): ?>

                        <div id="owl-csel-blog-tablet" class="owl-carousel owl-theme d-none d-md-block d-lg-none">

                            <?php 

                            if ($use_acf_posts):

                                // Loop through ACF selected posts

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

                                // Loop through WP_Query posts

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

                        <div id="owl-csel-blog-mobile" class="owl-carousel owl-theme d-block d-md-none">

                            <?php 

                            if ($use_acf_posts):

                                // Loop through ACF selected posts

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

                                // Loop through WP_Query posts

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

                    <?php endif;

                    wp_reset_postdata();

                else: ?>

                    <div class="blog-item">

                        <a href="#">

                            <img class="wow fadeInLeft" src="<?php echo get_template_directory_uri(); ?>/images/blog1.png" alt="">

                        </a>

                        <ul class="wow fadeInRight">

                            <li>August 13, 2025</li>

                            <li>|</li>

                            <li>Blog Post</li>

                        </ul>

                        <h3 class="wow fadeInLeft"><a href="#">Amparo Reports Significant Gains with ChainThat's BPA Platform </a></h3>

                        <div class="blog-btn wow fadeInUp">

                            <a href="#">read more</a>

                        </div>

                    </div>



                    <div class="blog-item">

                        <a href="#">

                            <img class="wow fadeInLeft" src="<?php echo get_template_directory_uri(); ?>/images/blog2.png" alt="">

                        </a>

                        <ul class="wow fadeInRight">

                            <li>August 11, 2025</li>

                            <li>|</li>

                            <li>Blog Post</li>

                        </ul>

                        <h3 class="wow fadeInLeft"><a href="#">ChainThat Leadership Bytes ft Jai Prakash</a></h3>

                        <div class="blog-btn wow fadeInUp">

                            <a href="#">read more</a>

                        </div>

                    </div>



                    <div class="blog-item">

                        <a href="#">

                            <img class="wow fadeInLeft" src="<?php echo get_template_directory_uri(); ?>/images/blog3.png" alt="">

                        </a>

                        <ul class="wow fadeInRight">

                            <li>August 5, 2025</li>

                            <li>|</li>

                            <li>Blog Post</li>

                        </ul>

                        <h3 class="wow fadeInLeft"><a href="#">ChainThat Powers Everett Cash Mutual's FREbird™ Solution Through</a></h3>

                        <div class="blog-btn wow fadeInUp">

                            <a href="#">read more</a>

                        </div>

                    </div>

                <?php endif; ?>

            </div>

            

            <!-- View All Button -->

            <div class="text-center wow fadeInUp" style="margin-top: 50px;">

                <div class="btn-all blog-btm-btn">

                    <a href="<?php echo esc_url(get_post_type_archive_link('news-and-insight')); ?>">View All</a>

                </div>

            </div>

       </div> 

       <div class="blog-btm">

           <?php 

           $blog_bottom_title = $blog_section['blog_bottom_title'] ?? '';

           $blog_bottom_demo_text = $blog_section['blog_bottom_demo_text'] ?? '';

           $blog_bottom_demo_link = $blog_section['blog_bottom_demo_link'] ?? '';

           $blog_bottom_contact_text = $blog_section['blog_bottom_contact_text'] ?? '';

           $blog_bottom_contact_link = $blog_section['blog_bottom_contact_link'] ?? '';

           // Only show section if at least the title is set
           if ($blog_bottom_title):
           ?>

           <h3 class="wow fadeInRight"><?php echo esc_html($blog_bottom_title); ?></h3>

           <ul class="wow fadeInLeft">

               <?php if ($blog_bottom_demo_text && $blog_bottom_demo_link): ?>
               <li><div class="btn-all blog-btm-btn"><a href="<?php echo esc_url($blog_bottom_demo_link); ?>"><?php echo esc_html($blog_bottom_demo_text); ?></a></div></li>
               <?php endif; ?>

               <?php if ($blog_bottom_contact_text && $blog_bottom_contact_link): ?>
               <li><a href="<?php echo esc_url($blog_bottom_contact_link); ?>"><?php echo esc_html($blog_bottom_contact_text); ?> <span><img src="<?php echo get_template_directory_uri(); ?>/images/arrow3.png" alt=""></span></a></li>
               <?php endif; ?>

           </ul>

           <?php endif; ?>

       </div>  

               </div>

            </div>

       </div>  

    </div>

</section>

<script>
jQuery(document).ready(function($) {
    // Initialize News & Insights carousel with backend controls
    const blogCarouselAutoplay = <?php echo $carousel_autoplay ? 'true' : 'false'; ?>;
    
    if ($('#owl-csel-blog-desktop').length) {
        $('#owl-csel-blog-desktop').owlCarousel({
            items: 3,
            loop: true,
            margin: 30,
            nav: true,
            dots: true,
            autoplay: blogCarouselAutoplay,
            autoplayTimeout: 5000,
            autoplayHoverPause: true,
            responsive: {
                0: { items: 1 },
                768: { items: 2 },
                992: { items: 3 }
            }
        });
    }
    
    if ($('#owl-csel-blog-tablet').length) {
        $('#owl-csel-blog-tablet').owlCarousel({
            items: 2,
            loop: true,
            margin: 30,
            nav: true,
            dots: true,
            autoplay: blogCarouselAutoplay,
            autoplayTimeout: 5000,
            autoplayHoverPause: true,
            responsive: {
                0: { items: 1 },
                576: { items: 2 }
            }
        });
    }
    
    if ($('#owl-csel-blog-mobile').length) {
        $('#owl-csel-blog-mobile').owlCarousel({
            items: 1,
            loop: true,
            margin: 20,
            nav: true,
            dots: true,
            autoplay: blogCarouselAutoplay,
            autoplayTimeout: 5000,
            autoplayHoverPause: true
        });
    }
});
</script>

<?php endif; ?>


<?php get_footer(); ?>
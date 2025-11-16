<?php
/**
 * Template Name: About
 *
 * @package ChainThat
 * @version 1.0.0
 */

get_header(); ?>

<?php
// Helper function to check if section is enabled
function is_section_enabled($section_name) {
    $section_controls = get_field('about_section_controls');
    if (!$section_controls) return true;
    $toggle_field = $section_name . '_section_toggle';
    return isset($section_controls[$toggle_field]) ? $section_controls[$toggle_field] === 'show' : true;
}
?>

<?php if (is_section_enabled('hero')): ?>
<!-- about-section -->
<section class="about-area">
    <?php
    // Get ACF field values
    $hero_section = get_field('about_hero_section');
    $background_type = (isset($hero_section['about_hero_background_type']) && $hero_section['about_hero_background_type']) ? $hero_section['about_hero_background_type'] : 'image';
    $background_video = (isset($hero_section['about_hero_background_video']) && $hero_section['about_hero_background_video']) ? $hero_section['about_hero_background_video'] : '';
    $background_image = (isset($hero_section['about_hero_background_image']) && $hero_section['about_hero_background_image']) ? $hero_section['about_hero_background_image'] : get_template_directory_uri() . '/images/swoos3.png';
    ?>
    
    <?php if ($background_type === 'video' && $background_video): ?>
        <video class="about-background-video" autoplay muted loop playsinline>
            <source src="<?php echo esc_url($background_video); ?>" type="video/mp4">
        </video>
    <?php else: ?>
        <div class="about-background-image" style="background-image: url('<?php echo esc_url($background_image); ?>');"></div>
    <?php endif; ?>
    
    <div class="container">
        <div class="about-title text-center wow fadeInLeft">
             <h2><?php echo esc_html(isset($hero_section['about_hero_title']) && $hero_section['about_hero_title'] ? $hero_section['about_hero_title'] : 'About Us'); ?></h2>
        </div>
    </div>
     <div class="about-main wow fadeInUp">
       <div class="main-content5">
            <div id="owl-csel5" class="owl-carousel owl-theme">
                <?php
                $hero_images = (isset($hero_section['about_hero_images']) && $hero_section['about_hero_images']) ? $hero_section['about_hero_images'] : array();
                if ($hero_images):
                    foreach ($hero_images as $index => $image):
                        $image_url = $image['about_hero_image']['url'] ?? get_template_directory_uri() . '/images/about' . ($index + 2) . '.png';
                        $image_alt = $image['about_hero_image']['alt'] ?? '';
                        $animation_class = ($index % 2 == 0) ? 'wow fadeInRight' : 'wow fadeInLeft';
                        ?>
                        <div class="about-item <?php echo $animation_class; ?>">
                           <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($image_alt); ?>">
                        </div>
                        <?php
                    endforeach;
                else:
                    // Fallback images - 4 images: about2, about3, about4, about1
                    $fallback_images = [2, 3, 4, 1];
                    $fallback_animations = ['wow fadeInRight', 'wow fadeInLeft', 'wow fadeInRight', 'fadeInLeft'];
                    foreach ($fallback_images as $index => $img_num):
                        ?>
                        <div class="about-item <?php echo $fallback_animations[$index]; ?>">
                           <img src="<?php echo get_template_directory_uri(); ?>/images/about<?php echo $img_num; ?>.png" alt="">
                        </div>
                        <?php
                    endforeach;
                endif;
                ?>
            </div>
            <div class="owl-theme">
                <div class="owl-controls">
                    <div class="custom-nav owl-nav"></div>
                </div>
            </div>
        </div> 
    </div>
    <div class="about-mobil d-block d-lg-none" aria-hidden="true">
        <?php
        $mobile_image = (isset($hero_section['about_mobile_image']) && $hero_section['about_mobile_image']) ? $hero_section['about_mobile_image']['url'] : get_template_directory_uri() . '/images/swoos3-m.png';
        ?>
        <img src="<?php echo esc_url($mobile_image); ?>" alt="" role="presentation">
    </div>
    <div class="container">
        <div class="about-cnt">
            <span class="wow fadeInUp"><?php echo esc_html(isset($hero_section['about_hero_subtitle']) && $hero_section['about_hero_subtitle'] ? $hero_section['about_hero_subtitle'] : 'Meet ChainThat'); ?></span>
            <h2 class="wow fadeInLeft"><?php echo esc_html(isset($hero_section['about_hero_heading']) && $hero_section['about_hero_heading'] ? $hero_section['about_hero_heading'] : 'Insurance Innovators'); ?></h2>
            <div class="wow fadeInRight"><?php echo wp_kses_post(isset($hero_section['about_hero_description']) && $hero_section['about_hero_description'] ? $hero_section['about_hero_description'] : 'ChainThat Insurtech platforms help insurance organisations to realise their full potential. A team of insurance insiders, we use our industry insights and advanced tech capabilities to continually develop faster, more agile, and better-connected ways of doing business.'); ?></div>
        </div>
    </div> 
    <div class="about-swoos-item wow fadeInUp" aria-hidden="true">
        <img src="<?php echo get_template_directory_uri(); ?>/images/swoos3.png" alt="" role="presentation">
    </div>
</section>
<?php endif; ?>

<?php if (is_section_enabled('video')): ?>
<!-- about-video-area -->
<section class="about-video-area">
    <div class="container">
        <div class="video-wrapper wow fadeInUp" data-video-type="youtube" data-video-id="Wb6Oc1_SdJw">
            <img class="video-cover img-desktop" src="<?php echo get_template_directory_uri(); ?>/images/about-video.png" alt="Desktop Video 1">
            <img class="video-cover img-mobile" src="<?php echo get_template_directory_uri(); ?>/images/about-video-m.png" alt="Mobile Video 1">
            <iframe src="https://www.youtube.com/embed/WtQau-Orl5s" frameborder="0" allowfullscreen title="ChainThat Leadership Bytes ft Jai Prakash"></iframe>
            <button class="play-btn-kp" aria-label="Play video"><img src="<?php echo get_template_directory_uri(); ?>/images/play.png" alt="" role="presentation"></button>
            <button class="close-btn-kp" aria-label="Close video"><i class="fas fa-times" aria-hidden="true"></i></button>
        </div>
        <div class="about-video-cnt">
            <h4 class="wow fadeInLeft">ChainThat Leadership Bytes ft Jai Prakash</h4>
            <p class="wow fadeInRight">In this #LeadershipBytes episode, Jai Prakash Jhankal (AVP – Program Manager) shares how the team turned pressure into performance — delivering a 99.7% migration in just 3 days.</p>
            <div class="btn-all about-video-btn wow fadeInUp">
                <a href="#">Download Video</a>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if (is_section_enabled('leadership')): ?>
<!-- employ-section -->
<section id="leadership" class="employ-area">
    <div class="container">
        <?php
        $leadership_section = get_field('about_leadership_section');
        $leadership_title = $leadership_section['about_leadership_title'] ?? 'ChainThat Leadership';
        $leadership_description = $leadership_section['about_leadership_description'] ?? '';
        ?>
        <div class="employ-title">
            <?php if ($leadership_title): ?>
                <h2 class="wow fadeInRight"><?php echo esc_html($leadership_title); ?></h2>
            <?php endif; ?>
            <?php if ($leadership_description): ?>
                <div class="wow fadeInLeft"><?php echo wp_kses_post($leadership_description); ?></div>
            <?php endif; ?>
        </div>
        
        <?php
        // Get leadership team from ACF relationship field (show all selected)
        $leadership_team = get_field('about_leadership_team');
        $animation_classes = array('wow fadeInLeft', 'wow fadeInUp', 'wow fadeInRight');
        
        if ($leadership_team && is_array($leadership_team)):
        ?>
        
        <div class="employ-main">
            <?php 
            foreach ($leadership_team as $index => $team_member): 
                $member_position = get_field('team_position', $team_member->ID);
                $member_linkedin = get_field('team_linkedin', $team_member->ID);
                $member_image = get_the_post_thumbnail_url($team_member->ID, 'team-about');
                if (!$member_image) $member_image = get_template_directory_uri() . '/images/employ' . ($index + 1) . '.png';
            ?>
            <div class="employ-item <?php echo esc_attr($animation_classes[$index % 3]); ?>">
                <a href="<?php echo $member_linkedin ? esc_url($member_linkedin) : '#'; ?>"<?php echo $member_linkedin ? ' target="_blank"' : ''; ?>>
                    <img src="<?php echo esc_url($member_image); ?>" alt="<?php echo esc_attr($team_member->post_title); ?>">
                </a>
                <h3>
                    <a href="<?php echo $member_linkedin ? esc_url($member_linkedin) : '#'; ?>"<?php echo $member_linkedin ? ' target="_blank"' : ''; ?>>
                        <?php echo esc_html($team_member->post_title); ?>
                    </a>
                </h3>
                <?php if ($member_position): ?>
                    <h4><?php echo esc_html($member_position); ?></h4>
                <?php endif; ?>
                <ul>
                    <?php if ($member_linkedin): ?>
                    <li><a href="<?php echo esc_url($member_linkedin); ?>" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/images/linkden.png" alt="LinkedIn"></a></li>
                    <?php endif; ?>
                </ul>
            </div>
            <?php endforeach; ?>
        </div>
        
        <?php else: ?>
        <!-- No team members found -->
        <div class="employ-main">
            <div class="col-12 text-center">
                <p style="color: #999; padding: 40px 0;">No team members added yet. Please add team members in the Team section.</p>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>
<?php endif; ?>

<?php if (is_section_enabled('5ps')): ?>
<!-- our-section -->
<section class="our-area">
    <div class="container">
        <?php 
        $five_ps_section = get_field('about_5ps_section');
        $five_ps_items = $five_ps_section['about_5ps_items'] ?? [];
        ?>
        
        <div class="our-title">
            <span class="wow fadeInRight"><?php echo esc_html($five_ps_section['about_5ps_subtitle'] ?? 'How we work'); ?></span>
            <h2 class="wow fadeInLeft"><?php echo esc_html($five_ps_section['about_5ps_title'] ?? 'Our 5Ps'); ?></h2>
            <div class="wow fadeInUp"><?php echo wp_kses_post($five_ps_section['about_5ps_description'] ?? 'ChainThat platforms are the pain relievers and gain creators at the heart of your organisation. Our processes are robust, streamlined and collaborative, run by insurtech specialists with a can-do attitude. We work in close partnership with our customers and bring passion to everything we do.'); ?></div>
            </div> 

        <div class="our-main">
            <?php 
            $default_icons = ['our1.png', 'our2.png', 'our3.png', 'our4.png', 'our5.png'];
            $default_titles = ['People', 'Process', 'Platforms', 'Passion', 'Partnerships'];
            $default_descriptions = [
                "ChainThat's dedication to insurance organisations, and our skill at what we do, stems from our founders and leaders. Over a decade ago, they recognized the opportunity that technology provides to revolutionize the insurance industry. Many of them have worked in the insurance sector for most of their professional careers, including at Lloyd's, the insurance market for the world. Our leaders are insurance insiders and they have built a team with huge amounts of specialist knowledge. By hiring other people who've worked in the insurance industry, and by recruiting those who are motivated by the challenge of disrupting the sector through innovation.",
                "We have a clear purpose at ChainThat: to develop technology platforms that activate agility in insurance organisations, enabling them to realise their full business potential. This means building insurance technology platforms that enable organisations to be more streamlined, automated, agile and innovative. Platforms that enable them to operate effectively and launch products quickly, to meet real-life needs.",
                "Today, ChainThat has two insurance focused platforms in operation: Beyond Policy Administration (BPA) and Beyond Multi-National Programs (BMNP). With more in the pipeline. Both platforms offer enterprise grade technology that is easily configurable, quick to implement and begin using. With our industry insights and technological knowhow, we have created platforms with capabilities that \"Go Beyond\" what you get in standard policy administration or multinational program platforms. By deploying our \"Beyond\" platforms, insurers are taking a proactive step to enable and promote agility within their operations.",
                "As well as being insurance insiders, our whole team brings deep technology expertise, and an openness to sharing our technology with our customers and partners. This means they can build on it, tailor it to their specific needs, and help us build add-on features that are beneficial to all our customers and available via regular updates to the software. Start-ups and established insurance organisations alike can use our platforms to leapfrog old ways of doing business. We help them achieve unlimited connectivity across their partner and internal ecosystems, complete transactions quickly, and launch products faster. We're the ingredient that enables them to activate agility and better serve their customers with the right time, right place, right price capabilities that other industries do.",
                "ChainThat is leading the way in insurtech innovation, solving the day-to-day challenges of insurance organisations. Improving and advancing insurance has been our driver since our launch in 2015. Our founders saw an opportunity to help insurance organisations to do things better and drive down costs. Blockchain originally provided the source of our name, ChainThat. Our name became synonymous with blockchain in the insurance world, but our horizons soon expanded as we embraced new technology that went way beyond blockchain and distributed ledger technology. Throughout all this innovation we have kept the ChainThat name; we may have gone way beyond blockchain but our roots as a true insurance technology insiders are a core part of both our heritage and our future."
            ];
            
            if ($five_ps_items && count($five_ps_items) > 0):
                foreach ($five_ps_items as $index => $item):
                    $icon = $item['about_5ps_item_icon'] ?? null;
                    $icon_url = $icon ? $icon['url'] : get_template_directory_uri() . '/images/' . $default_icons[$index];
                    $title = $item['about_5ps_item_title'] ?? $default_titles[$index];
                    $description = $item['about_5ps_item_description'] ?? $default_descriptions[$index];
                    $short_description = wp_trim_words($description, 20, '...');
                    $first_class = ($index === 0) ? ' our-box2' : '';
            ?>
            <div class="our-box<?php echo $first_class; ?>" data-accordion-item>
                <div class="our-left">
                    <ul class="wow fadeInLeft">
                        <li><img src="<?php echo esc_url($icon_url); ?>" alt="<?php echo esc_attr($title); ?>"></li>
                        <li><?php echo esc_html($title); ?></li>
                    </ul>
                </div>
                <div class="our-center wow fadeInUp">
                    <div class="description-text">
                        <span class="short-desc"><?php echo wp_kses_post($short_description); ?></span>
                        <span class="full-desc" style="display: none;"><?php echo wp_kses_post($description); ?></span>
                    </div>
                </div>
                <div class="our-right wow fadeInRight">
                    <a href="#" class="accordion-toggle" data-expanded="false">
                        Read more<span class="arrow-icon"><img src="<?php echo get_template_directory_uri(); ?>/images/arrow1.svg" alt=""></span>
                    </a>
                </div>
            </div> 
            <?php 
                endforeach;
            else:
                // Fallback with default values
                for ($i = 0; $i < 5; $i++):
                    $first_class = ($i === 0) ? ' our-box2' : '';
                    $short_description = wp_trim_words($default_descriptions[$i], 20, '...');
            ?>
            <div class="our-box<?php echo $first_class; ?>" data-accordion-item>
                <div class="our-left">
                    <ul class="wow fadeInLeft">
                        <li><img src="<?php echo get_template_directory_uri(); ?>/images/<?php echo $default_icons[$i]; ?>" alt=""></li>
                        <li><?php echo $default_titles[$i]; ?></li>
                    </ul>
                </div>
                <div class="our-center wow fadeInUp">
                    <div class="description-text">
                        <span class="short-desc"><?php echo esc_html($short_description); ?></span>
                        <span class="full-desc" style="display: none;"><?php echo esc_html($default_descriptions[$i]); ?></span>
                    </div>
                </div>
                <div class="our-right wow fadeInRight">
                    <a href="#" class="accordion-toggle" data-expanded="false">
                        Read more<span class="arrow-icon"><img src="<?php echo get_template_directory_uri(); ?>/images/arrow1.svg" alt=""></span>
                    </a>
                </div>
            </div>
            <?php 
                endfor;
            endif;
            ?>
        </div>
    </div>
     <!-- Mobile Accordion Version -->
     <div class="our-mobil d-block d-lg-none">
         <div class="accordion-container wow fadeInUp">
            <?php 
            // Use same data as desktop version
            if ($five_ps_items && count($five_ps_items) > 0):
                foreach ($five_ps_items as $index => $item):
                    $icon = $item['about_5ps_item_icon'] ?? null;
                    $icon_url = $icon ? $icon['url'] : get_template_directory_uri() . '/images/' . $default_icons[$index];
                    $title = $item['about_5ps_item_title'] ?? $default_titles[$index];
                    $description = $item['about_5ps_item_description'] ?? $default_descriptions[$index];
            ?>
            <div class="ac">
                <div class="ac-trigger">
               <div class="our-mobil-wrap">
                   <ul>
                            <li><img src="<?php echo esc_url($icon_url); ?>" alt="<?php echo esc_attr($title); ?>"></li>
                            <li><?php echo esc_html($title); ?></li>
                   </ul>
               </div>
             </div>
                <div class="ac-panel">
                    <div class="ac-panel-cnt">
                       <div class="our-mobil-cnt">
                            <?php echo wp_kses_post($description); ?>
                       </div>
                    </div>
                </div>
            </div>
            <?php 
                endforeach;
            else:
                // Fallback with default values
                for ($i = 0; $i < 5; $i++):
            ?>
            <div class="ac">
                <div class="ac-trigger">
               <div class="our-mobil-wrap">
                   <ul>
                            <li><img src="<?php echo get_template_directory_uri(); ?>/images/<?php echo $default_icons[$i]; ?>" alt="<?php echo $default_titles[$i]; ?>"></li>
                            <li><?php echo $default_titles[$i]; ?></li>
                   </ul>
               </div>
             </div>
                <div class="ac-panel">
                    <div class="ac-panel-cnt">
                       <div class="our-mobil-cnt">
                            <p><?php echo esc_html($default_descriptions[$i]); ?></p>
                       </div>
                    </div>
                </div>
            </div>
            <?php 
                endfor;
            endif;
            ?>
         </div>
    </div>
</section>
<?php endif; ?>

<?php if (is_section_enabled('news')): ?>
<!-- blog-section -->
<section class="blog-area blog-area20">
    <div class="container">
       <div class="blog-main-wrap blog-main-wrap20">
         <div class="blog-title text-center wow fadeInLeft">
            <h2>Latest News</h2>
        </div>
            <div class="blog-main">
                <?php
                // Get news from ACF relationship field
                $latest_news = get_field('about_latest_news');
                $animation_classes = array('fadeInLeft', 'fadeInUp', 'fadeInRight');
                
                if ($latest_news && is_array($latest_news)):
                    foreach ($latest_news as $index => $news_post):
                        $news_excerpt = get_the_excerpt($news_post->ID);
                        // Strip all HTML tags and normalize whitespace
                        $news_excerpt = wp_strip_all_tags($news_excerpt);
                        if (empty($news_excerpt)) {
                            $news_excerpt = wp_trim_words(wp_strip_all_tags($news_post->post_content), 20, '...');
                        }
                        $news_categories = get_the_terms($news_post->ID, 'news_category');
                        $news_category = $news_categories && !is_wp_error($news_categories) ? $news_categories[0]->name : 'News';
                        $news_image = get_the_post_thumbnail_url($news_post->ID, 'medium');
                        $post_date = get_the_date('F j, Y', $news_post->ID);
                ?>
                <article class="blog-item h-100 wow <?php echo esc_attr($animation_classes[$index % 3]); ?>">
                    <div class="blog-content">
                        <a href="<?php echo esc_url(get_permalink($news_post->ID)); ?>">
                            <img class="wow fadeInLeft" src="<?php echo esc_url($news_image ?: get_template_directory_uri() . '/images/blog1.png'); ?>" alt="<?php echo esc_attr($news_post->post_title); ?>">
                        </a>
                        <ul class="wow fadeInRight">
                            <li><?php echo esc_html($post_date); ?></li>
                            <li>|</li>
                            <li><?php echo esc_html($news_category); ?></li>
                        </ul>
                        <h3 class="wow fadeInLeft"><a href="<?php echo esc_url(get_permalink($news_post->ID)); ?>"><?php echo esc_html($news_post->post_title); ?></a></h3>
                    </div>
                    <div class="blog-button">
                        <div class="blog-btn wow fadeInUp">
                            <a href="<?php echo esc_url(get_permalink($news_post->ID)); ?>">read more</a>
                        </div>
                    </div>
                </article>
                <?php 
                    endforeach;
                 else:
                     // Fallback: Get latest 3 news posts automatically
                     $auto_news = get_posts(array('post_type' => 'news-and-insight', 'posts_per_page' => 3, 'post_status' => 'publish'));
                     if ($auto_news):
                         foreach ($auto_news as $index => $news_post):
                             $news_excerpt = get_the_excerpt($news_post->ID);
                             // Strip all HTML tags and normalize whitespace
                             $news_excerpt = wp_strip_all_tags($news_excerpt);
                             if (empty($news_excerpt)) {
                                 $news_excerpt = wp_trim_words(wp_strip_all_tags($news_post->post_content), 20, '...');
                             }
                             $news_categories = get_the_terms($news_post->ID, 'news_category');
                             $news_category = $news_categories && !is_wp_error($news_categories) ? $news_categories[0]->name : 'News';
                             $news_image = get_the_post_thumbnail_url($news_post->ID, 'medium');
                             $post_date = get_the_date('F j, Y', $news_post->ID);
                 ?>
                 <article class="blog-item h-100 wow <?php echo esc_attr($animation_classes[$index % 3]); ?>">
                     <div class="blog-content">
                         <a href="<?php echo esc_url(get_permalink($news_post->ID)); ?>">
                             <img class="wow fadeInLeft" src="<?php echo esc_url($news_image ?: get_template_directory_uri() . '/images/blog1.png'); ?>" alt="<?php echo esc_attr($news_post->post_title); ?>">
                         </a>
                         <ul class="wow fadeInRight">
                             <li><?php echo esc_html($post_date); ?></li>
                             <li>|</li>
                             <li><?php echo esc_html($news_category); ?></li>
                         </ul>
                         <h3 class="wow fadeInLeft"><a href="<?php echo esc_url(get_permalink($news_post->ID)); ?>"><?php echo esc_html($news_post->post_title); ?></a></h3>
                     </div>
                     <div class="blog-button">
                         <div class="blog-btn wow fadeInUp">
                             <a href="<?php echo esc_url(get_permalink($news_post->ID)); ?>">read more</a>
                         </div>
                     </div>
                 </article>
                 <?php 
                         endforeach;
                     endif;
                 endif; 
                 ?>
            </div>
       </div>   
    </div>
</section>
<?php endif; ?>

<?php if (is_section_enabled('faq')): ?>
<!-- question-section -->
<section class="question-area">
    <div class="container">
        <div class="question-title text-center wow fadeInRight">
            <h2>Frequently Asked<br> Questions</h2>
        </div>
    </div>
    <div class="main-content6">
        <div id="owl-csel6" class="owl-carousel owl-theme">
            <div class="question-item">
                <h3 class="wow fadeInUp">Lorem ipsum dolor</h3>
               <p class="wow fadeInRight">Lorem ipsum dolor sit amet consectetur. Feugiat elementum neque massa pellentesque. Tristique sapien gravida arcu mauris molestie lectus.</p>
            </div>
              <div class="question-item wow fadeInUp">
                <h3 class="wow fadeInUp">Lorem ipsum dolor</h3>
               <p class="wow fadeInRight">Lorem ipsum dolor sit amet consectetur. Feugiat elementum neque massa pellentesque. Tristique sapien gravida arcu mauris molestie lectus.</p>
            </div>
              <div class="question-item wow fadeInUp">
                <h3 class="wow fadeInUp">Lorem ipsum dolor</h3>
               <p class="wow fadeInRight">Lorem ipsum dolor sit amet consectetur. Feugiat elementum neque massa pellentesque. Tristique sapien gravida arcu mauris molestie lectus.</p>
            </div>
             <div class="question-item wow fadeInUp">
                <h3 class="wow fadeInUp">Lorem ipsum dolor</h3>
               <p class="wow fadeInRight">Lorem ipsum dolor sit amet consectetur. Feugiat elementum neque massa pellentesque. Tristique sapien gravida arcu mauris molestie lectus.</p>
            </div> 
            <div class="question-item wow fadeInUp">
                <h3 class="wow fadeInUp">Lorem ipsum dolor</h3>
               <p class="wow fadeInRight">Lorem ipsum dolor sit amet consectetur. Feugiat elementum neque massa pellentesque. Tristique sapien gravida arcu mauris molestie lectus.</p>
            </div>
             <div class="question-item wow fadeInUp">
                <h3 class="wow fadeInUp">Lorem ipsum dolor</h3>
               <p class="wow fadeInRight">Lorem ipsum dolor sit amet consectetur. Feugiat elementum neque massa pellentesque. Tristique sapien gravida arcu mauris molestie lectus.</p>
            </div>
        </div>
        <div class="owl-theme">
            <div class="owl-controls">
                <div class="custom-nav owl-nav"></div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<?php get_footer(); ?>

<script>
jQuery(document).ready(function($) {
    // Function to initialize accordion
    function initAccordion() {
        console.log('Attempting to initialize accordion...');
        console.log('Accordion library:', typeof Accordion);
        console.log('Window.Accordion:', typeof window.Accordion);
        
        var AccordionClass = window.Accordion || Accordion;
        
        if (typeof AccordionClass !== 'undefined') {
            var $accordionContainer = $('.accordion-container');
            
            if ($accordionContainer.length > 0) {
                console.log('Initializing accordion with', $('.ac').length, 'items');
                try {
                    var accordion = new AccordionClass('.accordion-container', {
                        duration: 400,
                        showMultiple: false,
                        elementClass: 'ac',
                        triggerClass: 'ac-trigger',
                        panelClass: 'ac-panel',
                        activeClass: 'is-active',
                        onOpen: function(currentElement) {
                            console.log('Accordion item opened');
                        },
                        onClose: function(currentElement) {
                            console.log('Accordion item closed');
                        }
                    });
                    console.log('✓ Accordion initialized successfully!');
                    return true;
                } catch(e) {
                    console.error('Accordion initialization error:', e);
                }
            } else {
                console.error('No accordion container found');
            }
        } else {
            console.error('Accordion library not loaded');
        }
        return false;
    }
    
    // Debug info
    console.log('=== About Page Debug Info ===');
    console.log('WOW library:', typeof WOW);
    console.log('Window width:', $(window).width());
    console.log('Our-mobil containers:', $('.our-mobil').length);
    console.log('Our-mobil visible:', $('.our-mobil').is(':visible'));
    console.log('Accordion containers:', $('.accordion-container').length);
    console.log('AC items found:', $('.ac').length);
    
    // Try to initialize immediately
    if (!initAccordion()) {
        // If it fails, try again after a short delay
        console.log('Retrying accordion initialization in 100ms...');
        setTimeout(initAccordion, 100);
    }
    
    // Initialize WOW animations
    if (typeof WOW !== 'undefined') {
        new WOW().init();
        console.log('WOW initialized');
    }
    
    // Video wrapper functionality
    const videoWrappers = document.querySelectorAll('.video-wrapper');

    function stopVideo(wrapper) {
        const iframe = wrapper.querySelector('iframe');
        const covers = wrapper.querySelectorAll('.video-cover');
        const playButton = wrapper.querySelector('.play-btn-kp');
        const closeButton = wrapper.querySelector('.close-btn-kp');

        // Stop video
        iframe.src = iframe.src.split('?')[0];
        iframe.style.display = 'none';
        closeButton.style.display = 'none';
        playButton.style.display = 'flex';

        covers.forEach(img => {
            if (img.classList.contains('img-desktop') && window.innerWidth > 768) img.style.display = 'block';
            if (img.classList.contains('img-mobile') && window.innerWidth <= 768) img.style.display = 'block';
        });
    }

    videoWrappers.forEach(wrapper => {
        const covers = wrapper.querySelectorAll('.video-cover');
        const iframe = wrapper.querySelector('iframe');
        const playButton = wrapper.querySelector('.play-btn-kp');
        const closeButton = wrapper.querySelector('.close-btn-kp');
        const videoType = wrapper.dataset.videoType;

        wrapper.addEventListener('click', function(event) {
            if ([...covers].some(img => event.target === img || event.target.closest('.play-btn-kp'))) {
                if (!iframe.src.includes('autoplay')) {
                    if (videoType === 'vimeo') iframe.src += "?autoplay=1";
                    else if (videoType === 'youtube') iframe.src += "?autoplay=1";
                }
                iframe.style.display = 'block';
                closeButton.style.display = 'flex';
                playButton.style.display = 'none';
                covers.forEach(img => img.style.display = 'none');
            }
        });

        closeButton.addEventListener('click', function(event) {
            event.stopPropagation();
            stopVideo(wrapper);
        });
    });

    // Stop any video if clicked outside
    document.addEventListener('click', function(event) {
        videoWrappers.forEach(wrapper => {
            const iframe = wrapper.querySelector('iframe');
            if (iframe.style.display === 'block' && !wrapper.contains(event.target)) {
                stopVideo(wrapper);
            }
        });
    });

    // Desktop 5Ps Accordion functionality - Inline expand/collapse
    const accordionToggles = document.querySelectorAll('.accordion-toggle');
    
    // Add inline styles to ensure proper layout - ONLY for 5Ps section
    const ourArea = document.querySelector('.our-area');
    if (ourArea) {
        const ourBoxes = ourArea.querySelectorAll('[data-accordion-item]');
        ourBoxes.forEach(box => {
            box.style.display = 'flex';
            box.style.alignItems = 'center';
            box.style.gap = '20px';
            
            const ourLeft = box.querySelector('.our-left');
            const ourCenter = box.querySelector('.our-center');
            const ourRight = box.querySelector('.our-right');
            
            if (ourLeft) {
                ourLeft.style.flex = '0 0 auto';
                ourLeft.style.minWidth = 'auto';
            }
            if (ourCenter) {
                ourCenter.style.flex = '1';
                ourCenter.style.minWidth = '0';
            }
            if (ourRight) {
                ourRight.style.flex = '0 0 auto';
                ourRight.style.minWidth = 'auto';
            }
        });
    }
    
    // Ensure blog section maintains its 3-column layout
    const blogMain = document.querySelector('.blog-main');
    if (blogMain) {
        blogMain.style.display = 'grid';
        blogMain.style.gridTemplateColumns = 'repeat(3, 1fr)';
        blogMain.style.gap = '30px';
        
        // Reset any flex styles that might be inherited
        const blogItems = blogMain.querySelectorAll('.blog-item');
        blogItems.forEach(item => {
            item.style.display = 'block';
            item.style.flex = 'none';
        });
    }
    
    // Add proper gap/spacing to leadership section - but respect responsive breakpoints
    function updateEmployLayout() {
        const employMain = document.querySelector('.employ-main');
        if (employMain) {
            if (window.innerWidth >= 992) {
                // Only apply fixed grid on desktop
                employMain.style.display = 'grid';
                employMain.style.gridTemplateColumns = 'repeat(3, 1fr)';
                employMain.style.gap = '30px';
                employMain.style.rowGap = '40px';
                
                // Ensure items don't have conflicting styles
                const employItems = employMain.querySelectorAll('.employ-item');
                employItems.forEach(item => {
                    item.style.marginBottom = '0';
                });
            } else {
                // For tablet/mobile, clear any inline styles to let CSS take over
                employMain.style.display = '';
                employMain.style.gridTemplateColumns = '';
                employMain.style.gap = '';
                employMain.style.rowGap = '';
            }
        }
    }
    
    // Call on load
    updateEmployLayout();
    
    // Call on resize/orientation change
    window.addEventListener('resize', updateEmployLayout);
    window.addEventListener('orientationchange', updateEmployLayout);
    
    accordionToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            
            const accordionItem = this.closest('[data-accordion-item]');
            const shortDesc = accordionItem.querySelector('.short-desc');
            const fullDesc = accordionItem.querySelector('.full-desc');
            const isExpanded = this.getAttribute('data-expanded') === 'true';
            const arrow = this.querySelector('.arrow-icon img');
            
            if (isExpanded) {
                // Collapse - Show short description
                shortDesc.style.display = 'inline';
                fullDesc.style.display = 'none';
                this.setAttribute('data-expanded', 'false');
                this.innerHTML = 'Read more<span class="arrow-icon"><img src="' + arrow.src + '" alt=""></span>';
                if (arrow) arrow.style.transform = 'rotate(0deg)';
            } else {
                // Expand - Show full description
                shortDesc.style.display = 'none';
                fullDesc.style.display = 'inline';
                this.setAttribute('data-expanded', 'true');
                this.innerHTML = 'Show Less<span class="arrow-icon"><img src="' + arrow.src + '" alt=""></span>';
                if (arrow) arrow.style.transform = 'rotate(180deg)';
            }
        });
    });
});
</script>
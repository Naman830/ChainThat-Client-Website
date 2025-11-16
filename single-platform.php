<?php
/**
 * The template for displaying single platform posts
 *
 * @package ChainThat
 * @version 1.0.0
 */

get_header();

// Helper function to check if section should be displayed
function is_platform_section_visible($section_name) {
    if (!function_exists('get_field')) {
        return true; // Show by default if ACF not available
    }
    
    // Get current post ID
    $post_id = get_the_ID();
    if (!$post_id) {
        return true;
    }
    
    // Get the section controls group
    $section_controls = get_field('platform_section_controls', $post_id);
    
    // If no section controls exist, show all sections by default
    if (!$section_controls || !is_array($section_controls)) {
        return true;
    }
    
    // Map section names to their toggle field names
    $toggle_name = $section_name . '_section_toggle';
    
    // Debug: Add HTML comment to show what we're checking (temporary)
    // echo "<!-- Checking: {$section_name} -> {$toggle_name} = " . (isset($section_controls[$toggle_name]) ? $section_controls[$toggle_name] : 'not set') . " -->\n";
    
    // Check if toggle exists and its value
    if (isset($section_controls[$toggle_name])) {
        // Return true if value is 'show', false if 'hide'
        return $section_controls[$toggle_name] === 'show';
    }
    
    // Default to showing section if toggle not found
    return true;
}
?>



<!-- adminis-section (Hero) -->
<?php if (is_platform_section_visible('hero')): ?>
<div class="adminis-area">
    <?php
    // Get ACF field values for background
    if (function_exists('get_field')) {
        $background_type = get_field('platform_background_type') ?: 'image';
        $background_video = get_field('platform_background_video');
        $background_image = get_field('platform_background_image');
    } else {
        $background_type = 'image';
        $background_video = '';
        $background_image = '';
    }
    
    // Fallback to default image if no background set
    if (!$background_image) {
        $background_image = get_template_directory_uri() . '/images/adminis-bg.jpg';
    }
    ?>
    
    <?php if ($background_type === 'video' && $background_video): ?>
        <video class="adminis-background-video" autoplay muted loop playsinline>
            <source src="<?php echo esc_url($background_video); ?>" type="video/mp4">
        </video>
        <!-- Fallback image for browsers that don't support video or while video loads -->
        <div class="adminis-background-image" style="background-image: url('<?php echo esc_url($background_image); ?>');"></div>
    <?php else: ?>
        <div class="adminis-background-image" style="background-image: url('<?php echo esc_url($background_image); ?>');"></div>
    <?php endif; ?>
    
    <div class="container">
        <div class="adminis-main">
            <div class="adminis-title">
                <h2 class="wow fadeInRight"><?php echo esc_html(get_the_title()); ?></h2>
                <p class="wow fadeInLeft"><?php echo esc_html(get_the_excerpt()); ?></p>
            </div>
        </div>
        <div class="adminis-img-wrap wow fadeInRight">
            <div class="adminis-img">
                <?php 
                $featured_image = get_the_post_thumbnail_url(get_the_ID(), 'full');
                if ($featured_image): ?>
                    <img class="d-none d-lg-block" src="<?php echo esc_url($featured_image); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
                    <img class="d-block d-lg-none" src="<?php echo esc_url($featured_image); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
                <?php else: ?>
                    <img class="d-none d-lg-block" src="<?php echo get_template_directory_uri(); ?>/images/adminis1.png" alt="<?php echo esc_attr(get_the_title()); ?>">
                    <img class="d-block d-lg-none" src="<?php echo get_template_directory_uri(); ?>/images/adminis1-m.png" alt="<?php echo esc_attr(get_the_title()); ?>">
                <?php endif; ?>
            </div>
        </div>      
    </div>
</div>
<?php endif; ?>
        
<!-- Description Section (We Go Beyond) -->
<?php if (is_platform_section_visible('description')): ?>
    <?php 
    $description_title_1 = get_field('video_section_tag');
    $description_title_2 = get_field('video_section_title');
    $description_text = get_field('video_section_description');
    ?>
    <section class="adminis-description-area">
        <div class="container">
            <div class="adminis-video-title">
                <?php if ($description_title_1): ?>
                    <span class="wow fadeInLeft"><?php echo esc_html($description_title_1); ?></span>
                <?php endif; ?>
                <?php if ($description_title_2): ?>
                    <h2 class="wow fadeInRight"><?php echo esc_html($description_title_2); ?></h2>
                <?php endif; ?>
                <?php if ($description_text): ?>
                    <p class="wow fadeInLeft"><?php echo esc_html($description_text); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </section>
<?php endif; ?>
        
<!-- Video Section -->
<?php if (is_platform_section_visible('video')): ?>
    <?php 
    $video_title = get_field('video_title');
    $video_description = get_field('video_description');
    $video_button_text = get_field('video_button_text');
    $video_url = get_field('video_url');
    $video_desktop_cover = get_field('video_desktop_cover');
    $video_mobile_cover = get_field('video_mobile_cover');
    
    // Extract YouTube ID from URL if it's a YouTube video
    $video_youtube_id = '';
    if ($video_url && (strpos($video_url, 'youtube.com') !== false || strpos($video_url, 'youtu.be') !== false)) {
        preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/', $video_url, $matches);
        $video_youtube_id = $matches[1] ?? '';
    }
    ?>
    <section class="adminis-video-area">
        <div class="container">
            <div class="adminis-video-main">
                <?php if ($video_title || $video_description || $video_button_text): ?>
                <div class="adminis-video-left">
                    <?php if ($video_title): ?>
                        <h3 class="wow fadeInRight"><?php echo esc_html($video_title); ?></h3>
                    <?php endif; ?>
                    <?php if ($video_description): ?>
                        <p class="wow fadeInLeft"><?php echo esc_html($video_description); ?></p>
                    <?php endif; ?>
                    <?php if ($video_button_text): ?>
                        <div class="btn-all wow fadeInUp">
                            <a href="<?php echo esc_url($video_url ?: '#'); ?>"><?php echo esc_html($video_button_text); ?></a>
                        </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                <?php if ($video_youtube_id): ?>
                <div class="adminis-video-right wow fadeInUp">
                    <div class="video-wrapper video-wrapper2" data-video-type="youtube" data-video-id="<?php echo esc_attr($video_youtube_id); ?>">
                        <?php if ($video_desktop_cover): ?>
                            <img class="video-cover img-desktop" src="<?php echo esc_url($video_desktop_cover); ?>" alt="<?php echo esc_attr($video_title ?: ''); ?>">
                        <?php endif; ?>
                        <?php if ($video_mobile_cover): ?>
                            <img class="video-cover img-mobile" src="<?php echo esc_url($video_mobile_cover); ?>" alt="<?php echo esc_attr($video_title ?: ''); ?>">
                        <?php endif; ?>
                        <iframe src="https://www.youtube.com/embed/<?php echo esc_attr($video_youtube_id); ?>" frameborder="0" allowfullscreen></iframe>
                        <button class="play-btn-kp"><img src="<?php echo get_template_directory_uri(); ?>/images/play.png" alt=""></button>
                        <button class="close-btn-kp"><i class="fas fa-times"></i></button>
                    </div>   
                </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- Benefits Section (Your Key Benefits) -->
<?php if (is_platform_section_visible('benefits')): ?>
    <?php 
    $benefits_tag = get_field('benefits_tag');
    $benefits_heading = get_field('benefits_heading');
    $benefit_items = get_field('benefit_items');
    ?>
    <section class="ipsum-area ipsum-area10">
        <div class="container">
            <?php if ($benefits_tag || $benefits_heading): ?>
            <div class="all-title ipsum-title-main text-center">
                <?php if ($benefits_tag): ?>
                    <span class="wow fadeInLeft"><?php echo esc_html($benefits_tag); ?></span>
                <?php endif; ?>
                <?php if ($benefits_heading): ?>
                    <h2 class="wow fadeInRight"><?php echo esc_html($benefits_heading); ?></h2>
                <?php endif; ?>
            </div>
            <?php endif; ?>
            <?php if ($benefit_items && is_array($benefit_items) && count($benefit_items) > 0): ?>
            <div class="row justify-content-center">
                <?php foreach ($benefit_items as $index => $item):
                    if (!empty($item['title']) || !empty($item['description']) || !empty($item['icon'])):
                        // 4-column layout: cycle through 4 animations (left, up, down, right)
                        $animation_class = $index % 4 == 0 ? 'wow fadeInLeft' : ($index % 4 == 1 ? 'wow fadeInUp' : ($index % 4 == 2 ? 'wow fadeInDown' : 'wow fadeInRight'));
                        ?>
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="benefit-card <?php echo $animation_class; ?>">
                                <?php if (!empty($item['icon'])): ?>
                                    <div class="benefit-icon mb-3">
                                        <img src="<?php echo esc_url($item['icon']); ?>" alt="<?php echo esc_attr($item['title'] ?? ''); ?>" style="width: 60px; height: 60px;">
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($item['title'])): ?>
                                    <h4><?php echo esc_html($item['title']); ?></h4>
                                <?php endif; ?>
                                <?php if (!empty($item['description'])): ?>
                                    <p><?php echo esc_html($item['description']); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif;
                endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </section>
<?php endif; ?>

<!-- Statistics Section (In Numbers) -->
<?php if (is_platform_section_visible('stats_section_toggle')): ?>
    <?php 
    $statistics_title = get_field('statistics_title');
    $statistics = get_field('statistics_items');
    ?>
    
    <section class="adminis-statistics-area">
        <div class="container">
            <?php if ($statistics_title): ?>
            <div class="year-inner-title wow fadeInRight">
                <h2><?php echo esc_html($statistics_title); ?></h2>
            </div>
            <?php endif; ?>
            <?php if ($statistics && is_array($statistics) && count($statistics) > 0): ?>
            <div class="yearbox-wrap yearbox-wrap10">
                <?php foreach ($statistics as $stat): 
                    // Use isset() to allow "0" as a valid value
                    $has_number = isset($stat['statistic_number']) && $stat['statistic_number'] !== '';
                    $has_description = isset($stat['statistic_description']) && $stat['statistic_description'] !== '';
                    
                    if ($has_number || $has_description): ?>
                    <div class="year-box year-box10">
                        <div class="year-inner year-inner10">
                            <?php if ($has_number): ?>
                                <h2 class="wow fadeInLeft"><?php echo esc_html($stat['statistic_number']); ?></h2>
                            <?php endif; ?>
                            <?php if ($has_description): ?>
                                <p class="wow fadeInRight"><?php echo esc_html($stat['statistic_description']); ?></p>
                            <?php endif; ?>
                        </div>   
                    </div>
                    <?php endif;
                endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </section>

<?php endif; ?>

<!-- rating-section (Features/Capabilities) -->
<?php if (is_platform_section_visible('features')): ?>
<?php
// Helper function to create URL-friendly slug from tab name
function chainthat_create_tab_slug($tab_name) {
    $slug = strtolower($tab_name);
    $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
    $slug = trim($slug, '-');
    return $slug;
}
?>
<section class="rating-area">
    <div class="container">
        <div class="rating-title">
            <h2 class="wow fadeInLeft"><?php echo esc_html(get_field('capabilities_title') ?: 'Functional Capabilities'); ?></h2>
            <p class="wow fadeInRight"><?php echo esc_html(get_field('capabilities_subtitle') ?: 'What Beyond Policy Administration delivers – in detail.'); ?></p>
        </div>
    </div>
    <div class="tab-container" data-set="set3">
        <!-- Mobile Dropdown (visible on small screens) -->
        <div class="tab-mobile-select d-block d-md-none">
            <select class="tab-select-dropdown" id="tabSelectMobile">
                <?php 
                $capabilities_tabs = get_field('capabilities_tabs');
                if ($capabilities_tabs): ?>
                    <?php foreach ($capabilities_tabs as $index => $tab): 
                        $tab_slug = chainthat_create_tab_slug($tab['tab_name']);
                        ?>
                        <option value="<?php echo esc_attr($tab_slug); ?>" <?php echo $index === 0 ? 'selected' : ''; ?>><?php echo esc_html($tab['tab_name']); ?></option>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- Fallback options -->
                    <option value="product-configuration" selected>Product Configuration</option>
                    <option value="underwriting-rating">Underwriting & Rating</option>
                    <option value="policy-lifecycle-servicing">Policy lifecycle servicing</option>
                    <option value="reporting-integration">Reporting & Integration</option>
                    <option value="forms-billing">Forms & Billing</option>
                <?php endif; ?>
            </select>
        </div>
        
        <!-- Desktop Tabs (hidden on small screens) -->
        <div class="tab-buttons tab-buttons3 d-none d-md-flex">
            <?php 
            if ($capabilities_tabs): ?>
                <?php foreach ($capabilities_tabs as $index => $tab): 
                    $tab_slug = chainthat_create_tab_slug($tab['tab_name']);
                    ?>
                    <a href="#<?php echo esc_attr($tab_slug); ?>" class="tab-btn <?php echo $index === 0 ? 'active' : ''; ?>" data-tab="<?php echo esc_attr($tab_slug); ?>"><?php echo esc_html($tab['tab_name']); ?></a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <div class="tab-content">
            <?php 
            if ($capabilities_tabs):
                foreach ($capabilities_tabs as $index => $tab):
                    $tab_name = $tab['tab_name'] ?? '';
                    $tab_slug = chainthat_create_tab_slug($tab_name);
                    $tab_image = $tab['tab_image'] ?? '';
                    $tab_mobile_image = $tab['tab_mobile_image'] ?? '';
                    $tab_items = $tab['tab_items'] ?? [];
                    $active_class = $index === 0 ? ' active' : '';
            ?>
            <div id="<?php echo esc_attr($tab_slug); ?>" class="tab-pane<?php echo $active_class; ?>">
                <div class="rating-mb d-block d-lg-none">
                    <?php if ($tab_mobile_image): ?>
                        <img src="<?php echo esc_url($tab_mobile_image); ?>" alt="<?php echo esc_attr($tab_name); ?>">
                    <?php else: ?>
                        <img src="<?php echo get_template_directory_uri(); ?>/images/rating-m.png" alt="">
                    <?php endif; ?>
                </div>
                <div class="container">
                    <div class="rating-main">
                        <div class="rating-img">
                            <?php if ($tab_image): ?>
                                <img class="d-none d-lg-block" src="<?php echo esc_url($tab_image); ?>" alt="<?php echo esc_attr($tab_name); ?>">
                            <?php else: ?>
                                <img class="d-none d-lg-block" src="<?php echo get_template_directory_uri(); ?>/images/rating.png" alt="">
                            <?php endif; ?>
                            <div class="ratimg-title">
                                <h2 class="section-title"><?php echo esc_html($tab_name); ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="rating-item-wrap">
                        <?php 
                        if ($tab_items):
                            foreach ($tab_items as $item):
                                $item_icon = $item['item_icon'] ?? '';
                                $item_title = $item['item_title'] ?? '';
                                $item_description = $item['item_description'] ?? '';
                        ?>
                        <div class="rating-item">
                            <?php if ($item_icon): ?>
                                <img src="<?php echo esc_url($item_icon); ?>" alt="<?php echo esc_attr($item_title); ?>">
                            <?php endif; ?>
                            <h4><?php echo esc_html($item_title); ?></h4>
                            <p><?php echo esc_html($item_description); ?></p>
                        </div>
                        <?php 
                            endforeach;
                        endif;
                        ?>
                    </div>
                </div>
            </div>
            <?php 
                endforeach;
            endif;
            ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- timeline-section (Architecture) -->
<?php if (is_platform_section_visible('timeline')): ?>
    <?php
    $architecture_tag = get_field('architecture_tag');
    $architecture_title = get_field('architecture_title');
    $architecture_description = get_field('architecture_description');
    $architecture_image = get_field('architecture_image');
    $architecture_features = get_field('architecture_features');
    ?>
<section class="timeline-area">
    <div class="container">
        <?php if ($architecture_tag || $architecture_title || $architecture_description): ?>
        <div class="all-title timeline-title">
            <?php if ($architecture_tag): ?>
                <span class="wow fadeInLeft"><?php echo esc_html($architecture_tag); ?></span>
            <?php endif; ?>
            <?php if ($architecture_title): ?>
                <h2 class="wow fadeInRight"><?php echo esc_html($architecture_title); ?></h2>
            <?php endif; ?>
            <?php if ($architecture_description): ?>
                <p class="wow fadeInLeft"><?php echo esc_html($architecture_description); ?></p>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        <div class="timeline-main">
            <?php if ($architecture_image): ?>
            <div class="timeline-left wow fadeInLeft">
                <img src="<?php echo esc_url($architecture_image); ?>" alt="Architecture">
            </div>
            <?php endif; ?>
            <?php if ($architecture_features && is_array($architecture_features) && count($architecture_features) > 0): ?>
            <div class="timeline-right">
               <div class="container">
                  <div class="rightbox wow fadeInUp">
                      <div class="rb-container">
                          <ul class="rb">
                              <?php foreach ($architecture_features as $feature):
                                  if (!empty($feature['feature_title']) || !empty($feature['feature_description'])): ?>
                              <li class="rb-item" data-wow-delay="0.2s">
                                  <div class="item-title">
                                      <h4 class="wow fadeInRight"><?php echo esc_html($feature['feature_title'] ?? ''); ?></h4>
                                  </div>
                                  <div class="item-des">
                                      <p class="wow fadeInLeft"><?php echo esc_html($feature['feature_description'] ?? ''); ?></p>
                                  </div>
                              </li>
                              <?php 
                                  endif;
                              endforeach; ?>
                          </ul>
                      </div>
                  </div>
               </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Testimonials Section -->
<!-- testimonials-section -->
<?php if (is_platform_section_visible('working')): ?>
    <?php
    $testimonials_title = get_field('testimonials_title');
    $testimonials_subtitle = get_field('testimonials_subtitle');
    $testimonials_items = get_field('testimonials_items');
    ?>
<section class="working-with-chainthat-area">
    <div class="container">
        <?php if ($testimonials_title || $testimonials_subtitle): ?>
        <div class="working-with-chainthat-title text-center">
            <?php if ($testimonials_title): ?>
                <h2 class="wow fadeInLeft"><?php echo esc_html($testimonials_title); ?></h2>
            <?php endif; ?>
            <?php if ($testimonials_subtitle): ?>
                <p class="wow fadeInRight"><?php echo esc_html($testimonials_subtitle); ?></p>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        
        <?php if ($testimonials_items && is_array($testimonials_items) && count($testimonials_items) > 0): ?>
        <div class="working-with-chainthat-main">
            <?php 
            foreach ($testimonials_items as $index => $post): 
                setup_postdata($post);
                
                $is_even = ($index % 2 == 0);
                
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
                            <?php if ($display_title): ?>
                                <h4 class="wow fadeInRight"><?php echo esc_html($display_title); ?></h4>
                            <?php endif; ?>
                            <?php if ($display_excerpt): ?>
                                <p class="wow fadeInLeft"><?php echo esc_html($display_excerpt); ?></p>
                            <?php endif; ?>
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
                            <?php if ($display_title): ?>
                                <h4 class="card-title wow fadeInUp"><?php echo esc_html($display_title); ?></h4>
                            <?php endif; ?>
                            <?php if ($display_excerpt): ?>
                                <p class="card-text wow fadeInUp"><?php echo esc_html($display_excerpt); ?></p>
                            <?php endif; ?>
                            <div class="btn-all working-with-chainthat-btn wow fadeInUp mt-auto">
                                <a href="<?php echo esc_url(get_permalink($post->ID)); ?>">Find out more</a>
                            </div>
                        </div>
                    </div>
                </div>
                
            <?php 
            endforeach; 
            wp_reset_postdata();
            ?>
        </div>
        <?php endif; ?>
    </div>
</section>
<?php endif; ?>

<!-- Bottom Buttons Section -->
<!-- bottom-cta-section -->
<?php if (is_platform_section_visible('bottom_cta')): ?>
    <?php
    $bottom_cta_title = get_field('bottom_cta_title');
    $bottom_cta_description = get_field('bottom_cta_description');
    $bottom_buttons = get_field('bottom_buttons');
    
    // Process Link fields (convert from array to URL string)
    if ($bottom_buttons) {
        $button1_link = $bottom_buttons['bottom_button1_link'] ?? null;
        $button2_link = $bottom_buttons['bottom_button2_link'] ?? null;
        
        $bottom_buttons['bottom_button1_url'] = is_array($button1_link) ? ($button1_link['url'] ?? '#') : ($button1_link ?: '#');
        $bottom_buttons['bottom_button1_target'] = is_array($button1_link) ? ($button1_link['target'] ?? '_self') : '_self';
        
        $bottom_buttons['bottom_button2_url'] = is_array($button2_link) ? ($button2_link['url'] ?? '#') : ($button2_link ?: '#');
        $bottom_buttons['bottom_button2_target'] = is_array($button2_link) ? ($button2_link['target'] ?? '_self') : '_self';
    }
    ?>
<section class="working-with-chainthat-area">
    <div class="container">
        <?php if ($bottom_cta_title || $bottom_cta_description): ?>
        <div class="working-with-chainthat-title text-center">
            <?php if ($bottom_cta_title): ?>
                <h2 class="wow fadeInLeft"><?php echo esc_html($bottom_cta_title); ?></h2>
            <?php endif; ?>
            <?php if ($bottom_cta_description): ?>
                <p class="wow fadeInRight"><?php echo esc_html($bottom_cta_description); ?></p>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        <?php if ($bottom_buttons): ?>
        <div class="tndus-btm d-none d-lg-block">
            <ul>
                <?php if (!empty($bottom_buttons['bottom_button1_text'])): ?>
                <li><div class="btn-all wow fadeInLeft">
                    <a href="<?php echo esc_url($bottom_buttons['bottom_button1_url']); ?>" 
                       target="<?php echo esc_attr($bottom_buttons['bottom_button1_target']); ?>"
                       <?php echo ($bottom_buttons['bottom_button1_target'] === '_blank') ? 'rel="noopener noreferrer"' : ''; ?>>
                        <?php echo esc_html($bottom_buttons['bottom_button1_text']); ?>
                    </a>
                </div></li>
                <?php endif; ?>
                <?php if (!empty($bottom_buttons['bottom_button2_text'])): ?>
                <li><div class="btn-all wow fadeInRight">
                    <a href="<?php echo esc_url($bottom_buttons['bottom_button2_url']); ?>" 
                       target="<?php echo esc_attr($bottom_buttons['bottom_button2_target']); ?>"
                       <?php echo ($bottom_buttons['bottom_button2_target'] === '_blank') ? 'rel="noopener noreferrer"' : ''; ?>>
                        <?php echo esc_html($bottom_buttons['bottom_button2_text']); ?>
                    </a>
                </div></li>
                <?php endif; ?>
                <?php if (!empty($bottom_buttons['bottom_button3_text'])): ?>
                <li><div class="btn-all wow fadeInUp">
                    <a href="<?php echo esc_url($bottom_buttons['bottom_button3_link'] ?? '#'); ?>"><?php echo esc_html($bottom_buttons['bottom_button3_text']); ?></a>
                </div></li>
                <?php endif; ?>
            </ul>
        </div>
        <!-- Mobile accordion version -->
        <div class="tndus-btm-mobile d-block d-lg-none">
            <?php if (!empty($bottom_buttons['bottom_button1_text'])): ?>
            <div class="btn-all wow fadeInUp mb-3">
                <a href="<?php echo esc_url($bottom_buttons['bottom_button1_url']); ?>" 
                   target="<?php echo esc_attr($bottom_buttons['bottom_button1_target']); ?>"
                   <?php echo ($bottom_buttons['bottom_button1_target'] === '_blank') ? 'rel="noopener noreferrer"' : ''; ?>>
                    <?php echo esc_html($bottom_buttons['bottom_button1_text']); ?>
                </a>
            </div>
            <?php endif; ?>
            <?php if (!empty($bottom_buttons['bottom_button2_text'])): ?>
            <div class="btn-all wow fadeInUp mb-3">
                <a href="<?php echo esc_url($bottom_buttons['bottom_button2_url']); ?>" 
                   target="<?php echo esc_attr($bottom_buttons['bottom_button2_target']); ?>"
                   <?php echo ($bottom_buttons['bottom_button2_target'] === '_blank') ? 'rel="noopener noreferrer"' : ''; ?>>
                    <?php echo esc_html($bottom_buttons['bottom_button2_text']); ?>
                </a>
            </div>
            <?php endif; ?>
            <?php if (!empty($bottom_buttons['bottom_button3_text'])): ?>
            <div class="btn-all wow fadeInUp mb-3">
                <a href="<?php echo esc_url($bottom_buttons['bottom_button3_link'] ?? '#'); ?>"><?php echo esc_html($bottom_buttons['bottom_button3_text']); ?></a>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</section>
<?php endif; ?>

<?php get_footer(); ?>

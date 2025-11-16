<?php
// Close main content wrapper
echo '</main>';

// Get ACF field values with fallbacks
$footer_section = get_field('theme_footer_section', 'option');
$logos_section = get_field('theme_logos_section', 'option');
$social_section = get_field('theme_social_section', 'option');

// Footer logos with fallbacks
$footer_logo = '';
if ($logos_section) {
    $footer_logo = (isset($logos_section['theme_footer_logo']) && $logos_section['theme_footer_logo']) ? $logos_section['theme_footer_logo'] : get_template_directory_uri() . '/images/footer-logo.png';
} else {
    $footer_logo = get_template_directory_uri() . '/images/footer-logo.png';
}

// Footer content with fallbacks
$footer_copyright = '';
$footer_company_info = '';
$footer_menu = '';
$footer_awards = array();
$footer_agility_logo = null;

if ($footer_section) {
    $footer_copyright = (isset($footer_section['theme_footer_copyright']) && $footer_section['theme_footer_copyright']) ? $footer_section['theme_footer_copyright'] : '© ChainThat 2024. Registered Company 09841465 ChainThat Limited';
    $footer_company_info = (isset($footer_section['theme_footer_company_info']) && $footer_section['theme_footer_company_info']) ? $footer_section['theme_footer_company_info'] : '';
    $footer_menu = (isset($footer_section['theme_footer_links_menu']) && $footer_section['theme_footer_links_menu']) ? $footer_section['theme_footer_links_menu'] : '';
    $footer_awards = (isset($footer_section['theme_footer_awards']) && $footer_section['theme_footer_awards']) ? $footer_section['theme_footer_awards'] : array();
    $footer_agility_logo = (isset($footer_section['theme_footer_agility_logo']) && $footer_section['theme_footer_agility_logo']) ? $footer_section['theme_footer_agility_logo'] : null;
} else {
    $footer_copyright = '© ChainThat 2024. Registered Company 09841465 ChainThat Limited';
    $footer_awards = array();
}

// Social media links with fallbacks
$social_links = array();
if ($social_section && isset($social_section['theme_social_links'])) {
    $social_links = $social_section['theme_social_links'];
}
?>

<!-- footer-section -->
<footer class="footer-area">
    <div class="container">
        <div class="footer-main">
            <div class="footer-top">
                <div class="footer-top-left wow fadeInLeft">
                    <a href="<?php echo esc_url(home_url('/')); ?>">
                        <img src="<?php echo esc_url($footer_logo); ?>" alt="<?php bloginfo('name'); ?>">
                    </a>
                </div>
                <div class="footer-top-right wow fadeInRight">
                    <ul>
                        <?php if ($footer_menu): ?>
                            <?php
                            wp_nav_menu(array(
                                'menu' => $footer_menu,
                                'container' => false,
                                'items_wrap' => '%3$s',
                                'fallback_cb' => false,
                                'walker' => new Footer_Menu_Walker()
                            ));
                            ?>
                        <?php else: ?>
                            <!-- Default fallback links -->
                            <li><a href="#">Contact Us</a></li>
                            <li><a href="#">Terms</a></li>
                            <li><a href="#">Privacy Policy</a></li>
                            <li><a href="#">Cookies</a></li>
                        <?php endif; ?>
                        
                        <li>
                            <?php if ($social_links): ?>
                                <?php foreach ($social_links as $social): ?>
                                    <div>
                                        <a href="<?php echo esc_url($social['social_url']); ?>" target="_blank" rel="noopener" aria-label="Follow us on <?php echo esc_attr($social['social_platform']); ?> (opens in new tab)">
                                            <?php if ($social['social_icon']): ?>
                                                <img src="<?php echo esc_url($social['social_icon']); ?>" alt="" role="presentation">
                                            <?php else: ?>
                                                <i class="fab fa-<?php echo esc_attr(strtolower($social['social_platform'])); ?>" aria-hidden="true"></i>
                                            <?php endif; ?>
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div><a href="#" aria-label="Follow us on LinkedIn (opens in new tab)" target="_blank" rel="noopener"><img src="<?php echo get_template_directory_uri(); ?>/images/social1.png" alt="" role="presentation"></a></div>
                                <div><a href="#" aria-label="Follow us on Twitter (opens in new tab)" target="_blank" rel="noopener"><img src="<?php echo get_template_directory_uri(); ?>/images/social2.png" alt="" role="presentation"></a></div>
                            <?php endif; ?>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="footer-btm">
                <div class="footer-btm-left wow fadeInUp">
                    <?php if ($footer_awards): ?>
                        <ul>
                            <?php foreach ($footer_awards as $award): ?>
                                <li><img src="<?php echo esc_url($award['footer_award_image']); ?>" alt="<?php echo esc_attr($award['footer_award_alt']); ?>"></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <ul>
                            <li><img src="<?php echo get_template_directory_uri(); ?>/images/footer1.png" alt="Industry award badge"></li>
                            <li><img src="<?php echo get_template_directory_uri(); ?>/images/footer1.png" alt="Industry award badge"></li>
                            <li><img src="<?php echo get_template_directory_uri(); ?>/images/footer1.png" alt="Industry award badge"></li>
                        </ul>
                    <?php endif; ?>
                    <h4 class="wow fadeInLeft">Accreds & Awards</h4>
                </div>
                <div class="footer-btm-right wow fadeInRight">
                    <?php if ($footer_agility_logo): ?>
                        <img src="<?php echo esc_url($footer_agility_logo['url']); ?>" 
                             alt="<?php echo esc_attr($footer_agility_logo['alt'] ?: 'Agility Logo'); ?>" 
                             class="footer-agility-logo">
                    <?php endif; ?>
                    
                    <?php if ($footer_copyright): ?>
                        <p><?php echo esc_html($footer_copyright); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</footer> 

<!--SCROLL-TOP  -->
<a href="#" class="scrolltotop" aria-label="Scroll to top of page">
<i class="fa-solid fa-arrow-up" aria-hidden="true"></i>
</a>

<script>
// Frontend page carousel settings from ACF
<?php if (is_front_page()): 
    $partners_section = get_field('partners_section');
    $blog_section = get_field('blog_section');
?>
var chainthatsettings = {
    partners: {
        autoplay: <?php echo isset($partners_section['partners_carousel_autoplay']) && $partners_section['partners_carousel_autoplay'] ? 'true' : 'true'; ?>,
        speed: <?php echo isset($partners_section['partners_carousel_speed']) ? intval($partners_section['partners_carousel_speed']) : 3000; ?>
    },
    blog: {
        autoplay: <?php echo isset($blog_section['blog_carousel_autoplay']) && $blog_section['blog_carousel_autoplay'] ? 'true' : 'true'; ?>,
        speed: 5000
    }
};
<?php else: ?>
var chainthatsettings = {
    partners: { autoplay: true, speed: 3000 },
    blog: { autoplay: true, speed: 5000 }
};
<?php endif; ?>
</script>

<?php wp_footer(); ?>

    <script src="<?php echo get_template_directory_uri(); ?>/js/accordion.js"></script>
    
    <script>
      new WOW().init();
      
      // Debug: Check if scripts are loading
      console.log('ChainThat theme loaded');
      console.log('jQuery version:', typeof $ !== 'undefined' ? $.fn.jquery : 'jQuery not loaded');
      console.log('Owl Carousel:', typeof $.fn.owlCarousel !== 'undefined' ? 'Loaded' : 'Not loaded');
      
      // Initialize Year Carousel if it exists
      jQuery(document).ready(function($) {
        if ($('#owl-csel-year').length) {
          var autoplay = $('#owl-csel-year').data('autoplay') === 'true';
          var timeout = parseInt($('#owl-csel-year').data('timeout')) || 3000;
          
          $('#owl-csel-year').owlCarousel({
            loop: true,
            margin: 20,
            nav: true,
            dots: false,
            autoplay: autoplay,
            autoplayTimeout: timeout,
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
        
        // Initialize Year Background Carousel with Marquee Effect
        if ($('#owl-csel-year-bg').length) {
          $('#owl-csel-year-bg').owlCarousel({
            loop: true,
            nav: false,
            dots: false,
            autoplay: true,
            autoplayTimeout: 0,
            autoplaySpeed: 8000,
            autoplayHoverPause: false,
            items: 1,
            smartSpeed: 8000,
            fluidSpeed: true,
            navSpeed: 8000,
            dragEndSpeed: 8000,
            responsive: {
              0: {
                autoplaySpeed: 6000,
                smartSpeed: 6000
              },
              768: {
                autoplaySpeed: 8000,
                smartSpeed: 8000
              }
            }
          });
        }

        // Initialize Policy Carousel - Desktop (3 items)
        if ($('#owl-csel-policy-desktop').length) {
          $('#owl-csel-policy-desktop').owlCarousel({
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
        
        // Initialize Policy Carousel - Tablet (2 items)
        if ($('#owl-csel-policy-tablet').length) {
          $('#owl-csel-policy-tablet').owlCarousel({
            loop: true,
            margin: 20,
            nav: true,
            dots: false,
            autoplay: true,
            autoplayTimeout: 4000,
            autoplayHoverPause: true,
            items: 2
          });
        }
        
        // Initialize Policy Carousel - Mobile (1 item)
        if ($('#owl-csel-policy-mobile').length) {
          $('#owl-csel-policy-mobile').owlCarousel({
            loop: true,
            margin: 0, // No margin to prevent cutting
            nav: true,
            dots: true,
            autoplay: true,
            autoplayTimeout: 4000,
            autoplayHoverPause: true,
            center: false,
            navText: ['<i class="fas fa-chevron-left"></i>', '<i class="fas fa-chevron-right"></i>'],
            dotsEach: false,
            stagePadding: 0, // No stage padding
            responsive: {
              0: {
                items: 1,
                margin: 0,
                nav: true,
                dots: true
              },
              600: {
                items: 2,
                margin: 0,
                nav: true,
                dots: true
              }
            },
            onInitialized: function(event) {
              // Force equal heights after initialization
              equalizeCarouselHeights('#owl-csel-policy-mobile');
            },
            onResized: function(event) {
              // Re-equalize heights on resize
              equalizeCarouselHeights('#owl-csel-policy-mobile');
            },
            onRefreshed: function(event) {
              // Re-equalize heights on refresh
              equalizeCarouselHeights('#owl-csel-policy-mobile');
            }
          });
        }
        
        // Function to equalize carousel card heights
        function equalizeCarouselHeights(carouselId) {
          var maxHeight = 0;
          $(carouselId + ' .policy-item').each(function() {
            $(this).css('height', 'auto'); // Reset height
            var thisHeight = $(this).outerHeight();
            if (thisHeight > maxHeight) {
              maxHeight = thisHeight;
            }
          });
          $(carouselId + ' .policy-item').css('height', maxHeight + 'px');
        }
        
        // Blog carousels are initialized in script.js with proper responsive breakpoints
      });
    </script>

<!--js-code-->
</body>
</html>
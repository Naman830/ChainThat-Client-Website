<?php
/**
 * Template Name: News Archive Page
 * 
 * @package ChainThat
 * @version 1.0.0
 */

get_header(); ?>

<!-- news-section -->
<section class="news-area">
    <div class="container">
        <div class="news-title wow fadeInLeft">
            <h2><?php echo esc_html(get_field('news_archive_title', 'option') ?: 'News & Insights'); ?></h2>
            <?php 
            $archive_subtitle = get_field('news_archive_subtitle', 'option');
            if ($archive_subtitle): ?>
                <p><?php echo esc_html($archive_subtitle); ?></p>
            <?php endif; ?>
        </div>
        <div class="news-main">
            <?php 
            // Get featured posts
            $featured_count = get_field('news_featured_posts_count', 'option') ?: 3;
            $featured_posts = new WP_Query(array(
                'post_type' => 'news-and-insight',
                'posts_per_page' => $featured_count,
                'post_status' => 'publish',
                'orderby' => 'date',
                'order' => 'DESC'
            ));
            
            if ($featured_posts->have_posts()): 
                $post_count = 0;
                while ($featured_posts->have_posts()): 
                    $featured_posts->the_post();
                    $post_count++;
                    $featured_image = get_the_post_thumbnail_url(get_the_ID(), 'medium');
                    ?>
                    <div class="news-item wow fadeInRight">
                        <a href="<?php echo esc_url(get_permalink()); ?>">
                            <?php if ($featured_image): ?>
                                <img class="wow fadeInRight" src="<?php echo esc_url($featured_image); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
                            <?php else: ?>
                                <img class="wow fadeInRight" src="<?php echo get_template_directory_uri(); ?>/images/news<?php echo $post_count; ?>.png" alt="<?php echo esc_attr(get_the_title()); ?>">
                            <?php endif; ?>
                        </a>
                        <h2 class="wow fadeInUp"><a href="<?php echo esc_url(get_permalink()); ?>"><?php echo esc_html(get_the_title()); ?></a></h2>
                        <div class="btn-all news-btn wow fadeInUp">
                            <a href="<?php echo esc_url(get_permalink()); ?>">read more</a>
                        </div>
                    </div>
                    <?php
                endwhile;
                wp_reset_postdata();
            endif; ?>
        </div>
    </div>
</section>

<!-- tab-section -->
<section class="tab-area">
    <div class="container">
        <div class="tab-main wow fadeInUp">
             <div class="tab-container" data-set="set1">
            <div class="tab-buttons">
              <a href="#all-news" class="tab-btn active" data-tab="all-news">All</a>
              <?php 
              // Get all categories for tabs
              $categories = get_terms(array(
                  'taxonomy' => 'news_category',
                  'hide_empty' => true,
              ));
              
              if ($categories && !is_wp_error($categories)):
                  foreach ($categories as $category): 
                      $category_slug = sanitize_title($category->name);
                      ?>
                      <a href="#<?php echo esc_attr($category_slug); ?>" class="tab-btn" data-tab="<?php echo esc_attr($category_slug); ?>"><?php echo esc_html($category->name); ?></a>
                  <?php 
                  endforeach;
              endif; ?>
            </div>
            <div class="tab-content">
              <!-- All Posts Tab -->
              <div id="all-news" class="tab-pane active">
                 <div class="tab-item-wrap">
                      <?php 
                      // Query all posts for "All" tab
                      $all_posts = new WP_Query(array(
                          'post_type' => 'news-and-insight',
                          'posts_per_page' => -1,
                          'post_status' => 'publish',
                          'orderby' => 'date',
                          'order' => 'DESC'
                      ));
                      
                      if ($all_posts->have_posts()): 
                          while ($all_posts->have_posts()): 
                              $all_posts->the_post();
                              $featured_image = get_the_post_thumbnail_url(get_the_ID(), 'medium');
                              $post_date = get_the_date('d/m/Y');
                              $post_categories = get_the_terms(get_the_ID(), 'news_category');
                              $category_name = !empty($post_categories) ? $post_categories[0]->name : 'Blog Post';
                              ?>
                              <div class="blog-item blog-item50">
                                <a href="<?php echo esc_url(get_permalink()); ?>">
                                    <?php if ($featured_image): ?>
                                        <img class="wow fadeInRight" src="<?php echo esc_url($featured_image); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
                                    <?php else: ?>
                                        <img class="wow fadeInRight" src="<?php echo get_template_directory_uri(); ?>/images/blog1.png" alt="<?php echo esc_attr(get_the_title()); ?>">
                                    <?php endif; ?>
                                </a>
                                <ul class="wow fadeInLeft">
                                    <li><?php echo esc_html($post_date); ?></li>
                                    <li>|</li>
                                    <li><?php echo esc_html($category_name); ?></li>
                                </ul>
                                <h3 class="wow fadeInRight"><a href="<?php echo esc_url(get_permalink()); ?>"><?php echo esc_html(get_the_title()); ?></a></h3>
                                <div class="blog-btn wow fadeInUp">
                                    <a href="<?php echo esc_url(get_permalink()); ?>">read more</a>
                                </div>
                            </div> 
                              <?php
                          endwhile;
                          wp_reset_postdata();
                      endif; ?>
                 </div> 
              </div>

              <?php 
              // Create tabs for each category
              if ($categories && !is_wp_error($categories)):
                  foreach ($categories as $category): 
                      $category_slug = sanitize_title($category->name);
                      ?>
                      <div id="<?php echo esc_attr($category_slug); ?>" class="tab-pane">
                         <div class="tab-item-wrap">
                              <?php 
                              // Query posts for this specific category
                              $category_posts = new WP_Query(array(
                                  'post_type' => 'news-and-insight',
                                  'posts_per_page' => -1,
                                  'post_status' => 'publish',
                                  'orderby' => 'date',
                                  'order' => 'DESC',
                                  'tax_query' => array(
                                      array(
                                          'taxonomy' => 'news_category',
                                          'field' => 'term_id',
                                          'terms' => $category->term_id,
                                      ),
                                  ),
                              ));
                              
                              if ($category_posts->have_posts()): 
                                  while ($category_posts->have_posts()): 
                                      $category_posts->the_post();
                                      $featured_image = get_the_post_thumbnail_url(get_the_ID(), 'medium');
                                      $post_date = get_the_date('d/m/Y');
                                      ?>
                                      <div class="blog-item blog-item50">
                                        <a href="<?php echo esc_url(get_permalink()); ?>">
                                            <?php if ($featured_image): ?>
                                                <img class="wow fadeInRight" src="<?php echo esc_url($featured_image); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
                                            <?php else: ?>
                                                <img class="wow fadeInRight" src="<?php echo get_template_directory_uri(); ?>/images/blog1.png" alt="<?php echo esc_attr(get_the_title()); ?>">
                                            <?php endif; ?>
                                        </a>
                                        <ul class="wow fadeInLeft">
                                            <li><?php echo esc_html($post_date); ?></li>
                                            <li>|</li>
                                            <li><?php echo esc_html($category->name); ?></li>
                                        </ul>
                                        <h3 class="wow fadeInRight"><a href="<?php echo esc_url(get_permalink()); ?>"><?php echo esc_html(get_the_title()); ?></a></h3>
                                        <div class="blog-btn wow fadeInUp">
                                            <a href="<?php echo esc_url(get_permalink()); ?>">read more</a>
                                        </div>
                                    </div> 
                                      <?php
                                  endwhile;
                                  wp_reset_postdata();
                              else: ?>
                                  <div class="tab-cnt20">
                                      No posts found in this category.
                                  </div>
                              <?php endif; ?>
                         </div> 
                      </div>
                  <?php 
                  endforeach;
              endif; ?>
            </div>
          </div>
        </div>
    </div>
</section>

<?php 
// Check if FAQ section should be shown
$show_faq = get_field('news_show_faq_section', 'option');
if ($show_faq): ?>
<!-- question-section -->
<section class="question-area">
    <div class="container">
        <div class="question-title text-center wow fadeInRight">
            <h2><?php echo esc_html(get_field('news_faq_title', 'option') ?: 'Frequently Asked Questions'); ?></h2>
        </div>
    </div>
    <div class="main-content9">
        <div id="owl-csel9" class="owl-carousel owl-theme">
            <?php 
            // Get FAQ items from ACF options
            $faq_items = get_field('news_faq_items', 'option');
            
            if ($faq_items && is_array($faq_items)): 
                foreach ($faq_items as $faq): ?>
                    <div class="question-item">
                        <h3 class="wow fadeInRight"><?php echo esc_html($faq['faq_question']); ?></h3>
                        <p class="wow fadeInLeft"><?php echo esc_html($faq['faq_answer']); ?></p>
                    </div>
                <?php endforeach;
            else: 
                // Fallback FAQ content
                $faq_items = array(
                    array('title' => 'What is ChainThat?', 'content' => 'ChainThat is a cloud-native platform that improves market speed, customer satisfaction, and efficiency for insurance organizations.'),
                    array('title' => 'How does BPA work?', 'content' => 'Beyond Policy Administration (BPA) provides end-to-end policy servicing and administration capabilities for insurers.'),
                    array('title' => 'What industries do you serve?', 'content' => 'We serve MGAs, carriers, mutuals, and other insurance organizations globally.'),
                    array('title' => 'Is ChainThat secure?', 'content' => 'Yes, ChainThat uses secure data both at rest and in transit with comprehensive authentication.'),
                    array('title' => 'Do you offer SaaS solutions?', 'content' => 'Yes, we offer SaaS solutions where you only pay for what you use.'),
                    array('title' => 'How can I get started?', 'content' => 'Contact us to book a demo and see how ChainThat can benefit your organization.')
                );
                
                foreach ($faq_items as $faq): ?>
                    <div class="question-item">
                        <h3 class="wow fadeInRight"><?php echo esc_html($faq['title']); ?></h3>
                        <p class="wow fadeInLeft"><?php echo esc_html($faq['content']); ?></p>
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
    <div class="container">
        <div class="blog-btm">
           <h3 class="wow fadeInRight"><?php echo esc_html(get_field('news_cta_title', 'option') ?: 'Let\'s Talk'); ?></h3>
           <ul class="wow fadeInLeft">
               <li><div class="btn-all blog-btm-btn"><a href="<?php echo esc_url(get_field('news_cta_demo_link', 'option') ?: '#'); ?>"><?php echo esc_html(get_field('news_cta_demo_text', 'option') ?: 'Book a demo'); ?></a></div></li>
               <li><a href="<?php echo esc_url(get_field('news_cta_contact_link', 'option') ?: '#'); ?>"><?php echo esc_html(get_field('news_cta_contact_text', 'option') ?: 'Get in touch'); ?> <span><img src="<?php echo get_template_directory_uri(); ?>/images/arrow3.png" alt=""></span></a></li>
           </ul>
       </div> 
    </div>
</section>
<?php endif; ?>

<?php get_footer(); ?>

<script>
    // Function to activate a tab within a specific set
    function activateTab(setId, tabId) {
      const container = document.querySelector(`.tab-container[data-set="${setId}"]`);
      if (!container) return;

      container.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
      container.querySelectorAll('.tab-pane').forEach(pane => pane.classList.remove('active'));

      const targetButton = container.querySelector(`.tab-btn[data-tab="${tabId}"]`);
      const targetPane = document.getElementById(tabId);

      if (targetButton && targetPane) {
        targetButton.classList.add('active');
        targetPane.classList.add('active');
      }
    }

    // Handle click events for each tab set
    document.querySelectorAll('.tab-container').forEach(container => {
      const setId = container.getAttribute('data-set');
      container.querySelectorAll('.tab-btn').forEach(button => {
        button.addEventListener('click', (e) => {
          e.preventDefault();
          const tabId = button.getAttribute('data-tab');
          activateTab(setId, tabId);
          window.history.pushState(null, null, `#${tabId}`); // Update URL
        });
      });
    });

    // On page load or refresh, activate first tab of each set
    window.addEventListener('load', () => {
      // Activate first tab of set1
      activateTab('set1', 'all-news');
      // Set URL to first tab of set1 (or leave blank)
      window.history.replaceState(null, null, '#all-news');
    });
</script>

<!-- Initialize FAQ Carousel -->
<script>
jQuery(document).ready(function($) {
    if ($('#owl-csel9').length) {
        $('#owl-csel9').owlCarousel({
            loop: true,
            margin: 30,
            nav: true,
            dots: false,
            autoplay: true,
            autoplayTimeout: 5000,
            autoplayHoverPause: true,
            items: 3,
            responsive: {
                0: {
                    items: 1
                },
                768: {
                    items: 2
                },
                992: {
                    items: 3
                }
            }
        });
    }
});
</script>

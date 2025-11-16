<?php
/**
 * Template Name: Privacy Policy
 * Description: Template for Privacy Policy page
 */

get_header();
?>

<main id="primary" class="site-main privacy-policy-page">
    <?php
    while ( have_posts() ) :
        the_post();
        ?>
        
        <article id="post-<?php the_ID(); ?>" <?php post_class('privacy-policy-content'); ?>>
            <div class="container">
                <!-- Page Header -->
                <header class="entry-header">
                    <h1 class="entry-title wow fadeInUp"><?php the_title(); ?></h1>
                    <?php
                    // Display last modified date
                    $modified_date = get_the_modified_date('F j, Y');
                    ?>
                    <span class="last-updated wow fadeInUp">Last Updated: <?php echo esc_html($modified_date); ?></span>
                </header>

                <!-- Page Content -->
                <div class="entry-content">
                    <?php
                    the_content();

                    wp_link_pages(
                        array(
                            'before' => '<div class="page-links">' . esc_html__('Pages:', 'chainthat'),
                            'after'  => '</div>',
                        )
                    );
                    ?>
                </div>

                <!-- Optional Contact Information Section -->
                <?php
                // You can add ACF fields for contact info or use these defaults
                $contact_email = get_field('privacy_contact_email') ?: 'privacy@chainthat.com';
                $contact_phone = get_field('privacy_contact_phone');
                $contact_address = get_field('privacy_contact_address');
                
                if ($contact_email || $contact_phone || $contact_address):
                ?>
                <div class="privacy-contact-info wow fadeInUp">
                    <h3>Contact Us</h3>
                    <p>If you have any questions about this Privacy Policy, please contact us:</p>
                    
                    <?php if ($contact_email): ?>
                    <p><strong>Email:</strong> <a href="mailto:<?php echo esc_attr($contact_email); ?>"><?php echo esc_html($contact_email); ?></a></p>
                    <?php endif; ?>
                    
                    <?php if ($contact_phone): ?>
                    <p><strong>Phone:</strong> <a href="tel:<?php echo esc_attr($contact_phone); ?>"><?php echo esc_html($contact_phone); ?></a></p>
                    <?php endif; ?>
                    
                    <?php if ($contact_address): ?>
                    <p><strong>Address:</strong> <?php echo nl2br(esc_html($contact_address)); ?></p>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        </article>

    <?php endwhile; ?>
</main>

<?php
get_footer();


<?php
/**
 * Contact Form Block Template
 * 
 * Displays contact form with Contact Form 7 integration
 * Includes optional company and phone fields
 * Terms and privacy policy links
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
$attributes = chainthat_get_block_attributes($block, 'contact-form-block');

// Get data source
$data_source = get_field('data_source') ?: 'page_fields';

// Fetch data based on source
if ($data_source === 'page_fields') {
    // Use existing page-level ACF fields
    $form_title = get_field('form_title', $post_id ?: get_the_ID()) ?: 'Get in Touch';
    $form_subtitle = get_field('form_subtitle', $post_id ?: get_the_ID()) ?: '';
    $cf7_form_id = get_field('contact_form_id', $post_id ?: get_the_ID());
    $show_company = true;
    $show_phone = true;
    $show_terms = true;
    $terms_link = get_field('terms_link', $post_id ?: get_the_ID()) ?: get_privacy_policy_url();
    $privacy_link = get_field('privacy_link', $post_id ?: get_the_ID()) ?: get_privacy_policy_url();
} else {
    // Use block-specific custom fields
    $form_title = get_field('custom_form_title') ?: 'Get in Touch';
    $form_subtitle = get_field('custom_form_subtitle') ?: '';
    $cf7_form_id = get_field('custom_cf7_form');
    $show_company = get_field('custom_show_company') !== false;
    $show_phone = get_field('custom_show_phone') !== false;
    $show_terms = get_field('custom_show_terms') !== false;
    $terms_link = get_field('custom_terms_link') ?: get_privacy_policy_url();
    $privacy_link = get_field('custom_privacy_link') ?: get_privacy_policy_url();
}

// Preview mode check
if ($is_preview && empty($cf7_form_id)) {
    chainthat_block_preview_placeholder(
        'Contact Form',
        'email',
        'Select a Contact Form 7 form to display. Create forms in the CF7 section.'
    );
    return;
}
?>

<!-- contact-form-section -->
<section id="<?php echo esc_attr($attributes['id']); ?>" class="contact-form-area <?php echo esc_attr($attributes['class']); ?>">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="contact-form-wrapper">
                    <?php if ($form_title || $form_subtitle): ?>
                    <div class="form-header text-center">
                        <?php if ($form_title): ?>
                        <h2 class="wow fadeInUp"><?php echo esc_html($form_title); ?></h2>
                        <?php endif; ?>
                        <?php if ($form_subtitle): ?>
                        <p class="wow fadeInUp" data-wow-delay="0.2s"><?php echo wp_kses_post($form_subtitle); ?></p>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>

                    <div class="contact-form wow fadeInUp" data-wow-delay="0.3s">
                        <?php if ($cf7_form_id): ?>
                            <?php 
                            // If CF7 is active, use it
                            if (function_exists('wpcf7_contact_form')) {
                                echo do_shortcode('[contact-form-7 id="' . intval($cf7_form_id) . '"]');
                            } else {
                                // Fallback HTML form
                                ?>
                                <form class="chainthat-contact-form" method="post" action="">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <input type="text" name="first_name" class="form-control" placeholder="First Name *" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <input type="text" name="last_name" class="form-control" placeholder="Last Name *" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <input type="email" name="email" class="form-control" placeholder="Email *" required>
                                        </div>
                                        <?php if ($show_phone): ?>
                                        <div class="col-md-6 mb-3">
                                            <input type="tel" name="phone" class="form-control" placeholder="Phone">
                                        </div>
                                        <?php endif; ?>
                                        <?php if ($show_company): ?>
                                        <div class="col-md-<?php echo $show_phone ? '12' : '6'; ?> mb-3">
                                            <input type="text" name="company" class="form-control" placeholder="Company">
                                        </div>
                                        <?php endif; ?>
                                        <div class="col-12 mb-3">
                                            <textarea name="message" class="form-control" rows="5" placeholder="Message *" required></textarea>
                                        </div>
                                        <?php if ($show_terms): ?>
                                        <div class="col-12 mb-3">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="terms-agreement" name="terms" required>
                                                <label class="form-check-label" for="terms-agreement">
                                                    I agree to the 
                                                    <?php if ($terms_link): ?>
                                                    <a href="<?php echo esc_url($terms_link); ?>" target="_blank">Terms & Conditions</a>
                                                    <?php endif; ?>
                                                    <?php if ($privacy_link): ?>
                                                    and <a href="<?php echo esc_url($privacy_link); ?>" target="_blank">Privacy Policy</a>
                                                    <?php endif; ?>
                                                </label>
                                            </div>
                                        </div>
                                        <?php endif; ?>
                                        <div class="col-12 text-center">
                                            <button type="submit" class="btn-submit">Send Message</button>
                                        </div>
                                    </div>
                                </form>
                            <?php } ?>
                        <?php else: ?>
                            <p class="text-center">Please select a Contact Form 7 form in the block settings.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php if (!$is_preview): ?>
<style>
/* Contact Form Styles */
.contact-form-area {
    padding: 80px 0;
    background: #f8f9fa;
}

.contact-form-wrapper {
    background: #fff;
    border-radius: 8px;
    padding: 60px;
    box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
}

.form-header {
    margin-bottom: 40px;
}

.form-header h2 {
    margin-bottom: 15px;
    font-size: 2rem;
}

.form-header p {
    color: #666;
    font-size: 1.1rem;
}

.contact-form .form-control {
    padding: 15px 20px;
    border: 2px solid #e0e0e0;
    border-radius: 4px;
    font-size: 1rem;
    transition: border-color 0.3s ease;
}

.contact-form .form-control:focus {
    border-color: #007bff;
    outline: none;
    box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
}

.contact-form textarea.form-control {
    resize: vertical;
    min-height: 120px;
}

.form-check {
    padding-left: 1.5rem;
}

.form-check-input {
    width: 18px;
    height: 18px;
    margin-top: 0.25rem;
}

.form-check-label {
    font-size: 0.95rem;
    color: #666;
}

.form-check-label a {
    color: #007bff;
    text-decoration: none;
}

.form-check-label a:hover {
    text-decoration: underline;
}

.btn-submit {
    padding: 15px 50px;
    background: #007bff;
    color: #fff;
    border: 2px solid #007bff;
    border-radius: 4px;
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-submit:hover {
    background: #0056b3;
    border-color: #0056b3;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
}

/* Contact Form 7 Overrides */
.contact-form .wpcf7-form p {
    margin-bottom: 20px;
}

.contact-form .wpcf7-form input[type="text"],
.contact-form .wpcf7-form input[type="email"],
.contact-form .wpcf7-form input[type="tel"],
.contact-form .wpcf7-form textarea {
    width: 100%;
    padding: 15px 20px;
    border: 2px solid #e0e0e0;
    border-radius: 4px;
    font-size: 1rem;
    transition: border-color 0.3s ease;
}

.contact-form .wpcf7-form input:focus,
.contact-form .wpcf7-form textarea:focus {
    border-color: #007bff;
    outline: none;
}

.contact-form .wpcf7-form .wpcf7-submit {
    padding: 15px 50px;
    background: #007bff;
    color: #fff;
    border: 2px solid #007bff;
    border-radius: 4px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.contact-form .wpcf7-form .wpcf7-submit:hover {
    background: #0056b3;
    border-color: #0056b3;
}

.contact-form .wpcf7-response-output {
    margin: 20px 0 0;
    padding: 15px;
    border-radius: 4px;
}

@media (max-width: 991px) {
    .contact-form-area {
        padding: 60px 0;
    }
    
    .contact-form-wrapper {
        padding: 40px 30px;
    }
}

@media (max-width: 767px) {
    .contact-form-area {
        padding: 40px 0;
    }
    
    .contact-form-wrapper {
        padding: 30px 20px;
    }
    
    .form-header h2 {
        font-size: 1.5rem;
    }
    
    .btn-submit {
        width: 100%;
    }
}
</style>
<?php endif; ?>



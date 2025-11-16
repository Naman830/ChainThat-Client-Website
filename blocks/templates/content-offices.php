<?php
/**
 * Office Locations Block Template
 * 
 * Displays office locations with contact information
 * Repeater-based for multiple locations
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
$attributes = chainthat_get_block_attributes($block, 'offices-block');

// Get data source
$data_source = get_field('data_source') ?: 'page_fields';

// Fetch data based on source
if ($data_source === 'page_fields') {
    // Use existing page-level ACF fields
    $section_title = get_field('offices_title', $post_id ?: get_the_ID()) ?: 'Our Offices';
    $section_description = get_field('offices_description', $post_id ?: get_the_ID()) ?: '';
    $offices = get_field('offices_list', $post_id ?: get_the_ID()) ?: array();
} else {
    // Use block-specific custom fields
    $section_title = get_field('custom_section_title') ?: 'Our Offices';
    $section_description = get_field('custom_section_description') ?: '';
    $offices = get_field('custom_offices') ?: array();
}

// Preview mode check
if ($is_preview && empty($offices)) {
    chainthat_block_preview_placeholder(
        'Office Locations',
        'location-alt',
        'Add office locations to display. Perfect for contact pages and footer sections.'
    );
    return;
}

if (empty($offices)) {
    return; // No offices to display
}

$animation_classes = ['fadeInLeft', 'fadeInUp', 'fadeInRight'];
?>

<!-- offices-section -->
<section id="<?php echo esc_attr($attributes['id']); ?>" class="offices-area <?php echo esc_attr($attributes['class']); ?>">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <?php if ($section_title || $section_description): ?>
                <div class="offices-header text-center">
                    <?php if ($section_title): ?>
                    <h2 class="wow fadeInUp"><?php echo esc_html($section_title); ?></h2>
                    <?php endif; ?>
                    <?php if ($section_description): ?>
                    <div class="wow fadeInUp" data-wow-delay="0.2s">
                        <?php echo wp_kses_post($section_description); ?>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <div class="offices-grid">
                    <div class="row">
                        <?php 
                        foreach ($offices as $index => $office):
                            $country = isset($office['country']) ? $office['country'] : (isset($office['office_country']) ? $office['office_country'] : '');
                            $city = isset($office['city']) ? $office['city'] : (isset($office['office_city']) ? $office['office_city'] : '');
                            $address = isset($office['address']) ? $office['address'] : (isset($office['office_address']) ? $office['office_address'] : '');
                            $email = isset($office['email']) ? $office['email'] : (isset($office['office_email']) ? $office['office_email'] : '');
                            $phone = isset($office['phone']) ? $office['phone'] : (isset($office['office_phone']) ? $office['office_phone'] : '');
                            $location_link = isset($office['location_link']) ? $office['location_link'] : (isset($office['office_map_link']) ? $office['office_map_link'] : '');
                            
                            $animation_class = $animation_classes[$index % 3];
                            ?>
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="office-card h-100 wow <?php echo esc_attr($animation_class); ?>">
                                    <div class="office-icon">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    
                                    <?php if ($country || $city): ?>
                                    <div class="office-location">
                                        <?php if ($country): ?>
                                        <h4><?php echo esc_html($country); ?></h4>
                                        <?php endif; ?>
                                        <?php if ($city): ?>
                                        <h5><?php echo esc_html($city); ?></h5>
                                        <?php endif; ?>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <div class="office-details">
                                        <?php if ($address): ?>
                                        <div class="office-detail">
                                            <i class="fas fa-building"></i>
                                            <p><?php echo nl2br(esc_html($address)); ?></p>
                                        </div>
                                        <?php endif; ?>
                                        
                                        <?php if ($email): ?>
                                        <div class="office-detail">
                                            <i class="fas fa-envelope"></i>
                                            <p><a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a></p>
                                        </div>
                                        <?php endif; ?>
                                        
                                        <?php if ($phone): ?>
                                        <div class="office-detail">
                                            <i class="fas fa-phone"></i>
                                            <p><a href="tel:<?php echo esc_attr(str_replace(' ', '', $phone)); ?>"><?php echo esc_html($phone); ?></a></p>
                                        </div>
                                        <?php endif; ?>
                                        
                                        <?php if ($location_link): ?>
                                        <div class="office-link">
                                            <a href="<?php echo esc_url($location_link); ?>" target="_blank" rel="noopener noreferrer">
                                                <i class="fas fa-map"></i> View on Map
                                            </a>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php if (!$is_preview): ?>
<style>
/* Office Locations Styles */
.offices-area {
    padding: 80px 0;
}

.offices-header {
    margin-bottom: 60px;
}

.offices-header h2 {
    margin-bottom: 20px;
}

.offices-header p {
    color: #666;
    font-size: 1.1rem;
    max-width: 800px;
    margin: 0 auto;
}

.office-card {
    background: #fff;
    border-radius: 8px;
    padding: 40px 30px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    text-align: center;
}

.office-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
}

.office-icon {
    width: 70px;
    height: 70px;
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 30px;
}

.office-icon i {
    font-size: 2rem;
    color: #fff;
}

.office-location h4 {
    margin: 0 0 5px;
    font-size: 1.5rem;
    font-weight: 600;
}

.office-location h5 {
    margin: 0 0 25px;
    font-size: 1.1rem;
    font-weight: 400;
    color: #666;
}

.office-details {
    text-align: left;
}

.office-detail {
    display: flex;
    align-items: flex-start;
    gap: 15px;
    margin-bottom: 20px;
}

.office-detail:last-child {
    margin-bottom: 0;
}

.office-detail i {
    flex-shrink: 0;
    width: 24px;
    color: #007bff;
    font-size: 1.1rem;
    margin-top: 3px;
}

.office-detail p {
    margin: 0;
    color: #333;
    line-height: 1.6;
}

.office-detail a {
    color: #333;
    text-decoration: none;
    transition: color 0.3s ease;
}

.office-detail a:hover {
    color: #007bff;
}

.office-link {
    margin-top: 25px;
    padding-top: 25px;
    border-top: 1px solid #e0e0e0;
    text-align: center;
}

.office-link a {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #007bff;
    font-weight: 600;
    text-decoration: none;
    transition: gap 0.3s ease;
}

.office-link a:hover {
    gap: 12px;
}

@media (max-width: 991px) {
    .offices-area {
        padding: 60px 0;
    }
    
    .offices-header {
        margin-bottom: 40px;
    }
}

@media (max-width: 767px) {
    .offices-area {
        padding: 40px 0;
    }
    
    .office-card {
        padding: 30px 20px;
    }
    
    .office-icon {
        width: 60px;
        height: 60px;
    }
    
    .office-icon i {
        font-size: 1.75rem;
    }
}
</style>
<?php endif; ?>



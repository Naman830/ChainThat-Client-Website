<?php
/**
 * Team Grid Block Template
 * 
 * Displays team members in responsive grid
 * Queries team custom post type
 * Supports all/featured display modes
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
$attributes = chainthat_get_block_attributes($block, 'team-grid-block');

// Get data source
$data_source = get_field('data_source') ?: 'page_fields';

// Fetch data based on source
if ($data_source === 'page_fields') {
    // Use existing page-level ACF fields
    $section_title = get_field('team_title', $post_id ?: get_the_ID()) ?: 'Leadership';
    $section_description = get_field('team_description', $post_id ?: get_the_ID()) ?: '';
    $display_mode = 'all';
    $featured_members = array();
    $columns = 3;
    $show_linkedin = true;
} else {
    // Use block-specific custom fields
    $section_title = get_field('custom_section_title') ?: 'Leadership';
    $section_description = get_field('custom_section_description') ?: '';
    $display_mode = get_field('custom_display_mode') ?: 'all';
    $featured_members = get_field('custom_featured_members') ?: array();
    $columns = get_field('custom_columns') ?: 3;
    $show_linkedin = get_field('custom_show_linkedin') !== false;
}

// Query team members
if ($display_mode === 'featured' && !empty($featured_members)) {
    $team_members = $featured_members;
} else {
    // Query all team members
    $team_query = new WP_Query(array(
        'post_type' => 'team',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'orderby' => 'menu_order',
        'order' => 'ASC'
    ));
    
    $team_members = array();
    if ($team_query->have_posts()) {
        while ($team_query->have_posts()) {
            $team_query->the_post();
            $team_members[] = get_post();
        }
        wp_reset_postdata();
    }
}

// Preview mode check
if ($is_preview && empty($team_members)) {
    chainthat_block_preview_placeholder(
        'Team Grid',
        'groups',
        'Create Team posts to display. Configure display mode and layout in block settings.'
    );
    return;
}

if (empty($team_members)) {
    return; // No team members to display
}

// Calculate column class
$col_class_map = array(
    2 => 'col-lg-6',
    3 => 'col-lg-4',
    4 => 'col-lg-3'
);
$col_class = isset($col_class_map[$columns]) ? $col_class_map[$columns] : 'col-lg-4';

$animation_classes = ['fadeInLeft', 'fadeInUp', 'fadeInRight'];
?>

<!-- team-section -->
<section id="<?php echo esc_attr($attributes['id']); ?>" class="team-area <?php echo esc_attr($attributes['class']); ?>">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <?php if ($section_title || $section_description): ?>
                <div class="team-title text-center">
                    <?php if ($section_title): ?>
                    <h2 class="wow fadeInLeft"><?php echo esc_html($section_title); ?></h2>
                    <?php endif; ?>
                    <?php if ($section_description): ?>
                    <p class="wow fadeInRight"><?php echo wp_kses_post($section_description); ?></p>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <div class="team-grid">
                    <div class="row">
                        <?php 
                        foreach ($team_members as $index => $member):
                            $member_id = is_object($member) ? $member->ID : $member;
                            
                            // Get team member fields
                            $name = get_the_title($member_id);
                            $position = get_field('team_position', $member_id) ?: get_field('member_position', $member_id);
                            $bio = get_field('team_bio', $member_id) ?: get_field('member_bio', $member_id);
                            $linkedin = get_field('team_linkedin', $member_id) ?: get_field('member_linkedin', $member_id);
                            $photo = get_the_post_thumbnail_url($member_id, 'medium');
                            
                            $animation_class = $animation_classes[$index % 3];
                            ?>
                            <div class="<?php echo esc_attr($col_class); ?> col-md-6 mb-4">
                                <div class="team-member h-100 wow <?php echo esc_attr($animation_class); ?>">
                                    <?php if ($photo): ?>
                                    <div class="team-photo">
                                        <img src="<?php echo esc_url($photo); ?>" alt="<?php echo esc_attr($name); ?>">
                                    </div>
                                    <?php endif; ?>
                                    
                                    <div class="team-info">
                                        <h4><?php echo esc_html($name); ?></h4>
                                        
                                        <?php if ($position): ?>
                                        <p class="team-position"><?php echo esc_html($position); ?></p>
                                        <?php endif; ?>
                                        
                                        <?php if ($bio): ?>
                                        <p class="team-bio"><?php echo esc_html($bio); ?></p>
                                        <?php endif; ?>
                                        
                                        <?php if ($show_linkedin && $linkedin): ?>
                                        <div class="team-social">
                                            <a href="<?php echo esc_url($linkedin); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr($name); ?> LinkedIn Profile">
                                                <i class="fab fa-linkedin"></i>
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
/* Team Grid Styles */
.team-area {
    padding: 80px 0;
}

.team-title {
    margin-bottom: 60px;
}

.team-title h2 {
    margin-bottom: 20px;
}

.team-member {
    background: #fff;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.team-member:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
}

.team-photo {
    width: 100%;
    height: 300px;
    overflow: hidden;
    background: #f0f0f0;
}

.team-photo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.team-info {
    padding: 30px 20px;
    text-align: center;
}

.team-info h4 {
    margin: 0 0 10px;
    font-size: 1.25rem;
    font-weight: 600;
}

.team-position {
    color: #666;
    font-size: 0.95rem;
    margin: 0 0 15px;
    font-style: italic;
}

.team-bio {
    color: #333;
    font-size: 0.9rem;
    line-height: 1.6;
    margin: 0 0 20px;
}

.team-social {
    display: flex;
    justify-content: center;
    gap: 15px;
}

.team-social a {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    background: #0077b5;
    color: #fff;
    border-radius: 50%;
    font-size: 18px;
    transition: background 0.3s ease, transform 0.3s ease;
}

.team-social a:hover {
    background: #005582;
    transform: scale(1.1);
}

@media (max-width: 991px) {
    .team-area {
        padding: 60px 0;
    }
    
    .team-title {
        margin-bottom: 40px;
    }
    
    .team-photo {
        height: 250px;
    }
}

@media (max-width: 767px) {
    .team-area {
        padding: 40px 0;
    }
    
    .team-photo {
        height: 300px;
    }
    
    .team-info {
        padding: 20px 15px;
    }
}
</style>
<?php endif; ?>



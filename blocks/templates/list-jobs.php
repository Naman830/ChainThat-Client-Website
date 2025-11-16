<?php
/**
 * Jobs Grid Block Template
 * 
 * Displays job listings with pagination
 * Supports manual job entry or auto-query from job CPT
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
$attributes = chainthat_get_block_attributes($block, 'jobs-grid-block');

// Get data source
$data_source = get_field('data_source') ?: 'page_fields';

// Fetch data based on source
if ($data_source === 'page_fields') {
    // Use existing page-level ACF fields (manual jobs from careers page)
    $section_title = get_field('jobs_title', $post_id ?: get_the_ID()) ?: 'Current Openings';
    $display_mode = 'manual';
    $manual_jobs = get_field('jobs_list', $post_id ?: get_the_ID()) ?: array();
    $items_per_page = 6;
} else {
    // Use block-specific custom fields
    $section_title = get_field('custom_section_title') ?: 'Current Openings';
    $display_mode = get_field('custom_display_mode') ?: 'auto';
    $manual_jobs = get_field('custom_manual_jobs') ?: array();
    $max_items = get_field('custom_max_items') ?: 12;
    $items_per_page = get_field('custom_items_per_page') ?: 6;
}

// Get jobs based on display mode
if ($display_mode === 'auto') {
    // Query job custom post type
    $jobs_query = new WP_Query(array(
        'post_type' => 'job',
        'posts_per_page' => isset($max_items) ? $max_items : 12,
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC'
    ));
    
    $jobs = array();
    if ($jobs_query->have_posts()) {
        while ($jobs_query->have_posts()) {
            $jobs_query->the_post();
            $jobs[] = array(
                'date_posted' => get_the_date('F d, Y'),
                'job_title' => get_the_title(),
                'job_description' => get_the_excerpt(),
                'details_link' => get_permalink(),
                'apply_link' => get_field('apply_link') ?: get_permalink()
            );
        }
        wp_reset_postdata();
    }
} else {
    // Manual mode
    $jobs = $manual_jobs;
}

// Preview mode check
if ($is_preview && empty($jobs)) {
    chainthat_block_preview_placeholder(
        'Jobs Grid',
        'portfolio',
        'Add job listings to display. Supports manual entry or auto-query from Job CPT.'
    );
    return;
}

if (empty($jobs)) {
    return; // No jobs to display
}

// Pagination logic
$total_jobs = count($jobs);
$total_pages = ceil($total_jobs / $items_per_page);
$current_page = 1;

// Generate unique ID
$jobs_id = 'jobs-grid-' . $block['id'];
?>

<!-- jobs-section -->
<section id="<?php echo esc_attr($attributes['id']); ?>" class="jobs-area <?php echo esc_attr($attributes['class']); ?>">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <?php if ($section_title): ?>
                <div class="jobs-title text-center">
                    <h2 class="wow fadeInUp"><?php echo esc_html($section_title); ?></h2>
                </div>
                <?php endif; ?>

                <!-- Jobs Grid -->
                <div id="<?php echo esc_attr($jobs_id); ?>" class="jobs-grid">
                    <div class="row">
                        <?php 
                        foreach ($jobs as $index => $job):
                            $date_posted = isset($job['date_posted']) ? $job['date_posted'] : '';
                            $job_title = isset($job['job_title']) ? $job['job_title'] : '';
                            $job_description = isset($job['job_description']) ? $job['job_description'] : (isset($job['description']) ? $job['description'] : '');
                            $details_link = isset($job['details_link']) ? $job['details_link'] : '';
                            $apply_link = isset($job['apply_link']) ? $job['apply_link'] : '';
                            
                            // Calculate page number for this job
                            $job_page = ceil(($index + 1) / $items_per_page);
                            $hidden_class = $job_page > 1 ? ' hidden' : '';
                            
                            $animation_classes = ['fadeInLeft', 'fadeInUp', 'fadeInRight'];
                            $animation_class = $animation_classes[$index % 3];
                            ?>
                            <div class="col-lg-4 col-md-6 mb-4 job-item <?php echo esc_attr($hidden_class); ?>" data-page="<?php echo esc_attr($job_page); ?>">
                                <div class="job-card h-100 wow <?php echo esc_attr($animation_class); ?>">
                                    <?php if ($date_posted): ?>
                                    <div class="job-date">
                                        <i class="far fa-calendar-alt"></i> <?php echo esc_html($date_posted); ?>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($job_title): ?>
                                    <h4><?php echo esc_html($job_title); ?></h4>
                                    <?php endif; ?>
                                    
                                    <?php if ($job_description): ?>
                                    <p><?php echo esc_html(wp_trim_words($job_description, 20)); ?></p>
                                    <?php endif; ?>
                                    
                                    <div class="job-actions">
                                        <?php if ($details_link): ?>
                                        <a href="<?php echo esc_url($details_link); ?>" class="btn-details">View Details</a>
                                        <?php endif; ?>
                                        
                                        <?php if ($apply_link): ?>
                                        <a href="<?php echo esc_url($apply_link); ?>" class="btn-apply">Apply Now</a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <?php if ($total_pages > 1): ?>
                <!-- Pagination -->
                <div class="jobs-pagination text-center wow fadeInUp">
                    <button class="pagination-prev" disabled>
                        <i class="fas fa-chevron-left"></i> Previous
                    </button>
                    
                    <div class="pagination-numbers">
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <button class="page-number <?php echo $i === 1 ? 'active' : ''; ?>" data-page="<?php echo $i; ?>">
                            <?php echo $i; ?>
                        </button>
                        <?php endfor; ?>
                    </div>
                    
                    <button class="pagination-next" <?php echo $total_pages === 1 ? 'disabled' : ''; ?>>
                        Next <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php if (!$is_preview): ?>
<style>
/* Jobs Grid Styles */
.jobs-area {
    padding: 80px 0;
}

.jobs-title {
    margin-bottom: 60px;
}

.job-card {
    background: #fff;
    border-radius: 8px;
    padding: 30px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.job-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
}

.job-date {
    color: #666;
    font-size: 0.9rem;
    margin-bottom: 15px;
}

.job-date i {
    margin-right: 5px;
}

.job-card h4 {
    margin: 0 0 15px;
    font-size: 1.25rem;
    font-weight: 600;
    line-height: 1.4;
}

.job-card p {
    color: #666;
    font-size: 0.95rem;
    line-height: 1.6;
    margin: 0 0 20px;
}

.job-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.job-actions a {
    flex: 1;
    min-width: 120px;
    padding: 10px 20px;
    text-align: center;
    text-decoration: none;
    border-radius: 4px;
    font-weight: 600;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.btn-details {
    background: #f8f9fa;
    color: #333;
    border: 2px solid #e0e0e0;
}

.btn-details:hover {
    background: #e9ecef;
    border-color: #ccc;
}

.btn-apply {
    background: #007bff;
    color: #fff;
    border: 2px solid #007bff;
}

.btn-apply:hover {
    background: #0056b3;
    border-color: #0056b3;
}

.job-item.hidden {
    display: none !important;
}

/* Pagination */
.jobs-pagination {
    margin-top: 50px;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 15px;
}

.jobs-pagination button {
    padding: 10px 20px;
    background: #fff;
    border: 2px solid #e0e0e0;
    border-radius: 4px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.jobs-pagination button:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.jobs-pagination button:not(:disabled):hover {
    border-color: #007bff;
    color: #007bff;
}

.pagination-numbers {
    display: flex;
    gap: 10px;
}

.page-number.active {
    background: #007bff;
    color: #fff;
    border-color: #007bff;
}

@media (max-width: 991px) {
    .jobs-area {
        padding: 60px 0;
    }
    
    .jobs-title {
        margin-bottom: 40px;
    }
}

@media (max-width: 767px) {
    .jobs-area {
        padding: 40px 0;
    }
    
    .job-card {
        padding: 20px;
    }
    
    .job-actions {
        flex-direction: column;
    }
    
    .job-actions a {
        width: 100%;
    }
    
    .jobs-pagination {
        flex-direction: column;
        gap: 10px;
    }
}
</style>

<script>
jQuery(document).ready(function($) {
    let currentPage = 1;
    const totalPages = <?php echo intval($total_pages); ?>;
    
    function showPage(page) {
        $('.job-item').addClass('hidden');
        $('.job-item[data-page="' + page + '"]').removeClass('hidden');
        
        // Update pagination
        $('.page-number').removeClass('active');
        $('.page-number[data-page="' + page + '"]').addClass('active');
        
        // Update prev/next buttons
        $('.pagination-prev').prop('disabled', page === 1);
        $('.pagination-next').prop('disabled', page === totalPages);
        
        // Scroll to top of jobs
        $('html, body').animate({
            scrollTop: $('.jobs-grid').offset().top - 100
        }, 300);
        
        currentPage = page;
    }
    
    // Page number click
    $('.page-number').on('click', function() {
        const page = parseInt($(this).data('page'));
        showPage(page);
    });
    
    // Previous button
    $('.pagination-prev').on('click', function() {
        if (currentPage > 1) {
            showPage(currentPage - 1);
        }
    });
    
    // Next button
    $('.pagination-next').on('click', function() {
        if (currentPage < totalPages) {
            showPage(currentPage + 1);
        }
    });
});
</script>
<?php endif; ?>



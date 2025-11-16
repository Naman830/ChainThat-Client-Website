<?php
/**
 * Video Embed Block Template
 * 
 * Displays video content with cover images and play controls
 * Supports YouTube, Vimeo, and self-hosted videos
 * Multiple layout options: full-width, split-left, split-right
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
$attributes = chainthat_get_block_attributes($block, 'video-block');

// Get data source
$data_source = get_field('data_source') ?: 'page_fields';

// Fetch data based on source
if ($data_source === 'page_fields') {
    // Use existing page-level ACF fields
    $video_section = get_field('video_section', $post_id ?: get_the_ID());
    $video_type = isset($video_section['video_type']) ? $video_section['video_type'] : 'youtube';
    $video_url = isset($video_section['video_url']) ? $video_section['video_url'] : '';
    $video_id = isset($video_section['video_id']) ? $video_section['video_id'] : '';
    $cover_image_desktop = isset($video_section['video_cover_desktop']) ? $video_section['video_cover_desktop'] : '';
    $cover_image_mobile = isset($video_section['video_cover_mobile']) ? $video_section['video_cover_mobile'] : '';
    $video_title = isset($video_section['video_title']) ? $video_section['video_title'] : '';
    $video_description = isset($video_section['video_description']) ? $video_section['video_description'] : '';
    $button_text = isset($video_section['button_text']) ? $video_section['button_text'] : '';
    $button_link = isset($video_section['button_link']) ? $video_section['button_link'] : '';
    $layout = 'full-width';
} else {
    // Use block-specific custom fields
    $video_type = get_field('custom_video_type') ?: 'youtube';
    $video_url = get_field('custom_video_url') ?: '';
    $video_id = get_field('custom_video_id') ?: '';
    $cover_image_desktop = get_field('custom_cover_image_desktop') ?: '';
    $cover_image_mobile = get_field('custom_cover_image_mobile') ?: '';
    $video_title = get_field('custom_video_title') ?: '';
    $video_description = get_field('custom_video_description') ?: '';
    $button_text = get_field('custom_button_text') ?: '';
    $button_link = get_field('custom_button_link') ?: '';
    $layout = get_field('custom_layout') ?: 'full-width';
}

// Handle ACF image array format
if (is_array($cover_image_desktop) && isset($cover_image_desktop['url'])) {
    $cover_image_desktop = $cover_image_desktop['url'];
}
if (is_array($cover_image_mobile) && isset($cover_image_mobile['url'])) {
    $cover_image_mobile = $cover_image_mobile['url'];
}

// Extract video ID from URL if not provided
if (empty($video_id) && !empty($video_url)) {
    if ($video_type === 'youtube') {
        $video_id = chainthat_get_youtube_id($video_url);
    } elseif ($video_type === 'vimeo') {
        $video_id = chainthat_get_vimeo_id($video_url);
    }
}

// Preview mode check
if ($is_preview && empty($video_id) && empty($video_url)) {
    chainthat_block_preview_placeholder(
        'Video Embed',
        'video-alt3',
        'Add a video URL to display. Supports YouTube, Vimeo, and self-hosted videos.'
    );
    return;
}

if (empty($video_id) && empty($video_url)) {
    return; // No video to display
}

// Generate unique video ID
$video_container_id = 'video-' . $block['id'];

// Build embed URL
$embed_url = '';
if ($video_type === 'youtube' && $video_id) {
    $embed_url = 'https://www.youtube.com/embed/' . $video_id . '?enablejsapi=1&rel=0';
} elseif ($video_type === 'vimeo' && $video_id) {
    $embed_url = 'https://player.vimeo.com/video/' . $video_id . '?title=0&byline=0&portrait=0';
} elseif ($video_type === 'self-hosted' && $video_url) {
    $embed_url = $video_url;
}
?>

<!-- video-section -->
<section id="<?php echo esc_attr($attributes['id']); ?>" class="video-area video-layout-<?php echo esc_attr($layout); ?> <?php echo esc_attr($attributes['class']); ?>">
    <div class="container<?php echo $layout === 'full-width' ? '-fluid' : ''; ?>">
        <div class="row <?php echo $layout !== 'full-width' ? 'align-items-center' : ''; ?>">
            <!-- Video Column -->
            <div class="<?php echo $layout === 'full-width' ? 'col-12' : ($layout === 'split-left' ? 'col-lg-6 order-lg-1' : 'col-lg-6 order-lg-2'); ?>">
                <div class="video-wrapper wow fadeIn<?php echo $layout === 'split-left' ? 'Right' : 'Left'; ?>">
                    <div id="<?php echo esc_attr($video_container_id); ?>" class="video-container" data-video-type="<?php echo esc_attr($video_type); ?>">
                        <?php if ($cover_image_desktop || $cover_image_mobile): ?>
                        <!-- Cover Image -->
                        <div class="video-cover active">
                            <?php if ($cover_image_desktop): ?>
                            <img class="video-cover-desktop d-none d-md-block" 
                                 src="<?php echo esc_url($cover_image_desktop); ?>" 
                                 alt="<?php echo esc_attr($video_title ?: 'Video Cover'); ?>">
                            <?php endif; ?>
                            
                            <?php if ($cover_image_mobile): ?>
                            <img class="video-cover-mobile d-block d-md-none" 
                                 src="<?php echo esc_url($cover_image_mobile); ?>" 
                                 alt="<?php echo esc_attr($video_title ?: 'Video Cover'); ?>">
                            <?php elseif ($cover_image_desktop): ?>
                            <img class="video-cover-mobile d-block d-md-none" 
                                 src="<?php echo esc_url($cover_image_desktop); ?>" 
                                 alt="<?php echo esc_attr($video_title ?: 'Video Cover'); ?>">
                            <?php endif; ?>
                            
                            <!-- Play Button -->
                            <button class="video-play-btn" 
                                    onclick="playVideo('<?php echo esc_js($video_container_id); ?>')" 
                                    aria-label="Play video">
                                <svg width="80" height="80" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="40" cy="40" r="40" fill="rgba(255,255,255,0.9)"/>
                                    <path d="M32 25L55 40L32 55V25Z" fill="#333"/>
                                </svg>
                            </button>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Video Iframe -->
                        <div class="video-iframe-wrapper" style="<?php echo ($cover_image_desktop || $cover_image_mobile) ? 'display: none;' : ''; ?>">
                            <?php if ($video_type === 'self-hosted'): ?>
                            <video controls class="video-player" style="width: 100%; height: auto;">
                                <source src="<?php echo esc_url($video_url); ?>" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                            <?php else: ?>
                            <iframe id="iframe-<?php echo esc_attr($video_container_id); ?>"
                                    class="video-iframe" 
                                    src="<?php echo esc_url($embed_url); ?>"
                                    frameborder="0" 
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                    allowfullscreen>
                            </iframe>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <?php if ($layout !== 'full-width' && ($video_title || $video_description || $button_text)): ?>
            <!-- Content Column -->
            <div class="col-lg-6 <?php echo $layout === 'split-left' ? 'order-lg-2' : 'order-lg-1'; ?>">
                <div class="video-content wow fadeIn<?php echo $layout === 'split-left' ? 'Left' : 'Right'; ?>">
                    <?php if ($video_title): ?>
                    <h2><?php echo esc_html($video_title); ?></h2>
                    <?php endif; ?>
                    
                    <?php if ($video_description): ?>
                    <p><?php echo wp_kses_post($video_description); ?></p>
                    <?php endif; ?>
                    
                    <?php if ($button_text && $button_link): ?>
                    <div class="video-button">
                        <a href="<?php echo esc_url($button_link); ?>" class="btn-primary">
                            <?php echo esc_html($button_text); ?>
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php if (!$is_preview): ?>
<style>
/* Video Block Styles */
.video-area {
    padding: 80px 0;
}

.video-container {
    position: relative;
    padding-bottom: 56.25%; /* 16:9 aspect ratio */
    height: 0;
    overflow: hidden;
    background: #000;
    border-radius: 8px;
}

.video-cover {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 2;
    transition: opacity 0.3s ease;
}

.video-cover.active {
    opacity: 1;
}

.video-cover img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.video-play-btn {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: none;
    border: none;
    cursor: pointer;
    z-index: 3;
    transition: transform 0.3s ease;
}

.video-play-btn:hover {
    transform: translate(-50%, -50%) scale(1.1);
}

.video-iframe-wrapper {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}

.video-iframe {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}

.video-content {
    padding: 20px;
}

.video-content h2 {
    margin-bottom: 20px;
}

.video-content p {
    margin-bottom: 30px;
    line-height: 1.8;
}

@media (max-width: 991px) {
    .video-area {
        padding: 60px 0;
    }
    
    .video-content {
        margin-top: 30px;
        padding: 0;
    }
}

@media (max-width: 767px) {
    .video-play-btn svg {
        width: 60px;
        height: 60px;
    }
}
</style>

<script>
// Play video function (if not already defined globally)
if (typeof window.playVideo === 'undefined') {
    window.playVideo = function(containerId) {
        const container = document.getElementById(containerId);
        if (!container) return;
        
        const cover = container.querySelector('.video-cover');
        const iframeWrapper = container.querySelector('.video-iframe-wrapper');
        const iframe = container.querySelector('.video-iframe');
        const video = container.querySelector('.video-player');
        const videoType = container.getAttribute('data-video-type');
        
        // Hide cover
        if (cover) {
            cover.style.opacity = '0';
            setTimeout(() => {
                cover.style.display = 'none';
            }, 300);
        }
        
        // Show and play video
        if (iframeWrapper) {
            iframeWrapper.style.display = 'block';
        }
        
        if (videoType === 'self-hosted' && video) {
            video.play();
        } else if (iframe) {
            // Autoplay for YouTube/Vimeo
            const currentSrc = iframe.src;
            if (videoType === 'youtube') {
                iframe.src = currentSrc + '&autoplay=1';
            } else if (videoType === 'vimeo') {
                iframe.src = currentSrc + '&autoplay=1';
            }
        }
    };
}
</script>
<?php endif; ?>



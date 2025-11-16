<?php
/**
 * Block Helper Functions
 * 
 * Shared utility functions for ACF blocks
 * 
 * @package ChainThat
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get block ID and classes
 * 
 * @param array $block The block array
 * @param string $base_class Base class name for the block
 * @return array Array containing 'id' and 'class' keys
 */
function chainthat_get_block_attributes($block, $base_class = '') {
    $block_id = 'block-' . $block['id'];
    if (!empty($block['anchor'])) {
        $block_id = $block['anchor'];
    }
    
    $block_class = $base_class;
    if (!empty($block['className'])) {
        $block_class .= ' ' . $block['className'];
    }
    if (!empty($block['align'])) {
        $block_class .= ' align' . $block['align'];
    }
    
    return array(
        'id' => $block_id,
        'class' => trim($block_class),
    );
}

/**
 * Get data from page fields or custom block fields
 * Uses the hybrid data source approach
 * 
 * @param string $page_field_name The field name in page-level ACF
 * @param string $custom_field_name The field name in block-level ACF (optional)
 * @param mixed $post_id Optional post ID to get data from
 * @return mixed The field value
 */
function chainthat_get_field_data($page_field_name, $custom_field_name = null, $post_id = null) {
    // Get data source preference from block
    $data_source = get_field('data_source') ?: 'page_fields';
    
    if ($data_source === 'page_fields') {
        // Use existing page-level ACF field
        if ($post_id === null) {
            $post_id = get_the_ID();
        }
        return get_field($page_field_name, $post_id);
    } else {
        // Use block-specific custom field
        if ($custom_field_name !== null) {
            return get_field($custom_field_name);
        }
        return get_field($page_field_name);
    }
}

/**
 * Check if we're in preview mode
 * 
 * @return bool
 */
function chainthat_is_block_preview() {
    return isset($_GET['context']) && $_GET['context'] === 'edit';
}

/**
 * Get block wrapper opening tag
 * 
 * @param array $attributes Array with 'id' and 'class' keys
 * @param string $tag HTML tag to use (default: 'div')
 * @return string Opening HTML tag
 */
function chainthat_block_wrapper_open($attributes, $tag = 'div') {
    $id = esc_attr($attributes['id']);
    $class = esc_attr($attributes['class']);
    return "<{$tag} id=\"{$id}\" class=\"{$class}\">";
}

/**
 * Get block wrapper closing tag
 * 
 * @param string $tag HTML tag to use (default: 'div')
 * @return string Closing HTML tag
 */
function chainthat_block_wrapper_close($tag = 'div') {
    return "</{$tag}>";
}

/**
 * Render block preview placeholder
 * 
 * @param string $title Block title
 * @param string $icon Dashicon class name
 * @param string $description Optional description
 */
function chainthat_block_preview_placeholder($title, $icon = 'admin-generic', $description = '') {
    ?>
    <div class="chainthat-block-preview">
        <div class="chainthat-block-preview-inner">
            <span class="dashicons dashicons-<?php echo esc_attr($icon); ?>"></span>
            <h3><?php echo esc_html($title); ?></h3>
            <?php if ($description): ?>
                <p><?php echo esc_html($description); ?></p>
            <?php endif; ?>
        </div>
    </div>
    <style>
        .chainthat-block-preview {
            background: #f0f0f1;
            border: 2px dashed #c3c4c7;
            padding: 40px;
            text-align: center;
            margin: 20px 0;
        }
        .chainthat-block-preview-inner {
            max-width: 400px;
            margin: 0 auto;
        }
        .chainthat-block-preview .dashicons {
            font-size: 48px;
            width: 48px;
            height: 48px;
            color: #2271b1;
            margin-bottom: 10px;
        }
        .chainthat-block-preview h3 {
            margin: 10px 0;
            font-size: 18px;
            color: #1d2327;
        }
        .chainthat-block-preview p {
            color: #646970;
            margin: 5px 0 0;
        }
    </style>
    <?php
}

/**
 * Sanitize and prepare repeater field data
 * 
 * @param array $repeater_data Raw repeater field data
 * @return array Sanitized repeater data
 */
function chainthat_sanitize_repeater($repeater_data) {
    if (!$repeater_data || !is_array($repeater_data)) {
        return array();
    }
    return $repeater_data;
}

/**
 * Get animation class for items in a loop
 * 
 * @param int $index Current loop index
 * @param array $classes Array of animation classes to cycle through
 * @return string Animation class
 */
function chainthat_get_animation_class($index, $classes = array('fadeInLeft', 'fadeInUp', 'fadeInRight')) {
    return $classes[$index % count($classes)];
}

/**
 * Check if section should be displayed (for legacy section controls)
 * 
 * @param string $section_name Section name
 * @param string $control_field_name Field name containing section controls
 * @param int $post_id Optional post ID
 * @return bool
 */
function chainthat_is_section_visible($section_name, $control_field_name, $post_id = null) {
    if ($post_id === null) {
        $post_id = get_the_ID();
    }
    
    $section_controls = get_field($control_field_name, $post_id);
    if (!$section_controls || !is_array($section_controls)) {
        return true; // Show by default
    }
    
    $toggle_field = $section_name . '_section_toggle';
    if (!isset($section_controls[$toggle_field])) {
        return true; // Show by default
    }
    
    return $section_controls[$toggle_field] === 'show';
}

/**
 * Get formatted animation attributes
 * 
 * @param string $animation_class WOW animation class
 * @param int $delay Optional delay in milliseconds
 * @return string Animation attributes for HTML
 */
function chainthat_get_animation_attrs($animation_class, $delay = 0) {
    $attrs = 'class="wow ' . esc_attr($animation_class) . '"';
    if ($delay > 0) {
        $attrs .= ' data-wow-delay="' . esc_attr($delay) . 'ms"';
    }
    return $attrs;
}

/**
 * Truncate text to word limit
 * 
 * @param string $text Text to truncate
 * @param int $limit Word limit
 * @param string $more Text to append if truncated
 * @return string Truncated text
 */
function chainthat_truncate_text($text, $limit = 20, $more = '...') {
    if (empty($text)) {
        return '';
    }
    
    $words = explode(' ', $text);
    if (count($words) <= $limit) {
        return $text;
    }
    
    return implode(' ', array_slice($words, 0, $limit)) . $more;
}

/**
 * Get responsive image HTML
 * 
 * @param string $desktop_image Desktop image URL or ID
 * @param string $mobile_image Mobile image URL or ID
 * @param string $alt Alt text
 * @param string $class Optional CSS class
 * @return string Image HTML
 */
function chainthat_get_responsive_image($desktop_image, $mobile_image, $alt = '', $class = '') {
    $html = '';
    
    if ($desktop_image) {
        $desktop_url = is_numeric($desktop_image) ? wp_get_attachment_url($desktop_image) : $desktop_image;
        $html .= '<img class="d-none d-lg-block ' . esc_attr($class) . '" src="' . esc_url($desktop_url) . '" alt="' . esc_attr($alt) . '">';
    }
    
    if ($mobile_image) {
        $mobile_url = is_numeric($mobile_image) ? wp_get_attachment_url($mobile_image) : $mobile_image;
        $html .= '<img class="d-block d-lg-none ' . esc_attr($class) . '" src="' . esc_url($mobile_url) . '" alt="' . esc_attr($alt) . '">';
    } elseif ($desktop_image) {
        // Use desktop image for mobile if no mobile image provided
        $desktop_url = is_numeric($desktop_image) ? wp_get_attachment_url($desktop_image) : $desktop_image;
        $html .= '<img class="d-block d-lg-none ' . esc_attr($class) . '" src="' . esc_url($desktop_url) . '" alt="' . esc_attr($alt) . '">';
    }
    
    return $html;
}

/**
 * Extract YouTube video ID from URL
 * 
 * @param string $url YouTube URL
 * @return string|false Video ID or false if not found
 */
function chainthat_get_youtube_id($url) {
    if (empty($url)) {
        return false;
    }
    
    preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/', $url, $matches);
    return !empty($matches[1]) ? $matches[1] : false;
}

/**
 * Extract Vimeo video ID from URL
 * 
 * @param string $url Vimeo URL
 * @return string|false Video ID or false if not found
 */
function chainthat_get_vimeo_id($url) {
    if (empty($url)) {
        return false;
    }
    
    preg_match('/vimeo\.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|)(\d+)(?:$|\/|\?)/', $url, $matches);
    return !empty($matches[3]) ? $matches[3] : false;
}

/**
 * Generate unique carousel ID
 * 
 * @param string $prefix Prefix for the ID
 * @return string Unique ID
 */
function chainthat_get_carousel_id($prefix = 'carousel') {
    static $counter = 0;
    $counter++;
    return $prefix . '-' . $counter . '-' . uniqid();
}



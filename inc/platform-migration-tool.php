<?php
/**
 * Platform Content Migration Tool
 * 
 * Admin page with buttons to migrate content for each platform
 * Adds menu item under Tools in WordPress admin
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Include the migration functions file FIRST
 */
require_once get_template_directory() . '/inc/platform-migration-functions.php';

/**
 * Add admin menu for Platform Migration Tool
 */
function chainthat_add_platform_migration_menu() {
    add_management_page(
        'Platform Content Migration',      // Page title
        'Platform Migration',              // Menu title
        'manage_options',                  // Capability
        'platform-migration',              // Menu slug
        'chainthat_platform_migration_page' // Callback function
    );
}
add_action('admin_menu', 'chainthat_add_platform_migration_menu');

/**
 * Render the Platform Migration Admin Page
 */
function chainthat_platform_migration_page() {
    // Handle form submission
    if (isset($_POST['migrate_platform']) && check_admin_referer('platform_migration_action', 'platform_migration_nonce')) {
        $platform_type = sanitize_text_field($_POST['platform_type']);
        $result = chainthat_migrate_platform_content($platform_type);
        
        if ($result['success']) {
            echo '<div class="notice notice-success is-dismissible"><p><strong>Success!</strong> ' . esc_html($result['message']) . '</p></div>';
            echo '<div class="notice notice-info"><p>';
            echo '<a href="' . esc_url(get_permalink($result['post_id'])) . '" target="_blank" class="button">View Post</a> ';
            echo '<a href="' . esc_url(admin_url('post.php?post=' . $result['post_id'] . '&action=edit')) . '" target="_blank" class="button button-primary">Edit Post</a>';
            echo '</p></div>';
        } else {
            echo '<div class="notice notice-error is-dismissible"><p><strong>Error!</strong> ' . esc_html($result['message']) . '</p></div>';
        }
    }
    ?>
    
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <p>Use the buttons below to migrate content for each platform. You can run this multiple times - it will update existing posts.</p>
        
        <hr>
        
        <div class="platform-migration-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-top: 30px;">
            
            <!-- BPA Platform -->
            <div class="platform-card" style="border: 2px solid #00524C; padding: 25px; border-radius: 8px; background: #fff;">
                <h2 style="color: #00524C; margin-top: 0;">
                    <span class="dashicons dashicons-admin-settings" style="font-size: 24px; vertical-align: middle;"></span>
                    BPA Platform
                </h2>
                <h3 style="font-size: 16px; font-weight: 600; color: #333;">Beyond Policy Administration</h3>
                <p style="color: #666; line-height: 1.6;">Next-generation policy administration platform. A digital catalyst for growth.</p>
                
                <form method="post" style="margin-top: 20px;">
                    <?php wp_nonce_field('platform_migration_action', 'platform_migration_nonce'); ?>
                    <input type="hidden" name="platform_type" value="bpa">
                    <button type="submit" name="migrate_platform" class="button button-primary button-hero" style="background: #00524C; border-color: #00524C; width: 100%;">
                        <span class="dashicons dashicons-update" style="vertical-align: middle;"></span> Migrate BPA Content
                    </button>
                </form>
                
                <?php
                $bpa_post = get_page_by_title('Beyond Policy Administration', OBJECT, 'platform');
                if ($bpa_post): ?>
                    <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #ddd;">
                        <p style="margin: 0; color: #46b450; font-weight: 600;">
                            <span class="dashicons dashicons-yes-alt" style="vertical-align: middle;"></span> Already Created
                        </p>
                        <p style="margin: 5px 0 0 0; font-size: 12px; color: #666;">
                            <a href="<?php echo esc_url(get_permalink($bpa_post->ID)); ?>" target="_blank">View</a> | 
                            <a href="<?php echo esc_url(admin_url('post.php?post=' . $bpa_post->ID . '&action=edit')); ?>" target="_blank">Edit</a>
                        </p>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- BMNP Platform -->
            <div class="platform-card" style="border: 2px solid #EB7923; padding: 25px; border-radius: 8px; background: #fff;">
                <h2 style="color: #EB7923; margin-top: 0;">
                    <span class="dashicons dashicons-admin-site-alt3" style="font-size: 24px; vertical-align: middle;"></span>
                    BMNP Platform
                </h2>
                <h3 style="font-size: 16px; font-weight: 600; color: #333;">Beyond Multi-National Programs</h3>
                <p style="color: #666; line-height: 1.6;">Activates agility in multinational insurance programs with 6 capability tabs and full architecture.</p>
                
                <form method="post" style="margin-top: 20px;">
                    <?php wp_nonce_field('platform_migration_action', 'platform_migration_nonce'); ?>
                    <input type="hidden" name="platform_type" value="bmnp">
                    <button type="submit" name="migrate_platform" class="button button-primary button-hero" style="background: #EB7923; border-color: #EB7923; width: 100%;">
                        <span class="dashicons dashicons-update" style="vertical-align: middle;"></span> Migrate BMNP Content
                    </button>
                </form>
                
                <?php
                $bmnp_post = get_page_by_title('Beyond Multi-National Programs', OBJECT, 'platform');
                if ($bmnp_post): ?>
                    <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #ddd;">
                        <p style="margin: 0; color: #46b450; font-weight: 600;">
                            <span class="dashicons dashicons-yes-alt" style="vertical-align: middle;"></span> Already Created
                        </p>
                        <p style="margin: 5px 0 0 0; font-size: 12px; color: #666;">
                            <a href="<?php echo esc_url(get_permalink($bmnp_post->ID)); ?>" target="_blank">View</a> | 
                            <a href="<?php echo esc_url(admin_url('post.php?post=' . $bmnp_post->ID . '&action=edit')); ?>" target="_blank">Edit</a>
                        </p>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- BIA Platform -->
            <div class="platform-card" style="border: 2px solid #009688; padding: 25px; border-radius: 8px; background: #fff;">
                <h2 style="color: #009688; margin-top: 0;">
                    <span class="dashicons dashicons-chart-bar" style="font-size: 24px; vertical-align: middle;"></span>
                    BIA Platform
                </h2>
                <h3 style="font-size: 16px; font-weight: 600; color: #333;">Beyond Insurance Accounting</h3>
                <p style="color: #666; line-height: 1.6;">Revolutionises insurance accounting with automated source-to-ledger processes. Features 7 BIA-specific accounting capability tabs including Invoice Generation, PAS Integration, Commission Management, Trust Accounting, and more.</p>
                
                <form method="post" style="margin-top: 20px;">
                    <?php wp_nonce_field('platform_migration_action', 'platform_migration_nonce'); ?>
                    <input type="hidden" name="platform_type" value="bia">
                    <button type="submit" name="migrate_platform" class="button button-primary button-hero" style="background: #009688; border-color: #009688; width: 100%;">
                        <span class="dashicons dashicons-update" style="vertical-align: middle;"></span> Migrate BIA Content
                    </button>
                </form>
                
            <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #ddd;">
                <p style="margin: 0; color: #666; font-size: 13px;">
                    <span class="dashicons dashicons-yes-alt" style="color: #009688; vertical-align: middle;"></span>
                    <strong>Ready:</strong> 7 accounting tabs, 25 items, 4 benefits + auto image import
                </p>
            </div>
            </div>
            
        </div>
        
        <hr style="margin: 40px 0;">
        
        <div style="background: #f0f0f1; padding: 20px; border-left: 4px solid #EB7923; border-radius: 4px;">
            <h3 style="margin-top: 0;">
                <span class="dashicons dashicons-info" style="color: #EB7923; vertical-align: middle;"></span>
                How to Use This Tool
            </h3>
            <ol style="line-height: 1.8;">
                <li><strong>BPA Platform:</strong> Fully configured with 6 capability tabs (24 items) and automatic image imports.</li>
                <li><strong>BMNP Platform:</strong> Fully configured with 6 capability tabs (24 items) and automatic image imports.</li>
                <li><strong>BIA Platform:</strong> Fully configured with 7 BIA-specific accounting capability tabs (25 items) and automatic image imports.</li>
                <li>Click the "Migrate" button for each platform to populate content.</li>
                <li>You can run migrations multiple times - existing posts will be updated.</li>
                <li>All images are automatically downloaded and imported to your media library.</li>
                <li>All content is stored in ACF fields for easy editing.</li>
            </ol>
            
            <h4 style="margin-top: 20px;">Migration Script Location:</h4>
            <p style="margin: 5px 0; font-family: monospace; background: #fff; padding: 10px; border-radius: 4px;">
                <?php echo get_template_directory(); ?>/inc/platform-migration-functions.php
            </p>
        </div>
        
    </div>
    
    <style>
        .platform-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
            transition: all 0.3s ease;
        }
        
        .platform-card button:hover {
            opacity: 0.9;
            transform: translateY(-1px);
            transition: all 0.2s ease;
        }
    </style>
    
    <?php
}

/**
 * Main Migration Function
 * Routes to specific platform migration based on type
 */
function chainthat_migrate_platform_content($platform_type) {
    switch ($platform_type) {
        case 'bpa':
            return chainthat_migrate_bpa_platform();
            break;
        
        case 'bmnp':
            return chainthat_migrate_bmnp_platform();
            break;
        
        case 'bia':
            return chainthat_migrate_bia_platform();
            break;
        
        default:
            return array(
                'success' => false,
                'message' => 'Invalid platform type selected.'
            );
    }
}


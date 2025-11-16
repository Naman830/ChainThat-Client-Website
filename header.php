<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php wp_title('|', true, 'right'); ?><?php bloginfo('name'); ?></title>
    <!-- Favicon -->
    <?php
    // Get ACF Theme Options favicon first
    $logos_section = get_field('theme_logos_section', 'option');
    $acf_favicon = ($logos_section && isset($logos_section['theme_favicon']) && $logos_section['theme_favicon']) ? $logos_section['theme_favicon'] : '';
    
    // Priority: ACF Theme Options > WordPress Customizer > Fallback
    if ($acf_favicon) {
        echo '<link rel="icon" href="' . esc_url($acf_favicon) . '" sizes="32x32" />';
        echo '<link rel="apple-touch-icon" href="' . esc_url($acf_favicon) . '" />';
    } else {
        $site_icon = get_site_icon_url();
        if ($site_icon) {
            echo '<link rel="icon" href="' . esc_url($site_icon) . '" sizes="32x32" />';
            echo '<link rel="apple-touch-icon" href="' . esc_url($site_icon) . '" />';
        } else {
            // Fallback to theme favicon if no site icon is set
            echo '<link rel="icon" href="' . get_template_directory_uri() . '/images/favicon.ico" />';
        }
    }
    ?>
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- HEADER -->
<header class="header-area header-area1">
    <!-- <div class="container"> -->
        <div class="header-main">
            <?php
            // Get logos from ACF Theme Options with fallbacks
            if (!isset($logos_section)) {
                $logos_section = get_field('theme_logos_section', 'option');
            }
            $main_logo = ($logos_section && isset($logos_section['theme_main_logo']) && $logos_section['theme_main_logo']) ? $logos_section['theme_main_logo'] : get_template_directory_uri() . '/images/logo.png';
            $mobile_logo = ($logos_section && isset($logos_section['theme_mobile_logo']) && $logos_section['theme_mobile_logo']) ? $logos_section['theme_mobile_logo'] : get_template_directory_uri() . '/images/mobil-loog.png';
            ?>
            <div class="logo-item wow fadeInLeft">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="ChainThat home"><img class="d-none d-lg-block" src="<?php echo esc_url($main_logo); ?>" alt="ChainThat logo"/></a>
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="ChainThat home"><img class="d-block d-lg-none" src="<?php echo esc_url($mobile_logo); ?>" alt="ChainThat logo"></a>
            </div>
            <nav class="main-nav wow fadeInUp">
                <?php
                // Get navigation menu from ACF theme options
                $nav_menu = get_field('theme_navigation_menu', 'option');
                
                if ($nav_menu): 
                    wp_nav_menu(array(
                        'menu' => $nav_menu,
                        'menu_class' => 'primary-menu',
                        'container' => false,
                        'fallback_cb' => false,
                        'walker' => new ChainThat_Walker_Nav_Menu(),
                    ));
                else: 
                    // Fallback to theme location if no ACF menu is selected
                    wp_nav_menu(array(
                        'theme_location' => 'primary',
                        'menu_class' => 'primary-menu',
                        'container' => false,
                        'fallback_cb' => false,
                        'walker' => new ChainThat_Walker_Nav_Menu(),
                    ));
                endif;
                ?>
            </nav> 
            <div>
                <?php
                // Get header button settings from Theme Options
                $header_section = get_field('theme_header_section', 'option');
                $button_text = ($header_section && isset($header_section['theme_header_button_text']) && $header_section['theme_header_button_text']) ? $header_section['theme_header_button_text'] : 'Book a demo';
                
                // Handle Link field (returns array with url, title, target)
                $button_url_field = ($header_section && isset($header_section['theme_header_button_url'])) ? $header_section['theme_header_button_url'] : null;
                $button_url = '#';
                $button_target = '_self';
                
                if ($button_url_field && is_array($button_url_field)) {
                    $button_url = $button_url_field['url'] ?? '#';
                    $button_target = $button_url_field['target'] ?? '_self';
                }
                
                $button_rel = ($button_target === '_blank') ? 'noopener noreferrer' : '';
                ?>
                <div class="header-btn wow fadeInRight">
                    <a href="<?php echo esc_url($button_url); ?>" 
                       target="<?php echo esc_attr($button_target); ?>" 
                       <?php if ($button_rel) echo 'rel="' . esc_attr($button_rel) . '"'; ?>
                       aria-label="<?php echo esc_attr($button_text); ?>">
                        <?php echo esc_html($button_text); ?>
                    </a>
                </div>
                <div class="menu-toggle">
                    <button class="menu-btn" aria-label="Open navigation menu" aria-expanded="false" aria-controls="sidebar-menu">
                      <img src="<?php echo get_template_directory_uri(); ?>/images/toggle.png" alt="Menu icon">
                    </button>
                </div>
            </div>  
        </div>
    <!-- </div> -->
</header>

<!-- Overlay -->
<div class="overlay" aria-hidden="true"></div>
<!-- MOBILE SIDEBAR MENU -->
<nav id="sidebar-menu" class="sidebar-menu" aria-label="Mobile navigation">
    <div class="menu-header">
        <div class="mobil-brand">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="ChainThat home"><img src="<?php echo esc_url($mobile_logo); ?>" alt="ChainThat logo"></a>
        </div>
        <div class="close-btn">
            <button class="close-icon" aria-label="Close navigation menu">
                <img src="<?php echo get_template_directory_uri(); ?>/images/close.png" alt="Close icon">
            </button>
        </div>
    </div>
    <div class="menu-wrap">
        <?php
        wp_nav_menu(array(
            'theme_location' => 'mobile',
            'menu_class' => 'mobile-menu',
            'container' => false,
            'fallback_cb' => false,
            'walker' => new ChainThat_Mobile_Walker_Nav_Menu(),
        ));
        ?>
    </div>
</nav>

<!-- Skip to main content link for keyboard users -->
<a href="#main-content" class="skip-link screen-reader-text">Skip to content</a>

<!-- Main Content Wrapper -->
<main id="main-content" class="site-main">
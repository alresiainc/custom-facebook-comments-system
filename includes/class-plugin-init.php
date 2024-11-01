<?php
class Plugin_Init
{
    public static function init()
    {
        self::includes();
        self::setup_hooks();
    }

    private static function includes()
    {
        require_once ALRESIA_CFCS_DIR . 'admin/class-admin-menu.php';
        require_once ALRESIA_CFCS_DIR . 'admin/class-admin-post-type.php';
        require_once ALRESIA_CFCS_DIR . 'admin/class-admin-comments.php';
        require_once ALRESIA_CFCS_DIR . 'admin/class-admin-settings.php';

        require_once ALRESIA_CFCS_DIR . 'includes/class-plugin-shortcodes.php';
    }

    private static function setup_hooks()
    {
        add_action('admin_enqueue_scripts', [__CLASS__, 'load_admin_assets']);
        add_action('wp_enqueue_scripts', [__CLASS__, 'load_public_assets']);
        add_action('init', [__CLASS__, 'enable_comments_for_custom_post_type']); // Enable comments for custom post type
    }

    public static function load_admin_assets()
    {
        // Enqueue jQuery
        wp_enqueue_script('jquery');

        // Enqueue ThickBox styles and scripts
        wp_enqueue_style('thickbox');
        wp_enqueue_script('thickbox');
        // Enqueue WordPress media scripts
        wp_enqueue_media();




        // Enqueue custom admin styles
        wp_enqueue_style('cfcs-plugin-admin-style', ALRESIA_CFCS_URL . 'assets/css/admin-styles.css');

        // Enqueue custom admin script
        wp_enqueue_script('cfcs-plugin-admin-script', ALRESIA_CFCS_URL . 'assets/js/admin-scripts.js', ['jquery'], null, true);
    }

    public static function load_public_assets()
    {
        // Enqueue jQuery
        wp_enqueue_script('jquery');

        // Enqueue public styles
        wp_enqueue_style('cfcs-plugin-style', ALRESIA_CFCS_URL . 'assets/css/styles.css');

        // Enqueue public script
        wp_enqueue_script('cfcs-plugin-script', ALRESIA_CFCS_URL . 'assets/js/scripts.js', ['jquery'], null, true);
    }

    public static function enable_comments_for_custom_post_type()
    {
        add_post_type_support('cfcs_facebook_post', 'comments');
    }
}

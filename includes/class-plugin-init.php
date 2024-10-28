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
    }

    public static function load_admin_assets()
    {
        wp_enqueue_script('jquery');
        wp_enqueue_style('thickbox');
        wp_enqueue_script('thickbox');
        wp_enqueue_style('cfcs-plugin-admin-style', ALRESIA_CFCS_URL . 'assets/css/admin-styles.css', __FILE__);
        wp_enqueue_script('cfcs-plugin-admin-script', ALRESIA_CFCS_URL . 'assets/js/admin-scripts.js', __FILE__, ['jquery'], null, true);

        // wp_enqueue_script('admin-post-type-modal',  plugins_url('/assets/js/admin-post-type-modal.js'), ['jquery'], null, true);
    }

    public static function load_public_assets()
    {
        wp_enqueue_script('jquery');
        wp_enqueue_style('cfcs-plugin-style', ALRESIA_CFCS_URL . 'assets/css/styles.css', __FILE__);
        wp_enqueue_script('cfcs-plugin-script', ALRESIA_CFCS_URL . 'assets/js/scripts.js', __FILE__, ['jquery'], null, true);

        // wp_enqueue_script('admin-post-type-modal',  plugins_url('/assets/js/admin-post-type-modal.js'), ['jquery'], null, true);
    }
}

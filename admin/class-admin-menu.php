<?php
class Admin_Menu
{
    public function __construct()
    {
        // add_action('admin_menu', [$this, 'add_admin_menu']);
        add_action('admin_menu', [__CLASS__, 'add_submenus']);
    }


    public static function add_submenus()
    {


        add_submenu_page(
            'edit.php?post_type=cfcs_facebook_post',
            'Manage Comments',
            'Comments',
            'manage_options',
            'edit-comments.php?post_type=cfcs_facebook_post', // This is the slug for the default comments page
            ''
        );


        add_submenu_page(
            'edit.php?post_type=cfcs_facebook_post',
            'Plugin Settings',
            'Settings',
            'manage_options',
            'cfcs_facebook_post_settings',
            [__CLASS__, 'display_settings']
        );
    }


    public static function display_comments()
    {
        echo '<h1>Manage Comments</h1>';
    }

    public static function display_settings()
    {
        echo '<h1>Plugin Settings</h1>';
    }
}

new Admin_Menu();

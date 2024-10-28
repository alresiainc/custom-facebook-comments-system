<?php
class Admin_Post_Type
{
    public static function init()
    {
        add_action('init', [__CLASS__, 'register_post_type']);
        add_filter('post_row_actions', [__CLASS__, 'add_custom_row_actions'], 10, 2);
    }

    public static function register_post_type()
    {
        register_post_type('cfcs_facebook_post', [
            'labels' => [
                'name' => 'Facebook Posts',
                'singular_name' => 'Facebook Post'
            ],
            'public' => true,
            'has_archive' => true,
            'supports' => ['title', 'editor', 'comments'],
            'menu_icon' => 'dashicons-facebook',
        ]);
    }



    public static function add_custom_row_actions($actions, $post)
    {
        if ($post->post_type == 'cfcs_facebook_post') {
            unset($actions['view']);
            unset($actions['inline hide-if-no-js']);

            // Add custom 'Add Comment' action
            $add_comment_url = admin_url("admin.php?page=cfcs_facebook_post_comments&post_id=" . $post->ID);
            $actions['add_comment'] = '<a href="' . esc_url($add_comment_url) . '">Add Comment</a>';

            // Add custom 'Copy Shortcode' action

            $shortcode_attr = json_encode([
                "id" => $post->ID
            ]);
            $actions['copy_shortcode'] = '<a href="#" class="copy-shortcode-link" data-shortcode-attr="' . esc_attr($shortcode_attr) . '">Copy Shortcode</a>';
        }
        return $actions;
    }
}

Admin_Post_Type::init();

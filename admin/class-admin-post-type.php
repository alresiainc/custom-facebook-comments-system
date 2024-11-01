<?php
class Admin_Post_Type
{
    public static function init()
    {
        add_action('init', [__CLASS__, 'register_post_type']);
        add_action('add_meta_boxes', [__CLASS__, 'add_meta_boxes']);
        // add_action('edit_comment_form_top', [__CLASS__, 'cfcs_add_comment_meta_box_on_post_edit']);
        add_action('add_meta_boxes_comment', [__CLASS__, 'cfcs_add_comment_meta_box']); // Update the syntax here
        add_action('save_post', [__CLASS__, 'save_meta_data']);
        add_action('edit_comment', [__CLASS__, 'save_comment_meta_data']); // Hook to save comment metadata
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

    public static function add_meta_boxes()
    {
        add_meta_box(
            'cfcs_facebook_post_meta', // ID of meta box
            'Post Details', // Title
            [__CLASS__, 'render_meta_box_content'], // Callback function
            'cfcs_facebook_post', // Post type
            'normal', // Context
            'default', // Priority
            'normal',
            'high'
        );
    }




    public static function cfcs_add_comment_meta_box($comment)
    {
        // Get the post object from the comment
        $post = get_post($comment->comment_post_ID);

        // Check if the post type is 'cfcs_facebook_post'
        if ($post && $post->post_type === 'cfcs_facebook_post') {
            add_meta_box(
                'cfcs_comment_meta',
                'Comment Settings',
                [__CLASS__, 'cfcs_render_comment_meta_fields'],
                'comment',
                'normal',
                'high'
            );
        }
    }



    public static function render_meta_box_content($post)
    {
        // Retrieve existing values if available
        $author_name = get_post_meta($post->ID, '_cfcs_author_name', true);
        $created_date = get_post_meta($post->ID, '_cfcs_created_date', true);
        $comment_count = get_post_meta($post->ID, '_cfcs_comment_count', true);
        $likes_count = get_post_meta($post->ID, '_cfcs_likes_count', true);
        // $author_photo = get_post_meta($post->ID, '_cfcs_author_photo', true);
        // Get the saved photo ID, if any
        $author_photo_id = get_post_meta($post->ID, 'cfcs_author_photo', true);
        $author_photo_url = $author_photo_id ? wp_get_attachment_url($author_photo_id) : '';

        // Output nonce field for security
        wp_nonce_field('cfcs_facebook_post_meta_box', 'cfcs_facebook_post_meta_box_nonce');



        // Output fields with custom layout
?>
        <div class="cfcs-meta-field w-100">
            <label for="cfcs_author_name">Author Name:</label>
            <input type="text" id="cfcs_author_name" name="cfcs_author_name" class="w-100"
                value="<?php echo esc_attr($author_name); ?>" />
        </div>

        <div class="cfcs-meta-field">
            <label for="cfcs_created_date">Created Date:</label>
            <input type="date" id="cfcs_created_date" name="cfcs_created_date" class="w-100"
                value="<?php echo esc_attr($created_date); ?>" />
        </div>

        <div class="cfcs-meta-field inline-fields">
            <div class="cfcs-meta-field w-50">
                <label for="cfcs_comment_count">Comment Count:</label>
                <input type="number" id="cfcs_comment_count" name="cfcs_comment_count" class="w-100"
                    value="<?php echo esc_attr($comment_count); ?>" min="0" />
            </div>

            <div class="cfcs-meta-field w-50">
                <label for="cfcs_likes_count">Likes Count:</label>
                <input type="number" id="cfcs_likes_count" name="cfcs_likes_count" class="w-100"
                    value="<?php echo esc_attr($likes_count); ?>" min="0" />
            </div>
        </div>

        <div class="cfcs-meta-field w-100 cfcs-author-photo-wrapper">
            <label for="cfcs_author_photo">Author Photo:</label>
            <button type="button" class="button" id="upload_author_photo_button">Select Photo</button>
            <input type="hidden" id="cfcs_author_photo" name="cfcs_author_photo"
                value="<?php echo esc_attr($author_photo_id); ?>" />
            <img id="author_photo_preview" src="<?php echo esc_url($author_photo_url); ?>" class="author-photo-preview"
                <?php echo $author_photo_url ? '' : 'style="display: none;"'; ?>>
        </div>

        <?php
    }
    public static function cfcs_render_comment_meta_fields($comment)
    {
        $author_photo_id = get_comment_meta($comment->comment_ID, 'cfcs_author_photo', true);
        $author_photo_url = $author_photo_id ? wp_get_attachment_url($author_photo_id) : '';

        // Get the post object from the comment
        $post = get_post($comment->comment_post_ID);

        // Check if the post exists and if its post type is 'custom_post_type'
        if ($post && $post->post_type === 'cfcs_facebook_post') {
        ?>
            <table class="form-table editcomment">
                <tbody>
                    <tr>
                        <th><label for="cfcs_comment_likes">Likes</label></th>
                        <td>
                            <input type="number" name="cfcs_comment_likes" id="cfcs_comment_likes"
                                value="<?php echo esc_attr(get_comment_meta($comment->comment_ID, 'cfcs_comment_likes', true)); ?>"
                                class="widefat">
                        </td>
                    </tr>
                    <tr>
                        <th><label for="cfcs_comment_author_photo">Author Photo</label></th>
                        <td>
                            <button type="button" class="button" id="upload_author_comment_photo_button">Select Photo</button>
                            <input type="hidden" id="cfcs_comment_author_photo" name="cfcs_author_photo"
                                value="<?php echo esc_attr($author_photo_id); ?>">
                            <img id="author_photo_preview" src="<?php echo esc_url($author_photo_url); ?>"
                                class="author-photo-preview" <?php echo $author_photo_url ? '' : 'style="display: none;"'; ?>>
                        </td>
                    </tr>
                </tbody>
            </table>


<?php
        }
    }


    public static function save_meta_data($post_id)
    {
        // Verify nonce
        if (!isset($_POST['cfcs_facebook_post_meta_box_nonce']) || !wp_verify_nonce($_POST['cfcs_facebook_post_meta_box_nonce'], 'cfcs_facebook_post_meta_box')) {
            return;
        }

        // Check autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Check permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Save Author Name
        if (isset($_POST['cfcs_author_name'])) {
            update_post_meta($post_id, '_cfcs_author_name', sanitize_text_field($_POST['cfcs_author_name']));
        }

        // Save Created Date
        if (isset($_POST['cfcs_created_date'])) {
            update_post_meta($post_id, '_cfcs_created_date', sanitize_text_field($_POST['cfcs_created_date']));
        }

        // Save Comment Count
        if (isset($_POST['cfcs_comment_count'])) {
            update_post_meta($post_id, '_cfcs_comment_count', intval($_POST['cfcs_comment_count']));
        }

        // Save Likes Count
        if (isset($_POST['cfcs_likes_count'])) {
            update_post_meta($post_id, '_cfcs_likes_count', intval($_POST['cfcs_likes_count']));
        }

        // Save Author photo
        if (isset($_POST['cfcs_author_photo'])) {
            update_post_meta($post_id, 'cfcs_author_photo', sanitize_text_field($_POST['cfcs_author_photo']));
        }
    }

    public static function save_comment_meta_data($comment_id)
    {

        if (isset($_POST['cfcs_comment_likes'])) {
            $likes = sanitize_text_field($_POST['cfcs_comment_likes']);
            update_comment_meta($comment_id, 'cfcs_comment_likes', $likes, true);
        }

        if (isset($_POST['cfcs_author_photo'])) {
            update_comment_meta($comment_id, 'cfcs_author_photo', sanitize_text_field($_POST['cfcs_author_photo']));
        }
    }

    public static function add_custom_row_actions($actions, $post)
    {
        if ($post->post_type == 'cfcs_facebook_post') {
            unset($actions['view']);
            unset($actions['inline hide-if-no-js']);

            // Add custom 'Add Comment' action
            // $add_comment_url = admin_url("admin.php?page=cfcs_facebook_post_comments&post_id=" . $post->ID);
            // $actions['add_comment'] = '<a href="' . esc_url($add_comment_url) . '">Add Comment</a>';

            // Link to WordPress post editing screen with Discussion metabox
            $comments_url = admin_url("edit-comments.php?p={$post->ID}");
            $actions['add_comment'] = '<a href="' . esc_url($comments_url) . '">Manage Comments</a>';

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
?>
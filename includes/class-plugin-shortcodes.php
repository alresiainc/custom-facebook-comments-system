<?php
class Plugin_Shortcodes
{
    public function __construct()
    {
        // Register the shortcode
        add_shortcode('facebook_post', [$this, 'cfcs_display_post_with_comments']);
        // Handle form submissions for new comments
        add_action('init', [$this, 'handle_comment_submission']);
    }

    public function handle_comment_submission()
    {
        // Check if form is submitted
        if (isset($_POST['cfcs_comment_submit'])) {
            $post_id = intval($_POST['post_id']);
            $comment_content = sanitize_textarea_field($_POST['comment_content']);
            $user_id = get_current_user_id();

            if ($post_id && $comment_content && $user_id) {
                // Prepare comment data
                $commentdata = [
                    'comment_post_ID' => $post_id,
                    'comment_content' => $comment_content,
                    'user_id' => $user_id,
                    'comment_approved' => 1, // Automatically approve comments
                ];

                // Insert the comment
                wp_insert_comment($commentdata);
                // Redirect to avoid resubmission
                wp_redirect(get_permalink($post_id));
                exit;
            }
        }
    }

    public function  display_child_comments($parent_comment)
    {
        $child_comments = get_comments([
            'parent' => $parent_comment->comment_ID,
            'status' => 'approve',
            'order' => 'asc',
        ]);

        if ($child_comments) {
            foreach ($child_comments as $child) {
                $author_photo_id = get_comment_meta($child->comment_ID, 'cfcs_author_photo', true);
                $author_photo_url = $author_photo_id ? wp_get_attachment_url($author_photo_id) : '';
?>
<div class="single-comment child-comment">
    <img src="<?php echo esc_url($author_photo_url); ?>" />
    <div class="single-container">
        <span class="name"><?php echo esc_html($child->comment_author); ?></span>
        <span class="comment"><?php echo esc_html($child->comment_content); ?></span>
        <div class="buttons">
            <p class="action-button">Like <?php echo $child->comment_karma ? "({$child->comment_karma})" : ''; ?></p>
            <p class="action-button">Reply</p>
            <p><span class="dashicons dashicons-thumbs-up"
                    style="font-size: 15px;"></span><?php echo esc_attr(get_comment_meta($child->comment_ID, 'cfcs_comment_likes', true) ?: '0'); ?>

            </p>
            <p><?php echo human_time_diff(strtotime($child->comment_date), current_time('timestamp')) . ' ago'; ?></p>
        </div>
    </div>

    <?php $this->display_child_comments($child); // Recursive call for deeper nesting 
                    ?>
</div>
<?php
            }
        }
    }

    public function cfcs_display_post_with_comments($atts)
    {
        // Extract attributes and set defaults
        $atts = shortcode_atts([
            'id' => '', // Expecting a post ID
            'show_post' => 'false', // Expecting a boolean value
            'show_comments' => 'true', // Expecting a boolean value
            'comments_count' => '10', // Expecting a number
            'comments_order' => 'desc', // Expecting 'asc' or 'desc'
            'comments_type' => 'all', // Expecting 'all', 'approved', 'unapproved', or 'spam'
            'comments_offset' => '0', // Expecting a number
            'comments_per_page' => '10', // Expecting a number
            'comments_page' => '1', // Expecting a number
            'allow_comments' => 'true', // Expecting a boolean value
            'comment_form_title' => 'Leave a comment', // Expecting a string
            'comment_form_placeholder' => 'Your comment here...', // Expecting a string
            'comment_form_button_text' => 'Post Comment', // Expecting a string

        ], $atts, 'facebook_post');

        if (empty($atts['id'])) {
            return '<p>No post ID provided.</p>';
        }

        // Fetch the post
        $post = get_post($atts['id']);
        if (!$post || $post->post_type !== 'cfcs_facebook_post') {
            return '<p>Post not found or invalid post type.</p>';
        }

        // Get the comments for the post
        $comments = get_comments([
            'post_id' => $post->ID,
            'status' => 'approve',
            'order' => $atts['comments_order'],
            'number' => $atts['comments_per_page'],
            'offset' => $atts['comments_offset'],
        ]);

        // Start building the HTML output
        ob_start(); // Start output buffering
        ?>
<div class="cfcs-post">
    <?php if ($atts['show_post'] == 'true'):
                $author_photo_id = get_post_meta($post->ID, 'cfcs_author_photo', true);
                $author_photo_url = $author_photo_id ? wp_get_attachment_url($author_photo_id) : '';
                $author_name = get_post_meta($post->ID, '_cfcs_author_name', true);
                $created_date = get_post_meta($post->ID, '_cfcs_created_date', true);
                $comment_count = get_post_meta($post->ID, '_cfcs_comment_count', true);
                $likes_count = get_post_meta($post->ID, '_cfcs_likes_count', true);
            ?>
    <div class="creator">
        <img src="<?php echo esc_url($author_photo_url); ?>" alt="<?php echo esc_attr($post->post_title); ?>" />
        <div>
            <p><?php echo esc_html($author_name); ?></p>
            <p><?php echo $comment_count ? $comment_count : count($comments); ?> Comments</p>
        </div>
    </div>

    <p class="message"><?php echo esc_html($post->post_content); ?></p>
    <div class="bar">
        <p class="action-button">
            <span class="dashicons dashicons-thumbs-up" style="margin-right: 5px;"></span> Like
        </p>
        <p class="action-button">
            <span class="dashicons dashicons-admin-comments" style="margin-right: 5px;"></span> Comment
        </p>
        <p class="action-button">
            <span class="dashicons dashicons-share-alt2" style="margin-right: 5px;"></span>Share
        </p>
    </div>
    <?php endif; ?>
    <?php if ($atts['allow_comments'] == 'true'): ?>
    <div class="input">
        <!-- <img src="<?php echo esc_url(ALRESIA_CFCS_URL . 'assets/images/facebook_no_profile_pic.png'); ?>"
            alt="Your Avatar" /> -->
        <form method="post">
            <textarea name="comment_content" placeholder="<?php echo esc_attr($atts['comment_form_placeholder']); ?>"
                style="height: 62px"></textarea>
            <input type="hidden" name="post_id" value="<?php echo esc_attr($post->ID); ?>" />
            <!-- <button type="submit"
                name="cfcs_comment_submit"><?php echo esc_html($atts['comment_form_button_text']); ?></button> -->
        </form>
    </div>
    <?php endif; ?>

    <?php if ($atts['show_comments'] == 'true'): ?>
    <div>

        <div class="comment-section">
            <?php foreach ($comments as $comment):
                            $author_photo_id = get_comment_meta($comment->comment_ID, 'cfcs_author_photo', true);
                            $author_photo_url = $author_photo_id ? wp_get_attachment_url($author_photo_id) : '';

                            if (!$comment->comment_parent):
                        ?>

            <div class="single-comment">
                <img src="<?php echo esc_url($author_photo_url); ?>" />
                <div class="single-container">
                    <span class="name"><?php echo esc_html($comment->comment_author); ?></span>
                    <span class="comment"><?php echo esc_html($comment->comment_content); ?></span>
                    <div class="buttons">
                        <p class="action-button">Like
                            <?php echo $comment->comment_karma ? "({$comment->comment_karma})" : ''; ?></p>
                        <p class="action-button">Reply</p>
                        <p><span class="dashicons dashicons-thumbs-up"
                                style="font-size: 15px;"></span><?php echo esc_attr(get_comment_meta($comment->comment_ID, 'cfcs_comment_likes', true) ?: '0'); ?>

                        </p>
                        <p><?php echo human_time_diff(strtotime($comment->comment_date), current_time('timestamp')) . ' ago'; ?>
                        </p>
                    </div>
                </div>

                <?php $this->display_child_comments($comment); ?>
            </div>
            <?php
                            endif;
                        endforeach; ?>
        </div>

    </div>
    <?php endif; ?>
</div>
<?php

        return ob_get_clean(); // Return the output buffer content
    }
}

new Plugin_Shortcodes();
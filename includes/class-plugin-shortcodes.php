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

    public function cfcs_display_post_with_comments($atts)
    {
        // Extract attributes and set defaults
        $atts = shortcode_atts([
            'id' => '', // Expecting a post ID
            'show_comments' => 'true', // Expecting a boolean value
            'comments_count' => '10', // Expecting a number
            'comments_order' => 'desc', // Expecting 'asc' or 'desc'
            'comments_type' => 'all', // Expecting 'all', 'approved', 'unapproved', or 'spam'
            'comments_offset' => '0', // Expecting a number
            'comments_per_page' => '10', // Expecting a number
            'comments_page' => '1', // Expecting a number
            'allow_comments' => 'false', // Expecting a boolean value
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
            <div class="creator">
                <img src="<?php echo esc_url(get_avatar_url($post->post_author)); ?>"
                    alt="<?php echo esc_attr($post->post_title); ?>" />
                <div>
                    <p><?php echo esc_html(get_the_author_meta('display_name', $post->post_author)); ?></p>
                    <p><?php echo count($comments); ?> Comments</p>
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
            <?php if ($atts['show_comments'] == 'true'): ?>
                <div>

                    <div class="comment-section">
                        <?php foreach ($comments as $comment): ?>
                            <div class="single-comment">
                                <img src="<?php echo esc_url(get_avatar_url($comment->user_id)); ?>"
                                    alt="<?php echo esc_attr($comment->comment_author); ?>" />
                                <div class="single-container">
                                    <span><?php echo esc_html(!empty($comment->comment_author) ? $comment->comment_author : 'Anonymous'); ?></span>
                                    <span><?php echo esc_html($comment->comment_content); ?></span>
                                </div>
                                <div class="buttons">
                                    <p>
                                        <?php echo esc_html($text = human_time_diff(strtotime($comment->comment_date), current_time('timestamp')) . ' ago'); ?>
                                    </p>
                                    <p class="action-button">Like
                                        <?php echo esc_html($comment->comment_karma > 0 ? '(' . $comment->comment_karma . ')' : ''); ?>
                                    </p>
                                    <p class="action-button">Respond</p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php if ($atts['allow_comments'] == 'true'): ?>
                        <div class="input">
                            <img src="<?php echo esc_url(ALRESIA_CFCS_URL . 'assets/images/facebook_no_profile_pic.png'); ?>"
                                alt="Your Avatar" />
                            <form method="post">
                                <textarea name="comment_content"
                                    placeholder="<?php echo esc_attr($atts['comment_form_placeholder']); ?>"
                                    style="height: 32px"></textarea>
                                <input type="hidden" name="post_id" value="<?php echo esc_attr($post->ID); ?>" />
                                <button type="submit"
                                    name="cfcs_comment_submit"><?php echo esc_html($atts['comment_form_button_text']); ?></button>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
<?php

        return ob_get_clean(); // Return the output buffer content
    }
}

new Plugin_Shortcodes();

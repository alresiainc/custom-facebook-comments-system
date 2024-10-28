<?php
class Admin_Comments
{
    public static function display()
    {
?>
        <div class="wrap">
            <h1>Manage Comments</h1>
            <form method="post">
                <label for="comment_content">Comment:</label>
                <textarea name="comment_content" id="comment_content" class="widefat"></textarea><br><br>
                <input type="submit" name="submit_comment" value="Add Comment" class="button button-primary">
            </form>

            <?php
            if (isset($_POST['submit_comment'])) {
                $comment_content = sanitize_text_field($_POST['comment_content']);
                // Add logic to link comment to a Facebook post here
            }
            ?>
        </div>
<?php
    }
}

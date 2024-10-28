jQuery(document).ready(function ($) {
  $(".copy-shortcode-link").on("click", function (e) {
    e.preventDefault();
    var shortcodeData = $(this).data("shortcode-attr");

    // Open ThickBox modal with form to customize shortcode attributes
    var tbContent = `
    <div id="copy-shortcode-modal" style="padding: 20px; max-width: 100%; box-sizing: border-box;">
      <p style="font-size: 16px; font-weight: bold; margin-bottom: 15px;">Customize the shortcode options below:</p>
      
      <form id="shortcode-options-form" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
        
        
  
        <div>
          <label for="show-comments" style="font-weight: 500; margin-bottom: 5px;">Show Comments:</label>
          <select id="show-comments" name="show_comments" style="padding: 3px 15px; width: 100%;">
            <option value="true">True</option>
            <option value="false">False</option>
          </select>
        </div>
  
        <div>
          <label for="comments-count" style="font-weight: 500; margin-bottom: 5px;">Comments Count:</label>
          <input type="number" id="comments-count" name="comments_count" class="regular-text" value="10" style="padding: 3px 15px; width: 100%;" />
        </div>
  
        <div>
          <label for="comments-order" style="font-weight: 500; margin-bottom: 5px;">Comments Order:</label>
          <select id="comments-order" name="comments_order" style="padding: 3px 15px; width: 100%;">
            <option value="asc">Asc</option>
            <option value="desc" selected>Desc</option>
          </select>
        </div>
  
        <div>
          <label for="allow-comments" style="font-weight: 500; margin-bottom: 5px;">Allow Comments:</label>
          <select id="allow-comments" name="allow_comments" style="padding: 3px 15px; width: 100%;">
            <option value="true">True</option>
            <option value="false" selected>False</option>
          </select>
        </div>
  
        <div style="grid-column: span 2; display: flex; flex-direction: column;">
          <label for="comment-form-title" style="font-weight: 500; margin-bottom: 5px;">Comment Form Title:</label>
          <input type="text" id="comment-form-title" name="comment_form_title" class="regular-text" value="Leave a comment" style="padding: 3px 15px; width: 100%;" />
        </div>
  
        <div style="grid-column: span 2; display: flex; flex-direction: column;">
          <label for="comment-form-placeholder" style="font-weight: 500; margin-bottom: 5px;">Comment Form Placeholder:</label>
          <input type="text" id="comment-form-placeholder" name="comment_form_placeholder" class="regular-text" value="Your comment here..." style="padding: 3px 15px; width: 100%;" />
        </div>
  
        <div style="grid-column: span 2; display: flex; flex-direction: column;">
          <label for="comment-form-button-text" style="font-weight: 500; margin-bottom: 5px;">Comment Form Button Text:</label>
          <input type="text" id="comment-form-button-text" name="comment_form_button_text" class="regular-text" value="Post Comment" style="padding: 3px 15px; width: 100%;" />
        </div>
  
        <div style="grid-column: span 2; text-align: center; margin-top: 20px;">
          <button id="generate-shortcode-button" class="button button-primary" style="padding: 3px 20px;">Generate Shortcode</button>
        </div>
        
      </form>
  
      <div style="margin-top: 20px;">
        <input class="regular-text" type="text" readonly id="shortcode-input" style="width: 100%; padding: 3px 10px;" placeholder="Generated shortcode will appear here">
        <button id="copy-shortcode-button" class="button button-outline-primary" style="margin-top: 10px; padding: 8px 20px;">Copy</button>
      </div>
    </div>
  `;

    tb_show(
      "Customize Shortcode",
      "#TB_inline?&width=500&height=600&inlineId=copy-shortcode-modal"
    );

    // Inject content
    $("#TB_ajaxContent").html(tbContent);

    function generateShortcode(withFields = true) {
      var shortcode = "[facebook_post";
      $.each(shortcodeData, function (key, value) {
        shortcode += ` ${key}="${value}"`;
      });

      if (withFields) {
        $("#shortcode-options-form")
          .find("input, select")
          .each(function () {
            var key = $(this).attr("name");
            var value = $(this).val();
            shortcode += ` ${key}="${value}"`;
          });
      }
      shortcode += "]";
      // Display generated shortcode in the readonly input
      $("#shortcode-input").val(shortcode);
    }

    // Generate shortcode based on form inputs
    $("#generate-shortcode-button").on("click", function (e) {
      e.preventDefault();
      generateShortcode();
    });

    generateShortcode(false);
    // Copy button functionality
    $("#copy-shortcode-button").on("click", function () {
      $("#shortcode-input").select();
      document.execCommand("copy");
      alert("Shortcode copied!");
    });
  });
});

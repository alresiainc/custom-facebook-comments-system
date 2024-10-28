jQuery(document).ready(function ($) {
  $(".copy-shortcode-link").on("click", function (e) {
    e.preventDefault();

    // Get the shortcode from the data attribute
    var shortcode = $(this).data("shortcode");

    // Create a modal with WordPress wp.media
    var modal = wp.media({
      title: "Copy Shortcode",
      content:
        '<p>Copy the shortcode below:</p><input type="text" readonly id="shortcode-input" style="width: 100%;" value="' +
        shortcode +
        '">',
      buttons: {
        close: {
          text: "Close",
          click: function () {
            modal.close();
          },
        },
      },
    });

    modal.on("open", function () {
      var $el = modal.content.find("#shortcode-input");
      $el.select(); // Automatically select the text for easy copying
    });

    modal.open();
  });
});

# Custom Facebook Comments System

![License](https://img.shields.io/github/license/alresiainc/custom-facebook-comments-system)
![Version](https://img.shields.io/github/v/release/alresiainc/custom-facebook-comments-system)
![Build Status](https://github.com/alresiainc/custom-facebook-comments-system/actions/workflows/main.yml/badge.svg)
![Downloads](https://img.shields.io/github/downloads/alresiainc/custom-facebook-comments-system/total)
![Contributors](https://img.shields.io/github/contributors/alresiainc/custom-facebook-comments-system)

A customizable WordPress plugin that mimics Facebook’s commenting interface, offering threaded replies, customizable display settings, and moderation options for enhanced user interaction. Designed for seamless integration with any WordPress site.

## Table of Contents

- [Features](#features)
- [Installation](#installation)
- [Usage](#usage)
- [Configuration](#configuration)
- [Shortcodes](#shortcodes)
- [License](#license)
- [Contributing](#contributing)

## Features

- **Threaded Comments**: Allows nested replies for a more engaging user experience.
- **Customization Options**: Configure display settings, comment order, and comment limits.
- **Moderation Controls**: Choose to allow or disallow comments, with easy approval options.
- **Responsive Interface**: Adapts to various screen sizes for a Facebook-like commenting experience.

## Installation

1. **Download** the plugin from the [GitHub repository](https://github.com/alresiainc/custom-facebook-comments-system).
2. **Upload** the plugin to your WordPress site:
   - Go to `Plugins > Add New`.
   - Click `Upload Plugin`, then choose the downloaded `.zip` file.
   - Click `Install Now` and then activate the plugin.
3. Alternatively, you can clone the repository into your WordPress plugins directory:
   ```bash
   git clone https://github.com/alresiainc/custom-facebook-comments-system.git wp-content/plugins/custom-facebook-comments-system
   ```
4. Go to `Settings > Custom Facebook Comments` to adjust initial settings.

## Usage

Once installed and activated, you can use the shortcode `[facebook_post]` to display the comments section anywhere on your WordPress site.

### Example Shortcode

To display a comments section with specific options, use:

```html
[facebook_post id="123" show_comments="true" comments_count="10"
comments_order="desc"]
```

## Configuration

To customize the plugin's functionality, modify the shortcode options as follows:

- **id**: Specify the post ID where comments should appear.
- **show_comments**: Set to `true` or `false` to display or hide comments.
- **comments_count**: Maximum number of comments to display.
- **comments_order**: Set to `asc` or `desc` to change the order.
- **comments_type**: Choose `all`, `approved`, `unapproved`, or `spam` for comment visibility.
- **allow_comments**: Set to `true` or `false` to enable or disable new comments.

## Shortcodes

Add any of these shortcode attributes to customize the comments display:

- `id` (integer) - Post ID to link the comments section.
- `show_comments` (boolean) - Show/hide comments.
- `comments_count` (integer) - Number of comments to display.
- `comments_order` (string) - `asc` or `desc` for comment ordering.
- `allow_comments` (boolean) - Allow/disallow new comments.

## License

This plugin is open-sourced under the [GPL-2.0+ License](https://www.gnu.org/licenses/gpl-2.0.html).

## Contributing

We welcome contributions! Here’s how you can get involved:

1. Fork the repository.
2. Create a new feature branch (`git checkout -b feature-branch`).
3. Commit your changes (`git commit -am 'Add new feature'`).
4. Push to the branch (`git push origin feature-branch`).
5. Open a Pull Request, detailing the changes and improvements.

For any issues, suggestions, or feature requests, please open an [issue](https://github.com/alresiainc/custom-facebook-comments-system/issues) on GitHub.

## Support

If you encounter any issues or need assistance, feel free to open an [issue on GitHub](https://github.com/alresiainc/custom-facebook-comments-system/issues).

---

Built and maintained by [Alresia](https://github.com/alresiainc).

<?php

/**
 * Plugin Name: Custom Facebook Comments System
 * Plugin URI: https://github.com/alresiainc/custom-facebook-comments-system
 * Description: A customizable, lightweight comment system that mimics the Facebook comments interface, complete with threaded replies, customizable display settings, and moderation options. Designed to integrate seamlessly with any WordPress site and enhance user interaction.
 * Version: v1.0.0
 * Requires at least: 5.0
 * Tested up to: 6.4
 * Requires PHP: 7.2
 * Author: Alresia
 * Author URI: https://github.com/alresiainc
 * License: GPL-2.0+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: custom-fb-comments
 * Domain Path: /languages
 * Tags: comments, social comments, threaded replies, Facebook-style comments, moderation
 *
 * Custom Facebook Comments System
 * Copyright (C) 2024 Alresia
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 *
 * @package CustomFacebookCommentsSystem
 * @version 1.0.0
 * @since 1.0.0
 * @link https://github.com/alresiainc/custom-facebook-comments-system
 */

if (!defined('ABSPATH')) {
    exit; // Prevent direct access
}


if (!defined('ALRESIA_CFCS_DIR')) {
    define('ALRESIA_CFCS_DIR', plugin_dir_path(__FILE__));
}

if (!defined('ALRESIA_CFCS_URL')) {
    define('ALRESIA_CFCS_URL', plugin_dir_url(__FILE__));
}

if (!defined('ALRESIA_CFCS_BASENAME')) {
    define('ALRESIA_CFCS_BASENAME', plugin_basename(__FILE__));
}

if (!defined('ALRESIA_CFCS_SLUG')) {
    define('ALRESIA_CFCS_SLUG', plugin_basename(__FILE__));
}
if (!defined('ALRESIA_CFCS_VERSION')) {
    define('ALRESIA_CFCS_VERSION', '1.0.0');
}

// Includes
require_once ALRESIA_CFCS_DIR . 'includes/class-plugin-init.php';
require_once ALRESIA_CFCS_DIR . 'includes/class-plugin-updater.php';

// Initialize the updater with your repository details.
$updater = new PluginUpdater(
    'https://github.com/alresiainc/custom-facebook-comments-system/', // GitHub repo URL
    __FILE__, // Full path to the main plugin file
    'custom-facebook-comments-system', // Plugin slug
    'main' // Branch to check for updates (optional, defaults to 'main')
);

// Initialize the plugin
add_action('plugins_loaded', ['Plugin_Init', 'init']);

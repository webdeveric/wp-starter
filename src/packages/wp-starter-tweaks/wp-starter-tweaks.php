<?php
/*
Plugin Name: WP Starter Tweaks
Plugin Group: Configuration
Plugin URI: http://phplug.in/
Description: Miscellaneous WordPress tweaks
Version: 0.2.0
Author: Eric King
Author URI: http://webdeveric.com/
*/

namespace webdeveric\WPStarterTweaks;

include WPMU_PLUGIN_DIR . '/wp-starter-tweaks/helpers.php';
include WPMU_PLUGIN_DIR . '/wp-starter-tweaks/theme-directory.php';
include WPMU_PLUGIN_DIR . '/wp-starter-tweaks/common.php';
include WPMU_PLUGIN_DIR . '/wp-starter-tweaks/login.php';

if (is_admin()) {
    include WPMU_PLUGIN_DIR . '/wp-starter-tweaks/admin.php';
}

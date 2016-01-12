<?php
/*
Plugin Name: WP Starter
Plugin Group: Configuration
Plugin URI: http://phplug.in/
Description: Miscellaneous WordPress configuration
Version: 0.1.0
Author: Eric King
Author URI: http://webdeveric.com/
*/

namespace WDE\WPStarter;

include WPMU_PLUGIN_DIR . '/wp-starter/helpers.php';
include WPMU_PLUGIN_DIR . '/wp-starter/theme-directory.php';
include WPMU_PLUGIN_DIR . '/wp-starter/common.php';
include WPMU_PLUGIN_DIR . '/wp-starter/login.php';

if ( is_admin() ) {
  include WPMU_PLUGIN_DIR . '/wp-starter/admin.php';
}

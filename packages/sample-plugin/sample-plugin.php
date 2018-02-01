<?php
/*
Plugin Name: Sample Plugin
Description: This plugin does nothing
Version: 1.0.0
Author: Eric King
Author URI: http://webdeveric.com/
*/

declare(strict_types=1);

namespace webdeveric\SamplePlugin;

\add_action('admin_notices', function () {
    $user = \wp_get_current_user();

    printf(
        '<div class="notice notice-success"><p>Hello, %s. The sample plugin does nothing.</p></div>',
        \esc_html($user->display_name)
    );
});

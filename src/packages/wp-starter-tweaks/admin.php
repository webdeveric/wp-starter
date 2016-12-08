<?php

namespace webdeveric\WPStarterTweaks;

function admin_footer_text()
{
    return get_bloginfo('sitename') .' Admin';
}

function update_footer($msg)
{
    global $wp_version;

    if (! str_starts_with($msg, 'Version', false)) {
        return sprintf('Version %s | %s', $wp_version, $msg);
    }

    return $msg;
}

add_action('after_setup_theme', function () {
    add_filter('admin_footer_text', __NAMESPACE__ . '\admin_footer_text', 1, 0);
});

add_filter('update_footer', __NAMESPACE__ . '\update_footer', PHP_INT_MAX, 1);

add_action('admin_notices', function() {
    if ( defined('WP_CONTENT_DIR') && ! is_readable(WP_CONTENT_DIR) ) {
        printf(
            '<div class="notice notice-error is-dismissible">
                <p>Unable to read <code>WP_CONTENT_DIR</code> <code>%s</code></p>
            </div>',
            WP_CONTENT_DIR
        );
    }
});

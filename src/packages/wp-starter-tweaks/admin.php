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
    if ( defined('WP_CONTENT_DIR') ) {
        $content_dir_perms = fileperms(WP_CONTENT_DIR) & 0777;

        if ( $content_dir_perms === 0777 ) {
            printf(
                '<div class="notice notice-error is-dismissible">
                    <p><code>%s</code>: <code>%s</code> is too permissive.</p>
                </div>',
                WP_CONTENT_DIR,
                decoct($content_dir_perms)
            );
        }
    }
});

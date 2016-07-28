<?php

namespace WDE\WPStarter;

function admin_footer_text($text = '')
{
    $footer_text = get_bloginfo('sitename') .' Admin';

    if ($text !== '' && strip_tags($text) !== strip_tags($footer_text)) {
        $footer_text .= ' | ' . $text;
    }

    return $footer_text;
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
  add_filter('admin_footer_text', __NAMESPACE__ . '\admin_footer_text', PHP_INT_MAX, 1);
});

add_filter('update_footer', __NAMESPACE__ . '\update_footer', PHP_INT_MAX, 1);

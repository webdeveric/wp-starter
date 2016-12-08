<?php

namespace webdeveric\WPStarterTweaks;

function remove_wp_logo()
{
    global $wp_admin_bar;

    if (isset($wp_admin_bar) && method_exists($wp_admin_bar, 'remove_menu')) {
        $wp_admin_bar->remove_menu('wp-logo');
    }
}

add_action('wp_before_admin_bar_render', __NAMESPACE__ . '\remove_wp_logo', 0);

add_filter('the_generator', '__return_empty_string');

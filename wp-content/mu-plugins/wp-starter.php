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

define( 'WPSTARTER_THEME_DIRECTORY', $_SERVER['DOCUMENT_ROOT'] . '/themes' );

if ( file_exists( WPSTARTER_THEME_DIRECTORY ) && is_readable( WPSTARTER_THEME_DIRECTORY ) ) {
    register_theme_directory( WPSTARTER_THEME_DIRECTORY );
}

/*
    This fixes the URI so that the theme screenshots will show up in the admin.
*/
function theme_root_uri( $theme_root_uri, $siteurl, $stylesheet_or_template )
{
    if ( $theme_root_uri === WPSTARTER_THEME_DIRECTORY ) {
        $theme_root_uri = str_replace( $_SERVER['DOCUMENT_ROOT'], get_home_url(), $theme_root_uri );
    }

    return $theme_root_uri;
}
add_filter( 'theme_root_uri', __NAMESPACE__ . '\theme_root_uri', 10, 3 );


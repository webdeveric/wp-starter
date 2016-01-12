<?php
/*
Plugin Name: Composer Autoloader
Plugin Group: PHP
Plugin URI: http://phplug.in/
Description: Load the Composer Autoloader
Version: 0.1.0
Author: Eric King
Author URI: http://webdeveric.com/
*/

$autoload_path = defined( 'COMPOSER_AUTOLOADER' ) ? COMPOSER_AUTOLOADER : ( getenv( 'COMPOSER_AUTOLOADER' ) !== false ? getenv( 'COMPOSER_AUTOLOADER' ) : false );

if ( $autoload_path === false ) {

    $autoload_file = '/vendor/autoload.php';

    $dir = ABSPATH;

    # Stop at one level above the document root
    $stop_dir = isset( $_SERVER['DOCUMENT_ROOT'] ) ? realpath( $_SERVER['DOCUMENT_ROOT'] . '/../' ) : '/';

    while ( ( $autoload_path = realpath( $dir . $autoload_file ) ) === false ) {
        $dir = realpath( $dir . '/../' );

        if ( $dir === $stop_dir ) {
            break;
        }
    }

}

if ( $autoload_path !== false && is_readable( $autoload_path ) ) {

    require_once $autoload_path;

}

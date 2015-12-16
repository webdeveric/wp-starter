<?php

/*
  Load the config from the env file.
*/

if ( isset( $_SERVER['WP_ENV'] ) && is_readable( $_SERVER['WP_ENV'] ) ) {

  $_ENV = array_merge(
    $_ENV,
    parse_ini_file(
      $_SERVER['WP_ENV'],
      false,
      INI_SCANNER_RAW
    )
  );

} else {

  error_log(
    isset( $_SERVER['WP_ENV'] ) ?
      'Could not read WP_ENV file: "' . $_SERVER['WP_ENV'] :
      'The WP_ENV environment varialbe was not found.'
  );

  header( 'HTTP/1.1 503 Service Temporarily Unavailable' );
  header( 'Status: 503 Service Temporarily Unavailable' );
  header( 'Retry-After: 300' );
  exit(1);

}

if ( isset( $_ENV['WP_SITEURL'], $_ENV['WP_HOME'] ) ) {

  define( 'WP_SITEURL',     $_ENV['WP_SITEURL'] );
  define( 'WP_HOME',        $_ENV['WP_HOME'] );
  define( 'WP_CONTENT_URL', $_ENV['WP_CONTENT_URL'] );
  define( 'WP_CONTENT_DIR', $_ENV['WP_CONTENT_DIR'] );

} else {

  define( 'WP_SITEURL',     'http://' . $_SERVER['HTTP_HOST'] . '/wp' );
  define( 'WP_HOME',        'http://' . $_SERVER['HTTP_HOST']  );
  define( 'WP_CONTENT_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/wp-content' );
  define( 'WP_CONTENT_DIR', __DIR__ . '/wp-content' );

}

define( 'DB_NAME',     $_ENV['DB_NAME'] );
define( 'DB_USER',     $_ENV['DB_USER'] );
define( 'DB_PASSWORD', $_ENV['DB_PASSWORD'] );
define( 'DB_HOST',     $_ENV['DB_HOST'] );
define( 'DB_CHARSET',  'utf8' );
define( 'DB_COLLATE',  '' );

define('AUTH_KEY',         $_ENV['AUTH_KEY'] );
define('SECURE_AUTH_KEY',  $_ENV['SECURE_AUTH_KEY'] );
define('LOGGED_IN_KEY',    $_ENV['LOGGED_IN_KEY'] );
define('NONCE_KEY',        $_ENV['NONCE_KEY'] );
define('AUTH_SALT',        $_ENV['AUTH_SALT'] );
define('SECURE_AUTH_SALT', $_ENV['SECURE_AUTH_SALT'] );
define('LOGGED_IN_SALT',   $_ENV['LOGGED_IN_SALT'] );
define('NONCE_SALT',       $_ENV['NONCE_SALT'] );

define( 'WPLANG', '' );
define( 'WP_DEBUG', isset( $_ENV['WP_DEBUG'] ) ? (bool)$_ENV['WP_DEBUG'] : false );

define( 'DISALLOW_FILE_EDIT', true );
define( 'FORCE_SSL_LOGIN', true );
define( 'FORCE_SSL_ADMIN', true );

define( 'AUTOMATIC_UPDATER_DISABLED', true );
define( 'WP_AUTO_UPDATE_CORE', false );

$table_prefix  = $_ENV['DB_TABLE_PREFIX'];

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) )
    define( 'ABSPATH', dirname(__FILE__) . '/' );

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php' );

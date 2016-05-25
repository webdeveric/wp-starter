<?php

include __DIR__ . '/src/helpers.php';

$config = \webdeveric\WPStarter\getConfig(
    \webdeveric\WPStarter\getEnvFilePath()
);

if (! $config) {
    error_log('Could not read config from env file.');

    header('HTTP/1.1 503 Service Temporarily Unavailable');
    header('Status: 503 Service Temporarily Unavailable');
    header('Retry-After: 300');

    exit(1);
}

define('WP_SITEURL',     $config('WP_SITEURL',     isset($_SERVER['HTTP_HOST']) ? 'http://' . $_SERVER['HTTP_HOST'] . '/cms' : ''));
define('WP_HOME',        $config('WP_HOME',        isset($_SERVER['HTTP_HOST']) ? 'http://' . $_SERVER['HTTP_HOST'] : ''));
define('WP_CONTENT_URL', $config('WP_CONTENT_URL', ! empty(WP_HOME) ? WP_HOME . '/wp-content' : ''));
define('WP_CONTENT_DIR', $config('WP_CONTENT_DIR', __DIR__ . '/wp-content'));

define('DB_NAME',     $config('DB_NAME'));
define('DB_USER',     $config('DB_USER'));
define('DB_PASSWORD', $config('DB_PASSWORD'));
define('DB_HOST',     $config('DB_HOST', 'localhost'));
define('DB_CHARSET',  $config('DB_CHARSET', 'utf8'));
define('DB_COLLATE',  $config('DB_COLLATE'));

define('AUTH_KEY',         $config('AUTH_KEY'));
define('SECURE_AUTH_KEY',  $config('SECURE_AUTH_KEY'));
define('LOGGED_IN_KEY',    $config('LOGGED_IN_KEY'));
define('NONCE_KEY',        $config('NONCE_KEY'));
define('AUTH_SALT',        $config('AUTH_SALT'));
define('SECURE_AUTH_SALT', $config('SECURE_AUTH_SALT'));
define('LOGGED_IN_SALT',   $config('LOGGED_IN_SALT'));
define('NONCE_SALT',       $config('NONCE_SALT'));

define('WPLANG',   $config('WPLANG'));
define('WP_DEBUG', $config('WP_DEBUG', false));

define('DISALLOW_FILE_MODS', $config('DISALLOW_FILE_MODS', true));
define('DISALLOW_FILE_EDIT', $config('DISALLOW_FILE_EDIT', true));

define('AUTOMATIC_UPDATER_DISABLED', $config('AUTOMATIC_UPDATER_DISABLED', true));
define('WP_AUTO_UPDATE_CORE',        $config('WP_AUTO_UPDATE_CORE', false));

define('WP_MEMORY_LIMIT',     $config('WP_MEMORY_LIMIT', '64M'));
define('WP_MAX_MEMORY_LIMIT', $config('WP_MAX_MEMORY_LIMIT', '256M'));

define('FORCE_SSL_ADMIN', $config('FORCE_SSL_ADMIN', false));

$table_prefix = $config('DB_TABLE_PREFIX', 'wp_');

unset($config);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if (! defined('ABSPATH')) {
    define('ABSPATH', dirname(__FILE__) . '/');
}

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

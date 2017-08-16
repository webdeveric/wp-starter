<?php

require_once __DIR__ . '/../vendor/autoload.php';

define('APP_ENV', getenv('APP_ENV') ?: 'development');

define('WP_SITEURL',     getenv('WP_SITEURL') ?: (isset($_SERVER['HTTP_HOST']) ? 'http://' . $_SERVER['HTTP_HOST'] . '/cms' : ''));
define('WP_HOME',        getenv('WP_HOME') ?: (isset($_SERVER['HTTP_HOST']) ? 'http://' . $_SERVER['HTTP_HOST'] : ''));

define('WP_CONTENT_URL', getenv('WP_CONTENT_URL') ?: (! empty(WP_HOME) ? WP_HOME . '/wp-content' : ''));
define('WP_CONTENT_DIR', getenv('WP_CONTENT_DIR') ?: __DIR__ . '/wp-content');

define('DB_NAME',     getenv('WORDPRESS_DB_NAME'));
define('DB_USER',     getenv('WORDPRESS_DB_USER'));
define('DB_PASSWORD', getenv('WORDPRESS_DB_PASSWORD'));
define('DB_HOST',     getenv('WORDPRESS_DB_HOST') ?: 'localhost');
define('DB_CHARSET',  getenv('WORDPRESS_DB_CHARSET') ?: 'utf8');
define('DB_COLLATE',  getenv('WORDPRESS_DB_COLLATE') ?: '');

define('AUTH_KEY',         getenv('WORDPRESS_AUTH_KEY'));
define('SECURE_AUTH_KEY',  getenv('WORDPRESS_SECURE_AUTH_KEY'));
define('LOGGED_IN_KEY',    getenv('WORDPRESS_LOGGED_IN_KEY'));
define('NONCE_KEY',        getenv('WORDPRESS_NONCE_KEY'));
define('AUTH_SALT',        getenv('WORDPRESS_AUTH_SALT'));
define('SECURE_AUTH_SALT', getenv('WORDPRESS_SECURE_AUTH_SALT'));
define('LOGGED_IN_SALT',   getenv('WORDPRESS_LOGGED_IN_SALT'));
define('NONCE_SALT',       getenv('WORDPRESS_NONCE_SALT'));

define('WP_DEBUG', getenv('WORDPRESS_DEBUG') === 'true');

define('DISALLOW_FILE_MODS', getenv('DISALLOW_FILE_MODS') === 'true');
define('DISALLOW_FILE_EDIT', getenv('DISALLOW_FILE_EDIT') === 'true');

define('FS_METHOD', getenv('FS_METHOD') ?: 'direct');

define('AUTOMATIC_UPDATER_DISABLED', getenv('AUTOMATIC_UPDATER_DISABLED') === 'true');
define('WP_AUTO_UPDATE_CORE',        getenv('WP_AUTO_UPDATE_CORE') === 'false' ? false : true );

define('WP_MEMORY_LIMIT',     getenv('WP_MEMORY_LIMIT') ?: '64M');
define('WP_MAX_MEMORY_LIMIT', getenv('WP_MAX_MEMORY_LIMIT') ?: '256M');

define('FORCE_SSL_ADMIN', getenv('FORCE_SSL_ADMIN') === 'true');

define('WP_ALLOW_MULTISITE',   getenv('WP_ALLOW_MULTISITE') === 'true');
define('MULTISITE',            getenv('MULTISITE') === 'true');
define('SUBDOMAIN_INSTALL',    getenv('SUBDOMAIN_INSTALL') === 'true');
define('DOMAIN_CURRENT_SITE',  getenv('DOMAIN_CURRENT_SITE') ?: $_SERVER['HTTP_HOST'] ?? '');
define('PATH_CURRENT_SITE',    getenv('PATH_CURRENT_SITE') ?: '/');
define('SITE_ID_CURRENT_SITE', getenv('SITE_ID_CURRENT_SITE') ?: 1);
define('BLOG_ID_CURRENT_SITE', getenv('BLOG_ID_CURRENT_SITE') ?: 1);

define('DISABLE_WP_CRON', getenv('DISABLE_WP_CRON') === 'true');

$table_prefix = getenv('WORDPRESS_TABLE_PREFIX') ?: 'wp_';

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if (! defined('ABSPATH')) {
    define('ABSPATH', dirname(__FILE__) . '/');
}

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

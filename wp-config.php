<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'workteam_wp330');

/** MySQL database username */
define('DB_USER', 'workteam_wp330');

/** MySQL database password */
define('DB_PASSWORD', '8O(bp]6S29');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'szk7co5f5to9ibhr5qdjbpq5u6spvypplvbkgtl11pefdp6qih9icjku0cme0tr7');
define('SECURE_AUTH_KEY',  '0xkroxdfxkmt00pajewiyei1wrnzwrk1tdx0px7u9axyayp5ti7zxj8hwii3xrqh');
define('LOGGED_IN_KEY',    'mlxnyqdweurc0wme33b7ngxeijq8gdhou5db317c4fibvdzxs1pwkrqlywrx7zwl');
define('NONCE_KEY',        'cm57frnyct5udimqxopz2o5gct9x3plckdyhl9kmootq64fcrjirvjkakvkvpcyo');
define('AUTH_SALT',        'drlz9stztg01lkzepgucciazwmoexz8xsxyjcotmpee5btjurvreolm1j3mghobv');
define('SECURE_AUTH_SALT', 'gwuu8tu3p3hz0wm3x1edi6ke99rqe1aqcazmb5imtcdwr6onxfptjbneo2xtvk4x');
define('LOGGED_IN_SALT',   'iz5t4hj6sh4yy76uypblfuf1qulohqqbaxa7qoe3hq81tcgnvohffek4tongv2sq');
define('NONCE_SALT',       'wxzw12j5rne4mar7vmc76usgcd38tcki8yl9wl3yjddozqrxefwwdlelvv22ky6e');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp2w_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

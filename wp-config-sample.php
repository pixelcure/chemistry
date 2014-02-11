<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'paultib1_cure');

/** MySQL database username */
define('DB_USER', 'paultib1_pixel');

/** MySQL database password */
define('DB_PASSWORD', 'cure2014pixels');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

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
define('AUTH_KEY',         'Qri+6-ZI%EJ|%5~-wt86;NeH-YLXxh:<smm?XYB[?:f3`ZK5u$7qc&g~F7fP2a}p');
define('SECURE_AUTH_KEY',  'GDV,S }cT?k>d>+qWn{1rgUwH3]5v,tUf%sa$y9RB[Gl=nkyLo)EE6&s|76Mm#o`');
define('LOGGED_IN_KEY',    'z:pijg/e-@{+Gb7RKzZ rAm|n-F$|yMB`1[etq#(u9h@D^{OcgDNhYkP0>-QhtBp');
define('NONCE_KEY',        '+ nTIoW15*a~ -m8$7^nq{w`yF2o^8{.+j<L@**@`XswLg5?7!m?tI?f2hG3Q1Y5');
define('AUTH_SALT',        '>ttYwARy^>U<4YA+r;nI0+r#Cl~D<`@~q.$4|i/52nzjSTJW7v-0/;;JS^25d>I8');
define('SECURE_AUTH_SALT', 'I-QwLPH,4vJ?|f8M$6Qh%9Uq i6C$u|+=QsuzT%X[auGDAX8kSh<vs8lsiVE2}V%');
define('LOGGED_IN_SALT',   'U8ff%jjq}q}^6jJ}fgP9|s%fKYDS$SAnxIOE`x;h|l&/WjO#SY_t3Xq~OA#7+[{=');
define('NONCE_SALT',       '><@eGyiaq=muKF|3GTvOwAx[cxeD[mlB:4b($+5y[%?-Bjnj]IcST*M+0ZpK~arW');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

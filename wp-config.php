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
define('DB_NAME', 'cure');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

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
define('AUTH_KEY',         'I%s!g+kz_-$QJtYIe#cil)q(=uze}`:IAfrF,*F9-/>o[|U1kFEsJQ/5NJND}T>Y');
define('SECURE_AUTH_KEY',  'LY:SH>ad|ti*|YfDl~O8aeV^+:{[3)B6gi/>PT}XRdyTx%URMv{)N!oBS5PwJ;<c');
define('LOGGED_IN_KEY',    ':+d|H:bj{z3%MZje=[T/1-1p-tj+RQte0;+?&wUh~3a*X^<Jj(KDY3p! dXt@;uB');
define('NONCE_KEY',        'bvbwi3ip`IJNh[YQYc{0IU!UY]!RyIYytF2(5,PKOZOT0ktC|5g-z:3H[^Ip^Tr|');
define('AUTH_SALT',        'Hn+Oze3]H7KC-TLs1BF}/)/f`~32*cD }+r 7Ox{![O,3*P&5<4Kb/s,%&P~-Ke}');
define('SECURE_AUTH_SALT', 'y^k3t4xkC0$jc8!nqa$u~FCQ!R+U8m]FCCA$*mj.96E=P]|OENVzl+B{8FCcz->8');
define('LOGGED_IN_SALT',   'sHR#l}`paz AH9@oYgL=^+Tt/a>qmDd8_/C1WlNQ0~HKO,,rq%AsMN+N`)OetEV{');
define('NONCE_SALT',       '`ont#~~})P,tFT|]Pe:i#{hN3cqvj^mx8n@q~s9a8f7L||1lzO[J$}B686gyd:|-');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'pc_';

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


$devMode = true;

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

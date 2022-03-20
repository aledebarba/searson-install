<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wp' );

/** Database username */
define( 'DB_USER', 'wp' );

/** Database password */
define( 'DB_PASSWORD', 'secret' );

/** Database hostname */
define( 'DB_HOST', 'mysql' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'bj6=1FnMMk)wuAyNUP^S`{18iZdM9SEGgZ=P#q3|g<C0sv2ozS0Q5,g33YT4O{7m' );
define( 'SECURE_AUTH_KEY',  '.Dad#>TaJ+0qqLMX>,dTMKgFg7%YhfylNBr97+Ax,sX&SD]p^n_h.CUzb~@K2~M:' );
define( 'LOGGED_IN_KEY',    '5DUXc>,{nFPG^S[pkp.D5j1N,49%0bKLeMdQHhr-=oOp?,7mx$(&uN5p-0$R7 LC' );
define( 'NONCE_KEY',        'X52x(2KwjV!srDr.<312JqJ|[i~6t)h$6}R3}fk|K/{*2;XWl%W2bp>:EXh!?QTP' );
define( 'AUTH_SALT',        'Ly0na(@+<E|^,Yi~E{I1^ypR;JU%lg.FCH5zc(3-4fHJ!NH$up_peP-W~#&MK^`6' );
define( 'SECURE_AUTH_SALT', 'eq]E^M(8O,^+@!OB$9ZsfA<Q0$x0G=b@|Y6 (T54&.]N^powm]cYt<pS<y2*ev`S' );
define( 'LOGGED_IN_SALT',   'H|I=8I(j`Y} `auInv34~vIv?nxxiZ(0AI8bO6.%;!b403[hz6b09+Vf?p}h0Xyb' );
define( 'NONCE_SALT',       '1aG9} =an$3gcixDeq~UQdX-?8RSfq)-BhN!;g%sK Rab.]EE/g?bLn`mAGET7+v' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

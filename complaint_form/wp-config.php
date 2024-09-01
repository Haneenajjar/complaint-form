<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'test' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'Minerets@2007' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

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
define( 'AUTH_KEY',         'N4GY0Cv,aq6S}l M=Ejsqa>+:sXeL,72s7kc&*RYH_@58[6Rh`qli3l3y^r#Ks:=' );
define( 'SECURE_AUTH_KEY',  '3It*kPl|`BP$*U/$.zbXTvl mCw0)a0XHrtu*D.x,hN$4Z`n~P6){<F&ELst><&!' );
define( 'LOGGED_IN_KEY',    'I}+sFG>,||$G14{dw{V,c&4:u%Yp^S~7y~?z>?.BW[riJ+rzXt$F5F~yYZc%QnKV' );
define( 'NONCE_KEY',        'Dl,)&tKkg%=H&}+wnXrvf-F$Zr;q)e7Pt$?%]YUp8pN@JJl9+/T>Cbl|2q*26LT&' );
define( 'AUTH_SALT',        ']_`#:b@`KJSsx:S{m+1fKFi4{Sofcw;)823Nz4JQm%T,s7-F;9Q6F:jHT&`[~aOW' );
define( 'SECURE_AUTH_SALT', 'lbL>~98]=k#ymu,qquaLEw{nJVyfaERCu#]{nhKk!;gG?Z/lTz7{h3e+$Kj?@U x' );
define( 'LOGGED_IN_SALT',   'Dg$QyBv73iXv3E ut1bXj03a(Cgh$=#wI`DX+/;?&1r&bnn,x;`_*&0,/t2of!aW' );
define( 'NONCE_SALT',       '=!zVvRG^ *iuzq9E#tXtbUiGa1eB;?.dm&|##-o?%+V&8+Oca:RP(0d},b&%m{_r' );

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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
define( 'FS_METHOD', 'direct' );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

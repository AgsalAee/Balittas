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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'balittas' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'J/z,qCIq7Uw^|2I(u3HR+2G`dS.EvVBXvy&:l=|_?XNVRwvW15H8=7RGjzx.^7L^' );
define( 'SECURE_AUTH_KEY',  '((dFq/*V|>$AbI(i]<jG093^ex@ZNXQ=u]VF>xO,GX1e4yM4-DQ:v5lFw.I4-Z4X' );
define( 'LOGGED_IN_KEY',    'epMVZ!N|fA-IzZc:jm&EcqaL6H?tTp{9,JMk1 4:v~2^D|W_0(hbg)6g)_EnDk>@' );
define( 'NONCE_KEY',        'gg:(^gH9$:}zSg2ENBhJ]$pd}0#G-T_SJ#B$zsQ3@f,_5CtG-z*eH0_C&@WK3WNz' );
define( 'AUTH_SALT',        'e?^-C3 &lUQ~vc$j/^GAG](~mV`RSXeH _IJ:[ Z%lGHQq2^>-SWngjnP.*$gL&h' );
define( 'SECURE_AUTH_SALT', '|~VE7cjdaLj}U]s;P?dUh-[q*Dd#j.et}Ws}=Ub=H!A[;:A0QagISvFX};5Qmf|C' );
define( 'LOGGED_IN_SALT',   'C@%@7)z9[=C`3lO][bPGm]L/u];+7mVVT7I8dU<(mDa2w<sAx_z2`2)Ph8]K *2v' );
define( 'NONCE_SALT',       'yF6t:84iT_t= _PNBbu9N>x#N59:+@Yt$.^i3A(Ru)~tdwhh3J/vr4oC=iW{,VM4' );

/**#@-*/

/**
 * WordPress Database Table prefix.
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

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

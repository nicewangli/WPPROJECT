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
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'local' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

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
define( 'AUTH_KEY',          'My#y4>5Z<|@|+<IM}Dg0bYRm&hx/qy6Xe[d:K:*Wk_|zf_|3:Z#o<Wlx2%m_*(=f' );
define( 'SECURE_AUTH_KEY',   'HAuXriH)VU9zt U#-F<f<$N%pAV5%AI@P~kH:mF:v+e*9g<Jx|G`vLV:@ M.<dJQ' );
define( 'LOGGED_IN_KEY',     '`h9GGpp]V+!O;MQgKhZg?eO}8zZ m[Z{_hgJmgyd?hYcQv{Q}_N?JH}<T+)oV=Q%' );
define( 'NONCE_KEY',         'l~$?;8O3-QB(O5*3[r| n2x7N9vp$|;BOQL/IFR+ ^8Qn&,mOR`{}dTb1=91A>Kg' );
define( 'AUTH_SALT',         'Dg;(Y 2g:]ao>lc?x=V#/o|k ?f[zAC q^s*a03{jy^%[&xAb7X_)=1.GKwok$_S' );
define( 'SECURE_AUTH_SALT',  '@)`CK/?92^q=aVRSo_cd0mv5<6IE4R9LuweGfC)SybAOqz1Evk w#uq:)hM*Ew2O' );
define( 'LOGGED_IN_SALT',    'echDI]cjD:6r:Stgz%/y=!EK$(:b^m#19)oD#@R.9Tsi+vb^RC(e/JGM7~_e2Vwv' );
define( 'NONCE_SALT',        'xNEE^LYzUh2eV8]_7zzm4C9Y!TG~SAj%=5+<h/PALG6QR ?]& #EZ4K*4E*fx3#W' );
define( 'WP_CACHE_KEY_SALT', 'FK6la}h_=SU+l[91^4#o4j&qNDGh^{-j$i*>mi-[]ND12oxC1{LqHgI7g9YsI.AI' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



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
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

define( 'WP_ENVIRONMENT_TYPE', 'local' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

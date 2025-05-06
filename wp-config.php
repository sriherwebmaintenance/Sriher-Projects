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
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'u379467699_main' );

/** Database username */
define( 'DB_USER', 'u379467699_main' );

/** Database password */
define( 'DB_PASSWORD', 'YixjWXQz#Lv3' );

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
define( 'AUTH_KEY',         'kabNEUmq=>5FJTuwK;1Ie1EO#Ta(zQ[;`-{^;J>/J,60kIJ[T-Sero`wwSw)5ula' );
define( 'SECURE_AUTH_KEY',  'iVa/vXEfaD=03~rnCOI3ALm>Q.9.@9DM9#Q=},#,)y4eQ]C|6CTh{{K:!-]u:EWi' );
define( 'LOGGED_IN_KEY',    '?6Y7)@9H5Z:eHG1+9+s=IrgD)Mbe55w.^iat[xGS|CHMA;`{TC@2A~RWWk9SZ7lc' );
define( 'NONCE_KEY',        '}8PquF:ufa[Eo)@1)@`Me1h^b>Wmet+cxB?>7?>%WlxYlI6#z5@~<;:7L[w;h.g#' );
define( 'AUTH_SALT',        '?~TW4G<NKca%+Ot)&=ASM[s:wD+`E)F`<W/VRya%c9<:6V??|@oBsBA!wD>8IR (' );
define( 'SECURE_AUTH_SALT', 'si}ko?-R^~RPM( HJIFSi/#hN  %&4Y,~)n-}^[!.{Hf&BRh|J>,GM!.11E2Ma6M' );
define( 'LOGGED_IN_SALT',   'gEX UG7nt>W;o28GDpK{Ko>qblQ.IRUPSH$|uf8-6r;E3PIGyX}Z({?y6~IhR?uH' );
define( 'NONCE_SALT',       'zrq?OPu+(5D%+c*GmGaC!Adr |(mbk)PW+wC@U+WA^hVxb9yL+bN%Ncs{SfubT1H' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'srh_';

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
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', true );

define( 'WP_POST_REVISIONS', 3 );

define( 'AUTOSAVE_INTERVAL', 600 );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

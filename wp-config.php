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
define( 'DB_NAME', 'homepizza' );

/** MySQL database username */
define( 'DB_USER', 'newuser' );

/** MySQL database password */
define( 'DB_PASSWORD', 'newuser123' );

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
define( 'AUTH_KEY',         'H~3Bu:b[<YXLt~Z0XE6(*J3/1Uk{{frYA)#m[udcVHUWlVr@X0$GPFLZP^@88Il}' );
define( 'SECURE_AUTH_KEY',  '}sT!RrvFM2QmeJ4.pwzwFs$wc!@_B~kE5Dc!M;y.eEi,DR$z~s6Fn$l?>xZkhZzs' );
define( 'LOGGED_IN_KEY',    '*QCE2U,@q=Wu^fMq@H<XNI2#&Qc@bR<;daElG[86zz}7qy#!DEgG}?kAxr.72&&G' );
define( 'NONCE_KEY',        'ltf3YD2L41GgK$b5!Xvt]P<j~DN;BBA#j~fmEVmUz#%N!nr,|3C>=i/7}W+g}T(B' );
define( 'AUTH_SALT',        '$td@<oi-P_`sP&7Z|hesz?#`7.-KXp0<O1Y93 ^R.ov1449kNYd4C[n||52,RtUg' );
define( 'SECURE_AUTH_SALT', '=AN_Z0J:Kns-K#wo~bJ>NIObO3#$B/v|]ODPA6+iA!){i+{HZpqBXsJ0y]U86pwf' );
define( 'LOGGED_IN_SALT',   'e>q##x1DO72x?jH:4cS)TS|FS|J{@o==|vQLLdQMQM6.)X0q$N)Au2]CZ^[8Xs~c' );
define( 'NONCE_SALT',       'n/{gFQsX^S2aS=e6cjXRQuRs&e4AUBTBy k>&Ow=esz3zrMB%qPp*K#zO/vC$TG%' );

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
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );


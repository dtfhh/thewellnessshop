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
define( 'DB_NAME', 'thewelldtc' );

/** MySQL database username */
define( 'DB_USER', 'phpadmin' );

/** MySQL database password */
define( 'DB_PASSWORD', 'Fine@12343' );

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
define( 'AUTH_KEY',         '7o+NQufB~J3<G`g3X+E!N/*^9drc/o?HeYu0/=b,AQurNV,Qs_jt=tI 4ea7Whzs' );
define( 'SECURE_AUTH_KEY',  'f<i*oLJ#J*Lxm%MgvMkHg8zmFVOy-_0=4 42:N)5>tY9=KQ}Gcw~DLar`*s-:4D%' );
define( 'LOGGED_IN_KEY',    '*hV_B8#v~{wB=tcM)r_a1kclY^H)d?Pd4`xPQ]nR*a2^-(ls[.hYEJuo%<Qi{xN>' );
define( 'NONCE_KEY',        'Aj$,gH{aLWn`-PlJDj]w6yBn @%i=+_|Oi,r~O34augi*(,NzMZ^b?,q.l$0i>|B' );
define( 'AUTH_SALT',        '%n9.4T>ISOMt;dJ4R(B/[krxHns57W8(3(R/~HMUxuNGEqus{:&i=n(-YE3QM:(I' );
define( 'SECURE_AUTH_SALT', 'MWdFb,8kFj6q&^SCBv4_Hgb,;B- es=R$)+Z>poVmxphtQ|ruNfi[M?{gL499E~B' );
define( 'LOGGED_IN_SALT',   '@C@qJ0ghXYBC%HVT`cTDaHp>a(RoS:1Q,onLV*gh*n0]QAT63GJ2e`JW9N;D1O:2' );
define( 'NONCE_SALT',       '5sfs3(a(;i{Yu>q=~Uz7.%JT^!eK=$0lI/p@y}?H88E2GJ,Uooe|[W9+RNlinvr`' );

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

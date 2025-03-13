<?php

/** Enable W3 Total Cache */
/** Enable W3 Total Cache */
/** Enable W3 Total Cache */
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
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */
// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'bitnami_wordpress' );
/** Database username */
define( 'DB_USER', 'bn_wordpress' );
/** Database password */
define( 'DB_PASSWORD', '2ded075ce21607abbba358e47a5d23f8e9ee4fe95967cd8f2f00ed9eae205fa0' );
/** Database hostname */
define( 'DB_HOST', '127.0.0.1:3306' );
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
define( 'AUTH_KEY',         '~Z{{SoWjTm[/4o7-Bfzn8i64L_UjX6omh^PYrb<(i7bu_1m/AiWC;r2zN9/oT=!)' );
define( 'SECURE_AUTH_KEY',  'sHD]XCza?@Q0EK=PAbF6NhSEo[wHNg[WaSXazC~`A12d@^VI^]tU6L,A{&x!.cPC' );
define( 'LOGGED_IN_KEY',    '78 rEI8Lt0x,fRR+?1T%w%*`:nU0`}xC6b9^AjLiulz,a9*%CMy;%GAWWyLJxzo_' );
define( 'NONCE_KEY',        'jr),-O{Bj<aAa={4G/^9$u[Mm3[YD~8orPcVEl47^R$%p5KlaVJ:9;QL, xX&0(.' );
define( 'AUTH_SALT',        'zFv2u9+E?8tK#H28bFAg(sz%V^Y!35c1;?E;t@a:8Ryvi:w$1s]2p6<W)hVgr^Xc' );
define( 'SECURE_AUTH_SALT', 'Hx?+Pk0(@4LTpNv`Zfy|F,LO<AsoI]`V1Y4gbyK{p-tLJ)=H;;L*8+qw&qjwHq/U' );
define( 'LOGGED_IN_SALT',   '25!epG*CaMT5s}hSRhkF }F*y#G= <.}Gguug~5>M_*jtI^a7kRlfHyBRr;V.0no' );
define( 'NONCE_SALT',       'OKb]wjBw1tJOI&P%w<Q~T+gK iP*{wWIg07|3%JiIP@ZNXFE>{]^9pawFuWV;mlG' );
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
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );
/* Add any custom values between this line and the "stop editing" line. */
define( 'FS_METHOD', 'direct' );
define( 'WP_AUTO_UPDATE_CORE', 'minor' );
define( 'WP_ALLOW_MULTISITE', true );
define( 'MULTISITE', true );
define( 'SUBDOMAIN_INSTALL', true );
$base = '/'; 
//define( 'DOMAIN_CURRENT_SITE', '52.74.144.9.nip.io' );
define( 'DOMAIN_CURRENT_SITE', 'www.mrcmekong.org' ); //www.mrcmekong.org
define( 'PATH_CURRENT_SITE', '/' );
define( 'SITE_ID_CURRENT_SITE', 1 );
define( 'BLOG_ID_CURRENT_SITE', 1 );
define('WP_MEMORY_LIMIT', '1024M');
/* That's all, stop editing! Happy publishing. */
/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}
/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
/**
 * Disable pingback.ping xmlrpc method to prevent WordPress from participating in DDoS attacks
 * More info at: https://docs.bitnami.com/general/apps/wordpress/troubleshooting/xmlrpc-and-pingback/
 */
if ( !defined( 'WP_CLI' ) ) {
	// remove x-pingback HTTP header
	add_filter("wp_headers", function($headers) {
		unset($headers["X-Pingback"]);
		return $headers;
	});
	// disable pingbacks
	add_filter( "xmlrpc_methods", function( $methods ) {
		unset( $methods["pingback.ping"] );
		return $methods;
	});
}

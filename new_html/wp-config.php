<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link http://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
// The following setting have been moved to another file out side source control
//  DB_NAME
//  DB_USER
//  DB_PASSWORD
//  DB_HOST

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
define('AUTH_KEY',         '1K_+P3?2-3)e^-: !U0I?W@1a{j6k:lDV>]e6Ko9O_#JB-bnuzRLD`y*,|*OcC1O');
define('SECURE_AUTH_KEY',  'l=Ls%:]@}DrcR||g8ne-Y=jgC5Jo7oPfz.YU5}EEeWE{+8[A%L6v#Q}klDvRH..o');
define('LOGGED_IN_KEY',    'W;A%z{3PPNBPtg-o)>TRxv};|{GsiPo-T[aP=s0e{/hfuO&;N%,W_08U8-y[MvjC');
define('NONCE_KEY',        ',FO*PE)jq-v9_|N;BARqa*%5Vq-7rA?Ou)VP,Qm&<^&z0j@<t4u!1~RVXb)Vf=*^');
define('AUTH_SALT',        'U`zeMSuCn`_h2y?;D@;6Kb7b8J$>- PMc;> iOktH-2h`-r,#Y| R[;d[Q|I.-v9');
define('SECURE_AUTH_SALT', 'Ij4M(YB#m.6{!F}_lQ>KWrm~2PGyVS| .F&tC|%$+K#4g;Nr88gp,,sZKo+1&8Aw');
define('LOGGED_IN_SALT',   '0i7Ls%PhY@Ega99N_A13R;5Hrk4.2U4Q)K`|)2yS1O(~Iv@u_(@cN/lMD)cFFA$s');
define('NONCE_SALT',       '!;aOI;#l9af8:B+/AtyjhDGTS&bc&vJ(lWZhq<ImM=^` NY7MT2|7TmEzYw:|Ru;');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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

// Config file outside source control
require_once(ABSPATH . '../wp-config.php');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

?>

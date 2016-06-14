<?php

// ===================================================
// Load database info and local development parameters
// ===================================================
if ( file_exists( dirname( __FILE__ ) . '/wp-local-config.php' ) ) {
    define( 'WP_LOCAL_DEV', true );
    include( dirname( __FILE__ ) . '/wp-local-config.php' );
} else {
    // ** MySQL settings - You can get this info from your web host ** //
    /** The name of the database for WordPress */
    define('DB_NAME', 'starnetwork');

    /** MySQL database username */
    define('DB_USER', 'root');

    /** MySQL database password */
    define('DB_PASSWORD', 'root');

    /** MySQL hostname */
    define('DB_HOST', 'localhost');

    // =================================================================
    // Debug mode
    // Debugging? Enable these. Can also enable them in local-config.php
    // =================================================================
    // define( 'SAVEQUERIES', true );
    define( 'WP_DEBUG', false );

    /**
     * Defined WP_HOME address for saving time when deploy into new server
     */
    define('WP_HOME','http://starnetwork.loc/');

    /**
     * Defined WP_SITEURL address for saving time when deploy into new server
     */
    define('WP_SITEURL','http://starnetwork.loc/wp');
}

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

// =================================================
// Custom Content Directory (change if renamed)
// =================================================
define( 'WP_CONTENT_DIR', dirname( __FILE__ ) . '/wp-content' );
define( 'WP_CONTENT_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/wp-content' );

// ==============================================================
// Salts, for security
// Grab these from: https://api.wordpress.org/secret-key/1.1/salt
// ==============================================================
define('AUTH_KEY',         's ^]d+/bJ-kT>Ng&#4k`PF$*,e~<OG8BG-^p#9mlS8Y|z.cMFg$@d9 R#hx|JDrF');
define('SECURE_AUTH_KEY',  'g*,$ya5t7|d>Y<29zYw|P`2TT:1{vqO:.`Qk7wTFcm|Sl/Y%m,bTGAVG>5.P_Y_!');
define('LOGGED_IN_KEY',    'n:KqjI=u`wOL|x=<UXP0V6_t#!5eo:$xYt%aT1h{~c-pb-*MlnKu>fdacHBc]T*S');
define('NONCE_KEY',        'g!FsB;(Y:-6ibxU#s/1-&U)1UA]Pt_`AmG&*]?62/C6(%dpY,n/W~nUnX+=f2lpJ');
define('AUTH_SALT',        'BXJJ/7C*HOZ.~.a|9EDfP)5Q+ACUu^A7=8b!HT7p +67V6*Dih{C.*fKi:^13(KW');
define('SECURE_AUTH_SALT', '*t_T]u& Xd_Vp-YW`pA~jH]T(C;z?osqi};-=R6N/qm@>R%hh3~Eo^8Xnx=q<N[F');
define('LOGGED_IN_SALT',   '@{-G%usIO )`Zb*m`-@6Kir%cU]+9-Eb1P:)W7P$a>i#P-8>eXk+m;[iAMV}iC7w');
define('NONCE_SALT',       'yV1m|&YG3=a>vCK?N+4^{Gf+*)=tG|~dO8i&yDJXNv7&<JF]3sChHUx@Er_a/-vq');

// ==============================================================
// Table prefix
// Change this if you have multiple installs in the same database
// ==============================================================
$table_prefix  = 'wp_';

// ================================
// Language
// Leave blank for American English
// ================================
define('WPLANG', '');

// ===========
// Hide errors
// ===========
ini_set( 'display_errors', 1 );
define( 'WP_DEBUG_DISPLAY', true );

// ======================================
// Load a Memcached config if we have one
// ======================================
if ( file_exists( dirname( __FILE__ ) . '/memcached.php' ) )
    $memcached_servers = include( dirname( __FILE__ ) . '/memcached.php' );

// ===========================================================================================
// This can be used to programatically set the stage when deploying (e.g. production, staging)
// ===========================================================================================
//define( 'WP_STAGE', '%%WP_STAGE%%' );
//define( 'STAGING_DOMAIN', '%%WP_STAGING_DOMAIN%%' ); // Does magic in WP Stack to handle staging domain rewriting

// ===================
// Bootstrap WordPress
// ===================
if ( !defined( 'ABSPATH' ) )
    define( 'ABSPATH', dirname( __FILE__ ) . '/wp/' );
/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');


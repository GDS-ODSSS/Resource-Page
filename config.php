<?php 
if (!defined("IN_PHPMEGATEMP"))
      die('Direct access to this location is not allowed.');

/**
 * Report all errors, except notices and deprecation messages
 * Not recomended for live site.
 */
$level = E_ALL & ~E_NOTICE & ~E_DEPRECATED;
error_reporting($level);

/** 
 * MySQL settings - You can get this info from your web host
 */
@define('DB_HOST',          'localhost:3307');
@define('DB_NAME',          'helpManager');
@define('DB_USER',          'root');
@define('DB_PASSWORD',      'Nawa@123');

/**
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
@define('DB_PREFIX',        '');

/* That's all, stop editing! */
@define('DB_PORT',          '');
@define('DB_SQLMS',         'mysqli');

/**
 * Absolute path to the UNHCR IM Resource Pagedirectory.
 */
if ( !defined('ABSPATH') )
      define('ABSPATH', dirname(__FILE__) . '/');
?>
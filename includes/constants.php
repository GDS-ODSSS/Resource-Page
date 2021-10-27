<?php
//----------------------------------------------------------------------|
/***********************************************************************|
 * Project:     UNHCR IM Resource Page                                       |
//----------------------------------------------------------------------|
 * @link http://nawaaugustine.com                                         |
 * @copyright 2021.                                                     |
 * @author Augustine Nawa <ocjpnawa@gmail.com>                   |
 * @package UNHCR IM Resource Page                                           |
 * @version 4.7                                                         |
//----------------------------------------------------------------------|
************************************************************************/
//----------------------------------------------------------------------|
if (!defined("IN_PHPMEGATEMP"))
      die('Direct access to this location is not allowed.');

// Script Version
@define('SCRIPT_VERSION', '4.5');
// chmod permissions
@define('CHMOD_ALL', 7);
@define('CHMOD_READ', 4);
@define('CHMOD_WRITE', 2);
@define('CHMOD_EXECUTE', 1);
// System
@define('THEME_PATH',           'themes/');
@define('TEMP_FOLDER_PATH',     'default');
@define('CACHE_PATH',           'cache/');
@define('GZIP_COMPRESS',        true);
@define('IN_ERROR_HANDLER',     true);
@define('ACTIVATION_KEY',       24); // 6 to 31
@define('REGISTRATION_STATUS',  1); // 0 or 1
// folder admin
@define('ADMIN_DASHBOARD',    'admin');
@define('PERMANENT_LINK_TYPE',  'php'); // html or php or name
@define('DEBUG_EXTRA',          true);
@define('TPL_ALLOW_PHP',        false);  
@define('LOAD_TPL_COMPILE',     true);  
@define('DISPLAY_SHOWDEBUG',    false);
@define('SHOWDEBUG_QUERIES',    true);
@define('SHOWDEBUG_MEMORE',     true);
@define('SHOWDEBUG_GZIP',       true);
@define('SHOWDEBUG_LOAD_TIME',  true);
// Table names
@define('CONFIG_TABLE',         DB_PREFIX . 'config');
@define('POSTS_TABLE',          DB_PREFIX . 'posts');
@define('POSTSMETA_TABLE',      DB_PREFIX . 'postsmeta');
@define('TERMS_TABLE',          DB_PREFIX . 'terms');
@define('TERMSMETA_TABLE',      DB_PREFIX . 'termsmeta');
@define('USERS_TABLE',          DB_PREFIX . 'users');
@define('USERSMETA_TABLE',      DB_PREFIX . 'usersmeta');
@define('LANGUAGE_TABLE',       DB_PREFIX . 'language');
@define('PHRASE_TABLE',         DB_PREFIX . 'phrase');
@define('COUNTER_TABLE',        DB_PREFIX . 'counter');
@define('EMAILTEMPLATES_TABLE', DB_PREFIX . 'emailtemplates');
// Additional tables
define( 'KB_IN_BYTES', 1024 );
define( 'MB_IN_BYTES', 1024 * KB_IN_BYTES );
define( 'GB_IN_BYTES', 1024 * MB_IN_BYTES );
define( 'TB_IN_BYTES', 1024 * GB_IN_BYTES );
?>
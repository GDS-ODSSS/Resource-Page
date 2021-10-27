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
ob_start();
if(!isset($_SESSION)) 
{ 
    session_start(); 
} 
define('IN_PHPMEGATEMP', true);
define('IN_PHPMEGATEMP_CP', true);
$starttime = microtime(true);


if (!file_exists('../config.php')) {
  header("Location: ../install/install.php");
  exit;
}

include('../config.php');
include(ABSPATH . 'includes/constants.php');
include(ABSPATH . 'includes/startup.php');
@define('ADMIN_ABSPATH',  dirname(__FILE__) . '/');
@define('ACPINC_ABSPATH', dirname(__FILE__) . '/includes/');
$users  = new users();
include(ACPINC_ABSPATH . 'admin-functions.php');
if(is_admin())
{
    include(ACPINC_ABSPATH . 'admin-post-page.php');
    include(ACPINC_ABSPATH . 'admin-menus.php');
    include(ACPINC_ABSPATH . 'admin-display.php');
    include(ACPINC_ABSPATH . 'admin-files.php'); 
    include(ACPINC_ABSPATH . 'admin-html.php'); 
    include(ACPINC_ABSPATH . 'admin-megapanel-options.php');
}
?>
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
require_once('./admin-common.php');
if(!defined('IN_PHPMEGATEMP_CP')) exit();

if(!is_admin())
{
    @header("location:admin-login.php");
}
is_user_permissions('appearance');
include(ACPINC_ABSPATH.'admin-settings.php');
$admin_settings = new admin_settings();
if(isset($_REQUEST['action']) or isset($_POST['action'])){}else {$token = admin_set_token();}

define('THIS_SCRIPT', 'themes.php');
if(isset($_REQUEST['page'])):
    $hooks->do_action('admin_page_theme_options_display');
elseif(isset($_REQUEST['mode']) and $_REQUEST['mode'] == 'menus'):
    $admin_settings->index_menus();
else:
    $admin_settings->index_themes();
endif;

?>
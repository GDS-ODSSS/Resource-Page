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

is_user_permissions('settings');

include(ACPINC_ABSPATH.'admin-settings.php');
$admin_settings = new admin_settings();

if(isset($_REQUEST['action']) or isset($_POST['action'])){}else {$token = admin_set_token();}

$hooks->do_action('admin_page_setting_display');

if(isset($_REQUEST['mode']) and $_REQUEST['mode'] == 'general'):
    $admin_settings->index_general();
elseif(isset($_REQUEST['mode']) and $_REQUEST['mode'] == 'extensions'):
    $admin_settings->index_extensions();
elseif(isset($_REQUEST['mode']) and $_REQUEST['mode'] == 'socials'):
    $admin_settings->index_socials();
elseif(isset($_REQUEST['mode']) and $_REQUEST['mode'] == 'users'):
    $admin_settings->index_users();
elseif(isset($_REQUEST['mode']) and $_REQUEST['mode'] == 'emailtemplate'):
    $admin_settings->index_emailtemplate();
else:
    $admin_settings->index_general();
endif;
?>
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
is_user_permissions('emailtemplate');
include(ACPINC_ABSPATH.'admin-emailtemplate.php');
$admin_emailtemplate = new admin_emailtemplate();
if(isset($_REQUEST['action']) or isset($_POST['action'])){}else {$token = admin_set_token();}
define('THIS_SCRIPT', 'emailtemplate.php');

if(isset($_REQUEST['page'])):
    $hooks->do_action('admin_page_theme_options_display');
elseif(isset($_POST['action']) and $_POST['action'] == 'update'):
    $admin_emailtemplate->index_update();
elseif(isset($_REQUEST['mode']) and $_REQUEST['mode'] == 'edit'):
    $admin_emailtemplate->index_edit();
elseif(isset($_REQUEST['mode']) and $_REQUEST['mode'] == 'preview'):
    $admin_emailtemplate->index_preview();
else:
    $admin_emailtemplate->index_emailtemplate();
endif;

?>
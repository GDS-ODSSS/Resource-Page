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
is_user_permissions('language');
include(ACPINC_ABSPATH.'admin-language.php');
$admin_language = new admin_language();
define('THIS_SCRIPT', 'language.php');
if(isset($_REQUEST['action']) or isset($_POST['action'])){}else {$token = admin_set_token();}

if(isset($_REQUEST['mode']) and $_REQUEST['mode'] == 'addnew_language'):
    $admin_language->index_form_language();
elseif(isset($_GET['edit_language']) and is_numeric($_GET['edit_language'])):
    $admin_language->index_form_language();
elseif(isset($_REQUEST['action']) and $_REQUEST['action'] == 'activ'):
    $admin_language->active_language();
elseif(isset($_GET['edit_phrase']) and is_numeric($_GET['edit_phrase'])):
    $admin_language->index_form_phrase();
elseif(isset($_REQUEST['action']) and $_REQUEST['action'] == 'delete'):
    $admin_language->delete_language();
elseif(isset($_REQUEST['mode']) and $_REQUEST['mode'] == 'new'):
    $admin_language->form_language('new');
elseif(isset($_REQUEST['copy_phrase']) and $_REQUEST['copy_phrase'] == 'copy'):
    $admin_language->copy_phrase();
else:
    $admin_language->index_language();
endif;
?>
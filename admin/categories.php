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
$taxonomy = (isset($_REQUEST['taxonomy']))? $_REQUEST['taxonomy']: 'knowledgebase';
is_user_permissions($taxonomy);
include(ACPINC_ABSPATH.'admin-categories.php');
$admin_categories = new admin_categories();
define('THIS_SCRIPT', 'categories.php');
define('THIS_SCRIPT_RETURN', 'categories.php?taxonomy='.$taxonomy);
if(isset($_REQUEST['action']) or isset($_POST['action'])){}else{$token = admin_set_token();}
$admin_categories->index_terms($taxonomy);
?>
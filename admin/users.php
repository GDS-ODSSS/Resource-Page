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
include(ACPINC_ABSPATH.'admin-users.php');
$admin_users = new admin_users();
define('THIS_SCRIPT', 'users.php');
if(isset($_REQUEST['action']) or isset($_POST['action']) ){}else {$token = admin_set_token('user');}
$admin_users->index_users();
?>
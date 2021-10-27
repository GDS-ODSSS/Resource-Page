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
is_user_permissions('media');
include(ACPINC_ABSPATH.'admin-post-boxes.php');
$post_boxes = new admin_post_boxes;
include(ACPINC_ABSPATH . 'admin-media.php');
define('THIS_SCRIPT', 'media.php');
if(isset($_REQUEST['action']) or isset($_POST['action'])){}else {$token = admin_set_token();}
$admin_media = new admin_media();
$admin_media->index_media();
?>
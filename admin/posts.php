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
$post_type = (isset($_REQUEST['post_type']))? $_REQUEST['post_type'] : 'knowledgebase';
is_user_permissions($post_type);
include(ACPINC_ABSPATH.'admin-post-boxes.php');
include(ACPINC_ABSPATH.'admin-posts.php');
$admin_posts = new admin_posts();
define('THIS_SCRIPT', 'posts.php');
define('THIS_SCRIPT_RETURN', 'posts.php?post_type='.$post_type);
if(isset($_REQUEST['action']) or isset($_POST['action'])){}else {$token = admin_set_token();}
$admin_posts->index_posts($post_type);
?>
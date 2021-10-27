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

include(ACPINC_ABSPATH . 'admin-dashboard.php');
$admin_dashboard = new admin_dashboard();
define('THIS_SCRIPT', 'index.php');

if(isset($_REQUEST['mode']) and $_REQUEST['mode'] == 'profile')
{
    $admin_dashboard->index_profile();
}
else
{
    $admin_dashboard->index_dashboard();
}
?>
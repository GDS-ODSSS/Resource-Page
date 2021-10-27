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
define('IN_PHPMEGATEMP', true);
require_once('common.php');
// set counter (visits)
set_counter();

if($config['maintenance_status'])
{
    $routes = array();
    $display->index_maintenance();
}
else
{
    // Validate Request
    $routes = (isset($_GET["route"]) && !empty($_GET["route"]))? explode("/",safe_input($_GET["route"])) : array() ;
    // add do action start page
    $hooks->do_action('index_page_start_display', $routes);
    $hooks->do_action('index_home_display', $routes);
}

?>

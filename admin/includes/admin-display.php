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

// add action print style and script
$hooks->add_action('admin_head', 'admin_print_style', 1);
$hooks->add_action('admin_head', 'admin_print_script', 2);
$hooks->add_action('admin_footer', 'admin_print_footer_script', 2);
// admin print style
function admin_print_style()
{
    global $config;
    $get_dir = get_language_direction($config['language']);
    $style_array = array(
        'style_bootstrap'           => 'assets/libs/bootstrap/bootstrap.min.css',
        'style_jquery'              => 'assets/libs/jquery/jquery-ui.min.css',
        'style_fontawesome'         => 'assets/libs/fontawesome/css/all.min.css',
        'style_peicon7stroke'       => 'assets/libs/peicon7stroke/css/peicon7stroke.css',
        'style_themeicons'          => 'assets/libs/themeicons/css/themeicons.min.css',
        'style_datatables'          => 'assets/libs/bootstrap/datatables.bootstrap.min.css',
        'style_checkbox'            => 'assets/libs/checkbox/checkbox.min.css',
        'style_admin_dragdrop'      => 'assets/libs/drag-drop/drag-drop.css',
        'style_thickbox'            => 'assets/libs/cupload/js/thickbox/thickbox.min.css',
        'style_tagsinput'           => 'assets/libs/bootstrap/bootstrap-tagsinput.css',
        'style_cupload'             => 'assets/libs/cupload/css/cup-style.min.css',
        'style_admin_style'         => ($get_dir == 'ltr')? 'assets/libs/admin/style.css' : 'assets/libs/admin/style.rtl.css',
        'style_color_blue'          => 'assets/libs/admin/blue.css',
        'style_megapanel'           => 'assets/libs/admin/megapanel/megapanel_options.css',
    );
    
    foreach($style_array AS $key => $value)
    {
        echo '<link rel="stylesheet" href="'.$value.'" type="text/css" />'."\n";
    }
}
// admin print script
function admin_print_script()
{
    $script_array = array(
        'script_jquery'                 => 'assets/libs/jquery/jquery.js',
        'script_jqueryui'               => 'assets/libs/jquery/jquery-ui.js',
        'script_bootstrap'              => 'assets/libs/bootstrap/bootstrap.min.js',
        'script_jquery_cookie'          => 'assets/libs/jquery/jquery.cookie.js',
        'script_ajax_queue'             => 'assets/libs/jquery/jquery.ajaxqueue.min.js',
        'script_dataTables'             => 'assets/libs/jquery/jquery.datatables.min.js',
        'script_dataTables_bootstrap'   => 'assets/libs/bootstrap/datatables.bootstrap.min.js',
        'script_tinymce'                => 'assets/libs/tinymce/tinymce.min.js',
        'script_jquery_tmpl'            => 'assets/libs/jquery/jquery.tmpl.js',
        'script_tagsinput'              => 'assets/libs/bootstrap/bootstrap-tagsinput.min.js',
        'script_tipsy'                  => 'assets/libs/admin/tipsy.js',
        'script_checkbox'               => 'assets/libs/checkbox/checkbox.min.js',
        'script_checkboxes'             => 'assets/libs/checkbox/iphone-style-checkboxes.min.js',
        'script_jquery_multifile'       => 'assets/libs/cupload/js/jquery.MultiFile.js',
        'script_thickbox'               => 'assets/libs/cupload/js/thickbox/thickbox.min.js',
        'script_cupload'                => 'assets/libs/cupload/js/creative-upload-plugins.min.js',
        'script_plugins'                => 'assets/libs/admin/plugins.js',
        'script_themearabia'            => 'assets/libs/admin/themearabia.min.js'
    );
    foreach($script_array AS $key => $value)
    {
        echo '<script type="text/javascript" src="'.$value.'"></script>'."\n";
    }
    echo '<script type="text/javascript">function resetMenu() {document.gomenu.selector.selectedIndex = 2;}</script>'."\n";
}
// admin print footer script
function admin_print_footer_script()
{
    $script_array = array(
        'script_megapanel' => 'assets/libs/admin/megapanel/megapanel_options.js',
    );
    foreach($script_array AS $key => $value)
    {
        echo '<script type="text/javascript" src="'.$value.'"></script>'."\n";
    }
}
// admin header
function admin_header($page_title, $full = true)
{
    global $hooks,$config;
$userid = get_session_userid();
echo '<!DOCTYPE html>
<!--[if IE 8]>
<html xmlns="http://www.w3.org/1999/xhtml" class="ie8" lang="'.get_language_country_abbreviation($config['language']).'">
<![endif]-->
<!--[if !(IE 8) ]><!-->
<html xmlns="http://www.w3.org/1999/xhtml" lang="'.get_language_country_abbreviation($config['language']).'">
<!--<![endif]-->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>'.$config['sitename'].' &#8212; '.$page_title.'</title>
<link rel="shortcut icon" href="assets/images/favicon.ico">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<script>var actionselect = true;</script>
';

$hooks->do_action( 'admin_head' );
$hooks->do_action( 'admin_supports_enqueue_style' );
echo '<!--[if lt IE 9]><script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script><script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->
</head>
<body dir="'.get_language_direction($config['language']).'" class="hold-transition skin-blue sidebar-mini '.get_language_direction($config['language']).'">';
    if($full){
        echo '
        <div class="wrapper">
        <header class="main-header">
        <nav class="navbar navbar-static-top">
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button"><i class="fas fa-bars"></i><span class="sr-only">Toggle navigation</span></a>
        <div class="navbar-custom-menu"><ul class="nav navbar-nav pull-left"><li><a href="#"><i class="fas fa-coffee"></i></a>
        <ul class="user-menu dropdown-menu dropdown-caret dropdown-closer">
        <li><a href="http://nawaaugustine.com" target="_blank">nawaaugustine.com</a></li>
        </ul></li><li><a href="../index.php"><i class="fas fa-home"></i> <span class="notphone">'.$config['sitename'].'</span></a>
        <ul class="user-menu dropdown-menu dropdown-caret dropdown-closer">
        <li><a href="../index.php" target="_blank">'.get_admin_languages('visit_site').'</a></li></ul></li>';
        $hooks->do_action( 'admin_navbar_top' );
        echo '</ul><ul class="nav navbar-nav pull-right navbar-user"><li><a href="#"><span class="user-info">'.get_admin_languages('howdy').' '.get_user_column($userid,'username').'</span>
        <img class="nav-user-photo" src="'.get_gravatar($userid,32,'','','../').'" alt="'.get_user_column($userid,'username').'" /></a>
        <ul class="user-menu pull-right dropdown-menu dropdown-caret dropdown-closer">
        <img class="nav-user-photo" src="'.get_gravatar($userid,80,'','','../').'" alt="'.get_user_column($userid,'username').'" />
        <li><a href="users.php?mode=profile"><i class="fas fa-user"></i> '.get_admin_languages('profile').'</a></li>
        <li><a href="admin-login.php?do=logout"><i class="fas fa-power-off"></i> '.get_admin_languages('logout').'</a></li>
        </ul></li>
        </ul></div></nav></header>';
        echo '<aside class="main-sidebar"><section class="sidebar"><ul class="sidebar-menu" data-widget="tree">';
        $hooks->do_action( 'admin_sidebar_menu' );
        echo '</ul></section></aside><div class="content-wrapper">';
        $hooks->do_action( 'admin_page_header' );
    }
}
// admin footer
function admin_footer($full = true)
{
    global $hooks, $config;
    echo '</div><footer class="main-footer"><hr />
<div class="float-right hidden-xs">'.get_admin_languages('version').' '.SCRIPT_VERSION.'</div>
Copyright &copy; '.date('Y').' <a href="http://nawaaugustine.com" target="_blank">nawaaugustine.com</a>. All rights reserved.</footer>
<div class="control-sidebar-bg"></div></div>
<div id="megapanel-icon-dialog" class="megapanel-dialog" title="'.get_admin_languages('add_icon').'">
<div class="megapanel-dialog-header megapanel-text-center"><input type="text" placeholder="'.get_admin_languages('search').'" class="megapanel-icon-search" /></div>
<div class="megapanel-dialog-load"><div class="megapanel-dialog-loading">'. get_admin_languages( 'loading') .'</div></div>
</div>
<div class="pop-up subscribe">
  <div class="content">
    <div class="container">
      <div class="dots">
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot"></div>
      </div>
      <span class="close">close</span>
      <div class="title">
        <h1>subscribe</h1>
      </div>
      <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/256492/cXsiNryL.png" alt="Car">
      <div class="subscribe">
        <h1>Subscribe to get the latest <span>news &amp; updates</span>.</h1>
        <form>
          <input type="email" placeholder="Your email address" required="">
          <input type="submit" value="Subscribe">
        </form>
      </div>
    </div>
  </div>
</div>
';
$hooks->do_action( 'admin_footer' );
$hooks->do_action( 'admin_supports_enqueue_script' );
echo '
<script type="text/javascript">
    var ajaxRequests    = [];
    var admin_ajax_url = "'.$config['siteurl'].'/'.ADMIN_DASHBOARD.'/admin_ajax.php";
	$(function() {
        $(".subscribe").addClass("-open");
        $(".subscribe .close").click(function(){
            $(".subscribe").removeClass("open");
        });
        $("table th input:checkbox").on("click" , function(){var that = this;$(this).closest("table").find("tr > td:first-child input:checkbox").each(function(){this.checked = that.checked;$(this).closest("tr").toggleClass("selected");});	});})
</script>
</body></html>';
}
?>
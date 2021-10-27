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

// get class men post type
function get_class_menu_post_type($type)
{
    $post_type  = (isset($_REQUEST['post_type']))? $_REQUEST['post_type'] : '';
    $taxonomy   = (isset($_REQUEST['taxonomy']))? $_REQUEST['taxonomy'] : '';
    return ($type == $post_type or $type == $taxonomy)? 'active' : '';
}
// display sidebar menu top
function display_sidebar_menu_top()
{
    admin_sidebar_menu_register(array('class' => current_page_sidebar('index.php','active'), 'url' => 'index.php', 'title' => get_admin_languages('dashboard'), 'icon' => 'fas fa-tachometer-alt', 'sub' => false));
}
// get admin sidebar sub themes
function get_admin_sidebar_sub_themes()
{
    global $hooks;
    //$menus['themes'] = array('url' => 'themes.php',            'title' => get_admin_languages('themes'));
    $menus['menus']  = array('url' => 'themes.php?mode=menus', 'title' => get_admin_languages('menus'));
    if($hooks->has_filter('add_admin_sidebar_sub_themes')):
        $menus_filters = $hooks->apply_filters( 'add_admin_sidebar_sub_themes' , $menus);
    else:
        $menus_filters = $menus;
    endif;
    return $menus_filters;
}
// get admin sidebar sub settings
function get_admin_sidebar_sub_settings()
{
    global $hooks;
    $menus['general'] = array('url' => 'settings.php?mode=general', 'title' => get_admin_languages('general_settings'));
    $menus['extensions'] = array('url' => 'settings.php?mode=extensions', 'title' => get_admin_languages('extensions_settings'));
    
    if($hooks->has_filter('add_admin_sidebar_sub_settings')):
        $menus_filters = $hooks->apply_filters( 'add_admin_sidebar_sub_settings' , $menus);
    else:
        $menus_filters = $menus;
    endif;
    return $menus_filters;
}
// display sidebar menu bottom
function display_sidebar_menu_bottom()
{
    // test permissions page
    if(is_user_permissions('page', false))
    {
        $menus[33] = array('class' => get_class_menu_post_type('page'), 'url' => '#', 'title' => get_admin_languages('pages'), 'icon' => 'far fa-file-alt',
            'sub' => array(
                array('url' => 'posts.php?post_type=page',          'title' => get_admin_languages('pages')),
                array('url' => 'posts.php?post_type=page&mode=new', 'title' => get_admin_languages('add_new')),
            )
        );
    }
    if(is_user_permissions('media_library', false))
    {
        $menus[44] = array('class' => current_page_sidebar('media.php', 'active'), 'url' => '#', 'title' => get_admin_languages('media_library'), 'icon' => 'fas fa-anchor',
            'sub' => array(
                array('url' => 'media.php',          'title' => get_admin_languages('media_library')),
                array('url' => 'media.php?mode=upload', 'title' => get_admin_languages('add_new')),
            )
        );
    }
    // test permissions user
    if(is_user_permissions('users', false))
    {
        $menus[55] = array('class' => current_page_sidebar('users.php','active'), 'url' => '#', 'title' => get_admin_languages('users'), 'icon' => 'fas fa-users',
            'sub' => array(
                array('url' => 'users.php',                 'title' => get_admin_languages('users')),
                array('url' => 'users.php?mode=new',        'title' => get_admin_languages('add_new')),
                array('url' => 'users.php?mode=profile',    'title' => get_admin_languages('your_profile')),
            )
        );
    }
    else
    {
        $menus[55] = array('class' => current_page_sidebar('users.php','active'), 'url' => 'users.php?mode=profile', 'title' => get_admin_languages('your_profile'), 'icon'=> 'fas fa-users', 'sub' => false);
    }
    // test permissions appearance
    if(is_user_permissions('appearance', false))
    {
        $menus[66] = array('class' => current_page_sidebar('themes.php','active'), 'url' => '#', 'title' => get_admin_languages('appearance'), 'icon' => 'fas fa-paint-brush', 
            'sub' => get_admin_sidebar_sub_themes()
        );
    }
    // test permissions settings
    if(is_user_permissions('settings', false))
    {
        $menus[77] = array('class' => current_page_sidebar('settings.php','active'), 'url' => '#', 'title' => get_admin_languages('settings'), 'icon' => 'fas fa-sliders-h', 
            'sub' => get_admin_sidebar_sub_settings()
        );
    }
    // test permissions language
    if(is_user_permissions('language', false))
    {
        $menus[88] = array('class' => current_page_sidebar('language.php','active'), 'url' => 'language.php', 'title' => get_admin_languages('languages'), 'icon' => 'fas fa-language', 'sub' => false);
    }
    
    // test permissions emailtemplate
    if(is_user_permissions('emailtemplate', false))
    {
        //$menus[99] = array('class' => current_page_sidebar('emailtemplate.php','active'), 'url' => 'emailtemplate.php', 'title' => get_admin_languages('emailtemplate'), 'icon' => 'fas fa-envelope-open-text', 'sub' => false);
    }
    // test permissions extensions
    if(is_user_permissions('extensions', false))
    {
        //$menus[109] = array('class' => current_page_sidebar('extensions.php','active'), 'url' => 'extensions.php', 'title' => get_admin_languages('extensions'), 'icon'=> 'fas fa-plug', 'sub' => false);
    }
    // foreach menus
    foreach($menus as $menu)
    {
        admin_sidebar_menu_register($menu);
    }
}
// admin sidebar menu register
function admin_sidebar_menu_register($menu)
{
    $html = '';
    $class = (isset($menu['class']))? $menu['class'] : '';
    if(isset($menu['container']) and $menu['container'] and is_array($menu['container'])):
        $container = '<span class="pull-right-container">';
        foreach($menu['container'] as $label)
        {
            $container .= '<small class="label pull-right bg-'.$label['color'].'">'.$label['count'].'</small>';
        }
        $container .= '</span>';
    else:
        $container = '';
    endif;
    // menus sub
    if($menu['sub'] == false)
    {
       $html .= '<li class="'.$class.'"><a href="'.$menu['url'].'"><i class="'.$menu['icon'].'"></i> <span>'.$menu['title'].'</span>'.$container.'</a></li>';
    }
    else
    {
        $html .= '<li class="treeview '.$class.'"><a href="'.$menu['url'].'"><i class="'.$menu['icon'].'"></i> <span>'.$menu['title'].'</span>'.$container.'</a><ul class="treeview-menu">'; 
        foreach($menu['sub'] as $sub)
        {
            $html .= '<li><a href="'.$sub['url'].'">'.$sub['title'].'</a></li>';
        }
        $html .= '</ul></li>';
    }
    echo $html;
}
// start action menus (top - bottom)
$hooks->add_action('admin_sidebar_menu', 'display_sidebar_menu_top', 1);  
$hooks->add_action('admin_sidebar_menu', 'display_sidebar_menu_bottom', 99);
?>
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
if (!defined("IN_PHPMEGATEMP"))
      die('Direct access to this location is not allowed.');

// WARNING! Do not change this file
// get permanent link type
function get_permanent_link_type()
{
    global $config;
    if(isset($config['permanent_link_type']) and in_array($config['permanent_link_type'], array('php','html','name')))
    {
        $permanent_link_type  = $config['permanent_link_type'];
    }
    else
    {
        $permanent_link_type = PERMANENT_LINK_TYPE;
    }
    return $permanent_link_type;
}
// assign permanent links
function assign_permanent_links()
{
    global $config, $template;
    $userid = get_session_userid();
    $template->assign_vars(array(
        'PERMALINK_HOME'                => $config['siteurl'], 
        'PERMALINK_SIGNIN'              => "{$config['siteurl']}/signin", 
        'PERMALINK_SIGNUP'              => "{$config['siteurl']}/signup", 
        'PERMALINK_SIGNOUT'             => "{$config['siteurl']}/signout", 
        'PERMALINK_FORGOT'              => "{$config['siteurl']}/forgot",
        'PERMALINK_USER_PROFILE'        => "{$config['siteurl']}/profile",
        'PERMALINK_ADMIN_DASHBOARD'     => "{$config['siteurl']}/".ADMIN_DASHBOARD,
    ));
}
// get permanent link
function get_permanent_link($page)
{
    global $config;
    return "{$config['siteurl']}/{$page}";
}
// get permanent link
function get_permanent_link_postid($id, $attr = '')
{
    global $config;
    $slug = '';
    return "{$config['siteurl']}/{$attr}/{$slug}";
}
// permanent post link
function permanent_post_link($type = 'post', $id = '')
{
    global $config;
    $slug = get_post_column($id, 'post_name');
    return "{$config['siteurl']}/{$type}/".utf8_uri_encode($slug);
}
// permanent user link
function permanent_user_link($user_link = '', $userid = false)
{
    global $config;
    $username = get_user_column($userid, 'username');
	return "{$config['siteurl']}/{$user_link}/".utf8_uri_encode($username);
}
// permanent terms link
function permanent_terms_link($terms_link = '', $terms_id = '', $terms_slug = '')
{
    global $config;
    return "{$config['siteurl']}/{$terms_link}/".utf8_uri_encode($terms_slug);
}
// permanent terms section link
function permanent_terms_section_link($section = '', $terms_id = '', $terms_slug = '')
{
    global $config;
    return "{$config['siteurl']}/{$section}";
}
// permanent tags link
function permanent_tags_link($tags_link = '', $tags_slug = '')
{
    global $config;
    return "{$config['siteurl']}/{$tags_link}/tag/{$tags_slug}";
}
?>
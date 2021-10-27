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

ob_start();
if(!isset($_SESSION)) 
{ 
    session_start(); 
} 
$starttime = microtime(true);

if (!file_exists('config.php')) {
  header("Location: install/install.php");
  exit;
}
    
require_once('config.php');
require_once(ABSPATH . 'includes/constants.php');
require_once(ABSPATH . 'includes/startup.php');
$hooks->do_action('init_display');
$system_start   = new system_start();
$template       = new template();
$display        = new display();
$users          = new users();
$system_start->setup();
/*
remove 4.5
get_assign_languages();
*/

assign_permanent_links();
// global header
function global_header($args)
{
    global $template, $config, $hooks;

    $static_counter = get_static_counter();

    $template->assign_vars(array(
        'SITE_CHARSET'      => (isset($config['charset']) and $config['charset'] !='')?$config['charset']: 'utf-8',
        'SITE_LANG'         => (get_language_country_abbreviation())? get_language_country_abbreviation() : 'en',
        'SITE_DIR'          => (get_language_direction())? get_language_direction() : 'ltr',
        'IS_RTL'            => (get_language_direction() =='rtl')? true: false,
        'SITE_NAME'         => $config['sitename'],
        'SITE_TAGLINE'      => $config['description'],
        'SITE_DESCRIPTION'	=> $config['sitedesc'],
        'SITE_KEYWORDS'     => $config['sitekey'],
        'SITE_MAIL'			=> $config['sitemail'],
        'SITE_URL'          => $config['siteurl'],
        'PAGE_TITLE'        => $args['page_title'],
        'PAGE_DISPLAY'      => (isset($args['pagedisplay']))? $args['pagedisplay']: false,
        'TEMPLATE_URL'      => $config['siteurl'].'/'.THEME_PATH.$config['sitethemes'],
        'SCRIPT_VERSION'    => SCRIPT_VERSION,
        'GET_DATE_Y'        => date('Y'),
        'IS_MOBILE'         => is_mobile(),
        'SCOUNTER_TOTAL'    => $static_counter['total'],
        'SCOUNTER_YEAR'     => $static_counter['year'],
        'SCOUNTER_MONTH'    => $static_counter['month'],
        'SCOUNTER_DAY'      => $static_counter['day'],
        'UN_SCOUNTER_TOTAL' => $static_counter['un_total'],
        'UN_SCOUNTER_YEAR'  => $static_counter['un_year'],
        'UN_SCOUNTER_MONTH' => $static_counter['un_month'],
        'UN_SCOUNTER_DAY'   => $static_counter['un_day'],
    ));
    $hooks->do_action('global_assign_vars');
    get_actions_enqueue_style();
    get_actions_enqueue_script();
    $hooks->do_action('global_header');
    $get_main_menu = maybe_unserialize($config['nav_main_menu']);
    if(is_array($get_main_menu))
    {
        $main_menu = $get_main_menu;
    }
    else
    {
        $main_menu = array();
    }
	$ls = 0;
    
    foreach($main_menu as $key => $value)
    {
        if($value['itemtype'] == 'Custom_Link')
        {
            $url = $value['url'];
        }
        elseif($value['url'] == '#')
        {
            $url = 'javascript:void(0);';
        }
        else
        {
            $url = trim($config['siteurl'], '/').'/'.$value['url'];
        }
        
        $template->assign_block_vars('loop_main_menu', array( 
			'MENU_LS'			=> $ls,
            'MENU_ICON'     	=> $value['icon'],
            'MENU_IMAGE'        => $value['image'],
			'MENU_TITLE'    	=> $value['title'],
			'MENU_URL'      	=> $url,
			'URL_TARGET'      	=> ($value['target'])? ' target="_blank"' : '',
			'MENU_USERONLY' 	=> ($value['useronly'])? true : false,
            'MENU_CLASS'	    => $value['classes'],
            'MENU_SUB_COUNT'	=> ($value['submenu'])? count($value['submenu']) : false,
		));
		if($value['submenu'])
		{
			$subls = 0;
			foreach($value['submenu'] as $subkey => $subvalue)
    		{
				$subls++;
                if($subvalue['itemtype'] == 'Custom_Link')
                {
                    $urlsub = $subvalue['url'];
                }
                elseif($subvalue['url'] == '#')
                {
                    $urlsub = 'javascript:void(0);';
                }
                else
                {
                    $urlsub = trim($config['siteurl'], '/').'/'.$subvalue['url'];
                }
				$template->assign_block_vars('loop_main_menu.loop_main_menu_sub', array( 
                    'MENU_ICON'     => $subvalue['icon'],
                    'MENU_IMAGE'    => $subvalue['image'],
					'MENU_TITLE'    => $subvalue['title'],
					'MENU_URL'      => $urlsub,
                    'URL_TARGET'    => ($subvalue['target'])? ' target="_blank"' : '',
                    'MENU_CLASS'	=> $subvalue['classes'],
					'MENU_USERONLY' => ($subvalue['useronly'])? true : false,
					'SUB_NUM'		=> $subls,
				));
			}
		}
    }
    if(has_session())
    {
        $userid = get_session_userid();
        $template->assign_vars(array(
            'IS_USER'           => true,
            'USER_ID'           => $userid,
            'USER_LEVEL'        => get_user_meta($userid,'userlevel'),
            'USER_NAME'         => get_user_column($userid, 'username'),
            'USER_EMAIL'        => get_user_column($userid, 'email'),
            'USER_STATUS'       => get_user_column($userid, 'status'),
            'USER_FULLNAME'     => get_user_meta($userid,'firstname').' '.get_user_meta($userid,'lastname'),
            'USER_FNAME'        => get_user_meta($userid,'firstname'),
            'USER_LNAME'        => get_user_meta($userid,'lastname'),
            'USER_AVATER'       => get_gravatar($userid,164),
            'IS_USER_ADMIN'     => is_admin(),
        ));
        $hooks->do_action('global_user_inof');
    }
    else
    {
        $template->assign_var('IS_USER', false);
        $hooks->do_action('is_not_login');
    }
}
// global footer
function global_footer($args)
{
    global $template, $hooks;
    $hooks->do_action('global_footer');
}
?>
<?php
/**
 * Extension Name: Team
 * Extension URI: http://nawaaugustine.com/php_help_manager/extension/team
 * Version: 1.0
 * Requires: 2.0
 * Description: team
 * Author: Augustine Nawa
 * Author URI: http://nawaaugustine.com
*/
if (!defined("IN_PHPMEGATEMP"))
      die('Direct access to this location is not allowed.');

if(!class_exists('extensions_team'))
{
    /* require class admin */
    require_once(dirname(__FILE__) .'/extension_admin.php');
    /* class extensions extends class admin */
    class extensions_team extends extensions_admin_team
    {
        /* construct */
        function __construct()
        {
            global $hooks;
            /* action and filter */
            $hooks->add_action('global_assign_vars', array($this , 'global_assign_vars'),1);
            $hooks->add_filter('add_page_theme_templates', array($this , 'page_theme_templates'), 40);
            /* display admin */
            if(defined('IN_PHPMEGATEMP_CP'))
            {
                $this->display_admin_team();
            }
        }
        /* get option ex*/
        function get_options($name)
        {
            global $config;
            $team_options = array(
                'section_home'          => '1', 
                'post_per_page'         => '10',
                'post_per_page'         => '10',
                'terms_home'       => array(),
            );
            $options = (isset($config['team_options']))? maybe_unserialize($config['team_options']) : $team_options;
            return @$options[$name];
        }
        /* global assign vars */
        function global_assign_vars()
        {
            global $routes, $db, $template, $config;
            $get_option = (isset($config['team_options']))? maybe_unserialize($config['team_options']) : array() ;
            foreach($get_option as $key => $value)
            {
                $template->assign_var('TEAM_'.strtoupper($key), $value);
            }
            $limit = (count($routes))? '' : 'LIMIT '.$this->get_options('home_post_per_page');
            $sql       = "SELECT * FROM ".POSTS_TABLE." JOIN ".POSTSMETA_TABLE." ON (`meta_key`='orders_post') AND (`post_id`=`id`) WHERE `post_status`='1' and `post_type`='team' ORDER BY meta_value ASC {$limit}";
            $result    = $db->sql_query($sql);
            while($row = $db->sql_fetchrow($result))
            {
                $template->assign_block_vars('loop_team', array(
                    'POST_ID'           => $row['id'],
                    'POST_TITLE'        => get_post_title($row['post_title']),
                    'POST_THUMB'        => get_post_attachment($row['id'], ''),
                    'POST_JOB'          => get_post_meta($row['id'], 'job'),
                    'POST_FACEBOOK'     => get_post_meta($row['id'], 'facebook'),
                    'POST_TWITTER'      => get_post_meta($row['id'], 'twitter'),
                    'POST_INSTAGRAM'    => get_post_meta($row['id'], 'instagram'),
                ));
            }
            $isteam = $db->sql_numrows($sql);
            
        }
        
        // page theme templates
        function page_theme_templates($arg)
        {
            $templates = array(
                'teamstyle1'   => 'team/page_team_style1.html',
                'teamstyle2'   => 'team/page_team_style2.html',
                'teamstyle3'   => 'team/page_team_style3.html',
            );
            return array_merge( $arg, $templates) ;
        }
    }
    /* End class */
    /* display class */
    new extensions_team();
}
?>
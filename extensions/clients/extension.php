<?php
/**
 * Extension Name: Clients
 * Extension URI: http://nawaaugustine.com/php_help_manager/extension/clients
 * Version: 1.0
 * Requires: 2.0
 * Description: clients
 * Author: Augustine Nawa
 * Author URI: http://nawaaugustine.com
*/
if (!defined("IN_PHPMEGATEMP"))
      die('Direct access to this location is not allowed.');

if(!class_exists('extensions_clients'))
{
    /* require class admin */
    require_once(dirname(__FILE__) .'/extension_admin.php');
    /* class extensions extends class admin */
    class extensions_clients extends extensions_admin_clients
    {
        /* construct */
        function __construct()
        {
            global $hooks;
            /* action and filter */
            $hooks->add_action('global_assign_vars', array($this , 'global_assign_vars'),1);
            /* display admin */
            if(defined('IN_PHPMEGATEMP_CP'))
            {
                $this->display_admin_clients();
            }
        }
        /* get option ex*/
        function get_options($name)
        {
            global $config;
            $clients_options = array(
                'section_home'          => '1', 
                'post_per_page'         => '10',
                'post_per_page'         => '10',
                'terms_home'       => array(),
            );
            $options = (isset($config['clients_options']))? maybe_unserialize($config['clients_options']) : $clients_options;
            return @$options[$name];
        }
        /* global assign vars */
        function global_assign_vars()
        {
            global $db, $template, $config;
            $get_option = (isset($config['clients_options']))? maybe_unserialize($config['clients_options']) : array() ;
            foreach($get_option as $key => $value)
            {
                $template->assign_var('CLIENTS_'.strtoupper($key), $value);
            }
            
            $sql       = "SELECT * FROM ".POSTS_TABLE." JOIN ".POSTSMETA_TABLE." ON (`meta_key`='orders_post') AND (`post_id`=`id`) WHERE `post_status`='1' and `post_type`='clients' ORDER BY meta_value ASC";
            $result    = $db->sql_query($sql);
            while($row = $db->sql_fetchrow($result))
            {
                $template->assign_block_vars('loop_clients', array(
                    'POST_ID'       => $row['id'],
                    'POST_TITLE'    => get_post_title($row['post_title']),
                    'POST_THUMB'    => get_post_attachment($row['id'], ''),
                    'POST_URL'      => get_post_meta($row['id'], 'client_url', ''),
                ));
            }
            $isclients = $db->sql_numrows($sql);
        }
    }
    /* End class */
    /* display class */
    new extensions_clients();
}
?>
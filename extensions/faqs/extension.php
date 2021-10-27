<?php
/**
 * Extension Name: Frequently Asked Questions
 * Extension URI: http://nawaaugustine.com/php_help_manager/extension/faqs
 * Version: 2.0
 * Requires: 2.0
 * Description: Frequently Asked Questions
 * Author: Augustine Nawa
 * Author URI: http://nawaaugustine.com
*/

if (!defined("IN_PHPMEGATEMP"))
      die('Direct access to this location is not allowed.');

if(!class_exists('extensions_faqs'))
{
    // include class admin
    include('extension_admin.php');
    
    // Start class
    class extensions_faqs extends extensions_admin_faqs
    {
        // class constructor
        function __construct()
        {
            global $hooks;
            // action site
            $hooks->add_action('global_assign_vars', array($this , 'global_assign_vars'),1);
            $hooks->add_action('index_home_action', array($this , 'home_action_faqs'), 1);
            $hooks->add_action('index_post_type_display', array($this , 'display_action_faqs'),1);
            $hooks->add_filter('supports_actions_post_type', array($this , 'supports_post_type'), 1);
            // display admin
            if(defined('IN_PHPMEGATEMP_CP'))
            {
                $this->display_admin_faqs();
            }
        }
        /* get option ex*/
        function get_options($name)
        {
            global $config;
            $faqs_options = array(
                'box_home'      => '1',
                'column'                => 'col-md-3',
                'classes'               => '',
                'title'                 => 'FAQ', 
                'icon'                  => '', 
                'description'           => '',
                'text_link'             => '',
                'section_home'          => '1', 
                'home_post_per_page'    => '10', 
                'post_per_page'         => '10',
                'post_per_page'         => '10',
            );
            $options = ($config['faqs_options'])? maybe_unserialize($config['faqs_options']) : $faqs_options;
            return $options[$name];
        }
        // global assign vars
        function global_assign_vars()
        {
            global $template, $config;
            $template->assign_vars(array(
                'HOME_FAQS'        => $this->get_options('section_home'),
                'PERMALINK_FAQS'   => get_permanent_link('faqs')
            ));
            $get_option = (isset($config['faqs_options']))? maybe_unserialize($config['faqs_options']) : array() ;
            foreach($get_option as $key => $value)
            {
                $template->assign_var('FAQOP_'.strtoupper($key), $value);
            }
        }
        // add support post type
        function supports_post_type($arg)
        {
            $support = array('faqs');
            return array_unique( array_merge( $support, (array) $arg ) );
        }
        // add script
        function enqueue_script($arg)
        {
            $script = array(
                'script_faqs' => get_url_template('faqs/js/faqs.min.js')
            );
            return array_unique( array_merge( $script, (array) $arg ) );
        }
        // display action faqs
        function display_action_faqs($arg)
        {
            global $db, $config, $template, $hooks;
            if(isset($arg['0']) and $arg['0'] == 'faqs')
            {
                $this->display_faqs($arg);
            }
        }
        // display faqs
        function display_faqs($arg)
        {
            get_terms_loop(array('term_type' => 'faqs', 'assign_name' => 'terms_faqs', 'terms_link' => 'faqs/category'));
            if(isset($_GET['search']) and !empty($_GET['search']))
            {
                $this->index_search($arg);
            }
            elseif(isset($arg['1']) and $arg['1'] == 'category')
            {
                $term_id = get_term_column_by_slug(safe_input($arg['2']), 'id', 'faqs');
                $this->term_faqs($term_id, $arg);
            }
            elseif(isset($arg['1']) and $arg['1'] != 'category')
            {
                location_lastpage("faqs");
            }
            else
            {
                $this->index_faqs($arg);
            }
        }
        // display faqs
        function term_faqs($term_id, $arg)
        {
            global $db, $config, $template, $lang;
            if(!has_term_found($term_id, 'faqs') or !get_count_posts_term($term_id))
            {
                location_lastpage("faqs");
            }
            $sql    = "SELECT * FROM ".POSTS_TABLE." JOIN ".POSTSMETA_TABLE." ON (`meta_key`='orders_post') AND (`post_id`=`id`) 
            WHERE `post_status`='1' and `post_type`='faqs' and `term_id`='{$term_id}' ORDER BY ABS(`meta_value`) ASC , post_modified DESC";
            $result     = $db->sql_query($sql);
            while($row = $db->sql_fetchrow($result))
            {
                $template->assign_block_vars('loop_faqs', array( 
                    'POST_ID'                   => $row['id'],
                    'POST_TITLE'                => get_post_title($row['post_title']), 
                    'POST_CONTENT'              => get_post_content($row['post_content']), 
                ));
            }
            //All Categories
            $template->assign_vars(array(
                'CATEGORIES_TITLE' => ($term_id)? get_term_column($term_id, 'name') :  $lang['all_categories'],
                'THE_CATEGORIE_ID' => ($term_id)? $term_id : false, 
            ));
            page_header(array('page_title' => $lang['faqs'], 'pagedisplay' => 'faqs'));
            $template->set_filename('faqs/index_faqs.html');
            page_footer();
        }
        // index faqs
        function index_faqs($arg)
        {
            global $db, $config, $template, $lang;
            $limit  = ($this->get_options('post_per_page'))? $this->get_options('post_per_page') : 10 ;
            $sql    = "SELECT * FROM ".POSTS_TABLE." WHERE `post_status`='1' and `post_type`='faqs' ORDER BY post_modified DESC LIMIT {$limit}";
            $result     = $db->sql_query($sql);
            while($row = $db->sql_fetchrow($result))
            {
                $template->assign_block_vars('loop_faqs', array( 
                    'POST_ID'                   => $row['id'],
                    'POST_TITLE'                => get_post_title($row['post_title']), 
                    'POST_CONTENT'              => get_post_content($row['post_content']), 
                ));
            }
            //All Categories
            $template->assign_vars(array(
                'CATEGORIES_TITLE' => $lang['latest_posts'] ,
                'THE_CATEGORIE_ID' => false,
            ));
            page_header(array('page_title' => $lang['faqs'], 'pagedisplay' => 'faqs'));
            $template->set_filename('faqs/index_faqs.html');
            page_footer();
        }
        // index search
        function index_search($arg)
        {
            global $db, $config, $template, $lang;
            $search = safe_input($_GET['search']);
            $sql    = "SELECT * FROM ".POSTS_TABLE." WHERE `post_status`='1' and `post_type`='faqs' and (`post_title` LIKE '%{$search}%' or `post_content` LIKE '%{$search}%') ORDER BY post_modified DESC LIMIT 10";
            $result     = $db->sql_query($sql);
            while($row = $db->sql_fetchrow($result))
            {
                $template->assign_block_vars('loop_faqs', array( 
                    'POST_ID'                   => $row['id'],
                    'POST_TITLE'                => get_post_title($row['post_title']), 
                    'POST_CONTENT'              => get_post_content($row['post_content']), 
                ));
            }
            $template->assign_vars(array(
                'CATEGORIES_TITLE' => $lang['search'] ,
                'THE_CATEGORIE_ID' => false,
                'SEARCH_TXT'       => $search,
            ));
            page_header(array('page_title' => $lang['faqs'], 'pagedisplay' => 'faqs'));
            $template->set_filename('faqs/index_faqs.html');
            page_footer();
        }
        // home action faqs
        function home_action_faqs()
        {
            global $db, $config, $template;
            $limit  = ($this->get_options('home_post_per_page'))? $this->get_options('home_post_per_page') : 10 ;
            $sql    = "SELECT * FROM ".POSTS_TABLE." WHERE `post_status`='1' and `post_type`='faqs' ORDER BY post_modified DESC LIMIT {$limit}";
            $result     = $db->sql_query($sql);
            while($row = $db->sql_fetchrow($result))
            {
                $template->assign_block_vars('loop_faqs', array( 
                    'POST_ID'                   => $row['id'],
                    'POST_TITLE'                => get_post_title($row['post_title']), 
                    'POST_CONTENT'              => get_post_content($row['post_content']), 
                ));
            }
        }
    }
    // End class
    
    // display class
    new extensions_faqs();
}
?>
<?php
/**
 * Extension Name: Documentation
 * Extension URI: http://nawaaugustine.com/php_help_manager/extension/documentation
 * Version: 2.0
 * Requires: 4.0
 * Description: Documentation
 * Author: Augustine Nawa
 * Author URI: http://nawaaugustine.com
*/
if (!defined("IN_PHPMEGATEMP"))
      die('Direct access to this location is not allowed.');

if(!class_exists('extensions_documentation'))
{
    /* require class admin */
    require_once(dirname(__FILE__) .'/extension_admin.php');
    
    /* class extensions extends class admin */
    class extensions_documentation extends extensions_admin_documentation
    {
        /* construct */
        function __construct()
        {
            global $hooks;
            /* action and filter */
            $hooks->add_action('index_home_action', array($this , 'home_action_documentation'),1);
            $hooks->add_filter('supports_actions_post_type', array($this , 'supports_post_type'), 1);
            $hooks->add_action('global_assign_vars', array($this , 'global_assign_vars'),1);
            $hooks->add_action('index_post_type_display', array($this , 'display_action_documentation'),1);
            /* display admin */
            if(defined('IN_PHPMEGATEMP_CP'))
            {
                $this->display_admin_documentation();
            }
        }
        /* get option ex */
        function get_options($name)
        {
            global $config;
            $documentation_options = array(
                'box_home'              => '1',
                'column'                => 'col-md-3',
                'classes'               => '',
                'title'                 => 'Documentation', 
                'icon'                  => '', 
                'description'           => '',
                'text_link'             => '',
                'section_home'          => '1', 
                'home_post_per_page'    => '10', 
                'post_per_page'         => '10',
                'post_per_page'         => '10',
            );
            $options = ($config['documentation_options'])? maybe_unserialize($config['documentation_options']) : $documentation_options;
            return $options[$name];
        }
        /* global assign vars */
        function global_assign_vars()
        {
            global $template, $config;
            $template->assign_vars(array(
                'HOME_DOCUMENTATION'        => $this->get_options('section_home'),
                'PERMALINK_DOCUMENTATION'   => get_permanent_link('documentation')
            ));
            $get_option = (isset($config['documentation_options']))? maybe_unserialize($config['documentation_options']) : array() ;
            foreach($get_option as $key => $value)
            {
                $template->assign_var('DOCUOP_'.strtoupper($key), $value);
            }
        }
        /* add support post type */
        function supports_post_type($arg)
        {
            $support = array('documentation');
            return array_unique( array_merge( $support, (array) $arg ) );
        }
        /* add hook to display */
        function display_action_documentation($arg)
        {
            if(isset($arg['0']) and $arg['0'] == 'documentation')
            {
                $this->display_documentation($arg);
            }
        }
        /* display documentation */
        function display_documentation($arg)
        {
            global $db, $template;
            if($this->get_options('sidebar'))
            {
                $widget_categories          = $this->get_options('widget_categories');
                $widget_recent              = $this->get_options('widget_recent');
                $post_per_recent            = $this->get_options('post_per_recent');
                $widget_popular             = $this->get_options('widget_popular');
                $post_per_popular           = $this->get_options('post_per_popular');
                $widget_tags                = $this->get_options('widget_tags');

                // widget categories
                if($widget_categories)
                {
                    get_terms_loop(array('term_type' => 'documentation', 'assign_name' => 'terms_documentation', 'terms_link' => 'documentation/category'));
                }
                // get recent
                if($widget_recent)
                {
                    $result_recent = $db->sql_query("SELECT * FROM ".POSTS_TABLE." WHERE `post_status`='1' and `post_type`='documentation' ORDER BY post_modified DESC LIMIT {$post_per_recent}");
                    while($row_recent = $db->sql_fetchrow($result_recent))
                    {
                        $this->get_assign_block($row_recent, 'loop_recent_documentation');
                    }
                }
                // get popular
                if($widget_popular)
                {
                    $result_popular = $db->sql_query("SELECT * FROM ".POSTS_TABLE." JOIN ".POSTSMETA_TABLE." ON (`meta_key`='views') AND (`post_id`=`id`) WHERE `post_status`='1' and `post_type`='documentation' ORDER BY ABS(`".POSTSMETA_TABLE."`.`meta_value`) DESC LIMIT {$post_per_popular}");
                    while($row_popular = $db->sql_fetchrow($result_popular))
                    {
                        $this->get_assign_block($row_popular, 'loop_popular_documentation');
                    }
                }
                // get tags
                if($widget_tags)
                {
                    $result_tags = get_post_type_tags('documentation');
                    foreach($result_tags as $key => $count)
                    {
                        $template->assign_block_vars('loop_widget_tags', array(
                            'TAG_NAME'  => $key,
                            'TAG_COUNT' => $count,
                            'TAG_LINK'  => permanent_tags_link('documentation', trim($key)),
                        ));
                    }
                }
            } 
            
            if(isset($_GET['search']) and !empty($_GET['search']))
            {
                $this->index_search($arg);
            }
            elseif(isset($arg['1']) and $arg['1'] == 'category')
            {
                $term_id = get_term_column_by_slug(safe_input($arg['2']), 'id', 'documentation');
                $this->term_documentation($term_id, $arg);
            }
            elseif(isset($arg['1']) and $arg['1'] == 'tag')
            {
                $this->tags_documentation($arg);
            }
            elseif(isset($arg['1']) and $arg['1'] == 'post')
            {
                $post_id = (int) get_post_column_by_slug(safe_input($arg['2']), 'id', 'documentation');
                $this->post_documentation($post_id);
            }
            elseif(isset($arg['1']) and $arg['1'] != 'category')
            {
                location_lastpage("documentation");
            }
            else
            {
                $this->index_documentation($arg);
            }
        }
        /* term documentation */
        function term_documentation($term_id, $arg)
        {
            global $db, $config, $template, $lang;
            if(!has_term_found($term_id, 'documentation') or !get_count_posts_term($term_id))
            {
                location_lastpage("documentation");
            }
            $term_slug      = get_term_column($term_id, 'slug');
            $page           = (int) (isset($arg['3']) and $arg['3'] == 'page')? safe_input($arg['4']) : 1 ;
            $limit          = ($this->get_options('post_per_page'))? $this->get_options('post_per_page') : 10 ;
            $startpoint     = ($page * $limit) - $limit;
            $sql        = "SELECT * FROM ".POSTS_TABLE." JOIN ".POSTSMETA_TABLE." ON (`meta_key`='orders_post') AND (`post_id`=`id`) 
            WHERE `post_status`='1' and `post_type`='documentation' and `term_id`='{$term_id}' ORDER BY ABS(`meta_value`) ASC , post_modified DESC";
            $total      = $db->sql_numrows($sql);
            $lastpage   = ceil($total/$limit);
            if($lastpage < $page )
            {
                $urlpage = ($lastpage > 1)? "/page/{$lastpage}" : '';
                location_lastpage("documentation/category/{$term_slug}{$urlpage}");
            }
            $template->assign_var('PAGINATION', pagination($total,$limit,$page,"documentation/category/{$term_slug}"));
            $result     = $db->sql_query_limit($sql,$limit,$startpoint);
            while($row = $db->sql_fetchrow($result))
            {
                $this->get_assign_block($row , 'loop_documentation');
            }
            //All Categories
            $template->assign_vars(array(
                'ALL'               => true,
                'SHOW_PAGINATION'   => ($total > $limit)? true : false,
                'THISPAGE'          => $page,
                'OFPAGES'           => $lastpage,
                'NOTFOUND'          => $total,
                'CATEGORIES_TITLE'  => ($term_id)? get_term_column($term_id, 'name') : $lang['latest_posts'],
                'THE_CATEGORIE_ID'  => ($term_id)? $term_id : false, 
            ));
            page_header(array('page_title' => $lang['documentation'], 'pagedisplay' => 'documentation'));
            $template->set_filename('documentation/index_documentation.html');
            page_footer();
        }
        /* index documentation */
        function index_documentation($arg)
        {
            global $db, $config, $template, $lang;
            $post_per_page = ($this->get_options('post_per_page'))? $this->get_options('post_per_page') : 10 ;
            $sql    = "SELECT * FROM ".POSTS_TABLE." WHERE `post_status`='1' and `post_type`='documentation' ORDER BY post_modified DESC LIMIT {$post_per_page}";
            $result     = $db->sql_query($sql);
            while($row = $db->sql_fetchrow($result))
            {
                $this->get_assign_block($row , 'loop_documentation');
            }
            $template->assign_vars(array(
                'CATEGORIES_TITLE' => $lang['latest_posts'],
                'THE_CATEGORIE_ID' => false,
            ));
            page_header(array('page_title' => $lang['documentation'], 'pagedisplay' => 'documentation'));
            $template->set_filename('documentation/index_documentation.html');
            page_footer();
        }
        // post documentation
        function post_documentation($post_id)
        {
            global $db, $config, $template, $lang;
            $term_id = get_post_column($post_id, 'term_id');
            if(!has_post_found($post_id, 'documentation'))
            {
                location_lastpage("documentation");
            }
            $publish_status = get_publish_status($post_id);
            if(!$publish_status['status'])
            {
                get_index_publish_status($publish_status);
                exit;
            }
            $args_posts = array(
                'post_id'       => $post_id, 
                'location'      => location_lastpage("documentation", false), 
                'meta_key_view' => 'views', 
            );
            $row = get_assign_single_posts($args_posts);
            $this->get_assign_block($row, false);
            if($this->get_options('related'))
            {
                $post_per_related = $this->get_options('post_per_related');
                $sql_related        = "SELECT * FROM ".POSTS_TABLE." WHERE `post_status`='1' and `post_type`='documentation' and `term_id`='{$row['term_id']}' and `id`!='{$row['id']}' ORDER BY post_modified DESC LIMIT {$post_per_related}";
                $result_related     = $db->sql_query($sql_related);
                while($row_related = $db->sql_fetchrow($result_related))
                {
                    $this->get_assign_block($row_related, 'loop_documentation_related');
                }
                $is_related = $db->sql_numrows($sql_related);
            }
            else
            {
                $is_related = false;
            }
            $is_tags = get_assign_post_tags($post_id, 'documentation');
            //All Categories
            $template->assign_vars(array(
                'IS_POST_RELATED'           => $is_related,
                'IS_POST_META_OG'           => true,
                'CATEGORIES_TITLE'          => ($term_id)? get_term_column($term_id, 'name') : $lang['all_categories'] ,
                'CATEGORIES_PERMANETLINK'   => permanent_terms_link('documentation/category', $term_id, get_term_column($term_id, 'slug')),
                'THE_CATEGORIE_ID'          => ($term_id)? $term_id : false,
                'IS_TAGS'                   => $is_tags,
            ));
            page_header(array('page_title' => $lang['documentation'], 'pagedisplay' => 'documentation'));
            $template->set_filename('documentation/index_post_documentation.html');
            page_footer();
        }
        /* index search */
        function index_search($arg)
        {
            global $db, $config, $template, $lang;
            $search = safe_input($_GET['search']);
            $sql    = "SELECT * FROM ".POSTS_TABLE." WHERE `post_status`='1' and `post_type`='documentation' and (`post_title` LIKE '%{$search}%' or `post_content` LIKE '%{$search}%') ORDER BY post_modified DESC";
            $result     = $db->sql_query($sql);
            while($row = $db->sql_fetchrow($result))
            {
                $this->get_assign_block($row , 'loop_documentation');
            }
            $template->assign_vars(array(
                'CATEGORIES_TITLE' => $lang['search'],
                'THE_CATEGORIE_ID' => false,
                'SHOW_PAGINATION'  => false,
                'IS_SEARCH'        => true,
                'SEARCH_FOUND'     => $db->sql_numrows($sql),
                'SEARCH_TXT'       => $search,
            ));
            page_header(array('page_title' =>$lang['documentation'], 'pagedisplay' => 'documentation'));
            $template->set_filename('documentation/index_documentation.html');
            page_footer();
        }
        // tags documentation
        function tags_documentation($arg)
        {
            global $db, $template, $lang;
            $tag = safe_input($arg['2']); 
            if(empty($tag))
            {
                location_lastpage("documentation");
            }
            $sql        = "SELECT * FROM ".POSTS_TABLE."  JOIN ".POSTSMETA_TABLE." ON (`meta_key`='post_tags') AND (`post_id`=`id`)  WHERE `post_status`='1' and `post_type`='documentation' and `meta_value` LIKE '%{$tag}%' ORDER BY post_modified DESC";
            $result     = $db->sql_query($sql);
            while($row = $db->sql_fetchrow($result))
            {
                $this->get_assign_block($row, 'loop_documentation');
            }
            $template->assign_vars(array(
                'CATEGORIES_TITLE' => $lang['tags'],
                'THE_CATEGORIE_ID' => false,
                'SHOW_PAGINATION'  => false,
                'IS_SEARCH'        => true,
                'SEARCH_FOUND'     => $db->sql_numrows($sql),
                'TAG_NAME'         => $tag,
            ));
            page_header(array('page_title' => $lang['documentation'], 'pagedisplay' => 'documentation'));
            $template->set_filename('documentation/index_tags_documentation.html');
            page_footer();
        }
        /* home action */
        function home_action_documentation()
        {
            global $db, $config, $template, $hooks;
            $args_posts = array(
                'post_type'         => 'documentation', 
                'orderby'           => 'post_modified', 
                'orders_meta_key'   => 'orders_post', 
                'orders'            => 'DESC', 
                'per_page'          => ($this->get_options('home_post_per_page'))? $this->get_options('home_post_per_page') : 6,
            );
            $result = get_home_posts($args_posts);
            while($row = $db->sql_fetchrow($result))
            {
                $this->get_assign_block($row , 'loop_documentation');
            }
        }
        /* get assign block */
        function get_assign_block($row = array(), $assign_name = 'loop_documentation')
        {
            global $template, $db, $config;
            $assign['POST_ID']                   = $row['id'];
            $assign['POST_TITLE']                = get_post_title($row['post_title']); 
            $assign['POST_MODIFIED']             = get_date_time_format(date($config['dateformat'], $row['post_modified']));
            $assign['POST_TERM_NAME']            = get_term_column($row['term_id'], 'name');
            $assign['POST_TERM_PERMANETLINK']    = permanent_terms_link('documentation/category', $row['term_id'], get_term_column($row['term_id'], 'slug'));
            $assign['POST_PERMANETLINK']         = permanent_post_link('documentation/post',$row['id']);
            $assign['POST_VERSION']              = get_post_meta($row['id'], 'version','1.0');
            $assign['POST_ONLINEDOCUMENT']       = get_post_meta($row['id'], 'onlinedocument','#');
            $assign['POST_DOWNLOAD']             = get_post_meta($row['id'], 'download','#');
            $assign['POST_AUTHOR']               = get_user_column($row['post_author'],'username'); 
            if($assign_name)
            {
                $template->assign_block_vars($assign_name, $assign);
            }
            else
            {
                $assign['POST_CONTENT']              = get_post_content($row['post_content']);
				$assign['POST_AUTHOR_PERMANETLINK']  = permanent_user_link('documentation/author',$row['post_author']);
                $assign['POST_VIEW']                 = number_abbreviation(get_post_meta($row['id'], 'views', '0'));
                $assign['POST_COMMENT_STATS']        = $row['comment_status'];
                
                $template->assign_vars($assign);
            }
        }
    }
    /* End class */
    
    /* display class */
    new extensions_documentation();
}
?>
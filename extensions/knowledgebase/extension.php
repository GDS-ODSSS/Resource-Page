<?php
/**
 * Extension Name: knowledge base
 * Extension URI: http://nawaaugustine.com/php_help_manager/extension/knowledgebase
 * Version: 2.0
 * Requires: 2.0
 * Description: knowledge base
 * Author: Augustine Nawa
 * Author URI: http://nawaaugustine.com
*/

if (!defined("IN_PHPMEGATEMP"))
      die('Direct access to this location is not allowed.');

if(!class_exists('extensions_knowledgebase'))
{
    include('extension_admin.php');
    class extensions_knowledgebase extends extensions_admin_knowledgebase
    {
        // construct
        function __construct()
        {
            global $hooks;
            // action site
            $hooks->add_action('global_assign_vars', array($this , 'global_assign_vars'),1);
            $hooks->add_action('index_home_action', array($this , 'home_action_knowledgebase'),1);
            $hooks->add_action('index_post_type_display', array($this , 'display_action_knowledgebase'),1);
            $hooks->add_filter('supports_actions_post_type', array($this , 'supports_post_type'), 1);
            // display admin
            if(defined('IN_PHPMEGATEMP_CP'))
            {
                $this->display_admin_knowledgebase();
            }
        }
        /* get option ex*/
        function get_options($name)
        {
            global $config;
            $knowledgebase_options = array(
                'box_home'              => '1',
                'column'                => 'col-md-3',
                'classes'               => '',
                'title'                 => 'Knowledge Base', 
                'icon'                  => '', 
                'description'           => '',
                'text_link'             => '',
                'section_home'          => '1', 
                'home_post_per_page'    => '10', 
                'post_per_page'         => '10',
                'post_per_page'         => '10',
            );
            $options = ($config['knowledgebase_options'])? maybe_unserialize($config['knowledgebase_options']) : $knowledgebase_options;
            return $options[$name];
        }
        // global assign vars
        function global_assign_vars()
        {
            global $template, $config;
            $template->assign_vars(array(
                'HOME_KNOWLEDGEBASE'        => $this->get_options('section_home'),
                'PERMALINK_KNOWLEDGEBASE'   => get_permanent_link('knowledgebase')
            ));
            $get_option = (isset($config['knowledgebase_options']))? maybe_unserialize($config['knowledgebase_options']) : array() ;
            foreach($get_option as $key => $value)
            {
                $template->assign_var('KNOWOP_'.strtoupper($key), $value);
            }
        }
        // add support post type
        function supports_post_type($arg)
        {
            $support = array('knowledgebase');
            return array_unique( array_merge( $support, (array) $arg ) );
        }
        // display action knowledgebase
        function display_action_knowledgebase($arg)
        {
            global $db, $config, $template, $hooks;
            if(isset($arg['0']) and $arg['0'] == 'knowledgebase')
            {
                $this->display_knowledgebase($arg);
            }
        }
        // display knowledgebase
        function display_knowledgebase($arg)
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
                    get_terms_loop(array('term_type' => 'knowledgebase', 'assign_name' => 'widget_terms_knowledgebase', 'terms_link' => 'knowledgebase/category'));
                }
                // get recent
                if($widget_recent)
                {
                    $result_recent = $db->sql_query("SELECT * FROM ".POSTS_TABLE." WHERE `post_status`='1' and `post_type`='knowledgebase' ORDER BY post_modified DESC LIMIT {$post_per_recent}");
                    
                    while($row_recent = $db->sql_fetchrow($result_recent))
                    {
                        $this->get_assign_block($row_recent, 'loop_recent_knowledgebase', true);
                    }
                }
                // get popular
                if($widget_popular)
                {
                    $result_popular = $db->sql_query("SELECT * FROM ".POSTS_TABLE." JOIN ".POSTSMETA_TABLE." ON (`meta_key`='views') AND (`post_id`=`id`) WHERE `post_status`='1' and `post_type`='knowledgebase' ORDER BY ABS(`".POSTSMETA_TABLE."`.`meta_value`) DESC LIMIT {$post_per_popular}");
                    while($row_popular = $db->sql_fetchrow($result_popular))
                    {
                        $this->get_assign_block($row_popular, 'loop_popular_knowledgebase', true);
                    }
                }
                // get tags
                if($widget_tags)
                {
                    $result_tags = get_post_type_tags('knowledgebase');
                    foreach($result_tags as $key => $count)
                    {
                        $template->assign_block_vars('loop_widget_tags', array(
                            'TAG_NAME'  => $key,
                            'TAG_COUNT' => $count,
                            'TAG_LINK'  => permanent_tags_link('knowledgebase', trim($key)),
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
                $term_id = get_term_column_by_slug(safe_input($arg['2']), 'id', 'knowledgebase');
                $this->term_knowledgebase($term_id, $arg);
            }
            elseif(isset($arg['1']) and $arg['1'] == 'tag')
            {
                $this->tags_knowledgebase($arg);
            }
            elseif(isset($arg['1']) and $arg['1'] == 'post')
            {
                $post_id = (int) get_post_column_by_slug(safe_input($arg['2']), 'id', 'knowledgebase');
                $this->post_knowledgebase($post_id);
            }
            elseif(isset($arg['1']) and !in_array($arg['1'], array('tag', 'category')))
            {
                location_lastpage("knowledgebase");
            }
            else
            {
                $this->index_knowledgebase($arg);
            }
        }
        // home action knowledgebase
        function home_action_knowledgebase()
        {
            global $db, $template;
            $terms_home = ($this->get_options('terms_home'))? $this->get_options('terms_home') : array();
            $result_terms = get_terms_loop(array('term_type' => 'knowledgebase'));
            while($row_terms = $db->sql_fetchrow($result_terms))
            {
                if(get_count_posts_term($row_terms['id']) and in_array($row_terms['id'], $terms_home))
                {
                    $template->assign_block_vars('terms_knowledgebase', array( 
                        'TERM_ID'               => $row_terms['id'],
                        'TERM_NAME'             => $row_terms['name'], 
                        'TERM_SLUG'             => $row_terms['slug'],
                        'TERM_COUNT_POSTS'      => get_count_posts_term($row_terms['id']),
                        'TERM_PERMANENT_LINK'   => permanent_terms_link('knowledgebase/category',$row_terms['id'],$row_terms['slug']),
                    ));
                    $args_posts = array(
                        'post_type'     => 'knowledgebase', 
                        'orderby'       => 'post_modified', 
                        'orders'        => 'DESC', 
                        'page_url'      => 'none', 
                        'term_id'       => $row_terms['id'],
                        'per_page'      => ($this->get_options('home_post_per_page'))? $this->get_options('home_post_per_page') : 6,
                    );
                    $result = get_home_posts($args_posts);
                    while($row = $db->sql_fetchrow($result))
                    {
                        $this->get_assign_block($row, 'terms_knowledgebase.loop_knowledgebase');
                    }
                }   
            }
        }
        // post knowledgebase
        function post_knowledgebase($post_id)
        {
            global $db, $config, $template, $lang;
            $term_id = get_post_column($post_id, 'term_id');
            if(!has_post_found($post_id, 'knowledgebase'))
            {
                location_lastpage("knowledgebase");
            }

            $publish_status = get_publish_status($post_id);
            if(!$publish_status['status'])
            {
                get_index_publish_status($publish_status);
                exit;
            }
            
            $args_posts = array(
                'post_id'       => $post_id, 
                'location'      => location_lastpage("knowledgebase", false), 
                'meta_key_view' => 'views', 
            );
            $row = get_assign_single_posts($args_posts);
            $this->get_assign_block($row, false);
            $is_tags = get_assign_post_tags($post_id, 'knowledgebase');

            if($this->get_options('related'))
            {
                $post_per_related = $this->get_options('post_per_related');
                $sql_related        = "SELECT * FROM ".POSTS_TABLE." WHERE `post_status`='1' and `post_type`='knowledgebase' and `term_id`='{$row['term_id']}' and `id`!='{$row['id']}' ORDER BY post_modified DESC LIMIT {$post_per_related}";
                $result_related     = $db->sql_query($sql_related);
                while($row_related = $db->sql_fetchrow($result_related))
                {
                    $this->get_assign_block($row_related, 'loop_knowledgebase_related');
                }
                $is_related = $db->sql_numrows($sql_related);
            }
            else
            {
                $is_related = false;
            }
            //All Categories
            $template->assign_vars(array(
                'IS_POST_RELATED'           => $is_related,
                'IS_POST_META_OG'           => true,
                'CATEGORIES_TITLE'          => ($term_id)? get_term_column($term_id, 'name') : $lang['all_categories'],
                'CATEGORIES_PERMANETLINK'   => permanent_terms_link('knowledgebase/category', $term_id, get_term_column($term_id, 'slug')),
                'THE_CATEGORIE_ID'          => ($term_id)? $term_id : false,
                'GET_POST_TAGS'             => get_post_meta( $post_id,'post_tags', ''),
                'IS_TAGS'                   => $is_tags,
            ));
            page_header(array('page_title' => $lang['knowledgebase'], 'pagedisplay' => 'knowledgebase'));
            $template->set_filename('knowledgebase/index_post_knowledgebase.html');
            page_footer();
        }
        // term knowledgebase
        function term_knowledgebase($term_id, $arg)
        {
            global $db, $template, $lang;
            if(!has_term_found($term_id, 'knowledgebase') or !get_count_posts_term($term_id))
            {
                location_lastpage("knowledgebase");
            }
            
            $term_slug      = get_term_column($term_id, 'slug');
            $page           = (int) (isset($arg['3']) and $arg['3'] == 'page')? safe_input($arg['4']) : 1 ;
            $limit          = ($this->get_options('cate_per_page'))? $this->get_options('cate_per_page') : 15;
            $startpoint     = ($page * $limit) - $limit;
            $sql = "SELECT * FROM ".POSTS_TABLE." WHERE `post_status`='1' and `post_type`='knowledgebase' and `term_id`='{$term_id}' ORDER BY post_modified DESC";
            $total      = $db->sql_numrows($sql);
            $lastpage   = ceil($total/$limit);
            if($lastpage < $page )
            {
                $urlpage = ($lastpage > 1)? "/page/{$lastpage}" : '';
                location_lastpage("knowledgebase/category/{$term_slug}{$urlpage}");
            }
            $template->assign_var('PAGINATION', pagination($total,$limit,$page,"knowledgebase/category/{$term_slug}"));
            $result = $db->sql_query_limit($sql,$limit,$startpoint);
            while($row = $db->sql_fetchrow($result))
            {
                $this->get_assign_block($row, 'loop_knowledgebase');
            }
            //All Categories
            $template->assign_vars(array(
                'ALL'               => true,
                'SHOW_PAGINATION'   => ($total > $limit)? true : false,
                'THISPAGE'          => $page,
                'OFPAGES'           => $lastpage,
                'NOTFOUND'          => $total,
                'CATEGORIES_TITLE'  => ($term_id)? get_term_column($term_id, 'name') : $lang['all_categories'],
                'THE_CATEGORIE_ID'  => ($term_id)? $term_id : false, 
                'CATEGORIES_PERMANETLINK'   => permanent_terms_link('knowledgebase/post', $term_id, get_term_column($term_id, 'slug')),
            ));
            page_header(array('page_title' => $lang['knowledgebase'], 'pagedisplay' => 'knowledgebase'));
            $template->set_filename('knowledgebase/index_term_knowledgebase.html');
            page_footer();
        }

        // index knowledgebase
        function index_knowledgebase($arg)
        {
            global $db, $template, $lang;
            $result_terms = get_terms_loop(array('term_type' => 'knowledgebase'));
            while($row_terms = $db->sql_fetchrow($result_terms))
            {
                if(get_count_posts_term($row_terms['id']))
                {
                    $template->assign_block_vars('terms_knowledgebase', array( 
                        'TERM_ID'               => $row_terms['id'],
                        'TERM_NAME'             => $row_terms['name'], 
                        'TERM_SLUG'             => $row_terms['slug'],
                        'TERM_COUNT_POSTS'      => get_count_posts_term($row_terms['id']),
                        'TERM_PERMANENT_LINK'   => permanent_terms_link('knowledgebase/category',$row_terms['id'],$row_terms['slug']),
                    ));
                    $args_posts = array(
                        'post_type'     => 'knowledgebase', 
                        'orderby'       => 'post_modified', 
                        'orders'        => 'DESC', 
                        'page_url'      => 'none', 
                        'term_id'       => $row_terms['id'],
                        'per_page'      => ($this->get_options('post_per_page'))? $this->get_options('post_per_page') : 6,
                    );
                    $result = get_home_posts($args_posts);
                    while($row = $db->sql_fetchrow($result))
                    {
                        $this->get_assign_block($row, 'terms_knowledgebase.loop_knowledgebase');
                    }
                }
            }
            page_header(array('page_title' => $lang['knowledgebase'], 'pagedisplay' => 'knowledgebase'));
            $template->set_filename('knowledgebase/index_knowledgebase.html');
            page_footer();
        }
        // tags knowledgebase
        function tags_knowledgebase($arg)
        {
            global $db, $template, $lang;
            $tag = safe_input($arg['2']); 
            if(empty($tag))
            {
                location_lastpage("knowledgebase");
            }
            $sql        = "SELECT * FROM ".POSTS_TABLE."  JOIN ".POSTSMETA_TABLE." ON (`meta_key`='post_tags') AND (`post_id`=`id`)  WHERE `post_status`='1' and `post_type`='knowledgebase' and `meta_value` LIKE '%{$tag}%' ORDER BY post_modified DESC";
            $result     = $db->sql_query($sql);
            while($row = $db->sql_fetchrow($result))
            {
                $this->get_assign_block($row, 'loop_knowledgebase');
            }
            $template->assign_vars(array(
                'CATEGORIES_TITLE' => $lang['tags'],
                'THE_CATEGORIE_ID' => false,
                'SHOW_PAGINATION'  => false,
                'IS_SEARCH'        => true,
                'SEARCH_FOUND'     => $db->sql_numrows($sql),
                'TAG_NAME'         => $tag,
            ));
            page_header(array('page_title' => $lang['knowledgebase'], 'pagedisplay' => 'knowledgebase'));
            $template->set_filename('knowledgebase/index_tags_knowledgebase.html');
            page_footer();
        }
        // index search
        function index_search($arg)
        {
            global $db, $template, $lang;
            $search = safe_input($_GET['search']);
            
            $sql    = "SELECT * FROM ".POSTS_TABLE." WHERE `post_status`='1' and `post_type`='knowledgebase' and (`post_title` LIKE '%{$search}%' or `post_content` LIKE '%{$search}%') ORDER BY post_modified DESC";
            $result     = $db->sql_query($sql);
            while($row = $db->sql_fetchrow($result))
            {
                $this->get_assign_block($row, 'loop_knowledgebase');
            }
            $template->assign_vars(array(
                'CATEGORIES_TITLE' => $lang['search'],
                'THE_CATEGORIE_ID' => false,
                'SHOW_PAGINATION'  => false,
                'IS_SEARCH'        => true,
                'SEARCH_FOUND'     => $db->sql_numrows($sql),
                'SEARCH_TXT'       => $search,
            ));
            page_header(array('page_title' => $lang['knowledgebase'], 'pagedisplay' => 'knowledgebase'));
            $template->set_filename('knowledgebase/index_term_knowledgebase.html');
            page_footer();
        }
        /* get assign block */
        function get_assign_block($row = array(), $assign_name = 'loop_knowledgebase', $widget = false)
        {
            global $template, $db, $config;
            if($widget)
            {
                $assign['POST_ID']                   = $row['id'];
                $assign['POST_TITLE']                = get_post_title($row['post_title']);
                $assign['POST_PERMANETLINK']         = permanent_post_link('knowledgebase/post',$row['id']);
            }
            else
            {
                $assign['POST_ID']                   = $row['id'];
                $assign['POST_TITLE']                = get_post_title($row['post_title']); 
                $assign['POST_MODIFIED']             = get_date_time_format(date($config['dateformat'], $row['post_modified']));
                $assign['POST_TIME_AGO']             = get_timeago($row['post_modified']);
                $assign['POST_TERM_NAME']            = get_term_column($row['term_id'], 'name');
                $assign['POST_TERM_PERMANETLINK']    = permanent_terms_link('knowledgebase/category', $row['term_id'], get_term_column($row['term_id'], 'slug'));
                $assign['POST_PERMANETLINK']         = permanent_post_link('knowledgebase/post',$row['id']);
                $assign['POST_AUTHOR']               = get_user_column($row['post_author'],'username'); 
                $assign['POST_VIEW']                 = number_abbreviation(get_post_meta($row['id'], 'views', '0'));
            }
            
            if($assign_name)
            {
                $template->assign_block_vars($assign_name, $assign);
            }
            else
            {
                $assign['POST_CONTENT']              = get_post_content($row['post_content']);
				$assign['POST_AUTHOR_PERMANETLINK']  = permanent_user_link('knowledgebase/author',$row['post_author']);
                $assign['POST_COMMENT_STATS']        = $row['comment_status'];
                $template->assign_vars($assign);
            }
        }
    }
    // End class
    // display class
    new extensions_knowledgebase();
}
?>
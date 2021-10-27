<?php
/**
 * Extension Name: News
 * Extension URI: http://nawaaugustine.com/php_help_manager/extension/news
 * Version: 2.0
 * Requires: 2.0
 * Description: News
 * Author: Augustine Nawa
 * Author URI: http://nawaaugustine.com
*/

if (!defined("IN_PHPMEGATEMP"))
      die('Direct access to this location is not allowed.');

if(!class_exists('extensions_news'))
{
    // extensions_admin_news
    include('extension_admin.php');
    class extensions_news extends extensions_admin_news
    {
        // construct
        function __construct()
        {
            global $hooks;
            // action site
            $hooks->add_action('index_home_action', array($this , 'home_action_news'),1);
            $hooks->add_action('index_post_type_display', array($this , 'display_action_news'),1);
            $hooks->add_action('global_assign_vars', array($this , 'global_assign_vars'),1);
            $hooks->add_filter('supports_actions_post_type', array($this , 'supports_post_type'), 1);
            // display admin
            if(defined('IN_PHPMEGATEMP_CP'))
            {
                $this->display_admin_news();
            }
        }
        /* get option ex*/
        function get_options($name)
        {
            global $config;
            $news_options = array(
                'section_home'          => '1', 
                'post_per_page'         => '10',
                'post_per_page'         => '10',
                'terms_home'       => array(),
            );
            $options = ($config['news_options'])? maybe_unserialize($config['news_options']) : $news_options;
            return $options[$name];
        }
        /* global assign vars */
        function global_assign_vars()
        {
            global $template, $config;
            $template->assign_vars(array(
                'HOME_NEWS'        => $this->get_options('section_home'),
                'PERMALINK_NEWS'   => get_permanent_link('news')
            ));
            $get_option = (isset($config['news_options']))? maybe_unserialize($config['news_options']) : array() ;
            foreach($get_option as $key => $value)
            {
                $template->assign_var('NEWSOP_'.strtoupper($key), $value);
            }
        }
        // supports post type
        function supports_post_type($arg)
        {
            $support = array('news');
            return array_unique( array_merge( $support, (array) $arg ) );
        }
        // display action news
        function display_action_news($arg)
        {
            global $db, $config, $template, $hooks;
            if(isset($arg['0']) and $arg['0'] == 'news')
            {
                $this->display_news($arg);
            }
        }
        // display news
        function display_news($arg)
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
                    get_terms_loop(array('term_type' => 'news', 'assign_name' => 'terms_news', 'terms_link' => 'news/category'));
                }
                // get recent
                if($widget_recent)
                {
                    $result_recent = $db->sql_query("SELECT * FROM ".POSTS_TABLE." WHERE `post_status`='1' and `post_type`='news' ORDER BY post_modified DESC LIMIT {$post_per_recent}");
                    while($row_recent = $db->sql_fetchrow($result_recent))
                    {
                        $this->get_assign_block($row_recent, 'loop_recent_news', true);
                    }
                }
                // get popular
                if($widget_popular)
                {
                    $result_popular = $db->sql_query("SELECT * FROM ".POSTS_TABLE." JOIN ".POSTSMETA_TABLE." ON (`meta_key`='views') AND (`post_id`=`id`) WHERE `post_status`='1' and `post_type`='news' ORDER BY ABS(`".POSTSMETA_TABLE."`.`meta_value`) DESC LIMIT {$post_per_popular}");
                    while($row_popular = $db->sql_fetchrow($result_popular))
                    {
                        $this->get_assign_block($row_popular, 'loop_popular_news', true);
                    }
                }
                // get tags
                if($widget_tags)
                {
                    $result_tags = get_post_type_tags('news');
                    foreach($result_tags as $key => $count)
                    {
                        $template->assign_block_vars('loop_widget_tags', array(
                            'TAG_NAME'  => $key,
                            'TAG_COUNT' => $count,
                            'TAG_LINK'  => permanent_tags_link('news', trim($key)),
                        ));
                    }
                }
            }
            
            if(isset($_GET['search']) and !empty($_GET['search']))
            {
                $this->index_search($arg);
            }
            elseif(isset($arg['1']) and $arg['1'] == 'tag')
            {
                $this->tags_news($arg);
            }
            elseif(isset($arg['1']) and $arg['1'] == 'post')
            {
                $post_id = (int) get_post_column_by_slug(safe_input($arg['2']), 'id', 'news');
                $this->post_news($post_id);
            }
            else
            {
                $this->index_news($arg);
            }
        }
        // index news
        function index_news($arg)
        {
            global $db, $template, $lang;
			$template->assign_var('PAGE_AUTHOR', false);
            $term_id    = (int) (isset($arg['1']) and $arg['1'] == 'category')? get_term_column_by_slug(safe_input($arg['2']), 'id') : false ;
            $user_id    = (int) (isset($arg['1']) and $arg['1'] == 'author')? get_userid_by_column('username', safe_input($arg['2'])) : false ;
            $limit      = ($this->get_options('post_per_page'))? $this->get_options('post_per_page') : '10';
            if($term_id)
            {
                $term_slug      = $arg['2'];
                $page           = (int) (isset($arg['3']) and $arg['3'] == 'page')? safe_input($arg['4']) : 1 ;
                //$limit          = ($config['per_page'])? $config['per_page'] : 10;
                $startpoint     = ($page * $limit) - $limit;
                $sql            = "SELECT * FROM ".POSTS_TABLE." WHERE `post_status`='1' and `post_type`='news' and `term_id`='{$term_id}' ORDER BY post_modified DESC";
                $total          = $db->sql_numrows($sql);
                $lastpage       = ceil($total/$limit);
                if($lastpage < $page )
                {
                    location_lastpage("news/category/{$term_slug}/page/{$lastpage}");
                }
                $template->assign_var('PAGINATION', pagination($total,$limit,$page,"news/category/{$term_slug}"));
            }
			elseif($user_id)
            {
                $page           = (int) (isset($arg['3']) and $arg['3'] == 'page')? safe_input($arg['4']) : 1 ;
                //$limit          = ($config['per_page'])? $config['per_page'] : 10;
                $startpoint     = ($page * $limit) - $limit;
                $sql            = "SELECT * FROM ".POSTS_TABLE." WHERE `post_status`='1' and `post_type`='news' and `post_author`='{$user_id}' ORDER BY post_modified DESC";
                $total          = $db->sql_numrows($sql);
                $lastpage       = ceil($total/$limit);
                if($lastpage < $page )
                {
                    location_lastpage("news/author/{$arg['2']}/page/{$lastpage}");
                }
                $template->assign_var('PAGINATION', pagination($total,$limit,$page,"news/author/{$arg['2']}"));
				$template->assign_var('PAGE_AUTHOR', true);
            }
            else
            {
                $page           = (int) (isset($arg['1']) and $arg['1'] == 'page')? safe_input($arg['2']) : 1 ;
                //$limit          = ($config['per_page'])? $config['per_page'] : 10;
                $startpoint     = ($page * $limit) - $limit;
                $sql = "SELECT * FROM ".POSTS_TABLE." WHERE `post_status`='1' and `post_type`='news' ORDER BY post_modified DESC";
                $total      = $db->sql_numrows($sql);
                $lastpage   = ceil($total/$limit);
                if($lastpage < $page )
                {
                    location_lastpage("news/page/{$lastpage}");
                }
                $template->assign_var('PAGINATION', pagination($total,$limit,$page,"news"));
            }
            $result     = $db->sql_query_limit($sql,$limit,$startpoint);
            while($row = $db->sql_fetchrow($result))
            {
                $this->get_assign_block($row, 'loop_news');
            }
			
			if($term_id)
			{
				$ctitle = get_term_column($term_id, 'name');
			}
			elseif($user_id)
			{
				$ctitle = $arg['2'];
				get_user_date($user_id, 'assign', 'POST_USER_');
			}
			else
			{
				$ctitle = $lang['latest_posts'];
			}
            $template->assign_vars(array(
                'ALL'               		=> true,
                'SHOW_PAGINATION'   		=> ($total > $limit)? true : false,
                'THISPAGE'          		=> $page,
                'OFPAGES'           		=> $lastpage,
                'NOTFOUND'          		=> $total,
                'IS_POST_META_OG'           => true,
                'CATEGORIES_TITLE'          => $ctitle,
                'CATEGORIES_PERMANETLINK'   => permanent_terms_link('news/category', $term_id, get_term_column($term_id, 'slug')),
                'THE_CATEGORIE_ID'          => ($term_id)? $term_id : false,
            ));
            page_header(array('page_title' => $lang['news'], 'pagedisplay' => 'news'));
            $template->set_filename('news/index_news.html');
            page_footer();
        }
        // post news
        function post_news($post_id)
        {
            global $db, $template, $lang;
            $term_id = get_post_column($post_id, 'term_id');
            if(!has_post_found($post_id, 'news'))
            {
                location_lastpage("news");
            }
            $publish_status = get_publish_status($post_id);
            if(!$publish_status['status'])
            {
                get_index_publish_status($publish_status);
                exit;
            }
            $args_posts = array(
                'post_id'       => $post_id, 
                'location'      => location_lastpage("news", false), 
                'meta_key_view' => 'views', 
            );
            $row = get_assign_single_posts($args_posts);
            $this->get_assign_block($row, false);
            $is_tags = get_assign_post_tags($post_id, 'news');
            // news related
            if($this->get_options('related'))
            {
                $post_per_related = $this->get_options('post_per_related');
                $sql_related        = "SELECT * FROM ".POSTS_TABLE." WHERE `post_status`='1' and `post_type`='news' and `term_id`='{$row['term_id']}' and `id`!='{$row['id']}' ORDER BY post_modified DESC LIMIT {$post_per_related}";
                $result_related     = $db->sql_query($sql_related);
                while($row_related = $db->sql_fetchrow($result_related))
                {
                    $this->get_assign_block($row_related, 'loop_news_related');
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
                'CATEGORIES_TITLE'          => ($term_id)? get_term_column($term_id, 'name') :  $lang['all_categories'],
                'CATEGORIES_PERMANETLINK'   => permanent_terms_link('news/category', $term_id, get_term_column($term_id, 'slug')),
                'THE_CATEGORIE_ID'          => ($term_id)? $term_id : false,
                'IS_TAGS'                   => $is_tags
            ));
            page_header(array('page_title' => $lang['news'], 'pagedisplay' => 'news'));
            $template->set_filename('news/index_post_news.html');
            page_footer();
        }
        // home action news
        function home_action_news()
        {
            global $db, $config, $template;
            $terms_home     = ($this->get_options('terms_home'))? $this->get_options('terms_home') : '';
            $terms_par_page = ($this->get_options('home_post_per_page'))? $this->get_options('home_post_per_page') : '6';
            $args_posts = array(
                'post_type'         => 'news', 
                'term_id'           => $terms_home,
                'orderby'           => 'post_modified', 
                'orders'            => 'DESC', 
                'orders_meta_key'   => 'none',
                'per_page'          => $terms_par_page,
            );
            $result = get_home_posts($args_posts);
            while($row = $db->sql_fetchrow($result))
            {
                $this->get_assign_block($row, 'loop_news');
            }
        }
        // index search
        function index_search($arg)
        {
            global $db, $template, $lang;
            $search = safe_input($_GET['search']);
            $sql    = "SELECT * FROM ".POSTS_TABLE." WHERE `post_status`='1' and `post_type`='news' and (`post_title` LIKE '%{$search}%' or `post_content` LIKE '%{$search}%') ORDER BY post_modified DESC";
            $result     = $db->sql_query($sql);
            while($row = $db->sql_fetchrow($result))
            {
                $this->get_assign_block($row, 'loop_news');
            }
            $template->assign_vars(array(
                'CATEGORIES_TITLE' => $lang['search'],
                'THE_CATEGORIE_ID' => false,
                'SHOW_PAGINATION'  => false,
                'IS_SEARCH'        => true,
                'SEARCH_FOUND'     => $db->sql_numrows($sql),
                'SEARCH_TXT'       => $search,
            ));
            page_header(array('page_title' => $lang['news'], 'pagedisplay' => 'news'));
            $template->set_filename('news/index_news.html');
            page_footer();
        }
        // tags news
        function tags_news($arg)
        {
            global $db, $template, $lang;
            $tag = safe_input($arg['2']); 
            if(empty($tag))
            {
                location_lastpage("news");
            }
            $sql        = "SELECT * FROM ".POSTS_TABLE."  JOIN ".POSTSMETA_TABLE." ON (`meta_key`='post_tags') AND (`post_id`=`id`)  WHERE `post_status`='1' and `post_type`='news' and `meta_value` LIKE '%{$tag}%' ORDER BY post_modified DESC";
            $result     = $db->sql_query($sql);
            while($row = $db->sql_fetchrow($result))
            {
                $this->get_assign_block($row, 'loop_news');
            }
            $template->assign_vars(array(
                'CATEGORIES_TITLE' => $lang['tags'],
                'THE_CATEGORIE_ID' => false,
                'SHOW_PAGINATION'  => false,
                'IS_SEARCH'        => true,
                'SEARCH_FOUND'     => $db->sql_numrows($sql),
                'TAG_NAME'         => $tag,
            ));
            page_header(array('page_title' => $lang['news'], 'pagedisplay' => 'news'));
            $template->set_filename('news/index_tags_news.html');
            page_footer();
        }
        /* get assign block */
        function get_assign_block($row = array(), $assign_name = 'loop_news', $widget = false)
        {
            global $template, $db, $config;
            $assign['POST_ID']                  = $row['id'];
            $assign['POST_TITLE']               = get_post_title($row['post_title']);
            if($widget)
            {
                $assign['POST_THUMB']           = get_post_attachment($row['id'], 'thumbnail');
            }
            else
            {
                $assign['POST_SHORT']           = accesspress_letter_count(get_post_content($row['post_content']), 180);
                $assign['POST_THUMB_M']         = get_post_attachment($row['id'], 'medium');
                $assign['POST_VIEW']            = number_abbreviation(get_post_meta($row['id'], 'views', '0'));
                $assign['POST_TERM_NAME']           = get_term_column($row['term_id'], 'name');
                $assign['POST_TERM_PERMANETLINK']   = permanent_terms_link('news/category', $row['term_id'], get_term_column($row['term_id'], 'slug'));
            }
            $assign['POST_PERMANETLINK']        = permanent_post_link('news/post',$row['id']);
            $assign['POST_MODIFIED']            = get_date_time_format(date($config['dateformat'], $row['post_modified']));
            if($assign_name)
            {
                $template->assign_block_vars($assign_name, $assign);
            }
            else
            {
                $assign['POST_TERM_ID']             = $row['term_id'];
                //$assign['POST_TERM_SLUG']           = get_term_column($row['term_id'], 'slug');
				$assign['POST_CONTENT']             = get_post_content($row['post_content']);
				$assign['POST_AUTHOR']              = get_user_column($row['post_author'],'username');
				$assign['POST_AUTHOR_PERMANETLINK'] = permanent_user_link('news/author',$row['post_author']);
                $assign['POST_THUMB_FULL']          = get_post_attachment($row['id'], '');
                $assign['POST_COMMENT_STATS']       = $row['comment_status'];
                $template->assign_vars($assign);
            }
        }
    }
    new extensions_news();
}
?>
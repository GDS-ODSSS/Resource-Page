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
if(!defined('IN_PHPMEGATEMP')){exit;}

if(!class_exists('admin_post_type_page'))
{
    class admin_post_type_page
    {
        // class constructor
        function __construct()
        {
            global $hooks;
            // action admin global
            $hooks->add_action('admin_menu_dropdown_new', array($this , 'display_menu_dropdown_new'), 3);
            // add action admin post page
            $hooks->add_action('admin_display_body_content_post_page', array($this , 'display_body_content_post'), 1);
            $hooks->add_action('admin_display_add_content_post_page', array($this , 'display_body_form_add_content_post'), 1);
            $hooks->add_action('admin_display_edit_content_post_page', array($this , 'display_body_form_edit_content_post'), 1);
            $hooks->add_action('admin_display_update_post_meta_page', array($this , 'display_update_post_meta_page'), 1);
            // action admin terms page
            $hooks->add_action('admin_display_body_content_terms_page', array($this , 'display_body_content_terms_page'), 1);
            $hooks->add_action('admin_display_update_term_meta_page', array($this , 'display_update_term_meta_page'), 1);
            // action admin comments
            $hooks->add_action('admin_display_body_content_comment_page', array($this , 'display_body_content_comment'), 1);
            $hooks->add_action('admin_display_edit_content_comment_page', array($this , 'display_body_form_edit_content_comment'), 1);
            // add filter admin post page
            $hooks->add_filter('admin_display_supports_post_th_page', array($this , 'display_supports_post_th'), 1);
            $hooks->add_filter('admin_display_supports_post_td_page', array($this , 'display_supports_post_td'), 1);
            
            $hooks->add_action('admin_nav_menu_side_control_section', array($this , 'nav_menu_side_control_section'), 1);
        }
        
        function nav_menu_side_control_section()
        {
            global $admin_settings, $db;
            $html_list  = $admin_settings->get_menu_item_li(array(
                    'id'        => 'home',
                    'title'     => 'Home',
                    'url'       => '',
                    'icon'      => 'fas fa-home',
                    'linkphp'   => 'index.php',
                    'linkhtml'  => 'index.html',
                ));
            $result     = $db->sql_query("SELECT * FROM ".POSTS_TABLE." WHERE `post_type`='page' ORDER BY post_title ASC");
            while ($row = $db->sql_fetchrow($result))
            {
                $html_list .= $admin_settings->get_menu_item_li(array(
                    'id'        => $row['id'],
                    'title'     => $row['post_title'],
                    'url'       => 'page/'.$row['post_name'],
                    'icon'      => '',
                    'linkphp'   => 'index.php?page_id='.$row['id'],
                    'linkhtml'  => 'page-'.$row['id'].'.html'
                ));
            }
            echo $admin_settings->get_nav_menu_side(array(
                'title'         => get_admin_languages('page'),
                'html_list'     => $html_list, 
                'btn_data'      => 'page',
            ));
        }
        
        // display menu dropdown new
        function display_menu_dropdown_new()
        {
            global $db, $config, $template, $language, $hooks;
            echo '<li><a href="posts.php?post_type=page&mode=new">'.get_admin_languages('page').'</a></li>';
        }
        // display supports th
        function display_supports_post_th($arg)
        {
            $th['views'] = '<th style="width: 90px;text-align: center;" class="hidden-phone">'.get_admin_languages('views').'</th>';
            return array_unique( array_merge( $th, (array) $arg ) );
        }
        // display supports td
        function display_supports_post_td($arg)
        {
            $views  = get_post_meta($arg['row']['id'], 'views', '0');
            $td['views'] = '<td style="width: 90px;text-align: center;" class="hidden-phone">'.$views.'</td>';
            return array_merge( $td, (array) $arg['td'] );
        }
        // display body content post
        function display_body_content_post()
        {
            global $db, $admin_posts;
            $result     = $db->sql_query("SELECT * FROM ".POSTS_TABLE." WHERE `post_type`='page' ORDER BY post_modified DESC");
            admin_header(get_admin_languages('pages'));
            $admin_posts->page_post_start(array(
                'labels' => array(
                    'name'   => get_admin_languages('pages'), 
                    'button' => array(
                        'title' => get_admin_languages('add_new'), 
                        'url'   => 'posts.php?post_type=page&mode=new'
                    )
                ),
                'post_type'         => 'page',
                'select_actions'    => array('delete'),
                'supports'          => array('checkbox', 'title', 'author', 'publish_status', 'date', 'views', 'comments'),
                'result'            => $result,
                'js_datatable'      => 'null, null, null, null, null, { "orderable": false }',
            ));
            admin_footer();
        }
        // form addnew post
        function display_body_form_add_content_post()
        {
            global $db, $admin_posts;
            admin_header(get_admin_languages('pages') .'('. get_admin_languages('add_new').')');
            $row['orders'] = $db->sql_numrows("SELECT * FROM ".POSTS_TABLE." WHERE `post_type`='page'") + 1;
            $admin_posts->admin_form_post_html(array(
                'labels'            => array('name' => get_admin_languages('pages'), 'add_new_item' => get_admin_languages('add_new')),
                'post_type'         => 'page',
                'query'             => 'addnew',
                'data'              => $row,
                'term_type'         => 'page',
                'supports'          => array('title', 'editor', 'template', 'publish_status', 'comments'),
                'screen_columns'    => '2'
            ));
            admin_footer(); 
        }
        // form edit post
        function display_body_form_edit_content_post()
        {
            global $db, $admin_posts;
            $post_id = (int) safe_input(intval($_GET['id'])) ;
            admin_header(get_admin_languages('page') .'('. get_admin_languages('edit').')');
            $result = $db->sql_query("SELECT * FROM ".POSTS_TABLE." WHERE `post_type`='page' AND `id`='{$post_id}'");
            $row    = $db->sql_fetchrow($result);
            $row['orders'] = get_post_meta($post_id, 'orders_post', '0');
            $admin_posts->admin_form_post_html(array(
                'labels'            => array('name' => get_admin_languages('page'), 'add_new_item' => get_admin_languages('edit')),
                'post_type'         => 'page',
                'query'             => 'update',
                'data'              => $row,
                'term_type'         => 'page',
                'supports'          => array('title', 'editor', 'template', 'publish_status', 'comments'),
                'screen_columns'    => '2'
            ));
            admin_footer(); 
        }
        // action update meta post
        function display_update_post_meta_page($arg)
        {
            if(isset($arg['post']['pin_post']))
            {
                (isset($arg['post']['pin_post']))? update_post_meta($arg['post_id'], 'pin_post', admin_get_form_status($arg['post']['pin_post'])) : '';
            }
            
            $order = (isset($arg['post']['orders']))? $arg['post']['orders'] : 0;
            update_post_meta($arg['post_id'], 'orders_post', $order);
        }
        // terms page
        function display_body_content_terms_page()
        {
            global $db, $admin_categories;
            if(isset($_REQUEST['mode']) and $_REQUEST['mode'] == 'edit' and isset($_GET['id']) and is_numeric($_GET['id'])):
                $term_id    = (int) safe_input(intval($_GET['id'])) ;
            else:
                $term_id    = false;
            endif;
            $title = ($term_id)? get_admin_languages('edit_category') : get_admin_languages('categories');
            admin_header($title);
            $admin_categories->page_terms_start(array(
                'labels'            => array('name' => $title),
                'query'             => ($term_id)? 'update' : 'addnew',
                'term_id'           => $term_id,                
                'term_type'         => 'page',
                'supports'          => array('checkbox', 'name', 'description', 'slug', 'count', 'order'),
                'select_actions'    => array('activs', 'disactivs', 'delete', 'orders'),
                'js_datatable'      => 'null, null, null, null, null',
            ));
            admin_footer();
        }
        // action update meta term
        function display_update_term_meta_page($arg)
        {
            
        }
        // display body content comment
        function display_body_content_comment()
        {
            global $db, $admin_comments;
            $result     = $db->sql_query("SELECT * FROM ".COMMENTS_TABLE." WHERE `comment_type`='page' ORDER BY comment_status ASC, comment_modified DESC");
            admin_header(get_admin_languages('page'));
            $admin_comments->page_comment_start(array(
                'labels'            => array('name'   => get_admin_languages('comments') .' '. get_admin_languages('page'), ),
                'post_type'         => 'page',
                'select_actions'    => array('approve', 'unapprove', 'delete'),
                'supports'          => array('checkbox', 'author', 'comments', 'response', 'date'),
                'result'            => $result,
                'js_datatable'      => 'null, null, null, null',
            ));
            admin_footer();
        }
        // display body content edit comment
        function display_body_form_edit_content_comment()
        {
            global $db, $admin_comments;
            $comment_id = (int) safe_input(intval($_GET['id'])) ;
            admin_header(get_admin_languages('edit_comment'));
            $result = $db->sql_query("SELECT * FROM ".COMMENTS_TABLE." WHERE `comment_type`='page' AND `comment_id`='{$comment_id}'");
            $row    = $db->sql_fetchrow($result);
            $admin_comments->admin_form_comment_html(array(
                'labels'            => array('name' => get_admin_languages('edit_comment')),
                'post_type'         => 'page',
                'query'             => 'update',
                'data'              => $row,
                'screen_columns'    => '2'
            ));
            admin_footer(); 
        }
    }
    new admin_post_type_page();
}
?>
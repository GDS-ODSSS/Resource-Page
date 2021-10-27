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

class display 
{
    // construct
    function __construct()
    {
        global $hooks;
        
        $hooks->add_action('index_home_display', array($this , 'display_action'),1);
        $hooks->add_action('ajax_index_display', array($this , 'ajax_post_comment'),1);
    }
	// ajax add comment
	function ajax_post_comment()
    {
        global $db, $config, $template, $language, $hooks, $lang;
        if(isset($_POST['action']) and $_POST['action'] == 'submit_post_comment' and has_session())
        {
            $post_id = intval($_POST['post_id']);
            if(isset($_POST['content']) and strlen($_POST['content']) and is_numeric($post_id))
            {
                $post_type = get_post_column($post_id, 'post_type');
                $content   = safe_textarea($_POST['content']);
                $date = array(
                    'post_id'           => $post_id,
                    'comment_content'   => $content,
                    'comment_type'      => $post_type,
                    'comment_status'    => 1,
                );
                $comid = insert_comment($date);
                echo json_encode(array('status' => 'success', 'links' => permanent_post_link($post_type.'/post', $post_id).'#comment-'.$comid));
                exit;
            }
            else
            {
                $arr = array(
                    'status'    => 'error', 
                    'html'      => '<h3 class="bordered"><span>'.$lang['whoops_you_missed_something'].'</span></h3>
                    <ul><li>'.$lang['you_need_to_enter_some_text_in_the_comment_field'].'</li></ul>
                    <p><a href="#" class="cancel button">'.$lang['okay'].'</a></p>
                    <a class="close" href="#">'.$lang['closed'].'</a>'
                );
                echo json_encode($arr);
                exit;
            }
        }
    }
    // display action
    function display_action($arg)
    {
        global $hooks;
		if(isset($arg) and count($arg) > 6):
            $this->index_404();
        elseif(isset($arg[0]) and in_array($arg[0],$this->get_actions_post_type())):
            $hooks->do_action('index_post_type_display', $arg);
        elseif(isset($arg[0]) and in_array($arg[0],$this->get_actions_url_request())):
            $hooks->do_action('index_url_request_display', $arg);
        elseif(isset($arg[0]) and $arg[0] == 'page'):
            $this->index_page($arg);
        else:
            $this->index_home();
        endif;
    }
    // index body 
    function index_maintenance()
    {
        global $config, $template, $lang;
        $template->assign_vars(array( 
            'PAGE_TITLE'    => $config['maintenance_title'],
            'HEADLINE'      => $config['maintenance_headline'],
            'DESC'          => $config['maintenance_desc'],
            'BGIMG'         => $config['maintenance_bgimg'],
            'TIMER'         => $config['maintenance_timer'],
            'TYEAR'         => $config['maintenance_year'],
            'TMONTH'        => $config['maintenance_month'],
            'TDAY'          => $config['maintenance_day'],
            'MATEND'        => $config['maintenance_msgend'],
        ));
        page_header(array('page_title' => $lang['maintenance'], 'pagedisplay' => 'maintenance'));
        $template->set_filename('index_maintenance.html');
        page_footer();
    }
    // index body 
    function index_home()
    {
        global $db, $config, $template, $hooks, $lang;
        $hooks->do_action('index_home_action');
        $template->assign_vars(array( 
            'IS_HOMEPAGE' => true,
        ));
        page_header(array('page_title' => $lang['home'], 'pagedisplay' => 'home'));
        $template->set_filename('home/index_home.html');
        page_footer();
    }
    // index page
    function index_page($arg)
    {
        global $db, $config, $template, $hooks;
        $post_id        = get_post_column_by_slug(safe_input($arg['1']), 'id', 'page');
        $page_template  = get_post_meta($post_id, 'page_template');
        $row            = get_assign_single_posts(array('post_id' => $post_id, 'meta_key_view' => 'views'));
        $template->assign_vars(array( 
            'POST_ID'                   => $row['id'],
            'POST_TITLE'                => $row['post_title'], 
            'POST_CONTENT'              => get_post_content($row['post_content']), 
            'POST_COMMENT_STATS'        => $row['comment_status'],
        ));
        $publish_status = get_publish_status($row['id']);
        if(!$publish_status['status'])
        {
            get_index_publish_status($publish_status);
            exit;
        }
        $default_template = array('contact' => 'pages/page_contact.html');
        if($hooks->has_filter('add_page_theme_templates')):
            $template_arg = $hooks->apply_filters( 'add_page_theme_templates' , $default_template);
        else:
            $template_arg = $default_template;
        endif;
        if(@array_key_exists($page_template, $template_arg)){
            $set_template = $template_arg[$page_template];
        }
        else{
            $set_template = 'pages/index_default.html';
        }
        page_header(array('page_title' => $row['post_title'], 'pagedisplay' => 'page'));
        $template->set_filename($set_template);
        page_footer();
    }
    // index 404
    function index_404()
    {
        global $db, $config, $template, $hooks, $lang;
        $template->assign_var('IS_CRR_404', true);
        $hooks->do_action('index_404_action');
        page_header(array('page_title' => $lang['page_not_found'], 'pagedisplay' => '404'));
        $template->set_filename('index_404.html');
        page_footer();
    }
    // get action post type
    function get_actions_post_type()
    {
        global $hooks;
        $post_type = array();
        if($hooks->has_filter('supports_actions_post_type')):
            $post_type_filters = $hooks->apply_filters( 'supports_actions_post_type', $post_type );
        else:
            $post_type_filters = $post_type;
        endif;
        return $post_type_filters;
    }
    // get action url request
    function get_actions_url_request()
    {
        global $hooks;
        $request = array();
        if($hooks->has_filter('supports_actions_url_request')):
            $request_filters = $hooks->apply_filters( 'supports_actions_url_request', $request );
        else:
            $request_filters = $request;
        endif;
        return $request_filters;
    }
}
?>
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
if(!defined('IN_PHPMEGATEMP_CP')) exit();

class admin_posts extends admin_post_boxes
{
    // index posts
    public function index_posts($post_type)
    {
        global $hooks;
        if(!$hooks->has_action('admin_display_body_content_post_'.$post_type))
        {
            get_admin_invalid_page(get_admin_languages('invalid_post_type'));
        }
        if(isset($_REQUEST['mode']) and $_REQUEST['mode'] == 'edit' and isset($_GET['id'])):
            $hooks->do_action( 'admin_display_edit_content_post_'.$post_type );
        elseif(isset($_REQUEST['mode']) and $_REQUEST['mode'] == 'new'):
            $hooks->do_action( 'admin_display_add_content_post_'.$post_type );
        elseif(isset($_GET['viewid'])):
            $hooks->do_action( 'admin_display_view_content_post_'.$post_type );
        elseif(isset($_POST['query']) and $_POST['query'] == 'addnew'):
            $this->post_insert($post_type);
        elseif(isset($_POST['query']) and $_POST['query'] == 'update'):
            $this->post_update($post_type);
        elseif(isset($_POST['query']) and $_POST['query'] == 'action'):
            $mark = (isset($_POST['mark']))? $_POST['mark'] : false;
            $this->post_query_action($_POST['idx'],$mark,$_POST['action'],$_POST['token']);
        elseif(isset($_REQUEST['action']) and $_REQUEST['action'] == 'activ'):
            $this->post_active();
        elseif(isset($_REQUEST['action']) and $_REQUEST['action'] == 'delete'):
            $this->post_delete();
        else:
            $hooks->do_action( 'admin_display_body_content_post_'.$post_type );
        endif;
    }
    // page post start
    function page_post_start($args = array())
    {
        global $token, $db;
        if(isset($args['labels']['button'])):
            $button_head = '<a href="'.$args['labels']['button']['url'].'" class="btn btn-sm btn-primary">'.$args['labels']['button']['title'].'</a>';
        else:
            $button_head = '';
        endif;
        if(isset($_SESSION['action_token'])):
            $message = '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'.$_SESSION['action_token'].'</div>';
            unset($_SESSION['action_token']);
        else:
            $message = '';
        endif;
        admin_content_header(array('title' => $args['labels']['name'], 'button' => $button_head));
        $returnpage = (isset($args['returnpage']))? '<input type="hidden" name="returnpage" value="'.$args['returnpage'].'">' : '';
        
        
        echo admin_content_section_start().'
        <div class="col-md-12">
        '.$message.'
        <form action="posts.php?post_type='.$args['post_type'].'" method="post">
        <input type="hidden" name="token" value="'.$token.'">
        <input type="hidden" name="query" value="action">
        '.$returnpage.'
        <table id="jq-table" class="table table-striped table-bordered">
    	<thead><tr>'; 
        foreach($args['supports'] as $supports)
        {
            echo $this->get_supports_th($supports, $args['post_type']);
        }
        echo '</tr></thead><tbody>';
        while ($row = $db->sql_fetchrow($args['result'])) 
        {
            echo '<tr>';
            foreach($args['supports'] as $supports)
            {
                echo $this->get_supports_td($supports, $args['post_type'], $row);
            }
            echo '</tr>';
        }
        echo '</tbody></table></form></div>
        <script type="text/javascript">$("#jq-table").DataTable({"columns": [{ "orderable": false }, '.$args['js_datatable'].']})</script>
        ';
        echo $this->select_actions($args['select_actions']);
        admin_content_section_end();
    }
    // get supports th
    function get_supports_th($supports, $post_type)
    {
        global $hooks;
        $th['checkbox']     = '<th style="width: 15px;" class="center th-checkbox"><label><input type="checkbox" /><span class="lbl"></span></label></th>';
        $th['title']        = '<th>'.get_admin_languages('title').'</th>';
        $th['author']       = '<th style="text-align: center;" class="hidden-phone">'.get_admin_languages('author').'</th>';
        $th['publish_status']   = '<th style="text-align: center;" class="hidden-phone">'.get_admin_languages('publish').'</th>';
        $th['categories']   = '<th style="text-align: center;" class="hidden-phone">'.get_admin_languages('categories').'</th>';
        $th['date']         = '<th style="width: 90px;text-align: center;" class="hidden-phone">'.get_admin_languages('date').'</th>';
        $th['comments']     = '<th style="width: 60px;text-align: center;" class="hidden-phone">'.get_admin_languages('comments').'</th>';
        if($hooks->has_filter('admin_display_supports_post_th_'.$post_type)):
            $th_filters = $hooks->apply_filters( 'admin_display_supports_post_th_'.$post_type, $th );
        else:
            $th_filters = $th;
        endif;
        return $th_filters[$supports];
    }
    // get supports td
    function get_supports_td($supports, $post_type, $row)
    {
        global $hooks;
        $post_status        = ($row['post_status'])? '<span class="sq-post-status disable"></span>' : '<span class="sq-post-status enable"></span>';
        $comment_status     = ($row['comment_status'])? '<span class="sq-post-status disable"></span>' : '<span class="sq-post-status enable"></span>';
        $post_link_edit     = "posts.php?post_type={$post_type}&mode=edit&id={$row['id']}";
        $actions            = $this->row_actions($post_type, $row);
        $td['checkbox']     = '<td class="td-checkbox"><label><input type="checkbox" name="mark[]" value="'.$row['id'].'" /><span class="lbl"></span></label>'.$post_status.'</td>';
        $td['title']        = '<td><strong><a href="'.$post_link_edit.'">'.$row['post_title'].'</a></strong><div class="row-actions">'.$actions.'</div></td>';
        $td['author']       = '<td style="text-align: center;" class="hidden-phone">'.get_user_column($row['post_author'], 'username').'</td>';
        $td['publish_status']   = '<td style="text-align: center;" class="hidden-phone">'.get_admin_languages(get_post_meta($row['id'], 'publish_status', 'public')).'</td>';
        $td['categories']   = '<td style="text-align: center;" class="hidden-phone">'.get_term_column($row['term_id'], 'name').'</td>';
        $pont_add           = get_post_meta($row['id'], 'post_date', false);
        $post_date          = ($pont_add)? $pont_add : $row['post_modified'];
        $td['date']         = '<td style="text-align: center;" class="hidden-phone" title="'.date('d/m/Y',$post_date).' '.date('h:i:s a',$post_date).'">'.date('d/m/Y',$post_date).'</td>';
        $td['comments']     = '<td class="hidden-phone">'.$comment_status.'</td>';
        if($hooks->has_filter('admin_display_supports_post_td_'.$post_type)):
            $td_filters = $hooks->apply_filters( 'admin_display_supports_post_td_'.$post_type , array('td' => $td, 'row' => $row));
        else:
            $td_filters = $td;
        endif;
        return $td_filters[$supports];
    }
    // row actions
    function row_actions($post_type, $row)
    {
        global $token;
        $html = '<a href="posts.php?post_type='.$post_type.'&mode=edit&id='.$row['id'].'">'.get_admin_languages('edit').'</a> | ';
        if($post_type != 'page'):
            if($row['post_status']):
                $html .= '<a href="posts.php?post_type='.$post_type.'&action=activ&status=disable&id='.$row['id'].'&token='.$token.'" class="green">'.get_admin_languages('disable').'</a> | ';
            else:
                $html .= '<a href="posts.php?post_type='.$post_type.'&action=activ&status=enable&id='.$row['id'].'&token='.$token.'" class="red">'.get_admin_languages('enable').'</a> | ';
            endif;
        endif;
        $html .= '<a href="posts.php?post_type='.$post_type.'&action=delete&id='.$row['id'].'&token='.$token.'" onclick="return confirm(\''.get_admin_languages('confirm_action').'\');" class="red">'.get_admin_languages('delete').'</a>';
        return $html;
    }
    // select actions
    function select_actions($select_actions)
    {
        $html  = '<select name="action" class="form-control input-sm select_actions" style="width: 150px;">';
        $html .= '<option value="-1">'.get_admin_languages('bulk_actions').'</option>';
        $html .= (in_array('activs', $select_actions))? '<option value="activs">'.get_admin_languages('enable').'</option>' : '';
        $html .= (in_array('disactivs', $select_actions))? '<option value="disactivs">'.get_admin_languages('disable').'</option>' : '';
        $html .= (in_array('delete', $select_actions))? '<option value="delete">'.get_admin_languages('delete').'</option>' : '';
        $html .= '</select>';
        $html .= '&nbsp;<input type="submit" class="btn btn-sm btn-primary" value="'.get_admin_languages('apply').'" onclick=return confirm("'.get_admin_languages('confirm_action').'"); />';
        $actionselect = '<script type="text/javascript">$(function(){$(".actionselect").html(\''.$html.'\')});</script>';
        return $actionselect;
    }
    // form post html
    function admin_form_post_html($args = array())
    {
        global $token, $hooks;
        if(isset($_SESSION['action_token'])):
            $message = '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'.$_SESSION['action_token'].'</div>';
            unset($_SESSION['action_token']);
        else:
            $message = '';
        endif;
        $button = ($args['query'] == 'update')? '<a href="posts.php?post_type='.$args['post_type'].'&mode=new" class="btn btn-sm btn-primary">'.get_admin_languages('add_new').'</a>' : '<small>('.$args['labels']['add_new_item'].')</small>';
        admin_content_header(array('title' => $args['labels']['name'], 'button' => $button));
        echo admin_content_section_start(). '<div class="col-md-12">'.$message.'';
        echo '<form action="posts.php?post_type='.$args['post_type'].'" method="post" name="form" class="checkbox_form">
        <input type="hidden" name="action" value="'.$args['query'].'" />
        <input type="hidden" name="query" value="'.$args['query'].'" />
        <input type="hidden" name="token" value="'.$token.'" />
        <div class="row">
        ';
        echo (isset($args['data']['id']))? '<input type="hidden" name="post_id" value="'.$args['data']['id'].'" />' : '';
        echo ($args['screen_columns'] == '2')? '<div class="col-md-9">' : '<div class="col-md-12">';
        echo (in_array('title', $args['supports']))? $this->box_input_post_title($args) : '';
        if(in_array('editor', $args['supports'])){
            $post_content = (isset($args['data']['post_content']))? $args['data']['post_content'] : '';
            echo $this->box_post_editor($post_content, 'content', 'content', '');
        }
        echo (in_array('excerpt', $args['supports']))? $this->box_post_excerpt($args) : '';
        echo (in_array('post_tag', $args['supports']))? $this->post_boxes_post_tag($args) : '';
        $hooks->do_action( 'admin_display_post_supports_box_lg_'.$args['post_type'] , $args);
        echo '</div>';
        echo ($args['screen_columns'] == '2')? '<div class="col-md-3">' : '<div class="col-md-12">';
        echo $this->post_boxes_publish($args);
        /*echo (in_array('category', $args['supports']))? $this->post_boxes_category($args) : '';*/
        echo (in_array('author', $args['supports']))? $this->post_boxes_author($args) : '';
        echo (in_array('thumbnail', $args['supports']))? $this->post_boxes_thumbnail($args) : '';
        echo (in_array('template', $args['supports']))? $this->post_boxes_template($args) : '';
        $hooks->do_action( 'admin_display_post_supports_box_md_'.$args['post_type'] , $args);
        echo '<div class="clearfix"></div></div></div></form></div>';
        admin_content_section_end();
    }
    // post insert
    public function post_insert($post_type)
    {
        global $hooks, $db;
        $post           = array();
        $post           = $_POST;
        $post_type      = ($post['post_type'])? $post['post_type'] : $post_type;
        $post_author    = ($post['post_author'])? safe_input($post['post_author']) : get_session_userid();
        $title          = ($post['title'])? safe_input(unsanitize_text($post['title'])) : 'not title' ;
        $content        = ($post['content'])? stripslashes(unsanitize_text($post['content'])) : '' ;
        $status         = $post['post_status'];
        $comment_status = (isset($post['comment_status']))? $post['comment_status'] : '0';
        $term_id        = ($post['term_id'])? $post['term_id'] : '' ;
        $_SESSION['action_token'] = get_admin_languages('post_published');
        $args = array(
            'post_author'       => $post_author,
            'content'           => $content,
            'title'             => $title,
            'status'            => $status,
            'comment_status'    => $comment_status,
            'post_type'         => $post_type,
            'term_id'           => $term_id
        );
        $post_id = insert_post($args, $post);
        $hooks->do_action( 'admin_display_update_post_meta_'.$post_type , array('post_id'=> $post_id, 'post' => $post));
        if(isset($post['metalang']))
        {
            foreach($post['metalang'] as $key => $value)
            {
                update_post_meta($post_id, $key, $value);
            }
        }
        if(isset($post['publish_status']))
        {
            update_post_meta($post_id, 'publish_status', $post['publish_status']);
        }
        if(isset($post['header_location']))
        {
            @header("Location:{$post['header_location']}{$post_id}");
        }
        else
        {
            @header("Location:posts.php?post_type={$post_type}&mode=edit&id={$post_id}");
        }
    }
    // post update
    public function post_update($post_type)
    {
        global $hooks, $db;
        $post           = array();
        $post           = $_POST;
        $posttype       = (isset($post['post_type']))? $post['post_type'] : $post_type;
        $post_author    = (isset($post['post_author']))? safe_input($post['post_author']) : get_session_userid();
        $title          = (isset($post['title']))? safe_input(unsanitize_text($post['title'])) : 'not title' ;
        $content        = (isset($post['content']))? stripslashes(unsanitize_text($post['content'])) : '' ;
        $status         = $post['post_status'];
        $comment_status = (isset($post['comment_status']))? $post['comment_status'] : '0';
        $term_id        = (isset($post['term_id']))? $post['term_id'] : '0' ;
        $post_name      = (isset($post['post_name']))? safe_input(preg_slug($post['post_name'])) : safe_input(preg_slug($title));
        $post_id        = safe_input($db->sql_escape($post['post_id']));
        $token          = safe_input($_REQUEST['token']);
        $post_modified  = time();
        if($token == $_SESSION['securitytokenadmincp']):
            $_SESSION['action_token'] = get_admin_languages('post_updated');
            $result = $db->sql_query("UPDATE " . POSTS_TABLE . " SET 
                `post_author`    = '{$post_author}', 
                `post_content`   = '{$content}', 
                `post_title`     = '{$title}', 
                `post_status`    = '{$status}', 
                `comment_status` = '{$comment_status}', 
                `post_name`      = '{$post_name}', 
                `post_modified`  = '{$post_modified}', 
                `term_id`        = '{$term_id}' 
                WHERE `id`='".$post_id."'
            ");
            if(isset($post['excerpt'])){update_post_meta($post_id, 'excerpt',safe_input($post['excerpt']));}
            if(isset($post['post_tags'])){update_post_meta($post_id, 'post_tags', safe_input($post['post_tags']));}
            if(isset($post['thumbnails'])){update_post_meta($post_id, 'thumbnails', $post['thumbnails']);}
            if(isset($post['page_template'])){update_post_meta($post_id, 'page_template',safe_input($post['page_template']));}
            if(isset($post['publish_status'])){update_post_meta($post_id, 'publish_status', $post['publish_status']);}
            $hooks->do_action( 'admin_display_update_post_meta_'.$posttype , array('post_id'=> $post_id, 'post' => $post));
            if(isset($post['metalang']))
            {
                foreach($post['metalang'] as $key => $value)
                {
                    update_post_meta($post_id, $key, $value);
                }
            }

        endif;
        if(isset($post['header_location']))
        {
            @header("Location:{$post['header_location']}{$post_id}");
        }
        else
        {
            @header("Location:posts.php?post_type={$post_type}&mode=edit&id={$post_id}");
        }
    }
    // post delete
    public function post_delete()
    {
        global $db;
        $token      = safe_input($_REQUEST['token']);
        $post_id    = (int) safe_input(intval($_GET['id']));
        if($token == $_SESSION['securitytokenadmincp']):
            $db->sql_query("DELETE FROM " . POSTS_TABLE . " WHERE `id`='".$db->sql_escape($post_id)."'");
            $db->sql_query("DELETE FROM " . POSTSMETA_TABLE . " WHERE `post_id`='".$db->sql_escape($post_id)."'");
            $_SESSION['action_token'] = get_admin_languages('delete_post_successfully');
        endif;
        if(isset($_REQUEST['returnpage']))
        {
            @header("Location:".$_SERVER['HTTP_REFERER']."");
        }
        else
        {
            @header("Location:".THIS_SCRIPT_RETURN."");
        }
    }
    // post query action
    public function post_query_action($idx,$checkbox,$action,$token)
    {
        global $db;
        $token  = safe_input($token);
        if($token == $_SESSION['securitytokenadmincp'])
        {
            if($action == "delete"){
                if($checkbox)
                {
                    $number = count($checkbox);
                    for($i=0;$i<$number;$i++){
                        $id     = $checkbox[$i];
                        $db->sql_query("DELETE FROM " . POSTS_TABLE . "  WHERE `id`='".$db->sql_escape($id)."'");
                        $db->sql_query("DELETE FROM " . POSTSMETA_TABLE . " WHERE `post_id`='".$db->sql_escape($id)."'");
                    }
                    $_SESSION['action_token'] = get_admin_languages('delete_post_successfully');
                }
                else {$_SESSION['action_token'] = get_admin_languages('not_select_anything');}
            }
            elseif($action == "activs"){
                if($checkbox)
                {
                    $number = count($checkbox);
                    for($i=0;$i<$number;$i++){
                        $id     = $checkbox[$i];
                        $db->sql_query("UPDATE " . POSTS_TABLE . " SET `post_status`='1' WHERE `id`='".$db->sql_escape($id)."'"); 
                    }
                    $_SESSION['action_token'] = get_admin_languages('enable_post_successfully');
                }
                else {$_SESSION['action_token'] = get_admin_languages('not_select_anything');}
            }
            elseif($action == "disactivs"){
                if($checkbox)
                {
                    $number = count($checkbox);
                    for($i=0;$i<$number;$i++){
                        $id     = $checkbox[$i];
                        $db->sql_query("UPDATE " . POSTS_TABLE . " SET `post_status`='0' WHERE `id`='".$db->sql_escape($id)."'"); 
                    }
                    $_SESSION['action_token'] = get_admin_languages('disable_post_successfully');
                }
                else {$_SESSION['action_token'] = get_admin_languages('not_select_anything');}
            }
        }
        
        
        if(isset($_POST['returnpage']))
        {
            @header("Location:".$_SERVER['HTTP_REFERER']."");
        }
        else
        {
            @header("Location:".THIS_SCRIPT_RETURN."");
        }
        
    }
    // post active
    public function post_active()
    {
        global $db;
        $get_status = safe_input($_REQUEST['status']);
        $token      = safe_input($_REQUEST['token']);
        $post_id    = (int) safe_input(intval($_GET['id']));
        if($token == $_SESSION['securitytokenadmincp']):
            if($get_status == 'disable'){$status = 0;$_SESSION['action_token']    = get_admin_languages('disable_post_successfully');}
            elseif($get_status == 'enable'){$status = 1;$_SESSION['action_token'] = get_admin_languages('enable_post_successfully');}
            else {$get_status = 1;$_SESSION['action_token'] = 'none';}
            $db->sql_query("UPDATE " . POSTS_TABLE . " SET `post_status`='".$status."' WHERE `id`='".$db->sql_escape($post_id)."'");
        endif;
        @header("Location:".THIS_SCRIPT_RETURN."");
    }
}
?>
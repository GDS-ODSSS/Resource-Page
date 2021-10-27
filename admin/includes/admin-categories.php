<?php
//----------------------------------------------------------------------|
/***********************************************************************|
 * Project:     UNHCR IM Resource Page                                       |
//----------------------------------------------------------------------|
 * @link http://nawaaugustine.com                                         |
 * @copyright 2020.                                                     |
 * @author Augustine Nawa <ocjpnawa@gmail.com>                   |
 * @package UNHCR IM Resource Page                                           |
 * @version 4.5                                                         |
//----------------------------------------------------------------------|
************************************************************************/
//----------------------------------------------------------------------|
require_once('./admin-common.php');
if(!defined('IN_PHPMEGATEMP_CP')) exit();

class admin_categories
{
    
    
    public function index_terms($terms)
    {
        global $hooks;
        
        if(!$hooks->has_action('admin_display_body_content_terms_'.$terms))
        {
            get_admin_invalid_page(get_admin_languages('invalid_taxonomy'));
        }
        
        if(isset($_POST['query']) and $_POST['query'] == 'addnew'):
            $this->insert($terms);
        elseif(isset($_POST['query']) and $_POST['query'] == 'update'):
            $this->update();
        elseif(isset($_POST['query']) and $_POST['query'] == 'action'):
            $mark = (isset($_POST['mark']))? safe_input($_POST['mark']) : false;
            $this->terms_query_action(safe_input($_POST['idx']),safe_input($_POST['order']),$mark,safe_input($_POST['action']),safe_input($_POST['token']));     
        elseif(isset($_REQUEST['action']) and $_REQUEST['action'] == 'activ'):
            $this->terms_active();
        elseif(isset($_REQUEST['action']) and $_REQUEST['action'] == 'delete'):
            $this->terms_delete();
        else:
            $hooks->do_action( 'admin_display_body_content_terms_'.$terms );
        endif;
        
    }
    // page_terms_start
    function page_terms_start($args = array())
    {
        global $token, $db;
        
        if(isset($_SESSION['action_token'])):
            $message = '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'.$_SESSION['action_token'].'</div>';
            unset($_SESSION['action_token']);
        else:
            $message = '';
        endif;
        

        admin_content_header(array('title' => $args['labels']['name']));
        echo admin_content_section_start(). '<div class="col-md-12">'.$message.'</div>';
        echo ($args['query'] == 'addnew')? '<div class="col-md-4">' : '<div class="col-md-4">';
        $this->terms_form_html($args);
        echo '</div>';
            echo ($args['query'] == 'addnew')? '<div class="col-md-8">' : '<div class="col-md-8 col-disabled">';
            echo '<form action="categories.php?taxonomy='.$args['term_type'].'" method="post">
            <input type="hidden" name="token" value="'.$token.'">
            <input type="hidden" name="query" value="action">
            <table id="jq-table" class="table table-striped table-bordered table-hover">
    		<thead><tr>';
            foreach($args['supports'] as $supports)
            {
                echo $this->get_supports_th($supports, $args['term_type']);
            }
            echo '</tr></thead><tbody>';
            $result  = $db->sql_query("SELECT * FROM ".TERMS_TABLE." WHERE `type`='{$args['term_type']}' ORDER BY orders ASC");
            while ($row = $db->sql_fetchrow($result)) 
            {
                echo '<tr>';
                foreach($args['supports'] as $supports)
                {
                    echo $this->get_supports_td($supports, $args['term_type'], $row);
                }
                echo '</tr>';
            }
            echo '</tbody></table></form>
            <script type="text/javascript">$("#jq-table").DataTable({"columns": [{ "orderable": false }, '.$args['js_datatable'].']})</script>
            ';
            echo $this->select_actions($args['select_actions']);
            echo '</div>';
        //endif;
        admin_content_section_end();
    }
    // terms form html
    function terms_form_html($args = array())
    {
        global $db;
        $row = array();
        $btn_title = ($args['query'] == 'addnew')? get_admin_languages('add_new_category') : get_admin_languages('update') ;
        $btn_cancel = ($args['query'] == 'update')? '&nbsp; <a class="btn btn-small btn-danger" href="categories.php?taxonomy='.$args['term_type'].'">'.get_admin_languages('cancel').'</a>' : '';
        
        $head_title = ($args['query'] == 'addnew')? '<h4 style="border-bottom: 1px solid #e5e5e5;padding: 5px 0;">'.get_admin_languages('add_new_category').'</h4>' : '';
        if($args['query'] == 'update')
        {
            $result  = $db->sql_query("SELECT * FROM ".TERMS_TABLE." WHERE `type`='{$args['term_type']}' AND `id`='{$args['term_id']}'");
            $row     = $db->sql_fetchrow($result);
        }
        else
        {
            $row['orders'] = $db->sql_numrows("SELECT * FROM ".TERMS_TABLE." WHERE `type`='{$args['term_type']}'") + 1;
        }
        
        $row['id'] = (isset($row['id']))? $row['id'] : '';
        $row['name'] = (isset($row['name']))? $row['name'] : '';
        $row['slug'] = (isset($row['slug']))? $row['slug'] : '';
        $row['description'] = (isset($row['description']))? $row['description'] : '';
        $row['status'] = (isset($row['status']))? $row['status'] : '';
        
        echo $head_title.'<form action="categories.php?taxonomy='.$args['term_type'].'" method="post" name="form" class="checkbox_form">
            <input type="hidden" name="query" value="'.$args['query'].'" />
            <input type="hidden" name="id" value="'.$row['id'].'" />
            <input type="hidden" name="oldname" value="'.$row['name'].'" />
            <input type="hidden" name="type_terms" value="'.$args['term_type'].'" />';
        echo '
            <div class="form-group">
                <label>'.get_admin_languages('name').'</label>
                <input type="text" name="name" placeholder="'.get_admin_languages('name').'" value="'.$row['name'].'" class="form-control" />
            </div>
            <div class="form-group">
                <label>'.get_admin_languages('description').'</label>
                <textarea class="form-control" name="description" placeholder="'.get_admin_languages('description').'" rows="4">'.get_term_meta($row['id'], 'description').'</textarea>
            </div>
            <div class="form-group">
                <label>'.get_admin_languages('slug').'</small></label>
                <input type="text" name="slug" placeholder="'.get_admin_languages('slug').'" value="'.urldecode($row['slug']).'" class="form-control" />
            </div>
            <div class="form-group">
				<div class="row">
                <label class="col-md-6" for="form-field-orders">'.get_admin_languages('order').'</label>
				<div class="col-md-6" >
					<input type="text" name="orders" value="'.$row['orders'].'"  class="form-control" style="text-align: center;" />
				</div>
                </div>
			</div>
            <div class="form-group">
				<div class="row">
                <label class="col-md-6" for="form-field-status">'.get_admin_languages('status').'</label>
				<div class="col-md-6">'.admin_get_status_checkbox_html_post('status', $row['status']).'</div>
                </div>
			</div>
            <div class="clear"></div>
            <div class="form-actions text-center">
    			<button class="btn btn-small btn-success">'.$btn_title.'</button>&nbsp; 
    			<button class="btn btn-small btn-info" type="reset">'.get_admin_languages('reset').'</button>
                '.$btn_cancel.'
    		</div>
        </form>';
    }
    // get supports th
    function get_supports_th($supports, $term_type)
    {
        global $hooks;
        $th['checkbox']     = '<th style="width: 15px;" class="center th-checkbox"><label><input type="checkbox" /><span class="lbl"></span></label></th>';
        $th['name']         = '<th>'.get_admin_languages('name').'</th>';
        $th['description']  = '<th class="hidden-phone">'.get_admin_languages('description').'</th>';
        $th['slug']         = '<th class="hidden-phone">'.get_admin_languages('slug').'</th>';
        $th['count']        = '<th style="width: 70px;text-align: center;" class="hidden-phone">'.get_admin_languages('count').'</th>';
        $th['order']        = '<th style="width: 70px;text-align: center;" class="hidden-phone">'.get_admin_languages('order').'</th>';
        if($hooks->has_filter('admin_display_supports_term_th_'.$term_type)):
            $th_filters = $hooks->apply_filters( 'admin_display_supports_term_th_'.$term_type, $th );
        else:
            $th_filters = $th;
        endif;
        return $th_filters[$supports];
    }
    // get supports td
    function get_supports_td($supports, $term_type, $row)
    {
        global $hooks;
        $post_status        = ($row['status'])? '<span class="sq-post-status disable"></span>' : '<span class="sq-post-status enable"></span>';
        $post_link_edit     = "categories.php?taxonomy={$term_type}&mode=edit&id={$row['id']}";
        $actions            = $this->row_actions($term_type, $row);
        $td['checkbox']     = '<td class="td-checkbox"><label><input type="checkbox" name="mark[]" value="'.$row['id'].'" /><span class="lbl"></span></label>'.$post_status.'</td>';
        $td['name']         = '<td><strong><a href="'.$post_link_edit.'">'.$row['name'].'</a></strong><div class="row-actions">'.$actions.'</div></td>';
        $td['description']  = '<td class="hidden-phone">'.get_term_meta($row['id'], 'description').'</td>';
        $td['slug']         = '<td class="hidden-phone">'.urldecode($row['slug']).'</td>';
        $td['count']        = '<td class="hidden-phone" style="text-align: center;">'.get_count_posts_term($row['id'],'').'</td>';
        $td['order']        = $this->box_trem_orders($row);
        if($hooks->has_filter('admin_display_supports_term_td_'.$term_type)):
            $td_filters = $hooks->apply_filters( 'admin_display_supports_term_td_'.$term_type , array('td' => $td, 'row' => $row));
        else:
            $td_filters = $td;
        endif;
        return $td_filters[$supports];
    }
    // box trem orders
    function box_trem_orders($row)
    {
        return '
        <td style="text-align: center;" class="hidden-phone">
            <span style="display: none;">'.$row['orders'].'</span>
            <input type="hidden" name="idx[]" value="'.$row['id'].'" style="display:none;">
            <input type="text" name="order[]" class="form-control input-sm" value="'.$row['orders'].'" style="width:50px;text-align:center;padding:0 2px;line-height: 19px">
        </td>
        ';
    }
    // row actions
    function row_actions($term_type, $row)
    {
        global $token;
        $html = '<a href="categories.php?taxonomy='.$term_type.'&mode=edit&id='.$row['id'].'">'.get_admin_languages('edit').'</a> | ';
        if($row['status']):
            $html .= '<a href="categories.php?taxonomy='.$term_type.'&action=activ&status=disable&id='.$row['id'].'&token='.$token.'" class="green">'.get_admin_languages('disable').'</a> | ';
        else:
            $html .= '<a href="categories.php?taxonomy='.$term_type.'&action=activ&status=enable&id='.$row['id'].'&token='.$token.'" class="red">'.get_admin_languages('enable').'</a> | ';
        endif;
        $html .= '<a href="categories.php?taxonomy='.$term_type.'&action=delete&id='.$row['id'].'&token='.$token.'" onclick="return confirm(\''.get_admin_languages('confirm_action').'\');" class="red">'.get_admin_languages('delete').'</a>';
        return $html;
    }
    // select actions
    function select_actions($select_actions)
    {
        $html  = '<select name="action" class="form-control input-sm select_actions" style="width: 150px;">';
        $html .= '<option value="-1">'.get_admin_languages('bulk_actions').'</option>';
        $html .= (in_array('orders', $select_actions))? '<option value="orders">'.get_admin_languages('order').'</option>' : '';
        $html .= (in_array('activs', $select_actions))? '<option value="activs">'.get_admin_languages('enable').'</option>' : '';
        $html .= (in_array('disactivs', $select_actions))? '<option value="disactivs">'.get_admin_languages('disable').'</option>' : '';
        $html .= (in_array('delete', $select_actions))? '<option value="delete">'.get_admin_languages('delete').'</option>' : '';
        $html .= '</select>';
        $html .= '&nbsp;<input type="submit" class="btn btn-sm btn-primary" value="'.get_admin_languages('apply').'" onclick=return confirm("'.get_admin_languages('confirm_action').'"); />';
        $actionselect = '<script type="text/javascript">$(function(){$(".actionselect").html(\''.$html.'\')});</script>';
        return $actionselect;
    }
    // insert
    public function insert($terms)
    {
        global $db, $hooks;
        $post = array();
        $post = $_POST;
        $status = admin_get_form_status(safe_input($post['status']));
        $sql_ins = array(
            'id'        => (int)'',
           	'name'      => security($post['name']),
            'slug'      => ($post['slug'])? security(preg_slug($post['slug'])) : security(preg_slug($post['name'])),
            'parent'    => (int)$post['parent'],
            'type'      => $terms,
            'orders'    => (int)$post['orders'],
            'status'    => (int)$status,
        );
        $sql     = 'INSERT INTO ' . TERMS_TABLE . ' ' . $db->sql_build_array('INSERT', $sql_ins, false);
        $result  = $db->sql_query($sql);
        $term_id = $db->sql_nextid();
        update_term_meta($term_id,'description',$post['description']);
        if(isset($post['metalang']))
        {
            foreach($post['metalang'] as $key => $value)
            {
                update_term_meta($term_id, $key, $value);
            }
        }
        $hooks->do_action( 'admin_display_update_term_meta_'.$terms , array('term_id' => $term_id, 'post' => $post));
        @header("Location:".THIS_SCRIPT_RETURN."");
    }
    
    public function update()
    {
        global $db, $hooks;
        $post       = array();
        $post       = $_POST;
        $term_id    = $db->sql_escape($post['id']);
        $name       = safe_input($post['name']);
        $slug       = ($post['slug'])? safe_input(preg_slug($post['slug'])) : safe_input(preg_slug($post['name']));
        $parent     = (isset($post['parent']))? $post['parent'] : '0';
        $orders     = $post['orders'];
        $status     = admin_get_form_status(@$post['status']);
        $_SESSION['action_token'] = get_admin_languages('category_updated');
        $result = $db->sql_query("UPDATE " . TERMS_TABLE . " SET 
            `name`   = '{$name}', 
            `slug`   = '{$slug}', 
            `parent` = '{$parent}',
            `orders` = '{$orders}', 
            `status` = '{$status}'
             WHERE `id` = '{$term_id}'
        ");
        update_term_meta($term_id, 'description', $post['description']);
        
        if(isset($post['metalang']))
        {
            foreach($post['metalang'] as $key => $value)
            {
                update_term_meta($term_id, $key, $value);
            }
        }
        $hooks->do_action( 'admin_display_update_term_meta_'.$terms , array('term_id'=> $term_id, 'post' => $post));
        @header("Location:".THIS_SCRIPT_RETURN."");
    }

    
    public function terms_query_action($idx,$order,$checkbox,$action,$token)
    {
        global $db;
        if($token == $_SESSION['securitytokenadmincp'])
        {
            if($action == "orders"){
                $number = count($idx);
                for($i=0;$i<$number;$i++){
                    $id     = $idx[$i];
                    $orders = $order[$i];
                    $result = $db->sql_query("UPDATE " . TERMS_TABLE . " SET `orders`='".$orders."' WHERE `id`='".$db->sql_escape($id)."'"); 
                }
                $_SESSION['action_token'] = get_admin_languages('orders_categorie_successfully');
                @header("Location:".THIS_SCRIPT_RETURN."");
            }
            elseif($action == "delete"){
                if($checkbox)
                {
                    $number = count($checkbox);
                    for($i=0;$i<$number;$i++){
                        $id     = $checkbox[$i];
                        $result = $db->sql_query("DELETE FROM " . TERMS_TABLE . "  WHERE `id`='".$db->sql_escape($id)."'");
                    }
                    $_SESSION['action_token'] = get_admin_languages('delete_categorie_successfully');
                }
                else {$_SESSION['action_token'] = get_admin_languages('not_select_anything');}
                @header("Location:".THIS_SCRIPT_RETURN."");
            }
            elseif($action == "activs"){
                if($checkbox)
                {
                    $number = count($checkbox);
                    for($i=0;$i<$number;$i++){
                        $id     = $checkbox[$i];
                        $result = $db->sql_query("UPDATE " . TERMS_TABLE . " SET `status`='1' WHERE `id`='".$db->sql_escape($id)."'"); 
                    }
                    $_SESSION['action_token'] = get_admin_languages('enable_categorie_successfully');
                }
                else {$_SESSION['action_token'] = get_admin_languages('not_select_anything');}
                @header("Location:".THIS_SCRIPT_RETURN."");
            }
            elseif($action == "disactivs"){
                if($checkbox)
                {
                    $number = count($checkbox);
                    for($i=0;$i<$number;$i++){
                        $id     = $checkbox[$i];
                        $result = $db->sql_query("UPDATE " . TERMS_TABLE . " SET `status`='0' WHERE `id`='".$db->sql_escape($id)."'"); 
                    }
                    $_SESSION['action_token'] = get_admin_languages('disable_categorie_successfully');
                }
                else {$_SESSION['action_token'] = get_admin_languages('not_select_anything');}
                @header("Location:".THIS_SCRIPT_RETURN."");
            }
        }
    }
    
    public function terms_active()
    {
        global $db, $config;
        $get_status = safe_input($_REQUEST['status']);
        $token      = safe_input($_REQUEST['token']);
        $term_id    = (int) safe_input(intval($_GET['id']));
        if($token == $_SESSION['securitytokenadmincp']):
            if($get_status == 'disable'){$status = 0;$_SESSION['action_token']    = get_admin_languages('disable_categorie_successfully');}
            elseif($get_status == 'enable'){$status = 1;$_SESSION['action_token'] = get_admin_languages('enable_categorie_successfully');}
            else {$get_status = 1;$_SESSION['action_token'] = 'none';}
            $db->sql_query("UPDATE ".TERMS_TABLE." SET `status`='{$status}' WHERE `id`='{$term_id}'");
        endif;
        @header("Location:".THIS_SCRIPT_RETURN."");
    }
    
    public function terms_delete()
    {
        global $template, $db, $config;
        $term_id = (int) safe_input(intval($_GET['id']));
        if($_REQUEST['token'] == $_SESSION['securitytokenadmincp']):
            $db->sql_query("DELETE FROM ".TERMS_TABLE." WHERE `id`='{$term_id}'");
            $_SESSION['action_token'] = get_admin_languages('delete_categorie_successfully');
        endif;
        @header("Location:".THIS_SCRIPT_RETURN."");
    }
    
}    
?>
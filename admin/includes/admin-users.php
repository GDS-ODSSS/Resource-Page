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
require_once('./admin-common.php');
if(!defined('IN_PHPMEGATEMP_CP')) exit();

class admin_users
{
    // index user
    public function index_users()
    {
        global $hooks;
        if(isset($_POST['query']) and isset($_POST['action']) and $_POST['action'] == 'update'):
            $this->user_update();
        elseif(isset($_POST['query']) and $_POST['query'] == 'addnew'):
            is_user_permissions('users');
            $this->user_insert();
        elseif(isset($_POST['query']) and $_POST['query'] == 'action'):
            is_user_permissions('users');
            $mark = (isset($_POST['mark']))? $_POST['mark'] : false;
            $this->query_action($mark,$_POST['action'],$_POST['token']);
        elseif(isset($_REQUEST['action']) and $_REQUEST['action'] == 'activ'):
            is_user_permissions('users');
            $this->active_users();
        elseif(isset($_REQUEST['action']) and $_REQUEST['action'] == 'delete'):
            is_user_permissions('users');
            $this->delete_users();
        elseif(isset($_REQUEST['mode']) and $_REQUEST['mode'] == 'profile'):
            $this->display_profile_users();
        elseif(isset($_REQUEST['mode']) and $_REQUEST['mode'] == 'new'):
            is_user_permissions('users');
            $this->form_users('new');
        else:
            is_user_permissions('users');
            $this->all_users();
        endif;
    }
    // all user
    public function all_users()
    {
        global $hooks, $db, $config, $token;
        if(isset($_SESSION['action_token'])):
            $message = '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'.$_SESSION['action_token'].'</div>';
            unset($_SESSION['action_token']);
        else:
            $message = '';
        endif;
        admin_header(get_admin_languages('users'));
        admin_content_header(array('title' => get_admin_languages('users'), 'button' => '<a href="users.php?mode=new" class="btn btn-sm btn-primary">'.get_admin_languages('add_new').'</a>'));
        echo admin_content_section_start().'
        <div class="col-md-12">
        '.$message.'
        <form action="users.php" method="post">
        <input type="hidden" name="token" value="{TOKEN}">
        <input type="hidden" name="query" value="action">
        <table id="jq-table" class="table table-striped table-bordered">
    	<thead><tr>
        <th style="width: 15px;" class="center th-checkbox"><label><input type="checkbox" /><span class="lbl"></span></label></th>
        <th>'.get_admin_languages('username').'</th>
        <th style="text-align: center;" class="hidden-phone">'.get_admin_languages('email').'</th>
        <th style="text-align: center;width: 100px;" class="hidden-phone">'.get_admin_languages('user_level').'</th>
        <th style="text-align: center;width: 60px;" class="hidden-phone">'.get_admin_languages('posts').'</th>
        </tr>
        </thead>
        <tbody>
        ';
        $result     = $db->sql_query("SELECT * FROM ".USERS_TABLE." ORDER BY id ASC");
        while ($row = $db->sql_fetchrow($result)) 
        {
            $user_status        = ($row['status'])? '<span class="sq-post-status disable"></span>' : '<span class="sq-post-status enable"></span>';
            $user_link_edit     = "users.php?mode=profile&userid={$row['id']}";
            $actions            = $this->row_actions($row);
            
            $mark = ($row['id'] != '1' )? '<input type="checkbox" name="mark[]" value="'.$row['id'].'" /><span class="lbl"></span>' : '';

            echo '<tr>
            <td class="td-checkbox"><label>'.$mark.'</label>'.$user_status.'</td>
            <td><img src="'.get_gravatar($row['id'],'32').'" class="avatar" /><strong><a href="'.$user_link_edit.'">'.$row['username'].'</a></strong><div class="row-actions">'.$actions.'</div></td>
            <td style="text-align: center;" class="hidden-phone">'.$row['email'].'</td>
            <td style="text-align: center;" class="hidden-phone">'.get_user_meta($row['id'],'userlevel').'</td>
            <td style="text-align: center;" class="hidden-phone">'.get_count_post_user($row['id'], 'all').'</td>
            </tr>';
        }
        echo '</tbody></table></form></div>
        <script type="text/javascript">$("#jq-table").DataTable({"columns": [{ "orderable": false }, null, null, null, null]})</script>
        ';
        echo $this->select_actions();
        admin_content_section_end();
        admin_footer();
    }
    // row actions
    function row_actions($row)
    {
        global $token;
        $userid = get_session_userid();
        //$html  = '<a href="users.php?action=edit&id='.$row['id'].'">'.get_admin_languages('edit').'</a>';
        $html = '<a href="users.php?mode=profile&userid='.$row['id'].'">'.get_admin_languages('profile').'</a>';
        if($row['id'] != '1' ):
        $html .= ' | <a href="users.php?action=delete&id='.$row['id'].'&token='.$token.'" onclick="return confirm(\''.get_admin_languages('confirm_action').'\');" class="red">'.get_admin_languages('delete').'</a>';
        endif;
        return $html;
    }
    // select actions
    function select_actions()
    {
        $html  = '<select name="action" class="form-control input-sm select_actions" style="width: 150px;">';
        $html .= '<option value="-1">'.get_admin_languages('bulk_actions').'</option>';
        $html .= '<option value="delete">'.get_admin_languages('delete').'</option>';
        $html .= '</select>';
        $html .= '&nbsp;<input type="submit" class="btn btn-sm btn-primary" value="'.get_admin_languages('apply').'" onclick=return confirm("'.get_admin_languages('confirm_action').'"); />';
        $actionselect = '<script type="text/javascript">$(function(){$(".actionselect").html(\''.$html.'\')});</script>';
        return $actionselect;
    }
    // display profile users script (The decision is made on the update or not v3.6)
    function display_profile_users_script()
    {
        
    }
    // display profile users
    public function display_profile_users()
    {
        global $hooks, $db, $config, $token;
        $hooks->add_action('admin_head', array($this, 'display_profile_users_script'), 10);
        $userid     = (isset($_GET['userid']) and is_numeric($_GET['userid']))? safe_input(intval($_GET['userid'])) : get_session_userid();
        $action_url = (isset($_GET['userid']) and is_numeric($_GET['userid']) and $userid != get_session_userid())? 'users.php?mode=profile&userid='.$userid : 'users.php?mode=profile';
        if($userid != get_session_userid())
        {
            is_user_permissions('users');
        }
        if(isset($_SESSION['action_token'])):
            if($_SESSION['action_token'] == 'Error Update')
            {
                $message = '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'.$_SESSION['action_token'].'</div>';
            }
            else
            {
                $message = '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'.$_SESSION['action_token'].'</div>';
            }
            unset($_SESSION['action_token']);
        else:
            $message = '';
        endif;
        admin_header(get_admin_languages('profile').' '.get_user_column($userid,'username'));
        $megapanel_options = new admin_megapanel_options();
        echo admin_content_section_start().'<div class="col-md-12">'.$message.'
        <div class="megapanel-panel">
        <div class="panel-wrapper">
        <form action="'.$action_url.'" method="post" class="form-horizontal">
        <input type="hidden" name="action" value="update" />
        <input type="hidden" name="query" value="editprofile" />
        <input type="hidden" name="token" value="'.$token.'" />
        <div class="megapanel-main">
        <div id="megapanel-editor" class="megapanel-jqcheckbox">
        <div class="megapanel-wrapper">
        <header class="megapanel-page-header">
        <h1>'.get_admin_languages('profile').' '.get_user_column($userid,'username').'</h1>
        <div class="megapanel-submit"><button class="button button-primary">'.get_admin_languages('save_changes').'</button></div>
        </header>
        <div class="megapanel-tabs-container megapanel-container">
        <div class="megapanel-tabs nav-tabs-cookie" data-cookie="usersettings">
            <a href="#" class="active" data-tab=".option-general"><i class="fas fa-sliders-h"></i> '.get_admin_languages('general_settings').'</a>
            <a href="#" class="" data-tab=".option-permissions"><i class="fas fa-toolbox"></i> '.get_admin_languages('user_permissions').'</a>
        </div>
        <div class="megapanel-tabs-content">
        ';
        
        echo '<div class="megapanel-tab-content option-general active">';
            $megapanel_options->field_options_item(array(
                'name'  => get_admin_languages('username'),
                'id'    => 'username',
                'type'  => 'text',
                'value' => get_user_column($userid,'username'),
                'class' => 'width320',
                'read'  => 'readonly'
            ));
            $megapanel_options->field_options_item(array(
                'name'  => get_admin_languages('email_address'),
                'id'    => 'email',
                'type'  => 'text',
                'value' => get_user_column($userid,'email'),
                'class' => 'width320',
                'dir'   => 'ltr',
            ));
            $megapanel_options->field_options_item(array(
                'name'  => get_admin_languages('firstname'),
                'id'    => 'firstname',
                'type'  => 'text',
                'value' => get_user_meta($userid,'firstname'),
                'class' => 'width320',
            ));
            $megapanel_options->field_options_item(array(
                'name'  => get_admin_languages('lastname'),
                'id'    => 'lastname',
                'type'  => 'text',
                'value' => get_user_meta($userid,'lastname'),
                'class' => 'width320',
            ));
        
            if(get_user_meta(get_session_userid(),'userlevel') == 'administrator')
            {
                $megapanel_options->field_options_item(array(
                    'name'  => get_admin_languages('status'),
                    'id'    => 'status',
                    'type'  => 'checkbox',
                    'value' => get_user_column($userid,'status'),
                ));
                $megapanel_options->field_options_item(array(
                    'name'  => get_admin_languages('user_level'),
                    'id'    => 'userlevel',
                    'type'      => 'radio',
                    'value'     => get_user_meta($userid,'userlevel'),
                    'options'   => array(
                        'administrator' => get_admin_languages('Administrator'),
                        'super_admin'   => get_admin_languages('super_admin'),
                        'user'          => get_admin_languages('user'),
                    )
                )); 
            }
            else
            {
                $label_level   = array(
                    'administrator' => get_admin_languages('Administrator'),
                    'super_admin'   => get_admin_languages('super_admin'),
                    'user'          => get_admin_languages('user'),
                );
                $megapanel_options->field_options_item(array(
                    'name'  => get_admin_languages('user_level'),
                    'type'  => 'label',
                    'value' => $label_level[get_user_meta($userid,'userlevel')],
                ));
            }
        
        
            $megapanel_options->field_options_item(array(
                'name'  => get_admin_languages('new_password'),
                'id'    => 'newpassword',
                'type'  => 'password',
                'value' => '',
                'place' => get_admin_languages('new_password'),
                'autoc' => 'autocomplete="off"',
                'class' => 'width320',
            ));
        
        echo '</div>';
        
        echo '<div class="megapanel-tab-content option-permissions">';
        
        if(get_user_meta(get_session_userid(),'userlevel') == 'administrator')
        {
            if($userid and is_serialized(get_user_meta($userid,'userpermissions')))
            {
                $user_permissions = maybe_unserialize(get_user_meta($userid,'userpermissions'));
            }
            else
            {
                $user_permissions = array();
            }

            $permissions = $this->user_permissions($userid, true);
            foreach($permissions as $key => $value)
            {
                $on_active  = (in_array($key,$user_permissions))? 'active' : '';
                $off_active = (!in_array($key,$user_permissions))? 'active' : '';
                $on_value  = (in_array($key,$user_permissions))? '1' : '0';
                echo '
                <div class="megapanel-col-item">
                    <label>'.$value.'</label>
                    <div class="megapanel-buttons-options">
                        <button type="button" data-value="1" class="option-on '.$on_active.'">ON</button>
                        <button type="button" data-value="0" class="option-off '.$off_active.'">OFF</button>
                        <input type="hidden" name="permissions['.$key.']" value="'.$on_value.'">
                    </div>
                </div>';
            }
        }
        else
        {
            $megapanel_options->field_options_item(array(
                'name'  => get_admin_languages('user_level'),
                'type'  => 'label',
                'value' => $label_level[get_user_meta($userid,'userlevel')],
            ));
        }

            
        echo '</div>';
        echo '
        <footer class="megapanel-page-footer megapanel-submit"><button class="button button-primary">'.get_admin_languages('save_changes').'</button></footer>
        </div>
        </div>
        </div>
        </div>
        </div>
        </form>
        </div>
        </div>
        </div>
        ';
        admin_content_section_end();
        admin_footer();
    }
    // form users
    public function form_users()
    {
        global $token;
        $megapanel_options = new admin_megapanel_options();
        $userid = (isset($userid))? $userid : '';
        admin_header(get_admin_languages('add_new_user'));
        echo admin_content_section_start().'<div class="col-md-12">
        <div class="megapanel-panel">
        <div class="panel-wrapper">
        <form action="users.php" method="post" enctype="multipart/form-data" name="form" class="form-horizontal">
        <input type="hidden" name="action" value="addnew" />
        <input type="hidden" name="query" value="addnew" />
        <input type="hidden" name="token" value="'.$token.'" />
        <div class="megapanel-main">
        <div id="megapanel-editor" class="megapanel-jqcheckbox">
        <div class="megapanel-wrapper">
        <header class="megapanel-page-header">
        <h1>'.get_admin_languages('add_new_user').'</h1>
        <div class="megapanel-submit"><button class="button button-primary">'.get_admin_languages('add_new_user').'</button></div>
        </header>
        <div class="megapanel-tabs-container megapanel-container">
        <div class="megapanel-tabs nav-tabs-cookie" data-cookie="usersettings">
            <a href="#" class="active" data-tab=".option-general"><i class="fas fa-sliders-h"></i> '.get_admin_languages('general_settings').'</a>
            <a href="#" class="" data-tab=".option-permissions"><i class="fas fa-toolbox"></i> '.get_admin_languages('user_permissions').'</a>
        </div>
        <div class="megapanel-tabs-content">
        ';
        
        echo '<div class="megapanel-tab-content option-general active">';
            $megapanel_options->field_options_item(array(
                'name'  => get_admin_languages('username'),
                'id'    => 'username',
                'type'  => 'text',
                'value' => '',
                'class' => 'width320',
            ));
            $megapanel_options->field_options_item(array(
                'name'  => get_admin_languages('email_address'),
                'id'    => 'email',
                'type'  => 'text',
                'value' => '',
                'class' => 'width320',
                'dir'   => 'ltr',
            ));
            $megapanel_options->field_options_item(array(
                'name'  => get_admin_languages('firstname'),
                'id'    => 'firstname',
                'type'  => 'text',
                'value' => '',
                'class' => 'width320',
            ));
            $megapanel_options->field_options_item(array(
                'name'  => get_admin_languages('lastname'),
                'id'    => 'lastname',
                'type'  => 'text',
                'value' => '',
                'class' => 'width320',
            ));
            $megapanel_options->field_options_item(array(
                'name'  => get_admin_languages('status'),
                'id'    => 'status',
                'type'  => 'checkbox',
                'value' => '1',
            ));
            $megapanel_options->field_options_item(array(
                'name'  => get_admin_languages('password'),
                'id'    => 'password',
                'type'  => 'password',
                'value' => '',
                'place' => get_admin_languages('password'),
                'autoc' => 'autocomplete="off"',
                'class' => 'width320',
            ));
            $megapanel_options->field_options_item(array(
                'name'  => get_admin_languages('confirm_password'),
                'id'    => 'confirm_password',
                'type'  => 'password',
                'value' => '',
                'place' => get_admin_languages('confirm_password'),
                'autoc' => 'autocomplete="off"',
                'class' => 'width320',
            ));
            $megapanel_options->field_options_item(array(
                'name'      => get_admin_languages('user_level'),
                'id'        => 'userlevel',
                'type'      => 'radio',
                'value'     => ($userid)? get_user_meta($userid, 'userlevel') : 'user',
                'options'   => array(
                    'administrator' => get_admin_languages('Administrator'),
                    'super_admin'   => get_admin_languages('super_admin'),
                    'user'          => get_admin_languages('user'),
                )
            ));
        echo '</div>';
        
        echo '<div class="megapanel-tab-content option-permissions">';
        
        if($userid and is_serialized(get_user_meta($userid,'userpermissions')))
        {
            $user_permissions = maybe_unserialize(get_user_meta($userid,'userpermissions'));
        }
        else
        {
            $user_permissions = array();
        }
        
        $permissions = $this->user_permissions($userid, true);
        foreach($permissions as $key => $value)
        {
            $on_active  = (in_array($key,$user_permissions))? 'active' : '';
            $off_active = (!in_array($key,$user_permissions))? 'active' : '';
            $on_value  = (in_array($key,$user_permissions))? '1' : '0';
            echo '
            <div class="megapanel-col-item">
                <label>'.$value.'</label>
                <div class="megapanel-buttons-options">
                    <button type="button" data-value="1" class="option-on '.$on_active.'">ON</button>
                    <button type="button" data-value="0" class="option-off '.$off_active.'">OFF</button>
                    <input type="hidden" name="permissions['.$key.']" value="'.$on_value.'">
                </div>
            </div>';
        }
        echo '</div>';
        echo '
        <footer class="megapanel-page-footer megapanel-submit"><button class="button button-primary">'.get_admin_languages('add_new_user').'</button></footer>
        </div>
        </div>
        </div>
        </div>
        </div>
        </form>
        </div>
        </div>
        </div>
        ';

        admin_content_section_end();
        admin_footer(); 
    }
    // delete users
    public function delete_users()
    {
        global $template, $db, $config;
        $id     = intval(safe_input($_GET['id']));
        if($_REQUEST['token'] == $_SESSION['tokenadmincp']['user'])
        {
            $result = $db->sql_query("DELETE FROM " . USERS_TABLE . " WHERE `id`='".$db->sql_escape($id)."'");
            $db->sql_freeresult($result);
            $_SESSION['action_token'] = get_admin_languages('delete_user_successfully');
        }
        header("Location:".THIS_SCRIPT."");
    }
    // user permissions (return array or options tag html)
    function user_permissions($userid = false, $array = false)
    {
        global $hooks;
        if($userid and is_serialized(get_user_meta($userid,'userpermissions')))
        {
            $user_permissions = maybe_unserialize(get_user_meta($userid,'userpermissions'));
        }
        else
        {
            $user_permissions = array();
        }
        $perm = array();
        $perm['page']               = get_admin_languages('pages');
        $perm['users']              = get_admin_languages('users');
        $perm['email_templates']    = get_admin_languages('email_templates');
        $perm['language']           = get_admin_languages('languages');
        $perm['settings']           = get_admin_languages('settings');
        $perm['appearance']         = get_admin_languages('appearance');
        $perm['extensions']         = get_admin_languages('extensions');
        if($hooks->has_filter('admin_display_user_permissions')):
            $perm_filters = $hooks->apply_filters( 'admin_display_user_permissions', $perm );
        else:
            $perm_filters = $perm;
        endif;
        $perm_html = '';
        if($array):
            $perm_html = $perm_filters;
        else:
            foreach($perm_filters as $key => $value)
            {
                $selected = (in_array($key,$user_permissions))? 'selected="selected"' : '' ;
                $perm_html .= '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
            }
        endif; 
        return $perm_html;
    }
    // user insert (A decision is made modifications in the function v3.7)
    public function user_insert()
    {
        global $db;
        $token      = safe_input($_POST['token']);
        if($token == $_SESSION['tokenadmincp']['user']){
            $username       = safe_input($_POST['username']);
            $email          = safe_input($_POST['email']);
            $firstname      = safe_input($_POST['firstname']);
            $lastname       = safe_input($_POST['lastname']);
            $userlevel      = safe_input($_POST['userlevel']);
            $status         = $_POST['status'];
            $password       = md5($_POST['password']);
            $sql_ins = array(
                'id'                => (int)'',
                'username'          => $username,
                'password'          => $password,
                'email'             => $email,
                'registered'        => (int)time(),
                'activation_key'    => '',
                'status'            => (int)$status,
            );
            $sql     = 'INSERT INTO ' . USERS_TABLE . ' ' . $db->sql_build_array('INSERT', $sql_ins, false);
            $result  = $db->sql_query($sql);
            $userid = $db->sql_nextid();
            update_user_meta($userid, 'firstname', $firstname);
            update_user_meta($userid, 'lastname', $lastname);
            update_user_meta($userid, 'userlevel', $userlevel);
            $permissions = array();
            foreach($_POST['permissions'] as $key => $val)
            {
                if($val) {
                    $permissions[] = $key;
                }   
            }
            update_user_meta($userid, 'userpermissions', maybe_serialize($permissions));
            $_SESSION['action_token'] = get_admin_languages('add_user_successfully');
            header("Location:users.php?mode=profile&userid={$userid}");
        }
    }
    // user update (A decision is made modifications in the function v3.7)
    public function user_update()
    {
        global $db;
        $userid     = (isset($_GET['userid']) and is_numeric($_GET['userid']))? safe_input(intval($_GET['userid'])) : get_session_userid();       
        if(($userid != get_session_userid() and is_user_permissions('users', false)) or $userid == get_session_userid())
        {
            $action_url = (isset($_GET['userid']) and is_numeric($_GET['userid']))? 'users.php?mode=profile&userid='.$userid : 'users.php?mode=profile';
            $token      = safe_input($_POST['token']);
            if($token == $_SESSION['tokenadmincp']['user']){
                $email          = safe_input($_POST['email']);
                $username       = safe_input($_POST['username']);
                $firstname      = safe_input($_POST['firstname']);
                $lastname       = safe_input($_POST['lastname']);
                
                $result         = $db->sql_query("UPDATE " . USERS_TABLE . " SET `username`='{$username}', `email`='{$email}' WHERE `id`='{$userid}'");
                update_user_meta($userid, 'firstname', $firstname);
                update_user_meta($userid, 'lastname', $lastname);
                
                if(get_user_meta(get_session_userid(),'userlevel') == 'administrator')
                {
                    $userlevel      = safe_input($_POST['userlevel']);
                    $status         = $_POST['status'];
                    $result         = $db->sql_query("UPDATE " . USERS_TABLE . " SET `status`='{$status}' WHERE `id`='{$userid}'");
                    update_user_meta($userid, 'userlevel', $userlevel);
                    $permissions = array();
                    foreach($_POST['permissions'] as $key => $val)
                    {
                        if($val) {
                            $permissions[] = $key;
                        }   
                    }
                    update_user_meta($userid, 'userpermissions', maybe_serialize($permissions));
                }
                if(!empty($_POST['newpassword']))
                {
                    $newpassword = md5($_POST['newpassword']);
                    $result = $db->sql_query("UPDATE ".USERS_TABLE." SET `password`='{$newpassword}' WHERE `id`='{$userid}'");
                }
                $_SESSION['action_token'] = get_admin_languages('edit_user_successfully');
                header("Location:{$action_url}");
            }
            else
            {
                $_SESSION['action_token'] = get_admin_languages('error_update');
                header("Location:{$action_url}");
            }
        }
        else
        {
            $_SESSION['action_token'] = get_admin_languages('error_update');
            header("Location:{$action_url}");
        }  
    }
    // actions users (A decision is made modifications in the function v3.6)
    public function query_action($checkbox,$action,$token){
        global $db;
        if($token == $_SESSION['tokenadmincp']['user'])
        {
            if($action == "delete"){
                if($checkbox)
                {
                    $number = count($checkbox);
                    for($i=0;$i<$number;$i++){
                        $id     = $checkbox[$i];
                        $result = $db->sql_query("DELETE FROM " . USERS_TABLE . "  WHERE `id`='".$db->sql_escape($id)."'");
                    }
                    $_SESSION['action_token'] = get_admin_languages('delete_users_successfully');
                }
                else {$_SESSION['action_token'] = get_admin_languages('not_select_anything');}
                header("Location:".THIS_SCRIPT."");
            }
        }
    }
}    
?>
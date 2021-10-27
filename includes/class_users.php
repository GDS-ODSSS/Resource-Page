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

class users extends display 
{
    // construct
    function __construct()
    {
        global $hooks;
        if(has_session())
        {
            $hooks->add_action('ajax_index_display', array($this , 'ajax_update_profile'),1);
        }
        else
        {
            $hooks->add_action('ajax_index_display', array($this , 'ajax_create_account'),1);
        }
        $hooks->add_filter('supports_actions_url_request', array($this , 'supports_request_display'), 1);
        
        $hooks->add_action('index_url_request_display', array($this , 'users_request_display'),1);
                                
    }
    // supports request display
    function supports_request_display($arg)
    {
        $request = array('signin', 'signup', 'forgot', 'profile', 'signout', 'activate');
        return array_unique( array_merge( $request, (array) $arg ) );
    }
    // start display users
    function  users_request_display($arg)
    {
        if(isset($arg['0']) and $arg['0'] == 'signin')
        {
            $this->index_signin($arg);
        }
        elseif(isset($arg['0']) and $arg['0'] == 'signup')
        {
            
            $this->index_signup($arg);
        }
        elseif(isset($arg['0']) and $arg['0'] == 'forgot')
        {
            $this->index_forgot($arg);
        }
        elseif(isset($arg['0']) and $arg['0'] == 'profile')
        {
            $this->index_edit_profile($arg);
        }
        elseif(isset($arg['0']) and $arg['0'] == 'signout')
        {
            $this->index_signout($arg);
        }
        elseif(isset($arg['0']) and $arg['0'] == 'activate')
        {
            $this->index_activate($arg);
        }
    }   
    // ajax create account
    function ajax_create_account()
    {
        global $db, $config, $lang;
        $name_not_available = array('admin');
        if(isset($_POST['action']) and $_POST['action'] == 'create_account' and !has_session())
        {
            $firstname              = safe_input($_POST['firstname']);
            $lastname               = safe_input($_POST['lastname']);
            $username               = safe_input($_POST['username']);
            $email                  = safe_input($_POST['email']);
            $password               = safe_input($_POST['password']);
            $confirmpassword        = safe_input($_POST['confirmpassword']);
            $fname_error            = (!isset($firstname) or !strlen($firstname))? '<li>'.$lang['firstname_field_is_required'].'</li>': false;
            $lname_error            = (!isset($lastname) or !strlen($lastname))? '<li>'.$lang['lastname_field_is_required'].'</li>': false;
            $username_error         = (!isset($username) or !strlen($username))? '<li>'.$lang['username_field_is_required'].'</li>': false;
            $username_exist         = (check_user_field('username', $username) or in_array($username, $name_not_available))? '<li>'.$lang['name_not_available_registration'].'</li>' : false;
            $email_error            = (!isset($email) or !strlen($email))? '<li>'.$lang['email_field_is_required'].'</li>': false;
            if(!$email_error)
            {
                $email_error = (!strlen(validate_email($email)))? '<li>'.$lang['please_write_the_email_correctly'].'</li>': false;
            }
            $email_exist            = (check_user_field('email', $email) and !$email_error)? '<li>'.$lang['email_another_user'].'</li>' : false;
            $password_error         = (!isset($password) or !strlen($password))? '<li>'.$lang['password_field_is_required'].'</li>': false;
            $password_length_error  = (strlen($password) and strlen($password) < 6)? '<li>'.$lang['password_must_be_at_least_6_characters'].'</li>': false;
            $confirmpassword_error  = (!isset($confirmpassword) or !strlen($confirmpassword))? '<li>'.$lang['confirm_password_field_is_required'].'</li>': false;
            $mpassword_error        = (strlen($password) > 5 and strlen($confirmpassword) and $password != $confirmpassword)? '<li>'.$lang['password_and_confirm_password_not_match'].'</li>': false;
            if($fname_error or $lname_error or $username_error or $username_exist or $email_error or $email_exist or $password_error or $password_length_error or $confirmpassword_error or $mpassword_error)
            {
                $arr = array(
                    'status'        => 'error', 
                    'html'          => '<h3 class="bordered"><span>'.$lang['whoops_you_missed_something'].'</span></h3>
                    <ul>'.$fname_error . $lname_error . $username_error . $username_exist . 
                    $email_error . $email_exist . $password_error . $password_length_error . $confirmpassword_error . $mpassword_error.'</ul>
                    <p><a href="#" class="cancel button">'.$lang['okay'].'</a></p><a class="close" href="#">'.$lang['closed'].'</a>'
                );
                echo json_encode($arr);
                exit;  
            }
            else
            {
                $sql_ins = array(
                    'id'                => (int)'',
                    'username'          => (string)$username,
                    'password'          => (string)md5($password),
                    'email'             => (string)$email,
                    'registered'        => (int)time(),
                    'activation_key'    => (string)(isset($config['registration_status']))? securitytoken(ACTIVATION_KEY): '',
                    'status'            => (int)(isset($config['registration_activation']))? 0 : 1,
                );
                $sql        = 'INSERT INTO ' . USERS_TABLE . ' ' . $db->sql_build_array('INSERT', $sql_ins, true);
                $result     = $db->sql_query($sql);
                $user_id    = $db->sql_nextid();
                //addmeta and redirect
                $ipaddress  = get_real_ipaddress();
                $user_agent = trim(substr(request_header('User-Agent'), 0, 149));
                update_user_meta($user_id,'firstname',$firstname);
                update_user_meta($user_id,'lastname',$lastname);
                update_user_meta($user_id,'userlevel', 'user');
                update_user_meta($user_id,'signuptime',time());
                update_user_meta($user_id,'avatar', '');
                update_user_meta($user_id,'forgettime', '');
                update_user_meta($user_id,'ipaddress', $ipaddress);
                update_user_meta($user_id,'user_agent', $user_agent);
                if($config['registration_activation'])
                {
                    $data = array(
                        'type'      => 'register_activation',
                        'username'  => $username,
                        'firstname' => $firstname,
                        'lastname'  => $lastname,
                        'email'     => $email,
                        'key'       => get_user_column($user_id, 'activation_key'),
                        'send_to'   => array('user' => $user_id),
                    );
                    mail_send($data);
                }
                else
                {
                    $data = array(
                        'type'      => 'register',
                        'username'  => $username,
                        'firstname' => $firstname,
                        'lastname'  => $lastname,
                        'send_to'   => array('user' => $user_id),
                    );
                    mail_send($data);
                }
                set_session($user_id, 1);
                echo json_encode(array('status' => 'success', 'links' => $config['siteurl']));
                exit;
            }
        }
    }
    // index signin
    function index_signin($arg, $act = '')
    {
        global $db, $config, $template, $lang;
        $template->assign_vars(array(
            'IS_NOTLOGIN'   => false,
            'IS_FIELDLOGIN' => false,
            'IS_SIGNOUT'    => false,
        ));
        if(has_session())
        {
            @header("location:{$config['siteurl']}");
        }
        if(isset($_POST['action']) and $_POST['action'] == 'signin')
        {
            if(isset($_POST['email']) and isset($_POST['password']) and !empty($_POST['email']) and !empty($_POST['password']))
            {
                $email      = safe_input($_POST['email']);
                $password   = md5($_POST['password']);
                $autologin  = (isset($_POST['autologin']))? 1 : 0;
                $sql = "SELECT id FROM ".USERS_TABLE." WHERE `email`='{$email}' and `password`='{$password}'";
                if($db->sql_numrows($sql))
                {
                    $result = $db->sql_query($sql);
                    $date   = $db->sql_fetchrow($result);
                    set_session($date['id'], $autologin, get_user_meta($date['id'],'capabilities'));
                    @header("location:{$config['siteurl']}");
                }
                else
                {
                    $template->assign_var('IS_NOTLOGIN', true);
                }
            }
            else
            {
                $template->assign_var('IS_FIELDLOGIN', true);
            }
        }
        if($act == 'signout')
        {
            $template->assign_var('IS_SIGNOUT', true);
        }
        page_header(array('page_title' => $lang['account_Login'], 'pagedisplay' => 'signin'));
        $template->set_filename('users/index_signin.html');
        page_footer();  
    }
    // index signup
    function index_signup($arg)
    {
        global $config, $template, $lang;
        $template->assign_var('IS_NOT_RES_USERS', $config['registration_status']);
        if(has_session())
        {
            @header("location:{$config['siteurl']}");
        }
        page_header(array('page_title' => $lang['create_your_free_account'], 'pagedisplay' => 'signup'));
        $template->set_filename('users/index_signup.html');
        page_footer(array('unset'=> true, 'unset_session' => 'error_signup'));
    }
    // activate
    function index_activate($arg)
    {
        global $db, $config, $template, $lang;
        $username   = (isset($arg['1']))? $arg['1'] : false;
        $key        = (isset($arg['2']))? $arg['2'] : false;
        if($username and $key)
        {
            $sql = "SELECT id FROM ".USERS_TABLE." WHERE `username`='{$username}' and `activation_key`='{$key}'";
            if($db->sql_numrows($sql))
            {
                $result    = $db->sql_query($sql);
                $row       = $db->sql_fetchrow($result);
                $user_id   = $row['id'];
                $username  = get_user_column($user_id, 'username');
                $firstname = get_user_column($user_id, 'firstname');
                $lastname  = get_user_column($user_id, 'lastname');
                $db->sql_query("UPDATE " . USERS_TABLE . " SET `status`='1', `activation_key`='' WHERE `id`='{$user_id}'");
                $data = array(
                    'type'      => 'register',
                    'username'  => $username,
                    'firstname' => $firstname,
                    'lastname'  => $lastname,
                    'send_to'   => array('user' => $user_id),
                );
                mail_send($data);
                @header("location:{$config['siteurl']}");
            }
            else
            {
                $template->assign_var('IS_CRR_404', true);
                page_header(array('page_title' => '404', 'pagedisplay' => '404'));
                $template->set_filename('index_404.html');
                page_footer();
            }
        }
        else
        {
            global $db, $config, $template, $hooks;
            $template->assign_var('IS_CRR_404', true);
            $hooks->do_action('index_404_action');
            page_header(array('page_title' => $lang['page_not_found'], 'pagedisplay' => '404'));
            $template->set_filename('index_404.html');
            page_footer();
        }
    }
    // forgot
    function index_forgot($arg)
    {
        global $db, $config, $template, $lang;
        if(has_session())
        {
            @header("location:{$config['siteurl']}");
        }

        $email = (isset($arg['1']))? $arg['1'] : false;
        $key   = (isset($arg['2']))? $arg['2'] : false;
        if($email and $key)
        {
            $sql = "SELECT id FROM ".USERS_TABLE." WHERE `email`='{$email}' and  `activation_key`='{$key}'";
            if($db->sql_numrows($sql))
            {
                $result         = $db->sql_query($sql);
                $row            = $db->sql_fetchrow($result);
                $user_id        = $row['id'];
                $password       = securitytoken(5);
                $password_md5   = md5($password);
                $username  = get_user_column($user_id, 'username');
                $firstname = get_user_column($user_id, 'firstname');
                $lastname  = get_user_column($user_id, 'lastname');
                $db->sql_query("UPDATE " . USERS_TABLE . " SET `password`='{$password_md5}', `activation_key`='' WHERE `id`='{$user_id}'");
                update_user_meta($user_id,'forgot_time', '');
                mail_send(array(
                    'type'          => 'update_password', 
                    'username'      => $username, 
                    'firstname'     => $firstname,
                    'lastname'      => $lastname, 
                    'new_password'  => $password, 
                    'user_id'       => $user_id, 
                    'send_to'       => array('user' => $user_id)
                ));
                page_header(array('page_title' => $lang['recover_account'], 'pagedisplay' => 'recoveraccount'));
                $template->set_filename('users/recover_account_sucess.html');
                page_footer();
            }
            else
            {
                $template->assign_var('IS_CRR_404', true);
                page_header(array('page_title' => '404', 'pagedisplay' => '404'));
                $template->set_filename('index_404.html');
                page_footer();
            }
        }
        else
        {
            $template->assign_var('FORGOT_ERROR', false );
            $template->assign_var('FORGOT_FORM', true );
            if(isset($_POST['action']) and $_POST['action'] == 'forgetpassword')
            {
                $email  = safe_input($_POST['email']);
                if(strlen(validate_email($email)))
                {
                    if(check_user_field('email', $email))
                    {
                        $user_id = get_userid_by_column('email', $email);
                        $key     = securitytoken(ACTIVATION_KEY);
                        update_user_column('activation_key', $key, $user_id);
                        update_user_meta($user_id,'forgot_time', time()+ (60*60*24));
                        $username  = get_user_column($user_id, 'username');
                        $firstname = get_user_column($user_id, 'firstname');
                        $lastname  = get_user_column($user_id, 'lastname');
                        mail_send(array(
                            'type'      => 'reset_password', 
                            'username'  => $username, 
                            'firstname' => $firstname,
                            'lastname'  => $lastname,
                            'email'     => $email, 
                            'key'       => $key, 
                            'user_id'   => $user_id, 
                            'send_to'   => array('user' => $user_id)
                        ));
                        $template->assign_var('FORGOT_ERROR', 'sendkey' );
                        $template->assign_var('FORGOT_FORM', false );
                    }
                    else
                    {
                        $template->assign_var('FORGOT_ERROR', 'error' );
                        $template->assign_var('FORGOT_FORM', true );
                    }
                }
                else
                {
                    $template->assign_var('FORGOT_ERROR', 'email_required' );
                    $template->assign_var('FORGOT_FORM', true );
                }
            }
            page_header(array('page_title' => $lang['recover_account'], 'pagedisplay' => 'recoveraccount'));
            $template->set_filename('users/index_forgot.html');
            page_footer();
        }
    }
    // index signout
    function index_signout($arg)
    {
        global $db, $config, $template;
        if(!has_session())
        {
            @header("location:{$config['siteurl']}");
        }
        else
        {
            unset_session();
            $this->index_signin($arg, 'signout');
        } 
    }
    // profile
    function index_profile($arg)
    {
        global $template, $db, $config, $session;
        $username = $_REQUEST['username'];
        $check_username = check_user_field('username', $username);
        if(!$check_username)
        {
            @header("location:{$config['siteurl']}");
        }
        else
        {
            $crr_userid = get_userid_by_column('username', $username);
            $userid     = (has_session())? get_session_userid() : false;
            if($crr_userid == $userid)
            {
                page_header(array('page_title' => 'dashboard', 'pagedisplay' => 'dashboard'));
                $template->set_filename('users/dashboard_profile.html');
                page_footer();
            }
            else
            {
                page_header(array('page_title' => 'dashboard', 'pagedisplay' => 'dashboard'));
                $template->set_filename('users/user_profile.html');
                page_footer();
            }
        }           
    }
    // ajax update profile
    function ajax_update_profile($arg)
    {
        global $db, $config, $lang;
        if(isset($_POST['action']) and $_POST['action'] == 'update_profile' and has_session())
        {
            $userid             = get_session_userid();
            $firstname          = safe_input($_POST['firstname']);
            $lastname           = safe_input($_POST['lastname']);
            $username           = safe_input($_POST['username']);
            $email              = safe_input($_POST['email']);
            $fname_error        = (!isset($firstname) or !strlen($firstname))? '<li>'.$lang['firstname_field_is_required'].'</li>': false;
            $lname_error        = (!isset($lastname) or !strlen($lastname))? '<li>'.$lang['lastname_field_is_required'].'</li>': false;
            $username_error     = (!isset($username) or !strlen($username))? '<li>'.$lang['username_field_is_required'].'</li>': false;
            $username_exist     = (check_user_field('username', $username, $userid))? '<li>'.$lang['name_not_available_registration'].'</li>' : false;
            $email_error        = (!isset($email) or !strlen($email))? '<li>'.$lang['email_field_is_required'].'</li>': false;
            $email_exist        = (check_user_field('email', $email, $userid))? '<li>'.$lang['email_another_user'].'</li>' : false;
            if($fname_error or $lname_error or $username_error or $username_exist or $email_error or $email_exist)
            {
                $arr = array(
                    'status'        => 'error', 
                    'html'          => '<h3 class="bordered"><span>'.$lang['whoops_you_missed_something'].'</span></h3>
                    <ul>'. $fname_error . $lname_error . $username_error . $username_exist . $email_error . $email_exist .'</ul>
                    <p><a href="#" class="cancel button">'.$lang['okay'].'</a></p><a class="close" href="#">'.$lang['closed'].'</a>'
                );
                echo json_encode($arr);
                exit;  
            }
            else
            {
                $ipaddress  = get_real_ipaddress();
                $user_agent = trim(substr(request_header('User-Agent'), 0, 149));
                $db->sql_query("UPDATE " . USERS_TABLE . " SET `username`='{$username}', `email`='{$email}' WHERE `id`='{$userid}'");
                update_user_meta($userid,'firstname',$firstname);
                update_user_meta($userid,'lastname',$lastname);
                update_user_meta($userid,'lastuptime',time());
                update_user_meta($userid,'lastupipaddress', $ipaddress);
                update_user_meta($userid,'lastupuser_agent', $user_agent);
                echo json_encode(array('status' => 'success', 'links' => "{$config['siteurl']}/profile"));
                exit;
            }
        }
        elseif(isset($_POST['action']) and $_POST['action'] == 'update_password' and has_session())
        {
            $userid                 = get_session_userid();
            $password               = $_POST['password'];
            $confirmpassword        = $_POST['confirmpassword'];
            $password_error         = (!isset($password) or !strlen($password))? '<li>'.$lang['password_field_is_required'].'</li>': false;
            $password_length_error  = (strlen($password < 5))? '<li>'.$lang['password_must_be_at_least_6_characters'].'</li>': false;
            $confirmpassword_error  = (!isset($confirmpassword) or !strlen($confirmpassword))? '<li>'.$lang['confirm_password_field_is_required'].'</li>': false;
            $mpassword_error        = (strlen($password > 5) and strlen($confirmpassword) and $password != $confirmpassword)? '<li>'.$lang['password_and_confirm_password_not_match'].'</li>': false;
            if($password_error or $password_length_error or $confirmpassword_error or $mpassword_error)
            {
                $arr = array(
                    'status'        => 'error', 
                    'html'          => '<h3 class="bordered"><span>'.$lang['whoops_you_missed_something'].'</span></h3>
                    <ul>'. $password_error . $password_length_error . $confirmpassword_error . $mpassword_error.'</ul>
                    <p><a href="#" class="cancel button">'.$lang['okay'].'</a></p><a class="close" href="#">'.$lang['closed'].'</a>'
                );
                echo json_encode($arr);
                exit;  
            }
            else
            {
                $newpassword = md5($password);
                $ipaddress  = get_real_ipaddress();
                $user_agent = trim(substr(request_header('User-Agent'), 0, 149));
                $db->sql_query("UPDATE " . USERS_TABLE . " SET `password`='{$newpassword}' WHERE `id`='{$userid}'");
                update_user_meta($user_id,'lastuptime',time());
                update_user_meta($user_id,'lastupipaddress', $ipaddress);
                update_user_meta($user_id,'lastupuser_agent', $user_agent);
                unset_session();
                echo json_encode(array('status' => 'success', 'links' => "{$config['siteurl']}/signin"));
                exit;
            }
        }
    }
    // edit profile
    function index_edit_profile($arg)
    {
        global $db, $config, $template;
        if(!has_session())
        {
            @header("location:{$config['siteurl']}");
        }
        else
        {
            page_header(array('page_title' => 'profile', 'pagedisplay' => 'profile'));
            $template->set_filename('users/edit_profile.html');
            page_footer();
        }
    }
    // index dashboard
    function index_dashboard($arg)
    {
        global $db, $config, $template;
        if(!has_session())
        {
            @header("location:{$config['siteurl']}");
        }
        else
        {
            
        } 
    }
}
?>
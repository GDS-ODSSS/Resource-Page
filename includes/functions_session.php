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


$cookie_name    = 'themearabia_rJKDz62h08Q3EGO';
$rand_session   = (isset($config['rand_session']))? $config['rand_session'] : '647918c4f2793d34fc039805691d5c3c' ;
$session_id     = md5(session_id().$rand_session);
// has session
function has_session()
{
    global $db, $session_id;
    $time       = time() + (60*60*24*7);
    $ip         = get_real_ipaddress();
    $browser    = trim(substr(request_header('User-Agent'), 0, 149));
    if(isset($_SESSION['sessionid']) and $_SESSION['sessionid'] == $session_id and $_SESSION['sessionuserid'])
    {
        if(has_user_found($_SESSION['sessionuserid']))
        {
            return $_SESSION['sessionuserid'];
        }
        else
        {
            unset_session();
            return false;
        }
    }
    else
    {
        return false;
    }
}
// is admin (administrator or super_admin)
function is_admin()
{
    if(has_session())
    {
        $userid     =  has_session();
        $userlevel  = get_user_meta($userid,'userlevel');
        if(in_array($userlevel, array('administrator', 'super_admin')))
        {
            return true;
        }
        else
        {
            return false;
        }  
    }
    else
    {
        return false;
    }
}
// get session userid
function get_session_userid()
{
    global $session_id;
    if(isset($_SESSION['sessionid']) and $_SESSION['sessionid'] == $session_id and $_SESSION['sessionuserid'])
    {
        return $_SESSION['sessionuserid'];
    }
    else
    {
        return false;
    }
}
// get session
function get_session($login = false, $location = 'index.php' )
{
    global $db, $session_id;
    $time       = time() + (60*60*24*7);
    $ip         = get_real_ipaddress();
    $browser    = trim(substr(request_header('User-Agent'), 0, 149));
    if(has_session())
    {
        if($login == true)
        {
            if($countsession == true)
            {
                @header("location:{$location}");
            }
        }
        else
        {
            if($countsession == true)
            {
                return true;
            }
            else
            {
                return false;
            }
        }
    }
    else
    {
        if($login == true)
        {
            @header("location:{$location}");
        }
        else
        {
            return false;
        }
    }
}
// set session
function set_session($user_id, $autologin = '', $set_admin = '')
{
    global $db, $session_id;
    $ip         = get_real_ipaddress();
    $browser    = trim(substr(request_header('User-Agent'), 0, 149));
    $time       = time() + (60*60*24*7);
    $set_admin  = get_user_meta($user_id,'capabilities', 'user');
    $_SESSION['sessionid']      = $session_id;
    $_SESSION['sessionuserid']  = $user_id;
    setcookie('sessionmega', $session_id, $time); 
    return $session_id;    
}
// update session
function update_session()
{
    global $db, $session_id;
    $ip         = get_real_ipaddress();
    $browser    = trim(substr(request_header('User-Agent'), 0, 149));
    $time       = time();
    $session    = $_SESSION['sessionid'];
}
// unset session
function unset_session()
{
    global $db, $session_id;
    $ip         = get_real_ipaddress();
    $browser    = trim(substr(request_header('User-Agent'), 0, 149));
    unset($_SESSION['sessionid']);
    unset($_SESSION['sessionuserid']);
    return true;
}
?>
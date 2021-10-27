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
ob_start();
session_start();
require_once('./admin-common.php');
$login_error = false;
if(is_admin() and !isset($_REQUEST['do']) and $_REQUEST['do'] != "logout")
{
    @header("location:index.php");
}
if(isset($_REQUEST['action']) or isset($_POST['action'])){}else {$token = admin_set_token();}
if(isset($_REQUEST['do']) and $_REQUEST['do'] == "logout")
{
	unset_session();
}
elseif(isset($_POST['action']) and $_POST['action'] == 'login')
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
            $userlevel = get_user_meta($date['id'],'userlevel');
            if(in_array($userlevel, array('administrator', 'super_admin')))
            {
                set_session($date['id'], $autologin, $userlevel);
                @header("location:index.php");
            }
            else
            {
                $login_error =  true;
            }
        }
        else
        {
            $login_error =  true;
        }
    }
    else
    {
        $login_error = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="robots" content="none" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title><?php echo $config['sitename']; ?> | <?php echo get_admin_languages('login_admin'); ?></title>
    <link rel="stylesheet" href="assets/libs/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="assets/libs/admin/adminlogin.css">
    <link rel="icon" href="assets/images/favicon.ico">
</head>
<body class="login">
<div class="container-fluid">
    <div class="row">
        <div class="faded-bg animated"></div>
        <div class="col-xs-12 col-sm-5 col-md-4 login-sidebar">
            <div class="login-container">
                <?php if($login_error){ echo '<div class="alert alert-warning text-center" role="alert">'.get_admin_languages('login_error').'</div>';} ?>
                <p><?php echo get_admin_languages('login_admin'); ?></p>
                <form class="form-horizontal" method="post" action="admin-login.php">
                    <input type="hidden" name="action" value="login" />
                    <input type="hidden" name="token" value="<?php echo $token;?>" />
                    <input type="hidden" name="remember" value="1">
                    <div class="form-group form-group-default" id="emailGroup">
                        <label><?php echo get_admin_languages('email_address'); ?></label>
                        <div class="controls">
                            <input type="text" name="email" id="email" value="" placeholder="<?php echo get_admin_languages('email_address'); ?>" class="form-control" required>
                         </div>
                    </div>
                    <div class="form-group form-group-default" id="passwordGroup">
                        <label><?php echo get_admin_languages('password'); ?></label>
                        <div class="controls">
                            <input type="password" name="password" placeholder="<?php echo get_admin_languages('password'); ?>" class="form-control" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-block login-button">
                        <span class="signingin hidden"><?php echo get_admin_languages('login'); ?>....</span>
                        <span class="signin"><?php echo get_admin_languages('login'); ?></span>
                    </button>
              </form>
              <div style="clear:both"></div>
            </div> <!-- .login-container -->
        </div>
        <div class="hidden-xs col-sm-7 col-md-8">
            <div class="clearfix">
                <div class="col-sm-12 col-md-10 col-md-offset-2">
                    <div class="logo-title-container">
                        <img class="img-responsive float-right flip logo hidden-xs animated fadeIn" src="assets/images/logo.png" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- .row -->
</div> <!-- .container-fluid -->
<script>
    var btn      = document.querySelector('button[type="submit"]');
    var form     = document.forms[0];
    var email    = document.querySelector('[name="email"]');
    var password = document.querySelector('[name="password"]');
    btn.addEventListener('click', function(ev){
        if (form.checkValidity()) {
            btn.querySelector('.signingin').className = 'signingin';
            btn.querySelector('.signin').className = 'signin hidden';
        } else {
            ev.preventDefault();
        }
    });
    email.focus();
    document.getElementById('emailGroup').classList.add("focused");
    email.addEventListener('focusin', function(e){
        document.getElementById('emailGroup').classList.add("focused");
    });
    email.addEventListener('focusout', function(e){
       document.getElementById('emailGroup').classList.remove("focused");
    });
    password.addEventListener('focusin', function(e){
        document.getElementById('passwordGroup').classList.add("focused");
    });
    password.addEventListener('focusout', function(e){
       document.getElementById('passwordGroup').classList.remove("focused");
    });
</script>
</body>
</html>
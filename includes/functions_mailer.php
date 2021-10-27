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

// get contact mailer
function get_contact_mailer($date = '', $subtitle= '', $dir = false)
{
    global $db, $config;
    $assign = array();
    $assign['attachments']          = array();
    $assign['{$time}']              = date("g:i a");
    $assign['{$date}']              = date("Y/m/d");
    $assign['{$dateen}']            = date("F j, Y");
    $assign['{$year}']              = date("Y");
    $assign['{$siteurl}']           = $config['siteurl'];
    $assign['{$sitename}']          = $config['sitename'];
    $assign['{$sitemail}']          = $config['sitemail'];
    $assign['{$sitelogomail}']      = $config['logomailer'];
    $assign['{$facebook}']          = $config['facebook'];
    $assign['{$twitter}']           = $config['twitter'];
    $assign['{$google}']            = $config['googleplus'];
    $assign['{$youtube}']           = $config['youtube'];
    $assign['{$ip}']                = get_real_ipaddress();
    return $assign;
}
// get format contact
function get_format_contact($contact)
{
    $date['contact'] = (is_array($contact))? $date['contact'] : $contact;
    return nl2br(str_replace('\r\n', '<br />', $date['contact']));
}
// get template mailer
function get_template_mailer($type, $theme, $lang, $column)
{
    global $db;
    $sql    = "SELECT * FROM ".EMAILTEMPLATES_TABLE." WHERE `type`='{$type}' AND `theme`='{$theme}' AND `language`='{$lang}'";
    $result = $db->sql_query($sql);
    $row    = $db->sql_fetchrow($result);
    return $row[$column];    
}
// get admin email phpmailer
function get_admin_email_phpmailer()
{
    global $db, $config;
    $email  = '';
    $sql    = "SELECT * FROM ".USERS_TABLE."  JOIN ".USERSMETA_TABLE." ON (`meta_key`='userlevel') AND (`user_id`=`id`) 
    WHERE `meta_value` IN ('administrator', 'super_admin', 'staff')";
    $result = $db->sql_query($sql);
    while($row = $db->sql_fetchrow($result))
    {
        $email .= "{$row['email']},";
    }
    return $email.$config['siteemail'];
}
// send email
function mail_send($date = array())
{
    global $config;
    $theme                  = 'default';
    $lang                   = (isset($config['lang_mailer']))? $config['lang_mailer'] : 'en';
    $ex_template['body']    = get_template_mailer('body', $theme, $lang, 'content');
    $ex_template['content'] = get_template_mailer($date['type'], $theme, $lang, 'content');
    $assign                 = get_contact_mailer($date);
    if(!isset($assign['title']))
    {
        $assign['title'] = get_template_mailer($date['type'], $theme, $lang, 'title');
    }
    if(isset($date['contact']))
    {
       $assign['contact'] = get_format_contact($date['contact']);
    }
    if(isset($date['title']))
    {
       $assign['title'] = $date['title'];
    }
    if($date['type'] == 'contact_us')
    {
        $assign['{$email}']     = $date['email'];
        $assign['{$username}']  = $date['username'];
        $assign['{$subject}']   = $date['subject'];
        $assign['{$message}']   = $date['message'];
    }
    elseif($date['type'] == 'register_activation')
    {
        $assign['{$email}']     = $date['email'];
        $assign['{$firstname}'] = $date['firstname'];
        $assign['{$lastname}']  = $date['lastname'];
        $assign['{$username}']  = $date['username'];
        $assign['{$key}']       = $date['key'];
    }
    elseif($date['type'] == 'register')
    {
        $assign['{$email}']     = $date['email'];
        $assign['{$firstname}'] = $date['firstname'];
        $assign['{$lastname}']  = $date['lastname'];
        $assign['{$username}']  = $date['username'];
    }
    elseif($date['type'] == 'reset_password')
    {
        $assign['{$email}']     = $date['email'];
        $assign['{$firstname}'] = $date['firstname'];
        $assign['{$lastname}']  = $date['lastname'];
        $assign['{$username}']  = $date['username'];
        $assign['{$key}']       = $date['key'];
    }
    elseif($date['type'] == 'update_password')
    {
        $assign['{$new_password}']  = $date['new_password'];
        $assign['{$email}']         = $date['email'];
        $assign['{$firstname}']     = $date['firstname'];
        $assign['{$lastname}']      = $date['lastname'];
        $assign['{$username}']      = $date['username'];
    }
    $title          = $config['sitename'].' - '.$assign['title'];
    $body_html	    = @str_replace('{$bodyhtml}', $ex_template['content'], $ex_template['body']);
    $message        = @str_replace(array_keys($assign), array_values($assign), $body_html);
    $attachments    = (isset($assign['attachments']))? $assign['attachments'] : array();
    foreach($date['send_to'] as $key => $value)
    {
        switch($key)
        {
            case"admin":
                $to = get_admin_email_phpmailer();
            break;
            case"user":
                $to = get_user_column($value, 'email');
            break;
            case"site":
                $to = $config['sitemail'];
            break;
            default:
                $to = get_user_column($value, 'email');
            break;
        }
        send_phpmailer($to, $title, $message, $attachments);
    }
}
// send phpmailer
function send_phpmailer($to, $subject, $message, $attachments = array() )
{
    global $db, $config, $template, $session;
    if ( !isset($phpmailer) ) {
		require_once(ABSPATH . 'includes/phpmailer/Exception.php');
        require_once(ABSPATH . 'includes/phpmailer/PHPMailer.php');
		require_once(ABSPATH . 'includes/phpmailer/SMTP.php');
		$phpmailer = new PHPMailer();
	}
	$phpmailer->ClearAllRecipients();
	$phpmailer->ClearAttachments();
	$phpmailer->ClearCustomHeaders();
	$phpmailer->ClearReplyTos();
    if($config['mailhost'] and $config['mailusername'] and $config['mailpassword'])
    {
        $config['mailport'] = (isset($config['mailport']))? $config['mailport'] : '587';
        $config['mailencryption'] = (isset($config['mailencryption']))? $config['mailencryption'] : 'tls';
        $phpmailer->isSMTP();                                   // Set mailer to use SMTP
        $phpmailer->Host = $config['mailhost'];                 // Specify main and backup SMTP servers
        $phpmailer->SMTPAuth = true;                            // Enable SMTP authentication
        $phpmailer->Username = $config['mailusername'];         // SMTP username
        $phpmailer->Password = $config['mailpassword'];         // SMTP password
        $phpmailer->SMTPSecure = $config['mailencryption'];     // Enable TLS encryption, `ssl` also accepted
        $phpmailer->Port = $config['mailport'];                 // TCP port to connect to
        if ( !isset( $from_email ) ) {
            $sitename = strtolower( $_SERVER['SERVER_NAME'] );
            if ( substr( $sitename, 0, 4 ) == 'www.' ) {
                $sitename = substr( $sitename, 4 );
            }
            $from_email = $config['mailusername'].'@'.$sitename;
        }
        $phpmailer->From = $from_email;
    }
    else
    {
        if ( !isset( $from_email ) ) {
            $sitename = strtolower( $_SERVER['SERVER_NAME'] );
            if ( substr( $sitename, 0, 4 ) == 'www.' ) {
                $sitename = substr( $sitename, 4 );
            }
            $from_email = 'info@' . $sitename;
        }
        $phpmailer->From = $from_email;
    }
    
	
	$phpmailer->FromName = $config['sitename'];
	if ( !is_array( $to ) )
		$to = explode( ',', $to );
	foreach ( (array) $to as $recipient ) {
		try {
			$recipient_name = '';
			if( preg_match( '/(.*)<(.+)>/', $recipient, $matches ) ) {
				if ( count( $matches ) == 3 ) {
					$recipient_name = $matches[1];
					$recipient = $matches[2];
				}
			}
			$phpmailer->AddAddress( $recipient, $recipient_name);
		} catch ( phpmailerException $e ) {
			continue;
		}
	}
    $phpmailer->IsHTML( true );
	$phpmailer->Subject = $subject;
	$phpmailer->Body    = $message;
	$phpmailer->IsMail();
	$content_type = 'text/html';
	$phpmailer->ContentType = $content_type;
	$phpmailer->CharSet = $config['charset'];
    foreach($attachments as $key => $value)
    {
        $phpmailer->addAttachment($value, $key);
    }
	try {
		return $phpmailer->Send();
	} catch ( phpmailerException $e ) {
		return false;
	}
}
?>
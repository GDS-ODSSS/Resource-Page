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

class admin_emailtemplate
{
    public function index_emailtemplate()
    {
        global $token, $db, $config;
        admin_header(get_admin_languages('emailtemplate'));
        admin_content_header(array('title' => get_admin_languages('emailtemplate')));
        echo admin_content_section_start().'<div class="col-md-12">';
        echo '<table class="table table-striped table-bordered"><thead><tr>
        <th>'.get_admin_languages('title').'</th>
        <th style="width: 100px;" class="text-center">'.get_admin_languages('language').'</th>
        <th style="width: 150px;" class="text-center">'.get_admin_languages('actions').'</th>
        </tr></thead><tbody>';
        $result  = $db->sql_query("SELECT * FROM ".EMAILTEMPLATES_TABLE." ORDER BY language ASC, title ASC");
        while ($row = $db->sql_fetchrow($result)) 
        {
            echo '
            <tr>
                <td>'.$row['title'].'</td>
                <td class="text-center">'.$row['language'].'</td>
                <td class="text-center">
                    <a class="btn btn-sm btn-success" href="emailtemplate.php?mode=edit&id='.$row['id'].'">'.get_admin_languages('edit').'</a>
                    <a class="btn btn-sm btn-info" href="emailtemplate.php?mode=preview&id='.$row['id'].'">'.get_admin_languages('preview').'</a>
                </td>
            </tr>';
        }
        echo '</tbody></table></div>
        ';
        
        echo admin_content_section_end();
        admin_footer();
    }
    
    public function index_edit()
    {
        global $token, $db, $config;
        $id     = intval($_GET['id']);
        $sql = "SELECT * FROM ".EMAILTEMPLATES_TABLE." WHERE `id`='{$id}'";
        if(!$db->sql_numrows($sql))
        {
            @header("Location:emailtemplate.php");
        }
        $result     = $db->sql_query($sql);
        $row        = $db->sql_fetchrow($result);
        $content    = $row['content'];
        $vars       = $row['vars'];
        if(isset($_SESSION['action_token'])):
            $message = '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'.$_SESSION['action_token'].'</div>';
            unset($_SESSION['action_token']);
        else:
            $message = '';
        endif;
        $button_head = '<a href="emailtemplate.php" class="btn btn-sm btn-primary">'.get_admin_languages('back').'</a>';
        $ptitle = get_admin_languages('edit').': '.$row['title'];
        admin_header($ptitle);
        admin_content_header(array('title' => $ptitle, 'button' => $button_head));
        echo admin_content_section_start();
        echo '<div class="col-md-12">'.$message.'
        <form action="emailtemplate.php?mode=edit&id='.$id.'" method="post">
            <input type="hidden" name="token" value="'.$token.'">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id" value="'.$id.'">
            <div class="row">
            <div class="col-md-8">
                    <textarea class="form-control" rows="20" name="content">'.$content.'</textarea>
            </div>';
        echo '
        <div class="col-md-4">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">'.get_admin_languages('update').'</h3>
                    <div class="box-tools pull-right"><button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fas fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-footer"><button class="button button-primary">'.get_admin_languages('update').'</button></div>
            </div>
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">vars</h3>
                    <div class="box-tools pull-right"><button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fas fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body">
                <strong>'.get_admin_languages('default').'</strong>
                <pre>{$time}         = The current Time
{$date}         = The current Date
{$year}         = The current Year
{$siteurl}      = Site URL
{$sitename}     = Site Title
{$sitemail}     = Site Email
{$sitelogomail} = URL Logo
{$facebook}     = Facebook link
{$twitter}      = Twitter link
{$youtube}      = Youtube link
{$ip}           = IP user</pre>
                
                '.$vars.'
                </div>
            </div>
            </div>
        
        </div>
        </div>
        </form>
        ';
        echo admin_content_section_end();
        admin_footer();
    }
    
    public function index_preview()
    {
        global $token, $db, $config;
        $id     = intval($_GET['id']);
        $sql = "SELECT * FROM ".EMAILTEMPLATES_TABLE." WHERE `id`='{$id}'";
        if(!$db->sql_numrows($sql))
        {
            @header("Location:emailtemplate.php");
        }
        $result     = $db->sql_query($sql);
        $row        = $db->sql_fetchrow($result);
        $content    = $row['content'];
        $vars       = $row['vars'];
        if(isset($_SESSION['action_token'])):
            $message = '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'.$_SESSION['action_token'].'</div>';
            unset($_SESSION['action_token']);
        else:
            $message = '';
        endif;
        $button_head = '<a href="emailtemplate.php" class="btn btn-sm btn-primary">'.get_admin_languages('back').'</a>';
        
        $ptitle = get_admin_languages('preview').': '.$row['title'];
        admin_header($ptitle);
        admin_content_header(array('title' => $ptitle, 'button' => $button_head));
        echo admin_content_section_start();
        $assign = array();
        if($row['type'] != 'body')
        {
            $language = $row['language'];
            $result_body = $db->sql_query("SELECT * FROM ".EMAILTEMPLATES_TABLE." WHERE `type`='body' and `language`='{$language}'");
            $row_body    = $db->sql_fetchrow($result_body);
            $assign['{$bodyhtml}'] = $row['content'];
            $message = $row_body['content'];
        }
        else
        {
            $message = $row['content'];
        }
        
        $assign['{$time}']          = date("g:i a");
        $assign['{$date}']          = date("Y/m/d");
        $assign['{$dateen}']        = date("F j, Y");
        $assign['{$year}']          = date("Y");
        $assign['{$siteurl}']       = $config['siteurl'];
        $assign['{$sitename}']      = $config['sitename'];
        $assign['{$sitemail}']      = $config['sitemail'];
        $assign['{$sitelogomail}']  = $config['logomailer'];
        $assign['{$facebook}']      = $config['facebook'];
        $assign['{$twitter}']       = $config['twitter'];
        $assign['{$youtube}']       = $config['youtube'];
        $assign['{$ip}']            = get_real_ipaddress();
        $assign['{$username}']      = 'username';
        $assign['{$email}']         = 'email';
        $assign['{$subject}']       = 'subject';
        $assign['{$message}']       = 'message';
        $assign['{$firstname}']     = 'firstname';
        $assign['{$lastname}']      = 'lastname';
        $assign['{$keyforget}']     = 'keyforget';
        $message_html    = @str_replace(array_keys($assign), array_values($assign), $message);
        echo '<div class="col-md-12">'.$message_html.'</div>';
        echo admin_content_section_end();
        admin_footer();
    }
    
    public function index_update()
    {
        global $token, $db, $config;
        $id     = intval($_POST['id']);
        $sql    = "SELECT * FROM ".EMAILTEMPLATES_TABLE." WHERE `id`='{$id}'";
        $token  = safe_input($_POST['token']);
        if($token == $_SESSION['securitytokenadmincp'])
        {
            $content = str_replace(array("'"), array("\'"), $_POST['content']);
            $result = $db->sql_query("UPDATE ".EMAILTEMPLATES_TABLE." SET `content`='{$content}' WHERE `id`='{$id}'");
            $_SESSION['action_token'] = get_admin_languages('updated');
        }
        else
        {
            $_SESSION['action_token'] = get_admin_languages('error_update');
        }
        @header("Location:emailtemplate.php?mode=edit&id={$id}");
    }
}
?>
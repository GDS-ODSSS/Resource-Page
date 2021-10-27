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

if(!class_exists('admin_ajax_index'))
{
    require_once(ACPINC_ABSPATH.'admin-class-media.php');
    class admin_ajax_index extends admin_class_media
    {
        // construct
        function __construct()
        {
            global $hooks;
            $hooks->add_action('admin_ajax_index_display', array($this , 'ajax_settings'), 10);
            $hooks->add_action('admin_ajax_index_display', array($this , 'ajax_users'), 20);
            $hooks->add_action('admin_ajax_index_display', array($this , 'ajax_save_phrase'), 30);
            $hooks->add_action('admin_ajax_index_display', array($this , 'ajax_async_upload'), 30);
            $hooks->add_action('admin_ajax_index_display', array($this , 'ajax_megapanel_geticons'), 10);
        }
        // settings
        function ajax_settings()
        {
            if(isset($_REQUEST['action']) and $_REQUEST['action'] == 'timeformat')
            {
                echo get_date_time_format(date($_REQUEST['date'],time()));
                exit;
            }
            elseif(isset($_REQUEST['action']) and $_REQUEST['action'] == 'dateformat')
            {
                echo get_date_time_format(date($_REQUEST['date'],time()));
                exit;
            }
        }
        // ajax users
        function ajax_users()
        {
            if(isset($_REQUEST['do']))
            {
                if($_REQUEST['do'] == 'emailexist')
                {
                    $email   = $_REQUEST['email'];
                    $numrows = $db->sql_numrows("SELECT * FROM ".USERS_TABLE." WHERE `email`='".$email."'");
                    if($numrows)
                    {
                        echo '<span class="messege error">'.get_admin_languages('email_another_user').'</span>';
                    }
                    else
                    {
                        echo '<span class="messege done">'.get_admin_languages('email_available_registration').'</span>';
                    }
                }
                elseif($_REQUEST['do'] == 'userexist')
                {
                    $username   = $_REQUEST['uname'];
                    $numrows    = $db->sql_numrows("SELECT * FROM ".USERS_TABLE." WHERE `username`='".$username."'");
                    if($numrows)
                    {
                        echo '<span class="messege error">'.get_admin_languages('name_not_available_registration').'</span>';
                    }
                    else
                    {
                        echo '<span class="messege done">'.get_admin_languages('name_available_registration').'</span>';
                    }
                }
            }
        }
        // save phrarse
        function ajax_save_phrase()
        {
            global $db;
            if(isset($_GET['action']) and $_GET['action'] == 'savephrase')
            {
                $phrasetext = safe_input($_REQUEST['phrasetext']);
                $phraseid   = safe_input($_REQUEST['phraseid']);
                $result     = $db->sql_query("UPDATE ".PHRASE_TABLE." SET `text`='{$phrasetext}' WHERE `phraseid`='{$phraseid}'");
                $db->sql_freeresult($result);
                echo json_encode(array('status' => 'savephrase', 'html' => $rating));
                exit;
            }
        }
        // upload files
        function ajax_async_upload()
        {
            if(isset($_REQUEST['action']) and $_REQUEST['action'] == 'async_upload')
            {
                $html = '';
                foreach($_FILES['files']['name'] as $key => $name)
                {
                    if($_FILES['files']['error'][$key] === 0 )
                    {
                        $fileup             = array();
                        $fileup['name']     = $_FILES['files']['name'][$key];
                        $fileup['tmp_name'] = $_FILES['files']['tmp_name'][$key];
                        $fileup['size']     = $_FILES['files']['size'][$key];
                        $fileup['type']     = $_FILES['files']['type'][$key];
                        $file = ajax_upload_file($fileup);
                        if($file['status'] == true)
                        {
                            if($_REQUEST['retype'] == 'thickbox')
                            {
                                echo '
                                <li class="" role="checkbox" data-id="'.$file['postid'].'" aria-checked="true" id="attachment-item-'.$file['postid'].'">
                                    <div class="check"><div class="media-icon"></div></div>
                                    <div class="attachment-preview">
                                        <div class="thumbnail-item">
                                            <img src="'.$file['url'].'" />
                                        </div>
                                    </div>
                                </li>
                
                                ';
                            }
                            else
                            {
                                $html .= '
                                <div class="media-item" id="media-item-'.$file['postid'].'">
                                    <img class="thump" src="'.$file['url'].'" alt="">
                                    <a class="edit-attachment" href="media.php?mode=edit&id='.$file['postid'].'" target="_blank">'.get_admin_languages('edit').'</a>
                                    <div class="filename">'.$file['filename'].'</div>
                                </div>
                                ';
                            }
                        }
                        else
                        {
                            $html .= '
                            <div class="media-item error" id="media-item-">
                                <div class="error-div error">
                                    <a class="dismiss" href="javascript:void(0);" onclick="jQuery(this).parents(\'div.media-item\').slideUp(200, function(){jQuery(this).remove();});">'.get_admin_languages('dismiss').'</a>
                                    <strong>“'.$file['filename'].'” '.get_admin_languages('has_failed_to_upload').'.</strong>
                                    <br>'.get_admin_languages('sorry_this_file_type_is_not_permitted_for_security_reasons').'.
                                </div>
                            </div>
                            ';
                        }
                    }
                }
                echo $html;
                exit;
            }
        }
        // ajax megapanel get icons
        function ajax_megapanel_geticons()
        {
            if(isset($_REQUEST['action']) and $_REQUEST['action'] == 'megapanel_geticons')
            {
                $json_files = array(
                    'default'       => array('title' => 'Default Icons', 'file' => ADMIN_ABSPATH.'assets/libs/json/icons.json'),
                    'peicon7stroke' => array('title' => 'Pe Icon 7 Stroke', 'file' => ADMIN_ABSPATH.'assets/libs/json/peicon7stroke.json'),
                    'fontawesome'   => array('title' => 'Font Awesome', 'file' => ADMIN_ABSPATH.'assets/libs/json/fontawesome.json'),
                );
                foreach($json_files as $key => $file)
                {
                    echo '<div class="megapanel-options-head-items">
                    <h3><span class="megapanel-title-item">'.$file['title'].'</span><span class="megapanel_tools collapse-button"><i class="fa fa-minus"></i></span></h3>
                    <div class="megapanel-options-content megapanel-toggle-content ">';
                    
                    if( file_exists($file['file']) ) {
                        $object = json_decode( file_get_contents($file['file']) );
                        if( is_object( $object ) ) {
                            foreach ( $object->icons as $icon ) {
                                echo '<a class="megapanel-icon-tooltip" data-geticon="'. $icon .'"><span class="megapanel-icon megapanel-selector"><i class="'. $icon .'"></i></span></a>';
                            }
                        } else {
                            echo '<h4 class="megapanel-dialog-title">Error! Can not load json file.</h4>';
                        }
                    }
                    else
                    {
                        echo '<h4 class="megapanel-dialog-title">Error! Can not load json file.</h4>';
                    }
                    echo '</div></div>';
                }
                exit;
            }
        }
    }
    new admin_ajax_index();
}
?>
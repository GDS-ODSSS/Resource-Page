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
if(!is_admin())
{
    exit;
}
require_once(ACPINC_ABSPATH.'admin-class-media.php');
class admin_media_upload extends admin_class_media
{
    protected $type             = 'image';
    protected $typeli           = '';
    protected $li_library       = '';
    protected $li_url           = '';
    protected $li_type          = '';
    protected $li_gallery       = '';
    protected $li_about         = '';
    protected $uploadtype       = 'plupload';
    protected $limit            = 10;
    protected $allowed          = 'jpg|jpeg|png|gif|ico|pdf|doc|docx|ppt|pptx|pps|ppsx|xls|xlsx|psd|odt|mp3|m4a|ogg|wav|mp4|m4v|mov|wmv|avi|mpg|ogv|3gp|3g2|zip|7z|swf';
    protected $allowedimage     = 'image/jpg|image/jpeg|image/png|image/gif|image/x-icon';
    protected $alloweddocuments = 'pdf|doc|docx|ppt|pptx|pps|ppsx|xls|xlsx|psd|odt';
    protected $allowedaudio     = 'mp3|m4a|ogg|wav';
    protected $allowedvideo     = 'mp4|m4v|mov|wmv|avi|mpg|ogv|3gp|3g2';
    protected $maxlength        = '10';
    protected $maxsize          = '30';
    protected $pathupload       = '../uploads/';
    protected $pathuploadurl    = '';
    protected $folderupload     = 'uploads';
    protected $pathassets       = 'assets/libs/cupload/';
    protected $uploaddir        = '';
    protected $folderuploads    = '';
    /* construct */
    public function __construct()
    {
        global $hooks, $db, $config;
        if ( isset($_POST['action']) and $_POST['action'] == 'handler' ):
            $this->media_upload_library();
        elseif ( isset($_POST['action']) and $_POST['action'] == 'loadmedia' ):
            $this->media_load_library();
        else:
            $this->set_header();
            echo '<div class="content-tab content-library">';
            $this->get_library_while();
            echo '
            </div>    
            <div class="content-tab content-upload hidden">';
            echo $this->get_form_upload(array('type' => 'thickbox'));
            echo '</div>';
            $this->set_footer();
        endif;
    }
    /* get library while */
    function get_library_while($type = 'library')
    {
        global $db;
        echo '<div id="media-items" class="media-items-library" data-loading="0" data-thelast="0"><ul class="media-attachments">';
        $page       = (int) (isset($arg['1']) and $arg['1'] == 'page')? safe_input($arg['2']) : 1 ;
        $limit      = 32;
        $startpoint = ($page * $limit) - $limit;
        $sql        = "SELECT * FROM ".POSTS_TABLE." WHERE `post_type`='attachment' ORDER BY `id` DESC";
        $result     = $db->sql_query_limit($sql, $limit, $startpoint);
        $total      = $db->sql_numrows($sql);
        $lastpage   = ceil($total/$limit);
        if($total):
            if($type == 'gallery'):
                $this->get_gallery_while_format($result);
            else:
                $this->get_library_while_format($result);
            endif;
        else:
        endif; 
        echo '</ul></div>';
    }

    function media_load_library()
    {
        global $db;
        
        $page       = (int) (isset($_POST['page']))? safe_input($_POST['page']) : 1 ;
        $limit      = 32;
        $startpoint = ($page * $limit) - $limit;
        $sql        = "SELECT * FROM ".POSTS_TABLE." WHERE `post_type`='attachment' ORDER BY `id` DESC";
        $result     = $db->sql_query_limit($sql, $limit, $startpoint);
        $total      = $db->sql_numrows($sql);
        $lastpage   = ceil($total/$limit);
        if($page <= $lastpage)
        {
            $this->get_library_while_format($result);
        }
    }
    /* get library while format */
    function get_library_while_format($result)
    {
        global $db, $config;
        
        while ($row = $db->sql_fetchrow($result)) 
        {
            $meta = maybe_unserialize(get_post_meta($row['id'],'attachment_file'));
            $info = new SplFileInfo($this->uploaddir.$meta['file']);
            $extension = pathinfo($info->getFilename(), PATHINFO_EXTENSION);
            $image_ext = get_image_ext();
            if(! empty( $image_ext[ $meta['mimetype'] ] ))
            {
                $thumbnail = trim($config['siteurl'], '/').'/'.$meta['thumbnail'];
                echo '
                <li class="" role="checkbox" data-id="'.$row['id'].'" aria-checked="true" id="attachment-item-'.$row['id'].'">
                    <div class="check"><div class="media-icon"></div></div>
                    <div class="attachment-preview">
                        <div class="thumbnail-item">
                            <img src="'.$thumbnail.'" />
                        </div>
                    </div>
                </li>
                ';
            }
            else
            {
                
            }   
        }
    }
    /* header */
    function set_header($title = 'Insert Media', $tab_current = '')
    {
        global $config;
        $get_dir = get_language_direction($config['language']);
        $page_title = (isset($page_title))? $page_title : '';
        echo '<!DOCTYPE html>
        <!--[if IE 8]>
        <html xmlns="http://www.w3.org/1999/xhtml" class="ie8"  lang="'.get_language_country_abbreviation($config['language']).'">
        <![endif]-->
        <!--[if !(IE 8) ]><!-->
        <html xmlns="http://www.w3.org/1999/xhtml" lang="'.get_language_country_abbreviation($config['language']).'">
        <!--<![endif]-->
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>'.$config['sitename'].' &#8212; '.$page_title.'</title>
        <link rel="shortcut icon" href="assets/images/favicon.png">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <link rel="stylesheet" href="assets/libs/bootstrap/bootstrap.min.css" />
        <link rel="stylesheet" href="assets/libs/drag-drop/drag-drop.css" />
        <link rel="stylesheet" href="assets/libs/admin/media-upload.min.css" />
        <script type="text/javascript" src="assets/libs/jquery/jquery.js"></script>
        <script type="text/javascript" src="assets/libs/jquery/jquery-ui.js"></script>
        <script type="text/javascript" src="assets/libs/cupload/js/thickbox/thickbox.min.js"></script>
        </head>
        <body id="creative-media-upload">
        <div class="creative-media-upload-header">
            <h1>'.$title.'</h1>
            <button type="button" class="creative-media-upload-close" onclick="try{top.tb_remove();}catch(e){}; return false;"></button>
            <ul class="creative-media-upload-sidemenu">
                <li><a href="#" data-type="upload" class="upload">Upload Files</a></li>
                <li><a href="#" data-type="library" class="library current">Media Library</a></li>
            </ul>
        </div>
        <div class="creative-media-upload-browser">
            <div class="creative-media-upload-content">
        ';
    }
    /* footer */
    function set_footer($button_text = 'Use this image')
    {
        global $config;
        echo '
                </div>
            </div>
            <div id="media-upload-content-toolbar">
                <button type="button" data-id="0" class="button button-media-select" disabled="disabled">'.$button_text.'</button>
                <div class="info"></div>
            </div>
            <script type="text/javascript">
                var ajaxRequests = [];
                var pageload = 1;
                var admin_media_upload_url = "'.$config['siteurl'].'/'.ADMIN_DASHBOARD.'/media-upload.php";
                var admin_ajax_url = "'.$config['siteurl'].'/'.ADMIN_DASHBOARD.'/admin_ajax.php";
                $(function() {$("table th input:checkbox").on("click" , function(){var that = this;$(this).closest("table").find("tr > td:first-child input:checkbox").each(function(){this.checked = that.checked;$(this).closest("tr").toggleClass("selected");});	});})
            </script>
            <script id="script_mediaupload" type="text/javascript" src="assets/libs/admin/media-upload.min.js"></script>
            <script type="text/javascript" src="assets/libs/drag-drop/drag-drop.js"></script>
        </body>
        </html>
        ';
    }
    /* media upload library */
    function media_upload_library() {
        global $db, $config;
        if(isset($_POST['send']) and $_POST['send'] == 'gallery')
        {
            
        }
        else
        {
            $send_id    = intval($_POST['id']);
            $result     = $db->sql_query("SELECT * FROM ".POSTS_TABLE." WHERE `id`='".$send_id."'");
            $row        = $db->sql_fetchrow($result);
            $meta       = maybe_unserialize(get_post_meta($row['id'],'attachment_file'));
            $file['fileid']         = $row['id'];
            $file['fileurl']        = trim($config['siteurl'], '/').'/'.$this->folderuploads.$meta['file'];
            $file['filethumbnail']  = trim($config['siteurl'], '/').'/'.$this->folderuploads.$meta['file'];
            $file['thumbnail']      = trim($config['siteurl'], '/').'/'.$this->folderuploads.$meta['thumbnail'];
            echo json_encode($file);
            exit;
        }
        
    }
    /* media upload form handler */
    function media_upload_form_handler() {
        global $db;
        if ( isset($_POST['gallery_item']) ):
            $idarray = implode(',',$_POST['gallery_item']);
            return $this->media_send_to_gallery('<input value="'.$idarray.'" />');    
        elseif ( isset($_POST['get_url']) ):
            return $this->media_send_to_editor('<img src="'.$_POST['src'].'" alt="'.$_POST['title'].'">');
        else:
            if ( isset($_POST['send']) ):
                $keys     = array_keys($_POST['send']);
                $send_id  = (int) array_shift($keys);
            endif;
            if ( isset($send_id) ):
                $result = $db->sql_query("SELECT * FROM ".POSTS_TABLE." WHERE `id`='".$send_id."'");
                $row    = $db->sql_fetchrow($result);
                $meta   = maybe_unserialize(get_post_meta($row['id'],'attachment_file'));
                $file['fileid']     = $row['id'];
                $file['fileurl']    = $this->folderuploads.$meta['file'];
                $file['filethumbnail']  = $this->folderuploads.$meta['file'];
                $file['thumbnail']  = $this->folderuploads.$meta['thumbnail'];
                return $this->media_send_to_editor(json_encode($file));
            endif;
        endif;
    }
    /* media send to editor */
    function media_send_to_editor($html) {
        ?>
        <script type="text/javascript">
        /* <![CDATA[ */
        var win = window.dialogArguments || opener || parent || top;
        win.tb_remove();
        win.send_to_editor('<?php echo $html; ?>');
        /* ]]> */
        </script>
        <?php
    }
    /* media send to gallery */
    function media_send_to_gallery($html) {
        ?>
        <script type="text/javascript">
        /* <![CDATA[ */
        var win = window.dialogArguments || opener || parent || top;
        win.tb_remove();
        win.send_to_gallery('<?php echo addslashes($html); ?>');
        /* ]]> */
        </script>
        <?php
    }
}
/* start class auto */
new admin_media_upload();
?>
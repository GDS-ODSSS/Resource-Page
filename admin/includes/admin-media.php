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

require_once(ACPINC_ABSPATH.'admin-class-media.php');
class admin_media extends admin_class_media
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
    protected $allowed          = 'jpg|jpeg|png|gif|svg|ico|pdf|doc|docx|ppt|pptx|pps|ppsx|xls|xlsx|psd|odt|mp3|m4a|ogg|wav|mp4|m4v|mov|wmv|avi|mpg|ogv|3gp|3g2|zip|7z|swf';
    protected $allowedimage     = 'image/jpg|image/jpeg|image/png|image/gif|image/svg+xml|image/x-icon';
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
    

    function __construct()
    {
        global $hooks;
        
    }
    function admin_settings_print_script()
    {
        echo '<script type="text/javascript" src="assets/libs/drag-drop/drag-drop.js"></script>';
    }
    // index media
    function index_media()
    {       
        global $hooks;
        if(isset($_REQUEST['mode']) and $_REQUEST['mode'] == 'edit' and isset($_GET['id'])):
            $this->index_edit();
        elseif(isset($_REQUEST['mode']) and $_REQUEST['mode'] == 'upload'):
            $this->index_upload();
        elseif(isset($_POST['query']) and $_POST['query'] == 'upload'):
            $this->post_insert();
        elseif(isset($_POST['query']) and $_POST['query'] == 'update'):
            $this->post_update();
        elseif(isset($_POST['query']) and $_POST['query'] == 'action'):
            $mark = (isset($_POST['mark']))? $_POST['mark'] : false;
            $this->post_query_action($_POST['idx'],$mark,$_POST['action'],$_POST['token']);
        elseif(isset($_REQUEST['action']) and $_REQUEST['action'] == 'delete'):
            $this->post_delete();
        else:
            $this->index_library();
        endif;
    }
    // index library 
    function index_library()
    {
        global $db, $config, $hooks, $token;
        if(isset($_SESSION['action_token'])):
            $message = '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'.$_SESSION['action_token'].'</div>';
            unset($_SESSION['action_token']);
        else:
            $message = '';
        endif;
        $button_head = '<a href="media.php?mode=upload" class="btn btn-sm btn-primary">'.get_admin_languages('upload').'</a>';
        admin_header(get_admin_languages('media_library'));
        admin_content_header(array('title' => get_admin_languages('media_library'), 'button' => $button_head));
        echo admin_content_section_start().'
        <div class="col-md-12">
        '.$message.'
        <form action="media.php" method="post">
        <input type="hidden" name="token" value="'.$token.'">
        <input type="hidden" name="query" value="action">
        <table id="jq-table" class="table table-striped table-bordered media-library">
    	<thead><tr>
        <th style="width: 15px;" class="center th-checkbox"><label><input type="checkbox" /><span class="lbl"></span></label></th>
        <th>'.get_admin_languages('title').'</th>
        <th class="hidden-phone">'.get_admin_languages('author').'</th>
        <th style="width: 90px;text-align: center;" class="hidden-phone">'.get_admin_languages('date').'</th>
        </tr></thead><tbody>
        ';
        $sql        = "SELECT * FROM ".POSTS_TABLE." WHERE `post_type`='attachment' ORDER BY post_modified DESC";
        $result     = $db->sql_query($sql);
        while ($row = $db->sql_fetchrow($result)) 
        {
            
            $meta = maybe_unserialize(get_post_meta($row['id'],'attachment_file'));
            $info = new SplFileInfo($this->uploaddir.$meta['file']);
            $extension = pathinfo($info->getFilename(), PATHINFO_EXTENSION);
            $image_ext = get_image_ext();
            if(! empty( $image_ext[ $meta['mimetype'] ] ))
            {
                $thumbnail = trim($config['siteurl'], '/').'/'.$meta['thumbnail'];
                $moreinfo = "<strong>Dimensions</strong>: {$meta['width']} × {$meta['height']} <br>";
            }
            else
            {
                $thumbnail = 'assets/images/media/'.$extension.'.png';
                $moreinfo = "<strong>Length</strong>: <br>";
            }
            $post_type = (isset($post_type))? $post_type : '';
            $post_status        = ($row['post_status'])? '<span class="sq-post-status disable"></span>' : '<span class="sq-post-status enable"></span>';
            $post_link_edit     = "media.php?mode=edit&id={$row['id']}";
            $actions            = $this->row_actions($post_type, $row);
            $meta['filename']   = (isset($meta['filename']))? $meta['filename'] : '';
            echo '
            <tr>
                <td class="td-checkbox"><label><input type="checkbox" name="mark[]" value="'.$row['id'].'" /><span class="lbl"></span></label>'.$post_status.'</td>
                <td>
                    <div class="post_featured_image"><img src="'.$thumbnail.'" /></div>
                    <strong><a href="'.$post_link_edit.'">'.$row['post_title'].'</a></strong>
                    <p>'.$meta['filename'].'</p>
                    <div class="row-actions">'.$actions.'</div>
                </td>
                <td class="hidden-phone">'.get_user_column($row['post_author'], 'username').'</td>
                <td style="text-align: center;" class="hidden-phone" title="'.date('d/m/Y',$row['post_modified']).' '.date('h:i:s a',$row['post_modified']).'">'.date('d/m/Y',$row['post_modified']).'</td>
            </tr>
            ';
        }
        echo '</tbody></table></form></div>
        <script type="text/javascript">$("#jq-table").DataTable({"columns": [{ "orderable": false }, null, null, null]})</script>
        ';
                
        echo $this->select_actions();
        admin_content_section_end();
        admin_footer();
    }
    // row actions
    function row_actions($post_type, $row)
    {
        global $token;
        $html = '<a href="media.php?mode=edit&id='.$row['id'].'">'.get_admin_languages('edit').'</a> | ';
        $html .= '<a href="media.php?action=delete&id='.$row['id'].'&token='.$token.'" onclick="return confirm(\''.get_admin_languages('confirm_action').'\');" class="red">'.get_admin_languages('delete').'</a>';
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
    // index upload
    function index_upload()
    {
        global $db, $config, $token, $hooks;
        $hooks->add_action('admin_supports_enqueue_script', array($this, 'admin_settings_print_script'), 3);
        $button_head = '<a href="media.php" class="btn btn-sm btn-primary">'.get_admin_languages('back').'</a>';
        admin_header(get_admin_languages('upload_new_media'));
        admin_content_header(array('title' => get_admin_languages('upload_new_media'), 'button' => $button_head));
        echo admin_content_section_start().'<div class="col-md-12">'.$this->get_form_upload().'</div>';
        admin_content_section_end();
        admin_footer();
    }
    // index edit
    
    function admin_edit_print_script()
    {
        echo '
        <script type="text/javascript" src="../assets/mediaelement/mediaelement-and-player.js"></script>
        <script type="text/javascript" src="../assets/mediaelement/script-player.js"></script>
        ';
    }
    function admin_edit_print_style()
    {
        echo '
        <link rel="stylesheet" href="../assets/mediaelement/mediaelementplayer.css">
        <link rel="stylesheet" href="../assets/mediaelement/style-player.css">
        ';
    }
    function index_edit()
    {
        global $db, $config, $token, $hooks, $post_boxes;
        $hooks->add_action('admin_supports_enqueue_script', array($this, 'admin_edit_print_script'), 3);
        $hooks->add_action('admin_supports_enqueue_style', array($this, 'admin_edit_print_style'), 3);
        $post_id    = (int) safe_input(intval($_GET['id'])) ;
        $result     = $db->sql_query("SELECT * FROM ".POSTS_TABLE." WHERE `post_type`='attachment' AND `id`='{$post_id}'");
        $row        = $db->sql_fetchrow($result);
        $title      = sanitize_text($row['post_title']);
        $content    = get_format_textarea($row['post_content'], "\n");
        $attachment         = $this->get_attachment_info($row);
        $attachment_meta    = $this->get_attachment_meta($row);
        
        $meta       = maybe_unserialize(get_post_meta($row['id'],'attachment_file'));
        $info       = new SplFileInfo(trim($config['siteurl'], '/').'/'.$meta['file']);
        $extension  = pathinfo($info->getFilename(), PATHINFO_EXTENSION);
        
        $more_box_meta = '';
        if(in_array($extension, get_file_formats_ext_array('video')))
        {
            $args['data']  = $row;
            $more_box_meta .= $post_boxes->post_boxes_thumbnail($args);
        }
        
        if(isset($_SESSION['action_token'])):
            $message = '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'.$_SESSION['action_token'].'</div>';
            unset($_SESSION['action_token']);
        else:
            $message = '';
        endif;
        $button_head = '<a href="media.php" class="btn btn-sm btn-primary">'.get_admin_languages('back').'</a>';
        admin_header(get_admin_languages('edit_media'));
        admin_content_header(array('title' => get_admin_languages('edit_media'), 'button' => $button_head));
        echo admin_content_section_start(). '<div class="col-md-12">'.$message.'
        <form action="media.php" method="post" name="form">
            <input type="hidden" name="action" value="update" />
            <input type="hidden" name="query" value="update" />
            <input type="hidden" name="token" value="'.$token.'" />
            <input type="hidden" name="post_id" value="'.$post_id.'" />
            <div class="row">
                <div class="col-md-9">
                    <div class="form-group">
                        <input type="text" name="title" placeholder="'.get_admin_languages('enter_title_here').'" value="'.$title.'" class="form-control post-title" />
                    </div>
                    '.$this->get_attachment_preview($row).'
                    <br />
                    <div class="box">
                        <div class="box-header with-border"><h3 class="box-title">'.get_admin_languages('description').'</h3></div>
                        <div class="box-body">
                            <textarea rows="3" name="content" id="content" class="form-control">'.$content.'</textarea>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="box">
                        <div class="box-header with-border">
                          <h3 class="box-title">'.get_admin_languages('save').'</h3>
                          <div class="box-tools pull-right"><button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fas fa-minus"></i></button></div>
                        </div>
                        <div class="box-body">
                            '.$this->get_attachment_media_meta_inside($row).'
                        </div>
                        <div class="box-footer">
                            <button class="button button-primary pull-right">'.get_admin_languages('update').'</button>
                            <a href="media.php?action=delete&id='.$row['id'].'&token='.$token.'" onclick="return confirm(\''.get_admin_languages('confirm_action').'\');" class="red">'.get_admin_languages('delete').'</a>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    '.$more_box_meta.'
                </div>
            </div>
        </form>
        </div>
        ';
        admin_content_section_end();
        admin_footer();
    }
    
    function get_library_while_format($result, $page, $pagesbar, $tagstart = '')
    {
        global $db, $config;
        if((!isset($_REQUEST['action']) or !isset($_POST['action'])) or ($_REQUEST['action']== 'edit')):
            $this->set_token();
        endif;
        $token = @$_SESSION['cmup_securitytoken'];
        echo $tagstart;
        echo '<form enctype="multipart/form-data" method="post" action="media-upload.php?type='.$this->type.'&#038;tab=library" id="library-form">';
        if($pagesbar):
            echo '<ul class="pagination">'.$pagesbar.'</ul>';
        endif;
        while($row = $db->sql_fetchrow($result)):
            $meta = maybe_unserialize(get_post_meta($row['id'],'attachment_file'));
            //print_r($meta);
            if(in_array($meta['mimetype'], explode('|',$this->allowedimage)))
            {
                $thumbnail = $this->folderuploads.$meta['thumbnail'];
                $moreinfo = "<strong>Dimensions</strong>: {$meta['width']} × {$meta['height']} <br>";
            }
            else
            {
                $thumbnail = 'assets/images/media/'.str_replace("/","-",$meta['mimetype']).'.png';
                $moreinfo = "<strong>Length</strong>: <br>";
            }
            echo '
            <div class="creative-media-upload-accordion-box">
                <div class="creative-media-upload-accordion-title">
                    <img src="'.$thumbnail.'" alt="" class="thumbnail_s">
                    <h5>'.$row['post_title'].'</h5>
                </div>
                <div class="creative-media-upload-accordion-body">
                    <img src="'.$thumbnail.'" alt="" id="img'.$row['id'].'" class="thumbnail_x">
                    <div class="delete-tihs">
                        <a href="media-upload.php?type='.$this->type.'&amp;tab=library&amp;action=edit&amp;id='.$row['id'].'&amp;token='.$token.'" class="button">Edit Image</a>
                        <a href="media-upload.php?type='.$this->type.'&amp;tab=library&amp;page='.$page.'&amp;action=delete&amp;id='.$row['id'].'&amp;token='.$token.'" class="button button-danger" onclick="return confirm(\'Are you sure?\')">Delete Image</a>
                    </div>
                    <div class="info">
                        <strong>File name</strong>: '.$row['post_title'].' <br>
                        <strong>File type</strong>: '.$row['mimetype'].' <br>
                        <strong>File Size</strong>: '.get_formatted_filesize($meta['size']).' <br>
                        '.$moreinfo.'
                        <strong>Upload date</strong>: '.date('j F Y',$row['post_modified']).' <br>
                        <input type="submit" name="send['.$row['id'].']" id="send['.$row['id'].']" class="button button-media-select" value="Use this image">
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            ';
        endwhile;
        if($pagesbar):
            echo '<ul class="pagination">'.$pagesbar.'</ul>';
        endif;
        echo '</form>';
    }
    // post update
    function post_update()
    {
        global $db;
        $title          = safe_input(unsanitize_text($_POST['title']));
        $content        = stripslashes(unsanitize_text($_POST['content']));
        $post_id        = safe_input($db->sql_escape($_POST['post_id']));
        $token          = safe_input($_POST['token']);
        if($token == $_SESSION['securitytokenadmincp']):
            $_SESSION['action_token'] = get_admin_languages('post_updated');
            $result = $db->sql_query("UPDATE ".POSTS_TABLE." SET `post_content`='{$content}', `post_title`='{$title}' WHERE `id`='".$post_id."'");
            if(isset($_POST['thumbnails'])){update_post_meta($post_id, 'thumbnails', $_POST['thumbnails']);}
        endif;
        @header("Location:media.php?mode=edit&id={$post_id}");
    }
    // get dir file
    function get_dir_file($file)
    {
        return ABSPATH.''.$this->folderupload.'/'.$file;
    }
    // folder upload date
    function folder_upload_date($target)
    {
        if(!is_dir( $target ))
        {
            $target_parent = dirname( $target );
            while ( '.' != $target_parent && ! is_dir( $target_parent ) ) {
                $target_parent = dirname( $target_parent );
            }

            if ( $stat = @stat( $target_parent ) ) {
                $dir_perms = $stat['mode'] & 0007777;
            } else {
                $dir_perms = 0777;
            }
            if ( @mkdir( $target, $dir_perms, true ) )
            {
                //
            }
        }
        return date('Y').'/'.date('m');
    }
    // security token
    function cmup_securitytoken($length = '15',$num = false)
    {
        if($num):
            $chars = '0123456789';
        else:
            $chars = 'abcdefghkmnopqrstuvwxyz0123456789';
        endif;
        srand((double)microtime()*1000000);
        $str = '';
        for($i=0 ; $i <= $length ;$i++){
            $num = rand() % 33;
            $tmp = substr($chars,$num,1);
            $str = $str. $tmp;
        }
        return $str;
    }
    // set security token
    function set_token()
    {
        $token = $this->cmup_securitytoken();
        @$_SESSION['cmup_securitytoken'] = $token;
    }
    // pagination
    function pagination($query, $per_page = 10,$page = 1, $url = '?')
    {
        global $db;
    	$result    = $db->sql_query("SELECT COUNT(id) as `num` FROM {$query} WHERE `post_type`='attachment'");
    	$row       = $db->sql_fetchrow($result);
    	$total     = $row['num'];
        $adjacents = "2"; 
    	$page = ($page == 0 ? 1 : $page);  
    	$start = ($page - 1) * $per_page;								
    	$prev = $page - 1;							
    	$next = $page + 1;
        $lastpage = ceil($total/$per_page);
    	$lpm1 = $lastpage - 1;
    	$pagination = "";
    	if($lastpage > 1)
    	{	
    		if ($lastpage < 7 + ($adjacents * 2))
    		{	
    			for ($counter = 1; $counter <= $lastpage; $counter++)
    			{
    				if ($counter == $page)
    					$pagination.= "<li class='active'><a href='#'>$counter</a></li>";
    				else
    					$pagination.= "<li><a href='{$url}page=$counter'>$counter</a></li>";					
    			}
    		}
    		elseif($lastpage > 5 + ($adjacents * 2))
    		{
    			if($page < 1 + ($adjacents * 2))		
    			{
    				for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
    				{
    					if ($counter == $page)
    						$pagination.= "<li class='active'><a>$counter</a></li>";
    					else
    						$pagination.= "<li><a href='{$url}page=$counter'>$counter</a></li>";					
    				}
    				$pagination.= "<li class='dot'><a href='#'>...</a></li>";
    				$pagination.= "<li><a href='{$url}page=$lpm1'>$lpm1</a></li>";
    				$pagination.= "<li><a href='{$url}page=$lastpage'>$lastpage</a></li>";		
    			}
    			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
    			{
    				$pagination.= "<li><a href='{$url}page=1'>1</a></li>";
    				$pagination.= "<li><a href='{$url}page=2'>2</a></li>";
    				$pagination.= "<li class='dot'><a href='#'>...</a></li>";
    				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
    				{
    					if ($counter == $page)
    						$pagination.= "<li class='active'><a>$counter</a></li>";
    					else
    						$pagination.= "<li><a href='{$url}page=$counter'>$counter</a></li>";					
    				}
    				$pagination.= "<li><a href='{$url}page=$lpm1'>$lpm1</a></li>";
    				$pagination.= "<li><a href='{$url}page=$lastpage'>$lastpage</a></li>";		
    			}
    			else
    			{
    				$pagination.= "<li><a href='{$url}page=1'>1</a></li>";
    				$pagination.= "<li><a href='{$url}page=2'>2</a></li>";
    				$pagination.= "<li class='dot'><a href='#'>...</a></li>";
    				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
    				{
    					if ($counter == $page)
    						$pagination.= "<li class='active'><a>$counter</a></li>";
    					else
    						$pagination.= "<li><a href='{$url}page=$counter'>$counter</a></li>";					
    				}
    			}
    		}		
    	}
        return $pagination;
    }
}
?>
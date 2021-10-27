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

//admin-class-media.php

class admin_class_media 
{
    // public
    
    
    function get_attachment_info($row = array())
    {
        global $db;
        
    }
    
    function get_attachment_meta($row = array())
    {
        global $db;
        
    }
    
    function get_attachment_preview($row)
    {
        global $config, $db;
        $meta       = maybe_unserialize(get_post_meta($row['id'],'attachment_file'));
        $file_url   = trim($config['siteurl'], '/').'/'.utf8_uri_encode($meta['file']);
        $info       = new SplFileInfo($file_url);
        $extension  = pathinfo($info->getFilename(), PATHINFO_EXTENSION);
        
        if(in_array($extension, get_file_formats_ext_array('image')))
        {
            $html = '
            <div class="attachment_image" id="media-head-'.$row['id'].'">
                <img src="'.$file_url.'" style="max-width:100%" alt="">
            </div>
            ';
        }
        elseif(in_array($extension, get_file_formats_ext_array('audio')))
        {
            $html = '
            <div class="attachment_audio" id="media-head-'.$row['id'].'">
                <audio id="mediaplayer" preload="none" controls style="max-width:100%;">
                    <source src="'.$file_url.'" type="'.$meta['mimetype'].'">
                </audio>
            </div>
            ';
        }
        elseif(in_array($extension, get_file_formats_ext_array('video')))
        {
            $html = '
            <div class="attachment_video" id="media-head-'.$row['id'].'">
                <video id="mediaplayer" width="640" height="360" style="max-width:100%;" poster="" preload="none" controls playsinline webkit-playsinline>
                    <source src="'.$file_url.'" type="'.$meta['mimetype'].'">
                    <track srclang="en" kind="subtitles" src="mediaelement.vtt">
                    <track srclang="en" kind="chapters" src="chapters.vtt">
                </video>
            </div>
            ';
        }
        else
        {
            $html = '
            <div class="attachment_image" id="media-head-'.$row['id'].'">
                <img src="assets/images/media/'.$extension.'.png" style="max-width:64px" alt="">
            </div>
            ';
        }
            
        return $html;
    }
    
    
    
    function add_id3_tag_data( &$metadata, $data ) {
        foreach ( array( 'id3v2', 'id3v1' ) as $version ) {
            if ( ! empty( $data[$version]['comments'] ) ) {
                foreach ( $data[$version]['comments'] as $key => $list ) {
                    if ( 'length' !== $key && ! empty( $list ) ) {
                        $metadata[$key] = wp_kses_post( reset( $list ) );
                        // Fix bug in byte stream analysis.
                        if ( 'terms_of_use' === $key && 0 === strpos( $metadata[$key], 'yright notice.' ) )
                            $metadata[$key] = 'Cop' . $metadata[$key];
                    }
                }
                break;
            }
        }

        if ( ! empty( $data['id3v2']['APIC'] ) ) {
            $image = reset( $data['id3v2']['APIC']);
            if ( ! empty( $image['data'] ) ) {
                $metadata['image'] = array(
                    'data' => $image['data'],
                    'mime' => $image['image_mime'],
                    'width' => $image['image_width'],
                    'height' => $image['image_height']
                );
            }
        } elseif ( ! empty( $data['comments']['picture'] ) ) {
            $image = reset( $data['comments']['picture'] );
            if ( ! empty( $image['data'] ) ) {
                $metadata['image'] = array(
                    'data' => $image['data'],
                    'mime' => $image['image_mime']
                );
            }
        }
    }

    
    function get_media_creation_timestamp( $metadata ) {
        $creation_date = false;

        if ( empty( $metadata['fileformat'] ) ) {
            return $creation_date;
        }

        switch ( $metadata['fileformat'] ) {
            case 'asf':
                if ( isset( $metadata['asf']['file_properties_object']['creation_date_unix'] ) ) {
                    $creation_date = (int) $metadata['asf']['file_properties_object']['creation_date_unix'];
                }
                break;

            case 'matroska':
            case 'webm':
                if ( isset( $metadata['matroska']['comments']['creation_time']['0'] ) ) {
                    $creation_date = strtotime( $metadata['matroska']['comments']['creation_time']['0'] );
                }
                elseif ( isset( $metadata['matroska']['info']['0']['DateUTC_unix'] ) ) {
                    $creation_date = (int) $metadata['matroska']['info']['0']['DateUTC_unix'];
                }
                break;

            case 'quicktime':
            case 'mp4':
                if ( isset( $metadata['quicktime']['moov']['subatoms']['0']['creation_time_unix'] ) ) {
                    $creation_date = (int) $metadata['quicktime']['moov']['subatoms']['0']['creation_time_unix'];
                }
                break;
        }

        return $creation_date;
    }
    
    
    function get_read_video_metadata( $file ) {
        if ( ! file_exists( $file ) ) {
            return false;
        }
        $metadata = array();
        require_once(ABSPATH.'includes/ID3/getid3.php');
        $id3 = new getID3();
        $data = $id3->analyze( $file );
        if ( ! empty( $data['audio'] ) ) {
            unset( $data['audio']['streams'] );
            $metadata['audio_format'] = $data['audio']['dataformat'];
            $metadata['audio_codec'] = $data['audio']['codec'];
        }
        if ( ! empty( $data['playtime_string'] ) ){
            $metadata['length'] = $data['playtime_string'];
        }
        if ( ! empty( $data['video']['resolution_x'] ) and ! empty( $data['video']['resolution_y'] ) ){
            $metadata['dimensions'] = (int) $data['video']['resolution_x'].' × '.(int) $data['video']['resolution_y'];
        }
        return $metadata;
    }


    function read_audio_metadata( $file ) {
        if ( ! file_exists( $file ) ) {
            return false;
        }
        $metadata = array();
        require_once(ABSPATH.'includes/ID3/getid3.php');
        $id3 = new getID3();
        $data = $id3->analyze( $file );
        if ( ! empty( $data['audio'] ) ) {
            unset( $data['audio']['streams'] );
            $metadata['bitrate'] = round($data['audio']['bitrate'] / 1000). 'kb/s '.strtoupper($data['audio']['bitrate_mode']);
        }
        if ( ! empty( $data['playtime_string'] ) )
        {
            $metadata['length'] = $data['playtime_string'];
        }
        return $metadata;
    }
    
    

    function get_attachment_media_meta_inside($row = array())
    {
        global $config, $db;
        $meta       = maybe_unserialize(get_post_meta($row['id'],'attachment_file'));
        $info       = new SplFileInfo(trim($config['siteurl'], '/').'/'.$meta['file']);
        $extension  = pathinfo($info->getFilename(), PATHINFO_EXTENSION);
        $file_url   = trim($config['siteurl'], '/').'/'.$meta['file'];
        if(in_array($extension, get_file_formats_ext_array('image')))
        {
            $moreinfo   = '<li><strong>'.get_admin_languages('dimensions').'</strong> : <span>'.$meta['width'].' × '.$meta['height'].'</span></li>';
        }
        elseif(in_array($extension, get_file_formats_ext_array('audio')))
        {
            $mor_meta = $this->read_audio_metadata(ABSPATH.$meta['file']);
            foreach($mor_meta as $key => $value)
            {
                $moreinfo .= '<li><strong>'.get_admin_languages($key).'</strong> : '.$value.'<span></span></li>';
            }
            
        }
        elseif(in_array($extension, get_file_formats_ext_array('video')))
        {
            $mor_meta = $this->get_read_video_metadata(ABSPATH.$meta['file']);
            foreach($mor_meta as $key => $value)
            {
                $moreinfo .= '<li><strong>'.get_admin_languages($key).'</strong> : '.$value.'<span></span></li>';
            }
        }
        $html = '
        <ul class="inside-media">
            <li><strong>'.get_admin_languages('uploaded_on').'</strong> : <span>'.date('d/m/Y',$row['post_modified']).' @ '.date('H:s',$row['post_modified']).'</span></li>
            <li><strong>'.get_admin_languages('file_url').'</strong>:<br>
            <input type="text" class="form-control" readonly="readonly" name="attachment_url" id="attachment_url" value="'.$file_url.'"></li>
            <li><strong>'.get_admin_languages('file_name').'</strong> : <span>'.$meta['filename'].'</span></li>
            <li><strong>'.get_admin_languages('file_type').'</strong> : <span>'.$extension.' ('.$meta['mimetype'].')</span></li>
            <li><strong>'.get_admin_languages('file_size').'</strong> : <span>'.get_size_format($meta['size']).'</span></li>
            '.$moreinfo.'
        </ul>
        ';
        return $html;
    }
    // form upload
    function get_form_upload($args = array())
    {
        global $config, $hooks, $token;
        $type = (isset($args['type']))? $args['type'] : 'normal';
        
        if($type == 'thickbox')
        {
            $media_items = '<ul id="media-items" class="media-attachments"></ul>';
        }
        else
        {
            $media_items = '<div id="media-items"></div>';
        }
        
        $html = '
        <div class="media-upload-form-wrap" id="async-upload-wrap">
            <form enctype="multipart/form-data" method="post" action="" id="media-upload-form">
                <div class="upload-console-drop" id="drop-zone">
                    <h3>'.get_admin_languages('drop_files_here').'</h3>
                    <span>'.get_admin_languages('or').'</span>
                    <input type="file" name="files[]" id="standard-upload-files" multiple="multiple" />
                    <input type="hidden" name="action" value="async_upload" />
                    <input type="hidden" name="token" value="'.$token.'" />
                    <input type="hidden" name="type" value="'.$type.'" id="type-upload-files" />
                    <input type="button" value="'.get_admin_languages('select_files').'" id="plupload-browse-button" />
                    <div class="maximum_upload_file_size">'.get_admin_languages('maximum_upload_file_size').': '.get_size_format(get_max_upload_size()).'</div>
                    <div class="bar hidden" id="bar">
                        <div class="bar-fill" id="bar-fill">
                            <div class="bar-fill-text" id="bar-fill-text"></div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        '.$media_items.'
        ';
        return $html;
    }
    // delete post
    function post_delete($location = false)
    {
        global $config, $db, $hooks, $token;
        $token      = safe_input($_REQUEST['token']);
        $post_id    = (int) safe_input(intval($_GET['id']));
        if($token == $_SESSION['securitytokenadmincp']):
            $this->delete_media($post_id);
            $_SESSION['action_token'] = get_admin_languages('delete_post_successfully');
        endif;
        if($location)
        {
            @header("Location:{$location}");
        }
        else
        {
            @header("Location:".THIS_SCRIPT."");
        }
    }
    // action query
    function post_query_action($idx,$checkbox,$action,$token, $location = false)
    {
        $token  = safe_input($token);
        if($token == $_SESSION['securitytokenadmincp'])
        {
            if($action == "delete"){
                if($checkbox)
                {
                    $number = count($checkbox);
                    for($i=0;$i<$number;$i++){
                        $post_id = $checkbox[$i];
                        $this->delete_media($post_id);
                    }
                    $_SESSION['action_token'] = get_admin_languages('delete_post_successfully');
                }
                else {$_SESSION['action_token'] = get_admin_languages('not_select_anything');}
            }
        }
        if($location)
        {
            @header("Location:{$location}");
        }
        else
        {
            @header("Location:".THIS_SCRIPT."");
        }
    }
    // delete media
    function delete_media($post_id)
    {
        global $db;
        $sql     = "SELECT id FROM ".POSTS_TABLE." WHERE `id`='{$post_id}'";
        $found   = $db->sql_numrows($sql);
        if($found):
            $result     = $db->sql_query($sql);
            $row        = $db->sql_fetchrow($result);
            $meta       = maybe_unserialize(get_post_meta($row['id'],'attachment_file'));
            @unlink(ABSPATH.$meta['file']);
            @unlink(ABSPATH.$meta['thumbnail']);
            @unlink(ABSPATH.$meta['medium']);
            @unlink(ABSPATH.$meta['medium_large']);
            $db->sql_query("DELETE FROM " . POSTS_TABLE . " WHERE `id`='".$db->sql_escape($post_id)."'");
            $db->sql_query("DELETE FROM " . POSTSMETA_TABLE . " WHERE `post_id`='".$db->sql_escape($post_id)."'");
            $db->sql_freeresult();
        endif;
    }
}
?>
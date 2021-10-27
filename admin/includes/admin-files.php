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

function cleanup_header_comment( $str ) {
	return trim(preg_replace("/\s*(?:\*\/|\?>).*/", '', $str));
}

function get_file_data( $file, $default_headers) {
	$fp        = fopen( $file, 'r' );
	$file_data = fread( $fp, 8192 );
	fclose( $fp );
	$file_data = str_replace( "\r", "\n", $file_data );
	$all_headers = $default_headers;
	foreach ( $all_headers as $field => $regex ) {
		if ( preg_match( '/^[ \t\/*#@]*' . preg_quote( $regex, '/' ) . ':(.*)$/mi', $file_data, $match ) && $match[1] )
			$all_headers[ $field ] = cleanup_header_comment( $match[1] );
		else
			$all_headers[ $field ] = '';
	}
	return $all_headers;
}

function set_upload_file($file = 'file'){
    global $db, $hooks;
    
}

function get_upload_file($file = 'file'){
    global $db;
    $getfile['name']     = $_FILES[$file]["name"];
    $getfile['tmp_name'] = $_FILES[$file]['tmp_name'];
    $getfile['size']     = $_FILES[$file]['size'];
    $getfile['type']     = $_FILES[$file]['type'];
    $post_id             = get_loop_upload_file($getfile);
    return $post_id;
}

function folder_upload_date($target){
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

function get_upload_dir(){
    global $config;
    $dir['basedir']     = date('Y').'/';
    $dir['subdir']      = date('m').'/';
    $dir['path']        = 'uploads/'.$dir['basedir'].$dir['subdir'];
    $dir['target']      = ABSPATH.$dir['path'];
    $dir['diruploads']  = ABSPATH.'uploads/';
    $dir['url']         = trim($config['siteurl'], '/').'/'.$dir['path'];
    $dir['siteurl']     = trim($config['siteurl'], '/').'/';
    $dir['uploaddir']   = $dir['basedir'].$dir['subdir'];
    folder_upload_date($dir['target']);
    return $dir;
}

function unique_filename( $dir, $filename) {
	$filename  = sanitize_file_name($filename);
	$ext       = pathinfo( $filename, PATHINFO_EXTENSION );
	$name      = pathinfo( $filename, PATHINFO_BASENAME );
	if ( $ext ) {
		$ext = '.' . $ext;
	}
	if ( $name === $ext ) {
		$name = '';
	}
    $number = '';
    if ( $ext && strtolower($ext) != $ext ) {
        $ext2       = strtolower($ext);
        $filename2  = preg_replace( '|' . preg_quote($ext) . '$|', $ext2, $filename );
        while ( file_exists($dir . $filename) || file_exists($dir . $filename2) ) {
            $new_number = (int) $number + 1;
            $filename   = str_replace( array( "-$number$ext", "$number$ext" ), "-$new_number$ext", $filename );
            $filename2  = str_replace( array( "-$number$ext2", "$number$ext2" ), "-$new_number$ext2", $filename2 );
            $number = $new_number;
        }
        return $filename;
    }
    while ( file_exists( $dir . $filename ) ) {
        $new_number = (int) $number + 1;
        if ( '' == "$number$ext" ) {
            $filename = "$filename-" . $new_number;
        } else {
            $filename = str_replace( array( "-$number$ext", "$number$ext" ), "-" . $new_number . $ext, $filename );
        }
        $number = $new_number;
    }
	return $filename;
}

function get_real_filename($filename){
    $ext       = pathinfo( $filename, PATHINFO_EXTENSION );
	$name      = pathinfo( $filename, PATHINFO_BASENAME );
    if ( $ext ) {
		$ext = '.' . $ext;
	}
    return preg_replace( '|' . preg_quote(strtolower($ext)) . '$|', '', $filename );
}

function ajax_upload_file($file, $mimes = null){
    $filetype           = check_filetype_and_ext( $file['tmp_name'], $file['name'], $mimes );
    $ext                = empty( $filetype['ext'] ) ? '' : $filetype['ext'];
    $type               = empty( $filetype['type'] ) ? '' : $filetype['type'];
    $proper_filename    = empty( $filetype['proper_filename'] ) ? '' : $filetype['proper_filename'];
    if ($proper_filename) {
        $file['name'] = $proper_filename;
    }
    if (( ! $type || !$ext )) {
        $error_handler['status'] = false;
        $error_handler['filename'] = $file['name'];
        $error_handler['error']  = 'Sorry, this file type is not permitted for security reasons.';
        return $error_handler;
    }
    if ( ! $type ) {
        $type = $file['type'];
    }
    $uploads            = get_upload_dir();
    $filename           = unique_filename($uploads['target'], $file['name']);
    $new_file           = $uploads['path'] . $filename;
    $up_file            = $uploads['target'] . $filename;
    $move_new_file      = move_uploaded_file($file['tmp_name'], $up_file);
    $file['filename']   = get_real_filename($file['name']);
    $title              = $file['filename'];
    $content            = '';
    $args = array(
        'post_author'       => get_session_userid(),
        'content'           => $content,
        'title'             => $title,
        'status'            => '1',
        'comment_status'    => '0',
        'post_type'         => 'attachment',
        'term_id'           => ''
    );
    $post_id = insert_post($args);
    $image_ext = get_image_ext();
    if(! empty( $image_ext[ $type ] ))
    {
        $image_info     = @getimagesize($up_file);
        $image_width    = $image_info[0];
        $image_height   = $image_info[1];   
        $attachment_file = image_resize($uploads, $filename, $ext);
        $attachment_file['width']           = $image_width;
        $attachment_file['height']          = $image_height;
        $file['file']   = $uploads['siteurl'].$new_file;
        $file['url']    = $uploads['siteurl'].$attachment_file['thumbnail'];
        $file['statusimage'] = true;
    }
    else
    {
        $file['statusimage'] = false;
        $file['url'] = '';
    }
    $attachment_file['file']        = $new_file;
    $attachment_file['filename']    = $filename;
    $attachment_file['mimetype']    = $type;
    $attachment_file['size']        = $file['size'];
    update_post_meta($post_id, 'attachment_file', maybe_serialize($attachment_file));
    // chmod file
    $stat = stat($uploads['target'].$filename);
    //$stat = @ stat( dirname( $new_file ));
    $perms = $stat['mode'] & 0000666;
    @chmod( $new_file, $perms );
    $file['status'] = true;
    $file['postid'] = $post_id;
    
    return $file;
}

function image_resize($uploads, $file, $ext){
    $thumbnail      = thumbnail_imgsize($uploads, $file,'150x150',$ext,150,150);
    $medium         = thumbnail_imgsize($uploads, $file,'300x200',$ext,300,200);
    $medium_large   = thumbnail_imgsize($uploads, $file,'260x335',$ext,260,335);
    //$medium_large   = '';//thumbnail_imgsize($uploads, $file,'700x200',$ext,'700','200');
    //$large   		= '';//thumbnail_imgsize($uploads, $file,'1100x500',$ext,'1100','500');
    $attachment_file['thumbnail']       = $thumbnail;
    $attachment_file['medium']          = $medium;
    $attachment_file['medium_large']    = $medium_large;
    //$attachment_file['large']    		= $large;
    return $attachment_file;
}

function thumbnail_imgsize($uploads,$fname,$att_name,$ext,$thumb_width = '150',$thumb_height = '150'){
    $fname = str_replace(".".$ext,"",$fname);
	$extnew = ".".$ext;
	
	if(in_array($ext, array('bmp', 'svg')) )
	{
		return $uploads['path'].$fname.$extnew;
	}
	else
    {
        if($ext == 'jpg' or $ext == 'jpeg')
        {
            $image = imagecreatefromjpeg($uploads['url'].$fname.$extnew);
        }
        elseif($ext == 'png')
        {
            $image = imagecreatefrompng($uploads['url'].$fname.$extnew);
        }
        elseif($ext == 'gif')
        {
            $image = imagecreatefromgif($uploads['url'].$fname.$extnew);
        }
        else
        {
            $image = imagecreatefromjpeg($uploads['url'].$fname.$extnew);
        }
        $filename = $fname.'-'.$att_name.$extnew;
        $width = imagesx($image);
        $height = imagesy($image);
        $original_aspect = $width / $height;
        $thumb_aspect = $thumb_width / $thumb_height;
        if ( $original_aspect >= $thumb_aspect )
        {
           $new_height = $thumb_height;
           $new_width = $width / ($height / $thumb_height);
        }
        else
        {
           $new_width = $thumb_width;
           $new_height = $height / ($width / $thumb_width);
        }
        $thumb = imagecreatetruecolor( $thumb_width, $thumb_height );
        imagecopyresampled($thumb,$image,0 - ($new_width - $thumb_width) / 2,0 - ($new_height - $thumb_height) / 2,0, 0,$new_width, $new_height,$width, $height);
        imagejpeg($thumb, $uploads['target'].$filename, 100);
        return $uploads['path'].$filename;
	}
}

function get_loop_upload_file($file){
    $uploads        = get_upload_dir();
    $filename       = unique_filename($uploads['path'], $file['name']);
	$new_file       = $uploads['path'] . $filename;
    $move_new_file  = @ move_uploaded_file($file['tmp_name'], $new_file);
    return $new_file;
}

function check_filetype_and_ext($file, $filename, $mimes = null){
    $filetype = check_filetype( $filename, $mimes );
	$ext = $filetype['ext'];
	$type = $filetype['type'];
    if ( ! file_exists( $file ) ) {
		return compact( 'ext', 'type', 'proper_filename' );
	}
    $real_mime = false;
    if ( $type && 0 === strpos( $type, 'image/' ) ) {
		$real_mime = get_image_mime( $file );
		if ( $real_mime && $real_mime != $type ) {
			$mime_to_ext = array(
				'image/jpeg' => 'jpg',
				'image/png'  => 'png',
				'image/gif'  => 'gif',
				'image/bmp'  => 'bmp',
				'image/tiff' => 'tif',
				'image/svg+xml' => 'svg',
			);
			if ( ! empty( $mime_to_ext[ $real_mime ] ) ) {
				$filename_parts = explode( '.', $filename );
				array_pop( $filename_parts );
				$filename_parts[] = $mime_to_ext[ $real_mime ];
				$new_filename = implode( '.', $filename_parts );
				if ( $new_filename != $filename ) {
					$proper_filename = $new_filename;
				}
				$wp_filetype = check_filetype( $new_filename, $mimes );
				$ext = $wp_filetype['ext'];
				$type = $wp_filetype['type'];
			} else {
				// Reset $real_mime and try validating again.
				$real_mime = false;
			}
		}
	}
    
    if ( $type && ! $real_mime && extension_loaded( 'fileinfo' ) ) {
		$finfo = finfo_open( FILEINFO_MIME_TYPE );
		$real_mime = finfo_file( $finfo, $file );
		finfo_close( $finfo );
		if ( $real_mime && ( $real_mime !== $type ) && ( 0 === strpos( $real_mime, 'application' ) ) ) {
			$allowed = get_allowed_mime_types();
			if ( ! in_array( $real_mime, $allowed ) ) {
				$type = $ext = false;
			}
		}
	}
    
    
    return compact( 'ext', 'type', 'proper_filename' );
}

function check_filetype( $filename, $mimes = null ) {
	if ( empty($mimes) )
		$mimes = get_mime_types();
	$type = false;
	$ext = false;

	foreach ( $mimes as $ext_preg => $mime_match ) {
		$ext_preg = '!\.(' . $ext_preg . ')$!i';
		if ( preg_match( $ext_preg, $filename, $ext_matches ) ) {
			$type = $mime_match;
			$ext = $ext_matches[1];
			break;
		}
	}

	return compact( 'ext', 'type' );
}

function get_image_mime( $file ) {
	try {
		if ( is_callable( 'exif_imagetype' ) ) {
			$imagetype = exif_imagetype( $file );
			$mime = ( $imagetype ) ? image_type_to_mime_type( $imagetype ) : false;
		} elseif ( function_exists( 'getimagesize' ) ) {
			$imagesize = getimagesize( $file );
			$mime = ( isset( $imagesize['mime'] ) ) ? $imagesize['mime'] : false;
		} else {
			$mime = false;
		}
	} catch ( Exception $e ) {
		$mime = false;
	}

	return $mime;
}

function get_image_ext(){
    return  array(
        'image/jpeg' => '.jpg',
        'image/png'  => '.png',
        'image/gif'  => '.gif',
        'image/bmp'  => '.bmp',
        'image/tiff' => '.tif',
        'image/svg+xml' => '.svg',
    );
}

function get_file_formats_ext_array($type){
    switch($type)
    {
        case 'image':
            $formats = 'jpg|jpeg|png|gif|ico|svg';
        break;
        case 'audio':
            $formats = 'mp3|m4a|ogg|wav';
        break;
        case 'video':
            $formats = 'mp4|m4v|mov|wmv|avi|mpg|ogv|3gp|3g2|flv';
        break;
    }
    return explode('|',$formats);
    
}

function get_mime_types() {
	return array(
	// Image formats.
	'jpg|jpeg|jpe' => 'image/jpeg',
	'gif' => 'image/gif',
	'png' => 'image/png',
	'bmp' => 'image/bmp',
	'tiff|tif' => 'image/tiff',
	'svg' => 'image/svg+xml',
	'ico' => 'image/x-icon',
	// Video formats.
	'asf|asx' => 'video/x-ms-asf',
	'wmv' => 'video/x-ms-wmv',
	'wmx' => 'video/x-ms-wmx',
	'wm' => 'video/x-ms-wm',
	'avi' => 'video/avi',
	'divx' => 'video/divx',
	'flv' => 'video/x-flv',
	'mov|qt' => 'video/quicktime',
	'mpeg|mpg|mpe' => 'video/mpeg',
	'mp4|m4v' => 'video/mp4',
	'ogv' => 'video/ogg',
	'webm' => 'video/webm',
	'mkv' => 'video/x-matroska',
	'3gp|3gpp' => 'video/3gpp', // Can also be audio
	'3g2|3gp2' => 'video/3gpp2', // Can also be audio
	// Text formats.
	'txt|asc|c|cc|h|srt' => 'text/plain',
	'csv' => 'text/csv',
	'tsv' => 'text/tab-separated-values',
	'ics' => 'text/calendar',
	'rtx' => 'text/richtext',
	'vtt' => 'text/vtt',
	'dfxp' => 'application/ttaf+xml',
	// Audio formats.
	'mp3|m4a|m4b' => 'audio/mpeg',
	'ra|ram' => 'audio/x-realaudio',
	'wav' => 'audio/wav',
	'ogg|oga' => 'audio/ogg',
	'flac' => 'audio/flac',
	'mid|midi' => 'audio/midi',
	'wma' => 'audio/x-ms-wma',
	'wax' => 'audio/x-ms-wax',
	'mka' => 'audio/x-matroska',
	// Misc application formats.
	'rtf' => 'application/rtf',
	'pdf' => 'application/pdf',
	'class' => 'application/java',
	'tar' => 'application/x-tar',
	'zip' => 'application/zip',
	'gz|gzip' => 'application/x-gzip',
	//'rar' => 'application/rar',
	'7z' => 'application/x-7z-compressed',
	'psd' => 'application/octet-stream',
	'xcf' => 'application/octet-stream',
	// MS Office formats.
	'doc' => 'application/msword',
	'pot|pps|ppt' => 'application/vnd.ms-powerpoint',
	'wri' => 'application/vnd.ms-write',
	'xla|xls|xlt|xlw' => 'application/vnd.ms-excel',
	'mdb' => 'application/vnd.ms-access',
	'mpp' => 'application/vnd.ms-project',
	'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
	'docm' => 'application/vnd.ms-word.document.macroEnabled.12',
	'dotx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.template',
	'dotm' => 'application/vnd.ms-word.template.macroEnabled.12',
	'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
	'xlsm' => 'application/vnd.ms-excel.sheet.macroEnabled.12',
	'xlsb' => 'application/vnd.ms-excel.sheet.binary.macroEnabled.12',
	'xltx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.template',
	'xltm' => 'application/vnd.ms-excel.template.macroEnabled.12',
	'xlam' => 'application/vnd.ms-excel.addin.macroEnabled.12',
	'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
	'pptm' => 'application/vnd.ms-powerpoint.presentation.macroEnabled.12',
	'ppsx' => 'application/vnd.openxmlformats-officedocument.presentationml.slideshow',
	'ppsm' => 'application/vnd.ms-powerpoint.slideshow.macroEnabled.12',
	'potx' => 'application/vnd.openxmlformats-officedocument.presentationml.template',
	'potm' => 'application/vnd.ms-powerpoint.template.macroEnabled.12',
	'ppam' => 'application/vnd.ms-powerpoint.addin.macroEnabled.12',
	'sldx' => 'application/vnd.openxmlformats-officedocument.presentationml.slide',
	'sldm' => 'application/vnd.ms-powerpoint.slide.macroEnabled.12',
	'onetoc|onetoc2|onetmp|onepkg' => 'application/onenote',
	'oxps' => 'application/oxps',
	'xps' => 'application/vnd.ms-xpsdocument',
	// OpenOffice formats.
	'odt' => 'application/vnd.oasis.opendocument.text',
	'odp' => 'application/vnd.oasis.opendocument.presentation',
	'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
	'odg' => 'application/vnd.oasis.opendocument.graphics',
	'odc' => 'application/vnd.oasis.opendocument.chart',
	'odb' => 'application/vnd.oasis.opendocument.database',
	'odf' => 'application/vnd.oasis.opendocument.formula',
	// WordPerfect formats.
	'wp|wpd' => 'application/wordperfect',
	// iWork formats.
	'key' => 'application/vnd.apple.keynote',
	'numbers' => 'application/vnd.apple.numbers',
	'pages' => 'application/vnd.apple.pages',
	);
}

function get_mime_type_file() {
	return array(
	// Image formats.
    'jpg'           => 'image/jpeg',
    'jpeg'          => 'image/jpeg',
    'jpe'           => 'image/jpeg',
    'gif'           => 'image/gif',
    'png'           => 'image/png',
    'bmp'           => 'image/bmp',
    'tiff'          => 'image/tiff',
    'tif'           => 'image/tiff',
    'svg'           => 'image/svg+xml',
    'ico'           => 'image/x-icon',
	// Video formats.
	'asf|asx' => 'video/x-ms-asf',
	'wmv' => 'video/x-ms-wmv',
	'wmx' => 'video/x-ms-wmx',
	'wm' => 'video/x-ms-wm',
	'avi' => 'video/avi',
	'divx' => 'video/divx',
	'flv' => 'video/x-flv',
	'mov|qt' => 'video/quicktime',
	'mpeg|mpg|mpe' => 'video/mpeg',
	'mp4|m4v' => 'video/mp4',
	'ogv' => 'video/ogg',
	'webm' => 'video/webm',
	'mkv' => 'video/x-matroska',
	'3gp|3gpp' => 'video/3gpp', // Can also be audio
	'3g2|3gp2' => 'video/3gpp2', // Can also be audio
	// Text formats.
	'txt|asc|c|cc|h|srt' => 'text/plain',
	'csv' => 'text/csv',
	'tsv' => 'text/tab-separated-values',
	'ics' => 'text/calendar',
	'rtx' => 'text/richtext',
	'vtt' => 'text/vtt',
	'dfxp' => 'application/ttaf+xml',
	// Audio formats.
	'mp3|m4a|m4b' => 'audio/mpeg',
	'ra|ram' => 'audio/x-realaudio',
	'wav' => 'audio/wav',
	'ogg|oga' => 'audio/ogg',
	'flac' => 'audio/flac',
	'mid|midi' => 'audio/midi',
	'wma' => 'audio/x-ms-wma',
	'wax' => 'audio/x-ms-wax',
	'mka' => 'audio/x-matroska',
	// Misc application formats.
	'rtf' => 'application/rtf',
	'pdf' => 'application/pdf',
	'class' => 'application/java',
	'tar' => 'application/x-tar',
	'zip' => 'application/zip',
	'gz|gzip' => 'application/x-gzip',
	//'rar' => 'application/rar',
	'7z' => 'application/x-7z-compressed',
	'psd' => 'application/octet-stream',
	'xcf' => 'application/octet-stream',
	// MS Office formats.
	'doc' => 'application/msword',
	'pot|pps|ppt' => 'application/vnd.ms-powerpoint',
	'wri' => 'application/vnd.ms-write',
	'xla|xls|xlt|xlw' => 'application/vnd.ms-excel',
	'mdb' => 'application/vnd.ms-access',
	'mpp' => 'application/vnd.ms-project',
	'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
	'docm' => 'application/vnd.ms-word.document.macroEnabled.12',
	'dotx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.template',
	'dotm' => 'application/vnd.ms-word.template.macroEnabled.12',
	'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
	'xlsm' => 'application/vnd.ms-excel.sheet.macroEnabled.12',
	'xlsb' => 'application/vnd.ms-excel.sheet.binary.macroEnabled.12',
	'xltx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.template',
	'xltm' => 'application/vnd.ms-excel.template.macroEnabled.12',
	'xlam' => 'application/vnd.ms-excel.addin.macroEnabled.12',
	'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
	'pptm' => 'application/vnd.ms-powerpoint.presentation.macroEnabled.12',
	'ppsx' => 'application/vnd.openxmlformats-officedocument.presentationml.slideshow',
	'ppsm' => 'application/vnd.ms-powerpoint.slideshow.macroEnabled.12',
	'potx' => 'application/vnd.openxmlformats-officedocument.presentationml.template',
	'potm' => 'application/vnd.ms-powerpoint.template.macroEnabled.12',
	'ppam' => 'application/vnd.ms-powerpoint.addin.macroEnabled.12',
	);
}

function get_allowed_mime_types( $user = null ) {
	$t = get_mime_types();

	unset( $t['swf'], $t['exe'] );
    unset( $t['htm|html'], $t['js'] );
    unset($t['code']);
	return $t;
}

function sanitize_file_name( $filename ) {
	$filename_raw = $filename;
	$special_chars = array("?", "[", "]", "/", "\\", "=", "<", ">", ":", ";", ",", "'", "\"", "&", "$", "#", "*", "(", ")", "|", "~", "`", "!", "{", "}", "%", "+", chr(0));
	$filename = preg_replace( "#\x{00a0}#siu", ' ', $filename );
	$filename = str_replace( $special_chars, '', $filename );
	$filename = str_replace( array( '%20', '+' ), '-', $filename );
	$filename = preg_replace( '/[\r\n\t -]+/', '-', $filename );
	$filename = trim( $filename, '.-_' );
	if ( false === strpos( $filename, '.' ) ) {
		$mime_types = get_mime_types();
		$filetype = check_filetype( 'test.' . $filename, $mime_types );
		if ( $filetype['ext'] === $filename ) {
			$filename = 'unnamed-file.' . $filetype['ext'];
		}
	}
	$parts = explode('.', $filename);
	if ( count( $parts ) <= 2 ) {
		return $filename;
	}
	$filename  = array_shift($parts);
	$extension = array_pop($parts);
	$mimes     = get_allowed_mime_types();
	foreach ( (array) $parts as $part) {
		$filename .= '.' . $part;
		if ( preg_match("/^[a-zA-Z]{2,5}\d?$/", $part) ) {
			$allowed = false;
			foreach ( $mimes as $ext_preg => $mime_match ) {
				$ext_preg = '!^(' . $ext_preg . ')$!i';
				if ( preg_match( $ext_preg, $part ) ) {
					$allowed = true;
					break;
				}
			}
			if ( !$allowed )
				$filename .= '_';
		}
	}
	$filename .= '.' . $extension;
	return $filename;
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
        $metadata = $data['audio'];
    }

    if ( ! empty( $data['fileformat'] ) )
        $metadata['fileformat'] = $data['fileformat'];
    if ( ! empty( $data['filesize'] ) )
        $metadata['filesize'] = (int) $data['filesize'];
    if ( ! empty( $data['mime_type'] ) )
        $metadata['mime_type'] = $data['mime_type'];
    if ( ! empty( $data['playtime_seconds'] ) )
        $metadata['length'] = (int) round( $data['playtime_seconds'] );
    if ( ! empty( $data['playtime_string'] ) )
        $metadata['length_formatted'] = $data['playtime_string'];


    return $metadata;
}

function get_max_upload_size() {
	$u_bytes = convert_hr_to_bytes( ini_get( 'upload_max_filesize' ) );
	$p_bytes = convert_hr_to_bytes( ini_get( 'post_max_size' ) );

	return min( $u_bytes, $p_bytes );
}

function convert_hr_to_bytes( $value ) {
	$value = strtolower( trim( $value ) );
	$bytes = (int) $value;
	if ( false !== strpos( $value, 'g' ) ) {
		$bytes *= GB_IN_BYTES;
	} elseif ( false !== strpos( $value, 'm' ) ) {
		$bytes *= MB_IN_BYTES;
	} elseif ( false !== strpos( $value, 'k' ) ) {
		$bytes *= KB_IN_BYTES;
	}
	return min( $bytes, PHP_INT_MAX );
}
?>
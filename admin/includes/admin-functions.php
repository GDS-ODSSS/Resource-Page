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

function get_text_align($dir = 1)
{
    global $config;
    $get_dir = get_language_direction($config['language']);
    if($get_dir == 'rtl')
    {
        $align['1'] = 'text-align:right;';
        $align['2'] = 'text-align:center;';
        $align['3'] = 'text-align:left;';
    }
    else
    {
        $align['1'] = 'text-align:left;';
        $align['2'] = 'text-align:center;';
        $align['3'] = 'text-align:right;';
    }
    return $align[$dir];
}

function is_user_permissions($permissions = false, $page_no_permissions = true)
{
    $userid     = get_session_userid();
    $user_level = get_user_meta($userid,'userlevel');
    if($userid and is_serialized(get_user_meta($userid,'userpermissions')))
    {
        $user_perm = maybe_unserialize(get_user_meta($userid,'userpermissions'));
    }
    else
    {
        $user_perm = array();
    }
    if($page_no_permissions and !in_array($permissions,$user_perm) and $user_level != 'administrator')
    {
        get_admin_invalid_page(get_admin_languages('sorry_you_are_not_allowed_page'));
    }
    else
    {
        return (in_array($permissions,$user_perm) or $user_level == 'administrator')? true : false ;
    }
}

function get_admin_invalid_page($text)
{
    global $config;
    echo '<!DOCTYPE html><html xmlns="http://www.w3.org/1999/xhtml" lang="en-US"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><meta name="viewport" content="width=device-width"><meta name="robots" content="noindex,follow" /><title>'.$config['sitename'].' &rsaquo; Error</title>
    <style type="text/css">html{background:#f1f1f1}body{background:#fff;color:#444;font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen-Sans,Ubuntu,Cantarell,"Helvetica Neue",sans-serif;margin:2em auto;padding:1em 2em;max-width:700px;-webkit-box-shadow:0 1px 3px rgba(0,0,0,.13);box-shadow:0 1px 3px rgba(0,0,0,.13)}h1{border-bottom:1px solid #dadada;clear:both;color:#666;font-size:24px;margin:30px 0 0;padding:0 0 7px}#error-page{margin-top:50px}#error-page p{font-size:14px;line-height:1.5;margin:25px 0 20px;text-align: center;}</style>
    </head><body id="error-page"><p>'.$text.'</p></body></html>';
    exit;
}

function admin_get_form_status($status, $name = false)
{
    return ($status == 'on')? 1 : 0;
}

function admin_set_token($tokenname = false)
{
    $token = securitytokenadmincp();
    if($tokenname)
    {
        $_SESSION['tokenadmincp']['user'] = $token;
    }
    else
    {
        $_SESSION['securitytokenadmincp'] = $token;
    }
    return $token;
}

function securitytokenadmincp($length = '15',$num = false)
{
    if($num){$chars = '0123456789';}
    else{$chars = 'abcdefghkmnopqrstuvwxyz0123456789';  }
    srand((double)microtime()*1000000);
    $str = '';
    for($i=0 ; $i <= $length ;$i++){
        $num = rand() % 33;
        $tmp = substr($chars,$num,1);
        $str = $str. $tmp;
    }
    return $str;
}

function get_user($filed,$id)
{
    global $db;
    $result = $db->sql_query("SELECT * FROM ".USERS_TABLE." WHERE `id`='".$id."'");
    $user   = $db->sql_fetchrow($result);
    $db->sql_freeresult($result);
    return $user[$filed];
}

function timezone_list($timezone)
{
    $zones_array = array();
    $timestamp = time();
    foreach(timezone_identifiers_list() as $key => $zone) {
        @date_default_timezone_set($zone);
        $zones_array[$key]['zone'] = $zone;
        $zones_array[$key]['diff_from_GMT'] = 'UTC/GMT ' . date('P', $timestamp);
    }
    $option = '<option value="0">Please, select timezone</option>';
    foreach($zones_array as $t)
    {
        $sel = ($timezone == $t['zone'])? 'selected="selected"' : '';
        $option .= '<option value="'.$t['zone'].'" '.$sel.'>'. $t['zone'].'</option>';
    }
    return $option;
}

function current_page_sidebar($page = '', $return = '', $else_return = '', $request = '', $eql = '')
{
    $thispage = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
    if(!$request)
    {
        return ($page == $thispage)? $return : $else_return;
    }
    else
    {
        return (isset($_REQUEST[$request]) and $_REQUEST[$request] == $eql)? $return : $else_return;
    }
}

function current_sub_page_sidebar($request, $eql , $return = '', $else_return = '')
{
    return (isset($_REQUEST[$request]) and $_REQUEST[$request] == $eql)? $return : $else_return;
}

function admin_ajax_nav_menu_list()
{
    global $config;
    if(isset($_POST['action']) and $_POST['action'] == 'addmenuitem')
    {
        $key                = safe_input(intval($_POST['itemid']));
        $value['title']     = (isset($_POST['title']))? safe_input($_POST['title']) : '';
        $value['icon']      = (isset($_POST['icon']))? safe_input($_POST['icon']) : '';
        $value['url']       = (isset($_POST['url']))? $_POST['url'] : ''; //$config['siteurl'].'/'.$_POST['url'];
        $value['itemtype']  = (isset($_POST['type']))? safe_input($_POST['type']) : '';
        $value['linkhtml']  = '';
        $value['linkphp']   = '';
        $value['classes']   = '';
        $value['target']    = '';
        $value['useronly']  = '';
        $value['depth']     = '';
        $value['parent']    = '';
		if(safe_input($_POST['type']) == 'Custom_Link')
		{
			$html = get_nav_menu_custom_link_list($key, $value);
		}
		else
		{
			$html = get_loop_nav_menu_list($key, $value);
		}
        $arr = array('status' => 'success', 'html' => $html);
        echo json_encode($arr);
        exit;
    }
	if(isset($_POST['action']) and $_POST['action'] == 'savemenuitem')
	{
		echo json_encode($_POST['menu-item']);
        exit;
    }
}
$hooks->add_action('admin_ajax_index_display', 'admin_ajax_nav_menu_list', 1);

function get_nav_menu_custom_link_list($key , $value, $li = true)
{
    $menuopen 			= (isset($value['menuopen']) and $value['menuopen'])? 'menu-open' : '';
    $checked_target   	= (isset($value['target']) and $value['target'])? 'checked="check"' : '';
    $checked_useronly 	= (isset($value['useronly']))? 'checked="check"' : '';
    $value['title'] 	= (isset($value['title']))? $value['title'] : '';
    $value['itemtype'] 	= (isset($value['itemtype']))? $value['itemtype'] : '';
    $value['icon'] 	    = (isset($value['icon']))? $value['icon'] : '';
    $value['classes'] 	= (isset($value['classes']))? $value['classes'] : '';
    $value['menuopen'] 	= (isset($value['menuopen']) and $value['menuopen'])? $value['menuopen'] : '';
    $value['url'] 	    = (isset($value['url']))? $value['url'] : '';
    $value['image']     = (isset($value['image']) and $value['image'])? $value['image'] : '';
    $display_image      = (isset($value['image']) and $value['image'])? 'style="display: block;"' : 'style="display: none;"';
    $display_button     = (isset($value['image']) and $value['image'])? 'display: none;' : 'display: block;';


	$html  = ($li)? '<li class="menu-item '.$menuopen.'" id="menu-item-'.$key.'" data-id="'.$key.'">' : '';
    $html .= '
        <div class="menu-item-bar">
            <div class="menu-item-handle">
                <span class="item-title"><span class="menu-item-title menu-title-'.$key.'">'.$value['title'].'</span></span>
                <span class="item-controls">
                    <span class="item-type">'.str_replace("_", " ", $value['itemtype']).'</span><i class="item-menu-edit" data-id="'.$key.'"></i>
                </span>
            </div>
        </div>
        <div class="menu-item-settings" id="menu-item-settings-'.$key.'">
            <div class="form-group">
                <label>'.get_admin_languages('url').'</label>
                <input type="text" class="form-control input-sm menu-url" data-id="'.$key.'" name="url['.$key.']" value="'.$value['url'].'">
            </div>
			<div class="form-group">
                <label>'.get_admin_languages('navigation_label').'</label>
                <input type="text" class="form-control input-sm menu-title" data-id="'.$key.'" name="title['.$key.']" value="'.$value['title'].'">
            </div>
            <div class="form-group">
                <label>'.get_admin_languages('icon').'</label>
                <div class="megapanel-icon-select">
                    <span class="megapanel-icon-preview"><i class="'.$value['icon'].'"></i></span>
                    <button type="button" class="button button-primary megapanel-icon-add">'.get_admin_languages('changes').'</button>
                    <button type="button" class="button megapanel-icon-default" data-geticon="'.$value['icon'].'">'.get_admin_languages('Default').'</button>
                    <button type="button" class="button button-remove megapanel-icon-remove">'.get_admin_languages('Remove').'</button>
                    <input type="hidden" name="icon['.$key.']" value="'.$value['icon'].'" class="megapanel-icon-value">
                </div>
            </div>
            <div class="form-group">
                <label>'.get_admin_languages('image').'</label>
                <div>
                    <div class="box-uploads">
                        <input class="cmp-input-image-'.$key.' form-control" type="text" name="image['.$key.']" value="'.$value['image'].'" dir="ltr">
                        <button typy="button" class="cmp-image-'.$key.'-button btn btn-block btn-default" style="right: 0px;'.$display_button.'">Upload</button>
                    </div>
                    <div class="cmp-image-'.$key.'-preview cmp-preview" '.$display_image.'>
                        <img src="'.$value['image'].'">
                        <a class="cmp-remove-image-'.$key.'-image icon-remove"></a>
                    </div>
                    <script type="text/javascript">creative_media_upload("image-'.$key.'","image", "library", "", "src");</script>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="form-group">
                <label>'.get_admin_languages('css_classes').'</label>
                <input type="text" class="form-control input-sm" name="classes['.$key.']" value="'.$value['classes'].'">
            </div>
            <div class="form-group">
                <input type="checkbox" value="_blank" name="target['.$key.']" '.$checked_target.'>
                <span class="lbl">'.get_admin_languages('open_link_in_a_new_tab').'</span> 
            </div>            
            <div class="menu-item-actions">
                <span class="item-delete menu-submitdelete" data-id="'.$key.'">'.get_admin_languages('remove').'</span>
            </div>
            <input type="hidden" class="data-itemtype-'.$key.'" name="itemtype['.$key.']" value="'.$value['itemtype'].'">
            <input type="hidden" class="menu-open-'.$key.'" name="menuopen['.$key.']" value="'.$value['menuopen'].'">
        </div>
    ';
	$html .= ($li)? '</li>' : '';
	return $html;
}

function get_loop_nav_menu_list($key , $value, $li = true)
{
    $menuopen 			= (isset($value['menuopen']) and $value['menuopen'])? 'menu-open' : '';
    $checked_target   	= (isset($value['target']) and $value['target'])? 'checked="check"' : '';
    $checked_useronly 	= (isset($value['useronly']))? 'checked="check"' : '';
    $value['title'] 	= (isset($value['title']))? $value['title'] : '';
    $value['itemtype'] 	= (isset($value['itemtype']))? $value['itemtype'] : '';
    $value['icon'] 	    = (isset($value['icon']))? $value['icon'] : '';
    $value['classes'] 	= (isset($value['classes']))? $value['classes'] : '';
    $value['menuopen'] 	= (isset($value['menuopen']) and $value['menuopen'])? $value['menuopen'] : '';
    $value['url'] 	    = (isset($value['url']))? $value['url'] : '';
    $value['image']     = (isset($value['image']) and $value['image'])? $value['image'] : '';
    $display_image      = (isset($value['image']) and $value['image'])? 'style="display: block;"' : 'style="display: none;"';
    $display_button     = (isset($value['image']) and $value['image'])? 'display: none;' : 'display: block;';
    $html  = ($li)? '<li class="menu-item '.$menuopen.'" id="menu-item-'.$key.'" data-id="'.$key.'">' : '';
    $html .= '
        <div class="menu-item-bar">
            <div class="menu-item-handle">
                <span class="item-title"><span class="menu-item-title menu-title-'.$key.'">'.$value['title'].'</span></span>
                <span class="item-controls">
                    <span class="item-type">'.str_replace("_", " ", $value['itemtype']).'</span><i class="item-menu-edit" data-id="'.$key.'"></i>
                </span>
            </div>
        </div>
        <div class="menu-item-settings" id="menu-item-settings-'.$key.'">
            <div class="form-group">
                <label>'.get_admin_languages('navigation_label').'</label>
                <input type="text" class="form-control input-sm menu-title" data-id="'.$key.'" name="title['.$key.']" value="'.$value['title'].'">
            </div>
            <div class="form-group">
                <label>'.get_admin_languages('icon').'</label>
                <div class="megapanel-icon-select">
                    <span class="megapanel-icon-preview"><i class="'.$value['icon'].'"></i></span>
                    <button type="button" class="button button-primary megapanel-icon-add">'.get_admin_languages('changes').'</button>
                    <button type="button" class="button megapanel-icon-default" data-geticon="'.$value['icon'].'">'.get_admin_languages('Default').'</button>
                    <button type="button" class="button button-remove megapanel-icon-remove">'.get_admin_languages('Remove').'</button>
                    <input type="hidden" name="icon['.$key.']" value="'.$value['icon'].'" class="megapanel-icon-value">
                </div>
            </div>
            <div class="form-group">
                <label>'.get_admin_languages('image').'</label>
                <div>
                    <div class="box-uploads">
                        <input class="cmp-input-image-'.$key.' form-control" type="text" name="image['.$key.']" value="'.$value['image'].'" dir="ltr">
                        <button typy="button" class="cmp-image-'.$key.'-button btn btn-block btn-default" style="right: 0px;'.$display_button.'">Upload</button>
                    </div>
                    <div class="cmp-image-'.$key.'-preview cmp-preview" '.$display_image.'>
                        <img src="'.$value['image'].'">
                        <a class="cmp-remove-image-'.$key.'-image icon-remove"></a>
                    </div>
                    <script type="text/javascript">creative_media_upload("image-'.$key.'","image", "library", "", "src");</script>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="form-group">
                <label>'.get_admin_languages('css_classes').'</label>
                <input type="text" class="form-control input-sm" name="classes['.$key.']" value="'.$value['classes'].'">
            </div>
            <div class="form-group">
                <input type="checkbox" value="_blank" name="target['.$key.']" '.$checked_target.'>
                <span class="lbl">'.get_admin_languages('open_link_in_a_new_tab').'</span> 
            </div>            
            <div class="menu-item-actions">
                <span class="item-delete menu-submitdelete" data-id="'.$key.'">'.get_admin_languages('remove').'</span>
            </div>
            <input type="hidden" class="data-itemtype-'.$key.'" name="itemtype['.$key.']" value="'.$value['itemtype'].'">
            <input type="hidden" class="menu-open-'.$key.'" name="menuopen['.$key.']" value="'.$value['menuopen'].'">
            <input type="hidden" name="url['.$key.']" value="'.$value['url'].'">
        </div>
    ';
	$html .= ($li)? '</li>' : '';
	return $html;
}

function admin_get_info_attachment($id)
{
    global $db;
    $sql    = "SELECT * FROM ".ATTACHMENT_TABLE." WHERE  `id`='".$db->sql_escape($id)."'";
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    return $row;
}

function admin_validate_dimensions($path,$width,$height)
{
    $image = getimagesize($path);
    if($image[0] >= $width){$output['w'] = true;}
    else{$output['w'] = false;}
    if($image[1] >= $height){$output['h'] = true;}
    else{$output['h'] = false;}
    return $output;
}

function admin_validatefile($path, $name, $allowed = array('png', 'jpg', 'gif', 'jpeg'))
{
	$ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
	$image = getimagesize($path);
	$output['width'] = $image[0];
	$output['height'] = $image[1];
    $output['mime'] = str_replace('image/', '', $image['mime']);
	// Verify if the mime type and extensions are allowed
	if(in_array($output['mime'], $allowed) && in_array($ext, $allowed)) {
		$output['valid'] = 1;
	} else {
		$output['valid'] = 0;
	}
	return $output;
}

function admin_upload_file($file,$path = 'images/',$username = 'none')
{
    global $db;
    $ext          = pathinfo($_FILES[$file]['name'], PATHINFO_EXTENSION);
    $admin_validatefile = admin_validatefile($_FILES[$file]['tmp_name'], $_FILES[$file]["name"]);
    
    if($admin_validatefile['valid'])
    {
        $imagesname     = preg_replace('/[^\w\._]+/', '_', $_FILES[$file]["name"]);
        $images         = $username.'_'.mt_rand().'_'.time().'.'.$ext;
        $type           = strrchr(strtolower($images) ,'.');
		$fileext        = str_replace(".","",$type);
		$fileext        =  strtolower($fileext);
        // Move the file into the uploaded folder
        move_uploaded_file($_FILES[$file]['tmp_name'], '../uploads/' . $path . $images );
        $imgname        = $imagesname;
        $imgname        = str_replace(".".$fileext,"",$imagesname);
        $imgurl         = '../uploads/' . $path . $images;
        $imgsize        = $_FILES[$file]['size'];
        $imgtype        = $_FILES[$file]['type'];
        $default        = 1;
        $orders         = 1;
        $image_info     = getimagesize('../uploads/' . $path . $images);
        $image_width    = $image_info[0];
        $image_height   = $image_info[1];
        $dimensions     = ''.$image_width.' × '.$image_height.'';
        $thumbnail  = ''; // if like add thumbnail images remove this line
        $medium     = ''; // if like add thumbnail images remove this line
        $sql_ins = array(
            'id'            => (int)'',
            'title'         => $imgname,
            'thumbnail'     => $thumbnail,
            'medium'        => $medium,
            'full'          => $imgurl,
           	'url'           => $imgurl, 
            'time'          => (int)time(), 
            'size'          => (int)$imgsize, 
            'type'          => $imgtype,    
        );
        $sql     = 'INSERT INTO ' . ATTACHMENT_TABLE . ' ' . $db->sql_build_array('INSERT', $sql_ins, false);
        $result  = $db->sql_query($sql);
        $db->sql_freeresult($result);
        $id = $db->sql_nextid();
        $new_file = '../uploads/' . $path . $images;
        $stat = stat( dirname( $new_file ));
    	$perms = $stat['mode'] & 0000666;
    	@ chmod( $new_file, $perms );
        $atr = array('id' => $id, 'imgurl' => $imgurl, 'imgw' => $image_width, 'imgh' => $image_height);
        return $atr;
    }
    else
    {
         $atr = array('id' => false, 'imgurl' => false, 'imgw' => false, 'imgh' => false);
         return $atr;
    }
}

function admin_upload_file_array($file,$i = '',$path = 'images/')
{
    global $db;
    $ext          = pathinfo($_FILES[$file]['name'][$i], PATHINFO_EXTENSION);
    $admin_validatefile = admin_validatefile($_FILES[$file]['tmp_name'][$i], $_FILES[$file]["name"][$i]);
    if($admin_validatefile['valid'])
    {
        $imagesname     = preg_replace('/[^\w\._]+/', '_', $_FILES[$file]["name"][$i]);
        $images         = $session->get_account('username').'_'.mt_rand().'_'.time().'.'.$ext;
        $type           = strrchr(strtolower($images) ,'.');
		$fileext        = str_replace(".","",$type);
		$fileext        =  strtolower($fileext);
        // Move the file into the uploaded folder
        move_uploaded_file($_FILES[$file]['tmp_name'][$i], 'uploads/' . $path . $images );
        $imgurl         = 'uploads/' . $path . $images;
        $stat = stat( dirname( $imgurl ));
    	$perms = $stat['mode'] & 0000666;
    	@ chmod( $imgurl, $perms );
        return $imgurl;
    }
    else
    {
         return false;
    }
}
?>
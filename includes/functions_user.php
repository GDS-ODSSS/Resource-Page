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

// if (has) user mata
function has_user_meta($user_id, $meta_key)
{
    global $db;
    $sql = "SELECT meta_value FROM ".USERSMETA_TABLE." WHERE `meta_key`='{$meta_key}' AND `user_id`='{$user_id}'";
    return $db->sql_numrows($sql);
}
// if (has) user found
function has_user_found($user_id)
{
    global $db;
    $sql = "SELECT id FROM ".USERS_TABLE." WHERE `id`='{$user_id}'";
    return $db->sql_numrows($sql);
}
// get user meta
function get_user_meta($user_id, $meta_key, $return = false)
{
    global $db;
    if(has_user_meta($user_id, $meta_key))
    {
        $sql    = "SELECT meta_value FROM ".USERSMETA_TABLE." WHERE `meta_key`='{$meta_key}' AND `user_id`='{$user_id}'";
        $result = $db->sql_query($sql);
        $row    = $db->sql_fetchrow($result);
        return $row['meta_value'];
    }
    else
    {
        return $return;
    }
}
// update user meta
function update_user_meta($user_id, $meta_key, $meta_value = '')
{
    global $db;
    if(has_user_meta($user_id, $meta_key))
    {
        $result = $db->sql_query("UPDATE " . USERSMETA_TABLE . " SET  `meta_value`='{$meta_value}' WHERE `meta_key`='{$meta_key}' AND `user_id`='{$user_id}'");
    }
    else
    {
        $sql_ins = array(
            'meta_id'       => (int)'',
            'user_id'       => (int)$user_id,
            'meta_key'      => $meta_key,
            'meta_value'    => $meta_value
        );
        $sql     = 'INSERT INTO ' . USERSMETA_TABLE . ' ' . $db->sql_build_array('INSERT', $sql_ins, true);
        $result  = $db->sql_query($sql);
    }
}
// update user column
function update_user_column($column, $value, $user_id )
{
    global $db;
    if(has_user_found($user_id))
    {
        $db->sql_query("UPDATE " . USERS_TABLE . " SET  `{$column}`='{$value}' WHERE `id`='{$user_id}'");
    }
}
// get user column
function get_user_column($user_id, $column)
{
    global $db;
    $result = $db->sql_query("SELECT {$column} FROM ".USERS_TABLE." WHERE `id`='{$user_id}'");
    $date   = $db->sql_fetchrow($result);
    return $date[$column];
}
// get user id by column
function get_userid_by_column($column, $value)
{
    global $db;
    $result = $db->sql_query("SELECT id FROM ".USERS_TABLE." WHERE `{$column}`='{$value}'");
    $date   = $db->sql_fetchrow($result);
    return $date['id'];
}
// get user date by column
function get_userdate_by_column($column, $value)
{
    global $db;
    $result = $db->sql_query("SELECT * FROM ".USERS_TABLE." WHERE `{$column}`='{$value}'");
    $date   = $db->sql_fetchrow($result);
    return $date;
}
// get user date
function get_user_date($user_id, $return = 'date', $prefix = 'IN_USER_')
{
    global $db, $template;
    
    $result  = $db->sql_query("SELECT * FROM ".USERS_TABLE." WHERE `id`='{$user_id}'");
    $date    = $db->sql_fetchrow($result);
    if($return == 'assign' OR $return == 'global')
    {
        $template->assign_vars(array(
            $prefix.'ID'        => $date['id'],
            $prefix.'USERNAME'  => $date['username'],
            $prefix.'EMAIL'     => $date['email'],
            $prefix.'HAS_CAPA'  => get_user_meta($date['id'],'capabilities', 'user'),
            $prefix.'AVATAR'    => get_gravatar($date['id'])
            
        ));
    }
    if($return == 'date' OR $return == 'global')
    {
         return $date;
    }
}
// get gravatar
function get_gravatar($user_id, $s = 80, $d = 'identicon', $r = 'gb', $dir = '') {
    global $config;
    $email  = get_user_column($user_id, 'email');
    $avatar = get_user_meta($user_id, 'avatar', false);
    $url = 'https://gravatar.com/avatar/' . md5( strtolower( trim( $email ) ) ) . '?size='.$s;
	return $url;
}
// get gravatar by email
function get_gravatar_email($email, $s = 80) {
    $url = 'https://gravatar.com/avatar/' . md5( strtolower( trim( $email ) ) ) . '?size='.$s;
	return $url;
}
// get cover (old Version)
function get_cover($user_id, $dir = '')
{
    global $config;
    $cover  = get_user_meta($user_id,'cover', false);
    if($cover)
    {
        $url = $config['siteurl'].'/uploads/userscovers/'.$cover;
    }
    else
    {
        $url = '';
    }
	return $url;
}
// check user field
function check_user_field($field, $val, $userid = false)
{
	global $db;
    if($userid)
    {
        $sql = "SELECT id,$field FROM ".USERS_TABLE." WHERE `{$field}`='{$val}' and `id`!={$userid}";
    }
    else
    {
        $sql = "SELECT $field FROM ".USERS_TABLE." WHERE `{$field}`='{$val}'";
    }
    return $db->sql_numrows($sql);
}
// get select option input
function get_user_field_select_input($input, $echo = true)
{
    global $db;
    $class = (isset($input['class']))? $input['class'] : '' ;
    $html = '<select name="'.$input['id'].'" class="form-control '.$class.'">';
    $result  = $db->sql_query("SELECT * FROM ".USERS_TABLE."");
    while($row = $db->sql_fetchrow($result))
    {
        $selected = ($input['value'] == $row['id'])? 'selected=""' : '';
        $html .= '<option value="'.$row['id'].'" '.$selected.'>'.$row['username'].'</option>';
    }
	$html .= '<select>';
    if( $echo ){ echo $html; } else{ return $html; }
}
?>
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


// has term meta
function has_term_meta($term_id, $meta_key)
{
    global $db;
    $sql = "SELECT meta_value FROM ".TERMSMETA_TABLE." WHERE `meta_key`='{$meta_key}' AND `term_id`='{$term_id}'";
    return $db->sql_numrows($sql);
}
// has term found
function has_term_found($term_id, $type = 'post')
{
    global $db;
    $sql = "SELECT id FROM ".TERMS_TABLE." WHERE `type`='{$type}' and `id`='{$term_id}'";
    return $db->sql_numrows($sql);
}
// get term meta
function get_term_meta($term_id, $meta_key, $return = false)
{
    global $db;
    if(has_term_meta($term_id, $meta_key))
    {
        $sql    = "SELECT meta_value FROM ".TERMSMETA_TABLE." WHERE `meta_key`='{$meta_key}' AND `term_id`='{$term_id}'";
        $result = $db->sql_query($sql);
        $row    = $db->sql_fetchrow($result);
        return $row['meta_value'];
    }
    else
    {
        return $return;
    }
}
// update term meta
function update_term_meta($term_id, $meta_key, $meta_value = '')
{
    global $db;
    if(has_term_meta($term_id, $meta_key))
    {
        $result = $db->sql_query("UPDATE " . TERMSMETA_TABLE . " SET  `meta_value`='{$meta_value}' WHERE `meta_key`='{$meta_key}' AND `term_id`='{$term_id}'");
    }
    else
    {
        $sql_ins = array(
            'meta_id'       => (int)'',
            'term_id'       => (int)$term_id,
            'meta_key'      => $meta_key,
            'meta_value'    => $meta_value
        );
        $sql     = 'INSERT INTO ' . TERMSMETA_TABLE . ' ' . $db->sql_build_array('INSERT', $sql_ins, true);
        $result  = $db->sql_query($sql);
    }
}
// update term column
function update_term_column($column, $value, $term_id )
{
    global $db;
    if(has_term_found($term_id))
    {
        $db->sql_query("UPDATE " . TERMS_TABLE . " SET  `{$column}`='{$value}' WHERE `id`='{$term_id}'");
    }
}
// get term column
function get_term_column($term_id, $column)
{
    global $db;
    $result = $db->sql_query("SELECT {$column} FROM ".TERMS_TABLE." WHERE `id`='{$term_id}'");
    $date   = $db->sql_fetchrow($result);
    return $date[$column];
}
// get term column by slug
function get_term_column_by_slug($slug, $column)
{
    global $db;
    $slug = utf8_uri_encode($slug);
    $result = $db->sql_query("SELECT {$column} FROM ".TERMS_TABLE." WHERE `slug`='{$slug}'");
    $date   = $db->sql_fetchrow($result);
    return $date[$column];
}
// get term loop
function get_terms_loop($args = array())
{
    global $db, $template, $permanent_links;
    $args['orderby'] = (isset($args['orderby']))? $args['orderby'] : 'orders';
    $args['orders']  = (isset($args['orders']))? $args['orders'] : 'ASC';
    if(isset($args['parent']) and $args['parent'] == 'none')
    {
        $result = $db->sql_query("SELECT * FROM ".TERMS_TABLE." WHERE `status`='1' and `parent`='0' and `type`='{$args['term_type']}' ORDER BY {$args['orderby']} {$args['orders']}");
    }
    if(isset($args['parent']) and $args['parent'])
    {
        $result = $db->sql_query("SELECT * FROM ".TERMS_TABLE." WHERE `status`='1' and `parent`='{$args['parent']}' and `type`='{$args['term_type']}' ORDER BY {$args['orderby']} {$args['orders']}");
    }
    else
    {
        $result = $db->sql_query("SELECT * FROM ".TERMS_TABLE." WHERE `status`='1' and `type`='{$args['term_type']}' ORDER BY {$args['orderby']} {$args['orders']}");
    }
    if(isset($args['assign_name']))
    {
        $args['show'] = (isset($args['show']))? $args['show'] : '';
        while($row = $db->sql_fetchrow($result))
        {
            if(get_count_posts_term($row['id']) or $args['show'])
            {
                $template->assign_block_vars($args['assign_name'], array( 
                    'TERM_ID'               => $row['id'],
                    'TERM_NAME'             => $row['name'], 
                    'TERM_SLUG'             => $row['slug'],
                    'TERM_COUNT_POSTS'      => get_count_posts_term($row['id']),
                    'TERM_PERMANENT_LINK'   => permanent_terms_link($args['terms_link'],$row['id'],$row['slug']),
                ));
            }
        }
    }
    else
    {
        return $result;
    }
}
// get term select options
function get_term_select_option($terms, $id = '')
{
    global $db;
    $result = $db->sql_query("SELECT * FROM ".TERMS_TABLE." WHERE `type`='{$terms}' ORDER BY orders ASC");
    $option = '';
    while ($row = $db->sql_fetchrow($result)) 
    {
        $selected = ($id == $row['id'])? 'selected="selected"' : '';
        $option .= '<option value="'.$row['id'].'" '.$selected.'>'.$row['name'].'</option>';
    }
    return $option;
}
?>
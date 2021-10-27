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

// has post meta
function has_post_meta($post_id, $meta_key)
{
    global $db;
    $sql = "SELECT meta_value FROM ".POSTSMETA_TABLE." WHERE `meta_key`='{$meta_key}' AND `post_id`='{$post_id}'";
    return $db->sql_numrows($sql);
}
// has post found
function has_post_found($post_id, $type = 'post')
{
    global $db;
    $postid = (int) safe_input(intval($post_id));
    $sql    = "SELECT id FROM ".POSTS_TABLE." WHERE `id`='{$post_id}' and `post_type`='{$type}'";
    return $db->sql_numrows($sql);
}
// get post meta
function get_post_meta($post_id, $meta_key, $return = false, $test = false)
{
    global $db;
    if(has_post_meta($post_id, $meta_key))
    {
        $sql    = "SELECT meta_value FROM ".POSTSMETA_TABLE." WHERE `meta_key`='{$meta_key}' AND `post_id`='{$post_id}'";
        $result = $db->sql_query($sql);
        $row    = $db->sql_fetchrow($result);
        return $row['meta_value'];
    }
    else
    {
        return $return;
    }
}
// get posi id by meta
function get_postid_meta_value($meta_key, $meta_value)
{
    global $db;
    $sql = "SELECT post_id FROM ".POSTSMETA_TABLE." WHERE `meta_key`='{$meta_key}' AND `meta_value`='{$meta_value}'";
    if($db->sql_numrows($sql))
    {
        $result = $db->sql_query($sql);
        $row    = $db->sql_fetchrow($result);
        return $row['post_id'];
    }
    else
    {
        return false;
    } 
}
// update post meta
function update_post_meta($post_id, $meta_key, $meta_value = '')
{
    global $db;
    if(has_post_meta($post_id, $meta_key))
    {
        $result = $db->sql_query("UPDATE " . POSTSMETA_TABLE . " SET  `meta_value`='{$meta_value}' WHERE `meta_key`='{$meta_key}' AND `post_id`='{$post_id}'");
    }
    else
    {
        $sql_ins = array(
            'meta_id'       => (int)'',
            'post_id'       => (int)$post_id,
            'meta_key'      => $meta_key,
            'meta_value'    => $meta_value
        );
        $sql     = 'INSERT INTO ' . POSTSMETA_TABLE . ' ' . $db->sql_build_array('INSERT', $sql_ins, true);
        $result  = $db->sql_query($sql);
    }
}
// update post meta view
function update_post_meta_view($post_id, $meta_key)
{
    global $db;
    if(has_post_meta($post_id, $meta_key))
    {
        $result = $db->sql_query("UPDATE " . POSTSMETA_TABLE . " SET  `meta_value`=meta_value+1 WHERE `meta_key`='{$meta_key}' AND `post_id`='{$post_id}'");
    }
    else
    {
        $sql_ins = array(
            'meta_id'       => (int)'',
            'post_id'       => (int)$post_id,
            'meta_key'      => $meta_key,
            'meta_value'    => (int)'1'
        );
        $sql     = 'INSERT INTO ' . POSTSMETA_TABLE . ' ' . $db->sql_build_array('INSERT', $sql_ins, true);
        $result  = $db->sql_query($sql);
    }
}
// update post column
function update_post_column($column, $value, $post_id, $post_type = 'post' )
{
    global $db;
    if(has_post_found($post_id, $post_type))
    {
        $result = $db->sql_query("UPDATE " . POSTS_TABLE . " SET  `{$column}`='{$value}' WHERE `id`='{$post_id}'");
        return $result;
    }
    else{
        return 'no';
    }
}
// get post column
function get_post_column($post_id, $column)
{
    global $db;
    $result = $db->sql_query("SELECT {$column} FROM ".POSTS_TABLE." WHERE `id`='{$post_id}'");
    $date   = $db->sql_fetchrow($result);
    return $date[$column];
}
// get post column by slug
function get_post_column_by_slug($slug, $column, $post_type = 'post')
{
    global $db;
    $slug = utf8_uri_encode($slug);
    $result = $db->sql_query("SELECT {$column} FROM ".POSTS_TABLE." WHERE `post_name`='{$slug}' and `post_type`='{$post_type}'");
    $date   = $db->sql_fetchrow($result);
    return $date[$column];
}
// get post title
function get_title_posts($post_id)
{
    global $template, $db, $config, $session;
    $result = $db->sql_query("SELECT post_title FROM ".POSTS_TABLE." WHERE `id`='{$post_id}'");
    $date   = $db->sql_fetchrow($result);
    return $date['post_title'];
}
// get publish status
function get_publish_status($post_id)
{
    global $config;
    $publish_status = get_post_meta($post_id, 'publish_status', false);
    if($publish_status == 'users')
    {
        if(has_session())
        {
            return array('status' => true, 'type' => 'user', 'msg' => '');
        }
        else
        {
            return array('status' => false, 'type' => 'user', 'msg' => $config['publish_status_user']);
        }
    }
    elseif($publish_status == 'private')
    {
        if(is_admin())
        {
            return array('status' => true, 'type' => 'private', 'msg' => '');
        }
        else
        {
            return array('status' => false, 'type' => 'private', 'msg' => $config['publish_status_private']);
        }
    }
    else
    {
        return array('status' => true, 'type' => 'public', 'msg' => '');
    }
}

function get_index_publish_status($publish_status)
{
    global $template, $lang;
    $template->assign_vars(array( 
        'PUB_TYPE'  => $publish_status['type'],
        'PUB_MSG'   => $publish_status['msg'],
    ));
    page_header(array('page_title' => $lang['publish_status'], 'pagedisplay' => 'publish_status'));
    $template->set_filename('index_publish_status.html');
    page_footer();
}

/* get pos tags */
function get_assign_post_tags($id, $type)
{
    global $template;
    $tags = get_post_meta($id, 'post_tags', '');
    $tags_array = explode(',', $tags);
    $x = 0;
    $con = count($tags_array);
    foreach($tags_array as $tag)
    {
        if($tag)
        {
            $x++;
            $template->assign_block_vars('loop_post_tags', array(
                'TAG_ID'    => $x,
                'TAG_NAME'  => trim($tag),
                'TAG_LINK'  => permanent_tags_link($type, trim($tag)),
                'TAG_CHAR'  => ($x < $con)? ' ,' : '',
            ));
        }
    }
    return $x;
}
/* get post type tags */
function get_post_type_tags($type)
{
    global $db;
    $tags = array();
    $result = $db->sql_query("SELECT id,meta_value FROM ".POSTS_TABLE." JOIN ".POSTSMETA_TABLE." ON (`meta_key`='post_tags') AND (`post_id`=`id`) WHERE `post_status`='1' and `post_type`='{$type}' and `meta_value`!=''");
    while($row = $db->sql_fetchrow($result))
    {
        $tags_array = explode(',', $row['meta_value']);
        $tags = array_merge( $tags, $tags_array);
    }
    return array_count_values($tags);
}
// get count post by user id (post_type, all)
function get_count_post_user($user_id, $post_type = "'post'")
{
    global $db;
    if($post_type == 'all')
    {
        $sql    = "SELECT id FROM ".POSTS_TABLE." WHERE `post_author`='{$user_id}'";
    }
    else
    {
        $type   = trim($post_type, ',');
        $sql    = "SELECT id FROM ".POSTS_TABLE." WHERE `post_author`='{$user_id}' AND `post_type` IN ({$type})";
    }
    return $db->sql_numrows($sql);
}
// get count post (post_type, all)
function get_count_posts($post_type = "'post'", $status = 1)
{
    global $db;
    $post_status = ($status)? " and `post_status`='{$status}'" : '';
    if($post_type == 'all')
    {
        $sql    = "SELECT * FROM ".POSTS_TABLE." WHERE {$post_status}";
    }
    else
    {
        $type   = trim($post_type, ',');
        $sql    = "SELECT * FROM ".POSTS_TABLE." WHERE {$post_status} `post_type` IN ({$type})";
    }
        
    return $db->sql_numrows($sql);
}
// get post per
function get_post_pre($post_type)
{
    global $db;
    $total_post = $db->sql_numrows("SELECT * FROM ".POSTS_TABLE."");
    $total_type = $db->sql_numrows("SELECT * FROM ".POSTS_TABLE." WHERE `post_type`='{$post_type}'");
    $per = ($total_type and $total_post)? ( $total_type / $total_post  ) * 100 : '0';
    return format_num($per,2);
}
// get coumt posts term id
function get_count_posts_term($term_id, $status = 1)
{
    global $db;
    $where_status = ($status)? "`post_status`='{$status}' and" : '';
    $sql    = "SELECT * FROM ".POSTS_TABLE." WHERE {$where_status} `term_id`='{$term_id}'";
    return $db->sql_numrows($sql);
}
// assign posts limit
function get_assign_limit_posts($args = array())
{
    global $template, $db, $config, $session, $permanent_links;
    $page           = (int) (!isset($_GET["pages"]) ? 1 : safe_input($_GET["pages"]));
    $page           = safe_input($page);
	$limit          = ($config['per_page'])? $config['per_page'] : 10;
	$startpoint     = ($page * $limit) - $limit;
    $orders_meta_key    = ($args['orders_meta_key'])? $args['orders_meta_key'] : 'pin_post';
    if(isset($args['term_id']) and $args['term_id'])
    {
        $sql    = "SELECT * FROM ".POSTS_TABLE." JOIN ".POSTSMETA_TABLE." ON (`meta_key`='{$orders_meta_key}') AND (`post_id`=`id`) WHERE `post_status`='1' and `post_type`='{$args['post_type']}' and `term_id`='{$args['term_id']}' ORDER BY meta_value DESC , {$args['orderby']} {$args['orders']}";
    }
    else
    {
        $sql    = "SELECT * FROM ".POSTS_TABLE." JOIN ".POSTSMETA_TABLE." ON (`meta_key`='{$orders_meta_key}') AND (`post_id`=`id`) WHERE `post_status`='1' and `post_type`='{$args['post_type']}' ORDER BY meta_value DESC , {$args['orderby']} {$args['orders']}";
    }
    $total          = $db->sql_numrows($sql);
    if($total)
    {
        $lastpage       = ceil($total/$limit);
        if($lastpage < $page )
        {
            @header("Location:index.php?mode={$args['post_type']}&pages={$lastpage}");
        }
        if($total > $limit){$showpagination = true;}else{$showpagination = false;}
        $result = $db->sql_query_limit($sql,$limit,$startpoint);
        while ($row = $db->sql_fetchrow($result)) 
        {
            set_assign_loop_posts($row,$args['assign_name']);
        }
        $template->assign_vars(array('PAGINATION'=> pagination($total,$limit,$page,$args['page_url'])));
        $template->assign_vars(array(
            'ALL'               => true,
            'SHOW_PAGINATION'   => $showpagination,
            'THISPAGE'          => $page,
            'OFPAGES'           => $lastpage,
            'NOTFOUND'          => $total,
        ));   
    }
}
// assign loop posts (not work)
function set_assign_loop_posts($row,$assign_name = 'loop_posts')
{
    global $template, $db, $config;
    $user_id = (has_session())? get_session_userid() : '0';
    $assign = array( 
        'POST_ID'               => $row['id'],
        'POST_TITLE'            => $row['post_title'], 
        'POST_CONTENT'          => $row['post_content'], 
        'POST_TYPE'             => $row['post_type'], 
        'POST_COMMENT_STATS'    => $row['comment_status'],
        'POST_MODIFIED'         => date($config['dateformat'], $row['post_modified']),
        'POST_TIME_AGO'         => get_timeago($row['post_modified']),
        'POST_VIEW'             => get_post_meta($row['id'], 'views','0'),
        'POST_TERM_ID'          => $row['term_id'],
        'POST_TERM_NAME'        => get_term_column($row['term_id'], 'name'),
        'POST_TERM_SLUG'        => get_term_column($row['term_id'], 'slug'),
        'POST_AUTHOR'           => get_user_column($row['post_author'],'username'), 
        'AUTHOR_AVATER'         => get_gravatar($row['post_author'],'40'),
    );
    if($assign_name)
    {
        $template->assign_block_vars($assign_name, $assign);
    }
    else
    {
        $template->assign_vars($assign);
    }
}
// get limit posts
function get_limit_posts($args = array())
{
    global $template, $db, $config, $session, $permanent_links;
    $page           = (int) (!isset($_GET["pages"]) ? 1 : safe_input($_GET["pages"]));
    $page           = safe_input($page);
    if(isset($args['per_page']))
    {
        $limit          = $args['per_page'];
    }
    else
    {
        $limit          = ($config['per_page'])? $config['per_page'] : 10;
    }
    if((isset($args['term_id']) and $args['term_id']))
    {
        if(is_array($args['term_id']))
        {
            $arg_in = implode(",", $args['term_id']);
            $where_termid = "and `term_id` IN($arg_in)";
        }
        else
        {
            $where_termid = "and `term_id`='{$args['term_id']}'";
        }
    }
    else
    {
        $where_termid = '';
    }
	$startpoint     = ($page * $limit) - $limit;
    $orders_meta_key    = (isset($args['orders_meta_key']))? $args['orders_meta_key'] : 'pin_post';
    if($orders_meta_key == 'none')
    {
        $sql    = "SELECT * FROM ".POSTS_TABLE." WHERE `post_status`='1' and `post_type`='{$args['post_type']}' {$where_termid} ORDER BY {$args['orderby']} {$args['orders']}";
    }
    elseif(isset($args['term_id']) and $args['term_id'])
    {
        $sql    = "SELECT * FROM ".POSTS_TABLE." JOIN ".POSTSMETA_TABLE." ON (`meta_key`='{$orders_meta_key}') AND (`post_id`=`id`) WHERE `post_status`='1' and `post_type`='{$args['post_type']}' {$where_termid} ORDER BY ABS(`meta_value`) ASC , {$args['orderby']} {$args['orders']}";
    }
    else
    {
        $sql    = "SELECT * FROM ".POSTS_TABLE." JOIN ".POSTSMETA_TABLE." ON (`meta_key`='{$orders_meta_key}') AND (`post_id`=`id`) WHERE `post_status`='1' and `post_type`='{$args['post_type']}' ORDER BY ABS(`meta_value`) ASC , {$args['orderby']} {$args['orders']}";
    }
    $total  = $db->sql_numrows($sql);
    if($total)
    {
        $result = $db->sql_query_limit($sql,$limit,$startpoint);
        return $result;   
    }
    else
    {
        return false;
    }
}
// get home posts
function get_home_posts($args = array())
{
    global $template, $db;
    
    $limit = (isset($args['per_page']))? $args['per_page'] : 8;
    
    if((isset($args['term_id']) and $args['term_id']))
    {
        if(is_array($args['term_id']))
        {
            $arg_in = implode(",", $args['term_id']);
            $where_termid = "and `term_id` IN($arg_in)";
        }
        else
        {
            $where_termid = "and `term_id`='{$args['term_id']}'";
        }
    }
    else
    {
        $where_termid = '';
    }
    $sql    = "SELECT * FROM ".POSTS_TABLE." WHERE `post_status`='1' and `post_type`='{$args['post_type']}' {$where_termid} ORDER BY {$args['orderby']} {$args['orders']} LIMIT {$limit}";
    $total  = $db->sql_numrows($sql);
    if($total)
    {
        $result = $db->sql_query($sql);
        return $result;   
    }
    else
    {
        return false;
    }
}
// get posts
function get_posts($args = array())
{
    global $template, $db, $config, $session, $permanent_links;
    $orders_meta_key      = (isset($args['orders_meta_key']))? $args['orders_meta_key'] : 'pin_post';
    $orders_meta_value    = (isset($args['orders_meta_value']))? $args['orders_meta_value'] : 'ASC';
    $args['orderby']      = (isset($args['orderby']))? $args['orderby'] : 'id';
    $args['orders']       = (isset($args['orders']))? $args['orders'] : 'ASC';
    if(isset($args['term_id']) and $args['term_id'])
    {
        $sql    = "SELECT * FROM ".POSTS_TABLE." JOIN ".POSTSMETA_TABLE." ON (`meta_key`='{$orders_meta_key}') AND (`post_id`=`id`) WHERE `post_status`='1' and `post_type`='{$args['post_type']}' and `term_id`='{$args['term_id']}' ORDER BY ABS(`meta_value`) {$orders_meta_value} , {$args['orderby']} {$args['orders']}";
    }
    else
    {
        $sql    = "SELECT * FROM ".POSTS_TABLE." JOIN ".POSTSMETA_TABLE." ON (`meta_key`='{$orders_meta_key}') AND (`post_id`=`id`) WHERE `post_status`='1' and `post_type`='{$args['post_type']}' ORDER BY ABS(`meta_value`) {$orders_meta_value} , {$args['orderby']} {$args['orders']}";
    }
    $total  = $db->sql_numrows($sql);
    if($total)
    {
        $result = $db->sql_query($sql);
        return $result;   
    }
    else
    {
        return false;
    }
}
// recent post
function recent_post($post_type, $limit)
{
    global $template, $db, $config, $session;
    $sql = "SELECT * FROM ".POSTS_TABLE." WHERE `post_status`='1' and `post_type`='{$post_type}' ORDER BY `post_modified` DESC LIMIT {$limit}";
    $total  = $db->sql_numrows($sql);
    if($total)
    {
        $result = $db->sql_query($sql);
        return $result;   
    }
    else
    {
        return false;
    }
}
// popular post
function popular_post($args = array())
{
    global $template, $db, $config, $session;
    $orders_meta_key    = ($args['orders_meta_key'])? $args['orders_meta_key'] : 'views';

    $sql = "SELECT * FROM ".POSTS_TABLE." JOIN ".POSTSMETA_TABLE." ON (`meta_key`='{$orders_meta_key}') AND (`post_id`=`id`) WHERE `post_status`='1' and `post_type`='{$args['post_type']}' ORDER BY ABS(`meta_value`) DESC LIMIT {$args['limit']}";
    $total  = $db->sql_numrows($sql);
    if($total)
    {
        $result = $db->sql_query($sql);
        return $result;   
    }
    else
    {
        return false;
    }
}
// get assign single posts
function get_assign_single_posts($args = array())
{
    global $template, $db, $config, $session;
    $sql    = "SELECT * FROM ".POSTS_TABLE." WHERE `post_status`='1' and `id`='{$args['post_id']}'";
    $total  = $db->sql_numrows($sql);
    if(!$total)
    {
        header("Location:{$args['location']}");
    }
    else
    {
        $result = $db->sql_query($sql);
        $row    = $db->sql_fetchrow($result);
        if(isset($args['meta_key_view']))
        {
            update_post_meta_view($args['post_id'], $args['meta_key_view']);
        }
        return $row;
    }  
}
// get title post
function get_post_title($title)
{
    return sanitize_text($title);
}
// get post content
function get_post_content($content)
{
    global $hooks;
    $content = sanitize_text($content);
    if($hooks->has_filter('post_content')):
        $content_filters = $hooks->apply_filters( 'post_content' , $content);
    else:
        $content_filters = $content;
    endif;
    
    return $content_filters;
}
// insert post
function insert_post($args, $post = false)
{
    global $db, $hooks;
    $sql_ins = array(
        'id'                => (int)'',
        'post_author'       => (int)$args['post_author'],
        'post_content'      => $args['content'],
        'post_title'        => $args['title'],
        'post_status'       => (int)$args['status'],
        'comment_status'    => (int)$args['comment_status'],
        'post_name'         => safe_input(preg_slug($args['title'])),
        'post_modified'     => (int)time(),
        'post_type'         => $args['post_type'],
        'term_id'           => (int)$args['term_id']
    );
    $sql     = 'INSERT INTO ' . POSTS_TABLE . ' ' . $db->sql_build_array('INSERT', $sql_ins, true);
    $result  = $db->sql_query($sql);
    $post_id = $db->sql_nextid();
    update_post_meta($post_id, 'post_date', time());
    if($post)
    {
        if(isset($post['excerpt'])){update_post_meta($post_id, 'excerpt',safe_input($post['excerpt']));}
        if(isset($post['post_tags'])){update_post_meta($post_id, 'post_tags', safe_input($post['post_tags']));}
        if(isset($post['thumbnails'])){update_post_meta($post_id, 'thumbnails', $post['thumbnails']);}
        if(isset($post['page_template'])){update_post_meta($post_id, 'page_template',safe_input($post['page_template']));}
    }
    return $post_id;
}
// get post attachment
function get_post_attachment($post_id, $size = false)
{
    global $config;
    $folderuploads  = trim($config['siteurl'], '/').'/';
    $thumbnailsid   = get_post_meta($post_id,'thumbnails');
    $meta   = maybe_unserialize(get_post_meta($thumbnailsid,'attachment_file'));
    if($meta)
    {
        switch($size)
        {
            default:
                $file = (isset($meta['file']))? $folderuploads.$meta['file'] : '';
            break;
            case 'thumbnail':
                $file = (isset($meta['thumbnail']))? $folderuploads.$meta['thumbnail'] : '';
            break;
            case 'medium':
                $file = (isset($meta['medium']))? $folderuploads.$meta['medium'] : '';
            break;
            case 'medium_large':
                $file = (isset($meta['medium_large']))? $folderuploads.$meta['medium_large'] : '';
            break;
            case 'all':
                $file['fileurl']        = (isset($meta['file']))? $folderuploads.$meta['file'] : '';
                $file['thumbnail']      = (isset($meta['thumbnail']))? $folderuploads.$meta['thumbnail'] : '';
                $file['medium']         = (isset($meta['medium']))? $folderuploads.$meta['medium'] : '';
                $file['medium_large']   = (isset($meta['medium_large']))? $folderuploads.$meta['medium_large'] : '';
            break;
        }
        return $file;
    }
    else
    {
        return false;
    }
}
?>
<?php
/**
 * Extension Name: Testimonials
 * Extension URI: http://nawaaugustine.com/php_help_manager/extension/testimonials
 * Version: 1.5
 * Requires: 2.0
 * Description: Testimonials
 * Author: Augustine Nawa
 * Author URI: http://nawaaugustine.com
*/
if (!defined("IN_PHPMEGATEMP"))
      die('Direct access to this location is not allowed.');

if(!class_exists('extensions_testimonials'))
{
    /* require class admin */
    require_once(dirname(__FILE__) .'/extension_admin.php');
    /* class extensions extends class admin */
    class extensions_testimonials extends extensions_admin_testimonials
    {
        /* construct */
        function __construct()
        {
            global $hooks;
            /* action and filter */
            $hooks->add_action('global_assign_vars', array($this , 'global_assign_vars'),1);
            $hooks->add_filter('add_page_theme_templates', array($this , 'page_theme_templates'), 40);
            $hooks->add_action('ajax_index_display', array($this , 'ajax_send_testimonial'),1);
            /* display admin */
            if(defined('IN_PHPMEGATEMP_CP'))
            {
                $this->display_admin_testimonials();
            }
        }
        /* get option ex*/
        function get_options($name)
        {
            global $config;
            $testimonials_options = array(
                'section_home'          => '1', 
                'post_per_page'         => '10',
                'post_per_page'         => '10',
                'terms_home'       => array(),
            );
            $options = (isset($config['testimonials_options']))? maybe_unserialize($config['testimonials_options']) : $testimonials_options;
            return @$options[$name];
        }
        /* global assign vars */
        function global_assign_vars()
        {
            global $routes, $db, $template, $config;
            $get_option = (isset($config['testimonials_options']))? maybe_unserialize($config['testimonials_options']) : array() ;
            foreach($get_option as $key => $value)
            {
                $template->assign_var('TESTIMONIALS_'.strtoupper($key), $value);
            }
            
            $limit = (count($routes))? '' : 'LIMIT '.$this->get_options('home_post_per_page');
            $sql       = "SELECT * FROM ".POSTS_TABLE." JOIN ".POSTSMETA_TABLE." ON (`meta_key`='orders_post') AND (`post_id`=`id`) WHERE `post_status`='1' and `post_type`='testimonials' ORDER BY meta_value ASC {$limit}";
            $result    = $db->sql_query($sql);
            while($row = $db->sql_fetchrow($result))
            {
                $rating   = get_post_meta($row['id'], 'rating');
                $htmlstar = '';
                for($i = 1; $i <= 5; $i++)
                {
                    $active    = ($i <= $rating)? ' class="active"' : '';
                    $htmlstar .= '<li'.$active.'><i class="fa fa-star"></i></li>';
                }
                $user_avatar = get_gravatar(get_user_column($row['post_author'], 'email'));
                $thumbnail = get_post_attachment($row['id'], '');
                $thumb = ($thumbnail != '')? $thumbnail : $user_avatar ;

                $template->assign_block_vars('loop_testimonials', array(
                    'POST_ID'           => $row['id'],
                    'POST_TITLE'        => get_post_title($row['post_title']),
                    'POST_THUMB'        => $thumb,
                    'POST_CONTENT'      => get_post_meta($row['id'], 'content'),
                    'POST_POSITION'     => get_post_meta($row['id'], 'position'),
                    'POST_RATING'       => $htmlstar,
                ));
            }

            $userid = get_session_userid();
            $fsql   = "SELECT * FROM ".POSTS_TABLE." JOIN ".POSTSMETA_TABLE." ON (`meta_key`='isform') AND (`post_id`=`id`) WHERE `meta_value`='1' and `post_author`='{$userid}' and `post_type`='testimonials'";
            $fresult    = $db->sql_query($fsql);
            $frow       = $db->sql_fetchrow($fresult);
            $frating    = get_post_meta($frow['id'], 'rating');
            $fhtmlstar  = '';
            for($i = 1; $i <= 5; $i++)
            {
                $active    = ($i <= $frating)? ' class="active"' : '';
                $fhtmlstar .= '<li'.$active.'><i class="fa fa-star"></i></li>';
            }
            $fuser_avatar = get_gravatar(get_user_column($frow['post_author'], 'email'));
            $fthumbnail = get_post_attachment($frow['id'], '');
            $fthumb = ($fthumbnail != '')? $fthumbnail : $fuser_avatar ;

            $template->assign_vars(array(
                'USER_IS_TESTIMONIAL_RATING'=> $db->sql_numrows($fsql),
                'USER_TESTIMONIAL_ID'       => $frow['id'],
                'USER_TESTIMONIAL_TITLE'    => get_post_title($frow['post_title']),
                'USER_TESTIMONIAL_THUMB'    => $fthumb,
                'USER_TESTIMONIAL_CONTENT'  => get_post_meta($frow['id'], 'content'),
                'USER_TESTIMONIAL_POSITION' => get_post_meta($frow['id'], 'position'),
                'USER_TESTIMONIAL_RATING'   => $fhtmlstar,
            ));
            //
        }
        // page theme templates
        function page_theme_templates($arg)
        {
            $templates = array(
                'testimonialsstyle1'   => 'testimonials/page_testimonials_style1.html',
                'testimonialsstyle2'   => 'testimonials/page_testimonials_style2.html',
                'testimonialsstyle3'   => 'testimonials/page_testimonials_style3.html',
                'testimonialform'      => 'testimonials/page_testimonials_form.html',
            );
            return array_merge( $arg, $templates) ;
        }
        // ajax send content
        function ajax_send_testimonial()
        {
            global $db, $lang;
            if(isset($_POST['action']) and $_POST['action'] == 'submittestimonial')
            {
                $username       = safe_input($_POST['username']);
                $email          = safe_input($_POST['email']);
                $rating         = safe_input($_POST['rating']);
                $message        = filter_content_strip_tags($_POST['message']);
                $username_error = (!isset($username) or !strlen($username))? '<li>'.$lang['username_field_is_required'].'</li>': false;
                $email_error    = (!isset($email) or !strlen($email))? '<li>'.$lang['email_field_is_required'].'</li>': false;
                if(!$email_error)
                {
                    $email_error = (!strlen(validate_email($email)))? '<li>'.$lang['please_write_the_email_correctly'].'</li>': false;
                }
                
                $rating_error      = (!isset($rating) or $rating == '0')? '<li>'.$lang['rating_is_required'].'</li>': false;
                $message_error      = (!isset($message) or !strlen($message))? '<li>'.$lang['message_field_is_required'].'</li>': false;
                if(!$username_error and !$email_error and !$emailvali_error and !$rating_error and !$message_error)
                {
                    $data = array(
                        'type'  => 'rating_us',
                        'username'  => $username,
                        'email'     => $email,
                        'title'     => $subject,
                        'rating'   => $rating,
                        'message'   => get_format_contact($message),
                        'send_to'   => array('site' => true),
                    );
                    //@mail_send($data);
                    $userid = get_session_userid();
                    $sql_ins = array(
                        'id'                => (int)'',
                        'post_author'       => (int)$userid,
                        'post_content'      => safe_textarea($message),
                        'post_title'        => safe_input($username),
                        'post_status'       => (int)'0',
                        'comment_status'    => (int)'0',
                        'post_name'         => safe_input(preg_slug($username)),
                        'post_modified'     => (int)time(),
                        'post_type'         => 'testimonials',
                        'term_id'           => (int)'0'
                    );
                    $sql     = 'INSERT INTO ' . POSTS_TABLE . ' ' . $db->sql_build_array('INSERT', $sql_ins, true);
                    $result  = $db->sql_query($sql);
                    $post_id = $db->sql_nextid();
                    $position   = ($userid)? 'User' : '';
                    update_post_meta($post_id, 'orders_post', '0');
                    update_post_meta($post_id, 'position', $position);
                    update_post_meta($post_id, 'rating', $rating);
                    update_post_meta($post_id, 'content', $message);
                    update_post_meta($post_id, 'isform', '1');
                    echo json_encode(array('status' => 'success', 'html' => $lang['thank_you_the_rating_was_sent_successfully']));
                    exit;
                }
                else
                {
                    $arr = array(
                        'status'    => 'error', 
                        'html'      => '<h3 class="bordered"><span>'.$lang['whoops_you_missed_something'].'</span></h3>
                        <ul>'.$username_error.$email_error.$subject_error.$message_error.'</ul>
                        <p><a href="#" class="cancel button">'.$lang['okay'].'</a></p>
                        <a class="close" href="#">'.$lang['closed'].'</a>'
                    );
                    echo json_encode($arr);
                    exit;
                }
            }
        }
    }
    /* End class */
    /* display class */
    new extensions_testimonials();
}
?>
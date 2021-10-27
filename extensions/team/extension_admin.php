<?php
if (!defined("IN_PHPMEGATEMP"))
      die('Direct access to this location is not allowed.');

if(!class_exists('extensions_admin_team'))
{
    class extensions_admin_team
    {
        // class constructor
        function __construct()
        {
            
        }
        // display admin team
        function display_admin_team()
        {
            global $hooks;
            // action admin global
            $hooks->add_action('admin_menu_dropdown_team', array($this , 'display_menu_dropdown_team'), 40);
            $hooks->add_action('admin_sidebar_menu', array($this , 'display_sidebar_menu'), 40);
            // extensions options
            $hooks->add_filter('add_admin_extensions_tabs', array($this , 'add_admin_extensions_tabs'), 40);
            $hooks->add_filter('add_admin_extensions_display', array($this , 'add_admin_extensions_display'), 40);
            $hooks->add_action('add_admin_extensions_options_update', array($this , 'extensions_options_update'), 40);
            // set section home themeoption
            $hooks->add_filter('add_admin_themeoption_home_sections', array($this , 'add_admin_themeoption_home_sections'), 40);
            // add filter admin post team
            $hooks->add_filter('admin_display_supports_post_th_team', array($this , 'display_supports_post_th'), 1);
            $hooks->add_filter('admin_display_supports_post_td_team', array($this , 'display_supports_post_td'), 1);
            // add action admin post team
            $hooks->add_action('admin_display_body_content_post_team', array($this , 'display_body_content_post'), 1);
            $hooks->add_action('admin_display_add_content_post_team', array($this , 'display_body_form_add_content_post'), 1);
            $hooks->add_action('admin_display_edit_content_post_team', array($this , 'display_body_form_edit_content_post'), 1);
            $hooks->add_action('admin_display_update_post_meta_team', array($this , 'display_update_post_meta_team'), 1);
            $hooks->add_action('admin_display_post_supports_box_lg_team', array($this , 'display_post_supports_box_lg'), 1);
            // user permissions
            $hooks->add_filter('admin_display_user_permissions', array($this , 'display_user_permissions'), 15);
            // add template
            $hooks->add_filter('add_admin_page_templates', array($this , 'add_admin_page_templates'), 40);
        }
        // display menu dropdown team
        function display_menu_dropdown_team()
        {
            global $db, $config, $template, $hooks;
            echo '<li><a href="posts.php?post_type=team&mode=team">'.get_admin_languages('team').'</a></li>';
        }
        // display sidebar menu
        function display_sidebar_menu()
        {
            if(is_user_permissions('team', false))
            {
                admin_sidebar_menu_register(array(
                    'class' => get_class_menu_post_type('team'),
                    'url'   => '#', 
                    'title' => get_admin_languages('team'), 
                    'icon'  => 'fas fa-user-friends' , 
                    'sub'   => array(
                            array('url' => 'posts.php?post_type=team',          'title' => get_admin_languages('team')),
                            array('url' => 'posts.php?post_type=team&mode=new', 'title' => get_admin_languages('add_new')),
                        )
                    )
                );
            }
        }
        // display extensions tabs settings
        function add_admin_page_templates($arg)
        {
            $templates = array(
                'teamstyle1'   => 'Team Style 1',
                'teamstyle2'   => 'Team Style 2',
                'teamstyle3'   => 'Team Style 3',
            );
            return array_merge( $arg, $templates) ;
        }
        // display extensions tabs settings
        function add_admin_extensions_tabs($arg)
        {
            $menu['team'] = array('icon' => 'fas fa-user-friends', 'id' => 'team', 'title' => get_admin_languages('team'));
            return array_merge( $arg, $menu) ;
        }
        // display page setting
        function add_admin_extensions_display($arg)
        {
            global $config;
            $options        = maybe_unserialize($config['team_options']);
            $title          = (isset($options['title']))? $options['title'] : '';
            $description    = (isset($options['description']))? $options['description'] : '';
            $section_home   = (isset($options['section_home']))? $options['section_home'] : 0;
            $home_post_per_page = (isset($options['home_post_per_page']))? $options['home_post_per_page'] : 0;
            $home_class     = (isset($options['home_class']))? $options['home_class'] : 'white_bg';
            $style          = (isset($options['style']))? $options['style'] : '1';
            // setting section home
            $settings['team']['section_general'] = array(
                'title'     => get_admin_languages('general'),
                'options'   => array(
                    array('type' => 'checkbox', 'id' => 'team[section_home]', 'name' => get_admin_languages('section_home'), 'value' => $section_home),
                    array('type' => 'radio', 'id' => 'team[home_class]', 'name' => 'home_class', 'value' => $home_class, 'options' => array(
                        'white_bg'  => 'White',
                        'gray_bg'   => 'Gray',
                        'black_bg'  => 'Black',
                        'bg_color'  => 'Color',
                    )),
                    array('type' => 'text', 'id' => 'team[title]', 'name' => get_admin_languages('title'), 'value' => $title),
                    array('type' => 'text', 'id' => 'team[description]', 'name' => get_admin_languages('description'), 'value' => $description),
                    array('type' => 'slider_number', 'id' => 'team[home_post_per_page]', 'name' => get_admin_languages('home_per_page'), 'value' => $home_post_per_page, 'min' => '1', 'max' => '32', 'step' => '1'),
                    array('name' => 'Style', 'id' => 'team[style]', 'type' => 'radio', 'value' => $style, 'options' => array(
                        '1' => array('label' => 'Style 1', 'img' => get_url_extensions('team').'/style1.png'),
                        '2' => array('label' => 'Style 2', 'img' => get_url_extensions('team').'/style2.png'),
                        '3' => array('label' => 'Style 3', 'img' => get_url_extensions('team').'/style3.png'),
                    ))
                )
            );
            return array_merge( $arg, $settings) ;
        }
        // extensions options update
        function extensions_options_update()
        {
            set_config('team_options', maybe_serialize($_POST['team']));
        }
        // add admin themeoption home
        function add_admin_themeoption_home_sections($arg)
        {
            global $config;
            $options           = maybe_unserialize($config['team_options']);
            $section_home      = (isset($options['section_home']) and $options['section_home'])? 'on' : 'off';
            $menu['team'] = array('title' => 'team', 'template' => 'team/home_block_team.html', 'status' => $section_home);
            return array_merge( $arg, $menu) ;
        }
        // display user permissions
        function display_user_permissions($arg)
        {
            $perm['team'] = get_admin_languages('team');
            return array_unique( array_merge( $perm, (array) $arg ) );
        }
        // display supports th
        function display_supports_post_th($arg)
        {
            $th['featured_image']   = '<th style="width: 50px;text-align: center;" class="hidden-phone"></th>';
            $th['order'] = '<th style="width: 90px;text-align: center;" class="hidden-phone">'.get_admin_languages('order').'</th>';
            return array_unique( array_merge( $th, (array) $arg ) );
        }
        // display supports td
        function display_supports_post_td($arg)
        {
            $orders             = get_post_meta($arg['row']['id'], 'orders_post', '0');
			$thumbnail = get_post_attachment($arg['row']['id'], '');
            $thumb = ($thumbnail != '')? '<div class="post_featured_image"><img src="'.$thumbnail.'" /></div>' : '' ;
            $td['featured_image'] = '<td style="text-align: center;" class="hidden-phone">'.$thumb.'</td>';
            $td['order'] = '<td style="width: 90px;text-align: center;" class="hidden-phone">'.$orders.'</td>';
            return array_merge( $td, (array) $arg['td'] );
        }
        // display body content post
        function display_body_content_post()
        {
            global $db, $admin_posts;
            $result     = $db->sql_query("SELECT * FROM ".POSTS_TABLE." WHERE `post_type`='team' ORDER BY post_modified DESC");
            admin_header(get_admin_languages('team'));
            $admin_posts->page_post_start(array(
                'labels' => array(
                    'name'   => get_admin_languages('team'), 
                    'button' => array(
                        'title' => get_admin_languages('add_new'), 
                        'url'   => 'posts.php?post_type=team&mode=new'
                    )
                ),
                'post_type'         => 'team',
                'select_actions'    => array('activs', 'disactivs', 'delete'),
                'supports'          => array('checkbox',  'featured_image', 'title', 'author', 'order', 'date'),
                'result'            => $result,
                'js_datatable'      => '{ "orderable": false }, null, null, null, null',
            ));
            admin_footer();
        }
        // form addnew post
        function display_body_form_add_content_post()
        {
            global $db, $admin_posts;
            admin_header(get_admin_languages('team') .'('. get_admin_languages('add_new').')');
            $row['orders'] = $db->sql_numrows("SELECT * FROM ".POSTS_TABLE." WHERE `post_type`='team'") + 1;
            $admin_posts->admin_form_post_html(array(
                'labels'            => array('name' => get_admin_languages('team'), 'add_new_item' => get_admin_languages('add_new')),
                'post_type'         => 'team',
                'query'             => 'addnew',
                'data'              => $row,
                'term_type'         => 'team',
                'supports'          => array('title', 'thumbnail', 'orders', 'team'),
                'screen_columns'    => '2',
                'permalink'         => false
            ));
            admin_footer(); 
        }
        // form edit post
        function display_body_form_edit_content_post()
        {
            global $db, $config, $admin_posts;
            $post_id = (int) safe_input(intval($_GET['id'])) ;
            admin_header(get_admin_languages('team') .'('. get_admin_languages('edit').')');
            $result = $db->sql_query("SELECT * FROM ".POSTS_TABLE." WHERE `post_type`='team' AND `id`='{$post_id}'");
            $row    = $db->sql_fetchrow($result);
            $row['orders'] = get_post_meta($post_id, 'orders_post', '0');
            $admin_posts->admin_form_post_html(array(
                'labels'            => array('name' => get_admin_languages('team'), 'add_new_item' => get_admin_languages('edit')),
                'post_type'         => 'team',
                'query'             => 'update',
                'data'              => $row,
                'term_type'         => 'team',
                'supports'          => array('title', 'thumbnail', 'orders', 'team'),
                'screen_columns'    => '2',
                'permalink'         => false
            ));
            admin_footer(); 
        }
         // supports box
        function display_post_supports_box_lg($arg)
        {
            if(in_array('team', $arg['supports']))
            {

                $job        = (isset($arg['data']['id']))? get_post_meta($arg['data']['id'], 'job') : '';
                $facebook   = (isset($arg['data']['id']))? get_post_meta($arg['data']['id'], 'facebook') : '';
                $twitter    = (isset($arg['data']['id']))? get_post_meta($arg['data']['id'], 'twitter') : '';
                $instagram  = (isset($arg['data']['id']))? get_post_meta($arg['data']['id'], 'instagram') : '';
                
                $html = '
                <div class="megapanel-tab-content">
                    <div class="form-group">
                        <label class="control-label">'.get_admin_languages('job').'</label>
                        <input type="text" name="job" value="'.$job.'" class="form-control text-left" dir="ltr">
                    </div>
                    <div class="form-group">
                        <label class="control-label">Facebook</label>
                        <input type="text" name="facebook" value="'.$facebook.'" class="form-control text-left" dir="ltr">
                    </div>
                    <div class="form-group">
                        <label class="control-label">Twitter</label>
                        <input type="text" name="twitter" value="'.$twitter.'" class="form-control text-left" dir="ltr">
                    </div>
                    <div class="form-group">
                        <label class="control-label">Instagram</label>
                        <input type="text" name="instagram" value="'.$instagram.'" class="form-control text-left" dir="ltr">
                    </div>
                </div>
                ';
                
                echo post_boxes_content(array('title' => get_admin_languages('Information'), 'html' => $html));
            }
        }
        // action update meta post
        function display_update_post_meta_team($arg)
        {
            $order      = (isset($arg['post']['orders']))? $arg['post']['orders'] : 0;
            $job        = (isset($arg['post']['job']))? $arg['post']['job'] : '';
            $facebook   = (isset($arg['post']['facebook']))? $arg['post']['facebook'] : '';
            $twitter    = (isset($arg['post']['twitter']))? $arg['post']['twitter'] : '';
            $instagram  = (isset($arg['post']['instagram']))? $arg['post']['instagram'] : '';
            update_post_meta($arg['post_id'], 'orders_post', $order);
            update_post_meta($arg['post_id'], 'job', $job);
            update_post_meta($arg['post_id'], 'facebook', $facebook);
            update_post_meta($arg['post_id'], 'twitter', $twitter);
            update_post_meta($arg['post_id'], 'instagram', $instagram);
        }
    }
}
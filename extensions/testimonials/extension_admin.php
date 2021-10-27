<?php
if (!defined("IN_PHPMEGATEMP"))
      die('Direct access to this location is not allowed.');

if(!class_exists('extensions_admin_testimonials'))
{
    class extensions_admin_testimonials
    {
        // class constructor
        function __construct()
        {
            
        }
        // display admin testimonials
        function display_admin_testimonials()
        {
            global $hooks;
            // action admin global
            $hooks->add_action('admin_menu_dropdown_testimonials', array($this , 'display_menu_dropdown_testimonials'), 40);
            $hooks->add_action('admin_sidebar_menu', array($this , 'display_sidebar_menu'), 40);
            // extensions options
            $hooks->add_filter('add_admin_extensions_tabs', array($this , 'add_admin_extensions_tabs'), 40);
            $hooks->add_filter('add_admin_extensions_display', array($this , 'add_admin_extensions_display'), 40);
            $hooks->add_action('add_admin_extensions_options_update', array($this , 'extensions_options_update'), 40);
            // set section home themeoption
            $hooks->add_filter('add_admin_themeoption_home_sections', array($this , 'add_admin_themeoption_home_sections'), 40);
            // add filter admin post testimonials
            $hooks->add_filter('admin_display_supports_post_th_testimonials', array($this , 'display_supports_post_th'), 1);
            $hooks->add_filter('admin_display_supports_post_td_testimonials', array($this , 'display_supports_post_td'), 1);
            // add action admin post testimonials
            $hooks->add_action('admin_display_body_content_post_testimonials', array($this , 'display_body_content_post'), 1);
            $hooks->add_action('admin_display_add_content_post_testimonials', array($this , 'display_body_form_add_content_post'), 1);
            $hooks->add_action('admin_display_edit_content_post_testimonials', array($this , 'display_body_form_edit_content_post'), 1);
            $hooks->add_action('admin_display_update_post_meta_testimonials', array($this , 'display_update_post_meta_testimonials'), 1);
            $hooks->add_action('admin_display_post_supports_box_lg_testimonials', array($this , 'display_post_supports_box_lg'), 1);
            // user permissions
            $hooks->add_filter('admin_display_user_permissions', array($this , 'display_user_permissions'), 15);
            // add template
            $hooks->add_filter('add_admin_page_templates', array($this , 'add_admin_page_templates'), 40);
        }
        // display menu dropdown testimonials
        function display_menu_dropdown_testimonials()
        {
            global $db, $config, $template, $hooks;
            echo '<li><a href="posts.php?post_type=testimonials&mode=testimonials">'.get_admin_languages('testimonials').'</a></li>';
        }
        // display sidebar menu
        function display_sidebar_menu()
        {
            if(is_user_permissions('testimonials', false))
            {
                global $db;
                $sql   = "SELECT * FROM ".POSTS_TABLE." WHERE `post_status`='0' and `post_type`='testimonials'";
                $total = $db->sql_numrows($sql);
                $label = ($total)? '<small class="label pull-right bg-red">'.$total.'</small>' : '';
                admin_sidebar_menu_register(array(
                    'class' => get_class_menu_post_type('testimonials'),
                    'url'   => '#', 
                    'title' => get_admin_languages('testimonials') . $label, 
                    'icon'  => 'fas fa-comment-dots' , 
                    'sub'   => array(
                            array('url' => 'posts.php?post_type=testimonials',          'title' => get_admin_languages('testimonials').$label),
                            array('url' => 'posts.php?post_type=testimonials&mode=new', 'title' => get_admin_languages('add_new')),
                        )
                    )
                );
            }
        }
        // display extensions tabs settings
        function add_admin_page_templates($arg)
        {
            $templates = array(
                'testimonialsstyle1'   => 'Testimonials Style 1',
                'testimonialsstyle2'   => 'Testimonials Style 2',
                'testimonialform'      => 'Testimonials Form',
            );
            return array_merge( $arg, $templates) ;
        }
        // display extensions tabs settings
        function add_admin_extensions_tabs($arg)
        {
            $menu['testimonials'] = array('icon' => 'fas fa-comment-dots', 'id' => 'testimonials', 'title' => get_admin_languages('testimonials'));
            return array_merge( $arg, $menu) ;
        }
        // display page setting
        function add_admin_extensions_display($arg)
        {
            global $config;
            $options        = maybe_unserialize($config['testimonials_options']);
            $title          = (isset($options['title']))? $options['title'] : '';
            $description    = (isset($options['description']))? $options['description'] : '';
            $section_home   = (isset($options['section_home']))? $options['section_home'] : 0;
            $home_post_per_page = (isset($options['home_post_per_page']))? $options['home_post_per_page'] : 0;
            $home_class     = (isset($options['home_class']))? $options['home_class'] : 'white_bg';
            $style          = (isset($options['style']))? $options['style'] : '1';
            // setting section home
            $settings['testimonials']['section_general'] = array(
                'title'     => get_admin_languages('general'),
                'options'   => array(
                    array('type' => 'checkbox', 'id' => 'testimonials[section_home]', 'name' => get_admin_languages('section_home'), 'value' => $section_home),
                    array('type' => 'radio', 'id' => 'testimonials[home_class]', 'name' => 'home_class', 'value' => $home_class, 'options' => array(
                        'white_bg'  => 'White',
                        'gray_bg'   => 'Gray',
                        'black_bg'  => 'Black',
                        'bg_color'  => 'Color',
                    )),
                    array('type' => 'text', 'id' => 'testimonials[title]', 'name' => get_admin_languages('title'), 'value' => $title),
                    array('type' => 'text', 'id' => 'testimonials[description]', 'name' => get_admin_languages('description'), 'value' => $description),
                    array('type' => 'slider_number', 'id' => 'testimonials[home_post_per_page]', 'name' => get_admin_languages('home_per_page'), 'value' => $home_post_per_page, 'min' => '1', 'max' => '32', 'step' => '1'),
                    array('name' => 'Style', 'id' => 'testimonials[style]', 'type' => 'radio', 'value' => $style, 'options' => array(
                        '1' => array('label' => 'Style 1', 'img' => get_url_extensions('testimonials').'/style1.png'),
                        '2' => array('label' => 'Style 2', 'img' => get_url_extensions('testimonials').'/style2.png'),
                    ))
                )
            );
            return array_merge( $arg, $settings) ;
        }
        // extensions options update
        function extensions_options_update()
        {
            set_config('testimonials_options', maybe_serialize($_POST['testimonials']));
        }
        // add admin themeoption home
        function add_admin_themeoption_home_sections($arg)
        {
            global $config;
            $options           = maybe_unserialize($config['testimonials_options']);
            $section_home      = (isset($options['section_home']) and $options['section_home'])? 'on' : 'off';
            $menu['testimonials'] = array('title' => 'testimonials', 'template' => 'testimonials/home_block_testimonials.html', 'status' => $section_home);
            return array_merge( $arg, $menu) ;
        }
        // display user permissions
        function display_user_permissions($arg)
        {
            $perm['testimonials'] = get_admin_languages('testimonials');
            return array_unique( array_merge( $perm, (array) $arg ) );
        }
        // display supports th
        function display_supports_post_th($arg)
        {
            $th['featured_image']   = '<th style="width: 50px;text-align: center;" class="hidden-phone"></th>';
            $th['isform'] = '<th style="width: 90px;text-align: center;" class="hidden-phone">'.get_admin_languages('submit').'</th>';
            $th['rating'] = '<th style="width: 90px;text-align: center;" class="hidden-phone">'.get_admin_languages('rating').'</th>';
            $th['order'] = '<th style="width: 90px;text-align: center;" class="hidden-phone">'.get_admin_languages('order').'</th>';
            return array_unique( array_merge( $th, (array) $arg ) );
        }
        // display supports td
        function display_supports_post_td($arg)
        {
            $isform    = get_post_meta($arg['row']['id'], 'isform', '0');
            $isform_txt = ($isform)? get_admin_languages('yes') : get_admin_languages('no');
            $rating    = get_post_meta($arg['row']['id'], 'rating', '0');
            $rating_star = '';
            for($i = 1; $i <= $rating; $i++)
            {
                $rating_star .= '<i class="fas fa-star"></i>';
            }
            $orders    = get_post_meta($arg['row']['id'], 'orders_post', '0');
            $user_avatar = get_gravatar(get_user_column($arg['row']['post_author'], 'email'));
            $thumbnail = get_post_attachment($arg['row']['id'], '');
            $thumb = ($thumbnail != '')? $thumbnail : $user_avatar ;


            $td['featured_image'] = '<td style="text-align: center;" class="hidden-phone"><div class="post_featured_image"><img src="'.$thumb.'" /></div></td>';
            $td['isform'] = '<td style="width: 40px;text-align: center;" class="hidden-phone">'.$isform_txt.'</td>';
            $td['rating'] = '<td style="width: 90px;text-align: center;" class="hidden-phone"><span style="color: #FFB401;font-size: 12px;">'.$rating_star.'</span></td>';
            $td['order'] = '<td style="width: 90px;text-align: center;" class="hidden-phone">'.$orders.'</td>';
            return array_merge( $td, (array) $arg['td'] );
        }
        // display body content post
        function display_body_content_post()
        {
            global $db, $admin_posts;
            $result     = $db->sql_query("SELECT * FROM ".POSTS_TABLE." WHERE `post_type`='testimonials' ORDER BY post_modified DESC");
            admin_header(get_admin_languages('testimonials'));
            $admin_posts->page_post_start(array(
                'labels' => array(
                    'name'   => get_admin_languages('testimonials'), 
                    'button' => array(
                        'title' => get_admin_languages('add_new'), 
                        'url'   => 'posts.php?post_type=testimonials&mode=new'
                    )
                ),
                'post_type'         => 'testimonials',
                'select_actions'    => array('activs', 'disactivs', 'delete'),
                'supports'          => array('checkbox',  'featured_image', 'title', 'author', 'rating', 'isform', 'order', 'date'),
                'result'            => $result,
                'js_datatable'      => '{ "orderable": false }, null, null, null, null, null, null',
            ));
            admin_footer();
        }
        // form addnew post
        function display_body_form_add_content_post()
        {
            global $db, $admin_posts;
            admin_header(get_admin_languages('testimonials') .'('. get_admin_languages('add_new').')');
            $row['orders'] = $db->sql_numrows("SELECT * FROM ".POSTS_TABLE." WHERE `post_type`='testimonials'") + 1;
            $admin_posts->admin_form_post_html(array(
                'labels'            => array('name' => get_admin_languages('testimonials'), 'add_new_item' => get_admin_languages('add_new')),
                'post_type'         => 'testimonials',
                'query'             => 'addnew',
                'data'              => $row,
                'term_type'         => 'testimonials',
                'supports'          => array('title', 'thumbnail', 'orders', 'testimonials'),
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
            admin_header(get_admin_languages('testimonials') .'('. get_admin_languages('edit').')');
            $result = $db->sql_query("SELECT * FROM ".POSTS_TABLE." WHERE `post_type`='testimonials' AND `id`='{$post_id}'");
            $row    = $db->sql_fetchrow($result);
            $row['orders'] = get_post_meta($post_id, 'orders_post', '0');
            $admin_posts->admin_form_post_html(array(
                'labels'            => array('name' => get_admin_languages('testimonials'), 'add_new_item' => get_admin_languages('edit')),
                'post_type'         => 'testimonials',
                'query'             => 'update',
                'data'              => $row,
                'term_type'         => 'testimonials',
                'supports'          => array('title', 'thumbnail', 'orders', 'testimonials'),
                'screen_columns'    => '2',
                'permalink'         => false
            ));
            admin_footer(); 
        }
         // supports box
        function display_post_supports_box_lg($arg)
        {
            if(in_array('testimonials', $arg['supports']))
            {
                $position   = (isset($arg['data']['id']))? get_post_meta($arg['data']['id'], 'position') : '';
                $rating     = (isset($arg['data']['id']))? get_post_meta($arg['data']['id'], 'rating') : '5';
                $content    = (isset($arg['data']['id']))? get_post_meta($arg['data']['id'], 'content') : '';
                $html = '
                <div class="megapanel-tab-content">
                    <div class="form-group">
                        <label class="control-label">content</label>
                        <textarea name="content" class="form-control" rows="6">'.$content.'</textarea>
                    </div>
                    <div class="form-group">
                        <label class="control-label">'.get_admin_languages('position').'</label>
                        <input type="text" name="position" value="'.$position.'" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="control-label">rating</label>
                        <div class="megapanel-buttons-options">';  
                        $star = '';
                        for($i = 1; $i <= 5; $i++)
                        {
                            $star .= '<i class="fas fa-star"></i>';
                            $active = ($i == $rating)? 'active' : '';
                            $html .= '
                            <button type="button" data-value="'.$i.'" class="option-on '.$active.'" style="font-size: 12px;">'.$star.'</button>
                            ';
                        }
                $html .= '<input type="hidden" name="rating" value="'.$rating.'">
                        </div>
                    </div>
                </div>
                ';
                echo post_boxes_content(array('title' => get_admin_languages('Information'), 'html' => $html));
            }
        }
        // action update meta post
        function display_update_post_meta_testimonials($arg)
        {
            $order      = (isset($arg['post']['orders']))? $arg['post']['orders'] : 0;
            $position   = (isset($arg['post']['position']))? $arg['post']['position'] : '';
            $rating     = (isset($arg['post']['rating']))? $arg['post']['rating'] : '';
            $content    = (isset($arg['post']['content']))? $arg['post']['content'] : '';
            update_post_meta($arg['post_id'], 'orders_post', $order);
            update_post_meta($arg['post_id'], 'position', $position);
            update_post_meta($arg['post_id'], 'rating', $rating);
            update_post_meta($arg['post_id'], 'content', $content);
        }
    }
}
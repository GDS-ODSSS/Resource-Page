<?php
if (!defined("IN_PHPMEGATEMP"))
      die('Direct access to this location is not allowed.');

if(!class_exists('extensions_admin_faqs'))
{
    // Start Class
    class extensions_admin_faqs
    {
        // class constructor
        function __construct()
        {
            
        }
        // display admin faqs
        function display_admin_faqs()
        {
            global $hooks;
            // action admin global
            $hooks->add_action('admin_menu_dropdown_new', array($this , 'display_menu_dropdown_new'), 3);
            $hooks->add_action('admin_sidebar_menu', array($this , 'display_sidebar_menu'), 20);
            // extensions options
            $hooks->add_filter('add_admin_extensions_tabs', array($this , 'add_admin_extensions_tabs'), 40);
            $hooks->add_filter('add_admin_extensions_display', array($this , 'add_admin_extensions_display'), 40);
            $hooks->add_action('add_admin_extensions_options_update', array($this , 'extensions_options_update'), 40);
            // set section home themeoption
            $hooks->add_filter('add_admin_themeoption_home_sections', array($this , 'add_admin_themeoption_home_sections'), 40);
            $hooks->add_filter('add_admin_themeoption_home_boxs', array($this , 'add_admin_themeoption_home_boxs'), 40);
            // dashboard stat boxes
            $hooks->add_action('admin_dashboard_stat_boxes', array($this , 'display_dashboard_stat_boxes'), 3);
            // add filter admin post faqs
            $hooks->add_filter('admin_display_supports_post_th_faqs', array($this , 'display_supports_post_th'), 1);
            $hooks->add_filter('admin_display_supports_post_td_faqs', array($this , 'display_supports_post_td'), 1);
            // add action admin post faqs
            $hooks->add_action('admin_display_body_content_post_faqs', array($this , 'display_body_content_post'), 1);
            $hooks->add_action('admin_display_add_content_post_faqs', array($this , 'display_body_form_add_content_post'), 1);
            $hooks->add_action('admin_display_edit_content_post_faqs', array($this , 'display_body_form_edit_content_post'), 1);
            $hooks->add_action('admin_display_update_post_meta_faqs', array($this , 'display_update_post_meta_faqs'), 1);
            // action admin terms faqs
            $hooks->add_action('admin_display_body_content_terms_faqs', array($this , 'display_body_content_terms_faqs'), 1);
            // user permissions
            $hooks->add_filter('admin_display_user_permissions', array($this , 'display_user_permissions'), 20);
            $hooks->add_action('admin_nav_menu_side_control_extension', array($this , 'display_admin_nav_menu_side'), 1);
        }
        // add to side nav menu
        function display_admin_nav_menu_side()
        {
            $html = '
            <li>
                <label class="menu-item-title">
                <input type="checkbox" class="menu-item-checkbox" name="menu-item" data-url="faqs" data-title="Faqs" data-icon="far fa-question-circle"> 
                <span class="lbl">'.get_admin_languages('faqs').'</span> 
                </label>
            </li>
            ';
            echo $html;
        }
        // display menu dropdown new
        function display_menu_dropdown_new()
        {
            if(is_user_permissions('faqs', false))
            {
                echo '<li><a href="posts.php?post_type=faqs&mode=new">'.get_admin_languages('faqs').'</a></li>';
            }
        }
        // display sidebar menu
        function display_sidebar_menu()
        {
            if(is_user_permissions('faqs', false))
            {
                admin_sidebar_menu_register(array(
                    'class' => get_class_menu_post_type('faqs'),
                    'url'   => '#', 
                    'title' => get_admin_languages('faqs'), 
                    'icon'  => 'far fa-question-circle' , 
                    'sub'   => array(
                            array('url' => 'posts.php?post_type=faqs',          'title' => get_admin_languages('faqs')),
                            array('url' => 'posts.php?post_type=faqs&mode=new', 'title' => get_admin_languages('add_new')),
                            array('url' => 'categories.php?taxonomy=faqs',      'title' => get_admin_languages('categories'))
                        )
                    )
                );
            }
        }
        // display extensions tabs settings
        function add_admin_extensions_tabs($arg)
        {
            $menu['faqs'] = array('icon' => 'far fa-question-circle', 'id' => 'faqs', 'title' => get_admin_languages('faqs'));
            return array_merge( $arg, $menu) ;
        }        
        // display page setting
        function add_admin_extensions_display($arg)
        {
            global $config;
            $options = maybe_unserialize($config['faqs_options']);
            $box_home           = (isset($options['box_home']))? $options['box_home'] : 0;
            $column                     = (isset($options['column']))? $options['column'] : '';
            $classes                    = (isset($options['classes']))? $options['classes'] : '';
            $title                      = (isset($options['title']))? $options['title'] : '';
            $icon                       = (isset($options['icon']))? $options['icon'] : '';
            $description                = (isset($options['description']))? $options['description'] : '';
            $text_link                  = (isset($options['text_link']))? $options['text_link'] : '';
            $section_home               = (isset($options['section_home']))? $options['section_home'] : 0;
            $home_post_per_page         = (isset($options['home_post_per_page']))? $options['home_post_per_page'] : 0;
            $home_class                 = (isset($options['home_class']))? $options['home_class'] : 'white_bg';
            $sidebar                    = (isset($options['sidebar']))? $options['sidebar'] : 0;
            $post_per_page              = (isset($options['post_per_page']))? $options['post_per_page'] : 3;
            $image                      = (isset($options['image']))? $options['image'] : '';
            // setting section home
            $settings['faqs']['section_general'] = array(
                'title'     => get_admin_languages('general'),
                'options'   => array(
                    array('type' => 'checkbox', 'id' => 'faqs[sidebar]', 'name' => get_admin_languages('sidebar'), 'value' => $sidebar),
                    array('type' => 'slider_number', 'id' => 'faqs[post_per_page]', 'name' => get_admin_languages('post_per_page'), 'value' => $post_per_page, 'min' => '1', 'max' => '32', 'step' => '1'),
                )
            );
            // setting section home
            $settings['faqs']['section_home'] = array(
                'title'     => get_admin_languages('section_home'),
                'options'   => array(
                    array('type' => 'checkbox', 'id' => 'faqs[box_home]', 'name' => get_admin_languages('box_home'), 'value' => $box_home),
                    array('type' => 'radio', 'id' => 'faqs[column]', 'name' => 'column', 'value' => $column, 'options' => array(
                        'col-md-2' => 'col-md-2',
                        'col-md-3' => 'col-md-3',
                        'col-md-4' => 'col-md-4',
                        'col-md-5' => 'col-md-6',
                    )),
                    array('type' => 'text', 'id' => 'faqs[classes]', 'name' => 'classes', 'value' => $classes),
                    array('type' => 'text', 'id' => 'faqs[title]', 'name' => get_admin_languages('title'), 'value' => $title),
                    array('type' => 'fonticon', 'id' => 'faqs[icon]', 'name' => get_admin_languages('icon'), 'value' => $icon),
                    array('type' => 'upload', 'id' => 'faqs[image]', 'name' => get_admin_languages('image'), 'value' => $image, 'src' => 'src'),
                    array('type' => 'text', 'id' => 'faqs[description]', 'name' => get_admin_languages('description'), 'value' => $description),
                    array('type' => 'text', 'id' => 'faqs[text_link]', 'name' => get_admin_languages('text_link'), 'value' => $text_link),
                    array('type' => 'checkbox', 'id' => 'faqs[section_home]', 'name' => get_admin_languages('section_home'), 'value' => $section_home),
                    array('type' => 'radio', 'id' => 'faqs[home_class]', 'name' => 'home_class', 'value' => $home_class, 'options' => array(
                        'white_bg'  => 'White',
                        'gray_bg'   => 'Gray',
                        'black_bg'  => 'Black',
                        'bg_color'  => 'Color',
                    )),
                    array('type' => 'slider_number', 'id' => 'faqs[home_post_per_page]', 'name' => get_admin_languages('post_per_page'), 'value' => $home_post_per_page, 'min' => '1', 'max' => '32', 'step' => '1'),
                )
            );
            

            return array_merge( $arg, $settings) ;
        }
        // extensions options update
        function extensions_options_update()
        {
            set_config('faqs_options', maybe_serialize($_POST['faqs']));
        }
        // add admin themeoption home
        function add_admin_themeoption_home_sections($arg)
        {
            global $config;
            $options      = maybe_unserialize($config['faqs_options']);
            $section_home = (isset($options['section_home']) and $options['section_home'])? 'on' : 'off';
            $menu['faqs'] = array('title' => get_admin_languages('faqs'), 'template' => 'faqs/home_block_faqs.html', 'status' => $section_home);
            return array_merge( $arg, $menu) ;
        }
        // add admin themeoption home
        function add_admin_themeoption_home_boxs($arg)
        {
            global $config;
            $options      = maybe_unserialize($config['faqs_options']);
            $box_home = (isset($options['box_home']) and $options['box_home'])? 'on' : 'off';
            $menu['faqs'] = array('title' => get_admin_languages('faqs'), 'template' => 'faqs/home_box_faqs.html', 'status' => $box_home);
            return array_merge( $arg, $menu) ;
        }
        
        // display sidebar menu
        function display_dashboard_stat_boxes()
        {
            $arg = array(
                'icon'          => 'far fa-question-circle',
                'title'         => get_admin_languages('faqs'),
                'color'         => '1',
                'number_pre'    => get_post_pre('faqs'),
                'number_count'  => get_count_posts("'faqs'", false),
            );
            display_dashboard_info_box($arg);
        }
        // display user permissions
        function display_user_permissions($arg)
        {
            $perm['faqs'] = get_admin_languages('faqs');
            return array_unique( array_merge( $perm, (array) $arg ) );
        }
        // display supports th
        function display_supports_post_th($arg)
        {
            $th['order'] = '<th style="width: 90px;text-align: center;" class="hidden-phone">'.get_admin_languages('order').'</th>';
            return array_unique( array_merge( $th, (array) $arg ) );
        }
        // display supports td
        function display_supports_post_td($arg)
        {
            $orders = get_post_meta($arg['row']['id'], 'orders_post', 'x');
            $td['order'] = '<td style="width: 90px;text-align: center;" class="hidden-phone">'.$orders.'</td>';
            return array_merge( $td, (array) $arg['td'] );
        }
        // display body content post
        function display_body_content_post()
        {
            global $db, $admin_posts;
            $result     = $db->sql_query("SELECT * FROM ".POSTS_TABLE." WHERE `post_type`='faqs' ORDER BY post_modified DESC");
            admin_header(get_admin_languages('faqs'));
            $admin_posts->page_post_start(array(
                'labels' => array(
                    'name'   => get_admin_languages('faqs'), 
                    'button' => array(
                        'title' => get_admin_languages('add_new'), 
                        'url'   => 'posts.php?post_type=faqs&mode=new'
                    )
                ),
                'post_type'         => 'faqs',
                'select_actions'    => array('activs', 'disactivs', 'delete'),
                'supports'          => array('checkbox', 'title', 'order', 'author', 'categories', 'date'),
                'result'            => $result,
                'js_datatable'      => 'null, null, null, null, null',
            ));
            admin_footer();
        }
        // form addnew post
        function display_body_form_add_content_post()
        {
            global $db, $admin_posts;
            admin_header(get_admin_languages('faqs') .'('. get_admin_languages('add_new').')');
            $row['orders'] = $db->sql_numrows("SELECT * FROM ".POSTS_TABLE." WHERE `post_type`='faqs'") + 1;
            $admin_posts->admin_form_post_html(array(
                'labels'            => array('name' => get_admin_languages('faqs'), 'add_new_item' => get_admin_languages('add_new')),
                'post_type'         => 'faqs',
                'query'             => 'addnew',
                'data'              => $row,
                'term_type'         => 'faqs',
                'supports'          => array('title', 'editor', 'category', 'orders'),
                'screen_columns'    => '2'
            ));
            admin_footer(); 
        }
        // form edit post
        function display_body_form_edit_content_post()
        {
            global $db, $config, $admin_posts;
            $post_id = (int) safe_input(intval($_GET['id'])) ;
            admin_header(get_admin_languages('faqs') .'('. get_admin_languages('edit').')');
            $result = $db->sql_query("SELECT * FROM ".POSTS_TABLE." WHERE `post_type`='faqs' AND `id`='{$post_id}'");
            $row    = $db->sql_fetchrow($result);
            $row['orders'] = get_post_meta($post_id, 'orders_post', '0');
            $admin_posts->admin_form_post_html(array(
                'labels'            => array('name' => get_admin_languages('faqs'), 'add_new_item' => get_admin_languages('edit')),
                'post_type'         => 'faqs',
                'query'             => 'update',
                'data'              => $row,
                'term_type'         => 'faqs',
                'supports'          => array('title', 'editor', 'category', 'orders'),
                'screen_columns'    => '2'
            ));
            admin_footer(); 
        }
        // action update meta post
        function display_update_post_meta_faqs($arg)
        {
            (isset($arg['post']['pin_post']))? update_post_meta($arg['post_id'], 'pin_post', admin_get_form_status($arg['post']['pin_post'])) : '';
            $order = (isset($arg['post']['orders']))? $arg['post']['orders'] : 0;
            update_post_meta($arg['post_id'], 'orders_post', $order);
        }
        // terms faqs
        function display_body_content_terms_faqs()
        {
            global $db, $admin_categories;
            if(isset($_REQUEST['mode']) and $_REQUEST['mode'] == 'edit' and isset($_GET['id']) and is_numeric($_GET['id'])):
                $term_id    = (int) safe_input(intval($_GET['id'])) ;
            else:
                $term_id    = false;
            endif;
            $title = ($term_id)? get_admin_languages('edit_category') : get_admin_languages('categories');
            admin_header($title);
            $admin_categories->page_terms_start(array(
                'labels'            => array('name' => $title),
                'query'             => ($term_id)? 'update' : 'addnew',
                'term_id'           => $term_id,                
                'term_type'         => 'faqs',
                'supports'          => array('checkbox', 'name', 'description', 'slug', 'count', 'order'),
                'select_actions'    => array('activs', 'disactivs', 'delete', 'orders'),
                'js_datatable'      => 'null, null, null, null, null',
            ));
            admin_footer();
        }
    }
    // End Class
}
?>
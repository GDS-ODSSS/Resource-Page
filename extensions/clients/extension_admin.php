<?php
if (!defined("IN_PHPMEGATEMP"))
      die('Direct access to this location is not allowed.');

if(!class_exists('extensions_admin_clients'))
{
    class extensions_admin_clients
    {
        // class constructor
        function __construct()
        {
            
        }
        // display admin clients
        function display_admin_clients()
        {
            global $hooks;
            // action admin global
            $hooks->add_action('admin_menu_dropdown_clients', array($this , 'display_menu_dropdown_clients'), 40);
            $hooks->add_action('admin_sidebar_menu', array($this , 'display_sidebar_menu'), 40);
            // extensions options
            $hooks->add_filter('add_admin_extensions_tabs', array($this , 'add_admin_extensions_tabs'), 40);
            $hooks->add_filter('add_admin_extensions_display', array($this , 'add_admin_extensions_display'), 40);
            $hooks->add_action('add_admin_extensions_options_update', array($this , 'extensions_options_update'), 40);
            // set section home themeoption
            $hooks->add_filter('add_admin_themeoption_home_sections', array($this , 'add_admin_themeoption_home_sections'), 40);
            // add filter admin post clients
            $hooks->add_filter('admin_display_supports_post_th_clients', array($this , 'display_supports_post_th'), 1);
            $hooks->add_filter('admin_display_supports_post_td_clients', array($this , 'display_supports_post_td'), 1);
            // add action admin post clients
            $hooks->add_action('admin_display_body_content_post_clients', array($this , 'display_body_content_post'), 1);
            $hooks->add_action('admin_display_add_content_post_clients', array($this , 'display_body_form_add_content_post'), 1);
            $hooks->add_action('admin_display_edit_content_post_clients', array($this , 'display_body_form_edit_content_post'), 1);
            $hooks->add_action('admin_display_update_post_meta_clients', array($this , 'display_update_post_meta_clients'), 1);
            
            $hooks->add_action('admin_display_post_supports_box_lg_clients', array($this , 'display_post_supports_box_lg'), 1);
            // user permissions
            $hooks->add_filter('admin_display_user_permissions', array($this , 'display_user_permissions'), 15);
        }
        // display menu dropdown clients
        function display_menu_dropdown_clients()
        {
            global $db, $config, $template, $hooks;
            echo '<li><a href="posts.php?post_type=clients&mode=clients">'.get_admin_languages('clients').'</a></li>';
        }
        // display sidebar menu
        function display_sidebar_menu()
        {
            if(is_user_permissions('clients', false))
            {
                admin_sidebar_menu_register(array(
                    'class' => get_class_menu_post_type('clients'),
                    'url'   => '#', 
                    'title' => get_admin_languages('clients'), 
                    'icon'  => 'fas fa-user-tie' , 
                    'sub'   => array(
                            array('url' => 'posts.php?post_type=clients',          'title' => get_admin_languages('clients')),
                            array('url' => 'posts.php?post_type=clients&mode=new', 'title' => get_admin_languages('add_new')),
                        )
                    )
                );
            }
        }
        // display extensions tabs settings
        function add_admin_extensions_tabs($arg)
        {
            $menu['clients'] = array('icon' => 'fas fa-user-tie', 'id' => 'clients', 'title' => get_admin_languages('clients'));
            return array_merge( $arg, $menu) ;
        }
        // display page setting
        function add_admin_extensions_display($arg)
        {
            global $config;
            $options        = maybe_unserialize($config['clients_options']);
            $title          = (isset($options['title']))? $options['title'] : '';
            $description    = (isset($options['description']))? $options['description'] : '';
            $section_home   = (isset($options['section_home']))? $options['section_home'] : 0;
            $home_class     = (isset($options['home_class']))? $options['home_class'] : 'white_bg';
            $style          = (isset($options['style']))? $options['style'] : '1';
            // setting section home
            $settings['clients']['section_general'] = array(
                'title'     => get_admin_languages('general'),
                'options'   => array(
                    array('type' => 'checkbox', 'id' => 'clients[section_home]', 'name' => get_admin_languages('section_home'), 'value' => $section_home),
                    array('type' => 'radio', 'id' => 'clients[home_class]', 'name' => 'home_class', 'value' => $home_class, 'options' => array(
                        'white_bg'  => 'White',
                        'gray_bg'   => 'Gray',
                        'black_bg'  => 'Black',
                        'bg_color'  => 'Color',
                    )),
                    array('type' => 'text', 'id' => 'clients[title]', 'name' => get_admin_languages('title'), 'value' => $title),
                    array('type' => 'text', 'id' => 'clients[description]', 'name' => get_admin_languages('description'), 'value' => $description),
                    array('name' => 'Style', 'id' => 'clients[style]', 'type' => 'radio', 'value' => $style, 'options' => array(
                        '1' => array('label' => 'Style 1', 'img' => get_url_extensions('clients').'/style1.png'),
                        '2' => array('label' => 'Style 2', 'img' => get_url_extensions('clients').'/style2.png'),
                    ))
                )
            );
            return array_merge( $arg, $settings) ;
        }
        // extensions options update
        function extensions_options_update()
        {
            set_config('clients_options', maybe_serialize($_POST['clients']));
        }
        // add admin themeoption home
        function add_admin_themeoption_home_sections($arg)
        {
            global $config;
            $options           = maybe_unserialize($config['clients_options']);
            $section_home      = (isset($options['section_home']) and $options['section_home'])? 'on' : 'off';
            $menu['clients'] = array('title' => 'clients', 'template' => 'clients/home_block_clients.html', 'status' => $section_home);
            return array_merge( $arg, $menu) ;
        }
        // display user permissions
        function display_user_permissions($arg)
        {
            $perm['clients'] = get_admin_languages('clients');
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
            $result     = $db->sql_query("SELECT * FROM ".POSTS_TABLE." WHERE `post_type`='clients' ORDER BY post_modified DESC");
            admin_header(get_admin_languages('clients'));
            $admin_posts->page_post_start(array(
                'labels' => array(
                    'name'   => get_admin_languages('clients'), 
                    'button' => array(
                        'title' => get_admin_languages('add_new'), 
                        'url'   => 'posts.php?post_type=clients&mode=new'
                    )
                ),
                'post_type'         => 'clients',
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
            admin_header(get_admin_languages('clients') .'('. get_admin_languages('add_new').')');
            $row['orders'] = $db->sql_numrows("SELECT * FROM ".POSTS_TABLE." WHERE `post_type`='clients'") + 1;
            $admin_posts->admin_form_post_html(array(
                'labels'            => array('name' => get_admin_languages('clients'), 'add_new_item' => get_admin_languages('add_new')),
                'post_type'         => 'clients',
                'query'             => 'addnew',
                'data'              => $row,
                'term_type'         => 'clients',
                'supports'          => array('title', 'thumbnail', 'orders', 'client_url'),
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
            admin_header(get_admin_languages('clients') .'('. get_admin_languages('edit').')');
            $result = $db->sql_query("SELECT * FROM ".POSTS_TABLE." WHERE `post_type`='clients' AND `id`='{$post_id}'");
            $row    = $db->sql_fetchrow($result);
            $row['orders'] = get_post_meta($post_id, 'orders_post', '0');
            $admin_posts->admin_form_post_html(array(
                'labels'            => array('name' => get_admin_languages('clients'), 'add_new_item' => get_admin_languages('edit')),
                'post_type'         => 'clients',
                'query'             => 'update',
                'data'              => $row,
                'term_type'         => 'clients',
                'supports'          => array('title', 'thumbnail', 'orders', 'client_url'),
                'screen_columns'    => '2',
                'permalink'         => false
            ));
            admin_footer(); 
        }
        // supports box
        function display_post_supports_box_lg($arg)
        {
            if(in_array('client_url', $arg['supports']))
            {
                $client_url    = (isset($arg['data']['id']))? get_post_meta($arg['data']['id'], 'client_url', '') : '';
                $x      = 0;
                $html   = '
                <div class="form-group">
                    <label class="control-label">'.get_admin_languages('url').'</label>
                    <input type="text" name="client_url" value="'.$client_url.'" class="form-control text-left" dir="ltr">
                </div>
                ';
                echo post_boxes_content(array('title' => get_admin_languages('url'), 'html' => $html));
            }
        }
        // action update meta post
        function display_update_post_meta_clients($arg)
        {
            $order = (isset($arg['post']['orders']))? $arg['post']['orders'] : 0;
            update_post_meta($arg['post_id'], 'orders_post', $order);

            $client_url = (isset($arg['post']['client_url']))? $arg['post']['client_url'] : '';
            update_post_meta($arg['post_id'], 'client_url', $client_url);
        }
    }
}
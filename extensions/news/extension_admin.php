<?php
if (!defined("IN_PHPMEGATEMP"))
      die('Direct access to this location is not allowed.');

if(!class_exists('extensions_admin_news'))
{
    class extensions_admin_news
    {
        // class constructor
        function __construct()
        {
            
        }
        // display admin news
        function display_admin_news()
        {
            global $hooks;
            // action admin global
            $hooks->add_action('admin_menu_dropdown_new', array($this , 'display_menu_dropdown_new'), 3);
            $hooks->add_action('admin_sidebar_menu', array($this , 'display_sidebar_menu'), 5);
            // extensions options
            $hooks->add_filter('add_admin_extensions_tabs', array($this , 'add_admin_extensions_tabs'), 40);
            $hooks->add_filter('add_admin_extensions_display', array($this , 'add_admin_extensions_display'), 40);
            $hooks->add_action('add_admin_extensions_options_update', array($this , 'extensions_options_update'), 40);
            // set section home themeoption
            $hooks->add_filter('add_admin_themeoption_home_sections', array($this , 'add_admin_themeoption_home_sections'), 40);
            $hooks->add_filter('add_admin_themeoption_home_boxs', array($this , 'add_admin_themeoption_home_boxs'), 40);
            // dashboard stat boxes
            $hooks->add_action('admin_dashboard_stat_boxes', array($this , 'display_dashboard_stat_boxes'), 3);
            // add filter admin post news
            $hooks->add_filter('admin_display_supports_post_th_news', array($this , 'display_supports_post_th'), 1);
            $hooks->add_filter('admin_display_supports_post_td_news', array($this , 'display_supports_post_td'), 1);
            // add action admin post news
            $hooks->add_action('admin_display_body_content_post_news', array($this , 'display_body_content_post'), 1);
            $hooks->add_action('admin_display_add_content_post_news', array($this , 'display_body_form_add_content_post'), 1);
            $hooks->add_action('admin_display_edit_content_post_news', array($this , 'display_body_form_edit_content_post'), 1);
            $hooks->add_action('admin_display_update_post_meta_news', array($this , 'display_update_post_meta_news'), 1);
            // action admin terms news
            $hooks->add_action('admin_display_body_content_terms_news', array($this , 'display_body_content_terms_news'), 1);
            $hooks->add_action('admin_display_update_term_meta_news', array($this , 'display_update_term_meta_news'), 1);
            // user permissions
            $hooks->add_filter('admin_display_user_permissions', array($this , 'display_user_permissions'), 5);
            $hooks->add_action('admin_nav_menu_side_control_extension', array($this , 'display_admin_nav_menu_side'), 1);
        }
        // display admin nav menu side
        function display_admin_nav_menu_side()
        {
            $html = '
            <li>
                <label class="menu-item-title">
                <input type="checkbox" class="menu-item-checkbox" name="menu-item" data-url="news" data-title="'.get_admin_languages('news').'" data-icon="far fa-newspaper"> 
                <span class="lbl">'.get_admin_languages('news').'</span> 
                </label>
            </li>
            ';
            echo $html;
        }
        // display menu dropdown new
        function display_menu_dropdown_new()
        {
            if(is_user_permissions('news', false))
            {
                echo '<li><a href="posts.php?post_type=news&mode=new">'.get_admin_languages('news').'</a></li>';
            }
            
        }
        // display sidebar menu
        function display_sidebar_menu()
        {
            global $db;
            if(is_user_permissions('news', false))
            {
                admin_sidebar_menu_register(array(
                    'class'     => get_class_menu_post_type('news'),
                    'url'       => '#', 
                    'title'     => get_admin_languages('news'), 
                    'icon'      => 'far fa-newspaper' , 
                    'container' => $container,
                    'sub'       => array(
                            array('url' => 'posts.php?post_type=news',          'title' => get_admin_languages('news')),
                            array('url' => 'posts.php?post_type=news&mode=new', 'title' => get_admin_languages('add_new')),
                            array('url' => 'categories.php?taxonomy=news',      'title' => get_admin_languages('categories'))
                        )
                    )
                );
            }
        }
        // display extensions tabs settings
        function add_admin_extensions_tabs($arg)
        {
            $menu['news'] = array('icon' => 'far fa-newspaper', 'id' => 'news', 'title' => get_admin_languages('news'));
            return array_merge( $arg, $menu) ;
        }        
        // display page setting
        function add_admin_extensions_display($arg)
        {
            global $config, $db;
            $options = maybe_unserialize($config['news_options']);
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
            $widget_search              = (isset($options['widget_search']))? $options['widget_search'] : '';
            $search_text                = (isset($options['search_text']))? $options['search_text'] : '';
            $widget_categories          = (isset($options['widget_categories']))? $options['widget_categories'] : 0;
            $widget_recent              = (isset($options['widget_recent']))? $options['widget_recent'] : 0;
            $post_per_recent            = (isset($options['post_per_recent']))? $options['post_per_recent'] : 3;
            $widget_popular             = (isset($options['widget_popular']))? $options['widget_popular'] : 0;
            $post_per_popular           = (isset($options['post_per_popular']))? $options['post_per_popular'] : 3;
            $sidebar                    = (isset($options['sidebar']))? $options['sidebar'] : 0;
            $post_per_page              = (isset($options['post_per_page']))? $options['post_per_page'] : 3;
            $related                    = (isset($options['related']))? $options['related'] : 0;
            $post_per_related           = (isset($options['post_per_related']))? $options['post_per_related'] : 3;
            $terms_home                 = (isset($options['terms_home']))? $options['terms_home'] : array();
            $image                      = (isset($options['image']))? $options['image'] : '';
            $widget_tags                = (isset($options['widget_tags']))? $options['widget_tags'] : 0;
            
                
            $news = array();
            $result_news = get_terms_loop(array('term_type' => 'news'));
            while($terms = $db->sql_fetchrow($result_news))
            {
                if(get_count_posts_term($terms['id']))
                {
                    $news[] = array(
                        'name'  => $terms['name'],
                        'id'    => 'news[terms_home]['.$terms['id'].']',
                        'value' => $terms['id'],
                    );
                }
            }
            
            
            // setting section home
            $settings['news']['section_general'] = array(
                'title'     => get_admin_languages('general'),
                'options'   => array(
                    array('type' => 'checkbox', 'id' => 'news[sidebar]', 'name' => get_admin_languages('sidebar'), 'value' => $sidebar),
                    array('type' => 'slider_number', 'id' => 'news[post_per_page]', 'name' => get_admin_languages('post_per_page'), 'value' => $post_per_page, 'min' => '1', 'max' => '32', 'step' => '1'),
                )
            );
            // setting section home
            $settings['news']['section_home'] = array(
                'title'     => get_admin_languages('section_home'),
                'options'   => array(
                    array('type' => 'checkbox', 'id' => 'news[box_home]', 'name' => get_admin_languages('box_home'), 'value' => $box_home),
                    array('type' => 'radio', 'id' => 'news[column]', 'name' => 'column', 'value' => $column, 'options' => array(
                        'col-md-2' => 'col-md-2',
                        'col-md-3' => 'col-md-3',
                        'col-md-4' => 'col-md-4',
                        'col-md-5' => 'col-md-6',
                    )),
                    array('type' => 'text', 'id' => 'news[classes]', 'name' => 'classes', 'value' => $classes),
                    array('type' => 'text', 'id' => 'news[title]', 'name' => get_admin_languages('title'), 'value' => $title),
                    array('type' => 'fonticon', 'id' => 'news[icon]', 'name' => get_admin_languages('icon'), 'value' => $icon),
                    array('type' => 'upload', 'id' => 'news[image]', 'name' => get_admin_languages('image'), 'value' => $image, 'src' => 'src'),
                    array('type' => 'text', 'id' => 'news[description]', 'name' => get_admin_languages('description'), 'value' => $description),
                    array('type' => 'text', 'id' => 'news[text_link]', 'name' => get_admin_languages('text_link'), 'value' => $text_link),
                    array('type' => 'checkbox', 'id' => 'news[section_home]', 'name' => get_admin_languages('section_home'), 'value' => $section_home),
                    array('type' => 'radio', 'id' => 'news[home_class]', 'name' => 'home_class', 'value' => $home_class, 'options' => array(
                        'white_bg'  => 'White',
                        'gray_bg'   => 'Gray',
                        'black_bg'  => 'Black',
                        'bg_color'  => 'Color',
                    )),
                    array('type' => 'checkbox_array', 'id' => 'news[terms_home]', 'name' => get_admin_languages('categories_home'), 'options' => $news, 'value' => $terms_home),
                    array('type' => 'slider_number', 'id' => 'news[home_post_per_page]', 'name' => get_admin_languages('post_per_page'), 'value' => $home_post_per_page, 'min' => '1', 'max' => '32', 'step' => '1'),
                )
            );
            // setting widget search
            $settings['news']['section_widget_search'] = array(
                'title'     => get_admin_languages('widget_search'),
                'options'   => array(
                    array('type' => 'checkbox', 'id' => 'news[widget_search]', 'name' => get_admin_languages('widget_search'), 'value' => $widget_search),
                    array('type' => 'text', 'id' => 'news[search_text]', 'name' => get_admin_languages('placeholder'), 'value' => $search_text),
                )
            );
            // setting widget categories
            $settings['news']['section_widget_categories'] = array(
                'title'     => get_admin_languages('widget_categories'),
                'options'   => array(
                    array('type' => 'checkbox', 'id' => 'news[widget_categories]', 'name' => get_admin_languages('widget_categories'), 'value' => $widget_categories),
                )
            );
            // setting widget recent
            $settings['news']['section_widget_recent'] = array(
                'title'     => get_admin_languages('widget_recent'),
                'options'   => array(
                    array('type' => 'checkbox', 'id' => 'news[widget_recent]', 'name' => get_admin_languages('widget_recent'), 'value' => $widget_recent),
                    array('type' => 'slider_number', 'id' => 'news[post_per_recent]', 'name' => get_admin_languages('post_per_page'), 'value' => $post_per_recent, 'min' => '1', 'max' => '12', 'step' => '1'),
                )
            );
            // setting widget popular
            $settings['news']['section_widget_popular'] = array(
                'title'     => get_admin_languages('widget_popular'),
                'options'   => array(
                    array('type' => 'checkbox', 'id' => 'news[widget_popular]', 'name' => get_admin_languages('widget_popular'), 'value' => $widget_popular),
                    array('type' => 'slider_number', 'id' => 'news[post_per_popular]', 'name' => get_admin_languages('post_per_page'), 'value' => $post_per_popular, 'min' => '1', 'max' => '12', 'step' => '1'),
                )
            );
            // setting widget tags
            $settings['news']['section_widget_tags'] = array(
                'title'     => get_admin_languages('widget_tags'),
                'options'   => array(
                    array('type' => 'checkbox', 'id' => 'news[widget_tags]', 'name' => get_admin_languages('widget_tags'), 'value' => $widget_tags),
                )
            );
            // setting single post
            $settings['news']['section_single_post'] = array(
                'title'     => get_admin_languages('single_post'),
                'options'   => array(
                    array('type' => 'checkbox', 'id' => 'news[related]', 'name' => get_admin_languages('related'), 'value' => $related),
                    array('type' => 'slider_number', 'id' => 'news[post_per_related]', 'name' => get_admin_languages('post_per_page'), 'value' => $post_per_related, 'min' => '1', 'max' => '12', 'step' => '1'),
                )
            );

            return array_merge( $arg, $settings) ;
        }
        // extensions options update
        function extensions_options_update()
        {
            set_config('news_options', maybe_serialize($_POST['news']));
        }
        // add admin themeoption home
        function add_admin_themeoption_home_sections($arg)
        {
            global $config;
            $options        = maybe_unserialize($config['news_options']);
            $section_home   = (isset($options['section_home']) and $options['section_home'])? 'on' : 'off';
            $menu['news']   = array('title' => get_admin_languages('news'), 'template' => 'news/home_block_news.html', 'status' => $section_home);
            return array_merge( $arg, $menu) ;
        }
        // add admin themeoption home
        function add_admin_themeoption_home_boxs($arg)
        {
            global $config;
            $options        = maybe_unserialize($config['news_options']);
            $box_home   = (isset($options['box_home']) and $options['box_home'])? 'on' : 'off';
            $menu['news']   = array('title' => get_admin_languages('news'), 'template' => 'news/home_box_news.html', 'status' => $box_home);
            return array_merge( $arg, $menu) ;
        }
        // display sidebar menu
        function display_dashboard_stat_boxes()
        {
            $arg = array(
                'icon'          => 'far fa-newspaper',
                'title'         => get_admin_languages('news'),
                'color'         => '4',
                'number_pre'    => get_post_pre('news'),
                'number_count'  => get_count_posts("'news'", false),
            );
            display_dashboard_info_box($arg);
        }
        // display user permissions
        function display_user_permissions($arg)
        {
            $perm['news'] = get_admin_languages('news');
            return array_unique( array_merge( $perm, (array) $arg ) );
        }
        // display supports th
        function display_supports_post_th($arg)
        {
            $th['featured_image']   = '<th style="width: 60px;text-align: center;" class="hidden-phone">'.get_admin_languages('image').'</th>';
            $th['views']    = '<th style="width: 90px;text-align: center;" class="hidden-phone">'.get_admin_languages('views').'</th>';
            return array_unique( array_merge( $th, (array) $arg ) );
        }
        // display supports td
        function display_supports_post_td($arg)
        {
            $views              = get_post_meta($arg['row']['id'],'views', '0');
            $thumbnail = get_post_attachment($arg['row']['id'], 'thumbnail');
            $thumb = ($thumbnail != '')? '<div class="post_featured_image"><img src="'.$thumbnail.'" /></div>' : '' ;
            $td['featured_image'] = '<td style="text-align: center;" class="hidden-phone">'.$thumb.'</td>';
            $td['views'] = '<td style="width: 90px;text-align: center;" class="hidden-phone">'.$views.'</td>';
            return array_merge( $td, (array) $arg['td'] );
        }
        // display body content post
        function display_body_content_post()
        {
            global $db, $admin_posts;
            $result     = $db->sql_query("SELECT * FROM ".POSTS_TABLE." WHERE `post_type`='news' ORDER BY post_modified DESC");
            admin_header(get_admin_languages('news'));
            $admin_posts->page_post_start(array(
                'labels' => array(
                    'name'   => get_admin_languages('news'), 
                    'button' => array(
                        'title' => get_admin_languages('add_new'), 
                        'url'   => 'posts.php?post_type=news&mode=new'
                    )
                ),
                'post_type'         => 'news',
                'select_actions'    => array('activs', 'disactivs', 'delete'),
                'supports'          => array('checkbox', 'featured_image','title', 'author', 'publish_status', 'categories', 'views', 'date', 'comments'),
                'result'            => $result,
                'js_datatable'      => '{ "orderable": false },null, null, null, null, null, null, { "orderable": false }',
            ));
            admin_footer();
        }
        // form addnew post
        function display_body_form_add_content_post()
        {
            global $db, $admin_posts;
            admin_header(get_admin_languages('news') .'('. get_admin_languages('add_new').')');
            $row['orders'] = $db->sql_numrows("SELECT * FROM ".POSTS_TABLE." WHERE `post_type`='news'") + 1;
            $admin_posts->admin_form_post_html(array(
                'labels'            => array('name' => get_admin_languages('news'), 'add_new_item' => get_admin_languages('add_new')),
                'post_type'         => 'news',
                'query'             => 'addnew',
                'data'              => $row,
                'term_type'         => 'news',
                'supports'          => array('title', 'editor', 'publish_status', 'category', 'orders', 'thumbnail', 'comments', 'author', 'post_tag'),
                'screen_columns'    => '2'
            ));
            admin_footer(); 
        }
        // form edit post
        function display_body_form_edit_content_post()
        {
            global $db, $admin_posts;
            $post_id = (int) safe_input(intval($_GET['id'])) ;
            admin_header(get_admin_languages('news') .'('. get_admin_languages('edit').')');
            $result = $db->sql_query("SELECT * FROM ".POSTS_TABLE." WHERE `post_type`='news' AND `id`='{$post_id}'");
            $row    = $db->sql_fetchrow($result);
            $row['orders'] = get_post_meta($post_id, 'orders_post', '0');
            $admin_posts->admin_form_post_html(array(
                'labels'            => array('name' => get_admin_languages('news'), 'add_new_item' => get_admin_languages('edit')),
                'post_type'         => 'news',
                'query'             => 'update',
                'data'              => $row,
                'term_type'         => 'news',
                'supports'          => array('title', 'editor', 'publish_status', 'category', 'orders', 'thumbnail', 'comments', 'author', 'post_tag'),
                'screen_columns'    => '2'
            ));
            admin_footer(); 
        }
        // action update meta post
        function display_update_post_meta_news($arg)
        {
            (isset($arg['post']['pin_post']))? update_post_meta($arg['post_id'], 'pin_post', admin_get_form_status($arg['post']['pin_post'])) : '';
            $order = (isset($arg['post']['orders']))? $arg['post']['orders'] : 0;
            update_post_meta($arg['post_id'], 'orders_post', $order);
        }
        // terms news
        function display_body_content_terms_news()
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
                'term_type'         => 'news',
                'supports'          => array('checkbox', 'name', 'description', 'slug', 'count', 'order'),
                'select_actions'    => array('activs', 'disactivs', 'delete', 'orders'),
                'js_datatable'      => 'null, null, null, null, null',
            ));
            admin_footer();
        }
        // action update meta term
        function display_update_term_meta_news($arg)
        {
            
        }
    }
}
?>
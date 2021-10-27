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

if (!defined('IN_PHPMEGATEMP_CP') and !defined('IN_PHPMEGATEMP'))
{
	exit;
}

if(!class_exists('theme_options'))
{
    class theme_options
    {
        private $path = '';
        private $path_pattern = '';
        private $themefolder = 'default';
        
        function __construct()
        {
            global $hooks, $config;
            $this->path = '../'.THEME_PATH.$this->themefolder.'/theme_options/';
            $this->path_pattern = THEME_PATH.$this->themefolder.'/assets/images/patterns/';
            // display admin
            if(defined('IN_PHPMEGATEMP_CP'))
            {
                $hooks->add_action('admin_navbar_top', array($this , 'navbar_top'), 1);
                $hooks->add_action('add_admin_sidebar_sub_themes', array($this , 'sidebar_menu'), 999);
                $hooks->add_action('admin_page_theme_options_display', array($this , 'options_display'), 1);
                $hooks->add_filter('admin_display_user_permissions', array($this , 'display_user_permissions'), 5);
            }
            else
            {
                $hooks->add_action('index_home_action', array($this , 'home_action_display'),1);
                $hooks->add_action('global_assign_vars', array($this , 'global_assign_vars'),1);
                $hooks->add_action('ajax_index_display', array($this , 'ajax_send_content'),1);
                $hooks->add_filter('compile_include_sections', array($this , 'compile_include_sections'), 1);
                $hooks->add_filter('compile_include_boxs', array($this , 'compile_include_boxs'), 1);
            }
        }
        // compile include sections
        function compile_include_sections($arg)
        {
            global $config;
            $option_home_sections = @(is_serialized($config['home_sections']))? maybe_unserialize($config['home_sections']) : array() ;
            return array_merge( $arg, $option_home_sections);
        }
        // compile include boxs
        function compile_include_boxs($arg)
        {
            global $config;
            $option_home_boxs = @(is_serialized($config['home_boxs']))? maybe_unserialize($config['home_boxs']) : array() ;
            return array_merge( $arg, $option_home_boxs);
        }
        // home action display
        function home_action_display()
        {
            global $template, $config;
            $option_home_sections = @(is_serialized($config['home_sections']))? maybe_unserialize($config['home_sections']) : array() ;
            $template->assign_block_vars('loop_section_home', array(
                'CLASS'          => '',
                'HOME_TEMPLATE'  => 1,
            ));
            $option_home_sections = @(is_serialized($config['home_boxs']))? maybe_unserialize($config['home_boxs']) : array() ;
            $template->assign_block_vars('loop_boxs_home', array(
                'CLASS'          => '',
                'HOME_TEMPLATE'  => 1,
            ));
            $loop_section_home = '';
            foreach($option_home_sections as $value)
            {
                $loop_section_home .= $value;
            }
            $template->assign_vars(array(
                'CLASS'     => '',
                'SECTION_HOME_TEMPLATE'  => $loop_section_home,
            ));
        }
        // display user permissions
        function display_user_permissions($arg)
        {
            $perm['theme_options'] = get_admin_languages('theme_options');
            return array_unique( array_merge( $perm, (array) $arg ) );
        }
        // ajax send content
        function ajax_send_content()
        {
            global $lang;
            if(isset($_POST['action']) and $_POST['action'] == 'submitcontent')
            {
                $username       = safe_input($_POST['username']);
                $email          = safe_input($_POST['email']);
                $subject        = safe_input($_POST['subject']);
                $message        = filter_content_strip_tags($_POST['message']);
                $username_error = (!isset($username) or !strlen($username))? '<li>'.$lang['username_field_is_required'].'</li>': false;
                $email_error    = (!isset($email) or !strlen($email))? '<li>'.$lang['email_field_is_required'].'</li>': false;
                if(!$email_error)
                {
                    $email_error = (!strlen(validate_email($email)))? '<li>'.$lang['please_write_the_email_correctly'].'</li>': false;
                }
                
                $subject_error      = (!isset($subject) or !strlen($subject))? '<li>'.$lang['subject_field_is_required'].'</li>': false;
                $message_error      = (!isset($message) or !strlen($message))? '<li>'.$lang['message_field_is_required'].'</li>': false;
                if(!$username_error and !$email_error and !$emailvali_error and !$subject_error and !$message_error)
                {
                    $data = array(
                        'type'  => 'contact_us',
                        'username'  => $username,
                        'email'     => $email,
                        'title'     => $subject,
                        'subject'   => $subject,
                        'message'   => get_format_contact($message),
                        'send_to'   => array('site' => true),
                    );
                    @mail_send($data);
                    echo json_encode(array('status' => 'success', 'html' => $lang['thank_you_the_message_was_sent_successfully']));
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
        // get google fonts
        function get_google_fonts()
        {
            
            $font_family = $this->get_theme_option('font_family');
            $font_link = array();
            if (!empty($font_family)) {
                $font_link[0] = str_replace(' ', '%20', $font_family);
                $font_link[2] = $font_family;
            }
            else {
                $font_link[0] = 'Nunito';
                $font_link[2] = 'Nunito';
            }
            $font_link[1] = '300,400,600,800';
            return $font_link;
        }
        // global assign vars
        function global_assign_vars()
        {
            global $template, $config;
            $option_name = 'default_theme_options';
        	$get_option = (isset($config[$option_name]))? maybe_unserialize($config[$option_name]) : array() ;
            if(!is_array($get_option))
            {
                $get_option = array( 
                    'show_sidebar'              => '1',
                    'position_sidebar'          => 'left',
                    'googlemap'                 => '',
                    'email'                     => 'ocjpnawa@gmail.com',
                    'phone'                     => '00201094140448',
                    'fax'                       => '00201094140448',
                    'disqus_status'             => '0',
                    'disqus_username'           => '',
                    'logo'                      => '',
                    'favicon'                   => '',
                    'headercode'                => '',
                    'sub_header_background'     => $config['siteurl'].'/themes/default/assets/images/start_page.jpg',
                    'sub_header_title'          => 'Welcome to UNHCR IM Resource Page'.SCRIPT_VERSION,
                    'sub_header_title_desc'     => 'How can I help you today ?',
                    'sub_header_search_text'    => 'Type your keyword to search...',
                    'page_header_background'    => $config['siteurl'].'/themes/default/assets/images/start_page2.jpg',
                    'header_background'         => $config['siteurl'].'/themes/default/assets/images/start_page.jpg',
                    'pageaboutus'               => '',
                    'pagecontactus'             => '', 
                    'pageprivacyterms'          => '', 
                    'font_family'               => 'Nunito',
                    'skinscolor'                => 'green',
                    'page_loaded'               => '',
                    'userlink_header'           => '1', 
                    'class_header'              => 'normal', 
                    'header_style'              => '2',
                    'sub_header_style'          => '1', 
                    'boxed'                     => '',
                    'boxed_margintop'           => '30', 
                    'boxed_marginbottom'        => '25', 
                    'boxed_attachment'          => 'fixed',
                    'boxed_repeat'              => 'repeat',
                    'boxed_bgsize'              => 'cover',
                    'boxed_bgtype'              => '',
                    'boxed_background'          => '',
                    'boxed_pattern'             => '8',
                    'facebook'                  => '#',
                    'twitter'                   => '#',
                    'youtube'                   => '#',
                    'vimeo'                     => '#',
                    'behance'                   => '#', 
                    'linkedin'                  => '#',
                    'pinterest'                 => '#',
                    'instagram'                 => '#',
                    'metarobots'                => 'index, follow',
                    'metatwitter'               => '@augustinenawa',
                    'description'               => 'Site wide Meta Description',
                    'keywords'                  => 'news, help, knowledgebase, faqs',
                    'copyright'                 => 'Copyright&copy; UNHCR IM Resource Page'.SCRIPT_VERSION.' All rights reserved.',
                    'footercode'                => '',
                    'customcss'                 => '',
                    'customjs'                  => '',
                    'bannertop'                 => '',
                    'bannerwidget'              => '',
                    'bannerbottom'              => '',
                    'cookie_status'             => '1',
                    'cookie_id'                 => 'phphelpm45cookie',
                    'cookie_title'              => 'I Want Cookies',
                    'cookie_position'           => 'left',
                    'cookie_image'              => $config['siteurl'].'/themes/default/assets/images/cookie.svg',
                    'cookie_desc'               => 'We use cookies to personalize content,  analyze traffic.',
                    'cookie_decline'            => 'I Decline',
                    'cookie_consent'            => 'I Understand',
                );
                set_config($option_name, maybe_serialize($get_option));
            }
            foreach($get_option as $key => $value)
            {
                $template->assign_var('THOP_'.strtoupper($key), $value);
            }
            $body_class = '';
            $body_class .= ($this->get_theme_option('class_header') != 'normal')? $this->get_theme_option('class_header') : '';
            $body_class .= ($this->get_theme_option('boxed'))? ' boxed ' : ' ';
            $template->assign_var('THOP_BODY_CLASS', $body_class);
            $template->assign_var('CLASS_HEADER', $this->get_theme_option('class_header'));
            $template->assign_var('USERLINK_HEADER', $this->get_theme_option('userlink_header'));
            $stylesheet = '<style type="text/css">';
            if($this->get_theme_option('boxed'))
            {
                $bgcolor        = $this->get_theme_option('boxed_bgcolor');
                $background     = $this->get_theme_option('boxed_background');
                $margintop      = $this->get_theme_option('boxed_margintop');
                $marginbottom   = $this->get_theme_option('boxed_marginbottom');
                $repeat         = $this->get_theme_option('boxed_repeat');
                $attachment     = $this->get_theme_option('boxed_attachment');
                $boxed_bgsize   = $this->get_theme_option('boxed_bgsize');
                if($this->get_theme_option('boxed_bgtype'))
                {
                    $stylesheet    .= '.boxed {background: transparent url(\''.$background.'\') '.$attachment.' '.$repeat.' center top;background-size: '.$boxed_bgsize.';margin-top: '.$margintop.'px;margin-bottom: '.$marginbottom.'px;}';
                }
                else
                {
                    $pattern     = $this->get_theme_option('boxed_pattern');
                    $pattern_img = array(
                        '1'  => $this->path_pattern.'pattern1.jpg',
                        '2'  => $this->path_pattern.'pattern2.png',
                        '3'  => $this->path_pattern.'pattern3.jpg',
                        '4'  => $this->path_pattern.'pattern4.png',
                        '5'  => $this->path_pattern.'pattern5.png',
                        '6'  => $this->path_pattern.'pattern6.png',
                        '7'  => $this->path_pattern.'pattern7.png',
                        '8'  => $this->path_pattern.'pattern8.png',
                        '9'  => $this->path_pattern.'pattern9.png',
                        '10' => $this->path_pattern.'pattern10.png',
                        '11' => $this->path_pattern.'pattern11.png',
                        '12' => $this->path_pattern.'pattern12.png',
                        '13' => $this->path_pattern.'pattern13.jpg',
                        '14' => $this->path_pattern.'pattern14.png',
                        '15' => $this->path_pattern.'pattern15.gif',
                        '16' => $this->path_pattern.'pattern16.png',
                        '17' => $this->path_pattern.'pattern17.png',
                        '18' => $this->path_pattern.'pattern18.png',
                        '19' => $this->path_pattern.'pattern19.png',
                        '20' => $this->path_pattern.'pattern20.png',
                        '21' => $this->path_pattern.'pattern21.png'
                    );
                    $stylesheet    .= '.boxed {background: transparent url(\''.$pattern_img[$pattern].'\');margin-top: '.$margintop.'px;margin-bottom: '.$marginbottom.'px;}';
                }
                
            }
            if($this->get_theme_option('sub_header_background'))
            {
                $sub_background = $this->get_theme_option('sub_header_background');
                $stylesheet    .= '#sub_header{background: url(\''.$sub_background.'\') no-repeat top center;}';
            }
            if($this->get_theme_option('page_header_background'))
            {
                $page_background = $this->get_theme_option('page_header_background');
                $stylesheet    .= '#sub_header.page{background: url(\''.$page_background.'\') no-repeat top center;}';
            }
            if($this->get_theme_option('header_background'))
            {
                $header_background = $this->get_theme_option('header_background');
                $stylesheet    .= '.parallax-window #sub_header{background-image: url(\''.$header_background.'\');}';
            }
            $stylesheet .= '</style>';
            $template->assign_var('THOP_STYLESHEET', $stylesheet);
            $skins_color = $this->get_theme_option('skinscolor');
            $template->assign_var('THOP_LOGOCOLOR', '-'.$skins_color);
            $template->assign_var('THOP_SKINS_COLOR', '<link rel="stylesheet" id="skins-'.$skins_color.'" href="'.get_url_template('assets/css/skins/'.$skins_color.'.css').'" type="text/css" />');
            $template->assign_var('THOP_LINK_PAGE_ABOUT', permanent_post_link('page', $this->get_theme_option('pageaboutus')));
            $template->assign_var('THOP_LINK_PAGE_CONTACT', permanent_post_link('page', $this->get_theme_option('pagecontactus')));
            $template->assign_var('THOP_LINK_PAGE_TERMS', permanent_post_link('page', $this->get_theme_option('pageprivacyterms')));
            $font_family = $this->get_google_fonts();
            $googlefont = '<link rel="stylesheet" href="https://fonts.googleapis.com/css?family='.$font_family[0].':'.$font_family[1].'">';
            $template->assign_var('THOP_LINK_GOOGLE_FONT', $googlefont);
            $template->assign_var('THOP_BODY_GOOGLE_FONT', '<style type="text/css">body {font-family: "'.$font_family[2].'";}</style>');
        }
        // add to top navbar
        function navbar_top()
        {
            if(is_user_permissions('theme_options', false))
            {
                echo '<li><a href="themes.php?page=theme_options"><i class="fa fa-cog"></i> <sapn class="notphone">'.get_admin_languages('theme_options').'</span></a></li>';
            }
        }
        // sidebar menu
        function sidebar_menu($arg)
        {
            if(is_user_permissions('theme_options', false))
            {
                $menu['theme_options'] = array('url' => 'themes.php?page=theme_options', 'title' => get_admin_languages('theme_options'));
                return array_merge( $arg, $menu) ;
            }
        }
        // get theme option
        function get_theme_option($name)
        {
            global $config;
            $option_name = 'default_theme_options';
            if(!isset($config[$option_name]))
            {
                set_config($option_name, '');
            }
        	$get_option = (is_serialized($config[$option_name]))? maybe_unserialize($config[$option_name]) : array() ;
        	if( !empty( $get_option[$name] ))
            {
        		return $get_option[$name];
        	}
        	return false ;
        }
        // start page option
        function options_display()
        {
            if(isset($_REQUEST['page']) and $_REQUEST['page'] == 'theme_options')
            {
                global $config, $token, $hooks;
                $option_name = 'default_theme_options';
                $megapanel_options = new admin_megapanel_options();
                if(isset($_POST['action']) and $_POST['action'] == 'savesetting')
                {
                    $token      = safe_input($_POST['token']);
                    if($token == $_SESSION['securitytokenadmincp']):
                        
                        if(isset($_POST['home_boxs']))
                        {
                            $home_boxs = $_POST['home_boxs'];
                            $home_boxs_array = array();
                            foreach($home_boxs as $key => $value)
                            {
                                if($_POST['home_boxsstatus'][$key] == 'on')
                                {
                                    $home_boxs_array[$key] = $value;
                                }
                                
                            }
                            set_config('home_boxs', maybe_serialize($home_boxs_array));
                            unset($_POST['home_boxs']);
                            unset($_POST['home_boxsstatus']);
                        }
                    
                        if(isset($_POST['home_sections']))
                        {
                            $home_sections = $_POST['home_sections'];
                            $home_sections_array = array();
                            foreach($home_sections as $key => $value)
                            {
                                if($_POST['home_sectionsstatus'][$key] == 'on')
                                {
                                    $home_sections_array[$key] = $value;
                                }
                            }
                            set_config('home_sections', maybe_serialize($home_sections_array));
                            unset($_POST['home_sections']);
                            unset($_POST['home_sectionsstatus']);
                        }
                        set_config($option_name, maybe_serialize($_POST));
                        $_SESSION['action_token'] = get_admin_languages('saved_successfully');
                    endif;
                    @header("Location:themes.php?page=theme_options");
                    exit;
                }
                if(isset($_SESSION['action_token'])):
                    $message = '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'.$_SESSION['action_token'].'</div>';
                    unset($_SESSION['action_token']);
                else:
                    $message = '';
                endif;
                admin_header(get_admin_languages('theme_options'));
                echo admin_content_section_start().'<div class="col-md-12">
                '.$message.'
                <div class="megapanel-panel">
                <div class="panel-wrapper">
                <form action="themes.php?page=theme_options" method="post" name="form" class="form-horizontal">
                <input type="hidden" name="action" value="savesetting" />
                <input type="hidden" name="token" value="'.$token.'" />
                <div class="megapanel-main">
                <div id="megapanel-editor" class="megapanel-jqcheckbox">
                <div class="megapanel-wrapper">
                <header class="megapanel-page-header">
                <h1>'.get_admin_languages('theme_options').'</h1>
                <div class="megapanel-submit"><button class="button button-primary"><i class="fa fa-save"></i> '.get_admin_languages('save_changes').'</button></div>
                </header>
                <div class="megapanel-tabs-container megapanel-container">
                <div class="megapanel-tabs nav-tabs-cookie" data-cookie="themeoptions">
                <a href="#" class="" data-tab=".option-general"><i class="fas fa-cog"></i> General</a>
                <a href="#" data-tab=".option-homepage"><i class="fas fa-home"></i> HomePage</a>
                <a href="#" data-tab=".option-pages"><i class="far fa-file-alt"></i> Pages</a>
                <a href="#" data-tab=".option-header"><i class="fas fa-heading"></i> Header</a>
                <a href="#" data-tab=".option-footer"><i class="far fa-hand-point-down"></i> Footer</a>
                <a href="#" data-tab=".option-style"><i class="fas fa-paint-brush"></i> Style</a>
                <a href="#" data-tab=".option-seo"><i class="fas fa-coffee"></i> SEO</a>
                <a href="#" data-tab=".option-socialmedia"><i class="fas fa-coffee"></i> Social media</a>
                <a href="#" data-tab=".option-custom"><i class="fas fa-coffee"></i> Custom</a>
                <a href="#" data-tab=".option-banner"><i class="fas fa-image"></i> Banner</a>
                <a href="#" data-tab=".option-cookie"><i class="fas fa-cookie-bite"></i> Cookie</a>
                </div>
                <div class="megapanel-tabs-content">
                ';
                
                /* options general */
                echo '<div class="megapanel-tab-content option-general">';
                
                
                echo $megapanel_options->start_options('General');
                $megapanel_options->field_options_item(array(
                    'name'      => 'Show Sidebar',
                    'id'        => 'show_sidebar',
                    'type'      => 'checkbox',
                    'value'     => $this->get_theme_option('show_sidebar'),
                ));
                $megapanel_options->field_options_item(array(
                    'name'      => 'Position Sidebar',
                    'id'        => 'position_sidebar',
                    'type'      => 'radio',
                    'value'     => $this->get_theme_option('position_sidebar'),
                    'options'   => array(
                        'left'      => 'Left',
                        'right'     => 'Right',
                    )
                ));
                $megapanel_options->field_options_item(array(
                    'name'  => 'URL Google Map',
                    'id'    => 'googlemap',
                    'type'  => 'text',
                    'value' => $this->get_theme_option('googlemap'),
                    'class' => '',
                    'dir'   => 'ltr',
                ));
                $megapanel_options->field_options_item(array(
                    'name'  => 'Support Email',
                    'id'    => 'email',
                    'type'  => 'text',
                    'value' => $this->get_theme_option('email'),
                    'class' => '',
                    'dir'   => 'ltr',
                ));
                $megapanel_options->field_options_item(array(
                    'name'  => 'Support Phone',
                    'id'    => 'phone',
                    'type'  => 'text',
                    'value' => $this->get_theme_option('phone'),
                    'class' => '',
                    'dir'   => 'ltr',
                ));
                $megapanel_options->field_options_item(array(
                    'name'  => 'Support FAX',
                    'id'    => 'fax',
                    'type'  => 'text',
                    'value' => $this->get_theme_option('fax'),
                    'class' => '',
                    'dir'   => 'ltr',
                ));
                echo $megapanel_options->end_options();
                
                echo $megapanel_options->start_options('Disqus Comments');
                $megapanel_options->field_options_item(array(
                    'name'      => 'Disqus Status',
                    'id'        => 'disqus_status',
                    'type'      => 'checkbox',
                    'value'     => $this->get_theme_option('disqus_status'),
                ));
                $megapanel_options->field_options_item(array(
                    'name'  => 'Disqus Username',
                    'id'    => 'disqus_username',
                    'type'  => 'text',
                    'value' => $this->get_theme_option('disqus_username'),
                ));
                echo $megapanel_options->end_options();
                
                echo '</div>';
                /* end options general */
                
                
                /* options homepage */
                echo '<div class="megapanel-tab-content option-homepage">';
                
                echo $megapanel_options->start_options('Boxs');

                $megapanel_options->field_options_item(array(
                    'name'      => 'Status',
                    'id'        => 'boxs_status',
                    'type'      => 'checkbox',
                    'value'     => $this->get_theme_option('boxs_status'),
                ));

                $megapanel_options->field_options_item(array(
                    'name'  => 'Title',
                    'id'    => 'boxs_title',
                    'type'  => 'text',
                    'value' => $this->get_theme_option('boxs_title'),
                ));
                $megapanel_options->field_options_item(array(
                    'name'      => 'Class',
                    'type'      => 'radio', 
                    'id'        => 'boxs_class', 
                    'value'     => $this->get_theme_option('boxs_class'),
                    'options'   => array('white_bg'  => 'White', 'gray_bg'   => 'Gray', 'black_bg'  => 'Black', 'bg_color'  => 'Color')
                ));
                echo $megapanel_options->end_options();
                echo $megapanel_options->start_options('Boxs Orders');
                echo '<br />
                <ul class="megapanel-list-sortable ui-sortable">';
                $home_boxs = array();
                if($hooks->has_filter('add_admin_themeoption_home_boxs')):
                    $home_boxs_arg = $hooks->apply_filters( 'add_admin_themeoption_home_boxs' , $home_boxs);
                else:
                    $home_boxs_arg = $home_boxs;
                endif;
                $html_boxs = '';
                $array_boxs = array();
                $option_home_boxs = @(is_serialized($config['home_boxs']))? maybe_unserialize($config['home_boxs']) : array() ;
                foreach($option_home_boxs as $key => $value)
                {
                    if(array_key_exists($key, $home_boxs_arg))
                    {
                        $array_boxs[$key] = $key;
                        $html_boxs .= '
                        <li id="megapanel-item-1" class="megapanel_box">
                            <div class="megapanel-options-head-items">
                                <h3>
                                    <span class="megapanel-title-item">'.$home_boxs_arg[$key]['title'].'</span>
                                    <span class="megapanel_tools megapanel-move tollpos2 hndle" title="Move"><i class="fas fa-arrows-alt"></i></span>
                                    <span class="megapanel_tools status-button status-'.$home_boxs_arg[$key]['status'].'"><i class="fas fa-circle"></i></span>
                                    <input type="hidden" name="home_boxs['.$key.']" value="'.$home_boxs_arg[$key]['template'].'" />
                                    <input type="hidden" name="home_boxsstatus['.$key.']" value="'.$home_boxs_arg[$key]['status'].'" />
                                </h3>
                            </div>
                        </li>
                        ';
                    }
                }
                
                foreach($home_boxs_arg as $key => $value)
                {
                    if(!array_key_exists($key, $array_boxs))
                    {
                        $html_boxs .= '
                        <li id="megapanel-item-1" class="megapanel_box">
                            <div class="megapanel-options-head-items">
                                <h3>
                                    <span class="megapanel-title-item">'.$value['title'].'</span>
                                    <span class="megapanel_tools megapanel-move tollpos2 hndle" title="Move"><i class="fas fa-arrows-alt"></i></span>
                                    <span class="megapanel_tools status-button status-'.$value['status'].'"><i class="fas fa-circle"></i></span>
                                    <input type="hidden" name="home_boxs['.$key.']" value="'.$value['template'].'" />
                                <input type="hidden" name="home_boxsstatus['.$key.']" value="'.$value['status'].'" />
                                </h3>
                            </div>
                        </li>
                        ';
                    } 
                }
                echo $html_boxs;
                echo '</ul>';
                echo $megapanel_options->end_options();
                
                echo $megapanel_options->start_options('Sections');
                echo '<br /><ul class="megapanel-list-sortable ui-sortable">';
                $home_sections = array();
                if($hooks->has_filter('add_admin_themeoption_home_sections')):
                    $home_sections_arg = $hooks->apply_filters( 'add_admin_themeoption_home_sections' , $home_sections);
                else:
                    $home_sections_arg = $home_sections;
                endif;
                $html_sections = '';
                $array_sections = array();
                $option_home_sections = @(is_serialized($config['home_sections']))? maybe_unserialize($config['home_sections']) : array() ;
                foreach($option_home_sections as $key => $value)
                {
                    if(array_key_exists($key, $home_sections_arg))
                    {
                        $array_sections[$key] = $key;
                        $html_sections .= '
                        <li id="megapanel-item-1" class="megapanel_box">
                            <div class="megapanel-options-head-items">
                                <h3>
                                    <span class="megapanel-title-item">'.$home_sections_arg[$key]['title'].'</span>
                                    <span class="megapanel_tools megapanel-move tollpos2 hndle" title="Move"><i class="fas fa-arrows-alt"></i></span>
                                    <span class="megapanel_tools status-button status-'.$home_sections_arg[$key]['status'].'"><i class="fas fa-circle"></i></span>
                                    <input type="hidden" name="home_sections['.$key.']" id="home_sections_'.$key.'" value="'.$home_sections_arg[$key]['template'].'" />
                                    <input type="hidden" name="home_sectionsstatus['.$key.']" id="home_sectionsstatus_'.$key.'" value="'.$home_sections_arg[$key]['status'].'" />
                                </h3>
                            </div>
                        </li>
                        ';
                    }
                }
                foreach($home_sections_arg as $key => $value)
                {
                    if(!array_key_exists($key, $array_sections))
                    {
                        $html_sections .= '
                        <li id="megapanel-item-1" class="megapanel_box">
                            <div class="megapanel-options-head-items">
                                <h3>
                                    <span class="megapanel-title-item">'.$value['title'].'</span>
                                    <span class="megapanel_tools megapanel-move tollpos2 hndle" title="Move"><i class="fas fa-arrows-alt"></i></span>
                                    <span class="megapanel_tools status-button status-'.$value['status'].'"><i class="fas fa-circle"></i></span>
                                    <input type="hidden" name="home_sections['.$key.']" id="home_sections_'.$key.'" value="'.$value['template'].'" />
                                    <input type="hidden" name="home_sectionsstatus['.$key.']" id="home_sectionsstatus_'.$key.'" value="'.$value['status'].'" />
                                </h3>
                            </div>
                        </li>
                        ';
                    } 
                }
                echo $html_sections;
                echo '</ul>';
                echo $megapanel_options->end_options();
                echo '</div>';
                /* end options general */
                
                /* options header */
                echo '<div class="megapanel-tab-content option-header">';
                echo $megapanel_options->start_options('Header');
                $megapanel_options->field_options_item(array(
                    'name'  => 'Logo',
                    'id'    => 'logo',
                    'type'  => 'upload',
                    'value' => $this->get_theme_option('logo'),
                    'src'   => 'src',
                ));
                $megapanel_options->field_options_item(array(
                    'name'  => 'Logo Mobile',
                    'id'    => 'logomobile',
                    'type'  => 'upload',
                    'value' => $this->get_theme_option('logomobile'),
                    'src'   => 'src',
                ));
                $megapanel_options->field_options_item(array(
                    'name'  => 'Favicon Icon',
                    'id'    => 'favicon',
                    'type'  => 'upload',
                    'value' => $this->get_theme_option('favicon'),
                    'src'   => 'src',
                ));
                $megapanel_options->field_options_item(array(
                    'name'  => 'Header Code',
                    'id'    => 'headercode',
                    'type'  => 'textarea_full',
                    'value' => $this->get_theme_option('headercode'),
                    'rows'  => '12',
                    'dir'   => 'ltr',
                ));
                echo $megapanel_options->end_options();
                
                echo $megapanel_options->start_options('Sub Header');
                $megapanel_options->field_options_item(array(
                    'name'  => 'Sub Header Background Image',
                    'id'    => 'sub_header_background',
                    'type'  => 'upload',
                    'value' => $this->get_theme_option('sub_header_background'),
                    'src'   => 'src',
                ));
                $megapanel_options->field_options_item(array(
                    'name'  => 'Sub Header',
                    'id'    => 'sub_header_title',
                    'type'  => 'text',
                    'value' => $this->get_theme_option('sub_header_title'),
                ));
                $megapanel_options->field_options_item(array(
                    'name'  => 'Sub Header Description',
                    'id'    => 'sub_header_title_desc',
                    'type'  => 'text',
                    'value' => $this->get_theme_option('sub_header_title_desc'),
                ));
                $megapanel_options->field_options_item(array(
                    'name'  => 'Sub Header search placeholder',
                    'id'    => 'sub_header_search_text',
                    'type'  => 'text',
                    'value' => $this->get_theme_option('sub_header_search_text'),
                ));
                $megapanel_options->field_options_item(array(
                    'name'  => 'Pages Header Background Image',
                    'id'    => 'page_header_background',
                    'type'  => 'upload',
                    'value' => $this->get_theme_option('page_header_background'),
                    'src'   => 'src',
                ));
                echo $megapanel_options->end_options();
                echo '</div>';
                /* end options header */
                
                /* options pages */
                echo '<div class="megapanel-tab-content option-pages">';
                echo $megapanel_options->start_options('General');
                $megapanel_options->field_options_item(array(
                    'name'      => 'Page About us',
                    'id'        => 'pageaboutus',
                    'type'      => 'select',
                    'value'     => $this->get_theme_option('pageaboutus'),
                    'options'   => $megapanel_options->get_pages()
                ));
                $megapanel_options->field_options_item(array(
                    'name'      => 'Page Contact Us',
                    'id'        => 'pagecontactus',
                    'type'      => 'select',
                    'value'     => $this->get_theme_option('pagecontactus'),
                    'options'   => $megapanel_options->get_pages()
                ));
                $megapanel_options->field_options_item(array(
                    'name'      => 'Page Privacy and Terms',
                    'id'        => 'pageprivacyterms',
                    'type'      => 'select',
                    'value'     => $this->get_theme_option('pageprivacyterms'),
                    'options'   => $megapanel_options->get_pages()
                ));
                echo $megapanel_options->end_options();
                echo '</div>';
                /* end options pages */
                
                /* options skins */
                echo '<div class="megapanel-tab-content option-style">';
                echo $megapanel_options->start_options('Typography');
                // 
                $font_family = array();
                $googlefonts = file_get_contents(ADMIN_ABSPATH . 'assets/libs/json/googlefonts.json');
                $gg_fonts    = json_decode($googlefonts, true);
                if (!empty($gg_fonts)) {
                    foreach ($gg_fonts as $key => $options) {
                        $font_family[$key] = $key;
                    }
                }
                
                $megapanel_options->field_options_item(array(
                    'name'      => 'Font family',
                    'id'        => 'font_family',
                    'type'      => 'select',
                    'value'     => $this->get_theme_option('font_family'),
                    'options'   => $font_family,
                    'class'     => 'width320',
                    'select2'   => true,
                ));
                
                echo $megapanel_options->end_options();
                
                echo $megapanel_options->start_options('Style');
                $megapanel_options->field_options_item(array(
                    'name'  => 'Skins',
                    'id'    => 'skinscolor',
                    'type'      => 'radio',
                    'value'     => $this->get_theme_option('skinscolor'),
                    'options'   => array(
                        'green'     => array('label' => '', 'boxcolor' => '#37a000'),
                        'blue'      => array('label' => '', 'boxcolor' => '#2487FB'),
                        'yellow'    => array('label' => '', 'boxcolor' => '#daaf02'),
                        'red'       => array('label' => '', 'boxcolor' => '#d02e37'),
                        'purple'    => array('label' => '', 'boxcolor' => '#8e74b2'),
                        'pink'      => array('label' => '', 'boxcolor' => '#f1505b'),
                        'orange'    => array('label' => '', 'boxcolor' => '#fa7642'),
                        
                    )
                ));
                $megapanel_options->field_options_item(array(
                    'name'      => 'Show loaded',
                    'id'        => 'page_loaded',
                    'type'      => 'checkbox',
                    'value'     => $this->get_theme_option('page_loaded'),
                ));
                
                $megapanel_options->field_options_item(array(
                    'name'      => 'Show User link',
                    'id'        => 'userlink_header',
                    'type'      => 'checkbox',
                    'value'     => $this->get_theme_option('userlink_header'),
                ));
                
                $megapanel_options->field_options_item(array(
                    'name'  => 'Header',
                    'id'    => 'class_header',
                    'type'      => 'radio',
                    'value'     => $this->get_theme_option('class_header'),
                    'options'   => array(
                        'normal'    => 'Normal',
                        'sticky'    => 'Sticky',
                        'jpinning'  => 'JPinning',
                    )
                ));
                
                $megapanel_options->field_options_item(array(
                    'name'      => 'Header Style',
                    'id'        => 'header_style',
                    'type'      => 'radio',
                    'value'     => $this->get_theme_option('header_style'),
                    'options'   => array(
                        '1' => array('label' => 'Style 1', 'img' => $this->path.'images/header1.png'),
                        '2' => array('label' => 'Style 2', 'img' => $this->path.'images/header2.png'),
                        '3' => array('label' => 'Style 3', 'img' => $this->path.'images/header3.png'),
                        '4' => array('label' => 'Style 4', 'img' => $this->path.'images/header4.png'),
                        '5' => array('label' => 'Style 5', 'img' => $this->path.'images/header5.png'),
                    )
                ));
                $megapanel_options->field_options_item(array(
                    'name'      => 'Sub Header Style',
                    'id'        => 'sub_header_style',
                    'type'      => 'radio',
                    'value'     => $this->get_theme_option('sub_header_style'),
                    'options'   => array(
                        '1' => array('label' => 'Style 1', 'img' => $this->path.'images/subheader1.png'),
                        '2' => array('label' => 'Style 2', 'img' => $this->path.'images/subheader2.png'),
                        '3' => array('label' => 'Style 3', 'img' => $this->path.'images/subheader3.png'),
                        '4' => array('label' => 'Style 4', 'img' => $this->path.'images/subheader4.png'),
                    )
                ));
                echo $megapanel_options->end_options();
                
                echo $megapanel_options->start_options('Boxed');
                $megapanel_options->field_options_item(array(
                    'name'      => 'Boxed',
                    'id'        => 'boxed',
                    'type'      => 'checkbox',
                    'value'     => $this->get_theme_option('boxed'),
                ));
                $megapanel_options->field_options_item(array(
                    'name'  => 'Boxed Margin Top',
                    'id'    => 'boxed_margintop',
                    'type'  => 'slider_number',
                    'value' => ($this->get_theme_option('boxed_margintop'))? $this->get_theme_option('boxed_margintop') : 0,
                    'min'	=> 0,
                    'max'	=> 100,
                    'step'  => 1,
                ));
                $megapanel_options->field_options_item(array(
                    'name'  => 'Boxed Margin Bottom',
                    'id'    => 'boxed_marginbottom',
                    'type'  => 'slider_number',
                    'value' => ($this->get_theme_option('boxed_marginbottom'))? $this->get_theme_option('boxed_marginbottom') : 0,
                    'min'	=> 0,
                    'max'	=> 100,
                    'step'  => 1,
                ));
                $megapanel_options->field_options_item(array(
                    'name'  => 'Background attachment',
                    'id'    => 'boxed_attachment',
                    'type'      => 'radio',
                    'value'     => $this->get_theme_option('boxed_attachment'),
                    'options'   => array(
                        'scroll'   => 'scroll',
                        'fixed'    => 'fixed',
                        'local'    => 'local',
                        'initial'  => 'initial',
                        'inherit'  => 'inherit',
                    )
                ));
                $megapanel_options->field_options_item(array(
                    'name'  => 'Background repeat',
                    'id'    => 'boxed_repeat',
                    'type'      => 'radio',
                    'value'     => $this->get_theme_option('boxed_repeat'),
                    'options'   => array(
                        'repeat'    => 'repeat',
                        'no-repeat' => 'no-repeat',
                        'repeat-x'  => 'repeat-x',
                        'repeat-y'  => 'repeat-y',
                    )
                ));
                $megapanel_options->field_options_item(array(
                    'name'  => 'Background Size',
                    'id'    => 'boxed_bgsize',
                    'type'      => 'radio',
                    'value'     => $this->get_theme_option('boxed_bgsize'),
                    'options'   => array(
                        'auto'    => 'auto',
                        'length'  => 'length',
                        'cover'   => 'cover',
                        'contain' => 'contain',
                        'initial' => 'initial',
                        'inherit' => 'inherit',
                    )
                ));
                $megapanel_options->field_options_item(array(
                    'name'      => 'Boxed Background',
                    'id'        => 'boxed_bgtype',
                    'type'      => 'checkbox',
                    'value'     => $this->get_theme_option('boxed_bgtype'),
                    'ontext'    => 'Image',
                    'offtext'   => 'Pattern',
                ));
                $megapanel_options->field_options_item(array(
                    'name'  => 'Boxed Background Image',
                    'id'    => 'boxed_background',
                    'type'  => 'upload',
                    'value' => $this->get_theme_option('boxed_background'),
                    'src'   => 'src',
                ));
                $megapanel_options->field_options_item(array(
                    'name'      => 'Boxed Background pattern',
                    'id'        => 'boxed_pattern',
                    'type'      => 'radio',
                    'value'     => $this->get_theme_option('boxed_pattern'),
                    'options'   => array(
                        '1'  => array('label' => 'Pattern 1', 'img' => $this->path.'images/pattern1.jpg'),
                        '2'  => array('label' => 'Pattern 2', 'img' => $this->path.'images/pattern2.png'),
                        '3'  => array('label' => 'Pattern 3', 'img' => $this->path.'images/pattern3.jpg'),
                        '4'  => array('label' => 'Pattern 4', 'img' => $this->path.'images/pattern4.png'),
                        '5'  => array('label' => 'Pattern 5', 'img' => $this->path.'images/pattern5.png'),
                        '6'  => array('label' => 'Pattern 6', 'img' => $this->path.'images/pattern6.png'),
                        '7'  => array('label' => 'Pattern 7', 'img' => $this->path.'images/pattern7.png'),
                        '8'  => array('label' => 'Pattern 8', 'img' => $this->path.'images/pattern8.png'),
                        '9'  => array('label' => 'Pattern 9', 'img' => $this->path.'images/pattern9.png'),
                        '10' => array('label' => 'Pattern 10', 'img' => $this->path.'images/pattern10.png'),
                        '11' => array('label' => 'Pattern 11', 'img' => $this->path.'images/pattern11.png'),
                        '12' => array('label' => 'Pattern 12', 'img' => $this->path.'images/pattern12.png'),
                        '13' => array('label' => 'Pattern 13', 'img' => $this->path.'images/pattern13.jpg'),
                        '14' => array('label' => 'Pattern 14', 'img' => $this->path.'images/pattern14.png'),
                        '15' => array('label' => 'Pattern 15', 'img' => $this->path.'images/pattern15.gif'),
                        '16' => array('label' => 'Pattern 16', 'img' => $this->path.'images/pattern16.png'),
                        '17' => array('label' => 'Pattern 17', 'img' => $this->path.'images/pattern17.png'),
                        '18' => array('label' => 'Pattern 18', 'img' => $this->path.'images/pattern18.png'),
                        '19' => array('label' => 'Pattern 19', 'img' => $this->path.'images/pattern19.png'),
                        '20' => array('label' => 'Pattern 20', 'img' => $this->path.'images/pattern20.png'),
                        '21' => array('label' => 'Pattern 21', 'img' => $this->path.'images/pattern21.png'),
                    )
                ));
                echo $megapanel_options->end_options();
                echo '</div>';
                /* end options skins */
                /* options socialmedia */
                echo '<div class="megapanel-tab-content option-socialmedia">';
                echo $megapanel_options->start_options('Social media');
                $megapanel_options->field_options_item(array(
                    'name'  => 'Facebook URL',
                    'id'    => 'facebook',
                    'type'  => 'text',
                    'value' => $this->get_theme_option('facebook'),
                    'dir'   => 'ltr',
                ));
                $megapanel_options->field_options_item(array(
                    'name'  => 'Twitter URL',
                    'id'    => 'twitter',
                    'type'  => 'text',
                    'value' => $this->get_theme_option('twitter'),
                    'dir'   => 'ltr',
                ));
                $megapanel_options->field_options_item(array(
                    'name'  => 'Youtube URL',
                    'id'    => 'youtube',
                    'type'  => 'text',
                    'value' => $this->get_theme_option('youtube'),
                    'dir'   => 'ltr',
                ));
                $megapanel_options->field_options_item(array(
                    'name'  => 'Vimeo URL',
                    'id'    => 'vimeo',
                    'type'  => 'text',
                    'value' => $this->get_theme_option('vimeo'),
                    'dir'   => 'ltr',
                ));
                $megapanel_options->field_options_item(array(
                    'name'  => 'Behance URL',
                    'id'    => 'behance',
                    'type'  => 'text',
                    'value' => $this->get_theme_option('behance'),
                    'dir'   => 'ltr',
                ));
                $megapanel_options->field_options_item(array(
                    'name'  => 'Linkedin URL',
                    'id'    => 'linkedin',
                    'type'  => 'text',
                    'value' => $this->get_theme_option('linkedin'),
                    'dir'   => 'ltr',
                ));
                $megapanel_options->field_options_item(array(
                    'name'  => 'Pinterest url',
                    'id'    => 'pinterest',
                    'type'  => 'text',
                    'value' => $this->get_theme_option('pinterest'),
                    'dir'   => 'ltr',
                ));
                $megapanel_options->field_options_item(array(
                    'name'  => 'Instagram url',
                    'id'    => 'instagram',
                    'type'  => 'text',
                    'value' => $this->get_theme_option('instagram'),
                    'dir'   => 'ltr',
                ));
                echo $megapanel_options->end_options();
                echo '</div>';
                /* end options socialmedia */
                
                /* options seo */
                echo '<div class="megapanel-tab-content option-seo">';
                echo $megapanel_options->start_options('SEO');
                $megapanel_options->field_options_item(array(
                    'name'  => 'Meta robots',
                    'id'    => 'metarobots',
                    'type'  => 'text',
                    'value' => $this->get_theme_option('metarobots'),
                    'help'  => 'Example: index, follow'
                ));
                $megapanel_options->field_options_item(array(
                    'name'  => 'Meta Twitter',
                    'id'    => 'metatwitter',
                    'type'  => 'text',
                    'value' => $this->get_theme_option('metatwitter'),
                    'help'  => 'Example: @name'
                ));
                $megapanel_options->field_options_item(array(
                    'name'  => 'Description',
                    'id'    => 'description',
                    'type'  => 'textarea',
                    'value' => $this->get_theme_option('description'),
                ));
                $megapanel_options->field_options_item(array(
                    'name'  => 'Keywords',
                    'id'    => 'keywords',
                    'type'  => 'textarea',
                    'value' => $this->get_theme_option('keywords'),
                    'help'  => 'Chapter each keyword by using the symbol (,)',
                ));
                echo $megapanel_options->end_options();
                echo '</div>';
                /* end options seo */
                
                /* options footer */
                echo '<div class="megapanel-tab-content option-footer">';
                echo $megapanel_options->start_options('Footer');
                $megapanel_options->field_options_item(array(
                    'name'  => 'Copyright',
                    'id'    => 'copyright',
                    'type'  => 'text',
                    'value' => $this->get_theme_option('copyright'),
                ));
                $megapanel_options->field_options_item(array(
                    'name'  => 'Footer Code',
                    'id'    => 'footercode',
                    'type'  => 'textarea_full',
                    'value' => $this->get_theme_option('footercode'),
                    'rows'  => '12',
                    'dir'   => 'ltr',
                ));
                echo $megapanel_options->end_options();
                echo '</div>';
                /* end options footer */
                
                /* options custom */
                echo '<div class="megapanel-tab-content option-custom">';
                echo $megapanel_options->start_options('Custom');
                $megapanel_options->field_options_item(array(
                    'name'  => 'Custom CSS',
                    'id'    => 'customcss',
                    'type'  => 'textarea_full',
                    'value' => $this->get_theme_option('customcss'),
                    'rows'  => '12',
                    'dir'   => 'ltr',
                    
                ));
                $megapanel_options->field_options_item(array(
                    'name'  => 'Custom JS',
                    'id'    => 'customjs',
                    'type'  => 'textarea_full',
                    'value' => $this->get_theme_option('customjs'),
                    'rows'  => '12',
                    'dir'   => 'ltr',
                ));
                echo $megapanel_options->end_options();
                echo '</div>';
                /* end options custom */
                

                /* options banner */
                echo '<div class="megapanel-tab-content option-banner">';
                echo $megapanel_options->start_options('Banner Top');
                $megapanel_options->field_options_item(array(
                    'name'  => 'Banner Top',
                    'id'    => 'bannertop',
                    'type'  => 'textarea_full',
                    'value' => $this->get_theme_option('bannertop'),
                    'rows'  => '8',
                    'dir'   => 'ltr',
                    
                ));
                echo $megapanel_options->end_options();
                echo $megapanel_options->start_options('Banner Widget');
                $megapanel_options->field_options_item(array(
                    'name'  => 'Banner Widget',
                    'id'    => 'bannerwidget',
                    'type'  => 'textarea_full',
                    'value' => $this->get_theme_option('bannerwidget'),
                    'rows'  => '8',
                    'dir'   => 'ltr',
                    
                ));
                echo $megapanel_options->end_options();
                echo $megapanel_options->start_options('Banner Bottom');
                $megapanel_options->field_options_item(array(
                    'name'  => 'Banner Bottom',
                    'id'    => 'bannerbottom',
                    'type'  => 'textarea_full',
                    'value' => $this->get_theme_option('bannerbottom'),
                    'rows'  => '8',
                    'dir'   => 'ltr',
                    
                ));
                echo $megapanel_options->end_options();
                echo '</div>';
                /* end options banner */

                /* options cookie */
                echo '<div class="megapanel-tab-content option-cookie">';
                echo $megapanel_options->start_options('cookie');
                $megapanel_options->field_options_item(array(
                    'name'      => 'Show Cookie Popup',
                    'id'        => 'cookie_status',
                    'type'      => 'checkbox',
                    'value'     => $this->get_theme_option('cookie_status'),
                ));
                $megapanel_options->field_options_item(array(
                    'name'      => 'Cookie ID',
                    'id'        => 'cookie_id',
                    'type'      => 'text',
                    'value'     => $this->get_theme_option('cookie_id'),
                    'desc'      => 'ex: namewebsite_cookie - Default: phphelpm4cookie'
                ));
                $megapanel_options->field_options_item(array(
                    'name'      => 'Position',
                    'id'        => 'cookie_position',
                    'type'      => 'radio',
                    'value'     => $this->get_theme_option('cookie_position'),
                    'options'   => array(
                        'left'      => 'Left',
                        'right'     => 'Right',
                    )
                ));
                $megapanel_options->field_options_item(array(
                    'name'  => 'Cookie image',
                    'id'    => 'cookie_image',
                    'type'  => 'upload',
                    'value' => $this->get_theme_option('cookie_image'),
                    'src'   => 'src',
                    'desc'  => 'Default: <br /><img src="'.$config['siteurl'].'/themes/default/assets/images/cookie.svg" style="width: 48px" />',
                ));
                $megapanel_options->field_options_item(array(
                    'name'  => 'Title',
                    'id'    => 'cookie_title',
                    'type'  => 'text',
                    'value' => $this->get_theme_option('cookie_title'),
                ));
                $megapanel_options->field_options_item(array(
                    'name'  => 'Text',
                    'id'    => 'cookie_desc',
                    'type'  => 'textarea',
                    'value' => $this->get_theme_option('cookie_desc'),
                    'rows'  => '5'
                ));
                $megapanel_options->field_options_item(array(
                    'name'  => 'Text Button Decline',
                    'id'    => 'cookie_decline',
                    'type'  => 'text',
                    'value' => $this->get_theme_option('cookie_decline'),
                ));
                $megapanel_options->field_options_item(array(
                    'name'  => 'Text Button Consent',
                    'id'    => 'cookie_consent',
                    'type'  => 'text',
                    'value' => $this->get_theme_option('cookie_consent'),
                ));
                echo $megapanel_options->end_options();
                echo '</div>';
                /* end options cookie */



                echo '
                <footer class="megapanel-page-footer megapanel-submit"><button class="button button-primary"><i class="fa fa-save"></i> '.get_admin_languages('save_changes').'</button></footer>
                </div>
                </div>
                </div>
                </div>
                </div>
                </form>
                </div>
                </div>
                </div>';
                echo admin_content_section_end();
                admin_footer(); 
                exit;             
            }
        }
    }
    new theme_options();
}
?>
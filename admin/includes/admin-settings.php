<?php
//----------------------------------------------------------------------|
/***********************************************************************|
 * Project:     UNHCR IM Resource Page                                       |
//----------------------------------------------------------------------|
 * @link http://nawaaugustine.com                                         |
 * @copyright 2020.                                                     |
 * @author Augustine Nawa <ocjpnawa@gmail.com>                   |
 * @package UNHCR IM Resource Page                                           |
 * @version 4.5                                                         |
//----------------------------------------------------------------------|
************************************************************************/
//----------------------------------------------------------------------|
require_once('./admin-common.php');
if(!defined('IN_PHPMEGATEMP_CP')) exit();

class admin_settings
{
    
    public function __construct()
    {
        global $hooks;
        $hooks->add_action('admin_head', array($this, 'admin_settings_print_style'), 2);
        $hooks->add_action('admin_supports_enqueue_script', array($this, 'admin_settings_print_script'), 3);
    }
    
    public function admin_settings_print_style()
    {
        
    }
    
    public function admin_settings_print_script()
    {
        if(isset($_REQUEST['mode']) and $_REQUEST['mode'] == 'menus')
        {
			echo '<script type="text/javascript" src="assets/libs/jquery/jquery.tree.js"></script>
			<script type="text/javascript" src="assets/libs/admin/menus.js"></script>
			';
        }
    }
    
    public function index_general()
    {
        global $token, $db, $config;
        $megapanel_options = new admin_megapanel_options();
        if(isset($_POST['action']) and $_POST['action'] == 'savesetting')
        {
            $token      = safe_input($_POST['token']);
            if($token == $_SESSION['securitytokenadmincp']):
                set_config('sitename', safe_input($_POST['sitename']));
                set_config('description', safe_input($_POST['description']));
                set_config('siteurl', trim(safe_input($_POST['siteurl']), '/'));
                set_config('sitemail', safe_input($_POST['sitemail']));
                set_config('language', safe_input($_POST['language']));
                set_config('per_page', safe_input($_POST['per_page']));
                set_config('per_popular', safe_input($_POST['per_popular']));
                set_config('sitekey', safe_input($_POST['sitekey']));
                set_config('sitedesc', safe_input($_POST['sitedesc']));
                set_config('timezone_string', safe_input($_POST['timezone_string']));
                set_config('dateformat', safe_input($_POST['dateformat']));
                set_config('timeformat', safe_input($_POST['timeformat']));
                set_config('logomailer', safe_input($_POST['logomailer']));
                set_config('registration_status', safe_input($_POST['registration_status']));
                set_config('registration_activation', safe_input($_POST['registration_activation']));
                set_config('gzip_compress', safe_input($_POST['gzip_compress']));
                set_config('maintenance_status', safe_input($_POST['maintenance_status']));
                set_config('maintenance_title', safe_input($_POST['maintenance_title']));
                set_config('maintenance_headline', safe_input($_POST['maintenance_headline']));
                set_config('maintenance_desc', safe_input($_POST['maintenance_desc']));
                set_config('maintenance_bgimg', safe_input($_POST['maintenance_bgimg']));
                set_config('maintenance_timer', safe_input($_POST['maintenance_timer']));
                set_config('maintenance_year', safe_input($_POST['maintenance_year']));
                set_config('maintenance_month', safe_input($_POST['maintenance_month']));
                set_config('maintenance_day', safe_input($_POST['maintenance_day']));
                set_config('maintenance_msgend', safe_input($_POST['maintenance_msgend']));
                set_config('lang_mailer', safe_input($_POST['lang_mailer']));
                set_config('mailhost', safe_input($_POST['mailhost']));
                set_config('mailport', safe_input($_POST['mailport']));
                set_config('mailencryption', safe_input($_POST['mailencryption']));
                set_config('mailusername', safe_input($_POST['mailusername']));
                set_config('mailpassword', safe_input($_POST['mailpassword']));
            endif;
            @header("Location:settings.php?mode=general");
            exit;
        }
        if(isset($_SESSION['action_token'])):
            $message = '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'.$_SESSION['action_token'].'</div>';
            unset($_SESSION['action_token']);
        else:
            $message = '';
        endif;
        
        admin_header(get_admin_languages('general_settings'));
        echo admin_content_section_start().'<div class="col-md-12">'.$message.'
            <div class="megapanel-panel">
            <div class="panel-wrapper">
            <form action="settings.php?mode=general" method="post" name="form" class="form-horizontal checkbox_form">
            <input type="hidden" name="action" value="savesetting" />
            <input type="hidden" name="token" value="'.$token.'" />
            <div class="megapanel-main">
            <div id="megapanel-editor" class="megapanel-jqcheckbox">
            <div class="megapanel-wrapper">
            <header class="megapanel-page-header">
            <h1>'.get_admin_languages('general_settings').'</h1>
            <div class="megapanel-submit"><button type="submit" class="button button-primary"><i class="fa fa-save"></i> '.get_admin_languages('save_changes').'</button></div>
            </header>
            <div class="megapanel-tabs-container megapanel-container">
            <div class="megapanel-tabs nav-tabs-cookie" data-cookie="generalsettings">
                <a href="#" class="active" data-tab=".option-general"><i class="fas fa-sliders-h"></i> '.get_admin_languages('general_settings').'</a>
                <a href="#" class="active" data-tab=".option-mailsmtp"><i class="fas fa-envelope-open-text"></i> Mail SMTP</a>
                <a href="#" class="" data-tab=".option-maintenance"><i class="fas fa-toolbox"></i> '.get_admin_languages('maintenance').'</a>
            </div>
            <div class="megapanel-tabs-content">
            ';
            echo '<div class="megapanel-tab-content option-general active">';
            $megapanel_options->field_options_item(array(
                'name'  => get_admin_languages('site_title'),
                'id'    => 'sitename',
                'type'  => 'text',
                'value' => $config['sitename'],
            ));
            $megapanel_options->field_options_item(array(
                'name'  => get_admin_languages('tagline'),
                'id'    => 'description',
                'type'  => 'text',
                'value' => $config['description'],
            ));
            $megapanel_options->field_options_item(array(
                'name'  => get_admin_languages('site_address_url'),
                'id'    => 'siteurl',
                'type'  => 'text',
                'value' => $config['siteurl'],
            ));
            
            $megapanel_options->field_options_item(array(
                'name'  => get_admin_languages('email_address'),
                'id'    => 'sitemail',
                'type'  => 'text',
                'value' => $config['sitemail'],
            ));
            
            $megapanel_options->field_options_item(array(
                'name'          => get_admin_languages('language_mailer'),
                'id'            => 'lang_mailer',
                'type'          => 'select',
                'value'         => $config['lang_mailer'],
                'options'       => array('en' => 'English', 'ar' => 'العربية'),
                'class'         => 'width320',
            ));
            $megapanel_options->field_options_item(array(
                'name'  => get_admin_languages('logomailer'),
                'id'    => 'logomailer',
                'type'  => 'upload',
                'value' => $config['logomailer'],
                'src'   => 'src',
            ));                        
            
            $megapanel_options->field_options_item(array(
                'name'  => get_admin_languages('site_wide_meta_keywords'),
                'id'    => 'sitekey',
                'type'  => 'text',
                'value' => $config['sitekey'],
            ));
            
            $megapanel_options->field_options_item(array(
                'name'  => get_admin_languages('site_wide_meta_description'),
                'id'    => 'sitedesc',
                'type'  => 'text',
                'value' => $config['sitedesc'],
            ));
        
            
        
            $megapanel_options->field_options_item(array(
                'name'          => get_admin_languages('language'),
                'id'            => 'language',
                'type'          => 'select',
                'value'         => $config['language'],
                'options_html'  => $this->get_language_options($config['language']),
                'class'         => 'width320',
            ));
            
            $megapanel_options->field_options_item(array(
                'name'          => get_admin_languages('timezone'),
                'id'            => 'timezone_string',
                'type'          => 'select',
                'value'         => $config['timezone_string'],
                'options_html'  => timezone_list($config['timezone_string']),
                'class'         => 'width320',
            ));
            
            $megapanel_options->field_options_item(array(
                'name'  => get_admin_languages('date_format'),
                'id'    => 'dateformat',
                'type'  => 'text',
                'value' => $config['dateformat'],
                'class' => 'dateformat width120 text-center',
            ));
            
            $megapanel_options->field_options_item(array(
                'name'  => get_admin_languages('time_format'),
                'id'    => 'timeformat',
                'type'  => 'text',
                'value' => $config['timeformat'],
                'class' => 'timeformat width120 text-center',
            ));
            
            $megapanel_options->field_options_item(array(
                'name'  => get_admin_languages('anyone_can_register'),
                'id'    => 'registration_status',
                'type'  => 'checkbox',
                'value' => $config['registration_status'],
            ));
            
            $megapanel_options->field_options_item(array(
                'name'  => get_admin_languages('registration_activation'),
                'id'    => 'registration_activation',
                'type'  => 'checkbox',
                'value' => $config['registration_activation'],
            ));
        
            $megapanel_options->field_options_item(array(
                'name'  => get_admin_languages('gzip_compress'),
                'id'    => 'gzip_compress',
                'type'  => 'checkbox',
                'value' => $config['gzip_compress'],
            ));
            echo '</div>';            
            
            
            echo '<div class="megapanel-tab-content option-mailsmtp">';
        
            $mailhost       = (isset($config['mailhost']))? $config['mailhost'] : '';
            $mailport       = (isset($config['mailport']))? $config['mailport'] : '';
            $mailencryption = (isset($config['mailencryption']))? $config['mailencryption'] : '';
            $mailusername   = (isset($config['mailusername']))? $config['mailusername'] : '';
            $mailpassword   = (isset($config['mailpassword']))? $config['mailpassword'] : '';
        
            $megapanel_options->field_options_item(array(
                'name'  => 'Mail Host',
                'id'    => 'mailhost',
                'type'  => 'text',
                'value' => $mailhost,
            ));
            $megapanel_options->field_options_item(array(
                'name'  => 'Mail Port',
                'id'    => 'mailport',
                'type'  => 'text',
                'value' => $mailport,
                'help'  => '`25` or `465` or `587`'
            ));
            $megapanel_options->field_options_item(array(
                'name'  => 'Mail Encryption',
                'id'    => 'mailencryption',
                'type'  => 'text',
                'value' => $mailencryption,
                'help'  => '`tls` or `ssl`'
            ));
            $megapanel_options->field_options_item(array(
                'name'  => 'Mail Username',
                'id'    => 'mailusername',
                'type'  => 'text',
                'value' => $mailusername,
            ));
            $megapanel_options->field_options_item(array(
                'name'  => 'Mail Password',
                'id'    => 'mailpassword',
                'type'  => 'text',
                'value' => $mailpassword,
            ));
            echo '</div>';            
            
            
            echo '<div class="megapanel-tab-content option-maintenance">';
            $maintenance_status     = (isset($config['maintenance_status']))? $config['maintenance_status'] : 0;
            $maintenance_title      = (isset($config['maintenance_title']))? $config['maintenance_title'] : '';
            $maintenance_headline   = (isset($config['maintenance_headline']))? $config['maintenance_headline'] : '';
            $maintenance_desc       = (isset($config['maintenance_desc']))? $config['maintenance_desc'] : '';
            $maintenance_bgimg      = (isset($config['maintenance_bgimg']))? $config['maintenance_bgimg'] : '';
            $maintenance_timer      = (isset($config['maintenance_timer']))? $config['maintenance_timer'] : '0';
            $maintenance_year       = (isset($config['maintenance_year']))? $config['maintenance_year'] : date('Y');
            $maintenance_month      = (isset($config['maintenance_month']))? $config['maintenance_month'] : date('m') + 1;
            $maintenance_day        = (isset($config['maintenance_day']))? $config['maintenance_day'] : date('d');
            $maintenance_msgend     = (isset($config['maintenance_msgend']))? $config['maintenance_msgend'] : 'Message After Time End';

            

            $megapanel_options->field_options_item(array(
                'name'  => get_admin_languages('maintenance'),
                'id'    => 'maintenance_status',
                'type'  => 'checkbox',
                'value' => $maintenance_status,
            ));
            $megapanel_options->field_options_item(array(
                'name'  => get_admin_languages('page_title'),
                'id'    => 'maintenance_title',
                'type'  => 'text',
                'value' => $maintenance_title,
            ));
            $megapanel_options->field_options_item(array(
                'name'  => get_admin_languages('headline'),
                'id'    => 'maintenance_headline',
                'type'  => 'text',
                'value' => $maintenance_headline,
            ));
            $megapanel_options->field_options_item(array(
                'name'  => get_admin_languages('description	'),
                'id'    => 'maintenance_desc',
                'type'  => 'textarea',
                'value' => $maintenance_desc,
            ));
            $megapanel_options->field_options_item(array(
                'name'  => get_admin_languages('background_image'),
                'id'    => 'maintenance_bgimg',
                'type'  => 'upload',
                'value' => $maintenance_bgimg,
                'src'   => 'src',
            ));             
            
            $megapanel_options->field_options_item(array(
                'name'  => get_admin_languages('maintenance_timer'),
                'id'    => 'maintenance_timer',
                'type'  => 'checkbox',
                'value' => $maintenance_timer,
            ));
            $megapanel_options->field_options_item(array(
                'name'  => get_admin_languages('maintenance_year'),
                'id'    => 'maintenance_year',
                'type'  => 'text',
                'value' => $maintenance_year,
            ));
            $megapanel_options->field_options_item(array(
                'name'  => get_admin_languages('maintenance_month'),
                'id'    => 'maintenance_month',
                'type'  => 'text',
                'value' => $maintenance_month,
            ));
            $megapanel_options->field_options_item(array(
                'name'  => get_admin_languages('maintenance_day'),
                'id'    => 'maintenance_day',
                'type'  => 'text',
                'value' => $maintenance_day,
            ));
            $megapanel_options->field_options_item(array(
                'name'  => get_admin_languages('message_after_time_end'),
                'id'    => 'maintenance_msgend',
                'type'  => 'text',
                'value' => $maintenance_msgend,
            ));
            
            echo '</div>';
            echo '
            <footer class="megapanel-page-footer megapanel-submit"><button type="submit" class="button button-primary"><i class="fa fa-save"></i> '.get_admin_languages('save_changes').'</button></footer>
            </div>
            </div>
            </div>
            </div>
            </div>
            </form>
            </div>
            </div>
            </div>
            ';
        echo admin_content_section_end();
        admin_footer();
    }
    
    public function index_extensions()
    {
        global $hooks, $config, $token;
        $megapanel_options = new admin_megapanel_options();
            if(isset($_POST['action']) and $_POST['action'] == 'savesetting')
            {
                $token = safe_input($_POST['token']);
                if($token == $_SESSION['securitytokenadmincp'])
                {
                    $hooks->do_action('add_admin_extensions_options_update');
                    $_SESSION['action_token'] = get_admin_languages('saved_successfully');
                }
                @header("Location:settings.php?mode=extensions");
                exit;
            }

            if(isset($_SESSION['action_token'])):
                $message = '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'.$_SESSION['action_token'].'</div>';
                unset($_SESSION['action_token']);
            else:
                $message = '';
            endif;
            
            $menus = array();
            if($hooks->has_filter('add_admin_extensions_tabs')):
                $extensions_tabs = $hooks->apply_filters( 'add_admin_extensions_tabs' , $menus);
            else:
                $extensions_tabs = $menus;
            endif;

            $html_tabs = '';
            foreach($extensions_tabs as $key => $value)
            {
                $html_tabs .= '<a href="#" class="" data-tab=".extensions-'.$value['id'].'"><i class="'.$value['icon'].'"></i> '.$value['title'].'</a>';
            }

            admin_header(get_admin_languages('extensions_settings'));
            echo admin_content_section_start().'<div class="col-md-12">'.$message.'
            <div class="megapanel-panel">
            <div class="panel-wrapper">
            <form action="settings.php?mode=extensions" method="post" name="form" class="form-horizontal">
            <input type="hidden" name="action" value="savesetting" />
            <input type="hidden" name="token" value="'.$token.'" />
            <div class="megapanel-main">
            <div id="megapanel-editor" class="megapanel-jqcheckbox">
            <div class="megapanel-wrapper">
            <header class="megapanel-page-header">
            <h1>'.get_admin_languages('extensions_settings').'</h1>
            <div class="megapanel-submit"><button type="submit" class="button button-primary"><i class="fa fa-save"></i> '.get_admin_languages('save_changes').'</button></div>
            </header>
            <div class="megapanel-tabs-container megapanel-container">
            <div class="megapanel-tabs nav-tabs-cookie" data-cookie="extensionsptions">'.$html_tabs.'</div>
            <div class="megapanel-tabs-content">
            ';
            $content = array();
            if($hooks->has_filter('add_admin_extensions_display')):
                $extensions_content = $hooks->apply_filters( 'add_admin_extensions_display' , $content);
            else:
                $extensions_content = $content;
            endif;
            foreach($extensions_content as $key => $value)
            {                    
                echo '<div class="megapanel-tab-content extensions-'.$key.'">';
                foreach($value as $k => $v)
                {
                    echo $megapanel_options->start_options($v['title']);
                    foreach($v['options'] as $option)
                    {
                        if(is_array($option))
                        {
                            $megapanel_options->field_options_item($option);
                        }
                    }
                    echo $megapanel_options->end_options();
                }
                echo '</div>';
            }
            echo '
            <footer class="megapanel-page-footer megapanel-submit"><button type="submit" class="button button-primary"><i class="fa fa-save"></i> '.get_admin_languages('save_changes').'</button></footer>
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
    }
      
    public function get_language_options($id = false)
    {
        global $db, $template;
        $option = '';
        $result = $db->sql_query("SELECT id,name FROM ".LANGUAGE_TABLE." WHERE `status`='1'");
        while ($row = $db->sql_fetchrow($result)) 
        {
            $selected = ($id == $row['id'])? 'selected="selected"' : '' ;
            $option .= '<option value="'.$row['id'].'" '.$selected.'>'.$row['name'].'</option>';
        }
        $db->sql_freeresult($result);
        return $option;
    }
    
    public function get_option_pages($id = '')
    {
        global $db;
        $result     = $db->sql_query("SELECT * FROM ".POSTS_TABLE." WHERE `post_type`='pages' ORDER BY id ASC");
        $option = '';
        while ($row = $db->sql_fetchrow($result)) 
        {
            if($id == $row['id'])
            {
                $sel = 'selected="selected"';
            }
            else
            {
                $sel = '';
            }
            $option .= '<option value="'.$row['id'].'" '.$sel.'>'.$row['post_title'].'</option>';
        }
        return $option;
    }
    
    public function getTemplates($dir, $site)
    {
      $getDir = dir($dir);
      $html = '';
      while (false !== ($templDir = $getDir->read())) {
          if ($templDir != "." && $templDir != ".." && $templDir != "index.php") {
              $selected = ($site == $templDir) ? " selected=\"selected\"" : "";
              $html .= "<option value=\"{$templDir}\"{$selected}>{$templDir}</option>\n";
          }
      }
      return $html;
      $getDir->close();
    }
    
    public function defaultcat($id = '',$catetype ='news')
    {
        global $db;
        $result = $db->sql_query("SELECT * FROM ".TERMS_TABLE." WHERE type='".$catetype."' ORDER BY id ASC");
        $html  = '';
        if ($id == false)
        {
            $html .= '<option value="0" selected="selected">none</option>';
        }
        else
        {
            $html .= '<option value="0" >none</option>';
        }   
        while( $row = $db->sql_fetchrow($result) )
        {   
            if (!is_null($id) && $row['id'] == $id)
            {
                $sel = ' selected="selected" ';
            }
            else{
                $sel = '';
            }
    		$html .= '<option  value="'.$row['id'].'" '.$sel.'>'.$row['name'].'</option>';
        }
        return $html;
    }
    
    public function get_themes($dir, $site)
    {
      global $config,$token;
      $getDir = dir($dir);
      $html = '';
      while (false !== ($templDir = $getDir->read())) {
          if ($templDir != "." && $templDir != ".." && $templDir != "index.php" && $templDir != "index.html") {
              $selected = ($site == $templDir) ?  1 : 0 ;
              $name = str_replace("_"," ",$templDir);
              $name = str_replace("-"," ",$name);
              $html .= '<li>';
              $html .= '<img src="../themes/'.$templDir.'/screenshot.png" class="" title="'.$name.'">';
              if($selected)
              {
                $html .= '
                <div class="theme-actions activate">
            		<strong>'.get_admin_languages('activate').'</strong>: '.$name.'
                ';
                $optionscolors;
                if(file_exists('../themes/'.$templDir.'/info_theme.php'))
                {
                    include('../themes/'.$templDir.'/info_theme.php');
                    $optionscolors = '<select name="themecolor" style="float: right;width: 100px;margin-top: -5px">';
                    foreach($arraycolor as $key => $value)
                    {
                        //
                        if(($config['themecolor'] == $key))
                        {
                            $optionscolors .= '<option value="'.$key.'" selected="selected">'.$value.'</option>';
                        }
                        else
                        {
                            $optionscolors .= '<option value="'.$key.'">'.$value.'</option>';
                        }
                            
                    }
                    $optionscolors .= '</select>';
                    
                    $html .= $optionscolors;
                }
                $html .= '</div>';
              }
              else
              {
                $html .= '
                <div class="theme-actions ">
            		<a class="button button-primary btn-small activate" href="themes.php?action=activate&themes='.$templDir.'&token='.$token.'">'.get_admin_languages('activate').'</a>
                    <p>'.$name.'</p>
            	</div>
                ';
              }
              $html .= '</li>';
              
          }
      }
      return $html;
      $getDir->close();
    }
    
    public function index_themes()
    {
        global $token, $db, $config;
        if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'activate' && isset($_REQUEST['themes']))
        {
            if($_REQUEST['token'] == $_SESSION['securitytokenadmincp'])
            {
                set_config('sitethemes',trim($_REQUEST['themes']));
                $_SESSION['action_token'] = get_admin_languages('theme_activated_successfully');
            }
            @header("Location:themes.php");
            exit;
        }
        if(isset($_SESSION['action_token'])):
            $message = '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'.$_SESSION['action_token'].'</div>';
            unset($_SESSION['action_token']);
        else:
            $message = '';
        endif;

        admin_header(get_admin_languages('themes'));
        admin_content_header(array('title' => get_admin_languages('themes'), 
            'linkmore' => array('title' => get_admin_languages('add_themes'), 'link' => 'http://nawaaugustine.com/php-help-manager/themes')));
        echo admin_content_section_start().'
        <div class="col-md-12">
        '.$message.'<ol class="box_layout_list">';
        echo $this->get_themes('../themes/',$config['sitethemes']);
        echo '
        <div class="clearfix"></div>
        </ol><div class="clearfix"></div></div>';
        echo admin_content_section_end();
        admin_footer();
    }
    

    public function set_menu_save()
    {
		global $config;
        if(isset($_POST['action']) and $_POST['action'] == 'menu-save')
        {
            $token = safe_input($_POST['token']);
            if($token == $_SESSION['securitytokenadmincp']):
				$main_menu  = [];
				$list_array = json_decode($_POST['menu_item_map']);
				$x          = 1;
				$newkey     = 1;
				foreach($list_array as $key => $value)
				{
					if($value == 'null')
					{
						$main_menu[$key] = array(
							'title'     => safe_input($_POST['title'][$key]), 
							'url'       => trim(safe_input($_POST['url'][$key]), '/'),
							'itemtype'  => safe_input($_POST['itemtype'][$key]), 
							'icon'      => (isset($_POST['icon'][$key]))? safe_input($_POST['icon'][$key]) : '', 
							'image'     => (isset($_POST['image'][$key]))? safe_input($_POST['image'][$key]) : '', 
							'classes'   => (isset($_POST['classes'][$key]))? safe_input($_POST['classes'][$key]) : '',
							'target'    => (isset($_POST['target'][$key]))? safe_input($_POST['target'][$key]) : '',
							'useronly'  => (isset($_POST['useronly'][$key]))? safe_input($_POST['useronly'][$key]) : '',
							'menuopen'  => (isset($_POST['menuopen'][$key]))? safe_input($_POST['menuopen'][$key]) : '',
							'newkey'  	=> $newkey,
                            'submenu'	=> [],
						);
					}
					else
					{
						$main_submenu = array(
							'title'     => safe_input($_POST['title'][$key]), 
							'url'       => trim(safe_input($_POST['url'][$key]), '/'),
							'itemtype'  => safe_input($_POST['itemtype'][$key]), 
							'icon'      => (isset($_POST['icon'][$key]))? safe_input($_POST['icon'][$key]) : '', 
							'image'     => (isset($_POST['image'][$key]))? safe_input($_POST['image'][$key]) : '', 
							'classes'   => (isset($_POST['classes'][$key]))? safe_input($_POST['classes'][$key]) : '',
							'target'    => (isset($_POST['target'][$key]))? safe_input($_POST['target'][$key]) : '',
							'useronly'  => (isset($_POST['useronly'][$key]))? safe_input($_POST['useronly'][$key]) : '',
							'menuopen'  => (isset($_POST['menuopen'][$key]))? safe_input($_POST['menuopen'][$key]) : '',
							'newkey'  	=> $newkey,
							'value'  	=> $value,
						);
                        array_push($main_menu[$value]['submenu'], $main_submenu);
					}
					$newkey++;
				}
				set_config('nav_main_menu', maybe_serialize($main_menu));
				$_SESSION['action_token'] = get_admin_languages('saved_successfully');
            endif;
            @header("Location:themes.php?mode=menus");
            exit;
        }
    }
    
    public function get_menu_item_li($args = array())
    {
        return '
        <li>
            <label class="menu-item-title">
            <input type="checkbox" class="menu-item-checkbox" name="menu-item" data-url="'.$args['url'].'" data-title="'.$args['title'].'" data-icon="'.$args['icon'].'"> 
            <span class="lbl">'.$args['title'].'</span> 
            </label>
        </li>
        ';  
    }
    
    public function get_nav_menu_side($args = array())
    {
        global $token, $db, $config, $hooks;
        $html = '
        <li class="control-section accordion-section" id="accordion-section-add-side-'.$args['btn_data'].'">
        <h3 class="accordion-section-title hndle">'.$args['title'].'<span class="screen-reader-text"></span></h3>
        <div class="accordion-section-content">
        <div class="section-content-checkbox">
            <ul>'.$args['html_list'].'</ul>
        </div>
        <p class="button-controls clearfix">
			<span class="add-to-menu">
                <button type="button" id="button-side-page" class="button submit-add-to-menu" data-prifx="side-'.$args['btn_data'].'" data-type="'.$args['btn_data'].'">'.get_admin_languages('add_to_menu').'</button>
				<span class="spinner"></span>
			</span>
		</p>
        </div></li>';
        return $html;
    }
	
	public function get_nav_menu_side_custom_links()
    {
        $html = '
        <li class="control-section accordion-section" id="accordion-section-add-side-custom_links">
        <h3 class="accordion-section-title hndle">Custom Links<span class="screen-reader-text"></span></h3>
        <div class="accordion-section-content">
        <div class="section-content-checkbox">
            <ul>
				<li>
					<label class="menu-item-title">
					URL <br />
					<input type="text" class="form-control input-sm" name="custom_links_url" id="custom_links_url"> 
					</label>
					<br />
					<label class="menu-item-title">
					Link Text <br />
					<input type="text" class="form-control input-sm" name="custom_links_title" id="custom_links_title"> 
					</label>
				</li>
			</ul>
        </div>
        <p class="button-controls clearfix">
			<span class="add-to-menu">
                <button type="button" id="button-side-custom_links" class="button submit-add-custom-link-to-menu" data-prifx="side-custom_links" data-type="Custom Links">'.get_admin_languages('add_to_menu').'</button>
				<span class="spinner"></span>
			</span>
		</p>
        </div></li>';
        
        return $html;
    }
    
    public function index_menus()
    {
        global $token, $db, $config, $hooks;
        $this->set_menu_save();
        if(isset($_SESSION['action_token'])):
            $message = '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'.$_SESSION['action_token'].'</div>';
            unset($_SESSION['action_token']);
        else:
            $message = '';
        endif;
        $admin_main_menu = maybe_unserialize($config['nav_main_menu']);
        if(is_array($admin_main_menu)):
            $main_menu = $admin_main_menu;
            $main_menu = @array_combine(range(1, count($main_menu)), array_values($main_menu));
        else:
            $main_menu = array();
        endif;
        $main_menu_html = '';
		$menu_item_count = 0;
        if(is_array($main_menu))
        {
            foreach($main_menu as $key => $value)
            {
                $menu_item_count++;
                $value['newkey'] = (isset($value['newkey']))? $value['newkey'] : '';
                $menuopen 		 = (isset($value['menuopen']) and $value['menuopen'])? 'menu-open' : '';
                $main_menu_html .= '<li class="menu-item '.$menuopen.'" id="menu-item-'.$value['newkey'].'" data-id="'.$value['newkey'].'">';
                if(isset($value['itemtype']) and $value['itemtype'] == 'Custom_Link')
                {
                    $main_menu_html .= get_nav_menu_custom_link_list($value['newkey'], $value, false);
                }
                else
                {
                    $main_menu_html .= get_loop_nav_menu_list($value['newkey'], $value, false);
                }
                if(isset($value['submenu']) and is_array($value['submenu']))
                {
                    $subls = 0;
                    $main_menu_html .= '<ul>';
                    foreach($value['submenu'] as $subkey => $subvalue)
                    {
                        $subls++;
                        $menu_item_count++;
                        if($subvalue['itemtype'] == 'Custom_Link')
                        {
                            $main_menu_html .= get_nav_menu_custom_link_list($subvalue['newkey'], $subvalue);
                        }
                        else
                        {
                            $main_menu_html .= get_loop_nav_menu_list($subvalue['newkey'], $subvalue);
                        }
                    }
                    $main_menu_html .= '</ul>';
                }
                $main_menu_html .= '</li>';
            }
        }
        
        admin_header(get_admin_languages('menus'));
        admin_content_header(array('title' => get_admin_languages('menus')));
        echo admin_content_section_start().'<div class="col-md-12">'.$message.'</div>';
        echo '
        <div class="col-lg-3 col-md-4" id="side-sortables">
        <div class="nav-menu-side-control-section accordion-container" id="side-sortables">
        <ul class="outer-border">';
        $hooks->do_action('admin_nav_menu_side_control_section');
		echo $this->get_nav_menu_side_custom_links();
        echo '
        <li class="control-section accordion-section" id="accordion-section-add-extension">
        <h3 class="accordion-section-title hndle">'.get_admin_languages('extension_url').'<span class="screen-reader-text"></span></h3>
        <div class="accordion-section-content">
        <div class="section-content-checkbox">
            <ul id="accordion-section-add-side-extension">';
            $hooks->do_action('admin_nav_menu_side_control_extension');
        echo '</ul>
        </div>
        <p class="button-controls clearfix">
			<span class="add-to-menu">
                <button type="button" id="button-side-extension" class="button submit-add-to-menu" data-prifx="side-extension" data-type="Extension">'.get_admin_languages('add_to_menu').'</button>
				<span class="spinner"></span>
			</span>
		</p>
        </div>
        </li>
        ';
        echo '</ul></div></div>';
        echo '
        <div class="col-lg-9 col-md-8">
            <form action="themes.php?mode=menus" method="post" class="" id="menu-form-edit">
            <input type="hidden" name="token" value="'.$token.'">
            <input type="hidden" name="menu_item_map" id="menu_item_map" value="">
			<input type="hidden" name="action" value="menu-save">
                <div class="menu-edit">
                    <div id="nav-menu-header">
                        <div class="publishing-actions">
                            <label class="menu-name-label" for="menu-name">'.get_admin_languages('menus').'</label>
                            <div class="publishing-action">
                                <input type="button" name="save_menu" id="save_menu_header" class="button button-primary menu-save" value="'.get_admin_languages('save_menu').'">
                            </div>
                        </div>
                    </div>
                    <div class="nav-menu-body">
                        <h3>'.get_admin_languages('menu_structure').'<div id="data-javas"></div></h3>
                        <div id="menu-body-edit" class="">
                            <ul class="sortable ui-sortable" id="menu-to-edit">'.$main_menu_html.'</ul>
                            <input type="hidden" id="menu_item_count" value="'.$menu_item_count.'">
                        </div>
                    </div>
                </div>
            </form>
        </div>
        ';
        echo admin_content_section_end();
        admin_footer();
        
    }
    
}
?>
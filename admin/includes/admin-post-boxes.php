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
if(!defined('IN_PHPMEGATEMP_CP')) exit();

// boxes content
function post_boxes_content($args)
{
    return '
    <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">'.$args['title'].'</h3>
          <div class="box-tools pull-right"><button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fas fa-minus"></i></button>
          </div>
        </div>
        <div class="box-body">'.$args['html'].'</div>
      </div>
    ';
}
// class post boxes
class admin_post_boxes
{
    // comments
    function post_boxes_comments($args)
    {
        if(isset($args['data']['id'])):
            if($args['data']['comment_status']):
                $activeon = 'active';
            else:
                $activeoff = 'active';
            endif;
        else:
            $activeon = 'active';
        endif;
        $status = (isset($args['data']['comment_status']))? $args['data']['comment_status'] : '1';
        return '
        <div class="form-group row">
            <label class="control-label col-md-12">'.get_admin_languages('comments_status').'</label>
            <div class="controls col-md-12">
                <div class="megapanel-buttons-options">
                    <button type="button" data-value="1" class="option-on '.$activeon.'">ON</button>
                    <button type="button" data-value="0" class="option-off  '.$activeoff.'">OFF</button>
                    <input type="hidden" name="comment_status" value="'.$status.'">
                </div>
            </div>
        </div>
        ';
    }// comments
    function post_boxes_publish_status($args)
    {
        if(isset($args['data']['id'])):

            $meta_publish_status = get_post_meta($args['data']['id'], 'publish_status', 'public');

            if($meta_publish_status == 'users'):
                $activeusers = 'active';
            elseif($meta_publish_status == 'private'):
                $activeprivate = 'active';
            else:
                $activepublic = 'active';
            endif;
        else:
            $activepublic = 'active';
        endif;

        $publish_status = (isset($args['data']['id']))? get_post_meta($args['data']['id'], 'publish_status', 'public') : 'puplic';

        return '
        <div class="form-group row">
            <label class="control-label col-md-12">'.get_admin_languages('publish').'</label>
            <div class="controls col-md-12">
                <div class="megapanel-buttons-options">
                    <button type="button" data-value="public" class="option-on '.$activepublic.'">'.get_admin_languages('public').'</button>
                    <button type="button" data-value="private" class="option-on '.$activeprivate.'">'.get_admin_languages('private').'</button>
                    <button type="button" data-value="users" class="option-on '.$activeusers.'">'.get_admin_languages('users').'</button>
                    <input type="hidden" name="publish_status" value="'.$publish_status.'">
                </div>
            </div>
        </div>
        ';
    }
    // publish
    function post_boxes_publish($args)
    {
        $btn_title   = ($args['query'] == 'addnew')? get_admin_languages('publish') : get_admin_languages('update') ;
        $orders      = (in_array('orders', $args['supports']))? $this->post_boxes_publish_orders($args) : '';
        $post_status = (isset($args['data']['post_status']))? $args['data']['post_status'] : 1;
        $on_active   = ($post_status == 1)? 'active' : '';
        $off_active  = ($post_status == 0)? 'active' : '';
        $box_comments = (in_array('comments', $args['supports']))? $this->post_boxes_comments($args) : '';
        $box_publish_status = (in_array('publish_status', $args['supports']))? $this->post_boxes_publish_status($args) : '';
        $box_category = (in_array('category', $args['supports']))? $this->post_boxes_category($args) : '';
        return '
        <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">'.get_admin_languages('publish').'</h3>
              <div class="box-tools pull-right"><button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fas fa-minus"></i></button>
              </div>
            </div>
            <div class="box-body">
                <div class="form-group row">
                    <label class="control-label col-md-12">'.get_admin_languages('status').'</label>
        			<div class="controls col-md-12">
                        <div class="megapanel-buttons-options">
                            <button type="button" data-value="1" class="option-on '.$on_active.'">ON</button>
                            <button type="button" data-value="0" class="option-off '.$off_active.'">OFF</button>
                            <input type="hidden" name="post_status" value="'.$post_status.'">
                        </div>
                    </div>
                </div>
                '.$box_publish_status.'
                '.$box_comments.'
                '.$box_category.'
                '.$orders.'
            </div>
            <div class="box-footer"><button class="button button-primary">'.$btn_title.'</button></div>
          </div>
        ';
    }
    // featured image
    function post_boxes_thumbnail($args)
    {
        $args['data']['id'] = (isset($args['data']['id']))? $args['data']['id'] : '';
        $thumbnail = get_post_attachment($args['data']['id']); //get_post_meta($args['data']['id'], 'thumbnails');
        $thumb = (get_post_meta($args['data']['id'],'thumbnails'))? get_post_meta($args['data']['id'],'thumbnails') : '' ;
        $thumbspacer  = ($thumbnail != '')? $thumbnail : 'assets/libs/cupload/images/spacer.png' ;
        $diaply_none  = ($thumbnail != '')? 'style="display: none;"' : '' ;
        $diaply_block = ($thumbnail != '')? 'style="display: block;"' : '' ;
        return '
        <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">'.get_admin_languages('featured_image').'</h3>
              <div class="box-tools pull-right"><button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fas fa-minus"></i></button>
              </div>
            </div>
            <div class="box-body">
                <input class="cmp-input-thumbnails" type="hidden" name="thumbnails" value="'.$thumb.'" />
                <button typy="button" class="cmp-thumbnails-button btn btn-block btn-default" '.$diaply_none.'>'.get_admin_languages('set_featured_image').'</button>
                <div class="cmp-thumbnails-preview cmp-preview" '.$diaply_block.'>
                    <img src="'.$thumbspacer.'" />
                    <a class="cmp-remove-thumbnails-image icon-remove"></a>
                </div>
            </div>
          </div>
          <script type="text/javascript">creative_media_upload("thumbnails","image", "library");</script>
        ';
    }
    // author
    function post_boxes_author($args)
    {
        $post_author = (isset($args['data']['post_author']))? $args['data']['post_author'] : get_session_userid();
        return '
        <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">'.get_admin_languages('author').'</h3>
              <div class="box-tools pull-right"><button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fas fa-minus"></i></button>
              </div>
            </div>
            <div class="box-body">
                <div class="">'.get_user_field_select_input(array('id' => 'post_author', 'value' => $post_author), false).'</div>
            </div>
          </div>
        ';
    }
    // orders
    function post_boxes_publish_orders($args)
    {
        return '
        <div class="form-group row">
            <label class="control-label col-md-12">'.get_admin_languages('order').'</label>
    		<div class="controls col-md-12">
                <input type="text" name="orders" value="'.$args['data']['orders'].'"  class="form-control" style="text-align: center;" />
            </div>
        </div>
        ';
    }
    // category
    function post_boxes_category($args)
    {
        $args['term_type'] = (isset($args['term_type']))? $args['term_type'] : '';
        $args['data']['term_id'] = (isset($args['data']['term_id']))? $args['data']['term_id'] : '';
        return '
        <div class="form-group row">
            <label class="control-label col-md-12">'.get_admin_languages('categories').'</label>
            <div class="controls col-md-12">
            <select class="form-control" name="term_id">'.get_term_select_option($args['term_type'], $args['data']['term_id']).'</select>
            </div>
        </div>
        ';
    }
    // tags
    function post_boxes_post_tag($args)
    {
        $tags = get_post_meta($args['data']['id'], 'post_tags');
        return '
        <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">'.get_admin_languages('tags').'</h3>
              <div class="box-tools pull-right"><button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fas fa-minus"></i></button>
              </div>
            </div>
            <div class="box-body">
                <input type="text" class="input_tags form-control" name="post_tags" data-role="tagsinput" value="'.$tags.'">
                <script>$(document).on("keyup keypress", \'form input[type="text"]\', function(e) {if(e.which == 13) {e.preventDefault();return false;}});</script>
            </div>
          </div>
        ';
    }
    // template
    function post_boxes_template($args)
    {
        global $hooks;
        $templates = array(
            'default'   => get_admin_languages('default_template'), 
            'contact'   => get_admin_languages('contact'), 
        );
        
        if($hooks->has_filter('add_admin_page_templates')):
            $page_template = $hooks->apply_filters( 'add_admin_page_templates' , $templates);
        else:
            $page_template = $templates;
        endif;
        
        
        $args['data']['id'] = (isset($args['data']['id']))? $args['data']['id'] : '';
        $page_temp  = get_post_meta($args['data']['id'], 'page_template');
        $option = '';
        foreach($page_template as $key => $value)
        {
            $selected = ($key == $page_temp)? 'selected=""' : '' ;
            $option .= '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
        }
        
        return '
        <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">'.get_admin_languages('page_attributes').'</h3>
              <div class="box-tools pull-right"><button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fas fa-minus"></i></button>
              </div>
            </div>
            <div class="box-body">
                <div class="form-group">
                    <label class="control-label">'.get_admin_languages('template').'</label>
                    <div class="controls">
                        <select name="page_template" class="form-control">
                            '.$option.'
                        </select>
                    </div>
                </div>
            </div>
          </div>
        ';
    }
    // title
    function box_input_post_title($args = '', $metalang = false)
    {
        global $config;
        if($metalang)
        {
            $title = (isset($args['post_title']))? sanitize_text($args['post_title']) : '';
            $html  = '
            <div class="form-group">
                <input type="text" name="'.$args['name'].'" placeholder="'.get_admin_languages('enter_title_here').'" value="'.$title.'" class="form-control post-title" />
            </div>
            ';
        }
        else
        {
            $title      = (isset($args['data']['post_title']))? sanitize_text($args['data']['post_title']) : '';
            $post_name  = (isset($args['data']['post_name']))? $args['data']['post_name'] : '';
            $html = '
            <div class="form-group">
                <input type="text" name="title" placeholder="'.get_admin_languages('enter_title_here').'" value="'.$title.'" class="form-control post-title" />
            </div>
            ';
            
            $permalink = (isset($args['permalink']))? $args['permalink'] : true ;
            if($permalink)
            {
                if($title and $post_name)
                {
                    $slug       = urldecode($post_name);
                    $url_atr    = ($args['post_type'] != 'page')? 'post/' : '' ;
                    $html .= '
                    <div class="form-group col-post-slug">
                        <strong>'.get_admin_languages('permalink').'</strong>: '.$config['siteurl'].'/'.$args['post_type'].'/'.$url_atr.'
                        <input type="text" name="post_name" class="form-control new-post-slug" value="'.$slug.'" autocomplete="off">
                    </div>
                    ';
                }
            }
                
            
        }
        
            
        return $html;
    }
    // Excerpt
    function box_post_excerpt($args = '', $name = 'excerpt', $id = 'excerpt')
    {
        $excerpt = sanitize_text(get_post_meta($args['data']['id'], $id));
        return '
        <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">'.get_admin_languages('excerpt').'</h3>
              <div class="box-tools pull-right"><button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fas fa-minus"></i></button>
              </div>
            </div>
            <div class="box-body">
                <textarea rows="3" name="'.$name.'" id="'.$id.'" class="form-control">'.$excerpt.'</textarea>
            </div>
        </div>
        ';
    }
    // editor
    function box_post_editor($post_content, $name = 'content', $id = 'content', $dir = 'ltr')
    {
        global $hooks, $config;
        $content = (isset($post_content))? sanitize_text($post_content) : '';
        $html = '';
         $html = '
        <span typy="button" class="cmp-picture-button button button-primary">'.get_admin_languages('add_picture').'</span><br /><br />
        <script type="text/javascript">creative_media_upload("picture", "library", "library", "'.$id.'");</script>';
        $hooks->do_action('admin_box_post_editor_top');
        $html .= '
        <div class="form-group">
    		<div class="controls">
                <textarea style="height: 300px" autocomplete="off" cols="40" name="'.$name.'" id="'.$id.'" class="megaeditor-content">'.$content.'</textarea>
    		</div>
    	</div>
        ';
        $html .= $this->admin_script_tinymce($id);
        return $html;
    }
    // admin script tinymce
    public function admin_script_tinymce($id)
    {
        global $config;
        $get_dir = get_language_direction($config['language']);
        $lang    = ($get_dir == 'rtl')? 'ar' : 'en';
        return '<script>
        tinymce.init({
            selector: "textarea#'.$id.'",
            language: "'.$lang.'",
            directionality: "'.$get_dir.'",
            /*
            plugins: [
                "code,charmap,colorpicker,hr,lists,media,paste,tabfocus,textcolor,fullscreen,wordcount,link,autolink,image,visualchars,textpattern,fullscreen"
            ],
            */
            //theme: "modern",
            //resize: false,
            menubar: false,
            indent: false,
            relative_urls: true,
            remove_script_host: false,
            convert_urls: false,
            browser_spellcheck: true,
            fix_list_elements: true,
            entities: "38,amp,60,lt,62,gt",
            entity_encoding: "raw",
            keep_styles: false,
            plugins: "print preview fullpage paste importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap quickbars emoticons",
            toolbar: "undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media template link anchor codesample | ltr rtl",
            image_advtab: true,
            height: 400,
            image_caption: true,
            quickbars_selection_toolbar: "bold italic | quicklink h2 h3 blockquote quickimage quicktable",
            noneditable_noneditable_class: "mceNonEditable",
            toolbar_drawer: "sliding",
            contextmenu: "link image imagetools table",
        });
        </script>';
    }
}
?>
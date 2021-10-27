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
require_once('./admin-common.php');
if(!defined('IN_PHPMEGATEMP_CP')) exit();

if(!class_exists('admin_megapanel_options'))
{
    class admin_megapanel_options
    {
        
        // class constructor
        function __construct()
        {
            
        }
        // switch fields
        function field_options_item($input, $echo = true)
        {
            switch($input['type'])
            {
                case"itembox":
                    return $this->field_section_itembox($input, $echo);
                break;
                case"label":
                    return $this->field_text_label($input, $echo);
                break;
                case"text":
                    return $this->field_text_input($input, $echo);
                break;
                case"password":
                    return $this->field_password_input($input, $echo);
                break;
                case"textarea":
                    return $this->field_textarea_input($input, $echo);
                break;
                case"textarea_full":
                    return $this->field_textarea_full_input($input, $echo);
                break;
                case"select":
                    return $this->field_select_input($input, $echo);
                break;
                case"checkbox":
                    return $this->field_checkbox_input($input, $echo);
                break;
                case"checkbox_array":
                    return $this->field_checkbox_array_input($input, $echo);
                break;
                case"text_checkbox":
                    return $this->field_text_checkbox_input($input, $echo);
                break;
                case"number":
                    return $this->field_number_input($input, $echo);
                break;
                case"slider_number":
                    return $this->field_slider_number_input($input, $echo);
                break;
                case"checkbox_post_types":
                    return $this->field_checkbox_post_types_input($input, $echo);
                break;
                case"radio":
                    return $this->field_radio_input($input, $echo);
                break;
                case"color":
                    return $this->field_color_input($input, $echo);
                break;
                case"upload":
                    return $this->field_upload_input($input, $echo);
                break;
                case"fonticon":
                    return $this->field_fieldicon_input($input, $echo);
                break;
            }
        }
        // get selected
        function get_selected($value, $key, $echo = true)
        {
            $selected = ($value == $key)? 'selected=""' : '';
            if($echo){echo $selected;}
            else{return $selected;}
        }
        // get checked
        function get_checked($value, $key, $echo = true)
        {
            $checkbox = ($value == $key)? 'checked=""' : '';
            if($echo){echo $checkbox;}
            else{return $checkbox;}
        }
        // get checked in array
        function get_checked_in_array($value, $keys, $echo = true)
        {
            $checkbox = (in_array($value, $keys))? 'checked=""' : '';
            if($echo){echo $checkbox;}
            else{return $checkbox;}
        }
        // field slider number input
        function field_slider_number_input($input,$echo = true)
        {
            if(!isset($input['step'])){$input['step'] = '1';}
            if(!isset($input['min'])){$input['min'] = '1';}
            if(!isset($input['max'])){$input['max'] = '20';}
            $input_class    = (isset($input['input_class']))? $input['input_class'] : '' ;
            $checkbox_class = (isset($input['checkbox_class']))? $input['input_class'] : '' ;
            $help           = (isset($input['help']))? '<span class="feature-details" original-title="'.$input['help'].'">?</span>' : '';
            $inputid        = str_replace(array('[', ']'), '', $input['id']);
            
            $html = '<div class="megapanel-col-item">
                <label>'.$input['name'].'</label>
                <div class="megapanel-field megapanel-field-slider">
                    <div class="slider_num '.$inputid.'" id="'.$inputid.'"></div>
                    <input id="'.$inputid.'_input" class="width80 text-center" type="text" name="'.$input['id'].'" value="'.$input['value'].'">
                </div>
            </div>
            <script>
              jQuery(document).ready(function() {
                jQuery(".'.$inputid.'").slider({
                    range:"min", min:'.$input['min'].', max:'.$input['max'].',step:'.$input['step'].', value:'.$input['value'].',
                    slide: function(event, ui) {jQuery("#"+jQuery(this).attr("id")+"_input").val(ui.value);}
                });
              });
            </script>';
            if( $echo ){ echo $html; } else{ return $html; }
        }
        // field text checkbox input
        function field_text_checkbox_input($input, $echo = true)
        {
            $input_class = (isset($input['input_class']))? $input['input_class'] : '' ;
            $checkbox_class = (isset($input['checkbox_class']))? $input['input_class'] : '' ;
            $help  = (isset($input['help']))? '<span class="feature-details" original-title="'.$input['help'].'">?</span>' : '';
            $html  = '<div class="megapanel-col-item">
                <label>'.$input['name'].'</label>
                <div class="megapanel-field">
                    <input type="text" name="'.$input['input_id'].'" class="'.$input_class.'" value="'.$input['input_value'].'">
                    <input type="checkbox" class="checkbox-on-of '.$checkbox_class.'" name="'.$input['checkbox_id'].'" '.get_checked( $input['checkbox_value'], '1', false ).'>
                    '.$help.'
                </div>
            </div>';
            if( $echo ){ echo $html; } else{ return $html; }
        }
        // field text label
        function field_text_label($input, $echo = true)
        {
            $html  = '<div class="megapanel-col-item">
                <label>'.$input['name'].'</label>
                <div class="col-md-9" style="line-height: 30px;">
                    <strong>'.$input['value'].'</strong>
                </div>
            </div>';
            if( $echo ){ echo $html; } else{ return $html; }
        }
        // field text input
        function field_text_input($input, $echo = true)
        {
            $class  = (isset($input['class']))? $input['class'] : '' ;
            $dir    = (isset($input['dir']))? 'dir="'.$input['dir'].'"' : '' ;
            $desc   = (isset($input['desc']))? '<div class="text-desc">'.$input['desc'].'</div>' : '';
            $help   = (isset($input['help']))? '<span class="feature-details" original-title="'.$input['help'].'">?</span>' : '';
            $html   = '
            <div class="megapanel-col-item">
                <label>'.$input['name'].'</label>
                <div class="megapanel-field">
                    <input type="text" name="'.$input['id'].'" class="'.$class.'" '.$dir.' value="'.$input['value'].'">
                    '.$desc.'
                    '.$help.'
                </div>
            </div>';
            if( $echo ){ echo $html; } else{ return $html; }
        }
        // field password input
        function field_password_input($input, $echo = true)
        {
            $class = (isset($input['class']))? $input['class'] : '' ;
            $place = (isset($input['place']))? 'placeholder="'.$input['place'].'"' : '' ;
            $autoc = (isset($input['autoc']))? $input['autoc'] : '' ;
            $help  = (isset($input['help']))? '<span class="feature-details" original-title="'.$input['help'].'">?</span>' : '';
            $html  = '<div class="megapanel-col-item">
                <label>'.$input['name'].'</label>
                <div class="megapanel-field">
                    <input type="password" name="'.$input['id'].'" class="'.$class.'" dir="ltr" '.$place.' '.$autoc.' value="'.$input['value'].'">'.$help.'
                </div>
            </div>';
            if( $echo ){ echo $html; } else{ return $html; }
        }
        // field textarea input
        function field_textarea_input($input, $echo = true)
        {
            $rows = (isset($input['rows']))? 'rows="'.$input['rows'].'"' : 'rows="3"' ;
            $dir = (isset($input['dir']))? 'dir="'.$input['dir'].'"' : '' ;
            
            $class = (isset($input['class']))? $input['class'] : '' ;
            $help = (isset($input['help']))? '<span class="feature-details" original-title="'.$input['help'].'">?</span>' : '';
            $html = '<div class="megapanel-col-item">
                <label>'.$input['name'].'</label>
                <div class="megapanel-field">
                    <textarea name="'.$input['id'].'" class="'.$class.'" type="textarea" '.$dir.' '.$rows.'>'.$input['value'].'</textarea>'.$help.'
                </div>
            </div>';
            if( $echo ){ echo $html; } else{ return $html; }
        }
        // field textarea full input
        function field_textarea_full_input($input, $echo = true)
        {
            $rows       = (isset($input['rows']))? 'rows="'.$input['rows'].'"' : 'rows="3"' ;
            $id         = (isset($input['id']))? 'name="'.$input['id'].'"' : '' ;
            $help       = (isset($input['help']))? '<span class="feature-details" original-title="'.$input['help'].'">?</span>' : '';
            $htmlmore   = (isset($input['htmlmore']))? $input['htmlmore'] : '' ;
            $html = '<div class="megapanel-col-item">
                <label>'.$input['name'].'</label>
                <div class="clearfix-5"></div>
                <div class="megapanel-field megapanel-full-field">
                    <textarea '.$id.' type="textarea" '.$rows.'>'.$input['value'].'</textarea>'.$help.'
                    '.$htmlmore.'
                </div>
            </div>';
            if( $echo ){ echo $html; } else{ return $html; }
        }
        // field select input
        function field_select_input($input, $echo = true)
        {
            $help = (isset($input['help']))? '<span class="feature-details" original-title="'.$input['help'].'">?</span>' : '';
            $class = (isset($input['class']))? $input['class'] : '' ;
            $multiple = (isset($input['multiple']))? 'multiple' : '' ;
            $html = '<div class="megapanel-col-item">
            <label>'.$input['name'].'</label>
            <div class="megapanel-field"><select name="'.$input['id'].'" class="'.$class.'" '.$multiple.'>';
            if(isset($input['options_html']))
            {
                $html .= $input['options_html'];
            }
            else
            {
                foreach($input['options'] as $key => $value)
                {
                    $html .= '<option value="'.$key.'" '.get_selected( $input['value'], $key, false ).'>'.$value.'</option>';
                }
            } 
            $html .= '<select>'.$help.'</div></div>';
            if( $echo ){ echo $html; } else{ return $html; }
        }
        // field checkbox input
        function field_checkbox_input($input, $echo = true)
        {
            $options    = (isset($input['options']) and is_array($input['options']))? true : false ;
            $help       = (isset($input['help']))? '<span class="feature-details" original-title="'.$input['help'].'">?</span>' : '';
            $on_active  = (get_checked( $input['value'], '1', false ))? 'active' : '';
            $off_active = (get_checked( $input['value'], '0', false ))? 'active' : '';
            $ontext     = (isset($input['ontext']))? $input['ontext'] : 'ON' ;
            $offtext    = (isset($input['offtext']))? $input['offtext'] : 'OFF' ;
            $html = '<div class="megapanel-col-item">
                <label>'.$input['name'].'</label>
                <div class="megapanel-buttons-options">
                    <button type="button" data-value="1" class="option-on '.$on_active.'">'.$ontext.'</button>
                    <button type="button" data-value="0" class="option-off '.$off_active.'">'.$offtext.'</button>
                    <input type="hidden" name="'.$input['id'].'" value="'.$input['value'].'">
                </div>'.$help.'
            </div>';
            if( $echo ){ echo $html; } else{ return $html; }
        }
        // field checkbox array input
        function field_checkbox_array_input($input, $echo = true)
        {
            $options    = (isset($input['options']) and is_array($input['options']))? true : false ;
            $help       = (isset($input['help']))? '<span class="feature-details" original-title="'.$input['help'].'">?</span>' : '';
            $ontext     = (isset($input['ontext']))? $input['ontext'] : 'ON' ;
            $offtext    = (isset($input['offtext']))? $input['offtext'] : 'OFF' ;

            if($options)
            {
                $input['value'] = (isset($input['value']))? $input['value'] : array();
                $counoption = count($input['options']);
                $checkbox = '';
                $i = 0;
                foreach($input['options'] as $option)
                {
                    $checkbox .= '
                        <br />
                        <input type="checkbox" name="'.$option['id'].'" value="'.$option['value'].'" '.$this->get_checked_in_array( $option['value'], $input['value'], false ).'>&nbsp;&nbsp;
                        <strong>'.$option['name'].'</strong> 
                    ';
                    
                }
            }

            
           $html = '<div class="megapanel-col-item">
                <label>'.$input['name'].'</label>
                <div class="megapanel-buttons-options">
                    '.$checkbox.'
                </div>'.$help.'
            </div>';
            if( $echo ){ echo $html; } else{ return $html; }
        }
        // field radio input
        function field_radio_input($input, $echo = true)
        {
            $help = (isset($input['help']))? '<span class="feature-details" original-title="'.$input['help'].'">?</span>' : '' ;
            $html = '<div class="megapanel-col-item">
                <label>'.$input['name'].'</label>
                <div class="megapanel-buttons-options">
                ';
                $counoption = count($input['options']);
                $i = 0;
                $labelafter = (isset($labelafter))? $labelafter : '';
                $class = '';
                foreach($input['options'] as $key => $value)
                {
                    $i++;
                    $clear = ($i < $counoption)? '<div class="clearfix-5"></div>' : '';
                    if(is_array($value))
                    {
                        if(isset($value['img'])){
                            $class = 'boximg';
                            $labelafter = '<div class="option-boximg"><img src="'.$value['img'].'" /></div>';
                        }
                        else
                        {
                            $class = (isset($value['boxcolor']))? 'boxcolor' : '';
                        }
                        
                        if(isset($value['boxcolor'])){$labelafter = '<div class="option-boxcolor" style="background: '.$value['boxcolor'].'"></div>';}
                        $label = $value['label'];
                        
                    }
                    else{$label = $value;}
                    
                    $active = (get_checked( $input['value'], $key, false ))? 'active' : '' ;
                    $html .= '
                    <button type="button" data-value="'.$key.'" class="option-on '.$class.' '.$active.'">'.$labelafter.' '.$label.'</button>
                    ';
                }
            
            $html .= $help.'<input type="hidden" name="'.$input['id'].'" value="'.$input['value'].'"></div></div>';
            if( $echo ){ echo $html; } else{ return $html; }
        }
        // field color input
        function field_color_input($input, $echo = true)
        {
            $help = (isset($input['help']))? '<span class="feature-details" original-title="'.$input['help'].'">?</span>' : '';
            $col_class  = (isset($input['col_class']))? $input['col_class'] : '' ;
            $class      = (isset($input['class']))? 'class="'.$input['class'].'"' : 'class="color-picker"' ;
            $alpha      = (isset($input['alpha']))? 'data-alpha="true"' : 'data-alpha="false"' ;
            $default    = (isset($input['default']))? 'data-default-color="'.$input['default'].'"' : '' ;
            $depend     = (isset($input['depend']))? 'data-depend-id="'.$input['depend'].'"' : '' ;
            $html = '<div class="col-md-3 col-md-10-color_picker '.$col_class.'">
                <label>'.$input['name'].'</label>
                <div class="megapanel-field">
                    <input type="text" name="'.$input['id'].'" value="'.$input['value'].'" class="'.$class.'" '.$alpha.' '.$depend.' '.$default.' />
                </div>
                <span class="feature-details" title="'.$help.'">?</span>
            </div>';
            if( $echo ){ echo $html; } else{ return $html; }
        }
        // field icon input
        function field_fieldicon_input($input, $echo = true)
        {
            $help = (isset($input['help']))? '<span class="feature-details" original-title="'.$input['help'].'">?</span>' : '';
            $class = (isset($input['class']))? $input['class'] : '' ;
            
            $html = '<div class="megapanel-col-item" data-value="true">
                <label>'.$input['name'].'</label>
                <div class="megapanel-field">
                    <div class="megapanel-icon-select">
                        <span class="megapanel-icon-preview"><i class="'.$input['value'].'"></i></span>
                        <button type="button" class="button button-primary megapanel-icon-add">'.get_admin_languages('changes').'</button>
                        <button type="button" class="button megapanel-icon-default" data-geticon="'.$input['value'].'">'.get_admin_languages('default').'</button>
                        <button type="button" class="button button-remove megapanel-icon-remove">'.get_admin_languages('remove').'</button>
                        <input type="hidden" name="'.$input['id'].'" value="'.$input['value'].'" class="megapanel-icon-value class="'.$class.'"">
                    </div>
                </div>
                '.$help.'
            </div>';
            if( $echo ){ echo $html; } else{ return $html; }
        }
        // field upload input
        function field_upload_input($input, $echo = true)
        {
            $class          = (isset($input['class']))? $input['class'] : '' ;
            $thumbnail      = $input['value'];
            $thumb          = ($thumbnail != '')? $thumbnail : '' ;
            $thumbspacer    = ($thumbnail != '')? $thumbnail : 'assets/plugins/cupload/images/spacer.png' ;
            $diaply_none    = ($thumbnail != '')? 'style="display: none;"' : '' ;
            $diaply_block   = ($thumbnail != '')? 'style="display: block;"' : '' ;
            $description    = (isset($input['desc']))? '<div class="text-desc">'.$input['desc'].'</div>' : '';
            $id             = str_replace(array('[', ']'), array('',''), $input['id']);
            $src            = (isset($input['src']))? $input['src'] : '';
            $html = '
            <div class="megapanel-col-item">
                <label>'.$input['name'].' '.$description.'</label>
                <div class="megapanel-field megapanel-field-upload">
                    <div class="box-uploads">
                        <input class="cmp-input-'.$id.' form-control" type="text" name="'.$input['id'].'" value="'.$thumb.'" dir="ltr" />
                        <button type="button" class="cmp-'.$id.'-button btn btn-block btn-default" '.$diaply_none.'>'.get_admin_languages('upload').'</button>
                    </div>
                    <div class="cmp-'.$id.'-preview cmp-preview" '.$diaply_block.'>
                        <img src="'.$thumbspacer.'" />
                        <a class="cmp-remove-'.$id.'-image icon-remove"></a>
                    </div>
                    <script type="text/javascript">creative_media_upload("'.$id.'","image", "library", "", "'.$src.'");</script>
                </div>
            </div>';
            if( $echo ){ echo $html; } else{ return $html; }
        }
        // start option
        function start_options($title)
        {
            return '
            <div class="megapanel-options-head-items">
            <h3><span class="megapanel-title-item">'.$title.'</span><span class="megapanel_tools collapse-button"><i class="fa fa-minus"></i></span></h3>
            <div class="megapanel-options-content megapanel-toggle-content ">
            ';
        }
        // end option
        function end_options()
        {
            return '</div></div>';
        }
        // get pages
        function get_pages()
        {
            global $db;
            $options = array();
            $sql    = "SELECT * FROM ".POSTS_TABLE." WHERE `post_status`='1' and `post_type`='page' ORDER BY post_title ASC";
            $result = $db->sql_query($sql);
            while($row = $db->sql_fetchrow($result))
            {
                $options[$row['id']] = $row['post_title'];
            }
            return $options;
        }
    }
    
}
?>
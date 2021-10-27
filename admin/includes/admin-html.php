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

// admin content header
function admin_content_header($arg)
{
    $button = (isset($arg['button']))? $arg['button'] : '';
    $linkmore = (isset($arg['linkmore']))? '<a href="'.$arg['linkmore']['link'].'" target="_blank" class="linkmore button">'.$arg['linkmore']['title'].'</a>' : '';
    echo '<section class="content-header"><h1>'.$arg['title'].' '.$button.' '.$linkmore.'</h1></section>';
}
// admin content section start
function admin_content_section_start($row = true)
{
    echo '';
    echo ($row)? '<section class="content"><div class="row">' : '<section class="content">';
}
// admin content section end
function admin_content_section_end($row = true)
{
    echo ($row)? '</div></section>' : '</section>';
}
// admin get status checkbox html post
function admin_get_status_checkbox_html_post($name , $status = '1')
{
    $checkbox = (isset($status) and $status == 0)? '' : 'checked=""';
    return '<input name="'.$name.'" type="checkbox" '.$checkbox.' />';
}
// display dashboard info box
function display_dashboard_info_box($arg)
{
    $color = array('1' => 'bg-aqua', '2' => 'bg-red', '3' => 'bg-green', '4' => 'bg-yellow', '5' => '', '6' => '', '7' => '', '8' => '');
    $col = (isset($arg['col']))? $arg['col'] : 'col-md-3 col-sm-3 col-xs-12';
    echo '<div class="'.$col.'">
      <div class="info-box">
        <span class="info-box-icon '.$color[$arg['color']].'"><i class="'.$arg['icon'].'"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">'.$arg['title'].'</span>
          <span class="info-box-number">'.$arg['number_count'].'</span>
          <span class="info-box-number">'.$arg['number_pre'].'<small>%</small></span>
        </div>
      </div>
    </div>';
}
// display dashboard info box2
function display_dashboard_info_box2($arg)
{
    $color = array('1' => 'bg-aqua', '2' => 'bg-red', '3' => 'bg-green', '4' => 'bg-yellow', '5' => '', '6' => '', '7' => '', '8' => '');
    $col = ($arg['col'])? $arg['col'] : 'col-md-4 col-sm-6 col-xs-12';
    echo '<div class="'.$col.'">
      <div class="info-box '.$color[$arg['color']].'">
        <span class="info-box-icon"><i class="'.$arg['icon'].'"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">'.$arg['title'].'</span>
          <span class="info-box-number">'.$arg['number_count'].'</span>
          <div class="progress"><div class="progress-bar" style="width: '.$arg['number_pre'].'%"></div></div>
          <span class="progress-description">'.$arg['number_pre'].'%</span>
        </div>
      </div>
    </div>';
}
// switch fields
function field_options_item($input, $echo = true)
{
    switch($input['type'])
    {
        case"normal":
            return field_normal_box($input, $echo);
        break;
        case"itembox":
            return field_section_itembox($input, $echo);
        break;
        case"label":
            return field_text_label($input, $echo);
        break;
        case"text":
            return field_text_input($input, $echo);
        break;
        case"password":
            return field_password_input($input, $echo);
        break;
        case"textarea":
            return field_textarea_input($input, $echo);
        break;
        case"textarea_full":
            return field_textarea_full_input($input, $echo);
        break;
        case"select":
            return field_select_input($input, $echo);
        break;
        case"checkbox":
            return field_checkbox_input($input, $echo);
        break;
        case"checkbox_array":
            return field_checkbox_array_input($input, $echo);
        break;
        
        case"text_checkbox":
            return field_text_checkbox_input($input, $echo);
        break;
        case"number":
            return field_number_input($input, $echo);
        break;
        case"slider_number":
            return field_slider_number_input($input, $echo);
        break;
        case"checkbox_post_types":
            return field_checkbox_post_types_input($input, $echo);
        break;
        case"radio":
            return field_radio_input($input, $echo);
        break;
        case"color":
            return field_color_input($input, $echo);
        break;
        case"upload":
            return field_upload_input($input, $echo);
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
    $help           = (isset($input['help']))? '<span class="feature-details">'.$input['help'].'</span>' : '';
    $html = '<div class="form-group row">
        <label class="col-md-3 control-label">'.$input['name'].'</label>
		<div class="col-md-9">
            <div class="slider_num '.$input['id'].'" id="'.$input['id'].'"></div>
            <input id="'.$input['id'].'_input" class="form-control width80 text-center" type="text" name="'.$input['id'].'" value="'.$input['value'].'">
		</div>
	</div>
    <script>
	  jQuery(document).ready(function() {
		jQuery(".'.$input['id'].'").slider({
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
    $help  = (isset($input['help']))? '<span class="feature-details">'.$input['help'].'</span>' : '';
    $html  = '<div class="form-group row">
        <label class="col-md-3 control-label">'.$input['name'].'</label>
		<div class="col-md-9">
			<input type="text" name="'.$input['input_id'].'" class="form-control '.$input_class.'" value="'.$input['input_value'].'">
            <input type="checkbox" class="checkbox-on-of '.$checkbox_class.'" name="'.$input['checkbox_id'].'" '.get_checked( $input['checkbox_value'], '1', false ).'>
            '.$help.'
		</div>
	</div>';
    if( $echo ){ echo $html; } else{ return $html; }
}
// field text label
function field_text_label($input, $echo = true)
{
    $html  = '<div class="form-group row">
        <label class="col-md-3 control-label">'.$input['name'].'</label>
		<div class="col-md-9" style="line-height: 30px;">
			<strong>'.$input['value'].'</strong>
		</div>
	</div>';
    if( $echo ){ echo $html; } else{ return $html; }
}
// field text input
function field_text_input($input, $echo = true)
{
    $class = (isset($input['class']))? $input['class'] : '' ;
    $dir = (isset($input['dir']))? 'dir="'.$input['dir'].'"' : '' ;
    $help  = (isset($input['help']))? '<span class="feature-details">'.$input['help'].'</span>' : '';
    $html  = '<div class="form-group row">
        <label class="col-md-3 control-label">'.$input['name'].'</label>
		<div class="col-md-9">
			<input type="text" name="'.$input['id'].'" class="form-control '.$class.'" '.$dir.' value="'.$input['value'].'">'.$help.'
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
    $help  = (isset($input['help']))? '<span class="feature-details">'.$input['help'].'</span>' : '';
    $html  = '<div class="form-group row">
        <label class="col-md-3 control-label">'.$input['name'].'</label>
		<div class="col-md-9">
			<input type="password" name="'.$input['id'].'" class="form-control '.$class.'" dir="ltr" '.$place.' '.$autoc.' value="'.$input['value'].'">'.$help.'
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
    $help = (isset($input['help']))? '<span class="feature-details">'.$input['help'].'</span>' : '';
    $html = '<div class="form-group row">
        <label class="col-md-3 control-label">'.$input['name'].'</label>
		<div class="col-md-9">
            <textarea name="'.$input['id'].'" class="form-control '.$class.'" type="textarea" '.$dir.' '.$rows.'>'.$input['value'].'</textarea>
		</div>
	</div>';
    if( $echo ){ echo $html; } else{ return $html; }
}
// field textarea full input
function field_textarea_full_input($input, $echo = true)
{
    $rows       = (isset($input['rows']))? 'rows="'.$input['rows'].'"' : 'rows="3"' ;
    $id         = (isset($input['id']))? 'name="'.$input['id'].'"' : '' ;
    $help       = (isset($input['help']))? '<span class="feature-details">'.$input['help'].'</span>' : '';
    $htmlmore   = (isset($input['htmlmore']))? $input['htmlmore'] : '' ;
    $html = '<div class="form-group row">
        <label class="col-md-3 control-label">'.$input['name'].'</label>
        <div class="clearfix-5"></div>
		<div class="col-md-9 megapanel-full-field">
            <textarea '.$id.' type="textarea" '.$rows.'>'.$input['value'].'</textarea>
            '.$htmlmore.'
		</div>
        <span class="feature-details" title="'.$input['help'].'">?</span>
	</div>';
    if( $echo ){ echo $html; } else{ return $html; }
}
// field select input
function field_select_input($input, $echo = true)
{
    $help = (isset($input['help']))? '<span class="feature-details">'.$input['help'].'</span>' : '';
    $class = (isset($input['class']))? $input['class'] : '' ;
    $multiple = (isset($input['multiple']))? 'multiple' : '' ;
    $html = '<div class="form-group row">
    <label class="col-md-3 control-label">'.$input['name'].'</label>
	<div class="col-md-9"><select name="'.$input['id'].'" class="form-control '.$class.'" '.$multiple.'>';
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
    $help = (isset($input['help']))? '<span class="feature-details">'.$input['help'].'</span>' : '';
    $checkbox   = '';
    if($options)
    {
        $counoption = count($input['options']);
        $i = 0;
        foreach($input['options'] as $key => $value)
        {
            $i++;
            $clear = ($i < $counoption)? '<div class="clearfix-5"></div>' : '';
            $checkbox .= '<input type="checkbox" class="checkbox-on-of" name="'.$input['id'].'" value="'.$value.'" '.get_checked( $input['value'], $value, false ).'>
            '.$value.' 
            '.$clear;
        }
    }
    else
    {
        $checkbox = '<input type="checkbox" class="checkbox-on-of" name="'.$input['id'].'" '.get_checked( $input['value'], '1', false ).'>';
    }
    $html = '<div class="form-group row">
        <label class="col-md-3 control-label">'.$input['name'].'</label>
		<div class="col-md-9">'.$checkbox.'</div>'.$help.'
	</div>';
    if( $echo ){ echo $html; } else{ return $html; }
}
// field checkbox array input
function field_checkbox_array_input($input, $echo = true)
{
    $options    = (isset($input['options']) and is_array($input['options']))? true : false ;
    $help = (isset($input['help']))? '<span class="feature-details">'.$input['help'].'</span>' : '';
    $checkbox   = '';
    
    if($options)
    {
        $input['value'] = ($input['value'])? $input['value'] : array();
        $counoption = count($input['options']);
        $i = 0;
        foreach($input['options'] as $option)
        {
            $i++;
            $clear = ($i < $counoption)? '<div class="clearfix-5"></div>' : '';
            $checkbox .= '<input type="checkbox" class="checkbox-on-of" name="'.$option['id'].'" value="'.$option['value'].'" '.get_checked_in_array( $option['value'], $input['value'], false ).'>
            '.$option['name'].' 
            '.$clear;
        }
    }
    else
    {
        $checkbox = '<input type="checkbox" class="checkbox-on-of" name="'.$input['id'].'" '.get_checked( $input['value'], '1', false ).'>';
    }
    $html = '<div class="form-group row">
        <label class="col-md-3 control-label">'.$input['name'].'</label>
		<div class="col-md-9">'.$checkbox.'</div>'.$help.'
	</div>';
    if( $echo ){ echo $html; } else{ return $html; }
}
// field radio input
function field_radio_input($input, $echo = true)
{
    $help = (isset($input['help']))? '<span class="feature-details">'.$input['help'].'</span>' : '' ;
    $html = '<div class="form-group row">
        <label class="col-md-3 control-label">'.$input['name'].'</label>
		<div class="col-md-9">
        ';
        $counoption = count($input['options']);
        $i = 0;
        $labelafter = (isset($labelafter))? $labelafter : '';
        foreach($input['options'] as $key => $value)
        {
            $i++;
            $clear = ($i < $counoption)? '<div class="clearfix-5"></div>' : '';
            if(is_array($value))
            {
                if(in_array('img', $value)){$labelafter = '<img src="'.$value['img'].'" />';}
                if(isset($value['boxcolor'])){$labelafter = '<div class="option-boxcolor" style="background: '.$value['boxcolor'].'"></div>';}
                $label = $value['label'];
            }
            else{$label = $value;}
            $html .= '
            <input type="radio" class="checkbox-on-of" name="'.$input['id'].'" value="'.$key.'" '.get_checked( $input['value'], $key, false ).'> '.$labelafter.' '.$label.' 
            '.$clear;
        }
	$html .= $help.'</div></div>';
    if( $echo ){ echo $html; } else{ return $html; }
}
// field color input (The function is not tried only for updating v3.6)
function field_color_input($input, $echo = true)
{
    $col_class  = (isset($input['col_class']))? $input['col_class'] : '' ;
    $class      = (isset($input['class']))? 'class="'.$input['class'].'"' : 'class="color-picker"' ;
    $alpha      = (isset($input['alpha']))? 'data-alpha="true"' : 'data-alpha="false"' ;
    $default    = (isset($input['default']))? 'data-default-color="'.$input['default'].'"' : '' ;
    $depend     = (isset($input['depend']))? 'data-depend-id="'.$input['depend'].'"' : '' ;
    $html = '<div class="col-md-3 col-md-10-color_picker '.$col_class.'">
        <label class="col-md-3 control-label">'.$input['name'].'</label>
        <div class="col-md-9">
            <input type="text" name="'.$input['id'].'" value="'.$input['value'].'" class="form-control '.$class.'" '.$alpha.' '.$depend.' '.$default.' />
        </div>
        <span class="feature-details" title="'.$input['help'].'">?</span>
    </div>';
    if( $echo ){ echo $html; } else{ return $html; }
}
// field icon input (The function is not tried only for updating v3.6)
function field_fieldicon_input($input, $echo = true)
{
    $class = (isset($input['class']))? $input['class'] : '' ;
    $html = '<div class="col-md-3 col-md-10-icon" data-value="true">
        <label class="col-md-3 control-label">'.$input['name'].'</label>
        <div class="col-md-9">
            <div class="megapanel-icon-select">
                <span class="megapanel-icon-preview"><i class="'.$input['value'].'"></i></span>
                <button type="button" class="button button-primary megapanel-icon-add">'.__('Changes', 'megapanel').'</button>
                <button type="button" class="button megapanel-icon-default" data-geticon="'.$input['value'].'">'.__('Default', 'megapanel').'</button>
                <button type="button" class="button button-remove megapanel-icon-remove">'.__('Remove', 'megapanel').'</button>
                <input type="hidden" name="'.$input['id'].'" value="'.$input['value'].'" class="megapanel-icon-value class="form-control '.$class.'"">
            </div>
        </div>
        <span class="feature-details" title="'.$input['help'].'">?</span>
    </div>';
    if( $echo ){ echo $html; } else{ return $html; }
}
// field upload input
function field_upload_input($input, $echo = true)
{
    $class = (isset($input['class']))? $input['class'] : '' ;
    $thumbnail = $input['value'];
    $thumb = ($thumbnail != '')? $thumbnail : '' ;
    $thumbspacer  = ($thumbnail != '')? $thumbnail : 'assets/libs/cupload/images/spacer.png' ;
    $diaply_none  = ($thumbnail != '')? 'style="display: none;"' : '' ;
    $diaply_block = ($thumbnail != '')? 'style="display: block;"' : '' ;
    $src = (isset($input['src']))? $input['src'] : '';
    $html = '
    <div class="form-group row">
        <label class="col-md-3 control-label">'.$input['name'].'</label>
		<div class="col-md-9">
            <div class="box-uploads">
                <input class="cmp-input-'.$input['id'].' form-control" type="text" name="'.$input['id'].'" value="'.$thumb.'" dir="ltr" />
                <button typy="button" class="cmp-'.$input['id'].'-button btn btn-block btn-default" '.$diaply_none.'>'.get_admin_languages('upload').'</button>
            </div>
            <div class="cmp-'.$input['id'].'-preview cmp-preview" '.$diaply_block.'>
                <img src="'.$thumbspacer.'" />
                <a class="cmp-remove-'.$input['id'].'-image icon-remove"></a>
            </div>
            <script type="text/javascript">creative_media_upload("'.$input['id'].'","image", "library", "", "'.$src.'");</script>
		</div>
	</div>';
    if( $echo ){ echo $html; } else{ return $html; }
}
?>
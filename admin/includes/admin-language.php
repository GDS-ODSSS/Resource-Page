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
if (!defined('IN_PHPMEGATEMP_CP')){exit;}

class admin_language
{
    // index language
    public function index_language()
    {
        global $db, $config, $token;
        $download = false;
        $import = false;
        
        if(isset($_POST['addlanguage'])):
            $this->add_language();
            @header("Location:language.php?ms=addlanguage");
        endif;
        
        if(isset($_SESSION['action_token'])):
            $message = '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'.$_SESSION['action_token'].'</div>';
            unset($_SESSION['action_token']);
        else:
            $message = '';
        endif;
        
        admin_header(get_admin_languages('language_manager'));
        admin_content_header(array(
            'title'  => get_admin_languages('language_manager'), 
            'button' => '<a href="language.php?mode=addnew_language" class="btn btn-sm btn-primary">'.get_admin_languages('add_new').'</a>'
        ));
        echo admin_content_section_start().'<div class="col-md-12">'.$message.'';
        echo '
        <table id="sample-table-2" class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th style="width: 50px;text-align: center;">ID</th>
                    <th>'.get_admin_languages('language_name').'</th>
                    <th style="text-align: center;">'.get_admin_languages('country_abbreviation').'</th>
                    <th style="text-align: center;">'.get_admin_languages('language_direction').'</th>
                    <th style="text-align: center;">'.get_admin_languages('author').'</th>
					<th style="text-align: center;">'.get_admin_languages('phrases').'</th>
                    <th style="text-align: center;">'.get_admin_languages('options').'</th>
				</tr>
			</thead>
            <tbody>
        ';
        $admin_loop_language = '';
        $result = $db->sql_query("SELECT * FROM ".LANGUAGE_TABLE." ORDER BY id ASC");
        while ($row = $db->sql_fetchrow($result)) 
        {
            echo '
            <tr>
                <td style="text-align: center;">'.$row['id'].'</td>
                <td>'.$row['name'].'</td>
                <td style="text-align: center;">'.$row['flag'].'</td>
                <td style="text-align: center;">'.$row['langdir'].'</td>
                <td style="text-align: center;">'.$row['author'].'</td>
                <td style="text-align: center;">'.$this->get_count_phrases($row['id']).'</td>
                <td style="text-align: center;">
                    <a href="language.php?edit_language='.$row['id'].'" class="btn btn-sm btn-success">'.get_admin_languages('edit').'</a>
                    <a href="language.php?edit_phrase='.$row['id'].'" class="btn btn-sm btn-success">'.get_admin_languages('edit_phrase').'</a>
                    <a href="language.php?action=delete&id='.$row['id'].'&token='.$token.'" onclick="confirm(\'Are you sure you want to delete \''.$row['name'].'\' pack\');" class="btn btn-sm btn-danger">'.get_admin_languages('delete').'</a>
                </td>
            </tr>
            ';
            $admin_loop_language .= '<option value="'.$row['id'].'">'.$row['name'].'</option>';
        }
        
        $admin_main_language = '<option value="global">Global</option>';
        echo '</tbody></table></div></div>
        
        <div class="page-header position-relative"><h1>'.get_admin_languages('add_new_phrase').'</h1></div>
        <form name="form1" method="post" action="language.php">
            <fieldset class="fieldset">
                <div class="row">
                    <div class="col-md-4">
                        <label for="varname">'.get_admin_languages('phrase_code').'</label>
                        <input type="text" name="varname" id="varname" class="form-control col-md-8" />
                    </div>
                    <div class="col-md-4">
                        <label for="languageid">'.get_admin_languages('language').'</label>
                        <select name="languageid" id="languageid" class="form-control col-md-8">'.$admin_loop_language.'</select>
                    </div>
                    <div class="col-md-4">
                        <label for="product">'.get_admin_languages('main_language').'</label>
                        <select name="product" id="product" class="form-control col-md-8">'.$admin_main_language.'</select>
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="col-md-6">
                        <label for="defaulttext">'.get_admin_languages('phrase_default').'</label>
                        <textarea name="defaulttext" class="form-control col-md-12" id="defaulttext" rows="2" dir="ltr"></textarea>
                    </div>
                    <div class="col-md-6">
                        <label for="phrasetext">'.get_admin_languages('phrase_text').'</label>
                        <textarea name="text" class="form-control col-md-12" id="phrasetext" rows="2"></textarea>
                    </div>
                </div>
            </fieldset>
            <br />
            <div class="row">
                <div class="col-md-12">
                    <input type="hidden" name="addlanguage" value="1" />
                    <input type="submit" name="add_phrase" id="add_phrase" value="'.get_admin_languages('add_phrase').'" class="button button-primary" />
                </div>
            </div>
        </form><div>';
        echo admin_content_section_end();
        admin_footer();
    }
    
    function get_count_phrases($langid)
    {
        global $db;
        $sql = "SELECT phraseid FROM ".PHRASE_TABLE." WHERE `languageid`='".$langid."'";
        return $db->sql_numrows($sql);
    }

    // form language
    public function index_form_language()
    {
        global $template, $db, $config;
        
        if(isset($_POST['action']) AND $_POST['action'] == 'addnew'){
            $sql_ins = array(
                'id'        => (int)'', 
                'name'      => safe_input($_POST['name']), 
                'flag'      => sanitize($_POST['flag'], 2), 
                'langdir'   => sanitize($_POST['langdir'], 3), 
                'author'    => sanitize($_POST['author']), 
                'status'    => admin_get_form_status($_POST['status']), 
                'statusshow'=> admin_get_form_status($_POST['statusshow'])
            );                   
            $sql     = 'INSERT INTO ' . LANGUAGE_TABLE . ' ' . $db->sql_build_array('INSERT', $sql_ins, true);
            $result  = $db->sql_query($sql);
            $db->sql_freeresult($result);
            $lang_id = $db->sql_nextid();
            $_SESSION['action_token'] = get_admin_languages('add_language_successfully');
            @header("Location:language.php?edit_language={$lang_id}");
            exit;
        }
        
        if(isset($_POST['action']) AND isset($_POST['id']) AND $_POST['action'] == 'update'){
            $lang_id = $db->sql_escape($_POST['id']);
            $result = $db->sql_query("UPDATE " . LANGUAGE_TABLE . " SET 
                `name`      ='".safe_input($_POST['name'])."',
                `flag`      ='".sanitize($_POST['flag'], 2)."',
                `langdir`   ='".sanitize($_POST['langdir'], 3)."',
                `author`    ='".sanitize($_POST['author'])."',
                `status`    ='".admin_get_form_status($_POST['status'])."',
                `statusshow`='".admin_get_form_status($_POST['statusshow'])."' 
                 WHERE `id` ='".$lang_id."'
            ");
            $db->sql_freeresult($result);
            $_SESSION['action_token'] = get_admin_languages('update_language_successfully');
            @header("Location:language.php?edit_language={$lang_id}");
            exit;
        }
        
        if(isset($_SESSION['action_token'])):
            $message = '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'.$_SESSION['action_token'].'</div>';
            unset($_SESSION['action_token']);
        else:
            $message = '';
        endif;
        
        
        if(isset($_GET['edit_language'])):
            //get_language_column
            $id         = intval($_GET['edit_language']);
            $resultlang = $db->sql_query("SELECT * FROM ".LANGUAGE_TABLE." WHERE `id`='".$id."'");
            $rowlang    = $db->sql_fetchrow($resultlang);
            $title_page = get_admin_languages('editing').' '.$rowlang['name'];
            $title_btn  = get_admin_languages('update');
            $action     = 'update';
        else:
            $title_page     = get_admin_languages('add_language');
            $title_btn      = get_admin_languages('add_language');
            $action         = 'addnew';
            $rowlang['id']  = 0;
        endif;
    
        
        admin_header($title_page);
        admin_content_header(array(
            'title'     => $title_page, 
            'button'    => '<a href="language.php" class="btn btn-mini btn-success">'.get_admin_languages('back').'</a>'
        ));
        echo admin_content_section_start().'<div class="col-md-12">'.$message.'';
        
        $rowlang['id'] = (isset($rowlang['id']))? $rowlang['id'] : '';
        $rowlang['name'] = (isset($rowlang['name']))? $rowlang['name'] : '';
        $rowlang['flag'] = (isset($rowlang['flag']))? $rowlang['flag'] : '';
        $rowlang['langdir'] = (isset($rowlang['langdir']))? $rowlang['langdir'] : '';
        $rowlang['author'] = (isset($rowlang['author']))? $rowlang['author'] : '';
        $rowlang['status'] = (isset($rowlang['status']))? $rowlang['status'] : '';
        $rowlang['statusshow'] = (isset($rowlang['statusshow']))? $rowlang['statusshow'] : '';
        $rowlang['icon'] = (isset($rowlang['icon']))? $rowlang['icon'] : '';
        
        echo '
        <form id="formlang" name="form1" method="post" action="" class="form-horizontal checkbox_form">
            <input type="hidden" name="action" value="'.$action.'" />
            <input type="hidden" name="id" value="'.$rowlang['id'].'" />
                <div class="form-group row">
                    <label for="name" class="col-md-2 control-label">'.get_admin_languages('language_name').'</label>
                    <div class="col-md-10">
                        <input name="name" type="text" class="form-control width320" id="name" value="'.$rowlang['name'].'" />
                    </div>
                </div>
                <div class="form-group row">
                    <label for="flag" class="col-md-2 control-label">'.get_admin_languages('country_abbreviation').'</label>
                    <div class="col-md-10">
                        <input name="flag" type="text" class="form-control width320" id="flag" value="'.$rowlang['flag'].'"/>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="langdir" class="col-md-2 control-label">'.get_admin_languages('language_direction').'</label>
                    <div class="col-md-10">
                        <select name="langdir" id="langdir" class="form-control width320">
                            <option value="ltr" '.get_selected( $rowlang['langdir'], 'ltr', false ).'>LTR</option>
                            <option value="rtl" '.get_selected( $rowlang['langdir'], 'rtl', false ).'>RTL</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="author" class="col-md-2 control-label">'.get_admin_languages('author').'</label>
                    <div class="col-md-10">
                        <input name="author" type="text" class="form-control width320" id="author" value="'.$rowlang['author'].'"/>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="status" class="col-md-2 control-label">'.get_admin_languages('status').'</label>
                    <div class="col-md-10">
                        <input type="checkbox" id="status" class="checkbox-on-of" name="status" '.get_checked($rowlang['status'], '1', false).'>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="status" class="col-md-2 control-label">'.get_admin_languages('showinwebsite').'</label>
                    <div class="col-md-10">
                        <input type="checkbox" id="statusshow" class="checkbox-on-of" name="statusshow" '.get_checked($rowlang['statusshow'], '1', false).'>
                    </div>
                </div>
                <div class="form-group flagicon">
                    <label for="status" class="col-md-2 control-label">'.get_admin_languages('icon').'</label>
                    <div class="col-md-10">
                        <button type="button" class="btn btn-default" name="icon" data-iconset="flagicon" data-icon="'.$rowlang['icon'].'" role="iconpicker" data-search-text="" data-footer="false" data-rows="4" data-cols="6"></button>
                        
                    </div>
                </div>
                
                

                <div class="form-actions">
                    <input type="submit" name="update_language" class="button button-primary" value="'.$title_btn.'" />
                </div>
        </form>
        </div>
        ';
        echo admin_content_section_end();
        admin_footer();
    }
    
    
    
    function index_form_phrase()
    {
        global $template, $db, $config;
        if(isset($_SESSION['action_token'])):
            $message = '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'.$_SESSION['action_token'].'</div>';
            unset($_SESSION['action_token']);
        else:
            $message = '';
        endif;
        admin_header(get_admin_languages('edit_phrase'));
        admin_content_header(array(
            'title'     => get_admin_languages('edit_phrase'), 
            'button'    => '<a href="language.php" class="btn btn-mini btn-success">'.get_admin_languages('back').'</a>'
        ));
        
        
        $lang_id    = intval($_GET['edit_phrase']);
        $lang_name  = get_language_column($lang_id , 'name' );
        $sql        = "SELECT * FROM ".PHRASE_TABLE." WHERE `languageid`='".$lang_id."' GROUP BY `product` ORDER BY `product` ASC";
        $result     = $db->sql_query($sql);
        
        $product    = (isset($_REQUEST['product']))? safe_input($_REQUEST['product']) : "";
        $where      = ($product)? " AND `product`='{$product}' " : "";
        $viewing_main = ($product)? $product : get_admin_languages('main_language') ;        
                
        $select_main_language = '<option value="0">'.get_admin_languages('all_phrases').'</option>';
        while ($row = $db->sql_fetchrow($result)) 
        {
            $select_main_language .= '<option value="'.$row['product'].'" '.get_selected($product, $row['product'], false).'>'.$row['product'].'</option>';
        }
        
        echo admin_content_section_start().'<div class="col-md-12">'.$message.'
        <div class="box box-default color-palette-box">
            <div class="box-header with-border">
                <h3 class="box-title">
                    '.get_admin_languages('viewing_language_phrases').' ['.$lang_name.'] / 
                    <span id="viewing_main">'.$viewing_main.'</span>
                </h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group row">
                            <label for="langdir" class="col-md-5 control-label">'.get_admin_languages('main_language').'</label>
                            <div class="col-md-7">
                                <select name="langdir" id="langdir" class="form-control select_main_language_actions">'.$select_main_language.'</select>
                            </div>
                        </div>
                    </div>
                </div>
                <hr />
                <div class="">
                    <div class="col-md-12">
                    <table id="jq-table" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th style="'.get_text_align(1).'">'.get_admin_languages('phrase').'</th>
                                <th style="text-align: center;width: 160px;">'.get_admin_languages('main_language').'</th>
                            </tr>
                        </thead>
                        <tbody>';
                
                
                $sql        = "SELECT * FROM ".PHRASE_TABLE." WHERE `languageid`='".$lang_id."' {$where} ORDER BY varname ASC";
                $result     = $db->sql_query($sql);
                while ($row = $db->sql_fetchrow($result)) 
                {
                    echo '
                    <tr>
                        <td style="'.get_text_align(1).'">
                            <ul class="list-table-language">
                                <li><label>'.get_admin_languages('phrase_code').'</label> : '.$row['varname'].' - {LANG_'.strtoupper($row['varname']).'}</li>
                                <li><label>'.get_admin_languages('phrase_default').'</label> : '.$row['defaulttext'].'</li>
                                <li><label>'.get_admin_languages('phrase_text').'</label> : 
                                    <span class="col-phrase-text-'.$row['phraseid'].'"><span id="phrase-text-'.$row['phraseid'].'">'.$row['text'].'</span> <span  id="edit-'.$row['phraseid'].'" data-id="'.$row['phraseid'].'" class="btn btn-xs btn-success edit_phrase">'.get_admin_languages('edit').'</span></span>
                                    <div style="display: inline-block;width: 70%;vertical-align: bottom;">
                                        <div id="divinput-'.$row['phraseid'].'" style="display: none;">
                                            <div class="input-group">
                                                <input type="text" class="form-control input-sm" id="input-'.$row['phraseid'].'" name="pk" value="'.$row['text'].'" />
                                                <span class="input-group-btn">
                                                    <button class="btn btn-success btn-flat save_phrase" data-id="'.$row['phraseid'].'">'.get_admin_languages('save').'</button>
                                                    <button class="btn btn-danger btn-flat cancel_phrase" data-id="'.$row['phraseid'].'">'.get_admin_languages('cancel').'</button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </td>
                        <td style="text-align: center;">'.$row['product'].'</td>
                    </tr>';
                }
        
                
                
                echo '</tbody></table></div></div>
            </div>
        </div>
        <script type="text/javascript">
        $(document).ready(function(){
            $(".edit_phrase").on("click",function(){var _this = $(this), id = _this.data("id");$(".col-phrase-text-"+id).hide();$("#divinput-"+id).show();});
            $(".cancel_phrase").on("click",function(){var _this = $(this), id = _this.data("id");$("#divinput-"+id).hide();$(".col-phrase-text-"+id).show();});
            $(".save_phrase").on("click",function(){
                var _this = $(this), btntext = $(this).html(), id = _this.data("id"), phrasetext = $("#input-"+id).val(), phraseid = id;
                _this.html(\'<i class="fas fa-circle-notch fa-spin fa-fw"></i> \'+btntext);
                ajaxRequests.push = $.ajaxQueue({url:admin_ajax_url, data:"action=savephrase&phrasetext="+phrasetext+"&phraseid="+phraseid, 
                    success	: function() {_this.html(btntext);$("#phrase-text-"+id).html(phrasetext);$("#divinput-"+id).hide();$(".col-phrase-text-"+id).show();}
                });
            });
            $("#jq-table").DataTable({"columns": [ null, null ]});
            $(".select_main_language_actions").on("change", function() {if(this.value == "0"){window.location.href = "language.php?edit_phrase='.$lang_id.'";}else{window.location.href = "language.php?edit_phrase='.$lang_id.'&product="+this.value;}});
            
        });
            
        </script>
        </div>';
        echo $this->select_copy_phrase_actions($lang_id);
        
        echo admin_content_section_end();
        admin_footer();
    }
    
    function select_copy_phrase_actions($id)
    {
        global $db, $token;
        $html  = '<form action="language.php?copy_phrase=copy" method="post">';
        $html .= '<input type="hidden" name="token" value="'.$token.'">';
        $html .= '<input type="hidden" name="copytolangid" value="'.$id.'">';
        $html .= '<input type="hidden" name="action" value="copyphrase">';
        $html .= '<select name="langid" class="form-control input-sm select_actions" style="width: 180px;">';
        $html .= '<option value="-1">'.get_admin_languages('copy_pharses_from').'</option>';
        $result = $db->sql_query("SELECT * FROM ".LANGUAGE_TABLE." WHERE `id`!='".$id."'");
        while($row = $db->sql_fetchrow($result))
        {
            $html .= '<option value="'.$row['id'].'">'.$row['name'].'</option>';
        }
        $html .= '</select>';
        $html .= '&nbsp;<input type="submit" class="btn btn-sm btn-primary" value="'.get_admin_languages('apply').'" onclick=return confirm("'.get_admin_languages('confirm_action').'"); />';
        $html .= '</form>';
        $actionselect = '<script type="text/javascript">$(function(){$(".actionselect").html(\''.$html.'\')});</script>';
        return $actionselect;
    }
    
    function copy_phrase()
    {
        global $db;
        $langid     = safe_input($_POST['langid']);
        $tolangid   = safe_input($_POST['copytolangid']);
        echo $langid.'::'.$tolangid.'<br />';
        $sql        = "SELECT * FROM ".PHRASE_TABLE." WHERE `languageid`='".$langid."' ORDER BY `varname` ASC";
        $result     = $db->sql_query($sql);
        while($row = $db->sql_fetchrow($result))
        {
            echo $this->is_phrase_copy($row['varname'], $tolangid).'<br />';
            if(!$this->is_phrase_copy($row['varname'], $tolangid))
            {
                $this->insert_phrase_copy($row, $tolangid);
            }
        }
        $_SESSION['action_token'] = get_admin_languages('copy_phrases_successfully');
        @header("Location:language.php?edit_phrase={$tolangid}");
        exit;        
    }
    
    function is_phrase_copy($varname, $tolangid)
    {
        global $db;
        $sql  = "SELECT * FROM ".PHRASE_TABLE." WHERE `languageid`='".$tolangid."' and `varname`='".$varname."'";
        return $db->sql_numrows($sql);
    }
    
    function insert_phrase_copy($row, $tolangid)
    {
        global $db;
        if($tolangid)
        {
            $sql_ins = array(
                'phraseid'      => (int)'', 
                'languageid'    => $tolangid, 
                'varname'       => $row['varname'], 
                'defaulttext'   => $row['defaulttext'], 
                'text'          => $row['defaulttext'], 
                'product'       => $row['product'], 
                'dateline'      => (int)time(),
            );                   
            $sql     = 'INSERT INTO ' . PHRASE_TABLE . ' ' . $db->sql_build_array('INSERT', $sql_ins, true);
            $result  = $db->sql_query($sql);
            $db->sql_freeresult($result);
        }
    }

    public function add_language()
    {
        global $db;
        $varname        = preg_replace('/[^\w\._]+/', '_', strtolower($_POST['varname']));
        $defaulttext    = safe_input($_POST['defaulttext']);
        $text           = safe_input($_POST['text']);
        $sql_ins = array(
            'phraseid'      => (int)'', 
            'languageid'    => $_POST['languageid'], 
            'varname'       => $varname, 
            'defaulttext'   => $defaulttext, 
            'text'          => $text, 
            'product'       => $_POST['product'], 
            'dateline'      => (int)time(),
        );                   
        $sql     = 'INSERT INTO ' . PHRASE_TABLE . ' ' . $db->sql_build_array('INSERT', $sql_ins, true);
        $result  = $db->sql_query($sql);
        $db->sql_freeresult($result);
    }
    // active language
    public function active_language()
    {
        global $template, $db, $config;
        $get_status = $_REQUEST['status'];
        $id     = intval($_GET['id']);
        if($get_status == 'disable'){$status = 0;$_SESSION['action_token'] = get_admin_languages('disable_language_successfully');}
        elseif($get_status == 'enable'){$status = 1;$_SESSION['action_token'] = get_admin_languages('enable_language_successfully');}
        else {$get_status = 1;$_SESSION['action_token'] = 'none';}
        $result = $db->sql_query("UPDATE " . LANGUAGE_TABLE . " SET `status`='".$status."' WHERE `id`='".$db->sql_escape($id)."'");
        $db->sql_freeresult($result);
        @header("Location:".THIS_SCRIPT);
    }
    // delete language
    public function delete_language()
    {
        global $template, $db, $config;
        $id     = intval($_GET['id']);
        if($_REQUEST['token'] == $_SESSION['securitytokenadmincp']):
            $result = $db->sql_query("DELETE FROM " . PHRASE_TABLE . "  WHERE `languageid`='".$db->sql_escape($id)."'");
            $db->sql_freeresult($result);
            $result = $db->sql_query("DELETE FROM " . LANGUAGE_TABLE . "  WHERE `id`='".$db->sql_escape($id)."'");
            $db->sql_freeresult($result);
            $_SESSION['action_token'] = get_admin_languages('delete_language_successfully');
        endif;
        header("Location:".THIS_SCRIPT."");
    }
}
?>
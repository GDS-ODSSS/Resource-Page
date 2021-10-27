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
if (!defined("IN_PHPMEGATEMP"))
      die('Direct access to this location is not allowed.');


// get id language defaultd 
function get_language_defaultd($column = 'id')
{
    global $db, $config;
    $result     = $db->sql_query("SELECT {$column} FROM ".LANGUAGE_TABLE." LIMIT 1");
    $row        = $db->sql_fetchrow($result);
    $defaultd   = ($config['language'])? $config['language'] : $row[$column] ;
    $language   = (get_language_site())? get_language_site() : $defaultd ;
    return $language;
}
// get id language column 
function get_language_column($id , $column = 'name' )
{
    global $db, $config;
    $result = $db->sql_query("SELECT {$column} FROM ".LANGUAGE_TABLE." WHERE `id`='{$id}'");
    $row    = $db->sql_fetchrow($result);
    return $row[$column];
}
// get id language column 
function get_language_id_by_column($column = 'name', $value = '' )
{
    global $db, $config;
    $result = $db->sql_query("SELECT id FROM ".LANGUAGE_TABLE." WHERE `{$column}`='{$value}'");
    $row    = $db->sql_fetchrow($result);
    return $row['id'];
}
// get fetch language
function get_fetch_language($column = 'flag')
{
    global $db;
    $fetch  = array();
    $result = $db->sql_query("SELECT {$column} FROM ".LANGUAGE_TABLE." WHERE `status`='1'");
    while($row = $db->sql_fetchrow($result))
    {
        $fetch[] = $row[$column];
    }
    return $fetch;
}
// get language site
function get_language_site()
{
    global $db;
    if(isset($_COOKIE['LANG_PHP_HELP_CENTER']))
    {
        $sel_lang = sanitize($_COOKIE['LANG_PHP_HELP_CENTER'], 2);
        $vlang = get_fetch_language();
        if(in_array($sel_lang, $vlang))
        {
            return get_language_id_by_column('flag', $sel_lang);
        } 
        else
        {
            return false;
        }
    }
    else
    {
        return false;
    }
}
// get languages DIR (direction)
function get_language_direction($langid = false)
{
    global $db;
    $language   = ($langid)? $langid : get_language_defaultd();
    $result     = $db->sql_query("SELECT langdir FROM ".LANGUAGE_TABLE." WHERE `id`='{$language}'");
    $row        = $db->sql_fetchrow($result);
    return $row['langdir'];
}
// get languages CODE (country abbreviation)
function get_language_country_abbreviation($langid = false)
{
    global $db;
    $language   = ($langid)? $langid : get_language_defaultd();
    $result     = $db->sql_query("SELECT flag FROM ".LANGUAGE_TABLE." WHERE `id`='{$language}'");
    $row        = $db->sql_fetchrow($result);
    return $row['flag'];
}
// get languages
function get_languages($varname = false)
{
    global $db;
    $language = get_language_defaultd();
    if($varname)
    {
        $sql    = "SELECT text FROM ".PHRASE_TABLE." WHERE `varname`='{$varname}' and `languageid`='{$language}'";
        $result = $db->sql_query($sql);
        $row    = $db->sql_fetchrow($result);
        $text   = ($row['text'])? $row['text'] : add_phrase_notfound($varname);
        return $text;
    }
    else
    {
        return 'Eror Lang 103!';
    }
}
// assign languages
function get_assign_languages($attr = 'LANG_', $product = false, $attr_product = '')
{
    global $template, $db;
    $language = get_language_defaultd();
    $where = ($product)? " AND `product`={$product}" : '';
    $sql    = "SELECT varname,defaulttext,text FROM ".PHRASE_TABLE." WHERE `languageid`='{$language}' {$where}";
    $result = $db->sql_query($sql);
    while($row = $db->sql_fetchrow($result))
    {
        $text = ($row['text'])? $row['text'] : $row['defaulttext'];
        $template->assign_var(strtoupper($attr.$attr_product.$row['varname']),$text);
    }
    $db->sql_freeresult($result);
}
// assign languages
function get_all_languages()
{
    global $db;
    $lang = array();
    $language = get_language_defaultd();
    $sql    = "SELECT varname,defaulttext,text FROM ".PHRASE_TABLE." WHERE `languageid`='{$language}'";
    $result = $db->sql_query($sql);
    while($row = $db->sql_fetchrow($result))
    {
        $text = ($row['text'])? $row['text'] : '??';
        $lang[$row['varname']] = $text;
    }
    $db->sql_freeresult($result);
    return $lang;
}
// get id language defaultd 
function get_admin_language_defaultd($column = 'id')
{
    global $db, $config;
    $result     = $db->sql_query("SELECT {$column} FROM ".LANGUAGE_TABLE." LIMIT 1");
    $row        = $db->sql_fetchrow($result);
    $language   = ($config['language'])? $config['language'] : $row[$column] ;
    return $language;
}
// get admin languages
function get_admin_languages($varname = false)
{
    global $db;
    $language = get_admin_language_defaultd();
    if($varname)
    {
        $sql    = "SELECT text FROM ".PHRASE_TABLE." WHERE `varname`='{$varname}' and `languageid`='{$language}'";
        $result = $db->sql_query($sql);
        $row    = $db->sql_fetchrow($result);
        $phrase = (isset($row['text']))? $row['text'] : add_phrase_notfound($varname);
        return $phrase;
    }
    else
    {
        return 'Eror Lang 103!';
    }
}
// get admin languages array
function get_admin_languages_array()
{
    global $db;
    $array      = array();
    $language   = get_language_defaultd();
    $result     = $db->sql_query("SELECT * FROM ".LANGUAGE_TABLE."");
    while($row  = $db->sql_fetchrow($result))
    {
        $array[$row['id']] = array('id' => $row['id'], 'name' => $row['name'], 'dir' => $row['langdir'], 'defaultd' => $language, 'flag' => $row['flag']);
    }
    return $array;
}
// add phrase if not found
function add_phrase_notfound($varname)
{
    global $db;
    $varname        = str_replace(' ', '_', strtolower($varname));
    $defaulttext    = safe_input($varname);
    $text           = str_replace('_', ' ', $varname);
    $result         = $db->sql_query("SELECT id FROM ".LANGUAGE_TABLE."");
    while($row = $db->sql_fetchrow($result))
    {
        $sql_ins = array(
            'phraseid'      => (int)'', 
            'languageid'    => $row['id'], 
            'varname'       => $varname, 
            'defaulttext'   => $defaulttext, 
            'text'          => $text, 
            'product'       => 'global', 
            'dateline'      => (int)time(),
        );                   
        $sql     = 'INSERT INTO ' . PHRASE_TABLE . ' ' . $db->sql_build_array('INSERT', $sql_ins, true);
        $db->sql_query($sql);
    }
    return $text;
}

// install phrase
function install_extension_phrase($phrase = array())
{
    global $db;
    $result = $db->sql_query("SELECT id FROM ".LANGUAGE_TABLE."");
    while($row = $db->sql_fetchrow($result))
    {
        $lang_id = $row['id'];
        foreach($phrase as $key => $value)
        {
            $varname    = $value['varname'];
            $is_phrase  = $db->sql_numrows("SELECT * FROM ".PHRASE_TABLE." WHERE `varname`='{$varname}' and `languageid`='{$lang_id}'");
            if(!$is_phrase)
            {
                $sql_ins = array(
                    'phraseid'      => (int)'', 
                    'languageid'    => $lang_id, 
                    'varname'       => $varname, 
                    'defaulttext'   => $value['defaulttext'], 
                    'text'          => $value['text'], 
                    'product'       => $value['product'], 
                    'dateline'      => (int)time(),
                );                   
                $sql     = 'INSERT INTO ' . PHRASE_TABLE . ' ' . $db->sql_build_array('INSERT', $sql_ins, true);
                $db->sql_query($sql);
            }
        }
    }
}
?>
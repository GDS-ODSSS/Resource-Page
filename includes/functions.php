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




function get_dd($dd)
{
    if(is_array($dd) or is_object($dd))
    {
        echo '<pre>';
        print_r($dd);
        echo '</pre>';
    }
    else
    {
        echo '<pre>';
        echo $dd;
        echo '</pre>';
    }
}
//
function get_post_type()
{
    global $hooks;
    $post_type = array();
    if($hooks->has_filter('supports_actions_post_type')):
        $post_type_filters = $hooks->apply_filters( 'supports_actions_post_type', $post_type );
    else:
        $post_type_filters = $post_type;
    endif;
    foreach($post_type_filters as $k => $v)
    {
        $type[$v] = $v;
    }
    return  $type;
}
// set counter
function set_counter($meta_key = 'visits')
{
    if (!defined('IN_PHPMEGATEMP_CP')){
        global $db;
         $sql_ins = array(
            'id'        => (int)'',
            'meta_key'  => $meta_key,
            'ip'        => get_real_ipaddress(),
            'modified'  => time(),
        );
        $sql     = 'INSERT INTO ' . COUNTER_TABLE . ' ' . $db->sql_build_array('INSERT', $sql_ins, true);
        $result  = $db->sql_query($sql);
        $db->sql_freeresult($result);
    }
}
// get url extensions
function get_url_extensions($path)
{
    global $config;
    return $config['siteurl'].'/extensions/'.$path;
}
// get uri extensions
function get_uri_extensions($dirname, $path)
{
    return dirname(__FILE__).$path;
}
// get url template
function get_url_template($path = '')
{
    global $config;
    return $config['siteurl'].'/'.THEME_PATH.$config['sitethemes'].'/'.$path;
}
// get actions enqueue style
function get_actions_enqueue_style()
{
    global $hooks, $template;
    $style          = array();
    $return_style   = '';
    if($hooks->has_filter('supports_enqueue_style')):
        $style_array = $hooks->apply_filters( 'supports_enqueue_style', $style );
    else:
        $style_array = $style;
    endif;
    foreach($style_array AS $key => $value)
    {
        $return_style .= '<link rel="stylesheet" href="'.$value.'" />';
    }
    $template->assign_var('ENQUEUE_STYLE', $return_style); 
}
// get actions enqueue script
function get_actions_enqueue_script()
{
    global $hooks, $template;
    $script         = array();
    $return_script  = '';
    if($hooks->has_filter('supports_enqueue_script')):
        $script_array = $hooks->apply_filters( 'supports_enqueue_script', $script );
    else:
        $script_array = $script;
    endif;
    foreach($script_array AS $key => $value)
    {
        $return_script .= '<script type="text/javascript" src="'.$value.'"></script>';
    }
    $template->assign_var('ENQUEUE_SCRIPT', $return_script); 
}
// location last page
function location_lastpage($url, $header = true)
{
    global $db, $config, $template;
    $permanent_link_type = get_permanent_link_type();
    $link = $config['siteurl'].'/'.$url;
    if($header)
    {
        header("Location:{$link}");
        exit;
    }
    else
    {
        return $link;
    }
}
// filter content strip tags
function filter_content_strip_tags($content)
{
    return strip_tags($content, '<p><a><img><strong><b><i><code><em><span><ol><ul><li><blockquote><pre><br>');   
}
// number abbreviation
function number_abbreviation($number) {
    if($number)
    {
        $abbrevs = array(12 => "T", 9 => "B", 6 => "M", 3 => "K", 0 => "");
        foreach($abbrevs as $exponent => $abbrev) {
            if($number >= pow(10, $exponent)) {
            	$display_num = $number / pow(10, $exponent);
            	$decimals = ($exponent >= 3 && round($display_num) < 100) ? 1 : 0;
                return number_format($display_num,$decimals) . $abbrev;
            }
        }
    }
    else
    {
        return $number;
    }
}
// accesspress letter count
function accesspress_letter_count($content, $limit) {
	$striped_content = strip_tags($content);
	$limit_content = mb_substr($striped_content, 0 , $limit );
	if($limit_content < $content){
		$limit_content .= "..."; 
	}
	return unsanitize_text($limit_content);
}
// get pagination url
function get_pagination_url($pagename, $pagenum)
{
    global $db, $config, $template;
    $userid = get_session_userid();
    $permanent_link_type = get_permanent_link_type();
    $link = "{$config['siteurl']}/{$pagename}/page/{$pagenum}";
    return $link;

}
// pagination
function pagination($total, $limit = 10,$page = 1, $pagename = ''){
    global $db,$config;
    $adjacents  = "2";
	$page       = ($page == 0 ? 1 : $page);
	$start      = ($page * $limit) - $limit;
	$prev       = $page - 1;
	$next       = $page + 1;
    $lastpage   = ceil($total/$limit);
	$lpm1       = $lastpage - 1;
    $pagination = '';
	if($lastpage > 1){ 
		if ($lastpage < 7 + ($adjacents * 2)){
            for ($counter = 1; $counter <= $lastpage; $counter++){
                if($counter == $page){$pagination.= "<span class='page-numbers current'>$counter</span>";}
				else{$pagination.= "<a class='page-numbers' href='".get_pagination_url($pagename,$counter)."'>$counter</a>";}
			}
		}
		elseif($lastpage > 5 + ($adjacents * 2)){
            if($page < 1 + ($adjacents * 2)){
                for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++){
					if($counter == $page){$pagination.= "<span class='page-numbers current'>$counter</span>";}
					else{$pagination.= "<a class='page-numbers' href='".get_pagination_url($pagename,$counter)."'>$counter</a>";}
				}
				$pagination.= '<span class="page-numbers dots">…</span>';
				$pagination.= "<a class='page-numbers' href='".get_pagination_url($pagename,$lpm1)."'>$lpm1</a>";
				$pagination.= "<a class='page-numbers' href='".get_pagination_url($pagename,$lastpage)."'>$lastpage</a>";
			}
			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)){
                $pagination.= "<a class='page-numbers' href='".get_pagination_url($pagename,1)."'>1</a>";
				$pagination.= "<a class='page-numbers' href='".get_pagination_url($pagename,2)."'>2</a>";
				$pagination.= '<span class="page-numbers dots">…</span>';
				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++){
					if($counter == $page){$pagination.= "<span class='page-numbers current'>$counter</span>";}
					else{$pagination.= "<a class='page-numbers' href='".get_pagination_url($pagename,$counter)."'>$counter</a>";}
				}
				$pagination.= "<a class='page-numbers' href='".get_pagination_url($pagename,$lpm1)."'>$lpm1</a>";
				$pagination.= "<a class='page-numbers' href='".get_pagination_url($pagename,$lastpage)."'>$lastpage</a>";
			}
			else{
                $pagination.= "<a class='page-numbers' href='".get_pagination_url($pagename,1)."'>1</a>";
				$pagination.= "<a class='page-numbers' href='".get_pagination_url($pagename,2)."'>2</a>";
				$pagination.= '<span class="page-numbers dots">…</span>';
				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++){
					if($counter == $page){$pagination.= "<span class='page-numbers current'>$counter</span>";}
					else{$pagination.= "<a class='page-numbers' href='".get_pagination_url($pagename,$counter)."'>$counter</a>";}
				}
			}
		}
	}
    return $pagination;
}
// validate email
function validate_email($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL);
} 
// is ssl
function is_ssl($url) {
	if ( isset($_SERVER['HTTPS']) ) {
		if ( 'on' == strtolower($_SERVER['HTTPS']) )
			return true;
		if ( '1' == $_SERVER['HTTPS'] )
			return true;
	} elseif ( isset($_SERVER['SERVER_PORT']) && ( '443' == $_SERVER['SERVER_PORT'] ) ) {
		return true;
	}
	return false;
}
// get file dir url
function get_file_dir_url()
{
    $scheme = is_ssl() ? 'https' : 'http';
}
// get normalize path
function get_normalize_path( $path ) {
	$path = str_replace( '\\', '/', $path );
	$path = preg_replace( '|/+|','/', $path );
	return $path;
}
// is mobile
function is_mobile() {
	if ( empty($_SERVER['HTTP_USER_AGENT']) ) {
		$is_mobile = false;
	} elseif ( strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile') !== false // many mobile devices (all iPhone, iPad, etc.)
		|| strpos($_SERVER['HTTP_USER_AGENT'], 'Android') !== false
		|| strpos($_SERVER['HTTP_USER_AGENT'], 'Silk/') !== false
		|| strpos($_SERVER['HTTP_USER_AGENT'], 'Kindle') !== false
		|| strpos($_SERVER['HTTP_USER_AGENT'], 'BlackBerry') !== false
		|| strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mini') !== false
		|| strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mobi') !== false ) {
			$is_mobile = true;
	} else {
		$is_mobile = false;
	}
	return $is_mobile;
}
// maybe unserialize
function maybe_unserialize( $original ) {
	if ( is_serialized( $original ) ) // don't attempt to unserialize data that wasn't serialized going in
		return @unserialize( $original );
	return $original;
}
// is serialized
function is_serialized( $data, $strict = true ) {
	if ( ! is_string( $data ) ) {return false;}
	$data = trim( $data );
 	if ( 'N;' == $data ) {return true;}
	if ( strlen( $data ) < 4 ) {return false;}
	if ( ':' !== $data[1] ) {return false;}
	if ( $strict ) {
		$lastc = substr( $data, -1 );
		if ( ';' !== $lastc && '}' !== $lastc ) {return false;}
	} else {
		$semicolon = strpos( $data, ';' );
		$brace     = strpos( $data, '}' );
		if ( false === $semicolon && false === $brace ) {return false;}
		if ( false !== $semicolon && $semicolon < 3 ) {return false;}
		if ( false !== $brace && $brace < 4 ) {return false;}
	}
	$token = $data[0];
	switch ( $token ) {
		case 's' :
			if ( $strict ) {
				if ( '"' !== substr( $data, -2, 1 ) ) {
					return false;
				}
			} elseif ( false === strpos( $data, '"' ) ) {
				return false;
			}
		case 'a' :
		case 'O' :
			return (bool) preg_match( "/^{$token}:[0-9]+:/s", $data );
		case 'b' :
		case 'i' :
		case 'd' :
			$end = $strict ? '$' : '';
			return (bool) preg_match( "/^{$token}:[0-9.E-]+;$end/", $data );
	}
	return false;
}
// is serialized string
function is_serialized_string( $data ) {
	if ( ! is_string( $data ) ) {
		return false;
	}
	$data = trim( $data );
	if ( strlen( $data ) < 4 ) {
		return false;
	} elseif ( ':' !== $data[1] ) {
		return false;
	} elseif ( ';' !== substr( $data, -1 ) ) {
		return false;
	} elseif ( $data[0] !== 's' ) {
		return false;
	} elseif ( '"' !== substr( $data, -2, 1 ) ) {
		return false;
	} else {
		return true;
	}
}
// maybe serialize
function maybe_serialize( $data ) {
	if ( is_array( $data ) || is_object( $data ) )
		return serialize( $data );
	if ( is_serialized( $data, false ) )
		return serialize( $data );
	return $data;
}

function get_size_format( $bytes, $decimals = 0 ) {
	$quant = array(
		'TB' => TB_IN_BYTES,
		'GB' => GB_IN_BYTES,
		'MB' => MB_IN_BYTES,
		'KB' => KB_IN_BYTES,
		'B'  => 1,
	);

	if ( 0 === $bytes ) {
		return number_format( 0, $decimals ) . ' B';
	}

	foreach ( $quant as $unit => $mag ) {
		if ( doubleval( $bytes ) >= $mag ) {
			return number_format( $bytes / $mag, $decimals ) . ' ' . $unit;
		}
	}

	return false;
}
// get formatted filesize
function get_formatted_filesize($value, $string_only = true, $allowed_units = false)
{
	$available_units = array(
		'tb' => array(
			'min' 		=> 1099511627776, // pow(2, 40)
			'index'		=> 4,
			'si_unit'	=> 'TB',
			'iec_unit'	=> 'TIB',
		),
		'gb' => array(
			'min' 		=> 1073741824, // pow(2, 30)
			'index'		=> 3,
			'si_unit'	=> 'GB',
			'iec_unit'	=> 'GIB',
		),
		'mb' => array(
			'min'		=> 1048576, // pow(2, 20)
			'index'		=> 2,
			'si_unit'	=> 'MB',
			'iec_unit'	=> 'MIB',
		),
		'kb' => array(
			'min'		=> 1024, // pow(2, 10)
			'index'		=> 1,
			'si_unit'	=> 'KB',
			'iec_unit'	=> 'KIB',
		),
		'b' => array(
			'min'		=> 0,
			'index'		=> 0,
			'si_unit'	=> 'BYTES', // Language index
			'iec_unit'	=> 'BYTES',  // Language index
		),
	);
    foreach ($available_units as $si_identifier => $unit_info)
	{
		if (!empty($allowed_units) && $si_identifier != 'b' && !in_array($si_identifier, $allowed_units))
		{
			continue;
		}
		if ($value >= $unit_info['min'])
		{
			$unit_info['si_identifier'] = $si_identifier;
			break;
		}
	}
	unset($available_units);
	for ($i = 0; $i < $unit_info['index']; $i++)
	{
		$value /= 1024;
	}
	$value = round($value, 2);
    $unit_info['si_unit'] = $unit_info['si_unit'];
	$unit_info['value'] = $value;
    // Default to IEC
	$unit_info['unit'] = $unit_info['si_unit'];
	if (!$string_only)
	{
		$unit_info['value'] = $value;
		return $unit_info;
	}
	return $value  . ' ' . $unit_info['unit'];
}
// get real ipaddress
function get_real_ipaddress()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])){
      $ip = $_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
      $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else{
      $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}
// request header
function request_header($header_name, $default = '')
{
	$var_name = 'HTTP_' . str_replace('-', '_', strtoupper($header_name));
	return request_server($var_name, $default);
}
// request server
function request_server($var_name, $default = '')
{
    $var = getenv($var_name);
	return $var;
}
// safe input
function safe_input($str)
{
    global $db, $xssClean;

    $str        = $xssClean->clean_input($str);
    $str        = strip_tags($str);
    $str        = addslashes($str);
    $search     = array("'",'"',"<",">",";");
    $replace    = array("","","","","");
    $str        = str_replace($search, $replace, $str);
    $str        = trim($str);
    return $db->sql_escape($str);
}

// safe textarea
function safe_textarea($str)
{
    global $db;
    $str        = unsanitize_text($str);
    $str        = strip_tags($str);
    $str        = addslashes($str);
    $search     = array("'",'"',"<",">",";");
    $replace    = array("","","","","");
    $str        = str_replace($search, $replace, $str);
    $str        = trim($str);
    return $db->sql_escape($str);
}
// security
function security($entry,$type='text')
{
    global $db, $xssClean;
    $entry = $xssClean->clean_input($entry);

    switch($type){
        case 'text':
            return strip_tags($db->sql_escape(trim($entry)));
        break;
        case 'password':
            return strip_tags($db->sql_escape($entry));
        break;
        case 'number':
            return intval(abs(trim($entry)));
        break;
        case 'array':
            $array = array();
            for($i=0;$i<count($entry);$i++)
            {
                $array[] = strip_tags($db->sql_escape(trim($entry[$i])));
            }
            return $array;
        break;
    }
}
// security token
function securitytoken($length = '15',$num = false)
{
   if($num)
   {
       $chars = '0123456789';
   }
   else
   {
       $chars = 'abcdefghkmnopqrstuvwxyz1234567890ABCDEFGHIJKLMNOQRSTUVXYZ';
   }
   srand((double)microtime()*1000000);
   $str = '';
   for($i=0 ; $i <= $length ;$i++){
       $num = rand() % 33;
       $tmp = substr($chars,$num,1);
       $str = $str. $tmp;
   }
   return $str;
}
// get time ago
function get_timeago( $ptime )
{
    global $lang;
    $estimate_time = time() - $ptime;
    if( $estimate_time < 1 )
    {
        return sprintf($lang['less_than_second_ago']);
    }
    $condition = array( 
                12 * 30 * 24 * 60 * 60  =>  $lang['year'],
                30 * 24 * 60 * 60       =>  $lang['month'],
                24 * 60 * 60            =>  $lang['day'],
                60 * 60                 =>  $lang['hour'],
                60                      =>  $lang['minute'],
                1                       =>  $lang['second']
    );
    foreach( $condition as $secs => $str )
    {
        $d = $estimate_time / $secs;
        if( $d >= 1 )
        {
            $r = round( $d );
            return sprintf($lang['time_ago'], $r, $str);
        }
    }
}
// get (date, time) format
function get_date_time_format($date)
{
    global $lang;

    $format['Sunday']      = $lang['sunday'];
	$format['Monday']      = $lang['monday'];
	$format['Tuesday']     = $lang['tuesday'];
	$format['Wednesday']   = $lang['wednesday'];
	$format['Thursday']    = $lang['thursday'];
	$format['Friday']      = $lang['friday'];
	$format['Saturday']    = $lang['saturday'];
	$format['Sun']         = $lang['sun'];
	$format['Mon']         = $lang['mon'];
	$format['Tue']         = $lang['tue'];
	$format['Wed']         = $lang['wed'];
	$format['Thu']         = $lang['thu'];
	$format['Fri']         = $lang['fri'];
	$format['Sat']         = $lang['sat'];
    $format['January']     = $lang['january'];
	$format['February']    = $lang['february'];
	$format['March']       = $lang['march'];
	$format['April']       = $lang['april'];
	$format['May']         = $lang['may'];
	$format['June']        = $lang['june'];
	$format['July']        = $lang['july'];
	$format['August']      = $lang['august'];
	$format['September']   = $lang['september'];
	$format['October']     = $lang['october'];
	$format['November']    = $lang['november'];
	$format['December']    = $lang['december'];
    $format['Jan']         = $lang['jan'];
	$format['Feb']         = $lang['feb'];
	$format['Mar']         = $lang['mar'];
	$format['Apr']         = $lang['apr'];
	$format['Jun']         = $lang['jun'];
	$format['Jul']         = $lang['jul'];
	$format['Aug']         = $lang['aug'];
	$format['Sep']         = $lang['sep'];
	$format['Oct']         = $lang['oct'];
	$format['Nov']         = $lang['nov'];
	$format['Dec']         = $lang['dec'];
    $format['am']          = $lang['am'];
	$format['pm']          = $lang['pm'];
	$format['AM']          = $lang['AM'];
    $format['PM']          = $lang['PM'];
    $str = str_replace(array_keys($format), $format, $date);
    return $str;
}
// preg slug
function preg_slug($title, $context = 'save')
{
    $title = strip_tags($title);
    $title = preg_replace('|%([a-fA-F0-9][a-fA-F0-9])|', '---$1---', $title);
    $title = str_replace('%', '', $title);
    $title = preg_replace('|---([a-fA-F0-9][a-fA-F0-9])---|', '%$1', $title);
    if (seems_utf8($title)) {
        if (function_exists('mb_strtolower')) {
            $title = mb_strtolower($title, 'UTF-8');
        }
        $title = utf8_uri_encode($title, 200);
    }
    $title = strtolower($title);
    $title = preg_replace('/&.+?;/', '', $title); // kill entities
    $title = str_replace('.', '-', $title);
    if ( 'save' == $context ) {
        $title = str_replace( array( '%c2%a0', '%e2%80%93', '%e2%80%94' ), '-', $title );
        $title = str_replace( array(
                '%c2%a1', '%c2%bf',
                '%c2%ab', '%c2%bb', '%e2%80%b9', '%e2%80%ba',
                '%e2%80%98', '%e2%80%99', '%e2%80%9c', '%e2%80%9d',
                '%e2%80%9a', '%e2%80%9b', '%e2%80%9e', '%e2%80%9f',
                '%c2%a9', '%c2%ae', '%c2%b0', '%e2%80%a6', '%e2%84%a2',
                '%c2%b4', '%cb%8a', '%cc%81', '%cd%81',
                '%cc%80', '%cc%84', '%cc%8c',
        ), '', $title );
        $title = str_replace( '%c3%97', 'x', $title );
    }
    $title = preg_replace('/[^%a-z0-9 _-]/', '', $title);
    $title = preg_replace('/\s+/', '-', $title);
    $title = preg_replace('|-+|', '-', $title);
    $title = trim($title, '-');
    return $title;
}
// seems utf8
function seems_utf8( $str ) {
    $length = strlen($str);
    for ($i=0; $i < $length; $i++) {
            $c = ord($str[$i]);
            if ($c < 0x80) $n = 0; // 0bbbbbbb
            elseif (($c & 0xE0) == 0xC0) $n=1; // 110bbbbb
            elseif (($c & 0xF0) == 0xE0) $n=2; // 1110bbbb
            elseif (($c & 0xF8) == 0xF0) $n=3; // 11110bbb
            elseif (($c & 0xFC) == 0xF8) $n=4; // 111110bb
            elseif (($c & 0xFE) == 0xFC) $n=5; // 1111110b
            else return false; // Does not match any model
            for ($j=0; $j<$n; $j++) { // n bytes matching 10bbbbbb follow ?
                    if ((++$i == $length) || ((ord($str[$i]) & 0xC0) != 0x80))
                            return false;
            }
    }
    return true;
}
// utf8 uri encode
function utf8_uri_encode( $utf8_string, $length = 0 ) {
    $unicode = '';
    $values = array();
    $num_octets = 1;
    $unicode_length = 0;
    $string_length = strlen( $utf8_string );
    for ($i = 0; $i < $string_length; $i++ ) {
        $value = ord( $utf8_string[ $i ] );
        if ( $value < 128 ) {
                if ( $length && ( $unicode_length >= $length ) )
                        break;
                $unicode .= chr($value);
                $unicode_length++;
        } else {
            if ( count( $values ) == 0 ) {
                if ( $value < 224 ) {
                        $num_octets = 2;
                } elseif ( $value < 240 ) {
                        $num_octets = 3;
                } else {
                        $num_octets = 4;
                }
            }
            $values[] = $value;
            if ( $length && ( $unicode_length + ($num_octets * 3) ) > $length )
                    break;
            if ( count( $values ) == $num_octets ) {
                for ( $j = 0; $j < $num_octets; $j++ ) {
                        $unicode .= '%' . dechex( $values[ $j ] );
                }
                $unicode_length += $num_octets * 3;
                $values = array();
                $num_octets = 1;
            }
        }
    }
    return $unicode;
}
// number format
function format_num($num,$dd = 2)
{
     return number_format((float)$num, $dd, '.', ',');
}
// sanitize
function sanitize($string, $trim = false, $int = false, $str = false)
{
    $string = filter_var($string, FILTER_SANITIZE_STRING);
    $string = trim($string);
    $string = stripslashes($string);
    $string = strip_tags($string);
    $string = str_replace(array('‘','’','“','”'), array("'","'",'"','"'), $string);
    if ($trim)
      $string = substr($string, 0, $trim);
    if ($int)
      $string = preg_replace("/[^0-9\s]/", "", $string);
    if ($str)
      $string = preg_replace("/[^a-zA-Z\s]/", "", $string);
    
    return $string;
}
// sanitize text
function sanitize_text( $string ) {
        $string = str_replace(array('‘', '’', '“', '”'), array("'", "'", '"', '"'), $string);
        return $string;
}
// un sanitize text
function unsanitize_text( $string ) {
        $string = str_replace(array("'", "'", '"', '"'), array('‘', '’', '“', '”'), $string);
        return $string;
}
// unique id
function unique_id($extra = 'c')
{
	$dss_seeded = false;

	$val = $config['rand_seed'] . microtime();
	$val = md5($val);
	$config['rand_seed'] = md5($config['rand_seed'] . $val . $extra);
	if ($config['rand_seed_last_update'] < time() - rand(1,10))
	{
		set_config('rand_seed_last_update', time());
		//set_config('rand_seed', $config['rand_seed']);
	}
	return substr($val, 4, 16);
}
// random salt
function get_random_salt($length = 16)
{
	$random = '';
	$random_state = unique_id();
	for ($i = 0; $i < $length; $i += 16)
	{
		$random_state = md5(unique_id() . $random_state);
		$random .= pack('H*', md5($random_state));
	}
	$random = substr($random, 0, $length);
	return $random;
}
// redirect
function redirect($url = false, $time = 0){
	if(isset($_SERVER['HTTP_REFERER']))
	{
		$url = $url ? $url : $_SERVER['HTTP_REFERER'];
	}
	else
	{
		$url = $url ? $url : 'index.php';
	}
	if(!headers_sent()){
		if(!$time){
			header("Location: {$url}");
		}else{
			header("refresh: $time; {$url}");
		}
	}else{
		echo "<script> setTimeout(function(){ window.location = '{$url}' },". ($time*1000) . ")</script>";
	}
}
// generate unique id
function generate_meta_value_uniqueid($meta_key = '', $type = '', $length = 7)
{
    global $db;
    $z      = 0;
    while ($z <= 0)
    {
        if($type == 'tid')
        {
            $seedsfirst = '123456789';
            $seeds      = '0123456789';
        }
        elseif($type == 'cid')
        {
            $seedsfirst = 'QWERTYUIOPASDFGHJKLZXCVBNM';
            $seeds      = 'qwertyuiopasdfghjklzxcvbnm123456789QWERTYUIOPASDFGHJKLZXCVBNM';
        }
        else
        {
            $seedsfirst = 'QWERTYUIOPASDFGHJKLZXCVBNM';
            $seeds      = 'qwertyuiopasdfghjklzxcvbnm123456789QWERTYUIOPASDFGHJKLZXCVBNM';
        }
        $str         = null;
        $seeds_count = strlen ($seeds) - 1;
        $i           = 0;
        while ($i < $length){
            if ($i == 0){@$str .= $seedsfirst[rand (0, $seeds_count - 1)];}
            else{$str .= $seeds[rand (0, $seeds_count)];}
            ++$i;
        }
        if ($meta_key)
        {
            $data   = $db->sql_query("SELECT * FROM ".POSTSMETA_TABLE." WHERE `meta_key`='{$meta_key}' and `meta_value`='{$str}'");
            if(isset($data->id)){$id = $data->id;}
            else{$id = '';}
            if ($id == ''){$z = 1;continue;}
            continue;
        }
        continue;
    }
    return $str;
}
// is rtl
function is_rtl()
{
    return (get_language_direction() == 'rtl')? true: false;
}
// get parse args
function get_parse_args( $args, $defaults = '' ) {
	if ( is_object( $args ) )
		$r = get_object_vars( $args );
	elseif ( is_array( $args ) )
		$r =& $args;

	if ( is_array( $defaults ) )
		return array_merge( $defaults, $r );
	return $r;
}
// get option
function get_option($option)
{
    global $config;
    return $config[$option];
}
// get option serialize
function get_option_serialize($option, $name)
{
    global $config;
    $option_name = $config[$option];
	$get_option = (is_serialized($config[$option_name]))? maybe_unserialize($config[$option_name]) : array() ;
	if( !empty( $get_option[$name] ))
    {
		return $get_option[$name];
	}
	return false ;
}
// esc textarea
function esc_textarea($text)
{
    $safe_text = htmlspecialchars($text, ENT_QUOTES, get_option('charset'));
    return $safe_text;
}
// esc attr
function esc_attr( $text ) {   
    return $text;
}
// get format textarea
function get_format_textarea($text, $preg = '<br />')
{
    return preg_replace('/\v+|\\\r\\\n/',$preg,$text);
}
// get static counter
function get_static_counter()
{
    global $db;
    $day            = date('d');
    $month          = date('m');
    $year           = date('Y');
    $total          = $db->sql_numrows("SELECT * FROM ".COUNTER_TABLE." WHERE `meta_key`='visits'");
    $total_year     = $db->sql_numrows("SELECT * FROM ".COUNTER_TABLE." WHERE `meta_key`='visits' and FROM_UNIXTIME(modified, '%Y')='{$year}'");
    $total_month    = $db->sql_numrows("SELECT * FROM ".COUNTER_TABLE." WHERE `meta_key`='visits' and FROM_UNIXTIME(modified, '%Y%m')='{$year}{$month}'");
    $total_day      = $db->sql_numrows("SELECT * FROM ".COUNTER_TABLE." WHERE `meta_key`='visits' and FROM_UNIXTIME(modified, '%Y%m%d')='{$year}{$month}{$day}'");
    $un_total       = $db->sql_numrows("SELECT * FROM ".COUNTER_TABLE." WHERE `meta_key`='visits' GROUP By ip");
    $un_total_year  = $db->sql_numrows("SELECT * FROM ".COUNTER_TABLE." WHERE `meta_key`='visits' and FROM_UNIXTIME(modified, '%Y')='{$year}' GROUP By ip");
    $un_total_month = $db->sql_numrows("SELECT * FROM ".COUNTER_TABLE." WHERE `meta_key`='visits' and FROM_UNIXTIME(modified, '%Y%m')='{$year}{$month}' GROUP By ip");
    $un_total_day   = $db->sql_numrows("SELECT * FROM ".COUNTER_TABLE." WHERE `meta_key`='visits' and FROM_UNIXTIME(modified, '%Y%m%d')='{$year}{$month}{$day}' GROUP By ip");

    $visits = array(
        'total'   => $total,
        'year'    => $total_year,
        'month'   => $total_month,
        'day'     => $total_day,
        'un_total'   => $un_total,
        'un_year'    => $un_total_year,
        'un_month'   => $un_total_month,
        'un_day'     => $un_total_day,
    );
    return $visits;
}








class xssClean {

    public function clean_input( $input, $safe_level = 0 ) {

        $output = $input;
        do {
            // Treat $input as buffer on each loop, faster than new var
            $input = $output;
            
            // Remove unwanted tags
            $output = $this->strip_tags( $input );
            $output = $this->strip_encoded_entities( $output );

            // Use 2nd input param if not empty or '0'
            if ( $safe_level !== 0 ) {
                $output = $this->strip_base64( $output );
            }

        } while ( $output !== $input );

        return $output;

    }

    private function strip_encoded_entities( $input ) {

        // Fix &entity\n;
        $input = str_replace(array('&amp;','&lt;','&gt;'), array('&amp;amp;','&amp;lt;','&amp;gt;'), $input);
        $input = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $input);
        $input = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $input);
        $input = html_entity_decode($input, ENT_COMPAT, 'UTF-8');

        // Remove any attribute starting with "on" or xmlns
        $input = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+[>\b]?#iu', '$1>', $input);

        // Remove javascript: and vbscript: protocols
        $input = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $input);
        $input = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $input);
        $input = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $input);

        // Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
        $input = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $input);
        $input = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $input);
        $input = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $input);

        return $input;

    }

    private function strip_tags( $input ) {
        // Remove tags
        $input = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $input);
        // Remove namespaced elements
        $input = preg_replace('#</*\w+:\w[^>]*+>#i', '', $input);
        return $input;

    }

    private function strip_base64( $input ) {
        $decoded = base64_decode( $input );
        $decoded = $this->strip_tags( $decoded );
        $decoded = $this->strip_encoded_entities( $decoded );
        $output = base64_encode( $decoded );
        return $output;
    }

}

$xssClean = new xssClean();
?>
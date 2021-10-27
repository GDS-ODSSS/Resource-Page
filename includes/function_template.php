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

// WARNING! Do not change this file

// message handler
function msg_handler($errno, $msg_text)
{
    echo 'General Error <br /> ['.$errno.'] <br />' . $msg_text;
    exit_handler();
    return false;
}
// phpmega chmod
function phpmega_chmod($filename, $perms = CHMOD_READ)
{
	static $_chmod_info;
	if (!file_exists($filename)){
		return false;
	}
	if ($_chmod_info['process']){
		$file_uid = @fileowner($filename);
		$file_gid = @filegroup($filename);
		if (@chown($filename, $_chmod_info['common_owner'])){
			clearstatcache();
			$file_uid = @fileowner($filename);
		}
		if (@chgrp($filename, $_chmod_info['common_group'])){
			clearstatcache();
			$file_gid = @filegroup($filename);
		}
		if ($file_uid != $_chmod_info['common_owner'] || $file_gid != $_chmod_info['common_group']){
			$_chmod_info['process'] = false;
		}
	}
	if ($_chmod_info['process']){
		if ($file_uid == $_chmod_info['php_uid']){
			$php = 'owner';
		}
		else if (in_array($file_gid, $_chmod_info['php_gids'])){
			$php = 'group';
		}
		else{
			$_chmod_info['process'] = false;
		}
	}
	if (!$_chmod_info['process']){
		$php = 'other';
	}
	$owner = CHMOD_READ | CHMOD_WRITE;
	if (is_dir($filename)){
		$owner |= CHMOD_EXECUTE;
		if ($perms & CHMOD_READ){
			$perms |= CHMOD_EXECUTE;
		}
	}
	switch ($php){
		case 'owner':
			$result = @chmod($filename, ($owner << 6) + (0 << 3) + (0 << 0));
			clearstatcache();
			if (is_readable($filename) && phpmega_is_writable($filename)){
				break;
			}
		case 'group':
			$result = @chmod($filename, ($owner << 6) + ($perms << 3) + (0 << 0));
			clearstatcache();
			if ((!($perms & CHMOD_READ) || is_readable($filename)) && (!($perms & CHMOD_WRITE) || phpmega_is_writable($filename))){
				break;
			}
		case 'other':
			$result = @chmod($filename, ($owner << 6) + ($perms << 3) + ($perms << 0));
			clearstatcache();
			if ((!($perms & CHMOD_READ) || is_readable($filename)) && (!($perms & CHMOD_WRITE) || phpmega_is_writable($filename))){
				break;
			}
		default:
			return false;
		break;
	}
	return $result;
}
// phpmega is writable
function phpmega_is_writable($file){
	if (strtolower(substr(PHP_OS, 0, 3)) === 'win' || !function_exists('is_writable')){
		if (file_exists($file)){
			// Canonicalise path to absolute path
			$file = phpmega_realpath($file);
			if (is_dir($file)){
				// Test directory by creating a file inside the directory
				$result = @tempnam($file, 'i_w');
				if (is_string($result) && file_exists($result)){
					unlink($result);
					// Ensure the file is actually in the directory (returned realpathed)
					return (strpos($result, $file) === 0) ? true : false;
				}
			}
			else{
				$handle = @fopen($file, 'r+');
				if (is_resource($handle)){
					fclose($handle);
					return true;
				}
			}
		}
		else{
			// file does not exist test if we can write to the directory
			$dir = dirname($file);
			if (file_exists($dir) && is_dir($dir) && phpmega_is_writable($dir)){
				return true;
			}
		}
		return false;
	}
	else{
		return is_writable($file);
	}
}
if (!function_exists('realpath')){
	function phpmega_realpath($path){
		return phpmega_own_realpath($path);
	}
}
else{
	function phpmega_realpath($path){
		$realpath = realpath($path);
		if ($realpath === $path || $realpath === false)
		{
			return phpmega_own_realpath($path);
		}
		if (substr($realpath, -1) == DIRECTORY_SEPARATOR)
        {
			$realpath = substr($realpath, 0, -1);
		}
		return $realpath;
	}
}
// is absolute
function is_absolute($path)
{
	return (isset($path[0]) && $path[0] == '/' || preg_match('#^[a-z]:[/\\\]#i', $path)) ? true : false;
}
// phpmega own realpath
function phpmega_own_realpath($path)
{
	$path = str_replace(DIRECTORY_SEPARATOR, '/', $path);
	$path_prefix = '';
	if (is_absolute($path))
	{
		$absolute = true;
		if ($path[0] == '/')
		{
			$path_prefix = '';
		}
		else
		{
			$path_prefix = $path[0] . ':';
			$path = substr($path, 2);
		}
	}
	else
	{
		if (function_exists('getcwd'))
		{
			$path = str_replace(DIRECTORY_SEPARATOR, '/', getcwd()) . '/' . $path;
			$absolute = true;
			if (preg_match('#^[a-z]:#i', $path))
			{
				$path_prefix = $path[0] . ':';
				$path = substr($path, 2);
			}
			else
			{
				$path_prefix = '';
			}
		}
		else if (isset($_SERVER['SCRIPT_FILENAME']) && !empty($_SERVER['SCRIPT_FILENAME']))
		{
			$path = str_replace(DIRECTORY_SEPARATOR, '/', dirname($_SERVER['SCRIPT_FILENAME'])) . '/' . $path;
			$absolute = true;
			$path_prefix = '';
		}
		else
		{
			$absolute = false;
			$path_prefix = '.';
		}
	}
	$path = preg_replace('#/{2,}#', '/', $path);
	$path = trim($path, '/');
	$bits = explode('/', $path);
	$bits = array_values(array_diff($bits, array('.')));
	for ($i = 0, $max = sizeof($bits); $i < $max; $i++)
	{
		if ($bits[$i] == '..' )
		{
			if (isset($bits[$i - 1]))
			{
				if ($bits[$i - 1] != '..')
				{
					unset($bits[$i]);
					unset($bits[$i - 1]);
					$i -= 2;
					$max -= 2;
					$bits = array_values($bits);
				}
			}
			else if ($absolute)
			{
				return false;
			}
		}
	}
	array_unshift($bits, $path_prefix);
	$resolved = '';
	$max = sizeof($bits) - 1;
	$symlink_resolve = (function_exists('readlink')) ? true : false;
	foreach ($bits as $i => $bit)
	{
		if (@is_dir("$resolved/$bit") || ($i == $max && @is_file("$resolved/$bit")))
		{
			if ($symlink_resolve && is_link("$resolved/$bit") && ($link = readlink("$resolved/$bit")))
			{
				$resolved = $link . (($i == $max) ? '' : '/');
				continue;
			}
		}
		else
		{
			
		}
		$resolved .= $bit . (($i == $max) ? '' : '/');
	}
	if (!@file_exists($resolved) || (!@is_dir($resolved . '/') && !is_file($resolved)))
	{
		return false;
	}
	$resolved = str_replace('/', DIRECTORY_SEPARATOR, $resolved);
	if (substr($resolved, -1) == DIRECTORY_SEPARATOR)
	{
		return substr($resolved, 0, -1);
	}
	return $resolved;
}

// start mysql (mysql, mysqli)
$sql_db     = (DB_SQLMS == 'mysql')? 'dbal_mysql': 'dbal_mysqli';
$db	        = new $sql_db();
$con        = $db->sql_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT, false, defined('DB_NEW_LINK') ? DB_NEW_LINK : false);
$cache		= new cache();
$config     = $cache->obtain_config($db);
if(isset($config['sitethemes']))
{
    define('THEME_FOLDER_PATH', $config['sitethemes']);
}
else
{
    define('THEME_FOLDER_PATH', TEMP_FOLDER_PATH);
}
// set config
function set_config($config_name, $config_value)
{
	global $db, $cache, $config;
	$sql = 'UPDATE ' . CONFIG_TABLE . "
		SET config_value = '" . $db->sql_escape($config_value) . "'
		WHERE config_name = '" . $db->sql_escape($config_name) . "'";
	$db->sql_query($sql);
	if (!$db->sql_affectedrows() && !isset($config[$config_name]))
	{
		$sql = 'INSERT INTO ' . CONFIG_TABLE . ' ' . $db->sql_build_array('INSERT', array('config_name'	=> $config_name,'config_value'	=> $config_value));
		$db->sql_query($sql);
	}
	$config[$config_name] = $config_value;
}
// generate debug output
function generate_debug_output($db, $config)
{
    $debug_info = array();
    // Output page creation time
	if (defined('SHOWDEBUG_LOAD_TIME'))
	{
		if (isset($GLOBALS['starttime']))
		{
			$totaltime = microtime(true) - $GLOBALS['starttime'];
            if (defined('SHOWDEBUG_QUERIES'))
        	{
        		$debug_info[] = sprintf('<span title="Cached: %d">Queries: %d</span>', $db->sql_num_queries(true), $db->sql_num_queries());
        	}
			$debug_info[] = sprintf('<span>SQL time: %.5fs</span>', $db->get_sql_time());
            $debug_info[] = sprintf('<span>PHP time: %.5fs</span>', ($totaltime - $db->get_sql_time()));
            $debug_info[] = sprintf('<span>Total time: %.5fs</span>', $totaltime);
		}
	}
    if (defined('SHOWDEBUG_QUERIES') AND !defined('SHOWDEBUG_LOAD_TIME'))
	{
		$debug_info[] = sprintf('<span title="Cached: %d">Queries: %d</span>', $db->sql_num_queries(true), $db->sql_num_queries());
	}
    if (defined('SHOWDEBUG_MEMORE'))
	{
		$memory_usage = memory_get_peak_usage();
		if ($memory_usage)
		{
			$memory_usage = get_formatted_filesize($memory_usage);
			$debug_info[] = 'Peak Memory Usage: ' . $memory_usage;
		}
	}
    if (defined('SHOWDEBUG_GZIP'))
	{
        $debug_info[] = sprintf('<span>GZIP: %s</span>', ((GZIP_COMPRESS && @extension_loaded('zlib')) ? 'On' : 'Off'));
	}
    $vars = array('debug_info');
    return implode(' | ', $debug_info);
}
// page header
function page_header($args = '')
{
	global $template, $config;
    if(function_exists('global_header'))
    {
        global_header($args);
    }
	if (defined('HEADER_INC'))
	{
		return;
	}
	define('HEADER_INC', true);
	if (GZIP_COMPRESS)
    {
		if (@extension_loaded('zlib') && !headers_sent() && ob_get_level() <= 1 && ob_get_length() == 0)
		{
			ob_start('ob_gzhandler');
		}
	}
	return;
}
// page footer
function page_footer($args = '', $display_template = true, $exit_handler = true)
{
	global $template, $db, $config;
    if(function_exists('global_footer'))
    {
        global_footer($args);
    }
    if(DISPLAY_SHOWDEBUG)
    {
        $template->assign_vars(array(
            'DISPLAY_SHOWDEBUG' => true,
            'DEBUG_OUTPUT'      => generate_debug_output($db, $config),
        ));
    }
    if ($display_template)
	{
		$template->display('body');
	}
	garbage_collection();
	if ($exit_handler)
	{
		exit_handler();
	}
}
// garbage collection
function garbage_collection(){
	global $cache, $db;
	if (!empty($cache)){
		$cache->unload();
	}
    // Close our DB connection.
	if (!empty($db)){
		$db->sql_close();
	}
}
// exit handler
function exit_handler($unset = true){
	(ob_get_level() > 0) ? @ob_flush() : @flush();
	exit;
}
// set timezone
function set_timezone($timezone = false)
{   
    if (function_exists('date_default_timezone_set') && function_exists('date_default_timezone_get'))
    {
    	if($timezone)
        {
            date_default_timezone_set($timezone);
        }
        else
        {
            date_default_timezone_set(@date_default_timezone_get());
        }
    }
}
// start timezone
set_timezone($config['timezone_string']);
?>
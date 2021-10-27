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
class acm
{
	public $vars = array();
	public $var_expires = array();
	public $is_modified = false;
	public $sql_rowset = array();
	public $sql_row_pointer = array();
	public $cache_dir = '';
	function __construct()
	{
		$this->cache_dir =  CACHE_PATH;
	}
    
	function load()
	{
		return $this->_read('data_global');
	}
 
	function unload()
	{
		$this->save();
		unset($this->vars);
		unset($this->var_expires);
		unset($this->sql_rowset);
		unset($this->sql_row_pointer);
		$this->vars = array();
		$this->var_expires = array();
		$this->sql_rowset = array();
		$this->sql_row_pointer = array();
	}
 
	function save()
	{
		if (!$this->is_modified)
		{
			return;
		}
		if (!$this->_write('data_global'))
		{
			if (!function_exists('phpmega_is_writable'))
			{
				//
			}
			if (!phpmega_is_writable($this->cache_dir))
			{
				die('Fatal: ' . $this->cache_dir . ' is NOT writable.');
				exit;
			}
			die('Fatal: Not able to open ' . $this->cache_dir . 'data_global.php');
			exit;
		}
		$this->is_modified = false;
	}

	function get($var_name)
	{
		if ($var_name[0] == '_')
		{
			if (!$this->_exists($var_name))
			{
				return false;
			}
			return $this->_read('data' . $var_name);
		}
		else
		{
			return ($this->_exists($var_name)) ? $this->vars[$var_name] : false;
		}
	}
 
	function put($var_name, $var, $ttl = 31536000)
	{
		if ($var_name[0] == '_')
		{
			$this->_write('data' . $var_name, $var, time() + $ttl);
		}
		else
		{
			$this->vars[$var_name] = $var;
			$this->var_expires[$var_name] = time() + $ttl;
			$this->is_modified = true;
		}
	}
    
	function destroy($var_name, $table = '')
	{
		if ($var_name == 'sql' && !empty($table))
		{
			if (!is_array($table))
			{
				$table = array($table);
			}
			$dir = @opendir($this->cache_dir);
			if (!$dir)
			{
				return;
			}
			while (($entry = readdir($dir)) !== false)
			{
				if (strpos($entry, 'sql_') !== 0)
				{
					continue;
				}
				if (!($handle = @fopen($this->cache_dir . $entry, 'rb')))
				{
					continue;
				}
				fgets($handle);
				fgets($handle);
				$query = substr(fgets($handle), 0, -1);
				fclose($handle);
				foreach ($table as $check_table)
				{
					if (strpos($query, $check_table) !== false)
					{
						$this->remove_file($this->cache_dir . $entry);
						break;
					}
				}
			}
			closedir($dir);
			return;
		}
		if (!$this->_exists($var_name))
		{
			return;
		}
		if ($var_name[0] == '_')
		{
			$this->remove_file($this->cache_dir . 'data' . $var_name . ".php", true);
		}
		else if (isset($this->vars[$var_name]))
		{
			$this->is_modified = true;
			unset($this->vars[$var_name]);
			unset($this->var_expires[$var_name]);
			$this->save();
		}
	}
 
	function _exists($var_name)
	{
		if ($var_name[0] == '_')
		{
			return file_exists($this->cache_dir . 'data' . $var_name . ".php");
		}
		else
		{
			if (!sizeof($this->vars))
			{
				$this->load();
			}
			if (!isset($this->var_expires[$var_name]))
			{
				return false;
			}
			return (time() > $this->var_expires[$var_name]) ? false : isset($this->vars[$var_name]);
		}
	}
 
	function sql_load($query)
	{
		$query = preg_replace('/[\n\r\s\t]+/', ' ', $query);

		if (($rowset = $this->_read('sql_' . md5($query))) === false)
		{
			return false;
		}
		$query_id = sizeof($this->sql_rowset);
		$this->sql_rowset[$query_id] = $rowset;
		$this->sql_row_pointer[$query_id] = 0;
		return $query_id;
	}
 
	function sql_save($query, &$query_result, $ttl)
	{
		global $db;
		$query = preg_replace('/[\n\r\s\t]+/', ' ', $query);
		$query_id = sizeof($this->sql_rowset);
		$this->sql_rowset[$query_id] = array();
		$this->sql_row_pointer[$query_id] = 0;
		while ($row = $db->sql_fetchrow($query_result))
		{
			$this->sql_rowset[$query_id][] = $row;
		}
		$db->sql_freeresult($query_result);
		if ($this->_write('sql_' . md5($query), $this->sql_rowset[$query_id], $ttl + time(), $query))
		{
			$query_result = $query_id;
		}
	}
 
	function sql_exists($query_id)
	{
		return isset($this->sql_rowset[$query_id]);
	}
 
	function sql_fetchrow($query_id)
	{
		if ($this->sql_row_pointer[$query_id] < sizeof($this->sql_rowset[$query_id]))
		{
			return $this->sql_rowset[$query_id][$this->sql_row_pointer[$query_id]++];
		}
		return false;
	}
 
	function sql_fetchfield($query_id, $field)
	{
		if ($this->sql_row_pointer[$query_id] < sizeof($this->sql_rowset[$query_id]))
		{
			return (isset($this->sql_rowset[$query_id][$this->sql_row_pointer[$query_id]][$field])) ? $this->sql_rowset[$query_id][$this->sql_row_pointer[$query_id]++][$field] : false;
		}
		return false;
	}
 
	function sql_rowseek($rownum, $query_id)
	{
		if ($rownum >= sizeof($this->sql_rowset[$query_id]))
		{
			return false;
		}
		$this->sql_row_pointer[$query_id] = $rownum;
		return true;
	}
 
	function sql_freeresult($query_id)
	{
		if (!isset($this->sql_rowset[$query_id]))
		{
			return false;
		}
		unset($this->sql_rowset[$query_id]);
		unset($this->sql_row_pointer[$query_id]);
		return true;
	}
 
	function _read($filename)
	{
		$file = "{$this->cache_dir}$filename.php";
		$type = substr($filename, 0, strpos($filename, '_'));
		if (!file_exists($file))
		{
			return false;
		}
		if (!($handle = @fopen($file, 'rb')))
		{
			return false;
		}
		fgets($handle);
		if ($filename == 'data_global')
		{
			$this->vars = $this->var_expires = array();
			$time = time();
			while (($expires = (int) fgets($handle)) && !feof($handle))
			{
				$bytes = substr(fgets($handle), 0, -1);
				if (!is_numeric($bytes) || ($bytes = (int) $bytes) === 0)
				{
					fclose($handle);
					$this->vars = $this->var_expires = array();
					$this->is_modified = false;
					$this->remove_file($file);
					return false;
				}
				if ($time >= $expires)
				{
					fseek($handle, $bytes, SEEK_CUR);
					continue;
				}
				$var_name = substr(fgets($handle), 0, -1);
				$data = fread($handle, $bytes - strlen($var_name));
				$data = @unserialize($data);
				if ($data !== false)
				{
					$this->vars[$var_name] = $data;
					$this->var_expires[$var_name] = $expires;
				}
				fgets($handle);
			}
			fclose($handle);
			$this->is_modified = false;
			return true;
		}
		else
		{
			$data = false;
			$line = 0;
			while (($buffer = fgets($handle)) && !feof($handle))
			{
				$buffer = substr($buffer, 0, -1);
				if (!is_numeric($buffer))
				{
					break;
				}
				if ($line == 0)
				{
					$expires = (int) $buffer;
					if (time() >= $expires)
					{
						break;
					}
					if ($type == 'sql')
					{
						fgets($handle);
					}
				}
				else if ($line == 1)
				{
					$bytes = (int) $buffer;
					if (!$bytes)
					{
						break;
					}
					$data = fread($handle, $bytes);
					fread($handle, 1);

					if (!feof($handle))
					{
						$data = false;
					}
					break;
				}
				else
				{
					break;
				}
				$line++;
			}
			fclose($handle);
			$data = ($data !== false) ? @unserialize($data) : $data;
			if ($data === false)
			{
				$this->remove_file($file);
				return false;
			}
			return $data;
		}
	}
 
	function _write($filename, $data = null, $expires = 0, $query = '')
	{
		$file = "{$this->cache_dir}$filename.php";
        
		if ($handle = @fopen($file, 'wb'))
		{
			@flock($handle, LOCK_EX);
			fwrite($handle, '<' . '?php exit; ?' . '>');
			if ($filename == 'data_global')
			{
				foreach ($this->vars as $public => $data)
				{
					if (strpos(@$var, "\r") !== false || strpos(@$var, "\n") !== false)
					{
						continue;
					}
					$data = serialize($data);
					fwrite($handle, "\n" . @$this->var_expires[@$var] . "\n");
					fwrite($handle, strlen($data . @$var) . "\n");
					fwrite($handle, $public . "\n");
					fwrite($handle, $data);
				}
			}
			else
			{
				fwrite($handle, "\n" . $expires . "\n");
				if (strpos($filename, 'sql_') === 0)
				{
					fwrite($handle, $query . "\n");
				}
				$data = serialize($data);
				fwrite($handle, strlen($data) . "\n");
				fwrite($handle, $data);
			}
			@flock($handle, LOCK_UN);
			fclose($handle);

			if (!function_exists('phpmega_chmod'))
			{
				//
			}
			phpmega_chmod($file, CHMOD_READ | CHMOD_WRITE);
			return true;
		}
		return false;
	}
 
	function remove_file($filename, $check = false)
	{
		if (!function_exists('phpmega_is_writable'))
		{
			//
		}
		if ($check && !phpmega_is_writable($this->cache_dir))
		{
			trigger_error('Unable to remove files within ' . $this->cache_dir . '. Please check directory permissions.', E_USER_ERROR);
		}
		return @unlink($filename);
	}
}

class template_compile
{
	var $template;
	var $block_names = array();
	var $block_else_level = array();

	function __construct(&$template)
	{
		$this->template = &$template;
	}
    
	function _tpl_load_file($handle, $store_in_db = false)
	{
		if (!file_exists($this->template->files[$handle]))
		{
			die("File {$this->template->files[$handle]} does not exist or is empty");
            trigger_error(TEMP_PATHCOULD_ERROR . THEME_FOLDER_PATH . '', E_USER_ERROR);
		}
		$this->template->compiled_code[$handle] = $this->compile(trim(@file_get_contents($this->template->files[$handle])));
		$this->compile_write($handle, $this->template->compiled_code[$handle]);
	}

	function remove_php_tags(&$code)
	{
		$match = array(
			'#<([\?%])=?.*?\1>#s',
			'#<script\s+language\s*=\s*(["\']?)php\1\s*>.*?</script\s*>#s',
			'#<\?php(?:\r\n?|[ \n\t]).*?\?>#s'
		);
		$code = preg_replace($match, '', $code);
	}
    
	function compile($code, $no_echo = false, $echo_var = '')
	{
		global $config, $hooks;
		if ($echo_var)
		{
			global $$echo_var;
		}
		$this->remove_php_tags($code);
		preg_match_all('#<!-- PHP -->(.*?)<!-- ENDPHP -->#s', $code, $matches);
		$php_blocks = $matches[1];
		$code = preg_replace('#<!-- PHP -->.*?<!-- ENDPHP -->#s', '<!-- PHP -->', $code);
		preg_match_all('#<!-- INCLUDE (\{\$?[A-Z0-9\-_]+\}|[a-zA-Z0-9\_\-\+\./]+) -->#', $code, $matches);
		$include_blocks = $matches[1];
        
        
        
        preg_match_all('#<!-- INCLUDE_SECTION_HOME (\{\$?[A-Z0-9\-_]+\}|[a-zA-Z0-9\_\-\+\./]+) -->#', $code, $matches);
		$include_blocks_include = $matches[1];
        
        
		$code = preg_replace('#<!-- INCLUDE (?:\{\$?[A-Z0-9\-_]+\}|[a-zA-Z0-9\_\-\+\./]+) -->#', '<!-- INCLUDE -->', $code);
		preg_match_all('#<!-- INCLUDEPHP ([a-zA-Z0-9\_\-\+\./]+) -->#', $code, $matches);
		$includephp_blocks = $matches[1];
		$code = preg_replace('#<!-- INCLUDEPHP [a-zA-Z0-9\_\-\+\./]+ -->#', '<!-- INCLUDEPHP -->', $code);
		preg_match_all('#<!-- ([^<].*?) (.*?)? ?-->#', $code, $blocks, PREG_SET_ORDER);
		$text_blocks = preg_split('#<!-- [^<].*? (?:.*?)? ?-->#', $code);
		for ($i = 0, $j = sizeof($text_blocks); $i < $j; $i++)
		{
			$this->compile_var_tags($text_blocks[$i]);
		}
		$compile_blocks = array();
		for ($curr_tb = 0, $tb_size = sizeof($blocks); $curr_tb < $tb_size; $curr_tb++)
		{
			$block_val = &$blocks[$curr_tb];
			switch ($block_val[1])
			{
                case 'BEGIN':
					$this->block_else_level[] = false;
					$compile_blocks[] = '<?php ' . $this->compile_tag_block($block_val[2]) . ' ?>';
				break;
				case 'BEGINELSE':
					$this->block_else_level[sizeof($this->block_else_level) - 1] = true;
					$compile_blocks[] = '<?php }} else { ?>';
				break;
				case 'END':
					array_pop($this->block_names);
					$compile_blocks[] = '<?php ' . ((array_pop($this->block_else_level)) ? '}' : '}}') . ' ?>';
				break;
				case 'IF':
					$compile_blocks[] = '<?php ' . $this->compile_tag_if($block_val[2], false) . ' ?>';
				break;
				case 'ELSE':
					$compile_blocks[] = '<?php } else { ?>';
				break;
				case 'ELSEIF':
					$compile_blocks[] = '<?php ' . $this->compile_tag_if($block_val[2], true) . ' ?>';
				break;
				case 'ENDIF':
					$compile_blocks[] = '<?php } ?>';
				break;
				case 'DEFINE':
					$compile_blocks[] = '<?php ' . $this->compile_tag_define($block_val[2], true) . ' ?>';
				break;
				case 'UNDEFINE':
					$compile_blocks[] = '<?php ' . $this->compile_tag_define($block_val[2], false) . ' ?>';
				break;
				case 'INCLUDE':
					$temp = array_shift($include_blocks);
					if ($temp[0] == '{')
					{
						$file = false;
						if ($temp[1] == '$')
						{
							$var = substr($temp, 2, -1);
							$temp = "\$this->_tpldata['DEFINE']['.']['$var']";
						}
						else
						{
							$var = substr($temp, 1, -1);
							$temp = "\$this->_rootref['$var']";
						}
					}
					else
					{
						$file = $temp;
					}
					$compile_blocks[] = '<?php ' . $this->compile_tag_include($temp) . ' ?>';
					if ($file)
					{
						$this->template->_tpl_include($file, false);
					}
				break;
                
                case 'INCLUDE_SECTION_HOME':
                    $sections = array();
                    $sections_home = @(is_serialized($config['home_sections']))? maybe_unserialize($config['home_sections']) : array() ;
                    foreach($sections_home as $value)
                    {   
                        $compile_blocks[] = '<?php ' . $this->compile_tag_include($value) . ' ?>';
                    }
                    $temp = array_shift($include_blocks);
				break;
				case 'INCLUDEPHP':
					$compile_blocks[] = (TPL_ALLOW_PHP) ? '<?php ' . $this->compile_tag_include_php(array_shift($includephp_blocks)) . ' ?>' : '';
				break;
				case 'PHP':
					$compile_blocks[] = (TPL_ALLOW_PHP) ? '<?php ' . array_shift($php_blocks) . ' ?>' : '';
				break;
				default:
					$this->compile_var_tags($block_val[0]);
					$trim_check = trim($block_val[0]);
					$compile_blocks[] = (!$no_echo) ? ((!empty($trim_check)) ? $block_val[0] : '') : ((!empty($trim_check)) ? $block_val[0] : '');
				break;
			}
		}
		$template_php = '';
		for ($i = 0, $size = sizeof($text_blocks); $i < $size; $i++)
		{
			$trim_check_text = trim($text_blocks[$i]);
			$template_php .= (!$no_echo) ? (($trim_check_text != '') ? $text_blocks[$i] : '') . ((isset($compile_blocks[$i])) ? $compile_blocks[$i] : '') : (($trim_check_text != '') ? $text_blocks[$i] : '') . ((isset($compile_blocks[$i])) ? $compile_blocks[$i] : '');
		}
		$template_php = str_replace(' ?><?php ', ' ', $template_php);
        $template_php = preg_replace('#\?\>([\r\n])#', '?>\1\1', $template_php);
		if ($no_echo)
		{
			return "\$$echo_var .= '" . $template_php . "'";
		}
		return $template_php;
	}

	function compile_var_tags(&$text_blocks)
	{
		$varrefs = array();
		preg_match_all('#\{((?:[a-z0-9\-_]+\.)+)(\$)?([A-Z0-9\-_]+)\}#', $text_blocks, $varrefs, PREG_SET_ORDER);
		foreach ($varrefs as $var_val)
		{
			$namespace = $var_val[1];
			$varname = $var_val[3];
			$new = $this->generate_block_varref($namespace, $varname, true, $var_val[2]);

			$text_blocks = str_replace($var_val[0], $new, $text_blocks);
		}
		if (strpos($text_blocks, '{LANG_') !== false)
		{
			$text_blocks = preg_replace('#\{LANG_([A-Z0-9\-_]+)\}#', "<?php echo ((isset(\$this->_rootref['LANG_\\1'])) ? \$this->_rootref['LANG_\\1'] : ((get_languages('\\1')) ? get_languages('\\1') : '{ \\1 }')); ?>", $text_blocks);
		}
		$text_blocks = preg_replace('#\{([A-Z0-9\-_]+)\}#', "<?php echo (isset(\$this->_rootref['\\1'])) ? \$this->_rootref['\\1'] : ''; ?>", $text_blocks);
		$text_blocks = preg_replace('#\{\$([A-Z0-9\-_]+)\}#', "<?php echo (isset(\$this->_tpldata['DEFINE']['.']['\\1'])) ? \$this->_tpldata['DEFINE']['.']['\\1'] : ''; ?>", $text_blocks);
		return;
	}
	function compile_tag_block($tag_args)
	{
        global $hooks;
		$no_nesting = false;
        $incloop = false;
		if (strpos($tag_args, '!') === 0)
		{
			$no_nesting = substr_count($tag_args, '!');
			$tag_args = substr($tag_args, $no_nesting);
		}
        if (strpos($tag_args, '#') === 0)
		{
			$incloop = substr_count($tag_args, '#');
			$tag_args = substr($tag_args, $no_nesting);
		}
		if (preg_match('#^([^()]*)\(([\-\d]+)(?:,([\-\d]+))?\)$#', $tag_args, $match))
		{
			$tag_args = $match[1];
			if ($match[2] < 0)
			{
				$loop_start = '($_' . $tag_args . '_count ' . $match[2] . ' < 0 ? 0 : $_' . $tag_args . '_count ' . $match[2] . ')';
			}
			else
			{
				$loop_start = '($_' . $tag_args . '_count < ' . $match[2] . ' ? $_' . $tag_args . '_count : ' . $match[2] . ')';
			}
			if (strlen($match[3]) < 1 || $match[3] == -1)
			{
				$loop_end = '$_' . $tag_args . '_count';
			}
			else if ($match[3] >= 0)
			{
				$loop_end = '(' . ($match[3] + 1) . ' > $_' . $tag_args . '_count ? $_' . $tag_args . '_count : ' . ($match[3] + 1) . ')';
			}
			else
			{
				$loop_end = '$_' . $tag_args . '_count' . ($match[3] + 1);
			}
		}
		else
		{
			$loop_start = 0;
			$loop_end = '$_' . $tag_args . '_count';
		}
		$tag_template_php = '';
		array_push($this->block_names, $tag_args);
		if ($no_nesting !== false)
		{
			$block = array_slice($this->block_names, -$no_nesting);
		}
		else
		{
			$block = $this->block_names;
		}
		if (sizeof($block) < 2)
		{
			$tag_template_php = '$_' . $tag_args . "_count = (isset(\$this->_tpldata['$tag_args'])) ? sizeof(\$this->_tpldata['$tag_args']) : 0;";
			$varref = "\$this->_tpldata['$tag_args']";
		}
		else
		{
			$namespace = implode('.', $block);
			$varref = $this->generate_block_data_ref($namespace, false);
			$tag_template_php = '$_' . $tag_args . '_count = (isset(' . $varref . ')) ? sizeof(' . $varref . ') : 0;';
		}
		$tag_template_php .= 'if ($_' . $tag_args . '_count) {';
		$tag_template_php .= 'for ($_' . $tag_args . '_i = ' . $loop_start . '; $_' . $tag_args . '_i < ' . $loop_end . '; ++$_' . $tag_args . '_i){';
		if($no_nesting === false)
        {
            $tag_template_php .= '$_'. $tag_args . '_val = &' . $varref . '[$_'. $tag_args. '_i];';
        }
        else
        {
            if($no_nesting == '1')
            {
                $boxs = array();
                if($hooks->has_filter('compile_include_boxs')):
                    $boxs_home = $hooks->apply_filters( 'compile_include_boxs' , $boxs);
                else:
                    $boxs_home = $boxs;
                endif;
                foreach($boxs_home as $value)
                {   
                    $tag_template_php .= $this->compile_tag_include($value);
                }
            }
            elseif($no_nesting == '2')
            {
                $sections = array();
                if($hooks->has_filter('compile_include_sections')):
                    $sections_home = $hooks->apply_filters( 'compile_include_sections' , $sections);
                else:
                    $sections_home = $sections;
                endif;
                foreach($sections_home as $value)
                {   
                    $tag_template_php .= $this->compile_tag_include($value);
                }
            }
        }
		return $tag_template_php;
	}
    
	function compile_tag_if($tag_args, $elseif)
	{
		preg_match_all('/(?:
			"[^"\\\\]*(?:\\\\.[^"\\\\]*)*"         |
			\'[^\'\\\\]*(?:\\\\.[^\'\\\\]*)*\'     |
			[(),]                                  |
			[^\s(),]+)/x', $tag_args, $match);

		$tokens = $match[0];
		$is_arg_stack = array();
		for ($i = 0, $size = sizeof($tokens); $i < $size; $i++)
		{
			$token = &$tokens[$i];
			switch ($token)
			{
				case '!==':
				case '===':
				case '<<':
				case '>>':
				case '|':
				case '^':
				case '&':
				case '~':
				case ')':
				case ',':
				case '+':
				case '-':
				case '*':
				case '/':
				case '@':
				break;
				case '==':
				case 'eq':
					$token = '==';
				break;
				case '!=':
				case '<>':
				case 'ne':
				case 'neq':
					$token = '!=';
				break;
				case '<':
				case 'lt':
					$token = '<';
				break;
				case '<=':
				case 'le':
				case 'lte':
					$token = '<=';
				break;
				case '>':
				case 'gt':
					$token = '>';
				break;
				case '>=':
				case 'ge':
				case 'gte':
					$token = '>=';
				break;
				case '&&':
				case 'and':
					$token = '&&';
				break;
				case '||':
				case 'or':
					$token = '||';
				break;
				case '!':
				case 'not':
					$token = '!';
				break;
				case '%':
				case 'mod':
					$token = '%';
				break;
				case '(':
					array_push($is_arg_stack, $i);
				break;
				case 'is':
					$is_arg_start = ($tokens[$i-1] == ')') ? array_pop($is_arg_stack) : $i-1;
					$is_arg	= implode('	', array_slice($tokens,	$is_arg_start, $i -	$is_arg_start));
					$new_tokens	= $this->_parse_is_expr($is_arg, array_slice($tokens, $i+1));
					array_splice($tokens, $is_arg_start, sizeof($tokens), $new_tokens);
					$i = $is_arg_start;
				default:
					if (preg_match('#^((?:[a-z0-9\-_]+\.)+)?(\$)?(?=[A-Z])([A-Z0-9\-_]+)#s', $token, $varrefs))
					{
						$token = (!empty($varrefs[1])) ? $this->generate_block_data_ref(substr($varrefs[1], 0, -1), true, $varrefs[2]) . '[\'' . $varrefs[3] . '\']' : (($varrefs[2]) ? '$this->_tpldata[\'DEFINE\'][\'.\'][\'' . $varrefs[3] . '\']' : '$this->_rootref[\'' . $varrefs[3] . '\']');
					}
					else if (preg_match('#^\.((?:[a-z0-9\-_]+\.?)+)$#s', $token, $varrefs))
					{
						$blocks = explode('.', $varrefs[1]);
						if (sizeof($blocks) > 1)
						{
							$block = array_pop($blocks);
							$namespace = implode('.', $blocks);
							$varref = $this->generate_block_data_ref($namespace, true);
							$varref .= "['" . $block . "']";
						}
						else
						{
							$varref = '$this->_tpldata';
							$varref .= "['" . $blocks[0] . "']";
						}
						$token = "sizeof($varref)";
					}
					else if (!empty($token))
					{
						$token = '(' . $token . ')';
					}
				break;
			}
		}

		if (!sizeof($tokens) || str_replace(array(' ', '=', '!', '<', '>', '&', '|', '%', '(', ')'), '', implode('', $tokens)) == '')
		{
			$tokens = array('false');
		}
		return (($elseif) ? '} else if (' : 'if (') . (implode(' ', $tokens) . ') { ');
	}

	function compile_tag_define($tag_args, $op)
	{
		preg_match('#^((?:[a-z0-9\-_]+\.)+)?\$(?=[A-Z])([A-Z0-9_\-]*)(?: = (\'?)([^\']*)(\'?))?$#', $tag_args, $match);

		if (empty($match[2]) || (!isset($match[4]) && $op))
		{
			return '';
		}

		if (!$op)
		{
			return 'unset(' . (($match[1]) ? $this->generate_block_data_ref(substr($match[1], 0, -1), true, true) . '[\'' . $match[2] . '\']' : '$this->_tpldata[\'DEFINE\'][\'.\'][\'' . $match[2] . '\']') . ');';
		}

		if ($match[3] && $match[5])
		{
			$match[4] = str_replace(array('\\\'', '\\\\', '\''), array('\'', '\\', '\\\''), $match[4]);
			$match[4] = $this->compile($match[4]);
			$match[4] = "'" . str_replace(array('<?php echo ', '; ?>'), array("' . ", " . '"), $match[4]) . "'";
		}
		else
		{
			preg_match('#true|false|\.#i', $match[4], $type);

			switch (strtolower($type[0]))
			{
				case 'true':
				case 'false':
					$match[4] = strtoupper($match[4]);
				break;

				case '.':
					$match[4] = doubleval($match[4]);
				break;

				default:
					$match[4] = intval($match[4]);
				break;
			}
		}
		return (($match[1]) ? $this->generate_block_data_ref(substr($match[1], 0, -1), true, true) . '[\'' . $match[2] . '\']' : '$this->_tpldata[\'DEFINE\'][\'.\'][\'' . $match[2] . '\']') . ' = ' . $match[4] . ';';
	}

	function compile_tag_include($tag_args)
	{
		if ($tag_args[0] == '$')
		{
			return "if (isset($tag_args)) { \$this->_tpl_include($tag_args); }";
		}
		return "\$this->_tpl_include('$tag_args');";
	}

	function compile_tag_include_php($tag_args)
	{
		return "\$this->_php_include('$tag_args');";
	}

	function _parse_is_expr($is_arg, $tokens)
	{
		$expr_end = 0;
		$negate_expr = false;

		if (($first_token = array_shift($tokens)) == 'not')
		{
			$negate_expr = true;
			$expr_type = array_shift($tokens);
		}
		else
		{
			$expr_type = $first_token;
		}
        
		switch ($expr_type)
		{
			case 'even':
				if (@$tokens[$expr_end] == 'by')
				{
					$expr_end++;
					$expr_arg = $tokens[$expr_end++];
					$expr = "!(($is_arg / $expr_arg) % $expr_arg)";
				}
				else
				{
					$expr = "!($is_arg & 1)";
				}
			break;
			case 'odd':
				if (@$tokens[$expr_end] == 'by')
				{
					$expr_end++;
					$expr_arg = $tokens[$expr_end++];
					$expr = "(($is_arg / $expr_arg) % $expr_arg)";
				}
				else
				{
					$expr = "($is_arg & 1)";
				}
			break;
			case 'div':
				if (@$tokens[$expr_end] == 'by')
				{
					$expr_end++;
					$expr_arg = $tokens[$expr_end++];
					$expr = "!($is_arg % $expr_arg)";
				}
			break;
		}
		if ($negate_expr)
		{
			$expr = "!($expr)";
		}
		array_splice($tokens, 0, $expr_end, $expr);
		return $tokens;
	}

	function generate_block_varref($namespace, $varname, $echo = true, $defop = false)
	{
		$namespace = substr($namespace, 0, -1);
		$varref = $this->generate_block_data_ref($namespace, true, $defop);
		$varref .= "['$varname']";
		$varref = ($echo) ? "<?php echo $varref; ?>" : ((isset($varref)) ? $varref : '');
		return $varref;
	}

	function generate_block_data_ref($blockname, $include_last_iterator, $defop = false)
	{
		$blocks = explode('.', $blockname);
		$blockcount = sizeof($blocks) - 1;
		if ($defop)
		{
			$varref = '$this->_tpldata[\'DEFINE\']';
			for ($i = 0; $i < $blockcount; $i++)
			{
				$varref .= "['" . $blocks[$i] . "'][\$_" . $blocks[$i] . '_i]';
			}
			$varref .= "['" . $blocks[$blockcount] . "']";
			if ($include_last_iterator)
			{
				$varref .= '[$_' . $blocks[$blockcount] . '_i]';
			}
			return $varref;
		}
		else if ($include_last_iterator)
		{
			return '$_'. $blocks[$blockcount] . '_val';
		}
		else
		{
			return '$_'. $blocks[$blockcount - 1] . '_val[\''. $blocks[$blockcount]. '\']';
		}
	}

	function compile_write($handle, $data)
	{
		$filename = $this->template->cachepath . str_replace('/', '.', $this->template->filename[$handle]) . '.php';
		$data = "<?php if (!defined('IN_PHPMEGATEMP')) exit;" . ((strpos($data, '<?php') === 0) ? substr($data, 5) : ' ?>' . $data);
		if ($fp = @fopen($filename, 'wb'))
		{
			@flock($fp, LOCK_EX);
			@fwrite ($fp, $data);
			@flock($fp, LOCK_UN);
			@fclose($fp);
			phpmega_chmod($filename, CHMOD_READ | CHMOD_WRITE);
		}
		return;
	}
}

class template
{
	public $_tpldata = array('.' => array(0 => array()));
	public $_rootref;
	public $root = '';
	public $cachepath = '';
	public $files = array();
	public $filename = array();
	public $files_inherit = array();
	public $files_template = array();
	public $inherit_root = '';
	public $orig_tpl_storedb;
	public $orig_tpl_inherits_id;
	public $compiled_code = array();

	function set_template()
	{
        if (file_exists(THEME_PATH.THEME_FOLDER_PATH))
		{
			$this->root = THEME_PATH.THEME_FOLDER_PATH;
			$this->cachepath = CACHE_PATH.'tpl_' . str_replace('_', '-', THEME_FOLDER_PATH) . '_';
		}
		else
        {
            trigger_error('Template path could not be found:' .THEME_PATH . THEME_FOLDER_PATH . '', E_USER_ERROR);
        }
		$this->_rootref = &$this->_tpldata['.'][0];
		return true;
	}
    
	function set_custom_template($template_path, $template_name, $fallback_template_path = false)
	{
		global $mega_root_path, $user;
		if (substr($template_path, -1) == '/')
		{
			$template_path = substr($template_path, 0, -1);
		}
		$this->root = $template_path;
		$this->cachepath = $mega_root_path . 'cache/ctpl_' . str_replace('_', '-', $template_name) . '_';
		if ($fallback_template_path !== false)
		{
			if (substr($fallback_template_path, -1) == '/')
			{
				$fallback_template_path = substr($fallback_template_path, 0, -1);
			}
			$this->inherit_root = $fallback_template_path;
			$this->orig_tpl_inherits_id = true;
		}
		else
		{
			$this->orig_tpl_inherits_id = false;
		}
		$this->orig_tpl_storedb = false;
		$this->_rootref = &$this->_tpldata['.'][0];
		return true;
	}
    
	function set_filenames($filename_array)
	{
		if (!is_array($filename_array))
		{
			return false;
		}
		foreach ($filename_array as $handle => $filename)
		{
			if (empty($filename))
			{
				trigger_error("template->set_filenames: Empty filename specified for $handle", E_USER_ERROR);
			}
			$this->filename[$handle] = $filename;
			$this->files[$handle] = $this->root . '/' . $filename;

			if ($this->inherit_root)
			{
				$this->files_inherit[$handle] = $this->inherit_root . '/' . $filename;
			}
		}
		return true;
	}
    
    function set_filename($filename)
	{
        if (empty($filename))
		{
			trigger_error(TEMP_EMPTY_ERROR, E_USER_ERROR);
		}
		$this->filename['body'] = $filename;
		$this->files['body'] = $this->root . '/' . $filename;
		if ($this->inherit_root)
		{
			$this->files_inherit['body'] = $this->inherit_root . '/' . $filename;
		}
		return true;
	}
    
	function destroy()
	{
		$this->_tpldata = array('.' => array(0 => array()));
		$this->_rootref = &$this->_tpldata['.'][0];
	}
    
	function destroy_block_vars($blockname)
	{
		if (strpos($blockname, '.') !== false)
		{
			$blocks = explode('.', $blockname);
			$blockcount = sizeof($blocks) - 1;
			$str = &$this->_tpldata;
			for ($i = 0; $i < $blockcount; $i++)
			{
				$str = &$str[$blocks[$i]];
				$str = &$str[sizeof($str) - 1];
			}
			unset($str[$blocks[$blockcount]]);
		}
		else
		{
			unset($this->_tpldata[$blockname]);
		}
		return true;
	}
    
	function display($handle, $include_once = true)
	{
		global $user, $phpmega_hook;

		if (!empty($phpmega_hook) && $phpmega_hook->call_hook(array(__CLASS__, __FUNCTION__), $handle, $include_once, $this))
		{
			if ($phpmega_hook->hook_return(array(__CLASS__, __FUNCTION__)))
			{
				return $phpmega_hook->hook_return_result(array(__CLASS__, __FUNCTION__));
			}
		}
		if (defined('IN_ERROR_HANDLER'))
		{
			if ((E_NOTICE & error_reporting()) == E_NOTICE)
			{
				error_reporting(error_reporting() ^ E_NOTICE);
			}
		}
		if ($filename = $this->_tpl_load($handle))
		{
			($include_once) ? include_once($filename) : include($filename);
		}
		else
		{
			eval(' ?>' . $this->compiled_code[$handle] . '<?php ');
		}
		return true;
	}

	function assign_display($handle, $template_var = '', $return_content = true, $include_once = false)
	{
		ob_start();
		$this->display($handle, $include_once);
		$contents = ob_get_clean();
		if ($return_content)
		{
			return $contents;
		}
		$this->assign_var($template_var, $contents);
		return true;
	}
    
    function _tpl_load(&$handle)
	{
		if (!isset($this->filename[$handle]))
		{
			trigger_error("template->_tpl_load(): No file specified for handle $handle", E_USER_ERROR);
		}
		$filename = $this->cachepath . str_replace('/', '.', $this->filename[$handle]) . '.php';
		$this->files_template[$handle] = 0;
		$recompile = false;
		if (!file_exists($filename) || @filesize($filename) === 0 || defined('DEBUG_EXTRA'))
		{
			$recompile = true;
		}
		else if (LOAD_TPL_COMPILE)
		{
			if (!file_exists($this->files[$handle]))
			{
				$this->files[$handle] = $this->files_inherit[$handle];
				$this->files_template[$handle];
			}
			$recompile = (@filemtime($filename) < filemtime($this->files[$handle])) ? true : false;
		}
		if (!$recompile)
		{
			return $filename;
		}
		if (!file_exists($this->files[$handle]))
		{
			$this->files[$handle] = $this->files_inherit[$handle];
			$this->files_template[$handle];
		}
		$compile = new template_compile($this);
		if (!isset($this->files[$handle]))
		{
            msg_handler('14', 'file template : '.$this->filename[$handle].' not found');
			//trigger_error($this->filename[$handle], E_USER_ERROR);
		}
		$compile->_tpl_load_file($handle);
		return false;
	}
    
    
	function assign_vars($vararray)
	{
		foreach ($vararray as $key => $val)
		{
			$this->_rootref[$key] = $val;
		}

		return true;
	}

	function assign_var($varname, $varval)
	{
		$this->_rootref[$varname] = $varval;

		return true;
	}

	function assign_block_vars($blockname, $vararray)
	{
		if (strpos($blockname, '.') !== false)
		{
			$blocks = explode('.', $blockname);
			$blockcount = sizeof($blocks) - 1;
			$str = &$this->_tpldata;
			for ($i = 0; $i < $blockcount; $i++)
			{
				$str = &$str[$blocks[$i]];
				$str = &$str[sizeof($str) - 1];
			}
			$s_row_count = isset($str[$blocks[$blockcount]]) ? sizeof($str[$blocks[$blockcount]]) : 0;
			$vararray['S_ROW_COUNT'] = $s_row_count;
			if (!$s_row_count)
			{
				$vararray['S_FIRST_ROW'] = true;
			}
			$vararray['S_LAST_ROW'] = true;
			if ($s_row_count > 0)
			{
				unset($str[$blocks[$blockcount]][($s_row_count - 1)]['S_LAST_ROW']);
			}
			$str[$blocks[$blockcount]][] = $vararray;
		}
		else
		{
			$s_row_count = (isset($this->_tpldata[$blockname])) ? sizeof($this->_tpldata[$blockname]) : 0;
			$vararray['S_ROW_COUNT'] = $s_row_count;
			if (!$s_row_count)
			{
				$vararray['S_FIRST_ROW'] = true;
			}
			$vararray['S_LAST_ROW'] = true;
			if ($s_row_count > 0)
			{
				unset($this->_tpldata[$blockname][($s_row_count - 1)]['S_LAST_ROW']);
			}
			$this->_tpldata[$blockname][] = $vararray;
		}
		return true;
	}

	function alter_block_array($blockname, $vararray, $key = false, $mode = 'insert')
	{
		if (strpos($blockname, '.') !== false)
		{
			return false;
		}
		if ($key === false || $key === true)
		{
			$key = ($key === false) ? 0 : sizeof($this->_tpldata[$blockname]);
		}
		if (is_array($key))
		{
			list($search_key, $search_value) = @each($key);
			$key = NULL;
			foreach ($this->_tpldata[$blockname] as $i => $val_ary)
			{
				if ($val_ary[$search_key] === $search_value)
				{
					$key = $i;
					break;
				}
			}
			if ($key === NULL)
			{
				return false;
			}
		}

		if ($mode == 'insert')
		{
			if ($key >= sizeof($this->_tpldata[$blockname]))
			{
				$key = sizeof($this->_tpldata[$blockname]);
				unset($this->_tpldata[$blockname][($key - 1)]['S_LAST_ROW']);
				$vararray['S_LAST_ROW'] = true;
			}
			else if ($key === 0)
			{
				unset($this->_tpldata[$blockname][0]['S_FIRST_ROW']);
				$vararray['S_FIRST_ROW'] = true;
			}
			for ($i = sizeof($this->_tpldata[$blockname]); $i > $key; $i--)
			{
				$this->_tpldata[$blockname][$i] = $this->_tpldata[$blockname][$i-1];
				$this->_tpldata[$blockname][$i]['S_ROW_COUNT'] = $i;
			}
			$vararray['S_ROW_COUNT'] = $key;
			$this->_tpldata[$blockname][$key] = $vararray;

			return true;
		}
		if ($mode == 'change')
		{
			if ($key == sizeof($this->_tpldata[$blockname]))
			{
				$key--;
			}
			$this->_tpldata[$blockname][$key] = array_merge($this->_tpldata[$blockname][$key], $vararray);
			return true;
		}
		return false;
	}

	function _tpl_include($filename, $include = true)
	{
		$handle = $filename;
		$this->filename[$handle] = $filename;
		$this->files[$handle] = $this->root . '/' . $filename;
		if ($this->inherit_root)
		{
			$this->files_inherit[$handle] = $this->inherit_root . '/' . $filename;
		}
		$filename = $this->_tpl_load($handle);
		if ($include)
		{
			global $user;

			if ($filename)
			{
				include($filename);
				return;
			}
			eval(' ?>' . $this->compiled_code[$handle] . '<?php ');
		}
	}

	function _php_include($filename)
	{
		global $mega_root_path;

		$file = $mega_root_path . $filename;

		if (!file_exists($file))
		{
			echo 'template->_php_include(): File ' . htmlspecialchars($file) . ' does not exist or is empty';
			return;
		}
		include($file);
	}
}

class cache extends acm
{
	function obtain_config($db)
    {
		if (($config = $this->get('config')) !== false){
			$sql    = 'SELECT config_name, config_value FROM ' . CONFIG_TABLE . '';
			$result = $db->sql_query($sql);
			while ($row = $db->sql_fetchrow($result)){$config[$row['config_name']] = $row['config_value'];}
			$db->sql_freeresult($result);
		}
		else{
			$config = $cached_config = array();
			$sql    = 'SELECT config_name, config_value FROM ' . CONFIG_TABLE;
			$result = $db->sql_query($sql);
			while ($row = $db->sql_fetchrow($result)){
				$config[$row['config_name']] = $row['config_value'];
			}
			$db->sql_freeresult($result);
			$this->put('config', $cached_config);
		}
		return $config;
	}
}

class system_start
{
    public function setup( $temppath = '', $tempname = '', $dircache = '', $tar = '', $fallback_template_path = '')  
	{
        global $template, $setting;
        if($temppath and $tempname)  
        { 
            $template->set_custom_template($temppath,$tempname, $fallback_template_path, $dircache, $tar);
        } 
        else 
        { 
            $template->set_template();  
        }
		return; 
	}
}
?>
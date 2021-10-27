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

class dbal
{
	var $db_connect_id;
	var $query_result;
	var $return_on_error = false;
	var $transaction = false;
	var $sql_time = 0;
	var $num_queries = array();
	var $open_queries = array();
	var $curtime = 0;
	var $query_hold = '';
	var $html_hold = '';
	var $sql_report = '';
	var $persistency = false;
	var $user = '';
	var $server = '';
	var $dbname = '';
	var $sql_error_triggered = false;
	var $sql_error_sql = '';
	var $sql_error_returned = array();
	var $transactions = 0;
	var $multi_insert = false;
	var $sql_layer = '';
	var $any_char;
	var $one_char;
	var $sql_server_version = false;
    public function __construct()
    {
        
    }
	function dbal()
	{
		$this->num_queries = array(
			'cached'		=> 0,
			'normal'		=> 0,
			'total'			=> 0,
		);

		// Fill default sql layer based on the class being called.
		// This can be changed by the specified layer itself later if needed.
		$this->sql_layer = substr(get_class($this), 5);

		// Do not change this please! This variable is used to easy the use of it - and is hardcoded.
		$this->any_char = chr(0) . '%';
		$this->one_char = chr(0) . '_';
	}

	function sql_return_on_error($fail = false)
	{
		$this->sql_error_triggered = false;
		$this->sql_error_sql = '';

		$this->return_on_error = $fail;
	}

	function sql_num_queries($cached = false)
	{
		return ($cached) ? $this->num_queries['cached'] : $this->num_queries['normal'];
	}

	function sql_add_num_queries($cached = false)
	{
		$this->num_queries['cached'] += ($cached !== false) ? 1 : 0;
		$this->num_queries['normal'] += ($cached !== false) ? 0 : 1;
		$this->num_queries['total'] += 1;
	}

	function sql_close()
	{
		if (!$this->db_connect_id)
		{
			return false;
		}
		if ($this->transaction)
		{
			do
			{
				$this->sql_transaction('commit');
			}
			while ($this->transaction);
		}
		foreach ($this->open_queries as $query_id)
		{
			$this->sql_freeresult($query_id);
		}
		if ($result = $this->_sql_close())
		{
			$this->db_connect_id = false;
		}

		return $result;
	}

	function sql_query_limit($query, $total, $offset = 0, $cache_ttl = 0)
	{
		if (empty($query))
		{
			return false;
		}
		$total = ($total < 0) ? 0 : $total;
		$offset = ($offset < 0) ? 0 : $offset;

		return $this->_sql_query_limit($query, $total, $offset, $cache_ttl);
	}

	function sql_fetchrowset($query_id = false)
	{
		if ($query_id === false)
		{
			$query_id = $this->query_result;
		}

		if ($query_id !== false)
		{
			$result = array();
			while ($row = $this->sql_fetchrow($query_id))
			{
				$result[] = $row;
			}

			return $result;
		}

		return false;
	}

	function sql_transaction($status = 'begin')
	{
		switch ($status)
		{
			case 'begin':
				if ($this->transaction)
				{
					$this->transactions++;
					return true;
				}

				$result = $this->_sql_transaction('begin');

				if (!$result)
				{
					$this->sql_error();
				}

				$this->transaction = true;
			break;

			case 'commit':
				if ($this->transaction && $this->transactions)
				{
					$this->transactions--;
					return true;
				}

				if (!$this->transaction)
				{
					return false;
				}

				$result = $this->_sql_transaction('commit');

				if (!$result)
				{
					$this->sql_error();
				}

				$this->transaction = false;
				$this->transactions = 0;
			break;

			case 'rollback':
				$result = $this->_sql_transaction('rollback');
				$this->transaction = false;
				$this->transactions = 0;
			break;

			default:
				$result = $this->_sql_transaction($status);
			break;
		}

		return $result;
	}

	function sql_build_array($query, $assoc_ary = false)
	{
		if (!is_array($assoc_ary))
		{
			return false;
		}

		$fields = $values = array();

		if ($query == 'INSERT' || $query == 'INSERT_SELECT')
		{
			foreach ($assoc_ary as $key => $var)
			{
				$fields[] = $key;

				if (is_array($var) && is_string($var[0]))
				{
					$values[] = $var[0];
				}
				else
				{
					$values[] = $this->_sql_validate_value($var);
				}
			}

			$query = ($query == 'INSERT') ? ' (' . implode(', ', $fields) . ') VALUES (' . implode(', ', $values) . ')' : ' (' . implode(', ', $fields) . ') SELECT ' . implode(', ', $values) . ' ';
		}
		else if ($query == 'MULTI_INSERT')
		{
			trigger_error('The MULTI_INSERT query value is no longer supported. Please use sql_multi_insert() instead.', E_USER_ERROR);
		}
		else if ($query == 'UPDATE' || $query == 'SELECT')
		{
			$values = array();
			foreach ($assoc_ary as $key => $var)
			{
				$values[] = "$key = " . $this->_sql_validate_value($var);
			}
			$query = implode(($query == 'UPDATE') ? ', ' : ' AND ', $values);
		}

		return $query;
	}

	function sql_in_set($field, $array, $negate = false, $allow_empty_set = false)
	{
		if (!sizeof($array))
		{
			if (!$allow_empty_set)
			{
				$this->sql_error('No values specified for SQL IN comparison');
			}
			else
			{
				if ($negate)
				{
					return '1=1';
				}
				else
				{
					return '1=0';
				}
			}
		}

		if (!is_array($array))
		{
			$array = array($array);
		}

		if (sizeof($array) == 1)
		{
			@reset($array);
			$var = current($array);

			return $field . ($negate ? ' <> ' : ' = ') . $this->_sql_validate_value($var);
		}
		else
		{
			return $field . ($negate ? ' NOT IN ' : ' IN ') . '(' . implode(', ', array_map(array($this, '_sql_validate_value'), $array)) . ')';
		}
	}

	function sql_bit_and($column_name, $bit, $compare = '')
	{
		if (method_exists($this, '_sql_bit_and'))
		{
			return $this->_sql_bit_and($column_name, $bit, $compare);
		}

		return $column_name . ' & ' . (1 << $bit) . (($compare) ? ' ' . $compare : '');
	}

	function sql_bit_or($column_name, $bit, $compare = '')
	{
		if (method_exists($this, '_sql_bit_or'))
		{
			return $this->_sql_bit_or($column_name, $bit, $compare);
		}

		return $column_name . ' | ' . (1 << $bit) . (($compare) ? ' ' . $compare : '');
	}

	function sql_multi_insert($table, &$sql_ary)
	{
		if (!sizeof($sql_ary))
		{
			return false;
		}

		if ($this->multi_insert)
		{
			$ary = array();
			foreach ($sql_ary as $id => $_sql_ary)
			{
				if (!is_array($_sql_ary))
				{
					return $this->sql_query('INSERT INTO ' . $table . ' ' . $this->sql_build_array('INSERT', $sql_ary));
				}

				$values = array();
				foreach ($_sql_ary as $key => $var)
				{
					$values[] = $this->_sql_validate_value($var);
				}
				$ary[] = '(' . implode(', ', $values) . ')';
			}

			return $this->sql_query('INSERT INTO ' . $table . ' ' . ' (' . implode(', ', array_keys($sql_ary[0])) . ') VALUES ' . implode(', ', $ary));
		}
		else
		{
			foreach ($sql_ary as $ary)
			{
				if (!is_array($ary))
				{
					return false;
				}

				$result = $this->sql_query('INSERT INTO ' . $table . ' ' . $this->sql_build_array('INSERT', $ary));

				if (!$result)
				{
					return false;
				}
			}
		}

		return true;
	}

	function _sql_validate_value($var)
	{
		if (is_null($var))
		{
			return 'NULL';
		}
		else if (is_string($var))
		{
			return "'" . $this->sql_escape($var) . "'";
		}
		else
		{
			return (is_bool($var)) ? intval($var) : $var;
		}
	}

	function sql_build_query($query, $array)
	{
		$sql = '';
		switch ($query)
		{
			case 'SELECT':
			case 'SELECT_DISTINCT';

				$sql = str_replace('_', ' ', $query) . ' ' . $array['SELECT'] . ' FROM ';

				$table_array = $aliases = array();
				$used_multi_alias = false;

				foreach ($array['FROM'] as $table_name => $alias)
				{
					if (is_array($alias))
					{
						$used_multi_alias = true;

						foreach ($alias as $multi_alias)
						{
							$table_array[] = $table_name . ' ' . $multi_alias;
							$aliases[] = $multi_alias;
						}
					}
					else
					{
						$table_array[] = $table_name . ' ' . $alias;
						$aliases[] = $alias;
					}
				}
				if (!empty($array['LEFT_JOIN']) && sizeof($array['FROM']) > 1 && $used_multi_alias !== false)
				{
				
					$join = current($array['LEFT_JOIN']);

					preg_match('/(' . implode('|', $aliases) . ')\.[^\s]+/U', str_replace(array('(', ')', 'AND', 'OR', ' '), '', $join['ON']), $matches);

					if (!empty($matches[1]))
					{
						$first_join_match = trim($matches[1]);
						$table_array = $last = array();

						foreach ($array['FROM'] as $table_name => $alias)
						{
							if (is_array($alias))
							{
								foreach ($alias as $multi_alias)
								{
									($multi_alias === $first_join_match) ? $last[] = $table_name . ' ' . $multi_alias : $table_array[] = $table_name . ' ' . $multi_alias;
								}
							}
							else
							{
								($alias === $first_join_match) ? $last[] = $table_name . ' ' . $alias : $table_array[] = $table_name . ' ' . $alias;
							}
						}

						$table_array = array_merge($table_array, $last);
					}
				}

				$sql .= $this->_sql_custom_build('FROM', implode(' CROSS JOIN ', $table_array));

				if (!empty($array['LEFT_JOIN']))
				{
					foreach ($array['LEFT_JOIN'] as $join)
					{
						$sql .= ' LEFT JOIN ' . key($join['FROM']) . ' ' . current($join['FROM']) . ' ON (' . $join['ON'] . ')';
					}
				}

				if (!empty($array['WHERE']))
				{
					$sql .= ' WHERE ' . $this->_sql_custom_build('WHERE', $array['WHERE']);
				}

				if (!empty($array['GROUP_BY']))
				{
					$sql .= ' GROUP BY ' . $array['GROUP_BY'];
				}

				if (!empty($array['ORDER_BY']))
				{
					$sql .= ' ORDER BY ' . $array['ORDER_BY'];
				}

			break;
		}

		return $sql;
	}

	function sql_error($sql = '')
	{
		global $auth, $user, $config;
		$this->sql_error_triggered = true;
		$this->sql_error_sql = $sql;

		$this->sql_error_returned = $this->_sql_error();

		if (!$this->return_on_error)
		{
			$message = '
            <div dir="ltr" style="text-align:left;font: normal 9pt Tahoma;">
            SQL ERROR [ ' . $this->sql_layer . ' ]<br />' . $this->sql_error_returned['message'] . ' [' . $this->sql_error_returned['code'] . ']
            </div>
            ';
			if ($this->transaction)
			{
				$this->sql_transaction('rollback');
			}

			if (strlen($message) > 1024)
			{
				global $msg_long_text;
				$msg_long_text = $message;

				trigger_error(false, E_USER_ERROR);
			}
			trigger_error($message, E_USER_ERROR);
		}

		if ($this->transaction)
		{
			$this->sql_transaction('rollback');
		}

		return $this->sql_error_returned;
	}

	function sql_report($mode, $query = '')
	{
		global $cache, $starttime, $Rapid_root_path, $user;

		if (empty($_REQUEST['explain']))
		{
			return false;
		}

		if (!$query && $this->query_hold != '')
		{
			$query = $this->query_hold;
		}

		switch ($mode)
		{
			case 'display':
				if (!empty($cache))
				{
					$cache->unload();
				}
				$this->sql_close();

				$mtime = explode(' ', microtime());
				$totaltime = $mtime[0] + $mtime[1] - $starttime;

				

				exit_handler();

			break;

			case 'stop':
				$endtime = explode(' ', microtime());
				$endtime = $endtime[0] + $endtime[1];

				$this->sql_report .= '

					<table cellspacing="1">
					<thead>
					<tr>
						<th>Query #' . $this->num_queries['total'] . '</th>
					</tr>
					</thead>
					<tbody>
					<tr>
						<td class="row3"><textarea style="font-family:\'Courier New\',monospace;width:99%" rows="5" cols="10">' . preg_replace('/\t(AND|OR)(\W)/', "\$1\$2", htmlspecialchars(preg_replace('/[\s]*[\n\r\t]+[\n\r\s\t]*/', "\n", $query))) . '</textarea></td>
					</tr>
					</tbody>
					</table>

					' . $this->html_hold . '

					<p style="text-align: center;">
				';

				if ($this->query_result)
				{
					if (preg_match('/^(UPDATE|DELETE|REPLACE)/', $query))
					{
						$this->sql_report .= 'Affected rows: <b>' . $this->sql_affectedrows($this->query_result) . '</b> | ';
					}
					$this->sql_report .= 'Before: ' . sprintf('%.5f', $this->curtime - $starttime) . 's | After: ' . sprintf('%.5f', $endtime - $starttime) . 's | Elapsed: <b>' . sprintf('%.5f', $endtime - $this->curtime) . 's</b>';
				}
				else
				{
					$error = $this->sql_error();
					$this->sql_report .= '<b style="color: red">FAILED</b> - ' . $this->sql_layer . ' Error ' . $error['code'] . ': ' . htmlspecialchars($error['message']);
				}

				$this->sql_report .= '</p><br /><br />';

				$this->sql_time += $endtime - $this->curtime;
			break;

			case 'start':
				$this->query_hold = $query;
				$this->html_hold = '';

				$this->_sql_report($mode, $query);

				$this->curtime = explode(' ', microtime());
				$this->curtime = $this->curtime[0] + $this->curtime[1];

			break;

			case 'add_select_row':

				$html_table = func_get_arg(2);
				$row = func_get_arg(3);

				if (!$html_table && sizeof($row))
				{
					$html_table = true;
					$this->html_hold .= '<table cellspacing="1"><tr>';

					foreach (array_keys($row) as $val)
					{
						$this->html_hold .= '<th>' . (($val) ? ucwords(str_replace('_', ' ', $val)) : '&nbsp;') . '</th>';
					}
					$this->html_hold .= '</tr>';
				}
				$this->html_hold .= '<tr>';

				$class = 'row1';
				foreach (array_values($row) as $val)
				{
					$class = ($class == 'row1') ? 'row2' : 'row1';
					$this->html_hold .= '<td class="' . $class . '">' . (($val) ? $val : '&nbsp;') . '</td>';
				}
				$this->html_hold .= '</tr>';

				return $html_table;

			break;

			case 'fromcache':

				$this->_sql_report($mode, $query);

			break;

			case 'record_fromcache':

				$endtime = func_get_arg(2);
				$splittime = func_get_arg(3);

				$time_cache = $endtime - $this->curtime;
				$time_db = $splittime - $endtime;
				$color = ($time_db > $time_cache) ? 'green' : 'red';

				$this->sql_report .= '<table cellspacing="1"><thead><tr><th>Query results obtained from the cache</th></tr></thead><tbody><tr>';
				$this->sql_report .= '<td class="row3"><textarea style="font-family:\'Courier New\',monospace;width:99%" rows="5" cols="10">' . preg_replace('/\t(AND|OR)(\W)/', "\$1\$2", htmlspecialchars(preg_replace('/[\s]*[\n\r\t]+[\n\r\s\t]*/', "\n", $query))) . '</textarea></td></tr></tbody></table>';
				$this->sql_report .= '<p style="text-align: center;">';
				$this->sql_report .= 'Before: ' . sprintf('%.5f', $this->curtime - $starttime) . 's | After: ' . sprintf('%.5f', $endtime - $starttime) . 's | Elapsed [cache]: <b style="color: ' . $color . '">' . sprintf('%.5f', ($time_cache)) . 's</b> | Elapsed [db]: <b>' . sprintf('%.5f', $time_db) . 's</b></p><br /><br />';
				$starttime += $time_db;

			break;

			default:

				$this->_sql_report($mode, $query);

			break;
		}

		return true;
	}
}

class dbal_mysql extends dbal
{
	var $multi_insert = true;

	function sql_connect($sqlserver, $sqluser, $sqlpassword, $database, $port = false, $persistency = false, $new_link = false)
	{
		$this->persistency = $persistency;
		$this->user = $sqluser;
		$this->server = $sqlserver . (($port) ? ':' . $port : '');
		$this->dbname = $database;

		$this->sql_layer = 'mysql4';

		$this->db_connect_id = ($this->persistency) ? @mysql_pconnect($this->server, $this->user, $sqlpassword) : @mysql_connect($this->server, $this->user, $sqlpassword, $new_link);

		if ($this->db_connect_id && $this->dbname != '')
		{
			if (@mysql_select_db($this->dbname, $this->db_connect_id))
			{

				if (version_compare($this->sql_server_info(true), '4.1.0', '>='))
				{
					@mysql_query("SET NAMES 'utf8'", $this->db_connect_id);

					if (version_compare($this->sql_server_info(true), '5.0.2', '>='))
					{
						$result = @mysql_query('SELECT @@session.sql_mode AS sql_mode', $this->db_connect_id);
						$row = @mysql_fetch_assoc($result);
						@mysql_free_result($result);
						$modes = array_map('trim', explode(',', $row['sql_mode']));

						if (!in_array('TRADITIONAL', $modes))
						{
							if (!in_array('STRICT_ALL_TABLES', $modes))
							{
								$modes[] = 'STRICT_ALL_TABLES';
							}

							if (!in_array('STRICT_TRANS_TABLES', $modes))
							{
								$modes[] = 'STRICT_TRANS_TABLES';
							}
						}

						$mode = implode(',', $modes);
						@mysql_query("SET SESSION sql_mode='{$mode}'", $this->db_connect_id);
					}
				}
				else if (version_compare($this->sql_server_info(true), '4.0.0', '<'))
				{
					$this->sql_layer = 'mysql';
				}

				return $this->db_connect_id;
			}
		}

		return $this->sql_error('');
	}

	function sql_server_info($raw = false, $use_cache = true)
	{
		global $cache;

		if (!$use_cache || empty($cache) || ($this->sql_server_version = $cache->get('mysql_version')) === false)
		{
			$result = @mysql_query('SELECT VERSION() AS version', $this->db_connect_id);
			$row = @mysql_fetch_assoc($result);
			@mysql_free_result($result);

			$this->sql_server_version = $row['version'];

			if (!empty($cache) && $use_cache)
			{
				$cache->put('mysql_version', $this->sql_server_version);
			}
		}

		return ($raw) ? $this->sql_server_version : 'MySQL ' . $this->sql_server_version;
	}

	function _sql_transaction($status = 'begin')
	{
		switch ($status)
		{
			case 'begin':
				return @mysql_query('BEGIN', $this->db_connect_id);
			break;

			case 'commit':
				return @mysql_query('COMMIT', $this->db_connect_id);
			break;

			case 'rollback':
				return @mysql_query('ROLLBACK', $this->db_connect_id);
			break;
		}

		return true;
	}
    
    

      
      
	function sql_query($query = '', $cache_ttl = 0)
	{
		if ($query != '')
		{
			global $cache;
			if (defined('DEBUG_EXTRA'))
			{
				$this->sql_report('start', $query);
			}

			$this->query_result = ($cache_ttl && method_exists($cache, 'sql_load')) ? $cache->sql_load($query) : false;
			$this->sql_add_num_queries($this->query_result);

			if ($this->query_result === false)
			{
				if (($this->query_result = @mysql_query($query, $this->db_connect_id)) === false)
				{
					$this->sql_error($query);
				}

				if (defined('DEBUG_EXTRA'))
				{
					$this->sql_report('stop', $query);
				}

				if ($cache_ttl && method_exists($cache, 'sql_save'))
				{
					$this->open_queries[(int) $this->query_result] = $this->query_result;
					$cache->sql_save($query, $this->query_result, $cache_ttl);
				}
				else if (strpos($query, 'SELECT') === 0 && $this->query_result)
				{
					$this->open_queries[(int) $this->query_result] = $this->query_result;
				}
			}
			else if (defined('DEBUG_EXTRA'))
			{
				$this->sql_report('fromcache', $query);
			}
		}
		else
		{
			return false;
		}

		return $this->query_result;
	}

	function _sql_query_limit($query, $total, $offset = 0, $cache_ttl = 0)
	{
		$this->query_result = false;
		if ($total == 0)
		{
			$total = '18446744073709551615';
		}

		$query .= "\n LIMIT " . ((!empty($offset)) ? $offset . ', ' . $total : $total);

		return $this->sql_query($query, $cache_ttl);
	}

	function sql_numrows($query, $cache_ttl = 0)
	{
		$this->query_result = false;
        
		return @mysql_num_rows($this->sql_query($query, $cache_ttl));
	}
    
    function get_row_count($table_name)
    {
        $result = mysql_query("SELECT * FROM ".$table_name."");
        $num_rows = mysql_num_rows($result);
        return $num_rows;
    }

	function sql_affectedrows()
	{
		return ($this->db_connect_id) ? @mysql_affected_rows($this->db_connect_id) : false;
	}

	function sql_fetchrow($query_id = false,$fetcharray = false)
	{
		global $cache;

		if ($query_id === false)
		{
			$query_id = $this->query_result;
		}

		if (isset($cache->sql_rowset[$query_id]))
		{
			return $cache->sql_fetchrow($query_id);
		}
        if($fetcharray == false)
        {
            return ($query_id !== false) ? mysql_fetch_assoc($query_id) : false;
        }
		else
        {
            return ($query_id !== false) ? mysql_fetch_array($query_id) : false;
        }
        
	}
    
    public function fetch($query_id)
    {
        $record = mysql_fetch_array($query_id, MYSQL_ASSOC);
        return $record;
    }
      
    public function fetch_all($sql)
    {
        $query_id = $this->sql_fetchrow($sql);
        $record = array();
        
        while ($row = $this->fetch($sql)) :
          $record[] = $row;
        endwhile;
        return $record;   
    }
      
      

	function sql_rowseek($rownum, &$query_id)
	{
		global $cache;

		if ($query_id === false)
		{
			$query_id = $this->query_result;
		}

		if (isset($cache->sql_rowset[$query_id]))
		{
			return $cache->sql_rowseek($rownum, $query_id);
		}

		return ($query_id !== false) ? @mysql_data_seek($query_id, $rownum) : false;
	}

	function sql_nextid()
	{
		return ($this->db_connect_id) ? @mysql_insert_id($this->db_connect_id) : false;
	}

	function sql_freeresult($query_id = false)
	{
		global $cache;

		if ($query_id === false)
		{
			$query_id = $this->query_result;
		}

		if (isset($cache->sql_rowset[$query_id]))
		{
			return $cache->sql_freeresult($query_id);
		}

		if (isset($this->open_queries[(int) $query_id]))
		{
			unset($this->open_queries[(int) $query_id]);
			return @mysql_free_result($query_id);
		}

		return false;
	}

	function sql_escape($msg)
	{
		if (!$this->db_connect_id)
		{
			return @mysql_real_escape_string($msg);
		}

		return @mysql_real_escape_string($msg, $this->db_connect_id);
	}
    
	function _sql_custom_build($stage, $data)
	{
		switch ($stage)
		{
			case 'FROM':
				$data = '(' . $data . ')';
			break;
		}

		return $data;
	}

	function _sql_error()
	{
		if (!$this->db_connect_id)
		{
			return array(
				'message'	=> @mysql_error(),
				'code'		=> @mysql_errno()
			);
		}

		return array(
			'message'	=> @mysql_error($this->db_connect_id),
			'code'		=> @mysql_errno($this->db_connect_id)
		);
	}

	function _sql_close()
	{
		return @mysql_close($this->db_connect_id);
	}
    
	function _sql_report($mode, $query = '')
	{
		static $test_prof;
		if ($test_prof === null)
		{
			$test_prof = false;
			if (version_compare($this->sql_server_info(true), '5.0.37', '>=') && version_compare($this->sql_server_info(true), '5.1', '<'))
			{
				$test_prof = true;
			}
		}

		switch ($mode)
		{
			case 'start':

				$explain_query = $query;
				if (preg_match('/UPDATE ([a-z0-9_]+).*?WHERE(.*)/s', $query, $m))
				{
					$explain_query = 'SELECT * FROM ' . $m[1] . ' WHERE ' . $m[2];
				}
				else if (preg_match('/DELETE FROM ([a-z0-9_]+).*?WHERE(.*)/s', $query, $m))
				{
					$explain_query = 'SELECT * FROM ' . $m[1] . ' WHERE ' . $m[2];
				}
				if (preg_match('/^SELECT/', $explain_query))
				{
					$html_table = false;
					if ($test_prof)
					{
						@mysql_query('SET profiling = 1;', $this->db_connect_id);
					}
					if ($result = @mysql_query("EXPLAIN $explain_query", $this->db_connect_id))
					{
						while ($row = @mysql_fetch_assoc($result))
						{
							$html_table = $this->sql_report('add_select_row', $query, $html_table, $row);
						}
					}
					@mysql_free_result($result);
					if ($html_table)
					{
						$this->html_hold .= '</table>';
					}
					if ($test_prof)
					{
						$html_table = false;
						if ($result = @mysql_query('SHOW PROFILE ALL;', $this->db_connect_id))
						{
							$this->html_hold .= '<br />';
							while ($row = @mysql_fetch_assoc($result))
							{
								if (!empty($row['Source_function']))
								{
									$row['Source_function'] = str_replace(array('<', '>'), array('&lt;', '&gt;'), $row['Source_function']);
								}
								foreach ($row as $key => $val)
								{
									if ($val === null)
									{
										unset($row[$key]);
									}
								}
								$html_table = $this->sql_report('add_select_row', $query, $html_table, $row);
							}
						}
						@mysql_free_result($result);
						if ($html_table)
						{
							$this->html_hold .= '</table>';
						}
						@mysql_query('SET profiling = 0;', $this->db_connect_id);
					}
				}

			break;
			case 'fromcache':
				$endtime = explode(' ', microtime());
				$endtime = $endtime[0] + $endtime[1];
				$result = @mysql_query($query, $this->db_connect_id);
				while ($void = @mysql_fetch_assoc($result))
				{
					
				}
				@mysql_free_result($result);
				$splittime = explode(' ', microtime());
				$splittime = $splittime[0] + $splittime[1];
				$this->sql_report('record_fromcache', $query, $endtime, $splittime);
			break;
		}
	}
}

class mysql
{
	var $multi_insert = true;
	var $connect_error = '';
	function sql_connect($sqlserver, $sqluser, $sqlpassword, $database, $port = false, $persistency = false, $new_link = false)
	{
		$this->persistency = $persistency;
		$this->user = $sqluser;
		$this->server = $sqlserver . (($port) ? ':' . $port : '');
		$this->dbname = $database;
		$this->sql_layer = 'mysql4';
		if ($this->persistency)
		{
			if (!function_exists('mysql_pconnect'))
			{
				$this->connect_error = 'mysql_pconnect function does not exist, is mysql extension installed?';
				return $this->sql_error('');
			}
			$this->db_connect_id = @mysql_pconnect($this->server, $this->user, $sqlpassword);
		}
		else
		{
			if (!function_exists('mysql_connect'))
			{
				$this->connect_error = 'mysql_connect function does not exist, is mysql extension installed?';
				return $this->sql_error('');
			}
			$this->db_connect_id = @mysql_connect($this->server, $this->user, $sqlpassword, $new_link);
		}

		if ($this->db_connect_id && $this->dbname != '')
		{
			if (@mysql_select_db($this->dbname, $this->db_connect_id))
			{
				// Determine what version we are using and if it natively supports UNICODE
				if (version_compare($this->sql_server_info(true), '4.1.0', '>='))
				{
					@mysql_query("SET NAMES 'utf8'", $this->db_connect_id);

					// enforce strict mode on databases that support it
					if (version_compare($this->sql_server_info(true), '5.0.2', '>='))
					{
						$result = @mysql_query('SELECT @@session.sql_mode AS sql_mode', $this->db_connect_id);
						if ($result)
						{
							$row = mysql_fetch_assoc($result);
							mysql_free_result($result);
							$modes = array_map('trim', explode(',', $row['sql_mode']));
						}
						else
						{
							$modes = array();
						}

						// TRADITIONAL includes STRICT_ALL_TABLES and STRICT_TRANS_TABLES
						if (!in_array('TRADITIONAL', $modes))
						{
							if (!in_array('STRICT_ALL_TABLES', $modes))
							{
								$modes[] = 'STRICT_ALL_TABLES';
							}

							if (!in_array('STRICT_TRANS_TABLES', $modes))
							{
								$modes[] = 'STRICT_TRANS_TABLES';
							}
						}

						$mode = implode(',', $modes);
						@mysql_query("SET SESSION sql_mode='{$mode}'", $this->db_connect_id);
					}
				}
				else if (version_compare($this->sql_server_info(true), '4.0.0', '<'))
				{
					$this->sql_layer = 'mysql';
				}

				return $this->db_connect_id;
			}
		}

		return $this->sql_error('');
	}

	/**
	* {@inheritDoc}
	*/
	function sql_server_info($raw = false, $use_cache = true)
	{
		global $cache;

		if (!$use_cache || empty($cache) || ($this->sql_server_version = $cache->get('mysql_version')) === false)
		{
			$result = @mysql_query('SELECT VERSION() AS version', $this->db_connect_id);
			if ($result)
			{
				$row = mysql_fetch_assoc($result);
				mysql_free_result($result);

				$this->sql_server_version = $row['version'];

				if (!empty($cache) && $use_cache)
				{
					$cache->put('mysql_version', $this->sql_server_version);
				}
			}
		}

		return ($raw) ? $this->sql_server_version : 'MySQL ' . $this->sql_server_version;
	}

	/**
	* SQL Transaction
	* @access private
	*/
	function _sql_transaction($status = 'begin')
	{
		switch ($status)
		{
			case 'begin':
				return @mysql_query('BEGIN', $this->db_connect_id);
			break;

			case 'commit':
				return @mysql_query('COMMIT', $this->db_connect_id);
			break;

			case 'rollback':
				return @mysql_query('ROLLBACK', $this->db_connect_id);
			break;
		}

		return true;
	}

	/**
	* {@inheritDoc}
	*/
	function sql_query($query = '', $cache_ttl = 0)
	{
		if ($query != '')
		{
			global $cache;

			// EXPLAIN only in extra debug mode
			if (defined('DEBUG'))
			{
				$this->sql_report('start', $query);
			}
			else if (defined('PHPBB_DISPLAY_LOAD_TIME'))
			{
				$this->curtime = microtime(true);
			}

			$this->query_result = ($cache && $cache_ttl) ? $cache->sql_load($query) : false;
			$this->sql_add_num_queries($this->query_result);

			if ($this->query_result === false)
			{
				if (($this->query_result = @mysql_query($query, $this->db_connect_id)) === false)
				{
					$this->sql_error($query);
				}

				if (defined('DEBUG'))
				{
					$this->sql_report('stop', $query);
				}
				else if (defined('PHPBB_DISPLAY_LOAD_TIME'))
				{
					$this->sql_time += microtime(true) - $this->curtime;
				}

				if (!$this->query_result)
				{
					return false;
				}

				if ($cache && $cache_ttl)
				{
					$this->open_queries[(int) $this->query_result] = $this->query_result;
					$this->query_result = $cache->sql_save($this, $query, $this->query_result, $cache_ttl);
				}
				else if (strpos($query, 'SELECT') === 0)
				{
					$this->open_queries[(int) $this->query_result] = $this->query_result;
				}
			}
			else if (defined('DEBUG'))
			{
				$this->sql_report('fromcache', $query);
			}
		}
		else
		{
			return false;
		}

		return $this->query_result;
	}

	/**
	* {@inheritDoc}
	*/
	function sql_affectedrows()
	{
		if ($this->db_connect_id)
		{
			// We always want the number of matched rows
			// instead of changed rows, when running an update.
			// So when mysql_info() returns the number of matched rows
			// we return that one instead of mysql_affected_rows()
			$mysql_info = @mysql_info($this->db_connect_id);
			if ($mysql_info !== false)
			{
				$match = array();
				preg_match('#^Rows matched: (\d)+  Changed: (\d)+  Warnings: (\d)+$#', $mysql_info, $match);
				if (isset($match[1]))
				{
					return $match[1];
				}
			}

			return @mysql_affected_rows($this->db_connect_id);
		}
		return false;
	}

	/**
	* {@inheritDoc}
	*/
	function sql_fetchrow($query_id = false)
	{
		global $cache;

		if ($query_id === false)
		{
			$query_id = $this->query_result;
		}

		if ($cache && $cache->sql_exists($query_id))
		{
			return $cache->sql_fetchrow($query_id);
		}

		return ($query_id) ? mysql_fetch_assoc($query_id) : false;
	}

	/**
	* {@inheritDoc}
	*/
	function sql_rowseek($rownum, &$query_id)
	{
		global $cache;

		if ($query_id === false)
		{
			$query_id = $this->query_result;
		}

		if ($cache && $cache->sql_exists($query_id))
		{
			return $cache->sql_rowseek($rownum, $query_id);
		}

		return ($query_id !== false) ? @mysql_data_seek($query_id, $rownum) : false;
	}

	/**
	* {@inheritDoc}
	*/
	function sql_nextid()
	{
		return ($this->db_connect_id) ? @mysql_insert_id($this->db_connect_id) : false;
	}

	/**
	* {@inheritDoc}
	*/
	function sql_freeresult($query_id = false)
	{
		global $cache;

		if ($query_id === false)
		{
			$query_id = $this->query_result;
		}

		if ($cache && !is_object($query_id) && $cache->sql_exists($query_id))
		{
			return $cache->sql_freeresult($query_id);
		}

		if (isset($this->open_queries[(int) $query_id]))
		{
			unset($this->open_queries[(int) $query_id]);
			return mysql_free_result($query_id);
		}

		return false;
	}

	/**
	* {@inheritDoc}
	*/
	function sql_escape($msg)
	{
		if (!$this->db_connect_id)
		{
			return @mysql_real_escape_string($msg);
		}

		return @mysql_real_escape_string($msg, $this->db_connect_id);
	}

	/**
	* return sql error array
	* @access private
	*/
	function _sql_error()
	{
		if ($this->db_connect_id)
		{
			$error = array(
				'message'	=> @mysql_error($this->db_connect_id),
				'code'		=> @mysql_errno($this->db_connect_id),
			);
		}
		else if (function_exists('mysql_error'))
		{
			$error = array(
				'message'	=> @mysql_error(),
				'code'		=> @mysql_errno(),
			);
		}
		else
		{
			$error = array(
				'message'	=> $this->connect_error,
				'code'		=> '',
			);
		}

		return $error;
	}

	/**
	* Close sql connection
	* @access private
	*/
	function _sql_close()
	{
		return @mysql_close($this->db_connect_id);
	}

	/**
	* Build db-specific report
	* @access private
	*/
	function _sql_report($mode, $query = '')
	{
		static $test_prof;

		// current detection method, might just switch to see the existance of INFORMATION_SCHEMA.PROFILING
		if ($test_prof === null)
		{
			$test_prof = false;
			if (version_compare($this->sql_server_info(true), '5.0.37', '>=') && version_compare($this->sql_server_info(true), '5.1', '<'))
			{
				$test_prof = true;
			}
		}

		switch ($mode)
		{
			case 'start':

				$explain_query = $query;
				if (preg_match('/UPDATE ([a-z0-9_]+).*?WHERE(.*)/s', $query, $m))
				{
					$explain_query = 'SELECT * FROM ' . $m[1] . ' WHERE ' . $m[2];
				}
				else if (preg_match('/DELETE FROM ([a-z0-9_]+).*?WHERE(.*)/s', $query, $m))
				{
					$explain_query = 'SELECT * FROM ' . $m[1] . ' WHERE ' . $m[2];
				}

				if (preg_match('/^SELECT/', $explain_query))
				{
					$html_table = false;

					// begin profiling
					if ($test_prof)
					{
						@mysql_query('SET profiling = 1;', $this->db_connect_id);
					}

					if ($result = @mysql_query("EXPLAIN $explain_query", $this->db_connect_id))
					{
						while ($row = mysql_fetch_assoc($result))
						{
							$html_table = $this->sql_report('add_select_row', $query, $html_table, $row);
						}
						mysql_free_result($result);
					}

					if ($html_table)
					{
						$this->html_hold .= '</table>';
					}

					if ($test_prof)
					{
						$html_table = false;

						// get the last profile
						if ($result = @mysql_query('SHOW PROFILE ALL;', $this->db_connect_id))
						{
							$this->html_hold .= '<br />';
							while ($row = mysql_fetch_assoc($result))
							{
								// make <unknown> HTML safe
								if (!empty($row['Source_function']))
								{
									$row['Source_function'] = str_replace(array('<', '>'), array('&lt;', '&gt;'), $row['Source_function']);
								}

								// remove unsupported features
								foreach ($row as $key => $val)
								{
									if ($val === null)
									{
										unset($row[$key]);
									}
								}
								$html_table = $this->sql_report('add_select_row', $query, $html_table, $row);
							}
							mysql_free_result($result);
						}

						if ($html_table)
						{
							$this->html_hold .= '</table>';
						}

						@mysql_query('SET profiling = 0;', $this->db_connect_id);
					}
				}

			break;

			case 'fromcache':
				$endtime = explode(' ', microtime());
				$endtime = $endtime[0] + $endtime[1];

				$result = @mysql_query($query, $this->db_connect_id);
				if ($result)
				{
					while ($void = mysql_fetch_assoc($result))
					{
						// Take the time spent on parsing rows into account
					}
					mysql_free_result($result);
				}

				$splittime = explode(' ', microtime());
				$splittime = $splittime[0] + $splittime[1];

				$this->sql_report('record_fromcache', $query, $endtime, $splittime);

			break;
		}
	}
}

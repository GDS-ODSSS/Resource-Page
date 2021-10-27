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

class dbal_mysqli
{

    var $db_connect_id;
    var $db_connect;
	var $query_result;
    var $num_queries    = array();
    var $sql_time       = 0;
    var $curtime        = 0;
    
    // mysqli real connect
    function __construct()
    {
        $this->num_queries = array(
			'cached'		=> 0,
			'normal'		=> 0,
			'total'			=> 0,
		);
        $this->sql_connect();
    }
    // sql connect
    function sql_connect()
	{
        $this->db_connect_id = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        mysqli_set_charset($this->db_connect_id,"utf8");
    }
    // sql query
    function sql_query($query = '', $cache_ttl = 0)
    {
        if (defined('SHOWDEBUG_LOAD_TIME'))
		{
			$this->curtime = microtime(true);
		}

        if ($query != '')
        {
            global $cache;
            $this->query_result = ($cache_ttl && method_exists($cache, 'sql_load')) ? $cache->sql_load($query) : false;
			$this->sql_add_num_queries($this->query_result);
            if ($this->query_result === false)
			{
                $this->query_result = mysqli_query($this->db_connect_id, $query);
            }
            if (defined('SHOWDEBUG_LOAD_TIME'))
			{
				$this->sql_time += microtime(true) - $this->curtime;
			}
        }
        return $this->query_result;
    }
    function sql_query_limit($query, $total, $offset = 0, $cache_ttl = 0)
	{
		if (empty($query))
		{
			return false;
		}
		$total = ($total < 0) ? 0 : $total;
		$offset = ($offset < 0) ? 0 : $offset;
        if ($total == 0)
		{
			$total = '18446744073709551615';
		}
		$query .= "\n LIMIT " . ((!empty($offset)) ? $offset . ', ' . $total : $total);
		return $this->sql_query($query, $cache_ttl);
	}
    // sql num rows
    function sql_numrows($query, $cache_ttl = 0)
	{
		$this->query_result = false;
		return @mysqli_num_rows($this->sql_query($query, $cache_ttl));
	}
    // sql affected rows
    function sql_affectedrows()
	{
		return ($this->db_connect_id) ? @mysqli_affected_rows($this->db_connect_id) : false;
	}
    // sql fetch assoc
    function sql_fetchrow($query_id = false, $fetcharray = false)
    {
    	global $cache;

        if($fetcharray == false)
        {
            return ($query_id !== false) ? mysqli_fetch_assoc($query_id) : false;
        }
		else
        {
            return ($query_id !== false) ? mysqli_fetch_array($query_id) : false;
        }
    	
    }
    // sql next id
    function sql_nextid()
	{
		return ($this->db_connect_id) ? @mysqli_insert_id($this->db_connect_id) : false;
	}
    // sql free result
    function sql_freeresult($result = false)
	{
        if(isset($result) && is_resource($result))
        {
            mysqli_free_result($result);
        }
	}
    // mysqli real escape string
    function sql_escape($msg)
	{
        return ($this->db_connect_id) ? mysqli_real_escape_string($this->db_connect_id, $msg) : @mysqli_real_escape_string($msg);
	}
    // sql add num queries
    function sql_add_num_queries($cached = false)
	{
		$this->num_queries['cached'] += ($cached !== false) ? 1 : 0;
		$this->num_queries['normal'] += ($cached !== false) ? 0 : 1;
		$this->num_queries['total']  += 1;
	}
    // num queries
    function sql_num_queries($cached = false)
	{
		return ($cached) ? $this->num_queries['cached'] : $this->num_queries['normal'];
	}
    // get sql time
    function get_sql_time()
    {
        return $this->sql_time;
    }
    // mysqli close
    function sql_close()
	{
		return mysqli_close($this->db_connect_id);
	}
    // sql build array
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
					$values[] = $this->sql_validate_value($var);
				}
			}

			$query = ($query == 'INSERT') ? ' (' . implode(', ', $fields) . ') VALUES (' . implode(', ', $values) . ')' : ' (' . implode(', ', $fields) . ') SELECT ' . implode(', ', $values) . ' ';
		}
		else if ($query == 'UPDATE' || $query == 'SELECT')
		{
			$values = array();
			foreach ($assoc_ary as $key => $var)
			{
				$values[] = "$key = " . $this->sql_validate_value($var);
			}
			$query = implode(($query == 'UPDATE') ? ', ' : ' AND ', $values);
		}
		return $query;
	}
    // sql validate value
    function sql_validate_value($var)
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
    //-- end functions mysqli
}
?>
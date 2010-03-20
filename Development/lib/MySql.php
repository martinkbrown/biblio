<?php

class MySql
{
	var $queries = array();
	
	var $result;
	
	var $time=0;
	
	var $link;

        var $connected = false;

        var $db_selected = false;
	
	function connect($hostname='',$username='',$password='',$new='')
	{
		return $this->connected = mysql_connect($hostname,$username,$password,$new);
	}
	
	function selectDb($name)
	{
		return $this->db_selected = mysql_selectdb($name);
	}
	
	function query($query)
	{
		array_push($this->queries,$query);
		
		$this->result = mysql_query($query);
		if(!$this->result) echo "<br/>".$query."<br/>".mysql_error()."<br/><br/>";
		if($this->result)
		{	
			$stats = mysql_stat();
			$this->time+=$stats[0];
			
			if($get && $this->count($this->result)==1)
			{
				$row = $this->fetch();
				return $row;
			}

			return $this->result;
		}
		
		return false;
	}
	
	function fetch($result='',$type=MYSQL_ASSOC)
	{
		if($result=='')	$result = $this->result;
		
		return mysql_fetch_array($result,$type);
	}
	
	function fetchRow($result='')
	{
		if($result=='')	$result = $this->result;
		
		return mysql_fetch_row($result);
	}
	
	function fetchField($result='')
	{
		if($result=='')	$result = $this->result;
		
		return mysql_fetch_field($result);
	}
	
	function fetchLength($result='',$offset=0)
	{
		if($result=='')	$result = $this->result;
		
		return mysql_field_len($result,$offset);
	}
	
	function setResult($result='')
	{
		$this->result=$result;
	}
	
	function count($result='')
	{
		if($result=='')	$result = $this->result;
		
		return mysql_num_rows($result);
	}
	
	function escape($string)
	{
		return mysql_real_escape_string($string);
	}
	
	function queries()
	{
		return $this->queries;
	}
	
	function error()
	{
		return mysql_error();
	}

        function errorNumber()
        {
            return msqyl_errno();
        }
	
	function close($link='')
	{
		if(!$link) $link = $this->link;
		return mysql_close($link);
	}
	
	function id()
	{
		return mysql_insert_id();
	}
	
	function insertedId()
	{
		return mysql_insert_id();
	}
	
	function debug()
	{
		foreach($this->queries as $query)
		{
			echo $query."<br/>";
		}
	}
	
	function getFieldNames($result='')
	{
		if(!$result) $result = $this->result;
		$field_names = array();
		
		for( $i=0; $i < mysql_num_fields($result); $i++) 
		{
			$field_names[$i] = mysql_field_name($result, $i);
		}
  		return $field_names;
	}

        function isConnected()
        {
            return $this->connected;
        }

        function dbIsSelected()
        {
            return $this->db_selected;
        }
}

function & sql()
{
	static $sql = NULL;
	
	if($sql===NULL)
	{
		$sql = new MySql();
	}
	
	return $sql;
}

$sql =& sql();


?>
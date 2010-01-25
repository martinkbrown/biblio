<?php

class recordset
{
	var $id;
	var $query;
	var $result;
	var $table;
	var $pk;
	var $values = array();
	var $extra_values = array();
	var $errors = array();
	var $messages = array();
	var $grid;
	
	function recordset($id=0,$table='',$load=true,$query = '')
	{
		$sql =& sql();
		
		if(!$table)	$table = TP.get_class($this);

		$this->table = $table;
		$this->setPk($table);
		$this->loadFields();
		$id = (integer)$id;
		
		if($load)
		{
			if(!($query))
			{
				$this->loadFromId($id);
			}
			else 
			{
				$this->loadFromQuery($query);
			}
		}
	}
	
	function loadFromId($id)
	{
		if(!$id)	return false;
		$sql =& sql();
		$query = "SELECT * FROM {$this->table} WHERE {$this->pk} = $id";
		$this->result = $sql->query($query);
		$this->load();
	}
	
	/**
	 * recordset::loadFromQuery()
	 * 
	 * Load information from the database into the local $values array
	 * from an SQL query
	 *
	 * @param string $query
	 */
	function loadFromQuery($query)
	{
		$sql =& sql();
		$this->result = $sql->query($query);
		$this->load();
	}
	
	/**
	 * recordset::loadFields()
	 * 
	 * Load the values that exist in this table without populating them
	 * 
	 *
	 */
	function loadFields()
	{
		$sql =& sql();
		$query = "SELECT * FROM ".$this->table;
		
		$result = $sql->query($query);
		
		foreach($sql->getFieldNames($result) as $field_name)
		{
			$field_name = $field_name;
			if($field_name != $this->pk)
			{
				$this->values[$field_name] = '';
			}
		}
		
	}
	
	/**
	 * Figure out what the primary key is
	 * and set it to the local $pk variable
	 *
	 * @param string $table		The name of the table
	 */
	function setPk($table)
	{
		/*$sql =& sql();
		$pk_query = "SELECT * FROM {$table}";
		$result = $sql->query($pk_query);
		while($meta = $sql->fetchField($result))
		{
			if($meta->primary_key == 1)
				$pk = $meta->name;
		}*/
		$this->pk = "id";
	}
	
	/**
	 * Wrapper function for recordset::next()
	 *
	 * @return unknown
	 */
	function load()
	{
		return $this->next();
	}
	
	/**
	 * recordset::next() uses the local result resource to get the next
	 * result returned from the query, if any
	 *
	 * @return unknown
	 */
	function next()
	{
		$sql =& sql();
		
		//if there isn't anything left to fetch then return false
		if(!($row = $sql->fetch($this->result))) return false;
		
		//for each field in the row
		foreach($row as $key=>$value)
		{
			$key = $key;
			//if the field is the primary key
			if($key == $this->pk)
			{
				//set the local id to the value of the primary key
				$this->id = $value;
				//skip the rest of this loop iteration
				continue;
			}
			//if the field belongs to this objects table
			if(array_key_exists($key,$this->values))
			{
				//set the value of the field
				$this->values[$key] = stripslashes($value);
				$this->$key = stripslashes($value);
			}
			//if the field belongs to another table
			else 
			{
				//set the value of the field in the local extra_values array
				$this->extra_values[$key] = stripslashes($value);
				$key = "_$key";
				$this->$key = stripslashes($value);
			}
		}
		
		return true;
	}
	
	/**
	 * recordset::getId() return the local id variable from this object
	 *
	 * @return unknown
	 */
	function getId()
	{
		return $this->id;
	}
	
	/**
	 * recordset::get_field() gets a value from this object's $values array.
	 * If no field is specified, then the whole array is returned.
	 * If $handle is true, then it checks to see if there are any method
	 * to manipulate each field, and applies that method to the values
	 * before returning anything.
	 *
	 * @param string $field			the name of the field to get
	 * @param bool $handle			whether to manipulate the values or not
	 * @return string|array
	 */
	function getValue($field='',$search_all = true,$handle=false)
	{
		$temp_values = $this->values;
		if($search_all)	$temp_values = array_merge($this->extra_values,$temp_values);
		//if no field is specified
		if(!$field)
		{
			//if the value(s) must be manipulated first
			if($handle)
			{
				//the values array to be returned if any of the values are manipulated successfully
				$values = array();
				//for every field
				foreach($temp_values as $key=>$value)
				{
					//set the name of the get method for this field
					$method = "get".strtoupper(substr($key,0,1)).strtolower(substr($key,1));
					//if there is a get method for this field
					if(method_exists($this,$method))
					{
						//call the method and set the values array
						$values[$key] = call_user_method($method,$this,$value);
					}
					//if there is no get method for this field
					else 
					{
						//set it to the default value
						$values[$key] = $value;
					}
				}
				//after the loop is over, return the manipulated values array
				return $values;
			}
			//if no values are to be manipulated first, then just return the object's field array
			return $temp_values;
		}
		//if a field has been specified
		else
		{
			//if the field value must be manipulated first
			if($handle)
			{
				//set the name of the field's get method
				$method = "get".strtoupper(substr($field,0,1)).strtolower(substr($field,1));
				// if the get method exists in this object
				if(method_exists($this,$method))
				{
					//apply the method and return the resulting value
					return call_user_method($method,$this,$this->values[$field]);
				}
			}
			//if the field doesn't have to be manipulated first
			//then just return the field value
			return $this->values[$field];
		}
	}
	
	function getExtra($field='')
	{
		if($field)
		{
			return $this->extra_values[$field];
		}
		return $this->extra_values;
	}
	
	/**
	 * recordset::setId() set the local id variable
	 *
	 * @param unknown_type $id
	 */
	function setId($id)
	{
		$this->id = $id;
	}
	
	/**
	 * recordset::set() sets a value in the local $values array
	 * It accepts either an associative array as the field value,
	 * or the name of the field as field value along with the value
	 * that it must be set to.
	 *
	 * @param string|array $field		The field(s) to be set
	 * @param mixed $value				The value of the field if $field is not an associative array
	 * @param bool $handle				Whether to maniuplate the field value(s) or not
	 */
	function setValue($field,$value='',$handle=false)
	{
		//if $field is not an array
		if(!is_array($field))
		{
			//make field an array
			$field = array($field=>$value);
		}
		
		//for every field
		foreach($field as $key=>$value)
		{
			//if the field exists in the local $values array then we can
			//go ahead and set the value. The value is not set if the
			//field doesn't exist, in order to prevent SQL errors
			if(array_key_exists($key,$this->values))
			{
				//set the name of the values set method
				$method = "set".strtoupper(substr($key,0,1)).strtolower(substr($key,1));
				$i = 0;
				while($i++ < strlen($method))
				{
					if($method[$i] == "_")
					{
						$method[$i+1] = strtoupper($method[$i+1]);
						
					}
				}
				$method = str_replace("_","",$method);
				//if the value must be maniputlated first and the method exists
				if($handle && method_exists($this,$method))
				{
					//call the set method, the user method should set the field itself
					//because it is not supposed to return a value
					call_user_method($method,$this,$value);
				}
				//if the value doesn't have to be manipulated first
				//then just set the value
				else $this->values[$key] = $value;
			}
		}
	}
	
	/**
	 * recordset::save is a wrapper function to recordset::insert()
	 * or recordset::update(). If a local id is not set, then
	 * it returns recordset::insert(). If a local id is set then it 
	 * calls recordset::update()
	 *
	 * @return int|bool
	 */
	function save()
	{
		//if an id is set
		if($this->id)	
		{
			return $this->update();
		}
		//if no id is set
		else 
		{
			return $this->insert();
		}
	}
	
	function setInfoFromForm($method = 'POST',$handle=true)
	{
		if(strtoupper($method) == 'GET')
		{
			if(!$_GET) return false;
			$input = get();
		}
		else 
		{
			if(!$_POST)	return false;
			$input = post();
		}
		foreach($input as $key=>$value)
		{
			$this->setValue($key,$value,$handle);
		}
		
		return true;
	}
	
	function saveFromForm($method = 'POST',$handle = true)
	{
		if($this->setInfoFromForm($method,$handle))
		{		
			return $this->save();
		}
		else return false;
	}
	
	/**
	 * recordset::insert() performs an SQL INSERT using the values
	 * in the local $values array
	 *
	 * @return int|result resource
	 */
	function insert()
	{
		if($this->errors) return false;
		
		$sql =& sql();
		
		$temp_values = $this->values;
		array_walk($temp_values,array($this,'quoteSmart'));
		$query = "	INSERT INTO {$this->table} 
					(".implode(",",array_keys($this->values)).") 
					VALUES (".implode(",",$temp_values).")";
		
		$result = $sql->query($query);
		$this->setId($sql->insertedId());
		if($result) return $sql->id();
		return false;
	}
	
	/**
	 * recordset::update() performs an SQL UPDATE using the values
	 * in the local $values array and the local id
	 *
	 * @return result resource
	 */
	function update()
	{
		if($this->errors) return false;
		
		$sql =& sql();
		$query = "	UPDATE {$this->table} SET ";
		foreach($this->values as $key=>$value)
		{
			$query.= "$key=".$this->quoteSmart($value).",";
		}
		$query = substr($query,0,-1);
		$query.= " WHERE {$this->pk} = {$this->id}";
		
		return $sql->query($query);
	}
	
	/**
	 * recordset::delete() performs an SQL DELETE the the id(s) passed
	 * If no id is passed, then the local id is used
	 *
	 * @param int|string|array $id
	 */
	function delete($id=0)
	{
		if($this->errors) return false;
		
		$sql =& sql();
		if(!$id)			$id = $this->id;
		if(!$id)			$id = 0;
		if(!is_array($id))	$id = array($id);
		
		$query = "DELETE FROM {$this->table} WHERE {$this->pk} IN (".implode(",",$id).")";
		
		return $sql->query($query);
	}
	
	/**
	 * recordset::exists() checks to see if there's an entry in
	 * the local table for the id passed. If no id is passed then
	 * the local id will be used
	 *
	 * @param int|string $id
	 * @return bool
	 */
	function exists($id=0)
	{
		$sql =& sql();
		if(!$id) $id = $this->id;
		$query = "SELECT 1 FROM {$this->table} WHERE {$this->pk} = '$id'";
		$sql->query($query);
		
		return (bool) $sql->count();
	}
	
	/**
	 * recordset::doesNotExist() returns the inverse of recordset::exists()
	 *
	 * @param int|string $id
	 * @return bool
	 */
	function doesNotExist($id=0)
	{
		return !$this->exists($id);
	}
	
	function quoteSmart(&$value)
	{
		$sql =& sql();
		
	   
	   // Quote if not a number or a numeric string
	   if (!is_numeric($value) || (strlen($value) > 1 && substr($value,0,1) == "0")) {
	       $value = "'" . $sql->escape($value) . "'";
	   }
	   return $value;
	}
	
	function listErrors()
	{
		foreach($this->errors as $error)
		{
			echo "<span style='color:red'>$error</span><br/>";
		}
	}
	
	function listMessages()
	{
		foreach($this->messages as $message)
		{
			echo "<span style='color:green'>$message</span><br/>";
		}
	}
}

?>
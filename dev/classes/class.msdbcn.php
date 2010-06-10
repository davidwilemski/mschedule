<?php
if ( !defined('CONFIG_INCLUDED') )
	{var_dump(debug_backtrace());exit("configuration.php missing");}

function myEscapeStrings(&$value, $key)
{
	$key = mysql_escape_string($key);
	$value = mysql_escape_string($value);
}
	
// Connection and interface to the Mschedule database 
class MSDbCn
{
	
	var $_dbcn; //the actual connection returned by
	var $_host;
	var $_port;
	var $_name;
	var $_tablePrefix;
	var $_username;
	var $_password;
	var $_lastQuery;
	
	
	function MSDbCn()
	{
		//print "yarr";
		global $cfg, $MSERROR;
		
		$this->_host = $cfg['db']['host'];
		$this->_port =  $cfg['db']['port'];
		$this->_name = $cfg['db']['dbName'];
		$this->_tablePrefix = $cfg['db']['tablePrefix'];
		$this->_username = $cfg['db']['username'];
		$this->_password = $cfg['db']['password'];
		//mjp: turned off error reporting here since we report it to MSERROR anyway
		@$this->_dbcn = mysql_connect($this->_host/*.':'.$this->_port*/, $this->_username, $this->_password);
		//print $this->_dbcn;
		if($this->_dbcn == false){
			$MSERROR->err("MSDbCn()", _ERR_DB_CN.$this->_host.mysql_error());
			//$MSERROR->emergencyFail();
		}
		//mjp: turned off error reporting here since we report it to MSERROR anyway
		if(@!mysql_select_db($this->_name, $this->_dbcn)){
			$MSERROR->err("MSDbCn()", _ERR_DB_CN.$this->_name.mysql_error());
		}
	}
	

	
	/*
	params:
		table - database table to insert row into
		array - associative array where keys correspond to column labels
	*/
	function insert($table, $array)
	{
		$keys = array_keys($array);
		array_walk($array, 'myEscapeStrings');
		$columns = "(`".implode("`, `", $keys)."`)";
		$values = "('".implode("', '", $array)."')";
		
		
		
		$this->sql("INSERT INTO `$table` $columns VALUES $values");
	}
	
	
	function sql($sql)
	{
		global $MSERROR;
		$this->_lastQuery = $sql;
		$result = mysql_query($sql, $this->_dbcn);
		if(!$result){
			$MSERROR->err("MSDbCn::sql()", _ERR_DB_QUERY.mysql_error()." SQL: ".$sql);
		}
		return $result;
	}
	
	function get_lastQuery()
	{
		return $this->_lastQuery;
	}
}

?>
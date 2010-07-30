<?php

function connectToDB()
{
    mysql_connect("localhost", "mschedule_user", "7ftNhFss5BUNFLcT") or die ("-1\nCan't connect to database.");
    mysql_select_db("mschedule_mi") or die ("-1\nCan't select database");
}

function execQuery($query)
{
	//print $query;
    $result = mysql_query($query) or die( databaseFailure($query) );
    return $result;
}

function databaseFailure($query)
{
    //mysql_query("UNLOCK TABLES");
    //mysql_close(); 
    return "-1\nQuery failed: " . mysql_error() . "\n" . $query;
}

function myEscapeStrings(&$value, $key)
{
	$key = mysql_escape_string($key);
	$value = mysql_escape_string($value);
}

/*
params:
	table - database table to insert row into
	array - associative array where keys correspond to column labels
*/
function insertDB($table, $array, $update = '')
{
	$keys = array_keys($array);
	array_walk($array, 'myEscapeStrings');
	$columns = "(`".implode("`, `", $keys)."`)";
	$values = "('".implode("', '", $array)."')";
	$query = "INSERT INTO `$table` $columns VALUES $values";
	if($update != ''){
		$query .= " ON DUPLICATE KEY UPDATE `$update`='$array[$update]'";
	}
	
	execQuery($query);
}

?>

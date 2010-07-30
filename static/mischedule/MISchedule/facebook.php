<?php

function myEscapeStrings(&$value, $key)
{
	$key = mysql_escape_string($key);
	$value = mysql_escape_string($value);
}

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
	mysql_query($query);
}

mysql_connect("localhost", "mschedul_misch0", "yey4Anew");
mysql_select_db("mschedul_misched");
$array = array(
'ip' => $_SERVER['REMOTE_ADDR'],
'host' => $_SERVER['REMOTE_HOST'],
'referer' => $_SERVER["HTTP_REFERER"],
'agent' => $_SERVER["HTTP_USER_AGENT"],


);
insertDB("facebook_traffic", $array);
header("Location: /");


include "index.php";


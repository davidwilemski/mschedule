<?php // db.php 
//include this file whenever you want nice functions and variables 
//to access the database
//print "db.php included";
//include_once 'inc/common.php';
include_once 'inc/dbvars.php';

//connects to the database if not already connected before in the current script
function dbconnect() { 
	global $dbhost, $dbuser, $dbpass, $dbname, $dbconnected;
	if($dbconnected){
		return;
	}
	//debug("connecting to database");
   $dbcnx = @mysql_pconnect($dbhost, $dbuser, $dbpass);

   if ($dbname!="" and !@mysql_select_db($dbname))
       throw new Exception("The site database is unavailable.");
   $dbconnected = true;
   return $dbcnx;
}

//issues and sql query and returs the result
//gives an error if the sql is not valid
function sql($sql){
	dbconnect();
	global $debug_mode;
	//debug("SQL: ".$sql);
	
	if(!($result = @mysql_query($sql))){
		if($debug_mode){
			throw new Exception("SQL:".$sql." | Error: ".mysql_error());
		}else{
			exit($sql);
			throw new Exception("There's been a database error.");
		}
	}
	//debug("SQL Result: ".$result);
	return $result;

}
?>

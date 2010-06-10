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
	debug("connecting to database");
   $dbcnx = @mysql_pconnect($dbhost, $dbuser, $dbpass)
       or error("The site database appears to be down.", 'fatal'); 

   if ($dbname!="" and !@mysql_select_db($dbname))
       error("The site database is unavailable.", 'fatal');
   $dbconnected = true;
   return $dbcnx;
}

//issues and sql query and returs the result
//gives an error if the sql is not valid
function sql($sql){
	dbConnect();
	global $debug_mode;
	debug("SQL: ".$sql);
	
	if(!($result = @mysql_query($sql))){
		if($debug_mode){
			error("SQL:".$sql." | Error: ".mysql_error());
		}else{
			error("There's been a database error.", 'sql');
		}
	}
	//debug("SQL Result: ".$result);
	return $result;

}
?>

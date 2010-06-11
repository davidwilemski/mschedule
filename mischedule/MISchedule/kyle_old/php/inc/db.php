<?php // db.php 

$dbhost = "localhost";
$dbuser = "mischedule";
$dbpass = "PasAWrd";
$dbname = "mischedule";

function dbConnect() { 
   global $dbhost, $dbuser, $dbpass, $dbname;
    
   $dbcnx = @mysql_connect($dbhost, $dbuser, $dbpass)
       or die("The site database appears to be down."); 

   if ($dbname!="" and !@mysql_select_db($dbname)) 
       die("The site database is unavailable."); 
    
   return $dbcnx; 
} 
?>


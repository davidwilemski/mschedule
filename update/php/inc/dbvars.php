<?
//only included in db.php as of August 04, 
//might or might not be of use elsewhere

//debug("Server name: {$_SERVER['SERVER_NAME']}");
switch($_SERVER["SERVER_NAME"]){
default:
	//debug("WARNING... USING DB VARS FOR REAL SITE");
		$dbhost = 'localhost';
		$dbuser = "mschedule";
		$dbpass = "jQquD6cezNW65xeC";
		if($mischedule) {
			$dbname = "mschedule_mi";
		}
		else {
			$dbname = "mschedule";
		}
break;
}

$dbconnected = false;

$user_class = 'uniqname_class_'.$term ;
$classes = 'classes_'.$term ;
$users = 'users';
$error_log = 'error_log';
$php_errors = 'phperror_log';
$invites = 'invites';
$prefs = 'preferences';
$access_log ='access_log';
$friends_table = 'friends';

?>

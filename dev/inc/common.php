<?php
//this will be included in every php file associated with mschedule.
session_start();
require_once "configuration.php";

//get the language definitions
$LANG_FILES = array(	"errorlang.php",	"userlang.php");

foreach($LANG_FILES as $file)
	require_once $cfg['ms_rootpath']['server']."/languages/".$cfg['language']."/".$file;

//the error and debug handlers
require_once $cfg['ms_rootpath']['server']."/classes/class.mserror.php";
require_once $cfg['ms_rootpath']['server']."/classes/class.msdebug.php";

$MSERROR = new MSError();
$MSDEBUG = new MSDebug();

//general includes
require_once $cfg['ms_rootpath']['server']."/classes/class.mscurrentuser.php";
require_once $cfg['ms_rootpath']['server']."/classes/class.msdbcn.php";
require_once $cfg['ms_rootpath']['server']."/classes/class.msauth.php";
require_once $cfg['ms_rootpath']['server']."/classes/class.mssmarty.php";
require_once $cfg['ms_rootpath']['server']."/classes/class.mssmartyprimary.php";
require_once $cfg['ms_rootpath']['server']."/classes/class.mspermissions.php";
require_once $cfg['ms_rootpath']['server']."/classes/class.msviewableobject.php";
require_once $cfg['ms_rootpath']['server']."/classes/class.msinputgrabber.php";
require_once $cfg['ms_rootpath']['server']."/classes/class.mshtmlform.php";
require_once $cfg['ms_rootpath']['server']."/classes/class.msemail.php";


//the database connection
//gives access to database through $MSDB which is a MSDbCn
$MSDB = new MSDbCn();

//set the currentUser according to whatever authentication system we are using
session_save_path();
switch($cfg['auth']['type']){

	//######### COSIGN LOGIN #############
	case 'cosign':
		$input = new MSInputGrabber();
		if ($input->postVar("msAction") == "login") {
			//if the user requested to log in to CoSign, we must redirect that person to the secured site for further login
			echo "<html><head><meta http-equiv=\"REFRESH\" content=\"0; URL=".$cfg['CoSignRedirect']."\"></head><body></body></html>";
			exit();
		}
		
		$currentUser = new MSCurrentUser($_SERVER['REMOTE_USER']);
		break;
	
	//######### MSAUTH LOGIN #############
	case 'msauth':
		$MSAUTH = new MSAuth(); //handles logins/logouts autonomously
		$currentUser = new MSCurrentUser(@$_SESSION['uniqname']);
		break;
		
	default: //by default, set $currentUser to a guest
		$currentUser = new MSCurrentUser(null);
		break;
}

//the permissions checker
$MSPERMS = new MSPermissions();

?>

<?php
// accesscontrol.php
//include this file to require that a user be logged in to the system
//this file will display an Access Denied message if they are not logged in

//load external fuctions
require_once 'inc/common.php';
require_once 'inc/db.php';

debug("Access control started");

$global_admin_password = '7cMSAbBL475DKcNy';

//open database connection
dbConnect();

$sid = session_id();
//debug("session id: ".$sid);
debug("session uniqname: |".$_SESSION['uniqname']."|");

if(!preg_match("/[a-z]{3,8}/", $_SESSION['uniqname'])){
	getdata(array('submit', 'uniqname', 'password'));
	
	//empty field check
	if($submit == 'login' and ($uniqname == '' or $password == '')){
		error('One or more fields are invalid. Please try again.', 'invalid_input');
	}
	
	//show login page
	if(!isset($uniqname)) {
		if($debug_mode){
			showhtmlpage("Debug Mode", '', '<META http-equiv=\"refresh\" content=\"3; URL=$login_page\">');
		}else{
			header("Location: $login_page");
			echo "You should not see this message...";
		}
		exit;
	}
	
	//skips database check if password entered is admin password
	if ($password != $global_admin_password){
		//check uniqname and password against database
		$result = sql('SELECT * ' 
		        . ' FROM users' 
		        . ' WHERE uniqname = \''
		        . $uniqname
		        . '\' AND ' 
		        . ' `password` = OLD_PASSWORD(\''
		        . mysql_real_escape_string($password)
		        . '\')'); 
		
		debug("Num rows in result: ".mysql_num_rows($result));
		if(mysql_num_rows($result) == 0) {
			//uniqname and password invalid, so unset session variables\
			 unset($_SESSION['uniqname']);
$body = <<<END
This may have occurred because
<ul>
<li>the uniqname entered is not registered (please <a href="$new_user_page">register</a>)
<li>the password entered was incorrect (try <a href="$login_page">logging in</a> again or <a href="passreset.php?uniqname=$uniqname">reseting your password</a>)
<li>your session expired (try <a href="$login_page">logging in</a> again)
</ul>
</body>
</html>
END;
			showHTMLPage("Access Denied", $body);
			exit;
		}else{
			//set variables from database in case page wants to use them
			$fullname = mysql_result($result,0,'fullname');
			
			session_unset();
			session_regenerate_id();
			//set session variables
			$_SESSION['uniqname'] = $uniqname;
			$_SESSION['fullname'] = $fullname;
			$_SESSION['ip'] = "{$_SERVER['REMOTE_ADDR']}";
		}
	

	}else{
		$_SESSION['uniqname'] = $uniqname;
		$_SESSION['ip'] = "{$_SERVER['REMOTE_ADDR']}";
	}
	sql("update users set last_login = NOW() where uniqname = '$uniqname'");
}else{
	debug("Skipping authentication, user already authenticated");
}



$auth_uniqname = $_SESSION['uniqname'];
$fullname = $_SESSION['fullname'];

//if the user just authenticated
if($_POST['uniqname'] == $_SESSION['uniqname']){
		$result = sql("select * from $user_class where uniqname = '$auth_uniqname'");
		if(mysql_num_rows($result) == 0){
			$where_to_start = 'importclasses.php';
		}else{
			$where_to_start = 'myschedule.php';
		}
	if($debug_mode){
		showhtmlpage("Debug Mode", '', "<META http-equiv=\"refresh\" content=\"3; URL=\"$where_to_start\">");
	}else{

		header("Location: $where_to_start");
		exit;
	}
	
}
debug("Access control done... loading rest of page");

?>

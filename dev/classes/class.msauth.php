<?php
if ( !defined('CONFIG_INCLUDED') )
	{var_dump(debug_backtrace());exit("configuration.php missing");}
	
require_once $cfg['ms_rootpath']['server']."/inc/db.php";
require_once $cfg['ms_rootpath']['server']."/classes/class.mscurrentuser.php";
require_once $cfg['ms_rootpath']['server']."/classes/class.msinputgrabber.php";


class MSAuth
{
	function MSAuth()
	{
		//check if a user is trying to log in or log out
		$input = new MSInputGrabber();
		
		switch($input->postVar("msAction")) {
		
			case "login":
				$user = $input->postVar("msUsername");
				$pass = $input->postVar("msPassword");
				$this->login($user,$pass);
				break;
				
			case "logout":
				$this->logout();
				break;
			
			default:
				//debugmjp: default (for logged in users)
				//			should be some kind of session security checking, like IP addy changes or something, and session timeouts
				//	note on this: REMOTE_ADDR may change on every request if the user comes through a proxy farm.
				//	note on cookies: http://www.sitepoint.com/article/p3p-cookies-ie6/2
				break;
		}
	}
		
	//returns true if login was a success
	//returns false on login failure
	//sets $currentUser variable if success
	function login($uniqname, $password)
	{
		global $cfg, $MSDB, $MSERROR, $MSDEBUG;
		
		//check if user is logged in already
		if ( $this->alreadyLoggedIn() ) {
			$MSERROR->err("MSAuth::login", _ERR_LOGIN_ALREADYIN);
			return false;
		}
		
		//check for empty fields
		if($uniqname == '' or $password == ''){
			$MSERROR->err("MSAuth::login", _ERR_LOGIN_EMPTY);
			return false;
		
		//admin override login
		}else if($cfg['useAdminPass'] and $password == $cfg['adminPass']){
			$MSDEBUG->add("MSAuth::login", "Administrator password entered");
			$result = $MSDB->sql("SELECT * FROM `{$cfg['db']['tables']['users']}` WHERE uniqname = '$uniqname'");
		
		//check for this user's password
		}else{
			$result = $MSDB->sql("SELECT * FROM `{$cfg['db']['tables']['users']}` WHERE uniqname = '$uniqname' AND PASSWORD = PASSWORD('$password')");
		}
	
		//login failed
		if(mysql_num_rows($result) == 0) {
			$MSERROR->err("MSAuth::login", _ERR_LOGIN_USER_NOT_EXIST);
			return false;
		
		//login successful, set session vars
		}else{
						
			//session_unset();
			//session_regenerate_id();
			
			$_SESSION['uniqname'] = $uniqname;
			$_SESSION['browser'] = $_SERVER['HTTP_USER_AGENT'];
			$_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
			return true;
		}
	}
	
	//determines if a user has already been logged in
	function alreadyLoggedIn()
	{
		if(@$_SESSION['uniqname'])
			return true;
		else
			return false;
	}
		
	function logout()
	{
		session_unset();
		session_destroy();
	}

	function create($username, $domain, $password)
	{
		global $cfg, $MSDB, $MSERROR;
		
		//check valid username and password
		//for now, only accept the default domain as valid until we decide how to take care of multiple
		if(strcasecmp($domain, $cfg['defaultDomain']) != 0){
			$MSERROR->err("MSAuth::create", _ERR_INVALID_DOMAIN);
			return false;
		}

		//check if user has already been created
		$result = $MSDB->sql("SELECT * FROM `{$cfg['db']['tables']['users']}` WHERE uniqname = '$username'");
		if(mysql_num_rows($result) != 0){
			$MSERROR->err("MSAuth::create", _ERR_CREATE_USER_EXISTS);
			return false;
		}
				
		$result = $MSDB->sql("INSERT INTO `{$cfg['db']['tables']['users']}` (uniqname, password, time) VALUES ('$username', PASSWORD('$password'), NOW())");
		return true;
	}
	
	//removes user only from user table
	function _delete($username)
	{
		global $MSDB, $cfg;
		$MSDB->sql("DELETE FROM `{$cfg['db']['tables']['users']}` WHERE uniqname = '$username'");
	}
	
	//sends an email to user with instructions on how to reset their password if they want
	function resetPassword($uniqname)
	{
		
	}
}
?>
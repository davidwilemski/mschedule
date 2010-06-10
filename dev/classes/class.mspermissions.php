<?php
if ( !defined('CONFIG_INCLUDED') )
	{var_dump(debug_backtrace());exit("configuration.php missing");}

/**
* Allows access to the permissions system on Mschedule.
*
* There are 2 types of permissions:
*		system (admin,registered user,guest)
*		user (VIP,friend)
*
*	NOTE: all user checks default to the currentUser
*
*/

class MSPermissions
{	
	//MEMBER FUNCTIONS
	
	//EFF: returns true if the user has read access
	//		if the objectname is invalid, then return false
	function read($objectname, $user=NULL)
	{
		//debugmjp: this is doing NOTHING right now
		return true;
		
		/*
		global $currentUser;
		
		if ($user == NULL)
			$user = &$currentUser;
		
		//reference the database table for SYSTEM permissions first
		//reference the database table for USER permissions second
		//if no matching object was found, return false
		
		//debugmjp: this is just a hacked together switch statement
		//			that will determine permissions until DB structure
		//			is set up
		switch($objectname) {
			
			case "system::coursecatalog":
				return true;//all users have access
				break;
								
			case "system::coursemaps":
				return true;//all users have access
				break;
				
			case "system::ratings":
				if ( $user->is_registered() ) return true;
				else return false; //only registered users
				break;
				
			case "system::inmyclasses":
				if ( $user->is_registered() ) return true;
				else return false; //only registered users
				break;
			
			case "system::searchmenu":
				return true;//all users have access
				break;
				
			default:
				return false;
				break;
		}
		*/
	}
	
	//EFF: returns true if the user has write access
	//		if the objectname is invalid, then return false
	function write($objectname, $user=NULL)
	{
		//debugmjp: this is doing NOTHING right now
		return true;
		
		/*
		global $currentUser;
		
		if ($user == NULL)
			$user = &$currentUser;
			
		//reference the database table for SYSTEM permissions first
		//reference the database table for USER permissions second
		//if no matching object was found, return false
		
		//debugmjp: this is just a hacked together switch statement
		//			that will determine permissions until DB structure
		//			is set up
		switch($objectname) {
		
			default:
				return false;
				break;
		}
		*/
	}
	
	//EFF: returns true if user has read access, but it is temporarily disabled
	function temporarilyUnreadable()
	{
		//debugmjp:finish this
	}
	
	//EFF: returns true if user has write access, but it is temporarily disabled
	function temporarilyUnwriteable()
	{
		//debugmjp:finish this
	}
}


?>

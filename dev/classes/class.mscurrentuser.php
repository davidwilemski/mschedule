<?php
if ( !defined('CONFIG_INCLUDED') )
	{var_dump(debug_backtrace());exit("configuration.php missing");}

require_once $cfg['ms_rootpath']['server']."/classes/class.msuser.php";

class MSCurrentUser extends MSUser
{
	function MSCurrentUser($uniqname = '')
	{
		parent::MSUser($uniqname);
	}

	function is_loggedIn()
	{
		if(isset($this->uniqname) and trim($this->uniqname) != ''){
			return true;
		}else{
			return false;
		}
	}
}

?>
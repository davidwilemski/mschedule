<?php
if ( !defined('CONFIG_INCLUDED') )
	{var_dump(debug_backtrace());exit("configuration.php missing");}

/**
* Provides a rudimentary way of obtaining data from an LDAP directory.
*
* Development of this class is being put on hold because we don't have a server which supports ldap php functions
* Please use MSUniqnameInfo for full name retrieval
*/


class MSLDAP
{
	var $_domain;	
	var $_ldapcn;	//LDAP resource link
	
	function MSLDAP($domain = '')
	{
		global $cfg;
		if($domain == ''){
			$this->_domain = $cfg['defaultDomain'];
		}else{
			$this->_domain = $domain;
		}
		//print $this->_domain;
		
		//$this->_ldapcn = ldap_connect($cfg['ldap']['host'], $cfg['ldap']['port']);
	}
	
	/**
	* REQ: a username, and optionally a domain
	* EFF: returns a string that *should* contain the user's name
	*
	* note- this is a slow command when executed outside the
	*		LDAP domain, so it should be executed once and stored
	*		in the local database
	*
	* debug- this does not work for uniqnames with multiple matches.  Example:
				finger mulka@umich.edu will return 5 uniqnames (mulkaj, krmulka, etc.)
				and therefore this function will return null
				Possible solution: use the PHP LDAP functions
	*/
	function get_fullname($username, $domain = '')
	{
		if($domain == ''){
			$domain = $this->_domain;
		}
		$exec = "finger " . $username . "@" . $domain . " | grep Also -B 0 -1";
		//print $exec;
		return system($exec);
	}
}

?>
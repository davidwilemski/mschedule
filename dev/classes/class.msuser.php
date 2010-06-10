<?php
if ( !defined('CONFIG_INCLUDED') )
	{var_dump(debug_backtrace());exit("configuration.php missing");}

require_once $cfg['ms_rootpath']['server']."/classes/class.msschedule.php";
require_once $cfg['ms_rootpath']['server']."/classes/class.msuniqnameinfo.php";
require_once $cfg['ms_rootpath']['server']."/inc/db.php";

class MSUser
{
	var $uniqname;
	var $_fullName;
	var $_schedule;
	var $_VIPList;

	function MSUser($uniqname)
	{
		$this->uniqname = $uniqname;
	}

	function is_VIP($uniqname)
	{
		//note from matt: we should probably have 2 functions in this area
		//					is_VIP($uniqname) will tell us if THIS user is a VIP of the $uniqname
		//					has_VIP($uniqname) will tell us if $uniqname is on THIS user's VIP list
		//	sound good?

		//fix
		return false;
	}
	
	function list_VIPs()
	{
		return $this->get_VIPList();
	}
	
	function get_VIPList()
	{
		if(!isset($this->_VIPList)){
			global $MSDB, $cfg;
			$result = $MSDB->sql("SELECT vip_uniqname FROM `{$cfg['db']['tables']['vips']}` WHERE `uniqname` = '{$this->uniqname}'");
			//print $MSDB->get_lastQuery();
			$rv = array();
			while($row = mysql_fetch_assoc($result)){
				array_push($rv, $row['vip_uniqname']);
			}
			$this->_VIPList = $rv;
		}
		return $this->_VIPList;
	}
	
	function get_schedule()
	{
		if(!isset($this->_schedule)){
			$this->_schedule = new MSSchedule($this->uniqname);
		}
		return $this->_schedule;
	}
	
	function get_fullName()
	{
		if(!isset($this->_fullName)){
			$i = new MSUniqnameInfo();
			$this->_fullName = $i->get_fullName($this->uniqname);
		}
		return $this->_fullName;
	}
	
	function get_uniqname()
	{
		return $this->uniqname;
	}
	
	function is_guest()
	{
		if(!isset($this->uniqname) or $this->uniqname == ''){
			return true;
		}else{
			return false;
		}
	}
	
	function is_admin()
	{
		global $cfg;
		if(in_array($this->uniqname, $cfg['adminUsers'])){
			return true;
		}else{
			return false;
		}
	}
	
	function is_registered()
	{
		return !$this->is_guest();
	}
}
?>
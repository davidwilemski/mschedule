<?php
if ( !defined('CONFIG_INCLUDED') )
	{var_dump(debug_backtrace());exit("configuration.php missing");}

require_once $cfg['ms_rootpath']['server']."/inc/smartylibs/Smarty.class.php";

/**
* Sets some neccessary conditions for Smarty objects in MSchedule
*/

class MSSmarty extends Smarty
{	
	function MSSmarty()
	{
		$this->Smarty();
		
		global $cfg;
		
		//set directories		
		$this->template_dir = $cfg['smarty']['template_dir'];
		$this->compile_dir = $cfg['smarty']['compile_dir'];
		$this->cache_dir = $cfg['smarty']['cache_dir'];
		$this->config_dir = $cfg['smarty']['config_dir'];
		
		//set delimiters
		$this->left_delimiter = "{{";
		$this->right_delimiter = "}}";
		
		//set ability to have yes/no/true/false/on/off boolean values
		$this->config_booleanize = true;
		
		//set caching info (debugmjp: set other stuff here)
		$this->caching = true;
		$this->force_compile = $cfg['smarty']['force_compile'];
	}
	
	//Override the fetch() and display() functions to make sure that everything
	//is made available to the template
	
	function fetch($template, $cache_id = NULL, $compile_id = NULL)
	{
		global $MSERROR, $MSDEBUG;
		global $currentUser;
		global $cfg;
		
		$this->assign("MSERROR",$MSERROR);
		$this->assign("MSDEBUG",$MSDEBUG);
		$this->assign("currentUser",$currentUser);
		$this->assign("cfg",$cfg);
		return parent::fetch($template, $cache_id, $compile_id);
	}
	
	function display($template, $cache_id = NULL, $compile_id = NULL)
	{
		//debugmjp: figure out why can't override the display function (give a blank page)
		echo $this->fetch($template, $cache_id, $compile_id);
	}
	
	function emergencyfail()
	{
		//global $cfg; //should this be here? -Kyle
		$cfg['showErrors'] = true;
		$this->display("mainlayout.shtml");
		exit();
	}
	
}

?>
<?php
if ( !defined('CONFIG_INCLUDED') )
	{var_dump(debug_backtrace());exit("configuration.php missing");}

/**
* Provides an interface for storing debugging statments during
* script execution.
*
* USAGE:
*		when testing a particular function/variable
*		call:
*		
*		$MSDEBUG->add("name_of_test","outcome of test");
*/

class MSDebug
{
	//MEMBER DATA
	var $messages; 	//an array with structure: messages[#]['origin']  and messages[#]['msg']
	var $num;		//holds the # of debug statements
	
	function MSDebug()
	{
		$this->messages = array();
		$this->num = 0;
	}
	
	//MEMBER FUNCTIONS
	
	/**
	* REQ: an originating function/variable, and a debug description
	*/
	function add($origin, $msg)
	{
		$this->messages[$this->num]['origin'] = $origin;
		$this->messages[$this->num]['msg'] = preg_replace("/\n/","<br>\n",$msg);
		
		$this->num++;
	}
}
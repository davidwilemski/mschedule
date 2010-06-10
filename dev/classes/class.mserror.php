<?php
if ( !defined('CONFIG_INCLUDED') )
	{var_dump(debug_backtrace());exit("configuration.php missing");}

/**
* Provides an interface for storing errors incurred during
* script execution.
*
* USAGE:
*		when an error occurs in a function my_function()
*		call:
*		
*		$MSERROR->err("my_function","Description of my error");
*/

class MSError
{
	//MEMBER DATA
	var $messages; 	//an array with structure: messages[#]['origin']  and messages[#]['msg']
	var $num;		//holds the # of errors incurred
	
	function MSError()
	{
		$this->messages = array();
		$this->num = 0;
	}
	
	//MEMBER FUNCTIONS
	
	/**
	* REQ: an originating function, and an error description
	*/
	function err($origin, $msg = "error occurred")
	{
		$this->messages[$this->num]['origin'] = $origin;
		$this->messages[$this->num]['msg'] = $msg;
		
		$this->num++;
	}
}
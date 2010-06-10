<?php
if ( !defined('CONFIG_INCLUDED') )
	{var_dump(debug_backtrace());exit("configuration.php missing");}

/**
 * This class handles input that comes from either a POST operation or
 * from a URL query.
 */

class MSInputGrabber
{
	var $_urlinput;
	var $_secure;

	//constructor (private)
	function MSInputGrabber()
	{
		$this->getInputFromURL();
		
		//debugmjp: disabled checking for now
		//$this->check_POSTsecurity();
	}
	
	//tells whether or not there was any input to look at
	function exists_input()
	{
		if ($this->_urlinput || $_POST)
			return true;
		else
			return false;
	}
	
	//tells whether or not this form was secured
	function is_secure()
	{
		return $this->_secure;
	}
	
	//return the requested POST variable
	function postVar($name)
	{
		if (@$_POST[$name])
			return $_POST[$name];
		else
			return false;
	}

	//return the requested URL variable
	function urlVar($name)
	{
		return $this->_urlinput[$name];
	}

	//return the requested variable, from either POST or URL (POST value is given higher priority than the URL value)
	function inputVar($name)
	{
		if($this->postVar($name))
			return $this->postVar($name);
		else
			return $this->urlVar($name);
	}
	
	//get input from URL (private)
	function getInputFromURL()
	{
		//put URL vars into the array
		parse_str($_SERVER['QUERY_STRING'],$urlvars);

		foreach ( $urlvars as $key => $value )
			$this->_urlinput[$key] = $value;
	}
	
	//get an array of URL vars
	function arrayURL()
	{
		return $this->_urlinput;
	}
	
	//get an array of the POST vars
	function arrayPOST()
	{
		return $_POST;
	}
	
	/*debugmjp: implement this sometime
	//checks to see if the POST data came from a form that the currentUser submitted
	//all secure forms made in MSchedule have an authkey value that matches a key in their session
	function check_POSTsecurity()
	{			
		if ($this->postVar("authkey")==NULL)
			$this->_secure = false;
		else {

			$session = new MSSessionHandler();
		
			$seed = $session->get_randID();
			
			$authkey = md5($seed);
						
			//check to see if the POSTed authkey is correct
			if ($authkey == $this->postVar("authkey")) {
				
				$this->_secure = true;
				
				//we also have to tell the user to get a new random ID
				$session->generate_randID();

			} else
				$this->_secure = false;
		}
	}
	*/
}
?>
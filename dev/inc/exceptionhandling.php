<?php

/*
##################################################################
----------------------------------	
FORMAL USAGE:

	Whenever an error occurrs, simply call...
	
			trigger_error(YOUR_ERROR_CODE);
		
	...where YOUR_ERROR_CODE is some predefined string/integer
	that has some meaning for you.
	
	To catch an error, you must first call try() for each catch() you
	plan on doing...
	
			try();
			try();
			try();
	
	...or, as shorthand...
			
			try(3);
	
	Once you have a corresponding try(), you can execute a catch()...			
	
			if(catch(YOUR_ERROR_CODE))
		
	...or...
		
			if($details = catch(YOUR_ERROR_CODE))
	
	...where...
			
			$details['errCode'] is the error code
			$details['file'] is the filename that the error occurred in
			$details['line'] is the line # of that error
			$details['phperrCode'] is the PHP error type (usually not needed)
	
	
	You can dump all current exceptions in the buffer to an HTML table
	with the command...
			
			dumpAllExceptions();
	
	By default, dumpAllExpceptions() is called when the script
	exits.  To disable this, use the command...
	
			silenceExceptionHandling()
	
	..which allows you to stop reporting error messages for users.

----------------------------------
EXAMPLE:

	A try/catch block that catches 3 error looks like this...
	
		try(3); {
		
			//do stuff
			trigger_error(DB_ERR);
			trigger_error(INPUT_ERR);			
			trigger_error(USER_ERR);
		}
		
		if(catch(DB_ERR)) {
			
			//do stuff
		}
		
		if(catch(INPUT_ERR)) {
			
			//do stuff
		}
		
		if(catch(USER_ERR)) {
			
			//do stuff
		}


##################################################################
*/

define('MS_E_NOTICE', "<b>MSchedule Error</b>");


//##### DEFINE THE ERR STACK #####

class MSErrorStack
{
	var $errors;		//queue of errors
	var $tries;		//stack of tries
	
	function MSErrorStack() {$this->errors = array(); $this->tries = array();}
	
	//EFF: pushes a new error onto the error queue
	function pushErr($errCode,$file,$line,$phperrCode) {return array_push($this->errors, array('errCode'=>$errCode,'file'=>$file,'line'=>$line,'phperrCode'=>$phperrCode));}
	
	//EFF: pops the oldest error off the error queue
	function popErr() {return array_shift($this->errors);}
	
	//EFF: pushes a new try onto the try stack
	function pushTry() {	return array_push($this->tries, $this->numErrors());}
	
	//EFF: pops the newest try off the try stack
	function popTry() {return array_pop($this->tries);}
	
	//EFF: returns the # of errors in the error stack
	function numErrors(){return count($this->errors);}
	
	//EFF: returns the # of tries in the try stack
	function numTries(){return count($this->tries);}
	
	//EFF: determines if an error exists in the stack, then pops it out if it is found
	//		Otherwise, returns false on no find.
	function findErrorInStack($errCode)
	{
		//check if there are any tries in the try stack
		if ($this->numTries() <= 0) {
			
			//get the originator of the error
			$trace = debug_backtrace();
			$trace = $trace[1];
			
			//put this error on the stack
			$this->pushErr("Can't catch() without a try()",$trace['file'],$trace['line'],E_NOTICE);
			return;
		}
		
		//get leftmost possible error since the last try
		$leastIndex = $this->popTry();
		$maxIndex = $this->numErrors() - 1;
		
		for ($i = $maxIndex; $i >= $leastIndex; $i--) {
			
			$error = $this->errors[$i];
			
			//if we found the error, splice it out of the array and return it
			if ($error['errCode'] == $errCode) {
				
				array_splice($this->errors, $i, 1);
				return $error;
			}
		}
		
		return false;
	}
	
	//EFF: dumps all current errors in the stack to an HTML table
	function quickdump()
	{		
		//begin the table
		echo "<br><center style=\"background: #999; border: 2px solid #555; font-size: 15px; font-weight: bold; color: #555;\">";
		echo "ERROR REPORT";
		echo "<table style=\"font-size: 11px; font-family: sans-serif;\">";
	
		//pop errors until there are none left
		while ($error = $this->popErr()) {
		
			//figure out what kind of error this is
			switch($error['phperrCode']) {
				
				case E_ERROR:	$type = "Fatal Error"; break;
				case E_WARNING:	$type = "Warning"; break;
				case E_NOTICE:
				case E_PARSE:	$type = "Notice"; break;
				default:			$type = MS_E_NOTICE; break;
			}
			?>
				<tr>
					<td style="border: 1px solid black; padding: 3px; background: #ccc;">
						<?php echo $type;?>
					</td>
					<td style="border: 1px solid black; padding: 3px; background: #eee;">
						<b><?php echo $error['errCode'];?></b>
					</td>
					<td style="border: 1px solid black; padding: 3px; background: #fff;">			
						<i>
							<?php
							//parse out the root path, for security
							echo str_replace($_SERVER['DOCUMENT_ROOT'],"", $error['file']);
							?>
						</i>
					</td>
					<td style="border: 1px solid black; padding: 3px; background: #eee;">	
						line <?php echo $error['line'];?>
					</td>
				</tr>
			<?php
		}
		
		//end the table
		echo "</table><br></center>";
	}
}


//##### DEFINE THE ERR HANDLER #####

//EFF: allows errors to be pushed onto an Error Stack, to be caught by catch() later
//		(or dumped to the screen)
function
my_error_handler($phperrCode,$errCode,$file,$line,$vars)
{
	if (!@is_object($GLOBALS['MSERRORSTACK']))
		$GLOBALS['MSERRORSTACK'] = new MSErrorStack();
	
	$GLOBALS['MSERRORSTACK']->pushErr($errCode,$file,$line,$phperrCode);
}

//EFF: dumps all exceptions before exiting the script, unless
//		$GLOBALS['SILENCE_EXCEPTIONDUMP'] is set to true
function
my_shutdown_function()
{
	if (!@is_object($GLOBALS['MSERRORSTACK']))
		return;
	
	//trigger errors if the # of tries is not zero (all tries should have a catch)
	if ($GLOBALS['MSERRORSTACK']->numTries() > 0)
		trigger_error("You executed try() ".$GLOBALS['MSERRORSTACK']->numTries()." times  without catch()");
		
	if(array_key_exists('SILENCE_EXCEPTIONDUMP',$GLOBALS) && $GLOBALS['SILENCE_EXCEPTIONDUMP']==true)
		return;
	else
		dumpAllExceptions();
}


//set these functions as the default handlers for errors and script exit
set_error_handler("my_error_handler");
register_shutdown_function("my_shutdown_function");


//##### DEFINE CATCH #####

//EFF: pushes a new try onto the stack
function
try($numTries = 1)
{
	if (!@is_object($GLOBALS['MSERRORSTACK']))
		$GLOBALS['MSERRORSTACK'] = new MSErrorStack();
	
	while($numTries) {
		$GLOBALS['MSERRORSTACK']->pushTry();
		$numTries--;
	}
}

//REQ: a valid error code (numerical)
//EFF: returns false if that error code was not in the stack
//		otherwise, it returns an error array with 3 fields:
//		errCode, file, line, and phperrCode
function
catch($errCode)
{
	if (!@is_object($GLOBALS['MSERRORSTACK']))
		$GLOBALS['MSERRORSTACK'] = new MSErrorStack();
		
	return $GLOBALS['MSERRORSTACK']->findErrorInStack($errCode);
}

//EFF: prints a table of all remaining errors in the error stack,
//		emptying the stack at the same time
function
dumpAllExceptions()
{
	if(is_object($GLOBALS['MSERRORSTACK']))
		$GLOBALS['MSERRORSTACK']->quickdump();
}

//EFF: sets a global variable that tells the exit function not to
//		print all the errors
function
silenceExceptionHandling()
{
	$GLOBALS['SILENCE_EXCEPTIONDUMP'] = true;
}

?>
<?php

define(DEBUG, false);

class LynxCmdScriptGenerator
{
	var $output = '';	//keeps track of the output that should go to the command file
	var $lastURL;
	var $fp;
	var $messages = array();
	//var $count = 0; // keeps track of how many lines have been added 
	
	//constructor
	function LynxCmdScriptGenerator($filename = '')
	{
		$this->fp = fopen($filename, "wb");
		if(!$this-fp){
			print "file opening failed: $filename";
		}
	}
	
	//MANGEMENT FUNCTIONS
		
	//saves currently running script to a file
	function saveScriptToFile($filename)
	{
		if(!isset($fp)){
			$fp = fopen($filename, "wb");
			fwrite($fp, $this->output);
			fclose($fp);
		}
	}
	
	function msg($msg){
		array_push($this->messages, $msg);
	}
	
	function printScript()
	{
		if(DEBUG){
			foreach($this->messages as $msg){
				print "#$msg\n";
			}
		}
		//header('Content-type: text/plain');
		if(isset($this->fp)){
			fclose($this->fp);
		}else{
			print $this->output;
		}
		
	}

	//adds line to script
	function addLine($line)
	{
		if(isset($this->fp)){
			fwrite($this->fp,$line."\n");
		}else{
			$this->output .= $line."\n";
		}
	}
	

	//LYNX ACTIONS
	
	function typeString($string)
	{
		$this->msg("$string");
		$strLength = strlen($string);
		for($i = 0; $i < $strLength; $i++){
			$this->addKey(substr($string, $i, 1));
		}
	}
	
	function goURL($URL)
	{
		$this->msg("URL:");
		$this->addKey("g");
		$this->typeString($URL);
		$this->pressEnter();
		$this->lastURL = $URL;
	}
	
	function savePageToFile($filename)
	{
		$this->msg("Save Page To: $filename");
		$this->addKey("p");
		$this->pressEnter();
		$this->clearLine();
		$this->typeString($filename);
		$this->pressEnter();
	}
	
	function addKey($character)
	{
		$this->addLine("key ".$character);
	}
	
	function clearLine()
	{
		$this->msg("Clear Line");
		$this->addKey("^u");
	}
	
	/* don't really need (use clearLine())
	function pressBackspace($count = 1)
	{
		for($i = 0; $i < $count; $i++){
			$this->addKey("<delete>");
		}
	}
	*/
	
	function pressEnter()
	{
		$this->msg("Return key");
		$this->addKey("^J");	
	}

	function quit()
	{
		$this->msg("Quit");
		$this->addKey("q");
		$this->addKey("y");
	}
}

?>
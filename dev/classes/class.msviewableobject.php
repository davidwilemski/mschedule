<?php
if ( !defined('CONFIG_INCLUDED') )
	{var_dump(debug_backtrace());exit("configuration.php missing");}

/**
* Container class for Smarty display elements:
*		
*		- links
*		- menu items
*		- alerts
*
* Specifically, this determines which elements are viewable by the
* current user, according to permissions.
*/

class MSViewableObject
{	
	//MEMBER DATA
	var $objectname;		//the name of this viewableobject (i.e. "system::coursecatalog" or "mulka::schedule")
	var $read;
	var $write;
	
	function MSViewableObject($objectname)
	{
		$this->objectname = $objectname;
	}
	
	//MEMBER FUNCTIONS
	
	//EFF: returns the object name
	function voName()
	{
		return $this->objectname;
	}
	
	//EFF: returns whether or not this object is readable by current user
	function readable()
	{
		global $MSPERMS;
		
		if ( !isset($read) )
			$this->read = $MSPERMS->read($this->objectname);
			
		return $this->read;
	}
	
	//EFF: returns whether or not this object is writeable by current user
	function writeable()
	{
		global $MSPERMS;
		
		if ( !isset($read) )
			$this->write = $MSPERMS->write($this->objectname);
			
		return $this->write;
	}
}

class MSVOLink extends MSViewableObject
{
	//MEMBER DATA
	var $label;
	var $target;
	var $class;
	
	function MSVOLink($label,$target,$objectname)
	{
		$this->MSViewableObject($objectname);
		
		$this->label = $label;
		$this->target = $target;
	}
}

class MSVOMenu extends MSViewableObject
{
	//MEMBER DATA
	var $title;
	var $linksArray;
	
	function MSVOMenu($title,$linksArray,$objectname)
	{
		$this->MSViewableObject($objectname);
		
		$this->title = $title;
		$this->linksArray = $linksArray;
	}
}

class MSVOSchedule extends MSViewableObject
{
}

?>

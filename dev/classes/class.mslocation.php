<?
if ( !defined('CONFIG_INCLUDED') )
	{var_dump(debug_backtrace());exit("configuration.php missing");}

class MSLocation
{
	var $name;		//string (name of location)
	var $map;		//string	(NORTH, SOUTH, or CENTRAL)
	var $x;		//int		(x-coord of the course on the given map)
	var $y;		//int		(y-coord of the course on the given map)
	
	function MSLocation($map, $x, $y, $name)
	{
		$this->map = $map;
		$this->x = $x;
		$this->y = $y;
		$this->name = $name;
	}
}

?>
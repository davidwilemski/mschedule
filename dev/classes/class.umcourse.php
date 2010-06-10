<?php
/*//////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////
	PLEASE USE 4-SPACE TABS WHEN VIEWING THIS FILE
	
	UMCourse
		This class is intended to organize the coursedata drawn from
		the registrar's website into a single object that can be
		referenced easily for a variety of display purposes

////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////*/

class UMCourse
{
	//MEMBERS CONTAINING REQUIRED, FUNCTIONAL DATA
	var $courseID;		//int
	var $directenroll;	//bool		(whether can directly enroll in this course)
	var $autoenrollID;	//int		(courseID of the course that you will be autoenrolled in when you enroll for this course)
	var $time_start;		//int		(range 1-33, since there are 33 half-hours between 8am and 11pm
	var $time_duration;	//int		(the number of half-hours that this this course lasts)
	var $time_days;		//intArray	(array containing the [numerically encoded] days that this course occurs, as in MON=1, TUES=2, WED=3...)
	var $map;			//string		(NORTH, SOUTH, or CENTRAL)
	var $map_x;			//int		(x-coord of the course on the given map)
	var $map_y;			//int		(y-coord of the course on the given map)
	
	//MEMBERS CONTAINING NON-REQUIRED, INFORMATIONAL DATA
	var $coursedept;		//string		(department, as in EECS, HIST, PHIL...)
	var $coursenum;		//int		(course number, as in 101, 203...)
	var $coursesection;	//int		(section number, as in 011, 002...)
	var $coursetimes;	//string		(the actual time range of this course, as in "3:00pm-4:30pm"...)
	var $coursedays;		//string		(the actual day range of this course, as in "MWF", "TUTH"...)
	var $professor;		//string		(the name of the professor who is teaching this course
	var $location;		//string		(the room# and building where this course is located, as in "1600 GGB" or "2130 EH"...)
	
	//MEMBERS CONTAINING DEBUG DATA
	var $errormessages;	//stringArray	(an array of strings containing error messages)
	
	
	//CONTRUCTOR
	function UMCourse($id)
	{
		//create this object based on the course ID sent as an argument, fail if no ID was sent
		if ( isset($id) ) {
			$this->courseID = $id;
			return true;
		} else {
			$this->errorAdd("constructor :: No course ID given in declaration of new UMCourse object.");
			return false;
		}
	}
	
	//METHODS
	
	//METHODS FOR DEBUG
	function errorAdd($msg)
	{
		////////////////////////////////////////////////////////////////////////////////////////////
		//	Add an error message to the list of errormessages
		////////////////////////////////////////////////////////////////////////////////////////////
		
		$this->errormessages[] = $msg;
		
		return;
	}
	
	
	function errorPrint()
	{
		////////////////////////////////////////////////////////////////////////////////////////////
		//	Prints out the errors that have been generated during the lifespan of this object
		//	into a clean HTML box for easy viewing.
		////////////////////////////////////////////////////////////////////////////////////////////
		
		//print the error messages only if there are any at all
		if ( isset($this->errormessages) ) {
			echo "<div style=\"font-family: sans-serif; font-size: 10px; color: black; border: 2px solid #87000A; background: #FF716E; padding: 5px;\">";
					
			//print each error
			foreach ($this->errormessages as $key=>$message) {
				echo "<b>UMCourse, ERROR(" . ($key+1) . ")</b><br>$message<br><br>";
			}
			
			echo "</div>";
		}
		
		return;	
	}
	
	function fillWithDummyValues($argArray)
	{
		////////////////////////////////////////////////////////////////////////////////////////////
		//	Fill this object with programmer-specified dummy values for testing
		//	in the absence of a data source (like SQL)
		////////////////////////////////////////////////////////////////////////////////////////////
		
		$this->directenroll = $argArray[0];		//bool		(whether can directly enroll in this course)
		$this->autoenrollID = next($argArray);	//int		(courseID of the course that you will be autoenrolled in when you enroll for this course)
		$this->time_start = next($argArray);		//int		(range 1-33, since there are 33 half-hours between 8am and 11pm
		$this->time_duration = next($argArray);	//int		(the number of half-hours that this this course lasts)
		$this->time_days = next($argArray);		//intArray	(array containing the [numerically encoded] days that this course occurs, as in MON=1, TUES=2, WED=3...)
		$this->map = next($argArray);			//string		(NORTH, SOUTH, or CENTRAL)
		$this->map_x = next($argArray);			//int		(x-coord of the course on the given map)
		$this->map_y = next($argArray);			//int		(y-coord of the course on the given map)
	
		$this->coursedept = next($argArray);		//string		(department, as in EECS, HIST, PHIL...)
		$this->coursenum = next($argArray);		//int		(course number, as in 101, 203...)
		$this->coursesection = next($argArray);	//int		(section number, as in 011, 002...)
		$this->coursetimes = next($argArray);	//string		(the actual time range of this course, as in "3:00pm-4:30pm"...)
		$this->coursedays = next($argArray);		//string		(the actual day range of this course, as in "MWF", "TUTH"...)
		$this->professor = next($argArray);		//string		(the name of the professor who is teaching this course
		$this->location = next($argArray);		//string		(the room# and building where this course is located, as in "1600 GGB" or "2130 EH"...)
		
		return;
	}
	
}

?>
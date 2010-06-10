<?php

 // in some instances, a section can have more than one location
class SectionLocation
{
    var $classNum;
    var $timeString;
    var $location;
 
    function SectionLocation()
    {
    }

    //initialize the location from a database row
    function setDataFromDBRow($row)
    {
        $this->classNum = $row['classNum'];
        $this->timeString = $row['timeString'];
        $this->location = $row['location'];
    }

}

?>
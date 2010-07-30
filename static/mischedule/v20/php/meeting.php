<?php

 // in some instances, a section can have more than one location
class Meeting
{  
    var $classNum;
    var $startTime;
    var $endTime;
    var $campus;
   
    function Meeting()
    {
    }

    function setData($classNum, $startTime, $endTime, $campus)
    {
        $this->classNum = $classNum;
        $this->startTime = $startTime;
        $this->endTime = $endTime;       
        $this->campus = $campus;
    }

    // initialize the meeting from a database row
    function setDataFromDBRow($row)
    {
        $this->classNum = $row['classNum'];
        $this->startTime = $row['startTime'];
        $this->endTime = $row['endTime'];
        $this->campus = $row['campus'];
    }
}


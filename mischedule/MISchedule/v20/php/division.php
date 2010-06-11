<?php

class Division
{
    // The full name of the Division, aka, "Electrical Engineering and Computer Science"
    var $name;

    // The up to eight letter abbreviation of the Division, aka, "EECS"
    var $abbrev;    

    function Division()
    {
    }


    // initializes the division from a "row" of data from a MYSQL query
    function setDataFromDBRow($row)
    {
        $this->name = $row['name'];
        $this->abbrev = $row['abbrev'];
    }

    function setData($name, $abbrev)
    {
        $this->name = $name;
        $this->abbrev = $abbrev;
    }
}

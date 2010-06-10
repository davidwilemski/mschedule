<?php

class Course
{
    var $number;
    var $name;    
    
    function Course()
    {
    }

    function setData($number, $name)
    {
        $this->name = $name;
        $this->number = $number;
    }

    function setDataFromDBRow($row)
    {
        $this->name = $row['name'];
        $this->number = $row['number'];
    }
}

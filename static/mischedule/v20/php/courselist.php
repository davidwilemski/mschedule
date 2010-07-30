<?php

require_once("wafunctions.php");
require_once("course.php");
require_once("parsers.php");
require_once("dbfunctions.php");

class CourseList
{
    // An Array of Course objects
    var $courses;    

    // Which division are we listing
    var $division;

    var $term;

    var $whereClause;

    function CourseList($pTerm, $pDivision)
    {
        $this->term = $pTerm;
        $this->division = $pDivision;
        $this->whereClause = "WHERE term = '$this->term' AND division = '$this->division'";
    }

    function readListFromWA()
    {
        $page = "";
        $errRet = getCourseListFromWA($this->term, $this->division, &$page);
        if ($errRet != 0) return -1;

        $tableData = parseWAPage($page, 3);
        if (!$tableData) return -1;

        //there are junk values at the beginning
	$junk = 1;

        $row = 0;
        $numColumns = 5;

        for ($i=$junk; $i<sizeof($tableData); $i+=$numColumns)
        {
             $course = new Course();
             $course->setData($tableData[$i], $tableData[$i+1]);
             $this->courses[$row++] = $course;
        }
    }

    function readListFromDatabase()
    {
        connectToDB();

	if ( $this->getNumCoursesInDB() == 0 ) return -1;
  
        $query = "SELECT * FROM courses $this->whereClause ORDER BY number";
        $result = execQuery($query);
        $row = 0;
        while ($line = mysql_fetch_array($result, MYSQL_ASSOC))
        {
            $course = new Course();
            $course->setDataFromDBRow($line);
            $this->courses[$row++] = $course;
        }
        mysql_close();
        return 0;
    }

    function writeListToDatabase()
    {
        connectToDB();

        if (!isset($this->courses)) return;
 
        $n = sizeof($this->courses); 
        if ($n == 0) return;

        //Create the insert query before-hand, so as not to lock
        //the database for more time than necessary
        $insertQuery = $this->createInsertQuery($n);
            
        execQuery("LOCK TABLES courses WRITE");

        if ( $this->getNumCoursesInDB() > 0 )
        { 
            $query = "DELETE FROM courses $this->whereClause";
            execQuery($query);
        }

        execQuery($insertQuery);

        execQuery("UNLOCK TABLES");

        mysql_close();
    }

    function outputList()
    {
        if (!isset($this->courses))
        {
            printf("0\n");
            return;
        }        
        
        $n = sizeof($this->courses); 
        printf("%d\n", $n);
        for ($i=0; $i<$n; $i++)
        {
            printf("%s\n", $this->courses[$i]->number);
            printf("%s\n", $this->courses[$i]->name);
        }
    }

    function getNumCoursesInDB()
    {
        $query = "SELECT count(*) FROM courses $this->whereClause";
     
        $result = execQuery($query);
     
        $line = mysql_fetch_array($result, MYSQL_ASSOC);
       
        return $line['count(*)'];
    }

    function createInsertQuery($n)
    {
        $query = "INSERT INTO courses (term, division, number, name) VALUES\n";
        for ($i=0; $i<$n; $i++)
        {   
            if ($i==0)
	        $query = $query . $this->createInsertForOne($this->courses[$i]);
            else 
                $query = $query . ",\n" . $this->createInsertForOne($this->courses[$i]);
        }
        return $query;
    }

    function createInsertForOne($course)
    {
        //We better escape any single-quotes so that the insert statement won't fail        
        $name = str_replace("'", "\\'", $course->name);
	return "('$this->term', '$this->division', '$course->number', '$name')";
    }
}
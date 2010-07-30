<?php

require_once("wafunctions.php");
require_once("division.php");
require_once("parsers.php");
require_once("dbfunctions.php");

class DivisionList
{
    // An Array of Division objects
    var $divisions;

    var $term;    

    var $whereClause;

    function DivisionList($pTerm)
    {
        $this->term = $pTerm;
        $this->whereClause = "WHERE term = '$this->term'";
    }

    function readListFromWA()
    {
        $page = "";
        if ( getDivisionListFromWA($this->term, &$page) != 0 ) return -1;

        $tableData = parseWAPage($page, 2);
        if (!$tableData) return -1;

        //there are junk values at the beginning
	$junk = 1;

        $row = 0;
        $numColumns = 3;
  
        for ($i=$junk; $i<sizeof($tableData); $i+=$numColumns)
        {
             $division = new Division();
             $division->setData($tableData[$i], $tableData[$i+1]);
             $this->divisions[$row++] = $division;
        }
    }

    function readListFromDatabase()
    {
        connectToDB();      
        $query = "SELECT * FROM divisions $this->whereClause ORDER BY abbrev";
        $result = execQuery($query);
        $row = 0;
        while ($line = mysql_fetch_array($result, MYSQL_ASSOC))
        {
            $division = new Division();
            $division->setDataFromDBRow($line);
            $this->divisions[$row++] = $division;
        }       
        mysql_close();
    }

    function writeListToDatabase()
    {
        connectToDB();

        if (!isset($this->divisions)) return;
 
        $n = sizeof($this->divisions); 
        if ($n == 0) return;

        //Create the insert query before-hand, so as not to lock
        //the database for more time than necessary
        $insertQuery = $this->createInsertQuery($n);
            
        execQuery("LOCK TABLES divisions WRITE");

        if ( $this->getNumDivisionsInDB() > 0 )
        { 
            $query = "DELETE FROM divisions $this->whereClause";
            execQuery($query);
        }

        execQuery($insertQuery);

        execQuery("UNLOCK TABLES");

        mysql_close();
    }

    function outputList()
    {
        if (!isset($this->divisions))
        {
            printf("0\n");
            return;
        }

        $n = sizeof($this->divisions); 
        printf("%d\n", $n);
        for ($i=0; $i<$n; $i++)
        {
            printf("%s\n", $this->divisions[$i]->abbrev);
            printf("%s\n", $this->divisions[$i]->name);
        }
    }

    function getNumDivisionsInDB()
    {
        $query = "SELECT count(*) FROM divisions $this->whereClause";
     
        $result = execQuery($query);
     
        $line = mysql_fetch_array($result, MYSQL_ASSOC);
       
        return $line['count(*)'];
    }

    function createInsertQuery($n)
    {
        $query = "INSERT INTO divisions (term, name, abbrev) VALUES\n";
        for ($i=0; $i<$n; $i++)
        {   
            if ($i == 0) 
                $query = $query . $this->createInsertForOne($this->divisions[$i]);
            else
                $query = $query . ",\n" . $this->createInsertForOne($this->divisions[$i]);
        }
        return $query;
    }

    // make an insert statement using the values in this class
    function createInsertForOne($division)
    {
        return "('$this->term', '$division->name', '$division->abbrev')";
    }
}



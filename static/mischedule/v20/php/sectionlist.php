<?php

require_once("wafunctions.php");
require_once("section.php");
require_once("meeting.php");
require_once("sectionLocation.php");
require_once("parsers.php");
require_once("dbfunctions.php");

class SectionList
{
    // An Array of Section objects
    var $sections;    

    // An Array that keeps track of which sections are at which index
    var $sectionPlaces;

    var $division;
    var $course;
    var $term;
    var $whereClause;

    function SectionList($pTerm, $pDivision, $pCourse)
    {
        $this->term = $pTerm;
        $this->division = $pDivision;
        $this->course = $pCourse;

	$this->whereClause = "WHERE term = '$this->term' AND division = '$this->division' AND course = '$this->course'";
    }

    function readListFromWA()
    {
        $startTime = time();

        $page = "";
        $errRet = getSectionListFromWA($this->term, $this->division, $this->course, &$page);
        if ($errRet != 0) return -1;

        $tableData = parseWAPage($page, 3);
        if (!$tableData) return -1;

        //there are 4 junk values at the beginning
	$junk = 4;

        $column = 0;
        $row = 0;
        $numColumns = 11;

        $getFullInformation = true;

        // The deal here is that sometimes we want to get full information, but sometimes
        // we might just want to get the number of open seats.

        // Also, when there are multiple locations for a class, after the first location.
        // we only want the time string and the location.

        for ($i=$junk; $i<sizeof($tableData); $i++)
        {
            switch ($column)
            {
                case 0: // worthless junk
                   break;
                case 1:
                   // this is the class number.  if it is filled, then we have a new 
                   // section, otherwise a continuation of the same section

                   $val = (int) $tableData[$i];
                   if ( $val <= 0 )
                   {
                       $location = new SectionLocation();
                       $currentSection->addLocation($location);   
                   }
                   else
                   {
                       $currentSection = new Section();
                       $currentSection->classNum = $tableData[$i];
                       $location = new SectionLocation();
                       $currentSection->addLocation($location);                       
                   }
                   break;
                case 2:
                   // this is the status (open/closed).  not too useful.
                   break;
                case 3:
                   // this is the time string.
                   if ($getFullInformation)
                   {
                       $currentSection->parseTimeInformation($tableData[$i], $tableData[$i+6]);
                   }
                   break;
                case 4:
                   // the number of credits
                   if ($getFullInformation && $currentSection->numLocations == 1)
                       $currentSection->credits = $tableData[$i];
                   break;
                case 5:
                   // the number of open seats
                   if ($currentSection->numLocations == 1)
                       $currentSection->openSeats = $tableData[$i];
                   break;
                case 6:
                   // waitlist
                   if ($currentSection->numLocations == 1)
                   {
                       if ($tableData[$i] == "No") 
                          $currentSection->waitlistNum = -1;
                       else 
                          $currentSection->waitlistNum = (int) $tableData[$i];
                   }
                   break;
                case 7:
                   // session, whatever the fuck that is
                   break;
                case 8:
                   // the section, in format like this: Discussion 001
                   if ($getFullInformation && $currentSection->numLocations == 1)                   
                       $currentSection->parseSectionInformation($tableData[$i]);
                   break;
                case 9:
                   // the location 
                   if ($getFullInformation)
                   {
                      $index = $currentSection->numLocations - 1;
                      $currentSection->locations[$index]->location = $tableData[$i];
                   }
                case 10:
                   if ($getFullInformation && $currentSection->numLocations == 1)
                       $currentSection->instructor = $tableData[$i];
             }
             $column++;
             if ($column == $numColumns) 
             {
                 // if there are multiple locations, then overwrite the previous row
                 if ($currentSection->numLocations > 1)
                 {
                     $this->sections[$row-1] = $currentSection;
                 }
                 else
                 {
                     $this->sections[$row++] = $currentSection;
                 }    
                 $column = 0;
             }
        }

        $this->calculateLinkGroups();
    }


    function readListFromDatabase()
    {
        connectToDB();
	if ( $this->getNumSectionsInDB() == 0 ) return -1;
        $this->getSectionsFromDB("SELECT * FROM sections $this->whereClause ORDER BY sectionNum");
        $this->getMeetingsFromDB("SELECT * FROM meetings $this->whereClause");
        $this->getLocationsFromDB("SELECT * FROM locations $this->whereClause");
        mysql_close();
        return 0;
    }

    function getSectionsFromDB($query)
    {
        $results = execQuery($query);
        $row = 0;
        while ($line = mysql_fetch_array($results, MYSQL_ASSOC))
        {
            $this->sections[$row] = new Section();
            $this->sections[$row]->setDataFromDBRow($line);
            $this->sectionPlaces[ $line['classNum'] ] = $row;
            $row++;
        }
    }

    function getMeetingsFromDB($query)
    {
        $results = execQuery($query);
        while ($line = mysql_fetch_array($results, MYSQL_ASSOC))
        {
            $classNum = $line['classNum'];
            $row = $this->sectionPlaces[$classNum];
            $meeting = new Meeting();
            $meeting->setDataFromDBRow($line);
            $this->sections[$row]->addMeeting( $meeting );
        }
    }

    function getLocationsFromDB($query)
    {         
	$results = execQuery($query);
        while ($line = mysql_fetch_array($results, MYSQL_ASSOC))
        {
            $classNum = $line['classNum'];
            $row = $this->sectionPlaces[$classNum];
            $location = new SectionLocation($line);
            $location->setDataFromDBRow($line);
            $this->sections[$row]->addLocation( $location );
        }
    }

    function writeListToDatabase()
    {
        connectToDB();

        if (!isset($this->sections)) return;
 
        $n = sizeof($this->sections); 
        if ($n == 0) return;

        //Create the insert querys before-hand, so as not to lock
        //the database for more time than necessary
        
        $this->createInsertQuery($n, $insertSections, $insertMeetings, $insertLocations);

        execQuery("LOCK TABLES sections WRITE, meetings WRITE, locations WRITE");            
        if ( $this->getNumSectionsInDB() > 0 )
        { 
            execQuery("DELETE FROM sections $this->whereClause");
            execQuery("DELETE FROM meetings $this->whereClause");
            execQuery("DELETE FROM locations $this->whereClause");
        }

        if (isset($insertSections)) execQuery($insertSections);
        if (isset($insertMeetings)) execQuery($insertMeetings);
        if (isset($insertLocations)) execQuery($insertLocations);

        execQuery("UNLOCK TABLES");
        mysql_close();
    }

    function outputList()
    {
        if (isset($this->sections))
        {
            $n = sizeof($this->sections); 
            printf("%d\n", $n);
            for ($i=0; $i<$n; $i++)
            {
                $section = $this->sections[$i];
                printf("%s\n", $section->classNum);
                printf("%s\n", $section->credits);
                printf("%s\n", $section->openSeats);
                printf("%s\n", $section->waitlistNum);
                printf("%s\n", $section->sectionType);
                printf("%s\n", $section->sectionNum);
                printf("%s\n", $section->instructor);
                printf("%s\n", $section->linkageGroup);
                printf("%s\n", $section->numLocations);
                for ($j=0; $j<$section->numLocations; $j++)
                {
                    printf("%s\n", $section->locations[$j]->timeString);
                    printf("%s\n", $section->locations[$j]->location);
                }
                printf("%s\n", $section->numMeetings);
                for ($j=0; $j<$section->numMeetings; $j++)
                {
                    printf("%d\n", $section->meetings[$j]->startTime);
                    printf("%d\n", $section->meetings[$j]->endTime);
                    printf("%s\n", $section->meetings[$j]->campus);
                }
            }
        }
        else 
        {
            printf("0\n");
        }    
    }

    function createInsertQuery($n, &$insertSections, &$insertMeetings, &$insertLocations)
    {
        for ($i=0; $i<$n; $i++)
        {   
            $section = $this->sections[$i];

            if (!isset($insertSections))
                $insertSections = "INSERT INTO sections (term, division, course, classNum, credits, openSeats, waitlistNum, sectionNum, sectionType, instructor, linkageGroup) VALUES\n" . $this->createInsertForSection($section);
            else
                $insertSections = $insertSections . ",\n" . $this->createInsertForSection($section);
 
            for ($j=0; $j<$section->numMeetings; $j++)
            {
                if (!isset($insertMeetings))
                    $insertMeetings = "INSERT INTO meetings (term, division, course, classNum, startTime, endTime, campus) VALUES\n" . $this->createInsertForMeeting($section->meetings[$j]);
                else
                    $insertMeetings = $insertMeetings . ",\n" . $this->createInsertForMeeting($section->meetings[$j]);
            }

            for ($j=0; $j<$section->numLocations; $j++)
            {
                if (!isset($insertLocations))
                    $insertLocations = "INSERT INTO locations (term, division, course, classNum, timeString, location) VALUES\n" . $this->createInsertForLocation($section->locations[$j]);
                else
                    $insertLocations = $insertLocations . ",\n" . $this->createInsertForLocation($section->locations[$j]);
            }         
        }
    }

    function createInsertForSection($section)
    {
        //We better escape any single-quotes so that the insert statement won't fail        
        $instructor = str_replace("'", "\\'", $section->instructor);

	return "('$this->term', '$this->division', '$this->course', '$section->classNum', '$section->credits', $section->openSeats, $section->waitlistNum, '$section->sectionNum', '$section->sectionType', '$instructor', $section->linkageGroup)";
    }

    function createInsertForMeeting($meeting)
    {
	return "('$this->term', '$this->division', '$this->course', '$meeting->classNum', $meeting->startTime, $meeting->endTime, '$meeting->campus')";
    }

    function createInsertForLocation($location)
    {
	return "('$this->term', '$this->division', '$this->course', '$location->classNum', '$location->timeString', '$location->location')";
    }

    function getNumSectionsInDB()
    {
        $query = "SELECT count(*) FROM sections $this->whereClause";
        $result = execQuery($query);
        $line = mysql_fetch_array($result, MYSQL_ASSOC);
        return $line['count(*)'];
    }

    function calculateLinkGroups()
    {
	if ( strcmp($this->division, "CHEM") == 0 && strcmp($this->course, "125") == 0  )
	{
	    $group1 = 0;
	    $group2 = 0;

            $n = sizeof($this->sections);
            for ($i=0; $i<$n; $i++)
            {
               if ($i==0)
               {
                   $firstType = $this->sections[$i]->sectionType;
               }
	       elseif ($i==1)
	       {
                   $secondType = $this->sections[$i]->sectionType;
               }
	
               if ($this->sections[$i]->sectionType == $firstType)
               {
                  $group1 = $group1 + 100;
		  $group2 = 0;
               }
	       elseif ($this->sections[$i]->sectionType == $secondType)
	       {
		  $group2 = $group2 + 1;
	       }

               $this->sections[$i]->linkageGroup = $group1 + $group2;              
            }

	    return;
	}

        if ($this->linkageExists())
        {
            $n = sizeof($this->sections);
            $currentGroup = 0;
            for ($i=0; $i<$n; $i++)
            {
               if ($i==0)
               {
                   $firstType = $this->sections[$i]->sectionType;
               }
               if ($this->sections[$i]->sectionType == $firstType)
               {
                  $currentGroup++;
               }
               $this->sections[$i]->linkageGroup = $currentGroup;              
            }
        }
        else
        {
            $n = sizeof($this->sections);
            for ($i=0; $i<$n; $i++)
            {
               $this->sections[$i]->linkageGroup = 1;
            }
        }
    }

   function linkageExists()
   {
        $n = sizeof($this->sections);
        for ($i=0; $i<$n; $i++)
        {
           $section = $this->sections[$i];
           if ($i==0)
           {
               $firstType = $section->sectionType;
           }
           else if ($section->sectionType == $firstType)
           {
               // we can't have two of the first type in a row
               if ($prevType == $firstType) return false;
           }
           else if ($i==1)
           {
               $secondType = $section->sectionType;
           }
           else if ($section->sectionType != $secondType)
           {
               //It's not the first type, or the second type.  Too
               //confusing for us, no linkage
               return false;
           }
           $prevType = $section->sectionType;   
        }
        // finally, check that the last section wasn't of the first type
        if ($section->sectionType == $firstType)
        {
            return false;
        }
        return true;
   }


}
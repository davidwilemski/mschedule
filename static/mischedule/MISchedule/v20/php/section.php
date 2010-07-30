<?php
require_once "campusfromlocation.php";

class Section
{
   // an array of section location objects. usually will have 
   // just one entry
   var $locations;
   var $numLocations;

   // an array of meetings for each section.  each meeting has 
   // a start time and an end-time
   var $numMeetings;
   var $meetings;

   var $classNum;
   var $credits;
   var $openSeats;
   var $waitlistNum;
   var $sectionNum;
   var $sectionType;
   var $instructor;
   var $linkageGroup;

   // used only when read from database
   var $division;
   var $course;

   function Section()
   {
       $this->numMeetings = 0;
       $this->numLocations = 0;
   }

   //initialize the location from a database row
   function setDataFromDBRow($row)
   {
       $this->classNum = $row['classNum'];
       $this->credits = $row['credits'];
       $this->openSeats = $row['openSeats'];
       $this->waitlistNum = $row['waitlistNum'];
       $this->sectionNum = $row['sectionNum'];
       $this->sectionType = $row['sectionType'];
       $this->instructor = $row['instructor'];
       $this->linkageGroup = $row['linkageGroup'];
       $this->division = $row['division'];
       $this->course = $row['course'];
   }

   function parseSectionInformation($sectionString)
   {
       $components = explode(" ", $sectionString);
       $this->sectionType = $components[0]; 
       $this->sectionNum = $components[1];
   }

   function getCampusFromLocation($location)
   {
	   //retun the general function
       return getCampusFromLocation($location);
   }

   function parseTimeInformation($timeString, $location)
   {            
       $k = $this->numLocations-1;
       $this->locations[$k]->timeString = $timeString;  

       //Check if this time string is a duplicate of another location.
       //If so, then don't add any meetings.
       for ($i=0; $i<$k; $i++)
       {
           if ($this->locations[$i]->timeString == $this->locations[$k]->timeString) return;
       }
       $campus = $this->getCampusFromLocation($location);
 
       $components = explode(",", $timeString);
       $stime = 0;
       $etime = 0;
       for ($i=sizeof($components)-1; $i>=0; $i--)
       {
          if ($components[$i] == "M") 
          {
              $meeting = new Meeting(); 
              $meeting->setData($this->classNum, $stime, $etime, $campus);
              $this->addMeeting($meeting);
          }
          else if ($components[$i] == "Tu") 
          {
              $meeting = new Meeting(); 
              $meeting->setData($this->classNum, $stime+48, $etime+48, $campus);
              $this->addMeeting($meeting);
          }
          else if ($components[$i] == "W") 
          {
              $meeting = new Meeting(); 
              $meeting->setData($this->classNum, $stime+96, $etime+96, $campus);
              $this->addMeeting($meeting);
          }
          else if ($components[$i] == "Th") 
          {
              $meeting = new Meeting(); 
              $meeting->setData($this->classNum, $stime+144, $etime+144, $campus);
              $this->addMeeting($meeting);
          }
          else if ($components[$i] == "F") 
          {
              $meeting = new Meeting(); 
              $meeting->setData($this->classNum, $stime+192, $etime+192, $campus);
              $this->addMeeting($meeting);
          }
          else
          {
              preg_match("/[^0-9]*([0-9]+):([0-9]+)([A,P]M)[^0-9]*([0-9]+):([0-9]+)([A,P]M)/", $components[$i], $matches);
  
              if (sizeof($matches) > 6)
              {
                  $stime = (int) $matches[1];
                  $stime = $stime*2;   
	   
	          if ($stime == 24) $stime = 0;
	          if ($matches[2] == "30")
	              $stime++;
                  if ($matches[3] == "PM")
	              $stime += 24;

                  $etime = (int) $matches[4];
                  $etime = $etime*2;   
	   
	          if ($etime == 24) $etime = 0;
	          if ($matches[5] == "30")
	              $etime++;
                  if ($matches[6] == "PM")
	              $etime += 24;
             }
         } 
      }     
   }

   function addMeeting($meeting)
   {
       $meeting->classNum = $this->classNum;
       $this->meetings[$this->numMeetings++] = $meeting;
   }

   function addLocation($location)
   {
       $location->classNum = $this->classNum;
       $this->locations[$this->numLocations++] = $location;
   }
}


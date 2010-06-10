<style>
table
{
    border:solid 1px black;
}

.td1
{
    background-color: #eeeeee;
}

.th1
{
    background-color: #eeeeee;
}

td
{
    border-bottom:solid 1px black;
    border-right:solid 1px black;
}

th
{
    border-bottom:solid 1px black;
    border-right:solid 1px black;
}
</style>

<?

require_once("sectionlist.php");
require_once("dbfunctions.php");
require_once("section.php");
require_once("meeting.php");
require_once("sectionLocation.php");

if ($command == "printsections")
{
    $toDisplay = explode(";", $data);
    for ($i=0; $i<sizeof($toDisplay); $i++)
    {
        $components = explode(":", $toDisplay[$i]);
        $division = $components[0];
        $course = $components[1];
        $sectionList = new SectionList($term, $division, $course);
        $sectionList->readListFromDatabase();
        drawSectionList($sectionList);
   }
}
if ($command == "printschedules")
{
    $sectionList = new SectionList($term, "", "");
    getSections($sectionList, $data, $term);

    if ($table == "1")
    {
        drawSkedTable($sectionList, false);
    }
    if ($list == "1")
    {
        drawSkedList($sectionList, false);
    }
}

function drawSectionList($sl)
{
    printf("<h3>Sections for $sl->division $sl->course<BR></h3>\n");
    printf("<TABLE cellspacing=0 cellpadding=2>\n");
    drawSectionHeader(false);
    for ($i=0; $i<sizeof($sl->sections); $i++)
    {
        drawSectionRow($sl->sections[$i], false);
    }
    printf("</TABLE>");  
}

function drawSectionHeader($showCourse)
{
    printf("<TR>\n");
    if ($showCourse)
    {
        printf("<TH>Course\n");
    }
    printf("<TH>Class Num\n");
    printf("<TH>Credits\n");
    printf("<TH>Open Seats\n");
    printf("<TH>Wait List\n");
    printf("<TH>Section\n");
    printf("<TH>Day/Time\n");
    printf("<TH>Location\n");
    printf("<TH>Instructor\n");
}

function drawSectionRow(&$s, $showCourse)
{
    $location = "";
    $timeString = "";
    for ($i=0; $i<$s->numLocations; $i++)
    {
        if ($i != 0)
        {
            $location .= "<BR>&nbsp;";
            $timeString .= "<BR>&nbsp;";
        }
        $location .= $s->locations[$i]->location;
        $timeString .= $s->locations[$i]->timeString;
    }
    if ($s->waitlistNum == -1)
        $waitlist = "No";
    else $waitlist = $s->waitlistNum;


    printf("<TR>\n");
    if ($showCourse)
    {
        printf("<TD>&nbsp;%s %s", $s->division, $s->course);
    }
    printf("<TD align=center>%s", $s->classNum);
    printf("<TD align=center>%s", $s->credits);
    printf("<TD align=center>%s", $s->openSeats);
    printf("<TD align=center>%s", $waitlist);
    printf("<TD>&nbsp;%s %s&nbsp;", $s->sectionType, $s->sectionNum);
    printf("<TD>&nbsp;%s&nbsp;", $timeString);
    printf("<TD>&nbsp;%s&nbsp;", $location);
    printf("<TD>&nbsp;%s&nbsp;", $s->instructor);
}

function drawSkedTable(&$sl)
{
    buildTimeArray($sl, $times);
    getTimeBoundaries($times, $startHH, $endHH);
    
    printf("<h3>Table View<BR></h3>\n");
    printf("<TABLE cellspacing=0 cellPadding=2>\n");
    printf("<TR><TH>&nbsp;\n");
    printf("<TH>Monday");
    printf("<TH>Tuesday");
    printf("<TH>Wednesday");
    printf("<TH>Thursday");
    printf("<TH>Friday");

    for ($hh=$startHH; $hh<$endHH; $hh++)
    {
        printf("<TR>");
        $time = getTimeStringFromHH($hh);
        printf("<TD>%s", $time);
        for ($d=0; $d<5; $d++)
        {
            $i = $d*48+$hh;
            if ( $times[$i] == -2 )
            {
                // this cell is already part of another <td>
            }
            else if ( $times[$i] == 0 )
            { 
                // empty cell
                printf("<TD>&nbsp;");
            }
            else 
            {
                // figure out how many rows this cell spans
                $j = $i+1;
                while ($times[$j] == $times[$i])
                {
                    $times[$j] = -2;
                    $j++;
                }
                printf("\n<td bgcolor=#eeeeee rowspan=%d>", $j-$i);  
                if ($times[$i] == -1)
                {
                    printf("Overlap!");
                }        
                else
                {
                    drawSectionDataCell($sl->sections[ $times[$i]-1 ] );
                }    
            }        
        }
    }

    printf("</TABLE>\n");
}

function drawSectionDataCell($s)
{
    printf("%s %s<BR>", $s->division, $s->course );
    printf("%s %s (%s)<BR>", $s->sectionType, $s->sectionNum, $s->classNum );
    if ($s->openSeats > 0)
    {
        printf("#Open Seats: %s", $s->openSeats);
    }
    else if ($s->waitlistNum == -1)
        printf("Closed! Waitlist: No");
    else printf("Closed! Waitlist: %s", $s->waitlistNum);

}

function drawSkedList(&$sl)
{
    printf("<h3>Detailed View<BR></h3>\n");
    printf("<TABLE cellspacing=0 cellpadding=2>\n");
    drawSectionHeader(true);
    for ($i=0; $i<sizeof($sl->sections); $i++)
    {
        drawSectionRow($sl->sections[$i], true);
    }
    printf("</TABLE>");  
}

function getSections(&$sectionList, &$data, &$term)
{
    $classNums = explode(";", $data);
    $where = "WHERE term = '$term' AND (";
    for ($i=0; $i<sizeof($classNums); $i++)
    {
        if ($i != 0) $where .= " OR ";
        $where .= "classNum = " . $classNums[$i];       
    }
    $where .= ")";
    connectToDB();
    $sectionList->getSectionsFromDB("SELECT * FROM sections $where");
    $sectionList->getLocationsFromDB("SELECT * FROM locations $where");
    $sectionList->getMeetingsFromDB("SELECT * FROM meetings $where");
    mysql_close();
}


function buildTimeArray(&$sl, &$times)
{
    for ($i=0; $i<48*5; $i++) $times[$i] = 0;

    for ($i=0; $i<sizeof($sl->sections); $i++)
    {
        for ($j=0; $j<$sl->sections[$i]->numMeetings; $j++)
        {
            $start = $sl->sections[$i]->meetings[$j]->startTime;
            $end = $sl->sections[$i]->meetings[$j]->endTime;
            for ($k=$start; $k<$end; $k++)
            {
                if ($times[$k] == 0)
                    $times[$k] = $i+1;
                else $times[$k] = -1;
            }
        }
    }
}

function getTimeBoundaries(&$times, &$startHH, &$endHH)
{
    $startHH = 48;
    for ($hh=0; $hh<48; $hh++)
    {
       for ($d=0; $d<5; $d++)
       {
           if ($times[$d*48+$hh] != 0)
           {
               $startHH = $hh;
               break 2;
           }
       }
   }

   $endHH = 0;
   for ($hh=47; $hh>=0; $hh--)
   {
       for ($d=0; $d<5; $d++)
       {
           if ($times[$d*48+$hh] != 0)
           {
               $endHH = $hh+1;
               break 2;
           }
       }
   }     
}

function getTimeStringFromHH($hh)
{
    $hour = (int) ($hh / 2);
    if ( $hh % 2 == 1 )
    {
         if ($hour < 12)
            $suffix = ":30am";
         else 
            $suffix = ":30pm";
    }
    else
    {
        if ($hour < 12)
            $suffix = ":00am";
        else 
            $suffix = ":00pm";
    }

    if ( $hour <= 12 ) 
        $timeString = ((String)$hour) . $suffix;
    else 
        $timeString = ((String)($hour-12)) . $suffix;

    return $timeString;
}

?>

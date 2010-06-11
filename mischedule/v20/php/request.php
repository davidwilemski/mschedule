<?php
@require_once("wafunctions.php");
@require_once("divisionlist.php");
@require_once("courselist.php");
@require_once("sectionlist.php");
@require_once("bookslist.php");
@require_once("dbfunctions.php");
@require_once("checkopen.php");
error_reporting(0);

if(array_key_exists('command', $_GET))
	$command = $_GET['command'];
if(array_key_exists('division', $_GET))
	$division = $_GET['division'];
if(array_key_exists('course', $_GET))
	$course = $_GET['course'];
if(array_key_exists('term', $_GET))
	$term = $_GET['term'];
if(array_key_exists('fresh', $_GET))
	$fresh = $_GET['fresh'];

if ( !isset($command) ) 
    die("No command");

// A quick check so that people can't execute
// arbitrary SQL on our database
if (isset($division))
    if (strlen($division) > 8) die("-1: Invalid Division");
if (isset($course))
    if (strlen($course) > 8) die("-1: Invalid Course");
if (isset($term))
    if (strlen($term) > 8) die("-1: Invalid Term");

if (!WAOpen())
{
    // if WA is not open, we can only get from the database
    $getFromDatabase = true;
}
else
{
    if (isset($fresh))
        $getFromDatabase = false;
    else
    {
        if ($command == "divisions") 
            $getFromDatabase = isCurrent($term, "-1", "-1", 24*60*60);
        else if ($command == "courses") 
            $getFromDatabase = isCurrent($term, $division, "-1", 24*60*60);
        else if ($command == "sections")
            $getFromDatabase = isCurrent($term, $division, $course, 60*60);
    }
}  

if ($command == "divisions")
{

    $divList = new DivisionList($term);
    if ($getFromDatabase)
    {
        $divList->readListFromDatabase();
    }
    else
    {
        $divList->readListFromWA();
        $divList->writeListToDatabase();
        updateLastModified($term, "-1", "-1");
    }
    $divList->outputList();
}

else if ($command == "courses")
{
    $courseList = new CourseList($term, $division);
    if ($getFromDatabase)
    {
        $courseList->readListFromDatabase();
    }
    else
    {
        $courseList->readListFromWA();
        $courseList->writeListToDatabase();
        updateLastModified($term, $division, "-1");
    }
    $courseList->outputList();
}

else if ($command == "sections")
{
    $sectionList = new SectionList($term, $division, $course);
    if ($getFromDatabase)
    {
        $sectionList->readListFromDatabase();
    }
    else
    {
        $sectionList->readListFromWA();
        $sectionList->writeListToDatabase();
        updateLastModified($term, $division, $course);
    }
    $sectionList->outputList();
}

function isCurrent($t, $d, $c, $timeOut)
{
    connectToDB();
    $time = time();
    $oldTime = $time - $timeOut;
    $query = "SELECT count(*) FROM lastModified WHERE term = '$t' AND division = '$d' AND course = '$c' AND lastModified > $oldTime";
    $result = execQuery($query);
    $line = mysql_fetch_array($result, MYSQL_ASSOC);
    if ($line['count(*)'] == 0)
        return false;
    else
        return true;
    mysql_close();
}

function updateLastModified($t, $d, $c)
{
    connectToDB();
    $time = time();
    $result = execQuery("SELECT count(*) FROM lastModified WHERE term = '$t' AND division = '$d' AND course = '$c'");
    $line = mysql_fetch_array($result, MYSQL_ASSOC); 
    execQuery("LOCK TABLES lastModified WRITE");
    if ($line['count(*)'] != 0)
        execQuery("UPDATE lastModified SET lastModified = $time WHERE term = '$t' AND division = '$d' AND course = '$c'");
    else
        execQuery("INSERT INTO lastModified (term,division,course,lastModified) VALUES ('$t', '$d', '$c', $time)");
    execQuery("UNLOCK TABLES");
    mysql_close();
}

?>

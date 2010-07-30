<?php
require_once("dbfunctions.php");

/******************************************** 
* getKeyFromWA
*
* This function fills the $waKey parameter with
* a new WolverineAccess session cookie.
*
* Returns 0 on success
********************************************/    

function getKey(&$waKey)
{
    connectToDB();    

    //Find a key used in the last seven minutes
    $time = time();
    $oldTime = $time - 420;
    $result = execQuery("SELECT wakey FROM wakeys WHERE lastUsed > $oldTime");
    $line = mysql_fetch_array($result, MYSQL_ASSOC);
    if ($line) 
    {
        // There is a key that works 
        $waKey = $line['wakey'];
        execQuery("UPDATE wakeys SET lastUsed = $time WHERE wakey = '$waKey'");
        mysql_close();
        return 0;
    }

    //We need to get a different key
    $retval = makeHttpRequestToWA("GET /UM_UnauthUser HTTP/1.0\r\n\r\n", &$page);
    if ($retval != 0) return -1;
    
    preg_match("/sesessionid=([^;]*)/i", $page, $matches);
    $waKey = $matches[1];
    if (strlen($waKey) == 0) return -1;

    //Save the new key
    execQuery("LOCK TABLES wakeys WRITE");
    execQuery("DELETE FROM wakeys");
    execQuery("INSERT INTO wakeys (wakey,lastUsed) VALUES ('$waKey', $time)");
    execQuery("UNLOCK TABLES");
    mysql_close();
    
    return 0;
}    





/******************************************** 
* getWAPage
*
* This function fills the $page parameter with
* a web page from WolverineAccess.
*
* $termCode -  The WolverineAccess term code 
* $waKey    -  The WolverineAccess session cookie (see getKeyFromWA)
* $division, $course  -
*    (If $division is "", then get the list of all available divisions)
*    (If $division is specified, but $course is "", then
*        get the list of courses for $division)
*    (If both are specified, get the sections for the specified $course)
*
* Returns 0 on success
********************************************/    

function getWAPage($termCode, $division, $course, &$page)
{
    $waKey = "";
    if ( getKey($waKey) != 0 ) return -1;

    $postData = "Submit=SubjectCodeSearch&Term=" . $termCode . "&TermActivated%3D%27false%27=&Source=ClassSearch&Action=SearchClass&SwapClass=null&Subject=" . $division . "&Catalog= " . $course . "&SubjectFilter=";

    // Build the HTTP header
    $httpRequest  = "POST /ClassSchedule HTTP/1.0\r\n";
    $httpRequest .= "Host: wolverineaccess.umich.edu\r\n";
    // We've got to close the connection, otherwise we spend about 15 seconds waiting for the last bit of data
    $httpRequest .= "Connection: close\r\n";
    $httpRequest .= "Cookie: sesessionid=" . $waKey . "\r\n";
    $httpRequest .= "Content-Type: application/x-www-form-urlencoded\r\n";
    $httpRequest .= "Content-Length: " . strlen($postData) . "\r\n";
    $httpRequest .= "\r\n";

    // Now add the data to the request
    $httpRequest .= $postData;

    $retval = makeHttpRequestToWA($httpRequest, $page);    

    if ($retval != 0) 
	return -1;

    return 0;
}

/******************************************** 
* makeHttpRequestToWA
*
* This function fills the $response parameter with the
* WolverineAccess response to the GET or POST request
* specified in $httpRequest.
*
* Returns 0 on success
********************************************/    
    
function makeHttpRequestToWA($httpRequest, &$response)
{
    $connection = fsockopen ("wolverineaccess.umich.edu", 80, &$errno, &$errstr, 30);
    if (!$connection)
    {
        return -1;
    }
    fputs ($connection, $httpRequest);
    $response = "";
    while (!feof($connection)) 
    {
        $response .= fgets ($connection,1024);
    }
    fclose ($connection);
    return 0;
}

function getDivisionListFromWA($term, &$page)
{
    return getWAPage($term, "", "", $page);
}

function getCourseListFromWA($term, $division, &$page)
{
    return getWAPage($term, $division, "", $page);
}

function getSectionListFromWA($term, $division, $course, &$page)
{
    return getWAPage($term, $division, $course, $page);
}





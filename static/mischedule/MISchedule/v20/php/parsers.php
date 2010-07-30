<?

function parseWAPage($page, $tableNum)
{
    //Find the starting position of the certain <table> in the page
    $startPos = strpos ( $page, "<table", 0 );
    $tableCount = 1;
    while ($startPos && $tableCount < $tableNum)
    {
        $startPos = strpos ( $page, "<table", $startPos+1 );
        $tableCount++;
    }

    if (!$startPos)
    {
       // The table is not found!
       return FALSE;
    }

    //Now find the end of the table
    $endPos = strpos ( $page, "</table>", $startPos+1);

    //Trim all the junk before and after our table
    $page = substr($page, $startPos, $endPos - $startPos + 8);

    //This regular expression removes all tags except for opening <td> tags.
    $page = preg_replace("/(<td)[^>]*(>)|<[^>]*>/", "$1$2", $page); 
 
    //Get rid of all the white space junk we don't want
    $page = preg_replace("/\s*<td>\s*/", "<td>", $page); 

    //There could still be junk at the very end.  Trim it.
    $page = trim($page);

    //Get rid of all the &nbsp; crap
    $page = str_replace("&nbsp;", " ", $page);

    // split to get to the data we want  
    $matches = explode("<td>", "$page");
          
    return $matches;
}
?>
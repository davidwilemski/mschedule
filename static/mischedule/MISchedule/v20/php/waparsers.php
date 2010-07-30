<?
include "../include/wafunctions.php";
include "../include/dbfunctions.php";


$start_time = time();

$results=requestDivisionList();

$request_time = time();

$split_results = preg_split("/<[^>]*>/", $results);

$split_time = time();

$dbh=mysql_connect("localhost", "mischedu_admin", "password") or die ("can't connect");
mysql_select_db("mischedu_winter02");
$query = "DELETE FROM divisions";
mysql_query($query);

$delete_time = time();


$counter = 0;
$field = 0;
$beginning_junk = 100;
$end_junk = 41;
foreach ($split_results as $i)
{
	$counter++;
        
	$i = trim($i);        
	if (strlen($i) > 1) 
        {
            if (($counter > $beginning_junk) && (sizeof($split_results) - $counter > $end_junk) ) {
		//print "$counter<BR>\n";        
		if ($field % 2 == 0) 
		{
			$abbrev = $i;
		}
		else
		{
			$query = "INSERT INTO divisions (abbrev, fullname) VALUES ('$abbrev', '$i')";
			mysql_query($query) or die($query);
		}
		//print "$i<BR>\n";    
		$field++;    
            }
        }
}
//print sizeof($split_results);

$process_time = time();

printf("%d divisions added <BR>", $field/2);
printf("request took %d seconds <BR>", $request_time - $start_time);
printf("split took %d seconds <BR>", $split_time - $request_time);
printf("delete took %d seconds <BR>", $delete_time - $split_time);
printf("processing took %d seconds <BR>", $process_time - $delete_time);
















<?
include_once 'inc/common.php';
include_once 'inc/accesscontrol.php';
include_once 'inc/showschedule.php';
include_once 'inc/miltime.php';
include_once 'inc/colors.php';

//have to go backwards in order to get TH to replace before T
$days_in_week = array('F' => 'Friday', 'TH' => 'Thursday', 'W' => 'Wednesday', 'T' => 'Tuesday', 'M' => 'Monday');
$earliest_time = 2400;
$latest_time = 0;
$row_interval = 30;

getdata(array('uniqnames'), 'get');

//$view = $_GET['view'];

$uniqname_array = array();

//defaults to all registered users uniqnames
/*
if($uniqnames == ''){
	$result = sql("SELECT DISTINCT uniqname FROM $users");
	while($myrow = mysql_fetch_row($result)){
		array_push($uniqname_array, $myrow[0]);
	}
}else
*/
{
	$uniqname_array = explode(' ', $uniqnames);
}

$main_schedule_array = array();

//needs to be here for display of uniqnames above schedule
//otherwise I would have put it further down the page
showhtmlhead("Group Scheduling");

foreach($uniqname_array as $uniqname){
	//uniqname display on top of schedule
	
	
	$user_schedule_array = array();
	$result = sql("SELECT t2.classid, t2.dept, t2.number, t2.section, t2.type, t2.days, t2.time, t2.location, t2.instructor "
			. " FROM `$user_class` as t1, `$classes` as t2 "
	        . " WHERE t1.uniqname = '$uniqname' AND "
	        . " t1.classid = t2.classid");
	while($myrow = mysql_fetch_assoc($result)){
		$days = $myrow['days'];
		$time = $myrow['time'];
		//converttime($time, $start_time, $end_time, $null);
		if(!converttime($time, $start_time, $end_time, $null)){
			continue;
		}
		
		//stretches schedule to fit
		if($start_time < $earliest_time){
			$earliest_time = $start_time;
		}
		if($end_time > $latest_time){
			$latest_time = $end_time;
		}
		
		
		for($cur_time = $start_time; $cur_time < $end_time; $cur_time = miltimeadd($cur_time,$row_interval)){
			$temp = $days; // a copy of days so we can use $days later
			//echo '2';
			//var_dump($day_strings);
			//have to go backwards in order to get TH to replace before T
			foreach($days_in_week as $key => $value){
			//for($i = 6; $i >= 0; $i--){
				//$key = $i;
				//$value = $days_in_week[$i];
				//echo '3';
				if(stristr($temp, $key)){
					//echo $key." => ".$cur_time."<br>\n";
					$user_schedule_array[$key][$cur_time] = true;
					//echo $html.$interval;
					$temp = str_replace($value, '', $temp);
				}
			}
		}
	}
	//echo "<pre>";
	//var_dump($user_schedule_array);
	//echo "</pre>";
	//copy uniqname's array to main array (if true, add one)
	foreach($user_schedule_array as $day => $value){
		foreach($value as $time => $check_mark){
			if($check_mark){
				$main_schedule_array[$day][$time]++;
			}
		}
	}
	 
}

?>
<p>
Enter uniqnames below separtated by a space. The table will show you
how many people, out of those you enter below, have a class during that time interval.
This will only work correctly if the uniqnames are registered, and their
classes are in the system.
<form method="get"  autocomplete="off" action="<?=$_SEVER['PHP_SELF']?>">
<input name="uniqnames" type="text" value="<?=$uniqnames?>" size="50" /> <br>
<input type="submit" name="submit" value="View" />
</form>
</p>
<?


if($uniqnames == ''){
	//echo "(Default shows all registered users)";
}
?>
<table border=1>
<tr>
<th> </th>
<?
//again, need to be reversed so that it gets the right order
$days_in_week_reverse = array_reverse($days_in_week, false);
foreach($days_in_week_reverse as $value){
	echo "<th>$value</th>";
}
echo "</tr>\n";
for($i = $earliest_time; $i < $latest_time; $i = miltimeadd($i,$row_interval)){
	echo "<tr>";
	echo "<td>".formattime($i)."</td>";
	foreach($days_in_week_reverse as $key => $value){
		//echo $key.$value.$i."<br>\n";
		echo "<td> {$main_schedule_array[$key][$i]}</td>";
	}
	echo "</tr>\n";
}

?>
</table>
<?
showhtmlfoot();
?>

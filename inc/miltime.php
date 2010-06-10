<?php
//functions for dealing with military time

//formats military time to look good in "normal" time
function formattime($cur_time){
	return date("g:ia", strtotime(substr($cur_time, 0, strlen($cur_time) - 2).":".substr($cur_time, -2, 2)));
}


function regulartomil($value, $last_time = 2400){
	echo $value;
	if(strstr($value, 'PM')){
		$value = str_replace('PM', '', $value);
		$is_pm = true;
		echo "true";
	}else{
		$is_pm = false;
		echo "false";
	}

	if(strlen($value) < 3){
		$value *= 100;
	}
	echo "et:$last_time";
	if($is_pm or ($value <= ($last_time - 1200))){
		echo "pm";
		$value += 1200;
	}
	return $value;
}

//converts a military time into minutes after midnight.... I think, don't quote me on this
function tominutes($time){
	//echo "tominutes($time):";
	$hours = ($time - ($time % 100)) / 100;
	$mins = $time % 100;
	//echo $hours.' '.$mins.' ';
	return (60 * $hours) + $mins;
	//echo "<br>\n";
}

//adds two military times together if that's possible. I claim it is... hehe
function miltimeadd($time1, $time2){
	$time1_mins = tominutes($time1);
	$time2_mins = tominutes($time2);
	$total_minutes = $time1_mins + $time2_mins;
	$minutes = $total_minutes % 60;
	$hours = ($total_minutes - ($total_minutes % 60)) / 60;
	return $hours * 100 + $minutes;
}

//returns the absolute difference between two military times in minutes
function timediff($time1, $time2){
	//$time1 = (int)$time1;
	//$time2 = (int)$time2;
	//echo $time1." ".$time2;
	//force time2 to be greater than or equal to time1
	if($time1 > $time2){
		$temp = $time1;
		$time1 = $time2;
		$time2 = $temp;
	}
	$hours1 = (int) ($time1 / 100);
	$hours2 = (int) ($time2 / 100);
	$minutes1 = $time1 % 100;
	$minutes2 = $time2 % 100;
	
	$hoursdiff = $hours2 - $hours1;
	$minutesdiff = $minutes2 - $minutes1;
//note, you could just change the 60 to 100 to get it in military time
	return $hoursdiff * 60 + $minutesdiff;
}
?>
<?php
/*
	This should be used with the following variables:
		time_denom (default: 30min)
		schedule_data

*/

// Do some quick math, create an array
$box_per_hour = 60 / $time_denom;

$weekdays = array(
	'Sunday',
	'Monday',
	'Tuesday',
	'Wednesday',
	'Thursday',
	'Friday',
	'Saturday'
);

//print_r($schedule_data);
// We only need the first one for now
$s = $schedule_data[0];
print_r($s);

$master = array();

for($i = 0; $i < 24; $i++) {
	for($j = 0; $j < $box_per_hour; $j++) {

		$master[$i][$j] = array();

		foreach($weekdays as $weekday) {
		
			$master[$i][$j][$weekday] = ''; //$i . ' ' . $j . ' ' . $weekday;
		
		}
	}
}

foreach($s as $c) {

	$days = explode(',', $c['days'][0]);

	for($i = 0; $i < count($days); $i++) {
	
		$start_key = -1;
		$start_key_minor = -1;
		$end_key = -1;
		$end_key_minor = -1;
		
		if(isset($days[$i]))
			$day = $days[$i];
		else
			$day = $days[0];
			
		if(isset($c['time'][$i]))
			$time = $c['time'][$i];
		else
			$time = $c['time'][0];
		$time = explode('-', $time);
		
		// Work on the start time
		if($time[0] % 100 == 0) {
			// Then the start time is on the hour mark
			$start_key = $time[0] / 100;
			$start_key_minor = 0;
		} else {
			$start_key_minor = 0;
			while($time[0] % 100 != 0) {
				$time[0] -= $time_denom;
				$start_key_minor++;
			}
			$start_key = $time[0] / 100;
		}
		
		// Work on the end time
		if($time[1] % 100 == 0) {
			// Then the start time is on the hour mark
			$end_key = $time[1] / 100;
			$end_key_minor = 0;
		} else {
			$end_key_minor = 0;
			while($time[1] % 100 != 0) {
				$time[1] -= $time_denom;
				$end_key_minor++;
			}
			$end_key = $time[1] / 100;
		}
		
		$day_of_week = '';
		if($day == 'SU')
			$day_of_week = 'Sunday';
		if($day == 'M')
			$day_of_week = 'Monday';
		if($day == 'TU')
			$day_of_week = 'Tuesday';
		if($day == 'W')
			$day_of_week = 'Wednesday';
		if($day == 'TH')
			$day_of_week = 'Thursday';
		if($day == 'F')
			$day_of_week = 'Friday';
		if($day == 'SA')
			$day_of_week = 'Saturday';
			
		echo $day . ' ' . $end_key_minor . "<br />";
			
		$begin = false;
		for($start_key; $start_key <= $end_key; $start_key++) {
		
			// If we are starting off
			if(!$begin) {
				$begin = true;
				for($start_key_minor; $start_key_minor < $box_per_hour; $start_key_minor++) {
					$master[$start_key][$start_key_minor][$day_of_week] = 'BUSY';
				}
			}
			// If we are not quite to the end yet.
			if($start_key != $end_key) {
				for($j = 0; $j < $box_per_hour; $j++)
					$master[$start_key][$j][$day_of_week] = 'BUSY';
			}
			// If we are at the end
			if($start_key == $end_key) {
				//if($end_key_minor == 0) {
					for($j = 0; $j < $end_key_minor; $j++) {
						$master[$start_key][$j][$day_of_week] = 'BUSY';
					}
				//}
			}
		}
	
	}

}

$day = mktime(0, 0);
echo $time_denom;
?>
<table border="1">
<tbody>
<tr><td>Times</td><td>Sunday</td><td>Monday</td><td>Tuesday</td><td>Wednesday</td><td>Thursday</td><td>Friday</td><td>Saturday</td></tr>
<?php foreach($master as $hour) { ?>
<?php foreach($hour as $hour_part) { ?>
<tr>
<td>
<?=date("H:i", $day)?>
<?php $day += $time_denom * 60; ?>
</td>
<?php foreach($hour_part as $weekday) { ?>
<td>
<?=$weekday?>
</td>
<?php } ?>
</tr>
<?php } ?>
<?php } ?>
</tbody>
</table>
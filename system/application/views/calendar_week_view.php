<?php
/*
	This should be used with the following variables:
		time_denom (default: 30min)

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

$master = array();

for($i = 0; $i < 24; $i++) {
	for($j = 0; $j < $box_per_hour; $j++) {

		$master[$i][$j] = array();

		foreach($weekdays as $weekday) {
		
			$master[$i][$j][$weekday] = 'nothing';
		
		}
	}
}

$day = mktime(0, 0);
echo $time_denom;
?>
<table border="1">
<tbody>
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
<?php
	/*
		Model for working with the time prefrances
	*/
?>
<?php
class class_model extends Model {

	$early_schedule = array(
		'M' => array(0730, 1400);
		'TU' => array(0730, 1400);
		'W' => array(0730, 1400);
		'TH' => array(0730, 1400);
		'F' => array(0730, 1400);
	);
	
	$evening_schedule = array(
		'M' => array(1100, 1700);
		'TU' => array(1100, 1700);
		'W' => array(1100, 1700);
		'TH' => array(1100, 1700);
		'F' => array(1100, 1700);
	);
	
	$friday_schedule = array(
		'M' => array(0900, 1700);
		'TU' => array(0900, 1700);
		'W' => array(0900, 1700);
		'TH' => array(0900, 1700);
		'F' => array(0000, 0000);
	);
	
	function checkTime($options = array()) {
	
		$check = array();
		switch($options['time_pref']) {
			case 0:
				$check = $early_schedule;
				break;
			case 1:
				$check = $evening_schedule;
				break;
			case 2:
				$check = $friday_schedule;
				break;
			default:
				return 0;
		}
		
		$day = $options['day'];
		$time = $options['time'];
		if(!preg_match(',', $day)) {
			if(!preg_match(';', $time)) {
				$this_time = split('-', $time);
				if($check[$day][0] > $this_time[0]) {
					if($check[$day][0] - $this_time[0] > 100) {
						return 1;
					} else {
						return 3;
					}
				} else if($check[$day][0] < $this_time[0] && $check[$day][1] > $this_time[0]) {
					return 5;
				} else if($this_time[0] - $check[$day][2] > 100){
					return 1;
				} else {
					return 3;
				}
			} else {
				return 0;
			}
		} else {
			return 0;
		}
		
		return 0;
	}
}
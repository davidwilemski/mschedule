<?php
	/*
		Model for working with the time prefrances
	*/
?>
<?php
class time_pref_model extends CI_Model {
	
	function checkTime($options = array()) {
	
		$early_schedule = array(
			'M' => array(0730, 1400),
			'TU' => array(0730, 1400),
			'W' => array(0730, 1400),
			'TH' => array(0730, 1400),
			'F' => array(0730, 1400)
		);
		
		$evening_schedule = array(
			'M' => array(1100, 1700),
			'TU' => array(1100, 1700),
			'W' => array(1100, 1700),
			'TH' => array(1100, 1700),
			'F' => array(1100, 1700)
		);
		
		$friday_schedule = array(
			'M' => array(0900, 1700),
			'TU' => array(0900, 1700),
			'W' => array(0900, 1700),
			'TH' => array(0900, 1700),
			'F' => array(0000, 0000)
		);
	
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
		
		$days = $options['day'];
		if(strpos($days, ';'))
			$days = preg_split('/;/', $days);
		else
			$days = array($days);
		foreach($days as &$day) {
			if(strpos($day, ','))
				$day = preg_split('/,/', $day);
			else
				$day = array($day);
		}

		$time = $options['time'];
		if(strpos($time, ';'))
			$time = preg_split('/;/', $time);
		else
			$time = array($time);

		$sum = 0;
		foreach($days as $day) {
			$num = 0;
			foreach($day as $d) {
				$this_time = preg_split('/-/', $time[$num]);
				$mean = ($check[$d][1] + $check[$d][0])/2;
				$this_mean = ($this_time[1] + $this_time[0])/2;
				$sum += sqrt(abs($mean-$this_mean));
			}
			$num++;
		}
		return $sum;

	}
}

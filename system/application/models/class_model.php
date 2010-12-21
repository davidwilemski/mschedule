<?php
	/*
		Model for working with student schedules
		importClasses() - takes the userID and the array of classIDs and adds them to the db
		addRow() - adds a row to the database with parameters set in $options
		getClasses() - gets all classes for a userID
		
		getClassIDList() - returns all class IDs or if DEPT terms are passed as values of an
						   array, it will return just those DEPT classIDs
		getMasterDepartmentList() - returns an array ready for CI's table helper to show DEPTs
		getClassSections() - must recieve a vector in the order of: dept, number
	*/
?>
<?php
class class_model extends Model {

	function _required($required, $data) {
		
		// checks for required fields
		foreach($required as $field) {
			
			if(!isset($data[$field]))
				return false;
			
		}
		return true;
		
	}

	function saveSchedule($options = array()) {
		$uid = $this->session->userdata('userID');
		
		$scheduleID = sha1($uid + $options);
		$this->db->where('scheduleID', $scheduleID);
		$this->db->from('user_class');
		$q = $this->db->get();
		if($q->num_rows() > 0)
			return false;
		
		$this->db->where('userID', $uid);
		$this->db->from('user_prefs');
		$q = $this->db->get();
		$q = $q->result_array();
		// $options is the class ids formatted as 123456;123456;123456;
		// note the ending semicolin
		if(count($q) == 1) {
			if($q[0]['curr_schedule'] == '') {
				$this->db->where('userID', $uid);
				$this->db->set('curr_schedule', $scheduleID);
				$this->db->update('user_prefs');
			}
		}

		$ids = preg_split("/;/", $options);
		unset($ids[count($ids)-1]);
		return $this->importClasses(array(
			'userID' => $uid,
			'class_list' => $ids,
			'scheduleID' => $scheduleID
		));
	}

	function importClasses($options = array()) {
		
		if(!$this->_required(array('userID', 'class_list'), $options))
			return false;
	
		foreach($options['class_list'] as $class) {
			if(!$this->addRow(array('scheduleID' => $options['scheduleID'], 'userID' => $options['userID'], 'classID' => $class, 'term' => $this->config->item('current_term'))))
				return false;
		}
		
		return true;
		
	}
	
	function addRow($options = array()) {
	
		$fields = array(
			'classID',
			'userID',
			'term',
			'scheduleID'
		);
		
		foreach($fields as $field) {
			if(isset($options[$field]))
				$this->db->set($field, $options[$field]);
		}
				
		// updates the table	
		$this->db->insert('user_class');
		
		// returns the affected rows, or false
		return $this->db->insert_id();
	
	}
	
	function getClasses($options = array()) {
	
		if(!$this->_required(array('userID'), $options))
			return false;
		
		$this->db->where('userID', $options['userID']);
		$this->db->where('term', $this->config->item('current_term'));
		
		
		if(isset($options['limit']) && isset($options['offset']))
			$this->db->limit($options['limit'], $options['offset']);
		else if(isset($options['limit']))
			$this->db->limit($options['limit']);
		
		if(isset($options['sortby']) && isset($options['sortdirection']))
			$this->db->order_by($options['sortby'], $options['sortdirection']);
			
		$this->db->from('user_class');
		$q = $this->db->get();
		
		return $q->result();
	
	}
	
	function getClassDetail($options = array()) {
	
		if(!$this->_required(array('classid'), $options))
			return false;
		
		$this->db->where('classid', $options['classid']);
			
		$this->db->from('classes_' . $this->config->item('current_term'));
		$q = $this->db->get();
		
		return $q->row(0);
	
	}
	
	function getClassIDList($options = array()) {
		
		$this->db->select('classid');
		
		foreach($options as $o) {
			$this->db->where('dept', $o);
		}
		
		$this->db->from('classes_' . $this->config->item('current_term'));
		
		$q = $this->db->get();
		
		$list = $q->result();
		
		$return = array();
		foreach($list as $l) {
			$return[$l->classid] = 1;
		}
		
		return $return;
		
	}
	
	function getMasterDepartmentList($options = array()) {
		
		$this->db->from('classes_' . $this->config->item('current_term'));
		$this->db->select('dept, department_names.full_name');
		$this->db->join('department_names', 'classes_winter11.dept = department_names.short_name');
		$this->db->order_by('dept', 'asc');
		
		$q = $this->db->get();
		
		$list = $q->result_array();
		
		$table = array();
		$junk = array();
		foreach($list as $l) {
			if(!isset($junk[$l['dept']])) {
				$table[] = array($l['dept'], $l['full_name']);
				$junk[$l['dept']] = 1;
			}
		}
		
		return $table;
	}
	
	function getDeptClassList($options = array()) {
		
		$this->db->select('dept, number, class_name, classid');
		$this->db->order_by('number', 'asc');
		
		foreach($options as $o) {
			$this->db->where('dept', $o);
		}
		
		$this->db->from('classes_' . $this->config->item('current_term'));
		
		$q = $this->db->get();
		
		$list = $q->result_array();
		
		$table = array();
		$junk = array();
		foreach($list as $l) {
			if(!isset($junk[$l['number']])) {
				$table[] = $l;
				$junk[$l['number']] = 1;
			}
		}
		
		return $table;
		
	}
	
	function getClassSections($options = array()) {
		
		//$this->db->select('dept, number, section');
		
		//$this->db->where('dept', 'AERO');
		//$this->db->where('number', '101');
		$this->db->where('dept', $options[0]);
		$this->db->where('number', $options[1]);
	
		//$this->db->order_by('section', 'asc');
		
		$this->db->from('classes_' . $this->config->item('current_term'));
		
		$q = $this->db->get();
		
		return $q->result_array();
	
	}
	
	function createCalendarWeek($data = array()) {
	// Because this is being called by the json api, we need to keep everything in one array
	// must be array(0 => 'schedule_data', 1 => 'time_denom')
	
		$s = explode(',', $data[0]);
		$time_denom = $data[1];
		
		// This function gets to return a string of html for displaying the schedule in a table.
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
				
					$master[$i][$j][$weekday] = ''; //$i . ' ' . $j . ' ' . $weekday;
				
				}
			}
		}
		
		//print_r($s);
		
		foreach($s as $class) {
		
			$c = $this->getClassDetail(array('classid' => $class));
			
			print_r($c);
			
			$days = explode(',', $c->days[0]);

			for($i = 0; $i < count($days); $i++) {
				
				$start_key = -1;
				$start_key_minor = -1;
				$end_key = -1;
				$end_key_minor = -1;
				
				if(isset($days[$i]))
					$day = $days[$i];
				else
					$day = $days[0];
					
				if(isset($c->time[$i]))
					$time = $c->time[$i];
				else
					$time = $c->time[0];
				$time = explode('-', $time);
				
				// Work on the start time
				if($time[0] % 100 == 0) {
					// Then the start time is on the hour mark
					$start_key = $time[0] / 100;
					$start_key_minor = 0;
				} else {
					$start_key_minor = 0;
					while($time[0] % 100 != 0) {
						print_r($time[0]);
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
					
				//echo $day . ' ' . $end_key_minor . "<br />";
					
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
		
		$HTML_STRING = '';
		$HTML_STRING .= '<table border="1">';
		$HTML_STRING .= '<tbody>';
		$HTML_STRING .= '<tr><td>Times</td><td>Sunday</td><td>Monday</td><td>Tuesday</td><td>Wednesday</td><td>Thursday</td><td>Friday</td><td>Saturday</td></tr>';
		foreach($master as $hour) {
			foreach($hour as $hour_part) {
				$HTML_STRING .= '<tr>';
				$HTML_STRING .= '<td>';
				$HTML_STRING .= date("H:i", $day);
				$day += $time_denom * 60;
				$HTML_STRING .= '</td>';
				foreach($hour_part as $weekday) {
					$HTML_STRING .= '<td>';
					$HTML_STRING .= $weekday;
					$HTML_STRING .= '</td>';
				}
				$HTML_STRING .= '</tr>';
			}
		}
		$HTML_STRING .= '</tbody>';
		$HTML_STRING .= '</table>';
		
		return $HTML_STRING;
	
	}
	
	function createSchedules($options = array()) {
	
		// Create the largest 4-d array I have ever seen.   <- True Statement - that is a monster
		// Then use it to create schedules.
		$CI =& get_instance();
		$CI->load->model('time_pref_model');
		$classes = array();
		$class_count = 0;
		$types_count = 0;
		$time_pref = -1;
		// $o is a classid
		$first = true;
		foreach($options as $o) {
			if($first) {
				$time_pref = $o;
				$first = false;
				continue;
			}	
			$c = $this->class_model->getClassDetail(array('classid'=>$o));
			if(!isset($classes[$c->dept . $c->number])) {
				$classes[$c->dept . $c->number] = array();
				$class_count++;
			}
			if(!isset( $classes[$c->dept . $c->number][$c->type] )) {
				$classes[$c->dept . $c->number][$c->type] = array();
				$types_count++;
			}
			$classes[$c->dept . $c->number][$c->type][$c->section] = array();
			if($c->days) {
				$c->days = preg_split('/","/', $c->days);
			}
			$classes[$c->dept . $c->number][$c->type][$c->section]['days'] = $c->days;
			if($c->time) {
				$c->time = preg_split('/","/', $c->time);
			}
			$classes[$c->dept . $c->number][$c->type][$c->section]['time'] = $c->time;
			$classes[$c->dept . $c->number][$c->type][$c->section]['classid'] = $c->classid;
			if($c->location) {
				$c->location = preg_split('/","/', $c->location);
			}
			$classes[$c->dept . $c->number][$c->type][$c->section]['location'] = $c->location;
			$classes[$c->dept . $c->number][$c->type][$c->section]['dept'] = $c->dept;
			$classes[$c->dept . $c->number][$c->type][$c->section]['number'] = $c->number;
			$classes[$c->dept . $c->number][$c->type][$c->section]['section'] = $c->section;
			$classes[$c->dept . $c->number][$c->type][$c->section]['type'] = $c->type;
		}
		
		/*
		 * This loop is to associate LABs, DISCs, and RESCs, and SEMs, with the appropriate LEC.
		 */
		foreach($classes as $c) {
			if(isset($c['LEC'])) {
				if(count($c['LEC']) > 1) {
					$j = 0;
					$go = 1;
					$diff = 0;
					$lec_sec = array();
					foreach($c['LEC'] as $s) {
						if($go < 3) {
							$math = ($s['section'] * pow(-1, $go));
							//echo $math . 'x';
							$diff = $diff + $math;
						}
						$go++;
						//if($go = 2)
						//	$go = 0;
						$lec_sec[$j] = $s['section'];
						$j++;
					}
					//echo $diff . '*';
					//print_r($lec_sec);
					if($diff >= 2) { // We don't need to associate things if the lectures are very close together
						$type_names = array('LAB', 'DISC', 'RESC', 'SEM');
						foreach($type_names as $type_name) {
							if(isset($c[$type_name])) {
								foreach($c[$type_name] as $z) {
									foreach($lec_sec as $l) {
										if( floor( $z['section'] / $l ) == 1 ) {
											$classes[$z['dept'] . $z['number']][$type_name][$z['section']]['assoc_lec'] = $l;
										} // if
									} // foreach
								} // foreach						
							} // if
						} // foreach
					} // if
				} // if
			} // if
		} // foreach
		
		//print_r($classes);
		$types = array();
		$place = array();
		$place_max = array();
		$k = 0;
		foreach($classes as $c) {
			foreach($c as $t) {
				$types[$k] = $t;
				$place[$k] = '0';
				$place_max[] = count($t);
				$k++;
			}
		}
		//print_r($place_max);
		
		$schedules_count = 1;
		foreach($place_max as $m) {
			$schedules_count = $schedules_count * $m;
		}
		
		//print_r($types);
		
		$schedules = array();
		$last_place = count($place) - 1;
		//print_r($place);
		//echo $schedules_count . '<br />';
		
		for($i = 0; $i < $schedules_count; $i++){ // This loops through the # of possible schedules
			//echo $i. '<br />';
			//print_r($place);
			$s = array();
			for($j = 0; $j <= $last_place; $j++) {
				$p = 0;
				foreach($types[$j] as $t) {
					if($p == $place[$j]) {
						$s[] = $t;
					}
					$p++;
				}
			}
			
			// do some checking of the schedule we just created ($s)
			$tests = true;
			$tests = $this->class_model->_check_section_assoc($s);
			if($tests)
				$tests = $this->class_model->_check_times($s);
			
			if($tests) {
				foreach($s as &$time_check) {
					//print_r($time_check);
					$score = $CI->time_pref_model->checkTime(
						array(
							'time_pref' => $time_pref, 
							'day' => $time_check['days'][0], 
							'time' => $time_check['time'][0]
						)
					);
					$time_check['score'] = $score;
				}
				$schedules[] = $s;
			}
			//print_r($s);	
			//echo 'count: ' . count($schedules) . '<br />';
			
			// increment the place holders so we can create the next one
			$go = false;
			//print_r($last_place);
			$place[$last_place]++;
			for($z = $last_place; $z >= 0; $z--) {
				//if($go == false) {
					if($place[$z] >= $place_max[$z]) { // If the place is not reached the last option
						if($schedules_count != ($i + 1) ) { // If we're not on the last one
							$place[$z] = 0;
							$place[$z - 1]++;
							//$go = true;
						}
					}
				//}
			}
			
			if(count($schedules) == 500)
				return $schedules;
			//echo count($schedules) . '<br />';
		}
		
		//echo $schedules_count . ' ' . count($schedules);
		
		return $schedules;
	
	}
	
	function _check_section_assoc($s = array()) {
		foreach($s as $c) {
			//print_r($c);
			if($c['type'] != 'LEC') {
				$d = $c['dept'];
				$n = $c['number'];
				//$id = $c['classid'];
				if(isset($c['assoc_lec']))
					$al = $c['assoc_lec'];
				else
					return true;
				foreach($s as $c2) {
					if($c2['type'] == 'LEC') {
						if($c2['dept'] == $d and $c2['number'] == $n and $c2['section'] != $al) {
							//echo 'broke <br />';
							//print_r($c2);
							//print_r($c);
							return false;
						}
					}
				}
			}
		}
		
		return true;
	}
	
	function _check_times($s = array()) {
	
		for($i = 0; $i < count($s) - 1; $i++) {
			//print_r($s[$i]['days']);
			$d1 = explode(',', $s[$i]['days'][0]);
			$d2 = explode(',', $s[$i]['days'][0]);
			//echo count($s[$i]['days']);
			if(count($d1) == 0) {
				// if this true, we only have one day for the first class
				if(count($d2) == 0) {
					// if this is true, the second class only has one day
					if($d1[0] == $d2[0]) {
						// if the days are the same, we need to check the time.
						return $this->class_model->_check_times_helper($s[$i]['time'], $s[$i+1]['time'], 0, 0);
					}
				} else {
					// we have multiple days to check against
					for($z = 0; $z < count($d1); $i++) {
						if($d1[0] == $d2[$z]) {
							// then the second only has one time
							if(!$this->class_model->_check_times_helper($s[$i]['time'], $s[$i+1]['time'], 0, $z)) {
								return false;
							}
						}
					}
				}
			} else {
				// we have multiple days for the first class
				if($d2 == 0) {
					// we only have one day to check against for the second one
					for($z = 0; $z < count($d1); $z++) {
						if($d1[$z] == $d1[0]) {
							if(!$this->class_model->_check_times_helper($s[$i]['time'], $s[$i+1]['time'], $z, 0)) {
								return false;
							}
						}
					}
				} else {
					// both have multiple days! fun stuff
					for($y = 0; $y < count($d1); $y++) {
						for($z = 0; $z < count($d2); $z++) {
							if($d1[$y] == $d2[$z]) {
								if(!$this->class_model->_check_times_helper($s[$i]['time'], $s[$i+1]['time'], $y, $z)) {
									return false;
								} 
							}
						}
					}
				}
			}
		}
	
		return true;
	
	}
	
	function _check_times_helper($c1, $c2, $i1, $i2) {
		
		//print_r($c1);
		//print_r($c2);
		
		if(count($c1 == 0)) {
			// then the second one has only one time set				
			$c1 = explode('-', $c1[0]);
		} else {
			$c1 = explode('-', $c1[$i1]);
		}	
		if(count($c2 == 0)) {
			// then the second one has only one time set
			$c2 = explode('-', $c2[0]);
		} else {
			// the second has more than one time
			$c2 = explode('-', $c2[$i2]);	
		}
		
		//print_r($c1);
		//echo ' --- ' ;
		//print_r($c2);
		
		if($c1[0] < $c2[0]) {
			if($c1[1] <= $c2[0]) {
				return true;
			} else {
				return false;
			}
		} else if($c1[0] > $c2[0]) {
			if($c2[1] <= $c1[0]) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	
	}
}

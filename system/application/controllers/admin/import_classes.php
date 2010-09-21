<?php
/*

*/

class import_classes extends Controller {

	function index() {
	
		$this->load->model('class_model');
	
		$courses = "http://www.ro.umich.edu/timesched/pdf/FA2010.csv";
		$term = "fall10";
		
		// Grab file
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $courses);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
		$cvs = curl_exec($ch);
		curl_close($ch);
		/*$cvs = 'Term,Session,Acad Group,Class Nbr,Subject,Catalog Nbr,Section,Course Title,Component,Codes,M,T,W,TH,F,S,SU,Time,Location,Instructor,
"Fall 2010","Regular Academic Session","Architecture & Urban Planning","10001","Architecture (ARCH)"," 201","001","Basic Drawing","LAB","P  W","M","","W","","","","","1130-230PM","1227 A&AB","Tierman, Smith",
"Fall 2010","Regular Academic Session","Architecture & Urban Planning","10003","Architecture (ARCH)"," 202","001","Graphic Commun","LAB","P  W","M","","W","","","","","11-2PM","B100 MLB","Quinn, Alcorn",
"Fall 2010","Regular Academic Session","Architecture & Urban Planning","10065","Architecture (ARCH)"," 211","001","CAD Fundamentals","LEC","P RW","M","","","","","","","1230-2PM","2104 A&AB","Bard, Tomova, Riikonen",
"Fall 2010","Regular Academic Session","Architecture & Urban Planning","","Architecture (ARCH)"," 211","","CAD Fundamentals","LEC","P RW","","","W","","F","","","1030-12PM","DC","",
"Fall 2010","Regular Academic Session","Architecture & Urban Planning","","Architecture (ARCH)"," 211","","CAD Fundamentals","LEC","P RW","","","W","","F","","","1030-12PM","ARR","",';*/
		$classes = preg_split('/\n/', $cvs);
		// Remove description line
		unset($classes[0]);
		
		// Loop through classes
		foreach($classes as $class) {
			if($class == null) continue;
			$fields = preg_split('/","/', $class);
			foreach($fields as $key => $field) {
				$fields[$key] = str_replace("'", "", $field);
				$fields[$key] = str_replace('"', "", $field);
			}
			$starttimeindex=0;
			$endtimeindex=0;
			$num = $fields[5];
			$name = $fields[7];
			$classnum = $fields[3];
			$location = $fields[18];
		        $instructor = $fields[19];
		        $section = $fields[6];
		        $sectype = $fields[8];
		
			$time = $fields[17];
			$mon = $fields[10];
			$tue = $fields[11];
			$wed = $fields[12];
			$thu = $fields[13];
			$fri = $fields[14];
			$sat = $fields[15];
			$sun = $fields[16];
			
			$time = explode('-', $time);
			/*
			Time is fickel. 

			1) If we see the second one to be 12PM, the only option for the first time should be AM, not PM.
			2) If the second one is AM, the only logical option is for the first time to be AM as well (class can't end before it begins, doesn't go through midnight)
			3) If the second one is 1230PM, the only option for PM in the first is 12. Otherwise it is AM
			4) If the second one is 1PM - 730PM
				a) if the first is 12 or 1230, it has to be PM
				b) if the hours position of the first is less than the second's, the first will be PM
				c) if the hours position of the first is greater than the second's, it will be AM
			5) If the second one is [8PM, 12AM)
				I hope to god you don't have a class that goes more than 8 hours, so we will assume it is PM.
			
			*/
			// 1230-2PM
			$newtime = 'NaT';
			$matches = array();
			if(count($time) == 2) {
				preg_match('/^[0-9]*/', $time[1], $matches);
				$temp_time = -1;
				if($matches[0]) {
					$temp_time = $this->_time_format($matches[0], 'PM');
				}
				if($time[1] == '12PM') {
					$newtime = $this->_time_format($time[0], 'AM') . '-1200';
				} else if(preg_match('/AM/', $time[1])) {
					$newtime = $this->_time_format($time[0], 'AM') . '-' . $this->_time_format($matches[0], 'AM');
				} else if($time[1] == '1230PM') {
					if($time[0] == '12') {
						$newtime = '1200-1230';
					} else {
						$newtime = $this->_time_format($time[0], 'AM') . '-1230';
					}
				} else if($temp_time >= 1300 and $temp_time <= 1930 and preg_match('/PM/', $time[1])) {
					if($time[0] == '12' or $time[0] == '1230') {
						$newtime = $this->_time_format($time[0], 'PM') . '-' . $this->_time_format($matches[0], 'PM');
					} else if($this->_time_format($time[0], 'PM') > $temp_time) {
						$newtime = $this->_time_format($time[0], 'AM') . '-' . $this->_time_format($matches[0], 'PM');
					} else {
						$newtime = $this->_time_format($time[0], 'PM') . '-' . $this->_time_format($matches[0], 'PM');
					}
				} else {
					$newtime = $newtime = $this->_time_format($time[0], 'PM') . '-' . $this->_time_format($matches[0], 'PM');
				}
			} else {
				$newtime = 'ARR';
			}
			
			$time = $newtime;
			
			$tue = preg_replace('/T/', 'TU', $tue);
			$days = $mon.$tue.$wed.$thu.$fri.$sat.$sun;
		
			//make the fields sql-friendly - automatically done by code-igniter		

			if ($fields[4] = preg_match('/([^\"]+?) \(([^\"]+?)\)/', $fields[4], $matches))
			{
				//if we have a legit course name/number add it to the db
				if($name != "" && $num != "" && $classnum != "")
				{
					$this->db->from("classes_$term");
					$this->db->where('classid', $classnum);
					$this->db->delete();
					
					$data = array(
						'classid' => $classnum,
						'dept' => $matches[2],
						'number' => $num,
						'section' => $section,
						'type' => $sectype,
						'days' => $days,
						'time' => $time,
						'location' => $location,
						'instructor' => $instructor,
						'class_name' => $name
					);
					$this->db->insert("classes_$term", $data);
					//print_r($this->class_model->getClassDetail(array('classid' => $classnum)));
					//sql("INSERT INTO classes_$term VALUES('$classnum','$matches[2]','$num','$section','','$sectype','$days','$time','$location','$instructor','$name') ON DUPLICATE KEY UPDATE location='$location', instructor='$instructor'");
				}
				else 
				{
					$classnum = $prevclassnum;
					$section = $prevsection;
					$this->db->from("classes_$term");
					$this->db->where('classid', $classnum);
					$q = $this->db->get();
					$data = $q->row_array(0);
					
					
					$this->db->from("classes_$term");
					$this->db->where('classid', $classnum);
					$this->db->delete();
					//print_r($data);
					
					//if we have a legit course name/number add it to the db
					//echo "argh" . $classnum . '</br>';
					if($name != "" && $num != "" && $classnum != "")
					{
						$data['days'] = $data['days'] . ';' . $days;
						$data['time'] = $data['time'] . ';' . $time;
						$data['location'] = $data['location'] . ';' . $location;
						
						$this->db->where('classid', $data['classid']);
						$this->db->insert("classes_$term", $data);
						
						//sql("DELETE FROM classes_$term WHERE classid = $classnum");
						//sql("INSERT INTO classes_$term VALUES('$classnum','$matches[2]','$num','$section','','$sectype','$days','$time','$location','$instructor') ON DUPLICATE KEY UPDATE location='$location', instructor='$instructor'");
					}
				}
			}
			$prevclassnum = $classnum;
			$prevsection = $section;
		
		}
	
	}
	
	function _time_format($time, $suffix) {
	
		$len = strlen($time);
		$time = $time * 1;
		$usetime = '';
		if($len == 1) {
			$usetime .= '0' . $time * 100;
		} else if($len == 2) {
			$usetime .= $time * 100;
		} else if($len == 3) {
			$usetime .= '0' . $time;
		} else if($len == 4) {
			$usetime .= $time;
		}
		if($suffix == 'PM' and $usetime < 1200) {
			$usetime = $usetime * 1;
			return $usetime + 1200;
		}
		return $usetime;
	
	}
	
	function test() {
	
		$this->db->select('classid')->from('classes_fall10');
		$q = $this->db->get();
		$data = $q->result_array();
		
		foreach($data as $d) {
		
			$this->db->select('classid')->where('classid', $d['classid'])->from('classes_fall10_test');
			$q = $this->db->get();
			if($q->num_rows() == 0) {
				echo $d['classid'] . '</br>';
			}
		
		}
	
	}

}
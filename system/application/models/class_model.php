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

	function importClasses($options = array()) {
	
		if(!$this->_required(array('userID', 'class_list'), $options))
			return false;
	
		foreach($options['class_list'] as $class) {
			if(!$this->addRow(array('userID' => $options['userID'], 'classID' => $class, 'term' => $this->config->item('current_term'))))
				return false;
		}
		
		return true;
		
	}
	
	function addRow($options = array()) {
	
		$fields = array(
			'classID',
			'userID',
			'term'
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
		$this->db->select('dept');
		$this->db->order_by('dept', 'asc');
		
		$q = $this->db->get();
		
		$list = $q->result_array();
		
		$table = array();
		$junk = array();
		foreach($list as $l) {
			if(!isset($junk[$l['dept']])) {
				$table[] = array($l['dept'], 'dept full name');
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
	
	function createSchedules($options = array()) {
	
		// Create the largest 4-d array I have ever seen.
		// Then use it to create schedules.
		$classes = array();
		// $o is a classid
		foreach($options as $o) {
			$c = $this->class_model->getClassDetail(array('classid'=>$o));
			if(!isset($classes[$c->dept . $c->number])) {
				$classes[$c->dept . $c->number] = array();
			}
			if(!isset( $classes[$c->dept . $c->number][$c->type] )) {
				$classes[$c->dept . $c->number][$c->type] = array();
			}
			$classes[$c->dept . $c->number][$c->type][$c->section] = array();
			$classes[$c->dept . $c->number][$c->type][$c->section]['days'] = $c->days;
			$classes[$c->dept . $c->number][$c->type][$c->section]['time'] = $c->time;
			$classes[$c->dept . $c->number][$c->type][$c->section]['id'] = $c->classid;
			$classes[$c->dept . $c->number][$c->type][$c->section]['location'] = $c->location;
		}
		
		print_r($m_sch);		
		return 1;
	
	}
}
<?php
	/*
		Model for working with student schedules
		importClasses() - takes the userID and the array of classIDs and adds them to the db
		addRow() - adds a row to the database with parameters set in $options
		getClasses() - gets all classes for a userID
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
			if(!$this->addRow(array('userID' => $options['userID'], 'classID' => $class)))
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
			
		$this->db->from('classes_fall10');
		$q = $this->db->get();
		
		return $q->row(0);
	
	}
	
	function getClassIDList() {
		
		$this->db->select('classid');
		
		$this->db->from('classes_fall10');
		
		$q = $this->db->get();
		
		$list = $q->result();
		
		$return = array();
		foreach($list as $l) {
			$return[$l->classid] = 1;
		}
		
		return $return;
		
	}
	
	function getMasterList() {
		
		$this->db->from('classes_fall10');
		$this->db->order_by('dept', 'asc');
		$this->db->order_by('number', 'asc');
		$this->db->order_by('section', 'asc');
		
		$q = $this->db->get();
		
		$list = $q->result_array();
		
		return $list;
		
	}

}
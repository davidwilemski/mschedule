<?php

class user_model extends Model {
	
	function _required($required, $data) {
		
		// checks for required fields
		foreach($required as $field) {
			
			if(!isset($data[$field]))
				return false;
			
		}
		return true;
		
	}
	
	function addUser($options = array()) {
		
		// makes sure the required fields are passed in
		if(!$this->_required(array('username', 'password', 'email', 'first_name', 'last_name'), $options))
			return false;
		
		// adds ['status'] = 'active' to $options
		$options = array_merge(array('status'=>'inactive'), $options);
		
		// adds ['activate_code'] = md5(email) to $options
		$options = array_merge(array('activate_code'=>md5($options['email'])), $options);
		
		// puts it into the table, $options must include all table fields
		$this->db->insert('users', $options);
		
		// returns the id of the new user or false
		return $this->db->insert_id();
			
	}
	
	function updateUser($options = array()) {
		
		// makes sure the required field is there
		if(!$this->_required(array('userID'), $options))
			return false;
			
		$this->db->where('userID', $options['userID']);
		
		// setter
		$fields = array(
			'email',
			'username',
			'first_name',
			'last_name',
			'status',
			'password',
			'userType'
		);
		
		foreach($fields as $field)
			if(isset($options[$field]))
				$this->db->set($field, $options[$field]);
		
		// updates the table	
		$this->db->update('users');
		
		// returns the affected rows, or false
		return $this->db->affected_rows();
	}
	
	function getUsers($options = array()) {
		
		$fields = array(
			'userID',
			'email',
			'username',
			'password',
			'activate_code',
			'status',
			'first_name',
			'last_name'
		);
		
		foreach($fields as $f)
			if(isset($options[$f]))
				$this->db->where($f, $options[$f]);
			
		if(isset($options['limit']) && isset($options['offset']))
			$this->db->limit($options['limit'], $options['offset']);
		else if(isset($options['limit']))
			$this->db->limit($options['limit']);
		
		if(isset($options['sortby']) && isset($options['sortdirection']))
			$this->db->order_by($options['sortby'], $options['sortdirection']);
			
		$this->db->from('users');
		$q = $this->db->get();
		
		if(isset($options['username']) || isset($options['userID']) || isset($options['email']) || isset($options['activate_code']))
			return $q->row(0);
			
		return $q->result();
	}
	
	function login($options = array()) {
		
		if(!$this->user_model->_required(array('username', 'password'), $options))
			return false;
			
		$user = $this->user_model->getUsers(array('username' => $options['username'], 'password' => md5($options['password'])));
		
		if(!$user) return false;
		
		$this->session->set_userdata('email', $user->email);
		$this->session->set_userdata('first_name', $user->first_name);
		$this->session->set_userdata('last_name', $user->last_name);
		$this->session->set_userdata('userType', $user->userType);
		$this->session->set_userdata('username', $user->username);
		$this->session->set_userdata('userID', $user->userID);
		
		return true;
		
	}
	
	function email_validation($u) {
	
		$user = $this->user_model->getUsers(array('email'=>$u['email']));
		
		$this->load->library('email');
		$this->load->helper('date');
		
		$name = $user->first_name . ' ' . $user->last_name;
		$email = $user->email;
		$message = 'Welcome to MSchedule.com, '. $user->first_name . '!' . "\n\n";
		$message .= 'To activate your account at MSchedule.com, please use the link below, or go to ' . base_url() . 'login/validate ';
		$message .= 'and enter your activation code (below).' . "\n\n";
		$message .= 'Link: ' . base_url() . 'login/validate/' . md5($email) . "\n";
		$message .= 'Activation code (if link above does not work): ' . md5($email) . "\n\n";
		$message .= 'Thank you, and enjoy MSchdule.com!';
		
		$this->email->set_newline("\r\n");
		$this->email->from('Webmaster <noreply@mschedule.com>');
		$this->email->to($email);
		$this->email->subject('Activate your MSchedule.com Account!');
		$this->email->message($message);
		
		$this->email->send();
	
	}
	
	function activate_account($code) {
		
		$user = $this->getUsers(array('activate_code'=>$code));
		
		if($user)
			if($this->updateUser(array('userID'=>$user->userID, 'status'=>'active')))
				return true;
		return false;
	
	}
	
	function secure($options = array()) {
		
		if(!$this->_required(array('userType'), $options))
			return false;
		
		$userType = $this->session->userdata('userType');
		
		
		if(is_array($options['userType'])) {
			foreach($options['userType'] as $optionUserType) {
				if($optionUserType == $userType)
					return true;
			}
		} else {
			
			if($userType == $options['userType'])
				return true;
			
		}
			
		return false;
	}
	
}
<?php

class user_model extends Model {
	
	function _required($required, $data) {
		
		foreach($required as $field) {
			
			if(!isset($data[$field]))
				return false;
			
		}
		return true;
		
	}
	
	function addUser($options = array()) {
		
		if(!$this->_required(array('username', 'password', 'email'), $options))
			return false;
		
		$options = array_merge(array('status', 'active'), $options);	
		
		$this->db->insert('users', $options);
		
		return $this->db->insert_id();
			
	}
	
	function updateUser($options = array()) {
		
		if(!$this->_required(array('userID'), $options))
			return false;
		
		if(isset($options['email']))
			$this->db->set('email', $options['email']);
		
		if(isset($options['password']))
			$this->db->set('password', md5($options['password']));
			
		if(isset($options['status']))
			$this->db->set('status', $options['status']);
			
		$this->db->update('users');
		
		return $this->db->affected_rows();
	}
	
	function getUsers($options = array()) {
		
		if(isset($options['userID']))
			$this->db->where('userID', $options['userID']);
		
		if(isset($options['email']))
			$this->db->where('email', $options['email']);
			
		if(isset($options['username']))
			$this->db->where('username', $options['username']);
			
		if(isset($options['password']))
			$this->db->where('password', $options['password']);
			
		if(isset($options['limit']) && isset($options['offset']))
			$this->db->limit($options['limit'], $options['offset']);
		else if(isset($options['limit']))
			$this->db->limit($options['limit']);
		
		if(isset($options['sortby']) && isset($options['sortdirection']))
			$this->db->order_by($options['sortby'], $options['sortdirection']);
			
		$this->db->from('users');
		$q = $this->db->get();
		
		if(isset($options['username']) || isset($options['userID']) || isset($options['email']))
			return $q->row(0);
			
		return $q->result();
	}
	
	function login($options = array()) {
		
		if(!$this->user_model->_required(array('username', 'password'), $options))
			return false;
			
		$user = $this->getUsers(array('username' => $options['username'], 'password' => md5($options['password'])));
		
		if(!$user) return false;
		
		$this->session->set_userdata('email', $user->email);
		$this->session->set_userdata('username', $user->username);
		$this->session->set_userdata('userID', $user->userID);
		
		return true;
		
	}
	
}
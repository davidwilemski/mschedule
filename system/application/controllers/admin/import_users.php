<?php

Class Import_Users extends Controller{
	function Import_Users(){
		parent::Controller();	
	}
	
	function index(){
		$this->load->database();
		//read users_old line by line, 
		//and insert new user in the users table 
		//for each user in old table
		//but add any missing info along the way
		//such as new user key, or migration data

		$this->db->select()->from('users_old');
		$q = $this->db->get();
		$result = $q->result();
		$this->load->helper('string');

		foreach($result as $r){

			$data = array(
				'username' => $r->uniqname,
				'first_name' => $r->fullname,
				'last_name' => '',
				'email' => $r->uniqname . '@umich.edu',
				'user_key' => random_string('unique'),
				'migrated' => -1,
				'password' => $r->password
			);
			$this->db->insert('users', $data); 
		}
		echo 'Import Complete!';

					
	}

}

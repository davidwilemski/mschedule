<?php
	/*
		This model contains functions for dealing with users.
		All the functions take an array with the field names as parameters (sans activate_account and secure).
		addUser() - adds a user to the table with status=>inactive and activation_code=>md5(email)
					returns the id of the new user, or false for an error
		updateUser() - updates a user.
					   requires the userID to be passed in
					   updates all other fields passed to it (send password as hash)
					   returns the number of affected rows (ideally 1 or 0, except for batch operations by admin)
		getUsers() - gets all users that match the parameters passed to the function
					 optional parameters:
					 	limit - max number of records to return
					 	offset - starting result record number
					 	sortby - field to sort by
					 	sortdirection - direction to sort (asc or desc)
					 returns user as a singular object if there is only one result
					 returns array of users if there is more than one result
		_hasMigrated() - returns true or false. True if the user's 
						password hash is in the SHA + salt format 
						and false if it is MD5'd
						private function - not to be used in public API
		login() - validates credentials and sets session=>userdata
				  requires username and password (as hash) to be passed
				  sets session=>userdata with username, first_name, last_name, userType, email, and userID
				  returns true if login successful
				  returns false if login unsuccessful
		email_validation() - sends the user an email with activation code
							 requires email to be passed in the array
		activate_account() - activates the user account, setting status=>active
							 requires a activation code passed in
							 returns true if activated, returns false if not
		secure() - function to help secure pages
				   requires a single value (i.e. 'admin') or array (i.e. array('admin', 'user')) passed in
				   returns true if session=>userdata['userType'] reflects a parameter in the argument passed in
				   returns false otherwise
				   user this in the construct of a controller along with a redirect to secure parts of the site				  
	*/
?>
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
		
		// adds ['user_key'] = unique string of length 32 to $options
		$this->load->helper('string');
		$options = array_merge(array('user_key'=>random_string('unique')), $options);
		
		// puts it into the table, $options must include all table fields
		$this->db->insert('users', $options);
		
		// returns the id of the new user or false
		$id = $this->db->insert_id();

		if(!$id)
			return false;

		$this->createPrefs($id);
		
		return $id;
	}

	function createPrefs($id) {
		
		$this->db->insert('user_prefs', array('userID' => $id));

		return;

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
			'userType',
			'activate_code',
			'migrated'
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

	//Returns migration code
	//0 for md5 hash of just password
	//-1 for OLD_PASSWORD hash of just password
	//1 for sha256 hash of user+pass
	function hasMigrated($options = array()){
		$this->db->select('migrated')->from('users')->where('username', $options['username']);
		$q = $this->db->get();
		$usr = $q->row();
		return $usr->migrated;
	}
	
	function login($options = array()) {
		
		if(!$this->user_model->_required(array('username', 'password'), $options))
			return false;

		$user = null;

		if($this->user_model->hasMigrated(array('username' => $options['username']))== 1){
			$user = $this->user_model->getUsers(array('username' => $options['username'], 'password' => hash('sha256', $options['username'] . $options['password'])));
		}	
		else if($this->user_model->hasMigrated(array('username' => $options['username']))== 0){
			//otherwise get user data, then if user data matches, migrate the password to the new hash (sha256(username + password))
			$user = $this->user_model->getUsers(array('username' => $options['username'], 'password' => (md5($options['password']))));
			if($user){//then migrate
				$options['password'] = hash('sha256', $user->username . $options['password']);
				$options['userID'] = $user->userID;
				$options['migrated'] = 1;
				$this->user_model->updateUser($options);
			}
		}
		else{ //otherwise we know to use the OLD_PASSWORD function - also migrate this
			$this->load->helper('mysqlcrypt');
			$user = $this->user_model->getUsers(array('username' => $options['username'], 'password' => $this->user_model->oldpass($options['password'])));
			if($user){//then migrate
				$options['password'] = hash('sha256', $user->username . $options['password']);
				$options['userID'] = $user->userID;
				$options['migrated'] = 1;
				$this->user_model->updateUser($options);

			}
		}
		
		if(!$user) return false;
		
		$this->session->set_userdata('email', $user->email);
		$this->session->set_userdata('first_name', $user->first_name);
		$this->session->set_userdata('last_name', $user->last_name);
		$this->session->set_userdata('userType', $user->userType);
		$this->session->set_userdata('username', $user->username);
		$this->session->set_userdata('userID', $user->userID);
		
		// Some housekeeping:
		// See if user prefs is in the database - this IS required.
		$this->db->from('user_prefs')->where('userID', $user->userID);
		$q = $this->db->get();
		if($q->num_rows() == '0') {
			// Then the user doesn't have a record in user_prefs and needs one
			$this->createPrefs($user->userID);
		}
		
		return true;
		
	}
	
	function email_validation($u = array()) {
	
		if(!$this->_required(array('email'), $u))
			return false;
	
		$user = $this->getUsers(array('email'=>$u['email']));
		
		$this->load->library('email');
		$this->load->helper('date');
		
		$name = $user->first_name . ' ' . $user->last_name;
		$email = $user->email;
		$message = 'Welcome to MSchedule.com, '. $user->first_name . '!' . "\n\n";
		$message .= 'To activate your account at MSchedule.com, please use this link: ';
		$message .= base_url() . 'login/validate/' . $u['activate_code'];
		$message .= "\n\n" . ' If you have trouble, use the link below and and enter your activation code.' . "\n\n";
		$message .= 'Link: ' . base_url() . 'login/validate' . "\n";
		$message .= 'Activation code (if link above does not work): ' . $u['activate_code'] . "\n\n";
		$message .= 'Thank you, and enjoy MSchdule.com!';
		
		$this->email->set_newline("\r\n");
		$this->email->from('Webmaster <noreply@mschedule.com>');
		$this->email->to($email);
		$this->email->subject('Activate your MSchedule.com Account!');
		$this->email->message($message);
		
		return $this->email->send();
	
	}
	
	function activate_account($options = array()) {
		
		if(!$this->_required(array('userID', 'status'), $options))
			return false;
			
		if($this->updateUser($options)) {
			$this->session->set_flashdata('resent', 'oops');
			return true;
		}
			
		$this->session->set_flashdata('resent', 'oops');
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
	
	function forgot_password($options = array()) {
		
		if(!$this->_required(array('userID'), $options))
			return false;
		
		$user = $this->getUsers($options);
		
		if($user) {
			$this->load->library('email');
			$this->load->helper('date');
			
			$name = $user->first_name . ' ' . $user->last_name;
			$email = $user->email;
			$code = md5($user->activate_code + microtime());
			
			$message = 'Greetings from MSchedule.com, '. $user->first_name . '!' . "\n\n";
			$message .= 'To re-activate your account and change your password at MSchedule.com, please use this link: ';
			$message .= base_url() . 'login/password_reset/' . $code;
			$message .= "\n\n" . ' If you have trouble, use the link below and and enter your activation code.' . "\n\n";
			$message .= 'Link: ' . base_url() . 'login/password_reset' . "\n";
			$message .= 'Activation code (if link above does not work): ' . $code . "\n\n";
			$message .= 'Thank you, and we hope you continue to use MSchdule.com!';
			
			$this->email->set_newline("\r\n");
			$this->email->from('Webmaster <noreply@mschedule.com>');
			$this->email->to($email);
			$this->email->subject('Your MSchedule.com Account Password Reset!');
			$this->email->message($message);
			
			if(!$this->email->send()) {
				$this->session->set_flashdata('email', 'Something went wrong. Sorry');
			}
			
			$this->updateUser(array('userID' => $user->userID, 'activate_code' => $code, 'status' => 'inactive'));
		} else {
			$this->session->set_flashdata('email', 'Something went wrong. Sorry');
		}
			
	}
	
	function oldpass($pass){
		$q = $this->db->query("SELECT OLD_PASSWORD('$pass') AS pass");
		$r = $q->row();
		return $r->pass;
	}


}

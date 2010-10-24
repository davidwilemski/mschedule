<?php

Class api_auth_model extends Model
{
	function api_auth_model()
	{
		parent::Model();
		
		$this->load->helper('string');
	}
	
	
	//Given an id, get the secret key for that application
	//Returns a string containing the secret_key
	function getSecretKey($appid)
	{
		$this->db->select('secret_key')->from('api_users')->where('id', $appid);
		
		$query = $this->db->get();
		
		if ($query->num_rows() > 0)
		{
		   $row = $query->row(); 
			
			return $row->secret_key;
		}
		
	}
	
	//Given a user id, get the user key for that user
	//Returns a string containing the user_key
	function getUserKey($uid)
	{
		$this->db->select('user_key')->from('users')->where('userID', $uid);
		
		$query = $this->db->get();
		
		if ($query->num_rows() > 0)
		{
		   	$row = $query->row(); 
			
			return $row->user_key;
		}
		
	}
	
	
	//Returns the last_call of the given appid
	function getLastCall($appid)
	{
		$this->db->select('last_call')->from('api_users')->where('id', $appid);
		
		$query = $this->db->get();
		
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			return $row->last_call;
		}	
		
	}

	
	//Check to make sure the recieved hash is correct 
	//Returns true if the hash is correct, else returns false
	//Build hash using hash_hmac with sha256
	//e.g. hash_hmac('sha256', 'user_key'.$nonce, 'secret_key')
	function verifyHash($recieved_hash, $app_id, $uid, $nonce)
	{
		$secret = $this->getSecretKey($app_id);
		$user = $this->getUserKey($uid);
		
		//echo 'uid: ' . $uid;
		
		//echo 'user: ' . $user . ' secret: ' . $secret;

		//Build hash based on info sent in request
		$server_hash = hash_hmac('sha256', $user.$nonce, $secret);
		
		//Compare hash to the one sent in the request
		if($server_hash == $recieved_hash)
		{
			return true;
		}
		
		return false;
		
	}
	
	//Verify the nonce is greater than the previous one sent.
	//Returns true if nonce > prev_nonce, else returns false
	function verifyNonce($app_id, $nonce)
	{
		if($nonce > $this->getLastCall($app_id))
		{
			return true;
		}
		return false;
	}
	

	
}

?>
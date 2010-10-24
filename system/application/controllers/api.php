<?php
	/*
		This acts as an interface for all mschedule models	
		
		The json() method requires arguments of the model and method required as well as
		post values of the nonce, application id, user id, and an array of any method arguments
	*/
?>
<?php

class Api extends Controller {
	
	function Api() {
		
		parent::controller();
		
		
		
	}
	
	//Function to check for correct API authentication before sending response
	//Steps: 
	/*
		0. Application sends request to api/json including model and method in the url. The post variables should be method arguments (in data), authentication hash, nonce, and application id.
		1. build HMAC-SHA1 hash where the key is the application (secret) key and data is user_key+nonce. Where nonce is some increasing number. (Microtime was suggested by Jake.)
		2. compare hashes together - if they match AND the nonce has not been used by the application before then allow then continue - otherwise return an authentication error
		3. record nonce used matched with application_id
		4. send the requested data as a response
	*/
	function _checkAuth($appid, $uid, $nonce, $sent_hash)
	{
		$this->load->model('admin/api_auth_model');
		
		if($this->api_auth_model->verifyHash($sent_hash, $appid, $uid, $nonce) && $this->api_auth_model->verifyNonce($appid, $nonce))
		{
			return true;
		}
		else 
		{
			return false;
		}
	}
	
	function json($model, $method)	
	{
		$sent_hash = $this->input->post('hash');
		$app_id = $this->input->post('appid');
		$uid = $this->input->post('uid');
		$nonce = $this->input->post('nonce');
		$data = $this->input->post('data');
		


		$this->load->helper('url');
		if($this->_checkAuth($app_id, $uid, $nonce, $sent_hash))
		{	
			$this->load->model($model);
			
			$output = $this->$model->$method($data);
			
			print_r(json_encode($output));

		}
		elseif(isset($_SERVER['HTTP_REFERER']))
		{
			//echo strpos($_SERVER['HTTP_REFERER'], 'localhost');
			 if(strpos($_SERVER['HTTP_REFERER'],'localhost') || strpos($_SERVER['HTTP_REFERER'],'mschedule.com')) {
			 	//echo 'hi';
				$this->load->model($model);
				
				$output = $this->$model->$method($data);
				
				print_r(json_encode($output));
			 }
		}
		else
		{
			echo "There was an error with authentication, please check your credentials and try again";
		}	


	}
	
	function test() {
	
		$this->load->model('class_model');
		$data = $this->class_model->createSchedules(
			array(
				'14785',
				'14787',
				'14789',
				'49025',
				'49026',
				'49027',
				'14795',
				'14797',
				'14799'/*,
				'11351',
				'11353',
				'11355',
				'11365',
				'11367',
				'11369'*/
			)
		);
		
		print_r(json_encode($data));
	
	}
	
	function create_user_keys()
	{
		echo 'p1';
		$this->load->helper('string');
		
		$this->db->select('*')->from('users');
		$query = $this->db->get();
		
		echo 'p2';


		
		if($query->num_rows() > 0)
		{
			
			foreach ($query->result() as $row)
			{
			  echo 'username: ' . $row->username . ' old key: ' . $row->user_key . ' new key: ';
			  $newkey = random_string('unique', 32);
			  echo $newkey . '<br />';
			  
			  $data = array('user_key'=> $newkey);
			  
			  $this->db->where('userID', $row->userID);
			  $this->db->update('users', $data);
			  
			}

		}

	}
	
	function create_new_api_key()
	{
		echo random_string('unique');
	}
	
	function test_hash()
	{
		//echo hash_hmac('sha256', '61dc0f2021aec1cfe9d08f1aaa141a58' . '99', '279f58556746bec11a507bf9d5e540a9');
		$this->load->model('admin/api_auth_model');
		
		echo $this->api_auth_model->getLastCall(1);
		//return false;
	}
	
}

?>
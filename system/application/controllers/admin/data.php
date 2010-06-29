<?php
/* 
	These methods will output the JSON data required for the admin pages (could be used elsewhere if needed... would just 
	have to adjust the authentication a bit...)
 */
 
 class Data extends Controller 
 {
	
	function Data() 
	{
		
		parent::controller();
		
		if(!$this->user_model->Secure(array('userType'=>'admin'))) 
		{
			$this->session->set_flashdata('flashError', 'You must be logged in as an admin to access this page.');
			$this->session->set_flashdata('flashRedirect', 'admin/data'); //why is this set to admin/dashboard?
			redirect('login');
		}
		
		$this->load->model('admin/admin_model');
		
				
 	}
	
 	
 	function index()
 	{
 		echo 'nothing here for now...';
 	}
 	
 	
 	
 	//Could later extend this (and the model) to specify how many items are wanted through a GET or POST or
 	//pass as a method function...hmm...
 	//I'm sure the same could go for all of these...
 	
 	//Returns JSON of 10 most recently updated pages
 	function updatedPages()
 	{
 		$pages = $this->admin_model->updatedPages();
 		print_r($pages);
 	}
 	
 	
 	//Outputs JSON of 10 most recently registered users
 	function newUsers()
 	{
 		$new_users = $this->admin_model->newUsers();
 		print_r($new_users);
 	}
 	
 	
 }
 
?>
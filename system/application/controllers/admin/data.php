<?php
/* 
	These methods will output the JSON data required for the admin pages (could be used elsewhere if needed... would just 
	have to adjust the authentication a bit...)
 */
?>
<?php
 
class Data extends Controller 
 {
	
	function __construct() 
	{
		
		parent::__construct();
		
		if(!$this->user_model->Secure(array('userType'=>'admin'))) 
		{
			//$this->session->set_flashdata('flashError', 'You must be logged in as an admin to access this page.');
			//$this->session->set_flashdata('flashRedirect', 'admin/data'); //why is this set to admin/dashboard?
			//redirect('login');
			return '{"error": "You are not authorized to request this information"}';
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
 		header('Content-Type: text/javascript');    
 		header('Cache-Control: no-cache');    
 		header('Pragma: no-cache');
 		
 		$pages = $this->admin_model->updatedPages();
 		print_r($pages);
 	}
 	
 	
 	//Outputs JSON of 10 most recently registered users
 	function newUsers()
 	{
 		header('Content-Type: text/javascript');    
 		header('Cache-Control: no-cache');    
 		header('Pragma: no-cache');
 		
 		$new_users = $this->admin_model->newUsers();
 		print_r($new_users);
 	}
 	
 	function recentActivity()
 	{
 		header('Content-Type: text/javascript');    
 		header('Cache-Control: no-cache');    
 		header('Pragma: no-cache');

 		echo '{"error": "This feature is not yet available"}';
 	}
 	
 	
 }
 
?>
<?php
	/*
		-secure- user, admin
		This controller is the main user interface for users to change their preferences.
		It loads the dashboard_view to show the password reset and personal info update forms.
		This has several custom form_validation callback functions:
		For the password reset:
			_check_user_password - validates the user's password agains the database hash
			_check_password - validates that the new password is typed correctly twice
			_check_same - validates that the user isn't changing the password to the same thing
		For the user information update:
			_check_email - checks that the user isn't changing the password to a currently registered
						   email, and if the user doesn't change their email (it's already registered
						   to them) it will allow the form to update the email with the same email.
	*/
?>
<?php

class dashboard extends CI_Controller {
	
	function __construct() {
		
		parent::__construct();
		if(!$this->user_model->Secure(array('userType'=>array('admin', 'user')))) {
		
			$this->session->set_flashdata('flashError', 'You must be logged in to access this page.');
			redirect('login');
		
		}
		
		
	}
	
	function index() {
		
		$data = array(
			'view_name'	=> 'dashboard_view',
			'navigation'=> "navigation",
			'css'		=> includeCSSFile("style"),
			'nav_data'	=> $this->nav_links_model->getNavBarLinks(),
			'nav_location' => 'dashboard'
		);
		
		$this->load->view('include/template', $data);
	}
}

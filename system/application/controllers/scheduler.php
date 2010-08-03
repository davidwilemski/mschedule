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

class scheduler extends Controller {
	
	function dashboard() {
		
		parent::controller();
		
		$this->load->library('form_validation');
		
		if(!$this->user_model->Secure(array('userType'=>array('admin', 'user')))) {
		
			$this->session->set_flashdata('flashError', 'You must be logged in to access this page.');
			redirect('login');
		
		}
		
		
	}
	
	function index($term = "f10") {
	
		$this->load->model('class_model');
		
		$data = array(
			'view_name'	=> 'mischedule_view',
			'ad'		=> 'static/ads/google_ad_120_234.php',
			'navigation'=> "navigation",
			'css'		=> includeCSSFile("style") . includeCSSFile("scheduler"),
			'nav_data'	=> $this->nav_links_model->getNavBarLinks(),
			'term' => $term
		);
		
		$data['page_data'] = array('master_list' => $this->class_model->getMasterList());
		
		$this->load->view('include/template', $data);
	}
	
}
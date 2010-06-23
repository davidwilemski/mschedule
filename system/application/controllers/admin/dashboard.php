<?php
	/*
		-secure- admin
		This controller is going to be the main admin interface for updating
		user information, static page content, and navigation links.
	*/
?>
<?php

class dashboard extends Controller {
	
	function dashboard() {
		
		parent::controller();
		
		if(!$this->user_model->Secure(array('userType'=>'admin'))) {
		
			$this->session->set_flashdata('flashError', 'You must be logged in as an admin to access this page.');
			$this->session->set_flashdata('flashRedirect', 'admin/dashboard');
			redirect('login');
		
		}
		
 	}
	
	function index() {
		
		$data = array(
			'view_name'	=> 'admin/dashboard_view',
			'navigation'=> "navigation",
			'css'		=> includeCSSFile("style"),
			'nav_data'	=> $this->nav_links_model->getNavBarLinks()
		);


		$this->load->view('include/template', $data);
	}
	
	function users() {
	
	
	
	}
	
	
	function pages() {
	
	
	
	}
	
	function nav_links() {
	
	
	
	}
	
}
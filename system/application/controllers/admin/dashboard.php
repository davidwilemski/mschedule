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
	
}
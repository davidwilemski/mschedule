<?php
	/*
		-secure- admin
		This controller is going to be the main admin interface for updating
		user information, static page content, and navigation links.
		
		This controller will handle the management of static pages and static page content as well as user profiles and 
		will later be integrated with the mschedule app giving the ability to manage user schedules and manually update the
		classes in our database.	
		
	*/
?>
<?php

		//This will be the management "dashboard" - show which pages have been recently updated and which users
		//have recently logged in and which are recently registered.
		//These will link to the pages that allow you to fully manage pages and users and so on.
		
class Dashboard extends CI_Controller {
	
	function __construct() {
		
		parent::__construct();
		
		if(!$this->user_model->Secure(array('userType'=>'admin'))) 
		{
			$this->session->set_flashdata('flashError', 'You must be logged in as an admin to access this page.');
			$this->session->set_flashdata('flashRedirect', 'admin/dashboard');
			redirect('login');
		}
		
		$this->load->model('admin/admin_model');
		
				
 	}
	
	//Index page
	//Should have panes 
	function index() {
		
		$data = array(
			'view_name'	=> 'admin/dashboard_view',
			'navigation'=> "navigation",
			'css'		=> includeCSSFile("admin_dashboard"),
			'nav_data'	=> $this->nav_links_model->getNavBarLinks()
		);
		
		$data['javascript'] = includeJSFile('jquery');
		$data['javascript'] .= includeJSFile('admin/dashboard');
		
		$this->load->view('include/template', $data);
	}
	
	function users() {
	
	
	
	}
	
	
	function pages() {
	
	
	
	}
	
	function nav_links() {
	
	
	
	}
	
	//outputs JSON that contains the most recently updated pages from static_pages including id, title, and date_modified
	function updatedPages()
	{
		$pages = $this->admin_model->updatedPages();
		print_r($pages);
	}
	
}
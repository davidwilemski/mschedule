<?php

class dashboard extends Controller {
	
	function dashboard() {
		
		parent::controller();
		
		$this->load->model('nav_links_model');
		
	}
	
	function index() {
		
		$data = array(
			'view_name'	=> 'dashboard_view',
			'ad'		=> 'static/ads/google_ad_120_234.php',
			'navigation'=> "navigation",
			'css'		=> includeCSSFile("style"),
			'nav_data'	=> $this->nav_links_model->getNavBarLinks()
		);
		
		$this->load->view('include/template', $data);
	}
	
}
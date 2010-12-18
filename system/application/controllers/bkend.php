<?php

class bkend extends Controller {
	
	function bkend() {
		
		parent::controller();
		$this->load->model('static_pages_model');
	}
	
	function index() {
	
		$data = array(
			'view_name' => "calendar_week_view",
			'navigation'=> "navigation"
		);
		
		$data['css'] = includeCSSFile("style");
		$data['nav_data'] = $this->nav_links_model->getNavBarLinks();
		$data['page_data'] = array('time_denom' => 30);	
		
		$this->load->view('include/template', $data);
	
	}
	
}
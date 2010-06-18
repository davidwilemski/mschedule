<?php

class Home extends Controller {
	
	function Home() {
		
		parent::controller();
		$this->load->helper("url");
		$this->load->helper("html");
		$this->load->helper("css");
		
		$this->load->model('static_pages_model');
	}
	
	function index($page = 'index') {		
		
		$data = array(
			'view_name' => "home_view",
			'ad'		=> "static/ads/google_ad_120_234.php",
			'navigation'=> "navigation"
		);
		
		$data['css'] = includeCSSFile("style");
		$data['nav_data'] = $this->static_pages_model->getNavBarLinks();
		$data['page_data'] = $this->static_pages_model->getPageContent($page);	
		
		$this->load->view('include/template', $data);
		
	}
	
}

?>
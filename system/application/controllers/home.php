<?php
	/*
		This controller loads the home page. Its contents are static.
	*/
?>
<?php

class Home extends Controller {
	
	function Home() {
		
		parent::controller();
		$this->load->model('static_pages_model');
	}
	
	function index($page = 'index') {		
		
		$data = array(
			'view_name' => "home_view",
			'ad'		=> "static/ads/google_ad_120_234.php",
			'navigation'=> "navigation"
		);
		
		$data['css'] = includeCSSFile("style");
		$data['nav_data'] = $this->nav_links_model->getNavBarLinks();
		$data['page_data'] = $this->static_pages_model->getPageContent($page);	
		
		$this->load->view('include/template', $data);
		
	}
	
}

?>
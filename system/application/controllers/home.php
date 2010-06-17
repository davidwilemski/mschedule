<?php

class Home extends Controller {
	
	function Home() {
		
		parent::controller();
		
	}
	
	function index() {		
		
		$data = array(
			'view_name' => "home_view",
			'ad'		=> "static/ads/google_ad_120_234.php",
			'navigation'=> "navigation"
		);
		$this->load->view('include/template', $data);
		
	}
	
}

?>
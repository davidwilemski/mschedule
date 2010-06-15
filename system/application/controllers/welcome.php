<?php

class Welcome extends Controller {
	
	function Welcome() {
		
		parent::controller();
		
	}
	
	function index() {		
		
		$data = array(
			'view_name' => "welcome_view",
			'ad'		=> "static/ads/google_ad_120_234.php"
		);
		$this->load->view('include/template', $data);
		
	}
	
}

?>
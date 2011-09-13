<?php
	/*
		This controller loads the home page. Its contents are static.
	*/
?>
<?php

class Home extends CI_Controller {
	
	function __construct() {
		
		parent::__construct();
		$this->load->model('static_pages_model');
	}
	
	function index($page = 'index') {		
		
		$data = array(
			'view_name' => "home_view",
            'navigation'=> "navigation",
		    'css' => includeCSSFile('tipsy'), 
		    'javascript' => includeJSFile('jquery.tipsy') . includeJSFile('signin'),
		    'nav_data' => $this->nav_links_model->getNavBarLinks(),
		    'page_data' => $this->static_pages_model->getPageContent($page)
		);
		
		
		$this->load->view('include/template', $data);
		
	}
	
}

?>

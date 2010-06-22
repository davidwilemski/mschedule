<?php

class dashboard extends Controller {
	
	function dashboard() {
		
		parent::controller();
		
	}
	
	function index() {
		
		$this->load->view('dashboard_view');
		
	}
	
}
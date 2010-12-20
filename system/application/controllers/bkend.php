<?php

class bkend extends Controller {
	
	function bkend() {
		
		parent::controller();
		$this->load->model('static_pages_model');
	}
	
	function index() {
	
		$this->load->model('class_model');
		
		$data = array();
		$data[] = 0;
		$data[] = 14678;
		$data[] = 14679;
		$data[] = 14680;
		$data[] = 14681;
		$data[] = 11541;
		$data[] = 11542;
		$data[] = 11543;
		$data[] = 11544;
		$data[] = 11545;
		$data[] = 11546;
		$data[] = 11547;
		$data[] = 11548;
		$data[] = 11549;
		$data[] = 21504;
		$data[] = 11550;
		$data[] = 20707;
		$data[] = 11551;
		$data[] = 11552;
		$data[] = 11553;
		$data[] = 11554;
		$data[] = 11555;
		$data[] = 11556;
		$data[] = 11557;
		$data[] = 11558;
		$data[] = 11559;
		$data[] = 11560;
		$data[] = 11561;
		$data[] = 11562;
		$data[] = 11563;
		$data[] = 11564;
		$data[] = 11565;
		$data[] = 11566;
		$data[] = 11567;
		$data[] = 11568;
		$data[] = 11569;
		$data[] = 11570;
		$data[] = 11571;
		$data[] = 11572;
		$data[] = 22725;
		$data[] = 22726;
		$data[] = 11573;
		$data[] = 11574;
		$data[] = 11575;
		$data[] = 11576;
		$data[] = 11577;
		$data[] = 11578;
		$data[] = 11579;
		$data[] = 11580;
		$data[] = 11581;
		$data[] = 11582;
		
		$class_data = $this->class_model->createSchedules($data);
	
		$data = array(
			'view_name' => "calendar_week_view",
			'navigation'=> "navigation"
		);
		
		$data['css'] = includeCSSFile("style");
		$data['nav_data'] = $this->nav_links_model->getNavBarLinks();
		$data['page_data'] = array('time_denom' => 30, 'schedule_data' => $class_data);	
		
		$this->load->view('include/template', $data);
	
	}
	
}
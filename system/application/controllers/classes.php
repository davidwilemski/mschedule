<?php
	/*
		This controller is for working with a user's class information.
		import() - loads view for importing classes by classID
		view() - loads view for showing classes
	*/
?>
<?php

class Classes extends controller {
	
	function Classes() {
		
		parent::controller();
		
		$this->load->library('form_validation');
		$this->load->model('class_model');
		
		if(!$this->user_model->Secure(array('userType'=>array('admin', 'user')))) {
		
			$this->session->set_flashdata('flashError', 'You must be logged in to access this page.');
			redirect('login');
		
		}
		
		$TERM = 'fall10';
		
	}
	
	function index() {
	
		
	
	}
	
	function import() {
	
		if($this->input->post('class_boxes')) {
			for($i = 1; $i <= $this->input->post('class_boxes'); $i++)
				$this->form_validation->set_rules('class' . $i, 'Class ' . $i, 'trim|callback__check_duplicates|callback__check_valid_class');
		}
		
		if($this->form_validation->run()) {
		
			$data = array();
			if($this->input->post('class_boxes')) {
				for($i = 1; $i <= $this->input->post('class_boxes'); $i++)
					if($this->input->post('class' . $i) != '')
						$data[$i] = $this->input->post('class' . $i);
			}
			
			if(!$this->class_model->importClasses(array('userID' => $this->session->userdata('userID'), 'class_list' => $data)))
				$this->session->set_flashdata('error', 'Something went wrong.');
			
			redirect('classes/view');
		}
	
		$data = array(
			'view_name'	=> 'class/import_view',
			'ad'		=> 'static/ads/google_ad_120_234.php',
			'navigation'=> "navigation",
			'css'		=> includeCSSFile("style"),
			'nav_data'	=> $this->nav_links_model->getNavBarLinks()
		);
		
		$data['javascript'] = includeJSFile('jquery');
		$data['javascript'] .= includeJSFile('class_view');
		
		$this->load->view('include/template', $data);
	
	}
	
	function _check_duplicates($class) {
	
		$this->form_validation->set_message('_check_duplicates', $class . ' has a duplicate here. Please fix and try, try again.');
		
		$count = 0;
		
		if(!$class) {
			return true;
		}
		
		for($i = 1; $i <= $this->input->post('class_boxes'); $i++) {
		
			if($this->input->post('class' . $i) == $class)
				$count++;
		
		}
		
		if($count > 1)
			return false;
			
		return true;
	}
	
	function _check_valid_class($class) {
	
		$this->form_validation->set_message('_check_valid_class', $class . ' is not a valid class ID. Please fix and try, try again.');
		
		$list = $this->class_model->getClassIDList();
		
		for($i = 1; $i <= $this->input->post('class_boxes'); $i++) {
		
			if($this->input->post('class' . $i) == $class) {
				
				if(isset($list[$class])) {
					return true;
				}
			
			} 
		
		}
		
		return false;
	
	}
	
	function view() {
	
		$classes = $this->class_model->getClasses(array('userID' => $this->session->userdata('userID')));
		
		$info = array();
		foreach($classes as $class) {
			$info[] = $this->class_model->getClassDetail(array('classid' => $class->classID));
		}
		
		$data = array(
			'view_name'	=> 'class/class_view',
			'ad'		=> 'static/ads/google_ad_120_234.php',
			'navigation'=> "navigation",
			'css'		=> includeCSSFile("style"),
			'nav_data'	=> $this->nav_links_model->getNavBarLinks(),
			'page_data'	=> $info
		);
		
		$this->load->view('include/template', $data);
	
	}
	
}
<?php
	/*
		This controller is for working with a user's class information.
		import() - loads view for importing classes by classID
		view() - loads view for showing classes
	*/
?>
<?php

class Classes extends CI_Controller {
	
	function __construct() {
		
		parent::__construct();
		
		$this->load->library('form_validation');
		$this->load->model('class_model');
		
		if(!$this->user_model->Secure(array('userType'=>array('admin', 'user')))) {
		
			$this->session->set_flashdata('flashError', 'You must be logged in to access this page.');
			redirect('login');
		
		}
		
	}
	
	function index() {
	
		redirect('classes/view');

	}
	
	function view() {
	
		$classes = $this->class_model->getUserClassSchedule(array('userID' => $this->session->userdata('userID')));
		
		$data = array(
			'view_name'	=> 'class/class_view',
			'navigation'=> "navigation",
			'nav_data'	=> $this->nav_links_model->getNavBarLinks(),
			'page_data'	=> array('user_classes' => $classes),
			'javascript'=> includeJSFile("jquery") . /*includeJSFile("class/display_user_schedule") .*/ includeJSFile("schedule_view_maker") . includeJSFile("jquery.bubblepopup.v2.3.1.min")
		);
		
		$this->load->view('include/template', $data);
	
	}
	
	function import() {
	
		// This sets the rules for every box that is there and currently created
		if($this->input->post('class_boxes')) {
			for($i = 1; $i <= $this->input->post('class_boxes'); $i++)
				$this->form_validation->set_rules('class' . $i, 'Class ' . $i, 'trim|callback__check_duplicates|callback__check_valid_class|callback__check_inDB');
		}
		
		if($this->form_validation->run()) {
		
			$data = array();
			
			$schedule_id = $this->class_model->getUserCurrSchedulePref(array('userID' => $this->session->userdata('userID')));
			if(!$schedule_id) {
				// Then the user doesn't have a record in user_prefs and needs one
				$this->db->insert('user_prefs', array('userID' => $this->session->userdata('userID'), 'curr_schedule' => 'coming'));
			}
			
			if($this->input->post("save_type") == "new") {
				// save a brand new schedule
				$schedule_id = "";
			} else {
				// remove the current curr_schedule, and add the new one starting with the curr schedule id
				
				$this->db->delete('user_class', array('scheduleID' => $schedule_id));
				$data = explode(";", $this->input->post('curr_schedule_string'));
				unset($data[count($data)-1]);
			}
						
			if($this->input->post('class_boxes')) {
				for($i = 1; $i <= $this->input->post('class_boxes'); $i++) {
					if($this->input->post('class' . $i) != '') {
						$data[] = $this->input->post('class' . $i);
						$schedule_id .= $this->input->post('class' . $i);
					}
				}
			}
			
			$import_info = array('scheduleID' => $schedule_id, 'userID' => $this->session->userdata('userID'), 'class_list' => $data);
			
			if(!$this->class_model->importClasses($import_info))
				$this->session->set_flashdata('error', 'Something went wrong.');
			
			redirect('classes/view');
		}
		
		$user_classes = $this->class_model->getUserClassSchedule(array('userID' => $this->session->userdata('userID')));
		$user_curr_schedule_id = $this->class_model->getUserCurrSchedulePref(array('userID' => $this->session->userdata('userID')));
	
		$data = array(
			'view_name'	=> 'class/import_view',
			'navigation'=> "navigation",
			'css'		=> includeCSSFile("style"),
			'javascript'=> includeJSFile('jquery') . includeJSFile('class_view'),
			'nav_data'	=> $this->nav_links_model->getNavBarLinks(),
			'page_data' => array('user_classes' => $user_classes, 'curr_schedule' => $user_curr_schedule_id)
		);
		
		$this->load->view('include/template', $data);
	
	}
	
	function _check_inDB($class) {
		
		if($this->input->post("save_type") == "new")
			return true;
		
		$go = true;
		if($class) {
			$classes = $this->class_model->getClasses(array('userID' => $this->session->userdata('userID')));
			foreach($classes as $c) {
				if($c->classID == $class) {
					$go = false;
				}
			}
		}
		
		$this->form_validation->set_message('_check_inDB', $class . ' is already in your list. Please fix and try, try again.');
	
		return $go;
	}
	
	function _check_duplicates($class) {
	
		$this->form_validation->set_message('_check_duplicates', $class . ' has a duplicate here. Please fix and try, try again.');
		
		$this->session->set_flashdata('fields', $this->input->post('class_boxes'));
		
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
		
		$this->session->set_flashdata('fields', $this->input->post('class_boxes'));
		
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
	
}

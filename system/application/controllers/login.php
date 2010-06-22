<?php

class login extends Controller {
	
	function login() {
		
		parent::Controller();
		
		$this->load->library('form_validation');
		$this->load->model('user_model');
		$this->load->helper('css');
		$this->load->helper('html');
		$this->load->model('nav_links_model');
		
	}
	
	function index() {
		
		
		$this->form_validation->set_rules('password', 'Password', 'trim|required');
		$this->form_validation->set_rules('username', 'Username', 'trim|required|callback__check_login');
		
		if($this->form_validation->run()) {
			
			if($this->user_model->login(array('username' => $this->input->post('username'), 'password' => $this->input->post('password')))) {
				redirect('dashboard');
			}
			redirect('home');
			
		}
		
		$data = array(
			'view_name'	=> 'login_view',
			'ad'		=> 'static/ads/google_ad_120_234.php',
			'navigation'=> "navigation",
			'css'		=> includeCSSFile("style"),
			'nav_data'	=> $this->nav_links_model->getNavBarLinks()
		);
		
		$this->load->view('include/template', $data);
		
	}
	
	function _check_login($username) {
		
		if($this->input->post('password')) {
			
			$user = $this->user_model->getUsers(array('username' => $username, 'password' => md5($this->input->post('password'))));
			if($user) return true;
						
		}
		
		$this->form_validation->set_message('_check_login', 'Your username / password combination is not correct.');
		return false;
		
	}
	
}
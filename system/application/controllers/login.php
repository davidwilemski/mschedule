<?php
	/*
		This controller loads the login form, and all the various login functions are run though here.
		Additionally, this page plays host to the register function (login/register), the user
		validation functions (login/validate), and the logout function (login/logout).
		This has several custom form_validation callback functions:
		For login:
			_check_login - makes sure the user's credentials are valid
		For register:
			_check_email - makes sure the email is not already registered
			_umich_email - makes sure that the email provided is @umich.edu 
			_check_username - makes sure the username is avaliable, taking the username from the email
			_check_password - makes sure the two passwords match up
		For validate
			_check_validation - checks the validation code; the correct code is the md5 hash of the user's email
	*/
?>
<?php

class login extends Controller {
	
	function login() {
		
		parent::Controller();
		
		$this->load->library('form_validation');
		$this->load->helper('css');
		$this->load->model('nav_links_model');
		//$this->load->model('static_page_model');
		
	}
	
	function index() {
		
		
		$this->form_validation->set_rules('username', 'Username', 'trim|required|callback__check_login');
		$this->form_validation->set_rules('password', 'Password', 'trim|required');
		
		if($this->form_validation->run()) {
			
			if($this->user_model->login(array('username' => $this->input->post('username'), 'password' => $this->input->post('password')))) {
				redirect($this->input->post('redirect'));
			}
			
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
			
			$user = $this->user_model->getUsers(array('username' => $username, 'password' => md5($this->input->post('password')), 'status' => 'active'));
			if($user) return true;
						
		}
		
		$this->form_validation->set_message('_check_login', 'Your username / password combination is not correct. If you have not activated your account, please check your email.');
		return false;
		
	}
	
	function register() {
	
		$this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
		$this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');
		$this->form_validation->set_rules('email', 'umich Email', 'trim|required|valid_email|callback__check_email|callback__umich_email|callback__check_username');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|callback__check_password');
		$this->form_validation->set_rules('password_confirm', 'Password Confirmation', 'trim|required');
	
		if($this->session->userdata('userID'))
		{
			redirect('dashboard');
		}
		
		if($this->form_validation->run()) {
		
			$user = array(
				'first_name'=>$this->input->post('first_name'), 
				'last_name'=>$this->input->post('last_name'),
				'email'=>$this->input->post('email'),
				'username'=>preg_replace('/@umich.edu/', '', $this->input->post('email')),
				'password'=>md5($this->input->post('password'))
			);
			
			if($this->user_model->addUser($user)) {
				$this->user_model->email_validation($user);
				redirect('home/validation_sent');
			}
			redirect('login/register');
		
		}

		$data = array(
			'view_name'	=> 'register_view',
			'ad'		=> 'static/ads/google_ad_120_234.php',
			'navigation'=> "navigation",
			'css'		=> includeCSSFile("style"),
			'nav_data'	=> $this->nav_links_model->getNavBarLinks()
		);
		
		$this->load->view('include/template', $data);
	}
	
	function _umich_email($email) {
	
		if($this->input->post('email'))
			if(preg_match('/^[a-zA-Z]{3,8}@umich.edu/', $email))
				return true;
		$this->form_validation->set_message('_umich_email', 'It is currently required that you use a @umich.edu email address. Try, try again.');
		return false;
	
	}
	
	function _check_username($email) {
		
		if($this->input->post('email')) {
			
			$username = preg_replace('/@umich.edu/', '', $email);
			$user = $this->user_model->getUsers(array('username' => $username));
			if(!$user) 
				return true;
				
		}
		$this->form_validation->set_message('_check_username', 'Your uniqname (email) is already registered. Try, try again.');
		return false;
		
	}
	
	function _check_email($email) {
		
		if($this->input->post('email')) {
			
			$user = $this->user_model->getUsers(array('email' => $email));
			if(!$user) 
				return true;
				
		}
		
		$this->form_validation->set_message('_check_email', 'Your email is already registered. Try, try again.');
		return false;
		
	}
	
	function _check_password($password) {
		
		if($this->input->post('password')) {
			if($this->input->post('password_confirm')) {			
				
				if($password != $this->input->post('password_confirm')) {
				
					$this->form_validation->set_message('_check_password', 'Your passwords do not mach. Try, try again.');
					return false;
				
				}
			}		
		}
		
		return true;
		
	}
	
	function logout() {
	
		$this->session->sess_destroy();
		
		redirect('login');
	
	}
	
	function validate() {
	
		if('' != $this->uri->segment(3))
			if($this->user_model->activate_account($this->uri->segment(3)))
				redirect('home/activated_account');
		
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('code', 'Activation Code', 'trim|required|callback__check_validation');
		
		if($this->form_validation->run()) {
			redirect('home/activated_account');
		}
		
		$data = array(
			'view_name'	=> 'activate_view',
			'ad'		=> 'static/ads/google_ad_120_234.php',
			'navigation'=> "navigation",
			'css'		=> includeCSSFile("style"),
			'nav_data'	=> $this->nav_links_model->getNavBarLinks()
		);
		
		$this->load->view('include/template', $data);
	}
	
	function _check_validation($code) {
		
		$this->form_validation->set_message('_check_validation', 'Your activation code is invalid for this email. Try, try again.');
		
		if($this->input->post('code')) {
			if($this->input->post('email')) {			
				if(md5($this->input->post('email')) == $code) {
					if(!$this->user_model->activate_account($code)) {
					
						return false;
					
					} return true;
				}
			}
		}
		
		return false;
		
	}
	

}
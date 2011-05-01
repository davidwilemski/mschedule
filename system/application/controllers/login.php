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
			if($this->user_model->hasMigrated(array('username' => $username)) == 1)
				$user = $this->user_model->getUsers(array('username' => $username, 'password' => hash('sha256', $username . $this->input->post('password')), 'status' => 'active'));
			else if($this->user_model->hasMigrated(array('username' => $username)) == 0)
				$user = $this->user_model->getUsers(array('username' => $username, 'password' => md5($this->input->post('password')), 'status' => 'active'));
			else{
				$user = $this->user_model->getUsers(array('username' => $username, 'password' => $this->user_model->oldpass($this->input->post('password'))));

			}

			if($user) return true;
						
		}
		
		$this->form_validation->set_message('_check_login', 'Your username / password combination is not correct. If you have not activated your account, please check your email.');
		return false;
		
	}
	
	function register() {
		
		if($this->session->userdata('userID'))
			redirect('dashboard');
	
		$this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
		$this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');
		$this->form_validation->set_rules('email', 'umich Email', 'trim|required|valid_email|callback__check_email|callback__umich_email|callback__check_username');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|callback__check_password');
		$this->form_validation->set_rules('password_confirm', 'Password Confirmation', 'trim|required');
		
		if($this->form_validation->run()) {
		
			$user = array(
				'first_name'=>$this->input->post('first_name'), 
				'last_name'=>$this->input->post('last_name'),
				'email'=>$this->input->post('email'),
				'username'=>preg_replace('/@umich.edu/', '', $this->input->post('email')),
				'password'=>hash('sha256', preg_replace('/@umich.edu/', '', $this->input->post('email')) . $this->input->post('password')),
				'activate_code'=>random_string('unique')
			);
			
			if($this->user_model->addUser($user)) {
				if($this->user_model->email_validation($user)) {
					redirect('home/validation_sent');
				}
			} else {
				$this->session->set_flashdata('flasherror', 'Something went wrong. Please contact mschedule support.');
				redirect('login/register');
			}
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
		
		redirect('home');
	
	}
	
	function validate($code) {
		
		if($this->session->userdata('userID'))
			redirect('dashboard');
	
		if($code)
			$user = $this->user_model->getUsers(array('activate_code' => $code));
			if($user) {
				if($this->user_model->activate_account(array('status' => 'active', 'userID' => $user->userID)))
					redirect('home/activated_account');
			}
		
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
					$user = $this->user_model->getUsers(array('activate_code' => $code));
					if($user) {
						if($this->user_model->activate_account(array('status' => 'active', 'userID' => $user->userID)))
						return true;
					}
				}
			}
		}
		
		return false;
		
	}
	
	function forgot() {
		
		if($this->session->userdata('userID'))
			redirect('dashboard');
		
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|callback__registered_email');
		
		if($this->form_validation->run()) {
			redirect('home/reactivation_sent');
		}
		
		$data = array(
			'view_name'	=> 'forgot_view',
			'ad'		=> 'static/ads/google_ad_120_234.php',
			'navigation'=> "navigation",
			'css'		=> includeCSSFile("style"),
			'nav_data'	=> $this->nav_links_model->getNavBarLinks()
		);
		
		$this->load->view('include/template', $data);
				
	}
	
	function _registered_email($email) {
		
		if($this->input->post('email')) {
			
			$user_1 = $this->user_model->getUsers(array('email' => $email));
			$user_2 = $this->user_model->getUsers(array('username' => preg_replace('/@umich.edu/', '', $this->input->post('email'))));
			
			if($user_1) {
				$this->user_model->forgot_password(array('userID' => $user_1->userID));
				return true;
			} else if($user_2) {
				$this->user_model->forgot_password(array('userID' => $user_2->userID));
				return true;
			}
				
		}
		
		$this->form_validation->set_message('_registered_email', 'This email does not match any of our records. Try, try again.');
		return false;
		
	}
	
	function password_reset() {
		
		if($this->session->userdata('userID'))
			redirect('dashboard');
		
		$this->form_validation->set_rules('code', 'Code', 'trim|required|callback__password_activate');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|callback__check_password');
		$this->form_validation->set_rules('password_confirm', 'Password Confirmation', 'trim|required');
		
		
		if($this->form_validation->run()) {
			
			$u = $this->user_model->getUsers(array('activate_code' => $this->input->post('code'), 'status' => 'inactive'));
			if($u) {
				$user = $this->user_model->activate_account(array('userID' => $u->userID, 'password' => hash('sha256', $this->session->userdata('username') . $this->input->post('password')), 'status' => 'active'));
				if($user) {
					$this->session->set_flashdata('flashError', 'Password reset. Try logging in.');
					redirect('login');
				}
			} else
				$this->session->set_flashdata('resent', 'This code is no longer valid. Try logging in.');
				
		}
		
		$data = array(
			'view_name'	=> 'password_reset_view',
			'ad'		=> 'static/ads/google_ad_120_234.php',
			'navigation'=> "navigation",
			'css'		=> includeCSSFile("style"),
			'nav_data'	=> $this->nav_links_model->getNavBarLinks()
		);
		
		$this->load->view('include/template', $data);
		
	}
	
	function _password_activate($code) {
		
		$this->form_validation->set_message('_password_activate', 'Your activation code is invalid for this email. Try, try again.');
		
		if($this->input->post('code')) {			
			if($this->user_model->getUsers(array('activate_code' => $code)))
				return true;
		}
		
		return false;
		
	}
}

<?php
	/*
		-secure- user, admin
		This controller is the main user interface for users to change their preferences.
		It loads the account_view to show the password reset and personal info update forms.
		This has several custom form_validation callback functions:
		For the password reset:
			_check_user_password - validates the user's password agains the database hash
			_check_password - validates that the new password is typed correctly twice
			_check_same - validates that the user isn't changing the password to the same thing
		For the user information update:
			_check_email - checks that the user isn't changing the password to a currently registered
						   email, and if the user doesn't change their email (it's already registered
						   to them) it will allow the form to update the email with the same email.
	*/
?>
<?php

class account extends CI_Controller {
	
	function __construct() {
		
		parent::__construct();
		
		$this->load->library('form_validation');
		
		if(!$this->user_model->Secure(array('userType'=>array('admin', 'user')))) {
		
			$this->session->set_flashdata('flashError', 'You must be logged in to access this page.');
			redirect('login');
		
		}
		
		
	}
	
	function index() {
		
		$data = array(
			'view_name'	=> 'account_view',
			'navigation'=> "navigation",
			'css'		=> includeCSSFile("style"),
			'nav_data'	=> $this->nav_links_model->getNavBarLinks(),
			'nav_location' => 'account'
		);
		
		$this->load->view('include/template', $data);
	}
	
	function password_reset() {
	
		$data = array(
			'view_name'	=> 'account_view',
			'ad'		=> 'static/ads/google_ad_120_234.php',
			'navigation'=> "navigation",
			'css'		=> includeCSSFile("style"),
			'nav_data'	=> $this->nav_links_model->getNavBarLinks()
		);
	
		$this->form_validation->set_rules('password', 'Password', 'trim|required|callback__check_user_password');
		$this->form_validation->set_rules('new_password', 'New Password', 'trim|required|callback__check_password');
		$this->form_validation->set_rules('new_password2', 'New Password Confirmation', 'trim|required|callback__check_same');
		
		if($this->form_validation->run()) {
			if($this->user_model->updateUser(array('userID' => $this->session->userdata['userID'], 'password' => md5($this->input->post('new_password'))))) {
				$this->session->set_flashdata('action', 'Your password has been changed');
				redirect('dashboard');
			}
		}
		
		$this->load->view('include/template', $data);
	}
	
	function _check_user_password($password) {
		
		if($this->input->post('password')) {
			if(!$this->user_model->getUsers(array('userID' => $this->session->userdata['userID'], 'password' => md5($password)))) {
				$this->form_validation->set_message('_check_user_password', 'Your passwords is incorrect. Try, try again.');
				return false;
			}
		}
		return true;
		
	}
	
	function _check_same($password) {
		
		if($this->input->post('password')) {
			if($this->input->post('password') == $this->input->post('new_password')) {
				$this->form_validation->set_message('_check_same', 'Your can not change your password to the same thing. Try, try again.');
				return false;
			}
		}
		return true;
		
	}
	
	
	function _check_password($password) {
		
		if($this->input->post('new_password')) {
			if($this->input->post('new_password2')) {	
				if($password != $this->input->post('new_password2')) {
				
					$this->form_validation->set_message('_check_password', 'Your passwords do not mach. Try, try again.');
					return false;
				
				}
			}		
		}
		
		return true;
		
	}
	
	function modify_user() {

		$data = array(
			'view_name'	=> 'account_view',
			'ad'		=> 'static/ads/google_ad_120_234.php',
			'navigation'=> "navigation",
			'css'		=> includeCSSFile("style"),
			'nav_data'	=> $this->nav_links_model->getNavBarLinks()
		);
		
		$this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
		$this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|callback__check_email');
		
		if($this->form_validation->run()) {
		
			$user = array(
				'userID' 		=> $this->session->userdata['userID'], 
				'first_name' 	=> $this->input->post('first_name'),
				'last_name'		=> $this->input->post('last_name'),
				'email'			=> $this->input->post('email')
			);
			if($this->user_model->updateUser($user)) {
				$this->session->set_flashdata('action', 'Personal Information updated.');
				$this->session->set_userdata('first_name', $user['first_name']);
				$this->session->set_userdata('last_name', $user['last_name']);
				$this->session->set_userdata('email', $user['email']);
				redirect('dashboard');
			} else if($this->session->userdata['email'] == $this->input->post('email')) {	
				$this->session->set_flashdata('action', 'Personal Information updated.');
				redirect('dashboard');
			}
		
		}
		
		$this->load->view('include/template', $data);
	}
	
	function _check_email($email) {
		
		if($this->input->post('email')) {
			
			$user_1 = $this->user_model->getUsers(array('email' => $email));
			$user_2 = $this->user_model->getUsers(array('username' => preg_replace('/@umich.edu/', '', $email)));
			if($user_1) {
				if($user_1->userID != $this->session->userdata('userID')) {
					$this->form_validation->set_message('_check_email', 'That email is already registered. Try, try again.');
					return false;
				} else
					return true;
			} else if ($user_2) {
				if($user_2->userID != $this->session->userdata('userID')) {
					$this->form_validation->set_message('_check_email', 'That uniqname is already registered. Try, try again.');
					return false;
				} else
					return true;
			} else
				return true;
		}
		
		$this->form_validation->set_message('_check_email', 'Something went wrong. Try, try again.');
		return false;
		
	}
	
}

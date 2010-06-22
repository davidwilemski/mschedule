<?php

/*
	Note for future: you can add TRUE as a second argument to $this->input->post() to filter it
*/

class Contact extends controller {
	
	function Contact() {
		
		parent::controller();
		$this->load->helper("css");
		$this->load->helper("url");
		$this->load->helper("html");
		$this->load->model('static_pages_model');
		$this->load->model('nav_links_model');
		
	}
	
	function index() {
		
		$data = array(
			'view_name'	=> 'contact_view',
			'ad'		=> 'static/ads/google_ad_120_234.php',
			'navigation'=> "navigation",
			'css'		=> includeCSSFile("style"),
			'nav_data'	=> $this->nav_links_model->getNavBarLinks()
		);
		
		$this->load->view('include/template', $data);
	}
	
	function send_email() {
		
		$data = array(
			'view_name'	=> 'contact_view',
			'ad'		=> 'static/ads/google_ad_120_234.php',
			'navigation'=> "navigation",
			'css'		=> includeCSSFile("style"),
			'nav_data'	=> $this->nav_links_model->getNavBarLinks(),
		);
		
		$this->load->library('form_validation');
		$this->form_validation->set_rules('name', 'Name', 'trim|required');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('message', 'Message', 'trim|required');
		
		if($this->form_validation->run() == FALSE) {
			
			$this->load->view('include/template', $data);
			
		} else {
			
			$this->load->library('email');
			$this->load->helper('date');
			
			$name = $this->input->post('name');
			$email = $this->input->post('email');
			$message = $this->input->post('message');
			
			$this->email->set_newline("\r\n");
			$this->email->from($name . ' <' . $email . '>');
			$this->email->to('webmaster@mschedule.com');
			$this->email->subject('Contact Form Message ' . unix_to_human(gmt_to_local(time(), 'UM5', TRUE)));
			$this->email->message($message);
			
			if($this->email->send()) {
				
				$data_confirm = array(
					'view_name'	=> 'home_view',
					'ad'		=> 'static/ads/google_ad_120_234.php',
					'navigation'=> "navigation",
					'css'		=> includeCSSFile("style"),
					'nav_data'	=> $this->nav_links_model->getNavBarLinks(),
					'page_data'	=> $this->static_pages_model->getPageContent('msgconfirm')
				);
				
				$this->load->view('include/template', $data_confirm);
				
			} else {
				
				$data_error = array(
					'view_name'	=> 'contact_view',
					'ad'		=> 'static/ads/google_ad_120_234.php',
					'navigation'=> "navigation",
					'css'		=> includeCSSFile("style"),
					'nav_data'	=> $this->nav_links_model->getNavBarLinks(),
					'page_data'	=> $this->static_pages_model->getPageContent('msgerror')
				);
				
				$this->load->view('include/template', $data_error);
				
			}
		}
	}
	
}
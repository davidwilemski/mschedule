<?php
	/*
		This controller loads the about information and contact form (public), and sends the email (send_email()) to webmaster@mschedule.com
	*/
?>
<?php
class About extends CI_Controller {
	
	function __construct() {
		
		parent::__construct();
		$this->load->model('static_pages_model');
		
	}
	
	function index() {
		
		$data = array(
			'view_name'	=> 'about_view',
			'navigation'=> "navigation",
			'css'		=> includeCSSFile("style"),
			'nav_data'	=> $this->nav_links_model->getNavBarLinks(),
			'nav_location' => 'about us'
		);
		
		$this->load->view('include/template', $data);
	}
	
	function send_email() {
		
		$this->load->library('form_validation');
		$this->form_validation->set_rules('name', 'Name', 'trim|required');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('message', 'Message', 'trim|required');
		
		if($this->form_validation->run() == FALSE) {
			redirect('about');
		} else {
			
			$this->load->library('email');
			$this->load->helper('date');
			
			$name = $this->input->post('name');
			$email = $this->input->post('email');
			$message = $this->input->post('message');
			
			$this->email->from($email, $name);
			$this->email->to('webmaster@mschedule.com');
			$this->email->subject('Contact Form Message ' . unix_to_human(gmt_to_local(time(), 'UM5', TRUE)));
			$this->email->message($message);
			
			if($this->email->send()) {
				$this->session->set_flashdata('flashSuccess', 'Thank you!');
			} else {
				$this->session->set_flashdata('flashError', 'Could not send your email. Try again later. ' . $this->email->print_debugger());				
			}
			
			redirect('about');
		}
	}
	
}

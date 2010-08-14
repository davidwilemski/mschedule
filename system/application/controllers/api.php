<?php
	/*
		This acts as an interface for all mschedule models	*/
?>
<?php

class Api extends Controller {
	
	function Api() {
		
		parent::controller();
		
	}
	
	function json($model, $method)	
	{

		$this->load->model($model);
		
		$post = $this->input->post('data');
		
		//if($method == "createSchedules") print_r($post);
		
		$data = $this->$model->$method($post);
		
		print_r(json_encode($data));
		
		
	}
	
	function test() {
	
		$this->load->model('class_model');
		print_r(json_encode($this->class_model->createSchedules(
			array(
				/*'14785',
				'14787',
				'14789',
				'49025',
				'49026',
				'49027',
				'14795',
				'14797',
				'14799',*/
				'11351',
				'11353',
				'11355',
				'11365',
				'11367',
				'11369'
			)
		)));
	
	}
	
}

?>
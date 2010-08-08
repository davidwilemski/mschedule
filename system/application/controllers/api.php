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
		
		//if($method == "getClassSections") print_r($post);
		
		$data = $this->$model->$method($post);
		
		print_r(json_encode($data));
		
		
	}
	
}

?>
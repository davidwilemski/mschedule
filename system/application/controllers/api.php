<?php
	/*
		This acts as an interface for all mschedule models	*/
?>
<?php

class Api extends Controller {
	
	function Api() {
		
		parent::controller();
		
	}
	
	function json($model, $method, $options = null)	
	{

		$this->load->model($model);
		
		$data = $this->$model->$method($options);
		
		print_r(json_encode($data));
		
		
	}
	
	function test()
	{
		$this->load->library('table');

$data = array(
             array('Name', 'Color', 'Size'),
             array('Fred', 'Blue', 'Small'),
             array('Mary', 'Red', 'Large'),
             array('John', 'Green', 'Medium')	
             );

echo $this->table->generate($data);
	}
}

?>
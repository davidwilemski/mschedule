<?php 

/*
	Documentation
*/

?>

<?php

$this->load->helper('form');

?>
<div id="class_div"></div>

<div id="time_div">
<?
$times = array(
	'0' => 'Early Riser',
	'1' => 'Sleep In',
	'2' => 'Friday Off!'
);
echo form_dropdown('times', $times, 'free_morning');
?>
</div>

<div id="section_div"></div>

<div id="schedule_div"></div>
<?php
//echo form_close();

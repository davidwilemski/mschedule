<?php 

/*
	Documentation
*/

?>

<?php
//echo '<script src="' . base_url() . 'static/javascript/jquery.js" type="text/JavaScript"></script>';
//echo '<script src="' . base_url() . 'static/javascript/mschedule.js" type="text/JavaScript"></script>';

$this->load->helper('form');

?>
<div id="schedule_nav">
	<fieldset id="schedule_nav">
	<span id="classes">Classes</span>
	<span id="times">Times</span>
	<span id="sections">Sections</span>
	<span id="schedules">Schedules</span>
	</fieldset>
	</div>
	<div id="class_div">
	
</div>

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

<div id="section_div">
</div>

<div id="schedule_div">
	Schedules!
</div>
<?php
//echo form_close();

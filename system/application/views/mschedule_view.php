<?php 

/*
	Documentation
*/

?>

<?php

$this->load->helper('form');

?>
<div id="schedule_picker_container">
<h1 class="heading">Course Picker</h1>
<div id="schedule_picker_div"></div>
</div>

<!-- <div id="time_div">
<?
/*
$times = array(
	'0' => 'Early Riser',
	'1' => 'Sleep In',
	'2' => 'Friday Off!'
);
echo form_dropdown('times', $times, 'free_morning');
*/
?>
</div>
-->
<div id="course_list_container">
<h1 class="heading">Course List</h1>
<ul></ul>
</div>

<a id="nextButton" class="gradient_button button_disabled" href="#">Continue &gt;</a>

<?php
//echo form_close();

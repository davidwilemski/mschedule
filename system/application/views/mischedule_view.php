<?php 
/*
require_once("static/mischedule/v20/php/checkopen.php");

$request = base_url() . "static/mischedule/v20/php/request.php";
$base = base_url() . "static/mischedule/v20/";
*/
?>

<!--<applet codebase=<?=base_url() . 'static/mischedule/v20/classes/'?> code='MISchedule.class' width=605 height=500>
<param name='term' value='<?=$term?>'>
<param name='request' value='<?=$request?>'>
</applet>-->

<?php
$this->load->helper('form');

echo form_open();

$class_button = array(
	'name'=>'classes', 
	'id' => 'classes',
	'content'=>'Classes',
	'class'=>'button'
);

$time_button = array(
	'name'=>'time', 
	'id' => 'time',
	'content'=>'Time',
	'class'=>'button'
);

$section_button = array(
	'name'=>'section', 
	'id' => 'section',
	'content'=>'Section',
	'class'=>'button'
);

$schedule_button = array(
	'name'=>'schedule', 
	'id' => 'section',
	'content'=>'Section',
	'class'=>'button'
);

?>
<div id="schedule_nav">
<fieldset id="schedule_nav">
<?=form_button($class_button)?>
<?=form_button($time_button)?>
<?=form_button($section_button)?>
<?=form_button($schedule_button)?>
</fieldset>
</div>
<div id="main_field">
<div id="left" class="left">
<fieldset id="left" class="left">
Content
</fieldset>
</div>
<div id="right" class="right">
<fieldset id="right" class="right">
Content
</fieldset>
</div>
</div>
<?php
echo form_close();
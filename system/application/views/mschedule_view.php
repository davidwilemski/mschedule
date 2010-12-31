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
	<table id="classes" border=0>
	<tr>
	<td id="left_side">
		<div id="department_list">
		<table id="depts">
		<?php
			foreach($master_list as $m) {
				echo '<tr id="' . $m[0] . '" class="dept_tr"><td>' . $m[0] . '</td><td>' . $m[1] . '</td></tr>';
			}
		?>
		</table>
		</div>
		<hr />
		<div id="class_list">
		<table id="class_table" class="class_table"></table>
		</div>
	</td>
	
	<td id="right_side">
	<? $rows = 1; ?>
	<input type="hidden" value="<?=$rows?>" id="rows">
	<input type="hidden" value="sel_1" id="selected_row">
	<p id="class_header">Department   -   Class</p>
	<?php
		for($i = 1; $i <= $rows; $i++) {
			echo '<p id="sel_' . $i . '" class="sel_p">' . form_input(array('id'=>'dept_' . $i, 'name'=>'dept_' . $i, 'class'=>'dept_input', 'readonly'=>'readonly'));
			echo form_input(array('id'=>'class_' . $i, 'name'=>'class_' . $i, 'class'=>'class_input', 'readonly'=>'readonly')) . '<span id="c' . $rows . '" class="rm_course">' .img('static/images/round_delete.png')  . '</span></p>';
		}
	?>
	</td>
	
	</tr>
	</table>
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

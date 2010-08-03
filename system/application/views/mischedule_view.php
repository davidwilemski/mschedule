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
echo '<script src="' . base_url() . 'static/javascript/jquery.js" type="text/JavaScript"></script>';
echo '<script src="' . base_url() . 'static/javascript/mschedule.js" type="text/JavaScript"></script>';

$this->load->helper('form');

$dept_tables = array();
$dept_html = '<table id="depts">';

foreach($master_list as $l) {
	if(!isset($dept_tables[$l['dept']])) {
		$dept_tables[$l['dept']] = array();
		$dept_html .= '<tr id="' . $l['dept'] . '" class="dept_tr"><td>' . $l['dept'] . '</td><td>' . 'Dept Full Name' . '</td></tr>';
		$dept_tables[$l['dept']]['class_html'] = '<table class="class_table" style="display: none;" id="' . $l['dept'] . '_classes">';
	}
	if(!isset($dept_tables[$l['dept']][$l['number']])) {
		$dept_tables[$l['dept']]['class_html'] .= '<tr id="' . $l['classid'] . '" class="class_tr"><td>' . $l['number'] . '</td><td>' . $l['class_name'] . '</td></tr>';
		$dept_tables[$l['dept']][$l['number']] = true;
	}
}

$dept_html .= '</table>';

echo form_open();

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
		<?=$dept_html?>
		</div>
		<hr />
		<div id="class_list">
		<?php
			foreach($dept_tables as $d)
				echo $d['class_html'] . '</table>';
		?>
		</div>
	</td>
	
	<td id="right_side">
	<? $rows = 4; ?>
	<input type="hidden" value="<?=$rows?>" id="rows">
	<input type="hidden" value="sel_1" id="selected_row">
	<p id="class_header">Department   -   Class</p>
	<?php
		for($i = 1; $i <= $rows; $i++) {
			echo '<p id="sel_' . $i . '" class="sel_p">' . form_input(array('id'=>'dept_' . $i, 'name'=>'dept_' . $i, 'class'=>'dept_input', 'readonly'=>'readonly'));
			echo form_input(array('id'=>'class_' . $i, 'name'=>'class_' . $i, 'class'=>'class_input', 'readonly'=>'readonly')) . '</p>';
		}
	?>
	</td>
	
	</tr>
	</table>
</div>

<div id="time_div">
	<p id="load_times">Click here to load class times!</p>
</div>

<div id="section_div">
Some content for sections.
</div>

<div id="schedule_div">
Schedules!
</div>
<?php
echo form_close();
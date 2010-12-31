<?php
	/*
		This view is for loading in classes BY CLASS NUMBER.
	*/
?>
<? if(is_array($user_classes)) { ?>
	<table border=1>
	<?php
	echo '<tr>';
	echo '<td>Class ID</td>';
	echo '<td>Department</td>';
	echo '<td>Class Number</td>';
	echo '<td>Class Section</td>';
	echo '<td>Class Type</td>';
	echo '</tr>';
	
	$current_schedule_id = "";
	foreach($user_classes as $p) {
		$current_schedule_id .= $p->classid . ";";
		echo '<tr>';
		echo '<td>' . $p->classid . '</td>';
		echo '<td>' . $p->dept . '</td>';
		echo '<td>' . $p->number . '</td>';
		echo '<td>' . $p->section . '</td>';
		echo '<td>' . $p->type . '</td>';
		echo '</tr>';
	}
	?>
	</table>
<?
} else {
echo "<p>You have no classes yet!</p>";
}
$this->load->helper('form');
if($this->session->flashdata('error')) { ?>
<div class="error"><?=$this->session->flashdata('error')?></div>
<?php }
// This sets the number of fields that are loaded in the view
if(!$this->session->flashdata('fields')) $fields = '3'; else $fields = $this->session->flashdata('fields'); ?>
<?=form_open('classes/import')?>

<fieldset>
<legend>Import Classes</legend>
<input type="hidden" id="class_boxes" name="class_boxes" value=<?=$fields?>></input>
<input type="hidden" id="curr_schedule_string" name="curr_schedule_string" value="<?php if(isset($current_schedule_id)) echo $current_schedule_id; else echo ''; ?>"></input>
<?php
$inputs = array();
for($i = 1; $i <=$fields; $i++) {
	$inputs[$i] = array(
		'name'	=> 'class' . $i,
		'id'	=> 'class' . $i,
		'value' => set_value('class' . $i)
	);
}

$button_data = array(
	'name'	=> 'submit',
	'id'	=> 'submit',
	'value'	=> 'Submit',
	'class'	=> 'button'
);

$add_button_data = array(
	'name'	=> 'add',
	'id'	=> 'add',
	'value'	=> 'true',
	'content'	=> '+',
	'class'	=> 'button'
);

$remove_button_data = array(
	'name'	=> 'remove',
	'id'	=> 'remove',
	'value'	=> 'true',
	'content'	=> '-',
	'class'	=> 'button'
);

?>
<fieldset>
<table><tbody>
<tr><td>Save as New:</td><td><?=form_radio('save_type', 'new', false)?></td></tr>
<tr><td>Append to current schedule:</td><td><?=form_radio('save_type', 'append', true)?></td></tr>
</tbody></table>
</fieldset>
<?php foreach($inputs as $input) { ?>
<p id="<?=$input['id']?>"><label for="<?=$input['name']?>">Class ID: </label><?=form_input($input)?></p>
<?php } ?>
<input type="hidden" value="" >
<?=form_submit($button_data)?>
<?=form_button($add_button_data)?>
<?=form_button($remove_button_data)?>

<div class="error"><?=validation_errors()?></div>

</fieldset>

<?=form_close()?>
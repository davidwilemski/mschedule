<?php
	/*
		This view is for loading in classes BY CLASS NUMBER.
	*/
?>
<?php 
$this->load->helper('form');
if($this->session->flashdata('error')) { ?>
<div class="error"><?=$this->session->flashdata('error')?></div>
<?php } ?>

<?=form_open('classes/import')?>

<fieldset>
<legend>Import Classes</legend>

<input type="hidden" id="class_boxes" name="class_boxes" value="3"></input>
<?php
$inputs = array();
for($i = 1; $i <=3; $i++) {
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
<?php foreach($inputs as $input) { ?>
<p id="<?=$input['id']?>"><label for="<?=$input['name']?>">Class ID: </label><?=form_input($input)?></p>
<?php } ?>

<?=form_submit($button_data)?>
<?=form_button($add_button_data)?>
<?=form_button($remove_button_data)?>

<div class="error"><?=validation_errors()?></div>

</fieldset>

<?=form_close()?>
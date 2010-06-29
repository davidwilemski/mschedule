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

<?=form_hidden('class_boxes', '6')?>
<?php
$inputs = array();
for($i = 1; $i <=6; $i++) {
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
?>
<?php foreach($inputs as $input) { ?>
<p><label for="<?=$input['name']?>">Class ID: </label><?=form_input($input)?></p>
<?php } ?>

<?=form_submit($button_data)?>

<div class="error"><?=validation_errors()?></div>

</fieldset>

<?=form_close()?>
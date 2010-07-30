<?php
	/*
		This is the forgot password form.
		It utilizes the login controller.
	*/
?>
<?php 
$this->load->helper('form');
if($this->session->flashdata('resent')) { ?>
<div class="error"><?=$this->session->flashdata('resent')?></div>
<?php }
?>
<?=form_open('login/forgot')?>
<fieldset>
<legend>Forgot Password</legend>
<?php

$email = array(
	'name'	=> 'email',
	'id'	=> 'email',
	'value'	=> set_value('email')
);

$button_data = array(
	'name'	=> 'forgot',
	'id'	=> 'submit',
	'content'	=> 'Recover',
	'class'	=> 'button'
);

?>

<p><label for="username">Email: </label><?php echo form_input($email); ?></p>

<?php echo form_submit($button_data); ?>

<div class="error"><?php echo validation_errors(); ?></div>

</fieldset>

<?php echo form_close(); ?>
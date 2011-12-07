<?php
	/*
		This is the form for the second and final step in resetting a user's password.
		Called by login/password_reset
	*/
?>
<?php 
$this->load->helper('form');?>
<div class="error">
<?if($this->session->flashdata('resent')) { ?>
<?=$this->session->flashdata('resent')?>
<?}
if($this->session->flashdata('email')) { ?>
<?=$this->session->flashdata('email')?>
<?}?>
</div>
<?
echo form_open('login/password_reset');
$code = array(
	'name'	=> 'code',
	'id'	=> 'code',
	'value'	=> set_value('code')
);

$password = array(
	'name'	=> 'password',
	'id'	=> 'password'
);

$password_confirm = array(
	'name'	=> 'password_confirm',
	'id'	=> 'password_confirm'
);

$button = array(
	'name'	=> 'sumbit',
	'id'	=> 'submit',
	'class'	=> 'button',
	'value'	=> 'Submit',
)

?>
<fieldset>
<legend>New Password</legend>
<? if(!$this->uri->segment(3)) { ?>
<p><label for="email">Code: </label><?php echo form_input($code); ?></p>
<?} else {
echo form_hidden('code', $this->uri->segment(3));
}
?>
<p><label for="code">New Password: </label><?php echo form_password($password); ?></p>
<p><label for="code">New Password (again): </label><?php echo form_password($password_confirm); ?></p>

<?php echo form_submit($button); ?>

<div class="error"><?php echo validation_errors(); ?></div>

</fieldset>

<?php echo form_close(); ?>
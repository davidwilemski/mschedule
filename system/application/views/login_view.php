<?php
	/*
		This is the login form.
		It is called by the login controller.
	*/
?>
<?php 
$this->load->helper('form');
?>
<div class="error"><?=$this->session->flashdata('flashError')?></div>
<?php
echo form_open('login');
if($this->session->flashdata('flashRedirect'))
	echo form_hidden('redirect', $this->session->flashdata('flashRedirect'));
else
	echo form_hidden('redirect', 'dashboard');

if($this->session->flashdata('flashError')) {?>
<?php }

?>
<fieldset>
<legend>Login</legend>
<?php

$username_data = array(
	'name'	=> 'username',
	'id'	=> 'username',
	'value'	=> set_value('username')
);

$password_data = array(
	'name'	=> 'password',
	'id'	=> 'password'
);

$button_data = array(
	'name'	=> 'login',
	'id'	=> 'submit',
	'value'	=> 'Login',
	'class'	=> 'button'
);

?>

<p><label for="username">Username: </label><?php echo form_input($username_data); ?></p>
<p><label for="email">Password: </label><?php echo form_password($password_data); ?></p>

<?php echo form_submit($button_data); ?>

<div class="error"><?php echo validation_errors(); ?></div>

</fieldset>

<?php echo form_close(); ?>
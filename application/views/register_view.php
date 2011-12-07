<?php
	/*
		This is the view for the registration form.
		Called by login/register.
	*/
?>
<?=form_open('login/register')?>
<fieldset>
<legend>Register</legend>
<?php
$email_data = array(
	'name'	=> 'email',
	'id'	=> 'email',
	'value'	=> set_value('email')
);

$password_data = array(
	'name'	=> 'password',
	'id'	=> 'password'
);

$password_confirm_data = array(
	'name'	=> 'password_confirm',
	'id'	=> 'password_confirm'
);

$first_name_data = array(
	'name'	=> 'first_name',
	'id'	=> 'first_name',
	'value'	=> set_value('first_name')
);

$last_name_data = array(
	'name'	=> 'last_name',
	'id'	=> 'last_name',
	'value'	=> set_value('last_name')
);
?>
<table><tbody>
<tr><td><label for="first_name">First Name: </label></td><td><?php echo form_input($first_name_data); ?></td></tr>
<tr><td><label for="last_name">Last Name: </label></td><td><?php echo form_input($last_name_data); ?></td></tr>
<tr><td><label for="email">Umich Email: </label></td><td><?php echo form_input($email_data); ?></td></tr>
<tr><td><label for="password">Password: </label></td><td><?php echo form_password($password_data); ?></td></tr>
<tr><td><label for="password_confirm">Password (again): </label></td><td><?php echo form_password($password_confirm_data); ?></td></tr>
<tr><td></td><td><?=form_submit('', 'Register', 'class = "button"')?></td></tr>
</tbody></table>
<? if($this->session->flashdata('flashError') || validation_errors()) { ?><div class="error"><?=$this->session->flashdata('flashError')?><?=validation_errors()?></div> <? } ?>

</fieldset>
<?=form_close()?>

<?=form_open('login/register')?>
<h2>Register</h2>
<fieldset>
<?php
$username_data = array(
	'name'	=> 'username',
	'id'	=> 'username',
	'value'	=> set_value('username')
);

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
<p><label for="first_name">First Name: </label><?php echo form_input($first_name_data); ?></p>
<p><label for="last_name">Last Name: </label><?php echo form_input($last_name_data); ?></p>
<p><label for="email">Email: </label><?php echo form_input($email_data); ?></p>
<p><label for="username">Username: </label><?php echo form_input($username_data); ?></p>
<p><label for="password">Password: </label><?php echo form_password($password_data); ?></p>
<p><label for="password_confirm">Password (again): </label><?php echo form_password($password_confirm_data); ?></p>

<?=form_submit('', 'Register')?>

<?=validation_errors()?>

</fieldset>
<?=form_close()?>
<?php
	/*
		-needs to be secure- user, admin
		This is the user account view. It contains a password change and a personal info update form.
		It is called by the (user) dashboard controller
	*/
?>
<div id="account_forms">
<div id="announcement"><?=$this->session->flashdata('action')?></div>
<div class="error"><?php echo validation_errors(); ?></div>
<div id="password_reset" class="account_form_container">
<?php
$this->load->helper('form'); 
echo form_open('dashboard/password_reset'); 
?>
<fieldset>
<legend id="password_label">Change Password</legend>
<div id="password_form">
<?php
$curr_password = array(
	'name'	=> 'password',
	'id'	=> 'password'
);

$new_password = array(
	'name'	=> 'new_password',
	'id'	=> 'new_password'
);

$new_password2 = array(
	'name'	=> 'new_password2',
	'id'	=> 'new_password2'
);

$button_data = array(
	'name'	=> 'updatePassword',
	'id'	=> 'submit',
	'value'	=> 'Update Password',
	'class'	=> 'button'
);
?>
<p><label for="password">Current Password: </label><?=form_password($curr_password)?></p>
<p><label for="new_password">New Password: </label><?=form_password($new_password)?></p>
<p><label for="new_password2">Confirm Password: </label><?=form_password($new_password2)?></p>

<?php echo form_submit($button_data); ?>
</div>

</fieldset>

<?php echo form_close(); ?>
</div>

<div id="personal_info" class="account_form_container">
<?php
$this->load->helper('form'); 
echo form_open('dashboard/modify_user'); 
?>
<fieldset>
<legend id="info_label">Personal Info</legend>
<div id="personal_info_form">
<?php
$email = array(
	'name'	=> 'email',
	'id'	=> 'email',
	'value'	=> $this->session->userdata['email']
);

$first_name = array(
	'name'	=> 'first_name',
	'id'	=> 'first_name',
	'value'	=> $this->session->userdata['first_name']
);

$last_name = array(
	'name'	=> 'last_name',
	'id'	=> 'last_name',
	'value'	=> $this->session->userdata['last_name']
);

$button_data = array(
	'name'	=> 'updateData',
	'id'	=> 'submit',
	'value'	=> 'Update Information',
	'class'	=> 'button'
);
?>
<p><label for="password">First Name: </label><?=form_input($first_name)?></p>
<p><label for="new_password">Last Name: </label><?=form_input($last_name)?></p>
<p><label for="new_password2">Email: </label><?=form_input($email)?></p>

<?php echo form_submit($button_data); ?>
</div>

</fieldset>

<?php echo form_close(); ?>

</div>
</div>

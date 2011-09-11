<?php
	/*
		-needs to be secure- user, admin
		This is the user account view. It contains a password change and a personal info update form.
		It is called by the (user) account controller
	*/
?>
<div id="announcement"><?=$this->session->flashdata('action')?></div>
<div class="error"><?php echo validation_errors(); ?></div>

<div id="account_forms">
<div id="password_reset" class="account_form_container">
<?php
$this->load->helper('form'); 
echo form_open('account/password_reset'); 
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
	'value'	=> 'Update Password',
	'class'	=> 'button submit'
);
?>
<dl>
<dt>Current Password:</dt>
<dd><?=form_password($curr_password)?></dd>

<dt>New Password:</dt>
<dd><?=form_password($new_password)?></dd>

<dt>Confirm Password:</dt>
<dd><?=form_password($new_password2)?></dd>
</dl>

<?php echo form_submit($button_data); ?>
</div>

</fieldset>

<?php echo form_close(); ?>
</div>

<div id="personal_info" class="account_form_container">
<?php
$this->load->helper('form'); 
echo form_open('account/modify_user'); 
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
	'value'	=> 'Update Information',
	'class'	=> 'button submit'
);
?>

<dl>
<dt>First Name:</dt>
<dd><?=form_password($curr_password)?></dd>

<dt>Last Name:</dt>
<dd><?=form_password($new_password)?></dd>

<dt>Email:</dt>
<dd><?=form_password($new_password2)?></dd>
</dl>

<?php echo form_submit($button_data); ?>
</div>

</fieldset>

<?php echo form_close(); ?>

</div>
</div>

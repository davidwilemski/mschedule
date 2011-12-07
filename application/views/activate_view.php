<?php
	/*
		This is the form for activating the user account.
		Called by login/validation				  
	*/
?>
<?php 
$this->load->helper('form');
echo form_open('login/validate');
$code = array(
	'name'	=> 'code',
	'id'	=> 'code',
	'value'	=> set_value('code')
);

$email = array(
	'name'	=> 'email',
	'id'	=> 'email',
	'value'	=> set_value('email')
);

$button = array(
	'name'	=> 'sumbit',
	'id'	=> 'submit',
	'class'	=> 'button',
	'value'	=> 'Submit',
)

?>
<fieldset>
<legend>Activate Account</legend>
<p><label for="email">Email: </label><?php echo form_input($email); ?></p>
<p><label for="code">Activation Code: </label><?php echo form_input($code); ?></p>

<?php echo form_submit($button); ?>

<div class="error"><?php echo validation_errors(); ?></div>

</fieldset>

<?php echo form_close(); ?>
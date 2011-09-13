<?php
	/*
		This is the form for the contact us page.
		Called by the contact controller
	*/
?>
<div id="contact_form">
<?php 

$this->load->helper('form');

echo form_open('contact/send_email');

echo '<fieldset>';

$name_data = array(
	'name'	=> 'name',
	'id'	=> 'name',
	'value'	=> set_value('name')
);

$email_data = array(
	'name'	=> 'email',
	'id'	=> 'email',
	'value'	=> set_value('email')
);

$email_data = array(
	'name'	=> 'email',
	'id'	=> 'email',
	'value'	=> set_value('email')
);

$message_data = array(
	'name'	=> 'message',
	'id'	=> 'message',
	'value'	=> set_value('message')
);

$submit_data = array(
	'name'	=> 'sumbit',
	'value'	=> 'Submit',
	'class'	=> 'submit button'
);

?>
<legend>Email Us</legend>
<dl>
<dt>Full Name:</dt>
<dd><?php echo form_input($name_data); ?></dd>

<dt>Email:</dt>
<dd><?php echo form_input($email_data); ?></dd>

<dt>Message:</dt>
<dd><?php echo form_textarea($message_data); ?></dd>
</dl>

<p><?php echo form_submit($submit_data); ?></p>

<?php echo validation_errors(); ?>

</fieldset>

<?php echo form_close(); ?>
</div>

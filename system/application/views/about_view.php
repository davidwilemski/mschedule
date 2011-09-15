<?php
	/*
		This is the form for the contact us page.
		Called by the contact controller
	*/
?>
<div id="contact_form">
<?php 
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
$this->load->helper('form');
?>
<h2>About Us</h2>
<p>Mschedule is a free service that helps students create schedules for the University of Michigan. Using the login system, users are also able to share schedules with each other and see what other students are in your class (if they decide to share their schedules as well).</p>
<p>More to come...</p>
<p>Maintained by Bryan Kendall, David Wilemski, Tom Bombach, Ben Asher, and Jake Schwartz.</p>
<hr/>
<h2>Contact Us</h2>
<p>If you would like to contact us, please use the form below. We read every email, but may not have time to get back to you right away.</p>
<?=form_open('about/send_email')?>
<fieldset>
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

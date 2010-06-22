<?php 
$this->load->helper('form');
echo form_open('login/validate');
?>
<h2>Activate Account</h2>
<?php
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
?>
<fieldset>
<p><label for="email">Email: </label><?php echo form_input($email); ?></p>
<p><label for="code">Activation Code: </label><?php echo form_input($code); ?></p>

<?php echo form_submit('', 'Activate'); ?>

<div class="error"><?php echo validation_errors(); ?></div>

</fieldset>

<?php echo form_close(); ?>
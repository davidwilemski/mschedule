<?php
	/*
		This is the login form.
		It is called in the header.
	*/
?>
<?php 
$this->load->helper('form');
?>
<?php

$username_data = array(
	'name'	=> 'username',
	'id'	=> 'username',
	'title' => 'username',
	'placeholder' => 'Username or Email',
	'tabindex' => '4',
	'class' => 'rounded_corners_small',
	'value'	=> set_value('username')
);

$password_data = array(
	'name'	=> 'password',
	'id'	=> 'password',
	'title' => 'password',
	'placeholder' => 'Password',
	'tabindex' => '5',
	'class' => 'rounded_corners_small'
);

$hidden_submit_data = array(
	'name'	=> 'login',
	'value'	=> 'Sign in'
);

?>

<?php /* Setup Login/Logout Button */ ?>

<?php
if($this->session->userdata('userID')) {
	echo anchor("login/logout", "Sign Out", 'id="signout_button" class="gradient_button rounded_corners_small signin_button"');
}
?>

<?php /* Setup Login Form (if we're not logged in) */ ?>
<?php if(!$this->session->userdata('userID')) { ?>
<div id="signin_menu">
	<?=form_open('login')?>
		<fieldset>
			<?=form_input($username_data)?>
			<?=form_password($password_data)?>
			<?php
			if($this->session->flashdata('flashRedirect')) {
				echo form_hidden('redirect', $this->session->flashdata('flashRedirect'));
			} else {
				echo form_hidden('redirect', 'dashboard');
			}
			if($this->session->flashdata('flashError')) {}
			?>
			<?=form_hidden($hidden_submit_data)?>

			<a href="#" id="signin_submit" tabindex="6" class="gradient_button rounded_corners_small signin_button">Sign In</a>
		</fieldset>
	<?=form_close()?>
	<p class="forgot"> <?=anchor('login/forgot', 'Forgot your username or password?', 'id="forgot_username_link" title="If you remember your password, then try logging in with your uniqname."')?> <?=anchor('login/register', 'Register')?></p>
	<div class="error"><?php echo validation_errors(); ?></div>
</div>
<?php } ?>

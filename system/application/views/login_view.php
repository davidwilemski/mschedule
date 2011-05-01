<?php
	/*
		This is the login form.
		It is called in the header.
	*/
?>
<?php 
$this->load->helper('form');
?>
<div class="error"><?=$this->session->flashdata('flashError')?></div>
<?php

$username_data = array(
	'name'	=> 'username',
	'id'	=> 'username',
	'title' => 'username',
	'tabindex' => '4',
	'class' => 'rounded_corners_small',
	'value'	=> set_value('username')
);

$password_data = array(
	'name'	=> 'password',
	'id'	=> 'password',
	'title' => 'password',
	'tabindex' => '5',
	'class' => 'rounded_corners_small'
);

$button_data = array(
	'name'	=> 'login',
	'id'	=> 'signin_submit',
	'value'	=> 'Sign in',
	'tabindex' => '6',
	'class'	=> 'rounded_corners_small'
);

?>

<?php /* Setup Login/Logout Button */ ?>
<?php
$text = "";
$url = "";
if($this->session->userdata('userID')) {
	$text = "Sign Out";
	$url = "login/logout";
} else {
	$text = "Sign In";
}
echo anchor($url, $text, 'id="signin_button" class="gradient_button rounded_corners"');
?>

<?php /* Setup Login Form (if we're not logged in) */ ?>
<?php if(!$this->session->userdata('userID')) { ?>
<div id="signin_menu">
	<?php
	echo form_open('login');
	?>
		<fieldset>
			<label for="username">Username or email</label>
			<?=form_input($username_data)?>
			<label for="password">Password</label>
			<?=form_password($password_data)?>
			<?php
			if($this->session->flashdata('flashRedirect')) {
				echo form_hidden('redirect', $this->session->flashdata('flashRedirect'));
			} else {
				echo form_hidden('redirect', 'dashboard');
			}
			if($this->session->flashdata('flashError')) {}
			?>
			<?=form_submit($button_data)?>
		</fieldset>
	</form>
	<p class="forgot"> <?=anchor('login/forgot', 'Forgot Your Password?', 'id="resend_password_link"')?></p>
	<p class="forgot-username"> <?=anchor('login/forgot', 'Forgot Your Username?', 'id="forgot_username_link" title="If you remember your password, try logging in with your uniqname"')?></p>
	<div class="error"><?php echo validation_errors(); ?></div>
</div>
<?php }
echo form_close(); 
?>

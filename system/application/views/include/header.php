<?php
	/*
		This is the header view. It includes the html5 doctype, the head, the css link, and the title image.
		It requires 'css' to be sent into it.
		This is loaded by include/template.
	*/
?>
<!DOCTYPE html>

<html>
<head>
<title>MSchedule</title>
<?php echo $css; ?>

<meta charset="UTF-8">
</head>

<body>


<div id="title_pane">
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
	<?php if(!$this->session->userdata('userID')) { ?>
	<div id="signin_menu">
		<?php
		$this->load->helper('form');
		echo form_open('login');
		?>
			<fieldset>
				<label for="username">Username or email</label>
				<input id="username" name="username" value="" title="username" tabindex="4" type="text" class="rounded_corners_small">
				<label for="password">Password</label>
				<input id="password" name="password" value="" title="password" tabindex="5" type="password" class="rounded_corners_small">
				<?php
				if($this->session->flashdata('flashRedirect')) {
					echo form_hidden('redirect', $this->session->flashdata('flashRedirect'));
				} else {
					echo form_hidden('redirect', 'dashboard');
				}
				?>
				<input id="signin_submit" value="Sign in" tabindex="6" type="submit" class="rounded_corners_small">
			</fieldset>
		</form>
		<p class="forgot"> <?php echo anchor('login/forgot', 'Forgot Your Password?', 'id="resend_password_link"'); ?></p>
		<p class="forgot-username"> <?php echo anchor('login/forgot', 'Forgot Your Username?', 'id="forgot_username_link" title="If you remember your password, try logging in with your uniqname"'); ?></p>
	</div>
	<?php } ?>
		<div id="<?php if(!$this->session->userdata('userID')) echo "title_name"; else echo "title_name_small";?>">
			<?php if($this->session->userdata('userID')) echo anchor('dashboard', img('static/images/mschedule_small.png')); else echo anchor('home', img('static/images/mschedule_large.png')); ?>
		</div>
		<?php if($this->session->userdata('userID')) { ?>
		<div id="nav_location">
			<?php if(isset($nav_location)) echo "<h1>" . $nav_location . "</h1>"; ?>
		</div>
		<?php } ?>
</div>
<div id="body_pane">
	<div id="vertical_ad">
		<?php if(isset($ad)) include($ad); ?>
	</div>
	<div id="body">
		<div id="content">
			
			<?php 
			$this->load->helper('form');
			echo form_open('login');
			?>
			<fieldset id="loginform">
			<legend>Login</legend>
			<?php
			
			$username_data = array(
				'name'	=> 'username',
				'id'	=> 'username',
				'value'	=> set_value('name')
			);
			
			$password_data = array(
				'name'	=> 'password',
				'id'	=> 'password',
			);
			
			?>
			
			<p><label for="username">Username: </label><?php echo form_input($username_data); ?></p>
			<p><label for="email">Password: </label><?php echo form_password($password_data); ?></p>
			
			<?php echo form_submit('', 'Login'); ?>
			
			<?php echo validation_errors(); ?>
			
			</fieldset>
			
			<?php echo form_close(); ?>
		</div>
	</div>
</div>
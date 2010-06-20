<div id="body_pane">
	<div id="vertical_ad">
		<?php if(isset($ad)) include($ad); ?>
	</div>
	<div id="body">
		<div id="content">
			
			<h2>Send us an email!</h2>
			
			<?php 
			
			$this->load->helper('form');
			
			echo form_open('home/send_email');
			
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
				'id'	=> 'submit_button',
				'value'	=> 'Submit'
			);
			
			?>
			
			<p><label for="name">Full Name: </label><?php echo form_input($name_data); ?></p>
			<p><label for="email">Email: </label><?php echo form_input($email_data); ?></p>
			<p><label for="email">Message: </label><?php echo form_textarea($message_data); ?></p>
			
			<p><?php echo form_submit($submit_data); ?></p>
			
			<?php echo form_close(); ?>
			
			<?php echo validation_errors(); ?>		
		</div>
	</div>
</div>
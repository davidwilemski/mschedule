<div id="navigation_pane">
	<ul id="nav_bar">
		<?php
		
			foreach($nav_data as $nav_item)
			{
				echo '<li class="nav_item">'. anchor($nav_item['url'] , $nav_item['name']) . '</li>';
			}
		?>
	</ul>
	<ul id="login_bar">
		<?php if($this->session->userdata('userID')) {?>		
			<li class="nav_item"><?=anchor('login/logout', 'Logout')?></li>
		<?php } else { ?>
			<li class="nav_item"><?=anchor('login', 'Login')?></li>
		<?php } ?>
	</ul>
</div>
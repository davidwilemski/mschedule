<?php
	/*
		This is the navigation view. It has a navigation pane (across the window) and the bar (smaller).
		This requires a 'nav_data' array to be passed into it, containing 'url' and 'name', best created
		from the nav_link_model.
		This is loaded by include/template.
	*/
?>
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
			<li class="nav_item"><?=anchor('dashboard', 'Dashboard')?></li>
			<li class="nav_item"><?=anchor('classes/import', 'Import Classes')?></li>
			<li class="nav_item"><?=anchor('classes/view', 'View Classes')?></li>
			<li class="nav_item"><?=anchor('login/logout', 'Logout')?></li>
		<?php } else { ?>
			<li class="nav_item"><?=anchor('login', 'Login')?></li>
			<li class="nav_item"><?=anchor('login/register', 'Register')?></li>
		<?php } ?>
	</ul>
</div>
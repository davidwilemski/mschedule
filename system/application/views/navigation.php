<div id="navigation_pane">
	<ul id="nav_bar">
		<?php
			foreach($nav_data as $nav_item)
			{
				echo '<li class="nav_item">'. anchor("Home/".$nav_item['URL_name'] , $nav_item['title']) . '</li>';
			}
		?>
	</ul>
</div>
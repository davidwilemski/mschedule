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
			
			$first = true;
			foreach($nav_data as $nav_item)
			{
				if($first) {
					$first = false;
					echo '<li class="nav_item" style="border:none;">'. anchor($nav_item['url'] , $nav_item['name']) . '</li>';
				} else {
					echo '<li class="nav_item">'. anchor($nav_item['url'] , $nav_item['name']) . '</li>';
				}
				
			}
		?>
	</ul>
</div>
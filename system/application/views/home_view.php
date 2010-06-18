<div id="body_pane">
	<div id="vertical_ad">
		<?php if(isset($ad)) include($ad); ?>
	</div>
	<div id="body">
		<div id="content">
			<?php echo '<h2>' . $page_data['title'] . '</h2>' . $page_data['content']; ?>		
		</div>
	</div>
</div>
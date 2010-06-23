<?php
	/*
		Loads static pages well.
		Requires an array with 'title' and 'content', and displays them.
		It is called by the home controller
	*/
?>
<?php echo '<h2>' . $page_data['title'] . '</h2>' . $page_data['content']; ?>
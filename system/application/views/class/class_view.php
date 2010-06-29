<?php
	/*
		This view needs to be handed $page_data, an object that has the classes in it from getClasses().
		Displays them out on the page.
	*/
?>
<?php
foreach($page_data as $p) {
	echo '<p>' . $p->classID . '</p>';
}
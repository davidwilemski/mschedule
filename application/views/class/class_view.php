<?php
	/*
		This view needs to be handed $page_data, an object that has the classes in it from getClasses().
		Displays them out on the page.
	*/
?>
<?
 if(isset($user_classes) && $user_classes != null) { ?>
	<table border=1>
	<?php
	echo '<tr>';
	echo '<td>Class ID</td>';
	echo '<td>Department</td>';
	echo '<td>Class Number</td>';
	echo '<td>Class Section</td>';
	echo '<td>Class Type</td>';
	echo '</tr>';
	
	$schedule_id_string = '';
	foreach($user_classes as $p) {
		$schedule_id_string .= $p->classid . ';';
		echo '<tr>';
		echo '<td>' . $p->classid . '</td>';
		echo '<td>' . $p->dept . '</td>';
		echo '<td>' . $p->number . '</td>';
		echo '<td>' . $p->section . '</td>';
		echo '<td>' . $p->type . '</td>';
		echo '</tr>';
	}
	?>
	</table>
	<p><?=$schedule_id_string?></p>
	<span id="put_schedule_here" value="<?=$schedule_id_string?>"><p>Loading</p></span>
<? } else echo "<p>You have no schedule, yet.</p>"; ?>
<?php
	/*
		This is the header view. It includes the html5 doctype, the head, the css link, and the title image.
		It requires 'css' to be sent into it.
		This is loaded by include/template.
	*/
?>
<!DOCTYPE html>

<html>
<head>
<title>MSchedule</title>
<?php echo $css; ?>

<meta charset="UTF-8">
</head>

<body>


<div id="title_pane">
		<div id="title_name">
			<?php echo anchor('home', img('static/images/mschedule_text.png')); ?>
		</div>
</div>
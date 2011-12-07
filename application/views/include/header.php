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

<div id="header">
		<?php $this->load->view('login_view'); ?>
		<div id="header_title">
			<?php echo img(array('src' => 'static/images/mschedule_large.png', 'alt' => 'mschedule_logo', 'id' => 'mschedule_img')); ?>
		</div>
		
		<h1>
		<?php
		
		$nav_location_title = '&nbsp;';
		if(isset($nav_location)) {
			$nav_location_title = $nav_location;
		}
		?>
		</h1>
		<?php //if($this->session->userdata('userID')) {?>
			<h1 id="nav_location"><?php echo $nav_location_title; ?></h1>
			<?php $this->load->view('include/' . $navigation, $nav_data); ?>
		<?php //} ?>
</div>
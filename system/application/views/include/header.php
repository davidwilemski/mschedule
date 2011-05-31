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
		<?php $this->load->view('login_view'); ?>
		<div id="<?php if(!$this->session->userdata('userID')) echo "title_name"; else echo "title_name_small";?>">
			<?php if($this->session->userdata('userID')) {
				echo anchor('dashboard', img(array('src' => 'static/images/mschedule_small.png', 'alt' => 'mschedule_logo')));
			} 
			else {
				echo anchor('home', img(array('src' => 'static/images/mschedule_large.png', 'alt' => 'mschedule_logo')));
			}
			?>
		</div>
		<?php if($this->session->userdata('userID')) { ?>
			<div id="nav_location">
				<?php if(isset($nav_location)) echo "<h1>" . $nav_location . "</h1>"; ?>
			</div>
		<?php } ?>
</div>
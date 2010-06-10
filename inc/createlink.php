<?php
//used for menu bar to create links that dissipear when you are on that page
//and there is no post or get data
include_once 'inc/common.php';

function createlink($page, $title, $end = " | \n"){
	
	//echo "<a href=\"/$main_dir/$page\">$title</a>$end";
	if(stristr($_SERVER["PHP_SELF"], "/".$page) and count($_GET) == 0 and count($_POST) == 0){
		echo $title.$end;
	}else{
		echo "<a href=\"$page\">$title</a>$end";
	}
}
?>

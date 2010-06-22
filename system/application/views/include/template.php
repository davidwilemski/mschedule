<?php

/*
	List of data array index values this View expects:
	
	-css 		- HTML for the css to be loaded
	-navigation - the name of the nav bar view
	-nav_data 	- data for creating the navigation bar
	-ad 		- which google ad to use
	-view_name 	- name of the view to load
	-page_data 	- the content/title for the page (if applicable)
	
*/


$this->load->view('include/header', $css);

$this->load->view($navigation, $nav_data);

echo '<div id="body_pane">';

$view_data = array();
if(isset($ad))
{
	echo '<div id="vertical_ad">';
	include($ad);
	echo '</div>';
}
if(isset($view_name))
{
	echo '<div id="body">';
	echo '<div id="content">';
	if(isset($page_data))
		$this->load->view($view_name, $page_data);
	else
		$this->load->view($view_name);
	echo '</div>';
	echo '</div>';
}

echo '</div>';

$this->load->view('include/footer');

?>
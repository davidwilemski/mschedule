<?php

/*
	List of data array index values this View expects:
	
	-css 		- HTML for the css to be loaded
	-navigation - the name of the nav bar view
	-nav_data 	- data for creating the navigation bar
	-ad 		- which google ad to use
	-view_name 	- name of the view to load
	-page_data 	- the content/title for the page
	
*/


$this->load->view('include/header', $css);

$this->load->view($navigation, $nav_data);

$view_data = array();
if(isset($ad))
{
	$view_data['ad'] = $ad;
}
if(isset($page_data))
{
	$view_data['page_data'] = $page_data;
}

$this->load->view($view_name, $view_data);

$this->load->view('include/footer');

?>
<?php
	/*
		This is the main view that should be used.
		Requires the following variables to be passed to it:
			css - HTML for the css to be loaded
			navigation - the name of the nav bar view
			nav_data - array for creating the navigation bar
			ad - which google ad to use
			view_name - name of the view to load
			page_data - the content/title array for the page (if applicable)
		Most imporatantly, this loads:
			include/header, css
			include/ . navigation, nav_data
			echos the div for the body_pane
			if there is an ad, it loads the divs and the ad
			if there is a view_name
				it loads the body and content divs (nested)
				if there is page_data passed in we pass that to the view_name as well
				and we close the body and content divs
			echos the closing div for the body_pane
			include/footer
		This loads the entire template for the site, so most views should be loaded through this.
		Also, this makes it so that you don't have to have any specific requirements in your view
		files, you can just put in the content that you want to be on the page
	*/
?>
<?php
$this->load->view('include/header', $css);

$this->load->view('include/' . $navigation, $nav_data);

echo '<div id="body_pane">';

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
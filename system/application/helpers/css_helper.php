<?php
/*
	CSS Helper Functions written for mschedule.
	Use these functions to load CSS files so that
	there will be no breakage from using relative
	paths in Views.
	
	Default search directory for CSS files will
	be mschedule/static/css. You may place CSS
	files in that directory or create directories
	under that one.

	This function will search the CSS directory for the
	specified $filename (without .css extension) and 
	return a string containing the HTML code to load the
	stylesheet.
	
	Usage:
	
	//in Controller:
	$this->load->helper('css');
	$viewVars['cssFiles'] = includeCSSFile('style');
	$viewVars['cssFiles'] .= includeCSSFile('other_style);
	
	The output from these functions will be HTML that
	the header include will utilize by default assuming
	that you pass the output to the template View.
	
*/
function includeCSSFile($filename)
{
	$CI =& get_instance();
	$baseURL = $CI->config->item('base_url');
	$src = "static/css/${filename}.css";
   
  	$css = '<link rel="stylesheet" type="text/css" href="' .  $baseURL .  $src . '" />' ;

	return $css;
}
?>
<?php
/*
	This helper function will generate the html required to load a javascript file into a view
	
	Argument: $filename the relative path to the JS file not including "static/js/" or the ".js" on the end
*/

function includeJSFile($filename)
{
	$CI =& get_instance();
	$baseURL = $CI->config->item('base_url');
	$src = "static/javascript/${filename}.js";
   
	$js = '<script type="text/JavaScript" src="' . $baseURL . $src . '"></script>';
	
	return $js;
}

?>
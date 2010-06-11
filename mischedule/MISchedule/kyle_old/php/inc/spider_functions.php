<?
$debug_msg = "Debug Info:<br>\n";



//open url as a file and return reference
function openURL($url){

	if(!isset($url)){
		echo "Error: URL to open is not set<br>\n";
		exit;
	}
	
	//debug?
	echo "<strong>Opening URL... </strong> $url<br>\n";
	
	
	$file = fopen ($url, "r");
	if (!$file) {
		echo "<p>Unable to open remote url: $url\n";
   		exit;
	}
	return $file;
}

function sql($sql){
	
	if(!($result = @mysql_query($sql))){
		debug("SQL:".$sql." | Error: ".mysql_error());
	}
	return $result;
}

function debug($msg, $stop=true){
	global $debug_msg;
	$debug_msg .= $msg."<br>\n";
	echo $msg;
	if($stop == true){
		exit;	
	}
}

function starting_msg($string1, $string2){
	echo "<br>\n<strong>Starting... </strong>".$string1."<strong> from </strong>".$string2."<br>\n";
}

function data_msg($array){
	
	foreach($array as $elt){
		echo $elt." | ";	
	}	
	echo "<br>\n";
	
}
?>

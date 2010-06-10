<?php
require_once "inc/common.php";

getdata(array('data'), 'get', 'classids');
getdata(array('term'), 'get');

//var_dump($data);
//exit;
$array = explode(";", $data);

$text = implode("+", $array);

if($term == "f10")
	$term = "fall10";

header("Location: /importclasses.php?submit=importclasses&term=$term&text=$text");

?>

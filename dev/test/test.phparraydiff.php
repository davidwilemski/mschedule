<?
header("Content-type: text/plain");
$array1 = array(
1 => "hey",
5 => "yo",
4 => "arf"
);

$array2 = array(
2 => "hey",
5 => "yo"
);

$array3 = array_diff_assoc($array1, $array2);

var_dump($array3);
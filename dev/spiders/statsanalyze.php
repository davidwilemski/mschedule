<?
header("Content-type: text/plain");
$statFile = "C:/Documents and Settings/Kyle/Desktop/wascrapestats.txt";

$lineArray = file($statFile);
//var_dump($lineArray);


foreach($lineArray as $line){
	list($junk, $junk, $junk, $junk, $size, $month, $day, $time, $file) = preg_split("/[\s]+/", $line);
	list($hour, $minute) = explode(":", $time);
	list($subject, $rest) = explode("_", $file);
	//print($junk.$hour. ":".  $minute. " - ". $subject);
	$size = $size/1024; //convert to kb
	
	$totalSize += $size;
	$sizeByMinute[$minute] += $size;
	$sizeBySubject[$subject] += $size;
	$minuteBySubject[$subject][$minute]++; 
}
ksort($sizeByMinute);
asort($sizeBySubject);
ksort($minuteBySubject);


print "Total Size:\n";
var_dump($totalSize);
print "Size By Minute:\n";
foreach($sizeByMinute as $key => $value){
	print $key.",".$value."\n";
}
print "Size By Subject:\n";
var_dump($sizeBySubject);
print "Minute By Subject:\n";
var_dump($minuteBySubject);


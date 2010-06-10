<?
require_once "inc/spider_functions.php";

?>

<p>
<form method="POST" action="<?=$_SERVER['PHP_SELF']?>">
<input type="password" name="password" size="10" /><br>
<!--<input type="text" name="command" size="20" /><br>-->
<input type="submit" value="submit" />
</form>
</p>

<?
if($_POST['password'] != "arf"){
	exit;
}

$start_url = "http://localhost/old_mschedule/buildings.html";


$file = openURL($start_url);


//go to table tag
while (!feof($file)) {
	$line = fgets ($file, 1024);
	if(strstr($line, "<table>")){
		print "found table";
		break;
	}
}

while (!feof($file)) {
	$line = fgets ($file, 1024);
	$line = trim(strip_tags($line));
	if($line == '') continue;
	if(preg_match("/[a-z]/", $line)){
		print $line."<br>\n";
	}else{
		print $line." # ";
	}
}



fclose($file);


?>
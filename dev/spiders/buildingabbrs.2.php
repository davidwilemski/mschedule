<?
require_once "inc/spider_functions.php";
require_once "../inc/common.php";

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

$start_url = "http://localhost/mschedule/spiders/buildingabbrs.txt";

$file = openURL($start_url);
while (!feof ($file)) {
	$line = fgets ($file, 1024);
	$array = explode("#", $line);
	$abbr = trim($array[0]);
	$name = trim($array[1]);	
	print "inserting $abbr - $name";
	$MSDB->sql("INSERT INTO `mschedule_buildings` (abbr, name) VALUES ('$abbr', '$name')");
}

?>
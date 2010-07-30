<?
include 'inc/db.php';
include 'inc/spider_functions.php';

dbConnect();

$result = sql('SELECT * '
    		. ' FROM `courses` '
    		. ' ORDER BY `dept`, `number`'); 

while($myrow = mysql_fetch_row($result)){
	//data_msg($myrow);
	//echo '<br>';
}

?>
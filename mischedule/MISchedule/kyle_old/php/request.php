<?
include 'inc/db.php';

$command = $_GET['command'];

if($command == ''){
	exit;
}

if($command == 'divisions'){
	dbConnect();

	 $sql = 'SELECT `dept` , `name` '
        . ' FROM `divisions` '
        . ' ORDER BY `dept`';
	$result = mysql_query($sql);
	//debug
	echo mysql_num_rows($result), "\n";
	while($myrow = mysql_fetch_row($result)){
		echo $myrow[0], "\n", $myrow[1], "\n"; 
	}
}
if($command == 'courses'){
?>3
101
The Easy Class
102
A Little Bit Harder
103
Oh man, I just can't take it now<?	
}



if($command == 'sections'){
	/*
?>1
10454
3.00
999999999
999999999
LEC
001
Yo
999999999
1

HALE AUD BUS
2
48
78
Central
172
174
Central<?
*/
?>1
11175
4
1
0
Lecture
200
Khan,Pauline BaryMeadows,Lorelle Annise
2
1
M,W, 12:30PM - 2:30PM
1109 FXB
2
25
29
North
121
125
North<?
}

?>
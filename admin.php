<?
include_once 'inc/common.php';
include_once 'inc/accesscontrol.php';
include_once 'inc/db.php';

if($_SESSION['uniqname'] != 'mulka'){
	exit;
}

$max_records = 20;
if(is_numeric($_GET['limit'])){
	$max_records = $_GET['limit'];
}


function showsql($sql){
	global $max_records;
	$sql_array = explode(';', $sql);
	foreach($sql_array as $key => $sql){
		if($key == (count($sql_array) - 1) and $_GET['show'] != 'all' and !stristr($sql, ' limit ')){
			$sql .= " LIMIT $max_records";
		}
		echo "<p>$sql</p>";
		$result = sql($sql);
	}
	echo "<p><table border=1>";
	while($myrow = mysql_fetch_row($result)){
		echo "<tr>";
		//debug($i);
		foreach($myrow as $value){
			echo "<td><pre>$value</pre></td>";
		}
		echo "</tr>";
	}
	echo "</table></p>";
}

$sql_array = array(
	'RecentUsers' => 'SELECT DISTINCT `last_login`, `uniqname` FROM `users` ORDER BY `last_login` DESC',
	'RecentActivity' => /*"delete FROM `access_log` WHERE "." ip = '141.213.40.114' or ip = '68.41.10.20' or "." ip = '{$_SERVER['REMOTE_ADDR']}';". */"SELECT * FROM `access_log` WHERE ip != '{$_SERVER['REMOTE_ADDR']}' ORDER BY `time` DESC ",
	'NewUsers' => 'SELECT * FROM `users` ORDER BY `time` DESC',
	'ClassesPerUser' => "SELECT 'total' AS num, count(DISTINCT uniqname) FROM `$user_class` UNION SELECT num, count(uniqname) FROM (SELECT count( classid ) AS `num`, $users.uniqname FROM  `$users` LEFT  JOIN  `$user_class`  ON $users.uniqname = $user_class.uniqname  GROUP BY users.uniqname) as classes GROUP BY num  HAVING `num` > 0 ORDER  BY  `num` DESC",
	'VIPsPerUser' => "SELECT num, count( uniqname ) FROM ( SELECT count( `friend_uniqname` ) AS `num` , uniqname FROM `friends` GROUP BY `uniqname`) AS `friends` GROUP BY num HAVING `num` > 0 ORDER BY `num` DESC",
	'LastLogin' => "SELECT DATE_FORMAT( last_login, '%Y-%m' ) AS date, COUNT( uniqname ) FROM `users` GROUP BY date ORDER BY date DESC",
	'RegistrationsPerDay' => "SELECT DATE( time ) AS date, COUNT( uniqname ) FROM `users` GROUP BY date ORDER BY date DESC",
	'RegistrationsPerMonth' => "SELECT DATE_FORMAT( time, '%Y-%m' ) AS
	MONTH , COUNT( uniqname )
	FROM `users`
	GROUP BY MONTH ORDER BY MONTH DESC",
	//'UsersWithClasses' => "SELECT DISTINCT uniqname FROM `uniqname_class_".$term."` ORDER BY uniqname",
);

$table_records_to_check = array('users', 'access_log', 'error_log', 'phperror_log', 'preferences', 'unconfirmed', 'uniqname_class_fall09' /*, 'viewed_printed'*/);

echo "<a href=\"myschedule.php\">My Schedule</a>";
echo "<p><table>";
foreach($table_records_to_check as $value){
	$num_records = mysql_result(sql("select count(*) from $value"), 0, 0);
	echo "<tr><td><a href=\"{$_SERVER['PHP_SELF']}?table=$value\">".$value."</td><td>".$num_records."</td>";
	if($num_records > $max_records){
		echo "<td><a href=\"{$_SERVER['PHP_SELF']}?table=$value&show=all\">all</a></td>";
	}
	echo "</tr>\n";
}
echo "</table></p>";
foreach($sql_array as $key => $value){
	echo "<a href=\"{$_SERVER['PHP_SELF']}?get=$key\">$key</a> (<a href=\"{$_SERVER['PHP_SELF']}?get=$key&show=all\">all</a>) | ";
}

if(array_key_exists($_GET['get'], $sql_array)){
	showsql($sql_array[$_GET['get']]);
}else if(in_array($_GET['table'], $table_records_to_check)){
	showsql("SELECT * FROM `{$_GET['table']}`");
}

?>

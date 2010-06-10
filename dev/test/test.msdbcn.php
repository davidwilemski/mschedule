<?
require '../classes/class.msdbcn.php';
print "<pre>";
$db = new MSDbCn();
$result = $db->sql("select * from mschedule_users");
if (!$result) {
   die('Invalid query: ' . mysql_error());
}
print mysql_num_rows($result);
var_dump(mysql_fetch_assoc($result));


var_dump($db);
print "</pre>";

?>
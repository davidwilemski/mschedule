<?
require_once "../classes/class.msuser.php";

$user = new MSUser('mulka');

print $user->get_uniqname();
print $user->get_fullName();
var_dump($user->list_VIPs());
print mysql_error();
?>
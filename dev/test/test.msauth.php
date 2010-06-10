<?
require_once '../inc/common.php';
require_once '../classes/class.msauth.php';
print "<pre>";

var_dump($currentUser);

$auth = new MSAuth();
//sleep(2);
//var_dump($auth);
if($auth->login('mulka', 'YoWaz^')){
	print "auth passed";
}else{
	print "auth failed";
}
$currentUser->get_fullName();
$currentUser->get_VIPList();
$currentUser->get_schedule();
var_dump($currentUser);

?>
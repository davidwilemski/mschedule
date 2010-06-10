<?
require_once '../inc/common.php';
require_once '../classes/class.msauth.php';
print "<pre>";

$auth = new MSAuth();
if($auth->login('testuser', 'yarr')){
	print "auth passed";
}else{
	print "auth failed";
}
print "before currentUser calls...\n";
$currentUser->get_fullName();
$currentUser->get_VIPList();
$currentUser->get_schedule();
var_dump($currentUser);

$auth->_delete('testuser');
?>
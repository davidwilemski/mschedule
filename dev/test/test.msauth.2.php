<?
require_once '../inc/common.php';
require_once '../classes/class.msauth.php';
print "<pre>";

$auth = new MSAuth();

var_dump($auth->create('testuser','umich.edu', 'yarr'));

if($auth->login('testuser', 'yarr')){
	print "auth passed\n";
}else{
	print "auth failed\n";
}
print "before currentUser calls...\n";
$currentUser->get_fullName();
$currentUser->get_VIPList();
$currentUser->get_schedule();
var_dump($currentUser);

//$auth->_delete('testuser');
?>

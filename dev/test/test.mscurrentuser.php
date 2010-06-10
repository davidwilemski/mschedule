<?
require_once '../inc/common.php';

function printInfo()
{
	global $currentUser;
	var_dump($currentUser->is_loggedIn());
	//var_dump($currentUser);
	//var_dump($_SESSION);
}

function b2s($bool)
{
	if($bool){
		return "true";
	}else{
		return "false";
	}
}

print "<pre>";
print "should be false, true, true, false\n";
printInfo();
var_dump($MSAUTH->login('testuser', 'yarr'));
printInfo();
$MSAUTH->logout();
printInfo();

print "done...";
?>
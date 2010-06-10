<?
include_once 'inc/accesscontrol.php';
include_once 'inc/common.php';
include_once 'inc/db.php';


getdata(array('fullname'));
getdata(array('pass1', 'pass2'), 'post','password');

if($pass1 == $pass2){
	$sql = 'UPDATE `'.$users.'` SET `password` = OLD_PASSWORD(\''.$pass1.'\') WHERE `uniqname` = \''.$auth_uniqname.'\'';
}else{
	error("Passwords do not match, please try again.");
}

clearpostdata();

$body = "Your user information has been updated.";

showHTMLPage('Updated', $body);


?>

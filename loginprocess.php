<?
include_once 'inc/common.php';
include_once 'inc/accesscontrol.php';

if(isset($_SESSION['uniqname'])){
	showHTMLHead("Logged In", $tag);
	echo "Choose one of the links above. If this is your first time, we recommend starting by <a href=\"importclasses.php\">importing your classes</a>.";
	showHTMLFoot();
	exit;
}
?>

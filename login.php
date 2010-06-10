<?
include_once 'inc/common.php';


if(isset($_SESSION['uniqname'])){
	//$tag = "<META http-equiv=\"refresh\" content=\"3; URL=$start_page\">";
	showHTMLHead("Logged In");
	echo "You have been logged in. Please choose one of the links above.";
	showHTMLFoot();
	exit;
}

showHTMLHead('Login');
?>
<p>Type your uniqname and password for this site below. <br>
If you haven't used this site before, <a href="<?=$new_user_page?>">register</a>.</p>
<?
$redirect = $start_page;
include 'components/loginbox.php';
showHTMLfoot();
?>
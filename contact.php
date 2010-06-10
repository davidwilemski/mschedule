<?
include_once 'inc/common.php';

if($_GET['action'] == 'sent'){
	$body = "Your message has been sent. Thank you!";
	showHTMLPage("Contact", $body);
	exit;
}

showHTMLHead("Contact");

?>
<p>Please fill out the form below to send questions, comments, suggestions, errors, or anything else that is on your mind.</p>

   <form method="post"  action="<?=$email_page?>">

	Your name: <input type="text" name="name" value="<?=$_SESSION['fullname']?>" size="20"><br>
	Your email: <input type="text" name="email" value="<?= $_SESSION['uniqname'] ? $_SESSION['uniqname']."@umich.edu" : "" ?>"size="20"> (if you want a reply)<br>
	<br>
	Message:<br>
    <textarea name="message" cols="40" rows="7"></textarea><br>
    <input type="submit" value="Send">
    </form>
<?
showHTMLFoot();
?>

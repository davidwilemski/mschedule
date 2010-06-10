<?
include_once 'inc/common.php';

$body = "
<p>
<h4>I've forgotten my password. What do I do?</h4>
Send an email to <a href=\"mailto:$myaddress\">$myaddress</a> and let us know what your uniqname is. We'll send you an email to your umich.edu address with instructions to reset your password.
</p>
<p>
<h4>My class schedule isn't showing up. What gives?</h4>
<ul>
<li>First, make sure that you are <a href=\"register.php\">registered</a>. 
<li>Next, make sure you have <a href=\"importclasses.php\">imported your classes</a>. Mschedule.com is not linked with Wolverine Access.
<li>Then, make sure that you have your Privacy <a href=\"prefs.php\">Preference</a> set correctly and that the user trying to view it is <a href=\"login.php\">logged in</a>.
<li>If you have Priavcy set to Private, make sure that the user is on your <a href=\"myfriends.php\">VIP list</a>.
<li>If you are still haveing trouble, please <a href=\"contact.php\">contact us</a>.
</ul>
</p>
<p>
<h4>My question isn't listed.... Ahh!</h4>
No problem. Use the <a href=\"contact.php\">contact</a> function of the website or email <a href=\"mailto:$myaddress\">$myaddress</a>
</p>
";
showHTMLPage("Questions and Answers", $body);
?>

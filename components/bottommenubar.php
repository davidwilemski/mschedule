<?php

//displays the lower menu bar, which as of August 04 is displayed directly
//under the topmenu bar, and doesn't change when you login and log out

include_once 'inc/common.php';
include_once 'inc/createlink.php';

/*
if(isset($_SESSION['uniqname'])){
	createlink('importclasses.php', 'import classes');
	createlink('myschedule.php', 'edit');
	createlink('view.php', 'view');
	createlink('classinfo.php', 'class info');
	createlink('prefs.php', 'preferences');
	createlink('changepass.php', 'change password');
	createlink('logout.php', 'logout');
}else{
	createlink('register.php', 'register');
	createlink('login.php', 'login');
	createlink('view.php', 'view schedule');
}
*/
createlink('spreadtheword.php', 'spread the word');
createlink('faq.php', 'Q&A');
createlink('howto.php', 'how to');
createlink('contact.php', 'contact');
//echo "<a href=\"http://www.livejournal.com/users/schedulesharing/\" target=\"_new\">blog</a> | \n";
createlink('about.php', 'about', '');

?>




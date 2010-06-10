<?php
//this file displays the menu bar that, as of August 04 is displayed on the top

include_once 'inc/common.php';
include_once 'inc/createlink.php';

if(isset($_SESSION['uniqname'])){
	createlink('importclasses.php', 'import classes');
	createlink('myschedule.php', 'edit');
	createlink('view.php', 'view');
	createlink('multiview.php', 'group');
	createlink('myfriends.php', 'VIPs');
	createlink('classinfo.php', 'info');
	createlink('prefs.php', 'prefs');
	createlink('changepass.php', 'change password');
	createlink('logout.php', 'logout', '');
}else{
	createlink('login.php', 'login');
	createlink('view.php', 'view schedule');
	createlink('register.php', 'register', '');

}

?>




<?php
//displays if a user is signed in and which one
if(isset($_SESSION['fullname'])){
	echo "<p><strong>Welcome, {$_SESSION['fullname']}!</strong></p>";
}else{
	echo "<p><strong>Welcome, Guest!</strong></p>";
}
?>

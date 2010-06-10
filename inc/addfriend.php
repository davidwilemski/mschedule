<?php 
//included in friendoperations.php only I think
include_once 'inc/common.php';
include_once 'inc/db.php';

debug("Tried to add VIP: ".$uniqname);


$result = sql("SELECT * FROM $friends_table"
	. " WHERE `uniqname` = '$auth_uniqname' AND"
	. " friend_uniqname = '$uniqname'"
	. " LIMIT 1");

if(mysql_num_rows($result)){
	status("<b>$uniqname</b> is already on your list.");
}else{
	if($uniqname != ''){
		sql("insert ignore into $friends_table set uniqname='$auth_uniqname', friend_uniqname = '$uniqname'");
		status("Added VIP: <b>$uniqname</b>");
	}else{
		error("The uniqname entered was blank or invalid.");
	}
}
?>
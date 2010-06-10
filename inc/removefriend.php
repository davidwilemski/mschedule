<?php 
//included in only friendoperations.php as of August 04
include_once 'inc/common.php';
include_once 'inc/db.php';

sql("DELETE FROM $friends_table "
		. "WHERE `uniqname` = '$auth_uniqname' AND "
        . "friend_uniqname = '$uniqname' LIMIT 1");

?>

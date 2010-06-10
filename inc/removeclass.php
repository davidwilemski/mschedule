<?php
//included only in classoperations.php as of August 04 
include_once 'inc/common.php';
include_once 'inc/db.php';

sql("DELETE FROM $user_class "
		. "WHERE `uniqname` = '$auth_uniqname' AND "
		. "`classid` = '$classid' AND "
        . "`dept` = '$dept' AND "
        . "`number` = '$number' AND "
        . "`section` = '$section' ");


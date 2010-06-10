<?php
//include this file if you need to have classes added or removed from the page via post
//as of August 04, included in myschedule.php
include_once 'inc/common.php';

getdata(array('submit', 'classid', 'dept', 'number', 'section'));

if($submit == 'add'){
	include 'inc/addclass.php';
}else if($submit == 'remove'){
	include 'inc/removeclass.php';
}

clearpostdata();

?>
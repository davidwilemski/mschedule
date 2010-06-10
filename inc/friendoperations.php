<?php
//include this if you wish to be able to add or remove friends from the page (VIPs)

include_once 'inc/common.php';

getdata(array('submit', 'uniqname'), 'get');

switch($submit){
case 'add':
case 'addtovips':
	include 'inc/addfriend.php';
	break;
case 'remove':
	include 'inc/removefriend.php';
	break;
}
?>
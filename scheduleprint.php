<?
require_once 'inc/common.php';
include_once 'inc/showschedule.php';

getdata(array('uniqname'), 'get');

if($uniqname == "") $uniqname = $auth_uniqname;

?>
<?php
echo showschedule($uniqname);
?>

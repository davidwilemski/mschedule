<?php
session_start();
//print "new";
$_SESSION['uniqname'] = "mulka";

var_dump($_SESSION);

session_unset();
session_destroy();

var_dump($_SESSION);
?>
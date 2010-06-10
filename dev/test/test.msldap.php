<?
require_once '../classes/class.msldap.php';

$a = new MSLDAP(/*'umich.edu'*/);

print $a->get_fullname('mjpizz');

?>
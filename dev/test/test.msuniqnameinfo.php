<?
require_once '../classes/class.msuniqnameinfo.php';

print "<pre>";
$a = new MSUniqnameInfo();
var_dump($a->get_all(''));
//print $a->get_fullName('yarr');
print "</pre>";
?>
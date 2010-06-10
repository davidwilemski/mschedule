<?
require '../classes/class.msevent.php';

$e = new MSEvent(5, 1600, 210, 'Movie', "Just watching a movie. Don't know which one yet", "My Place");

print "<pre>";
var_dump($e);

print $e->get_day();
print $e->get_startTime();
print '-';
print $e->get_endTime();
print ":";
print $e->get_length();

print "</pre>";

?>
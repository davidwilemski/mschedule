<?

define(_DEBUG, false);

/*
format:
time - 1:00PM
day - 0 for sunday, 1 for monday, and so on

returns number of half hours past sunday night at midnight rounded up to next half hour
*/
function convertToHalfHoursPastMonday($time, $day)
{
	list($hour, $rest) = explode(":", $time);
	if(stristr($rest, "pm") and $hour != 12){
		$hour = $hour + 12;
	}
	$minute = substr($rest, 0, 2);
	if(_DEBUG){
		print $hour;
		print $minute."<br>";
		print (($hour * 2) + ceil($minute/30))."<br>";
		print ($day - 1)."<br>";
	}
	return (($hour * 2) + ceil($minute/30)) + (($day - 1) * 48);
}